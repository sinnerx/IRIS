<?php
class Controller_Member
{
	public function __construct()
	{
		if(!authData("user") && !in_array(controller::getCurrentMethod(),Array("profile")))
			redirect::to("{site-slug}");
	}

	public function profile($userID = null)
	{
		## logged in as backend member.
		if(authData("user") && session::get("userLevel") != 1 && !$userID)
			redirect::to("dashboard/user/profile");
		else if(!authData("user") && !$userID)
			redirect::to("{site-slug}");


		if(!$userID)
		{
			$data['ownPage']	= true;
			$data['row']		= authData("user");
			$data['siteName']	= authData("site.siteName");
		}
		else
		{
			$data['row']	= model::load("site/member")->getUserMemberDetail($userID,authData("current_site.siteID"));

			## cannot find the related userID and current siteID.
			if(!$data['row'])
				return controller::load("error")->index();
			#redirect::to("404");

			$data['siteName']	= $data['row']['siteName'];
		}

		## save
		$userID	= $data['row']['userID'];

		$userActivity	= model::load("user/activity");


		$data['activities']				= $userActivity->getAllActivities(null,$userID,null,null,10);
		$data['activities_forum'] 		= $userActivity->getActivities(null,$userID,"forum",null,3);
		$data['activities_comment'] 	= $userActivity->getActivities(null,$userID,"comment",null,3);
		$data['activities_activity'] 	= $userActivity->getActivities(null,$userID,"activity",null,3);
		$data['activities_lms'] 		= $userActivity->getModuleByUser($userID);

		//print_r($data['activities_lms'] );

		$additional				= model::load("user/user")->getAdditional($data['row']['userID']);
		$data['row']			= array_merge($data['row'],$additional);
		$data['userActivity']	= $userActivity;
                //var_dump($data);
                //die;
		view::render("member/profile",$data);
	}

	public function test()
	{
		return view::render("auth/error");
	}

	public function profile_edit()
	{
            
		if(session::get("userLevel") != 1)
			redirect::to("dashboard/user/profile");

		$row	= model::load("access/auth")->getAuthData("user");
		$additional	= model::load("user/user")->getAdditional($row['userID']);

		## merge both row.
		$row	= array_merge($row,$additional);

		## Birthdate.
		list($row['DOByear'],$row['DOBmonth'],$row['DOBday'])	= explode("-",$row['userProfileDOB']);

		if(form::submitted())
		{
			$rules	= Array(
					"userProfileFullName,userProfileLastName"=>"required:Maklumat ini diperlukan",
					"userProfileDOBday,userProfileDOBmonth,userProfileDOByear"=>"required:Maklumat ini diperlukan"
							);

			if(input::get("userEmail") != "")
			{
				$rules['userEmail']	= 
				Array(
			"email:Sila masukkan format email yang betul.",
			"callback"=>Array(!model::load("user/services")->checkEmail(input::get("userEmail"),$row['userID']),"Email telah wujud.")
					);
			}

			if($error = input::validate($rules))
			{
				input::repopulate();
				redirect::withFlash(model::load("template/services")->wrap("input-error",$error));
				redirect::to("","<div class='msgbox error'>Sila lengkapkan maklumat yang diperlukan.</div>","error");
			}

			## ok.
			$userID	= session::get("userID");

			$data	= input::get();

			## build date of birth.
			$data['userProfileDOB']	= $data['userProfileDOByear']."-".$data['userProfileDOBmonth']."-".$data['userProfileDOBday'];

			model::load("user/user")->fullUpdate($userID,$data);

			redirect::to("","<div class='msgbox success'>Maklumat anda telah berjaya dikemaskini.</div>");
		}
                //var_dump($row);
                //die;
		view::render("member/profile_edit",$row);
	}

	public function profile_directory()
	{
		$siteID	= authData("current_site.siteID");
		$data['new_users'] = model::load("site/member")->getMemberListBySite(null,"site_member.userID != ".session::get("userID"),"user_profile_additional",10,"user.userCreatedDate DESC",null,$siteID);
		
		view::render("member/profile_directory",$data);
	}

	public function getUserList($type)
	{
		$siteID	= authData("current_site.siteID");
		$paginConf['urlFormat']	= url::base("{site-slug}/profile/getUserList/$type?page={page}");
		$data['page'] = $paginConf['currentPage']	= request::get("page",1);
		$paginConf['limit']			= 6;

		pagination::setFormat(model::load("template/frontend")->paginationFormat());

		$this->template = false;

		if($type == 'alphabetical')
		{
			$response = model::load("site/member")->getMemberListBySite(null,"site_member.userID != ".session::get("userID"),"user_profile_additional",null,"user_profile.userProfileFullName ASC",$paginConf,$siteID);
		}
		else if($type == 'date')
		{
			$response = model::load("site/member")->getMemberListBySite(null,"site_member.userID != ".session::get("userID"),"user_profile_additional",null,"user.userCreatedDate ASC",$paginConf,$siteID);
		}
		else if($type == 'lastLogin')
		{
			$response = model::load("site/member")->getMemberListBySite(null,"site_member.userID != ".session::get("userID"),"user_profile_additional",null,"user.userLastLogin DESC",$paginConf,$siteID);
		}
		else if($type == 'search')
		{
			$request	= input::get();
			$response = model::load("site/member")->getMemberListBySite(null,"site_member.userID != ".session::get("userID")." AND user_profile.userProfileFullName LIKE '%".$request['find']."%'","user_profile_additional",null,"user_profile.userProfileFullName ASC",$paginConf,$siteID);
		}

		$data['users'] = $response[0];
		$data['limit'] = ceil($response[1]/$paginConf['limit']);

		view::render("member/user_list",$data);
	}

	public function profileUploadAvatar()
	{
		$this->template	= false;
		if(form::submitted())
		{
			## upload.
			$file	= input::file("avatarPhoto");

			## if not jpg, png or gif.
			if(!$file->isExt("jpg,png,gif,jpeg"))
			{
				echo "<script type='text/javascript'>";
				echo "alert('Please choose jpg, png, or gif only');";
				echo "</script>";

				return;
			}

			$fileName	= $file->get("name");

			## add photo.
			$path		= model::load("image/photo")->addUserPhoto(0,$fileName);
			$services	= model::load("image/services");

			$photoUrl	= $services->getPhotoUrl($path);
                        
			## move upload
			$file->move($services->getPhotoPath($path));

			## update avatar.
			model::load("user/user")->updateAvatarPhoto(model::load("access/auth")->getAuthData("user","userID"),$path);

			echo "<script type='text/javascript'>";
			echo "parent.profile.upload.updateAvatar('$photoUrl');";
			echo "</script>";
		}
	}
}