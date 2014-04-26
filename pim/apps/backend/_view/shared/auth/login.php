<!doctype html>
<html>
<head>
<meta charset="UTF-8">

<title>Login</title>

<style type="text/css">
BODY{
background-color: #222733;
    color: #788288;
    font-family: "Open Sans","Helvetica Neue",Helvetica,Arial,sans-serif;
    font-size: 13px;
    line-height: 1.53846;
	margin:0px;
	padding:0px;
}
#left-panel{
	float:left;
	width:40%;
	border-right:1px solid #FFF;
	padding-right:35px;
	margin-right:35px;
	text-align:right;
	min-height:300px;
	
}

#right-panel{
	float:left;
	width:48%;
	
}

#login-box{
position:absolute;
     width:600px;
     height:200px;
     z-index:15;
     top:50%;
     left:50%;
     margin:-100px 0 0 -25%;
}


.heading-block{
 color: #FFFFFF;
    font-size: 40px;
    font-weight: bold;
    line-height: 33px;
	margin-bottom:20px;	
}

.left-panel-text{
	
	
}

table#login-table{
	width:100%;
	font-size:12px;

}


table#login-table input{
	
display: block;
  width: 100%;
  height: 34px;
  padding: 0px 12px;
  font-size: 12px;
  line-height: 1.42857143;
  font-weight:lighter;
  color: #555;
  background-color: #fff;
  background-image: none;
  border: 1px solid #ccc;
  border-radius: 4px;
  -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
          box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
  -webkit-transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
          transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
}
table#login-table input:focus {
  border-color: #66afe9;
  outline: 0;
  -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075), 0 0 8px rgba(102, 175, 233, .6);
          box-shadow: inset 0 1px 1px rgba(0,0,0,.075), 0 0 8px rgba(102, 175, 233, .6);
}


.login-submit{
	background:#009bff !important;
	width:auto !important;
	border:none !important;
	font-weight:bold !important;
	color:#FFF !important;
	cursor:pointer;
	margin-top:15px !important;
	
}
.right-panel-text{
	margin-bottom:10px;
	color:#FFF;
	text-transform:uppercase;
	font-weight:bold;
	
}

.logo-right{
	margin-top:-10px;
	
}
.footer{
	background:#000;
	bottom:0px;
	position:absolute;
	text-align:center;
	color:#788288;
	width:100%;
	font-size:12px;
	padding-top:15px;
	padding-bottom:15px;
	
}

.label.label-danger
{
	color:#dab8b8;
}

.alert.alert-danger
{
	color:#dab8b8;
}
</style>


</head>

<body><div id="login-box">
	<div id="left-panel">
		<div>
			<div class="heading-block">Dashboard<br>log-in panel</div>
			<div class="left-panel-text">For site manager, cluster lead, operational manager and root admin.</div>
		</div>
	</div>
	<div id="right-panel">
		<div>
		<form method="post">
        <div class="logo-right"><img src="<?php echo url::asset("backend/images/login_logo.png");?>"></div>
		<div class="right-panel-text">Fill in your authentication details.</div>
		<?php echo flash::data();?>
				<table id="login-table">
			<tbody><tr>
				<td>Email</td><td> <?php echo form::text("userEmail");?><?php echo flash::data("userEmail");?></td><td></td>
			</tr>
			<tr>
				<td>Password</td><td> <?php echo form::password("userPassword");?><?php echo flash::data("userPassword");?></td><td></td>
			</tr>
			<tr>
				<td colspan="2"><input type="submit" value="Log-In" class="login-submit"></td>
			</tr>
		</tbody></table>
		</form>
		</div>
  </div>
</div>
<div class="footer">
Hakcipta Terpelihara © 2013 Pusat Internet 1 Malaysia. All Rights Reserved</div>

</body></html>