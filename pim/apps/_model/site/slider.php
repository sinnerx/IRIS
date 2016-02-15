<?php
namespace model\site;
use db, session;
class Slider
{
	public function getSlider($siteID,$all = true)
	{
		db::from("site_slider");

		## if only status == 1
		if(!$all)
		{
			db::where("siteSliderStatus",1);
		}

		## get general type slider.
		if($siteID === 0)
		{
			db::where("siteSliderType",2);
		}
		else
		{
			db::where("siteSliderType = '2' AND siteSliderStatus = '1' OR siteID = '$siteID'");/*
			db::where(Array("siteSliderType"=>2,"siteSliderStatus"=>1));
			db::or_where("siteID",$siteID);*/
		}

		db::order_by("siteSliderType DESC, siteSliderID DESC");

		return db::get()->result();
	}

	public function addSlider($siteID,$data)
	{
		## if siteID = 0, add to general slider. (type 2)
		$type	= $siteID === 0?2:1;

		$data	= Array(
				"siteID"=>$siteID,
				"siteSliderType"=>$type,
				"siteSliderTarget"=>1,
				"siteSliderStatus"=>1,
				"siteSliderName"=>$data['siteSliderName'],
				"siteSliderImage"=>$data['siteSliderImage'],
				"siteSliderLink"=>$data['siteSliderLink'],
				"siteSliderCreatedDate"=>now(),
				"siteSliderCreatedUser"=>session::get("userID")
						);

		db::insert("site_slider",$data);
	}

	public function getSliderDetail($sliderID)
	{
		db::from("site_slider");
		db::where("siteSliderID",$sliderID);

		return db::get()->row();
	}

	public function updateSlider($siteSliderID,$data)
	{
		db::where("siteSliderID",$siteSliderID);
		db::update("site_slider",$data);
	}

	public function removeSlider($id)
	{
		db::where("siteSliderID",$id);
		db::update("site_slider",Array("siteSliderStatus"=>0));
		#db::delete("site_slider",Array("siteSliderID"=>$id));
	}

	//update slider status..
	public function toggleSlider($id)
	{
		$current	= db::select("siteSliderStatus")->where("siteSliderID",$id)->get("site_slider")->row("siteSliderStatus");
		$status		= $current == 1?0:1;

		db::where("siteSliderID",$id);
		db::update("site_slider",Array("siteSliderStatus"=>$status));
	}
}