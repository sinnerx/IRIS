<?php

class Form
{
	private function buildAttr($attr)
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

	public function text($name,$attr = Null,$value = Null)
	{
		$value		= flash::data("_post.$name",$value);
		$message	= /*session::has("fdata.$name")?"data-message='".session::get("fdata.$name")."'":*/"";
		$attr		= self::buildAttr($attr);
		return "<input name='$name' id='$name' type='text' $message $attr value='$value' />";
	}

	public function submit($value = "Submit",$attr = null)
	{
		$attr		= self::buildAttr($attr);
		return "<input type='submit' $attr value='$value' />";
	}

	public function textarea($name,$attr = Null,$value = Null)
	{
		$value		= flash::data("_post.$name",$value);
		$message	= /*session::has("fdata.$name")?"data-message='".session::get("fdata.$name")."'":*/"";
		$attr		= self::buildAttr($attr);
		return "<textarea name='$name' id='$name' $message $attr>$value</textarea>";
	}

	public function password($name,$attr = null,$value = Null)
	{
		$value		= flash::data("_post.$name",$value);
		$message	= /*session::has("fdata.$name")?"data-message='".session::get("fdata.$name")."'":*/"";
		$attr		= self::buildAttr($attr);
		return "<input type='password' name='$name' $message id='$name' $attr value='$value' />";
	}

	public function select($name,$array = Array(),$attr = Null,$value = Null,$firstVal	= "[PLEASE CHOOSE]")
	{
		$array		= is_array($array)?$array:Array();
		$value		= flash::data("_post.$name",$value);
		$message	= /*session::has("fdata.$name")?"data-message='".session::get("fdata.$name")."'":*/"";
		$attr		= self::buildAttr($attr);
		$sel	= "<select name='$name' $message id='$name' $attr><option value=''>$firstVal</option>";
		foreach($array as $key=>$val)
		{
			if($value != "")
				{
					$selected	= ($value == $key)?"selected":"";
				}
			
			$sel	.= "<option value='$key' $selected>$val</option>";
		}
		$sel	.=	"</select>";
		
		return $sel;
	}

	public function file($name,$attr = null)
	{
		$attr		= self::buildAttr($attr);
		return "<input type='file' name='$name' id='$name' $attr />";
	}

	public function radio($name,$array = Array(),$attr = null,$value = null,$wrapper = null)
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

	public function submitted($param = null)
	{

		if(request::method() == "POST")
		{
			return true;
		}

		return false;
	}
}



?>