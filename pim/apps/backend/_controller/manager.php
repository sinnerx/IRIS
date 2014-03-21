<?php

class Controller_Manager
{
	public function add()
	{
		if(form::submitted())
		{
			$manager	= model::load("manager");
			$email		= input::get("userEmail");


			$rules	= Array(
					"userProfileFullName,userIC,userEmail"=>"required:This field is required..",
					"userEmail"=>Array(
								"email:Please input a correct email",
								"callback"=>Array(!$manager->checkEmail($email),"This email already registered.")
										)
							);

			### validate rules.
			if($error = input::validate($rules))
			{
				input::repopulate();

				## wrap with template : input-error
				redirect::withFlash(model::load("template")->wrap("input-error",$error));
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

			## add manager.
			$manager->add($data);

			redirect::to("","New manager record has been registered.","success");
		}

		view::render("manager/add");
	}
}


?>