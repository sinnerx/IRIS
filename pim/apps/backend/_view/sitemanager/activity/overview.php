<script type="text/javascript">
	/*
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
});*/

var base_url  = "<?php echo url::base();?>/";
var Calendar  = function(id,dateListID)
{
  this.record = {};
  this.dateClicked  = false;

  this.getDate  = function(year,month,date)
  {
    this.hideDayActivity();
    //no year was set, get by currentDate.
    if(!year)
    {
      //recursive
      this.getDate(currentDate['year'],currentDate['month']);
      return;
    }

    //if prev or next.
    if(year == "prev" || year == "next")
    {
      //get prev/next month/year.
      var theDate = new Date(currentDate['year'],currentDate['month']-1);
      
      var month_interv  = year == "prev"?-1:1;
      theDate.setMonth(theDate.getMonth()+month_interv);
      this.getDate(theDate.getFullYear(),theDate.getMonth()+1,1);
      return;
    }

    // ## main process ##
    //set currentDate
    currentDate['year']   = year;
    currentDate['month']  = month;
    currentDate['date']   = !date?currentDate['date']:date;

    //check in dateR first, else, retrieve it by ajax.
    if(dateR[year] && dateR[year][month])
    {
      console.log("calendar loaded the saved");
      context.prepareDate(dateR[year][month]);

      if(date)
      {
        //hide date label.
        $(".date").slideUp();
      }

      context.prepareLabel();
    }
    else
    {
      $.ajax({type:"GET",url:base_url+"ajax/activity/calendarGetDateList/"+year+"/"+month}).done(function(dateList)
      {
        var dateList  = JSON.parse(dateList);

        //store in dateR
        if(!dateR[year])
        {
          dateR[year] = {};
        }

        dateR[year][month]  = dateList;

        context.prepareDate(dateList);
        

        if(date)
        {
          //hide date label.
          $(".date").slideUp();
        }

        context.prepareLabel();
      });
    }
  }

  this.prepareLabel = function()
  {
    var year  = currentDate['year'];
    var month = currentDate['month'];
    var date  = currentDate['date'];
    console.log(month);
    var monthR =  {
        1:"JANUARI",
        2:"FEBRUARI",
        3:"MAC",
        4:"APRIL",
        5:"MEI",
        6:"JUN",
        7:"JULAI",
        8:"OGOS",
        9:"SEPTEMBER",
        10:"OKTOBER",
        11:"NOVEMBER",
        12:"DISEMBER" 
          };

    $(".month-now").html(monthR[month]+" "+year);
    

    //if this is first load, show date..
    if(!loaded || month - 1 == new Date().getMonth())
    {
      var date = new Date().getDate();
      loaded  = true;
      $(".date").slideDown();
      $(".date").html(date);
      $("."+dateListID+" li[data-date='"+month+"-"+date+"']").addClass("today");
    }
  }

  //dateList is passed by server on every month change request.
  this.prepareDate  = function(dateList)
  {
    var month = currentDate['month']-1;
    var year  = currentDate['year'];

    //get first date. of the calendar.
    var theDate = new Date(year,month,1);
    var theDate = new Date(year,month,1-theDate.getDay());

    //loop.
    var cond  = true;
    var col     = 1;
    var result  = "";
    var week  = 1;
    while(cond == true)
    {
      //create list.
      if(col == 1)
      {
        result  += "<ul>";
      }

      //main record.
      var date  = theDate.getDate();
      var disable = theDate.getMonth() != month?"disable":"";
      var data  = "data-date='"+(theDate.getMonth()+1)+"-"+date+"'";
      var exists  = theDate.getMonth() == month && dateList[date]?"exists":"";
      var total = exists != ""?"title='"+dateList[date].length+" aktiviti'":"";

      //save record for later use.
      if(dateList)
      {
        var ymd = year+'-'+(month)+'-'+date;
        this.record[ymd]  = dateList[date];
      }

      result  += "<li "+total+" "+data+" class='"+disable+" "+exists+"'>"+date+"<span></span></li>";

      //increment col and date.
      col++;
      theDate.setDate(theDate.getDate()+1);

      if(col == 8)
      {
        result  += "</ul>";//close ul.
        col   = 1;//reset col to 1.
        week++;//increment week

        //stop the loop.
        if(theDate.getMonth() != month)
        {
          cond  = false;
        }
      }
    }

    //append result to dateListID.
    $("#"+id+" ."+dateListID).html(result);

    //re-set view activity click in every ajax load.
    $(".cal-date .exists").mouseover(function()
    {
      if(!context.dateClicked)
      {
        var d = $(this).data("date").split("-")[1]; // due to m-d format.
        context.showDayActivity(d,month,year);
      }
    }).mouseout(function()
    {
      if(!context.dateClicked)
      {
        context.hideDayActivity();
      }
    }).click(function()
    {
      $(".exists.clicked").removeClass("clicked");
      if(context.dateClicked == $(this).data("date"))
      {
        return context.hideDayActivity();
      }

      context.dateClicked = $(this).data("date");
      var d = $(this).data("date").split("-")[1]; // due to m-d format.
      context.showDayActivity(d,month,year,true);
      $(this).addClass("clicked");
    });
  }

  //return eg. Array(year:2013,month:3,date:28) [2013/mac/28th]
  this.getCurrentDate = function()
  {
    var date  = new Date();
    return {year:date.getFullYear(),month:(date.getMonth()+1),date:date.getDate()};
  }

  this.hideDayActivity = function()
  {
    context.dateClicked = false;

    //hide detail table and show back.
    $("#table-calendar-detail").hide();
    $("#table-calendar-main").show();
  }

  this.showDayActivity = function(d,m,Y,full)
  {
    var ymd = Y+"-"+m+"-"+d;

    $("#table-calendar-detail").html(""); // clear

    var header = '<tr><th style="width:15px;">No.</th><th>Name</th><th>Type</th><th>Date</th><th>Added by</th><th width="25px"></th></tr>';

    $("#table-calendar-detail").append(header);

    // and append each record.
    for(i in this.record[ymd])
    {
      var row = this.record[ymd][i];

      var url = pim.base_url+"activity/view/"+row.activityTypeEng+"/"+row.activityID;
      var dateLabel = row.activityStartDate != row.activityEndDate?row.activityStartDate+" to "+row.activityEndDate:row.activityStartDate+" haribulan";
      var a  = $("<a class='list-group-item text-ellipsis' href='"+url+"'><div>"+row.activityName+"</div><div>"+dateLabel+"</div></a>");

      var a = $("<tr></tr>");
      a.append("<td>"+(Number(i)+1)+".</td>");
      a.append("<td>"+row.activityName+"</td>");
      a.append("<td>"+row.activityType+"</td>");
      a.append("<td>"+dateLabel+"</td>");
      a.append("<td>"+row.activityCreatedUser+"</td>");
      a.append("<td><a href='"+url+"' class='fa fa-search'></a></td>");
      $("#table-calendar-detail").append(a);
    }

    // full show.
    if(full)
    {
      $("#table-calendar-detail").css("opacity",1);
    }

    $("#table-calendar-detail").show();
    $("#table-calendar-main").hide();
  } 

  //construct this variable on calendar object creation.
  var dateR   = {};
  var currentDate = this.getCurrentDate();
  var id      = id;
  var dateListID  = dateListID;
  var $     = jQuery;
  var context   = this;
  var loaded    = false;
}

