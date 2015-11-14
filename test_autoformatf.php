<?php
session_start();
ini_set('memory_limit', '500M');
include "include/config.php";
include "include/function.php";
date_default_timezone_set('America/New_York');
$today=date("Y-m-d");
echo "----------------fix unknown cause------------------------<br/>";
$today = $today." ".date("H:m:s");
$query="SELECT * FROM rec_entries WHERE orientation_comp is not null and status !='4' and status !='6' order by id";
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
			if($rows["int_show"]=='yes' && empty($rows["int_show_date"]))
			{
				//echo "..set hired status<br/>";
				$qx="update rec_entries set int_show_date='".$rows["idate"]." ".$rows["itime"]."' where id='".$rows["id"]."'";
				if($rxx=mysql_query($qx))
					echo "..int show date fix..";
				else
					echo "..int show date can't fix..";
				//echo "..set<br/>";
			}
			echo "<br/>";
			$count++;
		}
	}
}
include "include/unconfig.php";
?>