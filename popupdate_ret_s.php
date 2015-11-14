<?php
session_start();
include "include/config.php";
include "include/function.php";
include "include/incret_script.php";
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
		echo "<td width='".$colwidth[$cw]."%' align='center' valign='middle'><a class='linkstats' href='javascript:changeRetSort_s(\"".$sorto."\",\"".$qu."\")'>".$farrayv[$i]."</a></td>";
        $cw++;
	}
	?>
  </tr>
  <tr>
    <td colspan="<?Php echo $maxcol; ?>">
    	<div id="loadergif" style="text-align:center; display:none"><img src="images/floader.gif" border="0" /></div>
    	<div style="height:350px; overflow:auto" id="retdiv">
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
						if($t=="dret")
						{
							if($i==0)
							{
								$cvar=getEntryMatch($rows[$x][$farray[$i]],$rows[$x]["ccode"]);
								$var1=$cvar;
							}
							else if($i==1)
								$var1=$rows[$x]['ccode'];
							else if($i==2)
								$var1=getName($rows[$x][$farray[$i]]);
							else if($i==3)
								$var1=checkNA(fixdate_comps('d',$rows[$x][$farray[$i]]));
							else
								$var1=$rows[$x][$farray[$i]];
						}
						else if($t=="dsales")
						{
							if($i==0)
								$var1=getEntryMatch($rows[$x][$farray[$i]],$rows[$x]["ccode"]);
							if($i==1)
								$var1=$rows[$x]["ccode"];
							if($i==2)
							{
								$userx=$_SESSION["rec_user"];
								$var1=$rows[$x]['xgas'];
								if($var1>0)
									$var1="<a target='_blank' href='http://www.familyenergymap.com/salesreport/directaccess.php?aid=".base64_encode($rows[$x]["userid"])."&uid=".base64_encode($userx["id"])."&task=ret&date_detail=".$_REQUEST["date_detail"]."&oid=".$_REQUEST["office"]."' style='color:#000' >$var1</a>";
							}
							if($i==3)
							{
								$userx=$_SESSION["rec_user"];
								$var1=$rows[$x]['xelec'];
								if($var1>0)
									$var1="<a target='_blank' href='http://www.familyenergymap.com/salesreport/directaccess.php?aid=".base64_encode($rows[$x]["userid"])."&uid=".base64_encode($userx["id"])."&task=ret&date_detail=".$_REQUEST["date_detail"]."&oid=".$_REQUEST["office"]."' style='color:#000' >$var1</a>";
							}
							if($i==4)
								$var1=checkNA(fixdate_comps('d',$rows[$x]["hired"]));
							if($i==5)
								$var1=getName($rows[$x]["manager"]);
						}
						else if($t=="dsales_ret")
						{
							if($i==0)
								$var1=getEntryMatch($rows[$x][$farray[$i]],$rows[$x]["ccode"]);
							if($i==1)
								$var1=$rows[$x]["ccode"];
							if($i==2)
							{
								$userx=$_SESSION["rec_user"];
								$var1=$rows[$x]['xgas'];
								if($var1>0)
									$var1="<a target='_blank' href='http://www.familyenergymap.com/salesreport/directaccess.php?aid=".base64_encode($rows[$x]["userid"])."&uid=".base64_encode($userx["id"])."&task=ret&date_detail=".$_REQUEST["date_detail"]."&oid=".$_REQUEST["office"]."' style='color:#000' >$var1</a>";
							}
							if($i==3)
							{
								$userx=$_SESSION["rec_user"];
								$var1=$rows[$x]['xelec'];
								if($var1>0)
									$var1="<a target='_blank' href='http://www.familyenergymap.com/salesreport/directaccess.php?aid=".base64_encode($rows[$x]["userid"])."&uid=".base64_encode($userx["id"])."&task=ret&date_detail=".$_REQUEST["date_detail"]."&oid=".$_REQUEST["office"]."' style='color:#000' >$var1</a>";
							}
							if($i==4)
								$var1=checkNA(fixdate_comps('d',$rows[$x]["hired"]));
							if($i==5)
								$var1=getName($rows[$x]["manager"]);
						}
						else if($t=="dret_total")
						{
							if($i==0)
							{
								$cvar=getEntryMatch($rows[$x][$farray[$i]],$rows[$x]["ccode"]);
								$var1=$cvar;
							}
							else if($i==1)
								$var1=$rows[$x]['ccode'];
							else if($i==2)
								$var1=getName($rows[$x][$farray[$i]]);
							else if($i==3)
								$var1=checkNA(fixdate_comps('d',$rows[$x][$farray[$i]]));
							else
								$var1=$rows[$x][$farray[$i]];
						}
						else if($t=="rsales")
						{
							if($i==0)
								$var1=getEntryMatch($rows[$x][$farray[$i]],$rows[$x]["ccode"]);
							if($i==1)
								$var1=$rows[$x]["ccode"];
							if($i==2)
							{
								$userx=$_SESSION["rec_user"];
								$var1=$rows[$x]['xgas'];
								if($var1>0)
									$var1="<a target='_blank' href='http://www.familyenergymap.com/salesreport/directaccess.php?aid=".base64_encode($rows[$x]["userid"])."&uid=".base64_encode($userx["id"])."&task=ret&date_detail=".$_REQUEST["date_detail"]."&oid=".$_REQUEST["office"]."' style='color:#000' >$var1</a>";
							}
							if($i==3)
							{
								$userx=$_SESSION["rec_user"];
								$var1=$rows[$x]['xelec'];
								if($var1>0)
									$var1="<a target='_blank' href='http://www.familyenergymap.com/salesreport/directaccess.php?aid=".base64_encode($rows[$x]["userid"])."&uid=".base64_encode($userx["id"])."&task=ret&date_detail=".$_REQUEST["date_detail"]."&oid=".$_REQUEST["office"]."' style='color:#000' >$var1</a>";
							}
							if($i==4)
								$var1=checkNA(fixdate_comps('d',$rows[$x]["hired"]));
							if($i==5)
								$var1=getName($rows[$x]["manager"]);
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