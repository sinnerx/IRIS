<link rel="stylesheet" type="text/css" href="<?php echo url::asset('_templates/css/aktiviti.css');?>">
<script type="text/javascript" src='<?php echo url::asset("_templates/js/jquery.easydropdown.js");?>'></script>
<style type="text/css">
	
.lft-container
{
	width: inherit;
}
.calendar-right-mini-cal
{
	position: relative;
	z-index: 9999;
}

</style>
<h3 class="block-heading">Kalendar Aktiviti</h3>
<div class="block-content clearfix">
	<div class="page-content">
		<div class="page-description">
		Lorem ipsum dolor sit amet, maiores ornare ac fermentum, imperdiet ut vivamus a, nam lectus at nunc. Quam euismod sem, semper ut potenti pellentesque quisque. In eget sapien sed, sit duis vestibulum ultricies, placerat morbi amet vel, nullam in in lorem vel. In molestie elit dui dictum, praesent nascetur pulvinar sed, in dolor pede in aliquam, risus nec error quis pharetra. Eros metus quam augue suspendisse, metus rutrum risus erat in.  In ultrices quo ut lectus, etiam vestibulum urna a est, pretium luctus euismod nisl, pellentesque turpis hac ridiculus massa. Venenatis a taciti dolor platea, curabitur lorem platea urna odio.
		</div>
		<div class="page-sub-wrapper calendar-page clearfix">
			<div class="calendar-left-details">
				<div class="calendar-heading-month">Bulan <span><?php echo $monthLabel;?></span></div>
				<?php
				if(!$res_activity):?>
				Tiada aktiviti untuk bulan ini.
				<?php else:
				## type loop.
				foreach($activityType as $type=>$typeName)
				{
					if(isset($res_activity[$type]))
					{?>
						<div class='calendar-label-name'><?php echo $typeName;?></div>
						<div class='calendar-activity-list'>
						<ul>
					<?php
						## activity loop.
						foreach($res_activity[$type] as $row)
						{?>
						<li class="clearfix">
							<div class="activity-details-left">
								<div class="activity-name"><a href="#"><?php echo $row['activityName'];?></a></div>
								<div class="activity-time-date">
									<?php echo date("d F Y",strtotime($row['activityStartDate']));?> hingga
									<?php echo date("d F Y",strtotime($row['activityEndDate']));?>
								</div>
							</div>
							<div class="activity-join-count">
								<i class="fa fa-user"></i> 9
							</div>
						</li>
						<?php
						}?>
						</ul>
						</div>
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
			<div style="float:right;" class="calendar-right-mini-cal">
				<?php 
				## load calendar controller.
				controller::load("activity","calendar");
				?>
			</div>
		</div>
	</div><!-- /.page-content -->
</div>