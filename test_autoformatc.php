<?php
session_start();
ini_set('memory_limit', '500M');
include "include/config.php";
include "include/function.php";
date_default_timezone_set('America/New_York');
$today=date("Y-m-d");
echo "----------------set office------------------------<br/>";
$today = $today." ".date("H:m:s");
$query="select * from rec_entries where coffice is null order by id";
//echo $query."<br/>";
if($result=mysql_query($query))
{
	if(($num_rows=mysql_num_rows($result))>0)
	{
		$count=1;
		while($rows=mysql_fetch_array($result))
		{
			//echo "<br/>";
			echo $count.". enter: ".$rows["id"]." ".$rows["cname"];
			if($rows["createdby"]=="17" || $rows["createdby"]=='34')
			{
				//set in brooklyn
				$qx="update rec_entries set coffice='1' where id='".$rows["id"]."'";
				echo "..Brooklyn<br/>";
			}
			else if($rows["createdby"]=="1" || $rows["createdby"]=='29' || $rows["createdby"]=='33' || $rows["createdby"]=='66' || $rows["createdby"]=='81')
			{
				//set in manhattan
				$qx="update rec_entries set coffice='2' where id='".$rows["id"]."'";
				echo "..Manhattan<br/>";
			}
			else if($rows["createdby"]=='20' || $rows["createdby"]=='28')
			{
				//set in bronx
				$qx="update rec_entries set coffice='3' where id='".$rows["id"]."'";
				echo "..Bronx<br/>";
			}
			else
			{
				if($rows["office"]=='1')
				{
					echo "..No Found Reset Brooklyn<br/>";
					$qx="update rec_entries set coffice='1' where id='".$rows["id"]."'";
				}
				else if($rows["office"]=='2')
				{
					echo "..No Found Reset Manhattan<br/>";
					$qx="update rec_entries set coffice='2' where id='".$rows["id"]."'";
				}
				else if($rows["office"]=='3')
				{
					echo "..No Found Reset Bronx<br/>";
					$qx="update rec_entries set coffice='3' where id='".$rows["id"]."'";
				}
			}
			@mysql_query($qx);
			$count++;
		}
	}
}
include "include/unconfig.php";
?>