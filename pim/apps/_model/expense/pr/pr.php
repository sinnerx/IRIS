<?php
namespace model\expense\pr;
use model, session;

class Pr extends \Origami
{
	protected $table = 'pr';
	protected $primary = 'prID';

	const TYPE_COLLECTION_MONEY = 2;
	const TYPE_CASH_ADVANCE = 2;

	/**
	 * ORM : get status label
	 */
	public function getStatusLabel()
	{
		if($this->isClosed())
			return 'Closed';

		$status = $this->prStatus;

		$levels = array(
				2 => 'Site Manager',
				3 => 'Cluster Lead',
				4 => 'Operation Manager',
				5 => 'Financial Controller'
				);

		if($this->isWaitingForPRNumber())
		{
			return 'Waiting for PR Number';
		}
		else if($this->isPending())
		{
			$level = $this->prStatusPendingLevel;

			return 'Pending at '.$levels[$level];
		}
		else if($this->isRejected())
		{
			$level = $this->prStatusRejectionLevel;

			return 'Rejected by '.$levels[$level];
		}

		return 'Unknown';
	}

	public function getItems()
	{
		return $this->getMany('expense/pr/item', 'prID');
	}

	public function getApprovals()
	{
		return $this->getMany('expense/pr/approval', 'prID');
	}

	/**
	 * ORM : If pr is rejected
	 */
	public function isRejected()
	{
		return $this->prStatus == 2;
	}

	/**
	 * ORM : get type name
	 */
	public function getTypeLabel()
	{
		$types = array(
			1 => 'Collection Money',
			2 => 'Cash Advance'
			);

		return $types[$this->prType];
	}

	public function isManagerPending()
	{
		return $this->prStatusPendingLevel == 2;
	}

	public function isClusterLeadPending()
	{
		return $this->prStatusPendingLevel == 3;
	}

	public function isOperationManagerPending()
	{
		return $this->prStatusPendingLevel == 4 && $this->prStatusApprovalLevel != 4;
	}

	/*public function isFinancialControllerPending()
	{
		return $this->prStatusPendingLevel == 5;
	}*/

	/**
	 * ORM : if pr is pending
	 */
	public function isPending()
	{
		return $this->prStatus == 0;
	}

	/**
	 * ORM : if pr is closed
	 */
	public function isClosed()
	{
		return $this->prStatus == 1 && $this->prNumber != '';
	}

	/**
	 * ORM : close pr with the given pr number
	 */
	public function close(\model\user\user $user, $prNumber)
	{
		if(!$this->isWaitingForPRNumber())
			return;

		if(!$user->isRoot())
			return;

		$this->prStatusPendingLevel = 0;
		$this->prNumber = $prNumber;
		$this->prStatus = 1;
		$this->prUpdatedDate = now();
		$this->prUpdatedUser = \session::get('userID');
		$this->save();

		// and open a new RL
		$rl = orm('expense/pr/reconcilation/reconcilation')->create();

		$rl->prID = $this->prID;
		$rl->prReconcilationStatus = 0;
		$rl->prReconcilationPendingLevel = 2; // manager
		$rl->prReconcilationApprovalLevel = 0;
		$rl->prReconcilationRejectionLevel = 0;
		$rl->prReconcilationSubmitted = 0;
		$rl->prReconcilationCreatedDate = now();
		$rl->prReconcilationUpdatedDate = now();
		$rl->prReconcilationCreatedUser = session::get('userID');
		// $rl->prReconcilationCreatingUser = session::get('userID');
		$rl->save();

		// create approval level for rl.
		$rl->initiateApprovals();
	}

	/**
	 * ORM : Reject pr by the given user\user
	 * Should reset all approval to 0
	 * And set pending back to manager.
	 */
	public function reject(\model\user\user $user, $reason = null)
	{
		$approval = $this->getLevelApproval($user->userLevel);
		$approval->reject($user);

		$this->prUpdatedDate = now();
		$this->prUpdatedUser = \session::get('userID');
		$this->prStatusPendingLevel = 2;
		$this->prStatusRejectionLevel = $user->userLevel;
		$this->prStatus = 2;
		$this->save();

		$rejection = orm('expense/pr/rejection')->create();
		$rejection->prID = $this->prID;
		$rejection->userLevel = $user->userLevel;
		$rejection->prRejectionReason = $reason;
		$rejection->prRejectionCreatedDate = now();
		$rejection->prRejectionCreatedUser = \session::get('userID');
		$rejection->save();

		// reject
		// $approval->prApprovalStatus = 2;
		// $approval->prApprovalUpdatedDate = now();
		// $approval->save();
	}

	public function approve(\model\user\user $user)
	{
		$this->prStatusApprovalLevel = $user->userLevel;
		$this->prUpdatedDate = now();
		$this->prUpdatedUser = \session::get('userID');
		$approval = $this->getLevelApproval($user->userLevel);
		$approval->approve($user);
		$this->moveApprovalUp();

		$this->save();
	}

