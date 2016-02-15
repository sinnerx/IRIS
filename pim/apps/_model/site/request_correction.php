<?php
## extended by site/request model.
namespace model\site;
use db, session, pagination, model;

### Correction model ###
class Request_Correction
{
	public function deactivateCorrection($requestID)
	{
		## get last data.
		$lastData	= db::select("siteRequestData")->where("siteRequestID",$requestID)->get("site_request")->row("siteRequestData");

		## site_request_correction
		db::where("siteRequestID",$requestID);
		db::where("siteRequestCorrectionStatus",1);
		db::update("site_request_correction",Array(
								"siteRequestCorrectionStatus"=>2,
								"siteRequestCorrectionLastData"=>$lastData,
								"siteRequestCorrectionUpdatedDate"=>now(),
								"siteRequestCorrectionUpdatedUser"=>session::get("userID")
								));// updated.

		## set flag.
		db::where("siteRequestID",$requestID)->update("site_request",Array("siteRequestCorrectionFlag"=>0));
	}

	public function getTotalCorrection($siteRequestID)
	{
		db::select("siteRequestCorrectionID,siteRequestID");
		db::where("siteRequestID",$siteRequestID);
		db::where("siteRequestCorrectionStatus",2); ## corrected.

		if(is_array($siteRequestID))
		{
			## group by siteRequestID
			$res	= db::get("site_request_correction")->result("siteRequestID",true);

			return $res;
		}
		else
		{
			return db::get("site_request_correction")->num_rows();
		}
	}

	public function getCorrection($siteRequestID)
	{
		db::where("siteRequestID",$siteRequestID);
		db::where("siteRequestCorrectionStatus",1);
		db::get("site_request_correction");

		return is_array($siteRequestID)?db::result():db::row();
	}

	public function createCorrection($siteRequestID,$txt)
	{
		## no correction mode yet.
		if($this->getCorrection($siteRequestID))
		{
			return;
		}

		## set correction flag to 1.
		db::where("siteRequestID",$siteRequestID)->update("site_request",Array(
												"siteRequestCorrectionFlag"=>1,
												"siteRequestUpdatedDate"=>now(),
												"siteRequestUpdatedUser"=>session::get("userID")
												));

		## create correction message.
		db::insert("site_request_correction",Array(
										"siteRequestCorrectionMessage"=>$txt,
										"siteRequestID"=>$siteRequestID,
										"siteRequestCorrectionStatus"=>1,
										"siteRequestCorrectionCreatedDate"=>now(),
										"siteRequestCorrectionCreatedUser"=>session::get("userID")
													));
	}
}
?>