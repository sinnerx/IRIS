<?php
namespace model\expense\pr\reconcilation;

class Rejection extends \Origami
{
	protected $table = 'pr_reconcilation_rejection';
	protected $primary = 'prReconcilationRejectionID';

	public function getUser()
	{
		return $this->getOne('user/user', 'prReconcilationRejectionCreatedUser', 'userID');
	}
}



?>