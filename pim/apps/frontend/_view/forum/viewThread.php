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
				<div class="forum-post-comment-count">KOMEN <span>(<?php echo count($res_post);?>)</span></div>
				<div class="forum-post-comment-content">
				<?php if($res_post):?>
				<ul>
					<li class="clearfix">
					<div class="forum-post-comment-avatar"> <img src="members_photo/1538817_10202447454481584_450404680_n.jpg" alt=""/> </div>
					<div class="forum-post-comment-message">
					<div class="forum-post-comment-info">Mohd Hafiz
					<div class="comment-post-date"><i class="fa fa-clock-o"></i>  2 Jam Lalu</div></div>
					Lorem ipsum dolor sit amet, maiores ornare ac fermentum, imperdiet ut vivamus a, nam lectus at nunc. Quam euismod sem, semper ut potenti pellentesque quisque. In eget sapien sed, sit duis vestibulum ultricies, placerat morbi amet vel, nullam in in lorem vel. In molestie elit dui dictum, praesent nascetur pulvinar sed.
					</div>
					</li>
				</ul>
				<?php else:?>
					<div class='no-comment'>Tiada komen lagi untuk topik ini.</div>
				<?php endif;?>
				<!-- NEW POSTS -->
				<div class="forum-comment-form">
					<div class="comment-user-avatar"></div>
					<div class="comment-post-input">
					<h3><?php echo authData("user.userProfileFullName");?></h3>
					<div class="comment-text-input">
					<div class="comment-text-input-arrow"></div>
					<textarea placeholder='Taipkan komen anda di sini...'></textarea>
					<input type="submit" value="Hantar" class="bttn-submit">
					</div>
					</div>
				</div>
				</div>
			</div>
		</div>
	</div>
</div>