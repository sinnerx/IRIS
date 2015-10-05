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




	public function editExpenditure()
	{	
		
		$list = model::load('expense/expenditure')->getList();

		foreach ($list as $key => $value) {

			if ($value['purchaseRequisitionExpenditureSet'] == "1" ){

				$data['budgeted'][$value['purchaseRequisitionExpenditureId']]	= $value['purchaseRequisitionExpenditureName'];
			} elseif ($value['purchaseRequisitionExpenditureSet'] == "2"){

				$data['addition'][$value['purchaseRequisitionExpenditureId']]	= $value['purchaseRequisitionExpenditureName'];
			} else {

				$data['replacement'][$value['purchaseRequisitionExpenditureId']]	= $value['purchaseRequisitionExpenditureName'];
			}
		}
		
		view::render("root/expense/expenditure",$data);		
	}	


	public function editExpenditureItem($id = null)
	{	
		
		if(request::isAjax())
		{
			$this->template = false;
		}


		$row = model::load("expense/expenditure")->getItemDetail($id);
		$data['row'] = $row[0];
		
		$data['status'] = array("1"=>"Active",	
								"2"=>"Inactive");
		
		view::render("root/expense/expenditureItem",$data);
	}

	public function updateExpenditureItem($id)
	{
		# code...
		if(request::isAjax())
		{
			$this->template = false;
		}

				$data	= Array(

				"purchaseRequisitionExpenditureName" => input::get('itemName'),
				"purchaseRequisitionExpenditureStatus" => input::get('itemStatus'),
				"purchaseRequisitionExpenditureCreatedDate" => now()

						);

		model::load("expense/expenditure")->updateItem($id,$data);
		
		$row = model::load("expense/expenditure")->getItemDetail($id);
		$data['row'] = $row[0];

		$data['status'] = array("1"=>"Active",	"2"=>"Inactive");
		$message = 'Item Updated!';
				
		//	redirect::to('billing/add', $message, 'success');
		redirect::to('billing/editExpenditureItem/'.$id, $message, 'success');

	}

	public function addNewItem()
	{	

		$category = model::load('expense/category')->getList();
	
		foreach ($category as $key => $value) {

				$data['category'][$value['purchaseRequisitionCategoryId']]	= $value['purchaseRequisitionCategoryName'];
			
		}

		view::render("root/expense/addNewItem",$data);		
	}	

	public function selectItem($id = null)
	{	
		
		if(request::isAjax())
		{
			$this->template = false;
		}

		$data['row'] = model::load("expense/item")->getList($id);

		view::render("root/expense/selectItem",$data);
	}

	public function editItem($id = null)
	{	
		
		if(request::isAjax())
		{
			$this->template = false;
		}


		$row = model::load("expense/item")->getDetail($id);

		$data['row'] = $row[0];
		
		$data['status'] = array("1"=>"Active",	
								"2"=>"Inactive");
		
		view::render("root/expense/editItem",$data);
	}

	public function updateItem($id=null)
	{

		$data	= Array(

		 	"purchaseRequisitionItemName" => input::get('itemName'),
		 	"purchaseRequisitionItemStatus" => input::get('itemStatus'),
		 	"purchaseRequisitionItemCreatedDate" => now()

				);

		model::load("expense/item")->updateItem($id,$data);
			
		$message = 'Item Updated!';				
		
		redirect::to('expense/addNewItem/', $message, 'success');
	}




	public function addItem($id = null)
	{	
		
		if(request::isAjax())
		{
			$this->template = false;
		}

		$data['categoryId'] = $id;
		
		view::render("root/expense/addItem",$data);
	}

	public function saveItem($id = null)
	{	

		$data	= Array(

			"purchaseRequisitionCategoryId" => $id,
			"userID" => "99",
			"purchaseRequisitionItemName" => input::get('itemName'),
			"purchaseRequisitionItemCreatedDate" => now(),
			"purchaseRequisitionItemStatus" => "1"

						);

		model::load("expense/item")->addItem($data);
		
		$message = 'Item Inserted!';
				
		redirect::to('expense/addNewItem/', $message, 'success');
	}
	
}


?>