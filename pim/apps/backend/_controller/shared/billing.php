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

		db::from("site");
		db::order_by("siteName","ASC");
		
		$res_site = db::get()->result();

		foreach($res_site as $row)
		{
			$data['siteList'][$row['siteID']]	= $row['siteName'];
		}

		$allItem = model::load('billing/billing')->getItem();

		foreach($allItem as $row)
		{
			$data['itemList'][$row['billingItemID']]	= $row['billingItemHotkey'];
		}

		view::render("shared/billing/add", $data);
	}

	public function addItem()
	{		
		$this->template = false;
		
		if(form::submitted())
		{
//			$data['billingItemHotkey'] = model::orm('billing/billing')->where('billingItemHotkey', input::get('hotKey'))->execute();
				$billing = model::orm('billing/billing')->create();
				$billing->billingItemHotkey = input::get('hotKey');
				$billing->billingItemName = input::get('itemName');
				$billing->billingItemDescription = input::get('description');
				$billing->billingItemPrice = input::get('price');
				$billing->billingItemUnit = 1;
				$billing->billingItemQuantity = 1;
				$billing->billingItemTaxDisabled = input::get('taxDisabled');
				$billing->billingItemDescriptionDisabled = input::get('descriptionDisabled');
				$billing->billingItemPriceDisabled = input::get('priceDisabled');
				$billing->billingItemUnitDisabled = input::get('unitDisabled');
				$billing->billingItemQuantityDisabled = input::get('quantityDisabled');
				$billing->billingItemCreatedDate = now();
				$billing->save();	

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

		view::render("shared/billing/addItem", $data);
	}
	
	public function editItem($id)
	{	
		$this->template = false;
		$billing = model::orm('billing/billing')->find($id);

		if(form::submitted())
		{
			$billing->billingItemHotkey = input::get('hotKey');
			$billing->billingItemName = input::get('itemName');
			$billing->billingItemDescription = input::get('description');
			$billing->billingItemPrice = input::get('price');
			$billing->billingItemUnit = 1;
			$billing->billingItemQuantity = 1;
			$billing->billingItemTaxDisabled = input::get('taxDisabled');
			$billing->billingItemDescriptionDisabled = input::get('descriptionDisabled');
			$billing->billingItemPriceDisabled = input::get('priceDisabled');
			$billing->billingItemUnitDisabled = input::get('unitDisabled');
			$billing->billingItemQuantityDisabled = input::get('quantityDisabled');
			$billing->billingItemCreatedDate = now();
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

		$data['item'] = $billing;
		view::render("shared/billing/editItem", $data);
	}
	
	public function addTransaction($id)
	{	
		$todayDate = input::get('selectDate');
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

			$checkVerify = model::orm('billing/verify')->where('billingTransactionDate',  date('Y-m-d', strtotime($todayDate)))->execute();
		
			if($checkVerify->count() > 0){
				
				$message = 'Transaction for selected date already verify, no new transaction allowed.';
				redirect::to('billing/add', $message, 'error');			
			}

		}
			$checkBalance = model::load('billing/billing')->getList($siteID,1);
			$lastBalance = $checkBalance[0][billingTransactionBalance];

			$getTransactionID = model::load('billing/billing')->addTransaction($siteID,$id,input::get(),$lastBalance);			

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
		
		view::render("shared/billing/add", $data);
	}

	public function edit()
	{	
		$todayDate = request::get("selectDate");
		$data['todayDate'] = $todayDate = $todayDate ? :  date('Y-m-d');	
		
		db::from("site");
		db::order_by("siteName","ASC");
		
		$res_site = db::get()->result();

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
			$data['list'] = model::load('billing/billing')->getAllList($siteID,$itemID,$selectDate);	
		}

		$checkVerify = model::orm('billing/verify')->where('billingTransactionDate',  date('Y-m-d', strtotime($selectDate)))->execute();
		
		if($checkVerify->count() > 0){
			$data['verified'] = 1;
		}

		view::render("shared/billing/edit", $data);
	}

	public function settlement($transactionDate)
	{	
		
		$data['todayDate'] = $transactionDate;
		
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
	
		$data['list'] = model::load('billing/billing')->getAllList($siteID,$itemID,$transactionDate);	
		
		view::render("shared/billing/edit", $data);
	}

	public function editForm($itemID,$transactionID,$transactionDate = null)
	{	
		$this->template = false;

		$billing = model::orm('billing/journal')->find($transactionID);

		if(form::submitted())
		{
			$log = model::orm('billing/log')->create();
			$log->billingLogType = "Edit Transaction";
			$log->userID = authData('user.userID');
			$log->billingTransactionID = $transactionID;
			$log->billingLogContent = serialize(array(

					"itemID"=>$billing->billingItemID." to ".input::get('itemID'),
					"quantity"=>$billing->billingTransactionQuantity." to ".input::get('quantity'),
					"unit"=>$billing->billingTransactionUnit." to ".input::get('unit'),
					"total"=>$billing->billingTransactionTotal." to ".input::get('total')

										));
			$log->billingLogCreatedDate = now();
			$log->save();	

			$billing->billingItemID = input::get('itemID');
			$billing->billingTransactionQuantity = input::get('quantity');
			$billing->billingTransactionUnit = input::get('unit');
			$billing->billingTransactionTotal = input::get('total');
			$billing->billingTransactionDescription = input::get('description');
			$billing->billingTransactionUpdatedDate = now();
			$billing->save();
	
			$message = 'Transaction Updated!';

			redirect::to('billing/edit', $message, 'success');
		}

		$billing = model::orm('billing/journal')->find($transactionID);
		$allItem = model::load('billing/billing')->getItem();

		foreach($allItem as $row)
		{
			$data['itemList'][$row['billingItemID']] = $row['billingItemHotkey']."  ".$row['billingItemName'];
		}

		$data['item'] = $billing;
		$data['itemID'] = $itemID; 
		$data['transactionDate'] = date('d M Y  h:iA', $transactionDate);

		view::render("shared/billing/editForm", $data);
	}

	public function delete($transactionID)
	{	
		$this->template = false;
		//$transactionID = request::get("transactionId");
		$billing = model::orm('billing/journal')->find($transactionID);

		$billing->billingTransactionStatus = 0;
		$billing->save();
		
		$message = 'Transaction Updated!';
		
		redirect::to('billing/edit', $message, 'success');
	}

	public function dailyCashProcess($id = null)
	{		
		$data['siteID'] = $siteID = request::get("siteID") ? : authData('site.siteID');	
		
		$selectMonth = request::get("selectMonth");
		$selectYear = request::get("selectYear");

		$data['selectYear'] = $selectYear = $selectYear ? : input::get("year");
		$data['selectMonth'] = $selectMonth = $selectMonth ? : input::get("month");
		
		if ($siteID != ""){

		$approval = model::load('billing/approval')->getApproval($siteID, $selectMonth, $selectYear);

			if ($approval->getApprovalStatus(\model\user\user::LEVEL_SITEMANAGER) == 1){		
						
				$approvalDetail = $approval->getApprovalDetail($approval->billingApprovalID,\model\user\user::LEVEL_SITEMANAGER);
				$userDetail = model::load('user/user')->getUsersByID($approvalDetail['userID']);
								 
				$data['checked'] = 1;
				$data['checkedword'] = "Checked at ".$approvalDetail['billingApprovalLevelCreatedDate']." by ".$userDetail[$approvalDetail['userID']]['userProfileFullName'];
			}

			if ($approval->getApprovalStatus(\model\user\user::LEVEL_CLUSTERLEAD) == 1){						

				$approvalDetail = $approval->getApprovalDetail($approval->billingApprovalID,\model\user\user::LEVEL_CLUSTERLEAD);
				$userDetail = model::load('user/user')->getUsersByID($approvalDetail['userID']);
								 
				$data['approved'] = 1;
				$data['approvedword'] = "Approved at ".$approvalDetail['billingApprovalLevelCreatedDate']." by ".$userDetail[$approvalDetail['userID']]['userProfileFullName'];
			}

			if ($approval->getApprovalStatus(\model\user\user::LEVEL_FINANCIALCONTROLLER) == 1){
			
				$approvalDetail = $approval->getApprovalDetail($approval->billingApprovalID,\model\user\user::LEVEL_FINANCIALCONTROLLER);
				$userDetail = model::load('user/user')->getUsersByID($approvalDetail['userID']);

				$data['closed'] = 1;
				$data['closedword'] = "Closed at ".$approvalDetail['billingApprovalLevelCreatedDate']." by ".$userDetail[$approvalDetail['userID']]['userProfileFullName'];
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
				$data['checkedword'] = "Checked at ".$approvalDetail['billingApprovalLevelCreatedDate']." by ".$userDetail[$approvalDetail['userID']]['userProfileFullName'];

			} elseif(authData('user.userLevel') == \model\user\user::LEVEL_CLUSTERLEAD) {
				
				if (input::get("submit") == 1){

					$approval->approve(authData('user.userLevel'));		
					
					$approvalDetail = $approval->getApprovalDetail($approval->billingApprovalID,\model\user\user::LEVEL_CLUSTERLEAD);
					$userDetail = model::load('user/user')->getUsersByID($approvalDetail['userID']);
								 
					$data['approved'] = 1;
					$data['approvedword'] = "Approved at ".$approvalDetail['billingApprovalLevelCreatedDate']." by ".$userDetail[$approvalDetail['userID']]['userProfileFullName'];
				} else {
				
					$approval->reject(authData('user.userLevel'));

					$approvalDetail = $approval->getApprovalDetail($approval->billingApprovalID,\model\user\user::LEVEL_CLUSTERLEAD);
					$userDetail = model::load('user/user')->getUsersByID($approvalDetail['userID']);								
					
					$data['approvedword'] = "Rejected at ".$approvalDetail['billingApprovalLevelCreatedDate']." by ".$userDetail[$approvalDetail['userID']]['userProfileFullName'];
				}				
			} else {

				if (input::get("submit") == 1){

					$approval->approve(authData('user.userLevel'));		
					
					$approvalDetail = $approval->getApprovalDetail($approval->billingApprovalID,\model\user\user::LEVEL_FINANCIALCONTROLLER);
					$userDetail = model::load('user/user')->getUsersByID($approvalDetail['userID']);
								 
					$data['closed'] = 1;
					$data['closedword'] = "Closed at ".$approvalDetail['billingApprovalLevelCreatedDate']." by ".$userDetail[$approvalDetail['userID']]['userProfileFullName'];
				} else {
				
					$approval->disapprove(authData('user.userLevel'));

					$approvalDetail = $approval->getApprovalDetail($approval->billingApprovalID,\model\user\user::LEVEL_FINANCIALCONTROLLER);
					$userDetail = model::load('user/user')->getUsersByID($approvalDetail['userID']);								
					
					$data['closedword'] = "Rejected at ".$approvalDetail['billingApprovalLevelCreatedDate']." by ".$userDetail[$approvalDetail['userID']]['userProfileFullName'];
				}
				
					/*$approval->approve(authData('user.userLevel'));		
					
					$approvalDetail = $approval->getApprovalDetail($approval->billingApprovalID,\model\user\user::LEVEL_FINANCIALCONTROLLER);
					$userDetail = model::load('user/user')->getUsersByID($approvalDetail['userID']);
								 
					$data['closed'] = 1;
					$data['closedword'] = "Closed at ".$approvalDetail['billingApprovalLevelCreatedDate']." by ".$userDetail[$approvalDetail['userID']]['userProfileFullName'];*/
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
		
		if ($siteID != ""){
		
			$getClusterID = model::load('site/cluster')->getClusterID($siteID);
			$closingTime = model::load('site/cluster')->getTime($getClusterID[0][clusterID]);

			$balanceDebit = model::load('billing/process')->getBalanceDebit($siteID,$selectMonth-1,$selectYear);
			$balanceCredit = model::load('billing/process')->getBalanceCredit($siteID,$selectMonth-1,$selectYear);

			$data['balance'] = $balanceDebit['balance'] + $balanceCredit['balance'];
			$dateList = model::load('billing/process')->getdateList($siteID,$selectMonth,$selectYear);		

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

		foreach($res_site as $row)
		{
			$data['siteList'][$row['siteID']]	= $row['siteName'];
		}
		$todayDateStart = date('Y-m-d', strtotime($todayDateStart)); 
		$todayDateEnd = date('Y-m-d', strtotime($todayDateEnd)); 

		$data['journal'] = model::load('billing/journal')->getList($siteID,$todayDateStart,$todayDateEnd);

		$total = model::load('billing/journal')->getListTotal($siteID,$todayDateStart,$todayDateEnd);	

		foreach($total as $key => $journalTotal) {
			$checkDate = date('Y-m-d', strtotime($journalTotal['billingTransactionDate'])); 

			$data['journalDate'][$key] = $checkDate;
			$data['journalQuantity'][$key] = $journalTotal[quantity];
			$data['journalUnit'][$key] = $journalTotal[unit];
			$data['journalTotal'][$key] = $journalTotal[total];
		}

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

		$data['journal'] = model::orm('billing/journal')
				->where("siteID = '$siteID' AND billingTransactionDate >= '$todayDateStart' AND billingTransactionDate <= '$todayDateEnd' AND billingTransactionStatus = 1")
				->join("billing_item", "billing_item.billingItemID = billing_transaction.billingItemID")
				->order_by("billingTransactionDate","ASC")
				->execute();

		$startDate = date('Y-m-01', strtotime($todayDateStart)); 

		$previoussum = model::orm('billing/journal')
				->select("SUM(billingTransactionTotal) as total")
				->where("siteID = '$siteID' AND billingTransactionDate >= '$startDate' AND billingTransactionDate <= '$todayDateStart' AND billingTransactionStatus = 1")
				->join("billing_item", "billing_item.billingItemID = billing_transaction.billingItemID")
				->order_by("billingTransactionDate","ASC")
				->execute();
		
		 foreach($previoussum as $previousbalance)
		 {
		 	$data['previoussum'] = $previousbalance->total;

		 }

		view::render("shared/billing/transactionJournal", $data);
	}
}
