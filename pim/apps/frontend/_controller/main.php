<?php
Class Controller_Main
{
	## main landing page. example : pim.my
	public function landing()
	{
		$this->template	= false;
		view::render("main/landing");
	}

	## main landing page:about. example : p1m.my/about
	public function landing_about()
	{
		$this->template	= false;
		view::render("main/landing_about");
	}

	## main landing page:contact. example : p1m.my/contact
	public function landing_contact()
	{
		$this->template	= false;
		$data['categoryNameR']	= model::load("site/message")->getCategoryName();

		if(form::submitted())
		{
			## follow main@contact rules.
			$rules	= Array(
					"siteMessageCategory"=>"required:Sila pilih kategori.",
					"contactName,contactPhoneNo,contactEmail,messageSubject,messageContent"=>"required:Sila isikan ruangan ini.",
					"contactEmail"=>"email:Sila masukkan format alamat emel yang betul.",
					"contactPhoneNo"=>Array(
								"callback"=>Array(is_numeric(str_replace("-","",input::get("contactPhoneNo"))),"Sila masukkan nombor telefon yang betul.")
										)
							);

			if($error = input::validate($rules))
			{
				input::repopulate();
				redirect::withFlash(model::load("template/services")->wrap("input-error",$error));
				redirect::to("","<span id='mailmessage' class='msgbox error'>Maklumat tidak lengkap</span>","error");
			}

			## success and, createPublicMessage nonregarded to any site.
			$referenceNo	= model::load("site/message")->createPublicMessage(null,input::get());
			redirect::to("","<span id='mailmessage' class='msgbox success'>Mesej anda telah berjaya dihantar. No. Rujukan : $referenceNo</span>");
		}

		view::render("main/landing_contact",$data);
	}

	## site landing page. example : pim.my/[site-slug]
	public function index()
	{
		$page	= model::load("page/page");
		$site	= model::load("site/site");
		$siteID	= $site->getSiteIDBySlug(request::named("site-slug"));
		$data['siteName']	= $site->getSiteName();

		## get tentang-kami.
		$defaultR	= $page->getDefault();
		db::from("page");
		db::where("siteID",$siteID);
		db::where(Array(
					"pageType"=>1,
					"pageDefaultType"=>1
						));
		$data['pageName']	= $defaultR[1]['pageDefaultName'];
		$data['pageSlug']	= url::base("{site-slug}/".$defaultR[1]['pageDefaultSlug']);
		$row				= db::get()->row();
		#$data['photoName']	= model::load("page/page")->getPagePhotoUrl($row['pageID']); migration plan. now uses column pagePhotos
		$data['photoName']	= $row['pagePhoto'];
		$data['photoName']	= model::load("api/image")->buildPhotoUrl($row['pagePhoto'],"page_small");

		## repair page text to 90 words.
		$data['pageText'] = $row['pageTextExcerpt'] == ""?model::load("helper")->purifyHTML(stripslashes($row['pageText']),90):nl2br($row['pageTextExcerpt']);
		$data['siteID'] = $siteID;

		view::render("main/index",$data);
	}

	public function maintenance()
	{
		echo "Sorry we're currently having an urgent maintainance. Login and registration will temporarily be disabled.";die;
	}

	public function login()
	{
		if(form::submitted())
		{
			$userIC	= str_replace("-","",input::get('login_userIC'));
			$userIC	= str_replace(" ", "", $userIC);
			$pass	= input::get("login_userPassword");

			$rules	= Array(
					"_all"=>"required:Maklumat ini diperlukan"
							);

			## validate 
			if($error = input::validate($rules))
			{
				input::repopulate();
				redirect::withFlash(model::load("template/services")->wrap("span-error",$error));
				redirect::to("{site-slug}/registration#horizontalTab1","<br>Sila pastikan maklumat anda lengkap.","error");
			}

			## check member login.
			# check if current site is pengurus site.
			if(authData("current_site.siteID") == model::load("config")->get("configManagerSiteID"))
			{
				## this login is using email instead..
				$login	= model::load("access/auth")->checkManagerSiteLogin($userIC,$pass,authData("current_site.siteID"));
			}
			## normal member login.
			else
			{
				$login	= model::load("access/auth")->checkMemberLogin($userIC,$pass);
			}

			if(!$login)
			{
				input::repopulate();
				redirect::to("{site-slug}/registration#horizontalTab1","<br>Tidak dapat mengenal pasti login anda.","error");
			}

			## and log user in.
			model::load("access/auth")->login($login['userID'],$login['userLevel']);

			## success and redirect to main site.
			redirect::to("{site-slug}");
		}

		redirect::to("{site-slug}/registration#horizontalTab1");
	}

	public function logout()
	{
		## destroy all session.
		session::destroy();

		## redirect to main.
		redirect::to("{site-slug}");
	}

	## site registration. example : pim.my/[site-slug]/registration
	public function registration()
	{
		$this->template	= "template_registration";
		$row_site		= 	model::load("access/auth")->getAuthData("current_site");#model::load("site/site")->getSite();
		$data['siteName']	= $row_site['siteName'];
		$data['siteInfoDescription']	= $row_site['siteInfoDescription'];
		$data['monthR']					= model::load("helper")->monthYear("month");
		$data['yearR']				 	= model::load("helper")->monthYear('year',1925,date("Y"));

		if(form::submitted())
		{
			## prepare IC.
			$ic			= str_replace("-","",input::get("userIC"));## remove '-' if got.
			$ic			= str_replace(" ", "", $ic);
			$password	= input::get("userPassword");

			$icCheck	= Array(!model::load("user/services")->checkIC($ic),"Telah didaftarkan");
			#$icCheck	= $icCheck[0]?(is_numeric($ic)?Array(true):Array(false,"IC mestilah nombor yang betul")):$icCheck;
			$icCheck	= $icCheck[0]?((ctype_alnum($ic) || is_numeric($ic)) && !ctype_alpha($ic))?Array(true):Array(false,"Mestilah nombor atau alpha-numeric"):$icCheck;
		
			$rules	= Array(
					"userProfileFullName,userProfileLastName"=>"required:Maklumat ini diperlukan",
					"userProfileGender,userProfileOccupationGroup"=>"required:Diperlukan",
					"except:checkPenduduk,checkTerm"=>"required:Maklumat ini diperlukan.",
					"userIC"=>Array(
							 "callback"=>Array($icCheck[0],$icCheck[1])
									),
					"userPassword"=>Array(
							"callback"=>Array((strlen($password) >= 6),"Minimum kata laluan adalah 6 karakter")
										),
							);

			$isOutsider		= input::get("checkPenduduk")?0:1;
			$checkTerm		= input::get("checkTerm")?true:false;

			## if got error.
			if(($error = input::validate($rules)) || /*!$checkPenduduk || */!$checkTerm)
			{
				/*if(!$checkPenduduk)
				{
					$error['checkPenduduk']	= "Hanya penduduk di kampung ini sahaja boleh berdaftar.";
				}*/

				if(!$checkTerm)
				{
					$error['checkTerm']		= "Pastikan anda setuju dengan terma dan syarat kami.";
				}

				input::repopulate();## repopulate previous input into flash data
				redirect::withFlash(model::load("template/services")->wrap("span-error",$error));
				redirect::to("","Lengkapkan maklumat pendaftaran anda","error");
			}

			## register.
			## prepare
			$birthdate			= input::get("birthday_year")."-".input::get("birthday_month")."-".input::get("birthday_day");
			$fullname			= input::get("userProfileFullName");
			$lastname			= input::get("userProfileLastName");
			$gender				= input::get("userProfileGender");
			$occupationGroup	= input::get("userProfileOccupationGroup");

			## register.
			model::load("site/member")->register($row_site['siteID'],$ic,$password,$birthdate,$fullname,$lastname,$isOutsider,$gender,$occupationGroup);

			## success and redirect.
			$memberFee	= model::load("config")->get("configMemberFee",5);
			redirect::to("{site-slug}/registration#horizontalTab1","<br>Anda telah berjaya di daftarkan. Sila buat pembayaran RM $memberFee di Pusat Internet 1Malaysia anda untuk menikmati kemudahan laman web ini sepenuhnya.");
		}

		view::render("main/registration",$data);
	}

	## site contact-us : pim.my/[site-slug]/contact-us
	public function contact()
	{
		$site					= model::load("site/site");
		$data['row']			= $site->getSite();
		$row_manager			= $site->getManagerInfo(null,'userEmail');
		$data['managerEmail']	= $row_manager['userEmail'];
		$data['categoryNameR']	= model::load("site/message")->getCategoryName();
		
		## a message has been submitted.
		if(form::submitted())
		{
			$rules	= Array(
					"siteMessageCategory"=>"required:Sila pilih kategori.",
					"contactName,contactPhoneNo,contactEmail,messageSubject,messageContent"=>"required:Sila isikan ruangan ini.",
					"contactEmail"=>"email:Sila masukkan format alamat emel yang betul.",
					"contactPhoneNo"=>Array(
								"callback"=>Array(is_numeric(str_replace("-","",input::get("contactPhoneNo"))),"Sila masukkan nombor telefon yang betul.")
										)
							);

			## if got error.
			if($error = input::validate($rules))
			{
				input::repopulate();
				redirect::withFlash(model::load("template/services")->wrap("input-error",$error));
				redirect::to("","<span id='mailmessage' class='msgbox error'>Maklumat tidak lengkap</span>","error");
			}

			## else, createPublicMessage.
			$referenceNo	= model::load("site/message")->createPublicMessage($data['row']['siteID'],input::get());

			redirect::to("","<span id='mailmessage' class='msgbox success'>Mesej anda telah berjaya dihantar. No. Rujukan : $referenceNo</span>");
		}

		view::render("main/contact_us",$data);
	}

	public function search($keyword = null)
	{
		$this->template = "default";


		#$result	= model::load("search")->search($keyword,$module);
		$typeList	= Array(
						"blog",
						"activity",
						"video",
						"gallery",
						"page",
						"forum"
							);

		$data['filter']	= $typeList;

		## testdata.
		$selectedType		= request::get("jenis","all") == "all"?$typeList:explode("-",request::get("jenis"));

		$data['selectedType']	= $selectedType;

		$keyword		= request::get("q");
		$offset			= 0;
		$limit			= request::get("limit",10);
		$currentPage	= request::get("page",1);

		## Prepare searching condition and result column
		foreach($selectedType as $type)
		{
			switch($type)
			{
				case "blog":
					$tables['blog']	= "article";
					$select['blog']	= "articleID,articleText,articleName,articleCreatedDate,articlePublishedDate,articleSlug";
					$where['blog']['MATCH(articleText) AGAINST (?)'] = Array($keyword);
					$where['blog']['articleStatus']	= 1;
					$where['blog']['siteID']		= authData("current_site.siteID");

					## result columns.
					$resultcolumn['blog']	= Array(
									'refID' => "articleID",
									'title'	=> "articleName",
									'body'	=> "articleText",
									'date'	=> "articleCreatedDate",
									'img'	=> Array(
										"parameter"=>Array("articleText"),
										"callback"=>function($param)
										{
											return model::load("helper")->getImgFromText($param['articleText']);
										}
												)
													);

					$functions['blog']		= Array(
									"url"	=> Array(
										"parameter"=>Array("articleSlug","articlePublishedDate"),
										"callback"=>function($param)
										{
											return model::load("helper")->buildDateBasedUrl($param['articleSlug'],$param['articlePublishedDate'],url::base("{site-slug}/blog"));
										}
													));

				break;
				case "forum":
					$select['forum']					= "*";
					$tables['forum']					= "forum_thread_post";
					$where['forum']['MATCH(forumThreadPostBody) AGAINST (?)'] = Array($keyword);
					$where['forum']['forum_thread_post.forumThreadID IN (SELECT forumThreadID FROM forum_thread WHERE siteID = ?)'] = Array(authData("current_site.siteID"));
					$join['forum']['forum_thread']		= "forum_thread.forumThreadID = forum_thread_post.forumThreadID";
					$join['forum']['forum_category']	= "forum_category.forumCategoryID = forum_thread.forumCategoryID";

					## result columns.
					$resultcolumn['forum']	= Array(
									"refID"=>"forumThreadPostID",
									"title"=>"forumThreadTitle",
									"body"=>"forumThreadPostBody",
									"date"=>"forumThreadPostCreatedDate"
													);

					$functions['forum']		= Array(
										"url"	=> Array(
											"parameter"=>Array("forumThreadID","forumCategorySlug"),
											"callback"=>function($param)
											{
												return url::base("{site-slug}/forum/".$param['forumCategorySlug']."/".$param['forumThreadID']);
											}
														)
													);
				break;
				case "activity":
					$select['activity']	= "*";
					$tables['activity'] = "activity";
					$select['activity'] = "activityID,activityName,activityDescription";
					$where['activity']['MATCH(activityName) AGAINST (?) OR MATCH(activityDescription) AGAINST (?)'] 	= Array($keyword,$keyword);
					$where['activity']['activityApprovalStatus']			= 1;

					## result columns.
					$resultcolumn['activity']	= Array(
										"refID"=>"activityID",
										"title"=>"activityName",
										"body"=>"activityDescription",
										"date"=>"activityCreatedDate"
														);

					$functions['activity']	= Array(
											"url"=>Array(
												"parameter"=>Array("activityStartDate","activitySlug"),
												"callback"=>function($param)
												{
													return model::load("helper")->buildDateBasedUrl($param['activitySlug'],$param['activityStartDate'],url::base("{site-slug}/aktiviti"));
												}
														)
													);
				break;
				case "video":
					$select['video']	= "*";
					$where['video']['MATCH(videoName) AGAINST (?)'] = Array($keyword);
					$join['video']['video_album']	= "video.videoAlbumID = video_album.videoAlbumID";

					## result columns.
					$resultcolumn['video']	= Array(
										"refID"=>"videoAlbumID",
										"title"=>"videoName",
										"body"=>"videoAlbumDescription",
										"date"=>"videoCreatedDate"
													);

					$functions['video']		= Array(
										"url"=>Array(
											"parameter"=>Array("videoAlbumSlug"),
											"callback"=>function($param)
											{
												return url::base("{site-slug}/video/".$param['videoAlbumSlug']);
											}
													)
													);

				break;
				case "gallery":
					$select['gallery']	= "*";
					$tables['gallery']	= "site_album";
					$where['gallery']['siteAlbumStatus']	= 1;
					$where['gallery']['site_album.siteID']		= authData("current_site.siteID");
					$where['gallery']['site_album.albumID IN (SELECT albumID FROM album WHERE MATCH(albumDescription) AGAINST (?) OR MATCH(albumName) AGAINST (?))'] = Array($keyword,$keyword);
					$join['gallery']['album']	= "album.albumID = site_album.albumID";

					## result columns.
					$resultcolumn['gallery'] = Array(
										"refID"=>"siteAlbumID",
										"title"=>"albumName",
										"body"=>"albumDescription",
										"date"=>"albumCreatedDate",
										"img"=>Array(
											"parameter"=>Array("albumCoverImageName"),
											"callback"=>function($param){
												return model::load("api/image")->buildPhotoUrl($param['albumCoverImageName'],"small");
											}
												)
													);

					$functions['gallery']['url']	= Array(
											"parameter"=>Array("siteAlbumSlug","albumCreatedDate"),
											"callback"=>function($param)
											{
												return model::load("helper")->buildDateBasedUrl($param['siteAlbumSlug'],$param['albumCreatedDate'],url::base("{site-slug}/galeri"));
											}
															);
				break;
				case "page":
					$select['page']	= "*";
					$tables['page']	= "page";
					$where['page']['MATCH(pageText) AGAINST (?)'] = Array($keyword);
					$where['page']['siteID']		= authData("current_site.siteID");
					$join['page']['page_default']	= "page_default.pageDefaultType = page.pageDefaultType";

					## result columns.
					$resultcolumn['page']	= Array(
										"refID"=>"pageID",
										"title"=>"pageDefaultName",
										"body"=>"pageText",
										"date"=>"pageCreatedDate",
										"img"=>Array(
											"parameter"=>Array("pagePhoto"),
											"callback"=>function($param)
											{
												return model::load("api/image")->buildPhotoUrl($param['pagePhoto'],"page_small");
											}
													)
													);


				break;
			}
		}

		$total	= 0;

		## type worth to be listed in the current page list.
		$lookupType = Array();
		
		## initial offset.
		$offset		= $limit*($currentPage-1) + 1;
		foreach($where as $type=>$res)
		{
			foreach($res as $key=>$val)
			{
				db::where($key,$val);
			}
	
			$table	= !isset($tables[$type])?$type:$tables[$type];

			## query the total of the current search result.
			db::select($select[$type]?:"*");
			$nr	= db::get($table)->num_rows();
			$total	+= $nr;

			## check if offet is within this total row combined
			if(/*$offset <= $total && */$nr != 0)
			{
				$lookupType[]	= $type;
				continue;
				## check if next type is required.
				if(($offset-1)+$limit > $total)
				{
					continue;
				}
			}
			else
			{
				continue;
			}

			break;
		}

		$data['totalResult']	= $total;

		## -------------------
		## now only get the record from the selected table.
		## -------------------
		$no = 1;
		$result	= [];
		$row_no	= 0;
		$offset	= $offset-1;

		foreach($lookupType as $type)
		{
			foreach($where[$type] as $key=>$val)
			{
				db::where($key,$val);
			}

			## join.
			if($join[$type])
			{
				foreach($join[$type] as $table=>$cond)
				{
					db::join($table,$cond);
				}
			}

			$res	= db::get($tables[$type]?:$type)->result();

			## now prepare result.
			foreach($res as $row)
			{
				if($row_no >= $offset)
				{
					if($row_no >= $offset+$limit)
					{
						break 2;
					}

					$row_result	= Array();
					$row_result['refID']	= $row[$resultcolumn[$type]['refID']];
					$row_result['type']		= $type;

					$row_result['title']	= $row[$resultcolumn[$type]['title']];
					$row_result['body']		= model::load("helper")->purifyHTML($row[$resultcolumn[$type]['body']]);
					$row_result['date']		= model::load("helper")->frontendDatetime($row[$resultcolumn[$type]['date']]);

					if(is_array($resultcolumn[$type]['img']))
					{
						$param = Array();
						foreach($resultcolumn[$type]['img']['parameter'] as $col)
						{
							$param[$col]	= $row[$col];
						}

						$row_result['img']	= $resultcolumn[$type]['img']['callback']($param);
					}

					## build url.
					if($functions[$type]['url'])
					{
						$param	= Array();
						foreach($functions[$type]['url']['parameter'] as $col)
						{
							$param[$col]	= $row[$col];
						}

						$row_result['url']	= $functions[$type]['url']['callback']($param);
					}


					$result[]	= $row_result;
				}

				$row_no++;
			}


			$no++;
		}

		## ----------
		## Pagination
		## ----------
		pagination::initiate(Array(
						"totalRow"=>$total,
						"currentPage"=>$currentPage,
						"limit"=>$limit,
						"urlFormat"=>"carian?q=".$keyword.(request::get("jenis")?"&jenis=".request::get("jenis"):"")."&page={page}"
									));

		pagination::setFormat(model::load("template/frontend")->pagination());

		$data['result']	= $result;

		view::render("main/search",$data);
	}


	## site faq
	public function faq()
	{
		view::render("main/faq");
	}
}


?>