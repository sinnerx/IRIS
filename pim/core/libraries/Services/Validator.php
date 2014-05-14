<?php
######
# Validator Class
# API : validate. setRuleMessage
#
# Ahmad Rahimie @ Eimihar
# Github.com/eimihar
# eimihar.rosengate.com
######

class Validator
{
	private static $message	= Array(
					"required"=>"This field is required.",
					"isString"=>"String only.",
					"email"=>"Please input email only.",
					"min_length"=>"Length must be longer than {length}"
							);

	public function _validate($value,$rule)
	{
		if(strpos($rule,"min_length") === 0)
		{
			$length		= self::getRuleValue("min_length",$rule);
			if(strlen($value) <= $length)
			{
				return false;
			}
		}

		switch($rule)
		{
			case "required":
			if($value == "")
			{
				return false;
			}
			break;
			case "email":
				$mail_pattern = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/";
				if(!preg_match($mail_pattern,trim($value)))
				{
					return false;
				}
			break;
			case "isString":
				return is_string($value);
			break;
		}

		return true;
	}

	public function validate($item,$rules)
	{
		$result	= Array();
		$checked	= Array();

		foreach($rules as $filter=>$rule)
		{
			## filter item based on rule. 1. except, 2. selection, and 3. all.
			$filteredItemR	= filter_array($item,$filter,null,true);

			$ruleR	= !is_array($rule)?explode("|",$rule):$rule;

			## loop filtered item
			foreach($filteredItemR as $key=>$val)
			{
				## loop rule of the current item/s
				foreach($ruleR as $rule_key=>$rule)
				{
					if($rule_key === "callback")
					{
						if($rule[0] == false && !in_array($key,$checked))
						{
							$result[$key]	= $rule[1];
						}
						continue;
					}

					list($rule,$message)	= explode(":",$rule);

					if(!self::_validate($val,$rule))
					{
						## validate once only, if already got error, permit no more check.
						if(in_array($key,$checked))
						{
							continue;
						}

						$checked[]	= $key;
						$result[$key]	= self::getRuleMessage($rule,$message);
					}
				}
			}
		}

		return count($result) > 0?$result:false;
	}

	private function getRuleValue($rule_name,$rule)
	{
		return trim(str_replace($rule_name,"", $rule),"()");
	}

	private function getRuleMessage($rule,$curr = null)
	{
		$msg	= $curr?$curr:(isset(self::$message[$rule])?self::$message[$rule]:null);
		if(strpos($rule,"min_length") === 0)
		{
			$msg	= !$msg?self::$message['min_length']:$msg;
			$length	= self::getRuleValue("min_length",$rule);
			$msg	= str_replace("{length}",$length,$msg);
		}

		return $msg;
	}

	public function setRuleMessage($rule,$message = null)
	{
		if(is_array($rule) && is_null($message))
		{
			foreach($rule as $key=>$msg)
			{
				self::setRuleMessage($key,$msg);
			}

			return;
		}

		if(is_array($rule) && !is_null($message))
		{
			foreach($rule as $val)
			{
				self::setRuleMessage($val,$message);
			}
		}

		self::$message[$rule]	= $message;
	}
}



?>