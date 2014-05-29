<?php
Class Controller_Blog
{
	public function article()
	{
		$siteArticle	= model::load("blog/article");
		$siteID	= model::load("site/site")->getSiteIDBySlug(request::named("site-slug"));

		$data['article']	= $siteArticle->getArticleList($siteID, true);

		for($i=0;$i<count($data['article']);$i++){
			$tag = $siteArticle->getArticleTag($data['article'][$i]['articleID']);
			$user = model::load("user/user")->get($data['article'][$i]['articleCreatedUser']);

			$data['article'][$i]['articleCreatedUser'] = $user['userProfileFullName'];

			for($j=0;$j<count($tag);$j++){
				$data['article'][$i]['articleTags'] = $tag;
			}
		}

		view::render("blog/article", $data);
	}
}
?>