<script type="text/javascript">
var base_url	= "<?php echo url::base();?>/";
var user	= new function()
{
	this.index	= new function()
	{
	
		this.filter	= function()
		{
			var status	= $("#userStatus").val() != "" ? "&userStatus="+$("#userStatus").val() : "";
			var search	= $("#search").val() != ""?"&search="+$("#search").val():"";

			window.location.href	= base_url+"user/lists?"+status+search;
		}
	}
}

</script>
<h3 class="m-b-xs text-black">
<a href='info'>List of Users</a>
</h3>
<div class='well well-sm'>
List of all registered users, site managers and cluster leads.
</div>
<?php echo flash::data();?>
<div class='row'>
	<div class='col-sm-12' id='site-list'>
	<section class="panel panel-default">
	<div class="row wrapper" style='border-bottom:1px solid #f2f4f8;'>
		<form method="get" id='formSearch'>
		
		<div class="col-sm-3 pull-right">
		<div class="input-group">
		  <?php echo form::text('search', 'placeholder="Search" class="input-sm form-control"', request::get('search'));?>
		  <span class="input-group-btn">
		    <button class="btn btn-sm btn-default" onclick='$("#formSearch").submit();' type="button">Go!</button>

		  </span>
		</div>
		</div>
		<div class="col-sm-3 pull-right">			
		<?php echo form::select("userStatus",Array(1=>"Active", 3=>"NonActive"),"class='input-sm form-control' onchange='user.index.filter();'",request::get("userStatus"),"[All]");?>	
		</div>		
		</form>
		
		<div class='col-sm-3 pull-left'>
		
		<button type='button' class='class="btn btn-sm btn-bg btn-default pull-left'><a href='<?php echo url::base("user/add");?>'>Add +</a></button>
				</div>
	</div>
	<div class="table-responsive">
		<table id='table-site-list' class="table table-striped b-t b-light">
		<thead>
			<tr>
				<th width="20">No.</th>
				<th>Name</th>
				<th>I/C</th>
				<th>Email</th>
				<th class='site-col'>Level</th>
				<th width="60px"></th>
			</tr>
		</thead>
		<tbody>
		<?php

		if($users->count() > 0):
			$no	= pagination::recordNo();
			foreach($users as $user):
				
				$userID	= $user->userID;
				$name	= $user->userProfileFullName;
				$ic		= $user->userIC;
				$email	= $user->userEmail;
				$level	= $user->getLevelName();

				$detailIcon	= "<a class='fa fa-user' href='".url::base("user/detail/$userID")."'></a>";
				$editIcon	= "<a class='fa fa-edit' href='".url::base("user/edit/$userID")."'></a>";
				$deleteIcon = '<a onclick="return confirm(\'Are you really sure?\');" class="i i-cross2" href="'.url::base('user/delete/'.$userID).'"></a>';
				#$resetIcon	= "<a onclick='return confirm(\"Are you sure you want to reset this user password.?\");' class='fa fa-key'  href='".url::base("user/resetPassword/$userID")."'></a>";
				$resetIcon	= "";

				echo "<tr><td>$no.</td><td>$name</td><td>$ic</td><td>$email</td><td>$level</td><td>$resetIcon $editIcon $deleteIcon</td></tr>";
				$no++;
			endforeach;
		else:?>
		<tr>
			<td align="center" colspan="5">
				<?php if(request::get('search')):?>
					No user found with the search query.
				<?php else:?>
					No user added yet.
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