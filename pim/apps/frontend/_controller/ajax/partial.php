<?php

## an ajax controller.
Class Controller_Partial
{
	public function calendarGetDateList($month,$year)
	{
		## get current date list, based on month and year.
		$siteID	= model::load("site")->getSiteIDBySlug();

		## total date of current month/year.
		$total	= date("t",strtotime("$year-$month-1"));

		$dateR	= Array();
		for($i=1;$i<=$total;$i++)
		{
			$dateR[$i]	= Array();
		}

		return response::json($dateR);
	}
}


?>