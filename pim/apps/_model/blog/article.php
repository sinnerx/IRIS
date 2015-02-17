<?php
namespace model\blog;
use db, session, model, pagination, url, request as reqs, apps;

class Article extends \Origami
{
	protected $table = "article";
	protected $primary = "articleID";

	/**
	 * ORM : Simplify article text
	 */
	public function getSimplifiedText($length = 100)
	{
		$stripped = strip_tags($this->articleText);
		return substr($stripped, 0, $length);
	}

	/**
	 * ORM : get frontend link for $this article.
	 */
	public function getFrontendLink()
	{
		$siteSlug = $this->getSite()->siteSlug;
		$base_url = 'http://'.apps::config('base_url:frontend').'/'.$siteSlug.'/blog';
		$url = model::load("helper")->buildDateBasedUrl($this->articleSlug, $this->articlePublishedDate, $base_url);

		return $url;
	}

	/**
	 * ORM : Get site
	 */
	public function getSite()
	{
		return $this->getOne('site/site', 'siteID');
	}

	# return an array of article list
	public function getArticleList($siteID,$frontend = false,$page = 1)
	{
		db::from("article");

		# get by siteID.
		if($siteID === 0)
		{
			db::where("siteID",0);
		}
		else
		{
			if($frontend == false){
				db::where("siteID = '$siteID'");
				## paginate based on current query built.
				pagination::setFormat(model::load('template/cssbootstrap')->paginationLink());
				pagination::initiate(Array(
								"totalRow"=>db::num_rows(), 
								"limit"=>10,				
								"urlFormat"=>url::base("site/article/{page}"),
								"currentPage"=>$page
										));

				## limit, and offset.
				db::limit(pagination::get("limit"),pagination::recordNo()-1); 
			}else{
				db::where("articleStatus = '1' AND siteID = '$siteID' OR siteID = '0'");
				db::where("date(articlePublishedDate) <",date('Y-m-d', strtotime(now(). ' + 1 days')));
			}
		}

		db::order_by("articlePublishedDate DESC, articleID DESC");

		$data = db::get()->result("articleID");

		if($frontend == true){
			foreach ($data as $article => $row) {
				db::from('user_profile');

				if($article['articleCreatedUser']){
					db::where("userID",$row['articleCreatedUser']);
				}else{
					db::where("userID",$row['articleUpdatedUser']);
				}

				$user_profile = db::get()->row();

				$data[$article]['articleCreatedUser'] = $user_profile['userProfileFullName'];
			}
		}

		return $data;
	}

	public function getArticleList2($siteID,$pageConf = null,$where = null,$join = null)
	{
		db::from("article");
		db::where("siteID",$siteID);
		db::where("articleStatus",1);
		db::where("date(articlePublishedDate) <",date("Y-m-d",strtotime("+1 days",time())));

		## additional where clause.
		if($where)
		{
			foreach($where as $key=>$val)
			{
				db::where($key,$val);
			}
		}

		## pagination.
		if($pageConf)
		{
			pagination::initiate(Array(
								"urlFormat"=>$pageConf['urlFormat'],
								"totalRow"=>db::num_rows(),
								"limit"=>$pageConf['limit'],
								"currentPage"=>$pageConf['currentPage']
										));

			db::limit($pageConf['limit'],pagination::recordNo()-1);
		}

		## join
		if($join)
		{
			foreach($join as $key=>$val)
			{
				db::join($key,$val);
			}
		}

		db::join("user_profile","user_profile.userID = article.articleCreatedUser");
		db::order_by("articlePublishedDate DESC, articleID DESC");

		return db::get()->result("articleID");
	}

