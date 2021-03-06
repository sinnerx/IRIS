<?php
Class Controller_Sales
{
	public function add($todayDate = null)
	{
		$siteID	= authData('site.siteID');

		$data['todayDate'] = $todayDate = $todayDate ? :  date('Y-m-d');
		//$todayDate = date('Y-m-d');
		$data['todaySales'] = model::orm('sales/sales')->where('date(salesDate)', $todayDate)->execute();
		
		if(form::submitted())
		{
			$salesProducts = input::get();

			if($data['todaySales']->count() > 0)
			{
				$sales = $data['todaySales']->getFirst();

				db::delete('sales_product', array('salesID'=> $sales->salesID));
				$sales->salesTotal = 0;
				$sales->save();

				$message = 'Sales information updated.';
			}
			else
			{
				$sales = model::orm('sales/sales')->create();
				$sales->siteID = $siteID;
				$sales->salesCreatedDate = now();
				$sales->salesDate = $todayDate;
				$sales->salesCreatedUser = session::get('userID');
				$sales->salesRemark = '';
				$sales->save();

				$message = 'New sales added!';
			}

			foreach ($salesProducts as $productID => $quantity) {
				# code...

				if ($productID != "selectDate") {

					$sales->addProduct($productID, $quantity);	
				}
			}
			
			redirect::to(null, $message, 'success');
		}
		
		$data['types'] = model::load('sales/sales')->type();
/*		$month = $data['month'] = $month ? : date('n');
		$year = $data['year'] = $year ? : date('Y');

		// get monthly sales. return array of model/sales/sales
		pagination::setFormat(model::load("template/cssbootstrap")->paginationLink());
		$data['sales'] = model::orm('sales/sales')
		->where('siteID', site()->siteID)
		->where('month(salesCreatedDate)', $month)
		->where('year(salesCreatedDate)', $year)
		->order_by('salesCreatedDate', 'desc')
		->paginate(array(
			'limit'=>10,
			'currentPage'=>$page,
			'urlFormat'=>url::base('sales/add/'.$month.'/'.$year.'/{page}')
			))
		->execute();*/

		view::render("sitemanager/sales/add", $data);
	}

	public function delete($id)
	{
		$sales = model::orm('sales/sales')->find($id);

		$sales->delete();

		return redirect::to('sales/add', 'sales removed', 'success');
	}

	public function edit($id)
	{
		if(request::isAjax())
			$this->template = false;

		$data['sales'] = model::orm('sales/sales')->find($id);

		if(form::submitted())
		{
			if(input::get('salesTotal') == '' || input::get('salesType') == '')
			{
				redirect::to(null, 'Please complete the sales update information', 'error');
			}

			$data['sales']->salesTotal = input::get('salesTotal');
			$data['sales']->salesType = input::get('salesType');
			$data['sales']->salesRemark = input::get('salesRemark');
			$data['sales']->save();

			redirect::to('sales/add', 'Updated sales');
		}

		view::render('sitemanager/sales/edit', $data);
	}




}