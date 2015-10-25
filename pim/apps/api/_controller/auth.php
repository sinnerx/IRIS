<?php
class Controller_Auth
{
	public function ping()
	{
		return json_encode(array(
			'status' => 'success'
			));
	}
}

?>