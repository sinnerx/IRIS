<?php
namespace model\expense\pr\reconcilation;

class Item extends \Origami
{
	protected $table = 'pr_reconcilation_item';
	protected $primary = 'prReconcilationItemID';

	public function isReconciled()
	{
		return $this->isNonPR() || $this->prReconcilationItemStatus == 1;
	}

	public function isNonPR()
	{
		return $this->prItemID == 0 || !$this->prItemID;
	}

	public function isPrBased()
	{
		return !$this->isNonPR();
	}
}



?>