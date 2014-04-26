<?php
namespace model\access;
use request, controller, session;
class Services extends Data
{
	## role check.
	public function roleCheck($roleName)
	{
		$level	= $this->accessLevelCode(session::get("userLevel"));

		$roleListR	= $this->roleList();

		## role not set.
		if(!isset($roleListR[$roleName]))
		{
			return false;
		}

		## his level wasn't set in that role.
		if(!in_array($level,$roleListR[$roleName]))
		{
			return false;
		}

		return true;
	}

	## check whether the level was within the access list.
	public function accessListCheck($level)
	{
		$CM	= controller::getCurrentController()."/".controller::getCurrentMethod();
		$acl	= $this->accessList();
		return true; ## no more access list checking, because each role has now assigned to each subcontroller.
		## not isset.
		if(!isset($acl[$CM]))
		{
			if(!request::isAjax())
			{
				error::set("Access","Access list not found.");
			}

			
			return true;
		}
		else if(!in_array($level,$acl[$CM]))
		{
			return false;
		}

		return true;
	}
}


?>