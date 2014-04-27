<script type="text/javascript">
var base_url	= "<?php echo url::base('{site-slug}');?>/";
var Calendar	= function(id,dateListID)
{
	this.getDate	= function(year,month,date)
	{
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

			result	+= "<li "+data+" class='"+disable+"'>"+date+"</li>";

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
	}

	//return eg. Array(year:2013,month:3,date:28) [2013/mac/28th]
	this.getCurrentDate	= function()
	{
		var date	= new Date();
		return {year:date.getFullYear(),month:(date.getMonth()+1),date:date.getDate()};
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
			<li><div>Sun</div></li>
			<li><div>Mon</div></li>
			<li><div>Tue</div></li>
			<li><div>Wed</div></li>
			<li><div>Thu</div></li>
			<li><div>Fri</div></li>
			<li><div>Sat</div></li>
		</ul>
	</div>
	<div class="cal-date clearfix">	
	<?php /*
	<ul>
		<li class="disable">29</li>
		<li class="disable">30</li>
		<li class="disable">31</li>
		<li>1</li>
		<li class="today">2</li>
		<li>3</li>
		<li>4</li>
	</ul>
	<ul>
		<li>5</li>
		<li>6</li>
		<li>7</li>
		<li>8</li>
		<li>9</li>
		<li>10</li>
		<li>11</li>
	</ul>

	<ul>
		<li>12</li>
		<li>13</li>
		<li>14</li>
		<li>15</li>
		<li>16</li>
		<li>17</li>
		<li>18</li>
	</ul>
	<ul>
		<li>19</li>
		<li>20</li>
		<li>21</li>
		<li>22</li>
		<li>23</li>
		<li>24</li>
		<li>25</li>
	</ul>
	<ul>
		<li>26</li>
		<li>27</li>
		<li>28</li>
		<li>29</li>
		<li>30</li>
		<li>31</li>
		<li class="disable">1</li>
	</ul>
	*/?>
	</div>
</div>