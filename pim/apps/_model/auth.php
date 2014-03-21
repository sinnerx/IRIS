<?php

Class Model_Auth
{
	public function backendLoginCheck($email,$password)
	{
		db::from("user");
		db::where(Array(
				"userEmail"=>$email,
				"userPassword"=>md5($password)
						));

		$row = db::get()->row();

		if($row)
		{
			return $row;
		}

		return false;
	}
}

?>