<?php
namespace model\activity\training;

class Type extends \Origami
{
	protected $table = 'training_type';
	protected $primary = 'trainingTypeID';

	// change to status = 0
	public function deactivate()
	{
		$this->trainingTypeStatus = 0;
		$this->save();
	}
}


?>