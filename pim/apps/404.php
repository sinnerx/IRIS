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
	<?php echo flash::data();flash::clear();?>
</div>