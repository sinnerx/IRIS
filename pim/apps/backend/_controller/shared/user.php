<?php
class Controller_User
{
	## get the injected user record.
	public function __construct($row_user)
	{
		$this->row_user = $row_user;
		$this->userLevelR	= Array(
						2=>"Site Manager",
						3=>"Cluster Lead"
									);
	}

	## shared by all.
	public function profile()
	{
		$user				= model::load("user/user");
		$data['row']		= $this->row_user;
		$data['userLevelR']	= $this->userLevelR;
		$userID				= session::get("userID");

		if(form::submitted())
		{
			$emailCheck	= false;
			## check only if email not same as current email.
				if(input::get("userEmail") != $data['row']['userEmail'])
				{
					$emailCheck	= model::load("user/services")->checkEmail(input::get("userEmail"));
				}				
			
			## ic check.
			$icCheck	= false;
			if(input::get("userIC") != $data['row']['userIC'])
			{
				$icCheck	= model::load("user/services")->checkIC(input::get("userIC"));
			}
			// var_dump($icCheck);
			$userIC = str_replace('-', '', input::get('userIC'));
			if(strlen($userIC) != 12){
				$icCheck = 1;
			}	

			if (!(preg_match('/^[0-9]+$/', $userIC))) {
			  // contains only 0-9
				$icCheck = 1;
			}	

			$rules	= Array(
					"userProfileFullName,userIC"=>"required:This field is required.",
					"userIC"=>Array(
								"callback"=>Array(!$icCheck,"IC already exists / only 12 numeric characters are allowed")
									)
							);

			

			## got error.

			if($error = input::validate($rules))
			{
				input::repopulate();
				redirect::withFlash(model::load("template/services")->wrap("input-error",$error));
				redirect::to("","Got some error in your form.","error");
			}

			## update manager info.
			$data	= input::get();
			$data['userIC'] = $userIC;
			$user->fullUpdate($userID,$data);

			redirect::to("","Successfully updated user info.");
		}
                $data['user'] = model::load("access/auth")->getAuthData("user");
		view::render("shared/user/profile",$data);
	}


	public function changePassword()
	{
		if(form::submitted())
		{
			$pass	= input::get("userPassword");
			$confirm	= input::get("passwordConfirm");

			## got error.
			if($error = input::validate(Array("_all"=>"required:this field is required.")))
			{
				input::repopulate();
				redirect::withFlash(model::load("template/services")->wrap("input-error",$error));
				redirect::to("","Got some error in your form.","error");
			}

			## not same.
			if($pass != $confirm)
			{
				input::repopulate();
				redirect::to("","Please confirm your password.","error");
			}

			## must not default pass
			if($pass == model::load("user/services")->getDefaultPassword())
			{
				input::repopulate();
				redirect::to("","Cannot use default password","error");
			}

			## length > 5
			if(strlen($pass) < 5)
			{
				input::repopulate();
				redirect::to("","Password length must be more than 4 character","error");
			}



			## change.
			model::load("user/services")->changePassword(session::get("userID"),$pass);

			redirect::to("","Your password has been changed to '$pass'");
		}

		view::render("shared/user/changePassword");
	}
        public function changeProfileImage(){
            $data['user'] = model::load("access/auth")->getAuthData("user");
            view::render("shared/user/changeProfileImage", $data);
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
?>