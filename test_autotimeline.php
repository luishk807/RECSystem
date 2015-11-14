<?php
session_start();
ini_set('memory_limit', '500M');
include "include/config.php";
include "include/function.php";
$query="select * from rec_entries order by id";
if($result=mysql_query($query))
{
	if(($num_rows=mysql_num_rows($result))>0)
	{
		$count=1;
		while($rows=mysql_fetch_array($result))
		{
			echo "<br/>";
			echo $count.". enter: ".$rows["id"]." ".$rows["cname"]."<br/>";
			setTimeLinex($rows["id"]);
			//echo $test_check;
			$count++;
		}
	}
}
include "include/unconfig.php";
?>