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
</style>
								<div class="forum-comment-form">
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