<style type="text/css">
*
{
	font-family: "tahoma";
}
	
#login-box
{
	width:80%;
	margin:auto;
	margin-top: 100px;
}

#left-panel
{
	font-size:60px;
	width:50%;
	float:left;
	text-align: right;
}

#left-panel > div
{
	text-align: right;
}

#left-panel > div, #right-panel > div
{
	padding:5px;
	padding-right:20px;
}

.left-panel-text
{
	font-size:20px;
	letter-spacing: 1px;
}

#right-panel
{
	width:50%;
	float:left;
	padding-top:35px;
}

	#right-panel td, #right-panel input
	{
		font-size:20px;
	}

	#right-panel input[type=text], #right-panel input[type=password]
	{
		width:80%;
	}

.message_error
{
	font-size:18px;
	color: #dd7d7d;
}

</style>
<div id='login-box'>
	<div id='left-panel'>
		<div>
			<div>Dashboard<br>log-in panel</div>
			<div class='left-panel-text' style='padding-left:150px;'>For site manager, cluster lead, operational manager and root admin.</div>
		</div>
	</div>
	<div id='right-panel'>
		<div>
		<form method='post'>
		<div class='left-panel-text'>Fill in your authentication details.</div>
		<?php echo flash::data();?>
		<table id='login-table'>
			<tr>
				<td>Email</td><td>: <?php echo form::text("userEmail");?></td><td><?php echo flash::data("userEmail");?></td>
			</tr>
			<tr>
				<td>Password</td><td>: <?php echo form::password("userPassword");?></td><td><?php echo flash::data("userPassword");?></td>
			</tr>
			<tr>
				<td colspan="2"><?php echo form::submit("Log-In");?></td>
			</tr>
		</table>
		</form>
		</div>
	</div>
</div>