<?php
session_start();
include "include/config.php";
include "include/function.php";
$id = base64_decode($_REQUEST["id"]);
$idx = $id;
if(!empty($id))
{
	$query = "select * from rec_entries where id='".$id."'";
	if($result = mysql_query($query))
	{
		if(($num_rows = mysql_num_rows($result))>0)
		{
			$userm = mysql_fetch_assoc($result);
			$cphone = stripslashes($userm["phone"]);
		}
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Send Text Message Page</title>
<script type="text/javascript" language="javascript" src="js/script.js"></script>
</head>

<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<form action="" method="post">
<input type="hidden" id="id" name="id" value="<?php echo $idx; ?>" />
<input type="hidden" id="cphone" name="cphone" value="" />
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
    <td height="26" align="center">Send A Text Message To: <u><?php echo stripslashes($userm["cname"]); ?>&nbsp;&nbsp;<?php echo $updatedby; ?></u></td>
  </tr>
  <tr>
    <td align="center" valign="middle">
    	<div id="messagef" name="messagef" class="white" style="text-align:center; padding-right:50px; padding-left:50px">&nbsp;</div>
    </td>
  </tr>
  <tr>
    <td height="34" align="center" valign="middle">
    <fieldset>
    	<legend><span style="font-size:15pt; color:#666">User Information</span></legend>
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
        </td>
    </tr>
  <tr>
    <td height="37" align="left" valign="middle">
    <br/>
        <span id="foldiv">
         <fieldset>
        <legend><span style="font-size:15pt; color:#666">Phone Number</span></legend>
             <?Php
              $fixnum = @fixnum($userm["cphone"]);
              ?>
              		(<input type="text" id="cphonea" name="cphonea" size="5" value="<?php echo @$fixnum[0] ?>" maxlength="3"/>) - <input type="text" id="cphoneb" name="cphoneb" size="10" value="<?php echo @$fixnum[1]; ?>" maxlength="3" /> - <input type="text" id="cphonec" name="cphonec" size="20" value="<?php echo @$fixnum[2] ?>" maxlength="6" />
           </fieldset>
        <fieldset>
        <legend><span style="font-size:15pt; color:#666">Send Text Message (only 160 characters allowed)</span></legend>
             <textarea id="mmessage" name="mmessage" cols="60" rows="5" maxlength="160"></textarea>
           </fieldset>
        </span>
    </td>
  </tr>
  <tr>
    <td align="left" valign="middle"><div id="message2" name="message2" class="white" style="text-align:center; padding-right:50px; padding-left:50px">&nbsp;</div></td>
  </tr>
  <tr>
    <td height="50" align="center" valign="middle">
    	<input type="button" value="Send Text Message" onclick="checkFieldl()"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="Cancel" onclick="closemodal()"/>
    </td>
  </tr>
 </form>
</table>

</body>
</html>
<?php
	include "include/config.php";
?>