<?php
session_start();
include "include/config.php";
include "include/function.php";
$id = base64_decode($_REQUEST["id"]);
$idx = $id;
$valid=true;
if(!empty($id))
{
	$query = "select * from rec_entries where id='".$id."'";
	if($result = mysql_query($query))
	{
		if(($num_rows = mysql_num_rows($result))>0)
		{
			$info = mysql_fetch_assoc($result);
			if(!empty($info["folupdated_by"]) && !empty($info["folupdated_date"]))
				$updateby = getName($info["folupdated_by"])." on: ".fixdate_comps("d",$info["folupdated_date"]);
			if($info["folstatus"]=="3")
			{
				$followdiv = "";
				$checkprocess="3";
				$varname = "Completed";
				$_SESSION["recresult"]="This Follow Up is Already Completed";
				header('location:view.php');
				exit;
			}
			else if($info["folstatus"]=="2")
			{
				$varname = "Follow Up";
				$followdiv = "";
				$checkprocess="2";
			}
			else
			{
				$followdiv = "style='display:none'";
				$checkprocess="1";
			}
		}
		else
			$valid=false;
	}
	else
		$valid=false;
}
else
	$valid=false;
if(!$valid)
{
	$followdiv = "style='display:none'";
	$compdiv = "style='display:none'";
	$checkprocess="1";
	//$_SESSION["recresult"]="Invalid Entry";
	//header("location:view.php");
	//exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Follow Up Page</title>
<script type="text/javascript" language="javascript" src="js/script.js"></script>
</head>

<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<form action="" method="post">
<input type="hidden" id="id" name="id" value="<?php echo $idx; ?>" />
<input type="hidden" id="checkprocess" name="checkprocess" value="<?php echo $checkprocess; ?>" />
<input type="hidden" id="varname" name="varname" value="<?Php echo $varname; ?>" />
<input type="hidden" id="followupnote" name="followupnote" value="<?Php echo nl2br(stripslashes($info["folnote"])); ?>" />
<input type="hidden" id="fdate" name="fdate" value=""/>
<input type="hidden" id="checkcome" name="checkcome" value="no" />
<?Php
if(!empty($info["foldate"]))
{
	?>
  <tr>
    <td align="center"><span style="font-size:20pt; text-decoration:underline; color:#F00">Last Follow Up: <?php echo fixdate_comps("all",$info["foldate"]); ?></span>&nbsp;&nbsp;<span style="font-size:14pt; font-style:italic; color:#999">(by: <?php echo getName($info["folupdated_by"]); ?>)</span></td>
  </tr>
<?php
}
?>
  <tr>
    <td height="26" align="center">Follow Up Calls For <u><?php echo stripslashes($info["cname"]); ?>&nbsp;&nbsp;<?php echo $updatedby; ?></u></td>
  </tr>
  <tr>
    <td align="center" valign="middle">
    	<div id="messagef" name="messagef" class="white" style="text-align:center; padding-right:50px; padding-left:50px">&nbsp;</div>
    </td>
  </tr>
  <tr>
    <td height="34" align="center" valign="middle">
    <fieldset>
    	<legend><span style="font-size:15pt; color:#666">Call Process</span></legend>
    	<select id="followup" name="followup" onchange="switchfollow(this.value)">
        	<option value="na">Select One</option>
            <?php
				$query = "select * from rec_followup order by id desc";
				if($result = mysql_query($query))
				{
					if(($num_rows = mysql_num_rows($result))>0)
					{
						while($rows = mysql_fetch_array($result))
						{
							if($info["folstatus"]==$rows["id"])
							echo "<option value='".$rows["id"]."' selected='selected'>".stripslashes($rows["name"])."</option>";
							else
							echo "<option value='".$rows["id"]."'>".stripslashes($rows["name"])."</option>";
						}
					}
				}
			?> 
        </select>    
        </fieldset>
        </td>
    </tr>
  <tr>
    <td height="37" align="left" valign="middle">
    <br/>
        <span id="foldiv" <?php echo $followdiv; ?>>
        <fieldset>
        <legend><span style="font-size:15pt; color:#666">Follow Up Procedures</span></legend>
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td height="33" align="left">&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td width="19%" height="33" align="left"><span id="varname_date"><?php echo $varname; ?> Date: </span></td>
                <td width="81%">&nbsp;
                	<?php
					//date_default_timezone_set('America/New York');
					$year = date("Y");
					$nomonth = date("m");
					$noday =date("d");
					echo "<select id='fmonth' name='fmonth'>";
					for ($i = 1; $i <= 12; $i ++) 
					{
						if($i<10)
							$monthnum="0".$i;
						else
							$monthnum = $i;
						$monthnum =$i;
						$monthname= date("M",mktime (0,0,0,$i,1));
						if($monthname==$nomonth)
						echo "<option value='".$monthnum."' selected='selected'>$monthname</option>";
						else
						echo "<option value='".$monthnum."'>$monthname</option>";
					}
					echo "</select>&nbsp;/";
					echo "<select id='fday' name='fday'>";
					for ($i = 1; $i <= 31; $i ++) 
					{
						if($i<10)
							$sday = "0".$i;
						else
							$sday=$i;
						if($i==$noday)
						echo "<option value='".$sday."' selected='selected'>$sday</option>";
						else
						echo "<option value='".$sday."'>$sday</option>";
					}
					echo "</select>";
					//$num = cal_days_in_month(CAL_GREGORIAN, $monthsel, $year);
					?>
                   &nbsp;/<?php echo $year; ?>
                   <input type="hidden" id="fyear" name="fyear" value="<?php echo $year; ?>" />
                      &nbsp;&nbsp;Time:&nbsp;
          		<select id="fhour" name="fhour">
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
          <select id="fminute" name="fminute">
                        	<option value='na'>Minutes</option>
                        	<?php
								for($i=0;$i<60;$i++)
								{
									if($i<10)
										$timer = "0".$i;
									else
										$timer = $i;
									echo "<option value='$timer'>$timer</option>";
								}
							?>
                        </select>
          <select id="fampm" name="fampm">
                        	<option value='na'>Select Type</option>
                        	<option value="am">AM</option>
                            <option vlaue="pm">PM</option>
                        </select>
                </td>
              </tr>
              <td colspan="2">
               <div id="showcome" name="showcome" style="display:none">
              	<table width="100%" border="0" cellspacing="0" cellpadding="0">
                   <tr>
                    <td height="33" width="19%" align="left" valign="middle">Attending?</td>
                    <td align="left" width="81%" valign="middle">&nbsp;
                        <select id="ccome" name="ccome">
                        	<option value="na">Select Answer</option>
                            <option value="yes">Yes</option>
                            <option value="no">No</option>
                        </select>
                    </td>
                  </tr>
                </table>
                </div>
              </td>
              <tr>
                <td height="93" align="left" valign="top"><span id="varname_note"><?php echo $varname; ?> Note:</span></td>
                <td align="left" valign="top">&nbsp;<textarea id="fnote" name="fnote" rows="5" cols="40"><?php if($info["folstatus"]=="2") echo nl2br(stripslashes($info["folnote"])); ?></textarea></td>
              </tr>
            </table>
           </fieldset>
        </span>
    </td>
  </tr>
  <tr>
    <td align="left" valign="middle"><div id="message2" name="message2" class="white" style="text-align:center; padding-right:50px; padding-left:50px">&nbsp;</div></td>
  </tr>
  <tr>
    <td height="50" align="center" valign="middle">
    	<input type="button" value="Save Changes" onclick="checkFieldj()"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="Cancel" onclick="closemodal()"/>
    </td>
  </tr>
 </form>
</table>

</body>
</html>
<?php
	include "include/config.php";
?>