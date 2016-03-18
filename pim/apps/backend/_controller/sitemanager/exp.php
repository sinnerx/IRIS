<?php

class Controller_Exp
{
	public function prAdd()
	{
		$selectDate = request::get('selectDate');		
		$data['selectDate'] = $selectDate = $selectDate ? : date('d F Y');
		$data['siteName'] =  authData('site.siteName');
		$data['siteManager'] = authData('user.userProfileFullName');

		$data['month'] = date('n', strtotime($selectDate));
		$data['year'] = date('Y', strtotime($selectDate));
		
		$startDate = date('Y-m-1 00:00:00',strtotime($selectDate));
		$lastDate = date('Y-m-d 18:00:00',strtotime($selectDate));

		$data['prTerm'] = model::load('expense/transaction')->getPrTerm();

		$currentCollection = model::load('billing/process')->getCurrentCollection(authData('site.siteID'), $startDate, $lastDate);			
		$data['currentCollection'] = number_format($currentCollection['total'], 2, '.', ''); 

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

		// category
		$categories = model::orm('expense/expense_category')->execute();
		
		foreach($categories as $category)
			$data['categories'][$category->expenseCategoryID] = $category->expenseCategoryName;

		view::render('sitemanager/exp/prAdd', $data);
	}

	public function prAddCheck($prType, $month, $year)
	{
		// check if already got existing pr for this month.
		$prs = orm('expense/pr/pr')
			->where('siteID', site()->siteID)
			->where('prStatus !=', 3)
			->where('MONTH(prDate) = ? AND YEAR(prDate) = ? AND prType = ?', array($month, $year, $prType))
			->execute();

		return json_encode(array(
			'exists' => $prs->count() > 0
			));
	}

	/**
	 * Add pr, pr_item, and pr_approval (for all level)
	 */
	public function prAddSubmit()
	{
		// add : pr
		$pr = model::orm('expense/pr/pr')->create();
		$pr->userID = authData('user.userID');
		$pr->siteID = authData('site.siteID');
		$pr->prType = input::get('prTerm1');
		/*$pr->prExpense = input::get('budgeted1');
		$pr->prEquipment = input::get('budgeted2');
		$pr->prEvent = input::get('addition1');
		$pr->prAdhocevent = input::get('addition2');
		$pr->prOther = input::get('replacement1');
		$pr->prCitizen = input::get('replacement2');*/
		$pr->prTotal = input::get('total');
		$pr->prBalance = input::get('curCollection');
		$pr->prBalanceDate = date('Y-m-d 18:00:00', strtotime(input::get('selectDate')));
		$pr->prDeposit = input::get('balDeposit');
		$pr->prDate = date('Y-m-d',strtotime(input::get('selectDate')));
		$pr->prStatus = 0;
		$pr->prStatusPendingLevel = 2; // site manager
		$pr->prCreatedDate = now();
		$pr->prUpdatedDate = now();
		$pr->prUpdatedUser = session::get('userID');
		$pr->save();

		$type = 1; # 1 = pr 2 = ca 3 = rl
		$item = input::get('item');

		// expenditure
		if($expenditures = input::get('expenditure'))
		{
			foreach($expenditures as $set => $values)
			{
				foreach($values as $expenseExpenditureID => $value)
				{
					db::insert('pr_expenditure', array(
						'prID' => $pr->prID,
						'expenseExpenditureID' => $expenseExpenditureID,
						'prExpenditureCreatedDate' => now(),
						'prExpenditureCreatedUser' => session::get('userID')
						));
				}
			}
		}

		// add : pr_item
		foreach($item['itemCategory'] as $key => $itemID)
		{
			$prItem = model::orm('expense/pr/item')->create();

			$prItem->prID = $pr->prID;
			$prItem->expenseItemID = $itemID;
			$prItem->prItemDescription = $item['itemDescription'][$key];
			$prItem->prItemPrice = $item['itemPrice'][$key];
			$prItem->prItemQuantity = $item['itemQuantity'][$key];
			$prItem->prItemTotal = $item['itemTotalPrice'][$key];
			$prItem->prItemRemark = $item['itemRemark'][$key];
			$prItem->prItemCreatedDate = $pr->prCreatedDate;

			$prItem->save();
		}

		// add : pr_approval
		// create approval for all level
		$pr->initiateApprovals();

		/*
			for ($x = 11; $x < $itemCount+11; $x++)
			{
				foreach ($item as $key => $value) {  // start from 11
						$itemList[$key] = $value[$x];

				}

				$prItem = model::orm('expense/pr/item')->create();
				$prItem->prItemCategoryID = $itemList['itemCategory'];
				$prItem->prItemDescription = $itemList['itemDescription'];
				$prItem->prItemPrice = $itemList['itemPrice'];
				$prItem->prItemQuantity = $itemList['itemQuantity'];
				$prItem->prItemTotal = $itemList['itemTotalPrice'];

				
				"purchaseRequisitionId" => $id,
			"purchaseRequisitionDetailItemId" => $itemList['itemCategory'],
			"purchaseRequisitionDetailDescription" => $itemList['itemDescription'],
			"purchaseRequisitionDetailPrice" => $itemList['itemPrice'],
			"purchaseRequisitionDetailQuantity" => $itemList['itemQuantity'],
			"purchaseRequisitionDetailTotal" => $itemList['itemTotalPrice'],
			"purchaseRequisitionDetailRemark" => $itemList['itemRemark'],
			"purchaseRequisitionDetailCreatedDate" => now(),
       
				$insertTransaction = model::load('expense/transaction')->addTransaction($prID,$itemList);	
			}*/

		if($pr->isCashAdvance())
		{
			// create a cash advance.
			$cashAdvance = orm('expense/pr/cash_advance')->create();
			$cashAdvance->prID = $pr->prID;
			$cashAdvance->prCashAdvancePurpose = '';
			$cashAdvance->save();

			return redirect::to('exp/prEditCashAdvance/'.$cashAdvance->prCashAdvanceID, 'Submitted', 'success');
		}
		else
		{
			// move approval to clusterlead on each submission.
			$pr->managerSubmit(user());

			return redirect::to('exp/prList', 'Submitted', 'success');
		}
//		
		/*if (input::get('check') == 2 ){

			redirect::to('expense/addPRCashAdvance/'.$prID, $message, 'success');
		
		} else {

			$approval = model::load('expense/approval')->getApproval($prID, $type, $siteID, $createdDate);
			$message = 'Submitted';
			redirect::to('expense/listStatus', $message, 'success');							
		}*/
	}

