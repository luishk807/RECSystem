<?Php
session_start();
include "include/config.php";
include "include/function.php";
$id = base64_decode($_REQUEST["id"]);
$query = "select * from rec_entries where id='".$id."'";
if($result = mysql_query($query))
{
	if(($num_rows = mysql_num_rows($result))>0)
	{
		$userm = mysql_fetch_assoc($result);
		$host = getHost();
		$img = "images/aimg/".$userm["img"];
		$name = "<b>".stripslashes($userm["cname"])."</b>";
		$code = "<b>".$userm["ccode"]."</b>";
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Print Id</title>
<style>
@font-face {
   font-family: 'bebas';
   src: url(../fonts/BEBAS.TTF);
}
</style>
<script type="text/javascript" language="javascript">
function printer()
{
 window.print();
}
</script>
</head>

<body onload="printer()">
<div style="width:600px; height:316px; background:url(images/idsample.jpg)">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="5%" rowspan="2">&nbsp;</td>
      <td height="104" colspan="2">&nbsp;</td>
      <td width="25%" rowspan="2">&nbsp;</td>
    </tr>
    <tr>
      <td width="202" height="185" align="center" valign="middle"><img src="<?php echo $img; ?>" width="200" height="180" /></td>
      <td width="205" align="left" valign="top" ><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td height="95" align="center" valign="middle" style="font-size:18pt;font-family:'bebas'"><?php echo $name; ?></td>
        </tr>
        <tr>
          <td height="83" align="center" valign="middle" style="font-size:16pt; font-family:'bebas'">Energy Consultant<br/><span style="font-size:14pt">Agent Code:&nbsp;<?php echo $code; ?></span></td>
        </tr>
      </table></td>
    </tr>
  </table>

</div>
</body>
</html>
<?Php
include "include/unconfig.php";
?>