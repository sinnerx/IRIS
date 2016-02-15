<?php
class Controller_Expense
{
	public function __construct()
	{
	}


	public function listStatus($page=1)
	{	
		if (authData('user.userLevel') == \model\user\user::LEVEL_CLUSTERLEAD){
			
			$res_site	= model::load("site/site")->getSitesByClusterLead(session::get("userID"))->result();

			foreach($res_site as $key => $row)
			{
				$allSiteID[] = $row['siteID'];			
			}
			$level = \model\user\user::LEVEL_CLUSTERLEAD;

		} elseif (authData('user.userLevel') == \model\user\user::LEVEL_SITEMANAGER){

			$allSiteID = authData('site.siteID');
			$level = \model\user\user::LEVEL_SITEMANAGER;			

		} else {

			$allSiteID = "";			
		}
				

		$data['prTerm'] = model::load('expense/transaction')->getPrTerm();
		$data['listPR'] = model::load('expense/transaction')->getPRList($allSiteID,$level,$page);
				
		view::render("shared/expense/list",$data);		
	}


	public function listStatusRL()
	{	
		if (authData('user.userLevel') == \model\user\user::LEVEL_CLUSTERLEAD){
			
			$res_site	= model::load("site/site")->getSitesByClusterLead(session::get("userID"))->result();

			foreach($res_site as $key => $row)
			{
				$allSiteID[] = $row['siteID'];			
			}
			$level = \model\user\user::LEVEL_CLUSTERLEAD;

		} elseif (authData('user.userLevel') == \model\user\user::LEVEL_SITEMANAGER){

			$allSiteID = authData('site.siteID');
			$level = \model\user\user::LEVEL_SITEMANAGER;			

		} else {

			$allSiteID = "";			
		}
				

		if(request::get("search"))
		{
			
			$data['listRL'] = model::load('expense/transaction')->getRLList($allSiteID,$level,$page,request::get("search"));
		} else {

			$data['listRL'] = model::load('expense/transaction')->getRLList($allSiteID,$level,$page);
		}

		$data['prTerm'] = model::load('expense/transaction')->getPrTerm();
				
		view::render("shared/expense/listRL",$data);		
	}

		public function getFormSuccess($prID)
	{

		$data['opsmanager'] = authData('user.userProfileFullName');
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
		
		$approval = model::load('expense/approval')->getApprovalDetail($prID,$data['prType'],\model\user\user::LEVEL_CLUSTERLEAD);		
		$clusterlead = model::load('user/user')->getUsersByID($approval['userID']);
		$data['clusterLead'] =  $clusterlead[$approval['userID']];

		$test = model::load('site/cluster')->getClusterID($data['siteID']);			
		$clusterName = model::load('site/cluster')->get($test[0]['clusterID']);
		$data['clusterName'] = $clusterName['clusterName'];	

		$data['prItemList']  = model::load('expense/details')->getPrList($prID);
		$itemList  = model::load('expense/item')->getItemName();

		foreach($itemList as $key  => $row)
			{
				$data['itemList'][$row['purchaseRequisitionItemId']] = $row['purchaseRequisitionItemName'];
			}
		
		$data['prID'] = $prID;

		view::render("shared/expense/viewform",$data);		
	}

	public function viewRListSuccess($prId) # view Reconciliation List
	{ 

	
		$data['opsManager'] = authData('user.userProfileFullName');
		$getExpenseDetails = model::orm('expense/transaction')->find($prId);
		
		$data['prNo'] = $getExpenseDetails->purchaseRequisitionNumber;
		$data['selectYear'] = $year = date('Y',strtotime($getExpenseDetails->purchaseRequisitionDate)) ? : date("Y");
		$data['selectMonth'] = $month = date('n',strtotime($getExpenseDetails->purchaseRequisitionDate)) ? : date("n");
		$data['siteName'] = model::load('site/site')->getSite($getExpenseDetails->siteID);
		$siteManager = model::load('user/user')->getUsersByID($getExpenseDetails->userID);	
		$data['siteManager'] = $siteManager[$getExpenseDetails->userID];	
		$data['prId'] = $prId;		

		$userID =  authData('user.userID');
		$siteID =  $getExpenseDetails->siteID;

		$approval = model::load('expense/approval')->getApprovalDetail($prId,3,\model\user\user::LEVEL_CLUSTERLEAD);		
		$clusterlead = model::load('user/user')->getUsersByID($approval['userID']); 
		$data['clusterLead'] =  $clusterlead[$approval['userID']];

		$test = model::load('site/cluster')->getClusterID($siteID);			
		$clusterName = model::load('site/cluster')->get($test[0]['clusterID']);
		$data['clusterName'] = $clusterName['clusterName'];

		$fcApproval = model::load('expense/approval')->getApprovalDetail($prId,3,\model\user\user::LEVEL_FINANCIALCONTROLLER);	
		$data['fcApprove'] = $fcApproval['expenseApprovalLevelStatus'];

		$data['fileList'] = model::load('expense/transaction')->getFileList($siteID, $year, $month);	
		$data['rlSummary'] = model::load('expense/transaction')->getRLSummary($siteID, $year, $month);	
		$data['totalAmount'] =  model::load('expense/transaction')->getTotalAmount($siteID, $year, $month);		

		view::render("shared/expense/reconciliationSuccess", $data);			
	}

