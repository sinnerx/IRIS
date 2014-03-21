<?php

Class Controller_Site
{
	public function index()
	{
		view::render("site/index");
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

		view::render("site/add");
	}

	public function info()
	{
		$data['row_site']	= model::load("site")->getSiteByManager();

		view::render("site/info",$data);
	}

	public function edit()
	{
		view::render("site/edit");
	}
}



?>