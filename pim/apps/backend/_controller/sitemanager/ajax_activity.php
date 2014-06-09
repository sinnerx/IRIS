<?php
class Controller_Ajax_Activity
{
	public function incoming(){
		$type = 'incoming';
		$year = request::get('year',date("Y"));
		$month = request::get('month',date("n"));
		$siteID = model::load('access/auth')->getAuthData("site","siteID");

		//var_dump($type." ".$year." ".$month." ".$siteID);die;

		$data = model::load("activity/activity")->getUnlinkedActivity($siteID, $year, $month, $type);

		if($month == 12){
			$data['nextyear'] = $year+1;
			$data['nextmonth'] = 1;
		}else if($month == 1){
			$data['previousyear'] = $year-1;
			$data['previousmonth'] = 12;
		}else{
			$data['nextyear'] = $year;
			$data['nextmonth'] = $month+1;
			$data['previousyear'] = $year;
			$data['previousmonth'] = $month-1;
		}
		$data['type'] = $type;
		$data['year'] = $year;
		$data['month'] = $month;
		$data['ref'] = 1;
		//echo '<pre>';print_r($data);die;
		view::render("sitemanager/activity/ajax/incoming", $data);
	}
	public function previous($ref=0){
		$type = 'previous';
		$ref = $ref == 0?request::get('ref'):$ref;
		$year = request::get('year',date("Y"));
		$month = request::get('month',date("n"));
		$siteID = model::load('access/auth')->getAuthData("site","siteID");

		//var_dump($type." ".$year." ".$month." ".$siteID);die;

		$data = model::load("activity/activity")->getUnlinkedActivity($siteID, $year, $month, $type);

		if($month == 12){
			$data['nextyear'] = $year+1;
			$data['nextmonth'] = 1;
		}else if($month == 1){
			$data['previousyear'] = $year-1;
			$data['previousmonth'] = 12;
		}else{
			$data['nextyear'] = $year;
			$data['nextmonth'] = $month+1;
			$data['previousyear'] = $year;
			$data['previousmonth'] = $month-1;
		}
		$data['type'] = $type;
		$data['year'] = $year;
		$data['month'] = $month;
		$data['ref'] = $ref;
		//echo '<pre>';print_r($data);die;
		view::render("sitemanager/activity/ajax/previous", $data);
	}
}
?>