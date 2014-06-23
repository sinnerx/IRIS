<?php
namespace model\user;
use db, session, pagination, model;
class User
{
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
				1=>"Members",
				2=>"Site Manager",
				3=>"Cluster Lead",
				4=>"Operation Manager",
				99=>"Root Admin"
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
		$data_user	= Array(
				"userIC"=>$data['userIC']
						);

		if(isset($data['userEmail']))
		{
			$data_user['userEmail']	= $data['userEmail'];
		}

		if(isset($data['userPassword']) && $data['userPassword'] != "")
		{
			$data_user['userPassword']	= model::load("helper")->hashPassword($data['userPassword']);
		}

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
					"userProfileMailingAddress"=>$data['userProfileMailingAddress']
								);

		db::where('userID',$userID)->update("user_profile",$data_profile);

		## update user_profile_additional.
		$this->updateAdditional($userID,$data);
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
				"userProfileFacebook"=>$data['userProfileFacebook'],
				"userProfileTwitter"=>$data['userProfileTwitter'],
				"userProfileWeb"=>$data['userProfileWeb'],
				"userProfileEcommerce"=>$data['userProfileEcommerce'],
				"userProfileIntroductional"=>$data['userProfileIntroductional']
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
					"userLevel"=>1,
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
		$data_user	= Array(
					"userIC"=>$data['userIC'],
					"userPassword"=>model::load("helper")->hashPassword($data['userPassword']?$data['userPassword']:model::load("user/services")->getDefaultPassword()),
					"userEmail"=>$data['userEmail'],
					"userLevel"=>$level,
					"userStatus"=>$data['userStatus']?$data['userStatus']:1,
					"userPremiumStatus"=>$data['userPremiumStatus']?$data['userPremiumStatus']:0,
					"userCreatedDate"=>now(),
					"userCreatedUser"=>session::get("userID")
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
					"userProfilePOB"=>$data['userProfileDOB'],
					"userProfileMarital"=>$data['userProfileMarital'],
					"userProfileDOB"=>$data['userProfileDOB'],
					"userProfileMobileNo"=>$data['userProfileMobileNo'],
					"userProfileMailingAddress"=>$data['userProfileMailingAddress']
								);

		## insert into profile.
		db::insert("user_profile",$data_profile);

		## create user account.
		model::load("account/account")->createAccount(1,$userID);

		## return user record.
		return $this->get($userID);
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
}

?>