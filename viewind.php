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

$t= base64_decode($_REQUEST["t"]);

$id= base64_decode($_REQUEST["id"]);

if(!empty($t))

{

	if($t=="int")

	{

		if(!empty($id))

			$query = "select * from rec_entries where interviewer='".$id."' order by idate desc";

		else

			$query = "select * from rec_entries where interviewer != NULL or interviewer !='' order by idate desc";

		$title = "Interview";

	}

	else if($t=="ob")

	{

		$query = "select * from rec_entries where observationer !=NULL or observationer !='' order by observation desc";

		$title = "Observation";

	}

	else

	{

		$_SESSION["recresult"]="ERROR: Invalid Entry";

		header("location:view.php");

		exit;

	}

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

	$_SESSION["recresult"]="ERROR: Invalid Entry";

	header("location:view.php");

	exit;

}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<link rel="icon" type="image/png" href="images/favicon.ico">

<link rel="stylesheet" type="text/css" href="css/style.css" />

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<script type="text/javascript" language="javascript" src="js/script.js"></script>

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

            	All <?php echo $title; ?> Entries

            </div>

        </div>

        <div id="body_middle_middle" <?php echo $height; ?>>

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

              </div>

                <br/>

                <div>

                  <table width="100%" border="0" cellspacing="0" cellpadding="0">

                  <tr style="background-color:#28629e; color:#FFF">

                    <td width="10%" align="center" valign="middle"></td>

                    <td width="31%" align="center" valign="middle">Name</td>

                    <td width="21%" align="center" valign="middle">Phone</td>

                    <td width="15%" align="center" valign="middle">Date Entered</td>

                    <td width="23%" align="center" valign="middle">Status</td>

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

							if($t=="int")

							{

								if($rowsx["status"]=="1")

								{

									if($rowsx["hired"]=="no")

										$status="Not Hired";

								}

								else

									$status = getRecStatus($rowsx["status"]);

							}

							else

							{

								if($rowsx["observation_comp"]=="yes")

									$status="Completed";

								else

									$status ="Not Completed";

							}

						 	 echo "<tr $rowstyle><td align='center' valign='middle'>$countx</td><td align='center' valign='middle'><a class='adminlink' href='setrec.php?id=".base64_encode($rowsx["id"])."'>".stripslashes($rowsx["cname"])."</a></td><td align='center' valign='middle'>".$rowsx["cphone"]."</td><td align='center' valign='middle'>".$rowsx["cdate"]."</td><td align='center' valign='middle'>".$status."</td></tr>";

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