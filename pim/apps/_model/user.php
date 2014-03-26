<?php

class Model_User
{
	## abstract get.
	public function get($userID,$cols = null)
	{
		db::from("user");
		db::where("user.userID",$userID);
		db::join("user_profile","user.userID = user_profile.userID");

		if($cols)
		{
			$colsR	= explode(",",$cols);

			db::select($cols);

			if(count($colsR) == 1)
			{
				return db::get()->row($cols);
			}
		}

		## save record in row_user.

		return db::get()->row();
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
}

?>