	public function slugChecker($slug,$date,$articleID=null)
	{
		db::from("article");
		db::where("articleOriginalSlug",$slug);
		db::where("year(articlePublishedDate)",date("Y",strtotime($date)));
		db::where("month(articlePublishedDate)",date("n",strtotime($date)));
		// db::where("articleOriginalSlug = '".$slug."' AND YEAR(articlePublishedDate) = '".date('Y', strtotime($date))."' AND MONTH(articlePublishedDate) = '".date('n', strtotime($date))."'");
		
		if($articleID)
		{
			db::where("articleID !=".$articleID);
		}

		if($result=db::get()->result())
		{
			$slug = $slug.'-'.(count($result)+1);
		}

		return $slug;
	}

	public function addDraftedArticle($articleID,$data = null){

		//if data is passed, update curent drafted content.
		if($data)
		{
			$this->_updateArticle($articleID,$data);
		}

		// change status to pending.
		db::where("articleID",$articleID)->update('article',Array("articleStatus"=>0));

		// create article.add request.
		model::load("site/request")->create('article.add', $data['siteID'], $articleID, Array());
	}

	public function updateDraft($articleID,$data)
	{
		$this->_updateArticle($articleID,$data);
	}

	private function _updateArticle($articleID,$data,$withoutOthers = false){
		$originalSlug = model::load('helper')->slugify($data['articleName']);
		$articleSlug = $this->slugChecker($originalSlug,$data['articlePublishedDate'],$articleID);

		$dataArticle	= Array(
				"articleStatus"=>$data['articleStatus'],
				"articleName"=>$data['articleName'],
				"articleText"=>$data['articleText'],
				"articleOriginalSlug"=>$originalSlug,
				"articleSlug"=>$articleSlug,
				"articleUpdatedDate"=>now(),
				"articleUpdatedUser"=>session::get("userID")
				);

		db::where('articleID',$articleID)->update('article',$dataArticle);

		if($withoutOthers)
		{
			return;
		}

		if($data['articleTags']){
			db::delete('article_tag', 'articleID='.$articleID);

			$data['articleTags'] = strtok($data['articleTags'],',');

			while ($data['articleTags'] != false)
		    {
				$dataToken = Array(
					"articleID"=>$articleID,
					"articleTagName"=>$data['articleTags']
				);

				db::insert("article_tag",$dataToken);

		    	$data['articleTags'] = strtok(",");
			}
			unset($data['articleTags']);
		}
		
		if($data['category']){		
			db::delete('article_category', 'articleID='.$articleID);
			foreach($data['category'] as $category){
				$dataToken = Array(
					"articleID"=>$articleID,
					"categoryID"=>$category
				);

				db::insert("article_category",$dataToken);
			}
			unset($data['category']);
		}else{
			db::delete('article_category', 'articleID='.$articleID);
		}

		if(!is_null($data['activityID'])){
			db::from('activity_article');
			db::where('articleID',$articleID);

			if(db::get()->row()){
				$dataActivity = Array(
						"activityID" => $data['activityID'],
						"activityArticleType" => $data['activityArticleType']
				);
				db::where("articleID",$articleID)->update("activity_article",$dataActivity);
			}else{
				$dataActivity = Array(
					"articleID" => $articleID,
					"activityID" => $data['activityID'],
					"activityArticleType" => $data['activityArticleType'],
					"activityArticleCreatedDate" => now(),
					"activityArticleCreatedUser" => session::get("userID")
				);
				db::insert("activity_article", $dataActivity);
			}
			unset($data['activityID']);
			unset($data['activityArticleType']);
		}
	}

