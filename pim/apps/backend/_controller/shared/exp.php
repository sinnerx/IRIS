<?php
class Controller_Exp
{
	public function prList($page = 1)
	{

		$pr = model::orm('expense/pr/pr')->where('prStatus !=', 3);

		if(request::get('status'))
		{
			session::set('prFilter.status', request::get('status'));
		}

		if(isset($_GET['month']))
		{
			session::set('prFilter.month', request::get('month'));
		}

		if(request::get('year'))
			session::set('prFilter.year', request::get('year'));		

		if(request::get('site'))
			session::set('prFilter.site', request::get('site'));

		if(!(request::get('site'))){
			session::set('prFilter.site', '');
			session::set('prFilter.siteName', '');
		}
								

		if(request::get('siteName'))
			session::set('prFilter.siteName', request::get('siteName'));		

		//var_dump(request::get('cluster'));

		if(request::get('cluster'))
			session::set('prFilter.cluster', request::get('cluster'));

		//else
		//	session::set('prFilter.cluster', '');

		$data['status'] 	= session::get('prFilter.status', 'pending');
		$data['month'] 		= session::get('prFilter.month', date('n'));
		$data['year'] 		= session::get('prFilter.year', date('Y'));
		$data['cluster'] 	= session::get('prFilter.cluster');
		$data['site'] 		= session::get('prFilter.site', '');
		$data['siteName'] 		= session::get('prFilter.siteName', '');

		//var_dump(request::get('year'));
		//var_dump($data['site']);

		// month query.
		if($data['month'])
			$pr->where('MONTH(prDate)', $data['month']);

		if($data['year'])
			$pr->where('YEAR(prDate)', $data['year']);

		// either pending or rejected and Not finance.
		if(!user()->isFinancialController())
		{
			if($data['status'] == 'closed')
				$pr->where('prStatus', 1);
			else if($data['status'] != 'all')
				$pr->where('prStatus', array(0, 2));
		}

		if($data['cluster'] > 0){
			$pr->where('pr.siteID IN (SELECT siteID FROM cluster_site WHERE clusterID = ?) ', array($data['cluster']) );
			//$pr->execute();			
			//var_dump($pr);
			//die;
		}

		if($data['site'] != '')
			$pr->where('pr.siteID', $data['site']);

		if(user()->isManager())
		{
			$siteManager = user()->getSiteManager();

			$pr->where('pr.siteID', $siteManager->siteID);
		}
		else if(user()->isClusterLead())
		{
			$pr->where('pr.siteID IN (SELECT siteID FROM cluster_site WHERE clusterID IN (SELECT clusterID FROM cluster_lead WHERE userID = ?))', array(user()->userID));
		}
		else if(user()->isOperationManager())
		{
			// only for his cluster.
			$pr->where('pr.siteID IN (SELECT siteID FROM cluster_site WHERE clusterID IN (SELECT clusterID FROM cluster_opsmanager WHERE userID = ?))', array(user()->userID));
		}
		// only shown for pr with prNumber, and status is 1 (closed)
		else if(user()->isFinancialController())
		{
			$pr->where('prType', 2); // cash advance.
			$pr->where('prNumber != ? AND prStatus = ?', array('', 1));
		}

		if(!user()->isManager())
			$pr->join('site', 'pr.siteID = site.siteID');


		
		// pagination
		pagination::setFormat(model::load("template/cssbootstrap")->paginationLink());

		$pr->paginate(array(
			'urlFormat' => url::base('exp/prList/{page}'),
			'currentPage' => $page,
			'limit' => 10
			));

		$pr->order_by('prUpdatedDate DESC');

		$data['pr'] = $pr->execute();

		view::render('shared/exp/prList', $data);
	}

