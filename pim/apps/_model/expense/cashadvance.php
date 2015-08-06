<?php 

namespace model\expense;
use db, session;

class cashadvance extends \Origami
{ 
	protected $table = 'purchase_requisition_cash_advance';
	protected $primary = 'purchaseRequisitionCashAdvanceId';
	
	public function addTransaction($id,$cashAdvanceId,$itemList)
	{
		$data	= Array(

			"purchaseRequisitionId" => $id,
			"purchaseRequisitionCashAdvanceId" => $cashAdvanceId,
			"purchaseRequisitionCashDetailItem" => $itemList['itemRemark'],
			"purchaseRequisitionCashDetailAmount" => $itemList['itemPrice'],
			"purchaseRequisitionCashDetailCreatedDate" => now(),

						);

		db::insert("purchase_requisition_cash_detail",$data);
	}

	public function updateTransaction($id,$itemList)
	{
		$data	= Array(

			"purchaseRequisitionCashDetailItem" => $itemList['itemRemark'],
			"purchaseRequisitionCashDetailAmount" => $itemList['itemPrice'],
			"purchaseRequisitionCashDetailUpdatedDate" => now(),

						);
		
		db::where("purchaseRequisitionCashDetailId",$itemList['itemID']);
		db::update("purchase_requisition_cash_detail",$data);

	}

	//getCashAdvance
	public function getCashAdvance($prId)
	{

		db::from("purchase_requisition_cash_advance");
		db::where("purchase_requisition_cash_advance.purchaseRequisitionId",$prId);
		db::join("purchase_requisition_cash_detail", "purchase_requisition_cash_detail.purchaseRequisitionId = purchase_requisition_cash_advance.purchaseRequisitionId");
		return db::get()->result();
	}
}	
?>