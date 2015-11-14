<?php
session_start();
include "include/config.php";
include "include/function.php";
$v= $_REQUEST["v"];
$ndays="1";
$user=$_SESSION["rec_user"];
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
						$query = "select * from rec_entries where idate = CURDATE()+ INTERVAL ".$ndays." DAY and folstatus !='3' and cname like '%".clean($sn)."%' order by $orderby";
					else
						$query = "select * from rec_entries where idate = CURDATE()+ INTERVAL ".$ndays." DAY and folstatus !='3' order by $orderby";
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
						$query = "select * from rec_entries where idate = CURDATE()+ INTERVAL ".$ndays." DAY and folstatus !='3' and cname like '%".clean($sname)."%' order by cdate desc";
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
						$query = "select * from rec_entries WHERE idate = CURDATE()+ INTERVAL ".$ndays." DAY and folstatus !='3' order by cdate desc";
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
				?>
                <div <?php echo $height; ?>>
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr style="background-color:#28629e; color:#FFF">
                    <td width="8%" align="center" valign="middle"></td>
                    <td width="31%" align="center" valign="middle">Name</td>
                    <td width="16%" align="center" valign="middle">Phone</td>
                    <td width="14%" align="center" valign="middle">Date Entered</td>
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
						 	 echo "<tr $rowstyle><td align='center' valign='middle'>$countx</td><td align='center' valign='middle'><a class='adminlink' href='setrec.php?id=".base64_encode($rowsx["id"])."'>".stripslashes($rowsx["cname"])."</a>".$imgnew."</td><td align='center' valign='middle'>".$rowsx["cphone"]."</td><td align='center' valign='middle'>".$rowsx["idate"]."</td><td align='center' valign='middle'>".getOfficeName_s($rowsx["office"])."</td><td align='center' valign='middle'><a href='javascript:showmodal(\"".base64_encode($rowsx["id"])."\")'>".$status."</a></td></tr>";
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
<?php
	include "include/config.php";
?>