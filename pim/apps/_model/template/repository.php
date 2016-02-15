<?php
namespace model\template;

class Repository
{
	public function templateList($type,$templateName)
	{
		$templateList		= Array();
		switch($type)
		{
			## can be used by wrap()
			case "default":
			$templateList['input-error']	= "<div class='label label-danger'>{content}</div >";
			$templateList['span-error']		= "<span class='span-error'>{content}</span>";
			break;
			case "mail":
			$templateList	= Array(
				## public contact notification
				"public-subject"=>"Contact Form : {messageSubject}",
				"public-message"=>
						"Good days, Mr,<br><br>

						<b>Name</b> : {contactName}<br>
						<b>Phone No</b> : {contactPhoneNo}<br>
						<b>Email</b> : {contactEmail}<br><br>

						<b><u>Date</u></b> : {messageDate}<br>
						<b><u>Category</b> : {siteMessageCategory}<br>
						<b><u>Subject</u></b>: <br>
						{messageSubject}<br>
						<b><u>Content</u></b> : <br>
						{messageContent}<br><br>",

				## reset password notification
				"resetpass-subject"=>"P1M Password Reset",
				"resetpass-content"=>
						"Hi, Mr {userProfileFullName},<br><br>

						You or someone has requested for a password reset for the Pi1M site. If you want to proceed to reset the password, please click this link :<br>
						{resetLink}
						"
						);
			break;
		}

		return isset($templateList[$templateName])?$templateList[$templateName]:false;
	}
}


?>