<?php
$oid=base64_decode($_REQUEST["office"]);
if(empty($oid))
	$oid=0;
$t=base64_decode($_REQUEST["task"]);
$date1=$_REQUEST["date1"];
$date2=$_REQUEST["date2"];
$status=base64_decode($_REQUEST["status"]);
$ascdesc=$_REQUEST["ascdesc"];
$sort=$_REQUEST["sort"];
$ototal_name="";
$farray = array();
$farrayv = array();
$titleb="";
$xparray=$_SESSION["parray"];
//max column is six
$sortq="";
if($ascdesc=="desc")
	$ascdesc="asc";
else
	$ascdesc="desc";
if(!empty($oid))
{
	$namex = getOfficeName($oid);
	$officename = "For ".stripslashes($namex);
}
if(!empty($sort))
	$sortq=" order by $sort $ascdesc";
$phone_page="";
if($t=="phone")
{
	$farray[]="caller";
	$farray[]="tphone";
	$farray[]="office";
	$farray[]="date";
	$farrayv[]="Caller Id";
	$farrayv[]="Phone";
	$farrayv[]="Office";
	$farrayv[]="Date Called";
	//$rows=getStatusEntryPhone($oid,$date1,$date2,$sortq);
	//$rows=sortArray($sort,$rows);
	$parray=$xparray[$oid]["tphone"];
	$rows=sortArray($sort,$parray,$ascdesc);
	$titleb=" For Phone Numbers ";
	$phone_page="&nbsp;[<a href='viewuphone.php' target='_blank'>View Un-Matched Phones</a>]";
}
else if($t=="inter")
{
	$farray[]="name";
	$farray[]="manager";
	$farray[]="ocall";
	$farray[]="idate";
	$farray[]="csource";
	$farray[]="csource_title";
	$farrayv[]="Name";
	$farrayv[]="Interviewer";
	$farrayv[]="from Call";
	$farrayv[]="Int Date";
	$farrayv[]="Source";
	$farrayv[]="Source Info";
	/*if(!empty($oid))
		$rows=getStatusEntry_array($oid,$date1,$date2,'1');
	else
		$rows=getStatusEntry_all_array('1',$date1,$date2);
	$rows=sortArray($sort,$rows);*/
	$parray=$xparray[$oid]["inter"];
	$rows=sortArray($sort,$parray,$ascdesc);
	$titleb=" For Interviews ";
}
else if($t=="orien")
{
	$farray[]="name";
	$farray[]="manager";
	$farray[]="ocall";
	$farray[]="orientation";
	$farray[]="orientation_office";
	$farrayv[]="Name";
	$farrayv[]="Manager";
	$farrayv[]="From Call";
	$farrayv[]="Orient Date";
	$farrayv[]="Office";
	/*if(!empty($oid))
		$rows=getStatusEntry_array($oid,$date1,$date2,'3');
	else
		$rows=getStatusEntry_all_array('3',$date1,$date2);
	$rows=sortArray($sort,$rows);*/
	$parray=$xparray[$oid]["orien"];
	$rows=sortArray($sort,$parray,$ascdesc);
	$titleb=" For Orientation ";
}
else if($t=="orienc")
{
	$farray[]="name";
	$farray[]="manager";
	$farray[]="ocall";
	//$farray[]="orientation";
	$farray[]="ccode";
	$farray[]="orientation_comp";
	$farray[]="orientation_office";
	$farrayv[]="Name";
	$farrayv[]="Manager";
	$farrayv[]="From Call";
	//$farrayv[]="Orient Date";
	$farrayv[]="Agent Code";
	$farrayv[]="Orient Comp";
	$farrayv[]="Office";
	/*if(!empty($oid))
		$rows=getStatusEntry_array($oid,$date1,$date2,'7');
	else
		$rows=getStatusEntry_all_array('7',$date1,$date2);
	$rows=sortArray($sort,$rows);*/
	$parray=$xparray[$oid]["orienc"];
	$rows=sortArray($sort,$parray,$ascdesc);
	$titleb=" For Orientation Completed ";
}
else if($t=="ototal")
{
	$ototal_name=" Sales ";
	$farray[]="userid";
	$farray[]="ccode";
	$farray[]="manager";
	$farray[]="xelec";
	$farray[]="xgas";
	$farrayv[]="Name";
	$farrayv[]="Agent Code";
	$farrayv[]="Manager";
	$farrayv[]="Electrical";
	$farrayv[]="Gas";
	/*$rows=getStatusEntrySales($oid,$date1,$date2,$sortq);
	$rows=sortArray($sort,$rows);*/
	$parray=$xparray[$oid]["ototal"];
	$rows=sortArray($sort,$parray,$ascdesc);
	$xlink=createSalesVar($rows);
	$xlink_title="";
	if($xlink[0]["xgas"]>0 || $xlink[0]["xelec"]>0)
	{
		$myid=$_SESSION["rec_user"];
		$xlink_total=$xlink[0]["xgas"]+$xlink[0]["xelec"];
		$xlink_title="&nbsp;&nbsp;<a href='http://www.familyenergymap.com/salesreport/directaccess.php?aid=".base64_encode($xlink[0]["aid"])."&uid=".base64_encode($myid["id"])."&task=mult' target='_blank'>Total [".$xlink_total."]&nbsp; Gas: ".$xlink[0]["xgas"]." Electric: ".$xlink[0]["xelec"]."</a>";
	}
}
$colwidth=array();
$maxcol=sizeof($farray)+1;
if($maxcol>6)
{
	$colwidth[]=6;
	$colwidth[]=14;
	$colwidth[]=16;
	$colwidth[]=12;
	$colwidth[]=12;
	$colwidth[]=17;
	$colwidth[]=23;
	
}
else if($maxcol>5)
{
	$colwidth[]=6;
	$colwidth[]=20;
	$colwidth[]=16;
	$colwidth[]=12;
	$colwidth[]=17;
	$colwidth[]=29;
}
else
{
	$colwidth[]=6;
	$colwidth[]=36;
	$colwidth[]=12;
	$colwidth[]=27;
	$colwidth[]=19;
}
$qu="task=".base64_encode($t)."&status=".base64_encode($status)."&office=".base64_encode($oid)."&date1=".$date1."&date2=".$date2."&ascdesc=".$ascdesc;
?>