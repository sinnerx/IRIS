<script type="text/javascript">
	
function setFilter()
{
	var cat	= $("#category").val() != ""?"&category="+$("#category").val():"";

	window.location.href	= "?"+cat;
}

function findByRef()
{
	var base_url	= "<?php echo url::base();?>";
	window.location.href	= base_url+"/site/messageView/"+$("#search").val();

	return false;
}

</script>
<h3 class="m-b-xs text-black">
Submitted Contact Form Messages
</h3>
<div class='well well-sm'>
List of all messages sent through the contact form on all Pi1Ms
</div>
<?php echo flash::data();?>
<div class='row'>
<div class='col-sm-12'>
	<section class='panel panel-default'>
	<div class='row wrapper'>
		<div class='col-sm-10'>
			<?php echo form::select("category",$categoryNameR,"class='input-sm form-control input-s-sm inline' onchange='setFilter();'",request::get("category"),"[SEMUA]");?>
		</div>
		<form method='get' onsubmit="return findByRef();">
		<div class='col-sm-2'>
			<div class='input-group'><?php echo form::text("search","class='input-sm form-control' style='text-transform:uppercase;' placeholder='Reference No.'");?>
			<span class='input-group-btn'>
			<button class='btn btn-sm btn-default' type='button' onclick=''>Go!</button>
			</span>
			</div>
		</div>
		</form>
	</div>
	<div class="table-responsive">
	<table class='table'>
		<tr>
			<th width='100px'>Ref. No.</th><th>Subject</th><th>Kategori</th><th>From</th><th>Phone No.</th><th>Site</th><td>Date</td><th width='24px'></th>
		</tr>
		<?php
		if($res_message):
		$no = pagination::recordNo();
		foreach($res_message as $row)
		{
			$refNo	= $siteMessage->encryptID($row['siteMessageID']);
			$subject	= $row['messageSubject'];
			$from		= $row['contactName'];
			$phoneNo	= $row['contactPhoneNo'];
			$site		= $row['siteName'];
			$siteUrl	= url::base("site/edit/".$row['siteID']);
			$detailUrl	= url::base("site/messageView/$refNo");
			$date		= date("d F Y, g:i A",strtotime($row['messageCreatedDate']));
			$category	= $categoryNameR[$row['siteMessageCategory']];	

			echo "<tr>";
			echo "<td>$refNo</td>";
			echo "<td>$subject</td>";
			echo "<td>$category</td>";
			echo "<td>$from</td>";
			echo "<td>$phoneNo</td>";
			echo "<td><a href='$siteUrl'>$site</a></td>";
			echo "<td>$date</td>";
			echo "<td><a class='fa fa-search' href='$detailUrl'></a></td>";
			echo "</tr>";
		}

		else:?>
		<tr>
			<td colspan="4" align="center">No message at all.</td>
		</tr>

		<?php endif;?>
	</table>
	</div>
	</section>
	<footer class='row'>
	<div class='col-sm-12'>
	<?php echo pagination::link();?>
	</div>
	</footer>
</div>
</div>