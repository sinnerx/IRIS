<?php
namespace model\mailing;
use PHPMailer; ## use the autoloaded phpmailer, through composer.
class Services
{
	## construct with required data first.
	## https://github.com/PHPMailer/PHPMailer
	public function __construct()
	{
		$this->mailer	= new PHPMailer;

		## configuration
		$this->mailer->isSMTP();
		#$this->mailer->Host			= "smtp.gmail.com";			-- old
		$this->mailer->Host			= "power1.mschosting.com";
		$this->mailer->SMTPAuth		= true;
		#$this->mailer->Username		= "pi1m@digitalgaia.com"; 	-- old
		#$this->mailer->Password		= "12345%12345";			-- old
		$this->mailer->Username		= "pi1m@nusuara.com";
		$this->mailer->Password		= "fulkrum@123";
		$this->mailer->SMTPSecure	= "tls";
		$this->mailer->Port			= 456;
		$this->mailer->isHTML(true);

		## the from email, it should be like 
		$this->mailer->From			= "";
		$this->mailer->FromName 	= 'Pi1M Website';

		## some static email address.
		$this->From_Admin	= "admin@p1m.gaia.my";
	}

	## normal mail, content is passed.
	public function mail($from,$to,$subject,$message)
	{
		## set the required field.
		$this->mailer->From		= !$from?$this->From_Admin:$from;
		
		$to		= is_array($to)?$to:Array($to);

		## loop the 'to' address.
		foreach($to as $key=>$addr)
		{
			if(in_array($key,Array("cc","bcc")))
			{
				switch($key)
				{
					case "cc":
					$this->mailer->addCC($addr);
					break;
					case "bcc":
					$this->mailer->addBCC($addr);
					break;
				}
			}
			## if not a cc, or bcc kind of address.
			else
			{
				$this->mailer->addAddress($addr);
			}
		}

		## set subject and body.
		$this->mailer->Subject	= $subject;
		$this->mailer->Body		= $message;

		## if mail send return false.
		if(!$this->mailer->send())
		{
			return false;
		}

		return true;
	}

	public function getInstance()
	{
		return $this->mailer;
	}
}