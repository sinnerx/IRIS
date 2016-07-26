<?php
namespace model\user;
use db, model;

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
	public function create($siteID,$userID,$typeAction,$parameter = null)
	{
		list($type,$action)	= explode(".",$typeAction);

		$data	= Array(
				"siteID"=>$siteID,
				"userID"=>$userID,
				"userActivityType"=>$type,
				"userActivityAction"=>$action,
				"userActivityCreatedDate"=>now()
						);

		## reqs params.
		$paramName	= $this->parameterName($typeAction);

		$paramNo	= 1;

		## loop the given paramter. to set the param value sequencely.
		//if(!($parameter && count($paramName) != 0))
		if($parameter && count($paramName) != 0)
		{
			foreach($parameter as $key => $val)
			{
				## immedietly return false.
				if(!in_array($key,$paramName))
					return false;

				$data['userActivityParameter'.$paramNo] = $val;

				$paramNo++;
			}
			//return false;
		}

		db::insert("user_activity",$data);
	}

	public function hasActivity($siteID,$userID,$typeAction,$param)
	{
		$paramLocation	= $this->parameterName($typeAction);

		$data	= Array();
		if($siteID)
			$data['siteID']	= $siteID;

		if($userID)
			$data['userID']	= $userID;

		foreach($param as $key=>$val)
		{
			## find location for the key.
			foreach($paramLocation as $no=>$col)
			{
				if($col == $key)
					$data['userActivityParameter'.($no+1)] = $val;
			}
		}

		$result = db::where($data)->get("user_activity")->result();

		return $result?true:false;		
	}

	private function _prepareText($txt,$params)
	{
		$typeR	= $this->type();

		$newText	= $txt;
		foreach($params as $key=>$value)
		{
			$newText	= str_replace('{'.$key.'}', $value, $newText);
		}

		return $newText;
	}

	public function type($typeAction = null)
	{
		$typeR	= Array(
				"comment.add"=>"{user} meninggalkan komen di {commentType} \"{commentTypeRefName}\"",
				"forum.newthread"=>"{user} telah membuka topik baru yang bertajuk {threadTitle}",
				"forum.newpost"=>"{user} meninggalkan komen di forum \"{threadTitle}\"",
				"activity.join"=>"{user} akan hadir ke \"{activity}\"",
				"member.register"=>"Seorang pengguna baru {user} telah berdaftar di laman ini",
				"member.edit"=>"{user} mengemaskini profil peribadinya"
						);

		return $typeAction?$typeR[$typeAction]:$typeR;
	}

	## prepare text, given the param value.
	private function prepareText($typeAction,$params,$additional)
	{
		$userProfile	= $additional['user_profile'];

		switch($typeAction)
		{
			## user, module.
			case "comment.add":
				$row	= model::load("comment/comment")->getComment2($params['commentID']);

				$refNameCol	= Array(
					"article"=>"articleName",
					"activity"=>"activityName",
					"site_album"=>"albumName",
					"video_album"=>"videoAlbumName",
					"file"=>"fileName",
								);

				$typeNameR	= Array(
					"article"=>"blog",
					"activity"=>"aktiviti",
					"site_album"=>"galeri photo",
					"video_album"=>"galeri video",
					"file"=>"Fail"
									);

				$refName	= $row[$refNameCol[$row['commentType']]];

				$data	= Array("user"=>$userProfile['userProfileFullName'],"commentType"=>$typeNameR[$row['commentType']],"commentTypeRefName"=>$refName);
			break;
			## user, threadTitle
			case "forum.newthread":
				$row	= db::where("forumThreadID",$params['forumThreadID'])->get("forum_thread")->row();
				$data	= Array("user"=>$userProfile['userProfileFullName'],"threadTitle"=>$row['forumThreadTitle']);
			break;
			case "forum.newpost":
				db::join("forum_thread","forum_thread.forumThreadID = forum_thread_post.forumThreadID");
				$row	= db::where("forumThreadPostID",$params['forumThreadPostID'])->get("forum_thread_post")->row();

				$data	= Array("user"=>$userProfile['userProfileFullName'],"threadTitle"=>$row['forumThreadTitle']);
			break;
			case "activity.join":
				db::join("activity","activity.activityID = activity_user.activityID");
				$row	= db::where("activityUserID",$params['activityUserID'])->get("activity_user")->row();

				$data	= Array("user"=>$userProfile['userProfileFullName'],"activity"=>$row['activityName']);
			break;
			case "member.register":
				$data	= Array("user"=>$userProfile['userProfileFullName']);
			break;
			case "member.edit":
				$data	= Array("user"=>$userProfile['userProfileFullName']);
			break;
		}

		$text	= $this->_prepareText($this->type($typeAction),$data);
		return $text;
	}

	## Binds parameter with index because the table column for parameter is nameless. (index start from 1.)
	## This locator is sensitive, and should never be changed after launched. create a new key, if you want another version.
	public function parameterName($typeAction = null,$param = null)
	{
		$paramLocation	= Array(
			"comment.add"=>Array("commentID"),
			"forum.newthread"=>Array("forumThreadID"),
			"forum.newpost"=>Array("forumThreadPostID"),
			"activity.join"=>Array("activityUserID"),
			"member.register"=>Array(),
			"member.edit"=>Array()
						);

		if($param)
		{
			$new	= Array();
			foreach($paramLocation[$typeAction] as $no=>$col)
			{
				$new[$col] = $param[$no];
			}

			return $new;
		}

		return $typeAction?$paramLocation[$typeAction]:$paramLocation;
	}

	## return text row.
	public function getActivities($siteID = null,$userID = null,$type = null,$action = null,$limit = null)
	{
		if($siteID)
			db::where("siteID",$siteID);

		if($type)
			db::where("userActivityType",$type);

		if($action)
			db::where("userActivityAction",$action);

		if($userID)
			db::where("userID",$userID);

		if($limit)
			db::limit($limit);

		db::order_by("userActivityID","DESC");
		db::get("user_activity");
		$res	= db::result("userActivityID");

		## group typeAction.
		if(!$res)
			return false;

		## get user data first to ease the query.
		foreach($res as $row)
			$userIDr[]	= $row['userID'];

		$res_profile	= db::select("userID","userProfileFullName")->where("userID",$userIDr)->get("user_profile")->result("userID");

		foreach($res as $userActivityID=>$row)
		{
			## params
			$p1	= $row['userActivityParameter1'];
			$p2	= $row['userActivityParameter2'];
			$p3 = $row['userActivityParameter3'];
			$p4 = $row['userActivityParameter4'];

			$typeAction	= $row['userActivityType'].".".$row['userActivityAction'];

			$params	= $this->parameterName($typeAction,Array($p1,$p2,$p3,$p4));

			$text	= $this->prepareText($typeAction,$params,Array("user_profile"=>$res_profile[$row['userID']]));

			$activities[] = Array("text"=>$text,"url"=>"","row"=>$row);
		}

		return $activities;
	}

	public function getFrontendLink($type,$userActivityID)
	{
		db::where("userActivityType",$type);
		db::where("userActivityID",$userActivityID);

		$row	= db::get("user_activity")->row();

		if(!$row)
			return false;

		$params	= $this->parameterName($row['userActivityType'].".".$row['userActivityAction'],Array(
			$row['userActivityParameter1'],
			$row['userActivityParameter2'],
			$row['userActivityParameter3'],
			$row['userActivityParameter4']
			));

		switch($row['userActivityType'].".".$row['userActivityAction'])
		{
			case "comment.add":
			$link	= model::load("comment/comment")->getCommentReferenceLink($params['commentID']);
			break;
			case "forum.newthread":
			$link	= model::load("forum/thread")->createThreadLink($params['forumThreadID']);
			break;
			case "forum.newpost":
			$link	= model::load("forum/thread")->createThreadLink(db::where("forumThreadPostID",$params['forumThreadPostID'])->get("forum_thread_post")->row("forumThreadID"));
			break;
			case "activity.join":
			$link	= model::load("activity/activity")->createActivityLink(db::where("activityUserID",$params['activityUserID'])->get("activity_user")->row("activityID"));
			break;
			case "member.register":
			$link	= model::load("site/member")->createProfileLink($row['userID']);
			break;
			case "member.edit":
			$link	= model::load("site/member")->createProfileLink($row['userID']);
			break;
		}

		return $link;
	}

	public function getModuleByUser($userID)
	{
		// db::select("P.packageid as packageID");
		// db::where("AU.userID", $userID);
		// db::innerjoin("training AS TR", "TR.activityID = AU.activityID");
		// db::innerjoin("training_lms AS L", "TR.trainingID = L.trainingid");
		// db::innerjoin("lms_module AS M", "M.id = L.packagemoduleID");
		// db::innerjoin("lms_package_module AS LPM", "LPM.moduleid = M.id");
		// db::innerjoin("lms_package AS P", "P.packageid = LPM.packageid");
		// db::group_by("packageID");
		// $resultgroupdb = db::get("activity_user AS AU")->result();		

		db::select("P.packageid as packageID");
		db::where("AU.userID", $userID);
		db::innerjoin("training AS TR", "TR.activityID = AU.activityID");
		db::innerjoin("training_lms AS L", "TR.trainingID = L.trainingid");
		db::innerjoin("lms_module AS M", "M.id = L.packagemoduleID");
		db::innerjoin("lms_package_module AS LPM", "LPM.moduleid = M.id");
		db::innerjoin("lms_package AS P", "P.packageid = LPM.packageid");
		

		db::group_by("packageID");
		$resultgroupdb = db::get("activity_user AS AU")->result();	
		//print_r($resultgroupdb);
		//die;

		//grouping list of package and payment of user for that package
		$arrayGroup = array();
		$arrayPayment = array();
		$c = 0;
		$in_string = "(";
		foreach ($resultgroupdb as $value) {
			# code...
			//print_r($value);
			//$in_string .= implode(", ", $value);
			//array_push($arrayPayment, $this->getPayment($userID, $value["packageID"]));
			$arrayPayment[$c]['packageID'] = $value['packageID'];
			$arrayPayment[$c]['billingItemID'] = $this->getPayment($userID, $value["packageID"])[0]["billingitemID"];
			array_push($arrayGroup, $value["packageID"]);
			$c++;
		}
		
		//print_r($arrayPayment);
		//die;
		$in_string .= implode(", ", $arrayGroup);
		$in_string .= ")";
		//print_r($in_string);

		db::select("P.name as packageName, P.packageid as packageID, M.name as moduleName, M.id as moduleID, R.status, R.datecreated");
		db::where("AU.userID", $userID);
		db::innerjoin("training AS TR", "TR.activityID = AU.activityID");
		db::innerjoin("training_lms AS L", "TR.trainingID = L.trainingid");
		db::innerjoin("lms_module AS M", "M.id = L.packagemoduleID");
		db::innerjoin("lms_package_module AS LPM", "LPM.moduleid = M.id");
		db::innerjoin("lms_package AS P", "P.packageid = LPM.packageid");
		db::join("lms_result as R", "(R.userid = '$userID' AND R.moduleid = M.id)");
		db::order_by("R.datecreated", "ASC");				
		$resultdb = db::get("activity_user AS AU")->result();
		//print_r($resultdb);
		//die;		


		// db::select("P.name as packageName, P.packageid as packageID, M.name as moduleName, M.id as moduleID");
		// db::where("AU.userID", $userID);
		// db::innerjoin("training AS TR", "TR.activityID = AU.activityID");
		// db::innerjoin("training_lms AS L", "TR.trainingID = L.trainingid");
		// db::innerjoin("lms_package_module AS LPM", "LPM.id = L.packageModuleID");
		// db::innerjoin("lms_module AS M", "M.id = LPM.moduleid");
		// db::innerjoin("lms_package AS P", "P.packageid = LPM.packageid");		
		// $resultdb = db::get("activity_user AS AU")->result();
		//print_r($resultdb);
		//die;


		//print_r($userID);
		//print_r($resultdb);
		//$result = array();
		$resultdb["packageInclude"] = $in_string;
		$resultdb["paymentInclude"] = $arrayPayment;
		//array_push($resultdb, $in_string);

		if (empty($resultgroupdb))
			$result = null;
		else
			$result = $this->getModuleInPackage($resultdb);
		
		//print_r($result);	
		//return $resultdb;
		//die;
		return $result;

	}

	public function getPayment($userID, $packageID)
	{
		db::select("BTI.billingitemID");
		db::innerjoin("billing_transaction_user AS BTU", "BTU.billingTransactionUser = AU.userID");
		db::innerjoin("billing_transaction AS BT", "BT.billingTransactionID = BTU.billingTransactionID");
		db::innerjoin("billing_transaction_item AS BTI", "BTI.billingTransactionID = BT.billingTransactionID");
		db::innerjoin("billing_item AS BI", "BI.billingitemID = BTI.billingitemID");
		db::innerjoin("lms_package AS LP", "LP.billing_item_id = BI.billingitemID");
		db::where("AU.userID", $userID);
		db::where("LP.packageid", $packageID);
		db::group_by("billingitemID");

		$result = db::get("activity_user AS AU")->result();
		//print_r($result);
		//die;
		return $result;
	}

	public function getModuleInPackage($results)
	{
			//print_r($results);
			//die;
		//$x = 0;
			db::select("P.packageid, P.name as PackageName");
			db::where("P.packageid IN ", $results["packageInclude"]);
			$resultPackage = db::get("lms_package AS P")->result();	
			unset($results["packageInclude"]);	
			$x=0;
			foreach ($resultPackage as $key) {

					db::select("M.id, M.name");
					//db::where("M.packageid", $key["packageid"]);
					db::join("lms_package_module AS LPM", "LPM.moduleid = M.id");
					db::where("LPM.packageid", $key["packageid"]);					
					$countModule = db::get("lms_module AS M")->num_rows();

					//print_r($key["packageid"]);
					db::select("M.id, M.name");
					//db::where("M.packageid", $key["packageid"]);
					db::join("lms_package_module AS LPM", "LPM.moduleid = M.id");
					db::where("LPM.packageid", $key["packageid"]);
					$resultModule = db::get("lms_module AS M")->result();

					//$resultPackage["modules"] = array();
						//print_r($resultModule);
					 	//print_r($results);
							$y = 0;
							$taken = 0;
					 		foreach ($resultModule as $keyModule) {
					 			# code...
					 			//print_r($keyModule);
					 			//if(in_array())
					 			
					 			foreach ($results as $keyModuleSelected) {
						 			# code...
						 			//print_r($keyModuleSelected["status"].$y." ");
						 			//print_r($keyModule["id"]);
						 			//print_r($resultPackage[$x]);
						 			if(($keyModuleSelected["moduleID"] == $keyModule["id"]) && ($keyModuleSelected["packageID"] == $key["packageid"])){
						 				//print_r( $key["packageid"] ." ". $keyModuleSelected["packageID"] . " ". $keyModuleSelected["moduleName"] . "selected ");
						 				//array_push($resultModule, ["selected"]);
						 				$resultModule[$y]["selected"] = 1;
						 				$resultModule[$y]["status"] = $keyModuleSelected["status"] ;
						 				//$keyModule["selected"] = 1;
						 				//print_r($resultModule[$y]);
						 				//print_r($keyModule);
						 				//[$y]["selected"] = 1;
						 				$taken++;
									}//if
									
									
									//print_r($resultModule);
					 			}//foreach
					 			$y++;
					 		}
					 		//print_r($resultModule);
					 		//die;
					 		//print_r($taken);
					 		//print_r($countModule);
					 		if ($taken == $countModule)
					 			$resultPackage[$x]["complete"] = 1;
					 		else
					 			$resultPackage[$x]["complete"] = 0;

					 		foreach ($results["paymentInclude"] as $keypayment) {
					 			# code...
					 			if ($key["packageid"] == $keypayment["packageID"])
					 				if($keypayment["billingItemID"])
					 					$resultPackage[$x]["completepayment"] = 1;
					 				else
					 					$resultPackage[$x]["completepayment"] = 0;

					 		}//foreach
					 		//die;
					$resultPackage[$x]["modules"] =  $resultModule;	 	
					$x++;
				}//foreach	
					//print_r($resultPackage);
				//var_dump($results);
					return $resultPackage;
	}

//830512105141    172

}