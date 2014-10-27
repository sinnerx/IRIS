<style type="text/css">
	
</style>
<script type="text/javascript">

var search = new function($)
{
	this.submit = function()
	{
		// prepare type.
		var type = [];
		var all  = true
		$(".search-filter-type").each(function(i,e)
		{
			if(!e.checked)
			{
				all = false
			}
			else
			{
				type.push(e.value);
			}

		});

		if(all)
		{
			$("#search-type").remove();
		}
		$("#search-type").val(all?"all":type.join("-"));
	}
}(jQuery)

</script>
<h3 class="block-heading">
<?php
echo model::load("template/frontend")->buildBreadCrumbs(Array(
                                          Array("Carian",url::base("{site-slug}/carian")),
                                                            ));?>
</h3>

<!-- <div class="search-filter">
	<div class="search-filter-header">
		Maklumat Carian
        <i class="fa fa-search"></i>
	</div>
	<form action="http://dev.celcom1cbc.com/felda-sg-kelamah/carian" method="get" onsubmit="return search.submit();">
		<input type="hidden" name="jenis" id="search-type">
		<div class="search-form">
			<div class="clearfix search-top-form">
				<b>Carian :</b>
				<input type="search" value="blog" name="q" class="search-area">
                <div class="search-submit">
			<input type="submit" style="color:white !important;" class="bttn-submit" value="Cari">
		</div>
			</div>
			<div class="search-bottom-form clearfix">
				<b>Jenis :</b>
					<label><input type="checkbox" checked="" value="blog" class="search-filter-type"> blog</label>
					<label><input type="checkbox" checked="" value="activity" class="search-filter-type"> activity</label>
					<label><input type="checkbox" checked="" value="video" class="search-filter-type"> video</label>
					<label><input type="checkbox" checked="" value="gallery" class="search-filter-type"> gallery</label>
					<label><input type="checkbox" checked="" value="page" class="search-filter-type"> page</label>
					<label><input type="checkbox" checked="" value="forum" class="search-filter-type"> forum</label>
				</div>
		</div>
	</form>
</div> -->
<div class="search-filter">
	<div class="search-filter-header">
		Maklumat Carian
        <i class="fa fa-search"></i>
	</div>
	<form action="<?php echo url::base('{site-slug}/carian');?>" method="get" onsubmit="return search.submit();">
		<input type="hidden" name="jenis" id="search-type">
		<div class="search-form">
			<div class="clearfix search-top-form">
				<b>Carian :</b>
				<input type="search" value="<?php echo request::get("q");?>" name="q" class="search-area">
                <div class="search-submit">
			<input type="submit" style="color:white !important;" class="bttn-submit" value="Cari">
		</div>
			</div>
			<div class="search-bottom-form clearfix">
				<b>Jenis :</b>
					<?php
					$typeR	= model::load("localization/frontend")->getText("user_activity");
					foreach($filter as $type):?>
					<?php
					$checked	= in_array($type, $selectedType)?"checked":"";
					?>
						<label><input class='search-filter-type' value='<?php echo $type;?>' type='checkbox' <?php echo $checked;?> /> <?php echo ucwords($typeR[$type]?:$type);?></label>
					<?php
					endforeach;
					?>
			</div>
		</div>
	</form>
</div>
<!-- <div class='search-filter'>
	<div class='search-filter-header'>
		<u>Maklumat Carian</u>
		<i class="fa fa-search"></i>
	</div>
	<form onsubmit="return search.submit();" method='get' action="<?php echo url::base('{site-slug}/carian');?>" >
		<input id='search-type' name='jenis' type="hidden" />
		<div class='search-form'>
			<div>
				<b>Carian :</b>
				<input type='search' name='q' class="search-area" value='<?php echo request::get("q");?>' />
				<div class='search-submit'>
					<input type='submit' value='Cari' class='bttn-submit' style="color:white !important;" />
				</div>
			</div>
			<div class="search-bottom-form clearfix">
				<b>Jenis :</b>
			<?php
			foreach($filter as $type):?>
			<?php
			$checked	= in_array($type, $selectedType)?"checked":"";
			?>
				<label><input class='search-filter-type' value='<?php echo $type;?>' type='checkbox' <?php echo $checked;?> /> <?php echo $type;?></label>
			<?php
			endforeach;
			?>
			</div>

		</div>
	</form>
</div> -->
<div>
	<h3>
		Hasil Carian (<?php echo $totalResult;?>):
	</h3>
	<div class='search-container'>
	<?php if($result):?>
	<?php foreach($result as $row):?>
	<div class='search-list'>
		<div class='search-list-head'>
			<span class='search-list-title'><a href='<?php echo $row['url'];?>'><?php echo $row['title'];?></a></span>
		</div>
		<div class='search-list-body'>
			<div class='search-list-date'><?php echo ucwords($typeR[$row['type']]?:$row['type']);?> ditulis pada <?php echo $row['date'];?></div>
			<div class='search-list-content'>
			<?php if($row['img']):?>
			<div>
				<img src="<?php echo $row['img'];?>" style='width:95%;'>
			</div>
			<?php endif;?>
			<div>
				<?php echo $row['body'];?>
			</div>
			</div>
		</div>
	</div>
	<?php endforeach;?>
	<?php echo pagination::link();?>
	<?php else:?>
		<div>
			Tiada carian dijumpai.
		</div>
	<?php endif;?>
	</div>
</div>