<?php

class Model_Fixture
{
	public function state($no = null)
	{
		$state	= Array(
				1=>"Perlis",
				2=>"Kedah",
				3=>"Negeri Sembilan",
				4=>"Johor",
				5=>"Sabah",
				6=>"Sarawak",
				9=>"Selangor"
						);

		return !$no?$state:$state[$no];
	}
}


?>