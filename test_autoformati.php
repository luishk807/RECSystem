<?php
session_start();
ini_set('memory_limit', '500M');
include "include/config.php";
include "include/function.php";
date_default_timezone_set('America/New_York');
$today=date("Y-m-d");
$today = $today." ".date("H:m:s");
$users=array();
echo "----------------fix entries and timeline for rows 3123-3245------------------------<br/>";
$query="SELECT * FROM rec_entries WHERE id between '3123' and '3245' order by id";
if($result=mysql_query($query))
{
	if(($num_rows=mysql_num_rows($result))>0)
	{
		while($rows=mysql_fetch_array($result))
			$users[]=array('id'=>$rows["id"],'name'=>$rows["cname"],'acode'=>$rows["ccode"]);
	}
}
//echo $query."<br/>";
if(sizeof($users)>0)
{
	for($i=0;$i<sizeof($users);$i++)
	{
		$count=$i+1;
		$destroy=array();
		echo $count.".checking ".stripslashes($users[$i]["name"])." id: ".$users[$i]["id"]." acode: ".$users[$i]["acode"]."..";
		$query="select * from rec_entries where ccode='".$users[$i]["acode"]."' and id !='".$users[$i]["id"]."' order by id";
		if($result=mysql_query($query))
		{
			if(($num_rows=mysql_num_rows($result))>0)
			{
				$setid=$users[$i]["id"];
				while($rows=mysql_fetch_array($result))
				{
					if($rows["id"] < $setid)
					{
						$destroy[]=array('id'=>$setid);
						$setid=$rows["id"];
					}
				}
			}
		}
		if(sizeof($destroy)>0)
		{
			echo "..found multiple: ".sizeof($destroy)." will set as primarty: ".$setid."..<br/>";
			for($x=0;$x<sizeof($destroy);$x++)
			{
				echo "------>destroy: ".$destroy[$x]["id"]."..<br/>";
				/*$qx="delete from rec_timeline where entryid='".$destroy[$x]["id"]."'";
				if($rx=mysql_query($qx))
				{
					echo "..bad entry timeline deleted!...";
					$qxx="delete from rec_entries where id='".$destroy[$x]["id"]."'";
					if($rxx=mysql_query($qxx))
						echo "..bad entry deleted!<br/>";
					else
						echo "..unable to detele bad entry but timeline deleted!<br/>";
				}
				else
					echo "..unable to delete bad entry and timeline!<br/>";*/
			}
		}
		else
			echo "..no multiple found!<br/>";
		/*while($rows=mysql_fetch_array($result))
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
		}*/
	}
}
include "include/unconfig.php";
?>