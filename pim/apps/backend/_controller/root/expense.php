<?php
class Controller_Expense
{
	public function __construct()
	{
		$this->siteID = authData('user.userLevel') != 99 ? authData('site.siteID') : 0;
		$this->maxsize = 5000000;
	}


	public function listStatus($page=1)
	{	
				
		$data['prTerm'] = model::load('expense/transaction')->getPrTerm();
		$data['listPR'] = model::load('expense/transaction')->getPRList($allSiteID,$level,$page);

		view::render("root/expense/list",$data);		
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
			}

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
		
		view::render("operationmanager/expense/prform",$data);		
	}

	public function submitPrNumber()
	{	

		$prID = input::get('prID');
		$prNumber = input::get('prNumber');
		
		$requisition = model::orm('expense/transaction')->find($prID[key(input::get('submit'))]);
			
			$requisition->purchaseRequisitionNumber = $prNumber[key(input::get('submit'))];
			$requisition->purchaseRequisitionUpdatedDate = now();
			$requisition->save();		
	
		$message = 'Submitted';
		redirect::to('expense/listStatus', $message, 'success');			
	}	

	
}


?>