	public function prView($id)
	{
		$data['pr'] = model::orm('expense/pr/pr')->where('prID', $id)->execute()->getFirst();

		$prItems = model::orm('expense/pr/item')->where('prID', $id)->execute();

		// group pr_item by category
		$newPrItems = array();

		foreach($prItems as $prItem)
		{
			if(!isset($newPrItems[$prItem->expenseCategoryID]))
				$newPrItems[$prItem->expenseCategoryID] = array();

			$newPrItems[$prItem->expenseCategoryID][] = $prItem;
		}

		foreach(orm('expense/expense_expenditure')->execute() as $expenseExpenditure)
			$data[$expenseExpenditure->expenseExpenditureSet][$expenseExpenditure->expenseExpenditureID] = $expenseExpenditure->expenseExpenditureName;

		$data['expenditures'] = orm('expense/pr/expenditure')->where('prID', $id)->execute()->toList('expenseExpenditureID', 'prExpenditureID');

		$data['prItems'] = $newPrItems;

		// approvals
		$data['approvals'] = model::orm('expense/pr/approval')->where('prID', $id)->execute();

		view::render('shared/exp/prView', $data);
	}

	public function prEdit($prID)
	{
		if(form::submitted())
			return $this->prEditSubmit($prID);

		$data['pr'] = $pr = model::orm('expense/pr/pr')->where('prID', $prID)->execute()->getFirst();

		$data['isEditable'] = $pr->isPendingFor(user());
		$data['disabled'] = $data['isEditable'] ? '' : 'disabled';

		$data['collection'] = array(
			'total' => $data['pr']->prBalance,
			'date' => date('g:ia, d F Y', strtotime($data['pr']->prBalanceDate))
			);

		$selectDate = request::get('selectDate', date('d F Y', strtotime($pr->prDate)));
		$data['selectDate'] = date('d F Y', strtotime($selectDate));

		$startDate = date('Y-m-1 00:00:00',strtotime($selectDate));
		$lastDate = date('Y-m-d 18:00:00',strtotime($selectDate));

		if(request::get('selectDate'))
		{
			$currentCollection = model::load('billing/process')->getCurrentCollection(authData('site.siteID'), $startDate, $lastDate); 

			$data['collection']['date'] = date('g:ia, d F Y', strtotime($lastDate));
			$data['collection']['total'] = number_format($currentCollection['total'], 2, '.', '');
		}
		// $data['selectDate'] = $selectDate = $selectDate ? : date('d F Y');

		$data['siteName'] =  authData('site.siteName');
		$data['siteManager'] = authData('user.userProfileFullName');

		$data['prTerm'] = model::load('expense/transaction')->getPrTerm();

		// $currentCollection = model::load('billing/process')->getCurrentCollection(authData('site.siteID'), $startDate, $lastDate);			
		// $data['currentCollection'] = number_format($currentCollection['total'], 2, '.', ''); 

		// expenditure
		$expenditures = model::orm('expense/expense_expenditure')->execute();

		foreach($expenditures as $expenditure)
		{
			$id = $expenditure->expenseExpenditureID;
			$name = $expenditure->expenseExpenditureName;

			if($expenditure->isBudgeted())
				$data['budgeted'][$id] = $name;
			else if($expenditure->isAddition())
				$data['addition'][$id] = $name;
			else if($expenditure->isReplacement())
				$data['replacement'][$id] = $name;
		}

		// selected expenditure
		$data['expenditures'] = orm('expense/pr/expenditure')->where('prID', $prID)->execute()->toList('expenseExpenditureID', 'prExpenditureID');

		// category
		$categories = model::orm('expense/expense_category')->execute();
		
		foreach($categories as $category)
			$data['categories'][$category->expenseCategoryID] = $category->expenseCategoryName;

		$data['prItems'] = model::orm('expense/pr/item')->where('prID', $prID)->execute();

		view::render('shared/exp/prEdit', $data);
	}

