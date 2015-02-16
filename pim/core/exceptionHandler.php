<?php
if(!request::isAjax())
{
	echo "<pre>";
}
echo "Message : ".$e->getMessage()."\n";
echo "Code : ".$e->getCode();