<?php
class Controller_Expense
{
	public function __construct()
	{
		$this->siteID = authData('user.userLevel') != 99 ? authData('site.siteID') : 0;
		$this->maxsize = 5000000;
	}

	public function add()
	{
		$selectDate = request::get('selectDate');		
		$data['selectDate'] = $selectDate = $selectDate ? : date('d F Y');
		$data['siteName'] =  authData('site.siteName');
		$data['siteManager'] = authData('user.userProfileFullName');
		
		$startDate = date('Y-m-1 00:00:00',strtotime($selectDate));
		$lastDate = date('Y-m-d 18:00:00',strtotime($selectDate));

		$currentCollection = model::load('billing/process')->getCurrentCollection(authData('site.siteID'),$startDate,$lastDate);			
		$data['currentCollection'] = number_format($currentCollection['total'], 2, '.', ''); 

		$data['listPR'] = model::load('expense/transaction')->getPRList();
		$data['prTerm'] = model::load('expense/transaction')->getPrTerm();

		$categories  = model::load('expense/category')->getList();
		
		foreach($categories as $key  => $row)
		{
			$data['categories'][$row['purchaseRequisitionCategoryId']] = $row['purchaseRequisitionCategoryName'];
		}		

		view::render("sitemanager/expense/add", $data);		
	}

	public function viewForm($prID)
	{

		$data['siteManager'] = authData('user.userProfileFullName');		
		$data['prTerm'] = model::load('expense/transaction')->getPrTerm();
		$data['prFile'] = $prFile = model::load('expense/transaction')->getPrFile($prID);

		$itemName  = model::load('expense/item')->getItemName();

		foreach($itemName as $key  => $row)
		{
			$data['itemName'][$row['purchaseRequisitionItemId']] = $row['purchaseRequisitionItemName'];
		}	

		$data['prItemList']  = model::load('expense/details')->getPrList($prID);
		$approval = model::load('expense/approval')->getStatusDetail($prID, $prFile[0]['purchaseRequisitionType']);

		if ($approval[1]['userID'] != null){ # cl
			$clusterLead = model::load('user/user')->getUsersByID($approval[1]['userID']);		
			$data['clusterLead'] = $clusterLead[$approval[1]['userID']];
			$data['clstatus'] = "Done";

			$clusterInfo  = model::load('site/cluster')->getClusterID($prFile[0]['siteID']);	
			$clusterName  = model::load('site/cluster')->get($clusterInfo[0][clusterID]);
			$data['clusterName'] = $clusterName['clusterName'];
		}

		if ($approval[2]['userID'] != null){ # om
			$opsManager = model::load('user/user')->getUsersByID($approval[2]['userID']);		
			$data['opsManager'] = $opsManager[$approval[2]['userID']];
			$data['omstatus'] = "Done";
		}		
		
		
		$data['siteName'] = model::load('site/site')->getSite($prFile[0]['siteID']);
		$data['prID'] = $prID;	

		view::render("sitemanager/expense/viewForm",$data);		
	}

	public function editForm($prID)
	{
		$data['siteManager'] = authData('user.userProfileFullName');
		$data['prTerm'] = model::load('expense/transaction')->getPrTerm();
		$data['prFile'] = model::load('expense/transaction')->getPrFile($prID);
		$data['prID'] = $prID;	

		$itemName  = model::load('expense/item')->getItemName();

		foreach($itemName as $key  => $row)
		{
			$data['itemName'][$row['purchaseRequisitionItemId']] = $row['purchaseRequisitionItemName'];
		}

		$data['prItemList']  = model::load('expense/details')->getPrList($prID);
		$data['approval'] = model::load('expense/approval')->getStatus(\model\user\user::LEVEL_SITEMANAGER, $prID);

		view::render("sitemanager/expense/editForm",$data);		
	}

