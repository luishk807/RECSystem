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
include "include/write_excel.php";
$date1 = fixdate_comps("mildate",$_REQUEST["date1"]);
$date2 = fixdate_comps("mildate",$_REQUEST["date2"]);
$checkdate=false;
if(!empty($date1) && !empty($date2))
{
	$checkdate = true;
	$vname = " From Date Range: <u>".fixdate_comps("d",$date1)."</u> To <u>".fixdate_comps("d",$date2)."</u>";
}
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
function showmodal(value,task)
{
	//document.getElementById("showid").innerHTML=value;
	showmodalstats(value,task);
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
            	Gemeral Performance
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
              	General Performance Summary <?Php echo $vname; ?> [<a href='viewstats.php'>View Stadistics</a>]&nbsp;&nbsp;[<?Php echo $excelbtn; ?>]<br/>
                <div id="message2" name="message2" class="white" style="text-align:center; padding-right:50px; padding-left:50px">&nbsp;</div>
            	<form method="post" action="viewstatus.php" onsubmit="return checkFieldk();" >
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
								$datestr1="and idate >='$date1' and idate <='$date2'";
								$datestr3="and orientation >='$date1%' and orientation <= '$date2%'";
								$datestr4="and observation >='$date1' and observation <='$date2'";
								$datestr7="and orientation_comp>='$date1' and orientation_comp <='$date2'";
								$datestr6="and observation_comps>='$date1' and observation_comps <='$date2'";
							}
							echo "<fieldset><legend><span class='boldtitle'>".stripslashes($rows["name"])."</span>:</legend>";
							echo "<div style='text-align:center'>";
							echo "Number of Calls: <a class='statslink' href='javascript:showmodal(\"".base64_encode(checkGQuery($rows["id"],"all",$checkdate,$datestr1))."\",\"cdate\")'>".checkGTotal($rows["id"],"all",$checkdate,$datestr1)."</a>";
                        	echo "<br/><hr/>";
                        	echo "Interview Pending: <a class='statslink' href='javascript:showmodal(\"".base64_encode(checkGQuery($rows["id"],"1",$checkdate,$datestr1))."\",\"inpend\")'>".checkGTotal($rows["id"],"1",$checkdate,$datestr1)."</a>";
							echo "</div>";
                        	echo "<br/><hr/>";
							echo "<fieldset><legend>Total of Hired: <a class='statslink' href='javascript:showmodal(\"".base64_encode(checkGQuery($rows["id"],"hired",$checkdate,$datestr1))."\",\"thired\")'>".checkGTotal($rows["id"],"hired",$checkdate,$datestr1)."</a> :</legend>";
							echo "<div style='text-align:center'>";
                        	echo "Orientation Set: <a class='statslink' href='javascript:showmodal(\"".base64_encode(checkGQuery($rows["id"],"3",$checkdate,$datestr3))."\",\"oset\")'>".checkGTotal($rows["id"],"3",$checkdate,$datestr3)."</a>";
                        	echo "<br/><hr/>";
                        	echo "Orientation Completed: <a class='statslink' href='javascript:showmodal(\"".base64_encode(checkGQuery($rows["id"],"7",$checkdate,$datestr7))."\",\"ocomp\")'>".checkGTotal($rows["id"],"7",$checkdate,$datestr7)."</a>";
							echo "</dvi>";
							echo "</fieldset><hr/>";
							$total_nhired = $total_nint = $total_nshow="";
							$total_nhired =checkGTotal($rows["id"],"2",$checkdate,$datestr1);
							$total_nint = checkGTotal($rows["id"],"9",$checkdate,$datestr1);
							$total_nshow = checkGTotal($rows["id"],"8",$checkdate,$datestr7);
							$total_nag= $total_nhired + $total_nint+ $total_nshow;
							echo "<fieldset><legend>Number of Non-Agent: <a class='statslink' href='javascript:showmodal(\"".base64_encode(checkGQuery($rows["id"],"noagent",$checkdate,$datestr1))."\",\"nagent\")'>".$total_nag."</a> </legend>";
							echo "<div style='text-align:center'>";
							echo "Number of Not Hired: <a class='statslink' href='javascript:showmodal(\"".base64_encode(checkGQuery($rows["id"],"2",$checkdate,$datestr1))."\",\"nhired\")'>".$total_nhired."</a>";
                        	echo "<br/><hr/>";
							echo "Number of Not Interested: <a class='statslink' href='javascript:showmodal(\"".base64_encode(checkGQuery($rows["id"],"9",$checkdate,$datestr1))."\",\"nint\")'>".$total_nint."</a>";
                        	echo "<br/><hr/>";
                        	echo "Number of No Show: <a href='javascript:showmodal(\"".base64_encode(checkGQuery($rows["id"],"8",$checkdate,$datestr7))."\",\"nshow\")' class='statslink'>".$total_nshow."</a>";
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