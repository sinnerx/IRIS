<?php 

namespace model\expense;
use db, session;

class item extends \Origami
{ 
	protected $table = 'purchase_requisition_item';
	protected $primary = 'purchaseRequisitionItemId';
	
	public function getList($id)
	{
		
		db::from("purchase_requisition_item")->where("purchaseRequisitionCategoryId = '$id'");

		return db::get()->result();
	}

	
	public function getItemName()
	{
		
		db::from("purchase_requisition_item");

		return db::get()->result();
	}
}	
?>