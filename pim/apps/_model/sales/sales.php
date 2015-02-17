<?php 

namespace model\sales;
use db, session;

class sales {

/*	protected $table = 'sales';
	protected $primary = 'salesID';*/

	public function type($id = null)
	{
		$types = array(
			1=> 'Top-up',
			2=> 'Printing',
			3=> 'Pc');

		return !$id ? $types : $types[$id];
	}

	public function addSales($salesIncome,$salesType,$remark){

		$siteID	= authData('site.siteID');



				$data_sales	= Array(
					
					"siteID"=>$siteID,
					"salesCreatedDate"=>now(),
					"salesCreatedUser"=>session::get("userID"),
					"salesTotal"=>$salesIncome,
					"salesRemark"=>$remark,
					"salesType"=>$salesType,
					
								);

		db::insert("sales",$data_sales);


	}


	public function getSales($siteID,$month,$year){

		

		db::select("sum(salesTotal) as totalSale");
		db::where("siteID",$siteID);
		
		db::where("month(salesCreatedDate)",$month);
		db::where("year(salesCreatedDate)",$year);


		return db::get("sales")->result();

	}
}









?>