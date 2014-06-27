<script type="text/javascript" src='<?php echo url::asset("backend/js/pim.js");?>'></script>
<script type="text/javascript">
	
var pim = new pim({base_url:"<?php echo url::base('{site-slug}');?>"});

</script>
<style type="text/css">
.activity-date-list table
{
	font-size: 13px;
	width: 100%;
	margin-bottom: 5px;
}
.activity-date-list table td
{
	border-bottom:1px solid #c0c0c0;
	text-align: center;
}

</style>
<script type="text/javascript">
var activity = new function()
{
	var working = false;
	this.activityName	= "<?php echo $activityName;?>";
	this.activityID		= "<?php echo $activityID;?>";
	var $		= jQuery;
	this.join = function(date)
	{
		if(working)
		{
			return alert("Please wait.");
		}

		if(date)
		{
			var e	= jQuery("#activityDate"+date);
			var time	= e.data("starttime")+" hingga "+e.data("endtime");
			if(!confirm("Anda akan sertai aktiviti : "+this.activityName+"\nTarikh : "+date+"\nMasa : "+time+"\n\nAnda Pasti?"))
			{
				return false;
			}

			var url	= pim.base_url+"ajax/activity/join/"+this.activityID+"/"+date;
		}
		else
		{
			var start	= $("#startDate").val();
			var end		= $("#endDate").val();

			var dateTxt = start == end?"Tarikh : "+start:"Tarikh : "+start+" hingga "+end;

			if(!confirm("Adakah anda akan sertai aktiviti ini?\nNama : "+this.activityName+"\n"+dateTxt))
			{
				return false;
			}

			var url	= pim.base_url+"ajax/activity/join/"+this.activityID;
		}

		working = true;
		$.ajax({type:"GET",url:url}).done(function(r)
		{
			var r = $.parseJSON(r);

			if(!r[0])
			{
				alert("Problems, we're refreshing the browser.");
				window.location.href = "";
			}

			// change icon and remove href.
			var e = date?$("#activityDate"+date):$(".joinbutton");
			e.removeClass("fa-sign-in").addClass("fa-check").attr("href","#");

			// change image.
			if($("#userJoined").val() != "true")
			{
				$("#members-attend-list-none").remove();
				$("#userJoined").val("true");
				$(".members-attend-list ul").append("<li><img style='height:63px;' alt='' src='"+r[1]['userProfileAvatarPhoto']+"' /></li>");

				$(".joinTotal").html(Number($(".joinTotal").html())+1);
			}

			working = false;
		});
	}
}

</script>
<input type='hidden' id='userJoined' value='<?php echo $joinedDate?"true":"false";?>' />
<input type='hidden' id='startDate' value='<?php echo date("j M Y",strtotime($activityStartDate));?>' />
<input type="hidden" id='endDate' value='<?php echo date("j M Y",strtotime($activityEndDate));?>' />
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
						<li><span class="label-info">Penyertaan</span><span>: <?php echo $activityParticipationLabel;?></span></li>
						<li><span class="label-info">Yuran/Bulanan</span><span>: Percuma</span></li>
						</ul>
						</div>
						<div class="event-short-desc">
						<?php echo nl2br($activityDescription);?>
						</div>
						<div class='activity-date-list'>
							<table>
								<tr>
									<th>Tarikh</th>
									<th>Masa (mula)</th>
									<th>Masa (tamat)</th>
									<th></th>
								</tr>
								<?php
								if($activityDate){
									$no = 1;

									foreach($activityDate as $row)
									{
										$date	= date("j M Y",strtotime($row['activityDateValue']));
										$start	= date("g:i A",strtotime($row['activityDateStartTime']));
										$end	= date("g:i A",strtotime($row['activityDateEndTime']));

										## all date attendance flag.
										$allDateAttendanceOptional = $activityAllDateAttendance == 2;
										$allDateAttendanceCompulsary = $activityAllDateAttendance == 1 && $no == 1;

										$rowspan	= $allDateAttendanceCompulsary?"rowspan='".count($activityDate)."'":"";

										$href	= $allDateAttendanceOptional?"javascript:activity.join(\"$row[activityDateValue]\");":"javascript:activity.join();";

										$attr	= "href='$href' class='joinbutton fa fa-sign-in'";

										if($joinedDate)
										{
											if(isset($joinedDate[$row['activityDateValue']]))
											{
												$attr	= "href='#' class='joinbutton fa fa-check'";
											}
										}
										?>
									<tr>
										<td><?php echo $date;?></td>
										<td><?php echo $start;?></td>
										<td><?php echo $end;?></td>
										<?php if($allDateAttendanceOptional || $allDateAttendanceCompulsary):?>
										<td <?php echo $rowspan;?> >
										<?php if($participationFlag){?>
										<a id='activityDate<?php echo $row['activityDateValue'];?>' data-starttime='<?php echo $start;?>' data-endtime='<?php echo $end;?>' <?php echo $attr;?> title='Join'></a></td>
										<?php }?>
										<?php endif;?>
									</tr>
									<?php
										$no++;
									}
								}?>
							</table>
						</div>
						<?php
						if(!$participationFlag):?>
						<div class='members-guest'>
							<span><?php echo $participationFlagMessage;?></span>
						</div>
						<?php else:?>
						<div class='members-guest'>
							<span>
							<?php if($activityAllDateAttendance == 2):?>
							Sila pilih tarikh yang anda ingin sertai.
							<?php elseif($activityAllDateAttendance == 1):?>
							Klik <label onclick='activity.join();' style='color:blue;cursor:pointer;' class='fa fa-sign-in'></label> untuk sertai.
							<?php endif;?>
							</span>
						</div>
						<?php endif;?>
					</div>
				</div>
			</div>
		<div class="event-members-join">
		<div class="calendar-label-name">Kehadiran (<span class='joinTotal'><?php echo count($participantList['attending']);?></span>)</div>
		<div class="members-attend-list">
			<ul>
		<?php if(!$participantList['attending']):?>
			<span id='members-attend-list-none'>Tiada</span>
		<?php else:?>
			
				<?php
				foreach($participantList['attending'] as $row):
				$imgUrl	= model::load("image/services")->getPhotoUrl($row['userProfileAvatarPhoto']);
				?>
				<li><img src="<?php echo $imgUrl;?>" style='height:63px;' alt=""/></li>
				<?php
				endforeach;
				?>
			
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
			
		<?php endif; /* /participantList*/?>
			</ul>
		</div>
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