	public function listItem($categoryId)
	{
		$categories  = model::load('expense/item')->getList($categoryId);
		
		foreach($categories as $key  => $row)
		{
			$data['categories'][$row['purchaseRequisitionItemId']] = $row['purchaseRequisitionItemName'];
		}

		echo json_encode($data['categories']);
	}			

	public function addPRCashAdvance($prId)
	{
		$selectDate = input::get('selectDate');		
		$data['selectDate'] = $selectDate = $selectDate ? :  date('d F Y');
		$data['siteName'] =  authData('site.siteName');
		$data['siteManager'] = authData('user.userProfileFullName');
		$data['prId'] = $prId;

		view::render("sitemanager/expense/cashAdvance", $data);		
	}

	public function editPRCashAdvance($prId)
	{
		$selectDate = input::get('selectDate');		
		$data['selectDate'] = $selectDate = $selectDate ? :  date('d F Y');
		$data['siteName'] =  authData('site.siteName');
		$data['siteManager'] = authData('user.userProfileFullName');
		$data['prId'] = $prId;
		$data['cashadvance'] = model::load('expense/cashadvance')->getCashAdvance($prId);

		view::render("sitemanager/expense/editCashAdvance", $data);		
	}

	public function viewCashAdvance($prId)
	{	
		$selectDate = input::get('selectDate');		
		$data['selectDate'] = $selectDate = $selectDate ? :  date('d F Y');
		$data['siteName'] =  authData('site.siteName');
		$data['siteManager'] = authData('user.userProfileFullName');
		$data['prId'] = $prId;	
		$data['cashadvance'] = model::load('expense/cashadvance')->getCashAdvance($prId);

		if (input::get('check') == 1) {

			redirect::to('expense/listStatus', $message, 'success');	
		}	

		view::render("sitemanager/expense/cashAdvance", $data);				
	}


	public function submitCashAdvance($prId)
	{	
		if (input::get('check') == 1){ 

			$requisition = model::orm('expense/cashadvance')->create();
				$requisition->purchaseRequisitionId = $prId;
				$requisition->purchaseRequisitionCashAdvancePurpose = input::get('purpose');
				$requisition->purchaseRequisitionCashAdvanceTotal = input::get('amount');
				$requisition->purchaseRequisitionCashAdvanceCreatedDate = now();
				$requisition->save();	

			$cashAdvanceId = $requisition->purchaseRequisitionCashAdvanceId;		

			$type = 2; # 1 = pr 2 = ca 3 = rl
			$siteID = authData('site.siteID');
			$createdDate = $requisition->purchaseRequisitionCashAdvanceCreatedDate;			
			$item = input::get('item');
			$itemCount = count($item['itemPrice']);

				for ($x = 1; $x <= $itemCount; $x++) {
   		
					foreach ($item as $key => $value) { 
			
						$itemList[$key] = $value[$x];
					}
       
					$insertTransaction = model::load('expense/cashadvance')->addTransaction($prId,$cashAdvanceId,$itemList);	
				}	

			$approval = model::load('expense/approval')->getApproval($prId, $type, $siteID, $createdDate);

			$message = 'Submitted';
			redirect::to('expense/listStatus', $message, 'success');			


		} else {

			redirect::to('expense/listStatus', $message, 'success');
		}			
	}

	public function updateCashAdvance($prId)
	{	

		$requisition = model::load('expense/cashadvance')->getCashAdvance($prId);
		//$cashAdvanceId = input::get($itemID);		

		$type = 2; # 1 = pr 2 = ca 3 = rl
		$siteID = authData('site.siteID');
		$createdDate = $requisition[0]['purchaseRequisitionCashAdvanceCreatedDate'];
		$item = input::get('item');

		$itemCount = count($item['itemPrice']);

			for ($x = 1; $x <= $itemCount; $x++) {
   		
				foreach ($item as $key => $value) { 
			
					$itemList[$key] = $value[$x];					
				}
    			
       			$insertTransaction = model::load('expense/cashadvance')->updateTransaction($prId,$itemList);	
			}	

		$approval = model::load('expense/approval')->getApproval($prId, $type, $siteID, $createdDate);

		$message = 'Submitted';
		redirect::to('expense/listStatus', $message, 'success');			
	}

