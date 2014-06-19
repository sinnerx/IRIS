<?php
/***
TITLE	: RG PHP CALENDAR
AUTHOR 	: EIMIHAR
DATE	: 2013-05-09
EDITED FOR SPORTSNETWORK SOCIAL NETWORK.


***/
class Calendar
{
	var $result		= "";
	var $month;
	var $year;

	var $totalRow;
	var $totalDays;

	var $firstTime;

	var $dayR;
	var $tableClass	= "calendar-ui";
	var $tableID 	= "table-calendar";

	//store an array of record in the calendar.
	var $dateR;

	function __construct()
	{
		$this->dayR	= Array("Sun","Mon","Tue","Wed","Thu","Fri","Sat");
		#$this->dayR	= Array("Mon","Tue","Wed","Thu","Fri","Sat","Sun");
	}

	//need month and year only for config, at first.
	function setConfig($data)
	{
		foreach($data as $key => $value)
		{
			$this->$key	= $value;
		}
	}

	function createHeader()
	{
		//+++ prepare the calendar based on selected month.+++
		$year	= $this->year;
		$month	= $this->month;

		$monthR	= Array(
				1=>"January",
				2=>"February",
				3=>"Mac",
				4=>"April",
				5=>"May",
				6=>"June",
				7=>"July",
				8=>"August",
				9=>"September",
				10=>"October",
				11=>"November",
				12=>"December"
						);

		//get first time of the month.
		$firstDate	= "$year-$month-1";
		$interval	= date("w",strtotime($firstDate));
		$this->firstTime	= strtotime("- $interval days",strtotime($firstDate));

		$this->result	.= "<table id='".$this->tableID."' class='".$this->tableClass."'>";

		//prepare $totalRow for the loop.
		$totalDays	= date("t",strtotime($firstDate));
		$currTime	= $this->firstTime;
		$totalRow	= 0;
		while($currTime <= strtotime("$year-$month-$totalDays"))
		{
			$currTime	= strtotime("+ 7 day",$currTime);
			$totalRow++;
		}

		$this->totalRow	= $totalRow;

		//Prepare the header.
		$this->result	.= "<tr>";
		foreach($this->dayR as $day)
		{
			$this->result	.= "<th>$day</th>";
		}
		$this->result	.= "</tr>";
	}

	function createMainLoop()
	{
		$dateR	= $this->dateR;

		$month	= $this->month;
		$year	= $this->year;

		$week	= 1;
		//Prepare the main loop.
		$currTime	= $this->firstTime;

		//add 1 day : so that it match the weeksday.
		#$currTime	= strtotime("+ 1 day",$currTime);

		for($week = 1;$week <= $this->totalRow;$week++)
		{
			//week column.
			#$this->result	.= "<tr><td>$week</td>";

			foreach($this->dayR as $d)
			{
				//the date. 1 - 28/31.
				$currDate	= date("j",$currTime);

				$currMonth	= date("m",$currTime);
				$currYear	= date("Y",$currTime);

				//in-month day.
				if($currMonth == $month)
				{
					$item	= isset($dateR[$currDate]['text'])?$dateR[$currDate]['text']:"";
					$attr	= isset($dateR[$currDate]['attr'])?$dateR[$currDate]['attr']:"";

					$this->result .= "<td $attr >";
					$this->result .= "$item";
					$this->result .= "</td>";//large-all and large-event
				}
				//non-month day.
				else
				{
					$this->result .= "<td class='no-day'><span></span></td>";
				}
				//currTime increment.
				$currTime	= strtotime("+ 1 day",$currTime);
			}

			$this->result .= "</tr>";
		}
		$this->result .= "</table>";
		$this->result .= "";
	}

	function showCalendar()
	{
		$this->createHeader();
		$this->createMainLoop();
		return $this->result;
	}
}
/*** USAGE DOCUMENTATION

//Configuration
$calendar->setConfig($config);
+ 3 Type of config :
	- month	(calendar month)
	- year	(calendar year)
	- dateR (multi-dimensional array that store what the calendar want you to put.)
		+ to prepare, first :
		- loop from 1 to 31.
		- store the number into $dateR as key.
		- after that, there's two type of key for every date:
			- text (what you want it to be seen)
			- attribute (what attribute u want to apply at the current element.)
		- example usage :
		for($i=1;$i<=31;$i++)
		{
			$dateR[$i]['text']	= "";
			//$dateR[$i]['attribute']	= "";
		}
+ then, store into the config array, for example :
	$config['month']	= 5;
	$config['year']		= 2013;
	$config['dateR']	= $dateR;
	$calendar->setConfig($config);

//Show Calendar
$calendar->showCalendar();
+ echo this one to anywhere you want it to be placed.

***/
?>