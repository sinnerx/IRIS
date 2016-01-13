<?php
namespace model\expense\pr\reconcilation;

class File extends \Origami
{
	protected $table = 'pr_reconcilation_file';
	protected $primary = 'prReconcilationFileID';

	/**
	 * ORM : get expense_category
	 */
	public function getCategory()
	{
		return $this->getOne('expense/expense_category', 'expenseCategoryID');
	}

	/**
	 * Get file name
	 */
	public function getFileName()
	{
		return $this->prReconcilationFileName.'.'.$this->prReconcilationFileExt;
	}

	/**
	 * ORM : get RL
	 */
	public function getReconcilation()
	{
		return $this->getOne('expense/pr/reconcilation/reconcilation', 'prReconcilationID');
	}

	/**
	 * ORM : Alias to getReconcilation
	 */
	public function getRl()
	{
		return $this->getReconcilation();
	}

	public function getFilePath()
	{
		$pr = $this->getReconcilation()->getPr();

		$path = \path::files('site_requisition/'.$pr->siteID.'/'.$this->prReconcilationFileID);

		return $path;
		$info = getimagesize($path);

		$this->template = false;

		$item = model::orm('expense/file')->where('purchaseRequisitionFileId', $id)->execute();
		$data['item'] = $item = $item->getFirst();		
		$siteID = $item->siteID;

		$path = path::files("site_requisition/".$siteID."/".$item->purchaseRequisitionFileId);
		$thumb = $path;

		$getInfo = getimagesize($thumb);
		header('Content-type: ' . $getInfo['mime'] . '; filename=\'$file_name\'');

		return file_get_contents($path);
	}
}


?>