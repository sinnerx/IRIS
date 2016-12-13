<?php
namespace model\user;
use db, session, pagination, model, apps;

class User extends \Origami
{
	protected $table = 'user';
	protected $primary = 'userID';

	/**
	 * List of level constants.
	 */
	const LEVEL_MEMBER = 1;
	const LEVEL_SITEMANAGER = 2;
	const LEVEL_CLUSTERLEAD = 3;
	const LEVEL_OPERATIONMANAGER = 4;
	const LEVEL_FINANCIALCONTROLLER = 5;
	const LEVEL_HQADMIN = 6;
	const LEVEL_ROOT = 99;
	const LEVEL_DEVELOPER = 999;

	/**
	 * ORM : Delete user, set flag as deleted.
	 */
	public function delete()
	{
		$this->userStatus = 3;
		$this->save();
	}

	/**
	 * ORM : Get userProfile.
	 */
	public function getProfile()
	{
		// get user_profile anonymously.
		return $this->getOne(array('user_profile', 'userProfileID'), 'userID');
	}

	/**
	 * ORM : Get site manager (only with status == 1)
	 */
	public function getSiteManager()
	{
		return $this->withQuery(function($db)
		{
			$db->where('siteManagerStatus', 1);
		})->getOne('site/manager', 'userID');
	}

	/**
	 * ORM : Get it's clusterleads record.
	 * @return \Origamis
	 */
	public function getClusterLeads()
	{
		return $this->withQuery(function($db)
		{
			$db->where('clusterLeadStatus', 1);
		})->getMany(array('cluster_lead', 'clusterLeadID'), 'userID');
	}

	/**
	 * ORM : Get user level label
	 * @return string
	 */
	public function getLevelName()
	{
		return $this->levelLabel($this->userLevel);
	}

	/**
	 * ORM : Alias to getLevelName
	 * @return string
	 */
	public function getLevelLabel()
	{
		return $this->getLevelName();
	}

	/**
	 * ORM : Get users level 2, that is not yet a manager.
	 * @return \Origamis
	 */
	public function getAvailableManagers()
	{
		return model::orm('user/user')
		->where('userLevel', self::LEVEL_SITEMANAGER)
		->where('userStatus', 1)
		->where('userID NOT IN (SELECT userID FROM site_manager WHERE siteManagerStatus = 1)')
		->execute();
	}

	/**
	 * ORM : Get available clusterlead with the given clusterID as exception.
	 * @return \Origamis
	 */
	public function getAvailableClusterleads($clusterID)
	{
		return model::orm('user/user')
		->where('userLevel', self::LEVEL_CLUSTERLEAD)
		->where('userStatus', 1)
		->where('userID NOT IN (SELECT userID FROM cluster_lead WHERE clusterLeadStatus = 1 AND clusterID = ?)', array($clusterID))
		->execute();
	}

	/**
	 * ORM : User is level 2 and, is still site manager of some site.
	 * @return boolean.
	 */
	public function isManager()
	{
		if($this->userLevel == self::LEVEL_SITEMANAGER)
		{
			$siteManager = $this->getSiteManager();

			if($siteManager)
				return true;
		}

		return false;
	}

	/**
	 * ORM : Check if user level is financial controller
	 * @return bool
	 */
	public function isFinancialController()
	{
		return $this->userLevel == self::LEVEL_FINANCIALCONTROLLER;
	}

	/**
	 * ORM : Check if user level is operation manager
	 * @return bool
	 */
	public function isOperationManager()
	{
		return $this->userLevel == self::LEVEL_OPERATIONMANAGER;
	}

	/**
	 * ORM : Check if user level is root
	 * @return bool
	 */
	public function isRoot()
	{
		return $this->userLevel == self::LEVEL_ROOT;
	}

	/**
	 * ORM : Check if user level is clusterlead
	 * @return bool
	 */
	public function isClusterLead()
	{
		return $this->userLevel == self::LEVEL_CLUSTERLEAD;
	}

	/**
	 * ORM : Check if user level is HQ admin
	 * @return bool
	 */
	public function isHQAdmin()
	{
		return $this->userLevel == self::LEVEL_HQADMIN;
	}

