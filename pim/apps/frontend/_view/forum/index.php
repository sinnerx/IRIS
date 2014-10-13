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
		Lorem ipsum dolor sit amet, maiores ornare ac fermentum, imperdiet ut vivamus a, nam lectus at nunc. Quam euismod sem, semper ut potenti pellentesque quisque. In eget sapien sed, sit duis vestibulum ultricies, placerat morbi amet vel, nullam in in lorem vel. In molestie elit dui dictum, praesent nascetur pulvinar sed, in dolor pede in aliquam, risus nec error quis pharetra. Eros metus quam augue suspendisse, metus rutrum risus erat in.  In ultrices quo ut lectus, etiam vestibulum urna a est, pretium luctus euismod nisl, pellentesque turpis hac ridiculus massa. Venenatis a taciti dolor platea, curabitur lorem platea urna odio.
		</div>
		<div class="page-sub-wrapper forum-page">
		<div class="forum-header clearfix">
		<a href="#terkini">Topik Terkini</a>
		<a href="#kategori" class="active-cat">Kategori</a>
		</div>
			<div class="forum-container category">
			</div>
			<div class='forum-container topic'>
			</div>
		</div>
	</div>
</div>
<div id='comment-box'></div>