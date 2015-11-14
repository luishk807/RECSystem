<script type="text/javascript" language="javascript">
function showpop()
{
	showdivpop();
}
<?php
if($popview !="close")
{
	?>
var intervalID = window.setInterval(showpop, 10000);
<?php
}
?>
</script> 
    <div id="body_header">
    <?php
		echo "<span id='popdiv'>";
		if(isset($_SESSION["rec_user"]))//show pop
		{
			$check1 = false;
			$taskview = $_SESSION["rec_user"];
			if(pView($taskview["type"]))
				$uview = " or u_view='no'";
			else
				$uview="";
			$queryview= "select * from rec_entries where (interviewer='".$taskview["id"]."' and inter_view='no') or (observationer='".$taskview["id"]."' and ob_view='no') $uview";
			if($resultview = mysql_query($queryview))
			{
				if(($num_rows = mysql_num_rows($resultview))>0)
				{
					$check1=true;
				}
			}
			if($check1)
			{
					if($popview !="close")
						echo "<div id='newicon' style='position:absolute; top:50px;left:620px'><img src='images/new.png' border='0' alt='New Message' /></div>";
			}
		}
		echo "</span>";
			?>
    	<div id="body_header_menu">
        	 <a href="home.php" onmouseover="document.home.src='images/rec_b1_r.jpg'" onmouseout="document.home.src='images/rec_b1.jpg'"><img src="images/rec_b1.jpg"  border="0" alt="Back To Home" name="home" /></a><a href="import.php" onmouseover="document.mimport.src='images/rec_b2_r.jpg'" onmouseout="document.mimport.src='images/rec_b2.jpg'"><img src="images/rec_b2.jpg"  border="0" alt="Import Page" name="mimport" /></a><?php
			 if($showfamily)
			 {
				 ?><a href="viewusers.php" onmouseover="document.viewusers.src='images/rec_b3_r.jpg'" onmouseout="document.viewusers.src='images/rec_b3.jpg'"><img src="images/rec_b3.jpg"  border="0" alt="View Users Page" name="viewusers" /></a><?php
			 }
			 else
			 {
				 ?><img src="images/rec_b3_brown.jpg"  border="0" alt="" name="viewusers" /><?php
			 }
			 ?><a href="view.php" onmouseover="document.viewentries.src='images/rec_b4_r.jpg'" onmouseout="document.viewentries.src='images/rec_b4.jpg'"><img src="images/rec_b4.jpg"  border="0" alt="View Entries Page" name="viewentries" /></a><?php
             if($showfamily)
			 {
				 ?><a href="setting.php" onmouseover="document.msetting.src='images/rec_b5_r.jpg'" onmouseout="document.msetting.src='images/rec_b5.jpg'"><img src="images/rec_b5.jpg"  border="0" alt="My Settings" name="msetting" /></a><?Php
			 }
			 else
			 {
				 ?><img src="images/rec_b3_brown.jpg"  border="0" alt="" name="viewusers" /><img src="images/rec_b5_brown.jpg"  border="0" alt="" name="msetting" /><?php
			 }
			 ?><a href="logout.php" onmouseover="document.logout.src='images/rec_b6_r.jpg'" onmouseout="document.logout.src='images/rec_b6.jpg'"><img src="images/rec_b6.jpg"  border="0" alt="Logout" name="logout" /></a>
        </div>
    </div>