	# add an article on a blog
	public function addArticle($siteID,$data)
	{
		$originalSlug = model::load('helper')->slugify($data['articleName']);
		$articleSlug = $this->slugChecker($originalSlug,$data['articlePublishedDate']);

		$dataArticle	= Array(
				"siteID"=>$siteID,
				"articleStatus"=>$data['articleStatus'],
				"articleName"=>$data['articleName'],
				"articleText"=>$data['articleText'],
				"articleOriginalSlug"=>$originalSlug,
				"articleSlug"=>$articleSlug,
				"articlePublishedDate"=>$data['articlePublishedDate'],
				"articleCreatedUser"=>session::get("userID"),
				"articleCreatedDate"=>now()
						);
		
		db::insert("article",$dataArticle);

		$id = db::getLastID('article', 'articleID');

		if($data['articleTags']){
			$data['articleTags'] = strtok($data['articleTags'],',');

			while ($data['articleTags'] != false)
	    	{
				$dataToken = Array(
					"articleID"=>$id,
					"articleTagName"=>$data['articleTags']
				);

				db::insert("article_tag",$dataToken);

	    		$data['articleTags'] = strtok(",");
			}
		}
		
		if($data['category']){	
			foreach($data['category'] as $category){
				$dataToken = Array(
					"articleID"=>$id,
					"categoryID"=>$category
				);

				db::insert("article_category",$dataToken);
			}
		}

		if($data['activityID']){
			$dataActivity = Array(
					"articleID" => $id,
					"activityID" => $data['activityID'],
					"activityArticleType" => $data['activityArticleType'],
					"activityArticleCreatedDate" => now(),
					"activityArticleCreatedUser" => session::get("userID")
				);
			db::insert("activity_article", $dataActivity);
		}
		
		if(session::get('userLevel') == 2 && $data['articleStatus'] == 0)	{
			model::load("site/request")->create('article.add', $siteID, $id, Array());
		}
	}

	# return an array of articleS
	public function getArticleTag($articleID)
	{
		if($articleID){
			db::from("article_tag");

			# get by articleID
			db::where("articleID", $articleID);

			db::order_by("articleTagID ASC");

			if(is_array($articleID)){
				return db::get()->result("articleID", true);
			}else{
				return db::get()->result();
			}
		}else{
			return null;
		}

	}

	# return a single of activity_article
	public function getActivityArticle($articleID)
	{
		db::from("activity_article");

		# get by articleID
		db::where("articleID", $articleID);

		return db::get()->result();
	}

	# return only an article
	public function getArticle($articleID)
	{
		db::from("article");
		db::where("articleID",$articleID);

		$article = db::get()->row();

		db::from('user_profile');

		if($article['articleCreatedUser']){
			db::where("userID",$article['articleCreatedUser']);
		}else{
			db::where("userID",$article['articleUpdatedUser']);
		}

		$user_profile = db::get()->row();

		$article['articleEditedUser'] = $user_profile['userProfileFullName'];

		return $article;
	}

	public function getArticleIDBySlug($slug = null, $year = null, $month = null)
	{
		$slug	= trim($slug);
		db::from("article");
		// db::where("articleSlug = '".$slug."' AND YEAR(articlePublishedDate) = '".$year."' AND MONTH(articlePublishedDate) = '".$month."'");
		db::where('articleSlug', $slug);
		db::where('YEAR(articlePublishedDate)', $year);
		db::where('MONTH(articlePublishedDate)', $month);
		$articleID	= db::get()->row('articleID');

		return $articleID?$articleID:false;
	}


#	list all available activity report for that site,year and month
	public function getReportBySiteID($siteID = null, $year = null, $month = null, $articleID = null)
	{
			

		db::from("article");		


if ($articleID) {

		db::where('article.articleID', $articleID);

}	else {


		db::where('article.siteID', $siteID);
		db::where('YEAR(articlePublishedDate)', $year);
		db::where('MONTH(articlePublishedDate)', $month);
}

		
		db::join("activity_article","article.articleID = activity_article.articleID");
		db::join("activity","activity_article.activityID = activity.activityID");
		

		return db::get()->result();
		
	}

	/**
	 * get all available activity reprot for that year and month
	 * @param int year
	 * @param int month
	 * @return array of result
	 */
	public function getAllSiteReport($year, $month)
	{
		db::from("activity_article");

		db::join("activity","activity_article.activityID = activity.activityID");
		db::join("article","activity_article.articleID = article.articleID");
		
		db::where('activity_article.articleID IN (SELECT articleID FROM article WHERE articleStatus = ?)', array(1));
		db::where('activity.activityType',1);
		db::where('year(activity.activityStartDate)', $year);
		db::where('month(activity.activityStartDate)', $month);
		

		return db::get()->result();
	}

