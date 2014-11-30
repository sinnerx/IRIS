<?php
class Controller_Main
{
	public function index()
	{
		$data['res_article']	= db::where("siteID",authData("current_site.siteID"))->limit(10)->order_by("articleID","desc")->get("article")->result();

		view::render("index",$data);
	}

	public function about()
	{
		$page	= model::load("page/page")->listBySite(authData("current_site.siteID"));

		$data['row']	= $page[0];

		view::render("about",$data);
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
		$data['siteManagers']	= db::get("site_manager")->result();
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
		db::where("activityApprovalStatus",1);
		db::limit(5)->order_by("activityID","desc");
		$data['res_activity']	= db::where("siteID",authData("current_site.siteID"))->get("activity")->result();
		view::render("latestActivity",$data);
	}

	public function faq()
	{
		view::render("faq");
	}
}