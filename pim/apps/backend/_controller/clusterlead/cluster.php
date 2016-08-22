<?php
class Controller_Cluster
{
	public function __construct()
	{
		## get clusterID
		$cluster	= model::load("site/cluster")->getClusterByUser(session::get("userID"));
		$this->clusterID	= $cluster['clusterID'];
	}

	public function overview()
	{
		$data['res_sites']	= model::load("site/site")->getSitesByClusterLead(session::get("userID"))->result("stateID",true);
		$data['stateR']		= model::load("helper")->state();
		$data['requestR']	= model::load("site/request")->getRequestByCluster($this->clusterID)->result("siteID",true);
		view::render("clusterlead/cluster/overview",$data);
	}

	public function test()
	{
		$mailingServices	= model::load("mailing/services");
	}

	# edit article
	public function editArticle($articleID,$articleRequestID)
	{
		$data['row']	= model::load("blog/article")->getArticle($articleID);
		$data['gotreqs'] =model::load('site/request')->replaceWithRequestData('article.update', $articleID,$data['row']);
		$data['row']['articleTags'] = model::load("blog/article")->getArticleTag($data['row']['articleID']);

		if(form::submitted())
		{
			$rules	= Array(
						"articleName"=>"required:This field is required.",
						"articleText"=>"required:This field is required.",
						"articlePublishedDate"=>"required:This field is required."
							);

			## got validation error.
			if($error = input::validate($rules))
			{
				input::repopulate();
				redirect::withFlash(model::load("template/services")->wrap("input-error",$error));
				redirect::to("","Got some error in your form.","error");
			}

			## populate into data.
			$postdata = input::get();
			$postdata['articlePublishedDate'] = date('Y-m-d',strtotime($postdata['articlePublishedDate']));
			$postdata['siteID'] = $data['row']['siteID'];
			if($postdata['articleStatus'] == 'Save as draft'){
				$postdata['articleStatus']	= 3;
				model::load('blog/article')->updateDraft($articleID,$postdata);
			}else{
				if($data['row']['articleStatus'] == 3){
					$postdata['articleStatus'] = 0;
					model::load('blog/article')->addDraftedArticle($articleID,$postdata);
				}else{
					$postdata['articleStatus'] = $data['row']['articleStatus'];
					model::load('blog/article')->updateAndApproveArticle($articleID,$articleRequestID,$postdata);
				}
			}
			# $postdata['articleStatus'] = $data['row']['articleStatus'] == 1? 4:$postdata['articleStatus']; 
			# echo '<pre>';print_r($postdata);die;
			
			redirect::to("cluster/overview","Article Updated");
		}

		$data['category'] = model::load("blog/category")->getArticleCategoryList($data['row']['articleID']);
		$data['activity'] = model::load("blog/article")->getActivityArticle($data['row']['articleID']);
		$data['activity'][0]['data'] = model::load("activity/activity")->getActivity($data['activity'][0]['activityID']);

		# echo '<pre>';print_r($data);die;
		view::render("clusterlead/cluster/editArticle",$data);
	}

