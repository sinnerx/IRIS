<script type="text/javascript">
var participants = new function()
{
	this.totalDate	= <?php echo count($res_date);?>;
	this.userList  	= null;
	this.activityID	= <?php echo $activityID;?>;
	this.container	= null;
	this.addedList	= [];
	this.allDate	= <?php echo $row_activity['activityAllDateAttendance'];?>;
	this.siteID		= <?php echo $row_activity['siteID'];?>;
	this.deleted	= [];
	this.currentDate= "<?php echo date('Y-m-d');?>";
	
	this.delete	= function(userID)
	{
		if(!$("#row-user"+userID).hasClass("deleted"))
		{
			this.deleted.push(userID);
			$("#row-user"+userID).addClass("deleted");
		}
		else
		{
			// rebuild deleted.
			var newList = [];
			for(var i in this.deleted)
			{
				if(this.deleted[i] != userID)
				{
					newList.push(this.deleted[i]);
				}
			}
			this.deleted = newList;
			$("#row-user"+userID).removeClass("deleted");
		}

		this.showSave();
	}

	this.add = function()
	{
		var tdR	= "";
		if(this.allDate == 2)
		{
			for(var i = 0;i<this.totalDate;i++)
			{
				// set checked for current date.
				var checked = this.currentDate == $("#date"+(i+1)).attr("data-date")?"checked":"";
				tdR += "<td><input type='checkbox' "+checked+" /></td>";
			}
		}
		else
		{
			var checked = "";
			for(var i = 0;i<this.totalDate;i++)
			{
				// set checked if current date exists in any date.
				if(this.currentDate == $("#date"+(i+1)).attr("data-date"))
				{
					checked = "checked";
				}
			}
			tdR += "<td colspan='"+this.totalDate+"'><input type='checkbox' "+checked+" /></td>";
		}

		var currentTotal	= $("#participant-table tr").length - 1;
		var currNo			= currentTotal+1;
		$("#participant-table").append("<tr class='participant-added-row new'><td>"+currNo+".</td><td><div class='input-group' id='search-box'>"+'<input type="text" placeholder="I.C. or Member\'s name" class="form-control">'+"<span class='input-group-btn'><button class='btn btn-default' onclick='participants.searchByObj(this)' type='button'>Find</button></span></div></td>"+tdR+"<td style='color:red;' class='container-delete'><span onclick='participants.addCancel(this);' style='cursor:pointer;'>X</span></td></tr>");

		$("#addButton").hide();
	}

	this.search = function(val,obj)
	{
		var val = obj.val();

		if(val == "")
		{
			return alert("Please fill the search field");
		}

		var data = {
			activityID:this.activityID,
			search_value:val,
			added_list:this.addedList.join(",")
		};

		var context = this;
		$.ajax({type:"POST",url:pim.base_url+"ajax/activity/searchParticipant",data:data}).done(function(res)
		{
			var list	= $.parseJSON(res);

			if(list.length == 0)
			{
				alert("Cannot find the record with the keyword.");
				return false;
			}

			context.userList = list;
			context.createAutocomplete(list,obj);
		});
	}

	this.createAutocomplete = function(list,obj)
	{
		$("#search-list").remove();

		var ul = "<ul style='"+(Object.keys(list).length <= 5?"height:inherit":"")+"' id='search-list'>";
		for(var i in list)
		{
			var userID	= list[i].userID;
			var name = list[i].userProfileFullName;

			var userIC	= list[i].userIC;
			var inactive	= list[i].siteMemberStatus == 0?" <span class='member-inactive'>(Unpaid)</span>":"";
			var nonsite		= list[i].siteID != this.siteID?" <span class='member-nonsite'>Non-site member</span>":"";
			ul += "<li onclick='participants.choose("+userID+");'>"+name+"<br>"+userIC+inactive+nonsite+"</li>";
		}
		ul += "</ul>";

		obj.parent().append(ul);
		this.container = obj.parent().parent();
	}

	// choose the searched user.
	this.choose = function(userID)
	{
		var user	= this.userList[userID];

		this.container.html(user.userProfileFullName+"<br>"+user.userIC);
		this.addedList.push(userID);
		this.container.parent().attr("data-userID",userID);
		this.container.parent().attr("id","row-user"+userID);
		this.showSave();

		// add row into table.
	}

	this.addCancel	= function(obj)
	{
		$("#addButton").show();

		// remove from list if has data-userID
		if($(obj).parent().parent().attr("data-userID"))
		{
			var newList	= [];
			for(var i in this.addedList)
			{
				if($(obj).parent().parent().attr("data-userID") == this.addedList[i])
				{
					continue;
				}

				newList.push(this.addedList[i]);
			}
			this.addedList	= newList;
		}

		$(obj).parent().parent().remove();

		if(!$(".participant-added-row")[0])
		{
			$("#saveButton").hide();
		}
	}

	this.searchByObj = function(btn)
	{
		var input = $(btn).parent().parent().find("input");
		this.search(input.val(),input);
	}

	this.save 	= function()
	{
		// prepare data with date.
		var userData	= {};

		var deleteMsg	= this.deleted.length > 0?" \n(Some participant(s) will also be removed permanently.)":"";

		if(!confirm("Save this changes? "+deleteMsg))
		{
			return false;
		}

		var attendanceUnmarked = [];
		$(".participant-added-row, .participant-existing-row").each(function(i,e)
		{
			if($(e).attr("data-userID"))
			{
				var userID = $(e).attr("data-userID");
				userData[userID] = [];

				// find checkbox for checking date val.
				$(e).find("input").each(function(n,checkbox)
				{
					if(checkbox.checked)
					{
						date = $("#date"+(n+1)).data('date');
						if (participants.allDate == 1)
						{
							userData[userID] = true
						}
						else
						{
							userData[userID].push(date)
						}
					}
					else if(participants.allDate == 1)
					{
						userData[userID] = false
					}
				})

				if($(e).hasClass("new") && (!userData[userID] || userData[userID].length == 0))
				{
					
					attendanceUnmarked.push(userID);
				}

				if(userData[userID].length == 0)
				{
					delete userData[userID]
				}
			}
		});

		if(attendanceUnmarked.length > 0)
		{
			if(!confirm("There're some unchecked attendance at all for a new manually added participant. By continuing will not mark their attendance at all. \n\nDo you want to continue?"))
			{
				return false;
			}
			else
			{
				// mark them as deleted if he wish to continue.
				for(i in attendanceUnmarked)
				{
					$("#row-user"+attendanceUnmarked[i]).addClass("deleted");
				}
			}
		}

		var data = {
			activityID:this.activityID,
			userData: JSON.stringify(userData),
			deletedUser: JSON.stringify(this.deleted)
		};

		$.ajax({type:"POST",url:pim.base_url+"ajax/activity/addParticipantsSave",data:data}).done(function()
		{
			alert("Updated.");
			participants.hideSave();

			// switch to delete action and remove row with no id
			$(".participant-added-row").each(function(i,e)
			{
				if(!$(e).attr("id"))
				{
					$(e).remove();
					$("#addButton").show();
				}
				var uID	= $(e).attr("data-userID");
				$(e).find(".container-delete span").attr("onclick","participants.delete("+uID+")");
			});

			// remove new class.
			$(".participant-added-row").removeClass("new");
			// and rework numbering.
			$(".participant-added-row.deleted").remove();
			// and re-add numbering. 
			$(".participant-added-row, .participant-existing-row").each(function(i,e)
			{
				$(e).find("td:first-child").html((i+1)+".");
			})
		});
	}

	this.showSave	= function()
	{
		$("#saveButton").show();
		$("#addButton").show();
	}

	this.hideSave	= function()
	{
		$("#saveButton").hide();
	}
}

