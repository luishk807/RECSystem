<?php
session_start();
ini_set('memory_limit', '500M');
include "include/config.php";
include "include/function.php";
date_default_timezone_set('America/New_York');
$today=date("Y-m-d");
echo "----------------set hired------------------------<br/>";
$today = $today." ".date("H:m:s");
$query="select * from rec_entries where status='5' order by id";
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
			if(!empty($rows["orientation_show"]))
			{
				//echo "..set hired status<br/>";
				$qx="update rec_entries set ori_show='yes', ori_show_info=NULL where id='".$rows["id"]."'";
				if($rxx=mysql_query($qx))
					echo "..orientation fix..";
				else
					echo "..orientation can't fix..";
				//echo "..set<br/>";
			}
			if(!empty($rows["orientation_comp"]))
			{
				//echo "..set hired status<br/>";
				$qx="update rec_entries set ori_comp='yes', ori_comp_info=NULL where id='".$rows["id"]."'";
				if($rxx=mysql_query($qx))
					echo "..orientation comp fix..";
				else
					echo "..orientation comp can't fix..";
				//echo "..set<br/>";
			}
			echo "<br/>";
			$count++;
		}
	}
}
include "include/unconfig.php";
?>