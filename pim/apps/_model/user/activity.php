<?php
namespace model\user;

/*
user_activity:
userActivityID [int]
userID [int]
userActivityTypeCode [varchar(5)]			## 1XX = komen, 2XX = forum, 3XX = activity, 4XX = fail, 5XX = keahlian, 6XX = kemaskini
userActivityType [varchar]
userActivityAction [varchar]
userActivityParameter [varchar]
userActivityRefID [int]						## 1 reference table.
userActivityCreatedDate [datetime]
*/
## a class that log user activities.
class Activity
{
	public function create($userID,$typeAction,$parameter,$refID)
	{
		list($type,$action)	= explode(".",$typeAction);

		$parameterCheck	= $this->parameterCheck($type,$action,$parameter)

		$data	= Array(
				"userID"=>$userID,
				"userActivityType"=>"",
				"userActivityAction"=>"",
				"userActivityParameter"=>"",
				"userActivityRefID"=>"",
				"userActivityCreatedDate"=>now()
						);

		db::insert("user_activity",$data);
	}

	public function getText($type,$action,$parameter)
	{
		
	}

	public function commonParameter()
	{
		## stores common parameters and callback.
		$common	= Array(
				"user"=>function($userID)
				{
					return $userID;
				},
				"activity"=>function($activityID)
				{
					return $activityID;
				}
						);

		return $common;
	}

	public function type()
	{
		$typeR	= Array(
				"comment.add"=>"%user telah membuat komen di %module",
				"forum.newthread"=>"%user telah membuka topik baru yang bertajuk %threadTitle",
				"activity.join"=>"%user mendaftar untuk sertai aktiviti %activity",
				"member.register"=>"seorang pengguna baru %user telah berdaftar di laman ini"
						);
	}

	private function parameterCheck($type,$action,$parameter)
	{

	}


}