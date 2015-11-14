<?php
session_start();
include "include/config.php";
include "include/function.php";
date_default_timezone_set('America/New_York');
$oid=base64_decode($_REQUEST["u"]);
$oid=$_SESSION['woffice'];
//echo $oid;
//echo $oid["onsip"];
//echo $oid["id"];
$officename="";
$echeck=array();
$today=date("Y-m-d");
$fdate=$today."T00:00:00";
$tdate=$today."T23:00:00";
$uniqp=array();
$query="select * from rec_office where id='".$oid["id"]."'";
if($result = mysql_query($query))
{
	if(($numrows = mysql_num_rows($result))>0)
	{
		$info=mysql_fetch_assoc($result);
		$officename="For ".stripslashes($info["name"]);
		$echeck[]=array("id"=>$info["id"],"email"=>stripslashes($info["onsip"]));
	}
}

/*$restphone[]="17189280522";
$restphone[]="17185346792";
$restphone[]="16469375767";
$restphone[]="13476131428";
$restphone[]="19172976313";
$restphone[]="13472812321";
$restphone[]="17187108434";
$restphone[]="13474761135";
$restphone[]="13476349391";
$restphone[]="13479207842";
$restphone[]="17184145941";
$restphone[]="13476234773";
$restphone[]="13472459695";
$restphone[]="19178040006";
$restphone[]="19177484365";
$restphone[]="19175175366";
$restphone[]="13477441186";
$restphone[]="19175001341";
$restphone[]="19176808498";
$restphone[]="19053667050";
$restphone[]="12129207328";
$restphone[]="13476131328";
$restphone[]="19178817193";*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>View Phone Page</title>
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
<div style="text-align:right; display:none" id="closedivlink">
	<a href='javascript:setphone("")'>Phone Not Avaliable</a>
</div>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<form action="" method="post">
<input type="hidden" id="id" name="id" value="<?php echo $_REQUEST["u"]; ?>" />

  <tr>
    <td align="center" valign="middle">
    	<div id="messagef" name="messagef" class="white" style="text-align:center; padding-right:50px; padding-left:50px">&nbsp;</div>
    </td>
  </tr>
  <tr>
    <td height="34" align="center" valign="middle">
    <div id="wholestat">
    <fieldset>
    	<legend><span style="font-size:15pt; color:#666">Choose Phone Number From List <?Php echo $officename; ?></span></legend>
        <!--<div style="text-align:center;color:#666; font-size:13pt;">Please wait a maximum of 8 seconds to finally confirm that your number is not showing.</div>-->
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr style="background-color:#28629e; color:#FFF">
    <td width="6%" align="center" valign="middle">&nbsp;</td>
    <td width="6%" align="center" valign="middle">&nbsp;</td>
    <td width="30%" align="center" valign="middle">Caller Id</td>
    <td width="12%" align="center" valign="middle">Phone</td>
    <td width="17%" align="center" valign="middle">Date Called</td>
  </tr>
  <tr>
    <td colspan="5">
    	<div id="loadergif" style="text-align:center; display:none"><img src="images/floader.gif" border="0" /></div>
    	<div style="height:350px; overflow:auto" id="phonediv">
    	<table width="100%" border="0" cellspacing="0" cellpadding="0">
        <?php
		$userx=getOnSip(trim($echeck[0]["email"]));
$str = getJunction("cdr","UserId=".$userx["userid"]."&StartDateTime=".$fdate."&EndDateTime=".$tdate."&Limit=100","");
$xpoint = $str->Result[0]->CdrBrowse;
$num_rows = @$str->Result[0]->CdrBrowse->Cdrs[0]->attributes()->Found;
if($num_rows >0)
{
	$count=0;
	$countx=1;
	$totalx=0;
	while($count < $num_rows)
	{
		$xcount=$count;
		$totalx = $countx%2;
		$xpfrom= $xpoint->Cdrs[0]->Cdr[$count]->Source;
		$xpto= $xpoint->Cdrs[0]->Cdr[$count]->Destination;
		$xdisp = $xpoint->Cdrs[0]->Cdr[$count]->Disposition;
		$xlengh = $xpoint->Cdrs[0]->Cdr[$count]->Length;
		$xcallerid = $xpoint->Cdrs[0]->Cdr[$count]->CallerId;
		$xdate = fixdate_comps("onsip_mildate",$xpoint->Cdrs[0]->Cdr[$count]->DateTime);
		if($totalx==0)
			$rowstyle="style='font-size:15pt'";
		else
			$rowstyle="style='background-color:#e1fb51; font-size:15pt'";
		echo "<tr $rowstyle>";
		echo "<td width='6%' align='center' valign='middle'>$countx</td>";
		if(!empty($xpfrom) && $xpfrom !='Restricted')
			$xphone=fixOnSipPhone('',$xpfrom);
		else
			$xphone="";
		echo "<td width='6%' align='center' valign='middle'><input type='radio' id='fnum' name='fnum' value='".$xphone."' onclick='setphone(\"".$xphone."\")'/></td>";
		echo "<td width='30%' align='center' valign='middle'>".checkNA($xcallerid)."</td>";
		$xdate = fixdate_comps("onsip",$xdate);
		echo "<td width='12%' align='center' valign='middle'>".checkNA($xpfrom)."</td>";
		echo "<td width='17%' align='center' valign='middle'>$xdate</td>";
		echo "</tr>";
		$count++;
		$countx++;
	}
}
else
	echo "<tr class='rowstyleno'><td colspan='6' align='center' valign='middle'>Not Found</td></tr>";
?>
    </table>
    </div>
    </td>
  </tr>
  </table>
    </fieldset>
    </div>
        </td>
    </tr>
  <tr>
    <td align="left" valign="middle"><div id="messagex" name="messagex" class="white" style="text-align:center; padding-right:50px; padding-left:50px">&nbsp;</div></td>
  </tr>
  <tr>
    <td align="center" valign="middle">
      <div id="closebtn" style="text-align:center; display:none">
    	<input type="button" value="Cancel" onclick="closemodal()"/>
      </div>
    </td>
  </tr>
 </form>
</table>
</body>
</html>
<?php
	include "include/config.php";
?>