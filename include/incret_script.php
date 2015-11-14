<?php
$oid=base64_decode($_REQUEST["office"]);
if(empty($oid))
	$oid=0;
$t=base64_decode($_REQUEST["task"]);
$xset=base64_decode($_REQUEST["xset"]);
$date1=$xmark=getFirstDay(base64_decode($_REQUEST["date_detail"]));
$date2=getLastDay($date1);
$status=base64_decode($_REQUEST["status"]);
$ascdesc=$_REQUEST["ascdesc"];
$sort=$_REQUEST["sort"];
$ototal_name="";
$farray = array();
$farrayv = array();
$titleb="";
$xparray=$_SESSION["ret_array"];
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
if($t=="rsales")
{
	//real total of sales
	$farray[]="name";
	$farray[]="acode";
	$farray[]="xgas";
	$farray[]="xelec";
	$farray[]="hired";
	$farray[]="manager";
	$farrayv[]="Name";
	$farrayv[]="Agent Code";
	$farrayv[]="Gas";
	$farrayv[]="Electrical";
	$farrayv[]="Hired";
	$farrayv[]="Manager";
	//$rows=getStatusEntryPhone($oid,$date1,$date2,$sortq);
	//$rows=sortArray($sort,$rows);
	$parray=getSalesRequest($date1,$date2,$oid);
	$rows=sortArray($sort,$parray,$ascdesc);
	$titleb=" For Total Sales ";
	$xlink=createSalesVar($rows);
	$xlink_title="";
	if($xlink[0]["xgas"]>0 || $xlink[0]["xelec"]>0)
	{
		$myid=$_SESSION["rec_user"];
		$xlink_total=$xlink[0]["xgas"]+$xlink[0]["xelec"];
		$xlink_title="&nbsp;&nbsp;<a href='http://www.familyenergymap.com/salesreport/directaccess.php?aid=".base64_encode($xlink[0]["aid"])."&uid=".base64_encode($myid["id"])."&task=mult' target='_blank'>Total [".$xlink_total."]&nbsp; Gas: ".$xlink[0]["xgas"]." Electric: ".$xlink[0]["xelec"]."</a>";
	}
}
else if($t=="dret")
{
	//show retention per weeks or month
	$farray[]="name";
	$farray[]="ccode";
	$farray[]="manager";
	$farray[]="hired";
	$farrayv[]="Name";
	$farrayv[]="Agent Code";
	$farrayv[]="Manager";
	$farrayv[]="Hired Date";
	$parrayz=$xparray[$xmark];
	$parrayz_b=$parrayz[0][$xset];
	$parray=getRetRequest($t,$parrayz_b,$date1,$date2,$oid);
	$rows=sortArray($sort,$parray,$ascdesc);
	$titleb=" For Active Agents ";
}
else if($t=="dsales")
{
	//show retention per weeks or month and sales
	$farray[]="name";
	$farray[]="acode";
	$farray[]="xgas";
	$farray[]="xelec";
	$farray[]="hired";
	$farray[]="manager";
	$farrayv[]="Name";
	$farrayv[]="Agent Code";
	$farrayv[]="Gas";
	$farrayv[]="Electrical";
	$farrayv[]="Hired";
	$farrayv[]="Manager";
	$parrayz=$xparray[$xmark];
	$parrayz_b=$parrayz[0][$xset];
	$parray=getRetRequest($t,$parrayz_b,$date1,$date2,$oid);
	$rows=sortArray($sort,$parray,$ascdesc);
	$titleb=" For Active Agents With Sales ";
}
else if($t=="dsales_ret")
{
	//show all the agents with their sales
	$farray[]="name";
	$farray[]="acode";
	$farray[]="xgas";
	$farray[]="xelec";
	$farray[]="hired";
	$farray[]="manager";
	$farrayv[]="Name";
	$farrayv[]="Agent Code";
	$farrayv[]="Gas";
	$farrayv[]="Electrical";
	$farrayv[]="Hired";
	$farrayv[]="Manager";
	$parrayz=$xparray[$xmark];
	$parrayz_b=array();
	$xsetx=array('week0s','week1s','week2s','week3s','month1s','month2s','month3s','month6s','month12s');
	for($i=0;$i<sizeof($xsetx);$i++)
	{
		$parrayz_x=$parrayz[0][$xsetx[$i]];
		for($b=0;$b<sizeof($parrayz_x);$b++)
			$parrayz_b[]=array('id'=>$parrayz_x[$b]["id"],'acode'=>$parrayz_x[$b]["acode"]);
	}
	//$parrayz_b=$parrayz[0][$xset];
	$parray=getRetRequest($t,$parrayz_b,$date1,$date2,$oid);
	$rows=sortArray($sort,$parray,$ascdesc);
	$titleb=" For Active Agents With Sales ";
}
else if($t=="dret_total")
{
	//show all the agents regardless of the week and month of retentation
	$farray[]="name";
	$farray[]="ccode";
	$farray[]="manager";
	$farray[]="hired";
	$farrayv[]="Name";
	$farrayv[]="Agent Code";
	$farrayv[]="Manager";
	$farrayv[]="Hired Date";
	$parrayz=$xparray[$xmark];
	$parrayz_b=array();
	$xsetx=array('week0s','week1s','week2s','week3s','month1s','month2s','month3s','month6s','month12s');
	for($i=0;$i<sizeof($xsetx);$i++)
	{
		$parrayz_x=$parrayz[0][$xsetx[$i]];
		for($b=0;$b<sizeof($parrayz_x);$b++)
			$parrayz_b[]=array('id'=>$parrayz_x[$b]["id"],'acode'=>$parrayz_x[$b]["acode"]);
	}
	//$parrayz_b=$parrayz[0][$xset];
	$parray=getRetRequest($t,$parrayz_b,$date1,$date2,$oid);
	$rows=sortArray($sort,$parray,$ascdesc);
	$titleb=" For Active Agents ";
}
//define the width of columns
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
$xset_en="";
if(!empty($xset))
	$xset_en=base64_encode($xset);
$qu="xset=".$xset_en."&task=".base64_encode($t)."&status=".base64_encode($status)."&office=".base64_encode($oid)."&date_detail=".base64_encode($date1)."&ascdesc=".$ascdesc;
?>