$(document).ready(function()
{
	$(".participant-table input").click(function()
	{
		participants.showSave();
	});
});

</script>
<style type="text/css">
	
.participant-table td
{
	padding:5px;
	border-bottom:1px solid #e4e4e4;
	text-align: center;
}
.participant-table td:nth-child(2)
{
	text-align: left;
}
.participant-table th
{
	background: #707070;
	color:white;
	padding:5px;
}
.participant-table
{
	border-spacing: 1px;
	border-collapse:separate;
}

#search-box
{
	position: relative;
}

#search-list
{
	position: absolute;
	width: 100%;
	left:0px;
	top:36px;
	background: white;
	padding:0px;
	list-style: none;
	box-shadow: 0px 5px 6px #2e3034;
	z-index: 100;
	height:255px;
	overflow: auto;
}

#search-list li
{
	padding:5px;
	border-bottom:1px solid #e4e4e4;
	position: relative;
}

#saveButton
{
	display: none;
}

.participant-added-row
{
	background: #e9fce2;
}

.participant-added-row.deleted
{
	background: #ffd2d2;
}

.member-inactive
{
	color:red;
}

.member-nonsite
{
	color:grey;
	position: absolute;
	top:0px;
	right:0px;
	padding:5px;
}

</style>
<div class='date-container'>
<?php foreach($res_date as $row):
?>
	<input type='hidden' value='<?php echo $row['activityDateValue'];?>' />