	/**
	 * Get all of approval pending report for the given year/month.
	 * @return int
	 */
	public function getTotalApprovalPendingReport($year, $month)
	{
		db::from('activity_article');
		db::where('articleID IN (SELECT articleID FROM article WHERE articleStatus = ?)', array(0));
		db::where('activityID IN (SELECT activityID FROM activity WHERE month(activityStartDate) = ? AND year(activityStartDate) = ?)', array($month, $year));
		return db::get()->num_rows();
	}

	/**
	 * Get total of non recent report.
	 * @return int
	 */
	public function getTotalOfNonrecentReport($year, $month)
	{
		db::from('activity_article');
		db::where('activityID IN (SELECT activityID FROM activity WHERE month(activityStartDate) = ? AND year(activityStartDate) = ?)', array($month, $year));

		// check if there's existing unapproved site request for article.
		$result = db::get()->result('articleID');

		if(count($result) > 0)
		{
			// get list of article id.
			$articleIds = array_keys($result);
			db::where('siteRequestType', 'article.update');
			db::where('siteRequestRefID', $articleIds);
			db::where('siteRequestStatus', 0);
			db::get('site_request');

			return db::num_rows();
		}

		return 0;
	}

#	get single report
	public function getReportByActivityID($siteID = null, $year = null, $month = null)
	{
			

		db::from("article");		
		db::where('article.siteID', $siteID);
	
		db::join("activity_article","article.articleID = activity_article.articleID");
		db::join("activity","activity_article.activityID = activity.activityID");


		db::where('YEAR(activity.activityStartDate)', $year);
		db::where('MONTH(activity.activityStartDate)', $month);
		

		return db::get()->result();
		
	}


	#updating an article
	public function updateArticle($articleID,$data)
	{
		if($data['articleTags']){
			db::delete('article_tag', 'articleID='.$articleID);

			$data['articleTags'] = strtok($data['articleTags'],',');

			while ($data['articleTags'] != false)
		    {
				$dataToken = Array(
					"articleID"=>$articleID,
					"articleTagName"=>$data['articleTags']
				);

				db::insert("article_tag",$dataToken);

		    	$data['articleTags'] = strtok(",");
			}
			unset($data['articleTags']);
		}
		
		if($data['category']){

			db::delete('article_category', 'articleID='.$articleID);
			foreach($data['category'] as $category){
				$dataToken = Array(
					"articleID"=>$articleID,
					"categoryID"=>$category
				);

				db::insert("article_category",$dataToken);
			}
			unset($data['category']);
		}else{
			db::delete('article_category', 'articleID='.$articleID);
		}

		if(!is_null($data['activityID'])){
			db::from('activity_article');
			db::where('articleID',$articleID);

			if(db::get()->row()){
				$dataActivity = Array(
						"activityID" => $data['activityID'],
						"activityArticleType" => $data['activityArticleType']
				);
				db::where("articleID",$articleID)->update("activity_article",$dataActivity);
			}else{
				$dataActivity = Array(
					"articleID" => $articleID,
					"activityID" => $data['activityID'],
					"activityArticleType" => $data['activityArticleType'],
					"activityArticleCreatedDate" => now(),
					"activityArticleCreatedUser" => session::get("userID")
				);
				db::insert("activity_article", $dataActivity);
			}
			unset($data['activityID']);
			unset($data['activityArticleType']);
		}

		## if there're still got add request pending.
		if(model::load("site/request")->checkRequest("article.add",$data['siteID'],$articleID)){
			$this->_updateArticle($articleID,$data,true);
		}else{
			unset($data['articleTags']);
			unset($data['category']);
			unset($data['activityID']);
			unset($data['activityArticleType']);
			model::load("site/request")->create('article.update', $data['siteID'], $articleID, $data);
		}

		

		## only if no add request pending.
		// if(!model::load("site/request")->checkRequest("article.add",$data['siteID'],$articleID)/* && $data['articleStatus'] == 0 || $data['articleStatus'] == 4*/)
		// {
		// 	db::where("articleID",$articleID)->update("article",$data);
			
		// 	if($data['articleStatus'] == 0){
		// 		model::load("site/request")->create('article.add', $data['siteID'], $articleID, Array());
		// 	}
		// 	$article = $data;
		// 	$article['articleStatus'] = 1;
		// 	model::load("site/request")->create('article.update', $data['siteID'], $articleID, $article);
			
		// 	if($data['articleStatus'] == 0){
		// 		$status = Array("articleStatus"=>0);
		// 	}else{
		// 		$status = Array("articleStatus"=>4);
		// 	}

		// 	db::where("articleID",$articleID)->update("article",$status);
		// }
		// ## if exists, just update article table.
		// else
		// {
		// 	db::where("articleID",$articleID)->update("article",$data);
		// 	/*}else if($data['articleStatus'] == 0){
		// 		model::load("site/request")->create('article.update', $data['siteID'], $articleID, $article);
		// 	}*/
		// 	/*db::where("articleID",$articleID)->update("article",$data);

		// 	if($data['articleStatus'] == 0){
		// 		model::load("site/request")->create('article.add', $siteID, $id, Array());
		// 	}*/
		// }
	}

