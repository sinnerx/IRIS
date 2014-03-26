<?php
Class Controller_Page
{
	var $template	= "default";
	public function index()
	{
		$trail	= request::named("trail");
		$page	= model::load("page");
		$siteID	= model::load("site")->getSiteIDBySlug();

		$lastPageID	= null;
		$no			= 1;

		## had to do query this way, sorry.
		foreach(explode("/",$trail) as $slug)
		{
			db::from("page");
			db::where("siteID",$siteID);
			if($lastPageID)
			{
				db::where("pageID IN (SELECT pageID FROM page_reference WHERE pageParentID = '$lastPageID')");
			}

			db::where("pageSlug = '$slug' OR pageDefaultType IN (SELECT pageDefaultType FROM page_default WHERE pageDefaultSlug = '$slug')");

			$row	= db::get()->row();

			if(!$row)
			{
				redirect::to("404","Couldn't find the page you're looking for.","error");
			}

			if($no == count(explode("/",$trail)))
			{
				$row_page	= $row;
			}

			$no++;
		}

		$defaultR	= $page->getDefault();
		$data['row_page']	= $row_page;

		$data['title']		= $row_page['pageType'] == 1?$defaultR[$row_page['pageDefaultType']]['pageDefaultName']:$row_page['pageName'];

		view::render("page/index",$data);
	}
}

?>