<?php
session_start();
ini_set('memory_limit', '500M');
include "include/config.php";
include "include/function.php";
date_default_timezone_set('America/New_York');
$today=date("Y-m-d");
$today = $today." ".date("H:m:s");
echo "----------------fix timeline for rows 9261-9390------------------------<br/>";
$query="SELECT * FROM rec_timeline WHERE id between '9261' and '9390' order by id";
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
			$status=$rows["status"];
			switch($status)
			{
				case '1':
				{
					$xdate=$rows["xdate"];
					$qx="update rec_timeline set date='".$xdate."' where id='".$rows["id"]."'";
					if($rx=mysql_query($qx))
						echo "...status 1 date changed to ".$xdate;
					else
						echo "...status 1 date can't be changed";
					break;
				}
				case '10':
				{
					$xdate=$rows["xdate"];
					$qx="update rec_timeline set date='".$xdate."', xdate=NULL where id='".$rows["id"]."'";
					if($rx=mysql_query($qx))
						echo "...status 10 xdate removed and date changed to ".$xdate;
					else
						echo "...status 10 xdate can't be changed";
					break;
				}
				case '3':
				{
					$xdate=$rows["xdate"];
					$qx="update rec_timeline set date='".$xdate."' where id='".$rows["id"]."'";
					if($rx=mysql_query($qx))
						echo "...status 3 date changed to ".$xdate;
					else
						echo "...status 3 date can't be changed";
					break;
				}
				case '11':
				{
					$xdate=$rows["xdate"];
					$qx="update rec_timeline set date='".$xdate."' where id='".$rows["id"]."'";
					if($rx=mysql_query($qx))
						echo "...status 11 date changed to ".$xdate;
					else
						echo "...status 11 date can't be changed";
					break;
				}
				case '7':
				{
					$xdate=$rows["xdate"];
					$qx="update rec_timeline set date='".$xdate."' where id='".$rows["id"]."'";
					if($rx=mysql_query($qx))
						echo "...status 7 date changed to ".$xdate;
					else
						echo "...status 7 date can't be changed";
					break;
				}
				case '17':
				{
					$xdate=$rows["xdate"];
					$qx="update rec_timeline set date='".$xdate."', xdate=NULL where id='".$rows["id"]."'";
					if($rx=mysql_query($qx))
						echo "...status 17 date changed to ".$xdate." and xdate removed";
					else
						echo "...status 17 date can't be changed";
					break;
				}
				case '5':
				{
					$xdate=$rows["xdate"];
					$qx="update rec_timeline set date='".$xdate."', xdate=NULL where id='".$rows["id"]."'";
					if($rx=mysql_query($qx))
						echo "...status 5 date changed to ".$xdate." and xdate removed";
					else
						echo "...status 5 date can't be changed";
					break;
				}
				default:
					echo "..no date to remove..";
			}
			echo "<br/>";
			$count++;
		}
	}
}
include "include/unconfig.php";
?>