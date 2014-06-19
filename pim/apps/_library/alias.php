<?php

function getAuthData($param1 = null,$param2 = null)
{
	return model::load("access/auth")->getAuthData($param1,$param2);
}

?>