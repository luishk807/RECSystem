<?php
session_start();
include "include/config.php";
include "include/function.php";
$idmo=base64_decode($_REQUEST["id"]);
$heightmo="style='height:200px'";
if(!empty($idmo))
{
	$qmo="select * from rec_entries where id='".$idmo."'";
	if($rmo=mysql_query($qmo))
	{
		if(($nmo=mysql_num_rows($rmo))>0)
			$infomo=mysql_fetch_assoc($rmo);
	}
	$qmo="select * from rec_timeline where entryid='".$idmo."'";
	if($rmo=mysql_query($qmo))
	{
		if(($nmo=mysql_num_rows($rmo))>2)
			$heightmo="";
	}
}
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<form action="" method="post">
  <tr>
    <td align="center" valign="middle" colspan="3">
    	<span style="font-size:15pt; color:#666">Timeline For <?php echo stripslashes($infomo["cname"]); ?></span>
    </td>
  </tr>
  <tr style="background-color:#28629e; color:#FFF">
    <td align="center" valign="middle" width="10%">&nbsp;
    </td>
    <td align="center" valign="middle" width="45%">
    	Event
    </td>
    <td align="center" valign="middle" width="45%">
    	Date
    </td>
  </tr>
  <tr>
    <td align="center" valign="middle" colspan="3">
    	<div <?php echo $heightmo; ?>>
    	<table width="100%" border="0" cellspacing="0" cellpadding="0">
        	<?Php
			$qmo="select * from rec_timeline where entryid='".$idmo."'";
			if($rmo=mysql_query($qmo))
			{
				if(($nmo=mysql_num_rows($rmo))>0)
				{
					$cmo=1;
					while($rmox=mysql_fetch_array($rmo))
					{
						if(!empty($rmox["xdate"]))
							$xdatemo=fixdate_comps('onsip',$rmox["xdate"]);
						else
							$xdatemo=fixdate_comps('onsip',$rmox["date"]);
						if($rmox["status"]=='7')
						{
							$xdatemo_s=explode(" ",$xdatemo);
							if(sizeof($xdatemo_s)>1)
								$xdatemo=$xdatemo_s[0];
						}
						echo "<tr>";
						echo "
						<tr><td colspan='3' align='center' valign='middle'><hr/></td></tr>
						<td width='10%' align='center' valign='middle'>$cmo</td>
						<td width='45%' align='center' valign='middle'>".getRecStatus($rmox["status"])."</td>
						<td width='45%' align='center' valign='middle'>".checkNA($xdatemo)."</td>
						";
						echo "</tr>";
						$notemo="";
						$shownote=false;
						$getreason=getRecReason($idmo);
						$scheck=trim($rmox["status"]);
						switch($scheck)
						{
							case 2:
							{
								$shownote=true;
								break;
							}
							case 8:
							{
								//$notemo="here is this stuff";
								$shownote=true;
								break;
							}
							case '18':
							{
								$shownote=true;
								break;
							}
							case '19':
							{
								$shownote=true;
								break;
							}
							case '20':
							{
								$shownote=true;
								break;
							}
						}
						if($shownote && !empty($getreason))
						{
							echo "<tr>";
							echo "<tr><td colspan='3' align='center' valign='middle'><hr/></td></tr>
							<td colspan='3' align='center' valign='middle'>$notemo
							<span style='color:#666; font-style:italic; text-decoration:underline'>Reason</span><br/>".$getreason."
							</td>";
							echo "</tr>";
						}
					  	$cmo++;
					}
				}
				else
					echo "<tr class='rowstyleno'><td colspan='3' align='center' valign='middle'>No Timeline Found</td></tr>";
			}
			else
				echo "<tr class='rowstyleno'><td colspan='3' align='center' valign='middle'>No Timeline Found</td></tr>";
			?>
        </table>
        </div>
    </td>
  </tr>
 </form>
</table>
<?php
	include "include/config.php";
?>