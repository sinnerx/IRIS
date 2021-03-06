<?php
class Controller_Main
{
	public function index()
	{
		$data['res_article']	= db::where("siteID",authData("current_site.siteID"))->where('articleStatus = 1 AND date(articlePublishedDate) < ?', array(date('Y-m-d')))->limit(10)->order_by("articleID","desc")->get("article")->result("articleID");

		if($data['res_article'])
		{
			$data['res_category']	= db::where("articleID",array_keys($data['res_article']))->join("category","category.categoryID = article_category.categoryID")->get("article_category")->result("articleID");
		}

		view::render("index",$data);
	}

	public function about()
	{
		$page	= model::load("page/page")->listBySite(authData("current_site.siteID"));

		$data['row']	= $page[0];

		view::render("about",$data);
	}

	public function blog($id)
	{
		$data	= db::where("articleID",$id)->get("article")->row();
		$data['categoryName']	= db::where("categoryID IN (SELECT categoryID FROM article_category WHERE articleID = ?)",Array($id))->get("category")->row("categoryName");

		view::render("blog",$data);
	}

	public function blogSlug($year, $month, $slug)
	{
		$articleID	= model::load("blog/article")->getArticleIDBySlug($slug, $year, $month);
		return $this->blog($articleID);
	}

	public function blog_latest()
	{
		$data['res_article']	= db::where("siteID",authData("current_site.siteID"))->where('articleStatus = 1 AND date(articlePublishedDate) < ?', array(date('Y-m-d')))->limit(10)->order_by("articleID","desc")->get("article")->result("articleID");

		if($data['res_article'])
		{
			$data['res_category']	= db::where("articleID",array_keys($data['res_article']))->join("category","category.categoryID = article_category.categoryID")->get("article_category")->result("articleID");
		}

		view::render("blog_latest",$data);
	}

	public function gallery()
	{
		$siteID	= authData("current_site.siteID");
		db::where('site_album.siteID',$siteID);
		db::join("album","album.albumID = site_album.albumID");
		$data['albums']	= db::get("site_album")->result("siteAlbumID");

		## get all related photos.
		if($data['albums']):
		db::where("siteAlbumID",array_keys($data['albums']));
		db::where('sitePhotoStatus', 1);
		db::join("photo","photo.photoID = site_photo.photoID");
		$data['photos']	= db::get("site_photo")->result("siteAlbumID",true);
		endif;

		view::render("gallery",$data);
	}

	public function activity()
	{
		$data['res_training']	= db::where("siteID",authData("current_site.siteID"))->where("activityType",1)->limit(10)->order_by("activityStartDate","asc")->get("activity")->result();
		$data['res_event']		= db::where("siteID",authData("current_site.siteID"))->where("activityType",2)->limit(10)->order_by("activityStartDate","asc")->get("activity")->result();

		view::render("activity",$data);
	}

	public function contact()
	{
		$data	= authData("current_site");

		db::where("siteID",authData("current_site.siteID"))->join("user_profile","user_profile.userID = site_manager.userID");
		$data['siteManagers']	= db::where('siteManagerStatus', 1)->order_by('siteManagerCreatedDate ASC')->get("site_manager")->result();
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

		view::render("contact",$data);
	}

	## partial.
	public function latestActivity()
	{
		// db::where("activityApprovalStatus",1);
		db::limit(5)->order_by("activityID","desc");
		$data['res_activity']	= db::where("siteID",authData("current_site.siteID"))->get("activity")->result();
		view::render("latestActivity",$data);
	}

	public function faq()
	{
		view::render("faq");
	}

	public function page($slug)
	{
		$page = model::load('page/page')->findDefaultPageBySlug(authData("current_site.siteID"), $slug);

		if(!$page)
			redirect::to('{site-slug}');

		$data['pageTitle'] = $page->getPageDefault()->pageDefaultName;

		$data['pageImage'] = $page->pagePhoto;

		$data['pageText'] = $page->pageText;

		view::render('page', $data);
	}
}