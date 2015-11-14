<?php
session_start();
include "include/config.php";
include "include/function.php";
$xstatus = base64_decode($_REQUEST["qu"]);
$dateb = base64_decode($_REQUEST["dateb"]);
$oid=base64_decode($_REQUEST["oid"]);
$officename="";
$recphone=array();
$famphone=array();
$ascdesc = $_REQUEST["ascdesc"];
if($ascdesc=="desc")
	$ascdesc="asc";
else
	$ascdesc="desc";
$query="select * from rec_office where id='".$oid."'";
if($result = mysql_query($query))
{
	if(($numrows = mysql_num_rows($result))>0)
	{
		$info=mysql_fetch_assoc($result);
		$officename="For ".stripslashes($info["name"]);
	}
}
$queryx ="select * from rec_phones where office='".$oid."' $dateb";
if($resultx= mysql_query($queryx))
{
	if(($numrows = mysql_num_rows($resultx))>0)
	{
		while($rox=mysql_fetch_array($resultx))
		{
			$recphone[]=array('id'=>$rox["id"],'phone'=>fixOnSipPhone('family',trim($rox["tphone"])),'callerid'=>$rox["caller"],'date'=>$rox["date"]);
		}
	}
}
$t=$_REQUEST["t"];
$farray = array();
$farrayv = array();
$usefam=false;
if($t=="cdate")
{
	$farray[]="cdate";
	$farray[]="csource";
	$farray[]="csource";
	$farrayv[]="Date Called";
	$farrayv[]="Rec Source";
	$farrayv[]="Rec Source Info";
	$usefam=true;
}
else if($t=="inpend")
{
	$farray[]="idate";
	$farray[]="itime";
	$farray[]="folstatus";
	$farrayv[]="Int Date";
	$farrayv[]="Int Time";
	$farrayv[]="Follow Up";
}
else if($t=="thired")
{
	$farray[]="idate";
	$farray[]="itime";
	$farray[]="status";
	$farrayv[]="Int Date";
	$farrayv[]="Int Time";
	$farrayv[]="Status";
}
else if($t=="oset")
{
	$farray[]="orientation";
	$farray[]="orientation_show";
	$farray[]="status";
	$farrayv[]="Date of Orientation";
	$farrayv[]="Date Show";
	$farrayv[]="Status";
}
else if($t=="ocomp")
{
	$farray[]="orientation";
	$farray[]="orientation_comp";
	$farray[]="status";
	$farrayv[]="Date of Orientation";
	$farrayv[]="Date Completed";
	$farrayv[]="Status";
}
else if($t=="nagent")
{
	$farray[]="idate";
	$farray[]="interview_note";
	$farray[]="status";
	$farrayv[]="Int Date";
	$farrayv[]="Note";
	$farrayv[]="Status";
}
else if($t=="nhired")
{
	$farray[]="idate";
	$farray[]="interview_note";
	$farray[]="status";
	$farrayv[]="Int Date";
	$farrayv[]="Note";
	$farrayv[]="Status";
}
else if($t=="nint")
{
	$farray[]="orientation";
	$farray[]="interview_note";
	$farray[]="status";
	$farrayv[]="Orientation Date";
	$farrayv[]="Note";
	$farrayv[]="Status";
}
else if($t=="nshow")
{
	$farray[]="idate";
	$farray[]="int_show_info";
	$farray[]="folstatus";
	$farrayv[]="int Date";
	$farrayv[]="Note";
	$farrayv[]="Follow Up";
}
if($xstatus !='all' &&  $xstatus !='noagent' && $xstatus !='hired' && $xstatus !='set')
{
	$qstatus=" status='".$xstatus."' and ";
}
else
{
	if($xstatus=="noagent")
		$qstatus="(status ='2' or status='9' or status='8') and ";
	else if($xstatus=='hired')
		$qstatus=" hired='yes' and ";
	else
		$qstatus="";
}
if(sizeof($recphone)>0)
{
	for($i=0;$i<sizeof($recphone);$i++)
	{
		$query = "select * from rec_entries where $qstatus (cphone='".$recphone[$i]["phone"]."' or cphonex='".$recphone[$i]["phone"]."')";
		if($result=mysql_query($query))
		{
			if(($num_rows=mysql_num_rows($result))>0)
			{
				$rows=mysql_fetch_assoc($result);
				$famphone[]=array('phoneid'=>$recphone[$i]["id"],'id'=>$rows["id"],"name"=>stripslashes($rows["cname"]),"status"=>$rows["status"],'hired'=>$rows["hired"],'phone'=>$rows["cphone"],'phonex'=>$rows["cphonex"]);
			}
		}
	}
}
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
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<form action="" method="post">
<input type="hidden" id="id" name="id" value="<?php echo $idx; ?>" />
<input type="hidden" id="qu" name="qu" value="<?php echo $_REQUEST["qu"]; ?>" />
<input type="hidden" id="dateb" name="dateb" value="<?php echo $_REQUEST["dateb"]; ?>" />

  <tr>
    <td align="center" valign="middle">
    	<div id="messagef" name="messagef" class="white" style="text-align:center; padding-right:50px; padding-left:50px">&nbsp;</div>
    </td>
  </tr>
  <tr>
    <td height="34" align="center" valign="middle">
    <div id="wholestat">
    <fieldset>
    	<legend><span style="font-size:15pt; color:#666">Complete Detail List <?Php echo $officename; ?></span></legend>
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr style="background-color:#28629e; color:#FFF">
    <td width="6%" align="center" valign="middle">&nbsp;</td>
    <td width="6%" align="center" valign="middle">&nbsp;</td>
    <td width="30%" align="center" valign="middle">Caller Id</td>
    <td width="12%" align="center" valign="middle">Phone</td>
    <td width="17%" align="center" valign="middle">Date Called</td>
    <td width="29%" align="center" valign="middle">Match Found</td>
  </tr>
  <tr>
    <td colspan="6">
    	<div style="height:350px; overflow:auto" id="phonediv">
    	<table width="100%" border="0" cellspacing="0" cellpadding="0">
        <?php
		if($usefam)
		{
		if(sizeof($recphone)>0)
		{
			$countx=1;
			$totalx=0;
			for($i=0;$i<sizeof($recphone);$i++)
			{
				$totalx = $countx%2;
				if($totalx==0)
					 $rowstyle="style='font-size:15pt'";
				else
					$rowstyle="style='background-color:#e1fb51; font-size:15pt'";
				echo "<tr $rowstyle>";
				echo "<td width='6%' align='center' valign='middle'>$countx</td>";
				echo "<td width='6%' align='center' valign='middle'><a href='javascript:delphone(\"".base64_encode($recphone[$i]["id"])."\")'>Delete</a></td>";
				echo "<td width='30%' align='center' valign='middle'>".checkNA($recphone[$i]["callerid"])."</td>";
				$xdate = fixdate_comps("invdate_s",$recphone[$i]["date"]);
				echo "<td width='12%' align='center' valign='middle'>".checkNA($recphone[$i]["phone"])."</td>";
				echo "<td width='17%' align='center' valign='middle'>$xdate</td>";
				$linkrec="";
				$foundx=false;
				if(sizeof($famphone)>0)
				{
					for($x=0;$x<sizeof($famphone);$x++)
					{
						if($recphone[$i]["id"]==$famphone[$x]["phoneid"])
						{
							$foundx=true;
							break;
						}
					}
					if($foundx)
						$linkrec="<a href='setrec.php?id=".base64_encode($famphone[$x]["id"])."'>".stripslashes($famphone[$x]["name"])."</a>";
					else
						$linkrec="N/A";
				}
				else
					$linkrec="N/A";
				echo "<td width='29%' align='center' valign='middle'>$linkrec</td>";
				echo "</tr>";
				$countx++;
			}
		}
		else
			echo "<tr class='rowstyleno'><td colspan='6' align='center' valign='middle'>Not Found</td></tr>";
		}
		else
		{
		if(sizeof($famphone)>0)
		{
			$countx=1;
			$totalx=0;
			for($i=0;$i<sizeof($famphone);$i++)
			{
				$totalx = $countx%2;
				if($totalx==0)
					 $rowstyle="style='font-size:15pt'";
				else
					$rowstyle="style='background-color:#e1fb51; font-size:15pt'";
				$foundx=false;
				for($x=0;$x<sizeof($recphone);$x++)
				{
					if($recphone[$x]["id"]==$famphone[$i]["phoneid"])
					{
						$foundx=true;
							break;
					}
				}
				$linkrec="";
				if($foundx)
				{
					$linkrec="<a href='setrec.php?id=".base64_encode($famphone[$i]["id"])."'>".stripslashes($famphone[$i]["name"])."</a>";
					echo "<tr $rowstyle>";
					echo "<td width='6%' align='center' valign='middle'>$countx</td>";
					echo "<td width='6%' align='center' valign='middle'><a href='javascript:delphone(\"".base64_encode($recphone[$x]["id"])."\")'>Delete</a></td>";
					echo "<td width='30%' align='center' valign='middle'>".checkNA($recphone[$x]["callerid"])."</td>";
					$xdate = fixdate_comps("invdate_s",$recphone[$x]["date"]);
					echo "<td width='12%' align='center' valign='middle'>".checkNA($recphone[$x]["phone"])."</td>";
					echo "<td width='17%' align='center' valign='middle'>$xdate</td>";
					echo "<td width='29%' align='center' valign='middle'>$linkrec</td>";
					echo "</tr>";
					$countx++;
				}
			}
		}
		else
			echo "<tr class='rowstyleno'><td colspan='6' align='center' valign='middle'>Not Found</td></tr>";
		}
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
    <td height="50" align="center" valign="middle">
    	<input type="button" value="Cancel" onclick="closemodal()"/>
    </td>
  </tr>
 </form>
</table>
</body>
</html>
<?php
	include "include/config.php";
?>