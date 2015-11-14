<?php
session_start();
include "include/config.php";
include "include/function.php";
adminlogin();
$popview="close";
$showfamily=true;
if(adminlogin_exp())
	$user=$_SESSION["rec_user"];
else
{
	$user=$_SESSION["brownuser"];
	$showfamily=false;
}
$id = base64_decode($_REQUEST["id"]);
if(empty($id))
{
	$_SESSION["recresult"]="ERROR: Invalid Entry";
	header("location:view.php");
	exit;
}
else
{
	//update the view and height of content 
	$query = "select * from rec_entries where id='".$id."'";
	if($result = mysql_query($query))
	{
		if(($num_rows = mysql_num_rows($result))>0)
		{
			$userm= mysql_fetch_assoc($result);
			if(!empty($userm["interviewer"]) || $userm["interviewer"] !=0 && $userm["status"] !='8')
				$warset="yes";
			else
				$warset="no";
			if(!empty($userm["orientation"]) || $userm["status"] =='3' || $userm["status"] =='7' )
				$orset="yes";
			else
				$orset="no";
			if(!empty($userm["ori_show"]) && $userm["ori_show"]=="yes" )
				$oshow="yes";
			else
				$oshow="no";
			if(!empty($userm["ori_comp"]) && $userm["ori_comp"]=="yes" )
				$ocomp="yes";
			else
				$ocomp="no";
			if(pView($user["id"]))
			{
				$queryy = "update rec_entries set u_view='yes' where id='".$id."'";
				@mysql_query($queryy);
			}
			if($userm["interviewer"]==$user["id"])
			{
				$queryy = "update rec_entries set int_view='yes' where id='".$id."'";
				@mysql_query($queryy);
			}
			if($userm["observationer"]==$user["id"])
			{
				$queryy = "update rec_entries set ob_view='yes' where id='".$id."'";
				@mysql_query($queryy);
			}
			if($userm["status"] =="1")
				$styleh = "style='height:900px'";
			else
				$styleh = "";
		}
		else
		{
			$_SESSION["recresult"]="ERROR: Invalid Entry";
			header("location:view.php");
			exit;
		}
	}
	else
	{
		$_SESSION["recresult"]="ERROR: Invalid Entry";
		header("location:view.php");
		exit;
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="icon" type="image/png" href="images/favicon.ico">
<link rel="stylesheet" type="text/css" href="css/style.css" />
<link rel="stylesheet" type="text/css" href="css/calendarb.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
<script type="text/javascript" src="js/calendarb_js/date.js"></script>
<script type="text/javascript" src="js/calendarb_js/jquery.datePicker.js"></script>
<script type="text/javascript" language="javascript" src="js/script.js"></script>
<script type="text/javascript">
 $(function()
 {
	//$('.date-pick').datePicker({autoFocusNextInput: true});
	Date.format = 'mm/dd/yyyy';
	$('.date-pick').datePicker({startDate:'01/01/1996'});
 });
function closemodal()
{
	document.getElementById("contmodal").style.display="none";
}
function showmodal(value)
{
	//document.getElementById("showid").innerHTML=value;
	showmodalform_text(value);
	document.getElementById("contmodal").style.display="block";
}
function reopen(v1,v2)
{
	var confirmx = window.confirm("WARNING!! You are about to Re-Open This Entry! doing so will cause system to delete all the followups informations made in the past for this entry.\r\n\r\nDo You Want To Proceed?.\r\n\r\nTo Proceed, click Okay or To Cancel click Cancel");
	if(confirmx==true)
		window.location.href="save.php?id="+v1+"&task="+v2;
}
</script>
<style>
#contmodal{
	display:none;
}
#modalform{
	position:absolute;z-index:10000; width:800px; height:500px; background:#FFF;right: 0;left: 0; margin:0 auto; top:30%; color:#000; padding:40px;
}
#modalpop{
	position:fixed; z-index:9000;background-color:#000; top: 0; right: 0; bottom: 0; left: 0;opacity:0.4;
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
            	Edit User Information
            </div>
        </div>
        <div id="body_middle_middle" <?php //echo $styleh; ?>>
        	<div id="body_content">
            <div id="message" name="message" class="white" style="text-align:center; padding-right:50px; padding-left:50px">
            	<?Php
				if(isset($_SESSION["recresult"]))
				{
					echo $_SESSION["recresult"]."<br/>";
					unset($_SESSION["recresult"]);
				}
				?>
            </div>
            <?php
			if($userm["catid"] !='1')
			{
				?>
			<div  style='text-align:center; padding-right:50px; padding-left:40px'>
				********************************************************************************************
                <br/>
                ENTRY FROM COMPANY: <u><?php echo strtoupper(getExtraName($userm["catid"])); ?></u>
                <br/>
                ********************************************************************************************
            </div>
            <br/>
            <?Php
			}
			?>
            <form action="save.php" method="post" onsubmit="return checkFieldd()" enctype="multipart/form-data">
            <input type="hidden" id="id" name="id" value="<?php echo $_REQUEST["id"]; ?>" />
            <input type="hidden" id="task" name="task" value="saverec_m" />
            <input type="hidden" id="statusm" name="statusm" value="<?php echo $userm["status"]; ?>" />
            <input type="hidden" id="warset" name="warset" value="<?php echo $warset; ?>" />
            <input type="hidden" id="orset" name="orset" value="<?php echo $orset; ?>" />
            <input type="hidden" id="oshow" name="oshow" value="<?php echo $oshow; ?>" />
            <input type="hidden" id="ocomp" name="ocomp" value="<?php echo $ocomp; ?>" />
            <input type="hidden" id="codate" name="codate" value="<?php echo fixdate_comps('onsip',$userm["orientation"]); ?>" />
           <!-- <input type="hidden" id="cotime" name="cotime" value="<?php //echo fixdate_comps('hx',$userm["orientation"]); ?>" />-->
           	  <fieldset>
               	<legend>Information Of: <b><?php echo checkNA(stripslashes($userm["cname"])); ?></b>&nbsp;&nbsp;[<a href='setting_rec.php?id=<?Php echo base64_encode($userm["id"]); ?>'>Edit Information</a>]&nbsp;&nbsp;[<a href="javascript:showmodal('<?Php echo base64_encode($userm["id"]); ?>')">Send Text Message</a>]</legend>
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="19%" align="right" valign="middle">Date For Interview:</td>
                    <td width="22%" align="left" valign="middle">&nbsp;<b><?php echo rLongTextd(fixdate_comps('onsips',$userm["idate"]." ".$userm["itime"])); ?></b></td>
                    <td width="2%" align="left" valign="middle">&nbsp;</td>
                    <td width="17%" align="right" valign="middle">Caller Email:</td>
                    <td width="40%" align="left" valign="middle">&nbsp;<b><?php echo rLongTextd(checkNA(stripslashes($userm["email"]))); ?></b></td>
                  </tr>
                  <tr>
                    <td align="right" valign="middle">Phone Number:</td>
                    <td align="left" valign="middle">&nbsp;<b><?php echo rLongTextd(checkNA($userm["cphone"])); ?></b></td>
                    <td align="left" valign="middle">&nbsp;</td>
                    <td align="right" valign="middle">Address:</td>
                    <td align="left" valign="middle">&nbsp;<b><?php echo rLongTextd(checkNA($userm["address"])); ?></b></td>
                  </tr>
                  <tr>
                    <td align="right" valign="middle">Date Called:</td>
                    <td align="left" valign="middle">&nbsp;<b><?php echo rLongTextd(fixdate_comps('invdate_s',$userm["cdate"])); ?></b></td>
                    <td align="left" valign="middle">&nbsp;</td>
                    <td align="right" valign="middle">City:</td>
                    <td align="left" valign="middle">&nbsp;<b><?php echo rLongTextd(checkNA(stripslashes($userm["city"]))); ?></b></td>
                  </tr>
                  <tr>
                    <td align="right" valign="middle" title="<?php echo getSourceName($userm["csource"]); ?>">Source:</td>
                    <td align="left" valign="middle">&nbsp;<b><?php echo rLongTextd(getSourceName($userm["csource"])); ?></b></td>
                    <td align="left" valign="middle">&nbsp;</td>
                    <td align="right" valign="middle">Country:</td>
                    <td align="left" valign="middle">&nbsp;<b><?php echo rLongTextd(checkNA(stripslashes($userm["country"]))); ?></b></td>
                  </tr>
                  <tr>
                    <td align="right" valign="middle">Source Info:</td>
                    <td align="left" valign="middle" title="<?php echo getSourceInfo($userm["csource_title"]); ?>">&nbsp;<b><?php echo rLongTextd(getSourceInfo($userm["csource_title"])); ?></b></td>
                    <td align="left" valign="middle">&nbsp;</td>
                    <td align="right" valign="middle">State:</td>
                    <td align="left" valign="middle">&nbsp;<b><?php echo rLongTextd(checkNA(stripslashes($userm["state"]))); ?></b></td>
                  </tr>
                  <tr>
                    <td align="right" valign="middle">From Office:</td>
                    <td align="left" valign="middle">&nbsp;<b><?php echo rLongTextd(checkNA(stripslashes(getOfficeName($userm["office"])))); ?></b></td>
                    <td align="left" valign="middle">&nbsp;</td>
                    <td align="right" valign="middle">Zip/ Postal Code: </td>
                    <td align="left" valign="middle">&nbsp;<b><?php echo rLongTextd(checkNA($userm["zip"])); ?></b></td>
                  </tr>
                  </table>
                </fieldset>
                <br/><br/>
                <?php 
				if($userm["folcome"]=="no")
				{
					?>
                <fieldset><!--cancelled interview-->
                	<legend><span style="color:#F00; font-size:18pt;">Cancelled Information</span> <span style="font-size:13pt; color:#999; font-style:italic;">By: <?php echo getName($userm["folupdated_by"]);  ?> On date: <?php echo fixdate_comps("d", $userm["folupdated_date"]); ?></span>&nbsp;&nbsp;[<a href='javascript:reopen("<?php echo $_REQUEST["id"]; ?>","<?php echo 'reopen'; ?>")'>Re-Open</a>]</legend>
                    <div style="text-align:center">
                    	Cancelled Date: <?php echo fixdate_comps("all",$userm["compdate"]); ?>
                        <br/><hr/><br/>
                        <u>Cancelled Note:</u>
                        <br/>
                         <?php echo $userm["compnote"]; ?>
                    </div>
                </fieldset>
                <?php
				}
				else
				{
				?>
                 <fieldset> <!--showed for interview-->
                	<legend>Showed for Interview?</legend>
                    <div style="text-align:center">
                    <?php
					$style = "style='display:none'";
					$styleingmang = "";
					$intshow_div="style='display:none'";
					if(empty($userm["int_show"]))
					{
						echo "<span class='red'>**** Interviewer Required.  Please choose an whether or not this person check-in for Interview ****</span><br/><Br/>";
					}
					else
					{
						if($userm["status"]=="20")
						{
							$style="";
							$intshowcancel = "selected='selected'";
							$styleingmang = "style='display:none'";
						}
						if($userm["status"]=="8")
						{
								$style="";
								$intshowno = "selected='selected'";
								$styleingmang = "style='display:none'";
						}
						else
							$intshowyes = "selected='selected'";
					}
						?>
                        <select id="intshow" name="intshow" onchange="displayintnote(this.value)">
                          <option value="na">Select Status</option>
						  <option value="yes" <?php echo $intshowyes; ?>>Yes</option>
                          <option value="no" <?php echo $intshowno; ?>>No</option>
                          <option value="cancel" <?php echo $intshowcancel; ?>>Cancelled</option>
                    	</select>
                    <br/>
                    <div id="showintnote" name='showintnote' <?Php echo $style; ?> ><br/>Please Provide Reason<br/><textarea cols="100" rows="8" id="cintnote" name="cintnote"><?php echo nl2br(stripslashes($userm["int_show_info"])); ?></textarea></div>
                    </div>
                </fieldset>
                <br/><br/>
        		<div id="intmangdiv" name="intmangdiv" <?php echo $styleingmang; ?>>
                <fieldset>
                	<legend>Select A Manager For Interview</legend>
                    <div style="text-align:center">
                    <?php
					if(empty($userm["interviewer"]))
						echo "<span class='red'>**** Interviewer Required.  Please choose an Agent for interview ****</span><br/><Br/>";
					?>
                    <select id="intagent" name="intagent">
                          <option value="na">Select Agent</option>
                          <?php
                           $queryagent = "select * from task_users where status='1' order by name";
                            if($resulta = mysql_query($queryagent))
                            {
                               if(($num_rowsa = mysql_num_rows($resulta))>0)
                               {
                                   while($rowa = mysql_fetch_array($resulta))
                                   {
                                      if(!empty($userm["interviewer"]))
                                      {
                                         if($userm["interviewer"]==$rowa["id"])
                                             echo "<option value='".base64_encode($rowa["id"])."' selected='selected'>".stripslashes($rowa["name"])."</option>";
                                         else
                                             echo "<option value='".base64_encode($rowa["id"])."'>".stripslashes($rowa["name"])."</option>";
                                      }
                                      else
                                        echo "<option value='".base64_encode($rowa["id"])."'>".stripslashes($rowa["name"])."</option>";
                                    }
                                }
                            }
                         ?>
                    </select>
                    </div>
                </fieldset>
                </div>
                <?Php
				if($userm["int_show"]=="yes")
					$int_show_opt_s="";
				else
					$int_show_opt_s="style='display:none'";
				?>
                <div id="int_show_opt" <?php echo $int_show_opt_s; ?>>
					<?php
                    $checkhired="no";
                    if(!empty($userm["interviewer"]))
                    {
                        $checkhired="yes";
                        ?>
                        <br/><br/>
                        <fieldset>
                            <legend>Status of Interview</legend>
                            <div style="text-align:center">
                            <?php
                            if(empty($userm["hired"]))
                                echo "<span class='red'>**** Please Provide Interview Result Information ****</span><br/><Br/>";
                            ?>
                            <select id="hired" name="hired" onchange="displaynote(this.value)">
                            <?php
                            $style="style='display:none'";
                            $checkhirednote="no";
                            if($userm["hired"]=="yes")
                                $hiredyes="selected='selected'";
                            else if($userm["hired"]=="no" || $userm["status"]=='9')
                            {
                                $checkhirednote="yes";
                                $style="";
                                if($userm["status"]=="9")
                                    $hirednoint ="selected='selected'";
                                else
                                    $hiredno="selected='selected'";
                            }
                            else
                                $hiredpending="selected='selected'";
                            ?>
                            <option value='na' <?php echo $hiredpending; ?>>Choose An Decision</option>
                            <option value='yes' <?php echo $hiredyes; ?>>Aproved For Orientation</option>
                            <option value='no' <?php echo $hiredno; ?>>No - Not Hired</option>
                            <option value='notint' <?php echo $hirednoint; ?>>Not Interested</option>
                            </select>
                            <div id="shownote" name='shownote' <?Php echo $style; ?> ><br/>Please Provide Reason<br/><textarea cols="100" rows="8" id="cnote" name="cnote"><?php echo nl2br(stripslashes($userm["interview_note"])); ?></textarea></div>
                            </div>
                        </fieldset>
                    <?Php
                    }
                    ?>
                    <input type="hidden" id="checkhired" name="checkhired" value="<?php echo $checkhired; ?>" />
                    <input type="hidden" id="checkhirednote" name="checkhirednote" value="<?php echo $checkhirednote; ?>" />
                    <?Php
                    if($userm["hired"]=="yes")
                        $showhirepdiv="";
                    else
                        $showhirepdiv ="style='display:none'";
                    ?>
                    <?php
                    $oriendiv = "style='display:none'";
                    $checkoprocess="no";
                    if($userm["hired"]=='yes')
                    {
                        $oriendiv = "";
                        $checkoprocess = "yes";
                    }
                    ?>
                    <div id="oriendiv" <?php echo $oriendiv; ?>><!--content for orientation-->
                    <br/><br/>
                    <fieldset>
                        <legend>Orientation Setup&nbsp;&nbsp;
                            <?php
                                if(!empty($userm["orientation_comp"]))
                                    echo "<span style='font-size:13pt; font-style:italic;'><u>Completed On: ".fixdate_comps("d",$userm["orientation_comp"])."</u></span>";
                            ?>
                         </legend>
                         <div style="text-align:center">
                        <?php
                        if(empty($userm["orientation"]))
                            echo "<span class='red'>**** Orientation Setup.  Please Fill Up The Following****</span><br/><Br/>";
                        if(empty($userm["orientation"]))
                        {
                            $checkodate="yes";
                            $showodate="";
                        }
                        else
                        {
                            $checkodate="no";
                            $showodate="style='display:none'";
                        }
                        ?>
                        Orientation Date: <b><?php echo fixdate_comps('all',$userm["orientation"]); ?></b>&nbsp;
                        <?php
                        if(!empty($userm["orientation"]))
                            echo "<input type='checkbox' id='checkodate_check' name='checkodate_check' onclick='changeodatef()'>&nbsp;Change?&nbsp;&nbsp;";
                        ?>
                        <br/>
                        <span id="odate_div" name="odate_div" <?Php echo $showodate; ?>>
                            <div style="width:450px; text-align:center; margin-left:auto; margin-right:auto;">
                            <input name="odate" id="odate" class="date-pick" readonly="readonly" />
                            &nbsp;&nbsp;Time:&nbsp;
                            <select id="ohour" name="ohour">
                                <option value='na'>Hour</option>
                                <?php
                                    for($i=1;$i<13;$i++)
                                    {
                                        if($i<10)
                                            $timer = "0".$i;
                                        else
                                            $timer = $i;
                                        echo "<option value='$i'>$timer</option>";
                                    }
                                ?>
                            </select>&nbsp;:&nbsp;
                            <select id="ominute" name="ominute">
                                <option value='na'>Minutes</option>
                                <?php
                                    echo "<option value='00'>00</option><option value='15'>15</option><option value='30'>30</option><option value='45'>45</option>";
                                ?>
                            </select>
                            <select id="oampm" name="oampm">
                                <option value="am">AM</option>
                                <option vlaue="pm">PM</option>
                            </select>
                            </div>
                        </span>
                        <br/>
                        <hr/>
                        <br/>
                        Select Office For Orientation:<br/>
                        <select id="ooffice" name="ooffice">
                            <option value="na">Select Office</option>
                            <?php
                            $query="select * from rec_office order by id";
                            if($result=mysql_query($query))
                            {
                                if(($num_rows=mysql_num_rows($result))>0)
                                {
                                    while($row = mysql_fetch_array($result))
                                    {
                                        if(!empty($userm["orientation_office"]))
                                        {
                                            if($userm["orientation_office"]==$row["id"])
                                                echo "<option value='".base64_encode($row["id"])."' selected='selected'>".stripslashes($row["name"])."</option>";
                                            else
                                                echo "<option value='".base64_encode($row["id"])."'>".stripslashes($row["name"])."</option>";
                                        }
                                        else
                                            echo "<option value='".base64_encode($row["id"])."'>".stripslashes($row["name"])."</option>";
                                    }
                                }
                            }
                            ?>
                        </select>
                        <br/>
                        <?Php
                        if(!empty($userm["orientation"]))
                        {
                            ?>
                            <hr/><br/>
                            <?php
                            if(empty($userm["ori_show"]))
                                echo "<span class='red'>**** Please Answer The Following Question ****</span><br/><Br/>";
                            ?>
                            Did <u><?php echo stripslashes($userm["cname"]); ?></u> Attended Orientation?<br/>
                            <select id="ori_show" name="ori_show" onchange="changeOriShow(this.value)">
                                <option value="na">Select Answer</option>
                                <option value="yes" <?Php if($userm["ori_show"]=="yes") echo "selected='selected'"; ?>>Yes</option>
                                <option value="no" <?Php if($userm["ori_show"]=="no") echo "selected='selected'"; ?>>No</option>
                             </select>
                             <br/>
                            <?Php
                            $ori_show_m_opt="";
                            if($userm["ori_show"]=="yes")
                            {
                                $ori_show_s="";
                                $ori_show_div_no="style='display:none'";
                            }
                            else if($userm["ori_show"]=="no")
                            {
                                $ori_show_s="style='display:none'";
                                $ori_show_div_no="";
                            }
                            else
                            {
                                $ori_show_s="style='display:none'";
                                $ori_show_div_no="style='display:none'";
                            }
                            ?>
                               <div id="ori_show_div_no" <?Php echo $ori_show_div_no; ?>>
                               <hr/>
                                    <br/>Please Provide Reason<br/><textarea cols="100" rows="8" id="orishownote" name="orishownote"><?php echo nl2br(stripslashes($userm["ori_show_info"])); ?></textarea>
                               </div>
                              <div id='ori_show_div' <?php echo $ori_show_s; ?>>
                               <hr/><br/>
                                    Attended Orientation Date: <b><?php echo fixdate_comps("all",$userm["orientation_show"]); ?></b>&nbsp;
                                    <?php
                                    $checkoshowdate="no";
                                    if(!empty($userm["orientation_show"]))
                                    {
                                        $showoshowdate="style='display:none'";
                                        echo "<input type='checkbox' id='checkoshowdate_check' name='checkoshowdate_check' onclick='changeoshowdatef()'>&nbsp;change?&nbsp;&nbsp;";
                                    }
                                    else
									{
                                        $showoshowdate="";
										$checkoshowdate='yes';
									}
                                    ?>
                                    <br/>
                                    <span id="oshowdate_div" name="oshowdate_div" <?Php echo $showoshowdate; ?>>
                                        <div style="width:450px; text-align:center; margin-left:auto; margin-right:auto">
                                        <input name="oshowdate" id="oshowdate" class="date-pick" readonly="readonly" />
                                        &nbsp;&nbsp;Time:&nbsp;
                                        <select id="oshowhour" name="oshowhour">
                                            <option value='na'>Hour</option>
                                            <?php
                                                for($i=1;$i<13;$i++)
                                                {
                                                    if($i<10)
                                                        $timer = "0".$i;
                                                    else
                                                        $timer = $i;
                                                    echo "<option value='$i'>$timer</option>";
                                                }
                                            ?>
                                        </select>&nbsp;:&nbsp;
                                        <select id="oshowminute" name="oshowminute">
                                            <option value='na'>Minutes</option>
                                            <?php
                                                echo "<option value='00'>00</option><option value='15'>15</option><option value='30'>30</option><option value='45'>45</option>";
                                            ?>
                                        </select>
                                        <select id="oshowampm" name="oshowampm">
                                            <option value="am">AM</option>
                                            <option vlaue="pm">PM</option>
                                        </select>
                                        </div>
                                    </span>
                               </div>
                            <div id="ori_show_m_opt">
                             <?Php
                            if(!empty($userm["orientation_show"]))
                            {
                                ?>
                                    <hr/><br/>
                                    <?php
									if(empty($userm["ori_comp"]))
										echo "<span class='red'>**** Please Answer The Following Question ****</span><br/><Br/>";
									?>
                                    Did <u><?php echo stripslashes($userm["cname"]); ?></u> Completed Orientation?<br/>
                                    <select id="ori_comp" name="ori_comp" onchange="changeOriComp(this.value)">
                                        <option value="na">Select Answer</option>
                                        <option value="yes" <?Php if($userm["ori_comp"]=="yes") echo "selected='selected'"; ?>>Yes</option>
                                        <option value="no" <?Php if($userm["ori_comp"]=="no") echo "selected='selected'"; ?>>No</option>
                                     </select>
                                     <br/>
                                     <?php
									 $checkocompdate="no";
                                     if(empty($userm["ori_comp"]))
									 {
                                        $ori_show_opt_div="style='display:none'";
										$checkocompdate="yes";
										$ori_comp_div_no="style='display:none'";
									 }
                                   	 else if($userm["ori_comp"]=='no')
									 {
										$ori_show_opt_div="style='display:none'";
										$checkocompdate="yes";
										$ori_comp_div_no="";
									 }
									 else
									 {
										 $ori_show_opt_div="";
										 $ori_comp_div_no="style='display:none'";
									 }
                                    ?>
                                     <div id="ori_comp_div_no" <?Php echo $ori_comp_div_no; ?>>
                                      <hr/>
                                            <br/>Please Provide Reason<br/><textarea cols="100" rows="8" id="oricompnote" name="oricompnote"><?php echo nl2br(stripslashes($userm["ori_comp_info"])); ?></textarea>
                                     </div>
                                     <div id="ori_show_opt" <?php echo $ori_show_opt_div; ?>>
                                        <?php
                                        if($userm["ori_comp"]=="no" || empty($userm["ori_comp"]))
                                            $ori_comp_s="style='display:none'";
                                        else
                                            $ori_comp_s="";
                                        ?>
                                        <div id='ori_comp_div' <?Php echo $ori_comp_s; ?>>
                                            <hr/>
                                            <br/>
                                            <?php
                                             fixdate_comps("d",$userm["orientation_comp"]);
                                            if(!empty($userm["orientation_comp"]))
                                            {
                                                $ocomp_div="style='display:none'";
                                                echo "Completed: <b>".fixdate_comps("d",$userm["orientation_comp"])."</b>&nbsp;&nbsp;Change?&nbsp;<input type='checkbox' id='checkocompdate_check' name='checkocompdate_check' onclick='changeocompdatef()'><br/>";
                                            }
                                            else
                                                $ocomp_div="";
                                            ?><br/>
                                            <span id="ocomp_div" name="ocomp_div" <?Php echo $ocomp_div; ?>>
        Provide Date For Orientation Completed<br/>
                                           <div style="width:200px; text-align:center; margin-left:auto; margin-right:auto;">
                                            <input name="ocompdate" id="ocompdate" class="date-pick" readonly="readonly" />
                                            </div>
                                               </span>
                                             <br/>
                                        </div>
                                       <?Php
									   $checkacode='no';
									   $ccode_no_s="style='display:none'";
									   $checkacode_info="no";
									   $ccode_s="style='display:none'";
                                       if($userm["ori_comp"]=="yes")
                                       {
										   $checkacode='yes';
                                           ?>
                                             <hr/>
                                            Do You Have An Agent Code<br/> For <?php echo stripslashes($userm["cname"]); ?> Ready?<br/>
                                            <select id="acode_show" name="acode_show" onchange="changeAgentCode(this.value)">
                                                <option value="na" <?Php if(empty($userm["ccode_aval"])) echo "selected='selected'" ?>>Select Answer</option>
                                                <option value="yes" <?Php if($userm["ccode_aval"]=='yes') echo "selected='selected'" ?>>Yes</option>
                                                <option value="no" <?Php if($userm["ccode_aval"]=='no') echo "selected='selected'" ?>>No</option>
                                             </select>
                                             <br/>
                                            <?php
										   if(!empty($userm["ccode"]))
                                                $ccode_s="";
											if($userm["ccode_aval"]=="no")
											{
												$ccode_no_s="";
												$checkacode_info="yes";
											}
                                           ?>
                                            <div id="ccode_div" <?php echo $ccode_s; ?>>
                                                <hr/>
                                                <br/>
                                                <div style="text-align:center; padding-left:270px">
                                                	<div style="float:left; width:300px;">
                                                    	Assigned Code:&nbsp;<input type="text" id="ccode" name="ccode" value="<?php echo $userm["ccode"]; ?>" onkeyup="checkAgentCode()"/>
                                                    </div>
                                                    <div style='font-size:12pt; font-style:italic; float:left; display:none' id="ccode_result" name="ccode_result">
                                                    	<img src="images/floader.gif" width="25px" height="25px" border="0" />
                                                        <input type="hidden" id="ccode_dup" name="ccode_dup" value="no" />
                                                    </div>
                                                    <div style="clear:both;"></div>
                                                </div>
                                               <span style="font-style:italic; font-size:14pt; color:#F00"> This code is used when id template is printed</span>
                                                <hr/>
                                                <br/>
                                                Who <u><?php echo stripslashes($userm["cname"]); ?></u> Reports To?<br/>
                                                <select id="report_to" name="report_to">
                                                	<option value='na'>Select Manager/Team Leader</option>
                                                	<?php
													$qx="select * from task_users where type in('6','5','7','8') and status='1' order by name";
													if($rx=mysql_query($qx))
													{
														if(($numrowx=mysql_num_rows($rx))>0)
														{
															while($rxx=mysql_fetch_array($rx))
															{
																$report_selected=setSelected($userm["report_to"],$rxx["id"]);
																echo "<option value='".base64_encode($rxx["id"])."' $report_selected>".getName($rxx["id"])."</option>";
															}
														}
													}
													?>
                                                </select>
                                                <br/>
                                                <hr/>
                                                <br/>
                                                Who Trained <u><?php echo stripslashes($userm["cname"]); ?></u>?<br/>
                                                <select id="trained_by" name="trained_by">
                                                	<option value='na'>Select Manager/Team Leader</option>
                                                	<?php
													$qx="select * from task_users where type in('6','5','7','8') and status='1' order by name";
													if($rx=mysql_query($qx))
													{
														if(($numrowx=mysql_num_rows($rx))>0)
														{
															while($rxx=mysql_fetch_array($rx))
															{
																$report_selected=setSelected($userm["trained"],$rxx["id"]);
																echo "<option value='".base64_encode($rxx["id"])."' $report_selected>".getName($rxx["id"])."</option>";
															}
														}
													}
													?>
                                                </select>
                                           </div>
                                           <div id="ccode_no_div" name='ccode_no_div'  <?php echo $ccode_no_s; ?>><br/>
                                           		Reason of No Agent Code Avaliable<br/>
                                                <textarea cols="100" rows="8" id="ccode_info" name="ccode_info"><?php echo nl2br(stripslashes($userm["ccode_info"])); ?></textarea>
                                           </div>
                                           <?Php
										   $checkimg = "no";
										   $acode_ready='no';
										   if($userm["status"]=='5')
										   {
											   $styleimg="";
											   $acode_ready='yes';
										   }
										   else
											   $styleimg = "style='display:none'";
										   ?>
                                            <span id="checkimg_div" name="checkimg_div" <?php echo $styleimg; ?>>
                                            <?php
                                            if(!empty($userm["img"]))
                                            {
                                                echo "Image in Used <b>".$userm["img"]."</b> &nbsp;&nbsp;Change Image? <input type='checkbox' id='checkimg_check' name='checkimg_check' onclick='changeimgf()' /><br/>";
                                            }
                                            ?>
                                            <br/>
                                            <hr/>
                                            <br/>
                                                <span class='optional_style'>(Optional)</span> Upload Agent Picture: &nbsp; <input type='file' id='imgprof' name='imgprof' /><br/>
                                                <span style="font-style:italic; font-size:14pt; color:#F00">Image must be jpeg or jpg format and size 200(width) x 180(height)</span>
                                            </span>
                                       <?Php
                                       }
                                       ?>
                                    </div>
                                <br/>
                             <?Php
                            }
                           ?>
                            </div>
                        <?php
                        }
                        ?>
                        </div>
                    </fieldset>
                    <br/><br/>
                    <fieldset>
                        <legend>Notification for Orientation Setup</legend>
                        <div style="text-align:center">
                        <span class='optional_style'>Send Email Notification if Phone Number is Provided</span><br/>
                        Send Email Notification? 
                        <?php
                        if($userm["eornotx"]=="yes")
                            $eornotx_yes="selected='selected'";
                        else
                            $eornotx_no="selected='selected'";
                        ?>
                        <select id="eornotx" name="eornotx">
                        <option value="yes"  <?php echo $eornotx_yes; ?>>Yes</option>
                        <option value="no"  <?php echo $eornotx_no; ?>>No</option>
                        </select>
                        <hr/>
                        <span class='optional_style'>Send Text Message Notification if Phone Number is Provided</span><br/>
                        Send Text Message Notification?
                        <?php
                        if($userm["textornotx"]=="yes")
                            $textornotx_yes="selected='selected'";
                        else
                            $textornotx_no="selected='selected'";
                        ?>
                        <select id="textornotx" name="textornotx">
                        <option value="yes"  <?php echo $textornotx_yes; ?>>Yes</option>
                        <option value="no"  <?php echo $textornotx_no; ?>>No</option>
                    </select>&nbsp;&nbsp;
                        </div>
                    </fieldset>
                    </div>
                    <input type="hidden" id="checkimg" name="checkimg" value="<?php echo $checkimg; ?>" />
                    <input type="hidden" id="checkacode" name="checkacode" value="<?php echo $checkacode; ?>" />
                    <input type="hidden" id="acode_ready" name="acode_ready" value="<?php echo $acode_ready; ?>" />
                    <input type="hidden" id="checkacode_info" name="checkacode_info" value="<?php echo $checkacode_info; ?>" />
                    <input type="hidden" id="imgu" name="imgu" value="<?php echo $userm["img"]; ?>" />
                    <input type="hidden" id="checkocompdate" name="checkocompdate" value="<?php echo $checkocompdate; ?>"  />
                    <input type="hidden" id="checkoshowdate" name="checkoshowdate" value="<?php echo $checkoshowdate; ?>"  />
                    <input type="hidden" id="checkodate" name="checkodate" value="<?php echo $checkodate; ?>"  />
                    <input type="hidden" id="checkoprocess" name="checkoprocess" value="<?php echo $checkoprocess; ?>"  />
                </div>
                <?php
				}
				?>
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
    	      <td height="47" colspan="2" align="left" valign="middle">
              <div id="message2" name="message2" class="white" style="text-align:center; padding-right:50px; padding-left:50px; color:#F00">&nbsp;</div>
              </td>
   	        </tr>
    	    <tr>
    	      <td colspan="2" align="center" valign="middle">
      <a href="view.php" onmouseover="document.view.src='images/cancelbtn_r.jpg'" onmouseout="document.view.src='images/cancelbtn.jpg'"><img src="images/cancelbtn.jpg"  border="0" alt="View All Users" name="view" /></a>
      		<?php
			//if(showsetbutton($userm["id"]))
			//{
				?>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              <input type="image"  src="images/savebtn.jpg" onmouseover="javascript:this.src='images/savebtn_r.jpg'" onmouseout="javascript:this.src='images/savebtn.jpg'">
			 <?php
			//}
			 ?>
              </td>
  	      </tr>
    	    <tr>
    	      <td colspan="2" align="left" valign="middle">&nbsp;</td>
  	      </tr>
                </table>
            </form>
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