	/**
	 * Submit pr edit
	 * @param int prID
	 */
	protected function prEditSubmit($prID)
	{
		$pr = model::orm('expense/pr/pr')->find($prID);
		/*$pr->prExpense = input::get('budgeted1');
		$pr->prEquipment = input::get('budgeted2');
		$pr->prEvent = input::get('addition1');
		$pr->prAdhocevent = input::get('addition2');
		$pr->prOther = input::get('replacement1');
		$pr->prCitizen = input::get('replacement2');*/
		$pr->prTotal = input::get('total');
		$pr->prBalance = input::get('curCollection');
		$pr->prDeposit = input::get('balDeposit');
		$pr->prDate = date('Y-m-d', strtotime(input::get('selectDate')));
		$pr->prUpdatedDate = now();
		$pr->prUpdatedUser = user()->userID;
		$pr->save();

		$existing = input::get('existing');
		
		$prItems = model::orm('expense/pr/item')->where('prID', $prID)->execute();

		// loop and update record (or delete)
		foreach($prItems as $prItem)
		{
			// delete on no-longer exists record on update
			if(!isset($existing[$prItem->prItemID]))
			{
				$prItem->delete();
			}
			// update pr item
			else
			{
				$existingData = $existing[$prItem->prItemID];

				$prItem->expenseItemID = $existingData['expenseItemID'];
				$prItem->prItemDescription = $existingData['itemDescription'];
				$prItem->prItemQuantity = $existingData['itemQuantity'];
				$prItem->prItemPrice = $existingData['itemPrice'];
				$prItem->prItemTotal = $existingData['itemPrice'] * $existingData['itemQuantity'];
				$prItem->prItemRemark = $existingData['itemRemark'];
				$prItem->prItemUpdatedDate = now();
				$prItem->prItemUpdatedUser = user()->userID;

				$prItem->save();
			}
		}

		// new items.
		if(input::get('newItem'))
		{
			$newItems = input::get('newItem');
			
			foreach($newItems['expenseItemID'] as $key => $item)
			{
				$prItem = orm('expense/pr/item')->create();
				$prItem->prID = $pr->prID;
				$prItem->expenseItemID = $newItems['expenseItemID'][$key];
				$prItem->prItemDescription = $newItems['itemDescription'][$key];
				$prItem->prItemQuantity = $newItems['itemQuantity'][$key];
				$prItem->prItemPrice = $newItems['itemPrice'][$key];
				$prItem->prItemTotal = $prItem->prItemQuantity * $prItem->prItemPrice;
				$prItem->prItemRemark = $newItems['itemRemark'][$key];
				$prItem->prItemUpdatedDate = now();
				$prItem->prItemUpdatedUser = user()->userID;

				$prItem->save();
			}
		}

		// update pr_expenditure
		$db = db::where('prID', $prID);
		
		if(input::get('expenditure'))
			$db->where('expenseExpenditureID !=', array_keys(input::get('expenditure')));

		$db->delete('pr_expenditure');

		if(input::get('expenditure'))
		{
			foreach(array_keys(input::get('expenditure')) as $id)
			{
				// insert
				if(!db::where('expenseExpenditureID', $id)->where('prID', $prID)->get('pr_expenditure')->row())
				{
					db::insert('pr_expenditure', array(
						'prID' => $pr->prID,
						'expenseExpenditureID' => $id,
						'prExpenditureCreatedDate' => now(),
						'prExpenditureCreatedUser' => session::get('userID')
						));
				}
			}
		}

		// update pr
		$pr->prDeposit = input::get('balDeposit');
		$pr->prTotal = input::get('total');
		
		$selectDate = input::get('selectDate');
		$startDate = date('Y-m-1 00:00:00',strtotime($selectDate));
		$lastDate = date('Y-m-d 18:00:00',strtotime($selectDate));

		/*$currentCollection = model::load('billing/process')->getCurrentCollection(authData('site.siteID'), $startDate, $lastDate);
		$pr->prBalance = $currentCollection['total'];*/
		$pr->prBalanceDate = $lastDate;
		$pr->save();

		if(user()->isManager() && $pr->isCashAdvance())
		{
			$cashAdvance = $pr->getCashAdvance();

			return redirect::to('exp/prEditCashAdvance/'.$cashAdvance->prCashAdvanceID, 'Updated!', 'success');
		}
		else
		{
			if(request::get('saveOnly') == 'true')
			{
				return redirect::to('exp/prEdit/'.$pr->prID, 'Updated!', 'success');
			}
			else
			{

				// update approval
				if(user()->isClusterLead() || user()->isOperationManager())
					$pr->approve(user());
				else
					$pr->managerSubmit(user());

				return redirect::to('exp/prEdit/'.$pr->prID, 'Approved!', 'success');
			}
		}
	}

	public function prRejectForm($prID)
	{
		$this->template = false;

		$data['pr'] = orm('expense/pr/pr')->find($prID);

		view::render('shared/exp/prRejectForm', $data);
	}

