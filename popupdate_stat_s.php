<?php
session_start();
include "include/config.php";
include "include/function.php";
/*$oid=base64_decode($_REQUEST["office"]);
$t=base64_decode($_REQUEST["task"]);
$date1=$_REQUEST["date1"];
$date2=$_REQUEST["date2"];
$status=base64_decode($_REQUEST["status"]);
$ascdesc=$_REQUEST["ascdesc"];
$sort=$_REQUEST["sort"];
$ototal_name="";*/
/*$farray = array();
$farrayv = array();
$titleb="";
//max column is six
$sortq="";
if($ascdesc=="desc")
	$ascdesc="asc";
else
	$ascdesc="desc";
if(!empty($oid))
{
	$namex = getOfficeName($oid);
	$officename = "For ".stripslashes($namex);
}
if(!empty($sort))
	$sortq=" order by $sort $ascdesc";
if($t=="phone")
{
	$farray[]="caller";
	$farray[]="tphone";
	$farray[]="office";
	$farray[]="date";
	$farrayv[]="Caller Id";
	$farrayv[]="Phone";
	$farrayv[]="Office";
	$farrayv[]="Date Called";
	$rows=getStatusEntryPhone($oid,$date1,$date2,$sortq);
	$rows=sortArray($sort,$rows);
	$titleb=" For Phone Numbers ";
}
else if($t=="inter")
{
	$farray[]="name";
	$farray[]="interviewer";
	$farray[]="idate";
	$farray[]="csource";
	$farray[]="csource_title";
	$farrayv[]="Name";
	$farrayv[]="Interviewer";
	$farrayv[]="Int Date";
	$farrayv[]="Source";
	$farrayv[]="Source Info";
	if(!empty($oid))
		$rows=getStatusEntry_array($oid,$date1,$date2,'1');
	else
		$rows=getStatusEntry_all_array('1',$date1,$date2);
	$rows=sortArray($sort,$rows);
	$titleb=" For Interviews ";
}
else if($t=="orien")
{
	$farray[]="name";
	$farray[]="interviewer";
	$farray[]="orientation";
	$farray[]="orientation_office";
	$farrayv[]="Name";
	$farrayv[]="Manager";
	$farrayv[]="Orient Date";
	$farrayv[]="Office";
	if(!empty($oid))
		$rows=getStatusEntry_array($oid,$date1,$date2,'3');
	else
		$rows=getStatusEntry_all_array('3',$date1,$date2);
	$rows=sortArray($sort,$rows);
	$titleb=" For Orientation ";
}
else if($t=="orienc")
{
	$farray[]="name";
	$farray[]="interviewer";
	$farray[]="orientation";
	$farray[]="orientation_comp";
	$farray[]="orientation_office";
	$farrayv[]="Name";
	$farrayv[]="Manager";
	$farrayv[]="Orient Date";
	$farrayv[]="Orient Comp";
	$farrayv[]="Office";
	if(!empty($oid))
		$rows=getStatusEntry_array($oid,$date1,$date2,'7');
	else
		$rows=getStatusEntry_all_array('7',$date1,$date2);
	$rows=sortArray($sort,$rows);
	$titleb=" For Orientation Completed ";
}
else if($t=="ototal")
{
	$ototal_name=" Sales ";
	$farray[]="userid";
	$farray[]="userid";
	$farray[]="userid";
	$farray[]="userid";
	$farrayv[]="Name";
	$farrayv[]="Manager";
	$farrayv[]="Electrical";
	$farrayv[]="Gas";
	$rows=getStatusEntrySales($oid,$date1,$date2,$sortq);
	$rows=sortArray($sort,$rows);
}
$colwidth=array();
$maxcol=sizeof($farray)+1;
if($maxcol>6)
{
	$colwidth[]=6;
	$colwidth[]=14;
	$colwidth[]=16;
	$colwidth[]=12;
	$colwidth[]=12;
	$colwidth[]=17;
	$colwidth[]=23;
	
}
else if($maxcol>5)
{
	$colwidth[]=6;
	$colwidth[]=20;
	$colwidth[]=16;
	$colwidth[]=12;
	$colwidth[]=17;
	$colwidth[]=29;
}
else
{
	$colwidth[]=6;
	$colwidth[]=36;
	$colwidth[]=12;
	$colwidth[]=27;
	$colwidth[]=19;
}
$qu="task=".base64_encode($t)."&status=".base64_encode($status)."&office=".base64_encode($oid)."&date1=".$date1."&date2=".$date2."&ascdesc=".$ascdesc;*/
include "include/incstats_script.php";
?>
    <fieldset>
    	<legend><span style="font-size:15pt; color:#666">Complete <?php echo $ototal_name; ?>Detail List <?Php echo $officename."".$titleb; ?></span><?php echo $phone_page." ".$xlink_title;; ?></legend>
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr style="background-color:#28629e; color:#FFF">
    <td width="<?Php echo $colwidth[0]; ?>%" align="center" valign="middle">&nbsp;</td>
    <?php
	$cw=1;
	for($i=0;$i<sizeof($farray);$i++)
	{
		$sorto=$farray[$i];
		/*if($t=="ototal")
		{
			if($i==2)
				$sorto="xelec";
			else if($i==3)
				$sorto="xgas";
		}*/
		echo "<td width='".$colwidth[$cw]."%' align='center' valign='middle'><a class='linkstats' href='javascript:changeStatSort_s(\"".$sorto."\",\"".$qu."\")'>".$farrayv[$i]."</a></td>";
        $cw++;
	}
	?>
  </tr>
  <tr>
    <td colspan="<?Php echo $maxcol; ?>">
    	<div id="loadergif" style="text-align:center; display:none"><img src="images/floader.gif" border="0" /></div>
    	<div style="height:350px; overflow:auto" id="statsdiv">
    	<table width="100%" border="0" cellspacing="0" cellpadding="0">
        <?php
			if(sizeof($rows)>0)
			{
				$countx=1;
				$totalx=0;
				for($x=0;$x<sizeof($rows);$x++)
				{
					$totalx = $countx%2;
					if($totalx==0)
						$rowstyle="style='font-size:15pt'";
					else
						$rowstyle="style='background-color:#e1fb51; font-size:15pt'";
					echo "<tr $rowstyle>";
					echo "<td width='".$colwidth[0]."%' align='center' valign='middle'>$countx</td>";
					$cw=1;
					for($i=0;$i<sizeof($farray);$i++)
					{
						$var1="";
						if($t=="phone")
						{
							if($i==1)
							{
								$cvar=getPhoneMatch($rows[$x][$farray[$i]]);
								if(!empty($cvar))
									$var1="<a href='setrec.php?id=".base64_encode($cvar)."' target='_blank'>".$rows[$x][$farray[$i]]."</a>";
								else
									$var1=$rows[$x][$farray[$i]];
							}
							else if($i==2)
								$var1=getOfficeName($rows[$x][$farray[$i]]);
							else if($i==3)
								$var1=fixdate_comps('invdate_s',$rows[$x][$farray[$i]]);
							else
								$var1=$rows[$x][$farray[$i]];
						}
						else if($t=="inter")
						{
							if($i==0)
								$var1="<a href='setrec.php?id=".base64_encode($rows[$x]["id"])."' target='_blank' onmouseover='modalpop(\"".base64_encode($rows[$x]["id"])."\")' id='trigger' onmouseout='modalpophide()'>".$rows[$x][$farray[$i]]."</a>";
							else if($i==1)
								$var1=getName($rows[$x]["manager"]);
							else if($i==2)
								$var1=checkNA($rows[$x]['ocall']);
							else if($i==3)
								$var1=fixdate_comps('onsip',$rows[$x][$farray[$i]]." ".$rows[$x]['itime']);
							else if($i==4)
								$var1=getSourceName($rows[$x][$farray[$i]]);
							else
								$var1=$rows[$x][$farray[$i]];
						}
						else if($t=="orien")
						{
							if($i==0)
								$var1="<a href='setrec.php?id=".base64_encode($rows[$x]["id"])."' target='_blank' onmouseover='modalpop(\"".base64_encode($rows[$x]["id"])."\")' id='trigger' onmouseout='modalpophide()'>".$rows[$x][$farray[$i]]."</a>";
							else if($i==1)
								$var1=getName($rows[$x]["manager"]);
							else if($i==2)
								$var1=checkNA($rows[$x]['ocall']);
							else if($i==3)
								$var1=fixdate_comps('onsip',$rows[$x][$farray[$i]]);
							else if($i==4)
								$var1=getOfficeName($rows[$x][$farray[$i]]);
							else
								$var1=$rows[$x][$farray[$i]];
						}
						else if($t=="orienc")
						{
							if($i==0)
							{
								//$ccode=$rows[$x]["ccode"];
								//if(!empty($ccode))
								//	$ccode=" <span style='color:#a2a2a2; font-size:13pt;'>(".$ccode.")</span>";
								$var1="<a href='setrec.php?id=".base64_encode($rows[$x]["id"])."' target='_blank' onmouseover='modalpop(\"".base64_encode($rows[$x]["id"])."\")' id='trigger' onmouseout='modalpophide()'>".$rows[$x][$farray[$i]]."</a>";
								//$ccode="";
							}
							else if($i==1)
								$var1=getName($rows[$x]["manager"]);
							else if($i==2)
								$var1=checkNA($rows[$x]['ocall']);
							else if($i==3)
							{
								$ccode=$rows[$x]["ccode"];
								//$var1=fixdate_comps('invdate_s',$rows[$x][$farray[$i]]);
								$var1=checkNA($ccode);
								$ccode="";
							}
							else if($i==4)
								$var1=fixdate_comps('invdate_s',$rows[$x][$farray[$i]]);
							else if($i==5)
								$var1=getOfficeName($rows[$x][$farray[$i]]);
							else
								$var1=$rows[$x][$farray[$i]];
						}
						else if($t=="ototal")
						{
							if($i==0)
								$var1=getAgent($rows[$x]["userid"]);
							if($i==1)
								$var1=$rows[$x]["ccode"];
							if($i==2)
								$var1=$rows[$x]["manager"];
							/*if($i==2 || $i==3)
							{
								$userx=$_SESSION["rec_user"];
								//$var1=$allsales[0]["xelec"];
								$var1=$rows[$x][$farray[$i]];
								if($var1>0)
									$var1="<a target='_blank' href='http://www.familyenergymap.com/salesreport/directaccess.php?aid=".base64_encode($rows[$x][$farray[$i]])."&uid=".base64_encode($userx["id"])."' style='color:#000'>$var1</a>";
							}*/
							if($i==3)
							{
								$userx=$_SESSION["rec_user"];
								$var1=$rows[$x]['xelec'];
								if($var1>0)
									$var1="<a target='_blank' href='http://www.familyenergymap.com/salesreport/directaccess.php?aid=".base64_encode($rows[$x]["userid"])."&uid=".base64_encode($userx["id"])."' style='color:#000' >$var1</a>";
							}
							if($i==4)
							{
								$userx=$_SESSION["rec_user"];
								$var1=$rows[$x]['xgas'];
								if($var1>0)
									$var1="<a target='_blank' href='http://www.familyenergymap.com/salesreport/directaccess.php?aid=".base64_encode($rows[$x]["userid"])."&uid=".base64_encode($userx["id"])."' style='color:#000'>$var1</a>";
							}
						}
						echo "<td width='".$colwidth[$cw]."%' align='center' valign='middle'>".stripslashes($var1)."</td>";
						$cw++;
					}
					echo "</tr>";
					$countx++;
				}
			}
			else
				echo "<tr class='rowstyleno'><td colspan='".$maxcol."' align='center' valign='middle'>Not Found</td></tr>";
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