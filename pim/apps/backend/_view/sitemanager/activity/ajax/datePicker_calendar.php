<style type="text/css">
	
#table-calendar
{
	width:100%;
}
#table-calendar tr th, #table-calendar tr td
{
	width:12.5%;
}
#table-calendar tr td
{
	height: 50px;
	cursor: pointer;
	border-bottom:1px solid #effafa;
}
.day-label
{
	color:#aaaaaa;
	padding-left:4px;
}
#table-calendar td:hover
{
	background: #fbece1;
}


.cal-paging{
		color:#000;
	font-weight:bold;
	
}
.cal-paging a{
	color:#000;
	font-weight:bold;
	-webkit-transition: all 0.4s ease-out;
   -moz-transition: all 0.4s ease-out;
   -ms-transition: all 0.4s ease-out;
   -o-transition: all 0.4s ease-out;
   transition: all 0.4s ease-out;
	
	
}

.cal-paging a:hover{
	color:#428bca;



}
</style>

<?php
$mBefore	= $month == 1?12:$month-1;
$mAfter		= $month == 12?1:$month+1;
$yBefore	= $month == 1?$year-1:$year;
$yAfter		= $month == 12?$year+1:$year;

?>
<label class='pull-right cal-paging'>
	<a href='<?php echo url::base("ajax/activity/datePicker_calendar/$yBefore/$mBefore");?>' style="margin-right:10px;"><<</a> 
	<?php echo $monthLabel." ".$yearLabel;?> 
	<a href='<?php echo url::base("ajax/activity/datePicker_calendar/$yAfter/$mAfter");?>' style="margin-left:10px;">>></a>
</label>
<div class="clearfix"></div>
<?php 
//Main calendar show.
echo $calendar->showCalendar();?>

<script type="text/javascript">
//ajaxify url.
pim.ajax.urlify(".cal-paging a",".date-container");

//run the date.
$("#table-calendar td").click(function()
{
	var date	= $(this).data("date");
	activity.datePicker.selectRangeDate(date);
});

//refresh the current data.
activity.datePicker.refresh();

</script>