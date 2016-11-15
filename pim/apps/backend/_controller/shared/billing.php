<?php
Class Controller_Billing
{
	public function add()
	{
		$siteID = request::get("siteID");

		if ($siteID == ""){ 
			$siteID = authData('site.siteID');	
		} 

		$todayDate = request::get("selectDate");
		$data['todayDate'] = $todayDate = $todayDate ? : date('Y-m-d H:i');

		$month = date('n',strtotime($todayDate));
		
		$data['item'] = model::load('billing/billing')->getItem();
		// var_dump($data['item']);die;
		$data['list'] = model::load('billing/billing')->getList($siteID);

		$totalBalanceDebit = model::load('billing/process')->getBalanceDebit($siteID,date('n',strtotime($todayDate)),date('Y',strtotime($todayDate)));
		$totalBalanceCredit = model::load('billing/process')->getBalanceCredit($siteID,date('n',strtotime($todayDate)),date('Y',strtotime($todayDate)));		
		$previousBalanceDebit = model::load('billing/process')->getBalanceDebit($siteID,$month-1,date('Y',strtotime($todayDate)));
		$previousBalanceCredit = model::load('billing/process')->getBalanceCredit($siteID,$month-1,date('Y',strtotime($todayDate)));

		$previousBalance = $previousBalanceDebit['balance'] + $previousBalanceCredit['balance'];
		$data['totalBalance'] = $totalBalanceDebit['balance'] + $totalBalanceCredit['balance'] + $previousBalance;

		if (request::get("itemID") != ""){

			$item = model::orm('billing/billing')->where('billingItemID', request::get("itemID"))->execute();
			$data['itemSelect'] = $item->getFirst();			
		}

		if (authData('user.userLevel') == \model\user\user::LEVEL_CLUSTERLEAD){
			
			$res_site	= model::load("site/site")->getSitesByClusterLead(session::get("userID"))->result();
		
		} else {

			db::from("site");
			db::order_by("siteName","ASC");
		
			$res_site = db::get()->result();
		}

		foreach($res_site as $row)
		{
			$data['siteList'][$row['siteID']]	= $row['siteName'];
		}

		$allItem = model::load('billing/billing')->getItem();

		foreach($allItem as $row)
		{
			$data['itemList'][$row['billingItemID']]	= $row['billingItemHotkey'];
		}

		if(form::submitted())
		{ 
			$message = 'New Transactionn added!';
				
			redirect::to('billing/add', $message, 'success');
		} else {

			view::render("shared/billing/add", $data);	
		}	
	}

	public function editPoint($billingItemPointID)
	{
		$newEffectiveDate = input::get('newEffectiveDate');
		$newRedeem = input::get('newRedeem');
		$newReward = input::get('newReward');

		$billingpoint = model::orm('billing/billing_point')->find($billingItemPointID);

		$validateDatePoint =false;
		
		$validateDatePoint = $billingpoint->checkDatePoint($billingpoint->billingItemID, $newEffectiveDate);
		// var_dump($billingpoint->effectiveDate);

		//check if date passing = date on the record
		if($billingpoint->effectiveDate == $newEffectiveDate)
			$validateDatePoint = true;
		//success = 1
		if($validateDatePoint === false){
			// $message = "Date already exist for the selected billing item";
			$success = 0;
			// redirect::to('billing/add', $message, 'error');
		}	
		else{
			$billingpoint->rewardPoint 		= $newReward;
			$billingpoint->redeemPoint 		= $newRedeem;
			$billingpoint->effectiveDate 	= $newEffectiveDate;
			$billingpoint->updatedDate	 	= date('Y-m-d H:i:s');
			$billingpoint->updatedUser	 	= session::get("userID");	
			$billingpoint->save();

			// $message = "Successfully update billing point";		
			$success = 1;
			// redirect::to('billing/add', $message, 'success');
		}
			// $returnstring = $billingpoint->effectiveDate . " " . $newEffectiveDate;
		// $data = $billingItemPointID . " ". $newEffectiveDate . " " . $newRedeem . " " . $newReward. " " . $billingpoint->effectiveDate;
		return $success;
	}

	public function addPoint($billingItemID)
	{
		$this->template = false;

		if(form::submitted()){
			$effectiveDate = date('Y-m-d',strtotime(input::get('selectDatePoint')));
			//var_dump(date("Y-m-d", strtotime(input::get('selectDatePoint'))));
			//die;
			//check date exist for billingitemID in billingitempoint table
			$billingPointModel = model::load('billing/billing_point')->checkDatePoint($billingItemID, $effectiveDate);
			// var_dump($billingPointModel);
			// die;
			//if exist, cancel it
			if ($billingPointModel === true){
				$message = "Date already exist for the selected billing item";
				redirect::to('billing/add', $message, 'error');
			}

			//else
			else{
				// var_dump("false");
				// die;
				$billingpoint = model::orm('billing/billing_point')->create();
				$billingpoint->billingItemID 	= $billingItemID;
				$billingpoint->rewardPoint 		= input::get('rewardtxt');
				$billingpoint->redeemPoint 		= input::get('redeemtxt');
				$billingpoint->effectiveDate 	= $effectiveDate;
				$billingpoint->createdDate	 	= date('Y-m-d H:i:s');
				$billingpoint->createdUser	 	= session::get("userID");
				$billingpoint->status		 	= 1;
				$billingpoint->save();
				$message = 'New Billing Point has been added';	
				redirect::to('billing/add', $message, 'success');			
			}
			//add into table
		}
	}

	public function deletePoint($billingItemPointID)
	{	
		$this->template = false;
		
		$billing = model::orm('billing/billing_point')->find($billingItemPointID);

		$billing->status = 0;
		$billing->save();
		
		$message = 'Billing Point Deleted!';
		
		redirect::to('billing/add', $message, 'success');
	}

	public function addItem()
	{		
		$this->template = false;
		
		if(form::submitted())
		{
				$billing = model::orm('billing/billing')->create();
				$billing->billingItemHotkey = input::get('hotKey');
				$billing->billingItemName = input::get('itemName');
				$billing->billingItemType = input::get('itemType');
				$billing->billingItemDescription = input::get('description');
				$billing->billingItemPriceType = input::get('priceGeneral') == 1 ? 1 : 2;
				
				// general
				if($billing->billingItemPriceType == 1)
				{
					$billing->billingItemPrice = input::get('price', 0);
				}
				// membership based price
				else
				{
					$billing->billingItemPrice = input::get('priceMember', 0);
					$billing->billingITemPriceNonmember = input::get('priceNonmember', 0);
				}

				// $billing->billingItemUnit = 1;
				$billing->billingItemQuantity = 1;
				$billing->billingItemTaxDisabled = input::get('taxDisabled');
				$billing->billingItemDescriptionDisabled = input::get('descriptionDisabled');
				$billing->billingItemPriceDisabled = input::get('priceEnabled') == 1 ? 0 : 1;
				// $billing->billingItemUnitDisabled = input::get('unitDisabled');
				$billing->billingItemQuantityDisabled = input::get('quantityEnabled') == 1 ? 0 : 1;
				$billing->billingItemStatus = 1;
				// $billing->billingItemCreatedDate = now();
				$billing->billingItemUpdateddate = now();
				$billing->save();	

				$billingItemID = db::getLastInsertID();
				$billingpoint = model::orm('billing/billing_point')->create();
				$billingpoint->billingItemID 	= $billingItemID;
				$billingpoint->rewardPoint 		= null;
				$billingpoint->redeemPoint 		= null;
				$billingpoint->effectiveDate 	= date('Y-m-d H:i:s');
				$billingpoint->createdDate	 	= date('Y-m-d H:i:s');
				$billingpoint->createdUser	 	= session::get("userID");
				$billingpoint->status		 	= 1;
				$billingpoint->save();

				$message = 'New Item added!';
				
			redirect::to('billing/add', $message, 'success');
		}		

		$keyList = model::load('billing/billing')->getAllHotkey();

		foreach($keyList as $row){

			$keyList[$row['billingItemHotkey']]	= $row['billingItemHotkey'];
		}
			$alpha = array ( "A"=>"A","B"=>"B","C"=>"C","D"=>"D","E"=>"E","F"=>"F","G"=>"G","H"=>"H","I"=>"I","J"=>"J",
							 "K"=>"K","L"=>"L","M"=>"M","N"=>"N","O"=>"O","P"=>"P","Q"=>"Q","R"=>"R","S"=>"S","T"=>"T",
							 "U"=>"U","V"=>"V","W"=>"W","X"=>"X","Y"=>"Y","Z"=>"Z" );

		$data['keyList']=array_diff($alpha,$keyList);
		$data['itemType']=array(
									"1"=>"Debits",
									"2"=>"Credits"	
								);

		view::render("shared/billing/addItem", $data);
	}
	
	public function editItem($id,$pointid)
	{	
		$this->template = false;
		$billing = model::orm('billing/billing')->find($id);
		$billingItemPointList = model::load('billing/billing')->getBillingItemPoint($id);
		// var_dump($billingItemPointList);
		// die;

		if(form::submitted())
		{
			$billing->billingItemHotkey = input::get('hotKey');
			$billing->billingItemName = input::get('itemName');
			$billing->billingItemType = input::get('itemType');
			$billing->billingItemDescription = input::get('description');
			$billing->billingItemPriceType = input::get('priceGeneral') == 1 ? 1 : 2;

			// general
				if($billing->billingItemPriceType == 1)
				{
					$billing->billingItemPrice = input::get('price', 0);
				}
				// membership based price
				else
				{
					$billing->billingItemPrice = input::get('priceMember', 0);
					$billing->billingITemPriceNonmember = input::get('priceNonmember', 0);
				}

			// $billing->billingItemUnit = 1;
			$billing->billingItemQuantity = 1;
			$billing->billingItemTaxDisabled = input::get('taxDisabled');
			$billing->billingItemDescriptionDisabled = input::get('descriptionDisabled');
			$billing->billingItemPriceDisabled = input::get('priceEnabled') == 1 ? 0 : 1;
			// $billing->billingItemUnitDisabled = input::get('unitDisabled');
			$billing->billingItemQuantityDisabled = input::get('quantityEnabled') == 1 ? 0 : 1;
			$billing->billingItemStatus = 1;
			$billing->billingItemCreatedDate = now();
			$billing->billingItemUpdateddate = now();
			$billing->save();

			$message = 'Item Updated!';

			redirect::to('billing/add', $message, 'success');
		}

		$keyList = model::load('billing/billing')->getAllHotkey();

		foreach($keyList as $row){

			$keyList[$row['billingItemHotkey']]	= $row['billingItemHotkey'];
		}
			$alpha = array ( "A"=>"A","B"=>"B","C"=>"C","D"=>"D","E"=>"E","F"=>"F","G"=>"G","H"=>"H","I"=>"I","J"=>"J",
							 "K"=>"K","L"=>"L","M"=>"M","N"=>"N","O"=>"O","P"=>"P","Q"=>"Q","R"=>"R","S"=>"S","T"=>"T",
							 "U"=>"U","V"=>"V","W"=>"W","X"=>"X","Y"=>"Y","Z"=>"Z" );

		$current = array($billing->billingItemHotkey => $billing->billingItemHotkey );	

		$keyList=array_diff($alpha,$keyList);
		$data['keyList'] = array_merge($keyList,$current);

		$data['itemType']=array(
									"1"=>"Debits",
									"2"=>"Credits"	
								);

		$data['item'] = $billing;
		$data['billingItemPointList'] = $billingItemPointList;
		$data['currentPoint'] = $pointid;
		view::render("shared/billing/editItem", $data);	
	}
	
	public function deleteItem($itemID)
	{	

		$billing = model::orm('billing/billing')->find($itemID);

		$billing->billingItemUpdatedDate = now();
		$billing->billingItemStatus = 0;
		$billing->save();
				
		$message = 'Item Deleted!';

		redirect::to('billing/add', $message, 'success');
	}

	public function addTransaction($id)
	{	

		$todayDate = request::get("selectDate");
		$data['todayDate'] = $todayDate = $todayDate ? :  date('Y-m-d H:i');

		if (authData('site.siteID') == ""){ 

			$siteID = input::get('siteID');

		} else {

			$siteID = authData('site.siteID');	
		}

		if(form::submitted())
		{
			if ($siteID == ""){

				$message = 'Please Select Site.';
				redirect::to('billing/add', $message, 'error');
			}

			$checkVerify = model::load('billing/verify')->getVerify(date('Y-m-d', strtotime(input::get('selectDate'))));
					
			if(count($checkVerify) > 0){
				
				$message = 'Transaction for selected date already verify, no new transaction allowed.';
				redirect::to('billing/add', $message, 'error');			
			}

			$approval = model::load('billing/approval')->getApproval($siteID, date('m', strtotime(input::get('selectDate'))), date('Y', strtotime(input::get('selectDate'))));

			if (authData('user.userLevel') == \model\user\user::LEVEL_CLUSTERLEAD){
				$approvalDetail = $approval->getApprovalDetail($approval->billingApprovalID,\model\user\user::LEVEL_CLUSTERLEAD);
				$checkManager = $approval->getApprovalDetail($approval->billingApprovalID,\model\user\user::LEVEL_SITEMANAGER);
				
				if ($checkManager['billingApprovalLevelStatus'] == 1) {

					$message = 'Transaction for this month already checked, no new transaction allowed.';
					redirect::to('billing/add', $message, 'error');	

				} elseif ($checkManager['billingApprovalLevelStatus'] == 0){

					$message = 'This transaction not allowed, Site Manager should checked first';
					redirect::to('billing/add', $message, 'error');				
					
				}				


				if ($approvalDetail['billingApprovalLevelStatus'] == 1) {

					$message = 'Transaction for this month already verify, no new transaction allowed.';
					redirect::to('billing/add', $message, 'error');	

				} elseif ($approvalDetail['billingApprovalLevelStatus'] == 0){

					$message = 'This transaction not allowed';
					redirect::to('billing/add', $message, 'error');				
					
				}

			} else {
				$approvalDetail = $approval->getApprovalDetail($approval->billingApprovalID,\model\user\user::LEVEL_SITEMANAGER);

				if ($approvalDetail['billingApprovalLevelStatus'] == 1) {

					$message = 'Transaction for this month already verify, no new transaction allowed.';
					redirect::to('billing/add', $message, 'error');				
				}				
			}	
		}

			$getTransactionID = model::load('billing/billing')->addTransaction($siteID,$id,input::get());			

			$log = model::orm('billing/log')->create();
			$log->billingLogType = "Add New Transaction";
			$log->userID = authData('user.userID');
			$log->billingTransactionID = $getTransactionID['billingTransactionID'];
			$log->billingLogContent = serialize(array(

										"itemID"=>$getTransactionID['billingItemID'],
										"quantity"=>$getTransactionID['billingTransactionQuantity'],
										"unit"=>$getTransactionID['billingTransactionUnit'],
										"total"=>$getTransactionID['billingTransactionTotal']

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
			$data['item'] = model::load('billing/billing')->getItem();
			$data['list'] = model::load('billing/billing')->getList($siteID);

		$month = date('n',strtotime($todayDate));

		$totalBalanceDebit = model::load('billing/process')->getBalanceDebit($siteID,date('n',strtotime($todayDate)),date('Y',strtotime($todayDate)));
		$totalBalanceCredit = model::load('billing/process')->getBalanceCredit($siteID,date('n',strtotime($todayDate)),date('Y',strtotime($todayDate)));
		
		$previousBalanceDebit = model::load('billing/process')->getBalanceDebit($siteID,$month-1,date('Y',strtotime($todayDate)));
		$previousBalanceCredit = model::load('billing/process')->getBalanceCredit($siteID,$month-1,date('Y',strtotime($todayDate)));

		$previousBalance = $previousBalanceDebit['balance'] + $previousBalanceCredit['balance'];
		$data['totalBalance'] = $totalBalanceDebit['balance'] + $totalBalanceCredit['balance'] + $previousBalance;

		view::render("shared/billing/add", $data);
	}

	public function edit($page=1)
	{	
		$todayDate = request::get("selectDate");
		$data['todayDate'] = $todayDate = $todayDate ? :  date('Y-m-d');	
		
		if (authData('user.userLevel') == \model\user\user::LEVEL_CLUSTERLEAD){
			
			$res_site	= model::load("site/site")->getSitesByClusterLead(session::get("userID"))->result();
		
		} else {

			db::from("site");
			db::order_by("siteName","ASC");
		
			$res_site = db::get()->result();
		}

		foreach($res_site as $row)
		{
			$data['siteList'][$row['siteID']]	= $row['siteName'];
		}

		$allItem = model::load('billing/billing')->getItem();

		foreach($allItem as $row)
		{
			$data['itemList'][$row['billingItemID']] = $row['billingItemHotkey']."  ".$row['billingItemName'];
		}

		$siteID = request::get("siteID") ? : authData('site.siteID');	
		$itemID = request::get("itemID"); 
		$data['selectDate'] = $selectDate = request::get("selectDate") ? :  date('Y-m-d');
				
		if ($siteID != ""){
			$data['list'] = model::load('billing/billing')->getPaginationList($siteID,$itemID,$selectDate,$page);	
			$totalToday = model::load('billing/billing')->getTotalToday($siteID,$selectDate);
			
			foreach ($totalToday as $key => $row) {

				$total = $total + $row['billingTransactionTotal'];
			}

			$data['total'] = $total;	

		}

		$checkVerify = model::orm('billing/verify')->where('billingTransactionDate',  date('Y-m-d', strtotime($selectDate)))->execute();

		if($checkVerify->count() > 0){
			$data['verified'] = 1;
		}

		$approval = model::load('billing/approval')->getApproval($siteID, date('m', strtotime($selectDate)), date('Y', strtotime($selectDate)));

		$approvalDetail = $approval->getApprovalDetail($approval->billingApprovalID,\model\user\user::LEVEL_CLUSTERLEAD);
		$checkSettlement = $approval->getApprovalDetail($approval->billingApprovalID,\model\user\user::LEVEL_SITEMANAGER);

		$data['checkSettlement'] = $checkSettlement['billingApprovalLevelStatus'];
		$data['reject'] = $approvalDetail['billingApprovalLevelStatus'];

		view::render("shared/billing/edit", $data);
	}

	public function settlement($transactionDate = null)
	{	
		$data['todayDate'] = $transactionDate = $transactionDate ? :  date('Y-m-d');	
		
		$siteID =  authData('site.siteID');	
		$userID =  authData('user.userID');

		$verify = model::orm('billing/verify')->create();
				$verify->userID = authData('user.userID');
				$verify->siteID = authData('site.siteID');	
				$verify->billingTransactionDate = date('Y-m-d', strtotime($transactionDate));
				$verify->billingVerificationDate = now();
				$verify->save();

		$data['verified'] = 1;		

		$allItem = model::load('billing/billing')->getItem();

		foreach($allItem as $row)
		{
			$data['itemList'][$row['billingItemID']]	= $row['billingItemHotkey']."  ".$row['billingItemName'];
		}	

		$data['list'] = model::load('billing/billing')->getPaginationList($siteID,$itemID,$selectDate,$page);	
		$totalToday = model::load('billing/billing')->getTotalToday($siteID,$selectDate);
			
		foreach ($totalToday as $key => $row) {

			$total = $total + $row['billingTransactionTotal'];
		}

		$data['total'] = $total;	
		
		redirect::to('billing/edit?&itemID=&selectDate='.$transactionDate, $message, 'success');
	}

	public function editForm($transactionItemID,$transactionID,$transactionDate = null)
	{	
		$this->template = false;
  
		if(form::submitted())
		{
			$transactionDate = date('d-m-Y', strtotime(input::get('transactionDate'))); 						
			$billing = model::load('billing/journal')->editTranscation($transactionItemID);
			$billing = $billing[0];

			$log = model::orm('billing/log')->create();
			$log->billingLogType = "Edit Transaction";
			$log->userID = authData('user.userID');
			$log->billingTransactionID = $transactionID;
			$log->billingLogContent = serialize(array(

					"itemID"=>$billing['billingTransactionItemID']." to ".input::get('itemID'),
					"quantity"=>$billing['billingTransactionItemQuantity']." to ".input::get('quantity'),
					"unit"=>$billing['billingTransactionItemUnit']." to ".input::get('unit'),
					"total"=>$billing['billingTransactionItemPrice']." to ".input::get('total')

										));
			$log->billingLogCreatedDate = now();
			$log->save();

			$data	= Array(

				"billingItemID"=> input::get('itemID'),
				"billingTransactionID"=>$billing['billingTransactionID'],
				"billingTransactionItemDescription"=>input::get('description'),
				"billingTransactionItemQuantity"=>input::get('quantity'),
				"billingTransactionItemUnit"=>input::get('unit'),
				"billingTransactionItemPrice"=>input::get('total')	
						);

			$updateTransactionItem = model::load('billing/journal')->updateTranscationItem($transactionItemID,$data);

			$updateTransaction = model::load('billing/journal')->updateTranscation($billing['billingTransactionID']);

			$message = 'Transaction Updated!';

			redirect::to('billing/edit?&itemID=&selectDate='.$transactionDate, $message, 'success');
		}


		$billing = model::load('billing/journal')->editTranscation($transactionID);	
		$allItem = model::load('billing/billing')->getItem();

		foreach($allItem as $row)
		{
			$data['itemList'][$row['billingItemID']] = $row['billingItemHotkey']."  ".$row['billingItemName'];
		}

		$data['item'] = $billing[0];
		$data['itemID'] = $transactionItemID; 
		$data['transactionDate'] = date('d M Y  h:iA', $transactionDate);

		view::render("shared/billing/editForm", $data);
	}

	public function delete($transactionID)
	{	
		$this->template = false;
		
		$billing = model::orm('billing/journal')->find($transactionID);

		$billing->billingTransactionStatus = 0;
		$billing->save();
		
		$message = 'Transaction Updated!';
		
		redirect::to('billing/edit', $message, 'success');
	}


	public function dailyCashProcess()
	{
		$siteID = authData('site.siteID');
		$data['siteID'] = $siteID = $siteID ? : input::get('siteID');
		$data['siteID'] = $siteID = $siteID ? : request::get('siteID');

		$selectYear = input::get('selectYear');
		if (!$selectYear) {
			$selectYear = request::get('selectYear', date('Y'));
		}
		$data['selectYear'] = $selectYear;
		//$data['selectYear'] = $selectYear = $selectYear ? : input::get('selectYear');
		//$data['selectYear'] = $selectYear = $selectYear ? : request::get('selectYear', date('Y'));

		$selectMonth = input::get('selectMonth');
		if (!$selectMonth) {
			$selectMonth = request::get('selectMonth', date('m'));
		}
		$data['selectMonth'] = $selectMonth;

		//echo $siteID . ' & ' . $selectYear . ' & ' . $selectMonth;
		// prepare all the required by codes.
		$codes = model::load('billing/item')->getItemCodes();

		// if(!$data['siteID'])
		// 	die;

		// site list
		if(authData('user.userLevel') == 99 || authData('user.userLevel') == 5 )
			$data['siteList'] = model::orm('site/site')->execute()->toList('siteID', 'siteName');

		if(authData('user.userLevel') == 3 ){
			$res_site = model::load('site/site')->getSitesByClusterlead(authData('user.userID'))->result();
			foreach($res_site as $row)
			{
				$data['siteList'][$row['siteID']]	= $row['siteName'];
			}
		}

		if($data['siteID'])
			$data['site'] = model::orm('site/site')
			->where('siteID', $data['siteID'])
			->execute()
			->getFirst();
		else
			$data['site'] = null;

		if(!$data['site'])
			goto skipall;

		// get previous month balance.
		// list($previousYear, $previousMonth) = explode('-', date('Y-n', strtotime('-1 month', strtotime($year.'-'.$month.'-01'))));

		// sum of total for previous month.
		$previousTransaction = db::from('billing_transaction')
		->select('SUM(billingTransactionTotal) as total')
		->where('siteID', $data['siteID'])
		->where('billingTransactionDate <', $selectYear.'-'.$selectMonth.'-01')
		->where('billingTransactionStatus', 1)
		->get()->row('total');

		if($previousTransaction)
			$data['balance'] = $previousTransaction;
		else
			$data['balance'] = 0;

		$billingItemCode = model::orm(array('billing_item_code', 'billingItemCodeID'))->execute();

		$billingItems = model::orm('billing/item')->execute();

		$startDate = $selectYear.'-'.$selectMonth.'-01';
		
		$transactionItems = db::from('billing_transaction_item')
		->where('YEAR(billingTransactionDate)', $selectYear)
		->where('MONTH(billingTransactionDate)', $selectMonth)
		// ->where('DAY(billingTransactionDate)', '2')
		->where('siteID', $data['siteID'])
		->where('billingTransactionStatus', 1)
		->join('billing_transaction', 'billing_transaction.billingTransactionID = billing_transaction_item.billingTransactionID', 'INNER JOIN')
		->join('billing_transaction_user', 'billing_transaction_user.billingTransactionID = billing_transaction.billingTransactionID')
		->join('billing_item_code', 'billing_item_code.billingItemID = billing_transaction_item.billingItemID')
		->get()->result();

		// Group by date, itemCOde by item codes.
		$report = array();
		// var_dump($transactionItems);
		// die;
		$users = array();
		foreach($transactionItems as $row)
		{
			$date = date('Y-m-d', strtotime($row['billingTransactionDate']));

			//print_r($row);
			//die;			
			// if no code was configured for this item, set it to other.
			if($row['billingItemCodeName'])
				$code = $row['billingItemCodeName'];
			else
				$code = 'Other';

			// if age is lower than 18, OR occupation group = 1 (student), set it to student.
			if($row['billingTransactionUserAge'] < 18 || $row['billingTransactionUserOccupationGroup'] == 1)
				$userType = 'student';			
			else if($row['billingTransactionUserAge'] < 18 || $row['billingTransactionUserOccupationGroup'] == 7)
				$userType = 'nonstudent';
			// else if($code == 'PC OKU'){
			// 	$userType = 'OKU';		
			// 	$status = 'nonmember';
			// }	
			// else if($code == 'PC Warga Emas'){
			// 	$userType = 'WE';
			// 	$status = 'nonmember';
			// }
			else
				$userType = 'adult';

			// if membership, status will require no member.
			if($code == 'Membership')
				$status = 'nonmember';
			else
				$status = $row['billingTransactionUser'] === 0 || !$row['billingTransactionUser'] ? 'nonmember' : 'member';

			if($code == 'PC OKU'){
				$code = 'PC';
				$userType = 'OKU';
			} else if ($code == 'PC Warga Emas'){
				$code = 'PC';
				$userType = 'WE';		
			}

			$reference = &$report[$date][$code];

			// time
			$time = date('G', strtotime($row['billingTransactionDate'])) >= 19 ? 'night' : 'day';
			//var_dump($row);
			//var_dump(date('G', strtotime($row['billingTransactionDate'])));
			//die;
			// point to the time
			if($code == 'PC')
			{
				if(!isset($reference[$time]))
					$reference[$time] = array();

				$reference = &$reference[$time];
				//var_dump($reference);
			}

			// initiates.
			if(!isset($reference['total']))
				$reference['total'] = 0;
			// else
				// $reference['total'] = $row['billingTransactionItemPrice'] * $row['billingTransactionItemQuantity'];

			if(!isset($reference['total_users']))
				$reference['total_users'] = 0;

			if(!isset($reference['total_quantity']))
				$reference['total_quantity'] = 0;

			if(!isset($reference[$userType][$status]['total']))
				$reference[$userType][$status]['total'] = 0;

			if(!isset($report[$date]['total']))
				$report[$date]['total'] = 0;


			// set.
			$userOnThatDay = $row['billingTransactionUser'];
			if($row['billingTransactionUser'] == 0){
				$users[$date][$code][$time][$userType][$status][$row['billingTransactionUser']] += 1;
				if($code == 'PC'){
					$users[$date][$code][$time][$userType][$status]['total_users'] += 1;
				}else{
					$users[$date][$code][$userType][$status]['total_users'] += 1;
				}
				
				//$users[$date][$code][$time]['total_users'] = $users[$date][$code][$time][$userType][$status]['total_users'];
				
			}else{
				if(!isset($users[$date][$code][$time][$userType][$status][$row['billingTransactionUser']])){
					$users[$date][$code][$time][$userType][$status][$row['billingTransactionUser']] = 1;
					if($code == 'PC'){
						$users[$date][$code][$time][$userType][$status]['total_users'] += 1;
					}else{
						$users[$date][$code][$userType][$status]['total_users'] += 1;
					}
					
					//$users[$date][$code][$time]['total_users'] = $users[$date][$code][$time][$userType][$status]['total_users'];
					
				}
				
				
			}

			$transactionItemTotal = $row['billingTransactionItemPrice'] * $row['billingTransactionItemQuantity'];
			$reference[$userType][$status]['total'] += $transactionItemTotal;
			$reference['total'] += $transactionItemTotal;
			$reference['total_quantity'] += $row['billingTransactionItemQuantity'];
			// $reference['total_users'] += $counter;
			$reference[$userType][$status]['total_quantity'] += $row['billingTransactionItemQuantity'];
			// $reference[$userType][$status]['total_users'] += $counter;

			$report[$date]['total'] += $transactionItemTotal;
		}

		

		// echo '<pre>';
		// print_r($report);
		// print_r($users);
		// die;
		foreach ($users as $keyDate => $valueDate) {
			# code...
			//print_r($valueDate);
			foreach ($valueDate as $keyCode => $valueCode) {
				# code...
				// print_r($valueCode);
				if($keyCode == 'PC'){
					$totalUserPC = 0;
					foreach ($valueCode as $keyTime => $valueTime) {
						# code...
						// print_r($valueTime);
						foreach ($valueTime as $keyUserType => $valueUserType) {
							# code...
							foreach ($valueUserType as $keyStatus => $valueStatus) {
								# code...
								//print_r($valueStatus);
								$totalUserPC += $valueStatus['total_users'];
								//assign to report array
								$report[$keyDate][$keyCode][$keyTime][$keyUserType][$keyStatus]['total_users'] = $valueStatus['total_users'];
							}
							
						}//end foreach UserTYpe
						$users[$keyDate][$keyCode][$keyTime]['total_users'] = $totalUserPC;
						$report[$keyDate][$keyCode][$keyTime]['total_users'] = $totalUserPC;
					}//end foreach Time
				}//end if

				else{
					$totalUserNonPC= 0;
					foreach ($valueCode as $keyUserType => $valueUserType) {
						# code...
						foreach ($valueUserType as $keyStatus => $valueStatus) {
								# code...
								//print_r($valueStatus);
								$totalUserNonPC += $valueStatus['total_users'];
								$report[$keyDate][$keyCode][$keyUserType][$keyStatus]['total_users'] = $valueStatus['total_users'];
							}//end foreach Status

					}//end foreach UserType
					$users[$keyDate][$keyCode]['total_users'] = $totalUserNonPC;
					$report[$keyDate][$keyCode]['total_users'] = $totalUserNonPC;
				}

			}
		}//end foreach
		// $data['report'] = $report;
		// print_r($users);

		 // print_r($report);
		// print_r(count($users['2016-04-02']['PC']['student']['member']));
		// die;

		$data['report'] = $report;
		/*$data['itemTotalCalculator'] = function($rows)
		{
			$total = 0;
			foreach($rows as $row)
				$row['billingTransactionItemPrice'] 
		}*/

		// *** FOR APPROVAL FLOW ***

		//$data['siteID'] = $siteID = request::get("siteID") ? : authData('site.siteID');	
		//$data['selectMonth'] = $selectMonth = request::get("selectMonth") ? :  date('m');
		//$data['selectYear'] = $selectYear = request::get("selectYear") ? :  date('Y');

		$approval = model::load('billing/approval')->getApproval($siteID, $selectMonth, $selectYear);
		//echo 'Site: ' . $siteID . ' Mn: ' . $selectMonth . ' Yr: ' . $selectYear;
		//var_dump($approval);
		if(form::submitted()) {
			//echo 'User Level: ' . authData('user.userLevel') . ' vs ' . \model\user\user::LEVEL_CLUSTERLEAD;
			if (authData('user.userLevel') == \model\user\user::LEVEL_SITEMANAGER){
				$approval->check();	
			} elseif(authData('user.userLevel') == \model\user\user::LEVEL_CLUSTERLEAD) {
				if (input::get("submit") == 1){
					$approval->approve(authData('user.userLevel'));		
				} else {
					$approval->reject(authData('user.userLevel'));
				}
			} else {
				if (input::get("submit") == 1){
					$approval->approve(authData('user.userLevel'));		
				} else {
					$approval->disapprove(authData('user.userLevel'));
				}
			}
			//redirect::to('billing/dailyCashProcess', 'Daily Cash Process checked', 'success');
		}

		$data['checked'] = 0;
		$data['checkedword'] = 'Not Checked';
		$data['approved'] = 0;
		$data['approvedword'] = 'Not Approved<br>';
		$data['closed'] = 0;
		$data['closedword'] = 'Not Closed<br>';
		$approvalDetail = $approval->getApprovalDetail($approval->billingApprovalID,\model\user\user::LEVEL_SITEMANAGER);
		if ($approvalDetail != NULL) {
			$userDetail = model::load('user/user')->getUsersByID($approvalDetail['userID']);
							 
			if ($approvalDetail['billingApprovalLevelStatus'] == 1) {
				$data['checkedword'] = "Checked at ".$approvalDetail['billingApprovalLevelCreatedDate']." <br> by ".$userDetail[$approvalDetail['userID']]['userProfileFullName'];
				$data['checked'] = 1;
			} else {
				$data['checkedword'] = "Rejected by ".$userDetail[$approvalDetail['userID']]['userProfileFullName'] . '<br>';
				$data['checked'] = 2;
			}
		}
		$approvalDetail = $approval->getApprovalDetail($approval->billingApprovalID,\model\user\user::LEVEL_CLUSTERLEAD);
		if ($approvalDetail != NULL) {
			$userDetail = model::load('user/user')->getUsersByID($approvalDetail['userID']);
						 
			if ($approvalDetail['billingApprovalLevelStatus'] == 1) {
				$data['approvedword'] = "Approved at ".$approvalDetail['billingApprovalLevelCreatedDate']." <br> by ".$userDetail[$approvalDetail['userID']]['userProfileFullName'];
				$data['approved'] = 1;
			} else {
				$data['approvedword'] = "Rejected by ".$userDetail[$approvalDetail['userID']]['userProfileFullName'] . '<br>';
				$data['approved'] = 2;
			}
		}
		$approvalDetail = $approval->getApprovalDetail($approval->billingApprovalID,\model\user\user::LEVEL_FINANCIALCONTROLLER);
		if ($approvalDetail != NULL) {
			$userDetail = model::load('user/user')->getUsersByID($approvalDetail['userID']);
						 
			if ($approvalDetail['billingApprovalLevelStatus'] == 1) {
				$data['closedword'] = "Closed at ".$approvalDetail['billingApprovalLevelCreatedDate']." <br> by ".$userDetail[$approvalDetail['userID']]['userProfileFullName'];
				$data['closed'] = 1;
			} else {
				$data['closedword'] = "Rejected by ".$userDetail[$approvalDetail['userID']]['userProfileFullName'] . '<br>';
				$data['closed'] = 2;
			}
			//$data['closed'] = 1;
			//$data['closedword'] = "Closed at ".$approvalDetail['billingApprovalLevelCreatedDate']." <br> by ".$userDetail[$approvalDetail['userID']]['userProfileFullName'];
		}

		skipall:

		view::render('shared/billing/dailyCashProcessRedesign', $data);
	}

        public function dailyCashProcessSummary()
	{
                //$selectMonth = 8;
                //$selectYear = 2016;
		if (authData('user.userLevel') == \model\user\user::LEVEL_CLUSTERLEAD){
			
			$res_site	= model::load("site/site")->getSitesByClusterLead(session::get("userID"))->result();
		
		} else {

			db::from("site");
			db::order_by("siteName","ASC");
		
			$res_site = db::get()->result("siteID");
		}
                
         //var_dump($res_site[0]['siteID']);
        // die;
         $stringSite .= " IN (" ;
		//$stringSite = implode("," , $res_site['siteID']);
		$arraySite = array();
        foreach ($res_site as $keySite => $valueSite) {
        	# code...
        	//var_dump($valueSite['siteID']);
        	//$stringSite .= implode("," , $valueSite['siteID']);
        	array_push($arraySite, $valueSite['siteID']);
        }
        $stringSite .= implode("," , $arraySite);
        $stringSite .= ") ";

        //var_dump($stringSite);
        //die;
        $selectYear = input::get('selectYear');
		if (!$selectYear) {
			$selectYear = request::get('selectYear', date('Y'));
		}
		$data['selectYear'] = $selectYear;
		//$data['selectYear'] = $selectYear = $selectYear ? : input::get('selectYear');
		//$data['selectYear'] = $selectYear = $selectYear ? : request::get('selectYear', date('Y'));

		$selectMonth = input::get('selectMonth');
		if (!$selectMonth) {
			$selectMonth = request::get('selectMonth', date('m'));
		}
		$data['selectMonth'] = $selectMonth;
                
                if($selectMonth != 12){
                    
                $lastDate = $selectYear.'-'.($selectMonth+1).'-01';
                
                } else{
                    //endof december
                    $lastDate = ($selectYear+1).'-'.($selectMonth-11).'-01';
                }
                
                
                $result = db::query("
                select siteName, collection, balance, date2, user2, date3, user3, date5, user5 from site
                
                left join (select siteID, sum(bt.billingTransactionTotal) as collection from billing_transaction bt
                         where bt.billingTransactionStatus = 1
                         and month(bt.billingTransactionDate) = ? and year(bt.billingTransactionDate) = ?
                         group by siteID) as bal on bal.siteID = site.siteID "

                ."left join (select siteID, sum(bt.billingTransactionTotal) as balance from billing_transaction bt
                         where bt.billingTransactionStatus = 1
                         and bt.billingTransactionDate < ?
                         group by siteID) as col on col.siteID = site.siteID "

                ."left join (select siteID, billingApprovalLevelCreatedDate as date2,
                         up.userProfileFullName as user2
                         from billing_approval ba, billing_approval_level bal, user_profile up
                         where bal.billingApprovalID = ba.billingApprovalID
                          and up.userID = bal.userID
                         and bal.userLevel = 2
                        and month = ? and year = ?) as level2 on level2.siteID = site.siteID "

                ."left join (select siteID, billingApprovalLevelCreatedDate as date3,
                         up.userProfileFullName as user3
                         from billing_approval ba, billing_approval_level bal, user_profile up
                         where bal.billingApprovalID = ba.billingApprovalID
                          and up.userID = bal.userID
                         and bal.userLevel = 3
                        and month = ? and year = ?) as level3 on level3.siteID = site.siteID "

                ."left join (select siteID, billingApprovalLevelCreatedDate as date5,
                         up.userProfileFullName as user5
                         from billing_approval ba, billing_approval_level bal, user_profile up
                         where bal.billingApprovalID = ba.billingApprovalID
                          and up.userID = bal.userID
                         and bal.userLevel = 5
                        and month = ? and year = ?) as level5 on level5.siteID = site.siteID "
               ." WHERE site.siteID " . $stringSite

                ." order by siteName ",array($selectMonth,$selectYear, $lastDate, $selectMonth, $selectYear, $selectMonth, $selectYear, $selectMonth, $selectYear))->result();
                $data['allSiteSummary'] = $result;
                $data['selectionData'] = array ($selectMonth, $selectYear, $lastDate);
                