<?php endforeach;?>
</div>
<div class="modal-dialog" style="<?php if(count($res_date) < 5):?>width:800px;<?php else:?>width:80%;<?php endif;?>">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">×</button>
			<h4 class="modal-title"><b><?php echo $row_activity['activityName'];?></b> : Participations</h4>
			<span><span style='color:red;'>Requirement : <?php echo $requirement;?></span></span>
		</div>
		<div id='command-label' class='row-wrapper' style="border-bottom:1px solid #e5e5e5;padding:10px;">
			List of member attending your activity. Tick the checkbox to mark the attendance.
		</div>
		<div class="modal-body" style='padding-top:5px;padding-left:5px;padding-right:5px;'>
			<div class='activity-date-tab'>
			</div>
			<div>
				<table width="100%" class='participant-table' id='participant-table'>
					<tr>
						<th width="50px">No.</th><th>Name</th>
						<?php
						## start date looping. ##
						$no = 1;
						foreach($res_date as $row):
						$date	= date("d-m-Y",strtotime($row['activityDateValue']));
						$id		= "date$no";
						$no++;
						?>
						<th width="100px" id='<?php echo $id;?>' data-date='<?php echo $row['activityDateValue'];?>'><?php echo $date;?></th>
						<?php
						endforeach;
						## date looping and.
						?>
						<th width="15px"></th>
					</tr>
					<?php
					$no  = 1;
					if($res_participant):?>
					<?php foreach($res_participant as $row):
					$userID		= $row['userID'];
					$name		= $row['userProfileFullName']." ".$row['userProfileLastName'];
					$ic			= $row['userIC'];
					$activityUserID	= $row['activityUserID'];

					$class_originaluser	= $row['userID'] == $row['activityUserCreatedUser']? "participant-existing-row" : "participant-added-row";
					?>
					<tr class='<?php echo $class_originaluser;?>' data-userID='<?php echo $userID;?>' id='row-user<?php echo $userID;?>'>
						<td style="padding-left:10px;"><?php echo $no++;?>.</td>
						<td><?php echo $name;?><br><?php echo $ic;?></td>
						<?php
						if($row_activity['activityAllDateAttendance'] == 2):
						foreach($res_date as $row_date):
						$date	= $row_date['activityDateValue'];

						## attending check.
						$checked	= "";
						if(isset($res_participant_dates[$userID][$date]))
						{
							$checked	= $res_participant_dates[$userID][$date]['activityUserDateAttendance'] == 1?"checked":"";
						}
						?>
						<td>
							<input type='checkbox' <?php echo $disabled;?> <?php echo $checked;?> />
						</td>
						<?php endforeach;?>
						<?php else: ## all date required.
						$attendance = null;
						foreach($res_participant_dates[$userID] as $date=>$row)
						{
							if($row['activityUserDateAttendance'] == 1)
							{
								$attendance = 1;
							}
							else
							{
								$attendance = 2;
							}
						}

						$checked	= $attendance == 1 ? "checked" : "";
						?>
							<td colspan="<?php echo count($res_date);?>">
								<input type='checkbox' <?php echo $checked;?> />
							</td>
						<?php endif;?>
						<td class='container-delete'>
						<?php if($row['userID'] != $row['activityUserCreatedUser']):?>
							<span onclick='participants.delete(<?php echo $userID?>);' style='cursor:pointer;'>X</span>
						<?php endif;?>
						</td>
					</tr>
					<?php endforeach;?>
					<?php else:?>
					<tr>
						<td colspan="<?php echo 3+count($res_date);?>">No participant yet registered for this activity.</td>
					</tr>
					<?php endif;?>
				</table>
		</div>
	</div>
	<div class='modal-footer'>
		<div class='col-sm-6' style="text-align:left;" >
			<input type='button' value='Manually add participants' onclick='participants.add();' class='btn btn-primary' id='addButton' >
		</div>
		<div class='col-sm-6'>
			<input type='button' class='btn btn-danger' value='Save' id='saveButton' onclick="participants.save();" />
		</div>
	</div>
</div>