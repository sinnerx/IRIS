<?php


class Pagination
{
	private static $paginatedDB	= false;	## status of db_usage.

	private static $totalRow	= false;
	private static $limit		= null;
	public static $currentPage	= 1;
	public static $initiated	= false;
	public static $totalPage	= 0;
	public static $urlFormat	= false;	## example format. http://localhost/my_site/news/{10}

	private static $numStartNo	= false;
	private static $numEndNo	= false;

	private static $format	= Array();
	private static $default_format	= Array(
								"db_usage"				=>false, ## if true, will capture db usage, and autumate result limiting.
								"link_format"			=>Array("previous","num","next"),
								'number_limit'			=> 1,
								'html_wrapper'			=> "<div>{content}</div>",
								'html_default'			=> "<a class='{class}' href='{href}'>{text}</a>",
								'html_number'			=> "<a href='{href}'>{number}</a>",
								'html_first'			=> false,
								'html_last'				=> false,
								'html_previous'			=> false,
								'html_next'				=> false,
								'html_number_active'	=> "<a href='{href}'>{number}</a>",
								'class_first'			=> "pagination_start",
								'class_last'			=> "pagination_last",
								'class_previous'		=> "pagination_previous",
								'class_next'			=> "pagination_next",
								'class_active_num'		=> "pagination_active",
								'class_inactive_num'	=> "pagination_inactive",
								'limit'					=> 10
											);

	private function getFormat($var)
	{
		$default	= self::$default_format;

		return isset(self::$format[$var])?self::$format[$var]:$default[$var];
	}

	public function setFormat($name,$val = Null)
	{
		if(is_array($name))
		{
			foreach($name as $key=>$val)
			{
				self::setFormat($key,$val);
			}
			return;
		}

		if(!in_array($name,array_keys(self::$default_format)))
		{
			return false;
		}

		self::$format[$name]	= $val;
	}

	public function get($var)
	{
		return isset(self::$$var)?self::$$var:false;
	}

	### initiate pagination with config.
	## required property : totalRow, currentPage, limit urlFormat
	public function initiate($param)
	{
		### compulsary 1 : totalRow.
		if(!isset($param['totalRow']))
		{
			## no query run yet.
			if(!db::checkQuery() || !self::getFormat('db_usage'))
			{
				error::set("Pagination Init","Please input 'totalRow'.");
				return false;
			}

			## do db query.
			$param['totalRow']	= db::num_rows();

			## set useable flag.
			self::$paginatedDB	= true;
		}

		## compulsary 2 : urlFormat.
		if(!isset($param['urlFormat']))
		{
			error::set("Pagination Init","Please input 'urlFormat'.");
			return false;
		}

		## set initiation limit, from default format.
		self::$limit	= self::getFormat("limit");

		foreach($param as $key=>$val)
		{
			## key not as expected, continue.
			if(!in_array($key,Array("totalRow","limit","currentPage","urlFormat")))
			{
				continue;
			}

			## assign.
			self::$$key		= $val;
		}

		## initiated.
		self::$initiated	= true; 

		## calculate total page.
		self::$totalPage	= ceil(self::$totalRow/self::$limit);

		## calculate start and end limit.
		## prepare current no.
		if(self::$currentPage > self::$totalPage)
			self::$currentPage = self::$totalPage;

		## prepare pagination start and end limit.
		self::$numStartNo	= self::$currentPage-self::getFormat('number_limit');
		self::$numStartNo	= self::$numStartNo < 1?1:self::$numStartNo;

		self::$numEndNo		= self::$currentPage+self::getFormat('number_limit');
		self::$numEndNo		= self::$numEndNo > self::$totalPage?self::$totalPage:self::$numEndNo;

		## if db based pagination.
		if(self::$paginatedDB)
		{
			db::limit(self::$limit,self::$currentPage-1);
		}
	}

