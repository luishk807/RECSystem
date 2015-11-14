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
	$uname = trim($_REQUEST["uname"]);
	$upass = trim($_REQUEST["newpass"]);
	$changepass = $_REQUEST["changepass"];
	$newpass = trim($_REQUEST["newpass"]);
	$name = trim(ucwords(strtolower($_REQUEST["realname"])));
	$email =trim(strtolower($_REQUEST["uemail"]));
	if($username != $user["username"])
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
		$query = "update task_users set username='".clean($uname)."',password='".md5(clean($newpass))."',name='".clean($name)."',title='".clean($title)."',email='".clean($email)."' where id='".$user["id"]."'";
	else
		$query = "update task_users set username='".clean($uname)."',name='".clean($name)."',title='".clean($title)."',email='".clean($email)."' where id='".$user["id"]."'";
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
			$user = array("id"=>$row["id"], "name"=>stripslashes($row["name"]),"username"=>stripslashes($row["username"]),"password"=>stripslashes($row["password"]),"email"=>stripslashes($row["email"]),'title'=>$row["title"],"status"=>$row["status"],"type"=>$row["type"]);
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
	$query = "insert ignore into task_users(username,password,name,title,email,status,type,date)values('".clean($uname)."','".md5(clean($upass))."','".clean($name)."','".clean($title)."','".clean($email)."','".$status."','".$type."',NOW())";
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
	}
	else
		$_SESSION["recresult"]="ERROR: Unable To Create User";
	header('location:viewusers.php');
	exit;	
}
else if($task=="savem")
{
	$userid = base64_decode($_REQUEST["id"]);
	$email =trim(strtolower($_REQUEST["uemail"]));
	$uname = trim($_REQUEST["uname"]);
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
	if($changepass=="yes")
		$query = "update task_users set username='".clean($uname)."',password='".md5(clean($newpass))."',name='".clean($name)."',title='".clean($title)."',email='".clean($email)."',status='".$status."',type='".$type."' where id='".$userid."'";
	else
		$query = "update task_users set username='".clean($uname)."',name='".clean($name)."',title='".clean($title)."',email='".clean($email)."',status='".$status."',type='".$type."' where id='".$userid."'";
	if($result = mysql_query($query))
	{
		$_SESSION["recresult"]="SUCCESS: Changes For Admin $uname Saved";
		if($status =="2")
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
		}
		if($resultemail = sendEmail($email,$title,$message))
			$_SESSION["recresult"]="SUCCESS: User Changes is Saved and Email Sent";
	}
	else
		$_SESSION["recresult"]="ERROR: Unable To Save Changes For User $uname";
	header('location:settingm.php?id='.$_REQUEST["id"]);
	exit;
}
else if($task=="createrec")
{
	$cname = trim(ucwords(strtolower($_REQUEST["cname"])));
	$cphone = trim($_REQUEST["cphone"]);
	$cdate= fixdate($_REQUEST["cdate"]);
	$coffice = base64_decode($_REQUEST["coffice"]);
	$csource = trim(ucwords(strtolower($_REQUEST["csource"])));
	$csource_title = trim(ucwords(strtolower($_REQUEST["csource_title"])));
	$email = strtolower($_REQUEST["email"]);
	$address = trim(ucwords(strtolower($_REQUEST["address"])));
	$city = trim(ucwords(strtolower($_REQUEST["city"])));
	$country= trim(ucwords(strtolower($_REQUEST["country"])));
	$state = trim(ucwords(strtolower($_REQUEST["state"])));
	$zip=trim(strtoupper($_REQUEST["zip"]));
	$idate=NULL;
	if(!empty($_REQUEST["idate"]))
		$idate= fixdate($_REQUEST["idate"]);
		$query = "insert ignore into rec_entries(cname,cphone,cdate,office,csource,csource_title,address,city,country,state,zip,email)values('".clean($cname)."','".clean($cphone)."','".$cdate."','".clean($coffice)."','".clean($csource)."','".clean($csource_title)."','".clean($address)."','".clean($city)."','".clean($country)."','".clean($state)."','".clean($zip)."','".clean($email)."')";
	if($result = mysql_query($query))
	{
		$id = mysql_insert_id();
		$_SESSION["recresult"]="SUCCESS: New Entry  Saved";
		if(!empty($id))
		{
			if(!empty($idate))
			{
				$queryx = "update rec_entries set idate='".$idate."' where id='".$id."'";
				if($result = mysql_query($queryx))
				{
					if(!empty($email))
					{
						$queryy = "select * from rec_office where id='".$id."'";
						if($resulty= mysql_query($queryy))
						{
							$host = getHost();
							if(($num_rowsy = mysql_num_rows($resulty))>0)
							{
								$officeinfo =mysql_fetch_assoc($resulty);
								$title="Hello $cname, Family Energy Interview Information";
								$message = "Hello $cname<br/><br/>";
								$message .="This email is to confirm your interview with Family Energy at ".$officeinfo["name"]." on $idate.<br/><br/>";
								$message .="$officename information is at follows:<br/>";
								$message .=$officeinfo["address"]."<br/>";
								$message .=$officeinfo["city"].", ".$officeinfo["country"]." ".$officeinfo["zip"]."<br/>";
								$message .="Email: ".$officeinfo["email"]."<br/>";
								$message .="Phone: ".$officeinfo["phone"]."<br/><br/>";
								$message .="Days For Interview: ".$officeinfo["days"]."<br/>";
								$message .="Hours: ".$officeinfo["hours"]."<br/><br/>";
								$message .="Attn,<br/><br/><img src='$host/images/logoemail.jpg'><br/><br/>Family Energy Team";
								if(sendEmail($email,$title,$messages))
									$_SESSION["recresult"]="SUCCESS: Information Saved And Email Sent";
							}
						}
					}
				}
			}
		}
	}
	else
		$_SESSION["recresult"]="ERROR: Unable To Create Entry";
	header('location:import.php');
	exit;
}
else if($task=="saverec")
{
	$id = base64_decode($_REQUEST["id"]);
	$cname = trim(ucwords(strtolower($_REQUEST["cname"])));
	$cphone = trim($_REQUEST["cphone"]);
	$changecdate = $_REQUEST["changecdate"];
	if($changecdate=="yes")
		$cdate= fixdate($_REQUEST["cdate"]);
	$coffice = base64_decode($_REQUEST["coffice"]);
	$csource = trim(ucwords(strtolower($_REQUEST["csource"])));
	$csource_title = trim(ucwords(strtolower($_REQUEST["csource_title"])));
	$email = strtolower($_REQUEST["email"]);
	$address = trim(ucwords(strtolower($_REQUEST["address"])));
	$city = trim(ucwords(strtolower($_REQUEST["city"])));
	$country= trim(ucwords(strtolower($_REQUEST["country"])));
	$state = trim(ucwords(strtolower($_REQUEST["state"])));
	$zip=trim(strtoupper($_REQUEST["zip"]));
	$idate=NULL;
	$changeidate = $_REQUEST["changeidate"];
	if($changeidate=="yes")
	{
		if(!empty($_REQUEST["idate"]))
			$idate= fixdate($_REQUEST["idate"]);
	}
	$query = "update rec_entries set cname='".clean($cname)."',cphone='".clean($cphone)."',cdate='".$cdate."',office='".clean($coffice)."',csource='".clean($csource)."',csource_title='".clean($csource_title)."',address='".clean($address)."',city='".clean($city)."',country='".clean($country)."',state='".clean($state)."',zip='".clean($zip)."',email='".clean($email)."' where id='".$id."'";
	if($result = mysql_query($query))
	{
		$id = mysql_insert_id();
		$_SESSION["recresult"]="SUCCESS: Changes Saved";
		if(!empty($id))
		{
			if(!empty($idate))
			{
				$queryx = "update rec_entries set idate='".$idate."' where id='".$id."'";
				if($result = mysql_query($queryx))
				{
					if(!empty($email))
					{
						$queryy = "select * from rec_office where id='".$id."'";
						if($resulty= mysql_query($queryy))
						{
							if(($num_rowsy = mysql_num_rows($resulty))>0)
							{
								$host= getHost();
								$officeinfo =mysql_fetch_assoc($resulty);
								$title="Hello $cname, Family Energy Interview Information";
								$message = "Hello $cname<br/><br/>";
								$message .="This email is to confirm your interview with Family Energy at ".$officeinfo["name"]." on $idate.<br/><br/>";
								$message .="$officename information is at follows:<br/>";
								$message .=$officeinfo["address"]."<br/>";
								$message .=$officeinfo["city"].", ".$officeinfo["country"]." ".$officeinfo["zip"]."<br/>";
								$message .="Email: ".$officeinfo["email"]."<br/>";
								$message .="Phone: ".$officeinfo["phone"]."<br/><br/>";
								$message .="Days For Interview: ".$officeinfo["days"]."<br/>";
								$message .="Hours: ".$officeinfo["hours"]."<br/><br/>";
								$message .="Attn,<br/><br/><img src='$host/images/logoemail.jpg'><br/><br/>Family Energy Team";
								if(sendEmail($email,$title,$messages))
									$_SESSION["recresult"]="SUCCESS: Information Saved And Email Sent";
							}
						}
					}
				}
			}
		}
	}
	else
		$_SESSION["recresult"]="ERROR: Unable To Save Changes";
	header('location:import.php');
	exit;
}
else if($task=="saverec_m")
{
	$id = base64_decode($_REQUEST["id"]);
	$statusm = $_REQUEST["statusm"];
	$checkm=true;
	$intagent = base64_decode($_REQUEST["intagent"]);
	$query = "update rec_entries set ";
	if(!empty($intagent))
		$query .= "interviewer ='".$intagent."'";
	$hired = $_REQUEST["hired"];
	if(!empty($hired) && $hired !='na')
	{
		if($hired=="yes")
			$query .= ",hired='yes'";
		else if($hired=="no")
		{
			$cnote = trim($_REQUEST["cnote"]);
			$query .= ",hired='no', status='1', interview_note='".clean($cnote)."'";
		}
	}
	$checkhiredp = $_REQUEST["checkhiredp"];
	if($checkhiredp=="yes")
	{
		$hiredp = base64_decode($_REQUEST["hiredp"]);
		if($hiredp !="na")
			$query .= ",status='".$hiredp."'";
	}
	if(!empty($hiredp) && !empty($statusm))
	{
		if($hiredp != $statusm)
			$checkm=false;
	}
	if($checkm)
	{
	if($statusm=="3")
	{
		$ooffice = base64_decode($_REQUEST["ooffice"]);
		if(!empty($ooffice))
			$query .=", orientation_office='".$ooffice."' ";
		$checkodate = $_REQUEST["checkodate"];
		if($checkodate=="yes")
		{
			$odate = fixdate($_REQUEST["odate"]);
			$ohour = $_REQUEST["ohour"];
			$ominute = $_REQUEST["ominute"];
			$oampm = strtolower($_REQUEST["oampm"]);
			$newdate = $odate;
			echo $oampm;
			if($oampm=="pm")
			{
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
			$newdate .=" ".$newhour.":".$ominute.":00";
			if(!empty($newdate))
				$query .=", orientation='".$newdate."' ";
		}
		$ccode = $_REQUEST["ccode"];
		if(!empty($ccode))
		{
			$ccode = trim(strtoupper($ccode));
			$query .=", ccode='".clean($ccode)."' ";
		}
		$checkimg = $_REQUEST["checkimg"];
		if($checkimg=="yes")
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
				date_default_timezone_set('America/New_York');
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
	else if($statusm=="4")
	{
		$checkobdate = $_REQUEST["checkobdate"];
		$obdate = fixdate($_REQUEST["obdate"]);
		$obagent = base64_decode($_REQUEST["obagent"]);
		$obnote = trim($_REQUEST["obnote"]);
		$obcomp = $_REQUEST["obcomp"];
		if($obcomp =="na")
			$obcomp=NULL;
		if($checkobdate=="yes")
			$query .=", observation='".$obdate."',observationer='".$obagent."',observation_note='".clean($obnote)."', observation_comp='".$obcomp."'";
		else
			$query .=", observationer='".$obagent."',observation_note='".clean($obnote)."', observation_comp='".$obcomp."'";
	}
	}
	$query .=" where id='".$id."'";
	if($result = mysql_query($query))
	{
		if($checkm)
		{
		if($statusm=="4" || $statusm=="3")
		{
			if($statusm=="4")
				$queryx = "select * from task_users where id='".$obagent."'";
			else if($statusm=="3")
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
			$_SESSION["recresult"]="SUCCESS: Changes Saved";
			if($statusm=="3")
			{
				//email to agent
				$host = getHost();
				if(($entryx["status"]=="1" || empty($entryx["status"])) || $entryx["interviewer"] != $intagent)
				{
					$title="Family Energy Master Recuiter System: New Interview";
					$message = "Hello ".stripslashes($agentx["name"]).",<br/><br/>";
					$message .="This email is to confirm that you have been assigned an interview on ".$entryx["idate"].".<br/><br/>";
					$message .="The Agent information is at follows:<br/>";
					$message .="Name:&nbsp;".stripslashes($entryx["cname"])."<br/>";
					$message .="Email:&nbsp;".stripslashes($entryx["email"])."<br/>";
					$message .="Phone:&nbsp;".stripslashes($entryx["cphone"])."<br/>";
					$message .="From Office:&nbsp;".stripslashes(getOfficeName($entryx["office"]))."<br/>";
					$message .="<br/>In order to maintain fluid communication with the sytem user please update the system once observation is completed.<br/><br/>";
					$message .="To view your entries please log in to our Family Energy Master Recruiter System by clicking the link below and use your username and password.<br/>";
					$message .="<a href='familyenergymap.com/rec/' target='_blank'>Family Energy Master Recuiter System</a><br/><br/>";
					$message .="Attn,<br/><br/>";
					$message .="<img src='$host/images/imagelogo.jpg' border='0'/>";
					$message .="<br/>";
					$msssage .="Family Energy Team<br/>";
					$email_to = stripslashes($agentx["email"]);
					sendEmail($email_to,$title,$message);
				}
				$queryy="update rec_entries set int_view='no' where id='".$entryx["id"]."'";
				@mysql_query($queryy);
				//email for orientation template;
				if(!empty($entryx["email"]))
				{
					
				}
			}
			else if($statusm=="4")
			{
				//email to agent
				$host = getHost();
				if(($entryx["status"]=="1" || empty($entryx["status"])) || $entryx["observationer"] != $obagent)
				{
					$title="Family Energy Master Recuiter System: New Observation Task";
					$message = "Hello ".stripslashes($agentx["name"]).",<br/><br/>";
					$message .="This email is to confirm that you have been assigned an new agent for observation on ".$obdate.".<br/><br/>";
					$message .="The Agent information is at follows:<br/>";
					$message .="Name:&nbsp;".stripslashes($entryx["cname"])."<br/>";
					$message .="Email:&nbsp;".stripslashes($entryx["email"])."<br/>";
					$message .="Phone:&nbsp;".stripslashes($entryx["cphone"])."<br/>";
					$message .="Interviewed By:&nbsp;".stripslashes(getName($entryx["interviewer"]))."<br/>";
					$message .="From Office:&nbsp;".stripslashes(getOfficeName($entryx["office"]))."<br/><br/>";
					$message .="In order to maintain fluid communication with the sytem user please update the system once observation is completed.<br/><br/>";
					$message .="To view your entries please log in to our Family Energy Master Recruiter System by clicking the link below and use your username and password.<br/>";
					$message .="<a href='familyenergymap.com/rec/' target='_blank'>Family Energy Master Recuiter System</a><br/><br/>";
					$message .="Attn,<br/><br/>";
					$message .="<img src='$host/images/imagelogo.jpg' border='0'/>";
					$message .="<br/>";
					$msssage .="Family Energy Team<br/>";
					$email_to = stripslashes($agentx["email"]);
					sendEmail($email_to,$title,$message);
				}
				//email for observation template;
				$queryy="update rec_entries set ob_view='no' where id='".$entryx["id"]."'";
				@mysql_query($queryy);
				if(!empty($entryx["email"]))
				{
					
				}
			}
		}
		}
	}
	else
		$_SESSION["recresult"]="ERROR: Changes Couldn't Be Saved";
	header('location:setrec.php?id='.base64_encode($id));
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
	header('location:status.php');
	exit;
}
include "include/unconfig.php";
?>