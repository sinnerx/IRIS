<link rel="stylesheet" type="text/css" href="<?php echo url::asset('frontend/css/pim_calendar.css');?>">
<script type="text/javascript">
// scripts at frontend/js/site.js
var calendar = new Calendar("calendar-rght","cal-date","<?php echo url::base('{site-slug}');?>/");

$(document).ready(function()
{
	calendar.getDate();
});

</script>

<div id="calendar-rght">
	<div class="date" style='display:none;'>24</div>
	<div class="month clearfix">
		<div class="month-prev" onclick='calendar.getDate("prev");'></div>
		<div class="month-now"></div>
		<div class="month-next" onclick='calendar.getDate("next");'></div>
	</div>
	<div class='cal-day'>
		<ul>
			<li><div>AHA</div></li>
			<li><div>ISN</div></li>
			<li><div>SEL</div></li>
			<li><div>RAB</div></li>
			<li><div>KHA</div></li>
			<li><div>JUM</div></li>
			<li><div>SAB</div></li>
		</ul>
	</div>
	<div class='cal-date-container' style="position:relative;z-index:-1;">
		<div class='cal-date-detail'>
			<ul>
			</ul>
		</div>
		<div class="cal-date clearfix">	
		</div>
	</div>
</div>