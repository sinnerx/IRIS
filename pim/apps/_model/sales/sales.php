<?php 

namespace model\sales;
use db, session;

class sales extends \Origami
{
	protected $table = 'sales';
	protected $primary = 'salesID';

	protected $cachePrices = null;

	public function getProductPrice($productID)
	{
		if(!$this->cachePrices)
			$this->cachePrices = db::get('product')->result('productID');

		return $this->cachePrices[$productID]['productPrice'];
	}

	/**
	 * ORM : get type name.
	 */
	public function getTypeName()
	{
		return $this->type($this->salesType);
	}

	/**
	 * ORM : Add sales product
	 * @return total (quantity * productPrice)
	 */
	public function addProduct($productID, $quantity)
	{
		$total = $this->getProductPrice($productID) * $quantity;

		db::insert('sales_product', array(
			'productID'=>$productID,
			'salesID'=>$this->salesID,
			'salesProductQuantity'=>$quantity,
			'salesProductTotal'=>$total
			));

		$this->salesTotal = $this->salesTotal + $total;
		$this->save();
	}

	public function type($id = null)
	{
			db::from("product");

		return db::get()->result();


		
	}

	public function addSales($salesTotal,$remark){

		$siteID	= authData('site.siteID');

				$data_sales	= Array(
					
					"siteID"=>$siteID,
					"salesCreatedDate"=>now(),
					"salesCreatedUser"=>session::get("userID"),
					"salesTotal"=>$salesTotal,									
					
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