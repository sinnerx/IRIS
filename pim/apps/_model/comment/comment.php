<?php
namespace model\comment;
use db, session, model, pagination, url;

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

		$dataComment	= Array(
				"commentType"=>$type,
				"userID"=>$userID,
				"commentRefID"=>$commentRefID,
				"commentBody"=>$data['commentBody'],
				"commentApprovalStatus"=>1,
				"commentCreatedDate"=>now()
						);

		db::insert("comment",$dataComment);

		## create user/activity.
		model::load("user/activity")->create(authData("current_site.siteID"),$userID,"comment.add",Array("commentID"=>db::getLastID("comment","commentID")));

		return db::getLastID("comment","commentID");
	}

	# get comments on a post
	public function getComments($commentRefID,$type,$pageConf = null)
	{
		db::from("comment");
		db::select("user_profile.userProfileFullName,user_profile.userProfileAvatarPhoto,comment.*");
		db::where("commentRefID",$commentRefID);
		db::where("commentType",$type);
		db::where("commentApprovalStatus",1);
		db::join("user_profile","user_profile.userID = comment.userID");

		## pagination.
		if(count($pageConf)>0)
		{
			pagination::initiate(Array(
								"urlFormat"=>$pageConf['urlFormat'],
								"totalRow"=>db::num_rows(),
								"limit"=>$pageConf['limit'],
								"currentPage"=>$pageConf['currentPage']
										));

			db::limit($pageConf['limit'],pagination::recordNo()-1);
		}
		
		db::order_by("commentCreatedDate ASC");

		return db::get()->result();
	}

	# get a comment
	public function getComment($commentID)
	{
		db::from('comment');
		db::where('commentID',$commentID);
		db::where("commentApprovalStatus",1);
		db::join("user_profile","user_profile.userID = comment.userID");

		return db::get()->row();
	}

	# get comments count
	public function getCount($commentRefID,$type)
	{
		db::from("comment");
		db::select("COUNT(commentID) AS count");
		db::where("commentApprovalStatus",1);
		db::where("commentRefID",$commentRefID);
		db::where("commentType",$type);

		$data = db::get("comment")->row();

		return $data['count'];
	}

	## return comment and all the joined based on it's type.
	public function getComment2($commentID)
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

	# disable comment
	public function disableComment($commentID)
	{
		db::where("commentID",$commentID);
		db::update("comment",Array(
						"commentApprovalStatus"=>0
								));

		return true;
	}
}

?>