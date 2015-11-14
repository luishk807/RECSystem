<?php
session_start();
include "include/config.php";
include "include/function.php";
date_default_timezone_set('America/New_York');
adminlogin();
if(empty($_SERVER['HTTP_REFERER']))
{
	header("location:status.php");
	exit;
}
$host = getHost();
$task = $_REQUEST["task"];
if($task=="save")
{
	$user = $_SESSION["rec_user"];
	$uname = trim($_REQUEST["uname"]);
	$upass = trim($_REQUEST["newpass"]);
	$changepass = $_REQUEST["changepass"];
	$newpass = trim($_REQUEST["newpass"]);
	$cphone = trim($_REQUEST["cphone"]);
	$name = trim(ucwords(strtolower($_REQUEST["realname"])));
	$email =trim(strtolower($_REQUEST["uemail"]));
	if($uname != $user["username"])
	{
		$query = "select * from task_users where username='".clean($uname)."'";
		if($result = mysql_query($query))
		{
			if(($num_rows = mysql_num_rows($result))>0)
			{
				$_SESSION["recresult"]="ERROR: Username already in use";
				header('location:setting.php');
				exit;
			}
		}
	}
	$title = trim(ucwords(strtolower($_REQUEST["utitle"])));
	if($changepass=="yes")
		$query = "update task_users set username='".clean($uname)."',password='".md5(clean($newpass))."',name='".clean($name)."',title='".clean($title)."',email='".clean($email)."', phone='".clean($cphone)."' where id='".$user["id"]."'";
	else
		$query = "update task_users set username='".clean($uname)."',name='".clean($name)."',title='".clean($title)."',email='".clean($email)."' , phone='".clean($cphone)."' where id='".$user["id"]."'";
	if($result = mysql_query($query))
		$_SESSION["recresult"]="SUCCESS: Changes Saved";
	else
		$_SESSION["reckresult"]="ERROR: Unable To Save Changes";
	$query = "select * from task_users where id='".$user["id"]."'";
	if($result = mysql_query($query))
	{
		if(($num_rows = mysql_num_rows($result))>0)
		{
			$row = mysql_fetch_assoc($result);
			$user = array("id"=>$row["id"], "name"=>stripslashes($row["name"]),"username"=>stripslashes($row["username"]),"password"=>stripslashes($row["password"]),"email"=>stripslashes($row["email"]),'title'=>$row["title"],"status"=>$row["status"],"type"=>$row["type"],"phone"=>stripslashes($row["phone"]));
			adminstatus($row["status"]);
			$_SESSION["rec_user"]=$user;
			header("location:setting.php");
			exit;
		}
		else
		{
			$_SESSION["recstatus"]=array("bad","Invalid Username And Password","You need to login to access this page<br/><br/>Please Click <a href='index.php'>Here</a> To Login");
			unset($_SESSION["rec_user"]);
			header("location:status.php");
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
}
else if($task=="create")
{
	$user = $_SESSION["recuser"];
	$uname = trim($_REQUEST["uname"]);
	$upass = trim($_REQUEST["newpass"]);
	$name = trim(ucwords(strtolower($_REQUEST["realname"])));
	$email =trim(strtolower($_REQUEST["uemail"]));
	$status =$_REQUEST["ustatus"];
	$type = $_REQUEST["utype"];
	$cphone = trim($_REQUEST["cphone"]);
	$reportto = base64_decode($_REQUEST["reportto"]);
	$officeman = base64_decode($_REQUEST["officeman"]);
	$title = trim(ucwords(strtolower($_REQUEST["utitle"])));
	$query = "select * from task_users where username='".clean($uname)."'";
	if($result = mysql_query($query))
	{
		if(($num_rows = mysql_num_rows($result))>0)
		{
			$_SESSION["recresult"]="ERROR: Username already exist, please another username";
			header("location:create.php");
			exit;
		}
	}
	if($type=='6' || $type=='5')
	{
		if($officeman !='na' && !empty($officeman))
			$officemanq = "'".$officeman."' ";
		else
			$officemanq="NULL";
		if($type=='6')
			$reporttoq="NULL";
		else
		{
			if($reportto !='na' && !empty($reportto))
				$reporttoq = "'".$reportto."'";
			else
				$reporttoq="NULL";
		}
	}
	else
	{
		$officemanq="NULL";
		$reporttoq="NULL";
	}
	$query = "insert ignore into task_users(username,password,name,title,email,status,type,date,phone,office,report_to)values('".clean($uname)."','".md5(clean($upass))."','".clean($name)."','".clean($title)."','".clean($email)."','".$status."','".$type."',NOW(),'".clean($cphone)."',$officemanq,$reporttoq)";
	if($result = mysql_query($query))
	{
		$_SESSION["recresult"]="SUCCESS: User Created";
		$title = "Family Energy Master Recruiter System: $name, Your Account is Created!";
		$message = "Hello ".$name.",<br/><br/>";
		$message .="This is to let know that your account for the Family Energy Master Recruiter System has been created for you from and you can start using it.<br/><br/>";
		$message .="Your Login Information is as follow:<br/>Username: <b>".$uname."</b><br/>Password: <b>".$upass."</b><br/><br/>";
		$message .="To login to Family Energy TMaster Recruiter System just click the link below and the given username and password.<br/>";
		$message .="<a href='http://www.familyenergymap.com/rec/' target='_blank'>Login Here</a><br/><br/>You can always change this information by login in the website and change your settings.<br/><br/>Attn,<br/><br/>Family Energy Team<br/>";
		if($resultemail = sendEmail($email,$title,$message))
			$_SESSION["recresult"]="SUCCESS: User Created and Email Sent";
		/*if(!empty($cphone))
		{
			$mmessage="Family Energy: Your new account has been created!, Username: $uname and Password: $upass.";
			$result = sendSMS($cphone,$mmessage);
			if($result !="fail" && !empty($result))
			{
				if($resultemail)
					$_SESSION["recresult"]="SUCCESS: User Created and Email Sent and Text Message Sent";
				else
					$_SESSION["recresult"]="SUCCESS: User Created and Text Message Sent";
			}
		}*/
	}
	else
		$_SESSION["recresult"]="ERROR: Unable To Create User";
	header('location:viewusers.php');
	exit;
}
else if($task=="savem")
{
	$sendemail=false;
	$userid = base64_decode($_REQUEST["id"]);
	$email =trim(strtolower($_REQUEST["uemail"]));
	$uname = trim($_REQUEST["uname"]);
	$cphone = trim($_REQUEST["cphone"]);
	$officeman = base64_decode($_REQUEST["officeman"]);
	$reportto = base64_decode($_REQUEST["reportto"]);
	$query = "select * from task_users where id='".$userid."'";
	$changeusername=false;
	if($result = mysql_query($query))
	{
		if(($num_rows = mysql_num_rows($result))>0)
		{
			$checkusername = mysql_fetch_assoc($result);
			if($username != stripslashes($checkusername["username"]))
			{
				$query = "select * from task_users where username='".clean($uname)."' and id !='".$userid."'";
				if($result = mysql_query($query))
				{
					if(($num_rows = mysql_num_rows($result))>0)
					{
						$_SESSION["recresult"]="ERROR: Username already in use";
						header('location:settingm.php?id='.base64_encode($userid));
						exit;
					}
					else
						$changeusername= true;
				}
				else
					$changeusername= true;
			}
			else
				$changeusername= true;
		}
		else
		{
			$_SESSION["recresult"]="ERROR: invalid username";
			header("location:settingm.php?id=".base64_encode($userid));
			exit;
		}
	}
	else
	{
		$_SESSION["recresult"]="ERROR: invalid username";
		header("location:settingm.php?id=".base64_encode($userid));
		exit;
	}
	$upass = trim($_REQUEST["newpass"]);
	$changepass = $_REQUEST["changepass"];
	$newpass = trim($_REQUEST["newpass"]);
	$name = trim(ucwords(strtolower($_REQUEST["realname"])));
	$status =$_REQUEST["ustatus"];
	$type = $_REQUEST["utype"];
	$title = trim(ucwords(strtolower($_REQUEST["utitle"])));
	if($type=='6' || $type=='5')
	{
		if($officeman !='na' && !empty($officeman))
			$officemanq = ",office='".$officeman."' ";
		else
			$officemanq=",office=NULL ";
		if($type=='6')
			$reporttoq=",report_to=NULL ";
		else
		{
			if($reportto !='na' && !empty($reportto))
				$reporttoq = ",report_to='".$reportto."' ";
			else
				$reporttoq=",report_to=NULL ";
		}
	}
	else
	{
		$officemanq=",office=NULL ";
		$reporttoq=",report_to=NULL ";
	}
	if($changepass=="yes")
	{
		$query = "update task_users set username='".clean($uname)."',password='".md5(clean($newpass))."',name='".clean($name)."',title='".clean($title)."',email='".clean($email)."',status='".$status."',type='".$type."', phone='".$cphone."' $officemanq $reporttoq where id='".$userid."'";
		$sendemail=true;
	}
	else
		$query = "update task_users set username='".clean($uname)."',name='".clean($name)."',title='".clean($title)."',email='".clean($email)."',status='".$status."',type='".$type."' ,phone='".clean($cphone)."' $officemanq $reporttoq where id='".$userid."'";
	if(!$sendemail)
	{
		if($checkusername["type"] !=$type || stripslashes($checkusername["username"]) !=$uname)
			$sendemail=true;
	}
	if($result = mysql_query($query))
	{
		$_SESSION["recresult"]="SUCCESS: Changes For User $uname Saved";
		/*if($status =="2")
		{
			$title = "Family Energy Master Recruiter System: $name, Your Account is currently blocked!";
			$message = "Hello ".$name.",<br/><br/>";
			$message .="This is to let know that your account for the Family Energy Master Recruiter System has been recently updated and is currently blocked or cancelled<br/><br/>";
			$message .="Only Administrator or high staff personal can grant you access to the Family Energy Master Recruiter System.  You will be notified if your account becomes avaliable.<br/>";
			$message .="<br/><br/>Attn,<br/><br/>Family Energy Team<br/>";			
		}
		else
		{
			$title = "Family Energy Master Recruiter System: $name, Your Account is updated!";
			$message = "Hello ".$name.",<br/><br/>";
			$message .="This is to let know that your account for the Family Energy Master Recruiter System has been recently updated!<br/><br/>";
			if($changepass=="yes")
				$message .="Your New Login Information is as follow:<br/>Username: <b>$uname</b><br/>Password: <b>".$newpass."</b><br/><br/>";
			else if($changeusername== true)
				$message .="Your New Username is as follow:<br/>Username: <b>$uname</b><br/><br/>";
			$message .="To login to Family Energy Master Recruiter System just click the link below and the given username and password.<br/>";
			$message .="<a href='http://www.familyenergymap.com/rec/' target='_blank'>Login Here</a><br/><br/>Attn,<br/><br/>Family Energy Team<br/>";
		}*/
		if($sendemail)
		{
			if($resultemail = sendEmail($email,$title,$message))
				$_SESSION["recresult"]="SUCCESS: User Changes is Saved and Email Sent";
		}
	}
	else
		$_SESSION["recresult"]="ERROR: Unable To Save Changes For User $uname";
	header('location:settingm.php?id='.$_REQUEST["id"]);
	exit;
}
else if($task=="createrec")
{
	date_default_timezone_set('America/New_York');
	$qexp="";
	$qexpv="";
	$flink="import.php";
	$overwrite=$_REQUEST["overwrite"];
	$osession = $_REQUEST["osession"];
	if($osession)
		$osession="yes";
	else
		$osession="no";
	$catoverq="";
	$catoverv="";
	if(!adminlogin_exp())
	{
		$catoverq = "catid,";
		$catoverv = "'2',";
		$user=$_SESSION["brownuser"];
	}
	else
		$user=$_SESSION["rec_user"];
	if(isset($_SESSION["cfound"]) && $osession=="no")
	{
		$cfound =$_SESSION["cfound"];
		$cname = trim(ucwords(strtolower($cfound["cname"])));
		$cphone = trim($cfound["cphone"]);
		$ocall=$cfound["ocall"];
		$cphonex = trim($cfound["cphonex"]);
		$email = strtolower($cfound["email"]);
		$cdate= fixdate_comps("mildate",$cfound["cdate"]);
		$coffice = $cfound["coffice"];
		$csource = $cfound["csource"];
		$csource_title = trim(ucwords(strtolower($cfound["csource_title"])));
		$address = trim(ucwords(strtolower($cfound["address"])));
		$city = trim(ucwords(strtolower($cfound["city"])));
		$checkdatediv = trim($cfound["checkdatediv"]);
		$state = trim(ucwords(strtolower($cfound["state"])));
		$zip=trim(strtoupper($cfound["zip"]));
		$idate=NULL;
		$enotx = trim($cfound["enotx"]);
		$textnotx = trim($cfound["textnotx"]);
		date_default_timezone_set('America/New_York');
		$expw=$cfound["expw"];
		$expw_gos=$cfound["expw_gos"];	
	}
	else
	{
		$cname = trim(ucwords(strtolower($_REQUEST["cname"])));
		$cphone = trim($_REQUEST["cphone"]);
		$cphonex = trim($_REQUEST["cphonex"]);
		$email = strtolower($_REQUEST["email"]);
		$cdate= fixdate_comps("mildate",$_REQUEST["cdate"]);
		$coffice = base64_decode($_REQUEST["coffice"]);
		$csource = $_REQUEST["csource"];
		$ocall=$_REQUEST["ocall"];
		$csource_title = trim(ucwords(strtolower($_REQUEST["csource_cont"])));
		$address = trim(ucwords(strtolower($_REQUEST["address"])));
		$city = trim(ucwords(strtolower($_REQUEST["city"])));
		$checkdatediv = trim($_REQUEST["checkdatediv"]);
		$state = trim(ucwords(strtolower($_REQUEST["state"])));
		$zip=trim(strtoupper($_REQUEST["zip"]));
		$idate=NULL;
		$enotx = trim($_REQUEST["enotx"]);
		$textnotx = trim($_REQUEST["textnotx"]);
		date_default_timezone_set('America/New_York');
		$expw=$_REQUEST["expw"];
		$expw_gos=$_REQUEST["expw_gos"];
	}
	if($checkdatediv=="yes")
	{
		if(isset($_SESSION["cfound"]) && $osession=="no")
		{
			$cfound =$_SESSION["cfound"];
			$idate=fixdate_comps('mildate',$cfound["idate"]);
			$ctime= fixtomilhour($cfound["ctime"]);
		}
		else
		{
			$idate=fixdate_comps('mildate',$_REQUEST["idate"]);
			$ctime= fixtomilhour($_REQUEST["ctime"]);
		}
	}
	else
	{
		//walk-in was selected
		$idate = date("Y-m-d");
		$ctime = date("H:m:s");
		//express checkout options
		if(isset($_SESSION["cfound"]))
		{
			$checkexpress=$cfound["checkexpress"];
			$expw_showint = $cfound["expw_showint"];
			$expw_int=base64_decode($cfound["expw_int"]);
			$expw_hired=$cfound["expw_hired"];
			$expw_reason=base64_decode($cfound["expw_reason"]);
			$expw_ordate=$cfound["expw_ordate"];
			$expw_ooff=base64_decode($cfound["expw_ooff"]);
			$expw_ordatec=$cfound["expw_ordatec"];
		}
		else
		{
			$checkexpress=$_REQUEST["checkexpress"];
			$expw_showint = $_REQUEST["expw_showint"];
			$expw_int=base64_decode($_REQUEST["expw_int"]);
			$expw_hired=$_REQUEST["expw_hired"];
			$expw_reason=base64_decode($_REQUEST["expw_reason"]);
			$expw_ordate=$_REQUEST["expw_ordate"];
			$expw_ooff=base64_decode($_REQUEST["expw_ooff"]);
			$expw_ordatec=$_REQUEST["expw_ordatec"];
		}
		if($checkexpress=="yes")
		{
			//set interviwer
			$qexp=",interviewer"; //add variable for query
			$qexpv=",'".$expw_int."'";//add variable for query
			if($expw_showint=="yes") //showed for interview
			{
				$qexp .=",int_show,int_show_date";
				$qexpv .=",'yes','".$idate." ".$ctime."'";
				if($expw_hired=="yes")
				{
					$qexp .=",hired ";
					$qexpv .=",'yes' ";
					if($expw_ordate)
					{
						$qexp .=", orientation, orientation_office ";
						$qexpv .=", '".$idate." ".$ctime."','".$expw_ooff."'";
					}
					if($expw_ordatec)
					{
						$qexp .=",ori_show, ori_comp,orientation_comp,orientation_show,status";
						$qexpv .=",'yes', 'yes','".$idate."','".$idate." ".$ctime."','7'";
					}
					else
					{
						$qexp .=",status";
						$qexpv .=",'3'";
					}
				}
				else if($expw_hired=="no")
				{
					$expw_reason=getExpReason($expw_reason);
					$qexp .=",hired,status,interview_note";
					$qexpv .=",'no','2','".clean($expw_reason)."'";
				}
				else if($expw_hired=="notint")
				{
					$expw_reason=getExpReason($expw_reason);
					$qexp .=",hired,status,interview_note";
					$qexpv .=",'no','9','".clean($expw_reason)."'";
				}
			}
			else
			{
				if($expw_showint=="cancel")
				{
					$expw_reason=getExpReason($expw_reason);
					$qexp .=",int_show,int_show_info,hired,status";
					$qexpv .=",'no','".clean($expw_reason)."','no','20'";
				}
				else
				{
					$expw_reason=getExpReason($expw_reason);
					$qexp .=",int_show,int_show_info,hired,status";
					$qexpv .=",'no','".clean($expw_reason)."','no','8'";
				}
			}
		}
		//end of express checkout
	}
	//$idate= fixdate_slash($_REQUEST["idate"]);
	//find dupplicates
	if($overwrite !="yes")
	{
		if(!empty($email))
			$cfemail ="or email like '%".clean($email)."%'";
		if(!empty($cphone))
			$cfphone ="or cphone like '%".clean($cphone)."%'";
		$query = "select * from rec_entries where cname like '%".clean($cname)."%' $cfphone $cfemail";
		if($result = mysql_query($query))
		{
			if(($numrows = mysql_num_rows($result))>0)
			{
				if(!adminlogin_exp())
				{
					$_SESSION["recresult"]="ERROR: A Match Was Found. Entry Already Exists";
					header("location:import.php");
					exit;
				}
				$fuser = array("task"=>"createrec","cdate"=>$cdate,"ctime"=>$ctime,"checkdatediv"=>$checkdatediv,"cname"=>$cname,"email"=>$email,"cphone"=>$cphone,"cphonex"=>$cphonex,"idate"=>$idate,"itime"=>$itime,"address"=>$address,"city"=>$city,"state"=>$state,"zip"=>$zip,"coffice"=>$coffice,"csource"=>$csource,"csource_title"=>$csource_title,"email"=>$email,"createdby"=>$user["id"],"enotx"=>$enotx,"textnotx"=>$textnotx,"checkexpress"=>$checkexpress,"expw"=>$expw,"expw_gos"=>$expw_gos,"expw_showint"=>$expw_showint,"expw_int"=>$_REQUEST["expw_int"],"expw_hired"=>$expw_hired,"expw_reason"=>$_REQUEST["expw_reason"],"expw_ordate"=>$expw_ordate,"expw_ooff"=>$_REQUEST["expw_ooff"],"expw_ordatec"=>$expw_ordatec,'ocall'=>$ocall);
				$_SESSION["cfound"]=$fuser;
				header("location:viewcomp.php");
				exit;
			}
		}
	}
	unset($_SESSION["cfound"]);
	if(empty($cphonex) || $cphonex=="---")
		$cphonex="NULL";
	else
		$cphonex="'".clean($cphonex)."'";
	$ocallx="NULL";
	//if(empty($cphonex) || $cphonex=="---")
	//	$cphonex="'".clean($cphone)."'";
	if(isset($_SESSION["woffice"]))
	{
		$wofficex=$_SESSION["woffice"];
		if(!empty($wofficex["id"]))
		{
			$qexp .=",coffice ";
			$qexpv .=",'".$wofficex["id"]."'";
		}
	}
	if(!empty($ocall) && $ocall !='na')
		$ocallx="'".$ocall."'";
	$query = "insert ignore into rec_entries(".$catoverq."cname,cphone,cphonex,cdate,office,csource,csource_title,address,city,state,zip,email,itime,createdby,date,cr_time,idate,enotx,textnotx,ocall $qexp)values(".$catoverv."'".clean($cname)."','".clean($cphone)."',".$cphonex.",'".$cdate."','".clean($coffice)."','".$csource."','".clean($csource_title)."','".clean($address)."','".clean($city)."','".clean($state)."','".clean($zip)."','".clean($email)."','".$ctime."','".$user["id"]."',NOW(),NOW(),'".$idate."','".$enotx."','".$textnotx."',$ocallx $qexpv)";
	//echo $query;
	if($result=mysql_query($query))
	{
		$id=mysql_insert_id();
		setTimeLine($id,'1',$idate." ".$ctime);
		//begin timeline if express is choosen
		if($checkexpress=="yes")
		{
			//set interviwer
			if($expw_showint=="yes") //showed for interview
			{
				setTimeLine($id,'10',$idate." ".$ctime);
				if($expw_hired=="yes")
				{
					if($expw_ordate)
						setTimeLine($id,'3',$idate." ".$ctime);
					if($expw_ordatec)
					{
						setTimeLine($id,'11',$idate." ".$ctime);	
						setTimeLine($id,'7',$idate." ".$ctime,$idate." ".$ctime);
					}
					else
						setTimeLine($id,'3','');
				}
				else if($expw_hired=="no")
					setTimeLine($id,'2','');
				else if($expw_hired=="notint")
					setTimeLine($id,'9','');
			}
			else
			{
				if($expw_showint=="cancel")
					setTimeLine($id,'20','');
				else
					setTimeLine($id,'8','');
			}
		}
		//end of timeline
		if($expw=="noy")
			$flink="setrec.php?id=".base64_encode($id);
		if($expw_gos)
			$flink="setrec.php?id=".base64_encode($id);
		$_SESSION["recresult"]="SUCCESS: New Entry  Saved";
		if(!empty($id))
		{
			$queryy = "select * from rec_office where id='".$coffice."'";
			if($resulty= mysql_query($queryy))
			{
				$host = getHost();
				if(($num_rowsy = mysql_num_rows($resulty))>0)
				{
					$officeinfo =mysql_fetch_assoc($resulty);
					$title="Hello, $cname, Family Energy Interview Reminder Email";
					$message = "Hello $cname!,<br/><br/>";
					$message .="Thank you for your interest in Family Energy! This is to confirm your scheduled interview with one of our managers. Please see appointment details below.";
					$message .="<br/><br/><b>Appointment Details:</b><br/><br/>";
					$message .="Contact Name: ".stripslashes($officeinfo["contact"])."<br/>";
					$message .="Contact Email: ".stripslashes($officeinfo["email"])."<br/>";
					$message .="Contact Phone: ".stripslashes($officeinfo["phone"])."<br/>";
					$message .="Appointment Time: ".fixnormhour($ctime)."<br/>";
					$message .="Appointment Date: ".fixdate_comps("d",$idate)."<br/>";
					$message .="Appointment Office: ".stripslashes($officeinfo["name"])."<br/>";
					$message .="Appointment Office Address: ".stripslashes($officeinfo["address"]).", ".stripslashes($officeinfo["city"]).", ".stripslashes($officeinfo["state"])." ".stripslashes($officeinfo["country"])." ".stripslashes($officeinfo["zip"])."<br/><br/>";
					$message .="<u>If you are traveling by Car:</u><br/>";
					$message .=nl2br(stripslashes($officeinfo["idrive"]))."<br/><br/>";
					$message .="<u>If you are traveling by train:</u><br/>";
					$message .=nl2br(stripslashes($officeinfo["iwalk"]))."<br/><br/><br/>";
					$message .="Remember to bring a <b>photo ID and your Social Security card</b>. Please contact us if you have any questions or concerns regarding the interview. We are looking forward to meeting with you!";
					$message .="<br/><br/>Regards,<br/><br/>Family Energy Recruiting";
					//sms
					$mmessage = "Family Energy Interview Confirmation Date:".fixdate_comps("d",$idate)." at: ".fixnormhour($ctime)." in the: ".stripslashes($officeinfo["name"]);
					if($user["type"]=='9')
					{
						//message for brown morgan
						$titlex="Hello, Brown Morgan, Family Energy Interview Reminder Email";
						$messagex = "Hello Brown Morgan!,<br/><br/>";
						$messagex .="This is to confirm for scheduled interview. Please see appointment details below.";
						$messagex .="<br/><br/><b>Appointment Details:</b><br/><br/>";
						$messagex .="Name: ".$cname."<br/>";
						$messagex .="Contact Name: ".stripslashes($officeinfo["contact"])."<br/>";
						$messagex .="Contact Email: ".stripslashes($officeinfo["email"])."<br/>";
						$messagex .="Contact Phone: ".stripslashes($officeinfo["phone"])."<br/>";
						$messagex .="Appointment Time: ".fixnormhour($ctime)."<br/>";
						$messagex .="Appointment Date: ".fixdate_comps("d",$idate)."<br/>";
						$messagex .="Appointment Office: ".stripslashes($officeinfo["name"])."<br/>";
						$messagex .="Appointment Office Address: ".stripslashes($officeinfo["address"]).", ".stripslashes($officeinfo["city"]).", ".stripslashes($officeinfo["state"])." ".stripslashes($officeinfo["country"])." ".stripslashes($officeinfo["zip"])."<br/><br/>";
						$messagex.="<br/><br/>Regards,<br/><br/>Family Energy Recruiting System";
						//sms
						$mmessagex = "Family Energy Interview Confirmation Date:".fixdate_comps("d",$idate)." at: ".fixnormhour($ctime)." with ".$cname;
						$emailx="adam@brownmorganinc.com,charlesr@brownmorganinc.com";
						$cphonex='1917-881-7193,1212-920-7320';
						$resultx=sendEmail($emailx,$titlex,$messagex);
						$resultx=sendSMSm($cphonex,$mmessagex);
					}
				}
			}
			$checkemail_c = false;
			if($enotx=="yes" && !empty($email))
			{
				if($result = sendEmail($email,$title,$message))
				{
					$_SESSION["recresult"]="SUCCESS: Information Saved And Email Sent";
					$checkemail_c=true;
				}
			}
			if($textnotx=="yes" && !empty($cphone))
			{
				$result = sendSMS($cphone,$mmessage);
				if($result !="fail" && !empty($result))
				{
					if($checkemail_c)
						$_SESSION["recresult"]="SUCCESS: Information Saved And Email Sent and Text Message Sent";
					else
						$_SESSION["recresult"]="SUCCESS: Information Saved and Text Message Sent";
				}
			}
		}
	}
	else
		$_SESSION["recresult"]="ERROR: Unable To Create Entry";
	header('location:'.$flink);
	exit;
}
else if($task=="saverec")
{
	$flink ="setting_rec.php?id=".$_REQUEST["id"];
	$idate=NULL;
	date_default_timezone_set('America/New_York');
	$catoverq="";
	if(!adminlogin_exp())
	{
		$catoverq = "catid='2',";
		$user=$_SESSION["brownuser"];
	}
	else
		$user=$_SESSION["rec_user"];
	if(isset($_SESSION["cfound"]))
	{
		$cfound=$_SESSION["cfound"];
		$flink = "import.php";
	}
	$id = base64_decode($_REQUEST["id"]);
	if(empty($id))
	{
		$_SESSION["recresult"]="ERROR: Invalid Entry";
		header("location:".$flink);
		exit;
	}
	else
	{
		$queryr = "select * from rec_entries where id='".$id."'";
		if($resultr = mysql_query($queryr))
		{
			if(($num_rowsr = mysql_num_rows($resultr))>0)
				$cinfo = mysql_fetch_assoc($resultr);
			else
			{
				$_SESSION["recresult"]="ERROR: Invalid Entry";
				header("location:".$flink);
				exit;
			}
		}
		else
		{
			$_SESSION["recresult"]="ERROR: Invalid Entry";
			header("location:".$flink);
			exit;
		}
	}
	if(isset($_SESSION["cfound"]))
	{
		$cname = trim(ucwords(strtolower($cfound["cname"])));
		$cphone = trim($cfound["cphone"]);
		$cphonex = trim($cfound["cphonex"]);
		$cdate= fixdate_comps("mildate",$cfound["cdate"]);
		$cdatequery = "cdate='".$cdate."',";
		$coffice = $cfound["coffice"];
		$csource = $cfound["csource"];
		$ocall=$cfound["ocall"];
		$csource_title = trim(ucwords(strtolower($cfound["csource_title"])));
		$email = strtolower($cfound["email"]);
		$address = trim(ucwords(strtolower($cfound["address"])));
		$city = trim(ucwords(strtolower($cfound["city"])));
		$state = trim(ucwords(strtolower($cfound["state"])));
		$zip=trim(strtoupper($cfound["zip"]));
		$enotx = trim($cfound["enotx"]);
		$textnotx = trim($cfound["textnotx"]);
		$idate= fixdate_comps("mildate",$cfound["idate"]);
		$ctime= fixtomilhour($cfound["ctime"]);
		$itime= fixtomilhour($cfound["ctime"]);
		$expw_gos = $cfound["expw_gos"];
		$expw=$cfound["expw"];
		if($expw=="noy")
			$flink="setrec.php?id=".base64_encode($id);
		if($expw_gos)
			$flink="setrec.php?id=".base64_encode($id);
		$expw_int=base64_decode($cfound["expw_int"]);
		$checkexpress=$cfound["checkexpress"];
		$expw_showint = $cfound["expw_showint"];
		$expw_hired=$cfound["expw_hired"];
		$expw_reason=base64_decode($cfound["expw_reason"]);
		$expw_ordate=$cfound["expw_ordate"];
		$expw_ooff=base64_decode($cfound["expw_ooff"]);
		$expw_ordatec=$cfound["expw_ordatec"];
		$expw_reason=getExpReason($expw_reason);
		if($checkexpress=="yes")
		{
			$qexp =",interviewer='".$expw_int."'"; //add variable for query
			if($expw_showint=="yes") //showed for interview
			{
				$qexp .=",int_show='yes',int_show_date='".$idate." ".$itime."'";
				if($cinfo["status"]=='8' || $cinfo["status"]=='20')
				{
					setFTimeLine($id,'15','');
					//echo "Timeline Hard: Status Modified [15]<br/>";
				}
				else
				{
					setTimeLine($id,'10',$idate." ".$itime);
					//echo "Timeline Soft: Interview Attended [10]<br/>";
				}
				if($expw_hired=="yes")
				{
					if($cinfo["hired"]=='no')
					{
						setFTimeLine($id,'15','');
						//echo "Timeline Hard: Status Modified [15]<br/>";
					}
					if(checkSame("odatetime",$id,$idate." ".$itime))
					{
						setFTimeLine($id,'14','');
						//echo "Timeline Hard: Orientation ReScheduled [14]<br/>";
					}
					$qexp .=",hired='yes',orientation_office='".$expw_ooff."'";
					if($expw_ordate)
					{
						$qexp .=",orientation='".$idate." ".$itime."'";
						if($cinfo["hired"] !='yes')
						{
							setFTimeLine($id,'3',$idate." ".$itime);
							//echo "Timeline Hard: Orientation Set [3]<br/>";
						}
						else
						{
							setTimeLine($id,'3',$idate." ".$itime);
							//echo "Timeline Soft: Orientation Set [3]<br/>";
						}
					}
					if($expw_ordatec)
					{
						$qexp .=",ori_show='yes',ori_show_info=NULL,ori_comp='yes', ori_comp_info=NULL, orientation_comp='".$idate."',orientation_show='".$idate." ".$itime."',status='7'";
						if($cinfo["hired"] !='yes' || empty($cinfo["orientation_completed"]))
						{
							setFTimeLine($id,'11',$idate." ".$itime);
							setFTimeLine($id,'7',$idate." ".$itime);
							//echo "Timeline Hard: Orientation Attended [11]<br/>";
							//echo "Timeline Hard: Orientation Completed [7]<br/>";
						}
						else
						{
							setTimeLine($id,'11',$idate." ".$itime);
							setTimeLine($id,'7',$idate." ".$itime);
							//echo "Timeline Soft: Orientation Attended [11]<br/>";
							//echo "Timeline Soft: Orientation Completed [7]<br/>";
						}
					}
					else
					{
						$qexp .=",status='3', ori_show=NULL, ori_show_info=NULL, ori_comp=NULL, ori_comp_info=NULL, ccode=NULL, ccode_aval=NULL";
					}
				}
				else if($expw_hired=="no")
				{
					if($cinfo["hired"]=='yes')
					{
						setFTimeLine($id,'15','');
						setFTimeLine($id,'2','');
						//echo "Timeline Hard: Status Modified [15]<br/>";
						//echo "Timeline Hard: Not Hired [2]<br/>";
					}
					else
					{
						setTimeLine($id,'15','');
						setTimeLine($id,'2','');
						//echo "Timeline Soft: Status Modified [15]<br/>";
						//echo "Timeline Soft: Not Hired [2]<br/>";
					}
					$qexp_x="";
					$imgcr=$cinfo["img"];
					if(!empty($imgcr))
					{
						if(file_exists("images/aimg/" . $imgcr))
							unlink("images/aimg/" . $imgcr);
					}
					$qexp_x=",".getDelOr();
					$qexp .=",hired='no',status='2',interview_note='".clean($expw_reason)."' $qexp_x";
				}
				else if($expw_hired=="notint")
				{
					if($cinfo["status"]=='3' || $cinfo["status"]=='7' || $cinfo["status"]=='5')
					{
						setFTimeLine($id,'15','');
						setFTimeLine($id,'9','');
						//echo "Timeline Hard: Status Modified [15]<br/>";
						//echo "Timeline Hard: Not Interested [9]<br/>";
					}
					else
					{
						setTimeLine($id,'9','');
						//echo "Timeline Soft: Not Interested [9]<br/>";
					}
					$qexp_x="";
					$imgcr=$cinfo["img"];
					if(!empty($imgcr))
					{
						if(file_exists("images/aimg/" . $imgcr))
							unlink("images/aimg/" . $imgcr);
					}
					$qexp_x=",".getDelOr();
					$qexp .=",hired='no',status='9',interview_note='".clean($expw_reason)."' $qexp_x";
				}
			}
			else
			{
				if($cinfo["status"]=='3' || $cinfo["status"]=='7' || $cinfo["status"]=='5')
				{
						setFTimeLine($id,'15','');
						setFTimeLine($id,'8','');
						//echo "Timeline Hard: Status Modified [15]<br/>";
						//echo "Timeline Hard: No Show [8]<br/>";
				}
				else
				{
					setTimeLine($id,'8','');
					//echo "Timeline Soft: No Show [8]<br/>";
				}
				$qexp_x="";
				$imgcr=$cinfo["img"];
				if(!empty($imgcr))
				{
					if(file_exists("images/aimg/" . $imgcr))
						unlink("images/aimg/" . $imgcr);
				}
				$qexp_x=",".getDelOr();
				$qexp .=",int_show='no',int_show_info='".clean($expw_reason)."',hired=NULL, interviewer=NULL, status='8' $qexp_x";
			}
		}
	}
	else
	{
		$comentry = base64_decode($_REQUEST["comentry"]);
		$cname = trim(ucwords(strtolower($_REQUEST["cname"])));
		$cphone = trim($_REQUEST["cphone"]);
		$cphonex = trim($_REQUEST["cphonex"]);
		$ctime= fixtomilhour($_REQUEST["ctime"]);
		$changecdate = $_REQUEST["changecdate"];
		$ocall=$_REQUEST["ocall"];
		$checkdatediv = trim($_REQUEST["checkdatediv"]);
		if($changecdate=="yes")
		{
			$cdate= fixdate_comps("mildate",$_REQUEST["cdate"]);
			$cdatequery = "cdate='".$cdate."',";
		}
		$coffice = base64_decode($_REQUEST["coffice"]);
		$csource = $_REQUEST["csource"];
		$csource_title = trim(ucwords(strtolower($_REQUEST["csource_cont"])));
		$email = strtolower($_REQUEST["email"]);
		$address = trim(ucwords(strtolower($_REQUEST["address"])));
		$city = trim(ucwords(strtolower($_REQUEST["city"])));
		$state = trim(ucwords(strtolower($_REQUEST["state"])));
		$zip=trim(strtoupper($_REQUEST["zip"]));
		$enotx = trim($_REQUEST["enotx"]);
		$textnotx = trim($_REQUEST["textnotx"]);
		$changeidate = $_REQUEST["changeidate"];
		if(!empty($_REQUEST["idate"]))
			$idate= fixdate_comps("mildate",$_REQUEST["idate"]);
	}
	$resende = false;
	//echo $idate." ".$ctime;
	$idate_check=false;
	if(checkSame("idate",$id,$idate))
		$idate_check=true;
	else if(checkSame("itime",$id,$ctime))
		$idate_check=true;
	if($cinfo["status"] =="8")
	{
		if($idate_check)
		{
			//if the entry is no show, restart the setting to normal.
			$xq=",".getDelOr();
			$query = "update rec_entries set status='1',int_show=NULL, int_show_info=NULL,int_show_date=NULL $xq where id='".$id."'";
			@mysql_query($query);
			setFTimeLine($id,'15','');
			setFTimeLine($id,'13',$idate." ".$ctime);
			//echo "Timeline: Interview Re-Scheduled[13]: ".$idate." ".$ctime."<br/>";
		}
	}
	else
	{
		if($idate_check)
		{
			$resende=true;
			$cu_idate=$cinfo["idate"]." ".$cinfo["itime"];
			$n_idate=$idate." ".$ctime;
			if($n_idate>$cu_idate)
			{
				if(!empty($cinfo["img"]))
				{
					if(file_exists("images/aimg/" . $cinfo["img"]))
						unlink("images/aimg/" . $cinfo["img"]);
				}
				if($cinfo["status"] !='1')
					setFTimeLine($id,'15','');
				$qexp=" ,".getDelOr().", status='1' ";
			}
			setFTimeLine($id,'13',$idate." ".$ctime);
			//echo "Timeline: Interview Re-Scheduled[13]: ".$idate." ".$ctime."<br/>";
		}
	}
	//decide whether or not to send email or text message
	if(checkSame("office",$id,$coffice))
		$resende=true;
	else if(checkSame("email",$id,$email))
		$resende=true;
	else if(checkSame("phone",$id,$cphone))
		$resende=true;
	$idateq="";
	$newdatetime = $idate." ".$itime;
	$comentry="";
	if(!empty($comentry))
		$comentryq="catid='".$comentry."', ";
	//$checkpassdate = checkPassDate($id,$newdatetime); // check if date has passed to send email and message
	if(!empty($idate))
		$idateq = ",idate='".$idate."' ";
	if(empty($cphonex) || $cphonex=="---")
			$cphonex="NULL";
	else
		$cphonex="'".clean($cphonex)."'";
	$ocallx="NULL";
	if(!empty($ocall) && $ocall !='na')
		$ocallx="'".$ocall."'";
	$query = "update rec_entries set ".$comentryq." cname='".clean($cname)."',$cdatequery cphone='".clean($cphone)."', cphonex=".$cphonex.", office='".clean($coffice)."',csource='".$csource."',csource_title='".clean($csource_title)."',address='".clean($address)."',city='".clean($city)."',state='".clean($state)."',zip='".clean($zip)."',email='".clean($email)."', itime='".$ctime."', updatedby='".$user["id"]."',enotx='".$enotx."', textnotx='".$textnotx."', updatedby_date=NOW(),ocall=".$ocallx." $idateq $qexp where id='".$id."'";
	//echo $query;
	if($result = mysql_query($query))
	{
		unset($_SESSION["cfound"]);
		$_SESSION["recresult"]="SUCCESS: Changes Saved";
		$queryr = "select * from rec_entries where id='".$id."'";
		if($resultr = mysql_query($queryr))
		{
			if(($num_rowsr = mysql_num_rows($resultr))>0)
				$recinfo = mysql_fetch_assoc($resultr);
		}
		if($resende) //resend email if interview date and time are different
		{
		$queryy = "select * from rec_office where id='".$coffice."'";
		if($resulty= mysql_query($queryy))
		{
			if(($num_rowsy = mysql_num_rows($resulty))>0)
			{
				$host= getHost();
				$officeinfo =mysql_fetch_assoc($resulty);
				$title="Hello, $cname, Family Energy Interview Reminder Email";
				$message = "Hello $cname!,<br/><br/>";
				$message .="Thank you for your interest in Family Energy! This is to confirm your scheduled interview with one of our managers. Please see appointment details below.";
				$message .="<br/><br/><b>Appointment Details:</b><br/><br/>";
				$message .="Contact Name: ".stripslashes($officeinfo["contact"])."<br/>";
				$message .="Contact Email: ".stripslashes($officeinfo["email"])."<br/>";
				$message .="Contact Phone: ".stripslashes($officeinfo["phone"])."<br/>";
				$message .="Appointment Time: ".fixnormhour($ctime)."<br/>";
				$message .="Appointment Date: ".fixdate_comps("d",$recinfo["idate"])."<br/>";
				$message .="Appointment Office: ".stripslashes($officeinfo["name"])."<br/>";
				$message .="Appointment Office Address: ".stripslashes($officeinfo["address"]).", ".stripslashes($officeinfo["city"]).", ".stripslashes($officeinfo["state"])." ".stripslashes($officeinfo["country"])." ".stripslashes($officeinfo["zip"])."<br/><br/>";
				$message .="<u>If you are traveling by Car:</u><br/>";
				$message .=nl2br(stripslashes($officeinfo["idrive"]))."<br/><br/>";
				$message .="<u>If you are traveling by train:</u><br/>";
				$message .=nl2br(stripslashes($officeinfo["iwalk"]))."<br/><br/><br/>";
				$message .="Remember to bring a <b>photo ID and your Social Security card</b>. Please contact us if you have any questions or concerns regarding the interview. We are looking forward to meeting with you!";
				$message .="<br/><br/>Regards,<br/><br/>Family Energy Recruiting";
				//sms
				$mmessage = "Family Energy Interview Confirmation Date:".fixdate_comps("d",$recinfo["idate"])." at: ".fixnormhour($ctime)." in the: ".stripslashes($officeinfo["name"]);
			}
		}
		$checkemail_c = false;
		if(!empty($email) && $enotx=="yes")
		{
			if($recinfo["int_show"] !="no")
			{
				if(sendEmail($email,$title,$message))
				{
					$_SESSION["recresult"]="SUCCESS: Information Saved And Email Sent";
					$checkemail_c = true;
				}
			}
		}
		if(!empty($cphone) && $textnotx=="yes")
		{
			$result = sendSMS($cphone,$mmessage);
			if($result !="fail" && !empty($result))
			{
				if($checkemail_c)
					$_SESSION["recresult"]="SUCCESS: Information Saved And Email Sent and Text Message Sent";
				else
					$_SESSION["recresult"]="SUCCESS: Information Saved and Text Message Sent";
			}
		}
		}
	}
	else
		$_SESSION["recresult"]="ERROR: Unable To Save Changes";
	header('location:'.$flink);
	exit;
}
else if($task=="saverec_m")
{
	$id = base64_decode($_REQUEST["id"]);
	$eornotx = trim($_REQUEST["eornotx"]);
	$textornotx = trim($_REQUEST["textornotx"]);
	$intshow = $_REQUEST["intshow"];
	$intshownote = trim($_REQUEST["cintnote"]);
	//if no show is chosen..delete all data.
	//grab image if there is any
	$querycr = "select * from rec_entries where id='".$id."'";
	if($resultcr = mysql_query($querycr))
	{
		if(($numrcr = mysql_num_rows($resultcr))>0)
		{
			$infocr = mysql_fetch_assoc($resultcr);
			$imgcr = $infocr["img"];
		}
		else
		{
			$_SESSION["recresult"]="ERROR: Invalid Entry";	
			header('location:setrec.php?id='.base64_encode($id));
			exit;
		}
	}
	else
	{
		$_SESSION["recresult"]="ERROR: Invalid Entry";	
		header('location:setrec.php?id='.base64_encode($id));
		exit;
	}
	if($intshow=="no")
	{
		//if entry did not attend to the interview, all other information will be deleted
		if(!empty($imgcr))
		{
			if(file_exists("images/aimg/" . $imgcr))
				unlink("images/aimg/" . $imgcr);
		}
		$query = "update rec_entries set ";
		$query .= " int_show='no', status='8', int_show_info='".clean($intshownote)."' ";
		$delquer =getDelQuery();
		if(!empty($delquer))
			$query .=",$delquer";
		$query .=" where id='".$id."'";
		if($infocr["status"] !='8')
		{
			setFTimeLine($id,'15','');
			setFTimeLine($id,'8','');
			//echo "TimeLine: Status Modified[15]<br/>";
			//echo "TimeLine: No Show [8]<br/>";
		}
		if($result = mysql_query($query))
		{
			$_SESSION["recresult"]="SUCCESS: Information Saved";
		}
		else
			$_SESSION["recresult"]="ERROR: Information can't be Saved";
		header('location:setrec.php?id='.base64_encode($id));
		exit;
	}
	else if($intshow=="cancel")
	{
		//if entry did not attend to the interview, all other information will be deleted
		if(!empty($imgcr))
		{
			if(file_exists("images/aimg/" . $imgcr))
				unlink("images/aimg/" . $imgcr);
		}
		$query = "update rec_entries set ";
		$query .= " int_show='no', status='20', int_show_info='".clean($intshownote)."' ";
		$delquer =getDelQuery();
		if(!empty($delquer))
			$query .=",$delquer";
		$query .=" where id='".$id."'";
		if($infocr["status"] !='20')
		{
			setFTimeLine($id,'15','');
			setFTimeLine($id,'20','');
			//echo "TimeLine: Status Modified[15]<br/>";
			//echo "TimeLine: Interview Cancelled [20]<br/>";
		}
		if($result = mysql_query($query))
			$_SESSION["recresult"]="SUCCESS: Information Saved";
		else
			$_SESSION["recresult"]="ERROR: Information can't be Saved";
		header('location:setrec.php?id='.base64_encode($id));
		exit;
	}
	else
	{
	$statusm = $_REQUEST["statusm"];
	$checkm=true;
	$checkoprocess = $_REQUEST["checkoprocess"];
	$intagent = base64_decode($_REQUEST["intagent"]);
	$query = "update rec_entries set ";
	if($intagent !="na")
		$query .= "interviewer ='".$intagent."'";
	$hired = $_REQUEST["hired"];
	if(!empty($hired) && $hired !='na')
	{
		if($hired=="yes")
		{
			if($infocr["status"] !='7' && $infocr["status"] !='5' && $infocr["status"] !='3')
			{
				//$qx="update rec_entries set status='3', interview_note=NULL where id='".$id."'";
				//@mysql_query($qx);
				$query .= ",hired='yes'";
				//echo "TimeLine: Status Modified [15]<br/>";
				//setFTimeLine($id,'15','');
			}
		}
		else if($hired=="no" || $hired=='notint')
		{
			//if it's not hired...all orientation information will be deleted
			if(!empty($imgcr))
			{
				if(file_exists("images/aimg/" . $imgcr))
					unlink("images/aimg/" . $imgcr);
			}
			$cnote = trim($_REQUEST["cnote"]);
			if($hired=="notint")
			{
				$query .= ",hired='no', status='9',ori_show=NULL, interview_note='".clean($cnote)."'";
				if($infocr["status"] !='9')
				{
					setFTimeLine($id,'15','');
					setFTimeLine($id,'9','');
					//echo "TimeLine: Status Modified [15]<br/>";
					//echo "TimeLine: Not Interested [9]<br/>";
				}
			}
			else
			{
				$query .= ",hired='no', status='2',ori_show=NULL,interview_note='".clean($cnote)."'";
				if($infocr["status"] !='2')
				{
					setFTimeLine($id,'15','');
					setFTimeLine($id,'2','');
					//echo "TimeLine: Status Modified [15]<br/>";
					//echo "TimeLine: Not Hired [2]<br/>";
				}
			}
			$delquero =getDelOr();
			$query .= ",$delquero";
			$query .=" where id='".$id."'";
			if($result = mysql_query($query))
				$_SESSION["recresult"]="SUCCESS: Information Saved";
			else
				$_SESSION["recresult"]="ERROR: Information can't be Saved";
			header('location:setrec.php?id='.base64_encode($id));
			exit;
		}
	}
	$query .=" ,eornotx ='".$eornotx ."',textornotx='".$textornotx."' ";  
	if($infocr["status"]=='8' || $infocr["status"]=='20' )
	{
		$qx="update rec_entries set status='1',int_show='yes', int_show_info=NULL where id='".$id."'";
		@mysql_query($qx);
		setFTimeLine($id,'15','');
		setFTimeLine($id,'10','');
		//echo "TimeLine: Status Modified [15]<br/>";
		//echo "TimeLine: Interview Attended [10]<br/>";
	}
	else
	{
		$query .= ",int_show='yes', int_show_info=NULL ";
		setTimeLine($id,'10','');
		//echo "TimeLine: Interview Attended [10]<br/>";
	}
	if(!empty($hiredp) && (!empty($statusm) || !empty($checkoprocess)))
	{
		if($hiredp != $statusm && $checkoprocess !="yes")
			$checkm=false;
	}
	if($checkm)
	{
	if($checkoprocess=="yes")
	{
		$ooffice = base64_decode($_REQUEST["ooffice"]);
		if(!empty($ooffice))
			$query .=", orientation_office='".$ooffice."' ";
		//orientation date;
		$checkodate = $_REQUEST["checkodate"];
		$checkodate_check=$_REQUEST["checkodate_check"];
		if($checkodate=="yes" || $checkodate_check)
		{
			$odate = fixdate_comps("mildate",$_REQUEST["odate"]);
			$ohour = $_REQUEST["ohour"];
			$ominute = $_REQUEST["ominute"];
			$oampm = strtolower($_REQUEST["oampm"]);
			$newdate = $odate;
			/*if($oampm=="pm")
			{
				if($ohour=='12')
					$newhour = $ohour;
				else
					$newhour = $ohour +12;
			}
			else
			{
				if($ohour==12)
					$newhour = $ohour - 12;
				else
					$newhour = $ohour;
			}
			if($newhour <10)
				$newhour ="0".$newhour;
			$newdate .=" ".$newhour.":".$ominute.":00";*/
			$newhour=fixdate_comps('Hx',$ohour.":".$ominute." ".$oampm);
			$newdate .=" ".$newhour;
			if(!empty($newdate))
			{
				if($infocr["status"]=='1')
					$query .=", status='3' ";
				$query .=", orientation='".$newdate."' ";
				if($checkodate_check)
				{
					setFTimeLine($id,'14',$newdate);
					//echo "TimeLine: Orientation Reschedule [14]<br/>";
				}
				else
				{
					if($infocr["status"]=='2')
						setFTimeLine($id,'15','');
					setFTimeLine($id,'3',$newdate);
					//echo "TimeLine: Orientation Set [3]<br/>";
				}
			}
		}
		//reset orientation is new date is before the current date
		$oproceed=true;
		if(!empty($newdate) && ($checkodate=="yes" || $checkodate_check) )
		{
			if(!empty($infocr["orientation"]))
			{
				$check_codate=$infocr["orientation"];
				if($newdate > $check_codate)
				{
					if(!empty($imgcr))
					{
						if(file_exists("images/aimg/" . $imgcr))
							unlink("images/aimg/" . $imgcr);
					}
					$query .=", ".getDelOr_show().",status='3' ";
					$oproceed=false;
				}
			}
		}
		//end of reset
		if($oproceed)
		{
		//check if email is required
		$ockemail = true;
		$queryock = "select * from rec_entries where id='".$id."'";
		if($resultock = mysql_query($queryock))
		{
			if(($numrock = mysql_num_rows($resultock))>0)
			{
				$infock = mysql_fetch_assoc($resultock);
				if(($infock["orientation"]==$newdate || empty($newdate)) && $infock["orientation_office"]==$ooffice)
					$ockemail = false;
			}
		}
		//orientation attendance;
		//$oshowdate = fixdate_comps("mildate",$_REQUEST["oshowdate"]);
		//$oshowhour = $_REQUEST["oshowhour"];
		//$oshowminute = $_REQUEST["oshowminute"];
		//$oshowampm = strtolower($_REQUEST["oshowampm"]);
		$checkoshowdate = $_REQUEST["checkoshowdate"];
		$orishownote=trim($_REQUEST["orishownote"]);
		$ori_show=strtolower(trim($_REQUEST["ori_show"]));
		if($checkoshowdate=="yes" && !empty($oshowdate) && $oshowhour !="na" && $oshowminute !="na" && $oshowampm !="na")
		{
			$newoshowdate = $oshowdate;
			if($oshowampm=="pm")
			{
				if($oshowhour=='12')
					$newshowhour=$oshowhour;
				else
					$newshowhour=$oshowhour +12;
			}
			else
			{
				if($oshowhour==12)
					$newshowhour = $oshowhour - 12;
				else
					$newshowhour = $oshowhour;
			}
			if($newshowhour <10)
				$newshowhour ="0".$newshowhour;
			$newoshowdate .=" ".$newshowhour.":".$oshowminute.":00";
			if(!empty($newoshowdate) && $ori_show=="yes")
			{
				$query .=",ori_show='yes', orientation_show='".$newoshowdate."' ";
				if($infock["status"]=='18')
				{
					setFTimeLine($id,'15',$newoshowdate);
					setFTimeLine($id,'11',$newoshowdate);
					//echo "TimeLine: Status Modified [15]<br/>";
					//echo "TimeLine: Orientation Attended [11]<br/>";
				}
				else if($infock["status"]=='3')
				{
					setFTimeLine($id,'11',$newoshowdate);
					//echo "TimeLine: Status Modified [15]<br/>";
					//echo "TimeLine: Orientation Attended [11]<br/>";
				}
				else
				{
					setTimeLine($id,'11',$newoshowdate);
					//echo "TimeLine: Orientation Attended [11]<br/>";
				}
			}
		}
		if($ori_show=="no")
		{
			if(!empty($imgcr))
			{
				if(file_exists("images/aimg/" . $imgcr))
					unlink("images/aimg/" . $imgcr);
			}
			$query .=",status='18', img=NULL, ori_show='no',ccode=NULL, ccode_aval=NULL,ccode_info=NULL, orientation_show=NULL, ori_comp=NULL, orientation_comp=NULL, ori_comp_info=NULL,  ori_show_info='".clean($orishownote)."' ";
			if($infock["status"] =='11' || $infock["status"] =='7' || $infock["status"] =='5')
			{
				setFTimeLine($id,'15','');
				setTimeLine($id,'18','');
				//echo "TimeLine: Status Modified[15]<br/>";
				//echo "TimeLine: Orientation No Show[18]<br/>";
			}
			else
			{
				setTimeLine($id,'18','');
				//echo "TimeLine: Orientation No Show[18]<br/>";
			}
		}
		else
		{
			//set orientation show
			if($ori_show=="yes")
			{
				$newoshowdate=$infock["orientation"];
				$query .=",ori_show='yes', orientation_show='".$newoshowdate."' ";
				if($infock["status"]=='18')
				{
					setFTimeLine($id,'15',$newoshowdate);
					setFTimeLine($id,'11',$newoshowdate);
					//echo "TimeLine: Status Modified [15]<br/>";
					//echo "TimeLine: Orientation Attended [11]<br/>";
				}
				else if($infock["status"]=='3')
				{
					setFTimeLine($id,'11',$newoshowdate);
					//echo "TimeLine: Status Modified [15]<br/>";
					//echo "TimeLine: Orientation Attended [11]<br/>";
				}
				else
				{
					setTimeLine($id,'11',$newoshowdate);
					//echo "TimeLine: Orientation Attended [11]<br/>";
				}
			}
			//end of orientation show
			//orientation completed and status;
			$query .=" ,ori_show_info=NULL ";
			$checkocompdate = $_REQUEST["checkocompdate"];
			$ocompdate =fixdate_comps("mildate",$_REQUEST["ocompdate"]);
			$ori_comp=strtolower(trim($_REQUEST["ori_comp"]));
			$oricompnote=trim($_REQUEST["oricompnote"]);
			if(($checkocompdate=="yes" && !empty($ocompdate)) || $ori_comp=='yes')
			{
				if($infock["status"] !='7')
				{
					$query .=",ori_comp='yes', ori_comp_info=NULL ";
					if(!empty($ocompdate))
						$query .=" ,orientation_comp='".$ocompdate."' ";
					if($infock["status"]=='19')
					{
						setFTimeLine($id,'15','');
						setFTimeLine($id,'7',$ocompdate);
					}
					else if($infock["status"]=='3')
					{
						if($checkocompdate=="yes" && !empty($ocompdate))
						{
							setFTimeLine($id,'7',$ocompdate);
							//echo "TimeLine: Orientation Completed [7]<br/>";
						}
						else
							setTimeLine($id,'7',$ocompdate);
						//echo "TimeLine: Orientation Completed [7]<br/>";
					}
					else
					{
						setTimeLine($id,'7',$ocompdate);
						//echo "TimeLine: Orientation Completed [7]<br/>";
					}
				}
				$acode_show=strtolower(trim($_REQUEST["acode_show"]));
				$ccode = $_REQUEST["ccode"];
				$checkacode_info=trim($_REQUEST["ccode_info"]);
				if($acode_show=='yes' && !empty($ccode))
				{
					$report_to=base64_decode($_REQUEST["report_to"]);
					$trained=base64_decode($_REQUEST["trained_by"]);
					if(!empty($report_to) && $report_to !='na')
						$query .=", report_to='".$report_to."' ";
					if(!empty($trained) && $trained !='na')
						$query .=", trained='".$trained."' ";
					$ccode = trim(strtoupper($ccode));
					if(!empty($ccode))
					{
						$ccode = str_replace(" ","",$ccode);
						if(agentCodeExist($ccode,$id))
						{
							$_SESSION["recresult"]="ERROR: Agent Code Already Assigned, Choose Another Code";
							header("location:setrec.php?id=".base64_encode($id));
							exit;
						}
					}
					$query .=", ccode_aval='yes', ccode_info=NULL, ccode='".clean($ccode)."',status='5' ";
					if($infock["status"] !='5')
					{
						setFTimeLine($id,'15','');
						setFTimeLine($id,'17','');
						setFTimeLine($id,'5','');
					}
					else
					{
						setTimeLine($id,'17','');
						setTimeLine($id,'5','');
						//echo "TimeLine: Agent Code Assigned [17]<br/>";
						//echo "TimeLine: Hired [5]<br/>";
					}
				}
				else
				{
					if($acode_show=="no")
					{
						$query .=",ccode=NULL, ccode_aval='no',ccode_info='".clean($checkacode_info)."',status='2',report_to=NULL,trained=NULL ";
						if($infock["status"] =='5')
						{
							setFTimeLine($id,'15','');
							setFTimeLine($id,'2','');
						}
						else
							setTimeLine($id,'2','');
						//echo "TimeLine: Not Hired [2]<br/>";
					}
					else
					{
						$query .=" ,report_to=NULL,trained=NULL, ccode=NULL,status='7'";
					}
				}
				$checkimg = $_REQUEST["checkimg"];
				$imgname = $_FILES["imgprof"]["name"];
				if($checkimg=="yes" || !empty($imgname))
				{
					$fullurl = "images/aimg/";
					$fullurl .=$_FILES['imgprof']['name'];
					$target_path = "images/aimg/";
					//upload imge
					$imgname = $_FILES["imgprof"]["name"];
					if(!empty($imgname))
					{
						//$target_path = $target_path . $imgname;
						//cheange the name of the image;
						$imgdate = date("Ymdgis");
						$newname = $id.$imgdate.".jpg";
						$target_path = $target_path . $newname;
						//check the width and height
						list($width, $height, $type,$attr) = getimagesize($_FILES["imgprof"]["tmp_name"]);
						if($width > 200 || $height > 180 || $_FILES['imgprof']['type'] !='image/jpeg' ||  $_FILES['imgprof']['size'] > 90000)
						{
							$str="Please provide a right image size and type and size";
							$_SESSION["recresult"]=$str;
							header("location:setrec.php?id=".base64_encode($id));
							exit;
						}
						else if(file_exists("images/aimg/" . $newname))
						{
							$str = "ERROR:Image Already Exist";
							$_SESSION["recresult"]=$str;
							header("location:setrec.php?id=".base64_encode($id));
							exit;
						}
						else if(move_uploaded_file($_FILES['imgprof']['tmp_name'], $target_path))
						{
							$previmg = $_REQUEST["imgu"];
							if(!empty($previmg))
							{
								if(file_exists("images/aimg/" . $previmg))
									unlink("images/aimg/" . $previmg);
							}
							$query .= ", img='".$newname."' ";
						}
						else
						{
							$_SESSION["recresult"]="ERROR: Unable to upload Image";
							header("location:setrec.php?id=".base64_encode($id));
							exit;
						}
					}
				}
			}
			else if($ori_comp=='no')
			{
				$query .=",ori_comp='no', ccode=NULL,ccode_aval=NULL, ori_comp_info='".clean($oricompnote)."',orientation_comp=NULL, status='19' ";
				//setTimeLine($id,'19','');
				if($infock["status"] =='7' || $infock["status"] =='5')
				{
					setFTimeLine($id,'15','');
					setFTimeLine($id,'19','');
				}
				else
				{
					setTimeLine($id,'19','');
					//echo "TimeLine: Orientation Incompleted [19]<br/>";
				}
			}
		}
		}
	}
	}
	$query .=" where id='".$id."'";
	//echo $query;
	if($result = mysql_query($query))
	{
		if($statusm=="4")
			$queryx = "select * from task_users where id='".$obagent."'";
		else
			$queryx = "select * from task_users where id='".$intagent."'";
		if($resultx = mysql_query($queryx))
		{
			if(($num_rowsx = mysql_num_rows($resultx))>0)
				$agentx = mysql_fetch_assoc($resultx);
		}
		$queryx = "select * from rec_entries where id='".$id."'";
		if($resultx = mysql_query($queryx))
		{
			if(($num_rowsx = mysql_num_rows($resultx))>0)
				$entryx = mysql_fetch_assoc($resultx);
		}
		//,make sure if agent is changes then send email or it's new
		//email to agent
		$host = getHost();
		$queryy="update rec_entries set int_view='no' where id='".$entryx["id"]."'";
		@mysql_query($queryy);
		//end of email to agent
		if($checkm)
		{
		if($statusm=="3" || $checkoprocess=="yes")
		{
			$_SESSION["recresult"]="SUCCESS: Changes Saved";
			if($checkoprocess=="yes" && $ockemail==true)
			{
				//email for orientation template;
				if(!empty($entryx["email"]))
				{
					$queryy = "select * from rec_office where id='".$entryx["orientation_office"]."'";
					if($resulty = mysql_query($queryy))
					{
						if(($num_rowsy = mysql_num_rows($resulty))>0)
							$officeinfo= mysql_fetch_assoc($resulty);
					}
					$host= getHost();
					//$timxa = fixdate_comp($entryx["orientation"]);
					//$xstr = strtotime($entryx["orientation"]);
					$xhour = fixdate_comps("h",$entryx["orientation"]);
					$xdate = fixdate_comps("d",$entryx["orientation"]);
					//if(!empty($timxa))
					//	$timx = explode(" ",$timxa);
					$title="Hello, ".stripslashes($entryx["cname"]).", Family Energy Orientation Reminder Email";
					$message = "Hello, ".stripslashes($entryx["cname"])."!,<br/><br/>";
					$message .="Congratulations on making it this far! You are well on your way to becoming a CERTIFIED Family Energy Representative! You are scheduled for Orientation on ".$xdate.". Please arrive by ".$xhour." in our ".stripslashes($officeinfo["name"])." and be prepared to stay until 3:00 PM. Also, note that you will be required to complete our Certification Test at the end of the Orientation. Remember to bring your ID and Social Security card, if you have not already submitted previously.";
					$message .="<br/><br/>Below is our office information and we look forward to having you as part of the Family!<br/><br/>";
					$message .="<br/><br/><b>".stripslashes($officeinfo["name"])."</b><br/>";
					$message .=stripslashes($officeinfo["address"])."<br/>";
					$message .=stripslashes($officeinfo["city"]).", ".stripslashes($officeinfo["state"])." ".stripslashes($officeinfo["country"])." ".stripslashes($officeinfo["zip"])."<br/>";
					$message .="Email: ".stripslashes($officeinfo["email"])."<br/>";
					$message .="Phone: ".stripslashes($officeinfo["phone"])."<br/>";
					$message .="Email: ".stripslashes($officeinfo["fax"])."<br/><br/>";
					$message .="<u>if you are traveling by Car:</u><br/>";
					$message .=nl2br(stripslashes($officeinfo["idrive"]))."<br/><br/>";
					$message .="<u>if you are traveling by train:</u><br/>";
					$message .=nl2br(stripslashes($officeinfo["iwalk"]))."<br/><br/><br/>";
					$message .="Regards,<br/><br/>Family Energy Recruiting
";
					$email =stripslashes($entryx["email"]);
					if(!empty($email) && $eornotx =="yes")
					{
						if($checkemail_c = sendEmail($email,$title,$message))
							$_SESSION["recresult"]="SUCCESS: Information Saved And Email Sent";
					}
					if(!empty($entryx["cphone"]) && $textornotx=="yes")
					{
						$mmessage = "Family Energy: Orientation Confirmation on ".$xdate." at ".$xhour." in our ".stripslashes($officeinfo["name"]);
						$result = sendSMS($entryx["cphone"],$mmessage);
						if($result !="fail" && !empty($result))
						{
							if($checkemail_c)
								$_SESSION["recresult"]="SUCCESS: Information Saved And Email Sent and Text Message Sent";
							else
								$_SESSION["recresult"]="SUCCESS: Information Saved and Text Message Sent";
						}
					}
				}
			}
		}
		}
	}
	else
		$_SESSION["recresult"]="ERROR: Changes Couldn't Be Saved";
	}
	header('location:setrec.php?id='.base64_encode($id));
	exit;
}
else if($task=="delete")
{
	if(isset($_SESSION["rec_user"]))
	{
		$user = $_SESSION["rec_user"];
		if(!adminPrev($user["type"]))
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
	$query = "delete from task_users where id='$id'";
	if($result = mysql_query($query))
		$_SESSION["recresult"]="SUCCESS: User Deleted";
	else
		$_SESSION["recresult"]="ERROR: Unable To Delete User, Please try again later";
	header('location:viewusers.php');
	exit;
}
else if($task=="deleteen")
{
	if(isset($_SESSION["rec_user"]) && !isset($_SESSION["brownuser"]))
	{
		if(!adminlogin_exp())
			$user=$_SESSION["brownuser"];
		else
			$user=$_SESSION["rec_user"];
		/*if(!adminPrev($user["type"]))
		{
			header("location:status.php");
			exit;
		}*/
	}
	else
	{
		header("location:status.php");
		exit;
	}
	$id = base64_decode($_REQUEST["id"]);
	$query = "select * from rec_entries where id = '".$id."'";
	if($result = mysql_query($query))
	{
		if(($num_rows = mysql_num_rows($result))>0)
		{
			$userm = mysql_fetch_assoc($result);
			$userimg = $userm["img"];
		}
		else
			$userimg="";
	}
	else
		$userimg="";
	if($user["type"]=='9')
	{
		if($userm["catid"]!='2')
		{
			$_SESSION["recresult"]="ERROR: You are not authorized to delete this entry";
			header('location:view.php');
			exit;
		}
	}
	$query = "delete from rec_entries where id='$id'";
	if($result = mysql_query($query))
	{
		$_SESSION["recresult"]="SUCCESS: Entry Deleted";
		if(!empty($userm))
		{
			if(!empty($userm["img"]))
			{
				if(file_exists("images/aimg/" . $userimg))
					unlink("images/aimg/" . $userimg);
			}
		}
		$qx="delete from rec_timeline where entryid='".$id."'";
		@mysql_query($qx);
		$qx="delete from rec_nshow_track where entryid='".$id."'";
		@mysql_query($qx);
		$p=$_REQUEST["p"];
		if($p=="comp")
			header('location:viewcomp.php');
		else
			header('location:view.php');
		exit;
	}
	else
	{
		$_SESSION["recresult"]="ERROR: Unable To Delete Entry, Please try again later";
		header('location:view.php');
		exit;
	}
}
else
{
	if($task=="reopen")
	{
		$id = base64_decode($_REQUEST["id"]);
		if(!empty($id))
		{
			$query = "update rec_entries set folcome=NULL, status='1', int_show=NULL, int_show_info=NULL, int_show_date=NULL, folupdated_by=NULL, folupdated_date=NULL, compdate=NULL, folstatus='1', compnote=NULL, foldate=NULL, folnote=NULL where id='".$id."'";
			if($result = mysql_query($query))
				$_SESSION["recresult"]="SUCCESS: Information Restarted";
			else
				$_SESSION["recresult"]="ERROR: Unable To Restart Entry, Please try again later";
			header('location:setrec.php?id='.base64_encode($id));
			exit;
		}
		else
		{
			header('location:status.php');
			exit;
		}
	}
	else
	{
		header('location:status.php');
		exit;
	}
}
include "include/unconfig.php";
?>