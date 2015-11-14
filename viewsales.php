<?php
session_start();
include "include/config.php";
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

                <div style="text-align:center">

                <?php

                 if(isset($_SESSION["recresult"]))

                 {

                     echo $_SESSION["recresult"];

                     unset($_SESSION["recresult"]);

                 }

                ?>

               </div>

              <div style="width:850px;" id="usercont" name="usercont">

            	  <?php

                  $query = "select * from task_users where id != '".$user["id"]."' and id !=1 order by date desc";

                    if($result = mysql_query($query))

                    {

                        if(($num_rows = mysql_num_rows($result))>15)

                            $height ="style='font-size:15pt;'";

                        else

                            $height="style='height:500px;font-size:15pt;'";

                    }

                    else

                        $height="style='height:500px;font-size:15pt;'";

                  ?>

                  <div <?Php echo $height; ?>>

                      <?php

                        $taskview = base64_decode($_REQUEST["taskview"]);

                        if($taskview=="signin")

                        {

                            $query = "select id,status,name,last_checkin_rec as dateget,username from task_users where id != '".$user["id"]."' order by last_checkin_rec desc";

                            $titlecol = "Last Check-in";

                        }

						else if($taskview=="sortname")

						{

							 $query = "select id,status,name,date as dateget,username from task_users where id != '".$user["id"]."' order by name";

                            $titlecol = "Created";

						}

                        else

                        {

                            $query = "select id,status,name,date as dateget,username from task_users where id != '".$user["id"]."' order by date desc";

                            $titlecol = "Created";

                        }

                      ?>

                        <table width="100%" border="0" cellspacing="0" cellpadding="0">

                          <tr style="background-color:#014681; color:#FFF">

                            <td width="7%">&nbsp;</td>

                            <td width="28%" align="center" valign="middle">Username</td>

                            <td width="27%" align="center" valign="middle">Name</td>

                            <td width="21%" align="center" valign="middle">Status</td>

                            <td width="17%" align="center" valign="middle"><?php echo $titlecol; ?></td>

                          </tr>

                          <?php

                            if($result = mysql_query($query))

                            {

                                if(($num_rows = mysql_num_rows($result))>0)

                                {

                                    $count = 1;

                                    $total = 0;

                                    while($rows = mysql_fetch_array($result))

                                    {

                                        $total = $count %2;

                                        if($total !=0)

                                            $style = "style='background-color:#e6f882'";

                                        else

                                            $style="";

                                        if($taskview=="signin")

                                        {

                                            $date = fixdate_comp($rows["dateget"]);

                                            if(empty($date))

                                                $date = "N/A";

                                        }

                                        else

                                            $date = fixdateb($rows["dateget"]);

                                        echo "<tr class='rowstyle' $style><td height='27' align='center' valign='middle'>$count</td><td height='27' align='center' valign='middle'><a class='adminlink' href='settingm.php?id=".base64_encode($rows["id"])."'>".stripslashes($rows["username"])."</a></td><td height='27' align='center' valign='middle'>".stripslashes($rows["name"])."</td><td height='27' align='center' valign='middle'>".getStatus($rows["status"])."</td><td height='27' align='center' valign='middle'>".$date."</td></tr>";

                                        $count++;

                                    }

                                }

                                else

                                    echo "<tr class='rowstyleno'><td colspan='5' align='center' valign='middle'>No User Created</td></tr>";

                            }

                            else

                                echo "<tr class='rowstyleno'><td colspan='5' align='center' valign='middle'>No User Created</td></tr>";

                          ?>

                        </table>

                  </div>

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