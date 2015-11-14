<?php
session_start();
include "include/config.php";
include "include/function.php";
include "include/vars.php";
$entry_array=$_SESSION["entry_array"];
$pstart=$_REQUEST["pstart"]+1;
$climit=get_climit();
$plimit=$pstart + $climit;
$asize=sizeof($entry_array);
$lastnum="";
?>
<?php
if(sizeof($entry_array)>0)
{
	$countx=$pstart;
	$totalx=0;
	echo "<table width='100%' border='0' cellspacing='0' cellpadding='0'>";
	for($x=$pstart;$x<($asize-$plimit);$x++)
	{
		$lastnum=$x;
		$totalx = $countx%2;
		if($totalx==0)
			$rowstyle="style='background-color:#e1fb51; font-size:15pt'";
		else
			$rowstyle="style='font-size:15pt'";
		$newup=$entry_array[$x]["newup"];
		if($newup=="true")
			$imgnew="&nbsp;<img src='images/newgif.gif' border='0' alt='new'/>";
		 else
			$imgnew="";
		if($entry_array[$x]["folcome"]=="no")
			$status="Cancelled";
		else
			$status = getRecStatus($entry_array[$x]["status"]);
		$xdate = fixdate_comps("invdate_s",$entry_array[$x]["xdate"]);
		$xoffice=getOfficeName_s($entry_array[$x]["office_x"]);
		if(empty($xdate))
			 $xdate = "N/A";
		 if($showfamily)
			$linkuser = "<a class='adminlink' href='setrec.php?id=".base64_encode($entry_array[$x]["id"])."'>".stripslashes($entry_array[$x]["cname"])."</a>".$imgnew." ".getFolStatus_View($rowsx["folstatus"])." ";
		else
			 $linkuser="<a class='adminlink' href='setrec.php?id=".base64_encode($entry_array[$x]["id"])."'>".stripslashes($entry_array[$x]["cname"])."</a>".$imgnew;
		echo "<tr $rowstyle><td align='center' valign='middle' width='8%'>$countx</td><td align='center' valign='middle' width='31%'>".$linkuser."</td><td align='center' valign='middle' width='16%'>".$entry_array[$x]["cphone"]."</td><td align='center' valign='middle' width='14%'>$xdate</td><td align='center' valign='middle' width='11%'>".$xoffice."</td><td align='center' valign='middle' width='20%'>".$status."</td></tr>";
		 $countx++;
		 $_SESSION["climit"]=$x;
	}
	echo "</table>";
}
?>
<div id="more" class="morebox">
    <a href="javascript:showmore('<?php echo $lastnum; ?>')" class="more">Show More Entry</a>
</div>
<?php
	include "include/config.php";
?>