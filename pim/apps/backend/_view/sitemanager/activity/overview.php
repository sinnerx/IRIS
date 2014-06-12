<link rel="stylesheet" href="<?php echo url::asset("_scale/js/calendar/bootstrap_calendar.css");?>" type="text/css" />
<script type="text/javascript">
	
$(document).ready( function(){
  var cTime = new Date(), month = cTime.getMonth()+1, year = cTime.getFullYear();

	theMonths = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

	theDays = ["S", "M", "T", "W", "T", "F", "S"];
    events = [
      [
        "4/"+month+"/"+year, 
        'Meet a friend', 
        '#', 
        '#177bbb', 
        'Contents here'
      ],
      [
        "7/"+month+"/"+year, 
        'Kick off meeting!', 
        '#', 
        '#1bbacc', 
        'Have a kick off meeting with .inc company'
      ],
      [
        "17/"+month+"/"+year, 
        'Milestone release', 
        '#', 
        '#fcc633', 
        'Contents here'
      ],
      [
        "19/"+month+"/"+year, 
        'A link', 
        'http://www.google.com', 
        '#e33244'
      ]
    ];
    $('#calendar').calendar({
        months: theMonths,
        days: theDays,
        events: events,
        popover_options:{
            placement: 'top',
            html: true
        }
    });
});

</script>
<h3 class='m-b-xs text-black'>
Activity : Overview
</h3>
<div class='well well-sm'>
Overview of your site activities like events and training. Click <a href='add'>here</a> to add new activity.
</div>
<div class='row'>
	<div class="col-md-4 pull-right">
		<section class="panel b-light">
		<header class="panel-heading"><strong>Calendar</strong></header>
			<div id="calendar" class="bg-light dker m-l-n-xxs m-r-n-xxs"></div>
			<div class="list-group">
				<a href="#" class="list-group-item text-ellipsis">
				<span class="badge bg-warning">7:30</span> 
				Meet a friend
				</a>
				<a href="#" class="list-group-item text-ellipsis"> 
				<span class="badge bg-success">9:30</span> 
				Have a kick off meeting with .inc company
				</a>
			</div>
		</section>
	</div>
</div>
<script src="<?php echo url::asset("_scale/js/calendar/bootstrap_calendar.js");?>"></script>