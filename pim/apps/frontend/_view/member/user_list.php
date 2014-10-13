<?php if(count($users) > 0): ?>
	<?php foreach($users as $user): ?>
	<li>
		<a href="#">
			<?php if($user['userPremiumStatus'] == 1): ?><div class="member-status">Pemegang Kad</div><?php endif; ?>
			<img src="<?php echo model::load("api/image")->buildAvatarUrl($user['userProfileAvatarPhoto']); ?>" width="60" height="60" alt="title"/>
		</a>
		<div class="member-info">
			<div class="member-name"><?php echo $user['userProfileFullName']; ?></div>
			<?php if($user['userProfileOccupation']): ?><div class="member-level"><?php echo $user['userProfileOccupation']; ?></div><?php endif; ?>
		</div>
	</li>
	<?php endforeach; ?>
	<?php if($page < $limit): ?>
	<article id="get-older" style="">
		<input type="checkbox" id="read_more" role="button">
		<label for="read_more" onclick="javascript:userList.getMore(<?php echo $page+1; ?>);"><span>Seterusnya</span></label>  
	</article>
	<?php endif; ?>
<?php else: ?>
	Tiada maklumat.
<?php endif; ?>