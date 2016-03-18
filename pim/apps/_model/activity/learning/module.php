<?php
namespace model\activity\learning;
use model, db, session, pagination, url;

class Module extends \Origami
{
	protected $table = 'lms_module';
	protected $primary = 'id';

	// change to status = 0
	public function deactivate()
	{
		$this->status = 0;
		$this->save();
	}

	public function getModuleByPackageID($packageID)
	{
		db::where("packageid",$packageID);

		return db::get("lms_module")->result();
	}

	public function getModuleByID($ID)
	{
		db::select("lms_module.name AS mod_name, lms_module.description, T.trainingTypeName AS type_name, T.trainingTypeID as type_id ");
		db::where("lms_module.id", $ID);
		db::join("training_type AS T", "T.trainingTypeID = lms_module.typeid");
		return db::get("lms_module")->result();
	}
}


?>