	public function getListOfUser($cols = "*",$cond = null)
	{
		db::from("user");
		db::join("user_profile","user_profile.userID = user.userID");

		db::select($cols);

		if($cond)
		{
			db::where($cond);
		}
		
		return db::get()->result();
	}

	public function getUsersByID($userID)
	{
		db::where("user.userID",$userID);
		db::join("user_profile","user_profile.userID = user.userID");

		return db::get("user")->result("userID");
	}

	## abstract get of single record.
	public function get($userID,$cols = null)
	{
		db::from("user");
		db::where("user.userID",$userID);
		db::join("user_profile","user.userID = user_profile.userID");
		db::join("account","account.accountType = '1' AND account.accountRefID = user.userID");

		if($cols)
		{
			$colsR	= explode(",",$cols);

			db::select($cols);

			if(count($colsR) == 1)
			{
				return db::get()->row($cols);
			}
		}

		## and merge.
		$row	= db::get()->row();

		## if this record didn't exists. (due to the past design dont have table account related)
		## now one may get accountID directly.
		if(!$row['accountID'])
		{
			$row_account	= model::load("account/account")->createAccount(1,$userID);
			## return merged one.
			return array_merge($row_account,$row);
		}

		return $row;
	}

	public function levelLabel($no = null)
	{
		$arr	= Array(
				self::LEVEL_MEMBER => "Members",
				self::LEVEL_SITEMANAGER => "Site Manager",
				self::LEVEL_CLUSTERLEAD => "Cluster Lead",
				self::LEVEL_OPERATIONMANAGER=>"Operation Manager",
				self::LEVEL_FINANCIALCONTROLLER=>"Financial Controller",
				self::LEVEL_ROOT => "Root Admin",
				self::LEVEL_DEVELOPER => "Developer"
						);

		return !$no?$arr:$arr[$no];
	}

	## check user ic.
	public function checkIC($ic)
	{
		db::from("user")->where("userIC",$ic);

		return db::get()->row()?true:false;
	}

	## full update except password.
	public function fullUpdate($userID,$data)
	{
		if($data['userIC'])
		{
			$data_user	= Array(
					"userIC"=>$data['userIC']
							);
		}

		if(isset($data['userEmail']))
		{
			$data_user['userEmail']	= $data['userEmail'];
		}

		if(isset($data['userPassword']) && $data['userPassword'] != "")
		{
			$data_user['userPassword']	= model::load("helper")->hashPassword($data['userPassword']);

		}

		$data_user['userUpdatedDate'] = date('Y-m-d H:i:s');
		$data_user['userUpdatedUser'] = session::get('userID');
		$data_user['userOKUStatus'] = $data['userOKUStatus'];

		db::where('userID',$userID)->update('user',$data_user);
		
		$data_profile	= Array(
					"userProfileFullName"=>$data['userProfileFullName'],
					"userProfileLastName"=>$data['userProfileLastName'],
					"userProfileTitle"=>$data['userProfileTitle'],
					"userProfileGender"=>$data['userProfileGender'],
					"userProfileDOB"=>$data['userProfileDOB'],
					"userProfilePOB"=>$data['userProfilePOB'],
					"userProfileMarital"=>$data['userProfileMarital'],
					"userProfilePhoneNo"=>$data['userProfilePhoneNo'],
					"userProfileMobileNo"=>$data['userProfileMobileNo'],
					"userProfileMailingAddress"=>$data['userProfileMailingAddress'],
					"userRace"=>$data['userRace'],
								);

		db::where('userID',$userID)->update("user_profile",$data_profile);

		## update user_profile_additional.
		$this->updateAdditional($userID,$data);

		##
		model::load("user/activity")->create(null,$userID,"member.edit");

	}

