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

if($user["type"] !='2' && $user["type"] !='1')

{

	$_SESSION["recresult"]="ERROR: Invalid Access";

	header("location:home.php");

	exit;

}

$id = base64_decode($_REQUEST["id"]);

if(empty($id))

{

	$_SESSION["recresult"]="Invalid Office, please choose an office";

	header('location:officeview.php');

	exit;

}

else

{

	$query = "select * from rec_office where id='$id'";

	if($result = mysql_query($query))

	{

		if(($num_rows = mysql_num_rows($result))>0)

			$userm = mysql_fetch_assoc($result);

		else

		{

			$_SESSION["recresult"]="Invalid Office, please choose an office";

			header('location:officeview.php');

			exit;

		}

	}

	else

	{

		$_SESSION["recresult"]="Invalid Office, please choose an office";

		header('location:officeview.php');

		exit;

	}

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

<body onload="preload('savebtn_r.jpg')">

<div id="main_cont">

	<?php

	include "include/header.php";

	?>

    <div id="body_middle" >

    	<div id="body_middle_header">

        	<div id="body_middle_header_title">

            	Edit Office

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

                 <div style="text-align:center; font-size:15pt;">Hello <b><u><?php echo $user["username"]; ?></u></b>, Change settings for this office. 

               </div>

               <br/>

            	<table width="100%" border="0" cellspacing="0" cellpadding="0">

                  <form action="saveoffice.php" method="post" onsubmit="return checkFieldi()">

                    <input type="hidden" id="task" name="task" value="save"/>

                    <input type="hidden" id="id" name="id" value="<?php echo $_REQUEST["id"]; ?>"/>

                    <tr>

                      <td colspan="2" align="center" valign="middle">

                          <div id="message" name="message" class="white" style="text-align:center">

                            &nbsp;

                            <?php

                                if(isset($_SESSION["recresult"]))

                                {

                                   echo $_SESSION["recresult"];

                                   unset($_SESSION["recresult"]);

                                }

                             ?>

                          </div> 

              			</td>

                    </tr>

                    <tr>

                      <td width="27%" height="37" align="right" valign="middle"><span style='color:#b0b0b0; font-size:13pt; font-style:italic'>(e.g. Manhattan Office)</span>&nbsp;Name:</td>

                      <td width="73%" align="left" valign="middle">&nbsp;&nbsp;<input type="text" id="oname" name="oname" size="60" value="<?php echo stripslashes($userm["name"]); ?>" /></td>

                  </tr>

                  <tr>

                      <td height="37" align="right" valign="middle">Contact:</td>

                      <td align="left" valign="middle">&nbsp;&nbsp;<input type="text" id="ocontact" name="ocontact" size="60" value="<?Php echo stripslashes($userm["contact"]); ?>" /></td>

                  </tr>

                   <tr>

                      <td height="37" align="right" valign="middle">Service Email:</td>

                      <td align="left" valign="middle">&nbsp;&nbsp;<input type="text" id="oemail" name="oemail" size="60" value="<?Php echo stripslashes($userm["email"]); ?>" /></td>

                  </tr>

                    <tr>

                      <td height="37" align="right" valign="middle">Phone:</td>

                      <td align="left" valign="middle">&nbsp;&nbsp;<input type="text" id="ophone" name="ophone" size="60" value="<?Php echo stripslashes($userm["phone"]); ?>" /></td>

                  </tr>

                    <tr>

                      <td height="37" align="right" valign="middle">Fax:</td>

                      <td align="left" valign="middle">&nbsp;&nbsp;<input type="text" id="ofax" name="ofax" size="60" value="<?Php echo stripslashes($userm["fax"]); ?>"/></td>

                  </tr>

                  <tr>

                      <td height="37" align="right" valign="middle">Range of Days Avaliable:</td>

                      <td align="left" valign="middle">&nbsp;&nbsp;<input type="text" id="odays" name="odays" size="60" value="<?Php echo stripslashes($userm["days"]); ?>"/></td>

                  </tr>

                  <tr>

                      <td height="37" align="right" valign="middle">Range of Hours Avaliable:</td>

                      <td align="left" valign="middle">&nbsp;&nbsp;<input type="text" id="ohours" name="ohours" size="60" value="<?Php echo stripslashes($userm["hours"]); ?>"/></td>

                  </tr>

                  <tr>

                      <td height="37" align="right" valign="middle">Address:</td>

                      <td align="left" valign="middle">&nbsp;&nbsp;<input type="text" id="oaddress" name="oaddress" size="60" value="<?Php echo stripslashes($userm["address"]); ?>"/></td>

                  </tr>

                  <tr>

                      <td height="37" align="right" valign="middle">City:</td>

                      <td align="left" valign="middle">&nbsp;&nbsp;<input type="text" id="ocity" name="ocity" size="60" value="<?Php echo stripslashes($userm["city"]); ?>"/></td>

                  </tr>

                 <tr>

                      <td height="37" align="right" valign="middle">State:</td>

                      <td align="left" valign="middle">&nbsp;&nbsp;<input type="text" id="ostate" name="ostate" size="60" value="<?Php echo stripslashes($userm["state"]); ?>"/></td>

                  </tr>

                  <tr>

                      <td height="37" align="right" valign="middle">Country:</td>

                      <td align="left" valign="middle">&nbsp;&nbsp;<input type="text" id="ocountry" name="ocountry" size="60" value="<?Php echo stripslashes($userm["country"]); ?>"/></td>

                  </tr>

                   <tr>

                      <td height="37" align="right" valign="middle">Zip/Postal Code:</td>

                      <td align="left" valign="middle">&nbsp;&nbsp;<input type="text" id="ozip" name="ozip" size="60" value="<?Php echo stripslashes($userm["zip"]); ?>"/></td>

                  </tr>

                   <tr>

                     <td height="33" colspan="2" align="right" valign="middle"><hr/></td>

                    </tr>

                   <tr>

                      <td height="37" align="right" valign="top">Driving Directions:</td>

                      <td align="left" valign="middle">&nbsp;&nbsp;

                      <textarea id="odriving" name="odriving" cols="50" rows="5"><?Php echo htmlentities(stripslashes($userm["idrive"])); ?></textarea>

                    </td>

                  </tr>

                   <tr>

                     <td height="23" colspan="2" align="right" valign="top">&nbsp;</td>

                    </tr>

                   <tr>

                      <td height="37" align="right" valign="top">Walking Directions:</td>

                      <td align="left" valign="middle">&nbsp;&nbsp;

                      <textarea id="owalking" name="owalking" cols="50" rows="5"><?Php echo htmlentities(stripslashes($userm["iwalk"])); ?></textarea>

                    </td>

                  </tr>

                    <tr>

                      <td height="47" colspan="2" align="left" valign="middle">

                      <div id="message2" name="message2" class="white" style="text-align:center">

                &nbsp;

              </div>

                      </td>

                    </tr>

                    <tr>

                      <td colspan="2" align="center" valign="middle">

                      <a href="officeview.php" onmouseover="document.cancel.src='images/cancelbtn_r.jpg'" onmouseout="document.cancel.src='images/cancelbtn.jpg'"><img src="images/cancelbtn.jpg"  border="0" alt="Cancel and return to View Page" name="cancel" /></a>

              <?Php

              if($user["type"] == "1")

              {

                ?>

              &nbsp;&nbsp;&nbsp;

              <a href="Javascript:deletetask('office','<?php echo $_REQUEST["id"]; ?>')" onmouseover="document.delete.src='images/delete_r.jpg'" onmouseout="document.delete.src='images/delete.jpg'"><img src="images/delete.jpg"  border="0" alt="Delete This User" name="delete" /></a>

              <?php

              }

              ?>

              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                      <input type="image"  src="images/savebtn.jpg" onmouseover="javascript:this.src='images/savebtn_r.jpg';" onmouseout="javascript:this.src='images/savebtn.jpg';">

                      </td>

                  </tr>

                    <tr>

                      <td colspan="2" align="left" valign="middle">&nbsp;</td>

                  </tr>

                  </form>

                </table>

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