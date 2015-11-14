<?php
session_start();
include "include/config.php";
include "include/function.php";
adminlogin();
$showfamily=true;
$woffice=$_SESSION["woffice"];
if(adminlogin_exp())
	$user=$_SESSION["rec_user"];
else
{
	$user=$_SESSION["brownuser"];
	$showfamily=false;
}
$sname=$_REQUEST["sname"];
$v=$_REQUEST["v"];
$ascdesc=base64_decode($_REQUEST["ascdesc"]);
if(empty($ascdesc))
{
	$ascdescx=base64_decode("asc");
	$ascdesc="desc";
}
else
{
	if($ascdesc=="desc")
		$ascdescx=base64_decode("asc");
	else
		$ascdescx=base64_decode("desc");
}
$ndays=getNDays();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
include "include/includescript.php";
?>
<script type="text/javascript" language="javascript">
/*function showpop_view()
{
	var v=document.getElementById("v").value;
	showdivpoppend_view(v);
}
var intervalID_view = window.setInterval(showpop_view, 10000);*/
function closemodal()
{
	document.getElementById("contmodal").style.display="none";
}
function showmodal(value)
{
	//document.getElementById("showid").innerHTML=value;
	showmodalform(value);
	document.getElementById("contmodal").style.display="block";
}
$(document).ready(function()
	{
        $(".slidingDiv").hide();
        $(".show_this").show();
		$('.show_this').click(function()
		{
			$(".slidingDiv").slideToggle();
		}
	);
});
function changeexview()
{
	var xoff = document.getElementById("veoffice").value;
	var xdate = document.getElementById("date1").value;
	var xtype = document.getElementById("veview").value;
	changexpend(xoff,xdate,xtype,"pendviewholder");
}
function pen_view()
{
	var off=document.getElementById("office_s").value;
	showpenpop_view(off,'ori','',"pendviewholder");
}
var intervalID_pen= window.setInterval(pen_view, 5000);
function showmodalwoffice(targ)
{
	//document.getElementById("showid").innerHTML=value;
	showmodalcoffice(targ);
	document.getElementById("contmodal").style.display="block";
}
$().ready(function() {
	var $scrollingDiv = $("#modalform");
	$(window).scroll(function(){			
		$scrollingDiv
			.stop()
			.animate({"marginTop": ($(window).scrollTop() + 5) + "px"}, "slow" );			
	});
});
</script>
<style>
#contmodal{
	display:none;
}
#modalform{
	position:absolute;z-index:10000; width:800px; height:500px; background:#FFF;right: 0;left: 0; margin:0 auto;color:#000; padding:40px;
	top:2%;
}
#modalpop{
	position:fixed; z-index:9000;background-color:#000; top: 0; right: 0; bottom: 0; left: 0;opacity:0.4;
}
</style>
<title>Welcome to Family Energy Recruiter System</title>
</head>
<body 
<?php
if(!isset($_SESSION["woffice"]))
{
?>
onload="showmodalwoffice('<?php echo base64_encode('viewpend'); ?>')"
<?php
}
?>
>
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
            	Follow Ups
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
              	View Pending Interview For Tomorrow&nbsp;&nbsp;<span style="font-size:14pt">[<a href='view.php'>Return To Previous Page</a>]</span><br/>
                 <?php
              if(isset($_SESSION["woffice"]))
              {
                  ?>
              <br/><span style="text-decoration:underline; font-size:15pt">From <a href="javascript:showmodalwoffice('<?php echo base64_encode('viewpend'); ?>')"><?php echo $woffice["name"]; ?></a>.</span><br/>
              <?php
              }
                ?>
                <div id="message2" name="message2" class="white" style="text-align:center; padding-right:50px; padding-left:50px">&nbsp;</div>
				<?Php
				if($showfamily)
				{
					?>
            	<form method="post" action="viewpend.php" onsubmit="return checkFieldh()">
                <input type="hidden" id="office_s" name="office_s" value="<?php 
				if(empty($woffice["id"]))
				{
					echo base64_encode($woffice["id"]); 
				}
				else
				 echo "all"; ?>
                " />
                <input type="hidden" id="v" name="v" value="<?php echo $_REQUEST["v"]; ?>" />
                <input type="hidden" id="ascdesc" name="ascdesc" value="<?php echo $ascdescx; ?>" />
                Search For Entries Name: <input type="text" size="60" id="sname" name="sname" />&nbsp;&nbsp;<input type="submit" value="Submit" />&nbsp;Or&nbsp;
                <select id="showview" name="showview" onchange="changeviewpend(this.value)">
                	<option value="na">Select A View</option>
                    <option value="all" <?php echo setSelected('all',$v);  ?>>View All</option>
                    <option value="cname" <?php echo setSelected('cname',$v);  ?>>Order By Name</option>
                    <option value="office" <?php echo setSelected('office',$v);  ?>>Order By Office</option>
                    <option value="idate" <?php echo setSelected('idate',$v);  ?>>Order By date</option>
                </select>
                <input type="hidden" id="snamen" name="snamen" value="<?php echo base64_encode($_REQUEST["sname"]);?>" />
                </form>
                <?php
				}
				?>
              </div>
              <?Php
				if($showfamily)
				{
					?>
              <p class="show_this" style="font-size:12pt;color:#666;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[+] Show Extra View</p>
              <div class="slidingDiv" style="height:300px;">
              <fieldset>
              	<legend>Check Pending View</legend>
                <div style="color:#999">
                    <div style="float:left">
                        Choose Office: <select id="veoffice" name="veoffice">
                            <option value="all">View All</option>
                            <?php
                                $queryeo = "select * from rec_office order by id";
                                if($resulteo = mysql_query($queryeo))
                                {
                                    if(($numrowseo = mysql_num_rows($resulteo))>0)
                                    {
                                        while($roweo = mysql_fetch_array($resulteo))
                                        {
											$office_s=setSelected($woffice["id"],$roweo["id"]);
                                            echo "<option value='".base64_encode($roweo["id"])."' $office_s>".stripslashes($roweo["name"])."</option>";
                                        }
                                    }
                                }
                            ?>
                        </select>
                    </div>
                    <div style="float:left; padding-left:10px;">
                        <div style="float:left">From Date:</div>
                        <input name="date1" id="date1" class="date-pick" readonly="readonly" />
                    </div>
                    <div style="float:left;padding-left:5px;">
                        Choose View Type: 
                        <select id="veview" name="veview">
                            <option value="ori">Orientation</option>
                            <option value="inter">Interviews</option>
                        </select>
                        &nbsp;&nbsp;<input type="button" value="GO" onclick="changeexview()"/>
                    </div>
                    <div style="clear:both"></div>
                </div>
                <br/>
                <div id="pendviewholder" name="pendviewholder"> <!--view extra view tablke-->
                    <div id="viewpend_holder" name='viewpen_holder'>
                        <div id="loadergif" style="text-align:center;"><img src="images/floader.gif" border="0" /></div>
                    </div>
                <?php
					//$query = "select * from rec_entries WHERE orientation=CURDATE()+ INTERVAL ".$ndays. " DAY and status='3' and folstatus !='3' and (status !='8' and status !='9') and catid='1' order by orientation $ascdesc";
				?>
                <?php
					//include "include/write_excel_pen.php";
				?>
               <!-- <div id="excelbtn" style="text-align:center; background:#0C5404; color:#FFF;">
                	<?Php
						//echo $excelbtn;
					?>
                </div>
                <div style='height:200px; overflow:auto'>
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr style="background-color:#28629e; color:#FFF">
                    <td width="8%" align="center" valign="middle"></td>
                    <td width="31%" align="center" valign="middle">Name</td>
                    <td width="16%" align="center" valign="middle">Phone</td>
                    <td width="14%" align="center" valign="middle">Orientation</td>
                    <td width="11%" align="center" valign="middle">Office</td>
                    <td width="20%" align="center" valign="middle">Status</td>
                  </tr>
                  <?php
				 /* $queryx = $query;
				  if($resultx = mysql_query($queryx))
				  {
					  if(($num_rowsx = mysql_num_rows($resultx))>0)
					  {
						  $countx=1;
						  $totalx=0;
						  while($rowsx = mysql_fetch_array($resultx))
						  {
							 $totalx = $countx%2;
							 if($totalx==0)
							 	$rowstyle="style='font-size:15pt'";
							else
								$rowstyle="style='background-color:#e1fb51; font-size:15pt'";
							$status = getRecStatus($rowsx["status"]);
						 	 echo "<tr $rowstyle><td align='center' valign='middle'>$countx</td><td align='center' valign='middle'><a class='adminlink' href='setrec.php?id=".base64_encode($rowsx["id"])."'>".stripslashes($rowsx["cname"])."</a>".$imgnew." </td><td align='center' valign='middle'>".$rowsx["cphone"]."</td><td align='center' valign='middle'>".fixdate_comps("onsip",$rowsx["orientation"])."</td><td align='center' valign='middle'>".getOfficeName_s($rowsx["office"])."</td><td align='center' valign='middle'>".$status."</td></tr>";
							 $countx++;
						  }
					  }
					  else
					  	echo "<tr class='rowstyleno'><td colspan='6' align='center' valign='middle'>No Orientation Pending Found</td></tr>";
				  }
				  else
				  	echo "<tr class='rowstyleno'><td colspan='6' align='center' valign='middle'>No Orientation Pending Found</td></tr>";*/
               	?>
                	
                  </table>
                </div>-->
                </div> <!--view extra view table-->
              </fieldset>
              </div>
              <?php
				}
				?>
                <br/>
                <div id="viewholder" name="viewholder">
                <?php
				if(!$showfamily)
					$showbrown=" catid='2' and ";
				else
					$showbrown=" catid='1' and";
				if(!empty($v))
				{
					if($v=="all")
						$v="idate $ascdesc, itime $ascdesc";
					$sn = base64_decode($_REQUEST["sname"]);
					$orderby = $v;
					if(!empty($sn))
						$query = "select * from rec_entries where idate = CURDATE()+ INTERVAL ".$ndays." DAY and folstatus !='3' and cname like '%".clean($sn)."%' and (status !='8' and status !='9') and catid='1' order by $orderby";
					else
						$query = "select * from rec_entries where idate = CURDATE()+ INTERVAL ".$ndays." DAY and folstatus !='3' and (status !='8' and status !='9') and catid='1' order by $orderby";
				}
				else
				{
					if(!empty($sname))
					{
						$query = "select * from rec_entries where idate = CURDATE()+ INTERVAL ".$ndays." DAY and folstatus !='3' and cname like '%".clean($sname)."%' and (status !='8' and status !='9') and catid='1' order by idate $ascdesc, itime $ascdesc";
					}
					else
					{
						$query = "select * from rec_entries WHERE $showbrown idate = CURDATE()+ INTERVAL ".$ndays." DAY and folstatus !='3' and (status !='8' and status !='9')  order by idate desc, itime desc";
					}
					if($result = mysql_query($query))
					{
						if(($num_rows = mysql_num_rows($result)) <5)
							$height="style='height:500px'";
						else
							$height='';
					}
					else
						$height="style='height:500px'";
				}
				?>
                <div <?php echo $height; ?>>
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr style="background-color:#28629e; color:#FFF">
                    <td width="8%" align="center" valign="middle"></td>
                    <td width="31%" align="center" valign="middle">Name</td>
                    <td width="16%" align="center" valign="middle">Phone</td>
                    <td width="14%" align="center" valign="middle">Interview Date</td>
                    <td width="11%" align="center" valign="middle">Office</td>
                    <td width="20%" align="center" valign="middle">Status</td>
                  </tr>
                  <?php
				  $queryx = $query;
				  if($resultx = mysql_query($queryx))
				  {
					  if(($num_rowsx = mysql_num_rows($resultx))>0)
					  {
						  $countx=1;
						  $totalx=0;
						  while($rowsx = mysql_fetch_array($resultx))
						  {
							 $totalx = $countx%2;
							 if($totalx==0)
							 	$rowstyle="style='font-size:15pt'";
							else
								$rowstyle="style='background-color:#e1fb51; font-size:15pt'";
							$newup=false;
							if($user["id"]==$rowsx["observationer"])
						  	{
								if($rowsx["ob_view"]=="no")
									$newup=true;
							}
							if($user["id"]==$rowsx["interviewer"])
						  	{
								if($rowsx["inter_view"]=="no")
									$newup=true;
							}
							if($newup)
								$imgnew="&nbsp;<img src='images/newgif.gif' border='0' alt='new'/>";
							else
								$imgnew="";
							$status = getFolStatus($rowsx["folstatus"]);
						 	 echo "<tr $rowstyle><td align='center' valign='middle'>$countx</td><td align='center' valign='middle'><a class='adminlink' href='setrec.php?id=".base64_encode($rowsx["id"])."'>".stripslashes($rowsx["cname"])."</a>".$imgnew." </td><td align='center' valign='middle'>".$rowsx["cphone"]."</td><td align='center' valign='middle'>".fixdate_comps("onsip",$rowsx["idate"]." ".$rowsx["itime"])."</td><td align='center' valign='middle'>".getOfficeName_s($rowsx["office"])."</td><td align='center' valign='middle'><a href='javascript:showmodal(\"".base64_encode($rowsx["id"])."\")'>".$status."</a></td></tr>";
							 $countx++;
						  }
					  }
					  else
					  	echo "<tr class='rowstyleno'><td colspan='6' align='center' valign='middle'>No Entry Found</td></tr>";
				  }
				  else
				  	echo "<tr class='rowstyleno'><td colspan='6' align='center' valign='middle'>No Entry Found</td></tr>";
               	?>
                  </table>
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