<?php
namespace model\expense\pr\reconcilation;

class Approval extends \Origami
{
	protected $table = 'pr_reconcilation_approval';
	protected $primary = 'prReconcilationApprovalID';

	public function getUser()
	{
		return $this->getOne('user/user', 'userID');
	}

	public function getUserProfile()
	{
		return $this->getOne(array('user_profile', 'userProfileID'), 'userID');
	}

	public function getStatusLabel()
	{
		if($this->isPending())
			return 'Pending';

		if($this->isApproved())
			return 'Done';

		if($this->isRejected())
			return 'Rejected';
	}

	public function approve(\model\user\user $user)
	{
		if($user->userLevel != $this->userLevel)
			throw new \Exception("User is not permitted to approve this rl", 1);

		$this->userID = $user->userID;
		$this->prReconcilationApprovalStatus = 1;
		$this->prReconcilationApprovalUpdatedDate = now();
		$this->save();
	}

	public function reject(\model\user\user $user)
	{
		if($user->userLevel != $this->userLevel)
			throw new \Exception("User is not permitted to reject this rl", 1);

		$this->userID = $user->userID;
		$this->prReconcilationApprovalStatus = 2;
		$this->prReconcilationApprovalUpdatedDate = now();
		$this->save();
	}

	public function makePending()
	{
		$this->prReconcilationApprovalStatus = 0;
		$this->prReconcilationApprovalUpdatedDate = now();
		$this->save();
	}

	public function isPending()
	{
		return $this->prReconcilationApprovalStatus == 0;
	}

	public function isApproved()
	{
		return $this->prReconcilationApprovalStatus == 1;
	}

	public function isRejected()
	{
		return $this->prReconcilationApprovalStatus == 2;
	}
}


?>