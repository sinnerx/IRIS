<?php
class Controller_User
{
	public function __construct()
	{
		## get available (to add) user level.
		$this->userLevelR	= Array(
						2=>"Site Manager",
						3=>"Cluster Lead"
									);
	}

	public function lists($page = 1)
	{
		$data['userLevelR']	= $this->userLevelR;

		## manager and clusterlead only.
		$where[]	= "userLevel IN (2,3)";

		## if got search key.
		if(request::get("search"))
		{
			$where[]	= Array("userProfileFullName LIKE"=>"%".request::get("search","")."%");
		}

		## get paginated user list.
		$data['res_user']	= model::load("user/user")->getPaginatedList($page,url::base("user/lists/{page}"),$where);

		view::render("root/user/lists",$data);
	}

	public function add()
	{
		$data['userLevelR']	= $this->userLevelR;
		$data['userLevel']	= request::get("level");

		## form submitted.
		if(form::submitted())
		{
			$email		= input::get("userEmail");
			$level		= input::get("userLevel");
			$userIC		= input::get("userIC");

			## email and ic check.
			$emailCheck	= model::load("user/services")->checkEmail($email);
			$icCheck	= model::load("user/services")->checkIC($userIC);

			$rules	= Array(
					"userProfileFullName,userLevel,userIC,userEmail"=>"required:This field is required.",
					"userEmail"=>Array(
								"email:Please input a corrent email format.",
								"callback"=>Array(!$emailCheck,"Email already exists.")
										),
					"userIC"=>Array(
								"callback"=>Array(!$icCheck,"IC already exists.")
									)
							);

			## validate input.
			if($error = input::validate($rules))
			{
				input::repopulate();
				redirect::withFlash(model::load("template/services")->wrap("input-error",$error));
				redirect::to("","Got some error in your form.","error");
			}

			## success.
			$data	= Array(
					"userEmail"=>$email,
					"userIC"=>input::get("userIC"),
					"userPassword"=>input::get("userPassword"),
					"userProfileFullName"=>input::get("userProfileFullName"),
					"userProfileTitle"=>input::get("userProfileTitle"),
					"userProfileGender"=>input::get("userProfileGender"),
					"userProfileDOB"=>input::get("userProfileDOB"),
					"userProfilePOB"=>input::get("userProfilePOB"),
					"userProfileMarital"=>input::get("userProfileMarital"),
					"userProfilePhoneNo"=>input::get("userProfilePhoneNo"),
					"userProfileMailingAddress"=>input::get("userProfileMailingAddress")
							);

			## add!
			model::load("user/user")->add($data,$level);

			## success.
			redirect::to("","New user has successfully been registered.","success");
		}

		view::render("root/user/add",$data);
	}

	public function edit($userID)
	{
		$user			= model::load("user/user");
		$data['row']	= $user->get($userID);
		$data['userLevelR']	= $this->userLevelR;

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
					"userProfileFullName,userIC,userEmail"=>"required:This field is required.",
					"userEmail"=>Array(
								"email:Please input a corrent email format.",
								"callback"=>Array(!$emailCheck,"Email already exists.")
										),
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

		view::render("root/user/edit",$data);
	}

	public function resetPassword($userID)
	{
		model::load("user/services")->resetPassword($userID);

		redirect::to("user/lists","Password has been reset.");
	}
}

?>