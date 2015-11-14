<?php
session_start();
include "include/config.php";
include "include/function.php";
ini_set('max_execution_time', 300);
ini_set("memory_limit","500M");
require_once('include/excel/MySqlExcelBuilder.class.php');
$date = date('Y-m-d');
// Intialize the object with the database variables
$database=$vmdb;
$user=$vmuser;
$pwd=$vmpass;
$date1=$_REQUEST["date1"];
$date2=$_REQUEST["date2"];
if(!empty($date1) && !empty($date2))
{
	$date1=fixdate_comps('mildate',$_REQUEST["date1"]);
	$date2=fixdate_comps('mildate',$_REQUEST["date2"]);
}
else
{
	$todayx=$date;
	$date1=getFirstDay($date);
	$date2=getLastDay($date);
}
//$line_user=getExcelUser($date1,$date2);
//$line_phone=getExcelPhone($date1,$date2);
//echo $line_user;
//$line_user="'2410','2399','2400'";
$mysql_xls=new MySqlExcelBuilder($database,$user,$pwd);
// Setup the SQL Statements
$sql_statement=<<<END_OF_SQL
SELECT 
distinct 
a.name as Entered_In,
x.cname as Name, 
x.ccode as Agent_Code, 
x.ocall as Called_For_Interview,
x.cphone as Phone,
x.cphonex as Phone_2,
x.cdate as Date_Called,
c.name as Source,
x.csource_title as Source_Information,
DATE_FORMAT(x.idate, '%m/%d/%Y') as Date_For_Interview,
DATE_FORMAT(x.itime, '%l:%i %p') as Time_For_Interview,
e.name as Interview_Office,
x.int_show_info as Note,
DATE_FORMAT(x.int_show_date, '%m/%d/%Y %l:%i %p') as Date_Shown_For_Interview,
f.name as Manager,
b.name as Status,
DATE_FORMAT(x.orientation, '%m/%d/%Y %l:%i %p') as Date_For_Orientation,
d.name as Orientation_Office,
x.orientation_show as Shown_For_Orientation,
x.ori_show_info as Orientation_Note,
DATE_FORMAT(x.orientation_comp, '%m/%d/%Y') as Orientation_Completed_Date,
h.xelec as Electrical, 
h.xgas as Gas
FROM 
rec_entries as x  
LEFT OUTER JOIN 
rec_office as a
on 
x.coffice=a.id 
LEFT OUTER JOIN 
rec_status as b
on 
x.status=b.id 
LEFT OUTER JOIN 
rec_source as c
on 
x.csource=c.id 
LEFT OUTER JOIN 
rec_office as d
on 
x.orientation_office=d.id 
LEFT OUTER JOIN 
rec_office as e
on 
x.office=e.id  
LEFT OUTER JOIN 
task_users as f
on 
x.interviewer=f.id   
LEFT OUTER JOIN 
sales_agent as g
on 
g.acode=x.ccode  
LEFT OUTER JOIN 
(
select y.userid,sum(xelec) as xelec,sum(xgas) as xgas from sales_report_real y group by y.userid) h 
on h.userid=g.id  
where x.cdate between '$date1' and '$date2' 
ORDER BY 
x.coffice,x.cdate desc 
END_OF_SQL;

// Add the SQL statements to the spread sheet
//echo "<br/>".$sql_statement;
$mysql_xls->add_page('Recruitment Entries',$sql_statement,'','A',2);
// Get the spreadsheet after the SQL statements are built...
$phpExcel = $mysql_xls->getExcel(); // This needs to come after all the pages have been added.
$phpExcel->setActiveSheetIndex(0); // Set the sheet back to the first page.
// Write the spreadsheet file...
$objWriter = PHPExcel_IOFactory::createWriter($phpExcel, 'Excel5'); // 'Excel5' is the oldest format and can be read by old programs.
$fname = "Stats_Entry.xls";
$objWriter->save($fname);
if($rxx=mysql_query($sql_statement))
{
	if(($numxx=mysql_num_rows($rxx))>0)
	{
		header("location:$fname");
		exit;
	}
	else
	{
		$_SESSION["recresult"]="ERROR: Invalid Excel Entry";
		header("location:viewstats.php");
		exit;
	}
}
else
{
	$_SESSION["recresult"]="ERROR: Invalid Excel Entry";
	header("location:viewstats.php");
	exit;
}
include "include/unconfig.php";
?>