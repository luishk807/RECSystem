<?php
session_start();
include "include/config.php";
include "include/function.php";
$id=$_REQUEST["id"];
$r=base64_decode($_REQUEST["r"]);
if(!empty($id))
{
	$value=array();
	if(!empty($r))
	{
		$query = "select * from rec_entries where id='".$r."'";
		if($result = mysql_query($query))
		{
			if(($num_rows = mysql_num_rows($result))>0)
			{
				$userm = mysql_fetch_assoc($result);
				if(!empty($userm["csource_title"]))
				{
					$xvalue = explode(" || ",$userm["csource_title"]);
					if(sizeof($xvalue)>1)
					{
						for($x=0;$x<sizeof($xvalue);$x++)
						{
							$value[]=trim(stripslashes($xvalue[$x]));
						}
					}
					else
						$value[]=trim(stripslashes($userm["csource_title"]));
				}
			}
		}
	}
	$query = "select * from rec_source where id='".$id."'";
	if($result = mysql_query($query))
	{
		if(($num_rows = mysql_num_rows($result))>0)
		{
			$rows = mysql_fetch_assoc($result);
			if(!empty($rows["entry"]))
			{
				echo "<table width='100%' border='0' cellspacing='0' cellpadding='0'>";
				$fields = explode("||",$rows["entry"]);
				if(sizeof($fields)>0)
				{
					for($i=0; $i<sizeof($fields);$i++)
					{
						echo "<tr><td width='27%' height='37' align='right' valign='middle'><span class='red'>*</span> ".$fields[$i].":</td><td width='73%' align='left' valign='middle'>&nbsp;&nbsp;<input type='text' id='csource_title".$i."' name='csource_title".$i."' size='60'  value='";
						if($userm["csource"]==$rows["id"])
							echo $value[$i];
						echo "'/></td></tr>";
					}
				}
				else
				{
					echo "<tr><td width='27%' height='37' align='right' valign='middle'><span class='red'>*</span> ".$rows["entry"].":</td><td width='73%' align='left' valign='middle'>&nbsp;&nbsp;<input type='text' id='csource_title0' name='csource_title0' size='60' value='";
					if($userm["csource"]==$rows["id"])
							echo $value[0];
					echo "' /></td></tr>";	
				}
				echo "</table>";
			}
		}
	}
}
include "include/unconfig.php";
?>