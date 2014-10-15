<style type="text/css">
	::-webkit-input-placeholder { /* WebKit browsers */
       color:    #9e9e9e;
       opacity:  0.65;
	}
	:-moz-placeholder { /* Mozilla Firefox 4 to 18 */
	   color:    #9e9e9e;
	   opacity:  1;
	}
	::-moz-placeholder { /* Mozilla Firefox 19+ */
	   color:    #9e9e9e;
	   opacity:  1;
	}
	:-ms-input-placeholder { /* Internet Explorer 10+ */
	   color:    #9e9e9e;
	}
	.alert
	{
		font-size: 12px;
		box-shadow: inset 0 1px 0 rgba(255,255,255,0.2);
		padding: 15px;
		margin-bottom: 20px;
		border: 1px solid transparent;
		border-radius: 4px;
	}
	.alert-danger
	{
		color: #a94442;
		background-color: #f2dede;
		border-color: #ebccd1;
	}
</style>
								<div class="forum-comment-form">
									<div class="alert alert-danger" id="comment-alert" style="display:none;">Sila taip komen anda.</div>
									<div class="comment-user-avatar"></div>
									<div class="comment-post-input">
										<h3><?php echo $c_user['userProfileFullName']; ?> (Logged-in)</h3>
										<div class="comment-text-input">
											<div class="comment-text-input-arrow"></div>
											<textarea id="body" placeholder="Taipkan komen anda di sini..."></textarea>
											<br/><br/>
											<a href='javascript:comment.add();' class="bttn-submit">Hantar</a>
										</div>
									</div>
								</div>