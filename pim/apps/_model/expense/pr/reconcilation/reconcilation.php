<?php
namespace model\expense\pr\reconcilation;

use session;

class Reconcilation extends \Origami
{
	protected $table = 'pr_reconcilation';
	protected $primary = 'prReconcilationID';

	public function getPr()
	{
		return $this->getOne('expense/pr/pr', 'prID');
	}

	public function getCategories()
	{
		return $this->getMany('expense/pr/reconcilation/category', 'prReconcilationID');
	}

	public function getFiles()
	{
		return $this->getMany('expense/pr/reconcilation/file', 'prReconcilationID');
	}

	public function getItems()
	{
		return $this->getMany('expense/pr/reconcilation/item', 'prReconcilationID');
	}

	public function getReconciledCategories()
	{
		$id = $this->prReconcilationID;

		return $this->withQuery(function($query) use($id)
		{
			$query->where('prReconcilationID IN (SELECT prReconcilationID FROM pr_reconcilation_file WHERE prReconcilationCategoryID = pr_reconcilation_category.prReconcilationCategoryID AND prReconcilationID = ?)', array($id));
			$query->where('prReconcilationID IN (SELECT prReconcilationID FROM pr_reconcilation_item WHERE prReconcilationID = ? AND prReconcilationItemStatus = 1)', array($id));
		})->getCategories();
	}

	public function isEditable()
	{
		return $this->isManagerPending();
		// return !$this->isSubmitted() && user()->isManager();
	}

	public function getFileTotalAmount()
	{
		$amount = 0;

		foreach($this->getFiles() as $file)
			$amount += $file->prReconcilationFileTotal;

		return $amount;
	}

	public function isDeletableBy(\model\user\user $user)
	{
		if($this->isClosed())
			return false;

		return $user->isManager() || $user->isRoot();
	}

	public function getStatusLabel()
	{
		if($this->isClosed())
			return 'Closed';

		$levels = array(
			2 => 'Site Manager',
			3 => 'Cluster Lead',
			4 => 'Operation Manager',
			5 => 'Financial Controller'
			);

		if($this->isPending())
		{
			if($this->isManagerPending())
				return 'Waiting for submission';

			$level = $this->prReconcilationPendingLevel;

			return 'Pending at '.$levels[$level];
		}
		else if($this->isRejected())
		{
			$level = $this->prReconcilationRejectionLevel;

			return 'Rejected by '.$levels[$level];
		}

		return 'Unknown';
	}

	public function approve(\model\user\user $user)
	{
		if(!$this->isPendingFor($user))
			throw new \Exception("Unable to approve this RL on this level of user.", 1);

		$this->getLevelApproval($user->userLevel)->approve($user);
		$this->prReconcilationApprovalLevel = $user->userLevel;
		$this->save();
		$this->moveApprovalUp();
	}

	public function reject(\model\user\user $user, $reason = null)
	{
		if(!$this->isPendingFor($user))
			throw new \Exception("Unable to reject this RL on this level of user", 1);

		$this->getLevelApproval($user->userLevel)->reject($user);
		$this->prReconcilationStatus = 2;
		$this->prReconcilationPendingLevel = 2; // site manager
		$this->prReconcilationRejectionLevel = $user->userLevel;
		$this->save();

		// rejection reason.
		$rejection = orm('expense/pr/reconcilation/rejection')->create();
		$rejection->prReconcilationID = $this->prReconcilationID;
		$rejection->prReconcilationRejectionReason = $reason;
		$rejection->prReconcilationRejectionCreatedDate = now();
		$rejection->prReconcilationRejectionCreatedUser = session::get('userID');
		$rejection->save();
	}

	public function close()
	{
		$this->prReconcilationStatus = 1;

		$this->save();
	}

	public function isClosed()
	{
		return $this->prReconcilationStatus == 1;
	}

	public function isPending()
	{
		return $this->prReconcilationStatus == 0;
	}

	public function isRejected()
	{
		return $this->prReconcilationStatus == 2;
	}

	public function isManagerPending()
	{
		return $this->prReconcilationPendingLevel == 2;
	}

	public function isPendingFor(\model\user\user $user)
	{
		$level = $user->userLevel;

		return $this->prReconcilationPendingLevel == $level;
	}

	public function initiateApprovals()
	{
		$this->prReconcilationStatus = 0;

		$this->save();

		$levels = array(3, 4, 5); // cl, om, financial

		foreach($levels as $level)
		{
			$approval = $this->getLevelApproval($level);

			if($approval)
			{
				$approval->prReconcilationApprovalStatus = 0;
				$approval->userID = 0;
				$approval->prReconcilationApprovalUpdatedDate = now();
				$approval->save();
			}
			else
			{
				$approval = orm('expense/pr/reconcilation/approval')->create();
				$approval->prReconcilationID = $this->prReconcilationID;
				$approval->userLevel = $level;
				$approval->userID = 0;
				$approval->prReconcilationApprovalStatus = 0;
				$approval->prReconcilationApprovalCreatedDate = now();
				$approval->prReconcilationApprovalUpdatedDate = now();
				$approval->save();
			}
		}
	}