	public function prRejectionReason($prID)
	{
		$this->template = false;

		$pr = orm('expense/pr/pr')->find($prID);

		// get latest rejection
		$rejection = orm('expense/pr/rejection')->where('prID', $prID)->order_by('prRejectionID DESC')->limit(1)->find();

		view::render('shared/exp/prRejectionReason', compact('pr', 'rejection'));
	}

	public function prReject($prID)
	{
		$reason = input::get('prRejectionReason', null);

		$pr = orm('expense/pr/pr')->find($prID);

		$pr->reject(user(), $reason);

		redirect::to('exp/prList', 'Rejected the pr!', 'success');
	}

	public function submitPrNumber($prID)
	{
		$pr = orm('expense/pr/pr')->find($prID);

		$prNumber = input::get('prNumber');

		if(!$prNumber)
			redirect::back('Please set a PR number', 'error');

		$pr->close(user(), $prNumber);

		redirect::to('exp/prList', 'PR Number submitted', 'success');
	}

	public function listItem($categoryId)
	{
		$categories  = model::load('expense/item')->getList($categoryId);
		
		foreach($categories as $key  => $row)
		{
			$data['categories'][$row['expenseItemID']] = $row['expenseItemName'];
		}

		echo json_encode($data['categories']);
	}

	public function prDelete($prID)
	{
		$pr = orm('expense/pr/pr')->find($prID);

		// if($pr->isClosed())
		// 	redirect::to('exp/prList', 'This PR has already been closed. Unable to delete.', 'error');

		// $pr->delete();

		redirect::to('exp/prList', 'PR deletion has been disabled', 'error');
	}

	public function prEditCashAdvance($cashAdvanceID)
	{
		if(form::submitted())
			return $this->prEditCashAdvanceSubmit($cashAdvanceID);

		// $pr = orm('expense/pr/pr')->find($id);
		$cashAdvance = orm('expense/pr/cash_advance')->find($cashAdvanceID);
		$pr = $cashAdvance->getPr();

		$site = $pr->getSite();
		$manager = $pr->getRequestingUser();

		$selectDate = input::get('selectDate');		
		$data['selectDate'] = $selectDate = $selectDate ? :  date('d F Y');
		$data['siteName'] =  $site->siteName;
		$data['siteManager'] = $manager->userProfileFullName;
		$data['prId'] = $id;
		$data['cashAdvanceID'] = $cashAdvanceID;
		$data['pr'] = $pr;
		$data['ca'] = $cashAdvance;
		$data['isEditable'] = $isEditable = $pr->isPendingFor(user());
		$data['disabled'] = $isEditable ? '' : 'disabled';

		view::render('shared/exp/prEditCashAdvance', $data);
	}

	public function prEditCashAdvanceSubmit($cashAdvanceID)
	{
		$cashAdvance = orm('expense/pr/cash_advance')->find($cashAdvanceID);
		$cashAdvance->prCashAdvancePurpose = input::get('purpose');
		$cashAdvance->prCashAdvanceAmount = input::get('amount');

		$item = input::get('item');

		// let us just delete all, and reinsert.
		orm('expense/pr/cash_advance_item')
		->where('prCashAdvanceID', $cashAdvanceID)
		->delete();

		$total = 0;

		/*foreach($item['itemRemark'] as $key => $itemDescription)
		{
			$itemPrice = $item['itemPrice'][$key];

			$cashAdvanceItem = orm('expense/pr/cash_advance_item')->create();
			$cashAdvanceItem->prCashAdvanceID = $cashAdvanceID;
			$cashAdvanceItem->prCashAdvanceItemDescription = $itemDescription;
			$cashAdvanceItem->prCashAdvanceItemTotal = $itemPrice;
			$cashAdvanceItem->prCashAdvanceItemUpdatedDate = now();
			$cashAdvanceItem->save();

			$total += $itemPrice;
		}*/

		// $cashAdvance->prCashAdvanceTotal = $total;
		$cashAdvance->prCashAdvanceDate = now();
		$cashAdvance->prCAshADvanceUpdatedDate = now();
		$cashAdvance->save();

		// move to cluster lead
		$pr = $cashAdvance->getPr();

		// approve.
		if(request::get('saveOnly') != 'true')
		{
			if(user()->isManager())
			{
				$pr->managerSubmit(user());
				
				return redirect::to('exp/prList', 'PR Cash Advance Submitted.');
			}
			else
			{
				$pr->approve(user());

				return redirect::to('exp/prList', 'PR Cash Advance Approved.');
			}
		}
		else
		{
			return redirect::to('exp/prEditCashAdvance/'.$cashAdvanceID, 'PR Cash Advance Updated!');
		}
	}

