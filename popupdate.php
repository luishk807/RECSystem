<?php
session_start();
include "include/config.php";
include "include/function.php";
if(isset($_SESSION["rec_user"]))
{
	$check1 = false;
	$taskview = $_SESSION["rec_user"];
	$queryview= "select * from rec_entries where (interviewer='".$taskview["id"]."' and inter_view='no') or (observationer='".$taskview["id"]."' and ob_view='no')";
	if($resultview = mysql_query($queryview))
	{
		if(($num_rows = mysql_num_rows($resultview))>0)
		{
			$check1=true;
		}
	}
	if($check1)
	{
			if($popview !="close")
				echo "<div id='newicon' style='position:absolute; top:50px;left:620px'><img src='images/new.png' border='0' alt='New Message' /></div>";
	}
}
include "include/unconfig.php";
?>