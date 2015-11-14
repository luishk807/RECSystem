<?php
session_start();
ini_set('memory_limit', '500M');
include "include/config.php";
include "include/function.php";
date_default_timezone_set('America/New_York');
$today=date("Y-m-d");
$today = $today." ".date("H:m:s");
echo "----------------fix hired status, remove hired when no agent code is given------------------------<br/>";
$query="SELECT * FROM rec_timeline WHERE status='5'";
//echo $query."<br/>";
if($result=mysql_query($query))
{
	if(($num_rows=mysql_num_rows($result))>0)
	{
		$count=1;
		while($rows=mysql_fetch_array($result))
		{
			//echo "<br/>";
			echo $count.". check: ".$rows["entryid"]." ".getEntryInfo('name',$rows["entryid"]);
			$checkacode=getEntryInfo('ccode',$rows["entryid"]);
			if(empty($checkacode))
			{
				$qx="delete from rec_timeline where id='".$rows["id"]."'";
				if($rx=mysql_query($qx))
					echo "..agent code:".$checkacode." hired status removed..";
				else
					echo "..hire status can't be removed..";
			}
			else
				echo "..agent code:".$checkacode." hire status kept..";
			echo "<br/>";
			$count++;
		}
	}
}
echo "<br/><br/>----------------check entries if agent code exists, add hired if they do------------------------<br/>";
$query="SELECT * FROM rec_entries WHERE ccode is not null";
//echo $query."<br/>";
if($result=mysql_query($query))
{
	if(($num_rows=mysql_num_rows($result))>0)
	{
		$count=1;
		while($rows=mysql_fetch_array($result))
		{
			//echo "<br/>";
			echo $count.". check: ".$rows["entryid"]." ".getEntryInfo('name',$rows["entryid"]);
			if(!empty($rows["ccode"]))
			{
				setTimeLine($rows["id"],'17','');
				setTimeLine($rows["id"],'5','');
				echo "..agentcode: ".$rows["ccode"]." hired status added..";
			}
			else
				echo "..hire status not added..";
			echo "<br/>";
			$count++;
		}
	}
}
include "include/unconfig.php";
?>