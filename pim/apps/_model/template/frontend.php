<?php
namespace model\template;

class Frontend
{
	public function paginationFormat()
	{
		$format	= Array(
				"link_format"=>Array("previous","next"),
				"number_limit"=>0,
				"html_previous"=>"<a href='{href}'>< Newer Post</a>",
				"html_next"=>"<a href='{href}'>Older Post ></a>"
						);

		return $format;
	}
}