<?php
namespace model\expense\pr\reconcilation;

class Category extends \Origami
{
	protected $table = 'pr_reconcilation_category';
	protected $primary = 'prReconcilationCategoryID';
	protected $join = array(
		'expense_category' => array(
			'local' => 'expenseCategoryID',
			'foreign' => 'expenseCategoryID'
			)
		);

	public function getItems()
	{
		return $this->getMany('expense/pr/reconcilation/item', 'prReconcilationCategoryID');
	}

	public function getReconciledItems()
	{
		return $this->withQuery(function($query)
		{
			$query->where('prReconcilationItemStatus', 1);

		})->getItems();
	}

	public function getFile()
	{
		return $this->getOne('expense/pr/reconcilation/file', 'prReconcilationCategoryID');
	}

	public function getFiles()
	{
		return $this->getMany('expense/pr/reconcilation/file', 'prReconcilationCategoryID');
	}

	public function isUploaded()
	{
		return $this->getFile() ? true : false;
	}

	/**
	 * @ORM : get reconciled total for this category
	 */
	public function getTotal()
	{
		$total = 0;

		foreach($this->getReconciledItems() as $item)
			$total += $item->prReconcilationItemTotal;

		return $total;
	}

	public function reconcileAllItems()
	{
		foreach($this->getItems() as $item)
		{
			if(!$item->isPrBased())
				continue;

			$item->prReconcilationItemStatus = 1;
			$item->save();
		}
	}

	/**
	 * If category has receipt uploaded 
	 * and at least one item is reconciled
	 * @return bool
	 */
	public function isReconciled()
	{
		if(!$this->isUploaded())
			return false;

		$total = 0;

		foreach($this->getItems() as $item)
			if($item->isReconciled())
				$total++;

		return $total > 0;
	}
}

?>