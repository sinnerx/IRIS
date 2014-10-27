<style type="text/css">

.new-topic-wrapper
{
	width:670px;
}
.new-topic-title input
{
	width:99%;
	padding:5px;
	border:1px solid #cacaca;
}
.new-topic-body textarea
{
	height:200px;
	width: 100%;
	border:1px solid #cacaca;
}
.new-topic-footer input
{
	float:right;
	background:#009bff;
	border:0px;
	padding:5px;
	color:white !important;
	font-size: 1.1em;
}
.new-topic-body
{
	padding-top:10px;
}
.new-topic-footer
{
	padding-top:5px;
}
.new-topic-label
{
	padding:5px;
	font-weight: bold;
}

/* error */
.alert.alert-danger
{
	background: #db2424;
	color:white;
	padding:5px;
	padding-bottom:5px;	
}
.span-error
{
	color:red;
}
</style>
<h3 class="block-heading">
<?php
$breadCrumbs[]	= Array("Forum",url::base("{site-slug}/forum"));
if($withoutCategory)
{
	$breadCrumbs[]	= Array("TOPIK BARU");
}
else
{
	$breadCrumbs[]	= Array($row_category['forumCategoryTitle'],url::base("{site-slug}/forum/{category-slug}"));
	$breadCrumbs[]	= Array("BARU");
}


echo model::load("template/frontend")->buildBreadCrumbs($breadCrumbs);
                                                            ?>
</h3>
<div class="block-content clearfix">
<form method='post'>
	<div class="page-content">
		<div class="page-description">
			Buka topik baru tentang <u><?php echo $row_category['forumCategoryTitle'];?></u>
		</div>
		<?php echo flash::data();?>
		<div class='new-topic-wrapper'>
			<div class='new-topic-label'>Tajuk <?php echo flash::data("forumThreadTitle");?></div>
			<div class="new-topic-title">
				<?php echo form::text("forumThreadTitle");?>
			</div>
			<div class='new-topic-label'>Kategori <?php echo flash::data("forumCategoryID");?></div>
			<div class='new-topic-category-body'>
				<?php echo form::select("forumCategoryID",$categories,!$withoutCategory?"disabled":"",$row_category['forumCategoryID']);?>
			</div>
			<div class='new-topic-body'>
			<div class='new-topic-label'>Isi Kandungan <?php echo flash::data("forumThreadPostBody");?></div>
				<?php echo form::textarea("forumThreadPostBody","onkeyup='wordcount();'");?>
				<div style="position:absolute;font-size:0.9em;">Jumlah karakter : <span class='char-count'>0</span>/6000</div>
			</div>
		</div>
		<div class="new-topic-footer">
		<input type="submit" value='Buka Topik Baru'>
		</div>
	</div>
</form>
</div>
<script type="text/javascript">
	
function wordcount()
{
	var maxlength	= 6000;
	var text	= jQuery("#forumThreadPostBody").val();

	if(text.length > maxlength)
	{
		alert("Telah melebihi jumlah perkataan.");
		jQuery("#forumThreadPostBody").val(text.substring(0,maxlength));
	}


	jQuery(".char-count").html(jQuery("#forumThreadPostBody").val().length);
}

wordcount();
</script>