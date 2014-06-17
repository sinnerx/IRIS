<?php
namespace model\template;

//Font awesome.
class Icon
{
	## 0 or 1 or 2
	public function status($no = 0,$title = null)
	{
		$title	= $title?"title='$title'":"";
		$arr[0]	= "<span {title} class='fa fa-circle' style='color:grey;'></span>";
		$arr[1]	= "<span {title} class='fa fa-circle' style='color:green;'></span>";
		$arr[2] = "<span {title} class='fa fa-circle' style='color:red;'></span>";
		$arr[3] = "<span {title} class='fa fa-circle' style='color:yellow;'></span>";
		$arr[4] = "<span {title} class='fa fa-circle' style='color:blue;'></span>";

		return str_replace('{title}',$title,$arr[$no]);
	}
}

?>