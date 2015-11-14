<?php
session_start();
include "../include/config.php";
include "include/function.php";
adminlogin();
familyredirect();
$showfamily=true;
$user=$_SESSION["rec_user"];
if(!pView($user["type"]))
{
	$_SESSION["recresult"]="ERROR: Invalid Access";
	header("location:home.php");
	exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="icon" type="image/png" href="images/favicon.ico">
<link rel="stylesheet" type="text/css" href="css/style.css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" language="javascript" src="js/script.js"></script>
<title>Welcome to Family Energy Recruiter System</title>
</head>
<body onload="preload('cancelbtn_r.jpg,delete_r.jpg,savebtn_r.jpg')">
<div id="main_cont">
	<?php
	include "include/header.php";
	?>
    <div id="body_middle" >
    	<div id="body_middle_header">
        	<div id="body_middle_header_title">
            	User Settings
            </div>
        </div>
        <div id="body_middle_middle" >
        	<div id="body_content">
            <input type="hidden" id="ghost" name="ghost" value="<?php echo base64_encode("rec_test"); ?>"/>
            	<?php
				$crud_ghost=getGHost();
				$crud_host=getHost();
				$crud_session=getSession();
				$crud_systemtitle=getSystemTitle();
				include "../crud_lib/settingm.php";
				?>
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