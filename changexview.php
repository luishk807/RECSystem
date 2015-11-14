<?php
session_start();
include "include/config.php";
include "include/function.php";
$ndays =getNDays();
$xoff =$_REQUEST["xoff"];
$xtype=$_REQUEST["xtype"];
$xdate=@fixdate_comps("mildate",$_REQUEST["xdate"]);
if($xtype=="inter")
{
	$sdateq = "idate";
	$sdateq_ex = "a.idate"; //for spreadsheet script
}
else
{
	$sdateq ="orientation";
	$sdateq_ex = "a.orientation";
}
$dateq = " where catid='1' and $sdateq=CURDATE()+ INTERVAL ".$ndays. " DAY ";//default date format
$dateq_ex = " where catid='1' and $sdateq_ex=CURDATE()+ INTERVAL ".$ndays. " DAY ";//default date for spreadsheet
$restr = " and folstatus !='3' and (status !='8' and status !='9') ";
$restr_ex = " and a.folstatus !='3' and (a.status !='8' and a.status !='9') ";//spreadsheet
$query = "select * from rec_entries";
$query_ex=" ";
if(!empty($xdate))
{
	$dateq = " where catid='1' and $sdateq like '$xdate%' ";
	$dateq_ex = " where catid='1' and $sdateq_ex like '$xdate%' ";
}
if(!empty($dateq))
{
	$query .= $dateq;
	$query_ex =$dateq_ex;
}
if(!empty($xoff))
{
	if($xoff !="all")
	{
		$query .= " and office='".base64_decode($xoff)."' ";
		$query_ex .= "  and a.office='".base64_decode($xoff)."' ";
	}
}
if(!empty($xtype))
{
	if($xtype=="inter")
	{
		$query .=" and status='1' ";
		$query_ex .=" and a.status='1' ";
		$v = " order by idate desc, itime desc ";
		$v_ex= " order by a.idate desc, a.itime desc ";
		$vx = "Interview";
		$vxi = "idate";
	}
	else
	{
		$query .=" and status='3' ";
		$query_ex .=" and a.status='3' ";
		$v = " order by orientation desc ";
		$v_ex = " order by a.orientation desc ";
		$vx = "Orientation";
		$vxi = "orientation";
	}
}
else
{
	$query .=" and status='3' ";
	$query_ex .=" and a.status='3' ";
	$v = " order by orientation desc ";
	$v_ex = " order by a.orientation desc ";
	$vxi = "orientation";
}
$query .=" $restr ".$v;
$query_ex .=" $restr_ex ".$v_ex;
//check if needed to build excel
$excel_check=false;
$excelbtn="";
if(empty($xoff) && empty($xtype) && empty($xdate))
{
	$qx="SELECT c.name as Status, b.name as Category, a.cname as Name,a.cphone as Phone_1, a.orientation as Date_For_Orientation,DATE_FORMAT(a.orientation, '%m/%d/%Y %l:%i %p') as Date_For_Orientation,j.name as Office_For_Orientation FROM  rec_entries as a  LEFT OUTER JOIN task_category as b on a.catid=b.id LEFT OUTER JOIN rec_status as c on c.id=a.status LEFT OUTER JOIN 
rec_office as j on j.id=a.orientation_office WHERE orientation=CURDATE()+ INTERVAL $ndays DAY and status='3' and folstatus !='3' and (status !='8' and status !='9') and a.catid='1' ORDER BY a.date ";
}
else
{
	$qx="SELECT c.name as Status, b.name as Category, a.cname as Name,a.cphone as Phone_1, DATE_FORMAT(a.idate, '%m/%d/%Y') as Date_For_Interview,DATE_FORMAT(a.itime, '%l:%i %p') as Time_For_Interview,h.name as Office FROM rec_entries as a  LEFT OUTER JOIN  task_category as b on a.catid=b.id LEFT OUTER JOIN rec_status as c on c.id=a.status LEFT OUTER JOIN rec_office as h on h.id=a.office 
$query_ex ";
}
if($rxx=mysql_query($qx))
{
	if(($nx=mysql_num_rows($rxx))>0)
		$excel_check=true;
}
if($excel_check)
	$excelbtn="<a href='getExcelPen.php?xoff=".$xoff."&xtype=".$xtype."&xdate=".$xdate."' style='color:#FFF;' target='_blank'>Get Excel</a>";
//end of excell check
?>
<div id="excelbtn" style="text-align:center;background:#008000; color:#FFF;">
<?Php
	echo $excelbtn;
?>
</div>
<div style='height:200px; overflow:auto'>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr style="background-color:#28629e; color:#FFF">
   <td width="8%" align="center" valign="middle"></td>
   <td width="31%" align="center" valign="middle">Name</td>
   <td width="16%" align="center" valign="middle">Phone</td>
   <td width="14%" align="center" valign="middle"><?php echo $vx; ?></td>
   <td width="11%" align="center" valign="middle">Office</td>
   <td width="20%" align="center" valign="middle">Status</td>
  </tr>
<?php

$queryx = $query;
if($resultx = mysql_query($queryx))
{
	if(($num_rowsx = mysql_num_rows($resultx))>0)
	{
		$countx=1;
		$totalx=0;
		while($rowsx = mysql_fetch_array($resultx))
		{
			$totalx = $countx%2;
			if($totalx==0)
				$rowstyle="style='font-size:15pt'";
			else
				$rowstyle="style='background-color:#e1fb51; font-size:15pt'";
			if($user["id"]==$rowsx["interviewer"])
			{
				if($rowsx["inter_view"]=="no")
					$newup=true;
			}
			if($xtype=="inter")
				$datev = fixdate_comps("onsip",$rowsx[$vxi]." ".$rowsx["itime"]);
			else
				$datev = fixdate_comps("onsip",$rowsx[$vxi]);
			echo "<tr $rowstyle><td align='center' valign='middle'>$countx</td><td align='center' valign='middle'><a class='adminlink' href='setrec.php?id=".base64_encode($rowsx["id"])."'>".stripslashes($rowsx["cname"])."</a> </td><td align='center' valign='middle'>".stripslashes($rowsx["cphone"])."</td><td align='center' valign='middle'>".$datev."</td><td align='center' valign='middle'>".getOfficeName_s($rowsx["office"])."</td><td align='center' valign='middle'>".getRecStatus($rowsx["status"])."</td></tr>";
			$countx++;
		}
	}
	else
		echo "<tr class='rowstyleno'><td colspan='6' align='center' valign='middle'>No $vx Pending Found</td></tr>";
}
 else
	echo "<tr class='rowstyleno'><td colspan='6' align='center' valign='middle'>No $vx Pending Found</td></tr>";
?>
</table>
</div>
<?php
	include "include/config.php";
?>