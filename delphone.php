<?php
session_start();
include "include/config.php";
include "include/function.php";
$id=base64_decode($_REQUEST["id"]);
if(!empty($id))
{
	$q="delete from rec_phones where id='".$id."'";
	@mysql_query($q);
}
$query = base64_decode($_REQUEST["qu"]);
$dateb = base64_decode($_REQUEST["dateb"]);
$officename="";
$recphone=array();
$famphone=array();
$ascdesc = $_REQUEST["ascdesc"];
if($ascdesc=="desc")
	$ascdesc="asc";
else
	$ascdesc="desc";
if($result = mysql_query($query))
{
	if(($numrows = mysql_num_rows($result))>0)
	{
		$info=mysql_fetch_assoc($result);
		$namex = getOfficeName($info["office"]);
		$officename = "For ".stripslashes($namex);
	}
}
$queryx ="select * from rec_phones where office='".$info["office"]."' $dateb";
if($resultx= mysql_query($queryx))
{
	if(($numrows = mysql_num_rows($result))>0)
	{
		while($rox=mysql_fetch_array($resultx))
		{
			$recphone[]=array('id'=>$rox["id"],'phone'=>fixOnSipPhone('family',trim($rox["tphone"])),'callerid'=>$rox["caller"],'date'=>$rox["date"]);
		}
	}
}
$t = $_REQUEST["t"];
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
if($result=mysql_query($query))
{
	if(($numrows =mysql_num_rows($result))>0)
	{
		while($rows=mysql_fetch_array($result))
		{
			for($i=0;$i<sizeof($recphone);$i++)
			{
				if(trim($recphone[$i]["phone"])==trim($rows["cphone"]) || trim($recphone[$i]["phone"])==trim($rows["cphonex"]))
					$famphone[]=array('phoneid'=>$recphone[$i]["id"],'id'=>$rows["id"],"name"=>stripslashes($rows["cname"]),"status"=>$rows["status"],'hired'=>$rows["hired"],'phone'=>$rows["cphone"],'phonex'=>$rows["cphonex"]);
			}
		}
	}
}
?>
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
    <?Php
include "include/unconfig.php";
?>