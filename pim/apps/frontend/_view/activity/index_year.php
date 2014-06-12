<script type="text/javascript" src='<?php echo url::asset("backend/js/pim.js");?>'></script>
<script type="text/javascript">

var pim = new pim({base_url:"<?php echo url::base('{site-slug}/activity/'.$year);?>"});

</script>
<link rel="stylesheet" type="text/css" href="<?php echo url::asset('_templates/css/aktiviti.css');?>">
<script type="text/javascript" src='<?php echo url::asset("_templates/js/jquery.easydropdown.js");?>'></script>
<h3 class="block-heading">Kalendar Aktiviti</h3>
<div class="block-content clearfix">
<div class="page-content">
<div class="page-description"> 
Lorem ipsum dolor sit amet, maiores ornare ac fermentum, imperdiet ut vivamus a, nam lectus at nunc. Quam euismod sem, semper ut potenti pellentesque quisque. In eget sapien sed, sit duis vestibulum ultricies, placerat morbi amet vel, nullam in in lorem vel. In molestie elit dui dictum, praesent nascetur pulvinar sed, in dolor pede in aliquam, risus nec error quis pharetra. Eros metus quam augue suspendisse, metus rutrum risus erat in.  In ultrices quo ut lectus, etiam vestibulum urna a est, pretium luctus euismod nisl, pellentesque turpis hac ridiculus massa. Venenatis a taciti dolor platea, curabitur lorem platea urna odio.
</div>
<div class="page-sub-wrapper calendar-page clearfix">
<div class="activity-type-desc">
<div class="select-activity-type">
<?php echo form::select("activityType",$typeR,"class='dropdown' onchange='window.location.href = \"?t=\"+this.value;' tabindex='9' data-settings='".'{"wrapperClass":"flat-type"}'."'",request::get("t"),"[SEMUA]");?>
</div>
<div class="calendar-heading-month"><?php echo $typeLabel;?></div>
<div class="calendar-type-info">Lorem ipsum dolor sit amet, maiores ornare ac fermentum, imperdiet ut vivamus a, nam lectus at nunc. Quam euismod sem, semper ut potenti pellentesque quisque. In eget sapien sed, sit duis vestibulum ultricies, placerat morbi amet vel, nullam in in lorem vel. In molestie elit dui dictum.</div>
</div>
<div class="clr"></div>
<div class="activity-year-select">
<div class="year-select clearfix">
<div class="prev-year"><a href="<?php echo url::base("{site-slug}/activity/".($year-1),true);?>"><i class="fa fa-angle-left"></i></a></div>
<div class="year-count"><?php echo $year;?></div>
<div class="next-year"><a href="<?php echo url::base("{site-slug}/activity/".($year+1),true);?>"><i class="fa fa-angle-right"></i></a></div>
</div>
</div>
<div class="clr"></div>
<?php
if($res_activity):

## prepare the left and right side of the month first based on existing activity month.
$leftR	= Array();
$rightR	= Array();

$no	= 1;
foreach(array_keys($res_activity) as $m)
{
	if($no % 2 == 1)
	{
		$leftR[] 	= $m;
	}
	else
	{
		$rightR[]	= $m;
	}
	$no++;
}

?>
<div class='calendar-type-left'>
<?php ## left month loop ## 
foreach($leftR as $m):?>
	<div class='calendar-label-name'><a href='<?php echo url::base("{site-slug}/activity/$year/$m");?>' style='color:inherit;'><?php echo $monthR[$m];?></a></div>
	<div class='calendar-activity-list'>
		<ul>
			<?php
			foreach($res_activity[$m] as $row)
			{
				## total.
				$total	= isset($participantList[$row['activityID']])?count($participantList[$row['activityID']]):0;

				## prepare perma url.
				$url	= url::base("{site-slug}/activity/$year/").date("m",strtotime($row['activityStartDate']))."/".$row['activitySlug'];
				?>
			<li class='clearfix'>
				<div class='activity-details-left'>
					<div class="activity-name"><a href="<?php echo $url;?>"><?php echo $row['activityName'];?></a></div>
					<div class="activity-time-date">
					<?php if($row['activityStartDate'] == $row['activityEndDate']):
					echo date("d F Y",strtotime($row['activityStartDate']));
					else:
					echo date("d F Y",strtotime($row['activityStartDate']))." hingga ".date("d F Y",strtotime($row['activityEndDate']));
					endif;
					?>
					</div>
				</div>
				<div class="activity-join-count">
					<i class="fa fa-user"></i> <?php echo $total;?>
				</div>
			</li>
			<?php
			}
			?>
		</ul>
	</div>
