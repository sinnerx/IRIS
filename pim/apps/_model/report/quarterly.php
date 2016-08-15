<?php

namespace model\report;

class Quarterly extends \Origami
{
	protected $table = 'report_quarterly';

	protected $primary = 'reportQuarterlyID';

	public function siteCompletionIncrement()
	{
		$this->reportQuarterlySiteCompleted += 1;

		$this->save();
	}

	public function downloadIncrement()
	{
		$this->reportQuarterlyDownloaded += 1;

		$this->save();
	}

	public function updateState($state)
	{
		$this->reportQuarterlyStatusState = $state;

		if($state == 'completed')
			$this->reportQuarterlyStatus = 1;

		$this->save();
	}

	public function save()
	{
		$this->reportQuarterlyUpdatedDate = now();

		parent::save();
	}
}



