<?php 

namespace model\expense;
use db, session;

class expenditure extends \Origami
{ 
	protected $table = 'purchase_requisition_expenditure';
	protected $primary = 'purchaseRequisitionExpenditureId';
	
	public function getList($status = null)
	{
		
		if ($status == 1){

			db::where('purchaseRequisitionExpenditureStatus',$status);			

		}

		db::from("purchase_requisition_expenditure");

		return db::get()->result();
	}

	public function getItemDetail($id)
	{

		db::where('purchaseRequisitionExpenditureId',$id);
		db::from("purchase_requisition_expenditure");

		return db::get()->result();
	}

	public function updateItem($id,$data)
	{
		# code...

		db::where('purchaseRequisitionExpenditureId', $id);
		db::update("purchase_requisition_expenditure",$data);

	}
}	
?>