	public function submitRequisition()
	{	
		$requisition = model::orm('expense/transaction')->create();
				$requisition->userID = authData('user.userID');
				$requisition->siteID = authData('site.siteID');
				$requisition->purchaseRequisitionType = input::get('prTerm1');
				$requisition->purchaseRequisitionExpenses = input::get('expenses');
				$requisition->purchaseRequisitionEquipment = input::get('equipment');
				$requisition->purchaseRequisitionEvent = input::get('event');
				$requisition->purchaseRequisitionAdhocevent = input::get('adhocevent');
				$requisition->purchaseRequisitionOther = input::get('other');
				$requisition->purchaseRequisitionCitizen = input::get('1citizen');
				$requisition->purchaseRequisitionBalance = input::get('curCollection');
				$requisition->purchaseRequisitionDeposit = input::get('balDeposit');
				$requisition->purchaseRequisitionDate = date('Y-m-d',strtotime(input::get('selectDate')));
				$requisition->purchaseRequisitionCreatedDate = now();
				$requisition->save();	

		$type = 1; # 1 = pr 2 = ca 3 = rl
		$siteID = $requisition->siteID;
		$createdDate = $requisition->purchaseRequisitionCreatedDate;
		$prID = $requisition->purchaseRequisitionId;			
		$item = input::get('item');
		$itemCount = count($item['itemCategory']);

			for ($x = 11; $x < $itemCount+11; $x++) {
   		
				foreach ($item as $key => $value) {  // start from 11
			
						$itemList[$key] = $value[$x];
				}
       
				$insertTransaction = model::load('expense/transaction')->addTransaction($prID,$itemList);	
			}	
//		
		if (input::get('check') == 2 ){

			redirect::to('expense/addPRCashAdvance/'.$prID, $message, 'success');
		
		} else {

			$approval = model::load('expense/approval')->getApproval($prID, $type, $siteID, $createdDate);
			$message = 'Submitted';
			redirect::to('expense/listStatus', $message, 'success');							
		}		
	}

