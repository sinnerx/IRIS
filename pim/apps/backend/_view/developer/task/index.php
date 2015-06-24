<h3 class="m-b-xs text-black">
<a href='info'>Developer Tasks</a>
</h3>
<div class='well well-sm'>
Do some defined task.
</div>
<div class="table-responsive">
	<table id='table-site-list' class="table table-striped b-t b-light">
	<thead>
		<tr>
			<th width="20">No.</th>
			<th>Task Code</th>
			<th>Description</th>
			<th>Status</th>
			<th width="60px"></th>
		</tr>
	</thead>
	<tbody>
		<?php
		$no = 1;
		foreach($tasks as $code => $struct):
		$description = $struct['description'];
		$repeatable = $struct['repeatable'];
		$status = model::load('developer/task')->getStatus($code);

		$status = $repeatable ? ($status === 0 ? 'ready' : 'executed ('.$status.')' ) : $status ;
		?>
		<tr>
			<td><?php echo $no++;?>.</td>
			<td><?php echo $code;?> <?php if($repeatable):?>(repeatable)<?php endif;?></td>
			<td><?php echo $description;?></td>
			<td><?php echo $status;?></td>
			<td><?php if($repeatable || (!$repeatable && $status == 'ready')):?><a onclick="return confirm('Execute this task?');" href='<?php echo url::base('task/run/'.$code);?>'>Execute</a><?php endif;?></td>
		</tr>
		<?php endforeach;?>
	</tbody>
</div>