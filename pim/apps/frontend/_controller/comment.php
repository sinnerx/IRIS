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
		$createdDate = model::load("comment/comment")->addComment($data['commentRefID'],session::get("userID"),$data['commentType'],$data);
	}

	# get comments
	public function getComments($refID,$type)
	{
		$data['refID'] = $refID;
		$data['type'] = $type;
		$data['comments'] = model::load("comment/comment")->getComments($refID,$type);

		view::render("comment/view",$data);
	}

	# get add comment form
	public function getForm()
	{
		$data['c_user'] = model::load("access/auth")->getAuthData("user");
		
		view::render("comment/addComment",$data);
	}
}
?>