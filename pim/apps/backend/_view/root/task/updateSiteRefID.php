<script type="text/javascript">
	
function change()
{
	if(confirm("Confirm?"))
	{
		window.location.href	= "?siteID="+<?php echo $row['siteID'];?>+"&siteRefID="+document.getElementById("selectedSite").value;
	}
	return false;
}

</script>
<table>
	<tr>
		<td><?php echo $row['siteName'];?></td><td>
		<?php foreach($csv as $siteRefID=>$sName)
		{
			echo "<a onclick='return confirm(\"$sName ?\");' href='?siteID=$row[siteID]&siteRefID=$siteRefID'>$sName</a><br>";
		}
		?>

		 <a href='?skip=<?php echo $row['siteID'];?>'>Skip</td>
	</tr>
</table>
<a href='?showSkip=1'>Show skip</a>