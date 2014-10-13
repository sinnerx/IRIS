<style type="text/css">
	
.search-list
{
	margin-bottom:20px;
}

.search-container > div
{
	border-bottom:1px solid #eeeeee;
	padding-bottom: 20px;
}

.search-list-head .search-list-title
{
	font-size:1.3em;
	color:#0062a1;
	font-weight: bold;
}

.search-list-title a
{
	color:#009bff;
}

.search-list-date
{
	font-size: 14px;
	opacity: 0.5;
}

.search-list-content
{
	font-size:14px;
	width:100%;
	display:table;
	padding:10px;
	padding-left:0px;
	padding-top:5px;
}

	/* image container */
	.search-list-content > div:nth-child(1)
	{
		display:table-cell;
		width:100px;
	}

	.search-list-content > div:nth-child(2)
	{
		display:table-cell;
		padding:10px;
		padding-top:0px;
		padding-left:0px;
		vertical-align:top;
	}

.search-filter
{
	position: relative;
	background: white;
	box-shadow: 0px 0px 10px #a2a2a2;
}
.search-filter-header
{
	padding:10px;
	padding-bottom: 5px;
	font-size: 1.1em;
}
.search-form > div input
{
	border:0px;
	border-bottom:1px solid #d2d2d2;
	font-size:1.1em;
	font-family: 'Lato', sans-sarif;
}
.search-form > div
{
	padding:10px;
}
.search-submit
{
	position: absolute;
	bottom:10px;
	right:10px;
}

.search-list-content > div
{
}

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
<div class='search-filter'>
	<div class='search-filter-header'>
		<u>Maklumat Carian</u>
	</div>
	<form onsubmit="return search.submit();" method='get' action="<?php echo url::base('{site-slug}/carian');?>" >
		<input id='search-type' name='jenis' type="hidden" />
		<div class='search-form'>
			<div>
				<b>Carian :</b>
				<input type='search' name='q' value='<?php echo request::get("q");?>' />
			</div>
			<div>
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
		<div class='search-submit'>
			<input type='submit' value='Cari' class='bttn-submit' style="color:white !important;" />
		</div>
	</form>
</div>
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
			<div class='search-list-date'><?php echo ucwords($row['type']);?> ditulis pada <?php echo $row['date'];?></div>
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
	<?php else:?>
		<div>
			Tiada carian dijumpai.
		</div>
	<?php endif;?>
	</div>
</div>