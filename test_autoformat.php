<?php
session_start();
ini_set('memory_limit', '500M');
include "include/config.php";
include "include/function.php";
date_default_timezone_set('America/New_York');
$today=date("Y-m-d");
echo "----------------set Interviews no show------------------------<br/>";
$query="select * from rec_entries where status='1' and idate < '".$today."' order by id";
if($result=mysql_query($query))
{
	if(($num_rows=mysql_num_rows($result))>0)
	{
		$count=1;
		while($rows=mysql_fetch_array($result))
		{
			//echo "<br/>";
			echo $count.". enter: ".$rows["id"]." ".$rows["cname"]." idate:".$rows["idate"]."<br/>";
			/*$qx="update rec_entries set status='8', int_show='no', int_show_info='No Show' where id='".$rows["id"]."'";
			@mysql_query($qx);
			$qx="insert ignore into rec_timeline(entryid,status,date)values('".$rows["id"]."','8','".$rows["idate"]." ".$rows["itime"]."')";
			@mysql_query($qx);*/
			//echo $test_check;
			$count++;
		}
	}
}
sleep(5);
echo "----------------set orientation no show------------------------<br/>";
$today = $today." ".date("H:m:s");
$query="select * from rec_entries where status='3' and ori_show !='yes' and orientation < '".$today."' order by id";
echo $query."<br/>";
if($result=mysql_query($query))
{
	if(($num_rows=mysql_num_rows($result))>0)
	{
		$count=1;
		while($rows=mysql_fetch_array($result))
		{
			//echo "<br/>";
			echo $count.". enter: ".$rows["id"]." ".$rows["cname"]." orienatation:".$rows["orientation"]."<br/>";
			/*$qx="update rec_entries set status='18', ori_show='no', ori_show_info='No Show' where id='".$rows["id"]."'";
			@mysql_query($qx);
			$qx="insert ignore into rec_timeline(entryid,status,date)values('".$rows["id"]."','18','".$rows["orientation"]."')";
			@mysql_query($qx);*/
			//echo $test_check;
			$count++;
		}
	}
}
sleep(5);
$today=date("Y-m-d");
echo "----------------set orientation incompleted------------------------<br/>";
$query="select * from rec_entries where status='3' and ori_show ='yes' orientation_show < '".$today."' order by id";
if($result=mysql_query($query))
{
	if(($num_rows=mysql_num_rows($result))>0)
	{
		$count=1;
		while($rows=mysql_fetch_array($result))
		{
			//echo "<br/>";
			echo $count.". enter: ".$rows["id"]." ".$rows["cname"]." orientation_show:".$rows["orientation_show"]."<br/>";
			/*$qx="update rec_entries set status='19', ori_comp='no', ori_comp_info='Incompleted' where id='".$rows["id"]."'";
			@mysql_query($qx);
			$qx="insert ignore into rec_timeline(entryid,status,date)values('".$rows["id"]."','19','".$rows["orientation_show"]."')";
			@mysql_query($qx);*/
			//echo $test_check;
			$count++;
		}
	}
}
include "include/unconfig.php";
?>