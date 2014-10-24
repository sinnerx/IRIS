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
<h3 class="block-heading">
<?php
echo model::load("template/frontend")->buildBreadCrumbs(Array(
                                          Array("Forum",url::base("{site-slug}/forum")),
                                          Array($row_category['forumCategoryTitle'],url::base("{site-slug}/forum/{category-slug}"))
                                                            ));
                                                            ?>
</h3>
<div class="block-content clearfix">
	<div class="page-content">
		<div class="page-description"> 
		<?php echo $row_category['forumCategoryDescription'];?>
		</div>
		<div class="page-sub-wrapper forum-page">
		<div class="forum-header clearfix">
			<a href="<?php echo url::base("{site-slug}/forum");?>" >Kategori</a>
			<a href="#" class="active-cat">Topic</a> 
			<a href='<?php echo url::base("{site-slug}/forum/{category-slug}/topik-baru");?>' class='new-topic-icon'>Buka Topik Baru</a>
		</div>
			<?php
			if($res_threads):?>
			<div class="forum-container">
				<ul>
			<?php foreach($res_threads as $row):
			$name	= $res_users[$row['forumThreadCreatedUser']]['userProfileFullName'];
			$date	= dateRangeViewer($row['forumThreadCreatedDate'],1,"my");
			$url	= url::base("{site-slug}/forum/{category-slug}/".$row['forumThreadID']);
			$photoUrl   = model::load("image/services")->getPhotoUrl($res_users[$row['forumThreadCreatedUser']]['userProfileAvatarPhoto']);

			$profileHref	= url::createByRoute('api-redirect-general',Array('type'=>'profile'),true)."?user=".$row['forumThreadCreatedUser'];
			?>
			<li class="clearfix">
			<div class="forum-user-avatar"><img src="<?php echo $photoUrl;?>" alt=""/></div>
				<div class="forum-category-details">
				  <div class="forum-category-title"><a href="<?php echo $url;?>"><?php echo $row['forumThreadTitle'];?></a></div>
				  <div class="forum-category-info"> Oleh:  <a href="<?php echo $profileHref;?>"><?php echo $name;?></a>, <?php echo $date;?>, dalam <a href="#"><?php echo $row_category['forumCategoryTitle'];?></a></div>
				</div>
				<div class="forum-topic-count">
					<div class="count"><?php echo count($res_posts[$row['forumThreadID']])-1;?></div>
					<div class="count-label">komen</div>
				</div>
			</li>
			<?php endforeach;?>
				</ul>
			</div>
			<?php else:?>
			<div class='no-topic'>
			Tiada Topik lagi untuk kategori ini.
			</div>
			<?php endif;?>
		</div>
	</div>
</div>