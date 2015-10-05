<?php 

namespace model\expense;
use db, session;

class file extends \Origami
{ 
	protected $table = 'purchase_requisition_file';
	protected $primary = 'purchaseRequisitionFileId';
	
	public function getList()
	{
		
		db::from("purchase_requisition_category");

		return db::get()->result();
	}

	public function addFile($siteID,$prID,$data)
	{
		$data	= Array(
			"siteID"=>$siteID,
			"purchaseRequisitionId"=>$prID,
			"purchaseRequisitionCategoryId"=>$data['itemCategory'],
			"purchaseRequisitionFileName"=>$data['fileName'],
			"purchaseRequisitionFileType"=>$data['fileType'],
			"purchaseRequisitionFileSize"=>$data['fileSize'],
			"purchaseRequisitionFileExt"=>$data['fileExt'],
			"purchaseRequisitionFileAmount"=>$data['amount'],
			"purchaseRequisitionFileGst"=>$data['gst'],
			"purchaseRequisitionFileTotal"=>$data['total'],
			"purchaseRequisitionFileStatus"=>1,
			"purchaseRequisitionFileCreatedUser"=>session::get("userID"),
			"purchaseRequisitionFileCreatedDate"=>now(),
			"purchaseRequisitionFileUpdatedDate"=>now()			
			
						);

		db::insert("purchase_requisition_file",$data);

		return db::getLastID("purchase_requisition_file","purchaseRequisitionFileId");
	}

	public function editFile($fileID,$data)
	{
		
		$data	= Array(

				"purchaseRequisitionCategoryId"=>$data['itemCategory'],
				"purchaseRequisitionFileName"=>$data['fileName'],
				"purchaseRequisitionFileType"=>$data['fileType'],
				"purchaseRequisitionFileSize"=>$data['fileSize'],
				"purchaseRequisitionFileExt"=>$data['fileExt'],
				"purchaseRequisitionFileAmount"=>$data['amount'],
				"purchaseRequisitionFileStatus"=>1,
				"purchaseRequisitionFileUpdatedDate"=>now()	

						);

		db::where("purchaseRequisitionFileId",$fileID);
		db::update("purchase_requisition_file",$data);
	}


	public function getFileDetail($fileId)
	{
		
		db::from("purchase_requisition_file")->where("purchaseRequisitionId = '$fileId'");

		return db::get()->result();
	}

}	
?>