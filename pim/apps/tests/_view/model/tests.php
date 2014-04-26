<style type="text/css">
	
body
{
	background: #eaeaea;
}

.wrapper
{
	margin: auto;
	width:40%;
	background: white;
}
.wrapper-title
{
	padding:3px;
}

.wrapper-header, .wrapper-body
{
	padding:3px;
}

#model-name
{
	width:20%;
}

#model-function
{
	width:78%;
}

</style>
<div class='wrapper'>

<div class='wrapper-title'>Model Tester</div>
<div class='wrapper-header'>
<?php echo form::text("model-name")." : ".form::text("model-function");?>
</div>
<div class='wrapper-body'>

</div>

</div>