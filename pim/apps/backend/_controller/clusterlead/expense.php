<?php
class Controller_Expense
{
	public function __construct()
	{
		$this->siteID = authData('user.userLevel') != 99 ? authData('site.siteID') : 0;
		$this->maxsize = 5000000;
	}

	public function listStatus()
	{					
		redirect::to('expense/listStatus', $message, 'success');	
	}

	public function getForm($prID)
	{

		$data['clusterLead'] = authData('user.userProfileFullName');
		$data['prTerm'] = model::load('expense/transaction')->getPrTerm();

		$data['prFile'] = $prFile  = model::load('expense/transaction')->getPrFile($prID);

		foreach($prFile as $key  => $row)
			{
				$data['prDate'] = $selectDate = $row['purchaseRequisitionDate'];
				$data['prType'] = $row['purchaseRequisitionType'];
				$data['prNo'] = $row['purchaseRequisitionNumber'];
				$data['siteID'] = $siteID = $row['siteID'];
				$data['deposit']  = $row['purchaseRequisitionDeposit'];
				$userID = $row['userID'];
			}

		$siteManager = model::load('user/user')->getUsersByID($userID);	
		$data['siteManager'] = $siteManager[$userID];	

		$clusterInfo  = model::load('site/cluster')->getClusterID($data['siteID']);	

		foreach($clusterInfo as $key  => $row)
			{
				$clusterID = $row['clusterID'];
			}

		$startDate = date('Y-m-1 00:00:00',strtotime($selectDate));
		$lastDate = date('Y-m-d 18:00:00',strtotime($selectDate));

		$currentCollection = model::load('billing/process')->getCurrentCollection($siteID,$startDate,$lastDate);			
		$data['currentCollection'] = number_format($currentCollection['total'], 2, '.', ''); 
			
		$data['siteName'] = model::load('site/site')->getSite($data['siteID']);
		$clusterName  = model::load('site/cluster')->get($clusterID);
		$data['clusterName'] = $clusterName['clusterName'];		
		$data['prItemList']  = model::load('expense/details')->getPrList($prID);
		$itemList  = model::load('expense/item')->getItemName();

		foreach($itemList as $key  => $row)
			{
				$data['itemList'][$row['purchaseRequisitionItemId']] = $row['purchaseRequisitionItemName'];
			}
		
		$data['prID'] = $prID;

		view::render("clusterlead/expense/prform",$data);		
	}

	public function getFormSuccess($prID)
	{

		$data['clusterLead'] = authData('user.userProfileFullName');
		$data['prTerm'] = model::load('expense/transaction')->getPrTerm();

		$data['prFile'] = $prFile  = model::load('expense/transaction')->getPrFile($prID);

		foreach($prFile as $key  => $row)
			{
				$data['prDate'] = $selectDate = $row['purchaseRequisitionDate'];
				$data['prType'] = $row['purchaseRequisitionType'];
				$data['prNo'] = $row['purchaseRequisitionNumber'];
				$data['siteID'] = $siteID = $row['siteID'];
				$data['deposit']  = $row['purchaseRequisitionDeposit'];
				$userID = $row['userID'];
			}

		$siteManager = model::load('user/user')->getUsersByID($userID);	
		$data['siteManager'] = $siteManager[$userID];	

		$clusterInfo  = model::load('site/cluster')->getClusterID($data['siteID']);	

		foreach($clusterInfo as $key  => $row)
			{
				$clusterID = $row['clusterID'];
			}

		$startDate = date('Y-m-1 00:00:00',strtotime($selectDate));
		$lastDate = date('Y-m-d 18:00:00',strtotime($selectDate));

		$currentCollection = model::load('billing/process')->getCurrentCollection($siteID,$startDate,$lastDate);			
		$data['currentCollection'] = number_format($currentCollection['total'], 2, '.', ''); 
			
		$data['siteName'] = model::load('site/site')->getSite($data['siteID']);
		$clusterName  = model::load('site/cluster')->get($clusterID);
		$data['clusterName'] = $clusterName['clusterName'];		
		$data['prItemList']  = model::load('expense/details')->getPrList($prID);
		$itemList  = model::load('expense/item')->getItemName();

		foreach($itemList as $key  => $row)
			{
				$data['itemList'][$row['purchaseRequisitionItemId']] = $row['purchaseRequisitionItemName'];
			}
		
		$data['prID'] = $prID;

		view::render("clusterlead/expense/viewform",$data);		
	}

	public function submitRequisition($prID)
	{	
		$requisition = model::orm('expense/transaction')->find($prID);

		$type = input::get('prTerm');
		$siteID = $requisition->siteID;
		$createdDate = now();		
		$item = input::get('item');
		$itemCount = count($item['itemPrice']);

			for ($x = 1; $x <= $itemCount; $x++) {  
				foreach ($item as $key => $value) { 
			
						$itemList[$key] = $value[$x];
					}
       
				$insertTransaction = model::load('expense/transaction')->updateTransaction($prID,$itemList);					
			}	

		if (input::get('submit') == 1){
			$approval = model::load('expense/approval')->getApproval($prID, $type, $siteID, $createdDate);	
		
		} elseif (input::get('submit') == 2){ 
			$approval = model::load('expense/approval')->getDisapproval($prID, $type, $siteID, $createdDate);	
		
		} elseif (input::get('submit') == 3){ 
			redirect::to('expense/viewCashAdvance/'.$prID, $message, 'success');
		}

		$message = 'Submitted';
		redirect::to('expense/listStatus', $message, 'success');			
	}	

