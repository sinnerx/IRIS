<?php
namespace model\activity\learning;
use model, db, session, pagination, url;

class PackageModule extends \Origami
{
	protected $table = 'lms_package_module';
	protected $primary = 'id';

	// change to status = 0
	public function deactivate()
	{
		$this->status = 0;
		$this->save();
	}

	public function getModuleByPackageID($packageID)
	{
		db::select("LPM.id, M.name AS name, M.description AS description, T.trainingTypeName AS type_name, T.trainingTypeID as type_id ");
		db::where("LPM.packageid", $packageID);
		db::join("lms_module AS M", "M.id = LPM.moduleid");
		db::join("training_type AS T", "T.trainingTypeID = M.typeid");
		db::join("lms_package AS P", "P.packageid = LPM.packageid");
		//print_r( db::get("lms_package_module AS LPM"));
		return db::get("lms_package_module AS LPM")->result();
	}

	public function getModuleByID($ID)
	{
		db::select("LPM.id, M.name AS name, M.description AS description, T.trainingTypeName AS type_name, T.trainingTypeID as type_id ");
		db::where("LPM.id", $ID);
		db::join("lms_module AS M", "M.id = LPM.moduleid");
		db::join("training_type AS T", "T.trainingTypeID = M.typeid");
		db::join("lms_package AS P", "P.packageid = LPM.packageid");
		//print_r( db::get("lms_package_module AS LPM"));
		return db::get("lms_package_module AS LPM")->result();
	}
}


?>