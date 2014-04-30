<?php
class Controller_Site
{
	public function index($page = 1)
	{
		$data['stateR']	= model::load("helper")->state();

		## prepare cluster.
		$res_cluster	= model::load("site/cluster")->lists();
		foreach($res_cluster as $row)
		{
			$data['clusterR'][$row['clusterID']]	= $row['clusterName'];
		}

		## 1. paginate to list
		db::from("site");

		## if got _GET[state]
		if(request::get("state"))
		{
			db::where("stateID",request::get("state"));
		}

		## if got _GET[cluster]
		if(request::get("cluster"))
		{
			db::where("siteID IN (SELECT siteID FROM cluster_site WHERE clusterID = '".request::get("cluster")."')");
		}

		## if got _GET[search]
		if(request::get("search"))
		{
			db::where("siteName LIKE ?","%".request::get("search")."%");
		}

		pagination::initiate(Array(
						"totalRow"=>db::num_rows(),
						"currentPage"=>$page,
						"urlFormat"=>url::base("site/index/{page}",true)
								));

		db::order_by("siteName","ASC");
		db::limit(pagination::get("limit"),pagination::recordNo()-1);

		## get result.
		$data['res_site']		= db::get()->result('siteID');

		## 2. get manager list by site result
		if($data['res_site'])
		{
			$data['sitemanagerR']	= model::load("site/manager")->getManagersBySite(array_keys($data['res_site']));
		}

		view::render("root/site/index",$data);
	}

	## site detail.
	public function info()
	{
		$data['row_site']	= model::load("site/site")->getSiteByManager();

		view::render("root/site/info",$data);
	}

	public function add()
	{
		if(form::submitted())
		{
			## load site model.
			$site			= model::load("site/site");
			$siteServices	= model::load("site/services");

			## site name.
			$siteName	= input::get("siteName");
			$manager	= explode(",",input::get("manager")); ## expected list of email.
			$siteSlug	= input::get("siteSlug");
			
			## trim manager.
			array_walk($manager, create_function('&$manager', '$manager = trim($manager);'));
			
			$siteSlugCheck	= $siteServices->checkSiteSlug($siteSlug)?Array(false,"Slug already exists."):Array(true);

			## manually check email input.
			$wrongFormatEmail	= Array();
			$listedEmail		= Array();
			$sameEmail			= Array();
			foreach($manager as $email)
			{
				## validate format.
				if(!validator::_validate($email,"email"))
				{
					$wrongFormatEmail[]	= $email;
				}

				## check same email in input.
				if(in_array($email,$listedEmail))
				{
					$sameEmail[]	= $email;
				}

				## list all the looped email.
				$listedEmail[]	= $email;
			}

			## 1. got same email input.
			if(count($sameEmail) > 0)
			{
				$managerCheck	= Array(false,"Got duplicate email : ".implode(", ",$sameEmail));
			}
			## 2. got wrong email format.
			else if(count($wrongFormatEmail) > 0)
			{
				$managerCheck	= Array(false,"Please write a correct email format for : <u>".implode("</u>,<u>",$wrongFormatEmail)."</u>");
			}
			## 3. check manager email record in db.
			else
			{
				$managerCheck	= $siteServices->checkManager($listedEmail);
			}

			## 4. prepare rules.
			$rules	= Array(
					"siteName,siteSlug,manager"=>"required:This field is required.",
					"manager"=>Array(
							"callback"=>Array($managerCheck[0],$managerCheck[1])
									),
					"siteSlug"=>Array(
							"min_length(5):length must be longer than {length}",
							"callback"=>Array($siteSlugCheck[0],$siteSlugCheck[1])
									)
							);

			## 5. if got error in validating input.
			if($error = input::validate($rules))
			{
				## wrap with template span-error.
				$error	= model::load("template/services")->wrap("input-error",$error);

				input::repopulate();
				redirect::withFlash($error);
				redirect::to();
			}

			## populate data, with value from input.
			$data	= input::get();
			$data['userID']	= $managerCheck[1]; ## put list of userID.

			## finally, create site.
			$site->createSite($data);
			redirect::to("","New site has been added!.");
		}

		$data['stateR']	= model::load("helper")->state();

		view::render("root/site/add",$data);
	}

