<?php
namespace model\expense\pr;

class Item extends \Origami
{
	protected $table = 'pr_item';
	protected $primary = 'prItemID';
	protected $join = array(
		'expense_item'=> array('local'=>'expenseItemID', 'foreign'=> 'expenseItemID'),
		'expense_category' => array('local' => 'expense_item.expenseCategoryID', 'foreign' => 'expenseCategoryID')
		);
}


?>