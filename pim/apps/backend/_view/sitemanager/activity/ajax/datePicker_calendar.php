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

</style>

<?php
$mBefore	= $month == 1?12:$month-1;
$mAfter		= $month == 12?1:$month+1;
$yBefore	= $month == 1?$year-1:$year;
$yAfter		= $month == 12?$year+1:$year;

?>
<label class='pull-right cal-paging'>
	<a href='<?php echo url::base("ajax/activity/datePicker_calendar/$yBefore/$mBefore");?>'><</a> 
	<?php echo $monthLabel." ".$yearLabel;?> 
	<a href='<?php echo url::base("ajax/activity/datePicker_calendar/$yAfter/$mAfter");?>'>></a>
</label>
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