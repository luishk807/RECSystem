<?php
session_start();
include "include/config.php";
include "include/function.php";
date_default_timezone_set('America/New_York');
adminlogin();
$showfamily=true;
//unset($_SESSION["woffice"]);
if(adminlogin_exp())
	$user=$_SESSION["rec_user"];
else
{
	$user=$_SESSION["brownuser"];
	$showfamily=false;
}
$dcfound = base64_decode($_REQUEST["t"]);
if($dcfound=="dcfound")
	unset($_SESSION["cfound"]);
if(isset($_SESSION["cfound"]))
{
	$userm=$_SESSION["cfound"];
}
$today=date("m/d/Y");
$tomorrow=date("m/d/Y", mktime(0, 0, 0, date("m"),date("d")+1,date("Y")));
$thishx = date('H');
$thish = date('h');
$thismin = date('i');
$amsele="";
$pmsele="";
if($thishx>11)
	$pmsele="selected='selected'";
else
	$amsele="selected='selected'";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="icon" type="image/png" href="images/favicon.ico">
<link rel="stylesheet" type="text/css" href="css/style.css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="css/calendarb.css">
<script type="text/javascript" language="javascript" src="js/script.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
<script type="text/javascript" src="js/calendarb_js/date.js"></script>
<script type="text/javascript" src="js/calendarb_js/jquery.datePicker.js"></script>
<script type="text/javascript" charset="utf-8">
 $(function()
 {
	//$('.date-pick').datePicker({autoFocusNextInput: true});
	Date.format = 'mm/dd/yyyy';
	$('.date-pick').datePicker({startDate:'01/01/1996'});
 });
