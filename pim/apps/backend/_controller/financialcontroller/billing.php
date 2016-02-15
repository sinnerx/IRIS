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
				1 => "Monthly Revenue",			
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
		$data['list'] = model::load('billing/billing')->getFinanceList(authData('user.userID'));

		view::render("financialcontroller/billing/add", $data);
	}

	public function addTransaction()
	{	
		$selectDate = input::get('selectDate');
		
		$data['selectDate'] = $selectDate = $selectDate ? :  date('Y-m-d');
		$data['typeList'] = array(
				1 => "Monthly Revenue",
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
			
			$fcSite	= Array(

				"quantity" => 1,
				"unit" => 1,
				"total" => input::get('total'),
				"description" => input::get('description'),
				"type" => input::get('transactionType'),
				"selectDate" => $selectDate

						);

			$getTransactionID = model::load('billing/billing')->addTransaction($siteID,15,$fcSite,0);	

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
			$log->save();	

			db::from("site");
			db::order_by("siteName","ASC");
		
			$res_site = db::get()->result();

		foreach($res_site as $row)
		{
			$data['siteList'][$row['siteID']]	= $row['siteName'];
		}
						
			$data['list'] = model::load('billing/billing')->getFinanceList(authData('user.userID'));

		view::render("financialcontroller/billing/add", $data);
	}


	public function editTransaction($id)
	{	
		$this->template = false;
		$selectDate = input::get('selectDate');
		
		$data['selectDate'] = $selectDate = $selectDate ? :  date('Y-m-d');
		$data['typeList'] = array(
				1 => "Monthly Revenue",		
				2 => "Cash Out"
			);

		$siteID = input::get('siteID');

		$fcTransaction = model::orm('billing/journal')->find($id);		

		$data['fcTransaction'] = $fcTransaction;


		if(form::submitted())
		{
			if ($siteID == ""){

				$message = 'Please Select Site.';
				redirect::to('billing/add', $message, 'error');
			}


			$selectDate = date('Y-m-d', strtotime($selectDate)); 	

			$billing = model::orm('billing/journal')->find($id);

			$billing->billingItemID = 15;			
			$billing->billingTransactionTotal = "-".input::get('total');
			$billing->billingTransactionDescription = input::get('description');
			$billing->billingTransactionUpdatedDate = now();
			$billing->save();		

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
			$log->save();	


			$message = 'Transaction Updated!';
			
			redirect::to('billing/add', $message, 'success');
		}


			db::from("site");
			db::order_by("siteName","ASC");
		
			$res_site = db::get()->result();

		foreach($res_site as $row)
		{
			$data['siteList'][$row['siteID']]	= $row['siteName'];
		}
						
			$data['list'] = model::load('billing/billing')->getFinanceList(authData('user.userID'));

		view::render("financialcontroller/billing/editForm", $data);
	}

	public function dailyCashProcess()
	{
		view::render("shared/billing/dailyCashProcess");
	}

	public function delete($transactionID)
	{	
		$this->template = false;

		$billing = model::orm('billing/journal')->find($transactionID);

		$billing->billingTransactionStatus = 0;
		$billing->save();

		$message = 'Transaction Updated!';
		
		redirect::to('billing/add', $message, 'success');
	}
}


?>