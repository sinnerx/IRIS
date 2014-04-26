<?php
namespace model\template;

class Services extends repository
{
	public function getTemplate($type,$name,$param = Array())
	{
		$template	= $this->templateList($type,$name);

		## template not found;
		if(!$template)
		{
			return false;
		}

		## rebuild template with param.
		$rebuiltTemplate	= $this->prepareTemplate($template,$param);

		return $rebuiltTemplate;
	}

	public function prepareTemplate($template,$param)
	{
		foreach($param as $key=>$value)
		{
			## search key and replace with value.
			$template	= str_replace('{'.$key.'}',$value,$template);
		}

		return $template;
	}

	## same with getTemplate, but wrap with default type, without param.
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
		$template	= $this->templateList("default",$template);
		if(!$template)
		{
			$content;
		}

		$rebuiltTemplate	= $this->prepareTemplate($template,Array("content"=>$content));
		return $rebuiltTemplate;
	}
}

?>