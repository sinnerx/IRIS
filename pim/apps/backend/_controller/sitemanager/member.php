<?php
class Controller_Member
{
	public function lists($page = 1)
	{
		if(request::get("toggle")){
			model::load('site/member')->approveMember(request::get("toggle"));

			redirect::to("member/lists","Updated status.");
		}

		$where	= null;
		
		if(request::get("search"))
		{
			## ic.
			if(is_numeric(request::get("search")))
			{
				$where['site_member.userID IN (SELECT userID FROM user WHERE userIC = ?)']	= Array(request::get("search"));
			}
			## name.
			else
			{
				$where['site_member.userID IN (SELECT userID FROM user_profile WHERE userProfileFullName LIKE ?)']	= Array("%".request::get("search")."%");
			}

			if(request::get('searchAllSites'))
			{
				db::join('site', 'site.siteID = site_member.siteID');
			}
		}

		$data['user'] = model::load('site/member')->getPaginatedList(
			model::load('access/auth')->getAuthData('site','siteID'), 
			Array(
				'urlFormat'=>url::base("member/lists/{page}"),
				'currentPage'=>$page
			),$where, (request::get('search') && request::get('searchAllSites')) ? false : true);

		view::render("sitemanager/member/lists",$data);
	}

	/**
	 * Only allow the change of member password only.
	 */
	public function changePassword()
	{
		if(form::submitted())
		{
			$userIC	= input::get("userIC");

			$rules	= Array(
					"_all"					=>"required:This field is required.",
					"userIC"				=>Array(
						"callback"			=>Array(model::load("user/user")->checkIC(input::get("userIC")),"IC not found.")
						),
					"userPassword_confirm"	=>Array(
						"callback"=>Array(input::get("userPassword_confirm") == input::get("userPassword"),"Please confirm the password")
						)
							);

			if($error = input::validate($rules))
			{
				input::repopulate();
				redirect::withFlash(model::load("template/services")->wrap("input-error",$error));
				redirect::to("member/changePassword","Please complete the field.","error");
			}

			// check member existnce.
			$row = db::from('site')->where('siteID IN (SELECT siteID FROM site_member WHERE userID IN (SELECT userID FROM user WHERE userIC = ?))', array(input::get('userIC')))->get()->row();

			if(!$row || ($row['siteID'] != site()->siteID && site()->siteID != 85)) // site pengurus
				return redirect::to('member/changePassword', 'Unable to find the member information.', 'error');

			## do celcom api user update.
			$updated	= model::load("celcom/auth")->update_user(input::get('userIC'),input::get('userPassword'), $row['siteRefID']);

			## change password.
			model::load("user/user")->changePasswordByIC(input::get("userIC"),input::get("userPassword"));

			## check member, whether he already registered, or just in temporary_user table.
			#$checkmember	= model::load("site/member")->checkMember(authData("site.siteID"),$userIC);

			redirect::to("member/changePassword","Successfully updated to '".input::get("userPassword")."'");
		}

		view::render("sitemanager/member/changePassword");
	}

	public function printm()	
	{
		// db::select("*");
		// db::from("site_member");
		// db::where("userID","site_member.userID = 172");
		// $userR		= db::get()->result("userID");
		// print_r($userR);
		$siteid = $_GET['userID'];
		db::select("site_member.userID,site_member.siteID,site.siteName");
		//db::from("site_member");
		db::where("site_member.userID",$_GET['userID']);
		db::join("site","site.siteID = site_member.siteID");
		$data['site']	= db::get("site_member")->result();
		//print_r($registeredR);
		view::render("sitemanager/member/printm", $data);
	}

