<?php 

namespace model\expense;
use db, session;

class item extends \Origami
{ 
	protected $table = 'purchase_requisition_item';
	protected $primary = 'purchaseRequisitionItemId';
	
	public function getList($id)
	{
		
		db::from("expense_item")->where("expenseCategoryID", $id);

		return db::get()->result();
	}

	public function getDetail($id)
	{
		
		db::from("purchase_requisition_item")->where("purchaseRequisitionItemId = '$id'");

		return db::get()->result();
	}

	public function addItem($data)
	{
		
		db::insert("purchase_requisition_item",$data);
	}
	
	public function getItemName()
	{
		
		db::from("purchase_requisition_item");

		return db::get()->result();
	}

	public function updateItem($id, $data)
	{
				
		db::where('purchaseRequisitionItemId', $id);
		db::update("purchase_requisition_item",$data);

		
	}
}	
?>