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
</style>
</head>
<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<form action="" method="post">
  <tr>
    <td align="center" valign="middle">
    	<div id="messagef" name="messagef" class="white" style="text-align:center; padding-right:50px; padding-left:50px">&nbsp;</div>
    </td>
  </tr>
  <tr>
    <td height="34" align="center" valign="middle">
    <fieldset>
    	<legend><span style="font-size:15pt; color:#666">Please Choose Your Office</span></legend>
        <div style="text-align:center">
        	<select id="woffice" name="woffice" style="font-size:18pt;" onchange="setWOffice(this.value,'<?Php echo $_REQUEST["targ"]; ?>')">
            	<option value="na">Select Office</option>
            	<?php
					$query = "select * from rec_office where visible='yes' order by id";
					if($result=mysql_query($query))
					{
						if(($num_rows=mysql_num_rows($result))>0)
						{
							while($rows=mysql_fetch_array($result))
							{
								echo "<option value='".base64_encode($rows["id"])."'>".stripslashes($rows["name"])."</option>";
							}
						}
					}
				?>
            </select>
        </div>
    </fieldset>
        </td>
    </tr>
  <tr>
    <td align="left" valign="middle"><div id="message2" name="message2" class="white" style="text-align:center; padding-right:50px; padding-left:50px">&nbsp;</div></td>
  </tr>
  <tr>
    <td height="50" align="center" valign="middle">
    	<!--<input type="button" value="Cancel" onclick="closemodal()"/>-->
    </td>
  </tr>
 </form>
</table>
</body>
</html>
<?php
	include "include/config.php";
?>