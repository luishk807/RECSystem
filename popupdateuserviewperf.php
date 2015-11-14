<?php
session_start();
include "include/config.php";
include "include/function.php";
$user= $_SESSION["rec_user"];
$taskview = $_REQUEST["taskview"];
?>
<?php
	  $query = "select * from task_users order by date desc";
		if($result = mysql_query($query))
		{
			if(($num_rowst = mysql_num_rows($result))>15)
				$height ="style='font-size:15pt;'";
			else
				$height="style='height:500px;font-size:15pt;'";
		}
		else
			$height="style='height:500px;font-size:15pt;'";
	  ?>
      <div <?Php echo $height; ?>>
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr style="background-color:#014681; color:#FFF">
    <td width="7%">&nbsp;</td>
    <td width="28%" align="center" valign="middle">Username</td>
    <td width="27%" align="center" valign="middle">Name</td>
    <?php
	if($taskview=="sortinter")
	{
		?>
    <td width="10%" align="center" valign="middle">Total</td>
    <td width="12%" align="center" valign="middle">Hired</td>
    <td width="16%" align="center" valign="middle">Not Hired</td>
    <?Php
	}
	else if($taskview=="sortob")
	{
	?>
   	<td width="21%" align="center" valign="middle">Observation</td>
    <td width="17%" align="center" valign="middle">Percentage</td>
    <?Php
	}
	?>
  </tr>
  <?php
  	//$query = "select * from task_users where id != '".$user["id"]."' order by date desc";
	if($result = mysql_query($query))
	{
		if(($num_rows = mysql_num_rows($result))>0)
		{
			$count = 1;
			$total = 0;
			$gtotal=0;
			$gtotalhired=0;
			$gtotalnhired=0;
			while($rows = mysql_fetch_array($result))
			{
				$total = $count %2;
				$totalperf="";
				$totalsub="";
				$totalob="";
				$totalhired="";
				$totalnhired="";
				if($total !=0)
					$style = "style='background-color:#e6f882'";
				else
					$style="";
				if($taskview=="sortinter")
					$queryx = "select * from rec_entries where interviewer='".$rows["id"]."'";
				else if($taskview=="sortob")
					$queryx = "select count(*) as counter from rec_entries where observationer='".$rows["id"]."'";
				if($taskview=="sortob")
				{
					if($resultx = mysql_query($queryx))
					{
						if(($num_rowsu = mysql_num_rows($resultx))>0)
						{
							$crow = mysql_fetch_assoc($resultx);
							$totalsub = $crow["counter"];
							$totalperf = ($totalsub/$num_rowsu) * 100;
							$totalperf = sprintf("%.2f", $totalperf);
						}
						else
						{
							$totalsub = 0;
							$totalperf = 0;
						}
					}
					else
					{
						$totalsub = 0;
						$totalperf = 0;
					}
					echo "<tr class='rowstyle' $style><td height='27' align='center' valign='middle'>$count</td><td height='27' align='center' valign='middle'><a class='adminlink' href='settingm.php?id=".base64_encode($rows["id"])."'>".stripslashes($rows["username"])."</a></td><td height='27' align='center' valign='middle'>".stripslashes($rows["name"])."</td><td height='27' align='center' valign='middle'><a href='viewind.php?id=".base64_encode($rows["id"])."&t=".base64_encode('ob')."'>".$totalsub."</a></td><td height='27' align='center' valign='middle'>".$totalperf."%</td></tr>";
				}
				else
				{
					$totalhired=0;
					$totalnhired=0;
					$totalhiredperf = 0;
					$totalnhiredperf = 0;
					if($resultx = mysql_query($queryx))
					{
						if(($num_rowsu = mysql_num_rows($resultx))>0)
						{
							$gtotal =$gtotal+$num_rowsu;
							while($crow = mysql_fetch_assoc($resultx))
							{
								$totalsub = $num_rowsu;
								if($crow["hired"]=="yes")
								{
									$totalhired +=1;
									$gtotalhired +=1;
								}
								else
								{
									$totalnhired +=1;
									$gtotalnhired +=1;
								}
							}
							$totalhiredperf = ($totalhired/$num_rowsu) * 100;
							$totalhiredperf = sprintf("%.2f", $totalhiredperf);
							$totalnhiredperf = ($totalnhired/$num_rowsu) * 100;
							$totalnhiredperf = sprintf("%.2f", $totalnhiredperf);
						}
						else
						{
							$totalob = 0;
							$totalori = 0;
						}
					}
					else
					{
						$totalob= 0;
						$totalori = 0;
					}
					echo "<tr class='rowstyle' $style><td height='27' align='center' valign='middle'>$count</td><td height='27' align='center' valign='middle'><a class='adminlink' href='settingm.php?id=".base64_encode($rows["id"])."'>".stripslashes($rows["username"])."</a></td><td height='27' align='center' valign='middle'>".stripslashes($rows["name"])."</td><td height='27' align='center' valign='middle'><a href='viewind.php?id=".base64_encode($rows["id"])."&t=".base64_encode('int')."'>".$num_rowsu."</a></td><td height='27' align='center' valign='middle'>".$totalhired ." [".$totalhiredperf."%]</td><td height='27' align='center' valign='middle'>".$totalnhired ." [".$totalnhiredperf."%]</td></tr>";	
				}
				$count++;
			}
			if($taskview !="sortob")
			{
				$gtotalhiredperf=0;
				$gtotalnhiredperf=0;
				$gtotalhiredperf = ($gtotalhired/$gtotal) * 100;
				$gtotalhiredperf = sprintf("%.2f", $gtotalhiredperf);
				$gtotalnhiredperf = ($gtotalnhired/$gtotal) * 100;
				$gtotalnhiredperf = sprintf("%.2f", $gtotalnhiredperf);
				echo "<tr style='background-color:#014681; color:#FFF'><td colspan='6' align='center' valign='middle'>&nbsp;</td></tr>";
				echo "<tr><td height='27' colspan='3' align='right' valign='middle'>Grand Total&nbsp;&nbsp;</td><td height='27' align='center' valign='middle'><a href='viewind.php?t=".base64_encode('int')."'>$gtotal</a></td><td height='27' align='center' valign='middle'>$gtotalhired [".$gtotalhiredperf."%]</td> <td height='27' align='center' valign='middle'>$gtotalnhired [".$gtotalnhiredperf."%]</td></tr>";
			}
		}
		else
		{
			if($taskview=="sortinter")
				echo "<tr class='rowstyleno'><td colspan='6' align='center' valign='middle'>No User Created</td></tr>";
			else
				echo "<tr class='rowstyleno'><td colspan='5' align='center' valign='middle'>No User Created</td></tr>";
		}
	}
	else
	{
		if($taskview=="sortinter")
			echo "<tr class='rowstyleno'><td colspan='6' align='center' valign='middle'>No User Created</td></tr>";
		else
			echo "<tr class='rowstyleno'><td colspan='5' align='center' valign='middle'>No User Created</td></tr>";
	}
  ?>
        </table>
        </div>
<?php
include "include/config.php";
?>