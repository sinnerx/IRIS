<?php
class Controller_Forum
{
	public function __construct()
	{
		$this->siteID	= authData("site.siteID");
	}

	public function index()
	{
		$data['res_latestthreads']	= model::load("forum/thread")->getLatestThreads($this->siteID);
		$data['res_category_one']	= model::load("forum/category")->getCategories($this->siteID);

		$data['requestData']	= model::load("site/request")->replaceWithRequestData("forum_category.update",array_keys($data['res_category_one']));

		print_r($data['categoryRequest']);
		view::render("sitemanager/forum/index",$data);
	}

	public function addCategory()
	{
		if(form::submitted())
		{
			$forumCategory	= model::load("forum/category");

			$rules	= Array(

					"forumCategoryTitle"=>Array(
						"required:This field is required",
						"callback"=>Array(!$forumCategory->checkTitle($this->siteID,input::get("forumCategoryTitle")),"Title already exists."),
						),
					"forumCategoryAccess"=>"required:This field is required"
							);

			if($error = input::validate($rules))
			{
				input::repopulate();
				redirect::withFlash(model::load("template/services")->wrap("input-error",$error));
				redirect::to("","Please complete your form.","error");
			}

			## insert.
			$forumCategory->create($this->siteID,input::get("forumCategoryTitle"),input::get("forumCategoryAccess"),input::get("forumCategoryDescription"));

			redirect::to("forum/index","New category has successfully been added, and is pending for approval.");
		}

		view::render("sitemanager/forum/addCategory");
	}

	public function updateCategory($catID)
	{
		$data['row']	= model::load("forum/category")->getCategory($catID);

		$data['hasRequest'] =  model::load("site/request")->replaceWithRequestData("forum_category.update",$catID,$data['row']);

		if(form::submitted())
		{
			$forumCategory	= model::load("forum/category");

			$rules	= Array(
				"forumCategoryTitle"=>Array(
						"required:This field is required",
						"callback"=>Array(!$forumCategory->checkTitle($this->siteID,input::get("forumCategoryTitle"),$catID),"Title already exists."),
						),
				"forumCategoryAccess"=>"required:This field is required",
							);

			if($error = input::validate($rules))
			{
				redirect::to("","Please complete your form.","error");
			}

			## update.
			$forumCategory->updateCategory($catID,input::get("forumCategoryTitle"),input::get("forumCategoryAccess"),input::get("forumCategoryDescription"));

			redirect::to("","Updated and waiting for clusterlead approval.");
		}

		view::render("sitemanager/forum/updateCategory",$data);
	}
}



?>