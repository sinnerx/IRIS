<?php
namespace model\activity\learning;
use model, db, session, pagination, url;

class LearningPackage extends \Origami
{
	protected $table = 'lms_package';
	protected $primary = 'packageid';

	// change to status = 0
	public function deactivate()
	{
		$this->status = 0;
		$this->save();
	}

	public function getPackageID($ID)
	{
		db::where("packageid",$ID);

		return db::get("lms_package")->result();
	}
}


?>