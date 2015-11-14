<?Php
session_start();
include "include/config.php";
include "include/function.php";
ini_set('memory_limit','500M');
//echo $userx["userid"]."<br/>";
//$userx = getOnSip('lxexexaxd@familyenergy.onsip.com');
date_default_timezone_set('America/New_York');
//echo base64_encode("wjhS6ingABhVfC6K")."<Br/><br/>";
//$today=date("Y-m-d");
$today="2012-06-10";
$fdate=$today."T00:00:00";
$tdate=$today."T23:00:00";
//$xdate = getPhoneWeek();
//$fdate=$xdate[0]["date1"];
//$tdate=$xdate[0]["date2"];
//get restricted phones
$restphone=array();
$found=false;
$savephone=array();
/*$query = "select * from rec_office order by id";
if($result = mysql_query($query))
{
	if(($num_rows=mysql_num_rows($result))>0)
	{
		while($rows=mysql_fetch_array($result))
		{
			$found=false;
			$ophone=ocomphone($rows["ophone"]);
			if(sizeof($restphone)>0)
			{
				for($i=0;$i<sizeof($restphone);$i++)
				{
					if($restphone[$i]==$ophone)
					{
						$found=true;
						break;
					}
				}
				if(!$found)
					$restphone[]=$ophone;
			}
			else
				$restphone[]=ocomphone($rows["ophone"]);
		}
	}
}*/
//get rest phone from users
/*$query = "select * from task_users where phone is not null order by id";
if($result = mysql_query($query))
{
	if(($num_rows=mysql_num_rows($result))>0)
	{
		while($rows=mysql_fetch_array($result))
		{
			$found=false;
			$ophone=ocomphone($rows["phone"]);
			if(sizeof($restphone)>0)
			{
				for($i=0;$i<sizeof($restphone);$i++)
				{
					if($restphone[$i]==$ophone)
					{
						$found=true;
						break;
					}
				}
				if(!$found)
					$restphone[]=$ophone;
			}
			else
				$restphone[]=ocomphone($rows["phone"]);
		}
	}
}*/
/*if(sizeof($restphone)>0)
{
	$countq=1;
	for($i=0;$i<sizeof($restphone);$i++)
	{
		echo $countq.".".$restphone[$i]."<br/>";
		$countq++;
	}
}*/
$restphone[]="17189280522";
$restphone[]="17185346792";
$restphone[]="16469375767";
//$restphone[]="13476131428";
$restphone[]="19172976313";
$restphone[]="13472812321";
$restphone[]="17187108434";
$restphone[]="13474761135";
$restphone[]="13476349391";
$restphone[]="13479207842";
$restphone[]="17184145941";
$restphone[]="13476234773";
$restphone[]="13472459695";
$restphone[]="19178040006";
$restphone[]="19177484365";
$restphone[]="19175175366";
$restphone[]="13477441186";
$restphone[]="19175001341";
$restphone[]="19176808498";
$restphone[]="19053667050";
$restphone[]="12129207328";
$restphone[]="13476131328";
$restphone[]="19178817193";
$emailcheck=array();
$echeck[]=array("id"=>"1","email"=>"family.energy.brooklyn@familyenergy.onsip.com");
$echeck[]=array("id"=>"2","email"=>"lxexexaxd@familyenergy.onsip.com");
$echeck[]=array("id"=>"3","email"=>"bronx.office@familyenergy.onsip.com");
//$echeck[]=array("id"=>"3","email"=>"mark.abramova@familyenergy.onsip.com");
//$echeck[]=array("id"=>"3","email"=>"luishk807@familyenergy.onsip.com");
for($y=0;$y<sizeof($echeck);$y++)
{
	$fphone=array();
	//$str=getJunction("susere",$echeck[$y]["email"],"");
	//print_r($str)."<br/><br/>";
	$userx = getOnSip($echeck[$y]["email"]);
	//echo "<br/><br/>".$userx["userid"]."<br/>";
	//$str=getJunction("susere",$echeck[$y]["email"],"");
	$str = getJunction("cdr","UserId=".$userx["userid"]."&StartDateTime=".$fdate."&EndDateTime=".$tdate."&Limit=1000","");
	//$str = getJunction("gaddress",$echeck[$y]["email"],"");
	//echo $userx["username"]." and ".$userx["password"];
	//print_r($str);
	//echo "<br/><br/>";
	$xpoint = $str->Result[0]->CdrBrowse;
	$uniqp=array();
	$num_rows = @$str->Result[0]->CdrBrowse->Cdrs[0]->attributes()->Found;
	//echo "<br/>".$num_rows."<br/>";
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
					if(trim($uniqp[$i]["xpfrom"])==trim($xpfrom))
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
		//sleep(5);
		//save original numbers
		if(sizeof($uniqp)>0)
		{
			for($i=0;$i<sizeof($uniqp);$i++)
			{
				$testphone=trim($uniqp[$i]["xpfrom"]);
				for($x=0;$x<sizeof($restphone);$x++)
				{
					$found=false;
					if(trim($restphone[$x])==$testphone)
					{
						$found=true;
						break;
					}
				}
				$found=false;
				if(!$found)
				{
					//if(!$found)
					$xcheck=ucwords(strtolower($uniqp[$i]["xpfrom"]));
					if($xcheck !="Restricted" && $xcheck !="Anonymous" && $xcheck !='Anonymous')
					{
						$newcaller="";
						$newword="";
						$newcaller = explode('"',$uniqp[$i]["callerid"]);
						if(sizeof($newcaller)>0)
						{
							for($x=1;$x<=count($newcaller)-1;$x+=2){
								$newword= $newcaller[$x]." ";    
							}
							$newword = trim($newword);
						}
						else
							$newword=$uniqp[$i]["callerid"];
						//echo $uniqp[$i]["callerid"]." and ".$newword." date: ".$uniqp[$i]["date"]." Phone:".fixOnSipPhone('',$uniqp[$i]["xpfrom"])."<br/>";
						echo $uniqp[$i]["callerid"]." and ".$newword." date: ".$uniqp[$i]["date"]." Phone:".$uniqp[$i]["xpfrom"]." Disposition:".$uniqp[$i]["disposition"]."<br/>";
						//$query="insert ignore into rec_phones(office,caller,tphone,date)values('".$echeck[$y]["id"]."','".clean($newword)."','".$uniqp[$i]["xpfrom"]."','".$today."')";
						//echo $query."<br/>";
						//@mysql_query($query);
					}
				}
			}
		}
	}
	//sleep(5);
}
include "include/unconfig.php";
?>