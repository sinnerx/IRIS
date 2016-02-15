<?php
namespace model\billing;

class item extends \Origami
{
	public $table = 'billing_item';
	public $primary = 'billingItemID';

	protected $codes = array(
		'Membership', 'PC', 
		'Print Color', 'Black And White',
		'Scan', 'Laminate', 'Utilities',
		'Bank In'
		);

	public function getItemCodes()
	{
		return $this->codes;
	}
}


?>