<?php
namespace model\celcom;
use apps, db;
class Auth
{
	private function encryptlink($p)
	{
	  $keySalt = "commaghtUJ6y";
	  $q = "";
	  foreach ($p as $name => $value)
	    $q .= "$name=$value&";
	  $query = base64_encode(urlencode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256,
	                                                  md5($keySalt), $q,
	                                                  MCRYPT_MODE_CBC, md5(md5($keySalt)))));
	  return $query;
	}

	public function update_user($username, $password, $site_nid, $action = null)
	{
		## temporary return until we have a clear go.
		$action		= !$action?(apps::config("env") == "dev"?"test":"update"):$action;

		$p = array();
		$p['username'] = $username;
		$p['pwd'] = $password;
		$p['group_nid'] = 8309;
		$p['uid'] = 0;
		$p['disable'] = 'N';
		$p['project_id'] = 3715;
		$p['site_nid'] = $site_nid;
		$q = $this->encryptlink($p);

		// $link = "http://cahms.comm-alliance.my/api.php?action=$action&data=".$q;
		$link = 'http://ktw.celcom1cbc.com/api.php?action='.$action.'&data='.$q;

		$reply=file_get_contents($link);

		if(trim($reply) == "OK" || (apps::config("env") == "dev" && $reply != ""))
		{
			## update the synced for this user, everytime this function was called.
			$userID	= db::select("userID")->where("userIC",$username)->get("user")->row("userID");
			db::where("userID",$userID)->update("site_member",Array("siteMemberSynced"=>1));
		}

		return $reply;
	}

	// For testing
	# update_user("kat", "123", "389", "test");

	// For real
	//update_user("kat", "1234", "389", "update");
}