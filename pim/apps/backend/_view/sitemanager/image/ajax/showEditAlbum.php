<script type="text/javascript">
	
$(document).ready(function()
{
	pim.ajax.formify("#theForm","#right-pane",function(res)
		{
			var res	= $.parseJSON(res);

			if(!res[0])
			{
				alert("Sila isikan ruangan description");
			}
			else
			{
				window.location.href	= "";
			}
		});


});

</script>
<?php echo flash::data();?>
<form id='theForm' method='post' action="<?php echo url::base("ajax/gallery/editAlbum/$row[siteAlbumID]");?>">
<section class='panel panel-default'>
	<div class='panel-heading'>
		<h5>
			Edit Album : <?php echo $row['albumName'];?>
		</h5>
	</div>
	<div class='panel-body'>
		<label>Description</label>
		<div>
			<?php echo form::textarea("albumDescription","style='width:100%;height:100px;'",$row['albumDescription']);?>
		</div>
	</div>
	<div class='panel-footer' style="text-align:right;">
		<input type='submit' class='btn btn-primary' value='Submit' />
	</div>
</section>
</form>