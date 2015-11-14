<?php
session_start();
include "include/config.php";
include "include/function.php";
adminlogin();
unset($_SESSION["climit"]);
$popview="close";
$showfamily=true;
$showbrown="";
$woffice=$_SESSION["woffice"];
if(adminlogin_exp())
	$user=$_SESSION["rec_user"];
else
{
	$user=$_SESSION["brownuser"];
	$showfamily=false;
}
$sn=$_REQUEST["sname"];
$stypex=base64_decode($_REQUEST["stype"]); //date called, date of interview, etc
if(empty($stypex))
	$stype_all="selected='selected'";
else
	$stype_all="";
$sof = base64_decode($_REQUEST["sof"]); //ascendant or descendant
$scat = base64_decode($_REQUEST["scat"]);
$url_link=allParam();
$dnamex=getdatename_b($scat);
$datename=$dnamex[0]['name'];
$datenamex=$dnamex[0]['namex'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="icon" type="image/png" href="images/favicon.ico">
<link rel="stylesheet" type="text/css" href="css/style.css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" language="javascript" src="js/script.js"></script>
<script type="text/javascript" language="javascript" src="js/jquery_lib.js"></script>
<script type="text/javascript" language="javascript">
function showpop_view()
{
	var v=document.getElementById("v").value;
	var snameen=document.getElementById("snameen").value;
	var sdtype=document.getElementById("sdtype").value;
	var sof=document.getElementById("sof").value;
	var sdsort=document.getElementById("sdsort").value;
	var scat=document.getElementById("scat").value;
	var msort=document.getElementById("msort").value;
	var msortx=document.getElementById("msortx").value;
	var stype=document.getElementById("stype").value;
	showdivpop_view(v,snameen,sdtype,sof,sdsort,scat,msort,msortx,stype);
}
var intervalID_view = window.setInterval(showpop_view, 10000);
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
function showmore(lastnum)
{
	document.getElementById("showbtn").style.display="none";
	document.getElementById("showloader").style.display="block";
	var v=document.getElementById("v").value;
	var snameen=document.getElementById("snameen").value;
	var sdtype=document.getElementById("sdtype").value;
	var sof=document.getElementById("sof").value;
	var sdsort=document.getElementById("sdsort").value;
	var scat=document.getElementById("scat").value;
	var msort=document.getElementById("msort").value;
	var msortx=document.getElementById("msortx").value;
	var stype=document.getElementById("stype").value;
	clearInterval(intervalID_view);
	intervalID_view = window.setInterval(showmore_x, 10000);
	showmorex(v,snameen,sdtype,sof,sdsort,scat,msort,msortx,lastnum,stype);
}
function showmore_x()
{
	var lastnum=document.getElementById("index").value;
	var v=document.getElementById("v").value;
	var snameen=document.getElementById("snameen").value;
	var sdtype=document.getElementById("sdtype").value;
	var sof=document.getElementById("sof").value;
	var sdsort=document.getElementById("sdsort").value;
	var scat=document.getElementById("scat").value;
	var msort=document.getElementById("msort").value;
	var msortx=document.getElementById("msortx").value;
	var stype=document.getElementById("stype").value;
	showmorex(v,snameen,sdtype,sof,sdsort,scat,msort,msortx,lastnum,stype);
}
function closemodal()
{
	document.getElementById("contmodal").style.display="none";
	intervalID_view = window.setInterval(showmore_x, 10000);
}
function showmodalwoffice(targ)
{
	//document.getElementById("showid").innerHTML=value;
	showmodalcoffice(targ);
	clearInterval(intervalID_view);
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
<title>Welcome to Family Energy Recruiter System</title>
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
.morebox
{
	font-weight:bold;
	color:#333333;
	text-align:center;
	border:solid 1px #333333;
	padding:8px;
	margin-top:8px;
	-moz-border-radius: 6px;-webkit-border-radius: 6px;
}
.morebox a{ color:#333333; text-decoration:none}
.morebox a:hover{ color:#333333; text-decoration:none}
</style>
</head>
<body onload="showpop_view()
<?php
if(!isset($_SESSION["woffice"]))
{
?>
,showmodalwoffice('<?php echo base64_encode('view'); ?>')
<?php
}
?>
">
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
            	All Entries
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
              <?Php
			  if($showfamily)
              	echo "Edit/View All Entries Avaliable";
			  else
			  	 echo "View All Entries Avaliable From Brown Morgan";
				if(!empty($sn))
						echo "For '<i><u>$sn'</u></i>";
				?>
                <?php
              if(isset($_SESSION["woffice"]))
              {
                  ?>
              <br/><span style="text-decoration:underline; font-size:15pt">From <a href="javascript:showmodalwoffice('<?php echo base64_encode('view'); ?>')"><?php echo $woffice["name"]; ?></a>.</span><br/>
              <?php
              }
                ?>
                <br/>
                <div id="message2" name="message2" class="white" style="text-align:center; padding-right:50px; padding-left:50px">&nbsp;</div>
               <?php
			   if($showfamily)
			   {
				   ?>
            	<form method="post" action="view.php" onsubmit="return checkFieldh()">
                <input type="hidden" id="v" name="v" value="<?php echo $_REQUEST["v"]; ?>" />
                Search For Entries Name: <input type="text" size="60" id="sname" name="sname" />&nbsp;&nbsp;<input type="submit" value="Submit" />&nbsp;Or&nbsp;
                <select id="showview" name="showview" onchange="changeview(this.value)">
                	<option value="na" selected="selected">Select Option</option>
                    <option value="all">View All</option>
                    <option value="followup">Follow Up</option>
                    <option value="gstats">Stadistics</option>
                    <option value="reten">Retention</option>
                </select>
                <input type="hidden" id="snameen" name="snameen" value="<?php echo $_REQUEST["sname"];?>" />
                <input type="hidden" id="sdsort" name="sdsort" value="<?php echo $_REQUEST["sdsort"];?>" />
                <input type="hidden" id="sdtype" name="sdtype" value="<?php echo $_REQUEST["sdtype"];?>" />
                <input type="hidden" id="sof" name="sof" value="<?php echo $_REQUEST["sof"];?>" />
                <input type="hidden" id="scat" name="scat" value="<?php echo $_REQUEST["scat"];?>" />
                 <input type="hidden" id="msort" name="msort" value="<?php echo $_REQUEST["msort"];?>" />
                <input type="hidden" id="msortx" name="msortx" value="<?php echo $_REQUEST["msortx"];?>" />
                <input type="hidden" id="stype" name="stype" value="<?php echo $_REQUEST["stype"];?>" />
                </form>
                <?Php
			   }
			   else
			   {
				   ?>
                    <div  style='text-align:center; padding-right:50px; padding-left:40px'>
                    ********************************************************************************************
                    <br/>
                    <a href='viewpend.php'>VIEW INTERVIEWS FOR TOMORROW FOR <?php echo strtoupper(getTypeName($user["type"])); ?></a>
                    <br/>
                    ********************************************************************************************
                </div>
                <br/>
                   <?php
			   }
			   ?>
              </div>
           <?php
			   if($showfamily)
			   {
				   ?>
          <p class="show_this" style="font-size:12pt;color:#666;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[+] Show Advance Search</p>
          <div class="slidingDiv" style="display:none">
            <fieldset>
            	<legend>Select Office <span style='font-size:12pt;color:#666;'>[will show only result based on the office selected]</span></legend>
                <div style="text-align:center">
                	<select id="showofficeo" name="showofficeo" onchange="changesorttype()">
                    	<option value="<?php echo base64_encode("all"); ?>">All Offices</option>
                        <?php
						$queryof = "select * from rec_office order by id";
						if($resultof = mysql_query($queryof))
						{
							if(($numrowsof = mysql_num_rows($resultof))>0)
							{
								while($rowof = mysql_fetch_array($resultof))
								{
									$ofind=$sof;
									if(empty($ofind))
										$ofind=$woffice["id"];
									$office_s=setSelected($ofind,$rowof["id"]);
									echo "<option value='".base64_encode($rowof["id"])."' $office_s>".stripslashes($rowof["name"])."</option>";
								}
							}
						}
						?>
                    </select>
                </div>
            </fieldset>
            <br/>
            <fieldset>
            	<legend>Select Category <span style='font-size:12pt;color:#666;'>[will show only result based on the category selected]</span></legend>
                <div style="text-align:center">
                	<select id="showcato" name="showcato" onchange="changesorttype()">
                    	<option value="<?php echo base64_encode("all"); ?>" <?php echo setSelected('all',$scat); ?>>All Category</option>
                        <option value="<?php echo base64_encode("int"); ?>" <?php echo setSelected('int',$scat);  ?>>Interview Set Only</option>
                        <option value="<?php echo base64_encode("intcancel"); ?>" <?php echo setSelected('intcancel',$scat); ?>>Interview Cancelled</option>
                        <option value="<?php echo base64_encode("intshow"); ?>" <?php echo setSelected('intshow',$scat);  ?>>Shown For Interview</option>
                        <option value="<?php echo base64_encode("intnoshow"); ?>" <?php echo setSelected('intnoshow',$scat);  ?>>No Show - Interview</option>
                        <option value="<?php echo base64_encode("orinoshow"); ?>" <?php echo setSelected('orinoshow',$scat);  ?>>No Show - Orientation</option>
                        <option value="<?php echo base64_encode("hired"); ?>" <?php echo setSelected('hired',$scat);  ?>>Hired</option>
                        <option value="<?php echo base64_encode("nothired"); ?>" <?php echo setSelected('nothired',$scat);  ?>>Not Hired</option>
                        <option value="<?php echo base64_encode("noint"); ?>" <?php echo setSelected('noint',$scat);  ?>>Not Interested</option>
                        <option value="<?php echo base64_encode("oset"); ?>" <?php echo setSelected('oset',$scat);  ?>>Set For Orientation</option>
                        <option value="<?php echo base64_encode("ocomp"); ?>" <?php echo setSelected('ocomp',$scat);  ?>>Orientation Completed</option>
                        <option value="<?php echo base64_encode("orishow"); ?>" <?php echo setSelected('orishow',$scat);  ?>>Orientation Shown</option>
                        <option value="<?php echo base64_encode("oincomp"); ?>" <?php echo setSelected('oincomp',$scat);  ?>>Orientation Incompleted</option>
                    </select>
                </div>
            </fieldset>
            <br/>
            <fieldset>
            	<legend>Select Type<span style='font-size:12pt;color:#666;'>[Show only the selected type of entries]</span></legend>
                <div style="text-align:center">
                	<select id="showtype" name="showtype" onchange="changesorttype()">
                    	<option selected='selected' value="<?php echo base64_encode("all"); ?>" <?php echo $stype_all; ?>>All Type</option>
                        <?php
						$queryof = "select * from task_category order by id";
						if($resultof = mysql_query($queryof))
						{
							if(($numrowsof = mysql_num_rows($resultof))>0)
							{
								while($rowof = mysql_fetch_array($resultof))
								{
									$oselec=setSelected($rowof["id"],$stypex);
									echo "<option value='".base64_encode($rowof["id"])."' $oselec>".stripslashes($rowof["name"])."</option>";
								}
							}
						}
						?>
                    </select>
                </div>
            </fieldset>
           </div>
           <?php
			   }
			   ?>
                <br/>
                <div id="viewholder" name="viewholder">
					<div  style="height:500px">
                 	 <table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr style="background-color:#28629e; color:#FFF">
                        <td width="8%" align="center" valign="middle"></td>
                        <td width="31%" align="center" valign="middle"><a href='Javascript:changesorttype_sort("<?php echo base64_encode("cname")?>","")' class='viewheader_linkw'>Name</a></td>
                        <td width="16%" align="center" valign="middle"><a href='Javascript:changesorttype_sort("<?php echo base64_encode("cphone")?>","")' class='viewheader_linkw'>Phone</a></td>
                        <td width="14%" align="center" valign="middle"><a href='Javascript:changesorttype_sort("<?php echo base64_encode($datenamex)?>","")' class='viewheader_linkw'><?php echo $datename; ?></a></td>
                        <td width="11%" align="center" valign="middle"><a href='Javascript:changesorttype_sort("<?php echo base64_encode("office")?>","")' class='viewheader_linkw'>Office</a></td>
                        <td width="20%" align="center" valign="middle"><a href='Javascript:changesorttype_sort("<?php echo base64_encode("status")?>","")' class='viewheader_linkw'>Status</a></td>
                      </tr>
                      <tr>
                      	<td colspan="6" height="20" valign="middle" align="center"><img src="images/floader.gif" border="0" /></td>
                      </tr>
                    </table>
                    </div>
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