// var_dump($result);
// die();
		view::render('shared/billing/dailyCashProcessSummary', $data);
	}
        
	public function dailyCashProcessOld($id = null)
	{		
		$data['siteID'] = $siteID = request::get("siteID") ? : authData('site.siteID');	
		
		$selectMonth = request::get("selectMonth") ? :  date('m');
		$selectYear = request::get("selectYear") ? :  date('Y');

		$data['selectYear'] = $selectYear = $selectYear ? : input::get("year");
		$data['selectMonth'] = $selectMonth = $selectMonth ? : input::get("month");
		
		if ($siteID != ""){

			$approval = model::load('billing/approval')->getApproval($siteID, $selectMonth, $selectYear);

			if ($approval->getApprovalStatus(\model\user\user::LEVEL_SITEMANAGER) == 1){		
						
				$approvalDetail = $approval->getApprovalDetail($approval->billingApprovalID,\model\user\user::LEVEL_SITEMANAGER);
				$userDetail = model::load('user/user')->getUsersByID($approvalDetail['userID']);
								 
				$data['checked'] = 1;
				$data['checkedword'] = "Checked at ".$approvalDetail['billingApprovalLevelCreatedDate']." <br> by ".$userDetail[$approvalDetail['userID']]['userProfileFullName'];
			}

			if ($approval->getApprovalStatus(\model\user\user::LEVEL_CLUSTERLEAD) == 1){						

				$approvalDetail = $approval->getApprovalDetail($approval->billingApprovalID,\model\user\user::LEVEL_CLUSTERLEAD);
				$userDetail = model::load('user/user')->getUsersByID($approvalDetail['userID']);
								 
				$data['approved'] = 1;
				$data['approvedword'] = "Approved at ".$approvalDetail['billingApprovalLevelCreatedDate']." <br> by ".$userDetail[$approvalDetail['userID']]['userProfileFullName'];
			}

			if ($approval->getApprovalStatus(\model\user\user::LEVEL_FINANCIALCONTROLLER) == 1){
			
				$approvalDetail = $approval->getApprovalDetail($approval->billingApprovalID,\model\user\user::LEVEL_FINANCIALCONTROLLER);
				$userDetail = model::load('user/user')->getUsersByID($approvalDetail['userID']);

				$data['closed'] = 1;
				$data['closedword'] = "Closed at ".$approvalDetail['billingApprovalLevelCreatedDate']." <br> by ".$userDetail[$approvalDetail['userID']]['userProfileFullName'];
			}			
		}	

		if(form::submitted())
		{
			$data['selectMonth'] = $selectMonth = input::get("month");
			$data['selectYear'] = $selectYear = input::get("year");
			$data['siteID'] = $siteID = input::get("siteID");	


			if (authData('user.userLevel') == 2){
				$id = authData('site.siteID'); 
			}
			
			$approval = model::load('billing/approval')->getApproval($id, $selectMonth, $selectYear);

			if (authData('user.userLevel') == \model\user\user::LEVEL_SITEMANAGER){
				$approval->check();	
				
				$approvalDetail = $approval->getApprovalDetail($approval->billingApprovalID,\model\user\user::LEVEL_SITEMANAGER);
				$userDetail = model::load('user/user')->getUsersByID($approvalDetail['userID']);
								 
				$data['checked'] = 1;
				$data['checkedword'] = "Checked at ".$approvalDetail['billingApprovalLevelCreatedDate']." <br> by ".$userDetail[$approvalDetail['userID']]['userProfileFullName'];

			} elseif(authData('user.userLevel') == \model\user\user::LEVEL_CLUSTERLEAD) {
				
				if (input::get("submit") == 1){

					$approval->approve(authData('user.userLevel'));		
					
					$approvalDetail = $approval->getApprovalDetail($approval->billingApprovalID,\model\user\user::LEVEL_CLUSTERLEAD);
					$userDetail = model::load('user/user')->getUsersByID($approvalDetail['userID']);
								 
					$data['approved'] = 1;
					$data['approvedword'] = "Approved at ".$approvalDetail['billingApprovalLevelCreatedDate']." <br> by ".$userDetail[$approvalDetail['userID']]['userProfileFullName'];
				
					$approvalDetail = $approval->getApprovalDetail($approval->billingApprovalID,\model\user\user::LEVEL_SITEMANAGER);
					$userDetail = model::load('user/user')->getUsersByID($approvalDetail['userID']);
								 
					$data['checked'] = 1;
					$data['checkedword'] = "Checked at ".$approvalDetail['billingApprovalLevelCreatedDate']." <br> by ".$userDetail[$approvalDetail['userID']]['userProfileFullName'];
				} else {
				
					$approval->reject(authData('user.userLevel'));

					$approvalDetail = $approval->getApprovalDetail($approval->billingApprovalID,\model\user\user::LEVEL_CLUSTERLEAD);
					$userDetail = model::load('user/user')->getUsersByID($approvalDetail['userID']);								
					
					$data['approvedword'] = "Rejected at ".$approvalDetail['billingApprovalLevelCreatedDate']." <br> by ".$userDetail[$approvalDetail['userID']]['userProfileFullName'];
				}				
			} else {

				if (input::get("submit") == 1){

					$approval->approve(authData('user.userLevel'));		
					
					$approvalDetail = $approval->getApprovalDetail($approval->billingApprovalID,\model\user\user::LEVEL_FINANCIALCONTROLLER);
					$userDetail = model::load('user/user')->getUsersByID($approvalDetail['userID']);
								 
					$data['closed'] = 1;
					$data['closedword'] = "Closed at ".$approvalDetail['billingApprovalLevelCreatedDate']." <br> by ".$userDetail[$approvalDetail['userID']]['userProfileFullName'];

					$approvalDetail = $approval->getApprovalDetail($approval->billingApprovalID,\model\user\user::LEVEL_CLUSTERLEAD);
					$userDetail = model::load('user/user')->getUsersByID($approvalDetail['userID']);
								 
					$data['approved'] = 1;
					$data['approvedword'] = "Approved at ".$approvalDetail['billingApprovalLevelCreatedDate']." <br> by ".$userDetail[$approvalDetail['userID']]['userProfileFullName'];
				
					$approvalDetail = $approval->getApprovalDetail($approval->billingApprovalID,\model\user\user::LEVEL_SITEMANAGER);
					$userDetail = model::load('user/user')->getUsersByID($approvalDetail['userID']);
								 
					$data['checked'] = 1;
					$data['checkedword'] = "Checked at ".$approvalDetail['billingApprovalLevelCreatedDate']." <br> by ".$userDetail[$approvalDetail['userID']]['userProfileFullName'];
				} else {
				
					$approval->disapprove(authData('user.userLevel'));

					$approval->reject(\model\user\user::LEVEL_CLUSTERLEAD);

					$approvalDetail = $approval->getApprovalDetail($approval->billingApprovalID,\model\user\user::LEVEL_FINANCIALCONTROLLER);
					$userDetail = model::load('user/user')->getUsersByID($approvalDetail['userID']);								
					
					$data['closedword'] = "Rejected at ".$approvalDetail['billingApprovalLevelCreatedDate']." <br> by ".$userDetail[$approvalDetail['userID']]['userProfileFullName'];
				}
								
			}
		}	
				
		if (authData('user.userLevel') == \model\user\user::LEVEL_CLUSTERLEAD){
			
			$res_site	= model::load("site/site")->getSitesByClusterLead(session::get("userID"))->result();
		
		} else {

			db::from("site");
			db::order_by("siteName","ASC");
		
			$res_site = db::get()->result();
		}

		foreach($res_site as $row)
		{
			$data['siteList'][$row['siteID']]	= $row['siteName'];
		}
		
		echo 'Site: ' . $siteID;
		if ($siteID != ""){
		
			$getClusterID = model::load('site/cluster')->getClusterID($siteID);
			$closingTime = model::load('site/cluster')->getTime($getClusterID[0][clusterID]);

			$balanceDebit = model::load('billing/process')->getBalanceDebit($siteID,$selectMonth-1,$selectYear);
			$balanceCredit = model::load('billing/process')->getBalanceCredit($siteID,$selectMonth-1,$selectYear);

			$data['balance'] = $balanceDebit['balance'] + $balanceCredit['balance'];
			$dateList = model::load('billing/process')->getdateList($siteID,$selectMonth,$selectYear);
			var_dump($datelist);

		 	foreach($dateList as $key1 => $row)
			{
				$checkDate = date('Y-m-d', strtotime($row['billingTransactionDate'])); 
				$checkdateList = model::load('billing/process')->getList($siteID,$checkDate);

				foreach($checkdateList as $key2 => $cashProcess) {

					$getHour = date("H", strtotime($row['billingTransactionDate'])); 	

					if ($getHour > $closingTime){ 
						$pcType = "Night";
					} else {
						$pcType = "Day";
					}

					$availableData[$checkDate][$cashProcess['billingItemName']] =  Array(
		
						"date"=>$checkDate,
						"pcType"=>$pcType,
						"itemName"=>$cashProcess['billingItemName'],
						"desc"=>$cashProcess['billingTransactionDescription'],
						"quantity"=>$cashProcess['quantity'],
						"unit"=>$cashProcess['unit'],
						"total"=>$cashProcess['total'],			
					);
				}	
					
				$data['transferList'] = model::load('billing/process')->getTransferList($siteID,$checkDate);

				$availableDate[$checkDate] = array (
						$checkDate,
				);
			}

			$totalList = model::load('billing/process')->getListTotal($siteID,$selectMonth,$selectYear);

			foreach($totalList as $key3 => $row)
			{
				$list[$row['transactionDate']] =  Array(
			
					"date"=>$row['transactionDate'],				
					"total"=>$row['total'],
				
						);					
			}

			$data['list'] = $availableData;
			$data['totallist'] = $list;
			$data['availableDate'] = $availableDate;
		}

		$start_date = "01-".$selectMonth."-".$selectYear;
		$start_time = strtotime($start_date);
		$end_time = strtotime("+1 month", $start_time);

		for($i=$start_time; $i<$end_time; $i+=86400)
		{
   			$datelist[] = date('Y-m-d', $i);
		}

			$data['alldate'] = $datelist;	
	
		view::render("shared/billing/dailyCashProcess", $data);
	}

	public function dailyJournal()
	{
		$siteID = request::get("siteID") ? : authData('site.siteID');	
		$todayDateStart = request::get("selectDateStart");
		$todayDateEnd = request::get("selectDateEnd");

		$data['todayDateStart'] = $todayDateStart = $todayDateStart ? :  date('Y-m-d',(strtotime ( '-7 day' , strtotime (date('Y-m-d')) ) ));
		$data['todayDateEnd'] = $todayDateEnd = $todayDateEnd ? :  date('Y-m-d');

		db::from("site");
		db::order_by("siteName","ASC");
		
		$res_site = db::get()->result();
		//var_dump($res_site);
		//die;
		foreach($res_site as $row)
		{
			$data['siteList'][$row['siteID']]	= $row['siteName'];
		}
		$todayDateStart = date('Y-m-d', strtotime($todayDateStart));
		$todayDateEnd = date('Y-m-d', strtotime($todayDateEnd));

		if($siteID)
		{
			$transactions = db::where('billingTransactionDate > ? AND billingTransactionDate < ?', array($todayDateStart.' 00:00:00', $todayDateEnd.' 23:59:59'))
			->where('siteID', $siteID)
			->where('billingTransactionStatus', 1)
			->order_by('billingTransactionDate', "ASC")
			->get('billing_transaction')
			->result('billingTransactionID');

			$siteEnabled = db::from("site")
			->where("siteID", $siteID)->get()->result();
			$siteEnabled = $siteEnabled[0];
			//var_dump(strtotime($siteEnabled['siteUnlockDate']));
			//var_dump(strtotime('-1 day'));
			//die;
			if($siteEnabled['siteUnlockDate'] != '' && strtotime($siteEnabled['siteUnlockDate']) >= strtotime('-1 day')){
				$data['siteEnabled'] = 1;
			}
			else{
				$data['siteEnabled'] = 0;
			}

		}
		else
		{
			$transactions = array();
		}

		$data['groupedTransactions'] = array();
		if(count($transactions) > 0)
		{
			// get all items.
			$data['transactionItems'] = db::where('billingTransactionID', array_keys($transactions))
			->join('billing_item', 'billing_item.billingItemID = billing_transaction_item.billingItemID')
			->get('billing_transaction_item')
			->result('billingTransactionID', true);

			foreach($transactions as $row)
			{
				$date = date('Y-m-d', strtotime($row['billingTransactionDate']));
	
				if(!isset($data['groupedTransactions'][$date]))
					$data['groupedTransactions'][$date] = array();

				$data['groupedTransactions'][$date][] = $row;
			}
		}

		/*$dailyJournal = model::load('billing/journal')->getList($siteID,$todayDateStart,$todayDateEnd);
		$data['dailyJournal'] = $dailyJournal;*/

		/*foreach($dailyJournal as $key => $journalList) {

			$journal = model::load('billing/journal')->getListTotal($journalList['billingTransactionID']);		

			if (count($journal) > 0){

				$data['journal'][$journalList['billingTransactionDate']] = $journal;	
			}
		}*/
		//var_dump($data);
		//die;
		view::render("shared/billing/dailyJournal", $data);
	}

	public function transactionJournal()
	{
		$siteID = request::get("siteID") ? : authData('site.siteID');	
		$todayDateStart = request::get("selectDateStart");
		$todayDateEnd = request::get("selectDateEnd");

		$data['todayDateStart'] = $todayDateStart = $todayDateStart ? :  date('Y-m-d',(strtotime ( '-7 day' , strtotime (date('Y-m-d')) ) ));
		$data['todayDateEnd'] = $todayDateEnd = $todayDateEnd ? :  date('Y-m-d');

		db::from("site");
		db::order_by("siteName","ASC");
		
		$res_site = db::get()->result();

		foreach($res_site as $row)
		{
			$data['siteList'][$row['siteID']]	= $row['siteName'];
		}

		$todayDateStart = date('Y-m-d', strtotime($todayDateStart)); 
		$todayDateEnd = date('Y-m-d', strtotime($todayDateEnd)); 

		/*$data['transactionalJournal'] = model::load('billing/journal')->getTransactionalList($siteID,$todayDateStart,$todayDateEnd);
		
		$startDate = date('Y-m-01', strtotime($todayDateStart)); 

		$previoussum = model::orm('billing/journal')
				->select("SUM(billingTransactionTotal) as total")
				->where("siteID = '$siteID' AND billingTransactionDate >= '$startDate' AND billingTransactionDate <= '$todayDateStart' AND billingTransactionStatus = 1")
				->order_by("billingTransactionDate","ASC")
				->execute();*/

		if($siteID)
		{
			$transactions = db::where('billingTransactionDate > ? AND billingTransactionDate < ?', array($todayDateStart.' 00:00:00', $todayDateEnd.' 23:59:59'))
			->where('siteID', $siteID)
			->where('billingTransactionStatus', 1)
			->order_by('billingTransactionDate', "ASC")
			->get('billing_transaction')
			->result('billingTransactionID');
		}
		else
		{
			$transactions = array();
		}

		$data['balance'] = 0;
		$data['groupedTransactions'] = array();
		if(count($transactions) > 0)
		{
			// get all items.
			$data['transactionItems'] = db::where('billingTransactionID', array_keys($transactions))
			->join('billing_item', 'billing_item.billingItemID = billing_transaction_item.billingItemID')
			->get('billing_transaction_item')
			->result('billingTransactionID', true);

			foreach($transactions as $row)
			{
				$date = date('Y-m-d', strtotime($row['billingTransactionDate']));
	
				if(!isset($data['groupedTransactions'][$date]))
					$data['groupedTransactions'][$date] = array();

				$data['groupedTransactions'][$date][] = $row;
			}
		}

		list($year, $month) = explode('-', date('Y-m', strtotime($todayDateStart)));
		
		 /*foreach($previoussum as $previousbalance)
		 	$data['previoussum'] = $previousbalance->total;*/

		view::render("shared/billing/transactionJournal", $data);
	}

	public function editTransaction($transid)
	{

		$data['transaction'] = model::load("billing/billing")->getBillingTransaction($transid);
		view::render("shared/billing/editTransaction", $data);
	}

	public function unlockTransaction()
	{

		//if cl, choose site under cl only
		if (authData('user.userLevel') == \model\user\user::LEVEL_CLUSTERLEAD){
				
			$res_site	= model::load("site/site")->getSitesByClusterLead(session::get("userID"))->result();

		}
		else{
			db::from("site");
			db::order_by("siteName","ASC");
			
			$res_site = db::get()->result();			
		}

		foreach($res_site as $row)
		{
			//var_dump($row);
			//die;
			$data['siteList'][$row['siteID']]	= $row['siteName'];
		}		

		$site = model::load("site/site")->listUnlockSite();
		//var_dump($site);
		$data['unlockSite'] = $site;
		//$data[] = "";
		//var_dump($data);
		//die;
		view::render("shared/billing/unlockTransaction", $data);
	}
}
