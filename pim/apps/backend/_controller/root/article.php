<?php
class Controller_Article
{
	public function category(){
		$category	= model::load("blog/category");

		## manager.
		$data['category']	= $category->getCategoryList();

		view::render("root/article/category",$data);
	}
}
?>