	/**************************************************************************************************************
	 **************************************************************************************************************
	 * RL CODES BELOW
	 **************************************************************************************************************
	 **************************************************************************************************************/
	public function rlList($page = 1)
	{
		$rl = orm('expense/pr/reconcilation/reconcilation')->where('prReconcilationStatus != 3', 3);

		$rl->join('pr', 'pr.prID = pr_reconcilation.prID')->join('site', 'site.siteID = pr.siteID');

		// handles filter change

		if(request::get('site'))
			session::set('prFilter.site', request::get('site'));

		if(!(request::get('site'))){
			session::set('prFilter.site', '');
			session::set('prFilter.siteName', '');
		}
								
		if(request::get('siteName'))
			session::set('prFilter.siteName', request::get('siteName'));		

		if(request::get('cluster'))
			session::set('prFilter.cluster', request::get('cluster'));


		if(request::get('status'))
			session::set('rlFilter.status', request::get('status'));

		if(isset($_GET['month']))
		{
			if($_GET['month'] == '')
				session::destroy('rlFilter.month');
			else
				session::set('rlFilter.month', request::get('month'));
		}

		if(request::get('year'))
			session::set('rlFilter.year', request::get('year'));

		// handle query filter
		// if manager show non submitted RL OR (date based RL)
			$data['year'] 		= session::get('rlFilter.year', date('Y'));
			$data['month'] 		= session::get('rlFilter.month', date('n'));
			$data['cluster'] 	= session::get('prFilter.cluster', '');
			$data['site'] 		= session::get('prFilter.site', '');
			$data['siteName'] 	= session::get('prFilter.siteName', '');			
		if(user()->isManager())
		{
			//$rl->where('prReconcilationSubmitted = ? OR (prReconcilationSubmitted = ? AND MONTH(prReconcilationSubmittedDate) = ? AND YEAR(prReconcilationSubmittedDate) = ?)', array(0, 1, $data['month'], $data['year']));
			$rl->where('prReconcilationSubmitted = ? OR (prDate = ? AND MONTH(prDate) = ? AND YEAR(prDate) = ?)', array(0, 1, $data['month'], $data['year']));
		}
		// show date based submitted RL only
		else
		{
			//$rl->where('prReconcilationSubmitted = ? AND MONTH(prReconcilationSubmittedDate) = ? AND YEAR(prReconcilationSubmittedDate) = ?', array(1, $data['month'], $data['year']));
			$rl->where('prReconcilationSubmitted = ? AND MONTH(prDate) = ? AND YEAR(prDate) = ?', array(1, $data['month'], $data['year']));
		}

		$data['status'] = session::get('rlFilter.status', 'pending');

		if($data['status'] == 'closed')
			$rl->where('prReconcilationStatus', 1);
		else if($data['status'] != 'all')
			$rl->where('prReconcilationStatus', array(0, 2));

		// if site manager or 
		// cluster lead, limit list by their site.
		if(user()->isManager())
			$rl->where('site.siteID IN (SELECT siteID FROM site_manager WHERE userID = ?)', array(user()->userID));
		else if(user()->isClusterLead())
			$rl->where('site.siteID IN (SELECT siteID FROM cluster_site WHERE clusterID IN (SELECT clusterID FROM cluster_lead WHERE userID = ?))', array(user()->userID));

		// search
		if($search = request::get('search'))
			$rl->where('prNumber LIKE ', '%'.$search.'%');

		if($data['cluster'] > 0){
			$rl->where('site.siteID IN (SELECT siteID FROM cluster_site WHERE clusterID = ?) ', array($data['cluster']) );
			//$pr->execute();			
			//var_dump($pr);
			//die;
		}

		if($data['site'] != '')
			$rl->where('site.siteID', $data['site']);

		// pagination
		pagination::setFormat(model::load("template/cssbootstrap")->paginationLink());

		$rl->paginate(array(
			'urlFormat' => url::base('exp/rlList/{page}'),
			'currentPage' => $page,
			'limit' => 10
			));

		$rl->order_by('prReconcilationUpdatedDate DESC');
		//var_dump($rl);
		//die;
		$data['rl'] = $rl->execute();

		view::render('shared/exp/rlList', $data);
	}

