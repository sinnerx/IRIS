<?php
class Controller_Ajax_Exp
{
	public function rlCheckPending($month, $year)
	{
		$record = orm('expense/pr/reconcilation/reconcilation')
			->where('prReconcilationSubmitted', 1)
			->where('prReconcilationStatus !=', 1)
			->where('MONTH(prReconcilationSubmittedDate)', $month)
			->where('YEAR(prReconcilationSubmittedDate)', $year)
			->execute();

		return json_encode(array(
			'hasPending' => $record->count() > 0
			));
	}
}

?>