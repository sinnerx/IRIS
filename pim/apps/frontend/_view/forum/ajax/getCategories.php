<ul>
	<?php
	if($res_forum_category):?>
	<?php 
	foreach($res_forum_category as $row):
	if($row['forumCategoryAccess'] == 2 && !authData('current_site.isMember'))
		continue;
	
	$title				= $row['forumCategoryTitle'];
	$desc				= $row['forumCategoryDescription'];
	$latestTopic	= "";
	$slug				= $row['forumCategorySlug'];

	## url
	$url				= url::base("{site-slug}/forum/$slug");

	## if there're topic in this category.
	if(isset($res_forum_thread[$row['forumCategoryID']]))
	{
		$row_latesttopic	= $res_forum_thread[$row['forumCategoryID']][0];
		$latestTopicUrl		= url::base("{site-slug}/forum/$slug/$row_latesttopic[forumThreadID]");
		$latestTopic		= "<a href='$latestTopicUrl'>".$row_latesttopic['forumThreadTitle']."</a>";
		$topicCount			= count($res_forum_thread[$row['forumCategoryID']]);

		$latestTopicDate	= dateRangeViewer($row_latesttopic['forumThreadCreatedDate'],1,"my");
	}
	else
	{
		$latestTopic	= "Tiada topik terkini.";
		$topicCount			= 0;
		$latestTopicDate	= null;
	}
	$topicCount			= isset($res_forum_thread[$row['forumCategoryID']])?count($res_forum_thread[$row['forumCategoryID']]):0;
		?>
	<li class="clearfix">
		<div class="forum-category-details">
			<div class="forum-category-title"><a href="<?php echo $url;?>"><?php echo $title;?></a></div>
			<div class="forum-category-info"> Terkini:  <?php echo $latestTopic;?>, <i class="fa fa-user"></i> <?php echo $latestTopicDate;?>.</div>
		</div>
		<div class="forum-topic-count">
			<div class="count"><?php echo $topicCount;?></div>
			<div class="count-label">topik</div>
		</div>
	</li>
	<?php
	endforeach;
	else:?>
	<div style="padding-bottom:10px;">
		Tiada kategori lagi untuk paparan.
	</div>
	<?php endif;?>
</ul>