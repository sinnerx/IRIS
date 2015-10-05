<?php 
namespace model\expense;
use db, session, model;

class Approval extends \Origami
{
	protected $table = 'expense_approval';
	protected $primary = 'expenseApprovalID';

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
	public function disapprove($level = null)
	{
		if(!$level)
			$level = authData('user.userLevel');

		return $this->setApprovalStatus($level, 2);
	}

	protected function setApprovalStatus($level, $status)
	{
		$row = $this->getLevel($level);
		
		$data = array(
			'expenseApprovalLevelStatus' => $status,
			'userID' => authData('user.userID'),
			'expenseApprovalLevelCreatedDate' => now());

		db::where('expenseApprovalLevelID', $row['expenseApprovalLevelID'])
		->update('expense_approval_level', $data);
	}

	/**
	 * ORM : Get array of row for expense_approval_level
	 * @return array
	 */
	public function getLevel($level)
	{
		db::where('expenseApprovalID', $this->expenseApprovalID);
		db::where('userLevel', $level);

		$row = db::get('expense_approval_level')->row();

		if($row)
			return $row;

		if ($this->expenseApprovalID != ""){
		db::insert('expense_approval_level', array(
				'expenseApprovalID' => $this->expenseApprovalID,
				'userLevel' => $level,
				'expenseApprovalLevelStatus' => 0,
				'expenseApprovalLevelCreatedDate' => now()));
		}
		return db::getLastID('expense_approval_level', 'expenseApprovalLevelID', true);
	}

	/**
	 * @return \model\expense\approval
	 */
	public function getApproval($prID, $type, $siteID, $createdDate)
	{

		db::where('purchaseRequisitionId', $prID);
		db::where('siteID', $siteID);
		db::where('expenseApprovalType', $type);

		$row = db::get("expense_approval")->row();
		$this->expenseApprovalID = $row['expenseApprovalID'];

		if($row) {
			return $this->approve();
		} else {

		$approval = model::orm('expense/approval')->create();
		$approval->purchaseRequisitionId = $prID;
		$approval->expenseApprovalType = $type;
		$approval->siteID = $siteID;
		$approval->purchaseRequisitionCreatedDate = $createdDate;
		$approval->expenseApprovalCreatedDate = now();
		
		$approval->save();
		}
		
		//return db::getLastID('expense_approval', 'expenseApprovalID', true);
		return $this->approve();
	}

		public function getDisapproval($prID, $type, $siteID, $createdDate)
	{

		db::where('purchaseRequisitionId', $prID);
		db::where('siteID', $siteID);

		$row = db::get("expense_approval")->row();
		$this->expenseApprovalID = $row['expenseApprovalID'];

		//return db::getLastID('expense_approval', 'expenseApprovalID', true);
		return $this->disapprove();
	}

	public function getApprovalDetail($prID, $type, $level)
	{

		$where = array(
			'expense_approval.purchaseRequisitionId' => $prID,
			'expense_approval.expenseApprovalType' => $type,
			'expense_approval_level.userLevel' => $level
			);				


		db::from("expense_approval");
		db::where($where);
		db::join("expense_approval_level", "expense_approval_level.expenseApprovalID = expense_approval.expenseApprovalID");

		$row = db::get()->row();

		return $row;
	}
	public function getStatus($prID,$prType)
	{
		$where	= Array(
				"expense_approval.purchaseRequisitionId"=>$prID,												
				"expense_approval.expenseApprovalType" => $prType 
						);

		db::from("expense_approval");
		db::where($where);
		db::join("expense_approval_level", "expense_approval_level.expenseApprovalID = expense_approval.expenseApprovalID");
		db::order_by("expenseApprovalLevelCreatedDate","DESC");

		$getStatus = db::get()->row();

		if ($getStatus['userLevel'] == \model\user\user::LEVEL_SITEMANAGER )	
		{
			if ($getStatus['expenseApprovalLevelStatus'] == 1){

				$status = "Pending at Clusterlead";
				$pr = 2;
			} else {
				$status = "Rejected by Clusterlead";
				$pr = x;
			}

		} 
		elseif ($getStatus['userLevel'] == \model\user\user::LEVEL_CLUSTERLEAD )	
		{
			if ($getStatus['expenseApprovalLevelStatus'] == 1){

				$status = "Pending at Operation Manager";
				$pr = 3;
			} else {
				$status = "Rejected by Clusterlead";
				$pr = x;
			}

		}
		elseif ($getStatus['userLevel'] == \model\user\user::LEVEL_OPERATIONMANAGER )	
		{
			if ($getStatus['expenseApprovalLevelStatus'] == 1){

				$status = "Waiting PR Number";
				$pr = 1;
			} else {
				$status = "Rejected by Operation Manager";
				$pr = x;
			}

		} 
		elseif ($getStatus['userLevel'] == \model\user\user::LEVEL_FINANCIALCONTROLLER )	
		{
			if ($getStatus['expenseApprovalLevelStatus'] == 1){

				$status = "Closed by Financial Controller";
				$pr = 1;
			}

		} 


		else {

				$status = "Waiting Manager Input";
		}
			
			$data = array ($status,$pr);

		return $data;
	}

	public function getStatusDetail($prID,$prType)
	{
		$where	= Array(
				"expense_approval.purchaseRequisitionId"=>$prID,												
				"expense_approval.expenseApprovalType" => $prType 
						);

		db::from("expense_approval");
		db::where($where);
		db::join("expense_approval_level", "expense_approval_level.expenseApprovalID = expense_approval.expenseApprovalID");
		db::order_by("userLevel","ASC");

		return db::get()->result();
	}
}