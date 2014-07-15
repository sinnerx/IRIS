<?php
Class Controller_Blog
{
	public function article()
	{
		$urlFormat	= url::base("{site-slug}/blog/?page={page}");
		
		return $this->articleRenderer($urlFormat,$data,null);
	}

	## used for route tag/{tag} or category/{category}
	public function articleByTagOrCategory($type,$val)
	{
		$where	= null;

		## if category sort.
		if($type == "category")
		{
			$data['typeSortBy']	= "kategori";
			$data['currCatID']	= $val; ## tu be used by <select>
			$row_cat	= model::load("blog/category")->getCategory($val,"categoryName");
			$data['typeSortByValue']	= $row_cat;

			$where['articleID IN (SELECT articleID FROM article_category WHERE categoryID = ?)'] = Array($val);
		}

		## if tag sort.
		if($type == "tag")
		{
			$data['typeSortBy']	= "tag";
			$data['typeSortByValue']	= $val;
			$where['articleID IN (SELECT articleID FROM article_tag WHERE articleTagName = ?)'] = Array($val);
		}

		$urlFormat	= url::base("{site-slug}/blog/$type/$val/?page={page}");

		// view::render("blog/article",$data);
		return $this->articleRenderer($urlFormat,$data,$where);
	}

	## used for route blog/year or blog/year/month
	public function articleByYearOrMonth($year,$month = null)
	{
		$where['year(articlePublishedDate)']	= $year;
		$data['dateSortBy']	= true;
		$data['year']		= $year;
		$urlFormat			= url::base("{site-slug}/blog/$year?page={page}");

		if($month)
		{
			$data['month']	= date("F",strtotime("2014-$month-1"));
			$where['month(articlePublishedDate)'] = $month;
			$urlFormat		= url::base("{site-slug}/blog/$year/".zeronating($month,2)."?page={page}");
		}

		return $this->articleRenderer($urlFormat,$data,$where);
	}

	## used for route blog/user/$userID
	public function articleByUser($userID)
	{
		$urlFormat	= url::base("{site-slug}/blog/user/$userID?page={page}");
		$row_user	= model::load("user/user")->get($userID);

		$data['userSortBy']	= true;
		$data['userProfileFullName'] = $row_user['userProfileFullName'];

		$where['articleCreatedUser'] = $userID;

		return $this->articleRenderer($urlFormat,$data,$where);
	}

	## re-factored. main logic renderer.
	private function articleRenderer($urlFormat,$data,$where)
	{
		$page	= request::get("page",1);
		$siteID	= authData("current_site.siteID");

		$paginConf['urlFormat']	= $urlFormat;
		$paginConf['currentPage']	= $page;
		$paginConf['limit']			= 3;

		pagination::setFormat(model::load("template/frontend")->paginationFormat());

		$data['article'] = model::load("blog/article")->getArticleList2($siteID,$paginConf,$where);

		if($data['article'])
		{
			$res_cat	= model::load("blog/category")->getArticleCategoryList2(array_keys($data['article']));
			$data['categoryR']	= $res_cat;
		}

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
		$data['categoryListR']	= $catR;
		### /build categoryListR

		view::render("blog/article",$data);
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