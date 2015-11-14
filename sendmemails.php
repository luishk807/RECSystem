<?php
session_start();
include "include/config.php";
include "include/function.php";
date_default_timezone_set('America/New_York');
adminlogin();
familyredirect();
$showfamily=true;
$user=$_SESSION["rec_user"];
$today=date('Y-m-d');
$caid=base64_decode($_REQUEST["aid"]);
$caidx="";
if(!empty($caid))
	$caidx=" and id in(".$caid.") ";
ini_set('memory_limit','500M');
require("include/phpMailer/class.phpmailer.php");
date_default_timezone_set('America/New_York');
$today=date("Y-m-d");
/****************PHPMailer Iinitial Configuration********************************/
$mail = new PHPMailer();
$mail->IsSMTP(); // set mailer to use SMTP
$mail->Host = "smtpout.secureserver.net";  // specify main and backup server
$mail->SMTPAuth = true;     // turn on SMTP authentication
$mail->Username = "hr@familyenergysales.com";  // SMTP username
$mail->Password = "hr1514"; // SMTP password
$mail->Port = 80;
$mail->SMTPSecure = "http";
$mail->SMTPDebug = 1; // 1 tells it to display SMTP errors and messages, 0 turns off all errors and messages, 2 prints 

$mail->From = "info@yourfamilyenergy.com";
$mail->FromName = "Family Energy New York";
//$mail->WordWrap = 50;                                 // set word wrap to 50 characters
$mail->IsHTML(true);                                  // set email format to HTML
//$mail->AltBody = "This is the body in plain text for non-HTML mail clients";
/****************PHPMailer Iinitial Configuration********************************/
$title="Family Energy";
$mail->AddEmbeddedImage("images/email.jpg",md5($today).'_temp-image','temp-image');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="icon" type="image/png" href="images/favicon.ico">
<link rel="stylesheet" type="text/css" href="css/style.css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
<script type="text/javascript" src="js/calendarb_js/date.js"></script>
<script type="text/javascript" src="js/calendarb_js/jquery.datePicker.js"></script>
<link rel="stylesheet" type="text/css" href="css/calendarb.css">
<script type="text/javascript" language="javascript" src="js/script.js"></script>
<script type="text/javascript" language="javascript">
$(function()
{
	//$('.date-pick').datePicker({autoFocusNextInput: true});
	Date.format = 'mm/dd/yyyy';
	$('.date-pick').datePicker({startDate:'01/01/1996'});
});
</script>
<title>Welcome to Family Energy Recruiter System</title>
</head>
<body>
<div id="main_cont">
	<?php
	include "include/header.php";
	?>
    <div id="body_middle" >
    	<div id="body_middle_header">
        	<div id="body_middle_header_title">
            	Send Email Status
            </div>
        </div>
        <div id="body_middle_middle" >
        	<div id="body_content">
                 <div style="text-align:center; font-size:15pt;">Hello <b><u><?php echo $user["username"]; ?></u></b>, View Email Delivery Status.&nbsp;&nbsp;[<a href='memail.php'>Back To Email Setting Page</a>]
               </div>
               <br/>
                <div id="message2" name="message2" class="white" style="text-align:center; padding-right:50px; padding-left:50px">
                <?php
                 if(isset($_SESSION["recresult"]))
                 {
                     echo $_SESSION["recresult"]."<br/>";
                     unset($_SESSION["recresult"]);
                 }
                ?>
               </div>
               <!--<div style="text-align:center">
                <form method="post" action="viewuphone.php" onsubmit="return checkFieldk();" >
                	<div style="text-align:center; margin-left:auto; margin-right:auto; padding-left:80px;">
                        <div style="width:340px; text-align:center; margin-left:auto; margin-right:auto; float:left;">
                            <div style="float:left; padding-right:5px;">Choose A Date Range:</div>&nbsp;
                            <input name="date1" id="date1" class="date-pick" readonly="readonly" value='<?php echo $date1_n; ?>' />
                        </div>
                        &nbsp;&nbsp;&nbsp;
                        <div style="float:left; padding-right:10px;">
                         To:
                        </div>
                        <div style="width:200px; text-align:center; margin-left:auto; margin-right:auto;float:left">
                            <input name="date2" id="date2" class="date-pick" readonly="readonly" value='<?php echo $date2_n; ?>' />
                        </div>
                        <div style="float:left">
                        	&nbsp;&nbsp;<input type="submit" value="Submit" />
                        </div>
                        <div style="clear:both"></div>
                    </div>
                </form>
               </div>-->
               <br/>
               <div style="width:850px; height:600px; overflow:auto; border:1px solid #CCC" id="memailcont" name="memailcont">
				<?php
				$query="select * from m_emails where optout='no' $caidx order by email";
				if($result=mysql_query($query))
				{
					if(($num_rows=mysql_num_rows($result))>0)
					{
						$count=1;
						while($rows=mysql_fetch_array($result))
						{
							$message="";
							$mail2=clone $mail;
							$mail2->AddAddress(stripslashes($rows["email"]));
							$mail2->Subject=$title;
							$message .="<div style='width:800px;text-align:center'>";
							$message .="<img src='cid:".md5($today)."_temp-image' border='0' alt='template_email/><br/>";
							$message .="<br/><div style='text-align:center;font-size:13pt'><a href='http://www.yourfamilyenergy.com/unsub/unsub.php?email=".stripslashes(trim($rows["email"]))."' target='_blank' style='color:#585858'>Unsubscribe From This Campaign</a></div>";
							$message .="</div>";
							$mail2->Body=$message;
							echo $count."> Sending ".stripslashes($rows["email"])."....";
							if($mail2->Send())
							{
								echo "send!<br/>";
								$qx="insert ignore into m_emails_sent(emailid,date_sent)values('".$rows["id"]."',NOW())";
								@mysql_query($qx);
							}
							else
								echo "can't sent!<br/>";
							$count++;
						}
					}
				}
				?>
               </div>
            </div>
        </div>
        <div id="body_footer"></div>
    </div>
    <div class="clearfooter"></div>
</div>
<?Php
include "include/footer.php";
?>
</body>
</html>
<?php
include "include/unconfig.php";
?>