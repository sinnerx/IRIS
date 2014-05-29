<?php
namespace model\access;

class Data
{
	## used in backend main route. return true, if shared, else if otherwise.
	public function sharedController($controller = null,$method = null,$level = null)
	{
		if($controller != null && $method != null)
		{
			list($method) = explode("/",$method); ##get first puff.
		}

		$access	= Array(
					"auth/login"=>Array("sm","r","cl"),
					"auth/logout"=>Array("sm","r","cl"),
					"home/index"=>Array("sm","r"),
					"site/info"=>Array("sm","r"),
					"site/edit"=>Array("sm","r"),
					"site/slider"=>Array("sm","r"),
					"site/slider_edit"=>Array("sm","r"),
					"site/announcement"=>Array("sm","r"),
					"site/article"=>Array("sm","r"),
					"site/addArticle"=>Array("sm","r"),
					"site/editArticle"=>Array("sm","r"),
					"site/announcement_add"=>Array("sm","r"),
					"site/editAnnouncement"=>Array("sm","r"),
					"manager/edit"=>Array("r","sm"),
					"user/profile"=>Array("r","sm","cl"),
					"user/changePassword"=>Array("r","sm","cl"),
					"site/message"=>Array("r","sm","cl"),
					"site/messageView"=>Array("r","sm","cl")
							);

		if($level && isset($access["$controller/$method"]))
		{
			$level	= is_numeric($level)?$this->accessLevelCode($level):$level;

			return in_array($level,$access["$controller/$method"]);
		}

		return false;
	}

	public function publicBackend()
	{
		$list	= Array(
					"auth/login",
					"auth/resetPassword",
					"auth/authenticateToken"
						);

		return $list;
	}

	public function accessController($id)
	{
		$levelR	= Array(
				2=>"sitemanager",
				3=>"clusterlead",
				4=>"operationmanager",
				99=>"root"
						);

		return $levelR[$id];
	}

	public function accessLevelCode($id)
	{
		$levelR	= Array(
				2=>"sm",
				3=>"cl",
				4=>"om",
				99=>"r"
						);

		return $levelR[$id];
	}

	## list of role;
	public function roleList()
	{
		$role	= Array(
				"siteEditRoot"=>Array("r"), ## in site/edit
				"siteEdit"=>Array("sm")
						);

		return $role;
	}

	## obsolete, no longer used.
	public function accessList()
	{
		$access	= Array(
				"auth/login"=>Array("sm","r"),
				"auth/logout"=>Array("sm","r","cl"),
				"home/index"=>Array("sm","r"),
				"page/index"=>Array("sm","cl"),
				#"site/index"=>Array("r"),
				"site/info"=>Array("sm","r"),
				#"site/add"=>Array("r"),
				#"site/edit"=>Array("sm","r"),
				#"site/slider"=>Array("sm","r"),
				#"site/slider_edit"=>Array("sm","r"),
				#"user/list"=>Array("r"),
				#"manager/add"=>Array("r"),
				#"manager/lists"=>Array("r"),
				#"manager/edit"=>Array("r","sm"),
				"clusterlead/lists"=>Array("r"),
				"clusterlead/add"=>Array("r"),
				"clusterlead/sitelist"=>Array("r"),
				#"cluster/lists"=>Array("r"),
				#"cluster/assign"=>Array("r"),
				#"cluster/unassign"=>Array("r"),
						);

		return $access;
	}
}

?>