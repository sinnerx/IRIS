<h3 class="m-b-xs text-black">
<a href='info'>Report Dashboard</a>
</h3>
<div class='well well-sm'>
List of reports.
</div>
<?php echo flash::data();?>
<div class='row'>
	<div class='col-sm-12' id='site-list'>
	<section class="panel panel-default">
	<div class="row wrapper" style='border-bottom:1px solid #f2f4f8;'>
		<div class="col-sm-3 pull-right">
		<form method="get" id='formSearch'>
		<div class="input-group">
		  <?php echo form::text('search', 'placeholder="Search" class="input-sm form-control"', request::get('search'));?>
		  <span class="input-group-btn">
		    <button class="btn btn-sm btn-default" onclick='$("#formSearch").submit();' type="button">Go!</button>
		  </span>
	  	
		</div>
		</form>
		</div>
	</div>
	<div class="table-responsive">
		<table id='table-site-list' class="table table-striped b-t b-light">
		<thead>
			<tr>
				<th width="20">No.</th>
				<th>Report</th>
				<th>Description</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody>
		<?php
		//var_dump(($reports));
		//die;
		if(count($reports) > 0):
			$no	= pagination::recordNo();

			foreach($reports as $report):
				//var_dump($report['reportsID']);
				$reportID	= $report['reportsID'];
				$reportName	= $report['reportsName'];
				$reportDesc		= $report['reportsDesc'];

				if($report['reportsURL'] == "")
					$detailIcon	= "<a class='fa fa-user' href='".url::base("report/reportFormField/$reportID")."'></a>";
				else
					$detailIcon	= "<a class='fa fa-user' href='".url::base($report['reportsURL'])."'></a>";
				$editIcon	= "<a class='fa fa-edit' href='".url::base("user/edit/$userID")."'></a>";
				$deleteIcon = '<a onclick="return confirm(\'Are you really sure?\');" class="i i-cross2" href="'.url::base('user/delete/'.$userID).'"></a>';
				#$resetIcon	= "<a onclick='return confirm(\"Are you sure you want to reset this user password.?\");' class='fa fa-key'  href='".url::base("user/resetPassword/$userID")."'></a>";
				$resetIcon	= "";

				echo "<tr><td>$no.</td><td>$reportName</td><td>$reportDesc</td><td>$detailIcon</td></tr>";
				$no++;
			endforeach;
		else:?>
		<tr>
			<td align="center" colspan="5">
				<?php if(request::get('search')):?>
					No report found with the search query.
				<?php else:?>
					No report added yet.
				<?php endif;?>
			</td>
		</tr>
		<?php
		endif;
		?>
		</tbody>
		</table>
	</div>
	<footer class='panel-footer'>
	<div class='row'>
		<div class='col-sm-12'>
			<?php echo pagination::link();?>
		</div>
	</div>
	</footer>
	</section>
	</div>
</div>