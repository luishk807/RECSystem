<?php
session_start();
include "include/config.php";
include "include/function.php";
ini_set('memory_limit','500M');
require("include/phpMailer/class.phpmailer.php");
date_default_timezone_set('America/New_York');
$today=date('Y-m-d');
$today_comp=$today." ".date("H:m:s");
$date1=getFirstDay($today);
$date2=getLastDay($today);
$todaypt=fixdate_comps('invdate_s',$today);
$xmonth=date('F',strtotime($today));
$users = array();
$users_ad = array();
$listphones="";
$listphones_ad="";
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
$do_this=false;
$inter_ar=array();
$orien_ar=array();
$orienc_ar=array();
//get interview set 
$query="select * from rec_entries where DATE_ADD(idate, INTERVAL itime HOUR_SECOND) >='".$today_comp."' and status='1' order by office, idate desc, itime desc";
if($result=mysql_query($query))
{
	if(($numint_total=mysql_num_rows($result))>0)
	{
		$do_this=true;
		while($rows=mysql_fetch_array($result))
			$inter_ar[]=array('cname'=>stripslashes($rows["cname"]),'csource'=>$rows["csource"],'idate'=>$rows["idate"],'itime'=>$rows["itime"],'office'=>$rows["office"]);
	}
}
//get orientation set up
$query="select * from rec_entries where orientation >='".$today_comp."' order by office, orientation desc";
if($result=mysql_query($query))
{
	if(($numorien=mysql_num_rows($result))>0)
	{
		$do_this=true;
		while($rows=mysql_fetch_array($result))
			$orien_ar[]=array('cname'=>stripslashes($rows["cname"]),'csource'=>$rows["csource"],'orientation'=>$rows["orientation"],'orientation_office'=>$rows["orientation_office"]);
	}
}
//get orientation completed in the month
$query="select * from rec_entries where orientation_comp between '".$date1."' and '".$date2."' order by office, orientation desc";
if($result=mysql_query($query))
{
	if(($numorienc=mysql_num_rows($result))>0)
	{
		$do_this=true;
		while($rows=mysql_fetch_array($result))
			$orienc_ar[]=array('cname'=>stripslashes($rows["cname"]),'orientation_comp'=>$rows["orientation_comp"],'orientation_office'=>$rows["orientation_office"],'acode'=>$rows["ccode"]);
	}
}
$query = "select * from task_users where id in('3') and status='1'";
//get the information of admin of phone and email to send
if($result = mysql_query($query))
{
	if(($num_rows = mysql_num_rows($result))>0)
	{
		while($rows = mysql_fetch_array($result))
		{
			$users_ad[] = array('id'=>$rows["id"],'name'=>stripslashes($rows["name"]),'email'=>stripslashes($rows["email"]),'phone'=>$rows["phone"],'office'=>$rows["office"],'type'=>$rows["type"]);
			if(!empty($rows["phone"]))
			{
				if(empty($listphones_ad))
					$listphones_ad="1".$rows["phone"];
				else
					$listphones_ad .=",1".$rows["phone"];
			}
		}
	}
}
if($do_this)
{
$query = "select * from task_users where type in('6') and status='1'";
//$query = "select * from task_users where id in('51') and status='1'";
//get the information of phone and email to send
if($result = mysql_query($query))
{
	if(($num_rows = mysql_num_rows($result))>0)
	{
		while($rows = mysql_fetch_array($result))
		{
			if(!empty($rows["email"]))
			{
				$users[] = array('id'=>$rows["id"],'name'=>stripslashes($rows["name"]),'email'=>stripslashes($rows["email"]),'phone'=>$rows["phone"],'office'=>$rows["office"],'type'=>$rows["type"]);
				if(!empty($rows["phone"]))
				{
					if(empty($listphones))
						$listphones="1".$rows["phone"];
					else
						$listphones .=",1".$rows["phone"];
				}
			}
		}
	}
}
//add the admins to the list
/*$recadmin=array();
$query = "select * from task_users where type in('10') and status='1'";
if($result = mysql_query($query))
{
	if(($num_rows = mysql_num_rows($result))>0)
	{
		while($rows = mysql_fetch_array($result))
		{
			if(!empty($rows["email"]))
			{
				$recadmin[] = array('id'=>$rows["id"],'name'=>stripslashes($rows["name"]),'email'=>stripslashes($rows["email"]),'phone'=>$rows["phone"],'office'=>$rows["office"],'type'=>$rows["type"]);
			}
		}
	}
}
if(sizeof($recadmin)>0)
{
	for($i=0;$i<sizeof($recadmin);$i++)
	{
		$query = "select * from cronjob_reports_sender where report='1' and userid='".$recadmin[$i]['id']."'";
		if($result = mysql_query($query))
		{
			if(($num_rows = mysql_num_rows($result))>0)
			{
				while($rows = mysql_fetch_array($result))
				{
					if(!empty($recadmin[$i]["email"]))
					{
						$users[]=array('id'=>$recadmin[$i]["id"],'name'=>stripslashes($recadmin[$i]["name"]),'email'=>stripslashes($recadmin[$i]["email"]),'phone'=>$recadmin[$i]["phone"],'office'=>$rows["office"],'type'=>$recadmin[$i]["type"]);
						if(!empty($recadmin[$i]["phone"]))
						{
							if(empty($listphones))
								$listphones="1".$recadmin[$i]["phone"];
							else
								$listphones .=",1".$recadmin[$i]["phone"];
						}
					}
				}
			}
		}
	}
}*/
//end of receptionist
//new gather extra report
$query = "select * from cronjob_reports_sender where report='1'";
if($result = mysql_query($query))
{
	if(($num_rows = mysql_num_rows($result))>0)
	{
		while($rows = mysql_fetch_array($result))
		{
			$dopend=false;
			$qx="select * from task_users where id='".$rows["userid"]."'";
			if($rx=mysql_query($qx))
			{
				if(($numrowx=mysql_num_rows($rx))>0)
				{
					$recadmin=mysql_fetch_assoc($rx);
					if($recadmin['status']=='1' && !empty($recadmin['email']))
						$dopend=true;
				}
			}
			if($dopend)
			{
				$users[]=array('id'=>$recadmin["id"],'name'=>stripslashes($recadmin["name"]),'email'=>stripslashes($recadmin["email"]),'phone'=>$recadmin["phone"],'office'=>$rows["office"],'type'=>$recadmin["type"]);
				if(!empty($recadmin["phone"]))
				{
					if(empty($listphones))
						$listphones="1".$recadmin["phone"];
					else
						$listphones .=",1".$recadmin["phone"];
				}
			}
		}
	}
}
//end of new extra report
//delete if entries in nshow are already hired or orientation completed
/*$query="select * from rec_nshow_track order by id";
if($result=mysql_query($query))
{
	if(($num_rows=mysql_num_rows($result))>0)
	{
		while($rows=mysql_fetch_array($result))
		{
			$delx=false;
			$qx="select * from rec_entries where id='".$rows["id"]."' and status in('5','7')";
			if($rx=mysql_query($qx))
			{
				if(($numx=mysql_num_rows($rx))>0)
					$delx=true;
			}
			if($delx)
			{
				$qx="delete from rec_nshow_track where entryid='".$rows["id"]."'";
				@mysql_query($qx);
			}
		}
	}
}
sleep(5);*/
//end of delete if entries in nshow are already hired or orientation completed
if(sizeof($users)>0)
{
	for($i=0;$i<sizeof($users);$i++)
	{
		$query="select * from rec_entries where DATE_ADD(idate, INTERVAL itime HOUR_SECOND) >='".$today_comp."' and status='1' and office='".$users[$i]["office"]."'";
		if($result=mysql_query($query))
			$numint_totalx=mysql_num_rows($result);
		$query="select * from rec_entries where orientation >='".$today_comp."' and status='3' and orientation_office='".$users[$i]["office"]."'";
		if($result=mysql_query($query))
			$numorien_totalx=mysql_num_rows($result);
		$query="select * from rec_entries where orientation_comp between '".$date1."' and '".$date2."' and status='7' and orientation_office='".$users[$i]["office"]."'";
		if($result=mysql_query($query))
			$numorienc_totalx=mysql_num_rows($result);
		$title = "Family Energy Recruiter System: $todaypt";
		$message = "<div style='font-size:20pt; text-align:center; background:#00477f; color:#FFF; font-weight:bold'>".$users[$i]["name"]."</div>";
		$message .= "<div style='font-size:20pt; text-align:center; background:#00477f; color:#FFF; font-weight:bold'>Report Date: $todaypt</div><br/><br/>";
		$message .="<div style='font-size:16pt; text-align:center; background:#00477f; color:#FFF'><b>".getOfficeName($users[$i]["office"])."</b></div>";
		$message .="<div style='font-size:16pt; text-align:center; background:#00477f; color:#FFF'>Upcoming Interviews: <b>$numint_totalx</b></div>";
		$message .="<div style='font-size:16pt; text-align:center; background:#00477f; color:#FFF'>Upcoming Orientation: <b>$numorien_totalx</b></div>";
		$message .="<div style='font-size:16pt; text-align:center; background:#00477f; color:#FFF'>".$xmonth." Orientation Comp: <b>$numorienc_totalx</b></div>";
		$message .="<br/>";
		$mesaage .="<br/><br/>";
		$message .="<div style='font-size:14pt; text-align:center; background:#00477f; color:#FFF'>Report Summary</div>";
		//$qx = "select * from task_users where id in('3')";
		//get interviews
		$message .="<table width='100%' border='1' cellspacing='0' cellpadding='0'>";
		$message .="<tr><td colspan='4' align='center' valign='middle' style='font-size:14pt'>".getOfficeName($users[$i]["office"])." Upcoming Interviews<td></tr>";
		$message .="
		<tr>
		<td valign='middle' align='center' width='6%'>&nbsp;</td>
		<td valign='middle' align='center' width='39%'>Name</td>
		<td valign='middle' align='center' width='20%'>Source</td>
		<td valign='middle' align='center' width='35%'>Interview Date</td>
		</tr>
		";
		$query="select * from rec_entries where DATE_ADD(idate, INTERVAL itime HOUR_SECOND) >='".$today_comp."' and status='1' and office='".$users[$i]["office"]."' order by office, idate desc, itime desc";
		if($result=mysql_query($query))
		{
			if(($numint_totalx=mysql_num_rows($result))>0)
			{
				$cint=0;
				while($rows=mysql_fetch_array($result))
				{
					$cint++;
					//saveNShow_track($rows["id"],$rows["status"],$users[$i]["office"]);
					$message .="
					<tr>
					<td valign='middle' align='center'>$cint</td>
					<td valign='middle' align='center'>".stripslashes($rows["cname"])."</td>
					<td valign='middle' align='center'>".getSourceName($rows["csource"])."</td>
					<td valign='middle' align='center'>".fixdate_comps("onsip",$rows["idate"]." ".$rows["itime"])."</td>
					</tr>
					";
				}
			}
			else
				$message .="<tr><td colspan='4' align='center' valign='middle'>No Interviews Found</td></tr>";
		}
		else
			$message .="<tr><td colspan='4' align='center' valign='middle'>No Interviews Found</td></tr>";
		$message .="</table>";
		$message .="<br/><br/>";
		//interview no show
		$noshow_ar=array();
		$qx="select * from rec_nshow_track where office='".$users[$i]["office"]."' and cstatus='1'";
		if($rx=mysql_query($qx))
		{
			if(($nx=mysql_num_rows($rx))>0)
			{
				while($rxx=mysql_fetch_array($rx))
				{
					if(checkEntryStatus($rxx["entryid"],"inter"))
					{
						//$sqx="update rec_nshow_track set ccheck='yes' where id='".$rxx["id"]."'";
						//@mysql_query($sqx);
						$nsq="select * from rec_entries where id='".$rxx["entryid"]."'";
						if($nsr=mysql_query($nsq))
						{
							if(($nsn=mysql_num_rows($nsr))>0)
							{
								$info_nsr=mysql_fetch_assoc($nsr);
								$noshow_ar[]=array('id'=>$info_nsr["id"],'cname'=>stripslashes($info_nsr["cname"]),'info'=>stripslashes($info_nsr["int_show_info"]),'status'=>$info_nsr["status"],'idate'=>fixdate_comps('onsip',$info_nsr["idate"]." ".$info_nsr["itime"]),'csource'=>getSourceName($info_nsr["csource"]),'cphone'=>$info_nsr["cphone"]);
							}
						}
					}
				}
			}
		}
		if(sizeof($noshow_ar)>0)
		{
			$message .="<table width='100%' border='1' cellspacing='0' cellpadding='0'>";
			$message .="<tr><td colspan=3' align='center' valign='middle' style='font-size:14pt; background-color:#F00; color:#FFF'>".getOfficeName($users[$i]["office"])." No-Show or Cancelled Interviews<td></tr>";
			$message .="
			<tr>
			<td valign='middle' align='center' width='6%'>&nbsp;</td>
			<td valign='middle' align='center' width='39%'>Name</td>
			<td valign='middle' align='center' width='55%'>Information</td>
			</tr>
			";
			$cint=0;
			for($ns=0;$ns<sizeof($noshow_ar);$ns++)
			{
				$cint++;
				$message .="
				<tr>
				<td valign='middle' align='center'>$cint</td>
				<td valign='middle' align='center'>".$noshow_ar[$ns]["cname"]."</td>
				<td valign='middle' align='center'>
					<table width='100%' border='0' cellspacing='0' cellpadding='0'>
						<tr>
							<td style='border-bottom:solid 2px #C0C0C0'>Date</td>
							<td style='border-left:solid 2px #C0C0C0;border-bottom:solid 2px #C0C0C0'>
							".$noshow_ar[$ns]["idate"]."
							</td>
					    </tr>
						<tr>
							<td style='border-bottom:solid 2px #C0C0C0'>Source</td>
							<td style='border-left:solid 2px #C0C0C0;border-bottom:solid 2px #C0C0C0'>
							".checkNA(getSourceName($noshow_ar[$ns]["csource"]))."
							</td>
					    </tr>
						<tr>
							<td style='border-bottom:solid 2px #C0C0C0'>Phone</td>
							<td style='border-left:solid 2px #C0C0C0;border-bottom:solid 2px #C0C0C0'>
							".checkNA($noshow_ar[$ns]["cphone"])."
							</td>
					    </tr>
						<tr>
							<td>Info</td>
							<td style='border-left:solid 2px #C0C0C0'>
							".checkNA($noshow_ar[$ns]["info"])."
							</td>
						</tr>
					</table>
				</td>
				</tr>
				";
			}
			$message .="</table>";
			$message .="<br/><br/>";
		}
		//get orientation
		$message .="<table width='100%' border='1' cellspacing='0' cellpadding='0'>";
		$message .="<tr><td colspan='4' align='center' valign='middle' style='font-size:14pt'>".getOfficeName($users[$i]["office"])." Orientation Detail<td></tr>";
		$message .="
		<tr>
		<td valign='middle' align='center' width='6%'>&nbsp;</td>
		<td valign='middle' align='center' width='39%'>Name</td>
		<td valign='middle' align='center' width='20%'>Source</td>
		<td valign='middle' align='center' width='35%'>Orientation Date</td>
		</tr>
		";
		$query="select * from rec_entries where orientation >='".$today_comp."' and status='3' and orientation_office='".$users[$i]["office"]."' order by orientation desc";
		if($result=mysql_query($query))
		{
			if(($numint_totalx=mysql_num_rows($result))>0)
			{
				$corien=0;
				while($rows=mysql_fetch_array($result))
				{
					//saveNShow_track($rows["id"],$rows["status"],$users[$i]["office"]);
					$corien++;
					$message .="
					<tr>
					<td valign='middle' align='center'>$corien</td>
					<td valign='middle' align='center'>".stripslashes($rows["cname"])."</td>
					<td valign='middle' align='center'>".getSourceName($rows["csource"])."</td>
					<td valign='middle' align='center'>".fixdate_comps("onsip",$rows["orientation"])."</td>
					</tr>
					";
				}
			}
			else
				$message .="<tr><td colspan='4' align='center' valign='middle'>No Orientation Found</td></tr>";
		}
		else
			$message .="<tr><td colspan='4' align='center' valign='middle'>No Orientation Found</td></tr>";
		$message .="</table>";
		$message .="<br/><br/>";
		//orientation no show or incompleted or no hired
		$nori_ar=array();
		$qx="select * from rec_nshow_track where office='".$users[$i]["office"]."' and cstatus='3'";
		if($rx=mysql_query($qx))
		{
			if(($nx=mysql_num_rows($rx))>0)
			{
				while($rxx=mysql_fetch_array($rx))
				{
					if(checkEntryStatus($rxx["entryid"],"orien"))
					{
						//$sqx="update rec_nshow_track set ccheck='yes' where id='".$rxx["id"]."'";
						//@mysql_query($sqx);
						$nsq="select * from rec_entries where id='".$rxx["entryid"]."'";
						if($nsr=mysql_query($nsq))
						{
							if(($nsn=mysql_num_rows($nsr))>0)
							{
								$info_nsr=mysql_fetch_assoc($nsr);
								$nori_ar[]=array('id'=>$info_nsr["id"],'cname'=>stripslashes($info_nsr["cname"]),'info'=>stripslashes($info_nsr["ori_show_info"]),'status'=>$info_nsr["status"],'orientation'=>fixdate_comps('onsip',$info_nsr["orientation"]),'csource'=>getSourceName($info_nsr["csource"]),'cphone'=>$info_nsr["cphone"],'manager'=>getName($info_nsr["interviewer"]));
							}
						}
					}
				}
			}
		}
		if(sizeof($nori_ar)>0)
		{
			$message .="<table width='100%' border='1' cellspacing='0' cellpadding='0'>";
			$message .="<tr><td colspan='3' align='center' valign='middle' style='font-size:14pt; background-color:#F00; color:#FFF'>".getOfficeName($users[$i]["office"])." Orientation Absent, Incompleted, Not Hired<td></tr>";
			$message .="
			<tr>
			<td valign='middle' align='center' width='6%'>&nbsp;</td>
			<td valign='middle' align='center' width='39%'>Name</td>
			<td valign='middle' align='center' width='55%'>Information</td>
			</tr>
			";
			$cint=0;
			for($ns=0;$ns<sizeof($nori_ar);$ns++)
			{
				$cint++;
				$message .="
				<tr>
				<td valign='middle' align='center'>$cint</td>
				<td valign='middle' align='center'>".$nori_ar[$ns]["cname"]."</td>
				<td valign='middle' align='center'>
					<table width='100%' border='0' cellspacing='0' cellpadding='0'>
						<tr>
							<td style='border-bottom:solid 2px #C0C0C0'>Date</td>
							<td style='border-left:solid 2px #C0C0C0;border-bottom:solid 2px #C0C0C0'>
							".$nori_ar[$ns]["orientation"]."
							</td>
					    </tr>
						<tr>
							<td style='border-bottom:solid 2px #C0C0C0'>Source</td>
							<td style='border-left:solid 2px #C0C0C0;border-bottom:solid 2px #C0C0C0'>
							".checkNA(getSourceName($nori_ar[$ns]["csource"]))."
							</td>
					    </tr>
						<tr>
							<td style='border-bottom:solid 2px #C0C0C0'>Phone</td>
							<td style='border-left:solid 2px #C0C0C0;border-bottom:solid 2px #C0C0C0'>
							".checkNA($nori_ar[$ns]["cphone"])."
							</td>
					    </tr>
						<tr>
							<td style='border-bottom:solid 2px #C0C0C0'>Manager</td>
							<td style='border-left:solid 2px #C0C0C0;border-bottom:solid 2px #C0C0C0'>
							".checkNA($nori_ar[$ns]["manager"])."
							</td>
					    </tr>
						<tr>
							<td >Info</td>
							<td style='border-left:solid 2px #C0C0C0;'>
							".checkNA($nori_ar[$ns]["info"])."
							</td>
						</tr>
					</table>
				</td>
				</tr>
				";
			}
			$message .="</table>";
			$message .="<br/><br/>";
		}
		//get orientation Completed
		$message .="<table width='100%' border='1' cellspacing='0' cellpadding='0'>";
		$message .="<tr><td colspan='4' align='center' valign='middle' style='font-size:14pt'>".getOfficeName($users[$i]["office"])." Orientation Completed For $xmonth <td></tr>";
		$message .="
		<tr>
		<td valign='middle' align='center' width='6%'>&nbsp;</td>
		<td valign='middle' align='center' width='59%'>Name</td>
		<td valign='middle' align='center' width='10%'>Agent Code</td>
		<td valign='middle' align='center' width='25%'>Orien Comp Date</td>
		</tr>
		";
		$query="select * from rec_entries where orientation_comp between '".$date1."' and '".$date2."' and orientation_office='".$users[$i]["office"]."' order by orientation_office, orientation_comp desc";
		if($result=mysql_query($query))
		{
			if(($numint_totalx=mysql_num_rows($result))>0)
			{
				$corienc=0;
				while($rows=mysql_fetch_array($result))
				{
					$corienc++;
					$message .="
					<tr>
					<td valign='middle' align='center'>$corienc</td>
					<td valign='middle' align='center'>".stripslashes($rows["cname"])."</td>
					<td valign='middle' align='center'>".checkNA($rows["ccode"])."</td>
					<td valign='middle' align='center'>".fixdate_comps("onsip_s",$rows["orientation_comp"])."</td>
					</tr>
					";
				}
			}
			else
				$message .="<tr><td colspan='4' align='center' valign='middle'>No Orientation Completed Found</td></tr>";
		}
		else
			$message .="<tr><td colspan='4' align='center' valign='middle'>No Orientation Completed Found</td></tr>";
		$message .="</table>";
		$message .="<br/><br/>";
		echo $message;
		echo "<hr/><br/>";
		/*$mail2=NULL;
		$mail2=clone $mail;
		//$mail2->AddAddress(trim($users[$i]["email"]), stripslashes($users[$i]["name"]));
		$mail2->AddAddress('luishk807@hotmail.com','Luis Ho');
		$xtoday =fixdate_comps('invdate_s',$today);
		$title="Family Energy Recruiter System: ".$xtoday;
		$mail2->Subject=$title;
		$mail2->Body=$message;
		$mail2->Send();*/
	}
}
/*if(!empty($listphones))
{
	$mmessage="Family Energy Sales Report Updates: Interviews Report Has Been Sent To Your Email";
	$result = sendSMSm($listphones,$mmessage);
}*/
//delete from rec_nshow_track
//$dqy="delete from rec_nshow_track where ccheck='yes'";
//@mysql_query($dqy);
/*$dtable=false;
$dqy="select * from rec_nshow_track";
if($dr=mysql_query($dqy))
{
	if(($numdr=mysql_num_rows($dr))<1)
		$dtable=true;
}
if($dtable)
{
	$dqy="truncate rec_nshow_track";
	@mysql_query($dqy);
}
@mysql_query($dqy);*/
//sleep(5);
//now do the admin
if(sizeof($users_ad)>0)
{
	$title = "Family Energy Recruiter System: $todaypt";
	$message = "<div style='font-size:20pt; text-align:center; background:#00477f; color:#FFF; font-weight:bold'>Report Date: $todaypt</div><br/><br/>";
	$message .="<div style='font-size:16pt; text-align:center; background:#00477f; color:#FFF'>Upcoming Interviews: <b>$numint_total</b></div>";
	$message .="<div style='font-size:16pt; text-align:center; background:#00477f; color:#FFF'>Upcoming Orienation: <b>$numorien</b></div>";
	$message .="<div style='font-size:16pt; text-align:center; background:#00477f; color:#FFF'>".$xmonth." Orienation Comp: <b>$numorienc</b></div>";
	$message .="<div style='font-size:14pt; text-align:center; background:#00477f; color:#FFF'>Total Breakdown</div><br/><div style='text-align:center'>";
	//list of breakdown per office
	$query = "select * from rec_office order by name";
	if($result = mysql_query($query))
	{
		if(($numrows = mysql_num_rows($result))>0)
		{
			while($rows = mysql_fetch_array($result))
			{
				$ototal=getNumInterPerOffice('inter',$rows["id"],$today_comp,"");
				$ototal_per=@(round(($ototal/$numint_total)*100))."%";
				$orien=getNumInterPerOffice('orien',$rows["id"],$today_comp,"");
				$orien_per=@(round(($orien/$numorien)*100))."%";
				$orienc=getNumInterPerOffice('orienc',$rows["id"],$date1,$date2);
				$orienc_per=@(round(($orienc/$numorienc)*100))."%";
				$message .="<table width='100%' border='1' cellspacing='0' cellpadding='0'>
				<tr style='font-size:15pt; font-family:Tahoma;'>
					<td colspan='3' align='center' valign='middle' style='background:#00477f; color:#FFF''>".stripslashes($rows["name"])."</td>
				</tr>
				<tr style='font-size:14pt; font-family:Tahoma;'>
					<td width='40%' align='center' validn='middle'>Upcoming Interviews (".$ototal_per.")</td>
					<td width='30%' align='center' validn='middle'>Upcoming Orientation (".$orien_per.")</td>
					<td width='30%' align='center' validn='middle'>".$xmonth." Orientation Comp (".$orienc_per.")</td>
				</tr>
				<tr style='font-size:14pt; font-family:Tahoma;'>
				<td align='center' validn='middle'>".$ototal."</td>
				<td align='center' validn='middle'>".$orien."</td>
				<td align='center' validn='middle'>".$orienc."</td>
				</tr>
				";
				$message .="</table><br/>";
				//sleep(5);
			}
		}
	}
	$message .="</div><br/>";
	$mesaage .="<br/><br/>";
	$message .="<div style='font-size:14pt; text-align:center; background:#00477f; color:#FFF'>Report Summary</div>";
	//interviews
	$message .="<table width='100%' border='1' cellspacing='0' cellpadding='0'>";
	$message .="
	<tr>
		<td colspan='5' align='center' valign='middle' style='font-size:14pt'>Upcoming Interviews<td>
	</tr>";
	$message .="
	<tr>
	<td valign='middle' align='center' width='6%'>&nbsp;</td>
	<td valign='middle' align='center' width='34%'>Name</td>
	<td valign='middle' align='center' width='30%'>Office</td>
	<td valign='middle' align='center' width='15%'>Source</td>
	<td valign='middle' align='center' width='15%'>Interview Date</td>
	</tr>
	";
	if(sizeof($inter_ar)<1)
		$message .="<tr><td colspan='5' align='center' valign='middle'>No Interviews Found</td></tr>";
	else
	{
		$cint=0;
		for($i=0;$i<sizeof($inter_ar);$i++)
		{
			$cint=$i+1;
			$message .="
			<tr>
			<td valign='middle' align='center'>$cint</td>
			<td valign='middle' align='center'>".$inter_ar[$i]["cname"]."</td>
			<td valign='middle' align='center'>".getOfficeName($inter_ar[$i]["office"])."</td>
			<td valign='middle' align='center'>".getSourceName($inter_ar[$i]["csource"])."</td>
			<td valign='middle' align='center'>".fixdate_comps("onsip",$inter_ar[$i]["idate"]." ".$inter_ar[$i]["itime"])."</td>
			</tr>
			";
		}
	}
	$message .="</table>";
	$message .="<br/><br/>";
	//orientation
	$message .="<table width='100%' border='1' cellspacing='0' cellpadding='0'>";
	$message .="<tr><td colspan='5' align='center' valign='middle' style='font-size:14pt'>Orientation Detail<td></tr>";
	$message .="
	<tr>
	<td valign='middle' align='center' width='6%'>&nbsp;</td>
	<td valign='middle' align='center' width='34%'>Name</td>
	<td valign='middle' align='center' width='30%'>Office</td>
	<td valign='middle' align='center' width='15%'>Source</td>
	<td valign='middle' align='center' width='15%'>Orientation Date</td>
	</tr>
	";
	if(sizeof($orien_ar)<1)
		$message .="<tr><td colspan='5' align='center' valign='middle'>No Orientation Found</td></tr>";
	else
	{
		$corien=0;
		for($i=0;$i<sizeof($orien_ar);$i++)
		{
			$corien=$i+1;
			$message .="
			<tr>
			<td valign='middle' align='center'>$corien</td>
			<td valign='middle' align='center'>".$orien_ar[$i]["cname"]."</td>
			<td valign='middle' align='center'>".getOfficeName($orien_ar[$i]["orientation_office"])."</td>
			<td valign='middle' align='center'>".getSourceName($orien_ar[$i]["csource"])."</td>
			<td valign='middle' align='center'>".fixdate_comps("onsip",$orien_ar[$i]["orientation"])."</td>
			</tr>
			";
		}
	}
	$message .="</table>";
	$message .="<br/><br/>";
	//orientaiton comp
	$message .="<table width='100%' border='1' cellspacing='0' cellpadding='0'>";
	$message .="<tr><td colspan='5' align='center' valign='middle' style='font-size:14pt'>".$xmonth." Orientation Comp<td></tr>";
	$message .="
	<tr>
	<td valign='middle' align='center' width='6%'>&nbsp;</td>
	<td valign='middle' align='center' width='44%'>Name</td>
	<td valign='middle' align='center' width='10%'>Agent Code</td>
	<td valign='middle' align='center' width='25%'>Office</td>
	<td valign='middle' align='center' width='15%'>Orien Comp Date</td>
	</tr>
	";
	if(sizeof($orienc_ar)<1)
		$message .="<tr><td colspan='5' align='center' valign='middle'>No Orientation Completed Found</td></tr>";
	else
	{
		$corienc=0;
		for($i=0;$i<sizeof($orienc_ar);$i++)
		{
			$corienc=$i+1;
			$message .="
			<tr>
			<td valign='middle' align='center'>$corienc</td>
			<td valign='middle' align='center'>".$orienc_ar[$i]["cname"]."</td>
			<td valign='middle' align='center'>".checkNA($orienc_ar[$i]["acode"])."</td>
			<td valign='middle' align='center'>".getOfficeName($orienc_ar[$i]["orientation_office"])."</td>
			<td valign='middle' align='center'>".fixdate_comps("onsip_s",$orienc_ar[$i]["orientation_comp"])."</td>
			</tr>
			";
		}
	}
	$message .="</table>";
	$message .="<br/><br/>";
	echo $message;
	$mail2=NULL;
	$mail2=clone $mail;
	/*for($h=0;$h<sizeof($users_ad);$h++)
	{
		$mail2->AddAddress($users_ad[$h]["email"], $users_ad[$h]["name"]);
	}*/
	/*$mail2->AddAddress("luishk807@hotmail.com","Luis Ho");
	$xtoday = fixdate_comps('invdate_s',$today);
	$title="Family Energy Recruiter System: ".$xtoday;
	$mail2->Subject=$title;
	$mail2->Body=$message;
	$mail2->Send();*/
}
/*if(!empty($listphones_ad))
{
	$mmessage="Family Energy Sales Report Updates: Interview Report Has Been Sent To Your Email";
	$result = sendSMSm($listphones_ad,$mmessage);
}*/
}
include "include/unconfig.php";
?>