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
						<b><u>Subject</u></b>: <br>
						{messageSubject}<br>
						<b><u>Content</u></b> : <br>
						{messageContent}<br><br>

						Or you can follow the link below to read the message in your site inbox later :<br>
						{messageLink}",

				## reset password notification
				"resetpass-subject"=>"P1M Password Reset",
				"resetpass-content"=>
						"Selamat petang {userProfileFullName},<br><br>

						Untuk makluman anda, kata laluan anda telah berjaya di reset ke '{userPassword}'.
						"
						);
			break;
		}



		return isset($templateList[$templateName])?$templateList[$templateName]:false;
	}
}


?>