<?php endforeach;?>
</div>
<div class='calendar-type-right'>
<?php ## left month loop ## 
foreach($rightR as $m):?>
	<div class='calendar-label-name'><a href='<?php echo url::base("{site-slug}/activity/$year/$m");?>' style='color:inherit;'><?php echo $monthR[$m];?></a></div>
	<div class='calendar-activity-list'>
		<ul>
			<?php
			foreach($res_activity[$m] as $row)
			{
				## total.
				$total	= isset($participantList[$row['activityID']])?count($participantList[$row['activityID']]):0;
				
				## prepare perma url.
				$url	= url::base("{site-slug}/activity/$year/").date("m",strtotime($row['activityStartDate']))."/".$row['activitySlug'];
				?>
			<li class='clearfix'>
				<div class='activity-details-left'>
					<div class="activity-name"><a href="<?php echo $url;?>"><?php echo $row['activityName'];?></a></div>
					<div class="activity-time-date">
					<?php if($row['activityStartDate'] == $row['activityEndDate']):
					echo date("d F Y",strtotime($row['activityStartDate']));
					else:
					echo date("d F Y",strtotime($row['activityStartDate']))." hingga ".date("d F Y",strtotime($row['activityEndDate']));
					endif;
					?>
					</div>
				</div>
				<div class="activity-join-count">
					<i class="fa fa-user"></i> <?php echo $total;?>
				</div>
			</li>
			<?php
			}
			?>
		</ul>
	</div>
<?php endforeach;?>
</div>
<?php else:
echo "Tiada aktiviti untuk tahun ini";

endif;?>







































<!-- 
Go away
<div class="calendar-type-left">
	<div class="calendar-label-name">Disember</div>
	<div class="calendar-activity-list">
		<ul>
			<li class="clearfix">
			<div class="activity-details-left">
			<div class="activity-name"><a href="#">Lorem ipsum dolor sit amet</a></div>
			<div class="activity-time-date">15 November 2013, 3:00 PM</div>
			</div>
			<div class="activity-join-count">
			<i class="fa fa-user"></i> 9
			</div>
			</li>
			<li class="clearfix">
			<div class="activity-details-left">
			<div class="activity-name"><a href="#">Lorem ipsum dolor sit amet</a></div>
			<div class="activity-time-date">15 November 2013, 3:00 PM</div>
			</div>
			<div class="activity-join-count">
			<i class="fa fa-user"></i> 9
			</div>
			</li>
			<li class="clearfix">
			<div class="activity-details-left">
			<div class="activity-name"><a href="#">Lorem ipsum dolor sit amet</a></div>
			<div class="activity-time-date">15 November 2013, 3:00 PM</div>
			</div>
			<div class="activity-join-count">
			<i class="fa fa-user"></i> 9
			</div>
			</li>
			<li class="clearfix event-done">
			<div class="activity-details-left">
			<div class="activity-name"><a href="#">Lorem ipsum dolor sit amet</a></div>
			<div class="activity-time-date">15 November 2013, 3:00 PM</div>
			</div>
			<div class="activity-join-count">
			<i class="fa fa-user"></i> 9
			</div>
			</li>
			<li class="clearfix event-done">
			<div class="activity-details-left">
			<div class="activity-name"><a href="#">Lorem ipsum dolor sit amet</a></div>
			<div class="activity-time-date">15 November 2013, 3:00 PM</div>
			</div>
			<div class="activity-join-count">
			<i class="fa fa-user"></i> 9
			</div>
			</li>
		</ul>
	</div>
	<div class="calendar-label-name">Febuari</div>
	<div class="calendar-activity-list">
		<ul>
			<li class="clearfix">
			<div class="activity-details-left">
			<div class="activity-name"><a href="#">Lorem ipsum dolor sit amet</a></div>
			<div class="activity-time-date">15 November 2013, 3:00 PM</div>
			</div>
			<div class="activity-join-count">
			<i class="fa fa-user"></i> 9
			</div>
			</li>
			<li class="clearfix">
			<div class="activity-details-left">
			<div class="activity-name"><a href="#">Lorem ipsum dolor sit amet</a></div>
			<div class="activity-time-date">15 November 2013, 3:00 PM</div>
			</div>
			<div class="activity-join-count">
			<i class="fa fa-user"></i> 9
			</div>
			</li>
			<li class="clearfix">
			<div class="activity-details-left">
			<div class="activity-name"><a href="#">Lorem ipsum dolor sit amet</a></div>
			<div class="activity-time-date">15 November 2013, 3:00 PM</div>
			</div>
			<div class="activity-join-count">
			<i class="fa fa-user"></i> 9
			</div>
			</li>
			<li class="clearfix event-done">
			<div class="activity-details-left">
			<div class="activity-name"><a href="#">Lorem ipsum dolor sit amet</a></div>
			<div class="activity-time-date">15 November 2013, 3:00 PM</div>
			</div>
			<div class="activity-join-count">
			<i class="fa fa-user"></i> 9
			</div>
			</li>
			<li class="clearfix event-done">
			<div class="activity-details-left">
			<div class="activity-name"><a href="#">Lorem ipsum dolor sit amet</a></div>
			<div class="activity-time-date">15 November 2013, 3:00 PM</div>
			</div>
			<div class="activity-join-count">
			<i class="fa fa-user"></i> 9
			</div>
			</li>
		</ul>
	</div>
