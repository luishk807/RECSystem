<?php
date_default_timezone_set('America/New_York');
adminlogin();
$showfamily=true;
$today=date("Y-m-d");
if(adminlogin_exp())
	$user=$_SESSION["rec_user"];
else
{
	$user=$_SESSION["brownuser"];
	$showfamily=false;
}
//include "include/write_excel_stats.php";
$date1 = $_REQUEST["date1"];
$date2 = $_REQUEST["date2"];
$sfilter = $_REQUEST["sfilter"];
$sfilterq="";
$sfilterqx="";
if($sfilter !='all' && !empty($sfilter))
{
	$sfilter=base64_decode($_REQUEST["sfilter"]);
	$sfilterq=" and x.csource='".clean($sfilter)."'";
	$sfilterqx=" and csource='".clean($sfilter)."'";
}
if(!empty($date1) && !empty($date2))
{
	$date1 = fixdate_comps("mildate",$_REQUEST["date1"]);
	$date2 = fixdate_comps("mildate",$_REQUEST["date2"]);
}
else
{
	$date1=date("Y-m-d", mktime(0, 0, 0, date("m"),date("d")-1,date("Y")));
	$date2=getLastDay($today);
}
//check if needed to build excel
$excel_check=false;
$excelbtn="";
$qx="SELECT distinct i.name as Office, x.fphone as Phone_Number, a.cphone as Phone_1,  a.cname as Name, a.ccode as Agent_Code, f.name as Manager, h.name as Status, DATE_FORMAT(a.idate, '%m/%d/%Y') as Date_For_Interview,DATE_FORMAT(a.itime, '%l:%i %p') as Time_For_Interview,
d.name as Interview_Office,DATE_FORMAT(a.orientation, '%m/%d/%Y %l:%i %p') as Date_For_Orientation,e.name as Orientation_Office,
DATE_FORMAT(a.orientation_comp, '%m/%d/%Y') as Orientation_Completed_Date,c.xelec as Electrical, c.xgas as Gas FROM rec_phones as x  LEFT OUTER JOIN rec_entries as a on x.fphone=a.cphonex LEFT OUTER JOIN sales_agent as b on a.ccode=b.acode LEFT OUTER JOIN (select y.userid,sum(xelec) as xelec,sum(xgas) as xgas from sales_report_real y group by y.userid) c on c.userid=b.id LEFT OUTER JOIN rec_office as d on a.office=d.id LEFT OUTER JOIN rec_office as e on a.orientation_office=e.id LEFT OUTER JOIN task_users as f on a.interviewer=f.id  LEFT OUTER JOIN rec_timeline as g on a.id=g.entryid LEFT OUTER JOIN rec_status as h on h.id=g.status LEFT OUTER JOIN rec_office as i on i.id=x.office where x.date between '$date1' and '$date2' $sfilterq ORDER BY x.office,a.cdate desc ";
if($rxx=mysql_query($qx))
{
	if(($nx=mysql_num_rows($rxx))>0)
		$excel_check=true;
}
$entrybtn="";
$qx="SELECT distinct a.name as Entered_In,x.cname as Name, x.ccode as Agent_Code, x.ocall as Called_For_Interview,x.cphone as Phone,x.cphonex as Phone_2,x.cdate as Date_Called,c.name as Source,x.csource_title as Source_Information,DATE_FORMAT(x.idate, '%m/%d/%Y') as Date_For_Interview,DATE_FORMAT(x.itime, '%l:%i %p') as Time_For_Interview,e.name as Interview_Office,x.int_show_info as Note,DATE_FORMAT(x.int_show_date, '%m/%d/%Y %l:%i %p') as Date_Shown_For_Interview,f.name as Manager,b.name as Status,DATE_FORMAT(x.orientation, '%m/%d/%Y %l:%i %p') as Date_For_Orientation,d.name as Orientation_Office,x.orientation_show as Shown_For_Orientation,x.ori_show_info as Orientation_Note,DATE_FORMAT(x.orientation_comp, '%m/%d/%Y') as Orientation_Completed_Date,h.xelec as Electrical, h.xgas as Gas FROM rec_entries as x  LEFT OUTER JOIN rec_office as a on x.coffice=a.id LEFT OUTER JOIN rec_status as b on x.status=b.id LEFT OUTER JOIN rec_source as c on x.csource=c.id LEFT OUTER JOIN rec_office as d on  x.orientation_office=d.id LEFT OUTER JOIN rec_office as e on x.office=e.id  LEFT OUTER JOIN task_users as f on x.interviewer=f.id LEFT OUTER JOIN sales_agent as g on g.acode=x.ccode  LEFT OUTER JOIN ( select y.userid,sum(xelec) as xelec,sum(xgas) as xgas from sales_report_real y group by y.userid) h on h.userid=g.id  where x.cdate between '$date1' and '$date2' $sfilterq ORDER BY x.coffice,x.cdate desc  ";
if($rxx=mysql_query($qx))
{
	if(($nx=mysql_num_rows($rxx))>0)
		$excel_check=true;
}
if($excel_check)
	$excelbtn="<a href='getExcel.php?date1=".$date1."&date2=".$date2."&sfilter=".$_REQUEST["sfilter"]."' style='color:#000;' target='_blank'>Get Excel</a>";
