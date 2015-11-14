<?php
session_start();
ini_set('max_execution_time', 300);
ini_set("memory_limit","500M");
include "include/config.php";
include "include/function.php";
require_once('include/excel/MySqlExcelBuilder.class.php');
$date = date('Y-m-d');
$ndays =getNDays();
$xoff =$_REQUEST["xoff"];
$xtype=$_REQUEST["xtype"];
$xdate=@fixdate_comps("mildate",$_REQUEST["xdate"]);
if($xtype=="inter")
	$sdateq_ex = "a.idate"; //for spreadsheet script
else
	$sdateq_ex = "a.orientation";
$dateq_ex = " where catid='1' and $sdateq_ex=CURDATE()+ INTERVAL ".$ndays. " DAY ";//default date for spreadsheet
$restr_ex = " and a.folstatus !='3' and (a.status !='8' and a.status !='9') ";//spreadsheet
$query_ex=" ";
if(!empty($xdate))
	$dateq_ex = " where catid='1' and $sdateq_ex like '$xdate%' ";
if(!empty($dateq_ex))
{
	$query_ex =$dateq_ex;
}
if(!empty($xoff))
{
	if($xoff !="all")
		$query_ex .= "  and a.office='".base64_decode($xoff)."' ";
}
if(!empty($xtype))
{
	if($xtype=="inter")
	{
		$query_ex .=" and a.status='1' ";
		$v_ex= " order by a.idate desc, a.itime desc ";
	}
	else
	{
		$query_ex .=" and a.status='3' ";
		$v_ex = " order by a.orientation desc ";
	}
}
else
{
	$query_ex .=" and a.status='3' ";
	$v_ex = " order by a.orientation desc ";
}
$query_ex .=" $restr_ex ".$v_ex;
// Intialize the object with the database variables
$database=$vmdb;
$user=$vmuser;
$pwd=$vmpass;
$mysql_xls=new MySqlExcelBuilder($database,$user,$pwd);
// Setup the SQL Statements
if(empty($xoff) && empty($xtype) && empty($xdate))
{
$sql_statement=<<<END_OF_SQL
SELECT 
c.name as Status, 
b.name as Category, 
a.cname as Name,
a.cphone as Phone_1, 
a.orientation as Date_For_Orientation,
DATE_FORMAT(a.orientation, '%m/%d/%Y %l:%i %p') as Date_For_Orientation,
j.name as Office_For_Orientation
FROM 
rec_entries as a  
LEFT OUTER JOIN 
task_category as b 
on 
a.catid=b.id 
LEFT OUTER JOIN 
rec_status as c 
on c.id=a.status
LEFT OUTER JOIN 
rec_office as j 
on j.id=a.orientation_office 
WHERE orientation=CURDATE()+ INTERVAL $ndays DAY and status='3' and folstatus !='3' and (status !='8' and status !='9') and a.catid='1' 
ORDER BY 
a.date 
END_OF_SQL;
}
else
{
$sql_statement=<<<END_OF_SQL
SELECT 
c.name as Status, 
b.name as Category, 
a.cname as Name,
a.cphone as Phone_1, 
DATE_FORMAT(a.idate, '%m/%d/%Y') as Date_For_Interview,
DATE_FORMAT(a.itime, '%l:%i %p') as Time_For_Interview,
h.name as Office
FROM 
rec_entries as a  
LEFT OUTER JOIN 
task_category as b 
on 
a.catid=b.id 
LEFT OUTER JOIN 
rec_status as c 
on c.id=a.status
LEFT OUTER JOIN 
rec_office as h 
on h.id=a.office 
$query_ex 
END_OF_SQL;
}
// Add the SQL statements to the spread sheet
$mysql_xls->add_page('Followup Entries',$sql_statement,'','A',2);
// Get the spreadsheet after the SQL statements are built...
$phpExcel = $mysql_xls->getExcel(); // This needs to come after all the pages have been added.
$phpExcel->setActiveSheetIndex(0); // Set the sheet back to the first page.
// Write the spreadsheet file...
$objWriter = PHPExcel_IOFactory::createWriter($phpExcel, 'Excel5'); // 'Excel5' is the oldest format and can be read by old programs.
$fname = "RecruiterEntries_Followup_".$date.".xls";
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
		header("location:viewpend.php");
		exit;
	}
}
else
{
	$_SESSION["recresult"]="ERROR: Invalid Excel Entry";
	header("location:viewpend.php");
	exit;
}
include "include/unconfig.php";
?>