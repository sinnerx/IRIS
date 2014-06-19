<?php
class Controller_Article
{
	public function category(){
		$category	= model::load("blog/category");

		## manager.
		$data['res_category']	= $category->getCategoryList();

		view::render("root/article/category",$data);
	}

	public function category_add()
	{
		if(request::isAjax())
		{
			$this->template	= false;
		}

		if(form::submitted())
		{
			$name	= input::get("categoryName");
			if(model::load("blog/category")->add($name))
				return true;
		}

		view::render("root/article/category_add");
	}

	public function category_edit($catID)
	{
		if(request::isAjax())
		{
			$this->template	= false;
		}

		if(form::submitted())
		{
			$name	= input::get("categoryName");
			if(model::load("blog/category")->categoryRename($catID,$name))
				return $name;
		}

		$data['row']	= model::load("blog/category")->getCategory($catID);
		view::render("root/article/category_edit",$data);
	}

	public function category_addchild($parentID)
	{
		if(request::isAjax())
		{
			$this->template = false;
		}

		if(form::submitted())
		{
			$name	= input::get("categoryName");

			if(model::load("blog/category")->addChild($parentID,$name))
				return true;
		}

		$data['row']	= model::load("blog/category")->getCategory($parentID);
		view::render("root/article/category_addchild",$data);
	}

	public function category_delete($catID)
	{
		$res = model::load("blog/category")->delete($catID);

		return response::json($res);
	}
}
?>