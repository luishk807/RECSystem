    	<table width="100%" border="0" cellspacing="0" cellpadding="0">
        <?php
		//start
		$today=date("Y-m-d");
		$oid=$_REQUEST["u"];
		$fdate=$today."T00:00:00";
		$tdate=$today."T23:00:00";
		$uniqp=array();
		$echeck=array();
		$query="select * from rec_office where id='".$oid."'";
		if($result = mysql_query($query))
		{
			if(($numrows = mysql_num_rows($result))>0)
			{
				$info=mysql_fetch_assoc($result);
				$officename="For ".stripslashes($info["name"]);
				$echeck[]=array("id"=>$info["id"],"email"=>stripslashes($info["onsip"]));
			}
		}
		$userx = getOnSip($echeck[0]["email"]);
		$str = getJunction("cdr","UserId=".$userx["userid"]."&StartDateTime=".$fdate."&EndDateTime=".$tdate."&Limit=1000","");
		$xpoint = $str->Result[0]->CdrBrowse;
		$num_rows = @$str->Result[0]->CdrBrowse->Cdrs[0]->attributes()->Found;
		if($num_rows >0)
		{
			$count=0;
			while($count < $num_rows)
			{
				$xcount=$count;
				$xpfrom= $xpoint->Cdrs[0]->Cdr[$count]->Source;
				$xpto= $xpoint->Cdrs[0]->Cdr[$count]->Destination;
				$xdisp = $xpoint->Cdrs[0]->Cdr[$count]->Disposition;
				$xlengh = $xpoint->Cdrs[0]->Cdr[$count]->Length;
				$xcallerid = $xpoint->Cdrs[0]->Cdr[$count]->CallerId;
				$xdate = fixdate_comps("onsip_mildate",$xpoint->Cdrs[0]->Cdr[$count]->DateTime);
				if(sizeof($uniqp)>0)
				{
					$found=false;
					for($i=0;$i<sizeof($uniqp);$i++)
					{
						if(trim($uniqp[$i]["xpfrom"])==trim($xpfrom) && trim($uniqp[$i]["xpto"])==trim($xpto))
						{
							$found=true;
							break;
						}
					}
					if(!$found)
						$uniqp[]=array('date'=>$xdate,'xpfrom'=>$xpfrom,'xpto'=>$xpto,'disposition'=>$xdisp,'callerid'=>$xcallerid);
				}
				else
					$uniqp[]=array('date'=>$xdate,'xpfrom'=>$xpfrom,'xpto'=>$xpto,'disposition'=>$xdisp,'callerid'=>$xcallerid);
				$count++;
			}
		}
		//end
		if(sizeof($uniqp)>0)
		{
			$countx=1;
			$totalx=0;
			for($i=0;$i<sizeof($uniqp);$i++)
			{
				$totalx = $countx%2;
				if($totalx==0)
					 $rowstyle="style='font-size:15pt'";
				else
					$rowstyle="style='background-color:#e1fb51; font-size:15pt'";
				echo "<tr $rowstyle>";
				echo "<td width='6%' align='center' valign='middle'>$countx</td>";
				if(!empty($uniqp[$i]["xpfrom"]) && $uniqp[$i]["xpfrom"] !='Restricted')
					$xphone=fixOnSipPhone('',$uniqp[$i]["xpfrom"]);
				else
					$xphone="";
				echo "<td width='6%' align='center' valign='middle'><input type='radio' id='fnum' name='fnum' value='".$xphone."' onclick='setphone(\"".$xphone."\")'/></td>";
				echo "<td width='30%' align='center' valign='middle'>".checkNA($uniqp[$i]["callerid"])."</td>";
				$xdate = fixdate_comps("onsip",$uniqp[$i]["date"]);
				echo "<td width='12%' align='center' valign='middle'>".checkNA($uniqp[$i]["xpfrom"])."</td>";
				echo "<td width='17%' align='center' valign='middle'>$xdate</td>";
				echo "</tr>";
				$countx++;
			}
		}
		else
			echo "<tr class='rowstyleno'><td colspan='6' align='center' valign='middle'>Not Found</td></tr>";
		?>
    </table>