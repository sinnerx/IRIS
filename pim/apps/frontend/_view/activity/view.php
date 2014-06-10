<link rel="stylesheet" type="text/css" href="<?php echo url::asset('_templates/css/aktiviti.css');?>">
<h3 class="block-heading">Kalendar Aktiviti  <span class="subheading"> > <?php echo $activityTypeLabel;?></span></h3>
<div class="block-content clearfix">
	<div class="page-content">
		<div class="page-description">
		</div>
		<div class="page-sub-wrapper calendar-page clearfix">
			<div class="event-heading-title clearfix">
				<span class="title-event"><?php echo $activityName;?></span>
				<?php if(strtotime($activityStartDate) > time()):?>
				<span class="akan-datang-label"></span>
				<?php else:?>
				<span class='previous-activity-label'></span>
				<?php endif;?>
			</div>
			<div class="event-info-left">
				<div class="event-time-date">
				<?php if($activityStartDate == $activityEndDate):?>
				<?php echo date("d F Y",strtotime($activityStartDate));?>
				<?php else:?>
				<?php echo date("d F Y",strtotime($activityStartDate))." hingga ".date("d F Y",strtotime($activityEndDate));?>
				<?php endif;?>
				</div>  
				<div class="event-info-wrap clearfix">
					<div class="event-info-description">
						<div class="event-more-details">
						<ul>
						<li><span class="label-info">Jenis <?php echo $activityTypeLabel; ?></span><span>: <?php echo $activityType;?></span></li>
						<li><span class="label-info">Lokasi</span><span>: <?php echo $location;?></span></li>
						<li><span class="label-info">Penyertaan</span><span>: <?php echo $activityParticipation;?></span></li>
						<li><span class="label-info">Yuran/Bulanan</span><span>: Percuma</span></li>
						</ul>
						</div>
						<div class="event-short-desc">
						<?php echo nl2br($activityDescription);?>
						</div>
						<?php if($userdata = model::load("access/auth")->getAuthData("user")):
						if($userdata['isMember']):
						?>
						<div class="members-guest">
							<span>Adakah Anda Akan Hadir?</span> <a href="#">Ya </a><a href="#">Tidak</a>
						</div>
						<?php else:?>
						<div class='members-guest'>
							<span>Anda bukan pengguna berdaftar di laman ini</span>
						</div>
						<?php endif;?>
						<?php endif;?>
					</div>
				</div>
			</div>
		<div class="event-members-join">
		<div class="calendar-label-name">Kehadiran</div>
		<?php if(!$participantList):?>
			Tiada
		<?php else:?>
			<div class="members-attend-list">
			
				<!-- <ul>
					<li><img src="images/1.jpg" width="64" height="63"  alt=""/></li>
					<li><img src="images/2.jpg" width="64" height="63"  alt=""/></li>
					<li><img src="images/3.jpg" width="64" height="63"  alt=""/></li>
					<li><img src="images/4.jpg" width="64" height="63"  alt=""/></li>
					<li><img src="images/5.jpg" width="64" height="63"  alt=""/></li>
					<li><img src="images/6.jpg" width="64" height="63"  alt=""/></li>
					<li><img src="images/7.jpg" width="64" height="63"  alt=""/></li>
					<li><img src="images/8.jpg" width="64" height="63"  alt=""/></li>
					<li><img src="images/9.jpg" width="64" height="63"  alt=""/></li>
					<li><img src="images/1.jpg" width="64" height="63"  alt=""/></li>
					<li><img src="images/2.jpg" width="64" height="63"  alt=""/></li>
					<li><img src="images/3.jpg" width="64" height="63"  alt=""/></li>
				</ul> -->
			</div>
		<?php endif; /* /participantList*/?>
		</div>
		<div class="clr"></div>
		<?php /* +++++ Comment markup pending for this release (2) +++++
			<div class="forum-post-comment">
			<div class="forum-post-comment-count">KOMEN <span>(3)</span></div>
				<div class="forum-post-comment-content">
				<ul><li class="clearfix">
				<div class="forum-post-comment-avatar"> <img src="members_photo/1538817_10202447454481584_450404680_n.jpg" alt=""/> </div>
				<div class="forum-post-comment-message">
				<div class="forum-post-comment-info">Mohd Hafiz
				  <div class="comment-post-date"><i class="fa fa-clock-o"></i>  2 Jam Lalu</div></div>
				Lorem ipsum dolor sit amet, maiores ornare ac fermentum, imperdiet ut vivamus a, nam lectus at nunc. Quam euismod sem, semper ut potenti pellentesque quisque. In eget sapien sed, sit duis vestibulum ultricies, placerat morbi amet vel, nullam in in lorem vel. In molestie elit dui dictum, praesent nascetur pulvinar sed.
				</div>
				</li>
				<li class="clearfix">
				<div class="forum-post-comment-avatar"> <img src="members_photo/1901223_587997637960391_2111595029_n.jpg" alt=""/> </div>
				<div class="forum-post-comment-message">
				<div class="forum-post-comment-info">Nurul Syuhadah Mansoor <div class="comment-post-date"><i class="fa fa-clock-o"></i>  3 Jam Lalu</div></div>
				Lorem ipsum dolor sit amet, maiores ornare ac fermentum, imperdiet ut vivamus a, nam lectus at nunc. Quam euismod sem, semper ut potenti pellentesque quisque. In eget sapien sed, sit duis vestibulum ultricies, placerat morbi amet vel, nullam in in lorem vel. In molestie elit dui dictum, praesent nascetur pulvinar sed.
				</div>
				</li>
				<li class="clearfix">
				<div class="forum-post-comment-avatar"> <img src="members_photo/1450051_630273793680835_492412226_n.jpg" alt=""/> </div>
				<div class="forum-post-comment-message">
				<div class="forum-post-comment-info">Razali Hussein
				  <div class="comment-post-date"><i class="fa fa-clock-o"></i>  4 Jam Lalu</div></div>
				Lorem ipsum dolor sit amet, maiores ornare ac fermentum, imperdiet ut vivamus a, nam lectus at nunc. Quam euismod sem, semper ut potenti pellentesque quisque. In eget sapien sed, sit duis vestibulum ultricies, placerat morbi amet vel, nullam in in lorem vel. In molestie elit dui dictum, praesent nascetur pulvinar sed.
				</div>
				</li>
				</ul>
				<div class="forum-comment-form">
				<div class="comment-user-avatar"></div>
				<div class="comment-post-input">
				<h3>Nama Komentator (Logged-in)</h3>
				<div class="comment-text-input">
				<div class="comment-text-input-arrow"></div>
				<textarea>Taipkan komen anda di sini...</textarea>
				<input type="submit" value="Hantar" class="bttn-submit">
				</div>
				</div>
				</div>
				</div>
			</div> */?>
		</div>
	</div>
</div>