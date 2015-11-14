<?php
session_start();
include "include/config.php";
include "include/function.php";
$text = $_REQUEST["mmessage"];
$phone = $_REQUEST["phone"];
if($reuslt = sendSMS($phone,$text))
	echo "SUCCESS: Message Text Sent";
else
	echo "Unable To Send Text Message";
include "include/unconfig.php";