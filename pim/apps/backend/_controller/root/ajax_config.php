<?php

class Controller_Ajax_Config
{
	public function changeForm($name)
	{
		$value	= model::load("config")->get($name);

		$data['configName']	= $name;
		$data['value']	= $value;

		switch($name)
		{
			case "configNewsCategoryID":

			### build categoryListR. Get cat list and build one for <select>.
			$res_cat	= model::load("blog/category")->getCategoryList();

			$catR	= Array();
			foreach($res_cat as $row)
			{
				$catR[$row['categoryID']]	= $row['categoryName'];

				if($row['child'])
				{
					foreach($row['child'] as $row_child)
					{
						$catR[$row_child['categoryID']] = "- $row_child[categoryName]";
					}
				}
			}
			$data['catR']	= $catR;
			### /build categoryListR

			break;
			case "configManagerSiteID":
			$res_site	= model::load("site/site")->lists();

			foreach($res_site as $row)
			{
				$data['siteR'][$row['siteID']]	= $row['siteName'];
			}
			break;
		}

		view::render("root/config/ajax/changeForm",$data);
	}
}

?>