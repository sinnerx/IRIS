<?php 

namespace model\expense;
use db, session;

class details extends \Origami
{ 
	protected $table = 'purchase_requisition_detail';
	protected $primary = 'purchaseRequisitionDetailId';

	
	public function getPrList($prId)
	{
		$where	= Array(
				"purchaseRequisitionId"=>$prId									
						);

		db::from("purchase_requisition_detail");
		db::where($where);
		return db::get()->result();
	}
}	

?>