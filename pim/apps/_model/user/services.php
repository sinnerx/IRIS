<?php
namespace model\user;
use db, model, url;
class Services
{
	## check email existance. used in manager/add and manager/edit
	public function checkEmail($email,$userID = null)
	{
		db::select("userID,userLevel");
		db::from("user");
		db::where("userEmail",$email);

		## exception when userID is passed.
		if($userID)
		{
			db::where("userID !=",$userID);
		}

		$row	= db::get()->row();

		return $row?true:false;
	}

	## check ic existence.
	public function checkIC($ic)
	{
		db::select("userID");
		db::from("user");
		db::where("userIC",$ic);

		return db::get()->row();
	}

	## used by root/user/resetPassword, and auth/resetPassword.
	public function resetPassword($userID)
	{
		#$resetPass		= substr(md5(rand()),0,5);
		$row_user		= model::load("user/user")->get($userID);
		$name			= $row_user['userProfileFullName'];
		$email			= $row_user['userEmail'];

		## create token.
		$accessToken	= model::load("access/token");
		$row_token		= $accessToken->createToken(1,Array("userID"=>$userID));
		$tokenName		= $row_token['tokenName'];

		## get link with token appended.
		$link			= $accessToken->accessLink($tokenName);

		## get reset pass mail template.
		$templateServices	= model::load("template/services");
		$mailSubject		= $templateServices->getTemplate("mail","resetpass-subject");
		$mailContent		= $templateServices->getTemplate("mail","resetpass-content",Array("userProfileFullName"=>$name,"resetLink"=>$link));

		## mail.
		if(!model::load("mailing/services")->mail(null,$email,$mailSubject,$mailContent))
		{
			echo model::load("mailing/services")->getInstance()->ErrorInfo;
			die();
		}

		## update.
		#db::where("userID",$userID);
		#db::update("user",Array("userPassword"=>model::load("helper")->hashPassword($resetPass)));
	}

	## changePassword.
	public function changePassword($userID,$newPassword)
	{
		db::where("userID",$userID);
		db::update("user",Array(
						"userPassword"=>model::load("helper")->hashPassword($newPassword)
								));

		// *** Start API for AVEO ***

	    $url = 'http://localhost/aveo/app/controllers/api/user.php';

	    //1: create, 2: update, 3: change password, 4: delete
	    $process = 3;

	    $id = $userID;
	    $password = $password;

	    $myvars = 'process=' . $process;
	    $myvars .= '&id=' . $id;
	    $myvars .= '&password=' . $newPassword;

	    $ch = curl_init( $url );
	    curl_setopt( $ch, CURLOPT_POST, 1);
	    curl_setopt( $ch, CURLOPT_POSTFIELDS, $myvars);
	    curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
	    curl_setopt( $ch, CURLOPT_HEADER, 0);
	    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);

	    $response = curl_exec( $ch );

		// *** End API for AVEO ***
	}

	## return default password for everyone.
	public function getDefaultPassword()
	{
		return "OG63QKKLGM";
	}

	public function deactivateUser($userID)
	{
		db::where("userID",$userID);
		db::update("user",Array("userStatus"=>0));
	}
}

?>