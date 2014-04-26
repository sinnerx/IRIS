<h3 class="m-b-xs text-black">
Public messages
</h3>
<div class='well well-sm'>
Listing public message.
</div>
<div class='row'>
<div class='col-sm-12'>
	<section class='panel panel-default'>
	<div class='panel-body'>
	<div class="table-responsive">
	<table class='table'>
		<tr>
			<th width='15px'>No.</th><th>Subject</th><th>From</th><th>Phone No.</th><th>Site</th><td>Date</td><th width='24px'></th>
		</tr>
		<?php
		if($res_message):
		$no = pagination::recordNo();
		foreach($res_message as $row)
		{
			$subject	= $row['messageSubject'];
			$from		= $row['contactName'];
			$phoneNo	= $row['contactPhoneNo'];
			$site		= $row['siteName'];
			$siteUrl	= url::base("site/edit/".$row['siteID']);
			$detailUrl	= url::base("site/messageView/".$row['siteMessageID']);
			$date		= date("d F Y, g:i A",strtotime($row['messageCreatedDate']));

			echo "<tr>";
			echo "<td>".$no++.".</td>";
			echo "<td>$subject</td>";
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
	</div>
	</section>
	<footer class='row'>
	<div class='col-sm-12'>
	<?php echo pagination::link();?>
	</div>
	</footer>
</div>
</div>