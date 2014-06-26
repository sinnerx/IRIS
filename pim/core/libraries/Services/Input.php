<?php

class Input
{
	private static $file	= Array();

	public static function get($name = null,$default = null)
	{
		return request::post($name,$default);
	}

	### flash input. if name is null, flash all. else, selected. if got except: flash all, except the input..
	public static function repopulate($name = null,$baseData = null)
	{
		## if got baseData, will base the repopulated data base on it, else use _POST.
		$post	= !$baseData?request::post():$baseData;

		$filteredPOST	= filter_array($post,!$name?"_all":$name);
		
		foreach($filteredPOST as $key=>$val)
		{
			flash::set('_post.'.$key,$val);
		}
	}

	public static function validate($rule)
	{
		return validator::validate(request::post(),$rule);
	}

	public static function file($name)
	{
		### file name.
		if(isset(self::$file[$name]))
		{
			return $file[$name];
		}

		### file checking.
		if(!isset($_FILES[$name]))
		{
			return false;
		}

		## empty checking.
		if($_FILES[$name]['name'] == "")
		{
			return false;
		}

		$file	= new Uploaded_File($_FILES[$name]);
		self::$file[$name]	= $file;

		return $file;
	}
}

class Uploaded_File
{
	private $file;
	public function __construct($file)
	{
		$this->file	= $file;
	}

	## get file record.
	public function get($key)
	{
		if(in_array($key,Array("name","type","size","tmp_name","error")))
		{
			return $this->file[$key];
		}

		if(in_array($key,Array("ext","extension")))
		{
			$v		= explode(".",$this->get("name"));
			$ext	= array_pop($v);
			
			return $ext;
		}
	}

	## comma based, or array extension check..
	public function isExt($extensions)
	{
		if(!is_array($extensions))
		{
			$extensions	= explode(",",$extensions);
		}

		if(!in_array($this->get('ext'),$extensions))
		{
			return false;
		}

		return true;
	}

	## destination_path
	public function move($destination_path,$name = null)
	{
		## get original path.
		$original_file	= $this->get('tmp_name');
		#$name		= !$name?$this->get("name"):$name;

		## build file path.
		$file_path	= !$name?$destination_path:(trim($destination_path,"/")."/".$name);

		## move_upload.
		return move_uploaded_file($original_file, $file_path);
	}
}

?>