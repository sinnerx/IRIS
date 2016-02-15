<?php

class Controller_Ajax_Article
{
	public function lists($activityId)
	{
		$data['activityId'] = $activityId;
		
		$month = $data['month'] = request::get('month', date('m'));
		$year = $data['year'] = request::get('year', date('Y'));

		if(request::post('articleID'))
		{
			$articleId = request::post('articleID');
			$type = request::post('type');

			db::insert('activity_article', array(
				'activityArticleType' => $type,
				'articleID' => $articleId,
				'activityID' => $activityId,
				'activityArticleCreatedDate' => now(),
				'activityArticleCreatedUser' => session::get('userID')
				));
		}

		// select all article, except those already chosen as report.
		db::from('article');
		db::where('siteID', authData('site.siteID'));
		db::where('MONTH(articlePublishedDate) = ?', array($month));
		db::where('YEAR(articlePublishedDate) = ?', array($year));
		db::where('articleID NOT IN (SELECT articleID FROM activity_article)');
		db::where('articleStatus', 1);
		$data['articles'] = db::get()->result();

		view::render('sitemanager/article/ajax/lists', $data);
	}
}