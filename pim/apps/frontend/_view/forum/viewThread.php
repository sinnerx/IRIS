<style type="text/css">
	
.no-comment
{
	padding:10px;
	margin-bottom: 30px;
}

</style>
<h3 class="block-heading"><?php
echo model::load("template/frontend")->buildBreadCrumbs(Array(
                                          Array("Forum",url::base("{site-slug}/forum")),
                                          Array($row_category['forumCategoryTitle'],url::base("{site-slug}/forum/{category-slug}")),
                                          Array("TOPIK")
                                                            ));

 ?></h3>
<div class="block-content clearfix">
	<div class="page-content">
		<div class="page-description"> 
		</div>
		<div class="page-sub-wrapper forum-page">
			<div class="forum-post clearfix">
				<div class="forum-foto-user-avatar">
					<?php
					$photoUrl   = model::load("image/services")->getPhotoUrl($res_users[$res_posts[0]['forumThreadPostCreatedUser']]['userProfileAvatarPhoto']);?>
					<img src="<?php echo $photoUrl;?>" alt=""/></div>
				<div class="forum-post-content">
				<div class="forum-post-header">
				<div class="forum-post-title"><?php echo $row_thread['forumThreadTitle'];?></div>
				<div class="forum-post-info">Oleh:<a href="#"> <?php echo $res_users[$res_posts[0]['forumThreadPostCreatedUser']]['userProfileFullName'];?></a>,  <?php echo dateRangeViewer($res_posts[0]['forumThreadPostCreatedDate'],1,"my");?>,  dalam  <a href="#"><?php echo $row_category['forumCategoryTitle'];?></a>.</div>
				</div>
				<div class="forum-post-details">
				<?php echo $res_posts[0]['forumThreadPostBody'];?>
				</div>
				</div>
			</div>

					<!-- POSTS -->
			<div class="forum-post-comment">
				<div class="forum-post-comment-count">KOMEN <span>(<?php echo count($res_posts)-1;?>)</span></div>
				<div class="forum-post-comment-content">
				<?php if($res_posts):?>
				<ul>
					<?php
					$no = 0;
					foreach($res_posts as $row):
					$no++;
					if($no == 1)
						continue;

					$user	= $res_users[$row['forumThreadPostCreatedUser']];
					$photoUrl	= model::load("image/services")->getPhotoUrl($user['userProfileAvatarPhoto'],"avatar_icon.jpg");
					?>
					<li class="clearfix">
					<div class="forum-post-comment-avatar"> <img src="<?php echo $photoUrl;?>" alt=""/> </div>
					<div class="forum-post-comment-message">
					<div class="forum-post-comment-info"><?php echo $user['userProfileFullName'];?>
					<div class="comment-post-date"><i class="fa fa-clock-o"></i>  2 Jam Lalu</div></div>
					<?php echo $row['forumThreadPostBody'];?>
					</div>
					</li>
					<?php endforeach;?>
				</ul>
				<?php else:?>
					<div class='no-comment'>Tiada komen lagi untuk topik ini.</div>
				<?php endif;?>
				<!-- NEW POSTS -->
				<div class="forum-comment-form">
					<form method='post'>
						<?php 
						$myavatar	= model::load("image/services")->getPhotoUrl(authData("user.userProfileAvatarPhoto"),"avatar_icon.jpg");
						?>
						<div class="comment-user-avatar" style="background:url('<?php echo $myavatar;?>');border-radius:100%;"></div>
						<div class="comment-post-input">
						<h3><?php echo authData("user.userProfileFullName");?></h3>
						<div class="comment-text-input">
						<div class="comment-text-input-arrow"></div>
						<textarea placeholder='Taipkan komen anda di sini...' name='forumThreadPostBody'></textarea>
						<input type="submit" value="Hantar" class="bttn-submit">
						</div>
						</div>
					</form>
				</div>
				</div>
			</div>
		</div>
	</div>
</div>