	public function updateMember($userID,$data)
	{
		if($data['userIC'])
		{
			$data_user	= Array(
					"userIC"=>$data['userIC']
							);
		}

		if(isset($data['userEmail']))
		{
			$data_user['userEmail']	= $data['userEmail'];
		}

		if(isset($data['userPassword']) && $data['userPassword'] != "")
		{
			$data_user['userPassword']	= model::load("helper")->hashPassword($data['userPassword']);

		}

		$data_user['userUpdatedDate'] = date('Y-m-d H:i:s');
		$data_user['userUpdatedUser'] = session::get('userID');
		$data_user['userOKUStatus'] = $data['userOKUStatus'];

		db::where('userID',$userID)->update('user',$data_user);
		
		$data_profile = Array(
					"userProfileFullName"=>$data['userProfileFullName'],
					"userProfileLastName"=>$data['userProfileLastName'],
					"userProfileTitle"=>$data['userProfileTitle'],
					"userProfileGender"=>$data['userProfileGender'],
					"userProfileDOB"=>$data['userProfileDOB'],
					"userProfilePOB"=>$data['userProfilePOB'],
					"userProfileMarital"=>$data['userProfileMarital'],
					"userProfilePhoneNo"=>$data['userProfilePhoneNo'],
					"userProfileMobileNo"=>$data['userProfileMobileNo'],
					"userProfileMailingAddress"=>$data['userProfileMailingAddress'],
					"userRace"=>$data['userRace'],
								);

		db::where('userID',$userID)->update("user_profile",$data_profile);

		##
		model::load("user/activity")->create(null,$userID,"member.edit");

	}

	public function deleteMember($userID)
	{
		db::where("userID",$userID);
		db::update("user",Array("userStatus"=>3));

		##
		model::load("user/activity")->create(null,$userID,"member.delete");
	}

	public function updateAvatarPhoto($userID,$path)
	{
		db::where("userID",$userID);
		db::update("user_profile",Array("userProfileAvatarPhoto"=>$path));
	}

	public function getAdditional($userID)
	{
		return db::where("userID",$userID)->get("user_profile_additional")->row()?:Array();
	}

	public function updateAdditional($userID,$data)
	{
		## check if got this table already.
		$check	= db::select("userProfileAdditionalID")->where("userID",$userID)->get("user_profile_additional")->row();

		$data	= Array(
				"userProfileOccupation"=>$data['userProfileOccupation'],
				"userProfileOccupationGroup"=>$data['userProfileOccupationGroup'],
				"userProfileFacebook"=>$data['userProfileFacebook'],
				"userProfileTwitter"=>$data['userProfileTwitter'],
				"userProfileWeb"=>$data['userProfileWeb'],
				"userProfileEcommerce"=>$data['userProfileEcommerce'],
				"userProfileIntroductional"=>$data['userProfileIntroductional'],
				"userProfileVideo"=>$data['userProfileVideo']
						);

		if(!$check)
		{
			$data['userID']	= $userID;
			db::insert("user_profile_additional",$data);
		}
		else
		{
			db::where("userID",$userID)->update("user_profile_additional",$data);
		}
	}

	## member registration.
	public function registerMember($userIC,$data)
	{
		## insert user.
		$data_user	= Array(
					"userIC"=>$userIC,
					// "userPremiumStatus"=>0,
					"userLevel"=> self::LEVEL_MEMBER,
					"userStatus"=>1,
					"userPassword"=>model::load("helper")->hashPassword($data['userPassword'])
							);

		db::insert("user",$data_user);

		$userID	= db::getLastID("user","userID");

		## insert profile.
		$data_profile	= Array(
					"userID"=>$userID,
					"userProfileDOB"=>$data['userProfileDOB']
								);
		db::insert("user_profile",$data_profile);

		return $userID;
	}

