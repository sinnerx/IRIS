<?php
namespace model\site;
use db, session, model;

/**
 * This is supposedly object for cluster/cluster (but since it was placed here, i'll just use this class here.)
 */

class Cluster extends \Origami
{
	protected $table = 'cluster';
	protected $primary = 'clusterID';

	/**
	 * Get sites.
	 * @return \Origamis of model\site\site
	 */
	public function getSites()
	{
		return model::orm('site/site')->where('siteID IN (SELECT siteID FROM cluster_site WHERE clusterID = ?)', array($this->clusterID))->execute();
	}

	/**
	 * @orm Get all ops manager of this cluster.
	 * @return \model\user\user
	 */
	public function getOpsManager()
	{
		return orm('user/user')->where('userID IN (SELECT userID FROM cluster_opsmanager WHERE clusterID = ?)', array($this->clusterID))->find();
	}

	public function assignOpsmanager(\model\user\user $user)
	{
		if(!$this->hasOpsmanager())
		{
			db::insert('cluster_opsmanager', array(
				'clusterID' => $this->clusterID,
				'userID' => $user->userID,
				'clusterOpsmanagerCreatedDate' => now(),
				'clusterOpsmanagerCreatedUser' => session::get('userID')
				));

			return true;
		}

		return false;
	}

	public function hasOpsmanager()
	{
		return $this->getOpsManager() ? true : false;
	}

	public function getClusterID($siteID)
	{
		db::from("cluster_site");
		db::where("siteID",$siteID); ## only active onle
		
		## return result truely grouped by cluster id
		return db::get()->result();
	}

	public function getTime($id)
	{
		$closingTime = array(

				1=>"17",
				2=>"17",
				3=>"17",
				4=>"17",
				5=>"18"

		);
		return $closingTime[$id];
	}

	/**
	 * Remove site assignments.
	 * @param array siteIDs list of optional site id.
	 */
	public function clearAssignments(array $siteIDs = array())
	{
		db::delete('cluster_site', array('clusterID' => $this->clusterID));
	}

	public function lists()
	{
		db::select("cluster.*");
		#db::join("cluster_lead","cluster.clusterID = cluster_lead.clusterID AND clusterLeadStatus = '1'");
		#db::join("user","cluster_lead.userID = user.userID");
		return db::get('cluster')->result();
	}

	public function getClusterLeadByCluster($cluster = null,$selects = null)
	{
		db::from("cluster_lead");
		db::where("clusterLeadStatus",1); ## only active onle
		db::join("user","user.userID = cluster_lead.userID");
		db::join("user_profile","user_profile.userID = cluster_lead.userID");

		## return result truely grouped by cluster id
		return db::get()->result("clusterID",true);
	}

	public function add($data)
	{
		$data	= Array(
				"clusterName"=>$data['clusterName'],
				"clusterCreatedDate"=>now(),
				"clusterCreatedUser"=>session::get("userID")
						);

		db::insert("cluster",$data);

		return db::getLastID("cluster","clusterID");
	}

	## get one record of a cluster.
	public function get($clusterID,$col = null)
	{
		db::where("clusterID",$clusterID);
		return db::get("cluster")->row($col);
	}

	public function addSite($clusterID,$siteList)
	{
		## check if already added, to avoid clashes.
		db::where("clusterID",$clusterID);
		$result	= db::get("cluster_site")->result('siteID');

		foreach($siteList as $siteID)
		{
			## if set already has, skip.
			if(isset($result[$siteID]))
			{
				continue;
			}

			$data	= Array(
					"siteID"=>$siteID,
					"clusterID"=>$clusterID
							);

			db::insert("cluster_site",$data);
		}
	}

	## unassign site from the cluster.
	public function removeSite($clusterID,$siteID)
	{
		if(db::delete("cluster_site",Array("clusterID"=>$clusterID,"siteID"=>$siteID)))
		{
			return true;
		}

	}

	public function getAvailableClusterLead($clusterID)
	{
		db::from("user");
		db::where("userLevel",3);	## cluster lead 
		db::where("user.userID NOT IN (SELECT userID FROM cluster_lead WHERE clusterLeadStatus = '1' AND clusterID = '$clusterID')");
		#db::where("user.userID NOT IN (SELECT userID FROM cluster_lead WHERE clusterLeadStatus = '1')"); ## not assigned yet.
		db::join("user_profile","user_profile.userID = user.userID");

		return db::get()->result();
	}

	public function assignLead($clusterID,$userID)
	{/*
		## check if the cluster already had a cluster.
		db::where(Array("clusterID"=>$clusterID,"clusterLeadStatus"=>1));
		db::join("user","user.userID = cluster_lead.userID");
		$row	= db::get("cluster_lead")->row();
		
		## if got user, and not in override mode.
		if($row && !$override)
		{
			return Array(false,$row);
		}*/

		/*## else, update last lead (for the selected cluster) to 0, if got.
		db::where(Array("clusterLeadStatus"=>1,"clusterID"=>$clusterID))
		->update("cluster_lead",Array("clusterLeadStatus"=>0));*/

		## and insert new cluster lead.
		$data_lead	= Array(
					"clusterID"=>$clusterID,
					"userID"=>$userID,
					"clusterLeadCreatedDate"=>now(),
					"clusterLeadCreatedUser"=>session::get("userID"),
					"clusterLeadStatus"=>1
							);

		db::insert("cluster_lead",$data_lead);

		return Array(true);
	}

	public function unAssignLead($clusterID,$userID)
	{
		## set clusterLeadStatus to 0.
		db::where(Array("clusterID"=>$clusterID,"userID"=>$userID))
		->update("cluster_lead",Array("clusterLeadStatus"=>0));
	}

	##
	public function getClusterByUser($userID)
	{
		db::where(Array(
				"clusterLeadStatus"=>1,
				"userID"=>$userID
						));

		return db::get("cluster_lead")->row();
	}

	public function getClustersByUser($userID)
	{
		db::where(Array(
				"clusterLeadStatus"=>1,
				"userID"=>$userID
				));
		
		return db::get("cluster_lead")->result();
	}
}



?>