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

<body onload="preload('rec_b1_r.jpg,rec_b2_r.jpg,rec_b3_r.jpg,rec_b4_r.jpg,rec_b5_r.jpg,rec_b6_r.jpg')">

<div id="main_cont">

	<?php

	include "include/header.php";

	?>

    <div id="body_middle" >

    	<div id="body_middle_header">

        	<div id="body_middle_header_title">

            	Offices

            </div>

        </div>

        <div id="body_middle_middle">

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

                 <div style="text-align:center; font-size:15pt;">Hello <b><u><?php echo $user["username"]; ?></u></b>, Select office you wish to edit or <a href='createoffice.php'>Add an new office</a>. 

               </div>

               <br/>

            	 <div style="width:850px;" id="usercont" name="usercont">

            	  <?php

                  $query = "select * from rec_office order by datecreated desc";

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

                        <table width="100%" border="0" cellspacing="0" cellpadding="0">

                          <tr style="background-color:#014681; color:#FFF">

                            <td width="7%">&nbsp;</td>

                            <td width="28%" align="center" valign="middle">Name</td>

                            <td width="27%" align="center" valign="middle">City</td>

                            <td width="21%" align="center" valign="middle">Phone</td>

                            <td width="17%" align="center" valign="middle">Fax</td>

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
                                        echo "<tr $style><td height='27' align='center' valign='middle'>$count</td><td height='27' align='center' valign='middle'><a class='adminlink' href='editoffice.php?id=".base64_encode($rows["id"])."'>".stripslashes($rows["name"])."</a></td><td height='27' align='center' valign='middle'>".stripslashes($rows["city"])."</td><td height='27' align='center' valign='middle'>".$rows["phone"]."</td><td height='27' align='center' valign='middle'>".$rows["fax"]."</td></tr>";
                                        $count++;

                                    }

                                }

                                else

                                    echo "<tr class='rowstyleno'><td colspan='5' align='center' valign='middle'>No Office Created</td></tr>";

                            }

                            else

                                echo "<tr class='rowstyleno'><td colspan='5' align='center' valign='middle'>No Office Created</td></tr>";

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