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
$mysql_xls=new MySqlExcelBuilder($database,$user,$pwd);
// Setup the SQL Statements
$sql_statement=<<<END_OF_SQL
SELECT 
a.id,
b.name as Category, 
c.name as Status,
a.ccode as Agent_Code, 
a.cname as Name,
a.email as Email, 
a.cphone as Phone_1, 
a.cphonex as Phone_2, 
a.idate as Interview_Date, 
a.itime as Interview_Time, 
a.address as Address, 
a.city as City, 
a.state as State, 
a.country as Country, 
a.zip as Zip_Postal_Code,
h.name as Office_Assigned,
i.name as Source_Name,
a.csource_title as Source_Info,
a.int_show as Shown_For_Interview,
a.int_show_info as Interview_Note,
a.int_show_date as Interview_Shown_Date,
a.hired as Hired,
d.name as Interviewer,
a.interview_note as Interviewer_Note,
a.orientation as Date_For_Orientation,
j.name as Office_For_Orientation,
a.orientation_show as Show_For_Orientation,
a.orientation_comp as Orientation_Completed,
a.img as Agent_Photo_URL,
g.name as Follow_Up_Status,
a.folcome as Follow_Up_Come,
a.folupdated_date as Follow_Up_Date,
a.enotx as Entry_Email_Notification,
a.textnotx as Entry_Text_SMS_Notification,
a.eornotx as Entry_Orientation_Email_Notification,
a.textornotx as Entry_Orientation_Text_SMS_Notification,
e.name as Created_By,
f.name as Updated_By,
a.date as Date_created
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
task_users as d 
on d.id=a.interviewer
LEFT OUTER JOIN 
task_users as e 
on e.id=a.createdby 
LEFT OUTER JOIN 
task_users as f 
on f.id=a.updatedby  
LEFT OUTER JOIN 
rec_followup as g 
on g.id=a.folstatus 
LEFT OUTER JOIN 
rec_office as h 
on h.id=a.office 
LEFT OUTER JOIN 
rec_source as i 
on i.id=a.csource 
LEFT OUTER JOIN 
rec_office as j 
on j.id=a.orientation_office 
ORDER BY 
a.id 
END_OF_SQL;
// Add the SQL statements to the spread sheet
$mysql_xls->add_page('Recruitment Entries',$sql_statement,'','A',2);
// Get the spreadsheet after the SQL statements are built...
$phpExcel = $mysql_xls->getExcel(); // This needs to come after all the pages have been added.
$phpExcel->setActiveSheetIndex(0); // Set the sheet back to the first page.
// Write the spreadsheet file...
$objWriter = PHPExcel_IOFactory::createWriter($phpExcel, 'Excel5'); // 'Excel5' is the oldest format and can be read by old programs.
$fname = "RecruiterEntries".$date.".xls";
$objWriter->save($fname);
// Make it available for download.
$excelbtn="<a href=\"$fname\">Download $fname</a>";
?>