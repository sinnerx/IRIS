<?php
## only used as a hook, unless login, and logout.
Class Controller_Auth
{
	## hooked in routes.
	private function index()
	{
		if(!session::has("userLevel") && controller::getCurrentMethod() != "login")
		{
			redirect::to("login","Please log in.","error");
		}

		## has logged in, but access dashboard/login.
		if(controller::getCurrentMethod() == "login" && session::has("userLevel"))
		{
			$locR	= Array(
					2=>"site/edit",
					3=>"cluster/overview",
					99=>"site/index"
							);

			redirect::to($locR[session::get("userLevel")]);
		}

		if(controller::getCurrentMethod() != "login")
		{
			## site access authorization.
			$accessServices	= model::load("access/services");
			$level	= $accessServices->accessLevelCode(session::get("userLevel"));

			## get the user row.
			$row_user	= model::load("user/user")->get(session::get("userID"));

			## check default password.
			$userPass		= $row_user['userPassword'];
			$defaultPass	= model::load("user/services")->getDefaultPassword();

			if($userPass == model::load("helper")->hashPassword($defaultPass) && !in_array(controller::getCurrentMethod(),Array("changePassword","logout","login")))
			{
				redirect::to("user/changePassword","Hello there, since this is your first time logged in here, we need you to change the password, before you can start navigating your dashboard.","error");
			}

			if(!$accessServices->accessListCheck($level) && controller::getCurrentMethod() != "login")
			{
				redirect::to("../404","You have no access to these page.");
			}

		## return user record in construct to initiation construct.
		return $row_user;

		}
	}

	## accessed by : dashboard/login.
	public function login()
	{
		#echo model::load("helper")->hashPassword("OG63QKKLGM");die();
		$this->template	= false;

		if(form::submitted())
		{
			$rule	= Array("_all"=>"required","userEmail"=>"email:Please write a correct email format.");
			$error	= input::validate($rule);

			## got validation error.
			if($error)
			{
				flash::set(model::load("template/services")->wrap("input-error",$error));
				input::repopulate();
				redirect::to("");
			}
			## validation success.
			else
			{
				## use auth model.
				$accessAuth	= model::load("access/auth");
				$site		= model::load("site/site");

				$email		= input::get("userEmail");
				$pass		= input::get("userPassword");

				## login check
				$backendLoginCheck	= $accessAuth->backendLoginCheck($email,$pass);

				## login failed.
				if(!$backendLoginCheck)
				{
					input::repopulate();
					redirect::to("","Wrong log-in detail.","error");
				}

				
				## if site manager
				if($backendLoginCheck['userLevel'] == 2)
				{
					$site	= $site->getSiteByManager($backendLoginCheck['userID']);

					## and he didn't have any site yet.
					if(!$site)
					{
						input::repopulate();
						redirect::to("","You haven't been registered to any site yet.");
					}
				}

				## set session : userLevel and userID
				session::set("userLevel",$backendLoginCheck['userLevel']);
				session::set("userID",$backendLoginCheck['userID']);

				## go to home/index
				redirect::to("");
			}
		}

		view::render("shared/auth/login");
	}

	## dashboard/auth/logout
	public function logout()
	{
		## destroy all session.
		session::destroy();

		## redirect to login
		redirect::to("login");
	}
}


?>