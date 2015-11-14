<?php
session_start();
include "include/config.php";
include "include/function.php";
include "include/vars.php";
$woffice=$_SESSION["woffice"];
$showfamily=true;
if(adminlogin_exp())
	$user=$_SESSION["rec_user"];
else
{
	$user=$_SESSION["brownuser"];
	$showfamily=false;
}
$msort=base64_decode($_REQUEST["msort"]);
$msortx=base64_decode($_REQUEST["msortx"]);
$scat = base64_decode($_REQUEST["scat"]);
$wherex = false;
$scatx = getscat($scat);
$scat = base64_decode($_REQUEST["scat"]);
$sn = $_REQUEST["sname"];
$sdtype = base64_decode($_REQUEST["sdtype"]); //ascendant or descendant
	if(empty($sdtype))
$sdtype="desc";
$dnamex=getdatename_b($scat);
$datename=$dnamex[0]['name'];
$datenamex=$dnamex[0]['namex'];
$sdvar=$datenamex;
$office_x=getOfficeSdvar($sdvar);
$sof = base64_decode($_REQUEST["sof"]); //ascendant or descendant
if(empty($sof))
	$sof=$woffice["id"];
$stypex=base64_decode($_REQUEST["stype"]);
$v= $_REQUEST["v"]; // order by name, office, etc
$query = "select * from rec_entries ";
if(!empty($sof))
{
	if($sof !="all")
	{
		if(!empty($sn))
			$query .=" where cname like '%".clean($sn)."%' and office='".$sof."' ";
		else
			$query .=" where office='".$sof."' ";
		$wherex=true;
	}
	else
	{
		if(!empty($sn))
		{
			$query .=" where cname like '%".clean($sn)."%' ";
			$wherex=true;
		}
	}
}
else
{
	if(!empty($sn))
	{
		$query .=" where cname like '%".clean($sn)."%' ";
		$wherex=true;
	}
}
if(!empty($scat))
{
	if($scat !="all")
	{
		if($wherex)
			$query .="and $scatx ";
		else
		{
			$wherex=true;
			$query .=" where $scatx ";
		}
	}
}
if(!$showfamily)
{
	if($wherex)
		$query .="and catid='2' ";
	else
	{
		$query .="where catid='2' ";
		$wherex=true;
	}
}
else
{
	if(!empty($stypex) && $stypex !='all')
	{
		if($wherex)
			$query .="and catid='$stypex' ";
		else
		{
			$query .="where catid='$stypex' ";
			$wherex=true;
		}
	}
}
$vcheck = false;
//$query .=" order by ";
if(!empty($msort))
{
	if($msort !='x')
	{
		$query .=" order by ".$msort." ".$msortx;
		$vcheck =true;
	}
}
else
{
	$query .=" order by idate desc, itime desc ";
	$vcheck =true;
}
$height="style='height:700px;'";
if(!empty($query))
{
	if($result = mysql_query($query))
	{
		if(($num_rows=mysql_num_rows($result)) >15)
			$height="";
	}
}
//$climit=get_climit();
$entry_array=array();
$queryx = $query;
if($resultx = mysql_query($queryx))
{
	if(($num_rowsx = mysql_num_rows($resultx))>0)
	{
		while($rowsx = mysql_fetch_array($resultx))
		{
			$newup=checkNew($user,$rowsx["id"]);
			//echo $newup."<br/>";
			/****new****/
			if($sdvar=='x')
				$sdvarx=get_sdvar($rowsx["id"],$rowsx["status"]);
			else
				$sdvarx=$rowsx[$sdvar];
			/***end of new***/
			//$entry_array[]=array('id'=>$rowsx["id"],'cname'=>$rowsx["cname"],'cphone'=>$rowsx["cphone"],'xdate'=>$rowsx[$sdvar],'office_x'=>$rowsx[$office_x],'status'=>$rowsx["status"],'folcome'=>$rowsx["folcome"],'newup'=>$newup);
			$entry_array[]=array('id'=>$rowsx["id"],'cname'=>$rowsx["cname"],'cphone'=>$rowsx["cphone"],'xdate'=>$sdvarx,'office_x'=>$rowsx[$office_x],'status'=>$rowsx["status"],'folcome'=>$rowsx["folcome"],'folstatus'=>$rowsx["folstatus"],'newup'=>$newup);
		}
		if(sizeof($entry_array)>0 && $msort=='x')
			$entry_array=sortArray('xdate',$entry_array,$msortx);
		//else
		//	$entry_array=sortArray($msort,$entry_array,$msortx);
	}
}
$plimit=$_REQUEST["plimit"];
$showmoreend="style='display:none'";
if(empty($plimit))
	$plimit=$p_limit;
else
	$plimit=$plimit+$p_limit;
