<?php
class Controller_Cluster
{
	public function lists()
	{
		$cluster				= model::load("site/cluster");
		// $data['res_cluster']	= $cluster->lists();
		$data['res_cluster'] = orm('site/cluster')->execute();
		## get a grouped cluster lead, by cluster, that they may live under the same cluster.
		$data['clusterLeadByClusterR']	= $cluster->getClusterLeadByCluster();

		## new cluster submitted.
		if(form::submitted())
		{
			$name	= input::get("clusterName");

			## empty.
			if($name == "")
			{
				redirect::to("","Cluster name is required.","error");
			}

			$data['clusterName']	= $name;
			$clusterID	= $cluster->add($data);

			redirect::to("cluster/lists#added=$clusterID","New cluster has been added. Please proceed with assigning a site.");
		}

		view::render("root/cluster/lists",$data);
	}

	public function assignOpsmanager($clusterID)
	{
		$cluster = orm('site/cluster')->find($clusterID);

		if(form::submitted())
		{
			$cluster->assignOpsmanager($user = orm('user/user')->find(input::get('userID')));

			redirect::to('cluster/lists', 'Successfully assigned '.$user->getProfile()->userProfileFullName.' to cluster '.$cluster->clusterName);
		}

		// find unassigned clusters
		$clusters = orm('site/cluster')->where('clusterID NOT IN (SELECT clusterID FROM cluster_opsmanager)')->execute()->toList('clusterID', 'clusterName');

		$users = orm('user/user')->join('user_profile', 'user.userID = user_profile.userID')->where('userLevel', 4)->execute()->toList('userID', 'userProfileFullName');

		view::render('root/cluster/assignOpsmanager', array(
			'cluster' => $cluster,
			'clusters' => $clusters,
			'clusterID' => $cluster->clusterID,
			'users' => $users
			));
	}

	public function deassignOpsmanager($clusterID)
	{
		$cluster = orm('site/cluster')->find($clusterID);

		db::where('clusterID', $clusterID)->delete('cluster_opsmanager');

		redirect::to('cluster/lists', 'Successfully deassigned opsmanager from cluster'.$cluster->clusterName);
	}

	public function assign()
	{
		$cluster			= model::load("site/cluster");

		## check and get userID and clusterID from _GET if got.
		$data['userID']			= request::get("userID")?:null;
		$data['clusterID']		= request::get("clusterID")?:null;

		## prepare name for user or cluster to be put in title.
		$data['userName']	 	= $data['userID']?model::load("user/user")->get($data['userID'],"userProfileFullName"):null;
		$data['clusterName']	= $data['clusterID']?$cluster->get($data['clusterID'],"clusterName"):null;

		if(form::submitted())
		{
			$userID		= input::get("userID")?:request::get("userID");
			$clusterID	= input::get("clusterID")?:request::get("clusterID");

			if($error = input::validate(Array("_all"=>"required:This input is required.")))
			{
				redirect::withFlash(model::load("template/services")->wrap("input-error",$error));
				redirect::to("","Got some error in your form eh.?","error");
			}

			$result	= $cluster->assignLead($clusterID, $userID);

			redirect::to("cluster/lists","New cluster lead has been assigned.");
		}

		## get user and cluster.
		$users = model::load('user/user')->getAvailableClusterLeads($data['clusterID']);
		$clusters = model::orm('site/cluster')->execute();

		## prepare list of clusterID=>clusterName
		$data['clusterR']	= $clusters->toList('clusterID', 'clusterName');

		## prepare list of userID=>email (userProfileFullName)
		$data['userR']		= Array();
		foreach($users as $user)
		{
			$profile = $user->getProfile();
			$data['userR'][$user->userID] = $profile->userProfileFullName.' ('.$user->userEmail.')';
		}

		view::render("root/cluster/assign",$data);
	}

	public function unassign()
	{
		$userID		= request::get("userID");
		$clusterID	= request::get("clusterID");

		if($userID && $clusterID)
		{
			model::load("site/cluster")->unAssignLead($clusterID,$userID);
		}
		
		redirect::to("cluster/lists","A user has been un-assigned from the cluster.");
	}

	/**
	 * Delete cluster
	 */
	public function delete($id, $override = null)
	{
		$cluster = model::orm('site/cluster')->find($id);

		$name = $cluster->clusterName;

		$sites = $cluster->getSites();

		if($sites->count() > 0)
		{
			$deleteUrl = url::base('cluster/delete/'.$id.'/override');

			if($override != 'override')
			{
				redirect::to('cluster/lists', 'There are still '.$sites->count().' sites assigned under this cluster. Deleting this will unassign all of those site. Are you sure? <a class="label label-danger" href="'.$deleteUrl.'">Yes. Delete</a>', 'error');
			}
			else
			{
				// unassign all sites.
				$cluster->clearAssignments();
			}
		}

		// delete cluster.
		$cluster->delete();

		redirect::to('cluster/lists', 'Cluster '.$name.' has been deleted.');
	}

	/*public function leadLists($page = 1)
	{
		db::from("user");
		db::join("cluster_lead","cluster_lead.userID = user.userID AND clusterLeadStatus = '1'");
		db::join("cluster","cluster.clusterID = cluster_lead.clusterID");
		db::where("userLevel",3);
		pagination::initiate(Array(
						"totalRow"=>db::num_rows(),
						"limit"=>10,
						"currentPage"=>$page,
						"urlFormat"=>url::base("clusterlead/lists/{page}",true)
								));

		db::join("user_profile","user.userID = user_profile.userID");
		db::limit(pagination::get("limit"),$page-1);
		$data['res_cl']	= db::get()->result();

		view::render("root/clusterlead/lists",$data);
	}

	public function sitelist($userID)
	{
		$data['row']	= model::load("user/user")->get($userID);

		$data['row_sitelist']	= model::load("site/site")->getSitesByClusterlead($userID);

		view::render("root/clusterlead/sitelist",$data);
	}*/

	/*public function leadAdd()
	{
		if(form::submitted())
		{
			$email	= input::get("userEmail");

			## check email.
			$emailCheck	= model::load("user/services")->checkEmail($email);

			$rules	= Array(
					"userIC,userProfileFullName,userEmail"=>"required:This field is required.",
					"userEmail"=>Array(
							"callback"=>Array(!$emailCheck,"Email already exists.")
									)
							);

			## if got validation error.
			if($error = input::validate($rules))
			{
				input::repopulate();
				redirect::withFlash(model::load("template/services")->wrap("input-error",$error));
				redirect::to("","Looks like there's some error in your form.","error");
			}

			## add add add!
			$data	= input::get();
			model::load("user/user")->add($data,3); ## add new user with level :3.

			redirect::to("","New cluster lead has been added!");
		}

		view::render("root/clusterlead/add");
	}*/
}


?>