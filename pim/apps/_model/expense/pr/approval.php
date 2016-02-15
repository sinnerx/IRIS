<?php
namespace model\expense\pr;

class Approval extends \Origami
{
	protected $table = 'pr_approval';
	protected $primary = 'prApprovalID';

	public function getUser()
	{
		return $this->getOne('user/user', 'userID');
	}

	public function getUserProfile()
	{
		return $this->getOne(array('user_profile', 'userProfileID'), 'userID');
	}

	/**
	 * Approve this approval
	 * @param user\user ORM user entity
	 */
	public function approve(\model\user\user $user)
	{
		if($user->userLevel != $this->userLevel)
			return;

		$this->userID = $user->userID;
		$this->prApprovalStatus = 1;
		$this->prApprovalUpdatedDate = now();
		$this->save();
	}

	public function reject(\model\user\user $user)
	{
		if($user->userLevel != $this->userLevel)
			return;
		
		$this->userID = $user->userID;
		$this->prApprovalStatus = 2;
		$this->prApprovalUpdatedDate = now();
		$this->save();
	}
	
	public function makePending()
	{
		$this->prApprovalStatus = 0;
		$this->save();
	}

	public function getStatusLabel()
	{
		if($this->isPending())
			return 'Pending';
		else if($this->isApproved())
			return 'Done';
		else if($this->isRejected())
			return 'Rejected';
	}

	public function isPending()
	{
		return $this->prApprovalStatus == 0;
	}

	public function isApproved()
	{
		return $this->prApprovalStatus == 1;
	}

	public function isRejected()
	{
		return $this->prApprovalStatus == 2;
	}
}


?>