	public function rlSubmit($rlID)
	{
		$rl = orm('expense/pr/reconcilation/reconcilation')->find($rlID);

		$reconcileds = input::get('prReconcilationReconciled');

		$reconciledIds = $reconcileds ? array_keys($reconcileds) : array();

		// update.
		foreach(input::get('prReconcilationItemAmount') as $itemId => $value)
		{
			$status = in_array($itemId, $reconciledIds) ? 1 : 2;

			// update.
			$item = orm('expense/pr/reconcilation/item')->find($itemId);

			$item->prReconcilationItemAmount = $_POST['prReconcilationItemAmount'][$itemId];
			$item->prReconcilationItemGst = $_POST['prReconcilationItemGst'][$itemId];
			$item->prReconcilationItemTotal = $_POST['prReconcilationItemTotal'][$itemId];
			$item->prReconcilationItemStatus = $status;
			$item->save();
		}

		// add new item
		if($newItems = input::get('newItem'))
		{
			foreach($newItems['name'] as $categoryID => $items)
			{
				foreach($items as $index => $itemName)
				{
					$item = orm('expense/pr/reconcilation/item')->create();
					$item->prReconcilationID = $rlID;
					$item->prReconcilationCategoryID = $categoryID;
					$item->prReconcilationItemName = $newItems['name'][$categoryID][$index];
					$item->prReconcilationItemAmount = $newItems['amount'][$categoryID][$index];
					$item->prReconcilationItemGst = $newItems['gst'][$categoryID][$index];
					$item->prReconcilationItemTotal = $newItems['total'][$categoryID][$index];
					$item->prReconcilationItemStatus = 1;
					$item->prReconcilationItemCreatedDate = now();
					$item->prReconcilationItemCreatedUser = user()->userID;
					$item->save();
				}
			}
		}

		$rl->managerSubmit(user());

		redirect::to('exp/rlEdit/'.$rlID, 'Reconcilation list submitted!', 'success');
	}

	public function rlRemoveReceipt($fileID)
	{
		$file = orm('expense/pr/reconcilation/file')
			->where('prReconcilationID IN (SELECT prReconcilationID FROM pr_reconcilation WHERE prID IN (SELECT prID FROM pr WHERE siteID = ?))', array(site()->siteID))
			->find($fileID);

		if($file)
			$file->delete();

		redirect::back('Successfully deleted receipt.', 'success');
	}

	public function rlRemoveItem($itemID)
	{
		$item = orm('expense/pr/reconcilation/item')->find($itemID);

		if($item)
			$item->delete();

		redirect::back('Successfully deleted RL item', 'success');
	}
}