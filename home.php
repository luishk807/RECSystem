<?php
session_start();
include "include/config.php";
include "include/function.php";
adminlogin();
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
<script type="text/javascript" language="javascript">
function closemodal()
{
	document.getElementById("contmodal").style.display="none";
}
function showmodalwoffice(targ)
{
	//document.getElementById("showid").innerHTML=value;
	showmodalcoffice(targ);
	document.getElementById("contmodal").style.display="block";
}
$().ready(function() {
	var $scrollingDiv = $("#modalform");
	$(window).scroll(function(){			
		$scrollingDiv
			.stop()
			.animate({"marginTop": ($(window).scrollTop() + 5) + "px"}, "slow" );			
	});
});
</script>
<title>Welcome to Family Energy Recruiter System</title>
<style>
#contmodal{
	display:none;
}
#modalform{
	position:absolute;z-index:10000; width:800px; height:500px; background:#FFF;right: 0;left: 0; margin:0 auto;color:#000; padding:40px;
	top:2%;
}
#modalpop{
	position:fixed; z-index:9000;background-color:#000; top: 0; right: 0; bottom: 0; left: 0;opacity:0.4;
}
</style>
</head>
<body onload="preload('rec_b1_r.jpg,rec_b2_r.jpg,rec_b3_r.jpg,rec_b4_r.jpg,rec_b5_r.jpg,rec_b6_r.jpg')
<?php
if(!isset($_SESSION["woffice"]))
{
?>
,showmodalwoffice('<?php echo base64_encode('home'); ?>')
<?php
}
?>
">
<div id="contmodal">
	<div id="modalform">
    </div>
    <div id="modalpop"></div>
</div>
<div id="main_cont">
	<?php
	include "include/header.php";
	?>
    <div id="body_middle" >
    	<div id="body_middle_header">
        	<div id="body_middle_header_title">
            	Home
            </div>
        </div>
        <div id="body_middle_middle">
        	<div id="body_content_cen">Hello <b><u><?php echo $user["username"]; ?></u></b>, To Begin, Please Choose One Of The Followings:<br/>
					<?php
              if(isset($_SESSION["woffice"]))
              {
                  $woffice=$_SESSION["woffice"];
                  ?>
              <span style="text-decoration:underline; font-size:15pt">From <a href="javascript:showmodalwoffice('<?php echo base64_encode('home'); ?>')"><?php echo $woffice["name"]; ?></a>.</span><br/>
              <?php
              }
                ?>
            	<div id="home_icons">
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
            	<a href='import.php'><img src="images/hbtn5.jpg" border="0" /></a>
                <br/>
                <?Php
				if($showfamily)
				{
					?>
                <a href='create.php'><img src="images/hbtn1.jpg" border="0"  /></a>
                <br/>
                <a href='viewusers.php'><img src="images/hbtn2.jpg" border="0"  /></a>
                <br/>
                <?Php
				}
				?>
                <a href='view.php'><img src="images/hbtn3.jpg" border="0"  /></a>
                <br/>
                <?php
				if($showfamily)
				{
					?>
                <a href='setting.php'><img src="images/hbtn4.jpg" border="0"  /></a>
					<?Php
                    if(pView($user["type"]))
                    {
					?>
                    <br/>
                     <a href='memail.php'><img src="images/hbtn6.jpg" border="0"  /></a>
                     <?php
                    }
                    ?>
               <?Php
				}
				else
					echo "<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>";
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