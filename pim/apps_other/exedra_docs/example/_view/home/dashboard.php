<style type="text/css">
#content
{
	position: relative;
}
#controllerception
{
	position: absolute;
	top:5px;
	right:5px;
	opacity: 0.8;
	font-size:0.8em;
	background: #bae8ae;
	padding:3px;
}	

</style>
This is the main content for the current controller. Echoing <?php echo $abc;?>
<div id='controllerception'>
	<?php controller::load("home","ception");?>
</div>