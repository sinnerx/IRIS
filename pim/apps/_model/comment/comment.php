<?php
namespace model\comment;
use db, session, model, pagination, url;

/*
commentType :
activity
article
site_album
video

*/
class Comment
{
	public function commentType()
	{
		$typeR	= Array(
				"activity",
				"article",
				"site_album",
				"video_album"
						);

		return $typeR;
	}

	# add comment on a post
	public function addComment($commentRefID,$userID,$type,$data)
	{
		$typeR	= $this->commentType();

		## need to declare in commentType first.
		if(!in_array($type,$typeR))
		{
			return false;
		}

		$now = now();

		$dataComment	= Array(
				"commentType"=>$type,
				"userID"=>$userID,
				"commentRefID"=>$commentRefID,
				"commentBody"=>$data['commentBody'],
				"commentApprovalStatus"=>1,
				"commentCreatedDate"=>$now
						);

		db::insert("comment",$dataComment);

		## create user/activity.
		model::load("user/activity")->create(authData("current_site.siteID"),$userID,"comment.add",Array("commentID"=>db::getLastID("comment","commentID")));

		return $now;
	}

	# get comments on a post
	public function getComments($commentRefID,$type)
	{
		db::from("comment");
		db::select("user_profile.userProfileFullName,user_profile.userProfileAvatarPhoto,comment.*");
		db::where("commentRefID",$commentRefID);
		db::where("commentType",$type);
		db::join("user_profile","user_profile.userID = comment.userID");
		db::order_by("commentCreatedDate ASC");

		return db::get()->result();
	}

	## return comment and all the joined based on it's type.
	public function getComment($commentID)
	{
		db::where("commentID",$commentID);
		db::join("activity","comment.commentType = 'activity' AND comment.commentRefID = activity.activityID");
		db::join("article","comment.commentType = 'article' AND comment.commentRefID = article.articleID");
		db::join("site_album","comment.commentType = 'site_album' AND comment.commentRefID = site_album.siteAlbumID");
		db::join("album","comment.commentType = 'site_album' AND site_album.albumID = album.albumID");
		db::join("video_album","comment.commentType = 'video_album' AND comment.commentRefID = video_album.videoAlbumID");

		$row	= db::get("comment")->row();

		return $row;
	}
}

?>