</div>

<div class="calendar-type-right">
<div class="calendar-label-name">Januari</div>
<div class="calendar-activity-list">
<ul>
<li class="clearfix">
<div class="activity-details-left">
<div class="activity-name"><a href="#">Lorem ipsum dolor sit amet</a></div>
<div class="activity-time-date">15 November 2013, 3:00 PM</div>
</div>
<div class="activity-join-count">
<i class="fa fa-user"></i> 9
</div>
</li>
<li class="clearfix">
<div class="activity-details-left">
<div class="activity-name"><a href="#">Lorem ipsum dolor sit amet</a></div>
<div class="activity-time-date">15 November 2013, 3:00 PM</div>
</div>
<div class="activity-join-count">
<i class="fa fa-user"></i> 9
</div>
</li>
<li class="clearfix">
<div class="activity-details-left">
<div class="activity-name"><a href="#">Lorem ipsum dolor sit amet</a></div>
<div class="activity-time-date">15 November 2013, 3:00 PM</div>
</div>
<div class="activity-join-count">
<i class="fa fa-user"></i> 9
</div>
</li>
<li class="clearfix event-done">
<div class="activity-details-left">
<div class="activity-name"><a href="#">Lorem ipsum dolor sit amet</a></div>
<div class="activity-time-date">15 November 2013, 3:00 PM</div>
</div>
<div class="activity-join-count">
<i class="fa fa-user"></i> 9
</div>
</li>
<li class="clearfix event-done">
<div class="activity-details-left">
<div class="activity-name"><a href="#">Lorem ipsum dolor sit amet</a></div>
<div class="activity-time-date">15 November 2013, 3:00 PM</div>
</div>
<div class="activity-join-count">
<i class="fa fa-user"></i> 9
</div>
</li>
</ul>
</div>
<div class="calendar-label-name">mac</div>
<div class="calendar-activity-list">
<ul>
<li class="clearfix">
<div class="activity-details-left">
<div class="activity-name"><a href="#">Lorem ipsum dolor sit amet</a></div>
<div class="activity-time-date">15 November 2013, 3:00 PM</div>
</div>
<div class="activity-join-count">
<i class="fa fa-user"></i> 9
</div>
</li>
<li class="clearfix">
<div class="activity-details-left">
<div class="activity-name"><a href="#">Lorem ipsum dolor sit amet</a></div>
<div class="activity-time-date">15 November 2013, 3:00 PM</div>
</div>
<div class="activity-join-count">
<i class="fa fa-user"></i> 9
</div>
</li>
<li class="clearfix">
<div class="activity-details-left">
<div class="activity-name"><a href="#">Lorem ipsum dolor sit amet</a></div>
<div class="activity-time-date">15 November 2013, 3:00 PM</div>
</div>
<div class="activity-join-count">
<i class="fa fa-user"></i> 9
</div>
</li>
<li class="clearfix event-done">
<div class="activity-details-left">
<div class="activity-name"><a href="#">Lorem ipsum dolor sit amet</a></div>
<div class="activity-time-date">15 November 2013, 3:00 PM</div>
</div>
<div class="activity-join-count">
<i class="fa fa-user"></i> 9
</div>
</li>
<li class="clearfix event-done">
<div class="activity-details-left">
<div class="activity-name"><a href="#">Lorem ipsum dolor sit amet</a></div>
<div class="activity-time-date">15 November 2013, 3:00 PM</div>
</div>
<div class="activity-join-count">
<i class="fa fa-user"></i> 9
</div>
</li>
</ul>
</div>
</div> -->
</div>
</div>
</div>
