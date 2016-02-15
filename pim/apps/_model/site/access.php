<?php
namespace model\site;
use session;
class Access
{
	## sets of controller@action access for logged user.
	public function loggedAccess()
	{
		$arrR	= Array(
				"gallery"=>"all"
						);

		return $arrR;
	}

	public function checkLoggedAccess($currController,$currMethod)
	{
		## if user not logged in.
		$list	= $this->loggedAccess();

		## check if user is accessing the list.
		if(isset($list[$currController]))
		{
			if($list[$currController] == "all")
			{
				if(!session::has("userID"))
				{
					return false;
				}

				return true;
			}

			if(in_array($currMethod, $list[$currController]))
			{
				if(!session::has("userID"))
				{
					return false;
				}
			}
		}

		return true;
	}
}

?>