	public function rlView()
	{

	}

	public function rlEdit($rlID)
	{
		if(form::submitted())
			return $this->rlEditSubmit($rlID);

		$rl = $data['rl'] = orm('expense/pr/reconcilation/reconcilation')->find($rlID);
		$data['pr'] = $rl->getPr();
		$data['files'] = $rl->getFiles();
		$data['rlFileTotalAmount'] = $rl->getFileTotalAmount();

		view::render('shared/exp/rlEdit', $data);
	}

	public function rlEditSubmit($rlID)
	{
	}

	public function rlChangeMonth($prID, $selectMonth)
	{
		// $selectMonth = input::get('selectMonth');
		//print_r($prID . $selectMonth);
		//die;
		$pr = model::orm('expense/pr/pr')->find($prID);
		$dateChange = explode("-",$pr->prDate);
		$dateChange[1] = sprintf("%02d", $selectMonth);

		$dateChange = implode("-",$dateChange);
		//var_dump(implode("-",$dateChange));
		//die;

		$pr->prDate = date('Y-m-d', strtotime($dateChange));
		$pr->prUpdatedDate = now();
		$pr->prUpdatedUser = user()->userID;
		$pr->save();
	}

	public function rlFile($fileID)
	{
		$this->template = false;

		if(request::get('render') == 'true')
			return $this->rlFileImage($fileID);

		$data['rlFile'] = orm('expense/pr/reconcilation/file')->find($fileID);

		view::render('shared/exp/rlFile', $data);
	}

	public function rlFileImage($fileID)
	{
		$rl = orm('expense/pr/reconcilation/file')->find($fileID);

		$rl->getFileName();

		$path = $rl->getFilePath();

		$info = getimagesize($path);

		header('Content-type:'.$info['mime']."; filename='$fileName'");

		return file_get_contents($path);
	}

	public function rlFileUpload($rlID)
	{
		$rl = $data['rl'] = orm('expense/pr/reconcilation/reconcilation')->find($rlID);

		if(form::submitted()){		
			return $this->rlFileUploadSubmit($rl);
		}

		$this->template = false;

		$data['categories'] = orm('expense/expense_category')->execute()->toList('expenseCategoryID', 'expenseCategoryName');
		// var_dump($data['categories']);
		// die;
		$data['categories'] = orm('expense/pr/reconcilation/category')
			->where('prReconcilationID', $rlID)
			->execute()
			->toList('prReconcilationCategoryID', 'expenseCategoryName');
		
		// $data['categoryID'] = $categoryID;

		view::render('shared/exp/rlFileUpload', $data);
	}