	/**
	 * ORM : Get last rejection remark
	 */
	public function getLastRejectionRemark()
	{
		$remark = model::orm('expense/pr/remark')
			->where('prRemarkType', 0)
			->where('prID', $this->prID)
			->order_by('prRemarkCreatedDate DESC')
			->getFirst();

		return $remark;
	}

	/**
	 * ORM : pr is type cash advance
	 */
	public function isCashAdvance()
	{
		return $this->prType == 2;
	}

	public function getCashAdvance()
	{
		return $this->getOne('expense/pr/cash_advance', 'prID');
	}

	public function hasSubmittedCashAdvance()
	{
		$cashAdvance = $this->getCashAdvance();

		if($cashAdvance)
			return true;
		else
			return false;
	}

	public function isDeletableBy(\model\user\user $user)
	{
		if($this->isClosed())
			return false;
		
		return $user->isManager() || $user->isRoot();
	}

	public function cashAdvanceIsDownloadableBy(\model\user\user $user)
	{
		if(!$this->isClosed())
			return false;

		return $user->isRoot() || $user->isFinancialController();
	}

	public function getRequestingUser()
	{
		return orm('user/user')
			->where('user.userID', $this->userID)
			->join('user_profile', 'user.userID = user_profile.userID')
			->find();
	}

	public function getCluster()
	{
		return orm('site/cluster')->where('clusterID IN (SELECT clusterID FROM cluster_site WHERE cluster_site.siteID = ?)', array($this->siteID))->find();
	}

	public function getSite()
	{
		return $this->getOne('site/site', 'siteID');
	}

	public function getLevelApproval($level)
	{
		$levels = array(
			'cl' => 3,
			'om' => 4
			// 'fc' => 5
			);

		if(!is_numeric($level))
			$level = $levels[$level];

		return orm('expense/pr/approval')
			->where('prID', $this->prID)
			->where('userLevel', $level)
			->execute()
			->getFirst();
	}

	public function moveApprovalUp()
	{
		// current pending level
		if($this->prStatusApprovalLevel == 2)
			$this->moveApprovalToClusterLead();
		else if($this->prStatusApprovalLevel == 3)
			$this->moveApprovalToOperationManager();
		else if($this->prStatusApprovalLevel == 4)
			$this->moveApprovalToRoot();

	}

	public function managerSubmit(\model\user\user $user)
	{
		if($user->userLevel != 2)
			throw new \Exception('Only manager can submit a PR');

		$this->prUpdatedDate = now();
		$this->prUpdatedUser = \session::get('userID');
		$this->prStatusApprovalLevel = 2;
		$this->save();

		// reset all approval each time approval flow begins (manager resubmit)
		$this->initiateApprovals();

		return $this->moveApprovalToClusterLead();
	}

	public function moveApprovalToClusterLead()
	{
		$this->prStatusPendingLevel = 3; // cluster lead.
		$clusterLeadApproval = $this->getLevelApproval('cl');
		$clusterLeadApproval->makePending();
		$this->save();
	}

	public function moveApprovalToOperationManager()
	{
		$this->prStatusPendingLevel = 4; //opsmanager
		$operationManagerApproval = $this->getLevelApproval('om');
		$operationManagerApproval->makePending();
		$this->save();
	}

	public function moveApprovalToRoot()
	{
		$this->prStatusPendingLevel = 99; //root
		$this->save();
	}

	public function isWaitingForPRNumber()
	{
		return $this->prStatusPendingLevel == 99 && $this->prStatusApprovalLevel == 4 && $this->prNumber == ''; // ops manager has approved
	}

	public function initiateApprovals()
	{
		$levels = array(3, 4); // cl, om

		$this->prStatus = 0;

		$this->save();

		foreach($levels as $level)
		{
			$approval = $this->getLevelApproval($level);

			if(!$approval)
			{
				$approval = model::orm('expense/pr/approval')->create();
				$approval->prID = $this->prID;
				$approval->userLevel = $level;
				$approval->prApprovalStatus = 0;
				$approval->prApprovalCreatedDate = now();
				$approval->save();
			}
			// reset
			else
			{
				$approval->userID = 0;
				$approval->prApprovalStatus = 0;
				$approval->prApprovalUpdatedDate = now();
				$approval->save();
			}

		}
	}

	/**
	 * Check pending against given user
	 * @param \model\user\user
	 */
	public function isPendingFor(\model\user\user $user)
	{
		return $this->prStatusPendingLevel == $user->userLevel;
	}

	public function delete()
	{
		if($this->isClosed())
			return;

		$this->prStatus = 3;
		$this->prUpdatedUser = \session::get('userID');
		$this->save();
	}
}


?>