	### return rebuilt page num with url. Privatized. links should now be called through. link().
	private function num_links($format = Null)
	{
		$currentPage	= self::$currentPage;

		if(!self::$initiated)
		{
			return false;
		}

		## main loop for prepareing page number.
		for($i=self::$numStartNo;$i<=self::$numEndNo;$i++)
		{
			$pageNumber[]	= self::pageNumWrap($i);
		}

		return implode("",$pageNumber);
	}

	## function to wrap each number with format and href.
	private function pageNumWrap($no)
	{
		$urlFormat	= !self::$urlFormat?url::base("{page}"):self::$urlFormat;

		$url		= str_replace("{page}",$no, $urlFormat);
		
		## if no same with current page. will use html_number_active format instead .
		$htmlFormat = $no != self::$currentPage?self::getFormat("html_number"):self::getFormat("html_number_active");

		$result		 = str_replace(Array("{href}","{number}"),Array($url,$no),$htmlFormat);

		return $result;
	}

	public function link($type = null,$text = null)
	{
		$result	= self::prepare_link($type,$text);

		return self::wrapLink($result);
	}

	## type : first, last, previous, next
	private function prepare_link($type = Null,$text = Null)
	{
		$type	= !$type?self::getFormat('link_format'):$type;

		## concat all into one.
		if(is_array($type))
		{
			$result	= "";
			foreach($type as $typeName)
			{
				$text		= is_array($typeName)?$typeName[1]:Null;
				$typeName	= is_array($typeName)?$typeName[0]:$typeName;

				$result	.= self::prepare_link($typeName,$text);
			}
			return $result;
		}

		if(!self::$initiated || self::$totalPage <= 1)
		{
			return false;
		}

		### if first num already seen
		if(self::$numStartNo == 1 && ($type == "first" || $type == "previous"))
		{
			return false;
		}

		### if last num already seen.
		if(self::$numEndNo == self::$totalPage && ($type == "last" || $type == "next"))
		{
			return false;
		}

		### main switch case controller.
		switch($type)
		{
			case "first":
				$page_num	= 1;
				$defaultText	= "First";
				$class			= self::getFormat("class_first");
				$htmlFormat		= self::getFormat("html_first");
			break;
			case "last":
				$page_num	= self::$totalPage;
				$defaultText	= "Last";
				$class			= self::getFormat("class_last");
				$htmlFormat		= self::getFormat("html_last");
			break;
			case "previous":
				$page_num		= self::$currentPage-1;
				$defaultText	= "Previous";
				$class			= self::getFormat("class_previous");
				$htmlFormat		= self::getFormat("html_previous");

			break;
			case "next":
				$page_num		= self::$currentPage+1;
				$defaultText	= "Next";
				$class			= self::getFormat("class_next");
				$htmlFormat		= self::getFormat("html_next");
			break;
			case "num":
				return self::num_links();
			break;
			case "current_num":
				$page_num		= self::$currentPage;
				$defaultText	= $page_num;
			break;
		}

		$url		= str_replace("{page}",$page_num, self::$urlFormat);
		$text		= !$text?$defaultText:$text;
		$htmlFormat	= !$htmlFormat?self::getFormat("html_default"):$htmlFormat;
		$result		= str_replace(Array("{class}","{href}","{text}"), Array($class,$url,$text), $htmlFormat);

		return $result;
	}

	private function wrapLink($result)
	{
		$result		= str_replace("{content}",$result,self::getFormat("html_wrapper"));

		return $result;
	}

	### return current first record no.
	public function recordNo()
	{
		if(!self::$initiated)
		{
			return false;
		}
		$currentNo	= self::$currentPage > self::$totalPage?self::$totalPage:self::$currentPage;
		$currentNo	= $currentNo < 1?1:$currentNo;
		return self::$limit*($currentNo-1)+1;
	}

	## return array format, useful for ajax based pagination querying result.
	public function arrayResult()
	{
		if(!self::$initiated)
		{
			return false;
		}

		
	}
}

?>