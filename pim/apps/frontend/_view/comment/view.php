<link rel="stylesheet" type="text/css" href="<?php echo url::asset("_scale/css/icon.css"); ?>" media="all" />
<script type="text/javascript">	
var comment	= new function()
{
	this.page = <?php echo $page; ?>;
	this.refID = <?php echo $refID; ?>;
	this.commentType = '<?php echo $type; ?>';
	this.add = function()
	{
		var body = jQuery('#body').val();
		var data = {commentBody:body,commentType:this.commentType,commentRefID:this.refID};

		jQuery.ajax({type:"POST",data:data,url:"<?php echo url::base('{site-slug}/comment/addComment');?>?type="+this.commentType+"&refID="+this.refID}).done(function(resp)
		{
			if(resp == 'false')
			{
				jQuery("#comment-alert").attr('style','display:block;');
			}
			else
			{
				var response = jQuery.parseJSON(resp);

				jQuery("#comment-alert").attr('style','display:none;');
				jQuery('#body').val("");
				jQuery(".forum-post-comment-count").html('KOMEN <span>('+response[1]+')</span>');
				comment.get(response[0]);
			}
		});
	}
	this.get = function(id)
	{
		jQuery('#comment-container').animate({"opacity":"0.3"},"slow");
		jQuery.ajax({type:"GET",url:"<?php echo url::base('{site-slug}/comment/getComment/');?>"+id}).done(function(response)
		{
			if(response)
			{
				jQuery(".forum-post-comment-content ul").append(response);
			}
		});
		jQuery('#comment-container').animate({"opacity":"1"},"slow");
	}
	this.getMore = function(refresh)
	{
		if(this.page > 1 && this.page != 0 || refresh)
		{
			jQuery('#get-older').attr("style","display:block;");

			if(this.page == 2)
			{
				jQuery('#get-older').attr("style","display:none;");
			}

			refresh = refresh == 1?refresh:0;
			this.page--;
			jQuery('#comment-container').animate({"opacity":"0.3"},"slow");
			jQuery.ajax({type:"GET",url:"<?php echo url::base('{site-slug}/comment/getComments/');?>"+this.refID+"/"+this.commentType+"?page="+this.page+"&more=1&refresh="+refresh}).done(function(response)
			{
				if(response)
				{
					if(refresh == 1)
					{
						jQuery("#comment-container").html("");
						jQuery("#comment-container").html(response);
					}
					else
					{
						jQuery(".forum-post-comment-content ul").prepend(response);
					}
				}
			});
			jQuery('#comment-container').animate({"opacity":"1"},"slow");
		}
	}
	this.delete = function(obj)
	{
		var id = jQuery(obj).data("id");

		jQuery.ajax({type:"GET",url:"<?php echo url::base('{site-slug}/comment/disableComment/'); ?>"+id+"?type="+this.commentType+"&refID="+this.refID}).done(function(response)
		{
			if(response)
			{
				jQuery(obj).parent().animate({width:'toggle'},350);
				jQuery(".forum-post-comment-count").html('KOMEN <span>('+response+')</span>');
			}
			else
			{
				alert("there is an error on delete!!!");
			}

		});
	}
}
setTimeout(function(){comment.getMore(1)}, 300000);

</script>
<style type="text/css">
	article {
	    margin-bottom: 3rem;
	    position: relative;
	    *zoom: 1;
	}

	article:before, article:after {
	    content: "";
	    display: table;
	}

	article:after { clear: both }

	input[type=checkbox] {
	    border: 0;
	    clip: rect(0 0 0 0);
	    height: 1px;
	    width: 1px;
	    margin: -1px;
	    overflow: hidden;
	    padding: 0;
	    position: absolute;
	}

	[for="read_more"] {
	    position: absolute;
	    bottom: -3rem;
	    left: 0;
	    width: 100%;
	    text-align: center;
	    padding: .65rem;
	    box-shadow: inset 1px 1px rgba(0, 0, 0, 0.1), inset -1px -1px rgba(0, 0, 0, 0.1);
	}

	[for="read_more"]:hover {
	    background: rgba(0,0,0,.5);
	    color: rgb(255,255,255);
	}

	a.i
	{
		cursor: pointer;
	}
</style>
<div class="forum-post-comment">
	<div class="forum-post-comment-count">KOMEN <span>(<?php echo $count; ?>)</span></div>
	<?php if($limit != $count && $count != 0 && $count > $limit): ?>
	<article id="get-older" style="">
		<input type="checkbox" id="read_more" role="button">
	    <label for="read_more" onclick="javascript:comment.getMore();"><span>Read Older Comment</span></label>  
	</article>
	<br/>
    <?php endif; ?>
	<div class="forum-post-comment-content">
		<ul>
		<?php if(count($comments) > 0): ?>
			<?php foreach($comments as $comment):
			$profileHref	= url::createByRoute("api-redirect-general",Array("type"=>"profile"),true)."?user=".$comment['userID'];
			$profileHref = in_array($comment['userID'], $nonSitemembers) ? '#' : $profileHref;
			?>
			<li class="clearfix">
				<div class="forum-post-comment-avatar">
					<?php $photoUrl =  model::load("api/image")->buildAvatarUrl($comment['userProfileAvatarPhoto']); ?>
					<img src="<?php echo $photoUrl; ?>" alt=""/>
				</div>
				<div class="forum-post-comment-message">
					<div class="forum-post-comment-info">  <a href='<?php echo $profileHref;?>'><?php echo $comment['userProfileFullName']; ?></a>
				  		<div class="comment-post-date">
				  			<i class="fa fa-clock-o"></i>  <?php echo dateRangeViewer($comment['commentCreatedDate'],1,'my'); ?>
				  		</div>
				  	</div>
				  	<?php echo $comment['commentBody']; ?>
				</div>
				<?php if($comment['userID'] == session::get('userID')){ ?>
					<a data-id="<?php echo $comment['commentID']; ?>" onclick="javascript:comment.delete(this);" class="clearRequest i i-cross2 pull-right"></a>
				<?php } ?>
			</li>
			<?php endforeach ?>
		<?php endif; ?>
		</ul>
	</div>
</div>