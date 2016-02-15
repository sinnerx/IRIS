<style type="text/css">
	
.no-comment
{
	padding:10px;
	margin-bottom: 30px;
}

</style>
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

</script>
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
			<?php
			$userHref	=  url::route("api-redirect-general",Array("type"=>"profile"),true)."?user=".$firstPost['forumThreadPostCreatedUser'];

			?>
				<div class="forum-foto-user-avatar">
					<?php
					$photoUrl   = model::load("image/services")->getPhotoUrl($res_users[$firstPost['forumThreadPostCreatedUser']]['userProfileAvatarPhoto']);?>
					<img src="<?php echo $photoUrl;?>" alt=""/></div>
				<div class="forum-post-content">
				<div class="forum-post-header">
				<div class="forum-post-title"><?php echo $row_thread['forumThreadTitle'];?></div>
				<div class="forum-post-info">Oleh:<a href="<?php echo $userHref;?>"> <?php echo $res_users[$firstPost['forumThreadPostCreatedUser']]['userProfileFullName'];?></a>,  <?php echo dateRangeViewer($firstPost['forumThreadPostCreatedDate'],1,"my");?>,  dalam  <a href="#"><?php echo $row_category['forumCategoryTitle'];?></a>.</div>
				</div>
				<div class="forum-post-details">
				<?php echo nl2br($firstPost['forumThreadPostBody']);?>
				</div>
				</div>
			</div>

					<!-- POSTS -->
			<div class="forum-post-comment">
				<div class="forum-post-comment-count">RESPON <span>(<?php echo count($res_posts);?>)</span></div>
				<div class="forum-post-comment-content">
				<?php if($res_posts):?>
				<ul>
					<?php
					$no = 0;
					foreach($res_posts as $row):
					$user	= $res_users[$row['forumThreadPostCreatedUser']];
					$photoUrl	= model::load("image/services")->getPhotoUrl($user['userProfileAvatarPhoto'],"avatar_icon.jpg");
					$date		= dateRangeViewer($row['forumThreadPostCreatedDate'],1,"my");
					$href		= url::route("api-redirect-general",Array("type"=>"profile"),true)."?user=".$user['userID'];
					?>
					<li class="clearfix">
					<div class="forum-post-comment-avatar"> <img src="<?php echo $photoUrl;?>" alt=""/> </div>
					<div class="forum-post-comment-message">
					<div class="forum-post-comment-info"><a href='<?php echo $href;?>'><?php echo $user['userProfileFullName'];?></a>
					<div class="comment-post-date"><i class="fa fa-clock-o"></i><?php echo $date;?></div></div>
					<?php echo nl2br($row['forumThreadPostBody']);?>
					</div>
					</li>
					<?php endforeach;?>
				</ul>
				<?php else:?>
					<div class='no-comment'>Tiada respon lagi untuk topik ini.</div>
				<?php endif;?>
				<!-- NEW POSTS -->
				<?php echo pagination::link();?>
				<div class="forum-comment-form">
					<form method='post'>
						<?php 
						$myavatar	= model::load("image/services")->getPhotoUrl(authData("user.userProfileAvatarPhoto"),"avatar_icon.jpg");
						?>
						<div class="comment-user-avatar" style="background:url('<?php echo $myavatar;?>');border-radius:100%;"></div>
						<div class="comment-post-input">
						<h3><?php echo authData("user.userProfileFullName");?></h3>
						<div class="comment-text-input" style="position:relative;">
						<div class="comment-text-input-arrow"></div>
							<textarea placeholder='Taipkan komen anda di sini...' name='forumThreadPostBody' id='forumThreadPostBody' onkeyup='wordcount();'></textarea>
							<div style="width:300px;font-size:0.9em;">Jumlah karakter : <span class='char-count'>0</span>/6000</div>
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