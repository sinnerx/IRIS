<style type="text/css">
	
@media (min-width: 768px) {
	.modal-dialog
	{
		width:80%;
	}
}

.modal-dialog label
{
	font-size: inherit;
	font-weight: normal;
}

.activity-date-info
{
}
#command-label
{
	padding:5px;
}

#table-calendar th
{
	text-align: left;
}
#table-calendar td
{
	vertical-align: top;
	text-align: left;
	-webkit-touch-callout: none;
	-webkit-user-select: none;
	-khtml-user-select: none;
	-moz-user-select: none;
	-ms-user-select: none;
	user-select: none;
}
#table-calendar .day-label
{

}
#date-time
{
	width: 100%;
}
#date-time tr td:first-child
{
	width:30px;
}
#date-time tr th:nth-child(2), #date-time tr th:nth-child(3)
{
	width:35%;
}
.date-selected
{
	background: #d9f3f4;
}

.clicked-start
{
	background: #bee7e7;
}
.clicked-end
{
	background: #bee7e7;
}


.pull-right.cal-paging{
	margin-top:10px;
	margin-bottom:10px;

}

</style>
<script type="text/javascript">
// initiation.
activity.datePicker.reset();
activity.datePicker.initiate();

</script>
<style type="text/css">
	
.sum-title
{
	border-bottom:1px solid #dedfd9;
	font-weight: bold;
}

</style>
<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">×</button>
			<h4 class="modal-title">Activity Date
			</h4>
		</div>
		<div id='command-label' class='row-wrapper' style="border-bottom:1px solid #e5e5e5;"></div>
		<div class="modal-body" style='padding-top:5px;'>
			<div class='row'>
				<div class='col-sm-6'>
					<div class='date-container'>
					</div>
				</div>
				<div class='col-sm-6'>
					<div class='activity-date-info sum-title'>
					Summary of the selected date.
					</div>
					<div class='row'>
						<div class='col-sm-4 activity-date-from'>
						From : <span></span>
						</div>
						<div class='col-sm-4 activity-date-to'>
						To : <span></span>
						</div>
					</div>
					<div class='row-wrapper activity-date-total'>
						Total days : <span></span>
					</div>
					<div class='row'>
						<div class='col-sm-12'>
						<div class='sum-title' style='margin-top:15px;'>Configure the activity time below : </div>
						<table id='date-time'>

						</table>
						</div>
					</div>
				</div>
			</div>
			<div class='row-wrapper' style='text-align:right;'>
			<input type='button' value='Cancel' class='btn btn-default' data-dismiss='modal' />
			<input type='button' value='Submit date' onclick='return activity.datePicker.submit(event);' class='btn btn-primary' data-dismiss='modal' />
			</div>
		</div>
	</div>
</div>