function closemodal()
{
	document.getElementById("phonediv").innerHTML="";
	document.getElementById("closebtn").style.display="none";
	document.getElementById("closedivlink").style.display="none";
	document.getElementById("loadergif").style.display="block";
	document.getElementById("contmodal").style.display="none";
}
function showmodalwoffice(targ)
{
	//document.getElementById("showid").innerHTML=value;
	showmodalcoffice(targ);
	document.getElementById("contmodal").style.display="block";
}
function showmodalphone(value)
{
	//document.getElementById("showid").innerHTML=value;
	clearInterval(intervalID);
	intervalID= 0;
	showmodalphonefill(value);
	document.getElementById("contmodal").style.display="block";
}
function timershow()
{
	timerx=setInterval(showdiv,10000);
}
function setphone(value)
{
	if(value.length>1)
	{
		var str=value.split('-');
		document.getElementById("cphoneba").value=str[0];
		document.getElementById("cphonebb").value=str[1];
		document.getElementById("cphonebc").value=str[2];
		document.getElementById("check_cphonex").value='no';
	}
	else
	{
		document.getElementById("cphoneba").value="";
		document.getElementById("cphonebb").value="";
		document.getElementById("cphonebc").value="";
		document.getElementById("check_cphonex").value='no';
	}
	closemodal();
	document.getElementById("showc_cphonex").style.visibility='visible';
	document.getElementById("cbtn").src="images/confirmbtn.jpg";
	intervalID = window.setInterval(showpop, 10000);
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
<body onload="
<?php
if(!isset($_SESSION["woffice"]))
{
?>
showmodalwoffice('<?php echo base64_encode('import'); ?>')
<?php
}
?>
">
<!--<body>-->
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
                Entry A New Entry Manually
            </div>
        </div>
        <div id="body_middle_middle">
        	<div id="body_content">
      <div style="text-align:center; font-size:18pt">Hello <b><u><?php echo $user["username"]; ?></u></b>, Please fill up this form to enter a new entry manually.<br/>
      <?php
	  if(isset($_SESSION["woffice"]))
	  {
		  $woffice=$_SESSION["woffice"];
		  ?>
      <span style="text-decoration:underline; font-size:15pt">From <a href="javascript:showmodalwoffice('<?php echo base64_encode('import'); ?>')"><?php echo $woffice["name"]; ?></a>.</span><br/>
      <?php
	  }
		?>
      <br/></div>
            	<table width="100%" border="0" cellspacing="0" cellpadding="0">
          <form action="save.php" method="post" id="rec_forma" name="rec_forma" onsubmit="return checkFieldb()">
            <input type="hidden" id="task" name="task" value="createrec"/>
            <input type="hidden" id="cphone" name="cphone" value="" />
            <input type="hidden" id="cphonex" name="cphonex" value="" />
            <input type="hidden" id="ctime" name="ctime" value="" />
            <input type="hidden" id="officeid" name="officeid" value="<?php $wofficex=$_SESSION["woffice"]; echo base64_encode($wofficex["id"]); ?>" />
            <input type="hidden" id="csource_cont" name="csource_cont" value="" />
            <input type="hidden" id="checkdatediv" name="checkdatediv" value="yes" />
            <input type="hidden" id="checkexpress" name="checkexpress" value="no" />
            <input type="hidden" id="check_cphonex" name="check_cphonex" value="yes" />
            <?Php
				if(isset($_SESSION["rec_user"]))
					$checkfamily="yes";
				else
					$checkfamily="no";
			?>
            <input type="hidden" id="check_family" name="check_family" value="<?Php echo $checkfamily; ?>" />
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
      <?php
	  if(isset($_SESSION["cfound"]))
	  {
		  ?>
         	<tr>
               	      <td height="37" align="right" valign="middle">Overwrite Saved Session?:</td>
               	      <td align="left" valign="middle">&nbsp;<input type="checkbox" id="osession" name="osession" checked="checked" />&nbsp; <span class='optional_style'>System Will Use This Information</span></td>
   	        </tr>
         	<tr>
         	  <td height="37" align="right" valign="middle">or Is This A New Entry?:</td>
         	  <td align="left" valign="middle">&nbsp;&nbsp;<a style='font-style:italic' href='import.php?t=<?php echo base64_encode('dcfound');?>'>Start A New One</a>&nbsp; <span class='optional_style'>Saved Information will be lost to start a brand new entry</span></td>
       	  </tr>
       <?Php
	  }
	  ?>
      				<!--<input type="hidden" id="ocall" name="ocall" value="yes" />-->
               	    <tr>
               	      <td height="37" align="right" valign="middle"><span class='red'>*</span> Is This From A Phone Call?</td>
               	      <td align="left" valign="middle">&nbsp;
                      <select id="ocall" name="ocall">
                      	<?Php
						$ocallx=$userm["ocall"];
						$ocallno="";
						$ocallyes="";
						if($ocallx=="yes")
							$ocallyes="selected='selected'";
						else if($ocallx=="no")
							$ocallno="selected='selected'";
						?>
                      	<option value="na">Select Situation</option>
                        <option value="yes" <?Php echo $ocallyes; ?>>Yes</option>
                        <option value="no" <?Php echo $ocallno; ?>>No</option>
                      </select>
                    </td>
   	        </tr>
               	    <tr>
    	      <td height="37" align="right" valign="middle"><span class='red'>*</span> Caller Name:</td>
    	      <td align="left" valign="middle">&nbsp;&nbsp;<input type="text" id="cname" name="cname" size="60" value="<?Php echo $userm["cname"]; ?>" /></td>
  	      </tr>
    	    <tr>
    	      <td width="27%" height="37" align="right" valign="middle"><span class='red'>*</span> Caller Phone Number:</td>
    	      <td width="73%" align="left" valign="middle">&nbsp;&nbsp;
              <?Php
			  $fixnum = @fixnum($userm["cphone"]);
			  ?>
              		(<input type="text" id="cphonea" name="cphonea" size="5" value="<?php echo @$fixnum[0] ?>" maxlength="3" onkeyup="moveOnMax(this,'cphoneb')"/>) - <input type="text" id="cphoneb" name="cphoneb" size="10" value="<?php echo @$fixnum[1]; ?>" maxlength="3"  onkeyup="moveOnMax(this,'cphonec')"/> - <input type="text" id="cphonec" name="cphonec" size="20" value="<?php echo @$fixnum[2] ?>" maxlength="4" onkeyup="moveOnMax(this,'email')" />
              </td>
  	      </tr>
    	    <tr>
    	      <td height="10" colspan="2" align="right" valign="middle"></td>
   	        </tr>
    	    <tr>
    	      <td height="37" align="right" valign="middle">Phone Number (Caller Id):</td>
    	      <td align="left" valign="top">&nbsp;&nbsp;
			  <?Php
			  $fixnum = @fixnum($userm["cphonex"]);
			  ?>
              		(<input type="text" id="cphoneba" name="cphoneba" size="5" value="<?php echo @$fixnum[0] ?>" maxlength="3" onkeyup="moveOnMax(this,'cphonebb')"/>) - <input type="text" id="cphonebb" name="cphonebb" size="10" value="<?php echo @$fixnum[1]; ?>" maxlength="3"  onkeyup="moveOnMax(this,'cphonebc')"/> - <input type="text" id="cphonebc" name="cphonebc" size="20" value="<?php echo @$fixnum[2] ?>" maxlength="4" onkeyup="moveOnMax(this,'email')" /><span id="showc_cphonex" name="showc_cphonex" style="visibility:hidden">&nbsp;&nbsp;<a href='javascript:showmodalphone("<?Php echo $_SESSION["woffice"]; ?>")' style='font-style:italic; font-size:14pt;'>Change?</a></span>
              </td>
  	      </tr>
    	    <tr>
    	      <td height="37" align="right" valign="middle"><span class='red'>*</span> Date Called?:</td>
    	      <td align="left" valign="middle">&nbsp;&nbsp;<?php
			  $value="yes";
			  ?><span id="changecdate_div" <?Php echo $style; ?>>
              	<input name="cdate" id="cdate" class="date-pick" readonly="readonly" value="<?Php echo $today; ?>" />
               </span>
               <input type="hidden" id="changecdate" name="changecdate" value="<?php echo $value; ?>"/>
              </td>
  	      </tr>
    	    <tr>
    	      <td height="37" align="right" valign="middle"> <span class='red'>*</span> Office Calling For:</td>
    	      <td align="left" valign="middle">&nbsp;&nbsp;
             	<select id="coffice" name="coffice" onchange="showofficeinfo(this.value)">
                	<option value="na">Select Office</option>
                    <?php
						$query = "select * from rec_office where visible='yes' order by name";
						if($result = mysql_query($query))
						{
							if(($num_rows= mysql_num_rows($result))>0)
							{
								while($rows = mysql_fetch_array($result))
								{
									if(!empty($userm["office"]))
									{
										if($userm["office"]==$rows["id"])
											echo "<option value='".base64_encode($rows["id"])."' selected='selected'>".stripslashes($rows["name"])."</option>";
										else
											echo "<option value='".base64_encode($rows["id"])."'>".stripslashes($rows["name"])."</option>";
									}
									else
									{
										if(!adminlogin_exp())
										{
											if($rows["id"]=='2')
											echo "<option selected='selected' value='".base64_encode($rows["id"])."'>".stripslashes($rows["name"])."</option>";
											else
											echo "<option value='".base64_encode($rows["id"])."'>".stripslashes($rows["name"])."</option>";
										}
										else
											echo "<option value='".base64_encode($rows["id"])."'>".stripslashes($rows["name"])."</option>";
									}
								}
							}
						}
					?>
                </select>
              </td>
  	      </tr>
           <tr>
    	      <td  align="right" valign="middle"></td>
    	      <td align="left" valign="middle"><div id="officeinfo" name="officeinfo"></div></td>
  	      </tr>
    	    <tr>
    	      <td height="37" align="right" valign="middle"><span class='red'>*</span> Recruitment Source:</td>
    	      <td align="left" valign="middle">&nbsp;&nbsp;
              	<select id="csource" name="csource" onchange="showsourcefield(this.value,'<?php echo base64_encode($userm["id"]); ?>')">
                	<option value="na">Select A Source</option>
                    <?php
						$queryso = "select * from rec_source where visible='yes' order by id";
						if($resultso = mysql_query($queryso))
						{
							if(($numrso = mysql_num_rows($resultso))>0)
							{
								while($rowso = mysql_fetch_array($resultso))
								{
									echo "<option value='".$rowso["id"]."'>".$rowso["name"]."</option>";
								}
							}
						}
					?>
                </select>
              </td>
  	      </tr>
          <tr>
    	      <td colspan="2"  align="left" valign="middle">
              <div id="sourceentry" name="sourceentry"></div>
              </td>
   	      </tr>
           <tr>
    	      <td height="37" align="right" valign="middle">Caller Email:</td>
    	      <td align="left" valign="middle">&nbsp;&nbsp;<input type="text" id="email" name="email" size="60" value="<?Php echo $userm["email"]; ?>"/></td>
  	      </tr>
           <tr>
    	      <td height="37" align="right" valign="middle">Caller Address:</td>
    	      <td align="left" valign="middle">&nbsp;&nbsp;<input type="text" id="address" name="address" size="60" value="<?Php echo stripslashes($userm["address"]); ?>"/></td>
  	      </tr>
           <tr>
    	      <td height="37" align="right" valign="middle">Caller City:</td>
    	      <td align="left" valign="middle">&nbsp;&nbsp;<input type="text" id="city" name="city" size="60" value="<?Php echo stripslashes($userm["city"]); ?>"/></td>
  	      </tr>
           <tr>
    	      <td height="37" align="right" valign="middle">Caller State:</td>
    	      <td align="left" valign="middle">&nbsp;&nbsp;<input type="text" id="state" name="state" size="60" value="<?Php echo stripslashes($userm["state"]); ?>"/></td>
  	      </tr>
           <!--<tr>
    	      <td height="37" align="right" valign="middle">Caller Country:</td>
    	      <td align="left" valign="middle">&nbsp;&nbsp;<input type="text" id="country" name="country" size="60" value="<?Php //echo stripslashes($userm["country"]); ?>"/></td>
  	      </tr>-->
           <tr>
    	      <td height="37" align="right" valign="middle">Caller Zip/Postal Code:</td>
    	      <td align="left" valign="middle">&nbsp;&nbsp;<input type="text" id="zip" name="zip" size="60" value="<?Php echo stripslashes($userm["zip"]); ?>"/></td>
  	      </tr>
          <tr>
            <td colspan="2" align="right" valign="middle">
            <span id="showdatediv" name="showdatediv">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="27%" height="32" align="right" valign="middle"> <span class='red'>*</span> Date for interview:</td>
                <td width="73%">&nbsp;
               <input name="idate" id="idate" class="date-pick" readonly="readonly" value="<?php echo $tomorrow; ?>" /></td>
              </tr>
              <tr>
                <td height="35" align="right" valign="middle"><span class='red'>*</span> Time of Interview:</td>
                <td align="left" valign="middle">&nbsp;
                <select id="chour" name="chour">
                        	<option value='na'>Hour</option>
                        	<?php
								for($i=1;$i<13;$i++)
								{
									if($i<10)
										$timer = "0".$i;
									else
										$timer = $i;
									echo "<option value='$i'>$timer</option>";
								}
							?>
                      </select>&nbsp;:&nbsp;
                        <select id="cminute" name="cminute">
                        	<option value='na'>Minutes</option>
                        	<?php
								echo "<option value='00'>00</option><option value='15'>15</option><option value='30'>30</option><option value='45'>45</option>";
							?>
                        </select>
                        <select id="campm" name="campm">
                        	<option value="am">AM</option>
                            <option vlaue="pm">PM</option>
                        </select>
                </td>
              </tr>
            </table>
            </span>
            </td>
            </tr>
          <tr>
            <td height="37" align="right" valign="middle">Email Notifications&nbsp;</td>
            <td align="left" valign="middle">
           	    &nbsp;
                <select id="enotx" name="enotx">
                	<option value="yes">Yes</option>
                    <option value="no">No</option>
                </select>&nbsp;&nbsp;<span class='optional_style'>Send Email Notification if Email is Provided</span>
            </td>
          </tr>
          <tr>
            <td height="37" align="right" valign="middle">SMS Notifications&nbsp;</td>
            <td align="left" valign="middle">
           	    &nbsp;
                <select id="textnotx" name="textnotx">
                	<option value="yes">Yes</option>
                    <option value="no">No</option>
                </select>&nbsp;&nbsp;<span class='optional_style'>Send Text Message Notification if Phone Number is Provided</span>
            </td>
          </tr>
          <tr>
            <td colspan="2" align="left" valign="middle">
            	<div id="awexpw" name="awexpw" style="display:none">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="27%" height="37" align="right" valign="middle">Express Walk-In:&nbsp;</td>
                    <td width="73%" align="left" valign="middle">&nbsp;&nbsp;
                    <select id="expw" name="expw" onchange="showexpoption('expoption',this.value)">
                       <option value="no">no</option>
                       <option value="noy">No, But Take Straight To Entry Page After This</option>
                       <option value="yes">yes</option>
                    </select>
                      </td>
                  </tr>
                </table>
                  <div id="awexpw_sub" name="awexpw_sub" style="display:none">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td height="34" colspan="2" align="center" valign="top">Express Walk-In Option<br/><hr/></td>
                      </tr>
                       <tr>
                        <td width="27%" height="34" align="right" valign="middle">Go Straight To Entry Page?:&nbsp;</td>
                        <td width="73%" height="34" align="left" valign="middle">&nbsp;&nbsp;
                        	<input type="checkbox" id="expw_gos" name="expw_gos" />&nbsp;Yes?
                        </td>
                      </tr>
                      <tr>
                        <td width="27%" height="34" align="right" valign="middle">Choose Interviewer:&nbsp;</td>
                        <td width="73%" height="34" align="left" valign="middle">&nbsp;&nbsp;
                        <select id="expw_int" name="expw_int">
                        <?php
						$qexp = "select * from task_users where status='1' order by id";
						if($rexp = mysql_query($qexp))
						{
							if(($nexp = mysql_num_rows($rexp))>0)
							{
								while($rowexp = mysql_fetch_array($rexp))
								{
									echo "<option value='".base64_encode($rowexp["id"])."'>".stripslashes($rowexp["name"])."</option>";
								}
							}
						}
						?>
                        </select>
                        </td>
                      </tr>
					  <tr>
                        <td width="27%" height="40" align="right" valign="middle">Showed For Interview:&nbsp;</td>
                        <td width="73%" height="40" align="left" valign="middle">&nbsp;&nbsp;
                        <select id="expw_showint" name="expw_showint" onchange="showexpoption('expresult',this.value)">
                        <option value="na">Select One</option>	
                        <option value="yes">Yes</option>		
                        <option value="no">No</option>
                        <option value="cancel">Cancel</option>
                        </select>
                        </td>
                      </tr>
                     </table>
                     <div id="awexpw_sub_result" name="awexpw_sub_result" style="display:none">
                     <table width="100%" border="0" cellspacing="0" cellpadding="0">
                     <tr>
                        <td width="27%" height="31" align="right" valign="middle">Result:&nbsp;</td>
                        <td width="73%" height="31" align="left" valign="middle">&nbsp;&nbsp;
                        <select id="expw_hired" name="expw_hired" onchange="showexpoption('expreason',this.value)">
                        <option value="na">Choose One</option>	
                        <option value="yes">Aproved For Orientation</option>			
                        <option value="no">Not Hired</option>
                        <option value="notint">Not Interested</option>
                        </select>
                        </td>
                      </tr>
                      </table>
                     </div>
                     <div id="awexpw_sub_reason" name="awexpw_sub_reason" style="display:none">
                     <table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td width="27%" height="40" align="right" valign="middle">Choose Reason:&nbsp;</td>
                        <td width="73%" height="40" align="left" valign="middle">&nbsp;&nbsp;
                        <select id="expw_reason" name="expw_reason" onchange="showexpoption('expreason',this.value)">
                        <?php
						$qexp = "select * from rec_exp_reason order by id";
						if($rexp = mysql_query($qexp))
						{
							if(($nexp = mysql_num_rows($rexp))>0)
							{
								while($rowexp = mysql_fetch_array($rexp))
								{
									echo "<option value='".base64_encode($rowexp["id"])."'>".stripslashes($rowexp["name"])."</option>";
								}
							}
						}
						?>
                        </select>
                        </td>
                      </tr> 
                     </table>
                     </div>
                     <div id="awexpw_sub_hired" name="awexpw_sub_hired" style="display:none">
                     <table width="100%" border="0" cellspacing="0" cellpadding="0">
                     <tr>
                        <td height="48" align="right" valign="middle">Orientation Office:&nbsp;</td>
                        <td align="left"  valign="middle">&nbsp;&nbsp;
                        <select id="expw_ooff" name="expw_ooff">
                            <?php
                                $query = "select * from rec_office where visible='yes' order by name";
                                if($result = mysql_query($query))
                                {
                                    if(($num_rows= mysql_num_rows($result))>0)
                                    {
                                        while($rows = mysql_fetch_array($result))
                                        {
                                            if(!empty($userm["office"]))
                                            {
                                                echo "<option value='".base64_encode($rows["id"])."'>".stripslashes($rows["name"])."</option>";
                                            }
                                            else
                                                echo "<option value='".base64_encode($rows["id"])."'>".stripslashes($rows["name"])."</option>";
                                        }
                                    }
                                }
                            ?>
                        </select>
                        </td>
                      </tr>     
                      <tr>
                        <td width="27%" height="48" align="right" valign="top">Set Today's Date For Orientation?:&nbsp;</td>
                        <td width="73%" height="48" align="left" valign="top">&nbsp;&nbsp;<input type="checkbox" id="expw_ordate" name="expw_ordate" onclick="expw_odatec_check(this.checked)" />&nbsp;Yes?
                        </td>
                      </tr>
                      <tr>
                      	<td colspan="2">
                        	<div id="expw_ordatec_div" name="expw_ordatec_div" style="display:none;">
                            	<table width="100%" border="0" cellspacing="0" cellpadding="0">
                    				 <tr>
                      					 <td width="27%" height="37" align="right" valign="top">Set Today's Date For Orientation Completition?:&nbsp;</td>
                       					<td width="73%" align="left" valign="top">&nbsp;&nbsp;<input type="checkbox" id="expw_ordatec" name="expw_ordatec" />&nbsp;Yes?</td>
                                	</tr>
                                </table>
                        	</div>
                        </td>
                      </tr>
                    </table>
                    </div>
                    </div>
                </div>
            </td>
            </tr>
    	    <tr>
    	      <td height="47" colspan="2" align="left" valign="middle">
              <div id="message2" name="message2" class="white" style="color:#F00; text-align:center; padding-right:50px; padding-left:50px">&nbsp;
      </div>
              </td>
   	        </tr>
    	    <tr>
    	      <td colspan="2" align="center" valign="middle">
      <a href="import.php" onmouseover="document.view.src='images/cancelbtn_r.jpg'" onmouseout="document.view.src='images/cancelbtn.jpg'"><img src="images/cancelbtn.jpg"  border="0" alt="View All Users" name="view" /></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              <input id="cbtn" name="cbtn" type="image" src="images/createbtn.jpg">
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