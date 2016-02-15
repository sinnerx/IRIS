<style type="text/css">
	
#fourofour
{
	margin: auto;
	width:400px;
	font-family: "tahoma";
	text-align: center;
	opacity: 0.8;
}

#num
{
	font-size:200px;
}

#text
{
	letter-spacing: 3px;
}

.message_error
{
	letter-spacing: 3px;
}

</style>
<div id='fourofour'>
	<div id='num'>404</div>
	<div id='text'>Unable to execute the page request.</div>
	<?php
	if(request::get("site_not_found")):?>
	Error : site <u><?php echo request::get("site_not_found");?></u> not found.
	<?php
	elseif(request::get("error")):?>
		Error : 
		<?php
		$errorCode	= Array(
					"pagenotfound"=>"Couldn't find the page you're looking for.",
					"tokeninvalid"=>"Sorry but this access token is no longer invalid.",
					"noaccess"=>"You have no access to these page."
							);

		echo isset($errorCode[request::get("error")])?$errorCode[request::get("error")]:"Unable to parse error message";
		?>
	<?php endif;?>
</div>