<script type="text/javascript">
	
var activity	= function()
{
	this.showTypeDetail	= function(type)
	{
		var r	= {1:"event",2:"training"};
		$("#activityType").val(type);
		$("#type-event, #type-training").hide();
		$("#type-"+r[type]).show();

		pim.uriHash.set(r[type]);
	}

	this.disableAddress	= function()
	{
		if($("#activityAddressFlag")[0].checked)
		{
			$("#activityAddress").attr("disabled",true);
			$("#activityAddress").val($("#siteInfoAddress").html());
		}
		else
		{
			$("#activityAddress").removeAttr("disabled");
			$("#activityAddress").val("");
		}
	}

	this.form	= new function()
	{
		this.select	= function(name,arrR,attr,val)
		{
			var s	= "<select "+attr+" id='"+name+"' name='"+name+"'>";
			var attr = attr?attr:"";
			for(var i in arrR)
			{
				var sel	= val==i?"selected":'';
				s	+= "<option "+sel+" value='"+i+"'>"+arrR[i]+"</option>";
			}
			s	+= "</select>";
			return s;
		}
	}

	this.datePicker = new function()
	{
		var t			= this;
		this.dateTimeType = 1;
		this.startDate	= null;
		this.endDate	= null;
		this.dateList	= [];
		this.timeList	= {}; // date as key.
		this.monthList	= ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Okt','Nov','Dis'];
		this.dayList	= ['Sun','Mon','Tue','Wed','Thu','Fri','Sat','Sun'];

		this.selectRangeDate	= function(date)
		{
			var selected	= $("#date-"+date);

			// startdate.
			if(!this.startDate)
			{
				this.startDate	= date;
				selected.addClass("clicked-start");
				this.setCommand("Now please select an end date.");
				$(".activity-date-from span").html(date)
			}
			// end date choose
			else if(!this.endDate)
			{
				this.endDate	= date;
				selected.addClass("clicked-end");
				this.setCommand("You may now submit the date, or you can <a href='javascript:activity.datePicker.reset();' class='label label-danger'>reset</a> if you want. You may also unselect date within the range. By clicking in one of the date.");
				$(".activity-date-to span").html(date);

				// date list based on range.
				this.createDateList(this.startDate,this.endDate);
			}
		}

		this.reset	= function()
		{
			//reset vars.
			this.startDate	= null;
			this.endDate	= null;
			this.dateList	= [];
			this.timeList	= {};

			//remove selection.
			$(".clicked-start, .clicked-end").removeClass("clicked-start").removeClass("clicked-end");
			$(".date-selected").removeClass("date-selected");

			this.setCommand("Select start date");

			//empty.
			$(".activity-date-from span, .activity-date-to span, .activity-date-total span").html('');

			$("#date-time").html("");
		}

		this.createDateList	= function(start,end)
		{
			var startD	= new Date(start);
			var endD	= new Date(end);

			var total	= ((endD-startD)/86400000);

			var no	= 1;
			while(startD.getTime() <= endD.getTime())
			{
				var ymd	= startD.getFullYear()+"-"+activity.utils.zeronate((startD.getMonth()+1),2)+"-"+activity.utils.zeronate(startD.getDate(),2);

				// increment.
				startD.setDate(startD.getDate()+1);

				//loopbreaker.
				if(no==100)
				{
					alert(0);
					break;
				}

				// select the date.
				this.selectDate(ymd);
				no++;
			}

			// recreate time everytime got changes.
			this.createTimeList(this.dateTimeType);
		}

		this.parseTime	= function(time)
		{
			var h	= parseInt(time.split(":")[0]);
			var m	= parseInt(time.split(":")[1]);
			var ap	= h>=12?"PM":"AM";
				h	= h>=12?h-12:h;

			return [h,m,ap];
		}
		this.createTimePicker	= function(name,time,date,type)
		{
			var hR	= {0:12,1:1,2:2,3:3,4:4,5:5,6:6,7:7,8:8,9:9,10:10,11:11};
			var mR	= {0:"00",15:"15",30:"30",45:"45",59:"59"};
			var apR	= {AM:"AM",PM:"PM"};

			var time	= time?time:"00:00";
				time	= this.parseTime(time);

			var type	= type?"data-type='"+type+"'":"";
			var date	= date?"data-date='"+date+"'":"";

			var picker = [
				activity.form.select(name+"H",hR,type+" "+date,time[0]),
				activity.form.select(name+"M",mR,type+" "+date,time[1]),
				activity.form.select(name+"AP",apR,type+" "+date,time[2])
			];

			// save into hidden input.

			return picker.join("");
		}

		//get time from h,m,ap (am pm)
		this.getTime	= function(h,m,ap)
		{
			var h = ap == "PM"?parseInt(h)+12:h;
				h = activity.utils.zeronate(h,2);
			var m = activity.utils.zeronate(m,2);

			return h+":"+m;
		}

		this.changeType	= function(type)
		{
			if(this.dateTimeType != type && type == 2 && !confirm("Changing this to single time schema will reset all existing time for other date"))
			{
				return false;
			}

			if(this.dateTimeType == 2)
			{
				this.overwriteSingle();
			}
			
			// set curr.
			this.dateTimeType	= type;
			this.createTimeList(type);
		}

		this.createTimeList	= function(type)
		{
			// update total.
			$(".activity-date-total span").html(this.dateList.length);

			var type = type?type:1;
			var trs	= "";
			trs	+= "<tr><th colspan='2'>Date</th>";
			trs	+= "<th>Time schema : <label><input type='radio' value='1' "+(type == 1?"checked":"")+" name='activityDateTimeType' id='activityDateTimeType' onclick='return activity.datePicker.changeType(1);'/> Multiple</label></th>";
			trs	+= "<th><label><input type='radio' value='2' "+(type == 2?"checked":"")+" name='activityDateTimeType' id='activityDateTimeType' onclick='return activity.datePicker.changeType(2);' /> Single</label></th>";
			trs	+= "</tr>";

			for(var i in this.dateList)
			{
				var time		= this.timeList[this.dateList[i]];

				var startTime	= time?time['start']:"00:00";
				var endTime		= time?time['end']:"23:59";

				var selector	= this.createTimePicker("timestart",startTime,this.dateList[i],"start")+" ~ "+this.createTimePicker("timeend",endTime,this.dateList[i],"end");

				var d = new Date(this.dateList[i]);
				trs	+= "<tr id='date-time-row-"+this.dateList[i]+"'>";
				trs += "<td>"+this.dayList[d.getDay()]+", </td><td>&nbsp;"+activity.utils.zeronate(d.getDate(),2)+" "+this.monthList[d.getMonth()]+" <a class='i i-cross2' href='javascript:activity.datePicker.removeDate(\""+this.dateList[i]+"\");'></a></td>";
				
				if(type == 1)
				{
					trs += "<td colspan='2'>"+selector+"</td>";
				}
				else
				{
					if(i == 0)
					{
						trs	+= "<td colspan='2' rowspan='"+this.dateList.length+"'>";
						trs	+= selector;
						trs += "</td>";
					}
				}
				trs += "</tr>";
			}

			$("#date-time").html(trs);

			//update timeList on every changes.
			$("#date-time select").change(function()
			{
				t.updateTime(this);
			});
		}

		this.updateTime	= function(obj)
		{
			var type	= $(obj).data("type");
			var date	= $(obj).data("date");
			var row		= $("#date-time-row-"+date);
			var h 		= row.find("#time"+type+"H").val();
			var m		= row.find("#time"+type+"M").val();
			var ap		= row.find("#time"+type+"AP").val();

			//set start/end time.
			t.timeList[date][type]	= t.getTime(h,m,ap);

			if(this.dateTimeType == 2)
			{
				this.overwriteSingle();
			}
		}

		this.overwriteSingle = function()
		{
			var no = 1;
			for(var d in this.timeList)
			{
				if(no == 1)
				{
					var first	= this.timeList[d];
				}

				this.timeList[d]['start']	= first['start'];
				this.timeList[d]['end']		= first['end'];

				no++;
			}
		}

		this.selectDate	= function(d,timeStart,timeEnd)
		{
			var timeStart	= timeStart?timeStart:"00:00";
			var timeEnd		= timeEnd?timeEnd:"23:00";

			$("#date-"+d).addClass("date-selected");
			
			$("#date-"+d+".date-selected").click(function()
			{
				activity.datePicker.removeDate(d);
			});

			// add into selection along with start and end date.
			if(!pim.func.inArray(d,this.dateList))
			{
				this.dateList.push(d);
				this.timeList[d]	= {start:timeStart,end:timeEnd};
			}
		}

		this.removeDate	= function(d)
		{
			var el	= $("#date-"+d);

			// cannot remove start and end.
			if(!el.hasClass("clicked-start") && !el.hasClass("clicked-end"))
			{
				el.removeClass("date-selected");
				this.dateList	= pim.func.arrayRemoveElement(this.dateList,d);

				//delete this date.
				delete this.timeList[d];

				//recreate timelist.
				this.createTimeList(this.dateTimeType);
			}
		}

		this.refresh = function()
		{
			$("#date-"+this.startDate).addClass("clicked-start");
			$("#date-"+this.endDate).addClass("clicked-end");

			for(d in this.timeList)
			{
				this.selectDate(d,this.timeList[d]['start'],this.timeList[d]['end']);
			}

			this.createTimeList(this.dateTimeType);
		}

		this.setCommand = function(txt)
		{
			$("#command-label").html(txt);
		}


		this.submit		= function(e)
		{
			var result	= {
				startDate:this.startDate,
				endDate:this.endDate,
				dateTimeType:$("#activityDateTimeType:checked").val(),
				timeList:this.timeList
			};

			//if end date haven't been chosen yet.
			if(!this.endDate)
			{
				alert("Please complete the date picking.");
				return e.stopPropagation();
			}

			if(d = this.checkMistimed())
			{
				alert("Got time range problem with date : "+d);
				return e.stopPropagation();
			}

			JSON.stringify(result);

			$("#activityDateTime").val(JSON.stringify(result));
			this.updateMainSummary();
		}

		this.checkMistimed	= function()
		{
			for(var d in this.timeList)
			{
				var start = this.timeList[d]['start'];
				var end	  = this.timeList[d]['end'];

				if(new Date(d+" "+start) >= new Date(d+" "+end))
				{
					return d;
				}
			}

			return false;
		}

		// use to repopulate based on existing data in #activityDateTime
		this.initiate	= function()
		{
			if($("#activityDateTime").val() != "")
			{
				var result	= JSON.parse($("#activityDateTime").val());

				this.initiateData(result);
				this.setCommand("You may edit the existing submitted date and time for the activity. You may <a href='javascript:activity.datePicker.reset();' class='label label-danger'>reset</a> too.");

				$(".activity-date-from span").html(this.startDate);
				$(".activity-date-to span").html(this.endDate);

				var d	= new Date(this.startDate);		
			}
			else
			{
				var d	= new Date();
			}

			$(".date-container").load(pim.base_url+"ajax/activity/datePicker_calendar/"+d.getFullYear()+"/"+(d.getMonth()+1));
		}

		this.initiateData	= function(result)
		{
			var result	= !result?JSON.parse($("#activityDateTime").val()):result;

			this.startDate	= result.startDate;
			this.endDate	= result.endDate;
			this.timeList	= result.timeList;
			this.dateTimeType = result.dateTimeType;
			for(var d in this.timeList)
			{
				this.dateList.push(d);
			}
			this.updateMainSummary();
		}

		this.updateMainSummary = function()
		{
			$(".summary_title").html("Date summary :");
			$(".summary_from").html(this.startDate);
			$(".summary_to").html(this.endDate);
			$(".summary_total").html(this.dateList.length);
			$("#activityDateSummary").show();

			var trs	= "";
			for(var d in this.timeList)
			{
				var start	= this.parseTime(this.timeList[d]['start']);
				var end		= this.parseTime(this.timeList[d]['end']);

				// calculate hours
				var hours		= (new Date(d+" "+this.timeList[d]['end']) - new Date(d+" "+this.timeList[d]['start']))/3600000;
				var d		= new Date(d);

				start[0]	= start[0] == 0?12:start[0];
				end[0]		= end[0] == 0?12:end[0];


				trs	+= "<tr>";
				trs += "<td>"+this.dayList[d.getDay()]+", "+d.getDate()+" "+this.monthList[d.getMonth()]+"</td>";
				trs += "<td>"+activity.utils.zeronate(start[0],2)+":"+activity.utils.zeronate(start[1],2)+" "+start[2]+"</td>";
				trs += "<td>"+activity.utils.zeronate(end[0],2)+":"+activity.utils.zeronate(end[1],2)+" "+end[2]+"</td>";
				trs += "<td>("+hours+" hours)</td>";
			}

			$("#summary_datelist").html(trs);
		}
	}

	this.utils	= new function()
	{
		this.zeronate	= function(no,total)
		{
			var no	= no+"";
			var z = "";
			var t = total-no.length;

			for(i=0;i<t;i++)
			{
				z = z+"0";
			}

			return z+no;
		}
	}
}

