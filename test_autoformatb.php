<?php
session_start();
ini_set('memory_limit', '500M');
include "include/config.php";
include "include/function.php";
date_default_timezone_set('America/New_York');
$today=date("Y-m-d");
echo "----------------set orientation no show------------------------<br/>";
$today = $today." ".date("H:m:s");
$query="select * from rec_entries where status='3' and orientation < '".$today."' and ori_show is null order by id";
//echo $query."<br/>";
if($result=mysql_query($query))
{
	if(($num_rows=mysql_num_rows($result))>0)
	{
		$count=1;
		while($rows=mysql_fetch_array($result))
		{
			//echo "<br/>";
			echo $count.". enter: ".$rows["id"]." ".$rows["cname"]." orienatation:".$rows["orientation"]."";
			if(empty($rows["orientation_show"]))
			{
				//$qx="update rec_entries set ori_show='no',status='18',ori_show_info='No Show' where id='".$rows["id"]."'";
				//if($rx=mysql_query($qx))
				//{
				//	$qxx="insert ignore into rec_timeline(entryid,status,date)values('".$rows["id"]."','18','".$rows["orientation"]."')";
				//	@mysql_query($qxx);
					echo "..No Show Done<br/>";
				//}
				//else
				//	echo "..can't be done<br/>";
			}
			else
			{
				//$qx="update rec_entries set ori_show='yes',ori_show_info=NULL where id='".$rows["id"]."'";
				//if($rx=mysql_query($qx))
				//{
				//	$qxx="insert ignore into rec_timeline(entryid,status,xdate,date)values('".$rows["id"]."','11','".$rows["orientation_show"]."','".$rows["orientation_show"]."')";
				//	@mysql_query($qxx);
					echo "..Show done<br/>";
				//}
				//else
				//	echo "..can't be done<br/>";
			}
			$count++;
		}
	}
}
sleep(5);
$today=date("Y-m-d");
echo "----------------set orientation incompleted------------------------<br/>";
$query="select * from rec_entries where status='3' and orientation_show < '".$today."' and ori_comp is null order by id";
if($result=mysql_query($query))
{
	if(($num_rows=mysql_num_rows($result))>0)
	{
		$count=1;
		while($rows=mysql_fetch_array($result))
		{
			//echo "<br/>";
			echo $count.". enter: ".$rows["id"]." ".$rows["cname"]." orientation_show:".$rows["orientation_show"]."";
			if(empty($rows["orientation_comp"]))
			{
				//$qx="update rec_entries set ori_comp='no',status='19',ori_comp_info='Imcompleted' where id='".$rows["id"]."'";
				//if($rx=mysql_query($qx))
				//{
				//	$qxx="insert ignore into rec_timeline(entryid,status,date)values('".$rows["id"]."','19','".$rows["orientation_show"]."')";
				//	@mysql_query($qxx);
					echo "..Incomplete Done<br/>";
				//}
				//else
				//	echo "..can't be done<br/>";
			}
			else
			{
				//$qx="update rec_entries set ori_comp='yes',ori_show_info=NULL where id='".$rows["id"]."'";
				//if($rx=mysql_query($qx))
				//{
				//	$qxx="insert ignore into rec_timeline(entryid,status,xdate,date)values('".$rows["id"]."','19','".$rows["orientation_show"]."','".$rows["orientation_show"]."')";
				//	@mysql_query($qxx);
					echo "..Completed Done<br/>";
				//}
				//else
				//	echo "..can't be done<br/>";
			}
			$count++;
		}
	}
}
include "include/unconfig.php";
?>