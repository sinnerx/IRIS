var $news = jQuery;
$news(function () {
     // start the ticker 
	$news('#js-news').ticker();
	
	// hide the release history when the page loads
	$news('#release-wrapper').css('margin-top', '-' + ($news('#release-wrapper').height() + 20) + 'px');

	// show/hide the release history on click
	$news('a[href="#release-history"]').toggle(function () {	
		$news('#release-wrapper').animate({
			marginTop: '0px'
		}, 600, 'linear');
	}, function () {
		$news('#release-wrapper').animate({
			marginTop: '-' + ($news('#release-wrapper').height() + 20) + 'px'
		}, 600, 'linear');
	});	
	
	$news('#download a').mousedown(function () {
		_gaq.push(['_trackEvent', 'download-button', 'clicked'])		
	});
});

// code for calendar.
var Calendar	= function(id,dateListID,base_url)
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