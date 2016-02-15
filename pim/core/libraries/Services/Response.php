<?php

class Response
{
	public static function json($array)
	{
		return Array("json",json_encode($array));
	}

	public static function setHeader($item)
	{
		if(is_array($item))
		{
			foreach($item as $val)
			{
				header($val);
			}
			return;
		}

		header($item);
	}

	public static function setStatus($code,$customMessage = null)
	{
		## http://my1.php.net/http-response-code
		$http_status_codes = array(100 => "Continue", 101 => "Switching Protocols", 102 => "Processing", 200 => "OK", 201 => "Created", 202 => "Accepted", 203 => "Non-Authoritative Information", 204 => "No Content", 205 => "Reset Content", 206 => "Partial Content", 207 => "Multi-Status", 300 => "Multiple Choices", 301 => "Moved Permanently", 302 => "Found", 303 => "See Other", 304 => "Not Modified", 305 => "Use Proxy", 306 => "(Unused)", 307 => "Temporary Redirect", 308 => "Permanent Redirect", 400 => "Bad Request", 401 => "Unauthorized", 402 => "Payment Required", 403 => "Forbidden", 404 => "Not found", 405 => "Method Not Allowed", 406 => "Not Acceptable", 407 => "Proxy Authentication Required", 408 => "Request Timeout", 409 => "Conflict", 410 => "Gone", 411 => "Length Required", 412 => "Precondition Failed", 413 => "Request Entity Too Large", 414 => "Request-URI Too Long", 415 => "Unsupported Media Type", 416 => "Requested Range Not Satisfiable", 417 => "Expectation Failed", 418 => "I'm a teapot", 419 => "Authentication Timeout", 420 => "Enhance Your Calm", 422 => "Unprocessable Entity", 423 => "Locked", 424 => "Failed Dependency", 424 => "Method Failure", 425 => "Unordered Collection", 426 => "Upgrade Required", 428 => "Precondition Required", 429 => "Too Many Requests", 431 => "Request Header Fields Too Large", 444 => "No Response", 449 => "Retry With", 450 => "Blocked by Windows Parental Controls", 451 => "Unavailable For Legal Reasons", 494 => "Request Header Too Large", 495 => "Cert Error", 496 => "No Cert", 497 => "HTTP to HTTPS", 499 => "Client Closed Request", 500 => "Internal Server Error", 501 => "Not Implemented", 502 => "Bad Gateway", 503 => "Service Unavailable", 504 => "Gateway Timeout", 505 => "HTTP Version Not Supported", 506 => "Variant Also Negotiates", 507 => "Insufficient Storage", 508 => "Loop Detected", 509 => "Bandwidth Limit Exceeded", 510 => "Not Extended", 511 => "Network Authentication Required", 598 => "Network read timeout error", 599 => "Network connect timeout error");

		header("HTTP/1.0 $code ".(!$customMessage?$http_status_codes[$code]:$customMessage));
	}

	public static function redirect($to,$msg = null,$msg_type = null)
	{
		return Array("redirect",function() use($to,$msg,$msg_type) {
			redirect::to($to,$msg,$msg_type);
		});
	}

	public static function view($view,$data = Array())
	{
		return Array("view",function() use ($view,$data)
		{
			view::render($view,$data);
		});
	}

	public static function parse($response)
	{
		if(is_array($response))
		{
			if(!in_array($response[0], Array("json","redirect","view")))
				return $response;

			switch($response[0])
			{
				case "json":
					return $response[1];
				break;
				case "redirect":
					call_user_func($response[1]);
				break;
				case "view":
					call_user_func($response[1]);
				break;
			}
		}
		else
		{
			return $response;
		}
	}
}



?>