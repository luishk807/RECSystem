<?php
session_start();
include "include/config.php";
include "include/function.php";
$id=base64_decode($_REQUEST["id"]);
$ccode=$_REQUEST["ccode"];
if(!empty($id) && !empty($ccode))
{
	$query="select * from rec_entries where ccode='".$ccode."' and id !='".$id."'";
	if($result=mysql_query($query))
	{
		if(($num_rows=mysql_num_rows($result))>0)
		{
			echo "<span style='color:#F00'><img src='images/sm_checkbad.jpg' border='0'/>&nbsp;Code Taken!</span>
			<input type='hidden' id='ccode_dup' name='ccode_dup' value='yes' />
			";
		}
		else
		{
			echo "<span style='color:#218c08'><img src='images/sm_checkgood.jpg' border='0'/>&nbsp;Good!</span>
			<input type='hidden' id='ccode_dup' name='ccode_dup' value='no' />";
		}
	}
	else
	{
		echo "<span style='color:#218c08'><img src='images/sm_checkgood.jpg' border='0'/>&nbsp;Good!</span>
		<input type='hidden' id='ccode_dup' name='ccode_dup' value='no' />";
	}
}
else
{
 	echo "<input type='hidden' id='ccode_dup' name='ccode_dup' value='no' />";
}
include "include/unconfig.php";
?>