<script type="text/javascript">	
var comment	= new function()
{
	this.refID = <?php echo $refID; ?>;
	this.commentType = '<?php echo $type; ?>';
	this.add = function()
	{
		var body = jQuery('#body').val();
		var data = {commentBody:body,commentType:this.commentType,commentRefID:this.refID};

		jQuery.ajax({type:"POST",data:data,url:"<?php echo url::base('{site-slug}/comment/addComment/');?>"}).done(function(desc)
		{
				jQuery('#body').val("Taipkan komen anda di sini...");
				comment.get();
		});
	}
	this.get = function()
	{
		jQuery('#comment-container').animate({"opacity":"0.3"},"slow");
		jQuery.ajax({type:"GET",url:"<?php echo url::base('{site-slug}/comment/getComments/');?>"+this.refID+"/"+this.commentType}).done(function(response)
		{
			if(response)
			{
				jQuery("#comment-container").html("");
				jQuery("#comment-container").html(response);
			}
		});
		jQuery('#comment-container').animate({"opacity":"1"},"slow");
	}
}

setTimeout(function(){comment.get()}, 60000);
</script>
					<div class="forum-post-comment">
						<div class="forum-post-comment-count">KOMEN <span>(<?php echo count($comments); ?>)</span></div>
							<div class="forum-post-comment-content">
								<ul>
								<?php if(count($comments) > 0): ?>
									<?php foreach($comments as $comment): ?>
									<li class="clearfix">
										<div class="forum-post-comment-avatar">
										<?php $photoUrl = model::load("image/services")->getPhotoUrl($comment['userProfileAvatarPhoto']); ?>
											<img src="<?php echo $photoUrl; ?>" alt=""/>
										</div>
										<div class="forum-post-comment-message">
											<div class="forum-post-comment-info">  <?php echo $comment['userProfileFullName']; ?>
					  							<div class="comment-post-date">
					  								<i class="fa fa-clock-o"></i>  <?php echo dateRangeViewer($comment['commentCreatedDate'],1,'my'); ?>
					  							</div>
					  						</div>
					  						<?php echo $comment['commentBody']; ?>
										</div>
									</li>
									<?php endforeach ?>
								<?php endif; ?>
								</ul>
							</div>
					</div>