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

function changeList()
{
 var list = $("#changeList").val(); 

 window.location.href = "?list="+list;
}

</script>
<h3 class='m-b-xs text-black'>
Activity : Overview
</h3>
<div class='well well-sm'>
Overview of your site activities like events and training. Click <a href='add'>here</a> to add new activity.
</div>
<div class='row'>
<div class='col-md-8'>
  <section class='panel panel-default'>
    <div class='panel-heading'>
      <div class='row'>
        <div class='col-sm-3 pull-right' style="text-align:right;">
          <?php echo form::select("changeList",Array("upcoming"=>"Upcoming Activities","recent"=>"Recent Activities"),"class='form-control' onchange='changeList();'",request::get("list"),"[SORT LISTING]");?>
        </div>
      </div>
    </div>
    <div class='table-responsive'>
      <table class='table'>
        <tr>
          <th style="width:15px;">No.</th>
          <th><?php
          $label  = request::get("list")?request::get("list"):"upcoming";
          $label  = !in_array($label,Array("upcoming","recent"))?"upcoming":$label;

          echo ucwords($label)." ";
          ;?>Activities</th>
          <th>Type</th>
          <th>Date</th>
          <th>Added by</th>
          <th width="25px"></th>
        </tr>
        <?php
        if($res_activity):
        $no = pagination::recordNo();
          ?>
        <?php foreach($res_activity as $row):
        $typeR  = Array(1=>"event",2=>"training");
        ?>
        <tr>
          <td><?php echo $no++;?>.</td>
          <td><?php echo $row['activityName'];?></td>
          <td><?php echo model::load("activity/activity")->type($row['activityType']);?></td>
          <td><?php echo date("j M Y",strtotime($row['activityStartDate']));?></td>
          <td><?php echo $row['userProfileFullName'];?></td>
          <td><a class='fa fa-search' href='<?php echo url::base("activity/view/".$typeR[$row['activityType']]."/$row[activityID]");?>'></a></td>
        </tr>

        <?php endforeach;?>
        <?php else:?>
        <tr>
          <td colspan="5" align="center">Looks like there's no upcoming activities yet.</td>
        </tr>
        <?php endif;?>
      </table>
    </div>
    <footer class='panel-footer'>
      <div class='row'>
        <div class='col-sm-12'>
        <?php echo pagination::link();?>
        </div>
      </div>
    </footer>
  </section>
</div>
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