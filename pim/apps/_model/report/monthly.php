<?php

namespace model\report;

class Monthly extends \Origami
{
	protected $table = 'report_monthly';

	protected $primary = 'reportMonthlyID';

	public function articleCompletionIncrement()
	{
		$this->reportMonthlyArticleCompleted += 1;

		$this->save();
	}

	public function downloadIncrement()
	{
		$this->reportMonthlyDownloaded += 1;

		$this->save();
	}

	public function updateState($state)
	{
		$this->reportMonthlyStatusState = $state;

		if($state == 'completed')
			$this->reportMonthlyStatus = 1;

		$this->save();
	}

	public function save()
	{
		$this->reportMonthlyUpdatedDate = now();

		parent::save();
	}
}



