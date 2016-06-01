<?php


Class Controller_overview {
	public function index(){
		redirect::to( url::base('../'). 'assetmgmt/public/', '', 'success');
		//var_dump(url::base('../'). 'aveo/public/');
	}

}
?>