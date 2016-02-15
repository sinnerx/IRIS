<script type="text/javascript">
	
var newsletter = new function()
{
	this.sendTest = function()
	{
		if(!confirm('Send test to <?php echo $email;?>?'))
		{
			return false;
		}

		$.ajax({url: pim.base_url+'ajax/newsletter/testSend'}).done(function(txt)
		{
			alert(txt);
		});
	}

	this.send = function()
	{
		if(!confirm('Push this newsletter to all this sites subscriber?'))
		{
			return false;
		}

		$.ajax({url: pim.base_url+'ajax/newsletter/mailPush'}).done(function(txt)
		{
			alert(txt);
		});
	}
}

</script>
<h3 class='m-b-xs text-black'>Push Newsletter</h3>
<div class='well well-sm'>
List of your newsletter pushes.
</div>
<?php if(!$siteNL->isConnected()):?>
<div class='alert alert-danger'>
This site is not yet connected.
</div>
<?php endif;?>
<div class="row">
	<div class='col-sm-6'>
	<div class="panel panel-default">
		<p>Blast newsletter to this site's subscribers. You may test a mail send, so you can see how your email would looks like before blasting to subscribers.</p>
		<div class="panel-body">
			<div class="row" style="padding-bottom:10px;">
				<div class='col-sm-10'>
					<input type='button' class='btn btn-primary' value='Preview' data-toggle='ajaxModal' href='<?php echo url::base('ajax/newsletter/preview');?>' />
					<input type='button' class='btn btn-primary' onclick='newsletter.sendTest();' value='Test Send to <?php echo $email;?>' />
				</div>
			</div>
			<div class='row'>
				<div class='col-sm-2'>
					<input type='button' class='btn btn-success' onclick='newsletter.send();' value='Blast' />
				</div>
			</div>
		</div>
	</div>
	</div>
	<div class="col-sm-6">
		<h3>Latest Pushes</h3>
		<div class='table-responsive'>
		<table class="table">
			<thead>
				<tr>
					<th width="15px">No. </th>
					<th>Latest push </th>
				</tr>
			</thead>
			<tbody>
				<?php if(count($mailpushes) > 0):
				$no = 1;
				?>
				<?php foreach($mailpushes as $mailpush):?>
					<tr>
						<td><?php echo $no++;?>.</td>
						<td><?php echo $mailpush->siteNewsletterMailpushSubject;?></td>
						<td><?php echo date("d-F-Y, g:i A", strtotime($mailpush->siteNewsletterMailpushDate));?></td>
					</tr>
				<?php endforeach;?>
				<?php else:?>
					<tr>
						<td colspan="2">You haven't done any manual mail push yet.</td>
					</tr>
				<?php endif;?>
			</tbody>
		</table>
		</div>
	</div>
</div>