	## add user,
	public function add($data,$level)
	{
		//apps::config('aveo');
		//die;

		$data_user	= Array(
					"userIC"=>$data['userIC'],
					"userPassword"=>model::load("helper")->hashPassword($data['userPassword']?$data['userPassword']:model::load("user/services")->getDefaultPassword()),
					"userEmail"=>$data['userEmail'],
					"userLevel"=>$level,
					"userLevelManager" => $data['userLevelManager'],
					"userStatus"=>$data['userStatus']?$data['userStatus']:1,
					#"userPremiumStatus"=>$data['userPremiumStatus']?$data['userPremiumStatus']:0,
					"userCreatedDate"=>now(),
					"userCreatedUser"=>session::get("userID"),
					'userUpdatedDate' => now(),
					'userUpdatedUser' => session::get('userID')
							);

		## insert into user.
		db::insert("user",$data_user);

		$userID	= db::getLastID("user","userID");
		$data_profile	= Array(
					"userID"=>$userID,
					"userProfileFullName"=>$data['userProfileFullName'],
					"userProfileLastName"=>$data['userProfileLastName'],
					"userProfileTitle"=>$data['userProfileTitle'],
					"userProfilePhoneNo"=>$data['userProfilePhoneNo'],
					"userProfileGender"=>$data['userProfileGender'],
					"userProfilePOB"=>$data['userProfilePOB'],
					"userProfileMarital"=>$data['userProfileMarital'],
					"userProfileDOB"=>$data['userProfileDOB'],
					"userProfileMobileNo"=>$data['userProfileMobileNo'],
					"userProfileMailingAddress"=>$data['userProfileMailingAddress'],
								);

		## insert into profile.
		db::insert("user_profile",$data_profile);

		## insert into additonal.
		$data_additional	= Array(
					"userID"=>$userID,
					"userProfileOccupation"=>$data['userProfileOccupation'],
					"userProfileOccupationGroup"=>$data['userProfileOccupationGroup'],
					"userProfileEducation"=>$data['userProfileEducation'],
					"userProfileTwitter"=>$data['userProfileTwitter'],
					"userProfileEcommerce"=>$data['userProfileEcommerce'],
					"userProfileWeb"=>$data['userProfileWeb'],
					"userProfileIntroductional"=>$data['userProfileIntroductional']
									);

		db::insert("user_profile_additional",$data_additional);

		## create user account.
		model::load("account/account")->createAccount(1,$userID);

		## return user record.
		return $this->get($userID);
	}

	/**
	 * ORM : return \Origamis.
	 * @return \Origamis
	 */
	public function getUsers($where = null, $pagination = null)
	{
		$users = model::orm('user/user')
		->select('user_profile.*, user.*')
		->where('userStatus', 1)
		->join('user_profile', 'user_profile.userID = user.userID');

		if($where)
		{
			$where	= !is_array($where)?Array($where):$where;
			foreach($where as $key => $wher)
			{
				if(is_string($key))
					$users = $users->where($key, $wher);
				else
					$users = $users->where($wher);
			}
		}

		if($pagination)
		{
			$totalRows = db::from('user')->num_rows();
			
			pagination::initiate(Array(
				"totalRow"=>$totalRows,
				"currentPage"=>$pagination['currentPage'],
				"urlFormat"=>$pagination['urlFormat']
								));
			// var_dump(pagination::recordNo());
			// die;
			$users = $users->limit(pagination::get('limit'), pagination::recordNo()-1);
		}


		return $users->execute();
	}

	## return list of paginated, format : []
	public function getPaginatedList($currentPage,$urlFormat,$where = null)
	{
		db::from("user");
		## if got additional condition.
		if($where)
		{
			$where	= !is_array($where)?Array($where):$where;
			foreach($where as $wher)
			{
				db::where($wher);
			}
		}

		db::select("user_profile.*,user.*");
		db::join("user_profile","user_profile.userID = user.userID");

		## total rows
		$totalRows	= db::num_rows();

		pagination::initiate(Array(
						"totalRow"=>$totalRows,
						"currentPage"=>$currentPage,
						"urlFormat"=>$urlFormat
									));
		
		db::limit(pagination::get("limit"),pagination::recordNo()-1);

		$result	= db::get()->result();

		return $result;
	}

	## get user by email.
	public function getUserByEmail($email,$column = null)
	{
		db::from("user")
		->where("userEmail",$email)
		->join("user_profile","user.userID = user_profile.userID");

		return db::get()->row($column);
	}

	## change password by userIC.
	public function changePasswordByIC($userIC,$password)
	{
		db::where("userIC",$userIC)->update("user",Array(
							"userPassword"=>model::load("helper")->hashPassword($password)
														));
	}

	public function ICChanges($dataParam)
	{	
		$data = Array(
				"userID"=>$dataParam['userID'],
				"newIC"=>$dataParam['newIC'],
				"oldIC"=>$dataParam['oldIC'],
				"ICUpdatedDate"=>now()
			);
		db::insert("user_ic",$data);		
	}
}

?>