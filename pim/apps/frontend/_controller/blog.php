<?php
Class Controller_Blog
{
	public function article()
	{
		$siteArticle	= model::load("blog/article");
		$siteID	= model::load("site/site")->getSiteIDBySlug(request::named("site-slug"));

		$data['article']	= $siteArticle->getArticleList($siteID, true);

		foreach ($data['article'] as $article => $row) {
			$data['article'][$article]['category'] = model::load("blog/category")->getArticleCategoryList($row['articleID']);
		}

		view::render("blog/article", $data);
	}

	public function view()
	{
		$articleID	= model::load("blog/article")->getArticleIDBySlug(request::named('article-slug'), request::named('year'), request::named('month'));
		$data['article']	= model::load("blog/article")->getArticle($articleID);
		$data['articleTags'] = model::load("blog/article")->getArticleTag($data['article']['articleID']);

		$data['activity'] = model::load("blog/article")->getActivityArticle($data['article']['articleID']);
		$data['activity'][0]['data'] = model::load("activity/activity")->getActivity($data['activity'][0]['activityID']);
		$data['category'] = model::load("blog/category")->getArticleCategoryList($data['article']['articleID']);

		view::render("blog/view",$data);
	}
}
?>