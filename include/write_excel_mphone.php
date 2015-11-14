<?php
$excelbtn="";
require_once('include/excel/MySqlExcelBuilder.class.php');
$date = date('Y-m-d');
// Intialize the object with the database variables
$database=$vmdb;
$userx=$vmuser;
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
$mysql_xls=new MySqlExcelBuilder($database,$userx,$pwd);
// Setup the SQL Statements
$sql_statement=<<<END_OF_SQL
SELECT 
distinct 
x.fphone as Phone, 
b.name as Name,
a.date,
x.caller as Caller 
from rec_phones as x  
LEFT OUTER JOIN 
rec_phones as a
on
x.id=a.id 
LEFT OUTER JOIN 
rec_office as b
on
x.office=b.id 
where x.date between '$date1' and '$date2' and x.caller != 'Anonymous' 
order by x.date desc, x.office 
END_OF_SQL;

// Add the SQL statements to the spread sheet
//echo "<br/>".$sql_statement;
$mysql_xls->add_page('Recruitment Entries',$sql_statement,'','A',2);
// Get the spreadsheet after the SQL statements are built...
$phpExcel = $mysql_xls->getExcel(); // This needs to come after all the pages have been added.
$phpExcel->setActiveSheetIndex(0); // Set the sheet back to the first page.
// Write the spreadsheet file...
$objWriter = PHPExcel_IOFactory::createWriter($phpExcel, 'Excel5'); // 'Excel5' is the oldest format and can be read by old programs.
$monthx=date('F Y',strtotime($date1));
$fname = "Excel ".$monthx.".xls";
$objWriter->save($fname);
if($rxx=mysql_query($sql_statement))
{
	if(($numxx=mysql_num_rows($rxx))>0)
		$excelbtn="[<a href=\"$fname\" style='color:#000;'>$fname</a>]&nbsp;";
	else
		$excelbtn="";
}
else
	$excelbtn="";
// Make it available for download.
//$excelbtn="<a href=\"$fname\">Download $fname</a>";
?>