<?php
namespace model\site;
use db, session, model, apps;

/**
 * This is supposedly object for cluster/cluster (but since it was placed here, i'll just use this class here.)
 */

class Region extends \Origami
{
	protected $table = 'region';
	protected $primary = 'regionID';

	/**
	 * Get sites.
	 * @return \Origamis of model\site\site
	 */
	public function getClusterByRegionID()
	{
		return model::orm('site/cluster')->where('clusterID IN (SELECT clusterID FROM cluster WHERE regionID = ?)', array($this->regionID))->execute();
	}

	// public function getClusterByID($clusterID){
	// 	// db::from("cluster");
	// 	db::where("clusterID", $clusterID);
	// 	return db::get("cluster")->row();
	// }
}



?>