<?php
$excelbtn="";
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
distinct i.name as Office, 
x.fphone as Phone_Number, 
a.cphone as Phone_1,  
a.cname as Name, 
a.ccode as Agent_Code, 
f.name as Manager, 
h.name as Status, 
DATE_FORMAT(a.idate, '%m/%d/%Y') as Date_For_Interview,
DATE_FORMAT(a.itime, '%l:%i %p') as Time_For_Interview,
d.name as Interview_Office,
DATE_FORMAT(a.orientation, '%m/%d/%Y %l:%i %p') as Date_For_Orientation,
e.name as Orientation_Office,
DATE_FORMAT(a.orientation_comp, '%m/%d/%Y') as Orientation_Completed_Date,
c.xelec as Electrical, 
c.xgas as Gas
FROM 
rec_phones as x  
LEFT OUTER JOIN 
rec_entries as a
on 
x.fphone=a.cphonex 
LEFT OUTER JOIN 
sales_agent as b 
on 
a.ccode=b.acode 
LEFT OUTER JOIN 
(
select y.userid,sum(xelec) as xelec,sum(xgas) as xgas from sales_report_real y group by y.userid) c 
on c.userid=b.id 
LEFT OUTER JOIN 
rec_office as d 
on a.office=d.id 
LEFT OUTER JOIN 
rec_office as e 
on a.orientation_office=e.id 
LEFT OUTER JOIN 
task_users as f 
on a.interviewer=f.id 
LEFT OUTER JOIN 
rec_timeline as g 
on a.id=g.entryid 
LEFT OUTER JOIN 
rec_status as h 
on h.id=g.status 
LEFT OUTER JOIN 
rec_office as i 
on i.id=x.office 
where x.date between '$date1' and '$date2' 
ORDER BY 
x.office,a.cdate desc 
END_OF_SQL;

// Add the SQL statements to the spread sheet
//echo "<br/>".$sql_statement;
$mysql_xls->add_page('Recruitment Entries',$sql_statement,'','A',2);
// Get the spreadsheet after the SQL statements are built...
$phpExcel = $mysql_xls->getExcel(); // This needs to come after all the pages have been added.
$phpExcel->setActiveSheetIndex(0); // Set the sheet back to the first page.
// Write the spreadsheet file...
$objWriter = PHPExcel_IOFactory::createWriter($phpExcel, 'Excel5'); // 'Excel5' is the oldest format and can be read by old programs.
$fname = "Stats_".$date1."-".$date2.".xls";
$objWriter->save($fname);
if($rxx=mysql_query($sql_statement))
{
	if(($numxx=mysql_num_rows($rxx))>0)
		$excelbtn="[<a href=\"$fname\" style='color:#000;'>Excel $fname</a>]<br/>";
	else
		$excelbtn="";
}
else
	$excelbtn="";
// Make it available for download.
//$excelbtn="<a href=\"$fname\">Download $fname</a>";
?>