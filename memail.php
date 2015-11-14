<?php
session_start();
include "include/config.php";
include "include/function.php";
adminlogin();
familyredirect();
$showfamily=true;
$user=$_SESSION["rec_user"];
$st=base64_decode($_REQUEST["st"]);
$sortx=explode("&",$st);
$sort=$sortx[0];
$ascx=$sortx[1];
if($ascx=="desc")
	$ascdesc="asc";
else
	$ascdesc="desc";
if(empty($sort))
	$sort="sdate";
$allemails=array();
$qx="select * from m_emails order by email";
if($rx=mysql_query($qx))
{
	if(($num_rows=mysql_num_rows($rx))>0)
	{
		while($roxx=mysql_fetch_array($rx))
			$allemails[]=array('id'=>$roxx["id"],'email'=>stripslashes($roxx["email"]),'optout'=>$roxx["optout"],'cdate'=>$roxx["date"],'sdate'=>'');
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="icon" type="image/png" href="images/favicon.ico">
<link rel="stylesheet" type="text/css" href="css/style.css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
<script type="text/javascript" language="javascript" src="js/script.js"></script>
<script type="text/javascript" language="javascript">
function memail_changeview(value)
{
	if(value !='na' && value.length>0)
		window.location.href='memail.php?st='+value;
}
function memail_option(value)
{
	document.getElementById("message2").innerHTML="";
	var confirmx=false;
	if(value=='ds')
	{
		if(check_cbox('memail_form','eid'))
		{
			confirmx = window.confirm("WARNING! You Are About To Delete The Selected Email!. Action Can't Be Reverted!.\r\n\r\nDo You Want To Proceed?.\r\n\r\nTo Proceed, click Okay or To Cancel click Cancel");
			if(confirmx==true)
				document.getElementById("task").value="delemail";
			else
				document.getElementById("mopt").selectedIndex=0;
		}
		else
		{
			alert("ERROR:You must select at least one email");
			document.getElementById("mopt").selectedIndex=0;
		}
	}
	else if(value=='dae')
	{
		confirmx = window.confirm("WARNING! You Are About To Delete All Email!. Action Can't Be Reverted!.\r\n\r\nDo You Want To Proceed?.\r\n\r\nTo Proceed, click Okay or To Cancel click Cancel");
		if(confirmx==true)
			document.getElementById("task").value="delaemail";
		else
			document.getElementById("mopt").selectedIndex=0;
	}
	else if(value=='ra')
	{
		confirmx = window.confirm("WARNING! You Are About To Resent Email To All The Emails Address!. Action Can't Be Reverted!.\r\n\r\nDo You Want To Proceed?.\r\n\r\nTo Proceed, click Okay or To Cancel click Cancel");
		if(confirmx==true)
			document.getElementById("task").value="resentaemail";
		else
			document.getElementById("mopt").selectedIndex=0;
	}
	else if(value=='ss')
	{
		if(check_cbox('memail_form','eid'))
		{
			confirmx = window.confirm("WARNING! You Are About To Resent Email To All The Selected Email!. Action Can't Be Reverted!.\r\n\r\nDo You Want To Proceed?.\r\n\r\nTo Proceed, click Okay or To Cancel click Cancel");
			if(confirmx==true)
				document.getElementById("task").value="resentremail";
			else
				document.getElementById("mopt").selectedIndex=0;
		}
		else
		{
			alert("ERROR:You must select at least one email");
			document.getElementById("mopt").selectedIndex=0;
		}
	}
	if(confirmx==true)
	{
		document.forms[0].action = "memail_opt.php";
		document.forms['memail_form'].submit();	
	}
}
function closemodal()
{
	document.getElementById("contmodal").style.display="none";
}
function showmodal(value,id)
{
	//document.getElementById("showid").innerHTML=value;
	//document.getElementById("statsdiv").innerHTML="";
	if(value=="new" || (value=="edit" && id.length>0))
	{
		showmodalaemail_s(value,id);
		document.getElementById("contmodal").style.display="block";
	}
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
/**********LINK*********************/
.chatlink {
	text-decoration: none;
}
.chatlink:link {
    color: #FFF;
}
.chatlink:visited {
    color: #FFF;
}
.chatlink:active {
    color: #FFF;
}
.chatlink:hover {
    color:#FFF;
	/*background-color:#FF9900;*/
	text-decoration:underline;
}
.chatlinkb {
	text-decoration: none;
}
.chatlinkb:link {
    color: #000;
}
.chatlinkb:visited {
    color: #000;
}
.chatlinkb:active {
    color: #000;
}
.chatlinkb:hover {
    color: #000;
	/*background-color:#FF9900;*/
	text-decoration:underline;
}
/********end of link *********************/
</style>
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
            	Email Campaign Setup
            </div>
        </div>
        <div id="body_middle_middle">
        	<div id="body_content_cen">Hello <b><u><?php echo $user["username"]; ?></u></b>,To Import a new list of emails please provide the spreadsheet:<br/><br/>
            	<div id="home_icons">
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
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
          	<form enctype="multipart/form-data" action="" method="post" accept-charset="UTF-8" onsubmit="return checkField_excel();" name="memail_form">
  			<input type="hidden" name="MAX_FILE_SIZE" value="2000000"/>
  	
            <input type="hidden" id="task" name="task" value="createexcel"/>
    	    <tr>
    	      <td width="27%" height="37" align="right" valign="middle">Choose File:</td>
    	      <td align="left" valign="middle" width="73%">&nbsp;&nbsp;<input type="file" name="file" id="file" size="60" /></td>
  	      </tr>
          <tr>
          		<td colspan="2" height="20"></td>
          </tr>
    	    <tr>
    	      <td colspan="2" align="right" valign="middle">
              	<div style='text-align:center'>List of Emails</div>
                <!--option for the emails-->
                <?Php
				if(sizeof($allemails)>0)
				{
					?>
                <div style='text-align:center;background-color:#014681; color:#FFF; font-size:15pt'>
                <a href='Javascript:msg_checkall("memail_form","eid",true)' class='chatlink'>Check All</a>
                /
                <a href='Javascript:msg_checkall("memail_form","eid",false)' class='chatlink'>Uncheck All</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Select Option&nbsp;
                <select id="mopt" name="mopt" onchange="memail_option(this.value)">
                	<option value='na'>Select One</option>
                	<option value='ds'>Delete Selected</option>
                    <option value='dae'>Delete All Emails</option>
                    <option value='ra'>Resent All</option>
                    <option value='ss'>Sent Selected</option>
                </select>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                Sort Option&nbsp;
                <select id="s_mopt" name="s_mopt" onchange="memail_changeview(this.value)">
                	<option value='na'>Select Sort</option>
                	<option value='<?php echo base64_encode("sdate"."&".$ascdesc); ?>'>Sort By Date Sent</option>
                	<option value='<?php echo base64_encode("cdate"."&".$ascdesc); ?>'>Sort By Date Created</option>
                	<option value='<?php echo base64_encode("email"."&".$ascdesc); ?>'>Sort By Email</option>
                	<option value='<?php echo base64_encode("optout"."&".$ascdesc); ?>'>Sort By Opt-Out Options</option>
                </select>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a href='javascript:showmodal("new","")' class='chatlink'>Add New +</a>
                </div>
                <?php
				}
				?>
                <!--end of option for the emails-->
                 <div style='background-color:#014681;height:5px'>&nbsp;</div>
              	<div style="border:1px solid #999">
              	<table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr style="background-color:#014681; color:#FFF">
                    <td width='9%'  align='center' valign='middle'>&nbsp;</td>
                    <td width='6%' align='center' valign='middle'>&nbsp;</td>
                    <td width='35%' align='center' valign='middle'>Email</td>
                    <td width='10%' align='center' valign='middle'>Opt-Out</td>
                    <td width='21%' align='center' valign='middle'>Created</td>
                    <td width='19%' align='center' valign='middle'>Last Sent</td>
                  </tr>
                  <tr>
                    <td colspan='6'>
  						<div id="memailcont" style="height:500px; overflow:auto;">
                    	<table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <?Php
						/*$allemails=array();
						$qx="select * from m_emails order by email";
						if($rx=mysql_query($qx))
						{
							if(($num_rows=mysql_num_rows($rx))>0)
							{
								while($roxx=mysql_fetch_array($rx))
								 	$allemails[]=array('id'=>$roxx["id"],'email'=>stripslashes($roxx["email"]),'optout'=>$roxx["optout"],'cdate'=>$roxx["date"],'sdate'=>'');
							}
						}*/
						if(sizeof($allemails)>0)
						{
							for($i=0;$i<sizeof($allemails);$i++)
							{
								$qx="select * from m_emails_sent where emailid='".$allemails[$i]["id"]."' order by date_sent desc limit 1";
								if($rx=mysql_query($qx))
								{
									if(($nx=mysql_num_rows($rx))>0)
									{
										$info=mysql_fetch_assoc($rx);
										$allemails[$i]["sdate"]=$info["date_sent"];
									}
								}
							}
							$rows=sortArray($sort,$allemails,$ascdesc);
							$countx=0;
							$count = 1;
							$total = 0;
							for($i=0;$i<sizeof($rows);$i++)
							{
								$countx=$i+1;
								$total = $count %2;
								if($total !=0)
									$style = "style='background-color:#e6f882'";
								else
									$style="";
								echo "
								  <tr ".$style.">
									<td width='9%'  align='center' valign='middle'>$countx</td>
									<td width='6%' align='center' valign='middle'><input type='checkbox' id='eid' name='eid[]' value='".base64_encode($rows[$i]["id"])."'/></td>
									<td width='35%' align='center' valign='middle'><a href='javascript:showmodal(\"edit\",\"".base64_encode($rows[$i]["id"])."\")' class='chatlinkb'>".$rows[$i]["email"]."</a></td>
									<td width='10%' align='center' valign='middle'>".$rows[$i]["optout"]."</td>
									<td width='21%' align='center' valign='middle'>".fixdate_comps('invdate_s',$rows[$i]["cdate"])."</td>
									<td width='19%' align='center' valign='middle'>".checkNA(fixdate_comps('invdate_s',$rows[$i]["sdate"]))."</td>
								  </tr>";
								 $count++;
							}
						}
						else
							echo "tr><td colspan='6' align='center' valign='middle'>No Email Found</td></tr>";
						?>
                    	</table>
                        </div>
                    </td>
                  </tr>
                </table>
                </div>
              </td>
   	        </tr>
    	    <tr>
    	      <td height="47" colspan="2" align="left" valign="middle">
              <div id="message2" name="message2" class="black" style="text-align:center; padding-right:50px; padding-left:50px">
        &nbsp;
      </div>
              </td>
   	        </tr>
    	    <tr>
    	      <td colspan="2" align="center" valign="middle">
              <a href="home.php" onmouseover="document.cancel.src='images/cancelbtn.jpg'" onmouseout="document.cancel.src='images/cancelbtn.jpg'"><img src="images/cancelbtn.jpg"  border="0" alt="Cancel and return to View Page" name="cancel" /></a>
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              <input type="image" id="importbtn" name="importbtn" src="images/importbtn.png">
              </td>
  	      </tr>
    	    <tr>
    	      <td colspan="2" align="left" valign="middle">&nbsp;</td>
  	      </tr>
          </form>
        </table>
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