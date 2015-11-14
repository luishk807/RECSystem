<?php
session_start();
include "include/config.php";
include "include/function.php";
$id=base64_decode($_REQUEST["id"]);
if(!empty($id))
{
	$query = "select * from rec_office where id='".$id."'";
	if($result = mysql_query($query))
	{
		if(($num_rows = mysql_num_rows($result))>0)
		{
			$rows = mysql_fetch_assoc($result);
			echo "<span style='font-size:13pt; width:600px'><fieldset><legend><b>Information for ".$rows["name"]."</b></legend>";
			echo $rows["address"]."<br/>".$rows["city"]." ".$rows["state"]." ".$rows["zip"]."<br/>Contact Email: <b>".$rows["email"]."</b><br/>Phone: <b>".$rows["phone"]."</b><br/>Day Open: <b>".$rows["days"]."</b> During Hours: <b>".$rows["hours"]."</b>";
			echo "</fieldset></span>";
		}
	}
}
include "include/unconfig.php";
?>