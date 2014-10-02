<?php
namespace model\comment;
use db, session, model, pagination, url;

class Comment
{
	# add comment on a post
	public function addComment($commentRefID,$userID,$type,$data)
	{
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
}

?>