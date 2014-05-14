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
		view::render("main/landing_contact");
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
		$data['pageText'] = model::load("helper")->purifyHTML(stripslashes($row['pageText']),90);

		view::render("main/index",$data);
	}

	public function login()
	{
		if(form::submitted())
		{
			$userIC	= str_replace("-","",input::get('login_userIC'));
			$pass	= input::get("login_userPassword");

			$rules	= Array(
					"_all"=>"required:This field is required."
							);

			## validate 
			if($error = input::validate($rules))
			{
				input::repopulate();
				redirect::withFlash(model::load("template/services")->wrap("span-error",$error));
				redirect::to("{site-slug}/registration#horizontalTab1","<br>Got some error with your form.","error");
			}

			## check member login.
			if(!model::load("access/auth")->checkMemberLogin($userIC,$pass))
			{
				input::repopulate();
				redirect::to("{site-slug}/registration#horizontalTab1","<br>Couldn't authorize your log detail.","error");
			}

			## success and redirect to main site.
			redirect::to("{site-slug}");
		}

		redirect::to("{site-slug}/registration#horizontalTab1");
	}

	## site registration. example : pim.my/[site-slug]/registration
	public function registration()
	{
		$this->template	= "template_registration";
		$row_site		= model::load("site/site")->getSite();
		$data['siteName']	= $row_site['siteName'];
		$data['siteInfoDescription']	= $row_site['siteInfoDescription'];
		$data['monthR']					= model::load("helper")->monthYear("month");
		$data['yearR']				 	= model::load("helper")->monthYear('year',1990,date("Y"));

		if(form::submitted())
		{
			## prepare IC.
			$ic	= str_replace("-","",input::get("userIC"));## remove '-' if got.

			$rules	= Array(
					"except:checkPenduduk,checkTerm"=>"required:Lapangan ini diperlukan.",
					"userIC"=>Array(
							 "callback"=>Array(!model::load("user/services")->checkIC($ic),"I.C. ini telah didaftarkan.")
									)
							);

			$checkPenduduk	= input::get("checkPenduduk")?true:false;
			$checkTerm		= input::get("checkTerm")?true:false;

			## if got error.
			if(($error = input::validate($rules)) || !$checkPenduduk || !$checkTerm)
			{
				if(!$checkPenduduk)
				{
					$error['checkPenduduk']	= "Hanya penduduk di kampung ini sahaja boleh berdaftar.";
				}

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
			$data	= Array(
					"userPassword"=>input::get("userPassword"),
					"userProfileDOB"=>input::get("birthday_year")."-".input::get("birthday_month")."-".input::get("birthday_day")
							);

			## register.
			model::load("user/user")->registerMember($ic,$data);

			## success and redirect.
			redirect::to("{site-slug}/registration#horizontalTab1","<br>Anda telah berjaya di daftarkan.");
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
}


?>