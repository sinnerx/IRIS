<?php
namespace model\template;

//Font awesome.
class Icon
{
	## 0 or 1 or 2
	public function status($no = null)
	{
		$arr[0]	= "<span class='fa fa-circle' style='color:grey;'></span>";
		$arr[1]	= "<span class='fa fa-circle' style='color:green;'></span>";
		$arr[2] = "<span class='fa fa-circle' style='color:red;'></span>";

		return $no != null?$arr[$no]:$arr;
	}
}

?>