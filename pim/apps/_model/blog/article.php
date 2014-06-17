<?php
namespace model\blog;
use db, session, model, pagination, url;

class Article
{
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
								"limit"=>6,				
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

	public function slugChecker($slug,$date,$articleID=null)
	{
		db::from("article");
		db::where("articleOriginalSlug = '".$slug."' AND YEAR(articleCreatedDate) = '".date('Y', strtotime($date))."' AND MONTH(articleCreatedDate) = '".date('n', strtotime($date))."'");
		
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
		$articleSlug = $this->slugChecker($originalSlug,$data['articlePublishedDate']);

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
		$slug	= !$slug?reqs::named("site-slug"):$slug;

		$slug	= trim($slug);
		db::from("article");
		db::where("articleSlug = '".$slug."' AND YEAR(articlePublishedDate) = '".$year."' AND MONTH(articlePublishedDate) = '".$month."'");
		$articleID	= db::get()->row('articleID');

		return $articleID?$articleID:false;
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
}


?>