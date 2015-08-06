<?php 

namespace model\expense;
use db, session;

class category extends \Origami
{ 
	protected $table = 'purchase_requisition_category';
	protected $primary = 'purchaseRequisitionCategoryId';
	
	public function getList()
	{
		
		db::from("purchase_requisition_category");

		return db::get()->result();
	}
}	
?>