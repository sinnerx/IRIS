<?php
namespace model\template;

//Font awesome.
class Icon
{
	## 0 or 1 or 2
	public function status($no = 0,$title = null)
	{
		$title	= $title?"title='$title'":"";
		$arr[0]	= "<span {title} class='fa fa-stop' title='Pending' style='color:grey;'></span>";
		$arr[1]	= "<span {title} class='fa fa-stop' title='Approved' style='color:green;'></span>";
		$arr[2] = "<span {title} class='fa fa-stop' title='Rejected' style='color:red;'></span>";
		$arr[3] = "<span {title} class='fa fa-stop' title='As draft' style='color:yellow;'></span>";
		$arr[4] = "<span {title} class='fa fa-stop' title='Pending' style='color:blue;'></span>";

		return str_replace('{title}',$title,$arr[$no]);
	}

	public function privacy($no = 0,$title = null)
	{
		$title	= $title?"title='$title'":"";
		$arr[1]	= "<span class='fa fa-unlock-alt' style='color:red;' title='Open for all'></span>";
		$arr[2]	= "<span class='fa fa-lock' style='color:green;' title='Open for site member only'></span>";
		$arr[3]	= "<span class='fa fa-eye-slash' style='color:grey;' title='Only site manager'></span>";

		return $arr[$no];
	}
}

?>