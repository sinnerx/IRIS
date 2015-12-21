<?php

Class Controller_Site
{
	## site detail.
	public function info()
	{
		$data['row_site']	= model::load("site")->getSiteByManager();

		view::render("shared/site/info",$data);
	}

	## if got siteID, mean, it's accessed by root. else by manager.
	public function edit($siteID = null)
	{
		$site			= model::load("site/site");
		$data['stateR']	= model::load("helper")->state();

		## root only.
		if($siteID)
		{
			## Access:roleCheck
			if(!model::load("access/services")->roleCheck("siteEditRoot"))
			{
				redirect::to("../404","Only root is permitted.");
			}

			$data['row']	= $site->getSite($siteID);
		}
		else
		{
			## role for site manager check.
			if(!model::load("access/services")->roleCheck("siteEdit"))
			{
				redirect::to("../404","Can be accessed by site manager only.");
			}

			## get site based on manager.
			$data['row']	= $site->getSiteByManager();
			$siteID	= $data['row']['siteID'];
		}

		## get unapproved site request;
		$unapprovedRequest	= model::load("site/request")->getUnapprovedRequestData(3,$siteID);
		
		## if got unapproved request, use replace existing data with the unapproved one..
		if($unapprovedRequest){

			$data['unapproved']	= true;
			$data['row']		= model::load("site/request")->replaceData($data['row'],$unapprovedRequest);
		}

		## if form submitted.
		if(form::submitted())
		{
			$data_site	= input::get();

			## rules for site email.
			if(input::get("siteInfoEmail") != "")
			{
				$rules['siteInfoEmail']	= "email:Please input a correct email address.";
			}

			## do role for root.
			if(model::load("access/services")->roleCheck("siteEditRoot"))
			{
				$siteSlug		= input::get("siteSlug");
				$siteSlugCheck	= $siteSlug != $data['row']['siteSlug']?(model::load("site/services")->checkSiteSlug($siteSlug)?Array(false,"Slug already exists."):Array(true)):Array(true);

				$rules	= Array(
						"siteName,siteSlug,stateID"=>"required:This field is required.",
						"siteSlug"=>Array(
							"min_length(5):length must be longer than {length}",
							"callback"=>Array($siteSlugCheck[0],$siteSlugCheck[1])
									)
								);

				if(input::get("siteRefID") != "" && input::get('siteRefID') !== '0')
				{
					$rules['siteRefID']	= Array(
								"callback"=>Array(!model::load("site/site")->checkSiteRefID(input::get("siteRefID"),$siteID),"This site_id already exists")
												);


				}
			}

			## error.
			if($error = input::validate($rules))
			{
				input::repopulate();
				redirect::withFlash(model::load("template/services")->wrap("input-error",$error));
				redirect::to("","Got some error with the form.","error");
			}

			## if got file upload.
			if($file	= input::file("siteInfoImage"))
			{
				## but file extension isn't as permitted.
				if(!$file->isExt("png,jpg,jpeg"))
				{
					input::repopulate();
					redirect::to("","Please upload png, jpg, or jpeg only","error");
				}

				## upload that file.
				$path		= path::asset("frontend/images/siteImage");
				$filename	= time().".".$file->get("ext");

				## move uploaded file to the $path.
				if($file->move($path,$filename))
				{
					## update site image only.
					$site->updateSiteImage($data['row']['siteID'],$filename);
				}
			}

			$site->updateSiteInfo($data['row']['siteID'],$data_site);

			$additionalMessage	= model::load("access/services")->roleCheck("siteEditRoot")?"":" And the content is waiting for approval.";
			redirect::to("","Site has been updated. ".$additionalMessage);
		}

		## param for non-root..
		$data['disabled']	= model::load("access/services")->roleCheck("siteEditRoot")?"":"disabled";

		view::render("shared/site/edit",$data);
	}

	public function slider()
	{
		$site		= model::load("site/site");
		$siteSlider	= model::load("site/slider");

		## manager.
		if(session::get("userLevel") == 2)
		{
			$siteID	= $site->getSiteByManager(session::get("userID"),"siteID");
		}
		else ## must be root admin.
		{
			$siteID	= 0;
		}
		

		## add form submitted.	
		if(form::submitted())
		{
			$error	= input::validate(Array(
								"siteSliderName"=>"required:This field is required."
											));

			## file check.
			$FILE	= input::file("siteSliderImage");

			if(!$FILE)
			{
				$error['siteSliderImage']	= "No file was choosen.";
			}
			else
			{
				if(!$FILE->isExt("jpeg,jpg,png,bmp"))
				{
					$ext	= $FILE->get("ext");
					$error['siteSliderImage']	= "Please choose only jpg, png or bmp.";
				}
			}

			## if got error.
			if($error)
			{
				flash::set(model::load("template/services")->wrap("input-error",$error));
				input::repopulate();
				redirect::to("","Looks like there's some error in your form..","error");
			}

			## upload images.
			$file_name	= time().".".$FILE->get("ext");
			$file_path	= path::asset("frontend/images/slider");

			## Move uploaded file to the path.
			if(!$FILE->move($file_path,$file_name))
			{
				input::repopulate();
				redirect::to("","Failed to upload.","error");
			}

			## equate with _POST
			$data	= input::get();
			$data['siteSliderImage']	= $file_name;

			## execute add slider.
			$siteSlider->addSlider($siteID,$data);

			redirect::to("site/slider#","Successfully added a new slider.");
		}

		## if deactivated.
		if(request::get("toggle"))
		{			
			## execute remove slider.
			$id		= request::get("toggle");
			$siteSlider->toggleSlider($id);

			redirect::to("site/slider","Updated slider.");
		}

		$data['res_slider']	= $siteSlider->getSlider($siteID);
		view::render("shared/site/slider",$data);
	}

	public function slider_edit($sliderID)
	{
		$data['row']	= model::load("site/slider")->getSliderDetail($sliderID);

		if(form::submitted())
		{
			$rules	= Array(
					"_all"=>"required:This field is required."
							);

			## got validation error.
			if($error = input::validate($rules))
			{
				input::repopulate();
				redirect::withFlash(model::load("template/services")->wrap("input-error",$error));
				redirect::to("","Got some error in your form.","error");
			}

			## populate into data.
			$data = input::get();

			## got file uploaded.
			if($file = input::file("siteSliderImage"))
			{
				if(!$file->isExt("png,jpg,jpeg"))
				{
					input::repopulate();
					redirect::to("","Please upload with format png, jpg, or jpeg only","error");
				}

				$file_name	= time().".".$file->get("ext");
				$file_path	= path::asset("frontend/images/slider");

				## move uploaded file.
				if(!$file->move($file_path,$file_name))
				{
					input::repopulate();
					redirect::to("","Failed to upload.","error");
				}
				
				## save into data.
				$data['siteSliderImage']	= $file_name;	
			}

			## update db.
			model::load("site/slider")->updateSlider($sliderID,$data);

			redirect::to("site/slider","Slider has been updated.");
		}

		view::render("shared/site/slider_edit",$data);
	}

	## public message.
	public function message($page = 1)
	{
		## get categoryname
		$data['siteMessage']		= model::load("site/message");
		$data['categoryNameR']		= $data['siteMessage']->getCategoryName();

		db::from("site_message");
		db::select("site_message.*, message.*, contact.*, siteName, site.siteID");

		## paginate public message.
		pagination::initiate(Array(
						"totalRow"=>db::num_rows(),
						"urlFormat"=>url::base("site/message/{page}"),
						"currentPage"=>$page
									));

		if(request::get("category"))
		{
			db::where("siteMessageCategory",request::get("category"));
		}

		db::join("message","site_message.messageID = message.messageID");
		db::join("contact","contact.contactID = site_message.siteMessageCreatedUser");
		db::join("site","site_message.siteID = site.siteID");

		db::order_by("messageCreatedDate","DESC");
		db::limit(pagination::get("limit"),pagination::recordNo()-1);
		$data['res_message']	= db::get()->result();

		view::render("shared/site/message",$data);
	}

	public function messageView($refNo)
	{
		$refNo	= strtoupper($refNo);
		$siteMessage	= model::load("site/message");
		$siteMessageID	= $siteMessage->encryptID($refNo,"decrypt");

		$data['row']			= $siteMessage->readPublicMessage($siteMessageID);

		if(!$data['row'])
		{
			flash::set("_post.search",$refNo);
			redirect::to("site/message","[Reference No : $refNo] Message didn't exists.","error");
		}

		$data['referenceNo']	= $refNo;
		$data['category']		= $siteMessage->getCategoryName($data['row']['siteMessageCategory']);

		view::render("shared/site/messageView",$data);
	}

	public function announcement($page=1) #announcement list, add
	{
		$site		= model::load("site/site");
		$siteAnnounce	= model::load("site/announcement");

		## manager.
		if(session::get("userLevel") == 2)
		{
			$siteID	= $site->getSiteByManager(session::get("userID"),"siteID");
		}
		else ## must be root admin.
		{
			$siteID	= 0;
		}
		

		## add form submitted.	
		if(form::submitted())
		{
			$error	= input::validate(Array(
								"announcementText"=>"required:This field is required.",
								"announcementExpiredDate"=>"required:This field is required."
											));

			## if got error.
			if($error)
			{
				flash::set(model::load("template/services")->wrap("input-error",$error));
				input::repopulate();
				redirect::to("","Looks like there's some error in your form..","error");
			}

			## equate with _POST
			$data	= input::get();
			$data['announcementExpiredDate'] = date('Y-m-d',strtotime($data['announcementExpiredDate']));

			## execute addAnnouncement()
			$siteAnnounce->addAnnouncement($siteID,$data);

			redirect::to("site/announcement#","Successfully added an announcement.");
		}

		$data['announcement']	= $siteAnnounce->getAnnouncementList($siteID,false,$page);

		view::render("shared/site/announcement", $data);
	}

	# edit announcement
	public function editAnnouncement($announceID)
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
			model::load("site/announcement")->updateAnnouncement($announceID,$postdata);

			if(session::get('userLevel') == 99){
				redirect::to("site/announcement","Announcement has been updated.");
			}else{
				redirect::to("site/announcement","Your edited announcement has been requested.");
			}

		}

		view::render("shared/site/editAnnouncement",$data);
	}

	# view/add article
	public function article($page = 1)
	{
		$site		= model::load("site/site");
		$siteArticle	= model::load("blog/article");

		## manager.
		$siteID = model::load("access/auth")->getAuthData("site", "siteID");
		$data['article']	= $siteArticle->getArticleList($siteID, false, $page);
		$data['articleTags'] = $siteArticle->getArticleTag(array_keys($data['article']));

		view::render("shared/site/article", $data);
	}

	# view/add article
	public function addArticle()
	{
		## add form submitted.	
		if(form::submitted())
		{
			$site		= model::load("site/site");
			$siteAnnounce	= model::load("blog/article");

			## manager.
			if(session::get("userLevel") == 2)
			{
				$siteID	= $site->getSiteByManager(session::get("userID"),"siteID");
			}

			$error	= input::validate(Array(
								"articleName"=>"required:This field is required.",
								"articleText"=>"required:This field is required.",
								"articlePublishedDate"=>"required:This field is required."
											));

			## if got error.
			if($error)
			{
				flash::set(model::load("template/services")->wrap("input-error",$error));
				input::repopulate();
				redirect::to("","Looks like there's some error in your form..","error");
			}

			## equate with _POST
			$data	= input::get();
			$data['articleStatus'] = $data['articleStatus'] == 'Save as draft'? 3:0;
			$data['articlePublishedDate'] = date('Y-m-d',strtotime($data['articlePublishedDate']));
			//echo '<pre>';print_r($data);die;

			## execute model/addArticle()
			$siteAnnounce->addArticle($siteID,$data);

			redirect::to("site/article","Successfully added a blog post.");
		}

		## if there's this flag.
		if(request::get("reportfor"))
		{
			$activity	= model::load("activity/activity")->getActivity(request::get("reportfor"));

			if($activity)
			{
				$data['reportfor']	= Array(
									"activityID"=>request::get("reportfor"),
									"activityName"=>$activity['activityName']
											);
			}
		}

		$category	= model::load("blog/category")->getCategoryList();
		
		$data['category'] = $category;
		view::render("shared/site/addArticle", $data);
	}

	# edit article
	public function editArticle($articleID)
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
					model::load('blog/article')->updateArticle($articleID,$postdata);
				}
			}
			# $postdata['articleStatus'] = $data['row']['articleStatus'] == 1? 4:$postdata['articleStatus']; 
			# echo '<pre>';print_r($postdata);die;
			
			redirect::to("site/article","Your edited article has been requested.");
		}

		$data['category'] = model::load("blog/category")->getArticleCategoryList($data['row']['articleID']);
		$data['activity'] = model::load("blog/article")->getActivityArticle($data['row']['articleID']);
		$data['activity'][0]['data'] = model::load("activity/activity")->getActivity($data['activity'][0]['activityID']);

		# echo '<pre>';print_r($data);die;
		view::render("shared/site/editArticle",$data);
	}
}



?>