<?php
namespace model\template;
use url;
class Frontend
{
	public function paginationFormat()
	{
		$format	= Array(
				"link_format"=>Array("previous","next"),
				"number_limit"=>0,
				"html_previous"=>"<a href='{href}'>< Terkini</a>",
				"html_next"=>"<a href='{href}'>Terdahulu ></a>"
						);

		return $format;
	}

	public function pagination()
	{
		return Array(
			// "html_wrapper"=>"<div class='frontend-pagination'>{content}</div>",
			// "html_number_active"=>"<a href='{href}' class='active'>{number}</a>",
			// "html_previous"=>"<a href='{href}'><</a>",
			// "html_next"=>"<a href='{href}'>></a>"

			"html_wrapper"=>"<ul class='pagination pagination-sm m-t-none m-b-none'>{content}</ul>",
				"html_number"=>"<li><a href='{href}'>{number}</a></li>",
				"html_number_active"=>"<li><a href='{href}' style='color: #fff;border-color: #428bca;background-color:#428bca;'>{number}</a></li>",
				"html_previous"=>"<li><a href='{href}'><i class='fa fa-chevron-left'></i></a></li>",
				"html_next"=>"<li><a href='{href}'><i class='fa fa-chevron-right'></i></a></li>"
			);
	}

	public function buildBreadCrumbs($array)
	{
		$breadcrumb	= "<a href='".url::base("{site-slug}")."'>Utama</a>";
		$subheading	= "<span class='subheading subbread'> ";
		foreach($array as $row)
		{
			## if got href.
			if($row[1])
			{
				$subheading .= " > <a href='$row[1]'>".strtoupper($row[0])."</a>";
			}
			else
			{
				$subheading .= " > $row[0]";
			}
		}
		$subheading .= "</span>";

		return $breadcrumb.$subheading;
	}
}