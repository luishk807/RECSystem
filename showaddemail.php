<?php
session_start();
include "include/config.php";
include "include/function.php";
$officename="";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Select Office Of Work</title>
<script type="text/javascript" language="javascript" src="js/script.js"></script>
<style>
.rowstyleno{
	font-size:14pt;
	background-color:#900;
	color:#FFF;
}
.linkstats{
	color:#FFF;
}
.linkstats_b{
	color:#000;
}
.memail_title{
	font-size:18pt;
}
</style>
</head>
<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<form action="" name="memail_form_add" method="post">
  <tr>
    <td height="34" align="center" valign="middle">
    <fieldset>
    	<legend><span style="font-size:15pt; color:#666">Please Fill Up The Form</span></legend>
        <div style="text-align:center">
        	<span class='memail_title'>Email Address</span><br/>
            <input type="text" size="60" id="memail" name="memail" />
            <br/><br/><br/>
            <span class='memail_title'>Opt-Out?</span><br/>
        	<select id="moptout" name="moptout" style="font-size:14pt;">
            	<option value="no" selected="selected">No, Send Information To This Email</option>
            	<option value="yes">Yes, Don't Send Information To This Email</option>
            </select>
        </div>
    </fieldset>
        </td>
    </tr>
  <tr>
    <td align="left" valign="middle">&nbsp;</td>
  </tr>
    <tr>
    <td align="center" valign="middle">
    	<div id="messagef" name="messagef" class="white" style="text-align:center; padding-right:50px; padding-left:50px">&nbsp;</div>
    </td>
  </tr>
  <tr>
    <td height="50" align="center" valign="middle">
    	<input type="button" value="Cancel" onclick="closemodal()"/>&nbsp;&nbsp;&nbsp; <input type="button" value="Add Email" onclick="addMEmail()"/>
    </td>
  </tr>
 </form>
</table>
</body>
</html>
<?php
	include "include/config.php";
?>