<?php
namespace model\expense\pr;

class Cash_Advance extends \Origami
{
	protected $table = 'pr_cash_advance';
	protected $primary = 'prCashAdvanceID';

	public function getPr()
	{
		return $this->getOne('expense/pr/pr', 'prID');
	}

	public function getItems()
	{
		return $this->getMany('expense/pr/cash_advance_item', 'prCashAdvanceID');
	}
}


?>