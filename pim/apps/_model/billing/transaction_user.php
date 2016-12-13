<?php
namespace model\billing;
use db, session, pagination, model, url;


class Transaction_user extends \Origami
{
	protected $table = 'billing_transaction_user';
	protected $primary = 'billingTransactionUserID';

	public function getTransactionPoint($userID, $pagination, $dateStart = null, $dateEnd = null){

		// var_dump($pagination);
		// die;
		db::select("BI.billingItemName, BT.billingTransactionDate, (BTI.billingTransactionItemPoint*BTI.billingTransactionItemQuantity) AS billingTransactionItemPoint");
		db::from("billing_transaction_user BTU");
		db::join("billing_transaction_item BTI", "BTU.billingTransactionID = BTI.billingTransactionID");
		db::join("billing_transaction BT", "BT.billingTransactionID = BTI.billingTransactionID");
		db::join("billing_item BI", "BI.billingItemID = BTI.billingItemID");
		db::where("BT.billingTransactionDate >= NOW() - INTERVAL 3 MONTH");
		db::where("BTI.billingTransactionItemPoint IS NOT NULL");
		db::where("BT.billingTransactionStatus", "1");
		db::where("BTU.billingTransactionUser ", $userID);
		db::order_by("BT.billingTransactionDate", "DESC");

			if($pagination)
			{
				$totalRows = db::num_rows();
				
				## required property : totalRow, currentPage, limit urlFormat
				pagination::initiate(Array(
					"totalRow"=>$totalRows,
					"currentPage"=>$pagination['currentPage'],
					"urlFormat"=>$pagination['urlFormat']
									));
				
				//var_dump($pagination['urlFormat']);
		// var_dump(pagination::recordNo()-1);
		// die;
				db::limit(pagination::get("limit"), pagination::recordNo()-1);
			}		
		// db::where("BTI.billingTransactionItemPoint IS NOT NULL");
		$result  = db::get()->result();

		return $result;
	}

	public function getTransactionPointList($param){
		//var_dump($param);
		// die;
		db::select("BI.billingItemName, BT.billingTransactionDate, (BTI.billingTransactionItemPoint*BTI.billingTransactionItemQuantity) AS billingTransactionItemPoint");
		db::from("billing_transaction_user BTU");
		db::join("billing_transaction_item BTI", "BTU.billingTransactionID = BTI.billingTransactionID");
		db::join("billing_transaction BT", "BT.billingTransactionID = BTI.billingTransactionID");
		db::join("billing_item BI", "BI.billingItemID = BTI.billingItemID");
		db::where("BTI.billingTransactionItemPoint IS NOT NULL");
		db::where("BT.billingTransactionStatus", "1");

		// db::join("site_member SM", "BTU.billingTransactionUser = SM.userID");
		// db::join("user_profile UP", "UP.userID = BTU.billingTransactionUser");
		
		if($param['dateStart'] && $param['dateEnd']){
			$dateStart = $param['dateStart'];
			$dateEnd = $param['dateEnd'];

			db::where("BT.billingTransactionDate BETWEEN '$dateStart' AND '$dateEnd'");
		}
		
		if($param['siteID'])
			db::where("SM.siteID", $param['siteID']);

		if($param['userID'])
			db::where("BTU.billingTransactionUser ", $param['userID']);		

		if($param['billingItemType'])
			db::where("BI.billingItemID", $param['billingItemType']);
		
		$pagination = $param['pagination'];
		// var_dump($pagination);
		// die;
		db::order_by("BT.billingTransactionDate", "DESC");
		if($pagination)
		{
			$totalRows = db::num_rows();
			
			## required property : totalRow, currentPage, limit urlFormat
			pagination::initiate(Array(
				"totalRow"=>$totalRows,
				"currentPage"=>$pagination['currentPage'],
				"urlFormat"=>$pagination['urlFormat']
								));
			
			//var_dump($pagination['urlFormat']);
	// var_dump(pagination::recordNo()-1);
	// die;
			db::limit(pagination::get("limit"), pagination::recordNo()-1);
		}	

		$result = db::get()->result();
		// var_dump($result);
		// die;
		return $result;
	}
}

