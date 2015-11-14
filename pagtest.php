<?php
session_start();
include "include/config.php";
include "include/function.php";
adminlogin();
$popview="close";
$user=$_SESSION["rec_user"];
$sname = $_REQUEST["sname"];
$v= $_REQUEST["v"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="icon" type="image/png" href="images/favicon.ico">
<link rel="stylesheet" type="text/css" href="css/style.css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" language="javascript" src="js/script.js"></script>
<script type="text/javascript" language="javascript">
function showpop_view()
{
	var v=document.getElementById("v").value;
	showdivpop_view(v);
}
var intervalID_view = window.setInterval(showpop_view, 10000);
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
              	Edit/View All Entries Avaliable<br/>
                <div id="message2" name="message2" class="white" style="text-align:center; padding-right:50px; padding-left:50px">&nbsp;</div>
            	<form method="post" action="view.php" onsubmit="return checkFieldh()">
                <input type="hidden" id="v" name="v" value="<?php echo $_REQUEST["v"]; ?>" />
                Search For Entries Name: <input type="text" size="60" id="sname" name="sname" />&nbsp;&nbsp;<input type="submit" value="Submit" />&nbsp;Or&nbsp;
                <select id="showview" name="showview" onchange="changeview(this.value)">
                	<option value="na">Select A View</option>
                    <option value="all">View All</option>
                    <option value="cname">Order By Name</option>
                    <option value="cdate">Order By date</option>
                    <option value="status">Order By Status</option>
                    <option value="followup">Follow Up</option>
                    <option value="gperf">General Perfomance</option>
                </select>
                <input type="hidden" id="snameen" name="snamen" value="<?php echo base64_encode($_REQUEST["sname"]);?>" />
                </form>
              </div>
                <br/>
                <div id="viewholder" name="viewholder">
                <?php
				if(!empty($v))
				{
					if($v=="all")
						$v="cdate";
					$sn = base64_decode($_REQUEST["sn"]);
					if($v=="cname")
						$orderby = $v;
					else
						$orderby = $v." desc";
					if(!empty($sn))
						$query = "select * from rec_entries where cname like '%".clean($sn)."%' order by $orderby";
					else
						$query = "select * from rec_entries order by $orderby";
					if(!empty($query))
					{
						if($result = mysql_query($query))
						{
							if(($num_rows = mysql_num_rows($result)) < 12)
							{
								$height="style='height:500px'";
							}
							else
								$height='';
						}
						else
							$height='';	
					}
				}
				else
				{
					if(!empty($sname))
					{
						$query = "select * from rec_entries where cname like '%".clean($sname)."%' order by cdate desc";
						if($result = mysql_query($query))
						{
							if(($num_rows = mysql_num_rows($result)) < 12)
							{
								$height="style='height:500px'";
							}
							else
								$height='';
						}
						else
							$height='';	
					}
					else
					{
						$query = "select * from rec_entries order by cdate desc";
						if($result = mysql_query($query))
						{
							if(($num_rows = mysql_num_rows($result)) < 12)
							{
								$height="style='height:500px'";
							}
							else
								$height='';
						}
						else
							$height='';
					}
				}
				$totalrows = $num_rows;
				$rowperpage = 70;
				//find the total pages
				$totalpages = ceil($totalrows / $rowperpage);
				$cupagex = $_REQUEST["cupage"];
				if(!empty($cupagex))
					$cupage = (int) $cupagex;
				else
					$cupage =1;
				//if current page is greater than total pages
				if($cupage >$totalpages)
					$cupage = $totalpages;
				else if($cupage <1)
					$cupage = 1;
				$offset = ($cupage -1) * $rowperpage;
				$sql = "SELECT * FROM rec_entries LIMIT $offset, $rowsperpage";
				?>
                <div <?php echo $height; ?>>
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr style="background-color:#28629e; color:#FFF">
                    <td width="10%" align="center" valign="middle"></td>
                    <td width="31%" align="center" valign="middle">Name</td>
                    <td width="21%" align="center" valign="middle">Phone</td>
                    <td width="15%" align="center" valign="middle">Date Entered</td>
                    <td width="23%" align="center" valign="middle">Status</td>
                  </tr>
                  <?php
				  $queryx = $sql;
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
							/*if($rowsx["status"]=="1")
							{
								if($rowsx["hired"]=="no")
									$status="Not Hired";
							}
							else*/
							if($rowsx["folcome"]=="no")
								$status="Cancelled";
							else
								$status = getRecStatus($rowsx["status"]);
						 	 echo "<tr $rowstyle><td align='center' valign='middle'>$countx</td><td align='center' valign='middle'><a class='adminlink' href='setrec.php?id=".base64_encode($rowsx["id"])."'>".stripslashes($rowsx["cname"])."</a>".$imgnew." </td><td align='center' valign='middle'>".$rowsx["cphone"]."</td><td align='center' valign='middle'>".$rowsx["cdate"]."</td><td align='center' valign='middle'>".$status."</td></tr>";
							 $countx++;
						  }
					  }
					  else
					  	echo "<tr class='rowstyleno'><td colspan='5' align='center' valign='middle'>No Entry Found</td></tr>";
				  }
				  else
				  	echo "<tr class='rowstyleno'><td colspan='5' align='center' valign='middle'>No Entry Found</td></tr>";
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