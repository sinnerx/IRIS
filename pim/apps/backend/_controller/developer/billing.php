<?php

class Controller_Billing
{
	public function code()
	{
		if(form::submitted())
		{
			$itemID = input::get('billingItemID');
			$codeName = input::get('billingItemCode');

			if($itemID && $codeName)
			{
				db::insert('billing_item_code', array(
					'billingItemID' => $itemID,
					'billingItemCodeName' => $codeName
					));

				redirect::to();
			}
		}

		$data['billingItems'] = model::orm('billing/item')
		// ->where('billingItemID IN (SELECT billingItemID FROM billing_item_code)')
		->join('billing_item_code', 'billing_item_code.billingItemID = billing_item.billingItemID', 'INNER JOIN')
		->execute();

		$data['assign']['billingItems'] = model::orm('billing/item')
		->where('billingItemID NOT IN (SELECT billing_item_code.billingItemID FROM billing_item_code)')
		->execute()
		->toList('billingItemID', 'billingItemName');

		$codes = model::load('billing/item')->getItemCodes();

		$data['assign']['billingItemCodes'] = array();


		foreach($codes as $code)
		{
			if(!in_array($code, array_keys($data['billingItems']->toList('billingItemCodeName', 'billingItemID'))))
				$data['assign']['billingItemCodes'][$code] = $code;
		}

		view::render('developer/billing/code', $data);
	}
}