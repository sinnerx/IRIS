ALTER TABLE article ADD FULLTEXT(articleText);
ALTER TABLE article ADD FULLTEXT(articleName);
ALTER TABLE forum_thread_post ADD fulltext(forumThreadPostBody);
ALTER TABLE activity ADD fulltext(activityName);
ALTER TABLE activity ADD fulltext(activityDescription);
ALTER TABLE video ADD fulltext(videoName);
ALTER TABLE album ADD fulltext(albumDescription);
ALTER TABLE album ADD fulltext(albumName);
ALTER TABLE page ADD fulltext(pageText);

ALTER TABLE article ENGINE=MYISAM;
ALTER TABLE forum_thread_post ENGINE=MYISAM;
ALTER TABLE activity ENGINE=MYISAM;
ALTER TABLE video ENGINE=MYISAM;
ALTER TABLE album ENGINE=MYISAM;
ALTER TABLE page ENGINE=MYISAM;

ALTER TABLE  `user_profile` ADD INDEX(`userID`);
ALTER TABLE  `user_profile_additional` ADD INDEX(`userID`);