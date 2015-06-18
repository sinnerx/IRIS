<?php namespace model\billing;
use db, session, model;

class Approval extends \Origami
{
	protected $table = 'billing_approval';
	protected $primary = 'billingApprovalID';

	/**
	 * ORM : approve on the given level
	 */
	public function approve($level = null)
	{
		if(!$level)
			$level = authData('user.userLevel');

		return $this->setApprovalStatus($level, 1);
	}

	/**
	 * ORM : disapproval on the given level
	 */
	public function disapprove($level)
	{
		if($level == \model\user\user::LEVEL_FINANCIALCONTROLLER)
			$this->setApprovalStatus(\model\user\user::LEVEL_CLUSTERLEAD, 0);

		return $this->setApprovalStatus($level, 2);
	}

	public function reject($level)
	{
		if($level == \model\user\user::LEVEL_CLUSTERLEAD)
			$this->setRejectStatus(\model\user\user::LEVEL_SITEMANAGER, 0);

		return $this->setRejectStatus($level, 2);
	}

	protected function setRejectStatus($level, $status)
	{

		$row = $this->getLevel($level);
		
		$data = array(
			'userID' => authData('user.userID'),
			'billingApprovalLevelStatus' => $status
			);

		db::where('billingApprovalLevelID', $row['billingApprovalLevelID'])
		->update('billing_approval_level', $data);
	}

	/**
	 * @return int
	 */
	public function getApprovalStatus($level)
	{
		$row = $this->getLevel($level);

		return $row['billingApprovalLevelStatus'];
	}

	/**
	 * Check the approval (should only be used on site manager level)
	 */
	public function check()
	{
		$level = authData('user.userLevel');

		if($level != \model\user\user::LEVEL_SITEMANAGER)
			throw new \Exception("You must be sitemanager");

		$rowLevel = $this->getLevel($level);

		db::where('billingApprovalLevelID', $rowLevel['billingApprovalLevelID'])
		->update('billing_approval_level', array('billingApprovalLevelStatus' => 1, 'userID' => authData('user.userID'), 'billingApprovalLevelCreatedDate' => now()));
	}

	protected function setApprovalStatus($level, $status)
	{
		if(!in_array($level, array(\model\user\user::LEVEL_CLUSTERLEAD, \model\user\user::LEVEL_FINANCIALCONTROLLER)))
			throw new \Exception("Can only be approved by either CL or FC");

		$row = $this->getLevel($level);
		
		$data = array(
			'billingApprovalLevelStatus' => $status,
			'userID' => authData('user.userID'),
			'billingApprovalLevelCreatedDate' => now());

		db::where('billingApprovalLevelID', $row['billingApprovalLevelID'])
		->update('billing_approval_level', $data);
	}

	/**
	 * ORM : Get array of row for billing_approval_level
	 * @return array
	 */
	public function getLevel($level)
	{
		db::where('billingApprovalID', $this->billingApprovalID);
		db::where('userLevel', $level);

		$row = db::get('billing_approval_level')->row();

		if($row)
			return $row;

		if ($this->billingApprovalID != ""){
		db::insert('billing_approval_level', array(
				'billingApprovalID' => $this->billingApprovalID,
				'userLevel' => $level,
				'billingApprovalLevelStatus' => 0,
				'billingApprovalLevelCreatedDate' => now()));
		}
		return db::getLastID('billing_approval_level', 'billingApprovalLevelID', true);
	}

	/**
	 * @return \model\billing\approval
	 */
	public function getApproval($siteID, $month, $year)
	{

		$approval = model::orm('billing/approval')
		->where(array(
			'siteID' => $siteID,
			'month' => $month,
			'year' => $year
			))
		->execute();

		if($approval->count() > 0)
			return $approval->getFirst();

		$approval = model::orm('billing/approval')->create();
		$approval->siteID = $siteID;
		$approval->month = $month;
		$approval->year = $year;
		$approval->billingApprovalCreatedDate = now();
		
		if (($siteID != "") && ($year != "")){
		$approval->save();
		}
		
		return $approval;
	}

	public function getApprovalDetail($billingApprovalID, $level)
	{

		$where = array(
			'billingApprovalID' => $billingApprovalID,
			'userLevel' => $level
			);
		
		db::from("billing_approval_level")->where($where);

		$row = db::get()->row();

		return $row;
	}



}