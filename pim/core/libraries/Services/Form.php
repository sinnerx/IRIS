<?php

class Form
{
	private static function buildAttr($attr)
	{
		if(is_string($attr) || is_int($attr) || !$attr)
			return $attr;

		$str	= "";
		foreach($attr as $key=>$val)
		{
			$str	.= $key."='".$val."' ";
		}

		return $str;
	}

	public static function hidden($name,$attr = Null,$value = Null)
	{
		$value		= flash::data("_post.$name",$value);
		$message	= /*session::has("fdata.$name")?"data-message='".session::get("fdata.$name")."'":*/"";
		$attr		= self::buildAttr($attr);
		return "<input name='$name' id='$name' type='hidden' $message $attr value='$value' />";
	}

	public static function text($name,$attr = Null,$value = Null)
	{
		$value		= flash::data("_post.$name",$value);
		$value = htmlspecialchars($value, ENT_QUOTES);
		$message	= /*session::has("fdata.$name")?"data-message='".session::get("fdata.$name")."'":*/"";
		$attr		= self::buildAttr($attr);
		return "<input name='$name' id='$name' type='text' $message $attr value='$value' />";
	}

	public static function submit($value = "Submit",$attr = null)
	{
		$attr		= self::buildAttr($attr);
		return "<input type='submit' $attr value='$value' />";
	}

	public static function textarea($name,$attr = Null,$value = Null)
	{
		$value		= flash::data("_post.$name",$value);
		$value = htmlspecialchars($value, ENT_QUOTES);
		$message	= /*session::has("fdata.$name")?"data-message='".session::get("fdata.$name")."'":*/"";
		$attr		= self::buildAttr($attr);
		return "<textarea name='$name' id='$name' $message $attr>$value</textarea>";
	}

	public static function password($name,$attr = null,$value = Null)
	{
		$value		= flash::data("_post.$name",$value);
		$message	= /*session::has("fdata.$name")?"data-message='".session::get("fdata.$name")."'":*/"";
		$attr		= self::buildAttr($attr);
		return "<input type='password' name='$name' $message id='$name' $attr value='$value' />";
	}

	public static function select($name,$array = Array(),$attr = Null,$value = Null,$firstVal	= "[PLEASE CHOOSE]")
	{
		$array		= is_array($array)?$array:Array();
		$value		= flash::data("_post.$name",$value);
		$message	= /*session::has("fdata.$name")?"data-message='".session::get("fdata.$name")."'":*/"";
		$attr		= self::buildAttr($attr);

		$firstVal	= $firstVal !== false?"<option value=''>$firstVal</option>":"";

		$sel	= "<select name='$name' $message id='$name' $attr>$firstVal";
		foreach($array as $key=>$val)
		{
			if($value != "")
				{
					$selected	= ($value == $key)?"selected":"";
				}
			
			$key = htmlspecialchars($key, ENT_QUOTES);
			$val = htmlspecialchars($val, ENT_QUOTES);
			$sel	.= "<option value='$key' $selected>$val</option>";
		}
		$sel	.=	"</select>";
		
		return $sel;
	}

	public static function file($name,$attr = null)
	{
		$attr		= self::buildAttr($attr);
		return "<input type='file' name='$name' id='$name' $attr />";
	}

	public static function fileImage($name,$class,$attr = null)
	{
		$attr		= self::buildAttr($attr);
		return "<input type='file' name='$name' id='$name' $attr class='$class'/>";
	}


	public static function radio($name,$array = Array(),$attr = null,$value = null,$wrapper = null)
	{
		$array		= is_array($array)?$array:Array();
		$value		= flash::data("_post.$name",$value);

		$attr		= self::buildAttr($attr);
		$result		= "";


		foreach($array as $key=>$val)
		{
			$sel	=  $value?($key == $value?"checked":""):"";
			$radio	= "<label><input type='radio' value='$key'  $sel $attr name='$name' />$val</label>";
			$result	.= $wrapper?str_replace("{content}",$radio, $wrapper):$radio;
		}

		return $result;
	}

	public static function submitted($param = null)
	{

		if(request::method() == "POST")
		{
			return true;
		}

		return false;
	}
}



?>