	public function edit($activityID,$activityRequestID)
	{
		$row						= model::load("activity/activity")->getActivity($activityID);

		$row['activityDateTime']	= Array(); ## to override to replaceData limits.
		$data['requestFlag']		= model::load("site/request")->replaceWithRequestData("activity.update",$activityID,$row);

		## check participation
		$data['hasParticipation']	= model::load("activity/activity")->getParticipant($activityID)?true:false;

		$Training					= model::load("activity/training")->getTrainingModule($row['trainingID']);
		$data['learningPackage'] 	= model::load("activity/learning")->package();
		
		//print_r($data['learningModule']);
		//print_r($Training);
		if(form::submitted())
		{
			$rules	= Array(
					"activityName,activityParticipation,activityDateTime"=>"required:Required",
							);

			if(!$data['hasParticipation'])
				$rules['activityAllDateAttendance'] = "required:Required";

			## rule based on type.
			switch($row['activityType'])
			{
				case 1:
				$rules['eventType']	= Array(
						"required:Required"
							);
				break;
				case 2:
				$rules['trainingType']	= Array(
						"required:Required"
							);
				break;
			}

			if($error = input::validate($rules))
			{
				input::repopulate();
				redirect::withFlash(model::load("template/services")->wrap("input-error",$error));
				redirect::to("","Please correct the error highlighted.","error");
			}

			$data	= input::get();

			## decode activityDateTime.
			$datetime	= json_decode($data['activityDateTime'],true);
			$data['activityStartDate']		= $datetime['startDate']." ".$datetime['timeList'][$datetime['startDate']]['start'];
			$data['activityEndDate']		= $datetime['endDate']." ".$datetime['timeList'][$datetime['endDate']]['end'];
			$data['activityDateTimeType']	= $datetime['dateTimeType'];

			if(!$data['hasParticipation']): ## if has participation already, deny this data.
			$data['activityDateTime']		= $datetime['timeList'];
			endif;

			## win
			model::load("activity/activity")->updateAndApproveActivity($activityID,$row['activityType'],$data,$activityRequestID);

			redirect::to("","Activity has been updated");
		}

		if($Training[0]['packageID'] != ""){
			$row['learningSelection'] 	= 2;
			$row['package']				= $Training[0]['packageID'];
			$data['learningModule']		= model::load("activity/learning")->module(null,$row['package']);
			$row['module']				= $Training[0]['moduleID'];
		}
		else{
			$row['learningSelection'] = 1;
		}
		$data['learningSelection'] = model::load("activity/training")->getLearningSelection();

		$data['eventTypeR']		= model::load("activity/event")->type();
		$data['trainingTypeR']	= model::load("activity/training")->type();
		$data['row']			= $row;
		$row_site				= model::load("access/auth")->getAuthData("site");
		$data['siteInfoAddress']	= $row_site['siteInfoAddress'];

		## prepare activityDateTime input.
		$datetime['startDate']		= date("Y-m-d",strtotime($row['activityStartDate']));
		$datetime['endDate']		= date("Y-m-d",strtotime($row['activityEndDate']));
		$datetime['dateTimeType']	= $row['activityDateTimeType'];
		
		### timelist.
		if(!$data['requestFlag'])
		{
			$res_date	= model::load("activity/activity")->getDate($activityID);

			$datetime['timeList']		= Array();
			foreach($res_date as $row)
			{
				$datetime['timeList'][$row['activityDateValue']]['start']	= $row['activityDateStartTime'];
				$datetime['timeList'][$row['activityDateValue']]['end']		= $row['activityDateEndTime'];
			}
		}
		## re-use the stored one.
		else
		{
			$datetime['timeList']		= $row['activityDateTime'];
		}
		$data['datetime']			= json_encode($datetime);

		view::render("clusterlead/cluster/edit",$data);
	}

	public function updateCategory($catID,$catRequestID)
	{
		$data['row']	= model::load("forum/category")->getCategory($catID);

		$data['hasRequest'] =  model::load("site/request")->replaceWithRequestData("forum_category.update",$catID,$data['row']);

		if(form::submitted())
		{
			$forumCategory	= model::load("forum/category");

			$rules	= Array(
				"forumCategoryTitle"=>Array(
						"required:This field is required",
						"callback"=>Array(!$forumCategory->checkTitle($this->siteID,input::get("forumCategoryTitle"),$catID),"Title already exists."),
						),
				"forumCategoryAccess"=>"required:This field is required",
							);

			if($error = input::validate($rules))
			{
				redirect::to("","Please complete your form.","error");
			}

			## update.
			$forumCategory->updateAndApproveCategory($catID,input::get("forumCategoryTitle"),input::get("forumCategoryAccess"),input::get("forumCategoryDescription"),$catRequestID);

			redirect::to("cluster/overview","Updated and waiting for clusterlead approval.");
		}

		view::render("clusterlead/cluster/updateCategory",$data);
	}

	# edit announcement
	public function editAnnouncement($announceID,$announceRequestID)
	{
		$data['row']	= model::load("site/announcement")->getAnnouncement($announceID);
		$siterequest = model::load('site/request')->replaceWithRequestData('announcement.update', $announceID, $data['row']);

		if(form::submitted())
		{
			$rules	= Array(
						"announcementText"=>"required:This field is required.",
						"announcementExpiredDate"=>"required:This field is required."
							);

			## got validation error.
			if($error = input::validate($rules))
			{
				input::repopulate();
				redirect::withFlash(model::load("template/services")->wrap("input-error",$error));
				redirect::to("","Got some error in your form.","error");
			}

			## populate into data.
			$postdata = input::get();
			$postdata['announcementExpiredDate'] = date('Y-m-d',strtotime($postdata['announcementExpiredDate']));
			$postdata['siteID'] = $data['row']['siteID'];

			if(strpos($postdata['announcementLink'], 'http') === false || strpos($postdata['announcementLink'], '//') === false){
				$postdata['announcementLink'] = "http://".$postdata['announcementLink'];
			}

			## update db.
			model::load("site/announcement")->updateAndApproveAnnouncement($announceID,$announceRequestID,$postdata);

			if(session::get('userLevel') == 99){
				redirect::to("cluster/overview","Announcement has been updated.");
			}else{
				redirect::to("cluster/overview","Your edited announcement has been requested.");
			}

		}

		view::render("clusterlead/cluster/editAnnouncement",$data);
	}
}


?>