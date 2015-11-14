<?php
session_start();
include "include/config.php";
include "include/function.php";
adminlogin();
$popview="close";
$showfamily=true;
if(adminlogin_exp())
	$user=$_SESSION["rec_user"];
else
{
	$user=$_SESSION["brownuser"];
	$showfamily=false;
}
if(!isset($_SESSION["cfound"]))
{
	$_SESSION["recresult"]="Invalid Entry";
	header("location:home.php");
	exit;
}
$cfound=$_SESSION["cfound"];
$cuser =array();
$returnx=false;
$email=$cfound["email"];
$cphone=$cfound["cphone"];
if(!empty($email))
	$cfemail ="or email like '%".clean($cfound["email"])."%'";
if(!empty($cphone))
	$cfphone ="or cphone like '%".clean($cfound["cphone"])."%'";
$query = "select * from rec_entries where cname like '%".clean($cfound["cname"])."%' $cfphone $cfemail";
if($result = mysql_query($query))
{
	if(($numrows = mysql_num_rows($result))>0)
	{
		while($frow = mysql_fetch_array($result))
		{
			$cuser[]=$frow["id"];
		}
	}
	else
		$returnx = true;
}
else
	$returnx=true;
if($returnx)
{
	header("location:save.php?task=createrec");
	exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="icon" type="image/png" href="images/favicon.ico">
<link rel="stylesheet" type="text/css" href="css/style.css" />
<link rel="stylesheet" type="text/css" href="css/calendarb.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
<script type="text/javascript" src="js/calendarb_js/date.js"></script>
<script type="text/javascript" src="js/calendarb_js/jquery.datePicker.js"></script>
<script type="text/javascript" language="javascript" src="js/script.js"></script>
 <script type="text/javascript">
 $(function()
 {
	//$('.date-pick').datePicker({autoFocusNextInput: true});
	Date.format = 'mm/dd/yyyy';
	$('.date-pick').datePicker({startDate:'01/01/1996'});
 });
function closemodal()
{
	document.getElementById("contmodal").style.display="none";
}
function showmodal(value)
{
	//document.getElementById("showid").innerHTML=value;
	showmodalform_text(value);
	document.getElementById("contmodal").style.display="block";
}
function reopen(v1,v2)
{
	var confirmx = window.confirm("WARNING!! You are about to Re-Open This Entry! doing so will cause system to delete all the followups informations made in the past for this entry.\r\n\r\nDo You Want To Proceed?.\r\n\r\nTo Proceed, click Okay or To Cancel click Cancel");
	if(confirmx==true)
		window.location.href="save.php?id="+v1+"&task="+v2;
}
</script>
<style>
#contmodal{
	display:none;
}
#modalform{
	position:absolute;z-index:10000; width:800px; height:500px; background:#FFF;right: 0;left: 0; margin:0 auto; top:30%; color:#000; padding:40px;
}
#modalpop{
	position:fixed; z-index:9000;background-color:#000; top: 0; right: 0; bottom: 0; left: 0;opacity:0.4;
}
</style>
<title>Welcome to Family Energy Recruiter System</title>
</head>
<body>
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
            	User Match Found!
            </div>
        </div>
        <div id="body_middle_middle" <?php //echo $styleh; ?>>
        	<div id="body_content">
            <div id="message" name="message" class="white" style="text-align:center; padding-right:50px; padding-left:50px">
            	<?Php
				if(isset($_SESSION["recresult"]))
				{
					echo $_SESSION["recresult"]."<br/>";
					unset($_SESSION["recresult"]);
				}
				?>
            </div>
            <form action="" method="post" onsubmit="">
            <input type="hidden" id="id" name="id" value="<?php echo $_REQUEST["id"]; ?>" />
            <input type="hidden" id="task" name="task" value="" />
           	  <fieldset>
               	<legend>Information Entered: <b><?php echo checkNA(stripslashes($cfound["cname"])); ?></b>&nbsp;&nbsp;[<a href="javascript:showWarning('addnew','')">Create As New</a>]</legend>
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="19%" align="right" valign="middle">Date For Interview:</td>
                    <td width="22%" align="left" valign="middle">&nbsp;<b><?php echo rLongTextd(fixdate_comps('onsip',$cfound["idate"]." ".$cfound["ctime"])); ?></b></td>
                    <td width="2%" align="left" valign="middle">&nbsp;</td>
                    <td width="17%" align="right" valign="middle">Caller Email:</td>
                    <td width="40%" align="left" valign="middle">&nbsp;<b><?php echo rLongTextd(checkNA(stripslashes($cfound["email"]))); ?></b></td>
                  </tr>
                  <tr>
                    <td align="right" valign="middle">Phone Number:</td>
                    <td align="left" valign="middle">&nbsp;<b><?php echo rLongTextd(checkNA($cfound["cphone"])); ?></b></td>
                    <td align="left" valign="middle">&nbsp;</td>
                    <td align="right" valign="middle">Address:</td>
                    <td align="left" valign="middle">&nbsp;<b><?php echo rLongTextd(checkNA($cfound["address"])); ?></b></td>
                  </tr>
                  <tr>
                    <td align="right" valign="middle">Date Called:</td>
                    <td align="left" valign="middle">&nbsp;<b><?php echo rLongTextd($cfound["cdate"]); ?></b></td>
                    <td align="left" valign="middle">&nbsp;</td>
                    <td align="right" valign="middle">City:</td>
                    <td align="left" valign="middle">&nbsp;<b><?php echo rLongTextd(checkNA(stripslashes($cfound["city"]))); ?></b></td>
                  </tr>
                  <tr>
                    <td align="right" valign="middle" title="<?php echo getSourceName($cfound["csource"]); ?>">Source:</td>
                    <td align="left" valign="middle">&nbsp;<b><?php echo rLongTextd(getSourceName($cfound["csource"])); ?></b></td>
                    <td align="left" valign="middle">&nbsp;</td>
                    <td align="right" valign="middle">Country:</td>
                    <td align="left" valign="middle">&nbsp;<b>USA</b></td>
                  </tr>
                  <tr>
                    <td align="right" valign="middle">Source Info:</td>
                    <td align="left" valign="middle" title="<?php echo getSourceInfo($cfound["csource_title"]); ?>">&nbsp;<b><?php echo rLongTextd(getSourceInfo($cfound["csource_title"])); ?></b></td>
                    <td align="left" valign="middle">&nbsp;</td>
                    <td align="right" valign="middle">State:</td>
                    <td align="left" valign="middle">&nbsp;<b><?php echo rLongTextd(checkNA(stripslashes($cfound["state"]))); ?></b></td>
                  </tr>
                  <tr>
                    <td align="right" valign="middle">From Office:</td>
                    <td align="left" valign="middle">&nbsp;<b><?php echo rLongTextd(checkNA(stripslashes(getOfficeName($cfound["coffice"])))); ?></b></td>
                    <td align="left" valign="middle">&nbsp;</td>
                    <td align="right" valign="middle">Zip/ Postal Code: </td>
                    <td align="left" valign="middle">&nbsp;<b><?php echo rLongTextd(checkNA($cfound["zip"])); ?></b></td>
                  </tr>
                </table>
                </fieldset>
                <br/><br/>
				Matches Found:
                <?php
				//$cuser = $cfound["cid"];
				if(sizeof($cuser)>0)
				{
					$count=1;
					for($i=0;$i<sizeof($cuser);$i++)
					{
						$query = "select * from rec_entries where id='".$cuser[$i]."' order by id";
						if($result = mysql_query($query))
						{
							if(($num_rows = mysql_num_rows($result))>0)
							{
								while($userm= mysql_fetch_array($result))
								{
									echo "<br/><br/><hr/><br/>"
									?>
									  <fieldset>
										<legend>[<?php echo $count; ?>] Name: <b><?php echo checkNA(stripslashes($userm["cname"])); ?></b>&nbsp;&nbsp;Created by:&nbsp;<span style='font-weight:bold'><?Php echo getUserName($userm["createdby"]); ?></span>&nbsp;&nbsp;[<a href="javascript:showWarning('updatenew','<?Php echo base64_encode($userm["id"]); ?>')">Save It Into This</a>]&nbsp;&nbsp;[<a href="javascript:showWarning('delete','<?Php echo base64_encode($userm["id"]); ?>')">Delete</a>]</legend>
										  <table width="100%" border="0" cellspacing="0" cellpadding="0">
										  <tr>
											<td width="19%" align="right" valign="middle">Date For Interview:</td>
											<td width="22%" align="left" valign="middle">&nbsp;<b><?php echo rLongTextd($userm["idate"]); ?></b></td>
											<td width="2%" align="left" valign="middle">&nbsp;</td>
											<td width="17%" align="right" valign="middle">Caller Email:</td>
											<td width="40%" align="left" valign="middle">&nbsp;<b><?php echo rLongTextd(checkNA(stripslashes($userm["email"]))); ?></b></td>
										  </tr>
										  <tr>
											<td align="right" valign="middle">Phone Number:</td>
											<td align="left" valign="middle">&nbsp;<b><?php echo rLongTextd(checkNA($userm["cphone"])); ?></b></td>
											<td align="left" valign="middle">&nbsp;</td>
											<td align="right" valign="middle">Address:</td>
											<td align="left" valign="middle">&nbsp;<b><?php echo rLongTextd(checkNA($userm["address"])); ?></b></td>
										  </tr>
										  <tr>
											<td align="right" valign="middle">Date Called:</td>
											<td align="left" valign="middle">&nbsp;<b><?php echo rLongTextd($userm["cdate"]); ?></b></td>
											<td align="left" valign="middle">&nbsp;</td>
											<td align="right" valign="middle">City:</td>
											<td align="left" valign="middle">&nbsp;<b><?php echo rLongTextd(checkNA(stripslashes($userm["city"]))); ?></b></td>
										  </tr>
										  <tr>
											<td align="right" valign="middle" title="<?php echo getSourceName($userm["csource"]); ?>">Source:</td>
											<td align="left" valign="middle">&nbsp;<b><?php echo rLongTextd(getSourceName($userm["csource"])); ?></b></td>
											<td align="left" valign="middle">&nbsp;</td>
											<td align="right" valign="middle">Country:</td>
											<td align="left" valign="middle">&nbsp;<b><?php echo rLongTextd(checkNA(stripslashes($userm["country"]))); ?></b></td>
										  </tr>
										  <tr>
											<td align="right" valign="middle">Source Info:</td>
											<td align="left" valign="middle" title="<?php echo getSourceInfo($userm["csource_title"]); ?>">&nbsp;<b><?php echo rLongTextd(getSourceInfo($userm["csource_title"])); ?></b></td>
											<td align="left" valign="middle">&nbsp;</td>
											<td align="right" valign="middle">State:</td>
											<td align="left" valign="middle">&nbsp;<b><?php echo rLongTextd(checkNA(stripslashes($userm["state"]))); ?></b></td>
										  </tr>
										  <tr>
											<td align="right" valign="middle">From Office:</td>
											<td align="left" valign="middle">&nbsp;<b><?php echo rLongTextd(checkNA(stripslashes(getOfficeName($userm["office"])))); ?></b></td>
											<td align="left" valign="middle">&nbsp;</td>
											<td align="right" valign="middle">Zip/ Postal Code: </td>
											<td align="left" valign="middle">&nbsp;<b><?php echo rLongTextd(checkNA($userm["zip"])); ?></b></td>
										  </tr>
										</table>
               						 </fieldset>
                                     <?php
									 $count++;
								}
							}
						}
					}
				}
				?>
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
    	      <td height="47" colspan="2" align="left" valign="middle">
              <div id="message2" name="message2" class="white" style="text-align:center; padding-right:50px; padding-left:50px">&nbsp;</div>
              </td>
   	        </tr>
    	    <tr>
    	      <td colspan="2" align="center" valign="middle">
      <a href="import.php" onmouseover="document.view.src='images/cancelbtn_r.jpg'" onmouseout="document.view.src='images/cancelbtn.jpg'"><img src="images/cancelbtn.jpg"  border="0" alt="View All Users" name="view" /></a>
              </td>
  	      </tr>
    	    <tr>
    	      <td colspan="2" align="left" valign="middle">&nbsp;</td>
  	      </tr>
                </table>
            </form>
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