	public function viewFile($id)
	{
		$this->template = false;
		$data['fileId'] = $id;
		
		view::render("shared/expense/viewFile", $data);
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


	public function fcClose($prId)
	{
		$getExpenseDetails = model::orm('expense/transaction')->find($prId);

		$type = 3; # 1 = pr 2 = ca 3 = rl
		$siteID = input::get('siteID');
		$createdDate = now();

		$approval = model::load('expense/approval')->getApproval($prId, $type, $siteID, $createdDate);

		$fcSite	= Array(

			"quantity" => 1,
			"unit" => 1,
			"total" => input::get('total'),
			"description" => "RL for PR No = ".$getExpenseDetails->purchaseRequisitionNumber,
			"type" => 2,
			"selectDate" => now()

					);

		$getTransactionID = model::load('billing/billing')->addTransaction(input::get('siteID'),15,$fcSite);			

		$message = 'Closed by Financial Controller';
		redirect::to('expense/listStatusRL', $message, 'success');
	}


# generate RL report by PR number
	public function generateRLReport($prNumber,$prID)
	{
		
		$item = model::load('expense/file')->getFileDetail($prID);
		$item = $item[0];		
		$siteID = $item['siteID'];

		$folderpath = path::files('site_requisition/'.$siteID);


		if(!is_dir($folderpath))
		mkdir($folderpath);		 			
			
			$word	= new \PhpOffice\PhpWord\PhpWord();	
			
			$word->addFontStyle('fStyle', array('name' => 'Calibri', 'size' => 11));
			$word->addFontStyle('fStyleB', array('name' => 'Calibri', 'size' => 11,'bold' => true));
			
			$word->addParagraphStyle('pStyle', array('align' => 'center', 'spaceAfter' => 3));
		

    		$section = $word->addSection();				
			$section->addImage(url::asset("backend/images/nusuara-logo.jpg"), array('width' => 210, 'height' => 110, 'align' => 'center'));

			$companyName 	=	"NUSUARA TECHNOLOGIES SDN BHD (599840-M)";
			$address1 		=	"Unit No. 2-19-01, Block 2, VSQ @ PJ City Centre, Jalan Utara";
			$address2 		=	"46200 Petaling Jaya, Selangor Darul Ehsan";
			$address3 		=	"Tel : +60 (3) 7451 8080               Fax : +60 (3) 7451 8081";
			
			$section->addText(htmlspecialchars($companyName), 'fStyle', 'pStyle');
			$section->addText(htmlspecialchars($address1), 'fStyle', 'pStyle');
			$section->addText(htmlspecialchars($address2), 'fStyle', 'pStyle');
			$section->addText(htmlspecialchars($address3), 'fStyle', 'pStyle');
			
  			$word->addParagraphStyle('pStyle1', array( 'spaceAfter' => 0, 'spaceBefore' => 0));		
				  	
			$line1 = "________________________________________________________________________________";
			$section->addText(htmlspecialchars($line1),  null, null);
			$title = "RECONCILIATION LIST - Slip of Payment/Bill/Receipt";
			$section->addText(htmlspecialchars($title),  array('bold' => true), 'pStyle1');
			$section->addText(htmlspecialchars($line1),  null, null);

#----------------------------------------
			$test = model::load('site/cluster')->getClusterID($siteID);			
			$clusterName = model::load('site/cluster')->get($test[0]['clusterID']);
			$clusterName = $clusterName['clusterName'];

			$getExpenseDetails = model::orm('expense/transaction')->find($prID);		
			$prNo = $getExpenseDetails->purchaseRequisitionNumber;
			$year = date('Y',strtotime($getExpenseDetails->purchaseRequisitionDate));
			$month = date('n',strtotime($getExpenseDetails->purchaseRequisitionDate));
			$siteName = model::load('site/site')->getSite($getExpenseDetails->siteID);

			$totalAmount =  model::load('expense/transaction')->getTotalAmount($siteID, $year, $month);		
			$fileList = model::load('expense/transaction')->getFileList($siteID, $year, $month);	
#----------------------------------------


			$cluster = "Cluster : ".$clusterName;
			$month1 = "Month : ".model::load("helper")->monthYear("monthE")[$month]."  ".model::load("helper")->monthYear("year")[$year]; 
			$pi1m = "PI1M : ".$siteName['siteName'];
			$prNo1 = "PR Number : ".$prNo;

			$section->addText(htmlspecialchars($cluster), 'fStyle', array('spaceAfter' => 6));
			$section->addText(htmlspecialchars($month1), 'fStyle', array('spaceAfter' => 6));
			$section->addText(htmlspecialchars($pi1m), 'fStyle', array('spaceAfter' => 6));
			$section->addText(htmlspecialchars($prNo1), 'fStyle', array('spaceAfter' => 6));

			$section->addTextBreak(1);
/*    Start part table :  particular detail   */
			
			$table = $section->addTable('Fancy Table');
			$styleCell = array('valign' => 'top', 'spaceAfter' => 0,'borderSize' => 6, 'borderColor' => '999999');

			$table->addRow();
			$table->addCell(500, $styleCell)->addText(htmlspecialchars('No.'),'fStyleB');
			$table->addCell(3000, $styleCell)->addText(htmlspecialchars('Category'),'fStyleB');
			$table->addCell(6500, $styleCell)->addText(htmlspecialchars('Particular'),'fStyleB');
			$table->addCell(2000, $styleCell)->addText(htmlspecialchars('Amount (RM)'),'fStyleB');
			$table->addCell(2000, $styleCell)->addText(htmlspecialchars('GST Amount (RM)'),'fStyleB');
			$table->addCell(2000, $styleCell)->addText(htmlspecialchars('Total Amount (RM)'),'fStyleB');

			foreach ($fileList as $key => $category)	{

			$table->addRow();
			$table->addCell(500, $styleCell)->addText(htmlspecialchars($key + 1),'fStyle');
			$table->addCell(3000, $styleCell)->addText(htmlspecialchars($category['purchaseRequisitionCategoryName']),'fStyleB');

			$cell2 = $table->addCell(6500, $styleCell);
			$cell2->addImage(path::files("site_requisition/".$siteID."/".$category['purchaseRequisitionFileId']), 
				array('width' => 250, 'height' => 250, 'align' => 'center'));
			
			$table->addCell(2000, $styleCell)->addText(htmlspecialchars($category['purchaseRequisitionFileAmount']),'fStyle');
			$table->addCell(2000, $styleCell)->addText(htmlspecialchars($category['purchaseRequisitionFileGst']),'fStyle');
			$table->addCell(2000, $styleCell)->addText(htmlspecialchars($category['purchaseRequisitionFileTotal']),'fStyle');

  			}

            $table->addRow();
			$table->addCell(500, $styleCell)->addText(null,'fStyle');
			$table->addCell(3000, $styleCell)->addText(null,'fStyle');
			$table->addCell(6500, $styleCell)->addText(null,'fStyle'); 
			$table->addCell(2000, $styleCell)->addText(null,'fStyle');
			$table->addCell(2000, $styleCell)->addText(htmlspecialchars('Total Amount :'),'fStyleB');
			$table->addCell(2000, $styleCell)->addText(htmlspecialchars(number_format($totalAmount['totalAmount'],2,'.','')),'fStyleB');      

			$section->addTextBreak(2);

/*    End part table */
				

/*    Start part table :  particular summary   */

			$rlSummary = model::load('expense/transaction')->getRLSummary($siteID, $year, $month);	


			$title1 = "RECONCILIATION LIST - Summary";
			$section->addText(htmlspecialchars($title1),  array('bold' => true), 'pStyle1');


			$table = $section->addTable('Fancy Table');
			$styleCell = array('valign' => 'center', 'spaceAfter' => 0,'borderSize' => 6, 'borderColor' => '999999');

			$table->addRow();         
			$table->addCell(500, $styleCell)->addText(htmlspecialchars('No.'),'fStyleB');
			$table->addCell(8000, $styleCell)->addText(htmlspecialchars('Category'),'fStyleB');
			$table->addCell(2000, $styleCell)->addText(htmlspecialchars('Total Amount (RM)'),'fStyleB');


			foreach ($rlSummary as $key => $category){     

			$table->addRow();
			$table->addCell(500, $styleCell)->addText(htmlspecialchars($key + 1),'fStyle');
			$table->addCell(8000, $styleCell)->addText(htmlspecialchars($category['purchaseRequisitionCategoryName']),'fStyle');
			$table->addCell(2000, $styleCell)->addText(htmlspecialchars(number_format($category['amount'],2,'.','')),'fStyle');

			}

			$section->addTextBreak(2);	
/*    End part table */


/*    Start part table :  verification   */
		
#----------------------
			//$siteName = model::load('site/site')->getSite($getExpenseDetails->siteID);
			$approval = model::load('expense/approval')->getStatusDetail($prID, 3);

			$siteManager = model::load('user/user')->getUsersByID($getExpenseDetails->userID);	
			$siteManager = $siteManager[$getExpenseDetails->userID];	

			$clusterLead = model::load('user/user')->getUsersByID($approval[1]['userID']);		
			$clusterLead = $clusterLead[$approval[1]['userID']];

			$opsManager = model::load('user/user')->getUsersByID($approval[2]['userID']);		
			$opsManager = $opsManager[$approval[2]['userID']];
#----------------------

			$table = $section->addTable('Fancy Table');
			$styleCell = array('valign' => 'center', 'spaceAfter' => 0);

			$table->addRow();
			$table->addCell(4000, $styleCell)->addText(htmlspecialchars('Prepared by :'),'fStyle');
			$table->addCell(4000, $styleCell)->addText(htmlspecialchars('Verified by :'),'fStyle');
			$table->addCell(4000, $styleCell)->addText(htmlspecialchars('Approved by :'),'fStyle');
			$table->addCell(4000, $styleCell)->addText(htmlspecialchars('Closed by :'),'fStyle');

			$table->addRow();   // Nama
			$table->addCell(4000, $styleCell)->addText(htmlspecialchars($siteManager['userProfileFullName']),'fStyleB');
			$table->addCell(4000, $styleCell)->addText(htmlspecialchars($clusterLead['userProfileFullName']),'fStyleB');
			$table->addCell(4000, $styleCell)->addText(htmlspecialchars($opsManager['userProfileFullName']),'fStyleB');
			$table->addCell(4000, $styleCell)->addText(htmlspecialchars('Finance'),'fStyleB');

			$table->addRow();	// jawatan
			$table->addCell(4000, $styleCell)->addText(htmlspecialchars('Manager'),'fStyle');
			$table->addCell(4000, $styleCell)->addText(htmlspecialchars('Cluster Lead'),'fStyle');
			$table->addCell(4000, $styleCell)->addText(htmlspecialchars('Operations Manager'),'fStyle');
			$table->addCell(4000, $styleCell)->addText(htmlspecialchars('Financial Controller'),'fStyle');

			$table->addRow();   // Lokasi
			$table->addCell(4000, $styleCell)->addText(htmlspecialchars($siteName['siteName']),'fStyle');
			$table->addCell(4000, $styleCell)->addText(htmlspecialchars($clusterName),'fStyle');
			$table->addCell(4000, $styleCell)->addText(htmlspecialchars('Semenanjung Malaysia'),'fStyle');
			$table->addCell(4000, $styleCell)->addText(htmlspecialchars('Malaysia'),'fStyle');

			$section->addTextBreak(2);

/*    End part table */

			$title2 = "Disclaimer : This is a computer-generated document and it does not require a signature";
			$section->addText(htmlspecialchars($title2),  array('bold' => true), 'pStyle1');

			$fileName = $prNo.".docx";   // pr no
		
			$writer = \PhpOffice\PhpWord\IOFactory::createWriter($word, 'Word2007');
			$writer->save($folderpath.'/'.$fileName);

			$filePath = $folderpath.'/'.$fileName;

		header('Content-Description: File Transfer');
		header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
		header('Content-Disposition: attachment; filename="'.$fileName.'"');
		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');
		header('Content-Length: ' . filesize($filePath));

		readfile($filePath);

		die;
	}

}


?>