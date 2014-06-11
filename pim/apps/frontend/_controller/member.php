<?php
class Controller_Member
{
	public function profile()
	{
		if(session::get("userLevel") != 1)
			redirect::to("dashboard/user/profile");

		view::render("member/profile");
	}

	public function profile_edit()
	{
		if(session::get("userLevel") != 1)
			redirect::to("dashboard/user/profile");

		$row	= model::load("access/auth")->getAuthData("user");

		if(form::submitted())
		{
			$rules	= Array(
					"userProfileFullName,userProfileLastName"=>"required:Maklumat ini diperlukan"
							);

			if(input::get("userEmail") != "")
			{
				$rules['userEmail']	= "email:Sila masukkan format email yang betul.";
			}

			if($error = input::validate($rules))
			{
				input::repopulate();
				redirect::withFlash(model::load("template/services")->wrap("input-error",$error));
				redirect::to("","<div class='msgbox error'>Sila lengkapkan maklumat yang diperlukan.</div>","error");
			}

			## ok.
			$userID	= session::get("userID");

			model::load("user/user")->fullUpdate($userID,input::get());

			redirect::to("","<div class='msgbox success'>Maklumat anda telah berjaya dikemaskini.</div>");
		}

		view::render("member/profile_edit",$row);
	}

	public function profileUploadAvatar()
	{
		$this->template	= false;
		if(form::submitted())
		{
			## upload.
			$file	= input::file("avatarPhoto");

			## if not jpg, png or gif.
			if(!$file->isExt("jpg,png,gif"))
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