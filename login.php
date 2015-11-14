<?php
session_start();
include "include/config.php";
include "include/function.php";
if(empty($_SERVER['HTTP_REFERER']))
{
	$_SESSION["loginresult"]="Illegal entry detected";
	header("location:index.php");
	exit;
}
$uname = trim($_REQUEST["uname"]);
$upass = trim($_REQUEST["upass"]);
$query = "select * from task_users where (email = '".clean($uname)."' or username='".clean($uname)."') and password ='".md5(clean($upass))."'";
if($result = mysql_query($query))
{
	if(($num_rows = mysql_num_rows($result))>0)
	{
		$row = mysql_fetch_assoc($result);
		adminstatus($row["status"]);
		if(!empty($row["woffice"]) && $row["woffice"]>0)
			$_SESSION["woffice"]=$row["woffice"];
		else
			unset($_SESSION["woffice"]);
		if($row["type"]=='9')//brown morgan accounts
		{
			/*$_SESSION["loginresult"]="Brown Morgan System Under Maintenance";
			header("location:index.php");
			exit;*/
			unset($_SESSION["rec_user"]);
			$user = array("id"=>$row["id"], "name"=>stripslashes($row["name"]),"username"=>stripslashes($row["username"]),"password"=>stripslashes($row["password"]),"office"=>$row["office"],"email"=>stripslashes($row["email"]),'title'=>$row["title"],"status"=>$row["status"],"type"=>$row["type"],"phone"=>stripslashes($row["phone"]),'acode'=>stripslashes($row["acode"]));
			$_SESSION["brownuser"]=$user;
			//set office session
			if(!empty($user["office"]))
			{
				$query = "select * from rec_office where id='".$user["office"]."'";
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
			//end of creating office session.
			$query = "update task_users set last_checkin_rec=NOW() where id='".$row["id"]."'";
			@mysql_query($query);
			header("location:home.php");
			exit;
		}
		else
		{
			
			$user = array("id"=>$row["id"], "name"=>stripslashes($row["name"]),"username"=>stripslashes($row["username"]),"password"=>stripslashes($row["password"]),"email"=>stripslashes($row["email"]),'title'=>$row["title"],"status"=>$row["status"],"type"=>$row["type"],"phone"=>stripslashes($row["phone"]));
			$_SESSION["rec_user"]=$user;
			$query = "update task_users set last_checkin_rec=NOW() where id='".$row["id"]."'";
			@mysql_query($query);
			header("location:home.php");
			exit;
		}
	}
	else
	{
		$_SESSION["loginresult"]="Invalid Username And Password";
		header("location:index.php");
		exit;
	}
}
else
{
	$_SESSION["loginresult"]="System is unable to check your username and password, please try again later";
	unset($_SESSION["rec_user"]);
	header("location:index.php");
	exit;
}
include "include/unconfig.php";
?>