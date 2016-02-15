<?php
class Controller_Ajax_Cluster
{
	var $template	= false;
	public function siteList($clusterID)
	{
		$data['clusterName']	= db::where("clusterID",$clusterID)->get("cluster")->row("clusterName");
		$data['clusterID']		= $clusterID;
		$data['res_site']		= model::load("site/site")->getAllSiteWithCluster($clusterID);
		$data['stateR']			= model::load("helper")->state();
		view::render("root/cluster/ajax/siteList",$data);
	}

	public function siteAdd($clusterID)
	{
		$data	= Array();
		$data['res_site']			= model::load("site/site")->getAllSiteWithCluster();
		$data['stateR']				= model::load("helper")->state();
		$data['currentClusterID']	= $clusterID;

		view::render("root/cluster/ajax/siteAdd",$data);
	}

	public function siteAddExecute($clusterID)
	{
		$siteList	= input::get("siteList");

		## add site to cluster.
		model::load("site/cluster")->addSite($clusterID,$siteList);

		return true;
	}

	public function removeSite()
	{
		$clusterID	= input::get("cluster");
		$siteID		= input::get("site");

		## execute removal.
		if(model::load("site/cluster")->removeSite($clusterID,$siteID))
		{
			return true;
		}
	}
}



?>