	public function rlFileUploadSubmit($rl)
	{
		// session_write_close();

		set_time_limit(0);
		ini_set('memory_limit','800M');		
		//var_dump(input::file("fileUpload"));
		$files	=  
		$_FILES['fileUpload']; 
		
		 // input::file("fileUpload");
		//var_dump(input::get());
		//var_dump($files);
		//die;
		$rlID = $rl->prReconcilationID;

		$siteID = $rl->getPr()->siteID;

		$rules	= Array(			
			"fileUpload"=>Array(
				"callback"=>Array(!$files?false:true,"Please input an upload file.")
				)
						);
		if($error = input::validate($rules))
		{
			input::repopulate();
			redirect::withFlash(model::load("template/services")->wrap("input-error",$error));
			redirect::to("exp/rlEdit/".$rlID,"Error in your form's field","error");
		}

		if($files)
			$file_ary = $this->fixFilesArray($files);

		//var_dump($file_ary);
		// var_dump(input::get());
		// die;


		$maxsize = 5000000;

		$exts = array("xls","xlsx",
				"doc","docx","ppt","pptx",
				"pps","ppsx","odt","pdf",
				"png","jpeg","jpg","bmp",
				"zip","rar","mp3","m4a",
				"ogg","wav","mp4","m4v",
				"mov","wmv","avi","mpg",
				"ogv","3gp","3g2");		

		// $exts = array("xls","xlsx",
		// 		"doc","docx","ppt","pptx",
		// 		"pps","ppsx","odt","pdf",
		// 		"png","jpeg","jpg","bmp",
		// 		"zip","rar","mp3","m4a",
		// 		"ogg","wav","mp4","m4v",
		// 		"mov","wmv","avi","mpg",
		// 		"ogv","3gp","3g2");

		//$files	= input::file("fileUpload");
		//var_dump($files->get("name"));
		//	die;
		$msg_array 			= array();
		$msg_array_success 	= array();
		$msg = "";
		$resultUpload = array();

		foreach ($file_ary as $key => $file) {
			# code...
			//var_dump($key);
			$approveExist = false;
			$approveExtension = false;
			$approveSize = false;
			

			if($file['error'] == 4){
				//redirect::to("","File uploaded is empty for ". $empty . ".", "error");
				array_push($msg_array, "File uploaded is empty");
			}
			else{
				$approveExist = TRUE;
			}
			//check extension
			//var_dump(str_replace("image/", "", $file['type']));

			if(!in_array(str_replace("image/", "", $file['type']), $exts) && $file['error'] != 4){
				//add into msg array
				array_push($msg_array, "File Name : ". $file['name']. " have invalid extension file.");
				//redirect::to("","File Name : ". $file['name']. " have invalid extension file.", "error");
			}else{
				$approveExtension = TRUE;
			}

			//check max size
			if($file['size'] >= $maxsize){
				//add into msg array
				array_push($msg_array, "File Name : ". $file['name']. " have exceeded max size(".($maxsize/1000).").");
				//redirect::to("","File Name : ". $file['name']. " have exceeded max size(".($maxsize/1000).").", "error");
			}else{
				$approveSize = TRUE;
			}

			if(($approveExtension == TRUE) && ($approveSize == TRUE) && ($approveExist == TRUE)){
				//upload image & update rl record

				//add into msg array

				//set resultUpload = true
				// $resultUpload = TRUE;

				$data['fileType']	= $file["type"];

				## use file name.
				if($data['fileName'] == "")
				{
					$fn	= explode(".",$file["name"]);
					array_pop($fn);
					$data['fileName']	= implode(".",$fn);
				}

				$data['fileSize']	= $file["size"];
				$data['fileExt']	= str_replace("image/", "", $file['type']);			

				$rlFile = orm('expense/pr/reconcilation/file')->create();
				$rlFile->prReconcilationID = $rlID;
				// $rlFile->expenseCategoryID = $data['itemCategory'];
				$rlFile->prReconcilationCategoryID = $key;
				// $rlFile->prReconcilationFileAmount = $data['amount'];
				// $rlFile->prReconcilationFileGst = $data['gst'];
				// $rlFile->prReconcilationFileTotal = $data['total'];
				$rlFile->prReconcilationFileName = $data['fileName'];
				$rlFile->prReconcilationFileType = $data['fileType'];
				$rlFile->prReconcilationFileSize = $data['fileSize'];
				$rlFile->prReconcilationFileExt = $data['fileExt'];
				$rlFile->prReconcilationFileStatus = 1;
				$rlFile->prReconcilationFileUpdatedDate = now();
				$rlFile->save();

				$category = orm('expense/pr/reconcilation/category')->find($key);
				$category->reconcileAllItems();

				array_push($msg_array_success, "Success upload for " . $file['name']);
				//redirect::to('exp/rlEdit/'.$rlID, 'Item file added!', 'success');
				$path	= path::files("site_requisition/".$siteID);
				//var_dump($path);
				//die;
				if(!is_dir($path))
				{
					$mkdir = mkdir($path, 0775, true);

					if(!$mkdir)
						die;
				}

				//var_dump($file['name']);
				array_push($resultUpload, TRUE);
				move_uploaded_file($file['tmp_name'], $path."/".$rlFile->prReconcilationFileID);				
			}
			else{
				//add into msg array

				array_push($resultUpload, FALSE);
				//redirect::to('exp/rlEdit/'.$rlID,$msg, "error");
			}


			//die;
		}
				foreach ($msg_array as $keyMsg => $valueMsg) {
					# code...
					$msg .= $valueMsg . "<br>";
				}
			//result
			if(in_array(FALSE, $resultUpload)){
				redirect::to('exp/rlEdit/'.$rlID, $msg, 'error');
			}
			else{
				redirect::to('exp/rlEdit/'.$rlID,"All item are successfully uploaded!", "success");
			}

	}

