<?php
namespace model\blog;
use db, session, model, url;

class Rss
{
	# return all article list in a site
	public function getAllArticle($siteID,$frontend = false,$page = 1)
	{
		db::from("article");
		db::where("siteID = ? OR siteID = ?",array($siteID,0));
		db::where("articleApprovedStatus", 1);
		db::order_by("articlePublishedDate DESC, articleID DESC");

		$data = db::get()->result("articleID");

		return $data;
	}

	public function getArticlesByCategory($siteID,$categoryID)
	{
		db::from("category");
		db::select("categoryID");
		db::where("categoryID",$categoryID);
		$category_data = db::get()->result();

		db::from("article_category");
		db::where("categoryID",$category_data[0]['categoryID']);
		db::join("article","article.articleID = article_category.articleID");
		$data = db::get()->result("articleID");

		return $data;
	}
}


?>