//end of excell check
$checkdate=false;
if(!empty($date1) && !empty($date2))
{
	$checkdate = true;
	$vname = " From Date Range: <u>".fixdate_comps("d",$date1)."</u> To <u>".fixdate_comps("d",$date2)."</u>";
}
$date1_n=fixdate_comps('invdate_s',$date1);
$date2_n=fixdate_comps('invdate_s',$date2);
$qca="select * from rec_phones where date between '".$date1."' and '".$date2."'";
$allphones=array();
$allxphones=array();
$brxphones=array();
$manphones=array();
$brophones=array();
$syrphones=array();
if($rex=mysql_query($qca))
{
	if(($num_rows=mysql_num_rows($rex))>0)
	{
		while($rexx=mysql_fetch_array($rex))
		{
			$xphone=fixOnSipPhone('family',$rexx["tphone"]);
			$allphones[]=array('id'=>$rexx["id"],'caller'=>stripslashes($rexx["caller"]),'tphone'=>$rexx["tphone"],'date'=>$rexx["date"],'office'=>$rexx["office"]);
			//$allphones[]=$xphone;
			switch($rexx["office"])
			{
				case "1":
				{
					$brophones[]=array('id'=>$rexx["id"],'caller'=>stripslashes($rexx["caller"]),'fphone'=>$xphone,'tphone'=>$rexx["tphone"],'date'=>$rexx["date"],'office'=>$rexx["office"]);
					break;
				}
				case "2":
				{
					$manphones[]=array('id'=>$rexx["id"],'caller'=>stripslashes($rexx["caller"]),'fphone'=>$xphone,'tphone'=>$rexx["tphone"],'date'=>$rexx["date"],'office'=>$rexx["office"]);
					break;
				}
				case "3":
				{
					$brxphones[]=array('id'=>$rexx["id"],'caller'=>stripslashes($rexx["caller"]),'fphone'=>$xphone,'tphone'=>$rexx["tphone"],'date'=>$rexx["date"],'office'=>$rexx["office"]);
					break;
				}
				case "8":
				{
					$syrphones[]=array('id'=>$rexx["id"],'caller'=>stripslashes($rexx["caller"]),'fphone'=>$xphone,'tphone'=>$rexx["tphone"],'date'=>$rexx["date"],'office'=>$rexx["office"]);
					break;
				}
			}
		}
		$allxphones[1]=$brophones;
		$allxphones[2]=$manphones;
		$allxphones[3]=$brxphones;
		$allxphones[8]=$syrphones;
	}
}
$numcax=sizeof($allphones);
unset($_SESSION["parray"]);
?>