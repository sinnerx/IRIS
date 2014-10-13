<ul>
<?php foreach($res_threads as $row):
$photoUrl   = model::load("image/services")->getPhotoUrl($res_users[$row['forumThreadCreatedUser']]['userProfileAvatarPhoto']);
$cat_url= url::base("{site-slug}/forum/".$res_categories[$row['forumCategoryID']]['forumCategorySlug']);
$url	= $cat_url."/".$row['forumThreadID'];
$name	= $res_users[$row['forumThreadCreatedUser']]['userProfileFullName'];
$date	= dateRangeViewer($row['forumThreadCreatedDate'],1,"my");

?>
<li class="clearfix">
	<div class="forum-user-avatar"><img src="<?php echo $photoUrl;?>" alt=""/></div>
	<div class="forum-category-details">
	  <div class="forum-category-title"><a href="<?php echo $url;?>"><?php echo $row['forumThreadTitle'];?></a></div>
	  <div class="forum-category-info"> Oleh:  <a href="#"><?php echo $name;?></a>, <?php echo $date;?>, dalam <a href="<?php echo $cat_url;?>"><?php echo $res_categories[$row['forumCategoryID']]['forumCategoryTitle'];?></a></div>
	</div>
	<div class="forum-topic-count">
		<div class="count"><?php echo count($res_posts[$row['forumThreadID']])-1;?></div>
		<div class="count-label">komen</div>
	</div>
</li>
<?php endforeach;?>
</ul>