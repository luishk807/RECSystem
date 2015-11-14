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
$host = getHost();
$task = $_REQUEST["task"];
if(!empty($task))
{
	if($task=="addemail")
	{
		$memail=strtolower(trim($_REQUEST["memail"]));
		$moptout=strtolower(trim($_REQUEST["moptout"]));
		$query="select * from m_emails where email='".clean($memail)."'";
		if($result=mysql_query($query))
		{
			if(($num_rows=mysql_num_rows($result))>0)
			{
				$_SESSION["recresult"]="ERROR: Email Address Already Exists";
				header("location:memail.php");
				exit;
			}
		}
		$query="insert ignore into m_emails_file(date)values(NOW())";
		if($result=mysql_query($query))
		{
			$id=mysql_insert_id();
			$query="insert ignore into m_emails(fileid,email,optout,date)values('".$id."','".clean($memail)."','".$moptout."',NOW())";
			if($result=mysql_query($query))
				$_SESSION["recresult"]="SUCCESS: Email Address Saved";
			else
				$_SESSION["recresult"]="ERROR: Email Address Can't Be Saved";
		}
		else
			$_SESSION["recresult"]="ERROR: Email Address Can't Be Saved";
	}
	if($task=="saveemail")
	{
		$id=base64_decode($_REQUEST["id"]);
		if(empty($id))
		{
			$_SESSION["recresult"]="ERROR: Invalid Entry";
			header("location:memail.php");
			exit;
		}
		$memail=strtolower(trim($_REQUEST["memail"]));
		$moptout=strtolower(trim($_REQUEST["moptout"]));
		$query="select * from m_emails where email='".clean($memail)."' and id !='".$id."'";
		if($result=mysql_query($query))
		{
			if(($num_rows=mysql_num_rows($result))>0)
			{
				$_SESSION["recresult"]="ERROR: Email Address Already Exists";
				header("location:memail.php");
				exit;
			}
		}
		$query="update m_emails set email='".clean($memail)."',optout='".$moptout."' where id='".$id."'";
		if($result=mysql_query($query))
				$_SESSION["recresult"]="SUCCESS: Email Address Saved";
		else
			$_SESSION["recresult"]="ERROR: Email Address Can't Be Saved";
	}
	else if($task=="delemail")
	{
		$eid=$_REQUEST["eid"];
		$nemail=sizeof($eid);
		if($nemail>0)
		{
			$del=0;
			for($i=0;$i<sizeof($eid);$i++)
			{
				$xid=base64_decode($eid[$i]);
				if(!empty($xid))
				{
					//grab the file id
					$qx="select fileid from m_emails where id='".$xid."'";
					if($rx=mysql_query($qx))
					{
						if(($numrox=mysql_num_rows($rx))>0)
							$info=mysql_fetch_assoc($rx);
					}
					//delete the email
					$query="delete from m_emails where id='".$xid."'";
					if($result=mysql_query($query))
					{
						$qx="delete from m_emails_send where emailid='".$xid."'";
						@mysql_query($qx);
						$delt=false;
						//make sure if there is any email left under this fileid, if not then delete file id
						$qx="select * from m_emails where fileid='".$info["fileid"]."'";
						if($rx=mysql_query($qx))
						{
							if(($numx=mysql_num_rows($rx))<1)
								$delt=true;
						}
						if($delt)//if no emails is found then delete from m_emails_file
						{
							$qx="delete from m_emails_file where id='".$info["fileid"]."'";
							@mysql_query($qx);
						}
						$del++;
					}
				}
			}
			if($del >= $nemail)
				$_SESSION["recresult"]="SUCCESS: All Selected Email Deleted";
			else if($del < $nemail)
				$_SESSION["recresult"]="SUCCESS: Selected Email Deleted But Some Email Couldn't be Deleted";
			else
				$_SESSION["recresult"]="ERROR: No Selected Email Can't Be Deleted";
		}
		else
			$_SESSION["recresult"]="ERROR: No Email Selected";
	}
	else if($task=="delaemail")
	{
		$query="truncate m_emails";
		if($result=mysql_query($query))
		{
			$query="truncate m_emails_file";
			@mysql_query($query);
			$query="truncate m_emails_sent";
			@mysql_query($query);
			$_SESSION["recresult"]="SUCCESS: Emails Completely Deleted";
		}
		else
			$_SESSION["recresult"]="ERROR:Emails Can't Be Deleted";
	}
	else if($task=="resentaemail")
	{
		header("location:sendmemails.php");
		exit;
	}
	else if($task=="resentremail")
	{
		$eid=$_REQUEST["eid"];
		$allid="";
		$ad=0;
		if(sizeof($eid)>0)
		{
			for($i=0;$i<sizeof($eid);$i++)
			{
				$idx=base64_decode($eid[$i]);
				if(!empty($idx))
				{
					if($ad==0)
						$allid="'".$idx."'";
					else
						$allid .=",'".$idx."'";
					$add++;
				}		
			}
			if($add>0)
			{
				$allidx=base64_encode($allid);
				header("location:sendmemails.php?aid=".$allidx);
				exit;	
			}
			else
				$_SESSION["recresult"]="ERROR: No Emails Selected";
		}
		else
			$_SESSION["recresult"]="ERROR: No Emails Selected";
	}
	else
		$_SESSION["recresult"]="ERROR:Invalid Request";
}
else
	$_SESSION["recresult"]="ERROR:Invalid Access";
header("location:memail.php");
exit;
include "include/config.php";
?>