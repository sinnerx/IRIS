<?php
Class Controller_Comment
{
	public function __construct()
	{
		$this->template = false;
	}

	# add comment
	public function addComment()
	{
		$data	= input::get();
		$type = request::get("type");
		$refID = request::get("refID");

		if($data['commentBody'])
		{
			$id 	= model::load("comment/comment")->addComment($data['commentRefID'],session::get("userID"),$data['commentType'],$data);
			$count   = model::load("comment/comment")->getCount($refID,$type);
			return response::json(Array($id,$count));
		}
		else
		{
			return 'false';
		}

	}

	# get comments
	public function getComments($refID,$type)
	{
		$data['refID'] 	= $refID;
		$data['type'] 	= $type;

		$paginConf['limit']			= $data['limit'] = 5;
		$count 			= model::load("comment/comment")->getCount($refID,$type);
		
		$data['page_count'] = $page = ceil($count/$data['limit']);
		
		if(request::get("page"))
		{
			$page = request::get("page");
		}
		
		$data['page'] = $page = request::get("refresh")?$data['page_count']:$page;
		$data['count'] 	= $count;
		$paginConf['urlFormat']		= url::base("{site-slug}/comment/getComments/$refID/$type?page={page}");
		$paginConf['currentPage']	= $page;

		$data['comments'] = model::load("comment/comment")->getComments($refID,$type,$paginConf);

		if(!request::get("more") || request::get('refresh'))
		{
			view::render("comment/view",$data);
		}
		else
		{
			$comments = $data['comments'];

			if(count($comments) > 0)
			{
				foreach($comments as $comment)
				{
					$photoUrl = model::load("image/services")->getPhotoUrl($comment['userProfileAvatarPhoto']);
					echo '<li class="clearfix">
								<div class="forum-post-comment-avatar">
									<img src="'.$photoUrl.'" alt=""/>
								</div>
								<div class="forum-post-comment-message">
									<div class="forum-post-comment-info">';
					echo $comment['userProfileFullName'];
					echo '<div class="comment-post-date">
					  			<i class="fa fa-clock-o"></i>';
					echo dateRangeViewer($comment['commentCreatedDate'],1,'my');
					echo '</div>
					  			</div>';
					echo $comment['commentBody'];
					echo '</div>';
					if($comment['userID'] == session::get('userID'))
					{ 
						echo '<a data-id="'.$comment['commentID'].'" onclick="javascript:comment.delete(this);" class="clearRequest i i-cross2 pull-right"></a>';
					}
					echo '</li>';
				}
			}
		}
	}

	# get one comment
	public function getComment($commentID)
	{
		$comment = model::load('comment/comment')->getComment($commentID);

			$photoUrl = model::load("image/services")->getPhotoUrl($comment['userProfileAvatarPhoto']);
			echo '<li class="clearfix">
					<div class="forum-post-comment-avatar">
						<img src="'.$photoUrl.'" alt=""/>
					</div>
					<div class="forum-post-comment-message">
						<div class="forum-post-comment-info">';
			echo $comment['userProfileFullName'];
			echo '<div class="comment-post-date">
		  			<i class="fa fa-clock-o"></i>';
			echo dateRangeViewer($comment['commentCreatedDate'],1,'my');
			echo '</div>
			 		</div>';
			echo $comment['commentBody'];
			echo '</div>';
			if($comment['userID'] == session::get('userID'))
			{ 
				echo '<a data-id="'.$comment['commentID'].'" onclick="javascript:comment.delete(this);" class="clearRequest i i-cross2 pull-right"></a>';
			}
			echo '</li>';
	}

	# disable comment
	public function disableComment($commentID)
	{
		$type = request::get("type");
		$refID = request::get("refID");
		$respond = model::load("comment/comment")->disableComment($commentID);
		$count   = model::load("comment/comment")->getCount($refID,$type);

		if($respond)
		{
			return $count;
		}
	}

	# get add comment form
	public function getForm()
	{
		$data['c_user'] = model::load("access/auth")->getAuthData("user");
		
		view::render("comment/addComment",$data);
	}
}
?>