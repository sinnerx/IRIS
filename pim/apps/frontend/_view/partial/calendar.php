<script type="text/javascript">
var base_url	= "<?php echo url::base('{site-slug}');?>/";
var Calendar	= function(id,dateListID)
{
	this.record	= {};
	this.dateClicked	= false;

	this.getDate	= function(year,month,date)
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
			var theDate	= new Date(currentDate['year'],currentDate['month']-1);
			
			var month_interv	= year == "prev"?-1:1;
			theDate.setMonth(theDate.getMonth()+month_interv);
			this.getDate(theDate.getFullYear(),theDate.getMonth()+1,1);
			return;
		}

		// ## main process ##
		//set currentDate
		currentDate['year']		= year;
		currentDate['month']	= month;
		currentDate['date']		= !date?currentDate['date']:date;

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
			$.ajax({type:"GET",url:base_url+"ajax/partial/calendarGetDateList/"+year+"/"+month}).done(function(dateList)
			{
				var dateList	= JSON.parse(dateList);

				//store in dateR
				if(!dateR[year])
				{
					dateR[year]	= {};
				}

				dateR[year][month]	= dateList;

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

	this.prepareLabel	= function()
	{
		var year	= currentDate['year'];
		var month	= currentDate['month'];
		var date	= currentDate['date'];
		console.log(month);
		var monthR =	{
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
			loaded	= true;
			$(".date").slideDown();
			$(".date").html(date);
			$("."+dateListID+" li[data-date='"+month+"-"+date+"']").addClass("today");
		}
	}

	//dateList is passed by server on every month change request.
	this.prepareDate	= function(dateList)
	{
		var month	= currentDate['month']-1;
		var year	= currentDate['year'];

		//get first date. of the calendar.
		var theDate	= new Date(year,month,1);
		var theDate	= new Date(year,month,1-theDate.getDay());

		//loop.
		var cond	= true;
		var col 		= 1;
		var result	= "";
		var week	= 1;
		while(cond == true)
		{
			//create list.
			if(col == 1)
			{
				result	+= "<ul>";
			}

			//main record.
			var date	= theDate.getDate();
			var disable	= theDate.getMonth() != month?"disable":"";
			var data	= "data-date='"+(theDate.getMonth()+1)+"-"+date+"'";
			var exists	= theDate.getMonth() == month && dateList[date]?"exists":"";
			var total	= exists != ""?"title='"+dateList[date].length+" aktiviti'":"";

			//save record for later use.
			if(dateList)
			{
				var ymd	= year+'-'+(month)+'-'+date;
				this.record[ymd]	= dateList[date];
			}

			result	+= "<li "+total+" "+data+" class='"+disable+" "+exists+"'>"+date+"<span></span></li>";

			//increment col and date.
			col++;
			theDate.setDate(theDate.getDate()+1);

			if(col == 8)
			{
				result	+= "</ul>";//close ul.
				col 	= 1;//reset col to 1.
				week++;//increment week

				//stop the loop.
				if(theDate.getMonth() != month)
				{
					cond	= false;
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
				var d	= $(this).data("date").split("-")[1]; // due to m-d format.
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
			var d	= $(this).data("date").split("-")[1]; // due to m-d format.
			context.showDayActivity(d,month,year,true);
			$(this).addClass("clicked");
		});
	}

	//return eg. Array(year:2013,month:3,date:28) [2013/mac/28th]
	this.getCurrentDate	= function()
	{
		var date	= new Date();
		return {year:date.getFullYear(),month:(date.getMonth()+1),date:date.getDate()};
	}

	this.hideDayActivity = function()
	{
		context.dateClicked = false;
		$(".cal-date-detail").css("opacity","0.6").hide();
	}

	this.showDayActivity = function(d,m,Y,full)
	{
		var ymd	= Y+"-"+m+"-"+d;

		$(".cal-date-detail ul").html(""); // clear

		// and append each record.
		for(i in this.record[ymd])
		{
			var row	= this.record[ymd][i];

			var li	= $("<li></li>");
			var url	= row.activityUrl;
			var dateLabel	= row.activityStartDate != row.activityEndDate?row.activityStartDate+" hingga "+row.activityEndDate:row.activityStartDate+" haribulan";
			li.append("<div><a href='"+url+"'>"+row.activityName+"</a></div>");
			li.append("<div>"+dateLabel+"</div>");
			$(".cal-date-detail ul").append(li);
		}

		// full show.
		if(full)
		{
			$(".cal-date-detail").css("opacity",1);
		}

		$(".cal-date-detail").show();
	}

	//construct this variable on calendar object creation.
	var dateR		= {};
	var currentDate	= this.getCurrentDate();
	var id			= id;
	var dateListID	= dateListID;
	var $			= jQuery;
	var context		= this;
	var loaded		= false;
}

var calendar = new Calendar("calendar-rght","cal-date");

$(document).ready(function()
{
	calendar.getDate();
});

</script>
<style type="text/css">
#calendar-rght
{
}
.cal-day ul
{
	list-style: none;
	padding:0px;
	margin:0px;
}
.cal-day
{
	width:100%;
}
.cal-day li
{
	width:14.2%;
	text-align: center;
	float:left;
	font-size:0.8em;
	color: white;
}
.cal-day li div
{
	padding:3px;
}


#calendar-rght .month
{
	background:#0062a1;
	padding:0px;
}

.cal-date
{
	background: #009bff;
}

.cal-date ul li
{
	cursor: default;
}

.cal-date .exists
{
	cursor: pointer;
	/*font-weight: bold;*/
	color: #0062a1;
	position: relative;
	/*background: #bee693;
	color: #3a7931;
	box-shadow: 0px 0px 5px #557238;*/
}
.cal-date .exists:before
{
	content: " ";
	display: block;
	background: red;
	position: absolute;
	top:3px;
	right:3px;
}
.cal-date .exists.clicked
{
	font-weight: bold;
}
/*.cal-date-detail
{
	background:#f2f2f2;
	position:absolute;
	right:100%;
	top:20px;
	padding-right:0px;
	width:100%;
	display:none;
	opacity:0.6;
}*/

/*.cal-date-detail ul
{
	background:#f2f2f2;
	list-style: none;
	padding:0px;
	margin: 0px;
	padding:5px;
	box-shadow: -2px 0px 5px #c9c9c9;
	border-top-right-radius: 5px;
	border-bottom-right-radius: 5px;
}*/
.cal-date-detail a
{
	color:inherit;
}
.cal-date-detail li
{
	border-bottom:1px solid #dddddd;
	padding:5px;
}
.cal-date-detail li:last-child
{
	border-bottom: 0px;
}
.cal-date-detail li > div:first-child
{
	color: #606060;
	font-weight: bold;
}
.cal-date-detail li > div:last-child
{
	opacity: 0.7;
	font-size:0.8em;
}


/* CSS UPDATE BELOW */
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

.cal-date-detail{

    display: none;
    opacity: 0.6;
    padding-right: 0;
    position: absolute;
    right: 100%;
    top: 20px;
    width: 100%;	
}


.cal-date-detail ul{
   color: #fff;
    list-style: none outside none;
    margin: 0;
    padding: 5px;
	border-right:7px solid #0062a1;

background: #009bff;
}

.cal-date-detail a{
color:#FFF;	
}



</style>

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