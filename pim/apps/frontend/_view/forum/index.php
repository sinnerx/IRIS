<script type="text/javascript">

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
		<a href="#" class="active-cat">Kategori</a>
		</div>
			<div class="forum-container">
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


					<?php endif;?>
					
				</ul>
			</div>
		</div>
	</div>
</div>
<div id='comment-box'></div>