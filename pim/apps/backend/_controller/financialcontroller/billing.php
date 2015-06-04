<?php
class Controller_Billing
{
	public function overview()
	{
		
	}

	public function add()
	{
		$selectDate = input::get('selectDate');
		$data['selectDate'] = $selectDate = $selectDate ? :  date('Y-m-d');
		$data['typeList'] = array(
				1 => "Bank In",
				2 => "Cash Out"
			);

		db::from("site");
		db::order_by("siteName","ASC");
		$res_site = db::get()->result();
		foreach($res_site as $row)
		{
			$data['siteList'][$row['siteID']]	= $row['siteName'];
		}
		
		$data['transaction'] = model::load('billing/billing')->getHqTransaction();
		$data['list'] = model::load('billing/finance')->getList();

		view::render("financialcontroller/billing/add", $data);
	}

	public function addTransaction()
	{	
		$selectDate = input::get('selectDate');
		
		$data['selectDate'] = $selectDate = $selectDate ? :  date('Y-m-d');
		$data['typeList'] = array(
				1 => "Bank In",
				2 => "Cash Out"
			);

		$siteID = input::get('siteID');

		if(form::submitted())
		{
			if ($siteID == ""){

				$message = 'Please Select Site.';
				redirect::to('billing/add', $message, 'error');
			}
		}
			$selectDate = date('Y-m-d', strtotime($selectDate)); 	

			$fcTransaction = model::orm('billing/finance')->create();			
			$fcTransaction->siteID = $siteID;
			$fcTransaction->userID = authData('user.userID');
			$fcTransaction->billingItemID = input::get('itemID');
			$fcTransaction->billingFinanceTransactionTotal = input::get('total');
			$fcTransaction->billingFinanceTransactionDescription = input::get('description');
			$fcTransaction->billingFinanceTransactionType = input::get('transactionType');
			$fcTransaction->billingFinanceTransactionPayment = "";
			$fcTransaction->billingFinanceTransactionDate = $selectDate;
			$fcTransaction->billingFinanceTransactionCreatedDate = now();
			$fcTransaction->billingFinanceTransactionUpdatedDate = now();
			$fcTransaction->save();			

			$log = model::orm('billing/log')->create();
			$log->billingLogType = "Add New HQ Transaction";
			$log->userID = authData('user.userID');
			$log->billingTransactionID = $getTransactionID['billingTransactionID'];
			$log->billingLogContent = serialize(array(

											"itemID"=>input::get('itemID'),
											"description"=>input::get('description'),
											"type"=>input::get('transactionType'),
											"date"=>$selectDate

										));
			$log->billingLogCreatedDate = now();
			//$log->save();	

			db::from("site");
			db::order_by("siteName","ASC");
		
			$res_site = db::get()->result();

		foreach($res_site as $row)
		{
			$data['siteList'][$row['siteID']]	= $row['siteName'];
		}
						
			$data['list'] = model::load('billing/finance')->getList();

		view::render("financialcontroller/billing/add", $data);
	}


	public function dailyCashProcess()
	{
		view::render("shared/billing/dailyCashProcess");
	}
}


?>