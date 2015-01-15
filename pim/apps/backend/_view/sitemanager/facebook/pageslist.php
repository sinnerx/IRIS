<h3 class='m-b-xs text-black'>Page List</h3>
<div class='well well-sm'>
Select pages to connect
</div> 

<section class='panel panel-default'>

	<form method='post'>
	<div class="row">
	<div class='col-sm-6'>
	
		<table class='table'>
			<tr>
				<th width='15px'>No.</th>				
				<th width='200px'>Name Page</th>
				<th>ID</th>
				<th>Admin</th>
				<th width='50px'></th>
				<th width='50px'></th>
				
			</tr>
		<?php 	$i = 1;

			foreach($data['pages'] as $row)
			{

				$pageName 	= $row->name;				
				if ($row->perms[0] == "ADMINISTER") {	$pageAdmin = "Yes";	} else { $pageAdmin = "No"; }
				$pageID 	= $row->id;
		?>	
			
			<tr>
				<td><?php echo $i++; ?></td>
				<td><?php echo $pageName; ?></td>
				<td><?php echo $pageID; ?></td>
				<td><?php echo $pageAdmin; ?></td>
	  <?php if ($pageAdmin == "Yes")  { ?>
				<td><a class="btn btn-primary" style="margin-left:30px" 
					href="<?php echo url::base('facebook/getPageId');?>?pageID=<?php echo $pageID; ?>" role="button">Connect</a></td>				
					<?php } else { ?>
				<td></td>	<?php }

			if ($data['pageID'] == $pageID)  { ?>
				<td width='50px'><i class="fa fa-check-square" style="color:green"></i></td>
					<?php } else { ?>
				<td></td>	<?php } ?>					
			</tr>
			
	<?php 	}	?>

		</table>
	</div>
	</div>
	</form>
		
	
	<div class='col-sm-5'>
	</div>
	<div class='panel-footer'>

	</div>
</section>