	public function viewCashAdvance($prId)
	{	
		$requisition = model::orm('expense/transaction')->find($prId);

		$selectDate = $requisition->purchaseRequisitionDate;
		$data['selectDate'] = $selectDate;

		$data['siteName'] = model::load('site/site')->getSite($requisition->siteID);		
		$siteManager = model::load('user/user')->getUsersByID($requisition->userID);	
		$data['siteManager'] = $siteManager[$requisition->userID];
		$data['prId'] = $prId;	
		$data['cashadvance'] = model::load('expense/cashadvance')->getCashAdvance($prId);

		view::render("clusterlead/expense/cashAdvance", $data);				
	}

	public function submitCashAdvance($prID)
	{			
		$requisition = model::orm('expense/transaction')->find($prID);
		
		$type = 2; # 1 = pr 2 = ca 3 = rl
		$siteID = $requisition->siteID;
		$createdDate = $requisition->purchaseRequisitionCreatedDate;			

			if (input::get('submit') == 1){
				$approval = model::load('expense/approval')->getApproval($prID, $type, $siteID, $createdDate);	
		
			} else if (input::get('submit') == 2){ 
				$approval = model::load('expense/approval')->getDisapproval($prID, $type, $siteID, $createdDate);	
		
			}		

		$message = 'Submitted';
		redirect::to('expense/listStatus', $message, 'success');			

	}

	public function viewRList($prId) # view Reconciliation List
	{ 

		$data['clusterLead'] = authData('user.userProfileFullName');
		$getExpenseDetails = model::orm('expense/transaction')->find($prId);

		$data['selectYear'] = $year = date('Y',strtotime($getExpenseDetails->purchaseRequisitionDate)) ? : date("Y");
		$data['selectMonth'] = $month = date('n',strtotime($getExpenseDetails->purchaseRequisitionDate)) ? : date("n");
		$data['siteName'] = model::load('site/site')->getSite($getExpenseDetails->siteID);
		$siteManager = model::load('user/user')->getUsersByID($getExpenseDetails->userID);	
		$data['siteManager'] = $siteManager[$getExpenseDetails->userID];	
		$data['prId'] = $prId;		

		$userID =  authData('user.userID');
		$siteID =  $getExpenseDetails->siteID;

		$test = model::load('site/cluster')->getClusterID($siteID);			
		$clusterName = model::load('site/cluster')->get($test[0]['clusterID']);
		$data['clusterName'] = $clusterName['clusterName'];

		$data['fileList'] = model::load('expense/transaction')->getFileList($siteID, $year, $month);	
		$data['rlSummary'] = model::load('expense/transaction')->getRLSummary($siteID, $year, $month);	
		$data['totalAmount'] =  model::load('expense/transaction')->getTotalAmount($siteID, $year, $month);		

		view::render("clusterlead/expense/reconciliation", $data);			
	}

	public function viewRListSuccess($prId) # view Reconciliation List
	{ 

		$data['clusterLead'] = authData('user.userProfileFullName');
		$getExpenseDetails = model::orm('expense/transaction')->find($prId);

		$data['selectYear'] = $year = date('Y',strtotime($getExpenseDetails->purchaseRequisitionDate)) ? : date("Y");
		$data['selectMonth'] = $month = date('n',strtotime($getExpenseDetails->purchaseRequisitionDate)) ? : date("n");
		$data['siteName'] = model::load('site/site')->getSite($getExpenseDetails->siteID);
		$siteManager = model::load('user/user')->getUsersByID($getExpenseDetails->userID);	
		$data['siteManager'] = $siteManager[$getExpenseDetails->userID];	
		$data['prId'] = $prId;		

		$userID =  authData('user.userID');

		$approval = model::load('expense/approval')->getStatusDetail($prId, 3);
		$opsManager = model::load('user/user')->getUsersByID($approval[2]['userID']);		
		$data['opsManager'] = $opsManager[$approval[2]['userID']];
		
		$siteID =  $getExpenseDetails->siteID;

		$test = model::load('site/cluster')->getClusterID($siteID);			
		$clusterName = model::load('site/cluster')->get($test[0]['clusterID']);
		$data['clusterName'] = $clusterName['clusterName'];

		$data['fileList'] = model::load('expense/transaction')->getFileList($siteID, $year, $month);	
		$data['rlSummary'] = model::load('expense/transaction')->getRLSummary($siteID, $year, $month);	
		$data['totalAmount'] =  model::load('expense/transaction')->getTotalAmount($siteID, $year, $month);		

		view::render("clusterlead/expense/reconciliationSuccess", $data);			
	}

	public function viewFile($id)
	{
		$this->template = false;
		$data['fileId'] = $id;
		
		view::render("clusterlead/expense/viewFile", $data);
	}

	public function fileImage($id)
	{
		$this->template = false;

		$item = model::orm('expense/file')->where('purchaseRequisitionFileId', $id)->execute();
		$data['item'] = $item = $item->getFirst();	
		$siteID = $item->siteID;

		$path = path::files("site_requisition/".$siteID."/".$item->purchaseRequisitionFileId);
		$thumb = $path;

		$getInfo = getimagesize($thumb);
		header('Content-type: ' . $getInfo['mime'] . '; filename=\'$file_name\'');

		echo file_get_contents($path);
	}

	public function submitReconciliation($prID)
	{	

		$reconciliation = model::orm('expense/transaction')->find($prID);
		$type = 3; # 1 = pr 2 = ca 3 = rl
		$siteID = $reconciliation->siteID;
		$createdDate = $reconciliation->purchaseRequisitionCreatedDate;
		if (input::get('check') == 2 ) {
			redirect::to('expense/listStatus', $message, 'success');												
		}
		$approval = model::load('expense/approval')->getApproval($prID, $type, $siteID, $createdDate);
		$message = 'Submitted';
		redirect::to('expense/listStatus', $message, 'success');									
	}

}

?>