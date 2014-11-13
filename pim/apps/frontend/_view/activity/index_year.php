<script type="text/javascript" src='<?php echo url::asset("backend/js/pim.js");?>'></script>
<script type="text/javascript">

var pim = new pim({base_url:"<?php echo url::base('{site-slug}');?>"});
function yearChange()
{
	var y = jQuery("#activityYear").val();
	pim.redirect("aktiviti/"+y);
}

function monthChange(month)
{
	var y = jQuery("#activityYear").val();
	pim.redirect("aktiviti/"+y+"/"+month);
}

</script>
<link rel="stylesheet" type="text/css" href="<?php echo url::asset('_templates/css/aktiviti.css');?>">
<script type="text/javascript" src='<?php echo url::asset("_templates/js/jquery.easydropdown.js");?>'></script>
<h3 class="block-heading">
<?php
echo model::load("template/frontend")
->buildBreadCrumbs(Array(
			Array("Aktiviti",url::base("{site-slug}/aktiviti")),
			Array($year)
						));
?>
</h3>
<div class="block-content clearfix">
<div class="page-content">
<div class="page-description"></div>
<div class="page-sub-wrapper calendar-page clearfix">
<div class="activity-type-desc">
<div class="select-activity-type">
<?php echo form::select("activityType",$typeR,"class='dropdown' onchange='window.location.href = \"?t=\"+this.value;' tabindex='9' data-settings='".'{"wrapperClass":"flat-type"}'."'",request::get("t"),"[SEMUA]");?>
</div>
<div class="calendar-heading-month"><?php echo $typeLabel;?></div>
<div class="calendar-type-info"></div>
</div>
<div class="clr"></div>
<div class="activity-year-select">
</div>

<!-- month and date picker. -->
<div class="activity-year-select clearfix">
	<div class="month-left">
		<ul>
			<li><a href="javascript:monthChange(1);">Jan</a></li>
			<li><a href="javascript:monthChange(2);">Feb</a></li>
			<li><a href="javascript:monthChange(3);">Mac</a></li>
			<li><a href="javascript:monthChange(4);">Apr</a></li>
			<li><a href="javascript:monthChange(5);">Mei</a></li>
			<li><a href="javascript:monthChange(6);">Jun</a></li>
		</ul>
	</div>
	<div class="select-year-activity">
		<?php
		## reverse array along with their value.
		$yearR	= model::load("helper")->monthYear("year",date("Y")-4,date("Y")+1);
		$yearR	= array_combine( array_reverse(array_keys( $yearR )), array_reverse( array_values( $yearR ) ) );
		echo form::select("activityYear",$yearR,"onchange='yearChange();' class='dropdown' data-settings='{\"wrapperClass\":\"select-year\"}'",$year,false);?>
	</div>
	<div class="month-right">
		<ul>
			<li><a href="javascript:monthChange(7);">Jul</a></li>
			<li><a href="javascript:monthChange(8);">Ogs</a></li>
			<li><a href="javascript:monthChange(9);">Sep</a></li>
			<li><a href="javascript:monthChange(10);">Okt</a></li>
			<li><a href="javascript:monthChange(11);">Nov</a></li>
			<li><a href="javascript:monthChange(12);">Dis</a></li>
		</ul>
	</div>
</div>
<div class="clr"></div>
<?php
$helper	= model::load("helper");

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
	<div class='calendar-label-name'><a href='<?php echo url::base("{site-slug}/aktiviti/$year/$m");?>' style='color:inherit;'><?php echo $monthR[$m];?></a></div>
	<div class='calendar-activity-list'>
		<ul>
			<?php
			foreach($res_activity[$m] as $row)
			{
				## total.
				$total	= isset($participantList[$row['activityID']])?count($participantList[$row['activityID']]):0;

				## prepare perma url.
				$url	= url::base("{site-slug}/aktiviti/$year/").date("m",strtotime($row['activityStartDate']))."/".$row['activitySlug'];
				?>
			<li class='clearfix'>
				<div class='activity-details-left'>
					<div class="activity-name"><a href="<?php echo $url;?>"><?php echo $row['activityName'];?></a></div>
					<div class="activity-time-date">
					<?php if(date("Y-m-d",strtotime($row['activityStartDate'])) == date("Y-m-d",strtotime($row['activityEndDate']))):
					// echo date("d F Y",strtotime($row['activityStartDate']));
					echo $helper->frontendDate($row['activityStartDate']);
					else:
					echo $helper->frontendDate($row['activityStartDate'])." hingga ".$helper->frontendDate($row['activityEndDate']);
					// echo date("d F Y",strtotime($row['activityStartDate']))." hingga ".date("d F Y",strtotime($row['activityEndDate']));
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
	<div class='calendar-label-name'><a href='<?php echo url::base("{site-slug}/aktiviti/$year/$m");?>' style='color:inherit;'><?php echo $monthR[$m];?></a></div>
	<div class='calendar-activity-list'>
		<ul>
			<?php
			foreach($res_activity[$m] as $row)
			{
				## total.
				$total	= isset($participantList[$row['activityID']])?count($participantList[$row['activityID']]):0;
				
				## prepare perma url.
				$url	= url::base("{site-slug}/aktiviti/$year/").date("m",strtotime($row['activityStartDate']))."/".$row['activitySlug'];
				?>
			<li class='clearfix'>
				<div class='activity-details-left'>
					<div class="activity-name"><a href="<?php echo $url;?>"><?php echo $row['activityName'];?></a></div>
					<div class="activity-time-date">
					<?php if(date("Y-m-d",strtotime($row['activityStartDate'])) == date("Y-m-d",strtotime($row['activityEndDate']))):	
					echo $helper->frontendDate($row['activityStartDate']);
					else:
					echo $helper->frontendDate($row['activityStartDate'])." hingga ".$helper->frontendDate($row['activityEndDate']);
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
