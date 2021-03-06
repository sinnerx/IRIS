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

			$rules	= Array(
					"userProfileFullName,userIC"=>"required:This field is required.",
					"userIC"=>Array(
								"callback"=>Array(!$icCheck,"IC already exists")
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
			$user->fullUpdate($userID,$data);

			redirect::to("","Successfully updated user info.");
		}

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
}