	public function updateRequisition($prID)
	{	
		
		$requisition = model::orm('expense/transaction')->find($prID);
	
		$type = input::get('prType'); # 1 = pr 2 = ca 3 = rl
		$siteID = $requisition->siteID;
		$createdDate = $requisition->purchaseRequisitionCreatedDate;
		$prID = $requisition->purchaseRequisitionId;			
		$item = input::get('item');
		$itemCount = count($item['itemPrice']);
			for ($x = 1; $x <= $itemCount; $x++) {   		
				foreach ($item as $key => $value) {  // start from 11
			
					$itemList[$key] = $value[$x];
				}
       
				$insertTransaction = model::load('expense/transaction')->updateTransaction($prID,$itemList);	
			}	
//		
		if (input::get('check') == 2 ){

			redirect::to('expense/editPRCashAdvance/'.$prID, $message, 'success');
		
		} else {

			$approval = model::load('expense/approval')->getApproval($prID, $type, $siteID, $createdDate);
			$message = 'Submitted';
			redirect::to('expense/listStatus', $message, 'success');							
		}		
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

	public function viewRList($prId) # view Reconciliation List
	{ 

		$getExpenseDetails = model::orm('expense/transaction')->find($prId);

		$data['selectYear'] = $year = date('Y',strtotime($getExpenseDetails->purchaseRequisitionDate)) ? : date("Y");
		$data['selectMonth'] = $month = date('n',strtotime($getExpenseDetails->purchaseRequisitionDate)) ? : date("n");
		$data['siteName'] = model::load('site/site')->getSite($getExpenseDetails->siteID);
		$data['siteManager'] = authData('user.userProfileFullName');
		$data['prId'] = $prId;
		$clusterInfo  = model::load('site/cluster')->getClusterID($getExpenseDetails->siteID);	
		$clusterName  = model::load('site/cluster')->get($clusterInfo[0][clusterID]);
		$data['clusterName'] = $clusterName['clusterName'];

		$userID =  authData('user.userID');
		$siteID =  authData('site.siteID');

		$approval = model::load('expense/approval')->getStatusDetail($prId, 3);

		if ($approval[0]['userID'] != null){ # cl
			$data['smstatus'] = "Done";
		}

		if ($approval[1]['userID'] != null){ # cl
			$clusterLead = model::load('user/user')->getUsersByID($approval[1]['userID']);		
			$data['clusterLead'] = $clusterLead[$approval[1]['userID']];
			$data['clstatus'] = "Done";

		}

		if ($approval[2]['userID'] != null){ # om
			$opsManager = model::load('user/user')->getUsersByID($approval[2]['userID']);		
			$data['opsManager'] = $opsManager[$approval[2]['userID']];
			$data['omstatus'] = "Done";
		}	

		$data['fileList'] = model::load('expense/transaction')->getFileList($siteID, $year, $month);	
		$data['rlSummary'] = model::load('expense/transaction')->getRLSummary($siteID, $year, $month);	
		$data['totalAmount'] =  model::load('expense/transaction')->getTotalAmount($siteID, $year, $month);		

		view::render("sitemanager/expense/reconciliation", $data);			
	}

	public function viewRListSuccess($prId) # view Reconciliation List
	{ 

		$getExpenseDetails = model::orm('expense/transaction')->find($prId);

		$data['selectYear'] = $year = date('Y',strtotime($getExpenseDetails->purchaseRequisitionDate)) ? : date("Y");
		$data['selectMonth'] = $month = date('n',strtotime($getExpenseDetails->purchaseRequisitionDate)) ? : date("n");
		$data['siteName'] = model::load('site/site')->getSite($getExpenseDetails->siteID);
		$data['siteManager'] = authData('user.userProfileFullName');
		$data['prId'] = $prId;
		$clusterInfo  = model::load('site/cluster')->getClusterID($getExpenseDetails->siteID);	
		$clusterName  = model::load('site/cluster')->get($clusterInfo[0][clusterID]);
		$data['clusterName'] = $clusterName['clusterName'];

		$userID =  authData('user.userID');
		$siteID =  authData('site.siteID');

		$approval = model::load('expense/approval')->getStatusDetail($prId, 3);

		if ($approval[0]['userID'] != null){ # cl
			$data['smstatus'] = "Done";
		}

		if ($approval[1]['userID'] != null){ # cl
			$clusterLead = model::load('user/user')->getUsersByID($approval[1]['userID']);		
			$data['clusterLead'] = $clusterLead[$approval[1]['userID']];
			$data['clstatus'] = "Done";

		}

		if ($approval[2]['userID'] != null){ # om
			$opsManager = model::load('user/user')->getUsersByID($approval[2]['userID']);		
			$data['opsManager'] = $opsManager[$approval[2]['userID']];
			$data['omstatus'] = "Done";
		}	

		$data['fileList'] = model::load('expense/transaction')->getFileList($siteID, $year, $month);	
		$data['rlSummary'] = model::load('expense/transaction')->getRLSummary($siteID, $year, $month);	
		$data['totalAmount'] =  model::load('expense/transaction')->getTotalAmount($siteID, $year, $month);		

		view::render("sitemanager/expense/reconciliationSuccess", $data);			
	}

	public function fileImage($id)
	{
		$this->template = false;

		$siteID =  authData('site.siteID');
		$item = model::orm('expense/file')->where('purchaseRequisitionFileId', $id)->execute();
		$data['item'] = $item = $item->getFirst();		
		$path = path::files("site_requisition/".$siteID."/".$item->purchaseRequisitionFileId);
		$thumb = $path;

		$getInfo = getimagesize($thumb);
		header('Content-type: ' . $getInfo['mime'] . '; filename=\'$file_name\'');

		echo file_get_contents($path);
	}

	public function viewFile($id)
	{
		$this->template = false;
		$data['fileId'] = $id;
		
		view::render("sitemanager/expense/viewFile", $data);
	}
	
	public function editFile($prId,$id)
	{

		$this->template = false;

		$getExpenseDetails = model::orm('expense/transaction')->find($prId);

		$data['selectYear'] = $year = date('Y',strtotime($getExpenseDetails->purchaseRequisitionDate)) ? : date("Y");
		$data['selectMonth'] = $month = date('n',strtotime($getExpenseDetails->purchaseRequisitionDate)) ? : date("n");
		$data['fileId'] = $id;
		$data['prId'] = $prId;

		$categories  = model::load('expense/category')->getList();
		foreach($categories as $key  => $row)
		{
			$data['categories'][$row['purchaseRequisitionCategoryId']] = $row['purchaseRequisitionCategoryName'];
		}
		
		$item = model::orm('expense/file')->where('purchaseRequisitionFileId', $id)->execute();
		$data['item'] = $item->getFirst();		

		if(form::submitted())
		{	
			$fileID = $id;
		// max size
			$maxsize = $this->maxsize;
	
			$exts = array("xls","xlsx",
					"doc","docx","ppt","pptx",
					"pps","ppsx","odt","pdf",
					"png","jpeg","jpg","bmp",
					"zip","rar","mp3","m4a",
					"ogg","wav","mp4","m4v",
					"mov","wmv","avi","mpg",
					"ogv","3gp","3g2");
	
			$file	= input::file("fileUpload");
			
		if ($file == true){

			$rules	= Array(			
				"fileUpload"=>Array(
					"callback"=>Array(!$file?false:true,"Please input an upload file.")
					)
							);
	
			if($error = input::validate($rules))
			{
				input::repopulate();
				redirect::withFlash(model::load("template/services")->wrap("input-error",$error));
				redirect::to("expense/viewRList/".$prId,"Error in your form's field","error");
			}
	
			$data	= input::get();
			$data['fileType']	= $file->get("type");
	
			if(!$file->isExt($exts))
			{
				redirect::to("","List of allowed file extension : ". implode(", ", $exts), "error");
			}
	
			if($file->get('size') > $maxsize)
			{
				redirect::to("", "File size cannot be bigger than ". ($maxsize/1000) . "kb ", "error");
			}
	
			## use file name.
			if($data['fileName'] == "")
			{
				$fn	= explode(".",$file->get("name"));
				array_pop($fn);
				$data['fileName']	= implode(".",$fn);
			}
	
			$data['fileSize']	= $file->get("size");
			$data['fileExt']	= $file->get("ext");
			
			$path	= path::files("site_requisition/".$this->siteID);
	
			if(!is_dir($path))
			{
				$mkdir = mkdir($path,0775,true);
	
				if(!$mkdir)
				{
					die;
				}
			}

			$updateFile	= model::load("expense/file")->editFile($fileID,$data);
			$file->move($path."/".$fileID);
			
		} else {
			$data	= input::get();
			$fileID = $id;
			
			$updateFile = model::orm('expense/file')->find($fileID);

			$updateFile->purchaseRequisitionCategoryId = $data['itemCategory'];
			$updateFile->purchaseRequisitionFileAmount = $data['amount'];
			$updateFile->purchaseRequisitionFileGst = $data['gst'];
			$updateFile->purchaseRequisitionFileTotal = $data['total'];
			$updateFile->purchaseRequisitionFileStatus = 1;
			$updateFile->purchaseRequisitionFileUpdatedDate = now();

			$updateFile->save();

		}
		
			$message = 'Item File updated!';
	
			redirect::to('expense/viewRList/'.$prId, $message, 'success');
		}
		view::render("sitemanager/expense/editFile", $data);
	}

	public function deleteFile($id)
	{
		$this->template = false;
		$data['fileId'] = $id;

		$file = model::orm('expense/file')->find($id);

		$file->purchaseRequisitionFileStatus = 0;
		$file->save();
				
		$message = 'Item Deleted!';

		redirect::to('expense/viewRList'.$file->purchaseRequisitionId, $message, 'success');
		
		//view::render("sitemanager/requisition/viewFile", $data);
	}

	public function uploadFile($prId)
	{

		$this->template = false;

		$getExpenseDetails = model::orm('expense/transaction')->find($prId);
		$data['selectYear'] = $year = date('Y',strtotime($getExpenseDetails->purchaseRequisitionDate)) ? : date("Y");
		$data['selectMonth'] = $month = date('n',strtotime($getExpenseDetails->purchaseRequisitionDate)) ? : date("n");
		$data['siteName'] = model::load('site/site')->getSite($getExpenseDetails->siteID);
		$data['prId'] = $prID = $prId;
		$siteID =  $getExpenseDetails->siteID;

		//$prID = model::load('expense/transaction')->getPRId($siteID, $year, $month);	
		//$data['prId'] = $prID[0]['purchaseRequisitionId'];
		$categories  = model::load('expense/category')->getList();

		foreach($categories as $key  => $row)
		{
			$data['categories'][$row['purchaseRequisitionCategoryId']] = $row['purchaseRequisitionCategoryName'];
		}

		$data['categoryId'] = $id;

		if(form::submitted())
		{	

			$purchaseRequisitionId = input::get('prId');
		// max size
			$maxsize = $this->maxsize;
	
			$exts = array("xls","xlsx",
					"doc","docx","ppt","pptx",
					"pps","ppsx","odt","pdf",
					"png","jpeg","jpg","bmp",
					"zip","rar","mp3","m4a",
					"ogg","wav","mp4","m4v",
					"mov","wmv","avi","mpg",
					"ogv","3gp","3g2");
	
			$file	= input::file("fileUpload");
				
			$rules	= Array(			
				"fileUpload"=>Array(
					"callback"=>Array(!$file?false:true,"Please input an upload file.")
					)
							);
	
			if($error = input::validate($rules))
			{
				input::repopulate();
				redirect::withFlash(model::load("template/services")->wrap("input-error",$error));
				redirect::to("","Error in your form's field","error");
			}
	
			$data	= input::get();
			$data['fileType']	= $file->get("type");
	
			if(!$file->isExt($exts))
			{
				redirect::to("","List of allowed file extension : ". implode(", ", $exts), "error");
			}
	
			if($file->get('size') > $maxsize)
			{
				redirect::to("", "File size cannot be bigger than ". ($maxsize/1000) . "kb ", "error");
			}
	
			## use file name.
			if($data['fileName'] == "")
			{
				$fn	= explode(".",$file->get("name"));
				array_pop($fn);
				$data['fileName']	= implode(".",$fn);
			}
	
			$data['fileSize']	= $file->get("size");
			$data['fileExt']	= $file->get("ext");

			$fileID	= model::load("expense/file")->addFile($this->siteID,$purchaseRequisitionId,$data);
			$path	= path::files("site_requisition/".$this->siteID);
	
			if(!is_dir($path))
			{
				$mkdir = mkdir($path,0775,true);
	
				if(!$mkdir)
				{
					die;
				}
			}

			$file->move($path."/".$fileID);
			$message = 'New Item added!';
	
			redirect::to('expense/viewRList/'.$purchaseRequisitionId, $message, 'success');
		}		

		view::render("sitemanager/expense/uploadFile", $data);			
	}
}


?>