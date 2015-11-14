<?php
session_start();
include "include/config.php";
include "include/function.php";
include "include/stats_header_script.php";
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
	document.getElementById("statsdiv").innerHTML="";
	document.getElementById("loadergif").style.display="block";
	document.getElementById("contmodal").style.display="none";
}
function showmodal(value)
{
	//document.getElementById("showid").innerHTML=value;
	//document.getElementById("statsdiv").innerHTML="";
	showmodalstats_s(value);
	document.getElementById("contmodal").style.display="block";
}
$().ready(function() {
	/*var $scrollingDiv = $("#modalform");
	$(window).scroll(function(){
		$scrollingDiv
			.stop()
			.animate({"marginTop": ($(window).scrollTop() + 5) + "px"}, "slow" );
	});*/
	
	//script for pop up
	/*  var moveLeft = 30;
	  var moveDown = -10;
	  $('a#trigger').hover(function(e) {
		alert('here')
		//$('div#pop-up').show();
		
	  }, function() {
		  
		$('div#pop-up').hide();
	  });
	
	  $('a#trigger').mousemove(function(e) {
		$("div#pop-up").css('top', e.pageY + moveDown).css('left', e.pageX + moveLeft);
	  });*/
	//end of script
});
function modalpop(value)
{
	//alert(value);
	var moveLeft = 30;
	var moveDown = -10;
	$('div#pop-up').show();
	modalpop_ajax(value);
	$('a#trigger').mousemove(function(e) {
		$("div#pop-up").css('top', e.pageY + moveDown).css('left', e.pageX + moveLeft);
    });
}
function modalpophide()
{
	document.getElementById("pop-up-in").innerHTML="";
	document.getElementById("popfloader").style.display="block";
	$('div#pop-up').hide();
}
function stats_view()
{
	var date1=document.getElementById("date1x").value;
	var date2=document.getElementById("date2x").value;
	var sfilter=document.getElementById("sfilterx").value;
	showstatspop_view(date1,date2,sfilter);
}
var intervalID_view = window.setInterval(stats_view, 5000);
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
/********css for the rollover div***/

/*****end of css for the rollover div**/
/* HOVER STYLES */
div#pop-up {
  display: none;
  position: absolute;
  width: 500px;
  overflow:auto;
  padding: 10px;
  background: #FFF;
  color: #000000;
  border: 1px solid #1a1a1a;
  font-size: 90%;
  z-index:20001;
}
#popfloader{
	text-align:center; padding-top:20px;
}
</style>
<title>Welcome to Family Energy Recruiter System</title>
</head>
<body>
<div id="pop-up">
	<div id="popfloader"><img src="images/floader.gif" border="0" /></div>
	<div id="pop-up-in">
    </div>
</div>
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
            	View Statistics
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
           	  <div style="text-align:center">
              	View Statistics&nbsp;&nbsp;<?Php echo $excelbtn; ?><br/>
                <div id="message2" name="message2" class="white" style="text-align:center; padding-right:50px; padding-left:50px">&nbsp;</div>
            	<form method="post" action="viewstats.php" onsubmit="return checkFieldk();" >
                	<div style="text-align:center; margin-left:auto; margin-right:auto; padding-left:20px;">
                        <div style="width:270px; text-align:center; margin-left:auto; margin-right:auto; float:left;">
                            <div style="float:left; padding-right:5px;">Date Range:</div>&nbsp;
                            <input name="date1" id="date1" class="date-pick" readonly="readonly" value='<?php echo $date1_n; ?>' />
                        </div>
                        &nbsp;&nbsp;&nbsp;
                        <div style="float:left; padding-right:10px;">
                         To:
                        </div>
                        <div style="width:180px;float:left">
                            <input name="date2" id="date2" class="date-pick" readonly="readonly" value='<?php echo $date2_n; ?>' />
                        </div>
                        <div style="float:left">
                        	&nbsp;&nbsp;Source:
                            <select id="sfilter" name="sfilter">
                            	<option value='all'>All Source</option>
                            	<?php
									$gf="select * from rec_source order by name";
									if($rf=mysql_query($gf))
									{
										if(($numgf=mysql_num_rows($rf))>0)
										{
											while($rowf=mysql_fetch_array($rf))
											{
												$fselected=setSelected($rowf["id"],$sfilter);
												echo "<option value='".base64_encode($rowf["id"])."' $fselected>".stripslashes($rowf["name"])."</option>";
											}
										}
									}
								?>
                            </select>
                        	<input type="hidden" id="date1x" name="date1x" value="<?Php echo $date1; ?>"/>
                            <input type="hidden" id="date2x" name="date2x" value="<?Php echo $date2; ?>"/>
                            <input type="hidden" id="sfilterx" name="sfilterx" value="<?Php echo $_REQUEST["sfilter"]; ?>"/>
                        	&nbsp;&nbsp;<input type="submit" value="Submit" />&nbsp;&nbsp;<input type="button" value="Back" onclick="backview()" />
                        </div>
                        <div style="clear:both"></div>
                    </div>
                </form>
              </div>
                <br/>
                <div id="viewholder" name="viewholder">
                <div><!--start entries holder-->
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="22%" align="center" valign="middle">Office</td>
                    <td width="15%" align="center" valign="middle">Calls</td>
                    <td width="16%" align="center" valign="middle">Interviews</td>
                    <td width="16%" align="center" valign="middle">Orientation</td>
                    <td width="17%" align="center" valign="middle">Orientation<br/>
                    Completed</td>
                    <td width="14%" align="center" valign="middle">Sales</td>
                  </tr>
                  <tr>
                    <td colspan="6" align="center" valign="middle"><hr/></td>
                  </tr>
                  <tr>
                    <td colspan="6" height="400" align="left" valign="top">
                    	<div id="viewstats_holder" name='viewstas_holder'>
 							<div id="loadergif" style="text-align:center;"><img src="images/floader.gif" border="0" /></div>
                        </div>
                    </td>
                  </tr>
                  <!--<tr><td colspan='6' align='center' valign='middle'><hr/></td></tr>-->
                  <tr><td colspan='6' align='center' valign='middle'>&nbsp;</td></tr>
                </table>
                </div><!--end of entries holder-->
                <div style="height:200px"></div>
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