<?php

class Controller_Ajax_Article
{
	public function lists()
	{
		// select all article, except those already chosen as report.
		db::from('article');
		db::where('articleID NOT IN (SELECT articleID FROM activity_article)');
		$data['articles'] = db::get()->result();

		view::render('sitemanager/article/ajax/lists', $data);
	}
}