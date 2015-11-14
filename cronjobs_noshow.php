<?php
session_start();
ini_set('memory_limit', '500M');
include "include/config.php";
include "include/function.php";
date_default_timezone_set('America/New_York');
$today=date("Y-m-d");
$query="select * from rec_entries where status='1' and idate < '".$today."' order by id";
if($result=mysql_query($query))
{
	if(($num_rows=mysql_num_rows($result))>0)
	{
		$count=1;
		while($rows=mysql_fetch_array($result))
		{
			//echo "<br/>";
			//echo $count.". enter: ".$rows["id"]." ".$rows["cname"]." idate:".$rows["idate"]."<br/>";
			$qx="update rec_entries set status='8', int_show='no', int_show_info='No Show' where id='".$rows["id"]."'";
			@mysql_query($qx);
			$qx="insert ignore into rec_timeline(entryid,status,date)values('".$rows["id"]."','8','".$rows["idate"]." ".$rows["itime"]."')";
			@mysql_query($qx);
			//echo $test_check;
			$count++;
		}
	}
}
sleep(5);
$today = $today." ".date("H:m:s");
//echo "----------------set orientation missed------------------------<br/>";
$query="select * from rec_entries where status='3' and orientation < '".$today."' and ori_show is null order by id";
if($result=mysql_query($query))
{
	if(($num_rows=mysql_num_rows($result))>0)
	{
		$count=1;
		while($rows=mysql_fetch_array($result))
		{
			if(empty($rows["orientation_show"]))
			{
				$qx="update rec_entries set ori_show='no',status='18',ori_show_info='No Show' where id='".$rows["id"]."'";
				if($rx=mysql_query($qx))
				{
					$qxx="insert ignore into rec_timeline(entryid,status,date)values('".$rows["id"]."','18','".$rows["orientation"]."')";
					@mysql_query($qxx);
				}
			}
			$count++;
		}
	}
}
sleep(5);
$today=date("Y-m-d");
//echo "----------------set orientation incompleted------------------------<br/>";
$query="select * from rec_entries where status='3' and orientation_show < '".$today."' and ori_comp is null order by id";
if($result=mysql_query($query))
{
	if(($num_rows=mysql_num_rows($result))>0)
	{
		$count=1;
		while($rows=mysql_fetch_array($result))
		{
			if(empty($rows["orientation_comp"]))
			{
				$qx="update rec_entries set ori_comp='no',status='19',ori_comp_info='Incompleted' where id='".$rows["id"]."'";
				if($rx=mysql_query($qx))
				{
					$qxx="insert ignore into rec_timeline(entryid,status,date)values('".$rows["id"]."','19','".$rows["orientation_show"]."')";
					@mysql_query($qxx);
				}
			}
			$count++;
		}
	}
}
include "include/unconfig.php";
?>