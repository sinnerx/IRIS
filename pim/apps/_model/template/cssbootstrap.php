<?php
namespace model\template;

class Cssbootstrap
{
	public function paginationLink()
	{
		$format	= Array(
				"html_wrapper"=>"<ul class='pagination pagination-sm m-t-none m-b-none pull-right'>{content}</ul>",
				"html_number"=>"<li><a href='{href}'>{number}</a></li>",
				"html_number_active"=>"<li><a href='{href}' style='color:red;'>{number}</a></li>",
				"html_previous"=>"<li><a href='{href}'><i class='fa fa-chevron-left'></i></a></li>",
				"html_next"=>"<li><a href='{href}'><i class='fa fa-chevron-right'></i></a></li>",
				"limit"=>10,
				"number_limit"=>3);

		return $format;
	}
}


?>