	public function unassignManager($siteID,$userID)
	{
		##unassign.
		model::load("site/site")->unassignUser($siteID,$userID);

		redirect::to("site/index","Site manager has been de-assigned");
	}

	public function assignManager($siteID)
	{
		## get site name.
		$data['row'] 	= model::load("site/site")->getSite($siteID,"siteName,stateID");
		$data['state']	= model::load("helper")->state($data['row']['stateID']);

		if(form::submitted())
		{
			if($error = input::validate(Array("_all"=>"required:This field is required.")))
			{
				redirect::withFlash(model::load("template/services")->wrap("input-error",$error));
				redirect::to("","error lah","error");
			}

			## assign.
			model::load("site/site")->assignManager($siteID,input::get("userID"));

			redirect::to("site/index",'Site manager has been assigned to '.$data['row']['siteName']);
		}

		## get available manager.
		$row_user		= model::load("site/manager")->getAvailableManager("user.userID,userProfileFullName,userEmail");

		## create array for select/
		foreach($row_user as $row)
		{
			$data['userR'][$row['userID']] = $row['userProfileFullName']." (".$row['userEmail'].")";
		}

		view::render("root/site/assignManager",$data);
	}

	## temporary function to import csv.
	public function importCSV()
	{
		$filenameR	= Array(
					"sabahclusterA"=>Array(1,"sabah cluster A.csv",12),
					"sabahclusterB"=>Array(2,"sabah cluster B.csv",12),
					"sabahclusterC"=>Array(3,"sabah cluster C.csv",12),
					"sarawak"=>Array(4,"sarawak.csv",13),
					"semenanjung-johor"=>Array(5,"Johor.csv",1),
					"semenanjung-kedah"=>Array(5,"Kedah.csv",2),
					"semenanjung-kl"=>Array(5,"Kuala Lumpur.csv",14),
					"semenanjung-ns"=>Array(5,"Negeri Sembilan.csv",5),
					"semenanjung-perlis"=>Array(5,"Perlis.csv",9)
							);

		$file	= Array();
		foreach(array_keys($filenameR) as $name)
		{
			$file[$name]	= $name;
		}

		echo "<form method='post'>".form::select("cluster",$file)."<input type='submit' /></form>";
		echo flash::data();

		if(form::submitted())
		{
			$name	= input::get("cluster");

			if(!file_exists(path::files($filenameR[$name][1])))
			{
				redirect::to("","File no exists.?".path::files($filenameR[$name][1]));
			}

			$file	=  file_get_contents(path::files($filenameR[$name][1]));
			$file	= explode("\n",$file);

			$siteR	= Array();
			$clusterID	= $filenameR[$name][0];

			foreach($file as $row)
			{
				$row	= str_getcsv($row);

				if($row[1] == "")
				{
					continue;
				}

				## site name.
				$data['userID']		= Array();
				$data['siteName']	= $row[1];
				$data['stateID']	= $filenameR[$name][2];
				$data['siteSlug']	= $row[6];

				$user[1]['userPassword']		= model::load("user/services")->getDefaultPassword();
				$user[2]['userPassword']		= model::load("user/services")->getDefaultPassword();
				$user[1]['userProfileFullName']	= $row[2];
				$user[2]['userProfileFullName']	= $row[3];

				#email
				list($user[1]['userEmail'],$user[2]['userEmail']) = explode("/",$row[5]);

				#phone no
				list($user[1]['userProfilePhoneNo'],$user[2]['userProfilePhoneNo']) = explode("/",$row[4]);

				## get userID, create if not exists.
				foreach($user as $no=>$row)
				{
					$userID	= db::where("userEmail",trim($row['userEmail']))->get("user")->row("userID");

					if(!$userID)
					{
						$row	= model::load("user/user")->add($row,2); ## add manager.
						$userID	= $row['userID'];
					}

					$data['userID'][]	= $userID;
				}

				## create site.
				$row	= model::load("site/site")->createSite($data);

				$siteR[] = $row['siteID'];
			}

			## add into cluster.
			model::load("site/cluster")->addSite($clusterID,$siteR);

			redirect::to("","Cluster : ".input::get("cluster")." Added");
		}
	}
}


?>