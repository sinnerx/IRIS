<?php
class Flash
{
	public function set($name,$message = Null)
	{
		if(is_array($name))
		{
			foreach($name as $key=>$message)
			{
				$message	= is_array($message)?implode("",$message):$message;
				self::set($key,$message);
			}
			return;
		}

		if($message === Null)
		{
			$message	= $name;
			$name		= "_main";
		}

		session::set("fdata.$name",$message);
	}

	public function data($name = "_main",$def = null)
	{
		$message	= session::get("fdata.$name",$def);
		return $message;
	}

	public function has($data,$value = null)
	{
		return session::has("fdata.$data");
	}

	public function clear()
	{
		session::destroy("fdata");
	}
}

?>