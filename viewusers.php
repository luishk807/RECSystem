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
<body>
<div id="main_cont">
	<?php
	include "include/header.php";
	?>
    <div id="body_middle" >
    	<div id="body_middle_header">
        	<div id="body_middle_header_title">
            	All Users
            </div>
        </div>
        <div id="body_middle_middle" >
        	<div id="body_content">
            	<div id="message" name="message" class="white" style="text-align:center">
                 &nbsp;
                     <?php
                        if(isset($_SESSION["recresult"]))
                        {
                            echo $_SESSION["recresult"]."<br/>";
                            unset($_SESSION["recresult"]);
                        }
                     ?>
                </div> 
                 <div style="text-align:center; font-size:15pt;">Hello <b><u><?php echo $user["username"]; ?></u></b>, Select user you wish to see.
                <select id="selectview" name="selectview" onchange="changeuserview(this.value)">
                    <option value="<?php echo base64_encode('datecreated'); ?>" selected="selected">Select A View</option>
                    <option value="newuser">Create New User</option>
                    <option value="<?php echo base64_encode('signin'); ?>">Sort By Sign-in Date</option>
                    <option value="<?php echo base64_encode('datecreated'); ?>">Sort By Date Created</option>
                    <option value="<?php echo base64_encode('sortname'); ?>">Sort By Name</option>
                    <option value="sortob">Sort By Observation</option>
                    <option value="sortinter">Sort By Interviews</option>
                </select> 
               </div>
               </form>
                <br/>
			   <?php
			    $crud_ghost=getGHost();
				$crud_host=getHost();
				$crud_session=getSession();
				$crud_systemtitle=getSystemTitle();
			   include "../crud_lib/viewusers.php";
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