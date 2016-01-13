<?php
namespace model\expense\pr;

class Rejection extends \Origami
{
	protected $table = 'pr_rejection';
	protected $primary = 'prRejectionID';

	public function getUser()
	{
		return $this->getOne('user/user', 'prRejectionCreatedUser', 'userID');
	}
}



?>