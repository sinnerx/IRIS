<?php
namespace model\report;
use PHPWord, IOFactory;

class PHPWordHelper
{
	private $filename;

	public function __construct($PHPWord,$filename)
	{
		$this->PHPWord	= $PHPWord;
		//$PHPWord->setActiveSheetIndex(0);
		$this->filename	= $filename;

	}

	private function setOutputHeader()
	{
		$filename	= $this->filename;

		header('Content-Type: application/vnd.ms-word');
		header('Content-Disposition: attachment;filename="'.$filename.'"');

	}

	public function execute()
	{
		$this->setOutputHeader();



		$writer = \PhpOffice\PhpWord\IOFactory::createWriter($this->PHPWord, 'Word2007');
		$writer->save('php://output');
	}
}




?>