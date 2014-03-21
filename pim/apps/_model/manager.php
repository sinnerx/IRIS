<?php
class Model_Manager
{
	## check email existance. used in manager/add
	public function checkEmail($email)
	{
		db::from("user");
		db::where("userLevel",2);
		db::where("userEmail",$email);
		$row	= db::get()->row();

		return $row?true:false;
	}

	## add into table user, user_profile
	public function add($data)
	{
		## table : user
		$data_user	= Array(
				"userIC"=>str_replace("-", "", $data['userIC']),
				"userEmail"=>$data['userEmail'],
				"userPassword"=>md5($data['userPassword'] == ""?12345:$data['userPassword']),
				"userLevel"=>2,
				"userStatus"=>1,
				"userPremiumStatus"=>1,
				"userCreatedDate"=>now(),
				"userCreatedUser"=>session::get("userID")
						);

		db::insert("user",$data_user);

		## get inserted userID.
		$userID	= db::getLastID("user","userID");

		## table : user_premium
		$data_profile	= Array(
				"userID"=>$userID,
				"userProfileFullName"=>$data['userProfileFullName'],
				"userProfileTitle"=>$data['userProfileTitle'],
				"userProfileGender"=>$data['userProfileGender'],
				"userProfileDOB"=>$data['userProfileDOB'],
				"userProfilePOB"=>$data['userProfilePOB'],
				"userProfileMarital"=>$data['userProfileMarital'],
				"userProfilePhoneNo"=>$data['userProfilePhoneNo'],
				"userProfileMailingAddress"=>$data['userProfileMailingAddress']
						);

		db::insert("user_profile",$data_profile);
	}
}



?>