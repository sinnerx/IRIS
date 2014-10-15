<?php
namespace model\comment;
use db, session, model, pagination, url;

class Comment
{
	# add comment on a post
	public function addComment($commentRefID,$userID,$type,$data)
	{
		$dataComment	= Array(
				"commentType"=>$type,
				"userID"=>$userID,
				"commentRefID"=>$commentRefID,
				"commentBody"=>$data['commentBody'],
				"commentApprovalStatus"=>1,
				"commentCreatedDate"=>now()
						);

		db::insert("comment",$dataComment);

		return db::getLastID("comment","commentID");
	}

	# get comments on a post
	public function getComments($commentRefID,$type,$pageConf = null)
	{
		db::from("comment");
		db::select("user_profile.userProfileFullName,user_profile.userProfileAvatarPhoto,comment.*");
		db::where("commentRefID",$commentRefID);
		db::where("commentType",$type);
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
		db::join("user_profile","user_profile.userID = comment.userID");

		return db::get()->row();
	}

	# get comments count
	public function getCount($commentRefID,$type)
	{
		db::from("comment");
		db::select("COUNT(commentID) AS count");
		db::where("commentRefID",$commentRefID);
		db::where("commentType",$type);

		$data = db::get("comment")->row();

		return $data['count'];
	}
}

?>