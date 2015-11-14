<?php
session_start();
include "include/config.php";
include "include/function.php";
adminlogin();
$showfamily=true;
if(adminlogin_exp())
	$user=$_SESSION["rec_user"];
else
{
	$user=$_SESSION["brownuser"];
	$showfamily=false;
}
$date1 = fixdate_comps("mildate",$_REQUEST["date1"]);
$date2 = fixdate_comps("mildate",$_REQUEST["date2"]);
$checkdate=false;
if(!empty($date1) && !empty($date2))
{
	$checkdate = true;
	$vname = " From Date Range: <u>".fixdate_comps("d",$date1)."</u> To <u>".fixdate_comps("d",$date2)."</u>";
}
$recphox_date="";
if($checkdate)
	$recphox_date=" where date between '".$date1."' and '".$date2."'";
$gtphone=0;
$recphox="select count(*) as total from rec_phones $recphox_date";
if($rrecphox=mysql_query($recphox))
{
	if(($numrec=mysql_num_rows($rrecphox))>0)
	{
		$info_rec=mysql_fetch_assoc($rrecphox);
		$gtphone=$info_rec["total"];
	}
}
$gtphonet=" $gtphone Calls";
//echo $gtphonet;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
include "include/includescript.php";
?>
<script type="text/javascript" language="javascript">
function backview()
{
	window.location.href='view.php';
}
function closemodal()
{
	document.getElementById("contmodal").style.display="none";
}
function showmodal(value,oid,task,dateb)
{
	//document.getElementById("showid").innerHTML=value;
	showmodalphone(value,oid,task,dateb);
	document.getElementById("contmodal").style.display="block";
}
/*$().ready(function() {
	var $scrollingDiv = $("#modalform");
	$(window).scroll(function(){			
		$scrollingDiv
			.stop()
			.animate({"marginTop": ($(window).scrollTop() + 5) + "px"}, "slow" );			
	});
});*/
</script>
<style>
.statslink{
	color:#999; font-style:italic;
}
</style>
<title>Welcome to Family Energy Recruiter System</title>
</head>
<body>
<div id="contmodal">
	<div id="modalform">
    </div>
    <div id="modalpop"></div>
 </div>
