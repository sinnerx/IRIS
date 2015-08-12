<?php
class Controller_User
{
	public function __construct()
	{
		## get available (to add) user level.
		$this->userLevelR	= Array(
						2=>"Site Manager",
						3=>"Cluster Lead",
						4=>"Operation Manager",
						5=>"Financial Controller"
									);
	}

	public function lists($page = 1)
	{
		$data['userLevelR']	= $this->userLevelR;

		## manager and clusterlead only.
		$where[]	= "userLevel IN (2,3,4,5)";

		## if got search key.
		if(request::get("search"))
		{
			$searchText = request::get('search');
			$where['userProfileFullName LIKE ? OR userIC LIKE ? OR userEmail = ?'] = array('%'.$searchText.'%', '%'.$searchText.'%', $searchText);
		}

		## get paginated user list.
		$data['users']	= model::load("user/user")->getUsers($where,
			array('currentPage'=> $page,
				  'urlFormat'=> url::base("user/lists/{page}")));

		view::render("root/user/lists",$data);
	}

	public function upgradeUser()
	{
		$data = array();
		if(request::get('ic'))
		{
			$data['row'] = db::where('userIC', request::get('ic'))
			->where('userLevel', 1)
			->join('user_profile', 'user_profile.userID = user.userID')
			->get('user')
			->row();

			if($data['row'] && request::get('userLevel') && request::get('userID'))
			{
				// upgrade this user to the given level.
				db::where('userID', request::get('userID'))->update('user', array(
					'userLevel' => request::get('userLevel')
					));

				$levels = array(2 => 'Manager', 3 => 'Clusterlead');
				redirect::to('user/upgradeUser', 'Successfully upgraded user '.request::get('ic').' to '.$levels[request::get('userLevel')]);
			}
		}

		view::render('root/user/upgradeUser', $data);
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

	public function delete($userID, $param = null)
	{
		$user = model::orm("user/user")->find($userID);

		$profile = $user->getProfile();

		$name = $profile->userProfileFullName.' '.$profile->userProfileLastName;

		// check if he's still site manager of some site. and warm about it.
		$siteManager = $user->getSiteManager();

		if($siteManager)
		{
			if($param != 'override')
			{
				$siteName = $siteManager->getSite()->siteName;
				$url = url::base('user/delete/'.$userID.'/override');
				redirect::to('user/lists', 'This user ('.$name.') is still a manager of site ('.$siteName.'), do you truly want to unlink and delete him? <a class="label label-danger" href="'.$url.'">Yes. Delete</a>', 'error');
			}
			else
			{
				// deactivate sitemanager.
				$siteManager->deactivate();
			}
		}

		// check if he's still cluster lead of some clusters, and warn about it.
		$clusterleads = $user->getClusterLeads();

		if($clusterleads->count() > 0)
		{
			if($param != 'override')
			{
				foreach($clusterleads as $clusterlead)
				{
					$clusters[] = $clusterlead->getOne('site/cluster', 'clusterID')->clusterName;
				}

				$url = url::base('user/delete/'.$userID.'/override');
				redirect::to('user/lists', 'This user ('.$name.') is still a lead for cluster(s) <b>'.implode(', ',$clusters).'</b>. Do you truly want to unlink and delete him? <a class="label label-danger" href="'.$url.'">Yes. Delete</a>', 'error');
			}
			else
			{
				// set status manually since he has no model to do this.
				foreach($clusterleads as $clusterlead)
				{
					$clusterlead->clusterLeadStatus = 0;
					$clusterlead->save();
				}
			}
		}

		// delete this user (actually it just set it status to 3)
		$user->delete();

		redirect::to('user/lists', 'User <u>'.$name.'</u> has been deleted.');
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

			$userIC = str_replace('-', '', input::get('userIC'));

			## ic check.
			$icCheck	= false;
			if($userIC != $data['row']['userIC'])
			{
				$icCheck	= model::load("user/services")->checkIC($userIC);
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
			$data['userIC'] = $userIC;
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