	public function rlApproval($rlID, $status)
	{
		$rl = orm('expense/pr/reconcilation/reconcilation')->find($rlID);

		if($status == 'approve')
			$rl->approve(user());
		else if($status == 'reject')
			$rl->reject(user());

		redirect::to('exp/rlEdit/'.$rlID, 'Approved!', 'success');
	}

	public function rlDelete($rlID)
	{
		$rl = orm('expense/pr/reconcilation/reconcilation')->find($rlID);

		// if($rl->isClosed())
		// 	redirect::to('exp/rlList', 'This RL has already been closed. Unable to delete.', 'error');

		// $rl->delete();

		redirect::to('exp/rlList', 'RL deletion has been disabled.', 'error');
	}

	public function rlDownload()
	{
		if(request::has(array('clusterID', 'month', 'year')))
			return $this->rlSummaryGenerate(request::get('clusterID'), request::get('month'), request::get('year'));

		$this->template = false;

		$sites = db::create('site')->get()->result();

		$clusters = orm('site/cluster');

		if(user()->isClusterLead())
			$clusters = $clusters->where('clusterID IN (SELECT clusterID FROM cluster_lead WHERE userID = ? AND clusterLeadStatus = 1)', array(user()->userID));

		$clusters = $clusters->execute()->toList('clusterID', 'clusterName');

		view::render('shared/exp/rlDownload', array('clusters' => $clusters));
	}

	public function rlRejectForm($rlID)
	{
		if(form::submitted())
			return $this->rlRejectFormSubmit($rlID);

		$this->template = false;

		$rl = orm('expense/pr/reconcilation/reconcilation')->find($rlID);

		$data = array(
			'title' => 'RL #'.$rlID.' Reject Form',
			'action' => url::base('exp/rlRejectForm/'.$rlID),
			'inputName' => 'prReconcilationRejectionReason'
			);

		view::render('shared/exp/rejectForm', $data);
	}

	public function rlRejectFormSubmit($rlID)
	{
		$rl = orm('expense/pr/reconcilation/reconcilation')->find($rlID);

		if(!$rl->isPendingFor(user()))
			throw new Exception('Not on your level of approval yet.');

		$rl->reject(user(), input::get('prReconcilationRejectionReason'));

		return redirect::to('exp/rlEdit/'.$rlID, 'RL with PR number #'.$rl->getPr()->prNumber.' has been rejected', 'success');
	}

	public function rlRejectionReason($rlID)
	{
		$this->template = false;

		$rl = orm('expense/pr/reconcilation/reconcilation')->find($rlID);

		$rejection = orm('expense/pr/reconcilation/rejection')
			->where('prReconcilationID', $rlID)
			->order_by('prReconcilationRejectionID DESC')
			->limit(1)
			->find();

		$data = array(
			'title' => 'RL for PR '.$rl->getPr()->prNumber.' rejection reason',
			'rejectionDate' => $rejection->prReconcilationRejectionCreatedDate,
			'reason' => $rejection->prReconcilationRejectionReason,
			'rlEditLink' => url::base('exp/rlEdit/'.$rlID),
			'rejection' => $rejection,
			'isRL' => true
			);

		view::render('shared/exp/rejectionReason', $data);
	}

	function fixFilesArray(&$files)
	{
	    $names = array( 'name' => 1, 'type' => 1, 'tmp_name' => 1, 'error' => 1, 'size' => 1);
	    //var_dump($files);
	    foreach ($files as $key => $part) {
	        // only deal with valid keys and multiple files
	        //var_dump($part);
	        //die;
	        $key = (string) $key;
	        if (isset($names[$key]) && is_array($part)) {
	            foreach ($part as $position => $value) {
	                $files[$position][$key] = $value;
	            }
	            // remove old key reference
	            unset($files[$key]);
	        }
	    }
	    return $files;
	}


	
}