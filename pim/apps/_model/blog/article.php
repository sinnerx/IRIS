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

		return db::get()->result("articleID");
	}

	# add an article on a blog
	public function addArticle($siteID,$data)
	{
		$status = session::get('userLevel') == 99?1:0;

		$dataArticle	= Array(
				"siteID"=>$siteID,
				"articleStatus"=>$status,
				"articleName"=>$data['articleName'],
				"articleText"=>$data['articleText'],
				"articlePublishedDate"=>$data['articlePublishedDate'],
				"articleCreatedUser"=>session::get("userID"),
				"articleCreatedDate"=>now()
						);
		
		db::insert("article",$dataArticle);

		$id = db::getLastID('article', 'articleID');

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
		
		if( session::get('userLevel') == 2)	{
			model::load("site/request")->create('article.add', $siteID, $id, Array());
		}
	}

	# return an array of articleS
	public function getArticleTag($articleID)
	{
		db::from("article_tag");

		# get by articleID
		db::where("articleID", $articleID);

		db::order_by("articleTagID ASC");

		return db::get()->result();
	}

	# return only an article
	public function getArticle($articleID)
	{
		db::from("article");
		db::where("articleID",$articleID);

		return db::get()->row();
	}

	#updating an article
	public function updateArticle($articleID,$data)
	{
		$data['articleUpdatedDate'] = now();
		$data['articleUpdatedUser'] = session::get('userID');

		## only if no add request pending.
		if(!model::load("site/request")->checkRequest("article.add",$data['siteID'],$articleID))
		{
			model::load("site/request")->create('article.update', $data['siteID'], $articleID, $data);
		}
		## if exists, just update announcement tabel.
		else
		{
			db::where("articleID",$articleID)->update("article",$data);
		}
	}
}


?>