<?php
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="icon" type="image/png" href="images/favicon.ico">
<link rel="stylesheet" type="text/css" href="css/style.css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" language="javascript" src="js/script.js"></script>
<title>Welcome to Family Energy Map System</title>
</head>
<body onload="preload('login_r.png,login.png')">
<div id="main_cont">
	<div id="login_cont_panel">
    	<div id="fpassword"><a href='fpassword.php'><img src="images/fpassword.png" border="0"alt="Forget Password" /></a></div>
        <div id="login_cont">
        	<div id="form">
                    <form action="login.php" method="post" onsubmit="return checkField()">
                    <div id="questions_in">
                    <input type="text" size="50" id="uname" name="uname" />
                    	<div id="form_spacer"></div>
                      <input type="password" size="50" id="upass" name="upass" />
               		</div>
                    <div id="form_spacerb"></div>
          <div id="message2" name="message2" class="white_home">
                        &nbsp;
                          <?php
                    if(isset($_SESSION["loginresult"]))
                    {
                        echo $_SESSION["loginresult"];
                        unset($_SESSION["loginresult"]);
                    }
                 ?>
                      </div>
                      <div id="home_button">
                        <input type="image"  src="images/rec_lbtn.png" onmouseover="javascript:this.src='images/rec_lbtn_r.png';" onmouseout="javascript:this.src='images/rec_lbtn.png';">
                        </div>
                    </form>
            </div>
        </div>
    </div>
    <div class="clearfooter"></div>
</div>
<?Php
include "include/footer.php";
?>
</body>
</html>