	/**
	 * ORM : Get summary of files grouped by category.
	 * @return array
	 */
	public function getSummary()
	{
		$summaries = array();

		foreach($this->getFiles() as $file)
		{

			if(!isset($summaries[$file->expenseCategoryID]))
			{
				$category = $file->getCategory();
				
				$summaries[$file->expenseCategoryID] = array(
					'category' => $category,
					'total' => 0,
					'files' => array()
					);
			}

			$summaries[$file->expenseCategoryID]['files'][] = $file;
			$summaries[$file->expenseCategoryID]['total'] += $file->prReconcilationFileTotal;
		}

		return $summaries;
	}

	public function getLevelApproval($level)
	{
		$levels = array(
			'cl' => 3,
			'om' => 4,
			'fc' => 5
			);

		if(!is_numeric($level))
			$level = $levels[$level];

		return orm('expense/pr/reconcilation/approval')
			->where('prReconcilationID', $this->prReconcilationID)
			->where('userLevel', $level)
			->execute()
			->getFirst();
	}

	public function isUpdateableBy(\model\user\user $user)
	{
		return $this->isManagerPending() && $user->isManager();
	}

	public function getCreatingUser()
	{
		$userID = $this->prReconcilationCreatedUser;

		return orm('user/user')->find($userID);
	}

	public function getSubmittedUser()
	{
		$userID = $this->prReconcilationSubmittedUser;

		return orm('user/user')->find($userID);
	}

	public function managerSubmit(\model\user\user $user)
	{
		if($user->userLevel != 2)
			throw new \Exception("Only manager can submit this RL.");

		$this->prReconcilationApprovalLevel = 2;

		if(!$this->isSubmitted())
		{
			$this->prReconcilationSubmittedUser = \session::get('userID');
			$this->prReconcilationSubmittedDate = now();
			$this->prReconcilationSubmitted = 1;
		}

		$this->save();

		$this->initiateApprovals();

		$this->moveApprovalUp();
	}

	public function moveApprovalUp()
	{
		if($this->prReconcilationApprovalLevel == 2)
			$this->moveApprovalToClusterLead();
		else if($this->prReconcilationApprovalLevel == 3)
			$this->moveApprovalToOperationManager();
		else if($this->prReconcilationApprovalLevel == 4)
			$this->moveApprovalToFinancialController();
		else if($this->prReconcilationApprovalLevel == 5 && !$this->isClosed())
			$this->close();
	}

	public function moveApprovalToClusterLead()
	{
		$this->prReconcilationPendingLevel = 3;
		$this->getLevelApproval('cl')->makePending();
		$this->save();
	}

	public function moveApprovalToOperationManager()
	{
		$this->prReconcilationPendingLevel = 4;
		$this->getLevelApproval('om')->makePending();
		$this->save();
	}

	public function moveApprovalToFinancialController()
	{
		$this->prReconcilationPendingLevel = 5;
		$this->getLevelApproval('fc')->makePending();
		$this->save();
	}

	public function delete()
	{
		$this->prReconcilationStatus = 3;
		$this->save();
	}

	public function save()
	{
		$this->prReconcilationUpdatedDate = now();
		$this->prReconcilationUpdatedUser = \session::get('userID');
		parent::save();
	}

	public function isSubmitted()
	{
		return $this->prReconcilationSubmitted == 1;
	}

	public function initiateItems()
	{
		$pr = $this->getPr();

		$prItems = $pr->getItems();

		$prItemCategories = array();

		foreach($prItems as $prItem)
			$prItemCategories[$prItem->expenseCategoryID][] = $prItem;

		foreach($prItemCategories as $categoryID => $items)
		{
			// create pr_reconcilation_category
			$category = orm('expense/pr/reconcilation/category')->create();

			$category->prReconcilationID = $this->prReconcilationID;
			$category->expenseCategoryID = $categoryID;
			$category->save();

			foreach($items as $item)
			{
				// create pr_reconcilation_item
				$rlItem = orm('expense/pr/reconcilation/item')->create();
				$rlItem->prReconcilationID = $this->prReconcilationID;
				$rlItem->prItemID = $item->prItemID;
				$rlItem->expenseItemID = $item->expenseItemID;
				$rlItem->prReconcilationCategoryID = $category->prReconcilationCategoryID;
				$rlItem->prReconcilationItemName = $item->expenseItemName.($item->prItemDescription ? ' ('.$item->prItemDescription.')' : '');
				$rlItem->prReconcilationItemAmount = $item->prItemTotal;
				$rlItem->prReconcilationItemGst = 0;
				$rlItem->prReconcilationItemTotal = $item->prItemTotal;
				$rlItem->prReconcilationItemStatus = 2; // by default is not reconciled. after file uploaded will set to reconciled.
				$rlItem->prReconcilationItemCreatedDate = now();
				$rlItem->prReconcilationItemCreatedUser = \session::get('userID');
				$rlItem->save();
			}
		}
	}

	public function getTotal()
	{
		$total = 0;

		foreach($this->getItems() as $item)
		{
			if(!$item->isReconciled())
				continue;

			$total += $item->prReconcilationItemTotal;
		}

		return $total;
	}
}


?>