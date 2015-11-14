<?Php
session_start();
include "include/config.php";
include "include/function.php";
ini_set('memory_limit','500M');
//echo $userx["userid"]."<br/>";
//$userx = getOnSip('lxexexaxd@familyenergy.onsip.com');
date_default_timezone_set('America/New_York');
$date1=date("2012-06-01");
$date2=date("2012-06-31");
//$today="2012-04-12";
$rows=getStatusEntryPhone($oid,$date1,$date2,"");
$rows=sortArray('tphone',$rows);
/*for($i=0;$i<sizeof($xrow);$i++)
{
	$c=$i+1;
	echo $c.">> ".$xrow[$i]["id"]." ".$xrow[$i]["caller"]." ".$xrow[$i]["tphone"]." ".$xrow[$i]["date"]." ".$xrow[$i]["office"]."<br/>";
}*/
for($i=0;$i<sizeof($rows);$i++)
{
	$c=$i+1;
	echo $c.">> ".$rows[$i]["id"]." ".$rows[$i]["caller"]." ".$rows[$i]["tphone"]." ".$rows[$i]["date"]." ".$rows[$i]["office"]."<br/>";
}
include "include/unconfig.php";
?>