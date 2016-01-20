<?php
namespace model\expense;

class Expense_Expenditure extends \Origami
{
	protected $table = 'expense_expenditure';
	protected $primary = 'expenseExpenditureID';

	const SET_BUDGETED = 'budgeted';
	const SET_ADDITION = 'addition';
	const SET_REPLACEMENT = 'replacement';

	public function isBudgeted()
	{
		return $this->expenseExpenditureSet == self::SET_BUDGETED;
	}

	public function isAddition()
	{
		return $this->expenseExpenditureSet == self::SET_ADDITION;
	}

	public function isReplacement()
	{
		return $this->expenseExpenditureSet == self::SET_REPLACEMENT;
	}
}


?>