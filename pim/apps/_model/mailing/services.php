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
		$this->mailer->Host			= "smtp.gmail.com";
		$this->mailer->SMTPAuth		= true;
		$this->mailer->Username		= "pi1m@digitalgaia.com";
		$this->mailer->Password		= "12345%12345";
		$this->mailer->SMTPSecure	= "tls";
		$this->mailer->Port			= 587;
		$this->mailer->isHTML(true);
		#$this->mailer->SMTPDebug	= 1;

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
		$this->mailer->addAddress($to);
		$this->mailer->Subject	= $subject;
		$this->mailer->Body		= $message;

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