<?php
session_start();
include "include/config.php";
include "include/function.php";
include "include/ret_header_script.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
include "include/includescript.php";
?>
<script type="text/javascript" language="javascript">
function switchretoffice(value)
{
	var yearx=document.getElementById("year_ret").value;
	if(yearx.length <1 || yearx =='na')
		yearx="";
	if(value.length > 0)
		window.location.href="viewretention.php?office_ret="+value+"&year_ret="+yearx;
}
function switchretyear(value)
{
	var office=document.getElementById("office_ret").value;
	if(office.length <1 || office =='na')
		office="";
	if(value.length > 0)
		window.location.href="viewretention.php?office_ret="+office+"&year_ret="+value;
}
function backview()
{
	window.location.href='view.php';
}
function closemodal()
{
	document.getElementById("retdiv").innerHTML="";
	document.getElementById("loadergif").style.display="block";
	document.getElementById("contmodal").style.display="none";
}
function showmodal(value)
{
	//document.getElementById("showid").innerHTML=value;
	//document.getElementById("statsdiv").innerHTML="";
	showmodalret_s(value);
	document.getElementById("contmodal").style.display="block";
}
$().ready(function() {
	/*var $scrollingDiv=$("#modalform");
	$(window).scroll(function(){
		$scrollingDiv
			.stop()
			.animate({"marginTop": ($(window).scrollTop() + 5) + "px"}, "slow" );
	});*/
});
function ret_view()
{
	var value=document.getElementById("ret_office_x").value;
	var yearx=document.getElementById("ret_year_x").value;
	showretpop_view(value,yearx);
}
var intervalID_view = window.setInterval(ret_view, 5000);
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
.statslink{
	font-family:'qlassik';
	color:#373737;
}
.retbordercol{
	border-bottom:solid #000 2px; 
	border-left:solid #000 2px;
}
.retborderleft{
	border-bottom:solid #000 2px;
}
.retbordercol_header{
	border-bottom:solid #000 2px; 
	border-left:solid #000 2px;
	background-color:#00477f;
	color:#FFF;
}
.retborderleft_header{
	border-bottom:solid #000 2px;
	background-color:#00477f;
	color:#FFF;
}
.retbordercol_close{
	border-bottom:solid #000 2px; 
	border-left:solid #000 2px;
	border-right:solid #000 2px;
	height:395px;
}
.retborderleft_close{
	border-bottom:solid #000 2px;
	height:34px;
	background-color:#00477f;
	color:#FFF;
}
.retdivcol{
	width:200px;float:left;
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
<form action="" method="" >
<input type="hidden" id="ret_office_x" name="ret_office_x" value="<?Php echo $_REQUEST["office_ret"]; ?>" />
<input type="hidden" id="ret_year_x" name="ret_year_x" value="<?Php echo $_REQUEST["year_ret"]; ?>" />
	<?php
	include "include/header.php";
	?>
    <div id="body_middle" >
    	<div id="body_middle_header">
        	<div id="body_middle_header_title">
            	Retention Report Page
            </div>
        </div>
        <div id="body_middle_middle">
        	<div id="body_content">
              <div id="message2" name="message2" class="white" style="text-align:center">
       		 &nbsp;
       			 <?php
                    if(isset($_SESSION["recresult"]))
                    {
                        echo $_SESSION["recresult"]."<br/>";
                        unset($_SESSION["recresult"]);
                    }
                 ?>
      		  </div> 
                <br/>
                <div id="viewholder" name="viewholder">
                <div><!--start entries holder-->
                <div style="text-align:center">
                	THIS INFORMATION IS A SUMMARY OF SALES GATHERED STARTING ON: <u><?PHP echo $start_date; ?></u>
                </div>
                <br/>
                <div style="text-align:center">
                	Select Office:&nbsp;
                	<select id="office_ret" name="office_ret" onchange="switchretoffice(this.value)">
                    	<?php
							$query="select * from rec_office order by name";
							if($result=mysql_query($query))
							{
								if(($num_rows=mysql_num_rows($result))>0)
								{
									while($rows=mysql_fetch_array($result))
									{
										$office_ret_sel="";
										if(!empty($office_ret))
											$office_ret_sel=setSelected($office_ret,$rows["id"]);
										else
										{
											$office_ret=$rows["id"];
											$office_ret_sel="selected='selected'";
										}
										echo "<option value='".base64_encode($rows["id"])."' $office_ret_sel>".stripslashes($rows["name"])."</option>";
									}
								}
							}
						?>
                    </select>
                    &nbsp;&nbsp;&nbsp;
                    Select Year:&nbsp;
                	<select id="year_ret" name="year_ret" onchange="switchretyear(this.value)">
                    	<?php
							for($i=0;$i<sizeof($yearx_ar);$i++)
							{
								$retyear_select=setSelected($yearx_ar[$i],$yearx);
								echo "<option value='".base64_encode($yearx_ar[$i])."' $retyear_select>".$yearx_ar[$i]."</option>";
							}
						?>
                    </select>
                </div>
                <br/>
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="22%" align="center" valign="middle" class='retborderleft_header' height='34'>Duration</td>
                    <td rowspan="14" align="left" valign="top">
                      <div id="viewret_holder" name='viewret_holder' style="width:675px;overflow-x:auto; overflow-y:hidden;">
                      		<div id="loadergif" style="text-align:center;">
                               <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        		  <tr>
                            		<td align="center" valign="middle" class='retborderleft_close'>Loading</td>
                                  </tr>
                                 <tr>
                           		    <td align="center" valign="top" class='retbordercol_close'>
                                        <img src="images/floader.gif" border="0" />
                                    </td>
                                 </tr>
                               </table>
                            </div>
                      </div>
                   	  <!--<div id="viewret_holder" name='viewret_holder' style="width:675px;overflow-x:auto; overflow-y:hidden;">
                        	<div id="viewretx_holder" name='viewretx_holder' style="width:<?Php //echo $hwidth_div; ?>px;">
                               <?php
							   	/*for($x=0;$x<sizeof($month_aval);$x++)
								{
									$setyearx=fixdate_comps('y',$month_aval[$x]["date"]);
									//$retHired=getRetHired($office_ret,$month_aval[$x]["date"]);
									//$getRetActive=getRetActive($retHired);
									//$getRetActive=getRetention($retHired);
									$getRetActive=getRetentionActive($office_ret,$month_aval[$x]["date"]);
									$getMonthSales=getRetMonthSales($month_aval[$x]["date"],$office_ret);
									$total_sales=0;
									$week0sales=getRetSales($getRetActive[0]["week0s"],$month_aval[$x]["date"],$getMonthSales);
									$total_sales +=$week0sales[0]["total"];
									$week1sales=getRetSales($getRetActive[0]["week1s"],$month_aval[$x]["date"],$getMonthSales);
									$total_sales +=$week1sales[0]["total"];
									$week2sales=getRetSales($getRetActive[0]["week2s"],$month_aval[$x]["date"],$getMonthSales);
									$total_sales +=$week2sales[0]["total"];
									$week3sales=getRetSales($getRetActive[0]["week3s"],$month_aval[$x]["date"],$getMonthSales);
									$total_sales +=$week3sales[0]["total"];
									$month1sales=getRetSales($getRetActive[0]["month1s"],$month_aval[$x]["date"],$getMonthSales);
									$total_sales +=$month1sales[0]["total"];
									$month2sales=getRetSales($getRetActive[0]["month2s"],$month_aval[$x]["date"],$getMonthSales);
									$total_sales +=$month2sales[0]["total"];
									$month3sales=getRetSales($getRetActive[0]["month3s"],$month_aval[$x]["date"],$getMonthSales);
									$total_sales +=$month3sales[0]["total"];
									$month6sales=getRetSales($getRetActive[0]["month6s"],$month_aval[$x]["date"],$getMonthSales);
									$total_sales +=$month4sales[0]["total"];
									$month12sales=getRetSales($getRetActive[0]["month12s"],$month_aval[$x]["date"],$getMonthSales);
									$total_sales +=$month12sales[0]["total"];
									echo "
									<div class='retdivcol'>
									<table width='100%' border='0' cellspacing='0' cellpadding='0'>
										<tr>
											<td colspan='2' align='center' valign='middle' class='retbordercol' height='34'>".$month_aval[$x]["name"]." ".$setyearx."</td>
										</tr>
											<tr>
											<td colspan='2' align='center' valign='middle' class='retbordercol' height='30'>".$getMonthSales."</td>
											 </tr>
											<tr>
											<td width='51%' align='center' valign='middle' class='retbordercol'>Active</td>
											<td width='49%' align='center' valign='middle' class='retbordercol'>Sales %</td>
											</tr>
											<tr>
												<td align='center' valign='middle' class='retbordercol' height='34'>".$getRetActive[0]['week0']."</td>
												<td align='center' valign='middle' class='retbordercol'>".$week0sales[0]["total"]."/".$week0sales[0]["per"]."%"."</td>
											</tr>
											<tr>
												<td align='center' valign='middle' class='retbordercol' height='34'>".$getRetActive[0]['week1']."</td>
												<td align='center' valign='middle' class='retbordercol'>".$week1sales[0]["total"]."/".$week1sales[0]["per"]."%"."</td>
											</tr>
											<tr>
												<td align='center' valign='middle' class='retbordercol' height='34'>".$getRetActive[0]['week2']."</td>
												<td align='center' valign='middle' class='retbordercol'>".$week2sales[0]["total"]."/".$week2sales[0]["per"]."%"."</td>
											</tr>
											<tr>
												<td align='center' valign='middle' class='retbordercol' height='34'>".$getRetActive[0]['week3']."</td>
												<td align='center' valign='middle' class='retbordercol'>".$week3sales[0]["total"]."/".$week3sales[0]["per"]."%"."</td>
											</tr>
											<tr>
												<td align='center' valign='middle' class='retbordercol' height='34'>".$getRetActive[0]['month1']."</td>
												<td align='center' valign='middle' class='retbordercol'>".$month1sales[0]["total"]."/".$month1sales[0]["per"]."%"."</td>
											</tr>
											<tr>
												<td align='center' valign='middle' class='retbordercol' height='34'>".$getRetActive[0]['month2']."</td>
												<td align='center' valign='middle' class='retbordercol'>".$month2sales[0]["total"]."/".$month2sales[0]["per"]."%"."</td>
											</tr>
											<tr>
												<td align='center' valign='middle' class='retbordercol' height='34'>".$getRetActive[0]['month3']."</td>
												<td align='center' valign='middle' class='retbordercol'>".$month3sales[0]["total"]."/".$month3sales[0]["per"]."%"."</td>
											</tr>
											<tr>
												<td align='center' valign='middle' class='retbordercol' height='34'>".$getRetActive[0]['month6']."</td>
												<td align='center' valign='middle' class='retbordercol'>".$month6sales[0]["total"]."/".$month6sales[0]["per"]."%"."</td>
											</tr>
											<tr>
												<td align='center' valign='middle' class='retbordercol' height='34'>".$getRetActive[0]['month12']."</td>
												<td align='center' valign='middle' class='retbordercol'>".$month12sales[0]["total"]."/".$month12sales[0]["per"]."%"."</td>
											</tr>
											<tr>
												<td align='center' valign='middle' class='retbordercol' height='34'>".$getRetActive[0]['n_agent']."</td>
												<td align='center' valign='middle' class='retbordercol'>".$total_sales."</td>
											</tr>
									</table>
									</div>
										";
								}*/
							   ?>
                               
                                <div style="clear:both"></div>
                            </div>
                        </div>-->
                    </td>
                  </tr>
                  <tr>
                    <td align="center" valign="middle" class='retborderleft' height='30' style='background-color:#e1fb51;'>Real Sales Total</td>
                  </tr>
				  <tr>
                    <td align="center" valign="middle" class='retborderleft' >&nbsp;</td>
                  </tr>
                  <tr>
                    <td align="center" valign="middle" class='retborderleft' height='34'>Less Than A Week</td>
                  </tr>
                  <tr>
                    <td align="center" valign="middle" class='retborderleft' height='34'>1 Week</td>
                  </tr>
                  <tr>
                    <td align="center" valign="middle" class='retborderleft' height='34'>2 Weeks</td>
                  </tr>
                  <tr>
                    <td align="center" valign="middle" class='retborderleft' height='34'>3 Weeks</td>
                  </tr>
                  <tr>
                    <td align="center" valign="middle" class='retborderleft' height='34'>1 Month</td>
                  </tr>
                  <tr>
                    <td align="center" valign="middle" class='retborderleft' height='34'>2 Months</td>
                  </tr>
                  <tr>
                    <td align="center" valign="middle" class='retborderleft' height='34'>+3 Months</td>
                  </tr>
                  <tr>
                    <td align="center" valign="middle" class='retborderleft' height='34'>+6 Months</td>
                  </tr>
                  <tr>
                    <td align="center" valign="middle" class='retborderleft' height='34'>+12 Months</td>
                  </tr>
                  <tr>
                    <td align="center" valign="middle" class='retborderleft' height='34'>Total</td>
                  </tr>
                  <tr>
                    <td align="center" valign="middle">&nbsp;</td>
                  </tr>
                </table>
                </div><!--end of entries holder-->
                <!--<div style="font-size:11pt;color:#F00; text-align:center">
                	&nbsp;&nbsp; () Number of agents not found in the recruiter system.
                </div>-->
                <div style="height:200px"></div>
               </div>
          </div>
        </div>
        <div id="body_footer"></div>
    </div>
    <div class="clearfooter"></div>
 </form>
</div>
<?Php
include "include/footer.php";
?>
</body>
</html>
<?php
include "include/unconfig.php";
?>