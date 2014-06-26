<script type="text/javascript" src='<?php echo url::asset("_scale/js/sortable/jquery.sortable.js");?>'></script>
<style type="text/css">

.component-list
{
	background:#68cc9c;
	color: white;
}
.menu-container
{
	position: relative;
	margin-top:10px;
}

.menu-list
{
	list-style: none;
	margin:0px;
	padding:0px;
}

.menu-list ul
{
	padding-left:10px;
	list-style: none;
}

.menu-list li
{
	padding:0px;
}

.menu-list label
{
	font-size: inherit;
	font-weight: normal;
	margin-right:15px;
}

.menu-list li div
{
	font-size: inherit;
	padding:5px;
	border-bottom: 1px solid #e0e8f5;
	margin: 0px;
	font-weight: normal;
}
.menu-list .added
{

}

.i-plus2
{
	border-bottom: 1px solid #d0d6dd;
	border-right: 1px solid #d0d6dd;
	border-left: 1px solid #d0d6dd;
	top:-1px;
	color: #aeaeae;
}

.fa-edit, .menu-list .badge
{
	display: none;
}
.menu-list .badge
{
	opacity: 0.5;
}
.menu-list li div:hover .fa-edit, .menu-list li div:hover .badge
{
	display:inline;
}


.menu-container .btn{
	border-radius:0px;
	padding:3px 12px;
	margin-top:15px;
	background:#177bbb;
	border:none;
	font-size:12px;
	
}


.menu-container .panel .table td, .panel .table th{
	padding-left:0px;
	padding-right:0px;
	
}

#component-list .i-plus2{
	border:1px solid #d0d6dd;
	margin-right:10px;
	
	
}

#component-list td{
	padding-left:0px;
	padding-right:0px;
	
}

#component-list tr:hover{
	background:none;
	
}
</style>
<script type="text/javascript">

var menu	= new function()
{
	this.added	= [];
	this.changed = false;
	this.add	= function(no)
	{
		pim.func.switchShow("#component-list","#addButton");

		if(no)
		{
			if(pim.func.inArray(no,this.added))
			{
				this.remove(no);
				return false;
			}
			// marking.
			this.added.push(no);
			var tr	= $("#componentAdd"+no);
			tr.find(".i-plus2").addClass('i-checkmark2');

			//and add new item on list.
			var name	= tr.data("compname");
			$(".menu-list").append("<li draggable='true' id='menuComp"+no+"' data-ref='0' data-compno='"+no+"' class='added'><div><label>"+name+"</label><a href='javascript:menu.remove("+no+");' class='i i-cross2 pull-right'></a></div></li>");

			//show save.
			this.showSave();

			//re-init sortable.
			$(".sortable").sortable();
		}
	}

	this.edit		= function(no)
	{
		//set label to editable.
		$("#menuComp"+no).find("label").attr("contenteditable",true);
		setTimeout(function()
		{
			$("#menuComp"+no+" label").focus();
		},50);

		this.changed = true;
		this.showSave();
	}

	this.remove		= function(no)
	{
		$(".menu-list #menuComp"+no).remove();
		this.added = pim.func.arrayRemoveElement(this.added,no);

		//unmark.
		var tr	= $("#componentAdd"+no);
		tr.find(".i-plus2").removeClass("i-checkmark2");

		if(this.added.length == 0)
		{
			this.showSave(false);
		}
	}

	this.showSave 	= function(flag)
	{
		if(flag === false && !this.changed)
		{
			$("#btn-save").hide();
		}
		else
		{
			$("#btn-save").show();
		}
	}

	this.save		= function()
	{
		var finalList	= [];
		//re-prepare menu.
		$(".menu-list > li").each(function(i,e)
		{
			var refID	= $(e).data("ref");
			var compNo	= $(e).data("compno");
			var no		= i+1;
			var name	= $(e).find("label").html();

			finalList.push({
				menuName:name,
				componentNo:compNo,
				menuRefID:refID,
				menuNo:no});
		});

		// append and add.
		$("#menu-update-list").val(JSON.stringify(finalList));
		$("#form-menu-update").submit();
	}

	$(document).ready(function()
	{
		$(".menu-list label").keypress(function(e){
			if(e.which == 13)
			{
				$(this).blur();
				return false;
			}
		});
		$(".sortable").sortable().bind('sortupdate',function()
		{
			menu.changed = true;
			menu.showSave();
		});

		$(".menu-list label").blur(function()
		{
			// revert to original data.
			if($(this).html() == "")
			{
				$(this).html($(this).data("original"));
			}
		})
	});
}

</script>
<h3 class='m-b-xs text-black'>
Site Menu
</h3>
<div class='containersz'>	
</div>
<div id='test'>

</div>
<div class='well well-sm'>
Your main site menu. You can edit or add here based on existing component.
</div>
<?php echo flash::data();?>
<div class='row'>
	<div class='col-sm-4 col-xs-6'>
	<section class='panel panel-default'>
		<div class='panel-heading'>
		Current Menu
		</div>
		<div class='menu-container'>
			<ul class='menu-list sortable'>
				<?php
				$addedComp	= Array();
				foreach($topMenu as $row)
				{
					$addedComp[] = $row['componentNo'];
					$menuName	= $row['menuName'];
					$editIcon	= "<a href='javascript:menu.edit($row[componentNo])' class='fa fa-edit' title='Rename'></a>";
					$compName	= "<span class='badge bg-primary pull-right'>".$component[$row['componentNo']]['componentName']."</span>";

					echo "<li id='menuComp$row[componentNo]' data-ref='$row[menuRefID]' data-compno='$row[componentNo]'><div><label data-original='$menuName'>$menuName</label> $editIcon $compName</div></li>";
				}
				?>
			</ul>
			<div class='row'>
			<div class='col-sm-6 pull-right' id='btn-save' style='display:none;text-align:right;'>
			<form method='post' id='form-menu-update'>
				<input type='hidden' id='menu-update-list' name='menuUpdateList' />
			</form>
			<input type='button' class='btn btn-success' value='Save' onclick="menu.save();" />
			</div>
			<div class='col-sm-6'>
				<a href='javascript:menu.add();' class='i i-plus2 pull-right' id='addButton' style='font-size:1.5em;position:absolute;background:white;right:15px;'>
				</a>
			</div>
			</div>
		</div>
	</section>
	</div>
	<!-- component grid -->
	<div class='col-sm-4 col-xs-6' id='component-list' style="display:none;">
	<section class='panel panel-default'>
		<div class='panel-heading'>
		Add Menu Components
		</div>
			<table class='table'>
				<tr>
					<th>Choose from the available component :</th>
				</tr>
				<?php 
				$forbid	= Array(99);
				foreach($component as $row)
				{
					if(in_array($row['componentNo'],$forbid))
						continue;

					$compNo	= $row['componentNo'];
					if(in_array($row['componentNo'],$addedComp))
					continue;
				?>
				<tr data-compname='<?php echo $row['componentName'];?>' id='componentAdd<?php echo $compNo;?>'>
					<td><a href='javascript:menu.add(<?php echo $compNo;?>);' class='i i-plus2'></a> <?php echo $row['componentName'];?></td>
				</tr>

				<?php 
				}?>
			</table>
	</section>
	</div>
</div>
<script type="text/javascript">
	
$('#top-menu, #component').sortable({
    connectWith: '.connected'
});

</script>