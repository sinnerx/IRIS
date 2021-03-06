<link rel="stylesheet" type="text/css" href="<?php echo url::asset('_templates/css/aktiviti.css');?>">
<script type="text/javascript" src='<?php echo url::asset("_templates/js/jquery.easydropdown.js");?>'></script>
<script type="text/javascript" src='<?php echo url::asset("backend/js/pim.js");?>'></script>
<style type="text/css">
	
.calendar-right-mini-cal
{
	position: relative;
	z-index: 9999;
}

.calendar-activity-type-wrapper
{
	float:left;
}
.calendar-left-details
{
	width:inherit;
}
.month-left .active-month, .month-right .active-month
{
	background: #009bff;
	color: white;
}

</style>
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
<h3 class="block-heading">
<?php
echo model::load("template/frontend")
->buildBreadCrumbs(Array(
			Array("Aktiviti",url::base("{site-slug}/aktiviti")),
			Array($year,url::base("{site-slug}/aktiviti/$year")),
			Array(model::load("helper")->monthYear("month",$month))
						));
						?>
</h3>
<div class="block-content clearfix">
	<div class="page-content">
		<div class="page-description">
		Pi1M bertujuan memberi pendedahan kepada masyarakat tempatan terhadap teknologi komputer dan internet. Ia juga meningkatkan taraf ekonomi dan sosial masyarakat melalui kemudahan yang disediakan dan aktiviti yang dijalankan di PI1M. Berikut adalah senarai aktiviti yang di jalankan pada bulan ini. Klik pada aktiviti tersebut untuk maklumat lanjut.
		</div>
		<div class="page-sub-wrapper calendar-page clearfix">
			<div class="calendar-left-details">
				<!-- month and date picker. -->
				<div class="activity-year-select clearfix">
					<div class="month-left">
						<ul>
							<?php
							$monthLeft	= Array(1=>"Jan",2=>"Feb",3=>"Mac",4=>"Apr",5=>"Mei",6=>"Jun");
							foreach($monthLeft as $m=>$name):
							$active	= $month==$m?"class='active-month'":"";?>
							<li><a <?php echo $active;?> href='javascript:monthChange(<?php echo $m;?>);'><?php echo $name;?></a></li>
							<?php endforeach;?>
						</ul>
					</div>
					<div class="select-year-activity">
						<?php
						$yearR	= model::load("helper")->monthYear("year",date("Y")-4,date("Y")+1);
						$yearR	= array_combine( array_reverse(array_keys( $yearR )), array_reverse( array_values( $yearR ) ) );
						echo form::select("activityYear",$yearR,"onchange='yearChange();' class='dropdown' data-settings='{\"wrapperClass\":\"select-year\"}'",$year,false);?>
					</div>
					<div class="month-right">
						<ul>
							<?php
							$monthRight	= Array(7=>"Jul",8=>"Ogs",9=>"Sep",10=>"Okt",11=>"Nov",12=>"Dis");
							foreach($monthRight as $m=>$name):
							$active	= $month==$m?"class='active-month'":"";?>
							<li><a <?php echo $active;?> href='javascript:monthChange(<?php echo $m;?>);'><?php echo $name;?></a></li>
							<?php
							endforeach;
							?>
						</ul>
					</div>
				</div>
				<!-- <div class="calendar-heading-month">Bulan <span><?php echo $monthLabel;?></span></div> -->
				<?php
				if(!$res_activity):?>
				Tiada aktiviti untuk bulan ini.
				<?php else:
				## type loop.
				foreach($activityType as $type=>$typeName)
				{
					if(isset($res_activity[$type]))
					{?>
						<div class='calendar-activity-type-wrapper'>
						<div class='calendar-label-name'><?php echo $typeName;?></div>
						<div class='calendar-activity-list'>
						<ul>
					<?php
						## activity loop.
						foreach($res_activity[$type] as $row)
						{
							$total	= isset($participantList[$row['activityID']])?count($participantList[$row['activityID']]):0;
							$url	= model::load("helper")->buildDateBasedUrl($row['activitySlug'],$row['activityStartDate'],url::base("{site-slug}/aktiviti"));
							?>
						<li class="clearfix">
							<div class="activity-details-left">
								<div class="activity-name"><a href="<?php echo $url;?>"><?php echo $row['activityName'];?></a></div>
								<div class="activity-time-date">
									<?php echo model::load("helper")->frontendDate($row['activityStartDate']);?> hingga
									<?php echo model::load("helper")->frontendDate($row['activityEndDate']);?>
								</div>
							</div>
							<div class="activity-join-count">
								<i class="fa fa-user"></i> <?php echo $total;?>
							</div>
						</li>
						<?php
						}?>
						</ul>
						</div>
						</div> <!-- Wrapper ends -->
					<?php
					}/* /end of isset */
				}
				?>
				<?php endif;?>
				<!-- <div class="calendar-label-name">Kursus / Latihan</div>
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
				</ul>
				</div> -->
			</div>
			<!-- <div style="float:right;" class="calendar-right-mini-cal">
				<?php 
				## load calendar controller.
				controller::load("activity","calendar");
				?>
			</div> -->
		</div>
	</div><!-- /.page-content -->
</div>