	public function checkDraft($articleID)
	{
		if($row = db::where("articleID",$articleID)->get("article_draft")->row())
			return $row;

		return false;
	}

	public function cancelDraft($articleID)
	{
		db::delete("article_draft",Array("articleID"=>$articleID));
	}

	/*public function getArticlesByCategoryName($siteID=0,$categoryName){
		db::select("categoryID");
		db::where("categoryName",$categoryName);
		$categoryID = db::get("category")->row("categoryID");

		db::from("article_category,article,site");
		db::select("article.articleName,article.articlePublishedDate,article.articleSlug,site.siteSlug");
		db::where("article_category.categoryID = ".$categoryID." AND article.articleID = article_category.articleID AND article.articleStatus = 1 AND site.siteID = ".$siteID);
		db::order_by("articlePublishedDate DESC");
		db::limit(3);
		return db::get()->result();
	}*/

	public function getArticlesByCategoryID($siteID=0,$categoryID){
		db::from("article");
		db::select("article.articleName,article.articlePublishedDate,article.articleSlug,site.siteSlug");
		db::where("articleID IN (SELECT articleID FROM article_category WHERE categoryID = '$categoryID')");
		db::where("article.siteID",$siteID);
		db::where("articleStatus",1);
		db::where("articlePublishedDate <",date("Y-m-d"));
		#db::join("article","article.articleID = article_category.articleID AND article.articleStatus = '1'");
		db::join("site","site.siteID = article.siteID");
		#db::where("article_category.categoryID = ".$categoryID." AND article.articleID = article_category.articleID AND article.articleStatus = 1 AND site.siteID = ".$siteID);
		db::order_by("articlePublishedDate DESC");
		db::limit(3);
		return db::get()->result();
	}

	public function createArticleLink($articleSlug,$date = null,$siteSlug = null)
	{
		if(is_numeric($articleSlug))
		{
			$row			= db::where("articleID",$articleSlug)->get("article")->row();
			$date			= $row['articlePublishedDate'];
			$siteSlug		= db::where("siteID",$row['siteID'])->get('site')->row("siteSlug");
			$articleSlug	= $row['articleSlug'];
		}

		list($year,$month)	= explode("-",date("Y-m",strtotime($date)));
		return url::createByRoute("article-view",Array(
			"site-slug"=>$siteSlug,
			"year"=>$year,
			"month"=>$month,
			"article-slug"=>$articleSlug
			));
	}
}


?>