<?Php
session_start();
include "include/config.php";
include "include/function.php";
//$id = base64_decode($_REQUEST["id"]);
$id = $_REQUEST["id"];
if(empty($id) || !isset($_SESSION["rec_user"]))
{
	echo "Invalid Entry";
}
else
{
$user = $_SESSION["rec_user"];
$task = $_REQUEST["checkprocess"];
$fdate =$_REQUEST["fdate"];
$fhour = $_REQUEST["fhour"];
$fminute = $_REQUEST["fminute"];
$fampm = $_REQUEST["fampm"];
$checkcome = $_REQUEST["checkcome"];
$ccome = $_REQUEST["ccome"];
$ftime = fixtomilhour($fhour.":".$fminute." ".$fampm);
$fnote = $_REQUEST["fnote"];
$query = "update rec_entries set ";
if($task=="3")
{
	$ccome_q="";
	$query .=" folstatus='3'";
	if(!empty($fdate) && !empty($ftime))
		$newtime = $fdate." ".$ftime;
	if($checkcome=="yes")
	{
		$savecome = " folcome='".$ccome."' ,";
		if($ccome=='no')
		{
			$ccome_q=", int_show='no', status='20',int_show_info='".clean($fnote)."'";
			setTimeLine($id,'20','');
		}
	}
	$query .=" ,compdate='".$newtime."', $savecome compnote='".clean($fnote)."', folupdated_by='".$user["id"]."',folupdated_date=NOW() $ccome_q  where id='".$id."'";
  if($result = mysql_query($query))
  	echo "SUCCESS: Follow Up Changes Saved";
  else
  	echo "ERROR: Unable To Save Follow Up Changes";
}
else if($task=="2")
{
	$query .=" folstatus='2'";
	if(!empty($fdate) && !empty($ftime))
		$newtime = $fdate." ".$ftime;
	$query .=" ,foldate='".$newtime."', folnote='".clean($fnote)."',folupdated_by='".$user["id"]."',folupdated_date=NOW() where id='".$id."'";
	if($result = mysql_query($query))
  		echo "SUCCESS: Completed Changes Saved";
 	 else
  		echo "ERROR: Unable To Save Conmpleted Changes";
}
else if($task=="1")
{
	$query .=" folstatus='1' ";
	$query .=" ,folupdated_by='".$user["id"]."',folupdated_date=NOW() where id='".$id."'";
	if($result = mysql_query($query))
  		echo "SUCCESS: Changes Saved";
 	 else
  		echo "ERROR: Unable To Save Changes";
}
else
	echo "Invalid Entry";
}
include "include/unconfig.php";
?>