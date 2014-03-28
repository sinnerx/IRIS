<?php

Class Controller_Site
{
	## list of site.
	public function index($page = 1)
	{
		pagination::setFormat(Array(
						"html_wrapper"=>"<ul class='pagination pagination-sm m-t-none m-b-none pull-right'>{content}</ul>",
						"html_number"=>"<li><a href='{href}'>{number}</a></li>",
						"html_previous"=>"<li><a href='{href}'><i class='fa fa-chevron-left'></i></a></li>",
						"html_next"=>"<li><a href='{href}'><i class='fa fa-chevron-right'></i></a></li>"
									));

		$site	= model::load("site");

		db::from("site");
		db::join("user_profile","user_profile.userID IN (SELECT userID FROM site_manager WHERE siteID = site.siteID)");
		pagination::initiate(Array(
						"totalRow"=>db::num_rows(),
						"currentPage"=>$page,
						"limit"=>10,
						"urlFormat"=>url::base("site/index/{page}",true)
								));
		db::limit(pagination::get("limit"),$page-1);
		$data['res_site']	= db::get()->result();
		$data['totalRow']	= pagination::get("totalRow");

		view::render("site/index",$data);
	}

	public function test()
	{
		$data	= Array(
				"kaka"=>"val1",
				"kiki"=>"val2"
						);


		view::render('test');
	}

	public function add()
	{
		if(form::submitted())
		{
			## load site model.
			$site			= model::load("site");

			## site name.
			$siteName	= input::get("siteName");
			$manager	= input::get("manager");
			$siteSlug	= input::get("siteSlug");
			
			$managerCheck	= $site->checkManager($manager);
			$siteSlugCheck	= $site->checkSiteSlug($siteSlug)?Array(false,"Slug already exists."):Array(true);

			$rules	= Array(
					"siteName,siteSlug,manager"=>"required:This field is required.",
					"manager"=>Array(
							"email"=>"email:Please write a correct email format",
							"callback"=>Array($managerCheck[0],$managerCheck[1])
									),
					"siteSlug"=>Array(
							"min_length(5):length must be longer than {length}",
							"callback"=>Array($siteSlugCheck[0],$siteSlugCheck[1])
									)
							);

			## error
			if($error = input::validate($rules))
			{
				## wrap with template span-error.
				$error	= model::load("template")->wrap("input-error",$error);

				input::repopulate();
				redirect::withFlash($error);
				redirect::to();
			}

			## populate data, with value from input.
			$data	= input::get();
			$data['userID']	= $managerCheck[1];

			## create site.
			$site->createSite($data);
			redirect::to("","New site has been added!.");
		}

		$data['stateR']	= model::load("helper")->state();

		view::render("site/add",$data);
	}

	public function info()
	{
		$data['row_site']	= model::load("site")->getSiteByManager();

		view::render("site/info",$data);
	}

	public function edit()
	{
		$site			= model::load("site");
		$data['row']	= $site->getSiteByManager();

		if(form::submitted())
		{
			$data_site	= input::get();
			$site->updateSiteInfo($data['row']['siteID'],$data_site);

			redirect::to("site/edit","Site has been updated.");
		}

		view::render("site/edit",$data);
	}

	public function slider()
	{
		$site	= model::load("site");
		$siteID	= $site->getSiteByManager(session::get("userID"),"siteID");

		## add form submitted.	
		if(form::submitted())
		{
			$error	= input::validate(Array(
								"_all"=>"required:This field is required."
											));

			## file check.
			$FILE	= input::file("siteSliderImage");

			if(!$FILE)
			{
				$error['siteSliderImage']	= "No file was choosen.";
			}
			else
			{
				if(!$FILE->isExt("jpeg,jpg,png,bmp"))
				{
					$ext	= $FILE->get("ext");
					$error['siteSliderImage']	= "Please choose only jpg, png or bmp.";
				}
			}

			## if got error.
			if($error)
			{
				flash::set(model::load("template")->wrap("input-error",$error));
				input::repopulate();
				redirect::to("","Looks like there's some error in your form..","error");
			}

			## upload images.
			$file_name	= time().".".$FILE->get("ext");
			$file_path	= path::asset("frontend/images/slider");

			## Move uploaded file to the path.
			if(!$FILE->move($file_path,$file_name))
			{
				input::repopulate();
				redirect::to("","Failed to upload.","error");
			}

			## equate with _POST
			$data	= input::get();
			$data['siteSliderImage']	= $file_name;

			## execute add slider.
			$site->addSlider($siteID,$data);

			redirect::to("site/slider#","Successfully added a new slider.");
		}

		## if deactivated.
		if(request::get("toggle"))
		{			
			## execute remove slider.
			$id		= request::get("toggle");
			$site->toggleSlider($id);

			redirect::to("site/slider","Updated slider.");
		}

		$data['res_slider']	= $site->getSlider($siteID);
		view::render("site/slider",$data);
	}

	public function slider_add()
	{
		view::render("site/slider_add");
	}
}



?>