if($plimit>=sizeof($entry_array))
{
	$plimit=sizeof($entry_array);
	$showmoreoption="style='display:none'";
	$showmoreend="style='text-align:center'";
}
$index="";
?>
<div <?php echo $height; ?>>
   <input type="hidden" id="index" name="index" value="<?php echo $_REQUEST["plimit"]; ?>" />
   <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr style="background-color:#28629e; color:#FFF">
         <td width="8%" align="center" valign="middle"></td>
         <td width="31%" align="center" valign="middle"><a href='Javascript:changesorttype_sort("<?php echo base64_encode("cname");?>","<?Php echo getMsortFix('cname',$msort,$msortx); ?>")' class='viewheader_linkw'>Name</a></td>
         <td width="16%" align="center" valign="middle"><a href='Javascript:changesorttype_sort("<?php echo base64_encode("cphone"); ?>","<?Php echo getMsortFix('cphone',$msort,$msortx); ?>")' class='viewheader_linkw'>Phone</a></td>
         <td width="14%" align="center" valign="middle"><a href='Javascript:changesorttype_sort("<?php echo base64_encode($datenamex); ?>","<?Php echo getMsortFix($datenamex,$msort,$msortx); ?>")' class='viewheader_linkw'><?php echo ucwords(strtolower($datename)); ?></a></td>
         <td width="11%" align="center" valign="middle"><a href='Javascript:changesorttype_sort("<?php echo base64_encode($office_x); ?>","<?Php echo getMsortFix($office_x,$msort,$msortx); ?>")' class='viewheader_linkw'>Office</a></td>
         <td width="20%" align="center" valign="middle"><a href='Javascript:changesorttype_sort("<?php echo base64_encode("status")?>","<?Php echo getMsortFix('status',$msort,$msortx); ?>")' class='viewheader_linkw'>Status</a></td>
      </tr>
<?php
	if(sizeof($entry_array)>0)
	{
		$countx=1;
		$totalx=0;
		for($x=0;$x<$plimit;$x++)
		{
			$index=$x;
			$totalx = $countx%2;
			if($totalx==0)
				$rowstyle="style='font-size:15pt'";
			else
				$rowstyle="style='background-color:#e1fb51; font-size:15pt'";
			$newup=$entry_array[$x]["newup"];
			if($newup=="true")
				$imgnew="&nbsp;<img src='images/newgif.gif' border='0' alt='new'/>";
			else
				$imgnew="";
			if($entry_array[$x]["folcome"]=="no")
				$status="Cancelled";
			else
				$status = getRecStatus($entry_array[$x]["status"]);
			$xdate=fixdate_comps("invdate_s",$entry_array[$x]["xdate"]);
			$xoffice=getOfficeName_s($entry_array[$x]["office_x"]);
			if(empty($xdate))
				$xdate = "N/A";
			if($showfamily)
			{
				//$linkuser = "<a class='adminlink' href='setrec.php?id=".base64_encode($entry_array[$x]["id"])."'>".stripslashes($entry_array[$x]["cname"])."</a>".$imgnew." ".getFolStatus_View($entry_array[$x]["folstatus"])." ";
				$linkuser = "<a class='adminlink' href='setrec.php?id=".base64_encode($entry_array[$x]["id"])."'>".stripslashes($entry_array[$x]["cname"])."</a>".$imgnew;
			}
			else
				$linkuser="<a class='adminlink' href='setrec.php?id=".base64_encode($entry_array[$x]["id"])."'>".stripslashes($entry_array[$x]["cname"])."</a>".$imgnew;
				echo "<tr $rowstyle><td align='center' valign='middle'>$countx</td><td align='center' valign='middle'>".$linkuser."</td><td align='center' valign='middle'>".$entry_array[$x]["cphone"]."</td><td align='center' valign='middle'>$xdate</td><td align='center' valign='middle'>".$xoffice."</td><td align='center' valign='middle'>".$status."</td></tr>";
				$countx++;
		}
	}
	else
		echo "<tr class='rowstyleno'><td colspan='6' align='center' valign='middle'>No Entry Found</td></tr>";
	$_SESSION["entry_array"]=$entry_array;
?>
	</table>
    <div id="more<?php echo $index; ?>" class="morebox">
       <div id="showmoreoption" <?php echo $showmoreoption; ?>>
           <div id="showloader" name="showloader" style="display:none;" >
              <img src="images/floader.gif" border="0" />
           </div>
           <div id="showbtn" name="showbtn">
              <a href="javascript:showmore('<?php echo $index; ?>')" class="more" id="<?php echo $index; ?>">Show More Entry</a>
           </div>
       </div>
       <div id="showmoreend" <?Php echo $showmoreend; ?>>
          End of Entries
       </div>
    </div>
<?php
	include "include/config.php";
?>