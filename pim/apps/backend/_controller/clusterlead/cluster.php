<?php
class Controller_Cluster
{
	public function __construct()
	{
		## get clusterID
		$cluster	= model::load("site/cluster")->getClusterByUser(session::get("userID"));
		$this->clusterID	= $cluster['clusterID'];
	}

	public function overview()
	{
		$data['res_sites']	= model::load("site/site")->getSitesByClusterLead(session::get("userID"))->result("stateID",true);
		$data['stateR']		= model::load("helper")->state();
		$data['requestR']	= model::load("site/request")->getRequestByCluster($this->clusterID)->result("siteID",true);
		view::render("clusterlead/cluster/overview",$data);
	}

	public function test()
	{
		$mailingServices	= model::load("mailing/services");
	}
}


?>