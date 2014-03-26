<?php

class Model_Template
{
	public function wrap($template,$content)
	{
		if(is_array($content))
		{
			$newR	= Array();
			foreach($content as $key=>$value)
			{
				if(is_array($value))
				{
					continue;
				}

				$newR[$key]	= $this->wrap($template,$value);
			}

			return $newR;
		}

		## replace content with template.
		if($this->getTemplate($template))
		{
			return str_replace("{content}",$content,$this->getTemplate($template));;
		}

		return $content;
	}

	public function getTemplate($key = null)
	{
		$template['input-error']	= "<div class='label label-danger'>{content}</div >";

		return !$key?$template:(isset($template[$key])?$template[$key]:false);
	}
}


?>