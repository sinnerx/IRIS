<?php
namespace model\report;
use PHPExcel, PHPExcel_IOFactory;

class PHPExcelHelper
{
	private $filename;

	public function __construct($PHPExcel,$filename)
	{
		$this->PHPExcel	= $PHPExcel;
		$PHPExcel->setActiveSheetIndex(0);
		$this->filename	= $filename;

		$this->PHPExcel->getDefaultStyle()->getFont()->setName('Calibri');
		$this->PHPExcel->getDefaultStyle()->getFont()->setSize(11);
	}

	private function setOutputHeader()
	{
		$filename	= $this->filename;
		## taken from examples/01simple-download-xls.php
		// Redirect output to a client’s web browser (Excel2007)
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.$filename.'"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');

		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0
	}

	public function execute()
	{
		$this->setOutputHeader();

		$writer = PHPExcel_IOFactory::createWriter($this->PHPExcel, 'Excel5');
		$writer->save('php://output');
	}
}




?>