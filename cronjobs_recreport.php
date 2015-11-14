<?php
session_start();
include "include/config.php";
include "include/function.php";
ini_set('memory_limit','500M');
require("include/phpMailer/class.phpmailer.php");
date_default_timezone_set('America/New_York');
$xdate = getPhoneWeek();
$today = date('Y-m-d');
$ttoday = $xdate["date1"];
$tday = $xdate["date2"];
$todaypf = fixdate_comps('invdate_s',$tday);
$todaypt = fixdate_comps('invdate_s',$ttoday);
$gtotal=0.00;
$thisyear=date("Y");
$users = array();
$listemails="";
$listphones="";
$listemails_a=array();
//$today="2012-04-12";
$fdate=$today."T00:00:00";
$tdate=$today."T23:00:00";
$restphone=array();
$found=false;
$savephone=array();
$manhattan_total=0;
$brooklyn_total=0;
$bronx_total=0;
/****************PHPMailer Iinitial Configuration********************************/
$mail = new PHPMailer();
$mail->IsSMTP(); // set mailer to use SMTP
$mail->Host = "smtpout.secureserver.net";  // specify main and backup server
$mail->SMTPAuth = true;     // turn on SMTP authentication
$mail->Username = "hr@familyenergysales.com";  // SMTP username
$mail->Password = "hr1514"; // SMTP password
$mail->Port = 80;
$mail->SMTPSecure = "http";
$mail->SMTPDebug = 0; // 1 tells it to display SMTP errors and messages, 0 turns off all errors and messages, 2 prints 
$mail->From = "no-reply@yourfamilyenergy.com";
$mail->FromName = "Family Energy Master Recruiter System";
//$mail->WordWrap = 50;                                 // set word wrap to 50 characters
$mail->IsHTML(true);                                  // set email format to HTML
//$mail->AltBody = "This is the body in plain text for non-HTML mail clients";
/****************PHPMailer Iinitial Configuration********************************/
//$query = "select * from task_users where id in('3','22','14','4')";
$query = "select * from task_users where id in('3')";
//$query = "select * from task_users where id in('1','2')";
//get the information of phone and email to send
if($result = mysql_query($query))
{
	if(($num_rows = mysql_num_rows($result))>0)
	{
		while($rows = mysql_fetch_array($result))
		{
			$users[] = array('id'=>$rows["id"],'name'=>stripslashes($rows["name"]),'email'=>stripslashes($rows["email"]),'phone'=>$rows["phone"]);
			if(!empty($rows["phone"]))
			{
				if(empty($listphones))
					$listphones="1".$rows["phone"];
				else
					$listphones .=",1".$rows["phone"];
			}
			if(!empty($rows["email"]))
			{
				$listemails_a[]=array("name"=>stripslashes($rows["name"]),"email"=>stripslashes($rows["email"]));
			}
		}
	}
}
//get all the restrictions phone
$query = "select * from rec_office order by id";
if($result = mysql_query($query))
{
	if(($num_rows=mysql_num_rows($result))>0)
	{
		while($rows=mysql_fetch_array($result))
		{
			$found=false;
			$ophone=ocomphone($rows["ophone"]);
			if(sizeof($restphone)>0)
			{
				for($i=0;$i<sizeof($restphone);$i++)
				{
					if($restphone[$i]==$ophone)
					{
						$found=true;
						break;
					}
				}
				if(!$found)
					$restphone[]=$ophone;
			}
			else
				$restphone[]=ocomphone($rows["ophone"]);
		}
	}
}
//get rest phone from users
$query = "select * from task_users where phone is not null order by id";
if($result = mysql_query($query))
{
	if(($num_rows=mysql_num_rows($result))>0)
	{
		while($rows=mysql_fetch_array($result))
		{
			$found=false;
			$ophone=ocomphone($rows["phone"]);
			if(sizeof($restphone)>0)
			{
				for($i=0;$i<sizeof($restphone);$i++)
				{
					if($restphone[$i]==$ophone)
					{
						$found=true;
						break;
					}
				}
				if(!$found)
					$restphone[]=$ophone;
			}
			else
				$restphone[]=ocomphone($rows["phone"]);
		}
	}
}
//end of restription phones gathering
$emailcheck=array();
if(sizeof($listemails_a)>0)
{
	$rtotal=getRunTotal($tday,$ttoday);
	$title = "Family Energy Recruiter System: $todaypt";
	$message = "<div style='font-size:20pt; text-align:center; background:#00477f; color:#FFF; font-weight:bold'>Report Date: $todaypt</div><br/><br/>";
	$message .="<div style='font-size:16pt; text-align:center; background:#00477f; color:#FFF'>Total From $todaypf to $todaypt: <b>$rtotal</b> Calls</div>";
	$message .="<div style='font-size:14pt; text-align:center; background:#00477f; color:#FFF'>Total Breakdown</div><br/><div style='text-align:center'>";
	//list of breakdown per office
	$query = "select * from rec_office where visible='yes' order by name";
	if($result = mysql_query($query))
	{
		if(($numrows = mysql_num_rows($result))>0)
		{
			while($rows = mysql_fetch_array($result))
			{
				$ototal = getRunTotalo($rows["id"],$tday,$ttoday);
				if($rows["id"]=="1")
					$brooklyn_total=$ototal;
				else if($rows["id"]=="2")
					$manhattan_total=$ototal;
				else if($rows["id"]=="3")
					$bronx_total=$ototal;
				$ototal_per=@(round(($ototal/$rtotal)*100))."%";
				$message .="<div style=text-align:center'><span style='font-size:15pt; font-family:Tahoma; text-align:center; text-decoration:underline; color:#00477f;'>".stripslashes($rows["name"]).":&nbsp; ".$ototal." Calls &nbsp; $ototal_per</span><br/>";
				$message .="</div><br/>";
				sleep(5);
			}
		}
	}
	$message .="</div><br/>";
	$mesaage .="<br/><br/><hr/><br/>";
	$message .="<div style='font-size:14pt; text-align:center; background:#00477f; color:#FFF'>Call Report Summary</div>";
	//get all offices
	$query = "select * from rec_office where visible='yes' order by name";
	if($result = mysql_query($query))
	{
		if(($numrows = mysql_num_rows($result))>0)
		{
			while($rows = mysql_fetch_array($result))
			{
				$gxtotal=0;
				if($rows["id"]=="1")
					$gxtotal=$brooklyn_total;
				else if($rows["id"]=="2")
					$gxtotal=$manhattan_total;
				else if($rows["id"]=="3")
					$gxtotal=$bronx_total;
				$intphone=array();
				$oriphone=array();
				$hiredphone=array();
				$totalint=0;
				$totalori=0;
				$totalhired=0;
				$totalint_per=0;
				$totalori_per=0;
				$totalhired_per=0;
				$message .="<div style='font-size:18pt; text-align:center; background:#00477f; color:#FFF'>".stripslashes($rows["name"])."</div>";
				//check all entries from rec_entries for interviews
				$qy="select * from rec_phones where office='".$rows["id"]."' and date between '".$tday."' and '".$ttoday."' order by date desc";
				if($ry=mysql_query($qy))
				{
					if(($ny=mysql_num_rows($ry))>0)
					{
						while($roy=mysql_fetch_array($ry))
						{
							if(isValidPhone("inter",$roy["tphone"],$roy["date"]))
							{
								$intphone[]=array("caller"=>$roy["caller"],"phone"=>$roy["tphone"],"date"=>$roy["date"]);
								$totalint++;
							}
							if(isValidPhone("orien",$roy["tphone"],$roy["date"]))
							{
								$oriphone[]=array("caller"=>$roy["caller"],"phone"=>$roy["tphone"],"date"=>$roy["date"]);
								$totalori++;
							}
							if(isValidPhone("hired",$roy["tphone"],$roy["date"]))
							{
								$hiredphone[]=array("caller"=>$roy["caller"],"phone"=>$roy["tphone"],"date"=>$roy["date"]);
								$totalhired++;
							}
						}
					}
				}
				$totalint_per=@round(($totalint/$gxtotal)*100);
				$totalori_per=@round(($totalori/$gxtotal)*100);
				$totalhired_per=@round(($totalhired/$gxtotal)*100);
				//interview
				$message .="<table width='100%' border='1' cellspacing='0' cellpadding='0'>";
				$message .="<tr><td colspan='3' align='center' valign='middle'>Interviews Setup</td><td width='33%' align='center' valign='middle'>".$totalint_per."% (".$totalint.")</td></tr>";
				if(sizeof($intphone)>0)
				{
					$counx=1;
					for($x=0;$x<sizeof($intphone);$x++)
					{
						$intphone_date=fixdate_comps('invdate_s',$intphone[$x]["date"]);
						$intphone_caller=checkNA($intphone[$x]["caller"]);
						$message .="<tr><td width='15%' align='center' valign='middle'>".$counx."</td><td width='17%' align='center' valign='middle'>".$intphone_date."</td><td width='35%' align='center' valign='middle'>".$intphone_caller."</td><td align='center' valign='middle'>".$intphone[$x]["phone"]."</td></tr>";
						$counx++;
					}
				}
				else
					$message .="<tr style='background:#8a0604; color:#FFF;font-style:italic'><td colspan='4' align='center' valign='middle'>No Interviews Setup From Calls</td></tr>";
				$message .="</table>";
				//orientation
				$message .="<table width='100%' border='1' cellspacing='0' cellpadding='0'>";
				$message .="<tr><td colspan='3' align='center' valign='middle'>Orientation Setup</td><td width='33%' align='center' valign='middle'>".$totalori_per."% (".$totalori.")</td></tr>";
				if(sizeof($oriphone)>0)
				{
					$counx=1;
					for($x=0;$x<sizeof($oriphone);$x++)
					{
						$oriphone_date=fixdate_comps('invdate_s',$oriphone[$x]["date"]);
						$oriphone_caller=checkNA($oriphone[$x]["caller"]);
						$message .="<tr><td width='15%' align='center' valign='middle'>".$counx."</td><td width='17%' align='center' valign='middle'>".$oriphone_date."</td><td width='35%' align='center' valign='middle'>".$oriphone_caller."</td><td align='center' valign='middle'>".$oriphone[$x]["phone"]."</td></tr>";
						$counx++;
					}
				}
				else
					$message .="<tr style='background:#8a0604; color:#FFF;font-style:italic'><td colspan='4' align='center' valign='middle'>No Orientation Setup From Calls</td></tr>";
				$message .="</table>";
				//hired
				$message .="<table width='100%' border='1' cellspacing='0' cellpadding='0'>";
				$message .="<tr><td colspan='3' align='center' valign='middle'>Hired</td><td width='33%' align='center' valign='middle'>".$totalhired_per."% (".$totalhired.")</td></tr>";
				if(sizeof($hiredphone)>0)
				{
					$counx=1;
					for($x=0;$x<sizeof($hiredphone);$x++)
					{
						$hiredphone_date=fixdate_comps('invdate_s',$hiredphone[$x]["date"]);
						$hiredphone_caller=checkNA($hiredphone[$x]["caller"]);
						$message .="<tr><td width='15%' align='center' valign='middle'>".$counx."</td><td width='17%' align='center' valign='middle'>".$hiredphone_date."</td><td width='35%' align='center' valign='middle'>".$hiredphone_caller."</td><td align='center' valign='middle'>".$hiredphone[$x]["phone"]."</td></tr>";
						$counx++;
					}
				}
				else
					$message .="<tr style='background:#8a0604; color:#FFF;font-style:italic'><td colspan='4' align='center' valign='middle'>No Hired From Calls</td></tr>";
				$message .="</table>";
				$message .="<br/><br/>";
				sleep(5);
			}
		}
		else
			$message .="<div style='font-size:18pt; text-align:center; text-decoration:underline; color:#00477f;'>**************************************************<br/>NO OFFICE FOUND IN SYSTEM<br/>**************************************************</div>";
	}
	else
		$message .="<div style='font-size:18pt; text-align:center; text-decoration:underline; color:#00477f;'>**************************************************<br/>NO OFFICE FOUND IN SYSTEM<br/>**************************************************</div>";
	//echo $message;
	for($h=0;$h<sizeof($listemails_a);$h++)
	{
		$mail->AddAddress($listemails_a[$h]["email"], $listemails_a[$h]["name"]);
	}
	$xtoday = fixdate_comps('invdate_s',$today);
	$title="Family Energy Recruiter System: ".$xtoday;
	$mail->Subject=$title;
	$mail->Body=$message;
	$mail->Send();
}
if(!empty($listphones))
{
	$mmessage="Family Energy Sales Report Updates: Report Has Been Sent To Your Email";
	$result = sendSMSm($listphones,$mmessage);
}
include "include/unconfig.php";
?>