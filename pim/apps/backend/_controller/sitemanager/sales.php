<?php
Class Controller_Sales
{
	public function add()
	{
		$siteID	= authData('site.siteID');
	
	if(form::submitted())
		{

			$salesIncome = input::get("salesIncome");
			$remark 	 = input::get("remark");
			$salesType 	 = input::get("salesType");
		

			//save db
			
			model::load('sales/sales')->addSales($salesIncome,$salesType,$remark);
		}

		

		$data['types'] = model::load('sales/sales')->type();
		
		view::render("sitemanager/sales/add",$data);
	}


}