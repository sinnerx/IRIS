<?php
class Controller_Requisition
{
	public function check()
	{
		$selectDate = input::get('selectDate');		
		$data['selectDate'] = $selectDate = $selectDate ? :  date('d F Y');
		$data['siteName'] =  authData('site.siteName');
		$data['siteManager'] = authData('user.userProfileFullName');

		print_r(authData('user'));

		$categories  = model::load('requisition/category')->getList();
		
		foreach($categories as $key  => $row)
		{
			$data['categories'][$row['purchaseRequisitionCategoryId']] = $row['purchaseRequisitionCategoryName'];
		}

	//	$allItem  = model::load('requisition/transaction')->getListTotal(authData('user.userID'),authData('site.siteID'), date('Y-m-d',strtotime($selectDate)));

	//	$data['allItem'] =  $allItem;		

		view::render("clusterlead/requisition/check", $data);		
	}

	
/*	public function addTransaction($id,$selectDate)
	{
		$this->template = false;

		$data['selectDate'] = date('d F Y',$selectDate);
		$categories  = model::load('requisition/item')->getList($id);
		
		foreach($categories as $key  => $row)
		{
			$data['categories'][$row['purchaseRequisitionItemId']] = $row['purchaseRequisitionItemName'];
		}

		$data['categoryId'] = $id;

		if(form::submitted())
		{									
				$requisition = model::orm('requisition/transaction')->create();
				$requisition->userID = authData('user.userID');
				$requisition->siteID = authData('site.siteID');
				$requisition->purchaseRequisitionItemId = input::get('itemType');
				$requisition->purchaseRequisitionDescription = input::get('description');
				$requisition->purchaseRequisitionPrice = input::get('price');
				$requisition->purchaseRequisitionQuantity = input::get('quantity');
				$requisition->purchaseRequisitionTotal = input::get('totalprice');
				$requisition->purchaseRequisitionDate = date('Y-m-d',$selectDate);
				$requisition->purchaseRequisitionCreatedDate = now();
				$requisition->save();	

				$message = 'New Item added!';
				
			redirect::to('requisition/add', $message, 'success');
		}		

		view::render("sitemanager/requisition/addCategory", $data);		
	}	*/		



	public function listItem($categoryId)
	{
		$categories  = model::load('requisition/item')->getList($categoryId);
		
		foreach($categories as $key  => $row)
		{
			$data['categories'][$row['purchaseRequisitionItemId']] = $row['purchaseRequisitionItemName'];
		}

		echo json_encode($data['categories']);
	}			


	public function submitRequisition()
	{
		$requisition = model::orm('requisition/transaction')->create();
				$requisition->userID = authData('user.userID');
				$requisition->siteID = authData('site.siteID');
				$requisition->purchaseRequisitionDate = date('Y-m-d',strtotime(input::get('selectDate')));
				$requisition->purchaseRequisitionCreatedDate = now();
				$requisition->save();	
		
		$prID = $requisition->purchaseRequisitionId;	
		$item = input::get('item');
		$itemCount = count($item['itemCategory']);

			for ($x = 11; $x < $itemCount+11; $x++) {
   		
				foreach ($item as $key => $value) {  // start from 11
			
						$itemList[$key] = $value[$x];
				}
       
				$insertTransaction = model::load('requisition/transaction')->addTransaction($prID,$itemList);	

			}	

		$message = 'Submitted';
		redirect::to('requisition/add', $message, 'success');			
	}
}


?>