<div id="main_cont">
	<?php
	include "include/header.php";
	?>
    <div id="body_middle" >
    	<div id="body_middle_header">
        	<div id="body_middle_header_title">
            	All Phones Calls
            </div>
        </div>
        <div id="body_middle_middle">
        	<div id="body_content">
              <div id="message" name="message" class="white" style="text-align:center">
       		 &nbsp;
       			 <?php
                    if(isset($_SESSION["recresult"]))
                    {
                        echo $_SESSION["recresult"]."<br/>";
                        unset($_SESSION["recresult"]);
                    }
                 ?>
      		  </div> 
           	  <div style="text-align:center">
              	Summary of All Phones Calls Received<?Php echo $vname; ?> &nbsp; <?php echo $gtphonet; ?><br/>
               <span class='optional_style'>Phone Calls is automatically saved every night at 8pm.</span><br/>
                <div id="message2" name="message2" class="white" style="text-align:center; padding-right:50px; padding-left:50px">&nbsp;</div>
            	<form method="post" action="viewphones.php" onsubmit="return checkFieldk();" >
                	<div style="text-align:center; margin-left:auto; margin-right:auto; padding-left:80px;">
                        <div style="width:340px; text-align:center; margin-left:auto; margin-right:auto; float:left;">
                            <div style="float:left; padding-right:5px;">Choose A Date Range:</div>&nbsp;
                            <input name="date1" id="date1" class="date-pick" readonly="readonly" />
                        </div>
                        &nbsp;&nbsp;&nbsp;
                        <div style="float:left; padding-right:10px;">
                         To:
                        </div>
                        <div style="width:200px; text-align:center; margin-left:auto; margin-right:auto;float:left">
                            <input name="date2" id="date2" class="date-pick" readonly="readonly" />
                        </div>
                        <div style="float:left">
                        	&nbsp;&nbsp;<input type="submit" value="Submit" />&nbsp;&nbsp;<input type="button" value="Back" onclick="backview()" />
                        </div>
                        <div style="clear:both"></div>
                    </div>
                </form>
              </div>
                <br/>
                <div id="viewholder" name="viewholder">
                <div>
                <?Php
				$query = "select * from rec_office order by id";
				if($result = mysql_query($query))
				{
					if(($num_rows = mysql_num_rows($result))>0)
					{
						while($rows = mysql_fetch_array($result))
						{
							if($checkdate)
							{
								$datestrb=" and date between '".$date1."' and '".$date2."'";
								$datestr1="and idate >='$date1' and idate <='$date2'";
							}
							echo "<fieldset><legend><span class='boldtitle'>".stripslashes($rows["name"])."</span>:</legend>";
							$totalcall=getRunTotal_by_office($rows["id"],"all",$checkdate,$datestr1,$datestrb);
							$totalcall_per=@round(($totalcall/$gtphone)*100)."%";
							$intset=getRunTotal_by_office($rows["id"],"set",$checkdate,$datestr1,$datestrb);
							$intset_per=@round(($intset/$totalcall)*100)."%";
							$intpend=getRunTotal_by_office($rows["id"],"1",$checkdate,$datestr1,$datestrb);
							$intpend_per=@round(($intpend/$totalcall)*100)."%";
							$totalhired=getRunTotal_by_office($rows["id"],"hired",$checkdate,$datestr1,$datestrb);
							$totalhired_per=@round(($totalhired/$totalcall)*100)."%";
							$totalori=getRunTotal_by_office($rows["id"],"3",$checkdate,$datestr1,$datestrb);
							$totalori_per=@round(($totalori/$totalcall)*100)."%";
							$totaloricomp=getRunTotal_by_office($rows["id"],"7",$checkdate,$datestr1,$datestrb);
							$totaloricomp_per=@round(($totaloricomp/$totalcall)*100)."%";
							echo "<div style='text-align:center'>";
							echo "Number of Calls: $totalcall_per &nbsp;<a class='statslink' href='javascript:showmodal(\"".base64_encode("all")."\",\"".base64_encode($rows["id"])."\",\"cdate\",\"".base64_encode($datestrb)."\")'>".$totalcall."</a>";
							echo "<br/><hr/>";
							echo "Interview Set: $intset_per &nbsp;<a class='statslink' href='javascript:showmodal(\"".base64_encode("set")."\",\"".base64_encode($rows["id"])."\",\"nint\",\"".base64_encode($datestrb)."\")'>".$intset."</a>";
							echo "<br/><hr/>";
							echo "Interview Pending: $intpend_per &nbsp;<a class='statslink' href='javascript:showmodal(\"".base64_encode("1")."\",\"".base64_encode($rows["id"])."\",\"inpend\",\"".base64_encode($datestrb)."\")'>".$intpend."</a>";
							echo "</div>";
                        	echo "<br/><hr/>";
							echo "<fieldset><legend>Total of Hired: $totalhired_per &nbsp;<a class='statslink' href='javascript:showmodal(\"".base64_encode("hired")."\",\"".base64_encode($rows["id"])."\",\"thired\",\"".base64_encode($datestrb)."\")'>".$totalhired."</a> :</legend>";
							echo "<div style='text-align:center'>";
                        	echo "Orientation Set: $totalori_per &nbsp;<a class='statslink' href='javascript:showmodal(\"".base64_encode("3")."\",\"".base64_encode($rows["id"])."\",\"oset\",\"".base64_encode($datestrb)."\")'>".$totalori."</a>";
                        	echo "<br/><hr/>";
                        	echo "Orientation Completed: $totaloricomp_per &nbsp;<a class='statslink' href='javascript:showmodal(\"".base64_encode("7")."\",\"".base64_encode($rows["id"])."\",\"ocomp\",\"".base64_encode($datestrb)."\")'>".$totaloricomp."</a>";
							echo "</dvi>";
							echo "</fieldset><hr/>";
							$total_nhired = $total_nint = $total_nshow="";
							$total_nhired =getRunTotal_by_office($rows["id"],"2",$checkdate,$datestr1,$datestrb);
							$total_nhired_per=@round(($total_nhired/$totalcall)*100)."%";
							$total_nint = getRunTotal_by_office($rows["id"],"9",$checkdate,$datestr1,$datestrb);
							$total_nint_per=@round(($total_nint/$totalcall)*100)."%";
							$total_nshow = getRunTotal_by_office($rows["id"],"8",$checkdate,$datestr1,$datestrb);
							$total_nshow_per=@round(($total_nshow/$totalcall)*100)."%";
							$total_nag= $total_nhired + $total_nint+ $total_nshow;
							$total_nag_per=@round(($total_nag/$totalcall)*100)."%";
							echo "<fieldset><legend>Number of Non-Agent: $total_nag_per &nbsp;<a class='statslink' href='javascript:showmodal(\"".base64_encode("noagent")."\",\"".base64_encode($rows["id"])."\",\"nagent\",\"".base64_encode($datestrb)."\")'>".$total_nag."</a> </legend>";
							echo "<div style='text-align:center'>";
							echo "Number of Not Hired: $total_nhired_per &nbsp;<a class='statslink' href='javascript:showmodal(\"".base64_encode("2")."\",\"".base64_encode($rows["id"])."\",\"nhired\",\"".base64_encode($datestrb)."\")'>".$total_nhired."</a>";
                        	echo "<br/><hr/>";
							echo "Number of Not Interested: $total_nint_per &nbsp;<a class='statslink' href='javascript:showmodal(\"".base64_encode("9")."\",\"".base64_encode($rows["id"])."\",\"nint\",\"".base64_encode($datestrb)."\")'>".$total_nint."</a>";
                        	echo "<br/><hr/>";
                        	echo "Number of No Show: $total_nshow_per &nbsp;<a href='javascript:showmodal(\"".base64_encode("8")."\",\"".base64_encode($rows["id"])."\",\"nshow\",\"".base64_encode($datestrb)."\")' class='statslink'>".$total_nshow."</a>";
							echo "</div>";
							echo "</fieldset>";
							echo "</fieldset><br/>";
						}
					}
				}
				?>
                </div><!--end of entries holder-->
               </div>
          </div>
        </div>
        <div id="body_footer"></div>
    </div>
    <div class="clearfooter"></div>
</div>
<?Php
include "include/footer.php";
?>
</body>
</html>
<?php
include "include/unconfig.php";
?>