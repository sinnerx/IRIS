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

	public function getFiles()
	{
		return $this->getMany('expense/pr/reconcilation/file', 'prReconcilationID');
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
}


?>