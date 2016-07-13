<?php
namespace model\activity\training;
use model, db, session, pagination, url;

class SubType extends \Origami
{
	protected $table = 'training_SubType';
	protected $primary = 'trainingSubTypeID';

	// change to status = 0
	public function deactivate()
	{
		$this->trainingTypeStatus = 0;
		$this->save();
	}

	public function getSubTypeByTypeID($ID)
	{
		db::select("TST.trainingSubTypeID as type_id, TST.trainingSubTypeName as type_name");
		db::where("TST.trainingSubTypeParent", $ID);
		//print_r( db::get("lms_package_module AS LPM"));
		return db::get("training_SubType AS TST")->result();
	}	
}


?>