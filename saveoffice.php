<?php
session_start();
include "include/config.php";
include "include/function.php";
adminlogin();
if(empty($_SERVER['HTTP_REFERER']))
{
	header("location:status.php");
	exit;
}
$task = $_REQUEST["task"];
if($task=="save")
{
	$user = $_SESSION["rec_user"];
	$id = base64_decode($_REQUEST["id"]);
	$oname = trim(ucwords(strtolower($_REQUEST["oname"])));
	$oemail = trim(strtolower($_REQUEST["oemail"]));
	$ophone= trim($_REQUEST["ophone"]);
	$ofax = trim($_REQUEST["ofax"]);
	$odays = trim(ucwords(strtolower($_REQUEST["odays"])));
	$ohours = trim(ucwords(strtolower($_REQUEST["ohours"])));
	$oaddress = trim(ucwords(strtolower($_REQUEST["oaddress"])));
	$ocity = trim(ucwords(strtolower($_REQUEST["ocity"])));
	$ostate= trim(ucwords(strtolower($_REQUEST["ostate"])));
	$ocountry = trim(ucwords(strtolower($_REQUEST["ocountry"])));
	$ozip = trim(strtoupper($_REQUEST["ozip"]));
	$odriving = trim($_REQUEST["odriving"]);
	$owalking = trim($_REQUEST["owalking"]);
	$ocontact = trim(ucwords(strtolower($_REQUEST["ocontact"])));
	$query = "update rec_office set name='".clean($oname)."',email='".clean($oemail)."',address='".clean($oaddress)."', city='".clean($ocity)."',state='".clean($ostate)."',country='".clean($ocountry)."', zip='".clean($ozip)."', phone='".clean($ophone)."', days='".clean($odays)."', hours='".clean($ohours)."',idrive='".clean($odriving)."', iwalk='".clean($owalking)."',fax='".clean($ofax)."',contact='".clean($ocontact)."' where id='$id'";
	if($result = mysql_query($query))
		$_SESSION["recresult"]="SUCCESS: Changes Saved";
	else
		$_SESSION["reckresult"]="ERROR: Unable To Save Changes";
	header("location:editoffice.php?id=".base64_encode($id));
	exit;
}
else if($task=="create")
{
	$user = $_SESSION["rec_user"];
	$oname = trim(ucwords(strtolower($_REQUEST["oname"])));
	$oemail= trim(strtolower($_REQUEST["oemail"]));
	$ophone= trim($_REQUEST["ophone"]);
	$ofax = trim($_REQUEST["ofax"]);
	$odays = trim(ucwords(strtolower($_REQUEST["odays"])));
	$ohours = trim(ucwords(strtolower($_REQUEST["ohours"])));
	$oaddress = trim(ucwords(strtolower($_REQUEST["oaddress"])));
	$ocity = trim(ucwords(strtolower($_REQUEST["ocity"])));
	$ostate= trim(ucwords(strtolower($_REQUEST["ostate"])));
	$ocountry = trim(ucwords(strtolower($_REQUEST["ocountry"])));
	$ozip = trim(strtoupper($_REQUEST["ozip"]));
	$odriving = trim($_REQUEST["odriving"]);
	$owalking = trim($_REQUEST["owalking"]);
	$ocontact = trim(ucwords(strtolower($_REQUEST["ocontact"])));
	$query = "insert ignore into rec_office(name,address,city,state,country,zip,email,phone,fax,days,hours,idrive,iwalk,datecreated,contact)values('".clean($oname)."','".clean($oaddress)."','".clean($ocity)."','".clean($ostate)."','".clean($ocountry)."','".clean($ozip)."','".clean($oemail)."','".clean($ophone)."','".clean($ofax)."','".clean($odays)."','".clean($ohours)."','".clean($odriving)."','".clean($owalking)."',NOW(),'".clean($ocontact)."')";
	if($result = mysql_query($query))
		$_SESSION["recresult"]="SUCCESS: Office Created";
	else
		$_SESSION["recresult"]="ERROR: Unable To Create Office";
	header('location:officeview.php');
	exit;
}
else if($task=="delete")
{
	if(isset($_SESSION["rec_user"]))
	{
		$user = $_SESSION["rec_user"];
		if($user["type"] != "1")
		{
			header("location:status.php");
			exit;
		}
	}
	else
	{
		header("location:status.php");
		exit;
	}
	$id = base64_decode($_REQUEST["id"]);
	$query = "delete from rec_office where id='$id'";
	if($result = mysql_query($query))
		$_SESSION["recresult"]="SUCCESS: Office Deleted";
	else
		$_SESSION["recresult"]="ERROR: Unable To Delete Office, Please try again later";
	header('location:officeview.php');
	exit;
}
else
{
	header('location:status.php');
	exit;
}
include "include/unconfig.php";
?>