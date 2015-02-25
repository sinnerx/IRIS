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
			<?php echo form::select("category",$categoryNames,"class='input-sm form-control input-s-sm inline' onchange='setFilter();'",request::get("category"),"[SEMUA]");?>
		</div>
		<form method='get' onsubmit="return findByRef();">
		<div class='col-sm-2'>
			<div class='input-group'><?php echo form::text("search","class='input-sm form-control' style='text-transform:uppercase;' placeholder='Reference No.'");?>
			<span class='input-group-btn'>
			<button class='btn btn-sm btn-default' type='submit' onclick=''>Go!</button>
			</span>
			</div>
		</div>
		</form>
	</div>
	<div class="table-responsive">
	<table class='table'>
		<tr>
			<th width='100px'>Ref. No.</th><th>Subject</th><th>Kategori</th><th>From</th><th>Phone No.</th><th>Site</th><th>Date</th><th width='24px'></th>
		</tr>
		<?php
		if($messages->count() > 0):
		$no = pagination::recordNo();
		foreach($messages as $message):
			$refNo = $message->getEncryptedID();
			$subject	= $message->messageSubject;
			$from		= $message->contactName;
			$phoneNo	= $message->contactPhoneNo;
			$siteUrl	= $message->siteName?url::base("site/edit/".$message->siteID):"#";
			$detailUrl	= url::base("site/messageView/$refNo");
			$date		= date("d F Y, g:i A",strtotime($message->messageCreatedDate));
			$category = $message->getCategory();

			$icon = '';
			if($message->siteMessageReadStatus == 1)
			{
				if($message->siteMessageStatus != 2)
				{
					$url = url::base('site/messageClose/'.$message->siteMessageID);
					$icon = '<a href="'.$url.'" data-toggle="ajaxModal" title="Mark this as closed?" class="fa fa-circle-o text-success"></a>';
				}
				else
				{
					$icon = '<span title="This message has been marked as closed" class="fa fa-check-circle text-success"></span>';
				}
			}
		?>
			<tr>
				<td><?php echo $refNo;?></td>
				<td><?php echo $subject;?></td>
				<td><?php echo $category;?></td>
				<td><?php echo $from;?></td>
				<td><?php echo $phoneNo;?></td>
				<td><?php echo $date;?></td>
				<td>
					<a class='fa fa-search' href='<?php echo $detailUrl;?>'></a>
					<?php echo $icon;?>
				</td>
			</tr>
		<?php
		endforeach;

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