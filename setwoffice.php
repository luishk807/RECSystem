<?php
session_start();
include "include/config.php";
include "include/function.php";
$id=base64_decode($_REQUEST["id"]);
$target=base64_decode($_REQUEST["targ"]);
$link=$target.".php";
if(!empty($id))
{
	$query = "select * from rec_office where id='".$id."'";
	if($result=mysql_query($query))
	{
		if(($num_rows=mysql_num_rows($result))>0)
		{
			$info=mysql_fetch_assoc($result);
			$infox=array('id'=>$info["id"],'email'=>stripslashes($info["email"]),'onsip'=>stripslashes($info["onsip"]),'name'=>stripslashes($info["name"]));
			unset($_SESSION["woffice"]);
			$_SESSION["woffice"]=$infox;
		}
	}
}
header('location:'.$link);
exit;
include "include/unconfig.php";
?>