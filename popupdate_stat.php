<?php
session_start();
include "include/config.php";
include "include/function.php";
$task=$_REQUEST["task"];
$qu=base64_decode($_REQUEST["qu"]);
$query="";
$ascdesc = $_REQUEST["ascdesc"];
if($ascdesc=="desc")
	$ascdesc="asc";
else
	$ascdesc="desc";
if(!empty($qu))
{
	$quexpl = explode("order by",$qu);
	if(sizeof($quexpl)>1)
		$query = $quexpl[0]." order by ".$task." ".$ascdesc;
	else
		$query = $qu." order by ".$task." ".$ascdesc;;
}
$officename="";
//echo $_REQUEST["qu"]."<br/>";
//echo $query;
if($result = mysql_query($query))
{
	if(($numrows = mysql_num_rows($result))>0)
	{
		$info=mysql_fetch_assoc($result);
		$namex = getOfficeName($info["office"]);
		$officename = "For ".stripslashes($namex);
	}
}
$t = $_REQUEST["t"];
$farray = array();
$farrayv = array();
if($t=="cdate")
{
	$farray[]="cdate";
	$farray[]="csource";
	$farray[]="csource";
	$farrayv[]="Date Called";
	$farrayv[]="Rec Source";
	$farrayv[]="Rec Source Info";
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
?>
    <fieldset>
    	<legend><span style="font-size:15pt; color:#666">Complete Detail List <?Php echo $officename; ?></span></legend>
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr style="background-color:#28629e; color:#FFF">
    <td width="6%" align="center" valign="middle">&nbsp;</td>
    <td width="20%" align="center" valign="middle"><a class='linkstats' href='javascript:changeStatSort("cname","<?php echo $_REQUEST["qu"]; ?>","<?php echo $t; ?>","<?php echo $ascdesc; ?>")'>Name</a></td>
    <td width="16%" align="center" valign="middle"><a class='linkstats' href='javascript:changeStatSort("cphone","<?php echo $_REQUEST["qu"]; ?>","<?php echo $t; ?>","<?php echo $ascdesc; ?>")'>Phone</a></td>
    <td width="12%" align="center" valign="middle"><a class='linkstats' href='javascript:changeStatSort("<?php echo $farray[0]; ?>","<?php echo $_REQUEST["qu"]; ?>","<?php echo $t; ?>","<?php echo $ascdesc; ?>")'><?Php echo $farrayv[0]; ?></a></td>
    <td width="17%" align="center" valign="middle"><a class='linkstats' href='javascript:changeStatSort("<?php echo $farray[1]; ?>","<?php echo $_REQUEST["qu"]; ?>","<?php echo $t; ?>","<?php echo $ascdesc; ?>")'><?Php echo $farrayv[1]; ?></a></td>
    <td width="29%" align="center" valign="middle"><a class='linkstats' href='javascript:changeStatSort("<?php echo $farray[2]; ?>","<?php echo $_REQUEST["qu"]; ?>","<?php echo $t; ?>","<?php echo $ascdesc; ?>")'><?Php echo $farrayv[2]; ?></a></td>
  </tr>
  <tr>
    <td colspan="6">
    	<div style="height:350px; overflow:auto" id="statsdiv">
    	<table width="100%" border="0" cellspacing="0" cellpadding="0">
        <?php
		if(!empty($query))
		{
			if($result = mysql_query($query))
			{
				if(($num_rows = mysql_num_rows($result))>0)
				{
					$countx=1;
					$totalx=0;
					while($rows=mysql_fetch_array($result))
					{
						$totalx = $countx%2;
						if($totalx==0)
							 $rowstyle="style='font-size:15pt'";
						else
							$rowstyle="style='background-color:#e1fb51; font-size:15pt'";
						echo "<tr $rowstyle>";
						echo "<td width='6%' align='center' valign='middle'>$countx</td>";
						echo "<td width='20%' align='center' valign='middle'><a class='linkstats_b' target='_blank' href='setrec.php?id=".base64_encode($rows["id"])."'>".stripslashes($rows["cname"])."</a></td>";
						echo "<td width='16%' align='center' valign='middle'>".stripslashes($rows["cphone"])."</td>";
						$var1="";
						$var2="";
						if($farray[1]=="csource")
						{
							$cent=explode(" || ",$rows["csource_title"]);
							if(!empty($rows["csource"]))
							{
								$varsource = getSourceName($rows["csource"]);
								if(!empty($varsource))
									$varsource=$varsource;
							}
							//$var1=$cent[0];
							//$var2=$cent[1];
							$var1=$varsource;
							if(sizeof($cent)>1)
								$var2=$cent[0]."<br/>".$cent[1];
							else
								$var2=$cent[0];
							/*if(sizeof($cent)>1)
							{
								if(!empty($varsource))
									$var1=$varsource." ".$cent[0];
								else
									$var1=$cent[0];
								$var2=$cent[1];
							}
							else
							{
								if(!empty($varsource))
									$var1=$varsource;
								else
									$var1=$cent[0];
								$var1=$cent[0];
								//$var2=$cent[1];
							}*/
						}
						else
						{
							if($farray[1]=="folstatus")
								$var1 = getFolStatus($rows[$farray[1]]);
							else if($farray[1]=="status")
								$var1= getRecStatus($rows[$farray[1]]);
							else if($farray[1]=="itime")
								$var1=fixdate_comps("h",$rows[$farray[1]]);
							else if($farray[1]=="idate" || $farray[1]=="orientation" || $farray[1]=="orientation_comp")
								$var1=fixdate_comps("invdate_s",$rows[$farray[1]]);
							else
								$var1=$rows[$farray[1]];
							if($farray[2]=="folstatus")
								$var2 = getFolStatus($rows[$farray[2]]);
							else if($farray[2]=="status")
								$var2= getRecStatus($rows[$farray[2]]);
							else
								$var2=$rows[$farray[2]];
						}
						if($farray[0]=="orientation" || $farray[0]=="orientation_comp")
						{
							$datex = explode(" ",$rows[$farray[0]]);
							if(sizeof($datex)>1)
							{
								$xdate=fixdate_comps("invdate_s",$datex[0]);
								$var1=fixdate_comps("h",$datex[1]);
							}
							else
								$xdate=fixdate_comps("h",$rows[$farray[0]]);
						}
						else
							$xdate = fixdate_comps("invdate_s",$rows[$farray[0]]);
						echo "<td width='12%' align='center' valign='middle'>$xdate</td>";
						echo "<td width='17%' align='center' valign='middle'>".stripslashes($var1)."</td>";
						echo "<td width='29%' align='center' valign='middle'>".stripslashes($var2)."</td>";
						echo "</tr>";
						 $countx++;
					}
				}
				else
					echo "<tr class='rowstyleno'><td colspan='6' align='center' valign='middle'>Not Found</td></tr>";
			}
			else
			echo "<tr class='rowstyleno'><td colspan='6' align='center' valign='middle'>Not Found</td></tr>";
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
<?php
	include "include/config.php";
?>