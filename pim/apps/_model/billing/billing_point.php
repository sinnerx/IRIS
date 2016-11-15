<?php
namespace model\billing;
use db, session, pagination, model, url;

class Billing_point extends \Origami
{
	protected $table = 'billing_item_point';
	protected $primary = 'billingItemPointID';


	public function checkDatePoint($id, $date)
	{
		// var_dump($id. " ". $date);

		db::select("billingItemPointID");
		db::from("billing_item_point");
		db::where("billing_item_point.billingItemID", $id);
		db::where("billing_item_point.effectiveDate", $date);
		db::where("billing_item_point.status", 1);

		$result = db::get()->row();

		$countrow = count($result);
		// var_dump($countrow);
		// die;
		if($countrow >= 1){
			return false;
		}
		else{
			return true;
		}


	}
}

