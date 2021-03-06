<?php
## only used as a hook, unless login, and logout.
//require_once "asset_integration.php";


Class Controller_Auth
{
	## hooked in routes as for logged in user.
	
	private function index()
	{
		
		## if page was public, no need for further check.
		if(model::load("access/services")->checkPublicBackend())
		{
			return;
		}

		## use didn't log in.
		if(!session::has("userLevel"))
		{
			redirect::to("login","Please log in.","error");
		}

		## has logged in, but access dashboard/login.
		/*if(in_array(controller::getCurrentMethod(),Array("login","passwordReset")) && session::has("userLevel"))
		{
			$locR	= Array(
					2=>"site/overview",
					3=>"cluster/overview",
					99=>"site/index"
							);

			#redirect::to($locR[session::get("userLevel")]);
		}*/

		## site access authorization.
		$accessServices	= model::load("access/services");
		$level	= $accessServices->accessLevelCode(session::get("userLevel"));

		## get authenticated data.
		$data			= model::load("access/auth")->authUser(session::get("userID"));
		$row_user		= $data['user'];

		## check default password.
		$userPass		= $row_user['userPassword'];
		$defaultPass	= model::load("user/services")->getDefaultPassword();


		## 1. if password = default check.
		if($userPass == model::load("helper")->hashPassword($defaultPass) && !in_array(controller::getCurrentMethod(),Array("changePassword","logout","login")))
		{
			redirect::to("user/changePassword","Hello there, since this is your first time logged in here, we need you to change the password, before you can start navigating your dashboard.","error");
		}

		## 3. access list check.
		if(!$accessServices->accessListCheck($level) && controller::getCurrentMethod() != "login")
		{
			redirect::to("../404?error=noaccess");
		}

		## return user record in construct to initiation construct.
		return $row_user;
	}

	## accessed by : dashboard/login.
	public function login()
	{
		//print_r($_SERVER['DOCUMENT_ROOT'].'/snipeit/bootstrap/autoload.php');
		
		//print_r($app);
		//die;
		//print_r(Session::get("userLevel"));
		#echo model::load("helper")->hashPassword("OG63QKKLGM");die();
		$this->template	= false;
		//Session::put('key', 'value');
		## if logged member tried to go to backed, redirect to his site.
		if(session::get("userLevel") == 1)
		{
			$siteSlug	= model::load("site/site")->getSiteByMember(session::get("userID"),"siteSlug");

			redirect::to("../$siteSlug");
		}

		## if is logged in.
		if(session::has("userID"))
		{
			redirect::to(model::load("access/data")->firstLoginLocation(session::get("userLevel")));
		}

		if(form::submitted())
		{
			## if got change password field submitted.
			if(request::get("changePassword") == 1)
			{
				$token	= input::get("token");

				## check resetpass token.
				$row_token		= model::load("access/token")->getTokenInfo($token);

				## not found. then this token is invalid.
				if(!$row_token)
				{
					redirect::to("login","Invalid token","error");
				}

				$password	= input::get("userPassword");
				$confirm	= input::get("userPasswordConfirm");

				## empty.
				if($error = input::validate(Array("except:token"=>"required:This field is required.")))
				{
					input::repopulate();
					redirect::withFlash($error);
					redirect::to("login?token=$token#changePassword");
				}

				## not confirm.
				if($password != $confirm)
				{
					input::repopulate();
					redirect::to("login?token=$token#changePassword","Please confirm your password.","error");
				}

				## change pass and 
				model::load("user/services")->changePassword($row_token['userID'],$password);
				
				## use token.
				model::load("access/token")->useResetpassToken($token);
				
				## and redirect back to login.
				redirect::to("login","You have successfully changed your password. Please log-in with your new credential.");
			}

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

				//require_once $_SERVER['DOCUMENT_ROOT'].'/snipeit/bootstrap/autoload.php';
				//$app = require_once $_SERVER['DOCUMENT_ROOT'].'/snipeit/bootstrap/start.php';

// 				$included_files = get_included_files();

// foreach ($included_files as $filename) {
//     echo "$filename <br>";
// }
				// if (isset($_COOKIE[$app['config']['session.cookie']])) {
				//     $id = $app['encrypter']->decrypt($_COOKIE[$app['config']['session.cookie']]);
				//     //print_r($id);
				//     $app['session']->driver()->setId($id);
				// }
				//     $app->boot();

				//     $app['session']->driver()->start();
				//     //print_r($app['session']->driver());
				//     // Login credentials
				//     // $credentials = array(
				//     //     'email'    => 'root@gmail.com',
				//     //     'password' => '12345',
				//     // );
				//         $credentials = array(
				//         'email'    => urldecode($_POST['userEmail']),
				//         'password' => urldecode($_POST['userPassword']),
				//     );

				//     // Authenticate the user
				//     $user = Sentry::authenticate($credentials, false);    
				//     //Session::put('test','alan');
				//     //Session::save();
				//     die;


				## login check
				$backendLoginCheck	= $accessAuth->backendLoginCheck($email,$pass);

				## login failed.
				if(!$backendLoginCheck)
				{
					flash::set("forgot_pass","<span class='forgot-pass'>Forgot Password?</span>");
					input::repopulate();
					redirect::to("","Wrong log-in detail.","error");
				}

				## if site manager
				if($backendLoginCheck->userLevel == 2)
				{
					if($backendLoginCheck->userStatus == 3)
					{
						input::repopulate();
						redirect::to('', 'Your account has been disabled', 'error');
					}

					$site	= $site->getSiteByManager($backendLoginCheck->userID);

					## and he didn't have any site yet.
					if(!$site)
					{
						input::repopulate();
						redirect::to("","You haven't been registered to any site yet.", 'error');
					}
				}

				## login.
				//exec(getcwd()."/pim/apps/backend/_controller/asset_integration.php");
				$accessAuth->login($backendLoginCheck->userID,$backendLoginCheck->userLevel);
				$_SESSION['userid'] = $backendLoginCheck->userID;
				$_SESSION['userLevel'] = $backendLoginCheck->userLevel;
				$_SESSION['userIC'] = $backendLoginCheck->userIC;


				$url = "http://localhost/sentry/api.php";
				$fields_string = '';
				$fields = array(
				            'userEmail' 	=>	urlencode($_POST['userEmail']),
				            'userPassword' 	=>	urlencode($_POST['userPassword']),
				            'method'		=>	urlencode('1')
				            //'btnSubmit'		=>	urlencode('Submit')
				        );

				//url-ify the data for the POST
				foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
				rtrim($fields_string,'&');				

				$ch = curl_init();
				
				//set the url, number of POST vars, POST data
				curl_setopt($ch,CURLOPT_URL,$url);
				curl_setopt($ch,CURLOPT_POST,count($fields));
				curl_setopt($ch,CURLOPT_POSTFIELDS,$fields_string);				
				curl_setopt($ch, CURLOPT_COOKIE, $_COOKIE['laravel_session']);
				curl_setopt($ch, CURLOPT_COOKIESESSION, true);

				curl_exec($ch);
				curl_close($ch);				

				//$url = "http://localhost/sentry/api.php";
				

				// $http = new HttpRequest($url, HttpRequest::METH_POST);
				// $http->setOptions(array(
				//     'timeout' => 10,
				//     'redirect' => 4
				// ));
				// $http->addPostFields(array(
				//     'userEmail' 	=> $email,
				//     'userPassword' 	=> $pass,
				//     'method' 		=> '1',
				// ));

				// $response = $http->send();
				

				## go to home/index
				redirect::to(model::load("access/data")->firstLoginLocation($backendLoginCheck->userLevel));
			}
		}

		view::render("shared/auth/login");
	}

	## controller for authenticating token, and do it's logic by type.
	public function authenticateToken($token)
	{
		$accessToken	= model::load("access/token");
		$row_token		= $accessToken->getTokenInfo($token);

		## not found. then this token is invalid.
		if(!$row_token)
		{
			redirect::to("../404?tokeninvalid");
		}

		switch($row_token['tokenType'])
		{
			case 1: ## 1. reset password.
			## Login the user.
			#$level	= model::load("user/user")->get($row_token['userID'],"userLevel");
			#model::load("access/auth")->login($row_token['userID'],$level);

			## activate resetpass token
			$accessToken->activateResetpass($row_token['userID'],$row_token['tokenID']);

			## redirect to current
			redirect::to("login?token=$token#changePassword");
			break;
		}
	}

	public function resetPassword()
	{
		if(request::get("email"))
		{
			$email	= request::get("email");
			## get id by email.
			$userID = model::load("user/user")->getUserByEmail($email,"userID");

			## reset password.
			if($userID)
			{
				model::load("user/services")->resetPassword($userID);
			}
		}

		redirect::to("login","An email has been sent along with the reset link.");
	}

	## dashboard/auth/logout
	public function logout()
	{
		## destroy all session.
		session::destroy();

		## redirect to login
		redirect::to("login");
	}

	public function check_login($email = null,$pass = null)
	{
		//echo "abc";
		//die;
				$email		= $_POST['userEmail'];
				$pass		= $_POST['userPassword'];
				//echo $pass;
				//die;		
				$accessAuth	= model::load("access/auth");
		$backendLoginCheck	= $accessAuth->backendLoginCheck($email,$pass);
		print_r($backendLoginCheck->modelData['attributes']['userID']);
		die;
		return $backendLoginCheck;
	}
}


?>