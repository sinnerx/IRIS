<?php
namespace model\activity;
use db, session;
/*
training_type:
	trainingTypeID [int]
	trainingTypeName [varchar]
	trainingTypeStatus [int]	## 0 inactive, 1 = active.
	trainingTypeCreatedDate [datetime]
	trainingTypeCreatedUser [int]
	trainingTypeUpdatedDate [datetime]
	trainingTypeUpdatedUser [int]
*/

class Learning
{

	public function module($no = null, $packageid = null)
	{
		## temporarily empty.
		$arr	= Array(
			0=>"Others"
			);

		## type.
		$res_type = db::select("id,name")->where("status",1)->get("lms_module")->result();

		$arr	= Array();
		if($res_type)
			foreach($res_type as $row) $arr[$row['id']] = $row['name'];

		return $no != null?$arr[$no]:$arr;
	}

	public function package($no = null)
	{
		## temporarily empty.
		$arr	= Array(
			0=>"Others"
			);

		## type.
		$res_type = db::select("packageid,name")->where("status",1)->get("lms_package")->result();

		$arr	= Array();
		if($res_type)
			foreach($res_type as $row) $arr[$row['packageid']] = $row['name'];

		return $no != null?$arr[$no]:$arr;
	}

	public function getLearningPackage($where = null)
	{
		if($where)
			foreach($where as $key=>$v)
				db::where($key,$v);

		return db::get("lms_package")->result();
	}

	// public function addType($name,$desc)
	// {
	// 	db::insert("training_type",Array(
	// 		"trainingTypeName"=>$name,
	// 		"trainingTypeDescription"=>$desc,
	// 		"trainingTypeStatus"=>1,
	// 		"trainingTypeCreatedDate"=>now(),
	// 		"trainingTypeCreatedUser"=>session::get("userID")
	// 		));
	// }

	// public function updateType($trainingTypeID,$name,$desc)
	// {
	// 	db::where("trainingTypeID",$trainingTypeID);
	// 	db::update("training_type",Array(
	// 		"trainingTypeName"=>$name,
	// 		"trainingTypeDescription"=>$desc,
	// 		"trainingTypeUpdatedUser"=>session::get("userID"),
	// 		"trainingTypeUpdatedDate"=>now()
	// 		));
	// }

	// public function getTrainingByType($id,$select = "trainingType")
	// {
	// 	return db::select($select)->where("trainingType",$id)->get("training")->result("trainingType",true);
	// }
}


?>