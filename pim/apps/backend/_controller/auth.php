<?php

Class Controller_Auth
{
	## hooked in routes.
	private function index()
	{
		if(!session::has("userLevel") && controller::getCurrentMethod() != "login")
		{
			redirect::to("../404","Because you're not logged in.","error");
		}
		else 
		{
			if(controller::getCurrentMethod() == "login" && session::has("userLevel"))
			{
				redirect::to("home/index");
			}
		}
	}

	## accessed by : dashboard/login.
	public function login()
	{
		$this->template	= false;

		if(form::submitted())
		{
			$rule	= Array("_all"=>"required","userEmail"=>"email:Please write a correct email format.");
			$error	= input::validate($rule);

			## got validation error.
			if($error)
			{
				flash::set($error);
				input::repopulate();
				redirect::to("");
			}
			## validation success.
			else
			{
				## use auth model.
				$auth	= model::load("auth");
				$site	= model::load("site");

				$email	= input::get("userEmail");
				$pass	= input::get("userPassword");

				## login check
				$backendLoginCheck	= $auth->backendLoginCheck($email,$pass);

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
				redirect::to("home/index");
			}
		}

		view::render("auth/login");
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