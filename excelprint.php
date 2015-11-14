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
	window.location.href='viewstatus.php';
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
            	Spreadsheet Maker
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
              	Hello <u><b><?php echo $user["username"]; ?></b></u>, use this page to create your spreadsheet<br/>
                <div id="message2" name="message2" class="white" style="text-align:center; padding-right:50px; padding-left:50px">&nbsp;</div>
            	<form method="post" action="excelprint.php" onsubmit="return checkFieldk();" >
                	<div style="text-align:center; margin-left:auto; margin-right:auto; padding-left:10px;">
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
                        <br/>
                        <fieldset>
                        	<legend>Choose Desired Field For Spreadsheet</legend>
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                              <tr>
                                <td align="left" valign="middle"><input type="checkbox" checked="checked" id="sf[]" name="sf[]" value="id" />&nbsp;Entry Id</td>
                                <td align="left" valign="middle"><input type="checkbox" checked="checked" id="sf[]" name="sf[]" value="catid" />&nbsp;Category</td>
                                <td align="left" valign="middle"><input type="checkbox" checked="checked" id="sf[]" name="sf[]" value="cname" />&nbsp;Entry Name</td>
                                <td align="left" valign="middle"><input type="checkbox" checked="checked" id="sf[]" name="sf[]" value="email" />&nbsp;Entry Email</td>
                              </tr>
                              <tr>
                                <td align="left" valign="middle"><input type="checkbox" checked="checked" id="sf[]" name="sf[]" value="ccode" />&nbsp;Agent Code</td>
                                <td align="left" valign="middle"><input type="checkbox" checked="checked" id="sf[]" name="sf[]" value="cphone" />&nbsp;Entry Phone</td>
                                <td align="left" valign="middle"><input type="checkbox" checked="checked" id="sf[]" name="sf[]" value="cphonex" />&nbsp;Entry Phone 2</td>
                                <td align="left" valign="middle"><input type="checkbox" checked="checked" id="sf[]" name="sf[]" value="idate" />&nbsp;Interview Date</td>
                              </tr>
                              <tr>
                                <td align="left" valign="middle"><input type="checkbox" checked="checked" id="sf[]" name="sf[]" value="itime" />&nbsp;Interview Time</td>
                                <td align="left" valign="middle"><input type="checkbox" checked="checked" id="sf[]" name="sf[]" value="cdate" />&nbsp;Date Called</td>
                                <td align="left" valign="middle"><input type="checkbox" checked="checked" id="sf[]" name="sf[]" value="address" />&nbsp;Entry Address</td>
                                <td align="left" valign="middle"><input type="checkbox" checked="checked" id="sf[]" name="sf[]" value="city" />&nbsp;Entry City</td>
                              </tr>
                              <tr>
                                <td align="left" valign="middle"><input type="checkbox" checked="checked" id="sf[]" name="sf[]" value="state" />&nbsp;Entry State</td>
                                <td align="left" valign="middle"><input type="checkbox" checked="checked" id="sf[]" name="sf[]" value="country" />&nbsp;Entry Country</td>
                                <td align="left" valign="middle"><input type="checkbox" checked="checked" id="sf[]" name="sf[]" value="zip" />&nbsp;Entry Zip/Postal Code</td>
                                <td align="left" valign="middle"><input type="checkbox" checked="checked" id="sf[]" name="sf[]" value="office" />&nbsp;Office Assigned</td>
                              </tr>
                              <tr>
                                <td align="left" valign="middle"><input type="checkbox" checked="checked" id="sf[]" name="sf[]" value="csource" />&nbsp;Entry Source</td>
                                <td align="left" valign="middle"><input type="checkbox" checked="checked" id="sf[]" name="sf[]" value="csource_title" />&nbsp;Source Info</td>
                                <td align="left" valign="middle"><input type="checkbox" checked="checked" id="sf[]" name="sf[]" value="u_view" />&nbsp;View By User?</td>
                                <td align="left" valign="middle"><input type="checkbox" checked="checked" id="sf[]" name="sf[]" value="status" />&nbsp;Entry Status</td>
                              </tr>
                              <tr>
                                <td align="left" valign="middle"><input type="checkbox" checked="checked" id="sf[]" name="sf[]" value="int_show" />&nbsp;Show For Interview?</td>
                                <td align="left" valign="middle"><input type="checkbox" checked="checked" id="sf[]" name="sf[]" value="int_show_info" />&nbsp;Interview Note</td>
                                <td align="left" valign="middle"><input type="checkbox" checked="checked" id="sf[]" name="sf[]" value="int_show_date" />&nbsp;Interview Attendance</td>
                                <td align="left" valign="middle"><input type="checkbox" checked="checked" id="sf[]" name="sf[]" value="hired" />&nbsp;Hired Result</td>
                              </tr>
                              <tr>
                                <td align="left" valign="middle"><input type="checkbox" checked="checked" id="sf[]" name="sf[]" value="interviewer" />&nbsp;Interviewer</td>
                                <td align="left" valign="middle"><input type="checkbox" checked="checked" id="sf[]" name="sf[]" value="interview_note" />&nbsp;Interviewer Note</td>
                                <td align="left" valign="middle"><input type="checkbox" checked="checked" id="sf[]" name="sf[]" value="orientation" />&nbsp;Orientation Date</td>
                                <td align="left" valign="middle"><input type="checkbox" checked="checked" id="sf[]" name="sf[]" value="orientation_office" />&nbsp;Orientation Office</td>
                              </tr>
                              <tr>
                                <td align="left" valign="middle"><input type="checkbox" checked="checked" id="sf[]" name="sf[]" value="orientation_show" />&nbsp;Orientation Shown</td>
                                <td align="left" valign="middle"><input type="checkbox" checked="checked" id="sf[]" name="sf[]" value="orientation_comp" />&nbsp;Orientation Finished</td>
                                <td align="left" valign="middle"><input type="checkbox" id="sf[]" name="sf[]" value="observation" />&nbsp;Observation Date</td>
                                <td align="left" valign="middle"><input type="checkbox" id="sf[]" name="sf[]" value="observation_office" />&nbsp;Observation Office</td>
                              </tr>
                              <tr>
                                <td align="left" valign="middle"><input type="checkbox"  id="sf[]" name="sf[]" value="observation_show" />&nbsp;Observation Shown</td>
                                <td align="left" valign="middle"><input type="checkbox" id="sf[]" name="sf[]" value="observationer" />&nbsp;Observer</td>
                                <td align="left" valign="middle"><input type="checkbox" id="sf[]" name="sf[]" value="observation_comp" />&nbsp;Observation Finished(text)</td>
                                <td align="left" valign="middle"><input type="checkbox" id="sf[]" name="sf[]" value="observation_comps" />&nbsp;Observation Finished(date)</td>
                              </tr>
                              <tr>
                                <td align="left" valign="middle"><input type="checkbox"  id="sf[]" name="sf[]" value="observation_note" />&nbsp;Observation Note</td>
                                <td align="left" valign="middle"><input type="checkbox" checked="checked" id="sf[]" name="sf[]" value="img" />&nbsp;Agent Photo URL</td>
                                <td align="left" valign="middle"><input type="checkbox" checked="checked" id="sf[]" name="sf[]" value="folstatus" />&nbsp;Follow Up Status</td>
                                <td align="left" valign="middle"><input type="checkbox" checked="checked" id="sf[]" name="sf[]" value="folcome" />&nbsp;Follow Up Confirmation</td>
                              </tr>
                              <tr>
                                <td align="left" valign="middle"><input type="checkbox" checked="checked" id="sf[]" name="sf[]" value="folupdated_by" />&nbsp;Last Updated(person)</td>
                                <td align="left" valign="middle"><input type="checkbox" checked="checked" id="sf[]" name="sf[]" value="folupdated_date" />&nbsp;Last Updated(date)</td>
                                <td align="left" valign="middle"><input type="checkbox" checked="checked" id="sf[]" name="sf[]" value="compdate" />&nbsp;Follow Up Completed(date)</td>
                                <td align="left" valign="middle"><input type="checkbox" checked="checked" id="sf[]" name="sf[]" value="compnote" />&nbsp;Follow Up Completed(note)</td>
                              </tr>
                              <tr>
                                <td align="left" valign="middle"><input type="checkbox"  checked="checked" id="sf[]" name="sf[]" value="folupdated_by" />&nbsp;Follow up updated(person)</td>
                                <td align="left" valign="middle"><input type="checkbox" checked="checked" id="sf[]" name="sf[]" value="folupdated_date" />&nbsp;Follow up updated((date)</td>
                                <td align="left" valign="middle"><input type="checkbox" checked="checked" id="sf[]" name="sf[]" value="compdate" />&nbsp;Follow Up Completed(date)</td>
                                <td align="left" valign="middle"><input type="checkbox" checked="checked" id="sf[]" name="sf[]" value="folnote" />&nbsp;Follow Up(note)</td>
                              </tr>
                              <tr>
                                <td align="left" valign="middle"><input type="checkbox" checked="checked" id="sf[]" name="sf[]" value="updatedby" />&nbsp;Entry updated(person)</td>
                                <td align="left" valign="middle"><input type="checkbox" checked="checked" id="sf[]" name="sf[]" value="updatedby_date" />&nbsp;Entry updated(date)</td>
                                <td align="left" valign="middle"><input type="checkbox" checked="checked" id="sf[]" name="sf[]" value="createdby" />&nbsp;Entry Created(person)</td>
                                <td align="left" valign="middle"><input type="checkbox" checked="checked" id="sf[]" name="sf[]" value="enotx" />&nbsp;Email Confirm(entry)</td>
                              </tr>
                              <tr>
                                <td align="left" valign="middle"><input type="checkbox" checked="checked" id="sf[]" name="sf[]" value="textnotx" />&nbsp;Text SMS Confirm(entry)</td>
                                <td align="left" valign="middle"><input type="checkbox" checked="checked" id="sf[]" name="sf[]" value="eornotx" />&nbsp;Email Confirm(Orientation)</td>
                                <td align="left" valign="middle"><input type="checkbox" checked="checked" id="sf[]" name="sf[]" value="textornotx" />&nbsp;Text SMS Confirm(Orientation)</td>
                                <td align="left" valign="middle"><input type="checkbox" checked="checked" id="sf[]" name="sf[]" value="date" />&nbsp;Date Created(entry)</td>
                              </tr>
                            </table>
                      </fieldset>
                        <br/>
                        <fieldset>
                        	<legend>Optimize spresheet</legend>
                        </fieldset>
                    </div>
                </form>
              </div>
                <br/>
                <div id="viewholder" name="viewholder">
                <div>
					<!--result is show here-->
                    <div style='height:500px'>&nbsp;</div>
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