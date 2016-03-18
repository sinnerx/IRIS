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

class Training
{
	public function type($no = null)
	{
		## temporarily empty.
		$arr	= Array(
			0=>"Others",
			1=>"Digital Literacy",
			2=>"Productivity Tools",
			3=>"Multimedia Skills",
			4=>"Soft Skills",
			5=>"Online Marketing Skills",
			6=>"Entrepreneurship Skills",
			7=>"Ethical & Smart Living"
			);

		## type.
		$res_type = db::select("trainingTypeID,trainingTypeName")->where("trainingTypeStatus",1)->get("training_type")->result();

		$arr	= Array();
		if($res_type)
			foreach($res_type as $row) $arr[$row['trainingTypeID']] = $row['trainingTypeName'];

		return $no != null?$arr[$no]:$arr;
	}

	public function getTrainingType($where = null)
	{
		if($where)
			foreach($where as $key=>$v)
				db::where($key,$v);

		return db::get("training_type")->result();
	}

	public function addType($name,$desc)
	{
		db::insert("training_type",Array(
			"trainingTypeName"=>$name,
			"trainingTypeDescription"=>$desc,
			"trainingTypeStatus"=>1,
			"trainingTypeCreatedDate"=>now(),
			"trainingTypeCreatedUser"=>session::get("userID")
			));
	}

	public function updateType($trainingTypeID,$name,$desc)
	{
		db::where("trainingTypeID",$trainingTypeID);
		db::update("training_type",Array(
			"trainingTypeName"=>$name,
			"trainingTypeDescription"=>$desc,
			"trainingTypeUpdatedUser"=>session::get("userID"),
			"trainingTypeUpdatedDate"=>now()
			));
	}

	public function getTrainingByType($id,$select = "trainingType")
	{
		return db::select($select)->where("trainingType",$id)->get("training")->result("trainingType",true);
	}

	public function getTrainingModule($id)
	{
		//echo $id;
		db::select("P.name as packageName, P.packageid as packageID, M.name as moduleName, M.id as moduleID");
		//
		db::where("TR.trainingID", $id);
		db::join("training_lms AS L", "TR.trainingID = L.trainingid");
		db::join("lms_package_module AS LPM", "LPM.id = L.packageModuleID");
		db::join("lms_module AS M", "LPM.moduleID = M.id");
		db::join("lms_package AS P", "LPM.packageid = P.packageid");
		//db::from("training as TR");
		return db::get("training AS TR")->result();
	}

	public function getLearningSelection($no = null)
	{
				## temporarily empty.
		$arr	= Array(
			1=>"No",
			2=>"Yes"
			);

		## type.
		//$res_type = db::select("trainingTypeID,trainingTypeName")->where("trainingTypeStatus",1)->get("training_type")->result();

		return $no != null?$arr[$no]:$arr;
	}
}


?>