<style type="text/css">
	
*
{
	font-family: "tahoma";
}

</style>
<?php
// by schema perspective.
foreach($schema as $table=>$colR):
	if(isset($db_table[$table]))
	{
		foreach($colR['columns'] as $colname)
		{
			if(!in_array($colname,$db_table[$table]['columns']))
			{
				## column didnt exists in db
				$db_colunexists[$table][]	= $colname;
			}
		}
	}
	else
	{
		## table not exists in db
		$db_tableunexists[$table]	= $colR;
	}
endforeach;
// by db perspective.
foreach($db_table as $table=>$colR)
{
	if(isset($schema[$table]))
	{
		foreach($colR['columns'] as $colname)
		{
			if(!in_array($colname,$schema[$table]['columns']))
			{
				## extra col?
				$db_extracolumn[$table][] = $colname;
			}
		}
	}
	else
	{
		## extra table?
		$db_extratable[]	= $table;
	}
}?>
<style type="text/css">
.table-summary tr td:last-child
{
	width:20px;
}

.table > div
{
	padding:5px;
}

.tunnel_wrapper
{
	margin: auto;
}

.tunnel
{
	float:left;
	min-height: 100px;
}

.tunnel > div
{
	border-left:1px solid #d8d8d8;
}

.table_name
{
	border-bottom:1px solid #c4c4c4;
	font-weight: bold;
}

.table_column
{
	font-size:0.9em;
	border-bottom: 1px solid #b4ed92;
	letter-spacing: 1px;
	background: #eefbe6;
	padding:3px;
}

.table_dbnotexists
{
	background: #d75d5d;
}
	.table_dbnotexists .table_name
	{
		color: white;
	}

	.table_dbnotexists .table_column
	{
		background: none;
		color: white;
	}

.table_column_indbnotexists
{
	opacity: 0.9;
	color: white;
	background: #d34b4b;
}

.table_column_extra
{
	color: #818181;
	background: #dfdfdf;
}

.extra_table
{
	background: #e2e2e2;
}
	.extra_table .table_column
	{
		background: none;
		opacity: 0.8;
	}

</style>
<script type="text/javascript" src='<?php echo url::asset("scripts/jquery-min.js");?>'></script>
<script type="text/javascript">

function createTunnel(total)
{
	for(i=0;i<total;i++)
	{
		$(".tunnel_wrapper").append("<div class='tunnel' id='tunnel"+(i+1)+"'><div></div></div>");
	}
}

$(document).ready(function()
{
	createTunnel(5);

	var no = 1;

	var totalTunnel	= $(".tunnel").length;

	console.log(totalTunnel);
	$(".table").each(function(i,e)
	{
		$(e).appendTo("#tunnel"+no+" > div");

		no++;
		if(no == (totalTunnel+1))
		{
			no = 1;
		}
	});
});

</script>
<div style='position:absolute;right:10px;top:5px;background:white;'>Current Database Schema at <u>apps/_structure/schema.yaml</u></div>
<div>
<table class='table-summary'>
<tr><th colspan="3" style='padding:5px;letter-spacing:2;'>Syncronizing summary</th></tr>
<?php
if(!isset($db_colunexists) && !isset($db_extracolumn) && !isset($db_tableunexists) && !isset($db_extratable))
{
	echo "<tr><td colspan='3'>All syncronized.</td></tr>";
}
else
{
	if(isset($db_colunexists))
	{
		$total	= 0;
		foreach($db_colunexists as $table=>$colR)
		{
			$total	+= count($colR);
		}
		echo "<tr><td>Unsync column</td><td>: $total</td><td class='table_column_indbnotexists'></td></tr>";
	}

	if(isset($db_tableunexists))
	{
		$total	= count($db_tableunexists);
		echo "<tr><td>Unsync table</td><td>: $total</td><td class='table_dbnotexists'></td></tr>";
	}

	if(isset($db_extracolumn))
	{
		$total	= 0;
		foreach($db_extracolumn as $table=>$colR)
		{
			$total	+= count($colR);
		}
		echo "<tr><td>Extra column</td><td>: $total</td><td class='table_column_extra'></td></tr>";
	}

	if(isset($db_extratable))
	{
		$total	= count($db_extratable);
		echo "<tr><td>Extra table</td><td>: $total</td><td class='extra_table'></td></tr>";
	}
}

?>
</table>
</div>
<?php
// main loop.
foreach($schema as $table=>$colR)
{
	$extraclass	= Array();
	if(isset($db_tableunexists[$table]))
	{
		$extraclass[]	= "table_dbnotexists";
	}

	echo "<div class='table ".implode(" ",$extraclass)."'><div>";
	echo "<div class='table_name'>$table</div>";

		foreach($colR['columns'] as $colname)
		{
			$extraclass	= Array();
			$type	= trim($colR['type'][$colname],"[]");

			if(isset($db_colunexists[$table]) && in_array($colname,$db_colunexists[$table]))
			{
				$extraclass[]	= "table_column_indbnotexists";
			}

			echo "<div class='table_column ".implode(" ",$extraclass)."'>$colname [$type]</div>";
		}

		if(isset($db_extracolumn[$table]))
		{
			foreach($db_extracolumn[$table] as $colname)
			{
				echo "<div class='table_column table_column_extra'>$colname</div>";
			}
		}

	echo "</div></div>";
}

## extra table;
if(count($db_extratable) > 0)
{
	foreach($db_extratable as $table)
	{
		echo "<div class='table extra_table'><div>";
		echo "<div class='table_name'>$table</div>";
		foreach($db_table[$table]['columns'] as $colname)
		{
			echo "<div class='table_column'>$colname</div>";
		}
		echo "</div></div>";
	}
}


?>
<div class='tunnel_wrapper'>
</div>