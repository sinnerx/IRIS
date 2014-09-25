<?php
class Controller_Forum
{
	public function __construct()
	{
		$this->siteID	= authData("site.siteID");
	}

	public function index()
	{
		$data['res_category_one']	= model::load("forum/category")->getCategory($this->siteID);
		view::render("sitemanager/forum/index",$data);
	}

	public function addCategory()
	{
		if(form::submitted())
		{
			$forumCategory	= model::load("forum/category");

			$rules	= Array(
					"threadCategoryTitle"=>Array(
						"required:This field is required",
						"callback"=>Array(!$forumCategory->checkTitle($this->siteID,input::get("threadCategoryTitle")),"Title already exists."),
						)
							);

			if($error = input::validate($rules))
			{
				redirect::to("","Please complete your form.","error");
			}

			## insert.
			$forumCategory->create($this->siteID,input::get("threadCategoryTitle"),input::get("threadCategoryDescription"));

			redirect::to("forum/index","New category has successfully been added, and is pending for approval.");
		}

		view::render("sitemanager/forum/addCategory");
	}
}



?>