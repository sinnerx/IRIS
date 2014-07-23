<?php
Class Controller_Main
{
	## main landing page. example : pim.my
	public function landing()
	{
		$this->template	= false;
		view::render("main/landing");
	}

	## main landing page:about. example : p1m.my/about
	public function landing_about()
	{
		$this->template	= false;
		view::render("main/landing_about");
	}

	## main landing page:contact. example : p1m.my/contact
	public function landing_contact()
	{
		$this->template	= false;
		$data['categoryNameR']	= model::load("site/message")->getCategoryName();

		view::render("main/landing_contact",$data);
	}

	## site landing page. example : pim.my/[site-slug]
	public function index()
	{
		$page	= model::load("page/page");
		$site	= model::load("site/site");
		$siteID	= $site->getSiteIDBySlug(request::named("site-slug"));
		$data['siteName']	= $site->getSiteName();

		## get tentang-kami.
		$defaultR	= $page->getDefault();
		db::from("page");
		db::where("siteID",$siteID);
		db::where(Array(
					"pageType"=>1,
					"pageDefaultType"=>1
						));
		$data['pageName']	= $defaultR[1]['pageDefaultName'];
		$data['pageSlug']	= url::base("{site-slug}/".$defaultR[1]['pageDefaultSlug']);
		$row				= db::get()->row();
		$data['photoName']	= model::load("page/page")->getPagePhotoUrl($row['pageID']);

		## repair page text to 90 words.
		$data['pageText'] = $row['pageTextExcerpt'] == ""?model::load("helper")->purifyHTML(stripslashes($row['pageText']),90):nl2br($row['pageTextExcerpt']);
		$data['siteID'] = $siteID;

		view::render("main/index",$data);
	}

	public function login()
	{
		if(form::submitted())
		{
			$userIC	= str_replace("-","",input::get('login_userIC'));
			$pass	= input::get("login_userPassword");

			$rules	= Array(
					"_all"=>"required:Maklumat ini diperlukan"
							);

			## validate 
			if($error = input::validate($rules))
			{
				input::repopulate();
				redirect::withFlash(model::load("template/services")->wrap("span-error",$error));
				redirect::to("{site-slug}/registration#horizontalTab1","<br>Sila pastikan maklumat anda lengkap.","error");
			}

			## check member login.
			# check if current site is pengurus site.
			if(authData("current_site.siteID") == model::load("config")->get("configManagerSiteID"))
			{
				## this login is using email instead..
				$login	= model::load("access/auth")->checkManagerSiteLogin($userIC,$pass,authData("current_site.siteID"));
			}
			## normal member login.
			else
			{
				$login	= model::load("access/auth")->checkMemberLogin($userIC,$pass);
			}

			if(!$login)
			{
				input::repopulate();
				redirect::to("{site-slug}/registration#horizontalTab1","<br>Tidak dapat mengenal pasti login anda.","error");
			}

			## and log user in.
			model::load("access/auth")->login($login['userID'],$login['userLevel']);

			## success and redirect to main site.
			redirect::to("{site-slug}");
		}

		redirect::to("{site-slug}/registration#horizontalTab1");
	}

	public function logout()
	{
		## destroy all session.
		session::destroy();

		## redirect to main.
		redirect::to("{site-slug}");
	}

	## site registration. example : pim.my/[site-slug]/registration
	public function registration()
	{
		$this->template	= "template_registration";
		$row_site		= 	model::load("access/auth")->getAuthData("current_site");#model::load("site/site")->getSite();
		$data['siteName']	= $row_site['siteName'];
		$data['siteInfoDescription']	= $row_site['siteInfoDescription'];
		$data['monthR']					= model::load("helper")->monthYear("month");
		$data['yearR']				 	= model::load("helper")->monthYear('year',1925,date("Y"));

		if(form::submitted())
		{
			## prepare IC.
			$ic			= str_replace("-","",input::get("userIC"));## remove '-' if got.
			$password	= input::get("userPassword");

			$icCheck	= Array(!model::load("user/services")->checkIC($ic),"I.C. ini telah didaftarkan.");
			$icCheck	= $icCheck[0]?(is_numeric($ic)?Array(true):Array(false,"IC mestilah nombor yang betul")):$icCheck;
		
			$rules	= Array(
					"userProfileFullName,userProfileLastName"=>"required:Lapangan ini tidak lengkap",
					"except:checkPenduduk,checkTerm"=>"required:Lapangan ini diperlukan.",
					"userIC"=>Array(
							 "callback"=>Array($icCheck[0],$icCheck[1])
									),
					"userPassword"=>Array(
							"callback"=>Array((strlen($password) >= 6),"Minimum kata laluan adalah 6 karakter")
										)
							);

			$isOutsider		= input::get("checkPenduduk")?0:1;
			$checkTerm		= input::get("checkTerm")?true:false;

			## if got error.
			if(($error = input::validate($rules)) || /*!$checkPenduduk || */!$checkTerm)
			{
				/*if(!$checkPenduduk)
				{
					$error['checkPenduduk']	= "Hanya penduduk di kampung ini sahaja boleh berdaftar.";
				}*/

				if(!$checkTerm)
				{
					$error['checkTerm']		= "Pastikan anda setuju dengan terma dan syarat kami.";
				}

				input::repopulate();## repopulate previous input into flash data
				redirect::withFlash(model::load("template/services")->wrap("span-error",$error));
				redirect::to("","Terdapat kesalahan pada form anda.","error");
			}

			## register.
			## prepare
			$birthdate	= input::get("birthday_year")."-".input::get("birthday_month")."-".input::get("birthday_day");
			$fullname	= input::get("userProfileFullName");
			$lastname	= input::get("userProfileLastName");

			## register.
			model::load("site/member")->register($row_site['siteID'],$ic,$password,$birthdate,$fullname,$lastname,$isOutsider);

			## success and redirect.
			$memberFee	= model::load("config")->get("configMemberFee",5);
			redirect::to("{site-slug}/registration#horizontalTab1","<br>Anda telah berjaya di daftarkan. Sila buat pembayaran RM $memberFee di Pusat Internet 1Malaysia anda untuk menikmati kemudahan laman web ini sepenuhnya.");
		}

		view::render("main/registration",$data);
	}

	## site contact-us : pim.my/[site-slug]/contact-us
	public function contact()
	{
		$site					= model::load("site/site");
		$data['row']			= $site->getSite();
		$row_manager			= $site->getManagerInfo(null,'userEmail');
		$data['managerEmail']	= $row_manager['userEmail'];
		$data['categoryNameR']	= model::load("site/message")->getCategoryName();
		
		## a message has been submitted.
		if(form::submitted())
		{
			$rules	= Array(
					"siteMessageCategory"=>"required:Sila pilih kategori.",
					"contactName,contactPhoneNo,contactEmail,messageSubject,messageContent"=>"required:Sila isikan ruangan ini.",
					"contactEmail"=>"email:Sila masukkan format alamat emel yang betul.",
					"contactPhoneNo"=>Array(
								"callback"=>Array(is_numeric(str_replace("-","",input::get("contactPhoneNo"))),"Sila masukkan nombor telefon yang betul.")
										)
							);

			## if got error.
			if($error = input::validate($rules))
			{
				input::repopulate();
				redirect::withFlash(model::load("template/services")->wrap("input-error",$error));
				redirect::to("","<span id='mailmessage' class='msgbox error'>Maklumat tidak lengkap</span>","error");
			}

			## else, createPublicMessage.
			$referenceNo	= model::load("site/message")->createPublicMessage($data['row']['siteID'],input::get());

			redirect::to("","<span id='mailmessage' class='msgbox success'>Mesej anda telah berjaya dihantar. No. Rujukan : $referenceNo</span>");
		}

		view::render("main/contact_us",$data);
	}


	## site faq
	public function faq()
	{
		view::render("main/faq");
	}
}


?>