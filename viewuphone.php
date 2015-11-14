<?php
session_start();
include "include/config.php";
include "include/function.php";
date_default_timezone_set('America/New_York');
adminlogin();
familyredirect();
function matchFound($phone)
{
	$query="select * from rec_entries where cphone='".$phone."' or cphonex='".$phone."'";
	if($result=mysql_query($query))
	{
		if(($num_rows=mysql_num_rows($result))>0)
			return true;
		else
			return false;
	}
	else
		return false;
}
$showfamily=true;
$user=$_SESSION["rec_user"];
$sort=base64_decode($_REQUEST["st"]);
$today=date('Y-m-d');
if(empty($sort) || $sort =='na')
	$sortx="date";
else
{
	$ex=explode("&",$sort);
	$sortx=$ex[0];
	$ascx=$ex[1];
	$sortx=$sortx." ".$ascx;
}
include "include/write_excel_mphone.php";
if(empty($ascx))
	$asc="desc";
else
{
	if($ascx=="desc")
		$asc="asc";
	else
		$asc="desc";
}
$date1 = $_REQUEST["date1"];
$date2 = $_REQUEST["date2"];
if(!empty($date1) && !empty($date2))
{
	$date1 = fixdate_comps("mildate",$_REQUEST["date1"]);
	$date2 = fixdate_comps("mildate",$_REQUEST["date2"]);
}
else
{
	$date1=getFirstDay($today);
	$date2=getLastDay($today);
}
$checkdate=false;
$vname="";
if(!empty($date1) && !empty($date2))
{
	$checkdate = true;
	$vname = " From Date Range: <u>".fixdate_comps("d",$date1)."</u> To <u>".fixdate_comps("d",$date2)."</u>";
}
$date1_n=fixdate_comps('invdate_s',$date1);
$date2_n=fixdate_comps('invdate_s',$date2);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="icon" type="image/png" href="images/favicon.ico">
<link rel="stylesheet" type="text/css" href="css/style.css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
<script type="text/javascript" src="js/calendarb_js/date.js"></script>
<script type="text/javascript" src="js/calendarb_js/jquery.datePicker.js"></script>
<link rel="stylesheet" type="text/css" href="css/calendarb.css">
<script type="text/javascript" language="javascript" src="js/script.js"></script>
<script type="text/javascript" language="javascript">
$(function()
{
	//$('.date-pick').datePicker({autoFocusNextInput: true});
	Date.format = 'mm/dd/yyyy';
	$('.date-pick').datePicker({startDate:'01/01/1996'});
});
function changephonexview(value)
{
	if(value !='na')
	{
		var date1=document.getElementById("date1x").value;
		var date2=document.getElementById("date2x").value;
		window.location.href="viewuphone.php?st="+value+"&date1="+date1+"&date2="+date2;
	}
}
</script>
<title>Welcome to Family Energy Recruiter System</title>
</head>
<body>
<div id="main_cont">
	<?php
	include "include/header.php";
	?>
    <div id="body_middle" >
    	<div id="body_middle_header">
        	<div id="body_middle_header_title">
            	All Unknown Phones
            </div>
        </div>
        <div id="body_middle_middle" >
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
                 <div style="text-align:center; font-size:15pt;">Hello <b><u><?php echo $user["username"]; ?></u></b>, Phone Number Information. <?Php echo $excelbtn; ?>&nbsp;&nbsp;
                <select id="selectview" name="selectview" onchange="changephonexview(this.value)">
                    <option value="na" selected="selected">Select A Option</option>
                    <!--<option value="newuser">Create New User</option>-->
                    <option value="<?php echo base64_encode('office'."&".$asc); ?>">Sort By Office</option>
                    <option value="<?php echo base64_encode('caller'."&".$asc); ?>">Sort By Caller</option>
                    <option value="<?php echo base64_encode('tphone'."&".$asc); ?>">Sort By Phone</option>
                    <option value="<?php echo base64_encode('date'."&".$asc); ?>">Sort By Date</option>
                </select>
                <input type="hidden" id="date1x" name="date1x" value="<?php echo $date1; ?>" /> 
                <input type="hidden" id="date2x" name="date2x" value="<?php echo $date2; ?>" /> 
                <input type="hidden" id="sortx" name="sortx" value="<?php echo $_REQUEST["st"]; ?>" /> 
               </div>
               <br/>
               <div style="text-align:center"><?php echo $vname; ?></div>
                <br/>
                <div id="message2" name="message2" class="white" style="text-align:center; padding-right:50px; padding-left:50px">
                <?php
                 if(isset($_SESSION["recresult"]))
                 {
                     echo $_SESSION["recresult"];
                     unset($_SESSION["recresult"]);
                 }
                ?>
               </div>
               <div style="text-align:center">
                <form method="post" action="viewuphone.php" onsubmit="return checkFieldk();" >
                	<div style="text-align:center; margin-left:auto; margin-right:auto; padding-left:80px;">
                        <div style="width:340px; text-align:center; margin-left:auto; margin-right:auto; float:left;">
                            <div style="float:left; padding-right:5px;">Choose A Date Range:</div>&nbsp;
                            <input name="date1" id="date1" class="date-pick" readonly="readonly" value='<?php echo $date1_n; ?>' />
                        </div>
                        &nbsp;&nbsp;&nbsp;
                        <div style="float:left; padding-right:10px;">
                         To:
                        </div>
                        <div style="width:200px; text-align:center; margin-left:auto; margin-right:auto;float:left">
                            <input name="date2" id="date2" class="date-pick" readonly="readonly" value='<?php echo $date2_n; ?>' />
                        </div>
                        <div style="float:left">
                        	&nbsp;&nbsp;<input type="submit" value="Submit" />
                        </div>
                        <div style="clear:both"></div>
                    </div>
                </form>
               </div>
               <br/>
              <div style="width:850px;" id="phonecont" name="phonecont">
				<?php
				$query = "select distinct tphone,fphone,office,date,caller from rec_phones where date between '".$date1."' and '".$date2."' and caller != 'Anonymous' order by ".$sortx;
				if($result = mysql_query($query))
				{
					if(($num_rows = mysql_num_rows($result))>7)
                       $height ="style='font-size:15pt;'";
                     else
                        $height="style='height:500px;font-size:15pt;'";
				}
                else
                   $height="style='height:500px;font-size:15pt;'";
                ?>
               <table width="100%" border="0" cellspacing="0" cellpadding="0">
               <tr style="background-color:#014681; color:#FFF">
					<td width="7%">&nbsp;</td>
					<td width="28%" align="center" valign="middle">Caller Id</td>
					<td width="27%" align="center" valign="middle">Phone</td>
					<td width="21%" align="center" valign="middle">Office</td>
					<td width="17%" align="center" valign="middle">Called Date</td>
               </tr>
               <tr>
               		<td colspan="5">
                    	<div id="phonecont_in" name="phonecont_in" <?php echo $height; ?>>
                       	   <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                           <?php
						   //sort and get the phones
						   	$allphones=array();
							if($result=mysql_query($query))
							{
								if(($num_rows=mysql_num_rows($result))>0)
								{
									while($rows=mysql_fetch_array($result))
									{
										if(sizeof($allphones)<1)
											$allphones[]=array('id'=>$rows["id"],'caller'=>$rows["caller"],'fphone'=>$rows["fphone"],'date'=>$rows["date"],'office'=>$rows["office"]);
										else
										{
											for($i=0;$i<sizeof($allphones);$i++)
											{
												$found=false;
												if(trim($allphones[$i]["fphone"])==trim($rows["fphone"]))
												{
													$found=true;
													break;
												}
											}
											if(!$found)
												$allphones[]=array('id'=>$rows["id"],'caller'=>$rows["caller"],'fphone'=>$rows["fphone"],'date'=>$rows["date"],'office'=>$rows["office"]);
										}
									}
								}
							}
						   if(sizeof($allphones)>0)
						   {
							    $count = 1;
							    $total = 0;
							 	for($i=0;$i<sizeof($allphones);$i++)
								{
									if(!matchFound($allphones[$i]["fphone"]))
									{
										$total = $count %2;
										if($total !=0)
											$style = "style='background-color:#e6f882'";
										else
											$style="";
										$date=fixdate_comps('invdate_s',$allphones[$i]["date"]);
										echo "<tr class='rowstyle' $style>
										<td height='27' width='7%' align='center' valign='middle'>$count</td>
										<td height='27' align='center' valign='middle' width='28%'>".stripslashes($allphones[$i]["caller"])."</td>
										<td height='27' align='center' valign='middle' width='27%'>".stripslashes(trim($allphones[$i]["fphone"]))."</td>
										<td height='27' align='center' valign='middle' width='21%'>".getOfficeName($allphones[$i]["office"])."</td>
										<td height='27' align='center' valign='middle' width='17%'>".$date."</td>
										</tr>";
										$count++;
									}
								}
						   }
						   else
								echo "<tr class='rowstyleno'><td colspan='5' align='center' valign='middle'>No Phone Found</td></tr>";
							?>
                           </table>
                        </div>
                    </td>
               </tr>
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