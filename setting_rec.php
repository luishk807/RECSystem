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
$id = base64_decode($_REQUEST["id"]);
$checkcomentry="no";
$showcomentry=false;
if(empty($id))
{
	$_SESSION["recresult"]="ERROR: Invalid Entry";
	header("location:view.php");
	exit;
}
else
{
	$query = "select * from rec_entries where id='".$id."'";
	if($result = mysql_query($query))
	{
		if(($num_rows = mysql_num_rows($result))>0)
		{
			$userm= mysql_fetch_assoc($result);
			if($userm["catid"] !='1')
			{
				$showcomentry=true;
				$checkcomentry='yes';
			}
		}
		else
		{
			$_SESSION["recresult"]="ERROR: Invalid Entry";
			header("location:view.php");
			exit;
		}
	}
	else
	{
		$_SESSION["recresult"]="ERROR: Invalid Entry";
		header("location:view.php");
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
</script>
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
            	Entry Information
            </div>
        </div>
        <div id="body_middle_middle">
        	<div id="body_content">
            <div style="text-align:center; font-size:18pt">Hello <b><u><?php echo $user["username"]; ?></u></b>, Edit/Delete An Entry<br/><br/></div>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <form action="save.php" method="post" onsubmit="return checkFieldc()">
            <input type="hidden" id="task" name="task" value="saverec"/>
            <input type="hidden" id="id" name="id" value="<?Php echo $_REQUEST["id"];  ?>" />
            <input type="hidden" id="cphone" name="cphone" value="" />
            <input type="hidden" id="cphonex" name="cphonex" value="" />
            <input type="hidden" id="ctime" name="ctime" value="" />
            <input type="hidden" id="csource_cont" name="csource_cont" value="" />
            <input type="hidden" id="checkdatediv" name="checkdatediv" value="yes" />
             <input type="hidden" id="checkcomentry" name="checkcomentry" value="<?php echo $checkcomentry; ?>" />
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
			if(!empty($userm["img"]))
			{
				?>
             <tr>
               	<td height="20" colspan="2" align="center" valign="middle">
                <div style="background-color:#06457b; color:#FFF;"><a style='color:#FFF' href='javascript:printthis()'>ID Template is Avaliable, Click To View</a></div>
                </td>
   	        </tr>
            <?php
			}
			?>
            <?php
			if($showcomentry)
			{
				?>
            <tr>
               	<td height="37" align="right" valign="middle">Recruiter Company:</td>
               	 <td align="left" valign="middle">&nbsp;&nbsp;
                 <select id="comentry" name="comentry">
                	<option value="na">Select Recruiter Company</option>
                    <?php
						$query = "select * from task_category order by name";
						if($result = mysql_query($query))
						{
							if(($num_rows= mysql_num_rows($result))>0)
							{
								while($rows = mysql_fetch_array($result))
								{
									if($userm["catid"]==$rows["id"])
										echo "<option value='".base64_encode($rows["id"])."' selected='selected'>".stripslashes($rows["name"])."</option>";
									else
										echo "<option value='".base64_encode($rows["id"])."'>".stripslashes($rows["name"])."</option>";
								}
							}
						}
					?>
                </select>
                 </td>
   	        </tr>
            <?php
			}
			?>
            <!--<input type="hidden" id="ocall" name="ocall" value="yes" />-->
            <tr>
               <td height="37" align="right" valign="middle"><span class='red'>*</span> Is This From A Phone Call?</td>
               <td align="left" valign="middle">&nbsp;&nbsp;
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
              		(<input type="text" id="cphonea" name="cphonea" size="5" value="<?php echo @$fixnum[0] ?>" maxlength="3" onkeyup="moveOnMax(this,'cphoneb')"/>) - <input type="text" id="cphoneb" name="cphoneb" size="10" value="<?php echo @$fixnum[1]; ?>" maxlength="3" onkeyup="moveOnMax(this,'cphonec')"/> - <input type="text" id="cphonec" name="cphonec" size="20" value="<?php echo @$fixnum[2] ?>" maxlength="4" onkeyup="moveOnMax(this,'email')" />
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
              		(<input type="text" id="cphoneba" name="cphoneba" size="5" value="<?php echo @$fixnum[0] ?>" maxlength="3" onkeyup="moveOnMax(this,'cphonebb')"/>) - <input type="text" id="cphonebb" name="cphonebb" size="10" value="<?php echo @$fixnum[1]; ?>" maxlength="3" onkeyup="moveOnMax(this,'cphonebc')"/> - <input type="text" id="cphonebc" name="cphonebc" size="20" value="<?php echo @$fixnum[2] ?>" maxlength="6" onkeyup="moveOnMax(this,'email')"/>
              </td>
  	      </tr>
    	    <tr>
    	      <td height="37" align="right" valign="middle"><span class='red'>*</span> Date Called?:</td>
    	      <td align="left" valign="middle">&nbsp;&nbsp;<?php
			  if(empty($userm["cdate"]))
			  {
				  $value="yes";
			  }
			  else
			  {
				  echo fixdate_comps('d',$userm["cdate"])." - Change?&nbsp;&nbsp;<input type='checkbox' id='cdate_check' name='cdate_check' onclick='allowchangecdate(\"cdate_check\",\"changecdate_div\",\"changecdate\")'>&nbsp;Yes &nbsp;";
				  $style="style='display:none'";
				  $value="no";
			  }
			  ?>
              </td>
  	      </tr>
          <tr>
    	      <td  align="right" valign="middle"></td>
    	      <td align="left" valign="middle">
              <span id="changecdate_div" <?Php echo $style; ?>>&nbsp;&nbsp;<input name="cdate" id="cdate" class="date-pick" readonly="readonly"/>
                    <br/>
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
						$query = "select * from rec_office order by name";
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
										echo "<option value='".base64_encode($rows["id"])."'>".stripslashes($rows["name"])."</option>";
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
    	      <td height="37" align="right" valign="middle"><span class='red'>*</span> Recruiter Source:</td>
    	      <td align="left" valign="middle">
              	&nbsp;&nbsp;
              	<select id="csource" name="csource" onchange="showsourcefield_setting(this.value,'<?php echo base64_encode($userm["id"]); ?>')">
                	<option value="na">Select A Source</option>
                    <?php
						$queryso = "select * from rec_source where visible='yes' order by id";
						if($resultso = mysql_query($queryso))
						{
							if(($numrso = mysql_num_rows($resultso))>0)
							{
								while($rowso = mysql_fetch_array($resultso))
								{
									if($userm["csource"]==$rowso["id"])
										echo "<option value='".$rowso["id"]."' selected='selected'>".$rowso["name"]."</option>";
									else
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
              <div id="sourceentry" name="sourceentry">
              		<?Php
						$value=array();
						if(!empty($userm["csource_title"]))
						{
							$xvalue = explode(" || ",$userm["csource_title"]);
							if(sizeof($xvalue)>0)
							{
								for($x=0;$x<sizeof($xvalue);$x++)
								{
									$value[]=trim(stripslashes($xvalue[$x]));
								}
							}
							else
								$value[]=trim(stripslashes($userm["csource_title"]));
						}
						$queryf = "select * from rec_source where id='".$userm["csource"]."'";
						if($resultf = mysql_query($queryf))
						{
							if(($numrf = mysql_num_rows($resultf))>0)
							{
								$rowsf = mysql_fetch_assoc($resultf);
								if(!empty($rowsf["entry"]))
								{
									echo "<table width='100%' border='0' cellspacing='0' cellpadding='0'>";
									$fields = explode(" || ",$rowsf["entry"]);
									if(sizeof($fields)>0)
									{
										for($i=0; $i<sizeof($fields);$i++)
										{
											echo "<tr><td width='27%' height='37' align='right' valign='middle'><span class='red'>*</span> ".$fields[$i].":</td><td width='73%' align='left' valign='middle'>&nbsp;&nbsp;<input type='text' id='csource_title".$i."' name='csource_title".$i."' size='60' value='";
											if($userm["csource"]==$rowsf["id"])
												echo $value[$i];
											echo "' /></td></tr>";
										}
									}
									else
									{
										echo "<tr><td width='27%' height='37' align='right' valign='middle'><span class='red'>*</span> ".$rowsf["entry"].":</td><td width='73%' align='left' valign='middle'>&nbsp;&nbsp;<input type='text' id='csource_title0' name='csource_title0' size='60' value='";
										if($userm["csource"]==$rowsf["id"])
												echo $value[0];
										echo "' /></td></tr>";
									}
									echo "</table>";
								}
							}
						}
					?>
              </div>
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
                <td width="73%" align="left">
               		&nbsp;&nbsp;
				<?php
				  if(empty($userm["idate"]))
					  $value="yes";
				  else
				  {
					  echo fixdate_comps('d',$userm["idate"])." - Change?&nbsp;&nbsp;<input type='checkbox' id='idate_check' name='idate_check' onclick='allowchangecdate(\"idate_check\",\"changeidate_div\",\"changeidate\")'>&nbsp;Yes &nbsp;";
					  $style="style='display:none'";
					  $value="no";
				  }
				  ?>
               </td>
              </tr>
               <tr>
                  <td  align="right" valign="middle"></td>
                  <td align="left" valign="middle">
                  <span id="changeidate_div" <?Php echo $style; ?>>
                  &nbsp;&nbsp;
                   <input name="idate" id="idate" class="date-pick" readonly="readonly" />
                    </span>
                    <input type="hidden" id="changeidate" name="changeidate" value="<?php echo $value; ?>"/>
                  </td>
              </tr>
              <tr>
                <td height="35" align="right" valign="middle"><span class='red'>*</span> Time of Interview:</td>
                <td align="left" valign="middle">&nbsp;
                <?php
					$ihour="";
					$imin ="";
					$iampm="";
					$ihours = @fixnormhour($userm["itime"]);
					if(!empty($ihours))
					{
						$ihoursx = explode(":",$ihours);
						if(sizeof($ihoursx)>0)
						{
							$ihour = $ihoursx[0];
							$ihourx = explode(" ",$ihoursx[1]);
							if(sizeof($ihourx)>0)
							{
								$imin = $ihourx[0];
								$iampm = trim($ihourx[1]);
							}
						}
					}
				?>
            	<select id="chour" name="chour">
                        	<option value='na'>Hour</option>
                        	<?php
								for($i=1;$i<13;$i++)
								{
									$selected=false;
									if($ihour==$i)
										$selected=true;
									if($i<10)
										$timer = "0".$i;
									else
										$timer = $i;
									if($selected)
										echo "<option value='$i' selected='selected'>$timer</option>";
									else
										echo "<option value='$i'>$timer</option>";
								}
							?>
                        </select>&nbsp;:&nbsp;
                        <select id="cminute" name="cminute">
                        	<option value='na'>Minutes</option>
                        	<?php
								if($imin=="00")
									$min0="selected='selected'";
								else if($imin=="15")
									$min15="selected='selected'";
								else if($imin=="30")
									$min30="selected='selected'";
								else if($imin=="45")
									$min45="selected='selected'";
								echo "<option value='00' $min0>00</option>";
								echo "<option value='15' $min15>15</option>";
								echo "<option value='30' $min30>30</option>";
								echo "<option value='45' $min45>45</option>";
							?>
                        </select>
                        <select id="campm" name="campm">
                        	<?php
								if(!empty($iampm))
								{
									if($iampm=="am")
										$amsel = "selected='selected'";
									else if($iampm=="pm")
										$pmsel = "selected='selected'";
								}
							?>
                        	<option value="am" <?php echo $amsel; ?>>AM</option>
                            <option vlaue="pm" <?php echo $pmsel; ?>>PM</option>
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
                <?php
				if($userm["enotx"]=="yes")
					$enotx_yes="selected='selected'";
				else
					$enotx_no="selected='selected'";
				?>
                <select id="enotx" name="enotx">
                	<option value="yes" <?php echo $enotx_yes; ?>>Yes</option>
                    <option value="no" <?php echo $enotx_no; ?>>No</option>
                </select>&nbsp;&nbsp;<span class='optional_style'>Send Email Notification if Email is Provided</span>
            </td>
          </tr>
          <tr>
            <td height="37" align="right" valign="middle">SMS Notifications&nbsp;</td>
            <td align="left" valign="middle">
           	    &nbsp;
                <?php
				if($userm["textnotx"]=="yes")
					$textnotx_yes="selected='selected'";
				else
					$textnotx_no="selected='selected'";
				?>
                <select id="textnotx" name="textnotx">
                	<option value="yes" <?php echo $textnotx_yes; ?>>Yes</option>
                    <option value="no" <?php echo $textnotx_no; ?>>No</option>
                </select>&nbsp;&nbsp;<span class='optional_style'>Send Text Message Notification if Phone Number is Provided</span>
            </td>
          </tr>
          <?Php
		  if(!empty($userm["createdby"]))
		  {
			  ?>
          <tr>
            <td height="37" align="right" valign="middle">Created By:</td>
            <td align="left" valign="middle">&nbsp;&nbsp;<span style="font-style:italic;color:#666"><?php echo getName($userm["createdby"])." On: ".fixdate_comps("d",$userm["date"]); ?></span></td>
          </tr>
          <?php
		  }
		  if(!empty($userm["updatedby_date"]) && !empty($userm["updatedby"]))
		  {
			  ?>
          <tr>
            <td height="37" align="right" valign="middle">Last Info Update By:</td>
            <td align="left" valign="middle">&nbsp;&nbsp;<span style="font-style:italic;color:#666"><?php echo getName($userm["updatedby"]); ?></span></td>
          </tr>
          <tr>
            <td height="37" align="right" valign="middle">Last Info Update Date:</td>
            <td align="left" valign="middle">&nbsp;&nbsp;<span style="font-style:italic;color:#666"><?php echo fixdate_comps("d",$userm["updatedby_date"]); ?></span></td>
          </tr>
         <?php
		  }
		  ?>
          <tr>
    	      <td height="47" colspan="2" align="left" valign="middle">
              <div id="message2" name="message2" class="white" style="color:#F00;text-align:center; padding-right:50px; padding-left:50px">
        &nbsp;
      </div>
              </td>
   	        </tr>
    	    <tr>
    	      <td colspan="2" align="center" valign="middle">
      <a href="setrec.php?id=<?php echo $_REQUEST["id"]; ?>" onmouseover="document.view.src='images/cancelbtn_r.jpg'" onmouseout="document.view.src='images/cancelbtn.jpg'"><img src="images/cancelbtn.jpg"  border="0" alt="View All Users" name="view" /></a>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              <a href="Javascript:deletetask('entry','<?php echo $_REQUEST["id"]; ?>')" onmouseover="document.delete.src='images/delete_r.jpg'" onmouseout="document.delete.src='images/delete.jpg'"><img src="images/delete.jpg"  border="0" alt="Delete This User" name="delete" /></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              <input type="image"  src="images/savebtn.jpg" onmouseover="javascript:this.src='images/savebtn_r.jpg'" onmouseout="javascript:this.src='images/savebtn.jpg'">
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