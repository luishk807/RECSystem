<?php
session_start();
include "../include/config.php";
include "include/function.php";
adminlogin();
familyredirect();
$showfamily=true;
if(adminlogin_exp())
	$user=$_SESSION["rec_user"];
else
{
	$user=$_SESSION["brownuser"];
	$showfamily=false;
}
$p = $_REQUEST["p"];
if($p=="view")
	$prelink = "viewusers.php";
else
	$prelink = "home.php";
if(!pView($user["type"]))
	$disabled = "disabled='disabled'";
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
<body onload="preload('createbtn_r.jpg')">
<div id="main_cont">
	<?php
	include "include/header.php";
	?>
    <div id="body_middle" >
    	<div id="body_middle_header">
        	<div id="body_middle_header_title">
            	Create A New User
            </div>
        </div>
        <div id="body_middle_middle">
        	<div id="body_content">
            <?php
			$crud_ghost=getGHost();
			$crud_host=getHost();
			$crud_session=getSession();
			$crud_systemtitle=getSystemTitle();
			include "../crud_lib/create.php";
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
include "../include/unconfig.php";
?>