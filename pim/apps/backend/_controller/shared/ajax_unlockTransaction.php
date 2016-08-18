<?php
class Controller_ajax_UnlockTransaction {

	public function checkSite ($siteid) {
		$site = model::load("site/site")->getSite($siteid);
		//var_dump($site);
		//die;
		if($site['siteUnlockDate'] != '' && strtotime($site['siteUnlockDate']) >= strtotime('-1 day')){
			//delete siteunlockdate on site row
			//model::load("site/site")->unlockSite($siteid, NULL);

			return json_encode(array('status' => 'success'));
		}
		else{
			return json_encode(array('status' => 'fail'));
		}
	}

	public function unlockSite($siteid){
		$site = model::load("site/site")->getSite($siteid);

		if(strtotime($site['siteUnlockDate']) >= strtotime('-1 day')){
			return json_encode(array('status' => 'fail'));
		}
		else{
			$dateToday = date('Y-m-d H:i:s');
			$site = model::load("site/site")->unlockSite($siteid, $dateToday);
			return json_encode(array('status' => 'success'));
		}
	}
	
} 

?>