var activity = new activity();

$(document).ready(function()
{
	if($("#activityDateTime").val() != "")
	{
		activity.datePicker.initiateData();
	}

	activity.showTypeDetail(<?php echo $row['activityType'];?>);
});

pim.uriHash.addCallback({"event":function(){activity.showTypeDetail(1)},"training":function(){activity.showTypeDetail(2)}});

</script>
<style type="text/css">
	
#summary_datelist
{
	width: 100%;
}
#summary_datelist td
{
	border-bottom:1px solid #d6dce9;
}
#summary_basic
{
	width:100%;
}
#summary_basic td
{
	width: 25%;
}
#summary_basic tr td:nth-child(1), #summary_basic tr td:nth-child(3)
{
	font-weight: bold;
}

</style>
<h3 class='m-b-xs text-black'>
Edit Activity
</h3>
<div class='well well-sm'>
Edit activity
</div>
<?php echo flash::data();?>
<?php
if(!flash::data() && $requestFlag == true):?>
<div class='alert alert-danger'>
Content is waiting for approval
</div>
<?php endif;?>
<form method='post' onsubmit="">
<div class='row'>
	<div class='col-sm-7'>
		<div class='row'>
			<div class='col-sm-6'>
				<div class='form-group'>
					<label>
						1. Activity Name <?php echo flash::data("activityName");?>
					</label>
					<?php echo form::text("activityName","class='form-control'",$row['activityName']);?>
				</div>
			</div>
			<div class='col-sm-3'>
				<div class='form-group'>
					<label>5. Participation <?php echo flash::data("activityParticipation");?></label>
					<?php echo form::select("activityParticipation",Array(1=>"Open",2=>"Only for site member"),"class='form-control'",$row['activityParticipation']);?>
				</div>
			</div>
			<div class='col-sm-3'>
				<div class='form-group'>
					<label>2. Type <?php echo flash::data("activityType");?></label>
					<?php
					$conv	= Array("event"=>1,"training"=>2);
					?>
					<?php echo form::select("activityType",Array(1=>"Event",2=>"Training"),"disabled onchange='activity.showTypeDetail(this.value);' class='form-control'",$row['activityType']);?>
				</div>
			</div>
		</div>
		<div class='row'>
			<div class='col-sm-6'>
				<div class='form-group'>
				<label>3. Description</label>
				<?php echo form::textarea("activityDescription","class='form-control'",$row['activityDescription']);?>
				</div>

				<div class='form-group'>
				<label style='display:block;'>4. Where? (Address) 
					<span class='pull-right'>Use site address <input onclick='activity.disableAddress();' type='checkbox' id='activityAddressFlag' name='activityAddressFlag' value='1' /></span>
				</label>
				<?php echo form::textarea("activityAddress","class='form-control'");?>
				<div id='siteInfoAddress' style='display:none;'><?php echo $siteInfoAddress;?></div>
				</div>
			</div>
			<div class='col-sm-6'>
				<div class='form-group'>
					<label>6. Date
						<a href='<?php echo url::base("ajax/activity/datePicker");?>' data-toggle='ajaxModal' class='fa fa-calendar'></a>
						<?php /*echo flash::data("activityDate");*/?>
					</label>
					<?php echo form::hidden("activityDateTime",null,$datetime);?>
					<?php echo flash::data("activityDateTime");?>
					<div class='summary_title'>Configure the activity date.</div>
					<div id='activityDateSummary' style="display:none;">
					<table id='summary_basic'>
						<tr>
							<td>From</td><td>: <span class='summary_from'></span></td>
							<td>To</td><td>: <span class='summary_to'></span></td>
						</tr>
						<tr>
							<td>Total Days</td><td>: <span class='summary_total'></span></td>
						</tr>
					</table>
					<table id='summary_datelist'>
						
					</table>
					</div>
					<?php/* echo form::text("activityDate","class='form-control' readonly style='background:white;'");*/?>
				</div>
			</div>
		</div>		
	</div>
	<div class='col-sm-5'> <!-- activity specific data. -->
		<div class='row'>
			<div class='col-sm-12' id='type-event' style="display:none;">
				<section class='panel panel-default'>
					<div class='panel-heading'>
					<h5>Event's detail</h5>
					</div>
					<div class='panel-body'>
						<div class='form-group'>
							<label>Type of event <?php echo flash::data("eventType");?></label>
							<?php echo form::select("eventType",$eventTypeR,"class='form-control'",$row['eventType']);?>
						</div>
					</div>
				</section>
			</div>
			<div class='col-sm-12' id='type-training' style="display:none;">
				<section class='panel panel-default'>
					<div class='panel-heading'>
					<h5>Training's detail</h5>
					</div>
					<div class='panel-body'>
						<div class='form-group'>
							<label>Training Type <?php echo flash::data("trainingType");?></label>
							<?php echo form::select("trainingType",$trainingTypeR,"class='form-control'",$row['trainingType']);?>
						</div>
						<div class='form-group'>
							<label>Max Pax <span style='opacity:0.5;'>(0 for no-limit)</span></label>
							<?php echo form::text("trainingMaxPax","class='form-control' style='width:70px;'",$row['trainingMaxPax']);?>
						</div>
					</div>
				</section>
			</div>
		</div>
	</div>
</div>
<div class='row'>
	<div class='col-sm-12' style='text-align:center;'>
		<input type='submit' value='Update Activity' class='btn btn-primary' />
		<input type='button' value='Cancel' class='btn btn-default' />
	</div>
</div>
</form>