<?php
class Controller_Ajax_Exp
{
	public function rlCheckPending($clusterID, $type, $month, $year)
	{
		$record = orm('expense/pr/reconcilation/reconcilation')
			->where('prReconcilationSubmitted', 1)
			->where('prReconcilationStatus !=', 1)
			->where('prID IN (SELECT prID FROM pr WHERE prType = ? AND siteID IN (SELECT siteID FROM cluster_site WHERE clusterID = ?))', array($type, $clusterID))
			->where('MONTH(prReconcilationSubmittedDate)', $month)
			->where('YEAR(prReconcilationSubmittedDate)', $year)
			->execute();

		return json_encode(array(
			'hasPending' => $record->count() > 0
			));
	}
}

?>