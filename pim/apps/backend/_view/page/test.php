<!DOCTYPE html>
<html>
<head>
	<title></title>
<script type="text/javascript" src='<?php echo url::asset("_scale/js/jquery.min.js");?>'></script>
<link rel="stylesheet" type="text/css" href="<?php echo url::asset("_scale/css/bootstrap.css");?>">
<script type="text/javascript" src='<?php echo url::asset("_scale/js/bootstrap.js");?>'></script>
<style type="text/css">
	
body
{
	padding-top:10px;
	background: #dbdbdb;
	font-family: "tahoma";
}

.container
{
	width: 80%;
}

</style>
</head>
<body>
<div class='container'>
	<div class='row'>
		<div class='col-sm-6'>
			<div class='panel panel-default'>
				<div class='panel-heading'>
				Header 1
				</div>
				<div class='panel-body'>
				This is panel body
				</div>
			</div>
		</div>
		<div class='col-sm-3'>
			<div class='panel panel-default'>
				<div class='panel-heading'>
				Header 2
				</div>
				<div class='panel-body'>
				This is panel body 2
				</div>
			</div>
		</div>
		<div class='col-sm-3'>
			<div class='panel panel-default'>
				<div class='panel-heading'>
				Header 3
				</div>
				<div class='panel-body'>
				This is panel body 3
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class='panel panel-default'>
			<div class='panel-heading'>
			Header 4
			</div>
			<div class='panel-body'>
				<div class='row'>
					<div class='col-sm-4'>
					<div class='panel panel-primary'>
						<div class='panel-heading'>
							Headerception 1
						</div>
						<div class='panel-body'>
							This is panelception 1
						</div>
					</div>
					</div>
					<div class='col-sm-3'>
						<div class='panel panel-success'>
						<div class='panel-heading'>
							Headerception 2
						</div>
						<div class='panel-body'>
							This is panelception 2
						</div>
						</div>
					</div>
					<div class='col-sm-2'>
						<div class='panel panel-warning'>
						<div class='panel-heading'>
							Headerception 3
						</div>
						<div class='panel-body'>
							This is panelception 3
						</div>
						</div>
					</div>
					<div class='col-sm-3'>
						<div class='panel panel-danger'>
						<div class='panel-heading'>
							Headerception 4
						</div>
						<div class='panel-body'>
							This is panelception 4
						</div>
						</div>
					</div>
				</div>
				<div class='row'>
					<div class='col-sm-6'>
						<div class='alert alert-success'>
						This is success alert
						</div>
					</div>
					<div class='col-sm-3'>
						<div class='alert alert-danger'>
						This is error alert
						</div>
					</div>
					<div class='col-sm-3'>
						<div class='alert alert-warning'>
						Just a warning alert
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</body>
</html>