var calendar = new Calendar("calendar","cal-date");

$(document).ready(function()
{
  calendar.getDate();
});

function changeList()
{
 var list = $("#changeList").val(); 

 window.location.href = "?list="+list;
}

</script>
<style type="text/css">

.cal-date ul, .cal-day ul
{
  list-style: none;
  display:block;
  height:25px;
  padding:0px;
}
.cal-day ul
{
  height: 15px;
}
.cal-date ul li
{
  float:left;
  width:14%;
  text-align: center;
  border-left:1px solid #ced8e8;
}
.cal-day ul li
{
  float:left;
  width: 14%;
  text-align: center; 
}
.cal-date ul
{
  background: white;
}

#calendar .month
{
  height:25px;
  background: white;
}
#calendar .month > div, #calendar .month > a
{
  float:right;
}
#calendar .month .month-prev, #calendar .month .month-next
{
  padding:5px;
}

/* cal-date */
.cal-date ul li
{

}

.cal-date .exists span
{
  display: block;
  position: absolute;

  width:10px;
  height:10px;
  right:0px;
  top:-4px;

  /* triangle credited to http://css-tricks.com/snippets/css/css-triangle */
  width: 0; 
  height: 0; 
  border-top: 6px solid transparent;
  border-bottom: 6px solid transparent;
  border-left:6px solid #dc5560;

  -webkit-transform:rotate(-45deg);
  -moz-transform:rotate(-45deg);
}

.cal-date .exists
{
  cursor: pointer;
  /*font-weight: bold;*/
  color: #0062a1;
  position: relative;
}

.cal-date .exists.clicked
{
  font-weight: bold;
}

.list-group .list-group-item
{
  border-bottom: 1px solid #e0e6f0;
}

</style>
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
      <table class='table' id='table-calendar-main'>
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
          <td><?php echo date("j M Y",strtotime($row['activityStartDate']));?> to <?php echo date("j M Y",strtotime($row['activityEndDate']));?></td>
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
    <div class='table-responsive'>
      <table class='table' id='table-calendar-detail'>

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
      <div id="calendar" class="bg-light dker m-l-n-xxs m-r-n-xxs">
        <div class="month clearfix">
          <a href='#' class="month-next fa fa-arrow-right" onclick='calendar.getDate("next");'></a>
            <div class="month-now"></div>
          <a href='#' class="month-prev fa fa-arrow-left" onclick='calendar.getDate("prev");'></a>
        </div>
        <div class='cal-day'>
          <ul>
            <li><div>SUN</div></li>
            <li><div>MON</div></li>
            <li><div>TUE</div></li>
            <li><div>WED</div></li>
            <li><div>THU</div></li>
            <li><div>FRI</div></li>
            <li><div>SAT</div></li>
          </ul>
        </div>
        <div class='cal-date-container' style="position:relative;">
          <div class="cal-date clearfix"> 
          </div>
        </div>
      </div>
    </section>
  </div>
</div>
