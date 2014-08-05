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