	public function edit($userID)
	{
		$user			= model::load("user/user");
		$data['row']	= $user->get($userID);
		$data['add']	= $user->getAdditional($userID);

		if(form::submitted())
		{
			$emailCheck	= false;
			## check only if email not same as current email.
			if(input::get("userEmail") != ""){
				$emailRule = 
					Array(
							"email:Please input a correct email format.",
							"callback"=>Array(!$emailCheck,"Email already exists.")
						);
									
				if(input::get("userEmail") != $data['row']['userEmail'])
				{
					$emailCheck	= model::load("user/services")->checkEmail(input::get("userEmail"));
				
				}				
			}
			else if(input::get("userEmail") == ""){
				$emailCheck = true;
				$emailRule = array();
			}

			$userIC = str_replace('-', '', input::get('userIC'));

			## ic check.
			$icCheck	= false;
			if($userIC != $data['row']['userIC'])
			{
				$icCheck = model::load("user/services")->checkIC($userIC);
			}
			//var_dump($emailRule);
			// var_dump($icCheck);
			// var_dump(strlen($userIC));
			if (preg_match('/^[0-9]*$/', $userIC)) {
			  // contains only 0-9
				if(strlen($userIC) != 12){
					$icCheck = 1;
				}				
			}
			else{
				// var_dump("alphabet");
				if(strlen($userIC) >= 12){
					$icCheck = 1;
				}
			}		
			// $icCheck = null;
			// var_dump($icCheck);
			// die;			
			$rules	= Array(
					"userProfileFullName,userIC,userRace,userProfileGender,userProfileDOB,userProfileOccupationGroup"=>"required:This field is required.",
					"userIC"=>Array(
								"callback"=>Array(!$icCheck,"IC already exists / only 12 numeric characters are allowed")
									)
							);

			$rules['userEmail'] = $emailRule;
			## got error.

			if($error = input::validate($rules))
			{
				//var_dump($rules['userIC']['callback'][1] . );
				$message = $rules['userEmail'][0];
				//var_dump($message);
				//die;
				input::repopulate();
				redirect::withFlash(model::load("template/services")->wrap("input-error",$error));
				redirect::to("","Got some error in your form.","error");
			}

			if(input::get("userIC") != $data['row']['userIC']){
				$dataIC['userID'] 		 = $userID;
				$dataIC['newIC'] 		 = input::get('userIC');
				$dataIC['oldIC'] 		 = $data['row']['userIC'];				
				// $dataIC['ICUpdatedDate'] = date('Y-m-d H:i:s');

				$user->ICChanges($dataIC);
			}

			## update member data
			$userProfileLastName = $data['row']['userProfileLastName'];
			$data	= input::get();
			$data['userIC'] = $userIC;
			$data['userProfileFullName'] = str_replace('"', '\'', $data['userProfileFullName']);
			$data['userProfileLastName'] = str_replace('"', '\'', $data['userProfileLastName']);
			// var_dump($data);
			// die;
			//$data['userProfileLastName'] = $userProfileLastName;
			$user->updateMember($userID,$data);
			$user->updateAdditional($userID,$data);

			

			redirect::to("","Successfully updated member info.");
		}

		view::render("sitemanager/member/edit",$data);

		//echo "Editing " . $data['row']['userProfileFullName'] . "<br>";
		//echo "Level " . $data['userLevelR'] . "<br>";
	}

	public function delete($userID)
	{
		$user = model::load("user/user");
		$user->deleteMember($userID);

		redirect::to("member/lists","Member id " . $userID . " deleted.");
	}

	public function point($userID, $page){
		// var_dump(model::load('access/auth')->getAuthData('site','siteID'));
		// die;

		$siteID = model::load('access/auth')->getAuthData('site','siteID');
		// $dateStart = date("Y-m-01");
		// $dateEnd = date("Y-m-t");

		$todayDateStart 	= request::get("selectDateStart");
		$todayDateEnd 		= request::get("selectDateEnd");
		$billingItemType	= request::get("billingItemType");

		$data['todayDateStart'] = $todayDateStart = $todayDateStart ? :  date('Y-m-01');
		$data['todayDateEnd'] = $todayDateEnd = $todayDateEnd ? :  date('Y-m-t');

		$todayDateStart = date('Y-m-d', strtotime($todayDateStart));
		$todayDateEnd = date('Y-m-d', strtotime($todayDateEnd));

		$params = array(
			// 'siteID' 		=> $siteID,
			'userID'			=> $userID,
			'dateStart'			=> $todayDateStart,
			'dateEnd'			=> $todayDateEnd,
			'billingItemType'	=> $billingItemType,
			'pagination'		=> Array(
				'urlFormat'=>url::base("member/point/$userID/{page}/?billingItemType=$billingItemType&selectDateStart=$todayDateStart&selectDateEnd=$todayDateEnd"),
				'currentPage'=>$page
			),
			);

		//,$where, (request::get('search') && request::get('searchAllSites')) ? false : true

		db::from("billing_item");
		db::order_by("billingItemID","ASC");
		
		$billing_type = db::get()->result();
		
		foreach($billing_type as $row)
		{
			$data['itemList'][$row['billingItemID']]= $row['billingItemName'];
		}

		$data['transactions'] 		= model::load('billing/transaction_user')->getTransactionPointList($params);

		
		pagination::setFormat(model::load("template/cssbootstrap")->paginationLink());

		view::render('sitemanager/member/point', $data);
	}	
}