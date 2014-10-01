<?php

class Controller_Forum
{
	public function category()
	{
		$data['res_category']	= model::load("forum/category")->getCategories(0);

		view::render("root/forum/category",$data);
	}

	public function addCategory()
	{
		if(form::submitted())
		{
			$forumCategory	= model::load("forum/category");

			$rules	= Array(
					"forumCategoryTitle"=>Array(
						"required:This field is required",
						"callback"=>Array(!$forumCategory->checkTitle(0,input::get("forumCategoryTitle")),"Title already exists."),
										),
					"forumCategoryAccess"=>"required:This field is required"
							);

			if($errors = input::validate($rules))
			{
				print_r($errors);
				input::repopulate();
				redirect::withFlash(model::load("template/services")->wrap("input-error",$errors));
				redirect::to("","Error in your form.","error");
			}

			$forumCategory->create(0,input::get("forumCategoryTitle"),input::get("forumCategoryAccess"),input::get("forumCategoryDescription"));

			redirect::to("forum/category","Added new general category.");
		}

		view::render("root/forum/addCategory");
	}

	public function updateCategory($categoryID)
	{
		$data['row']	= model::load("forum/category")->getCategory($categoryID);

		if(form::submitted())
		{
			$forumCategory	= model::load("forum/category");

			$rules	= Array(
				"forumCategoryTitle"=>Array(
						"required:This field is required",
						"callback"=>Array(!$forumCategory->checkTitle($this->siteID,input::get("forumCategoryTitle"),$categoryID),"Title already exists."),
						),
				"forumCategoryAccess"=>"required:This field is required",
							);

			if($error = input::validate($rules))
			{
				redirect::to("","Please complete your form.","error");
			}

			## update.
			$forumCategory->updateCategory($categoryID,input::get("forumCategoryTitle"),input::get("forumCategoryAccess"),input::get("forumCategoryDescription"));

			redirect::to("","Category Updated.");
		}

		view::render("root/forum/updateCategory",$data);
	}
}


?>