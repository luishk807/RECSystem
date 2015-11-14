<?Php
session_start();
include "include/config.php";
include "include/function.php";
ini_set('memory_limit','500M');
require("include/phpMailer/class.phpmailer.php");
date_default_timezone_set('America/New_York');
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
// Perform backup  
$filename = "/tmp/".date('Ymd-Hi').".gz";  

//DONT EDIT BELOW THIS LINE
//Export the database and output the status to the page
// Database Backup Filename & Location
$backupto = 'http://www.familyenergymap.com/femap';  // absolute path to folder containing database - no trailing slash.
echo $vmdb." ".$vmuser." ".$vmpass."<br/>";
$backupas =date( "Ymd" ).'.sql';
//exec("mysqldump --opt -h ".$vmhost." -u".$vmuser." -p".$vmpass." ".$vmdb." > ".$backupto."/".$backupas);


// Perform backup
$backupcommand = "mysqldump -h ".$vmhost." -u".$vmuser." -p".$vmpass." ".$vmdb." > ".$backupto."/".$backupas;

passthru ("$backupcommand", $error);
if($error) {
   echo ("Problem: $error\n"); exit;
}
/*switch($worked){
    case 0:
        echo 'Database <b>' .$vmdb.'</b> successfully exported to <b>~/' .$filename .'</b>';
        break;
    case 1:
        echo 'There was a warning during the export of <b>' .$vmdb.'</b> to <b>~/' .$filename .'</b>';
        break;
    case 2:
        echo 'There was an error during export. Please check your values:<br/><br/><table><tr><td>MySQL Database Name:</td><td><b>' .$vmdb.'</b></td></tr><tr><td>MySQL User Name:</td><td><b>' .$vmuser.'</b></td></tr><tr><td>MySQL Password:</td><td><b>NOTSHOWN</b></td></tr><tr><td>MySQL Host Name:</td><td><b>' .$vmhost.'</b></td></tr></table>';
        break;
}*/
// Send backup  
$date = date('d/m/Y');
$m=clone $mail;
$m->AddAddress("luishk807@hotmail.com","Luis Ho");
$m->AddAttachment($backupto.'/'.$backupas);
//$m->AddAttachment('calendar.gif');
$m->Subject = "Database Backup";  
$m->MsgHTML(nl2br("The Database Backup for {$date} is attached, the filesize is: {$filesize}MB."));  
if ($m->Send()) {  
   echo "Success";  
   unlink($backupto.'/'.$backupas);  
} else {  
   echo "Error, could not send: {$m->ErrorInfo}";  
}
//end of backup
include "include/unconfig.php";
?>