<section class='panel panel-default'>
	<div class='panel-heading'>
	Latest Contact Message
	</div>
	<div class='panel-body'>
		<div class='table-responsive'>
		<table class='table'>
			<tr>
				<th width='15px'>No.</th><th>Category</th><th>Subject</th><th>From</th><th style='text-align:right;'></th>
			</tr>
			<?php
			if($res_message)
			{
				$no = pagination::recordNo();
				foreach($res_message as $row)
				{
					$subject	= $row['messageSubject'];
					$refNo	= $siteMessage->encryptID($row['siteMessageID']);
					$url	= url::base("site/messageView/$refNo");
					$from	= $row['contactName']." (".$row['contactEmail'].")";
					$cat	= $siteMessage->getCategoryName($row['siteMessageCategory']);

					echo "<tr><td>$refNo</td><td>$cat</td><td>$subject</td><td>$from</td><td><a href='$url' class='fa fa-search pull-right'></a></td></tr>";
				}
			}
			else
			{
				echo "<tr><td colspan='3'>No message yet.</td></tr>";
			}
			?>
		</table>
		</div>
	<footer class='pagination-numlink'>
		<?php echo pagination::link();?>
	</footer>
	</div>
</section>