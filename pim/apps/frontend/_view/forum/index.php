<style type="text/css">
	
.no-topic
{
	padding:20px 0 0 0px;

}

.new-topic-icon
{
	background:#c64f4f;
	color:white !important;
	float:right !important;
}

.new-topic-icon:hover
{
	background: #c64f4f !important;
}

</style>
<script type="text/javascript" src='<?php echo url::asset("frontend/js/grapnel.js");?>'></script>
<script type="text/javascript">
// use Grapnel js. simple router.
var router = new Grapnel();

jQuery(document).ready(function()
{
	router.get(":page",function(req)
	{
		var $ = jQuery;
		$(".forum-container").hide();

		$(".forum-header a").removeClass("active-cat");
		$($(".forum-header a")[{kategori:1,terkini:0}[req.params.page]]).addClass("active-cat");
		switch(req.params.page)
		{
			// load latest topik
			case "terkini":
			$(".forum-container.topic").load("ajax/forum/getLatestTopic").fadeIn();

			break;
			case "kategori":
			$(".forum-container.category").load("ajax/forum/getCategories").fadeIn();
			// $(".forum-container.category").fadeIn();
			break;
		}

	});

	if(window.location.hash == "")
	{
		window.location.hash = "terkini";
	}
});

</script>
<h3 class="block-heading">
<?php
echo model::load("template/frontend")->buildBreadCrumbs(Array(
                                          Array("Forum",url::base("{site-slug}/forum"))))
                                                            ?>
</h3>
<div class="block-content clearfix">
	<div class="page-content">
		<div class="page-description">
		Forum perbincangan bersama ahli-ahli laman ini.
		</div>
		<div class="page-sub-wrapper forum-page">
		<div class="forum-header clearfix">
		<a href="#terkini">Topik Terkini</a>
		<a href="#kategori" class="active-cat">Kategori</a>
		<a href='<?php echo url::createByRoute("forum-new-thread1",null,true);?>' class='new-topic-icon'>Buka Topik Baru</a>
		</div>
			<div class="forum-container category">
			</div>
			<div class='forum-container topic'>
			</div>
		</div>
	</div>
</div>
<div id='comment-box'></div>