<?php
define("MAPS_HOST", "maps.google.com");
define("KEY", "ABQIAAAAUQoOcLjWVW04XTfLi1SbghRHDJMFrGd7U-5vIm6DVyt_Kv6o_BSNRkm6Jc5CUWvgHIeR0Q2uNVQ4Fw");
$showbutton = true;
$host = "http://www.familyenergymarketing.com/rec/";
function getSession()
{
	return "rec_user";
}
function getSystemTitle()
{
	return "Family Energy Master Recruiter System";
}
function getGHost()
{
	return "/rec/";
}
function agentCodeExist($ccode,$id)
{
	$check=false;
	if(!empty($ccode) && !empty($id))
	{
		$query="select * from rec_entries where ccode='".$ccode."' and id !='".$id."'";
		if($result=mysql_query($query))
		{
			if(($numrows=mysql_num_rows($result))>0)
				$check=true;
		}
	}
	return $check;
}
function getManId($name)
{
	$id="";
	if(!empty($name))
	{
		$query="select * from task_users where name='".clean($name)."' and (type='5' or type='6' or type='7' or type='8')";
		if($result=mysql_query($query))
		{
			if(($num_rows=mysql_num_rows($result))>0)
			{
				$info=mysql_fetch_assoc($result);
				$id=$info["id"];
			}
		}
	}
	return $id;
}
function fixToRecPhone($str)
{
	$newphone="";
	if(!empty($str))
	{
		$replace="";
		$find="/[()-]/";
		$matches=preg_replace ($find, $replace, $str);
		$matches=str_replace(" ",'',$matches);
		$matches=trim($matches);
		$num3=substr($matches,-4);
		$num2=substr($matches,-7,3);
		$max=strlen($num3)+strlen($num2);
		$limit=strlen($matches)-$max;
		$num1=substr($matches,0,$limit);
		$newphone=$num1."-".$num2."-".$num3;
	}
	return $newphone;
}
function isEmail($str)
{
	if(eregi('^[a-zA-Z0-9.-_]+@{1}[a-zA-Z0-9.-_]+\.([a-zA-Z]{2,3})$',$str))
		return true;
	else
		return false;
}
function setRetYearVar($yearx)
{
	date_default_timezone_set('America/New_York');
	$newyear=array();
	$yearx_split=explode("-",$yearx);
	if(sizeof($yearx_split)>1)
	{
		if(!empty($yearx_split[0]) && !empty($yearx_split[1]))
		{
			$thisyear=$yearx_split[0];
			$nextyear=$yearx_split[1];
		}
		else
		{
			$thisyear=date("Y");
			$nextyear = date("Y",strtotime("+1 years"));
		}
	}
	else
	{
		$thisyear=date("Y");
		$nextyear = date("Y",strtotime("+1 years"));
	}
	$newyear[]=$thisyear;
	$newyear[]=$nextyear;
	return $newyear;
}
function getSalesYear($task)
{
	$var="";
	$year="";
	if($task=="start")
		$var="asc";
	else if($task=="end")
		$var="desc";
	if(!empty($var))
	{
		$query="select distinct ddate from sales_report_real order by ddate $var limit 1";
		if($result=mysql_query($query))
		{
			if(($num_rows=mysql_num_rows($result))>0)
			{
				$info=mysql_fetch_assoc($result);
				$year=fixdate_comps('y',$info["ddate"]);
			}
		}
	}
	return $year;
}
function getSalesYear_array()
{
	$year_array=array();
	$b_year=getSalesYear("start");
	$l_year=getSalesYear("end");
	if(!empty($b_year) && !empty($l_year))
	{
		$count=$b_year;
		while($count<=$l_year)
		{
			$mark_ends=$count+1;
			$year_array[]=$count."-".$mark_ends;
			$count++;
		}
	}
	return $year_array;
}
function getEntryMatch($name,$acode)
{
	$link="";
	if(!empty($acode) && !empty($name))
	{
		$query="select * from rec_entries where ccode='".trim(ucwords($acode))."' limit 1";
		if($result=mysql_query($query))
		{
			if(($num_rows=mysql_num_rows($result))>0)
			{
				$info=mysql_fetch_assoc($result);
				$link="<a href='setrec.php?id=".base64_encode($info["id"])."' target='_blank'>".$name."</a>";
			}
			else
				$link=$name;
		}
		else
			$link=$name;
	}
	else
		$link=$name;
	return $link;
}
function fixret_modalpop_link($date,$task,$str,$office,$xset)
{
	$link=$str;
	if(!empty($date) && !empty($task) && !empty($str) && $str>0)
		$link="<a class='statslink' href='javascript:showmodal(\"date_detail=".base64_encode($date)."&task=".base64_encode($task)."&office=".base64_encode($office)."&xset=".base64_encode($xset)."\")'>".$str."</a>";
	return $link;
}
function getRetStartHired()
{
	$date="";
	$query="select distinct date from rec_timeline where status='5' order by date asc limit 1";
	if($result=mysql_query($query))
	{
		if(($num_rows=mysql_num_rows($result))>0)
		{
			$info=mysql_fetch_assoc($result);
			$date=$info["date"];
		}
	}
	return $date;
}
function getRetentionActive_array($office,$date)
{
	//echo $date;
	$fday=getFirstDay($date);
	$lday=getLastDay($fday);
	$entryid=array();
	if(!empty($fday) && !empty($lday))
	{
		$query="select distinct x.userid as userid,a.acode as acode,b.cname as name from sales_report_real as x left outer join sales_agent as a on x.userid=a.id left outer join rec_entries as b on b.ccode=a.acode where x.ddate between '".$fday."' and '".$lday."' and b.cname is not null and x.office='".$office."' order by b.id";
		if($result=mysql_query($query))
		{
			if(($num_rows=mysql_num_rows($result))>0)
			{
				while($rows=mysql_fetch_array($result))
					$entryid[]=array('acode'=>$rows["acode"]);
			}
		}
	}
	return $entryid;
}
function getHiredDate($acode,$office)
{
	$id=getRecId_byCode($acode);
	$idx=getAgentId($acode);
	$hired="";
	$sdate="";
	$i=0;
	$query="select date from rec_timeline where entryid='".$id."' and status='5' order by date desc limit 1";
	if($result=mysql_query($query))
	{
		if(($num_rows=mysql_num_rows($result))>0)
		{
			$info=mysql_fetch_assoc($result);
			if(!empty($info["date"]))
				$hired=fixdate_comps('mildate',$info["date"]);
		}
	}
	$query="select distinct ddate from sales_report_real where office !='".$office."' and userid='".$idx."' order by ddate desc limit 1";
	if($result=mysql_query($query))
	{
		if(($num_rows=mysql_num_rows($result))>0)
		{
			$info=mysql_fetch_assoc($result);
			if(!empty($info["ddate"]))
				$sdate=fixdate_comps('mildate',$info["ddate"]);
		}
	}
	if(empty($sdate))
	{
		$query="select distinct ddate from sales_report_real order by ddate limit 1";
		if($result=mysql_query($query))
		{
			if(($num_rows=mysql_num_rows($result))>0)
			{
				$info=mysql_fetch_assoc($result);
				if(!empty($info["ddate"]))
					$sdate=fixdate_comps('mildate',$info["ddate"]);
			}
		}
	}
	//echo "<".$id." hired: ".$hired." sdate: ".$sdate.">";
	if(!empty($hired) && !empty($sdate))
	{
		$d1 = strtotime($hired);
		$d2 = strtotime($sdate);
		$min_date = min($d1, $d2);
		$max_date = max($d1, $d2);
		$i = 0;
		while (($min_date = strtotime("+1 MONTH", $min_date)) <= $max_date) {
			$i++;
		}
		//echo $i;
	}
	//$name=getEntryInfo('name',$id);
	//echo "<".$id." name: ".$name." acode: ".$acode." office: ".$office." hired: ".$hired." sdate: ".$sdate." total:".$i." query:".$query.">";
	//echo "<".$id." name: ".$name." acode: ".$acode." office: ".$office." hired: ".$hired." sdate: ".$sdate." total:".$i.">";
	return $i;
}
function getRetentionActive($office,$date)
{
	//$date=fixdate_comps('mildate',$date);
	//echo "before ".$date." here ";
	$sdate=getRetStartHired();
	$sdate=fixdate_comps('mildate',$sdate);
	$date=fixdate_comps('mildate',$date);
	$lday=getLastDay($date);
	$array=getRetentionActive_array($office,$lday);
	$active=array();
	$week_0=0;
	$week_0_ar=array();
	$week_one=0;
	$week_one_ar=array();
	$week_two=0;
	$week_two_ar=array();
	$week_three=0;
	$week_three_ar=array();
	$month1=0;
	$month1_ar=array();
	$month2=0;
	$month2_ar=array();
	$month3=0;
	$month3_ar=array();
	$month6=0;
	$month6_ar=array();
	$month12=0;
	$month12_ar=array();
	$sales_dates=array();
	$nagen=0;
	if(sizeof($array)>0)
	{
		$qx="select distinct ddate from sales_report_real where ddate between '".$sdate."' and '".$lday."' and office='".$office."' order by ddate asc";
		//echo $qx;
		if($result=mysql_query($qx))
		{
			if(($num_rows=mysql_num_rows($result))>0)
			{
				while($rows=mysql_fetch_array($result))
					$sales_dates[]=$rows["ddate"];	
			}
		}
		if(sizeof($sales_dates)>0)
		{
			for($x=0;$x<sizeof($array);$x++)
			{
				$weeks=0;
				$ftimes=0;
				$month=0;
				$id=getAgentId($array[$x]["acode"]);
				$prev_date="";
				$xadd=false;
				$month=getHiredDate($array[$x]["acode"],$office);
				for($i=0;$i<sizeof($sales_dates);$i++)
				{
					if(empty($prev_date))
						$prev_date=fixdate_comps('m_text',$sales_dates[$i]);
					$cu_date=fixdate_comps('m_text',$sales_dates[$i]);
					$qx="select distinct ddate from sales_report_real where ddate='".$sales_dates[$i]."' and office='".$office."' and userid='".$id."'";
					//$qx="select distinct ddate from sales_report_real where ddate='".$sales_dates[$i]."' and userid='".$id."'";
					//echo $qx."<br/>";
					if($result=mysql_query($qx))
					{
						if(($num_rows=mysql_num_rows($result))>0)
							$weeks++;
					}
					$ftimes++;
					if($ftimes>3)
					{
						if($weeks>3)
						{
							if(!$xadd)
							{
								$month++;
								$weeks=0;
							}
						}
						$ftimes=0;
					}
					else if(!empty($cu_date) && !empty($prev_date))
					{
						if(($cu_date !=$prev_date) && $ftimes>3)
						{
							$month++;
							$weeks=0;
							$ftimes=0;
							$prev_date="";
						}
						else if($cu_date !=$prev_date)
						{
							$prev_date="";
							if($weeks>0)
							{
								$month++;
								$xadd=true;
							}
						}
					}
					else
						$xadd=false;
				}
				if($month>0)
				{
					if($month>12)
					{
						$nagen++;
						$month12++;
						$month12_ar[]=array('id'=>$id,'acode'=>$array[$x]["acode"]);
					}
					else if($month>6)
					{
						$nagen++;
						$month6++;
						$month6_ar[]=array('id'=>$id,'acode'=>$array[$x]["acode"]);
					}
					else if($month>2)
					{
						$nagen++;
						$month3++;
						$month3_ar[]=array('id'=>$id,'acode'=>$array[$x]["acode"]);
					}
					else if($month>1)
					{
						$nagen++;
						$month2++;
						$month2_ar[]=array('id'=>$id,'acode'=>$array[$x]["acode"]);
					}
					else
					{
						if($weeks>0)
						{
							$nagen++;
							$month2++;
							$month2_ar[]=array('id'=>$id,'acode'=>$array[$x]["acode"]);
						}
						else
						{
							$nagen++;
							$month1++;
							$month1_ar[]=array('id'=>$id,'acode'=>$array[$x]["acode"]);
						}
					}
				}
				else if($weeks>3)
				{
					$nagen++;
					$month1++;
					$week_one_ar[]=array('id'=>$id,'acode'=>$array[$x]["acode"]);
				}
				else if($weeks>2)
				{
					$nagen++;
					$week_three++;
					$week_three_ar[]=array('id'=>$id,'acode'=>$array[$x]["acode"]);
				}
				else if($weeks>1)
				{
					$nagen++;
					$week_two++;
					$week_two_ar[]=array('id'=>$id,'acode'=>$array[$x]["acode"]);
				}
				else if($weeks>0)
				{
					$nagen++;
					$week_one++;
					$week_one_ar[]=array('id'=>$id,'acode'=>$array[$x]["acode"]);
				}
				else
				{
					$nagen++;
					$week_0++;
					$week_0_ar[]=array('id'=>$id,'acode'=>$array[$x]["acode"]);
				}
			}
		}
		$active[]=array('n_agent'=>$nagen,'week0'=>$week_0,'week1'=>$week_one,'week2'=>$week_two,'week3'=>$week_three,'month1'=>$month1,'month2'=>$month2,'month3'=>$month3,'month6'=>$month6,'month12'=>$month12,'week0s'=>$week_0_ar,'week1s'=>$week_one_ar,'week2s'=>$week_two_ar,'week3s'=>$week_three_ar,'month1s'=>$month1_ar,'month2s'=>$month2_ar,'month3s'=>$month3_ar,'month6s'=>$month6_ar,'month12s'=>$month12_ar);
	}
	return $active;
}
function getRetMonthSales($date,$office)
{
	$month=fixdate_comps('m_text_l',$date);
	$year=fixdate_comps('y',$date);
	$fdate=$year."-".$month."-01";
	$ldate=$year."-".$month."-31";
	$stotal=0;
	$query="select sum(xgas+xelec) as stotal from sales_report_real where ddate between '".$fdate."' and '".$ldate."' and office='".$office."'";
	if($result=mysql_query($query))
	{
		if(($num_rows=mysql_num_rows($result))>0)
		{
			$info=mysql_fetch_assoc($result);
			$stotal=$info["stotal"];
			if($stotal<1 && empty($stotal))
				$stotal=0;
		}
	}
	return $stotal;
}
function getRetMonthUsers($date,$office)
{
	$month=fixdate_comps('m_text_l',$date);
	$year=fixdate_comps('y',$date);
	$fdate=$year."-".$month."-01";
	$ldate=$year."-".$month."-31";
	$stotal=0;
	$query="select distinct userid from sales_report_real where ddate between '".$fdate."' and '".$ldate."' and office='".$office."'";
	if($result=mysql_query($query))
	{
		$stotal=mysql_num_rows($result);
		if($stotal<1 && empty($stotal))
				$stotal=0;
	}
	return $stotal;
}
function getAgentId($acode)
{
	$id="";
	if(!empty($acode))
	{
		$query="select * from sales_agent where acode='".$acode."' limit 1";
		if($result=mysql_query($query))
		{
			if(($num_rows=mysql_num_rows($result))>0)
			{
				$info=mysql_fetch_assoc($result);
				$id=$info["id"];
			}
		}
	}
	return $id;
}
function getRecId_byCode($acode)
{
	$id="";
	if(!empty($acode))
	{
		$query="select * from rec_entries where ccode='".$acode."' order by id limit 1";
		if($result=mysql_query($query))
		{
			if(($num_rows=mysql_num_rows($result))>0)
			{
				$info=mysql_fetch_assoc($result);
				$id=$info["id"];
			}
		}
	}
	return $id;
}
function getRetSales($array,$date,$amount,$office)
{
	$total=0;
	$per=0;
	$resultx=array();
	if(sizeof($array)>0 && !empty($date))
	{
		$month=fixdate_comps('m_text_l',$date);
		$year=fixdate_comps('y',$date);
		$fdate=$year."-".$month."-01";
		$ldate=$year."-".$month."-31";
		for($x=0;$x<sizeof($array);$x++)
		{
			$qx="select sum(xelec+xgas) as stotal from sales_report_real where userid='".$array[$x]["id"]."' and ddate between '".$fdate."' and '".$ldate."' and office='".$office."'";
			//if($fdate=='2012-06-01' && ($array[$x]["id"]==315 || $array[$x]["id"]=='47'))
			//	echo $qx;
			if($result=mysql_query($qx))
			{
				if(($num_rows=mysql_num_rows($result))>0)
				{
					$info=mysql_fetch_assoc($result);
					$total +=$info["stotal"];
				}
			}
		}
	}
	if($total>0)
	{
		$per=@round(($total/$amount)*100);
	}
	$resultx[]=array('total'=>$total,'per'=>$per);
	return $resultx;
}
function getRetention($array)
{
	$active=array();
	$week_one=0;
	$week_one_ar=array();
	$week_two=0;
	$week_two_ar=array();
	$week_three=0;
	$week_three_ar=array();
	$month1=0;
	$month1_ar=array();
	$month2=0;
	$month2_ar=array();
	$sales_dates=array();
	$qx="select distinct ddate from sales_report_real order by ddate asc";
	if($result=mysql_query($qx))
	{
		if(($num_rows=mysql_num_rows($result))>0)
		{
			while($rows=mysql_fetch_array($result))
				$sales_dates[]=$rows["ddate"];	
		}
	}
	if(sizeof($sales_dates)>0)
	{
		for($x=0;$x<sizeof($array);$x++)
		{
			$weeks=0;
			$ftimes=0;
			$month=0;
			$id=getAgentId($array[$x]["acode"]);
			for($i=0;$i<sizeof($sales_dates);$i++)
			{
				$qx="select * from sales_report_real where ddate='".$sales_dates[$i]."' and userid='".$id."'";
				//echo $qx."<br/>";
				if($result=mysql_query($qx))
				{
					if(($num_rows=mysql_num_rows($result))>0)
						$weeks++;
				}
				if($ftimes>3)
				{
					if($weeks>3)
					{
						$month++;
						$weeks=0;
					}
					$ftimes=0;
				}
				$ftimes++;
			}
			if($month>0)
			{
				if($month>1)
				{
					$month2++;
					$month2_ar[]=array('id'=>$id,'acode'=>$array[$x]["acode"]);
				}
				else
				{
					if($weeks>0)
					{
						$month2++;
						$month2_ar[]=array('id'=>$id,'acode'=>$array[$x]["acode"]);
					}
					else
					{
						$month1++;
						$month1_ar[]=array('id'=>$id,'acode'=>$array[$x]["acode"]);
					}
				}
			}
			else if($weeks>2)
			{
				$week_three++;
				$week_three_ar[]=array('id'=>$id,'acode'=>$array[$x]["acode"]);
			}
			else if($weeks>1)
			{
				$week_two++;
				$week_two_ar[]=array('id'=>$id,'acode'=>$array[$x]["acode"]);
			}
			else if($weeks>0)
			{
				$week_one++;
				$week_one_ar[]=array('id'=>$id,'acode'=>$array[$x]["acode"]);
			}
		}
	}
	$active[]=array('week1'=>$week_one,'week2'=>$week_two,'week3'=>$week_three,'month1'=>$month1,'month2'=>$month2,'week1s'=>$week_one_ar,'week2s'=>$week_two_ar,'week3s'=>$week_three_ar,'month1s'=>$month1_ar,'month2s'=>$month2_ar);
	return $active;
}
function getRetActive($earray)
{
	$active=array();
	$week_one=0;
	$week_one_ar=array();
	$week_two=0;
	$week_two_ar=array();
	$week_three=0;
	$week_three_ar=array();
	$month1=0;
	$month1_ar=array();
	$month2=0;
	$month2_ar=array();
	if(sizeof($earray)>0)
	{
		for($i=0;$i<sizeof($earray);$i++)
		{
			$id=getAgentId($earray[$i]["acode"]);
			if(!empty($id))
			{
				$q="select * from sales_report_real where userid='".$id."'";
				if($rx=mysql_query($q))
				{
					$num_rows=mysql_num_rows($rx);
					if($num_rows >4)
					{
						$month2++;
						$month2_ar[]=array('id'=>$id,'acode'=>$earray[$i]["acode"]);
					}
					else if($num_rows >3)
					{
						$month1++;
						$month1_ar[]=array('id'=>$id,'acode'=>$earray[$i]["acode"]);
					}
					else if($num_rows >2)
					{
						$week_three++;
						$week_three_ar[]=array('id'=>$id,'acode'=>$earray[$i]["acode"]);
					}
					else if($num_rows >1)
					{
						$week_two++;
						$week_two_ar[]=array('id'=>$id,'acode'=>$earray[$i]["acode"]);
					}
					else if($num_rows >0)
					{
						$week_one++;
						$week_one_ar[]=array('id'=>$id,'acode'=>$earray[$i]["acode"]);
					}
				}
			}
		}
	}
	$active[]=array('week1'=>$week_one,'week2'=>$week_two,'week3'=>$week_three,'month1'=>$month1,'month2'=>$month2,'week1s'=>$week_one_ar,'week2s'=>$week_two_ar,'week3s'=>$week_three_ar,'month1s'=>$month1_ar,'month2s'=>$month2_ar);
	return $active;
}
function getRetHired($office,$month,$year)
{
	$e_array=array();
	if(!empty($office) && !empty($month) && !empty($year))
	{
		if($month<10)
			$month="0".$month;
		$xstart=$year."-".$month."-01";
		$xend=$year."-".$month."-31";
		$query="SELECT x.entryid, a.cname AS name, a.ccode as acode, c.name AS office, x.date AS date FROM rec_timeline AS x LEFT OUTER JOIN rec_entries a ON x.entryid = a.id LEFT OUTER JOIN rec_office AS c ON a.orientation_office = c.id WHERE x.status = '5' AND a.orientation_office = '".$office."' AND x.date BETWEEN '".$xstart."' AND '".$xend."' ORDER BY x.entryid";
		if($result=mysql_query($query))
		{
			if(($num_rows=mysql_num_rows($result))>0)
			{
				while($rows=mysql_fetch_array($result))
					$e_array[]=array('id'=>$rows["entryid"],'acode'=>$rows["acode"]);
			}
		}
	}
	return $e_array;
}
function getRecReason($id)
{
	$notemo="";
	$query="select * from rec_entries where id='".$id."'";
	if($result=mysql_query($query))
	{
		if(($num_rows=mysql_num_rows($result))>0)
		{
			$rmox=mysql_fetch_assoc($result);
			switch($rmox["status"])
			{
				case '2':
				{
					if(!empty($rmox["interview_note"]))
						$notemo=stripslashes($rmox["interview_note"]);
					else
						$notemo=stripslashes($rmox["ccode_info"]);
					break;
				}
				case '8':
				{
					$notemo=stripslashes($rmox["int_show_info"]);
					//$notemo="here is this stuff";
					break;
				}
				case '18':
				{
					$notemo=stripslashes($rmox["ori_show_info"]);
					break;
				}
				case '19':
				{
					$notemo=stripslashes($rmox["ori_comp_info"]);
					break;
				}
				case '20':
				{
					$notemo=stripslashes($rmox["int_show_info"]);
					break;
				}
			}
		}
	}
	return $notemo;
}
function get_climit()
{
	if(isset($_SESSION["climit"]))
		$climit=$_SESSION["climit"];
	else
	{
		$climit=700;
		$_SESSION["climit"]=$climit;
	}
	return $climit;
}
function checkNew($user,$id)
{
	$newup="false";
	$query="select * from rec_entries where id='".$id."'";
	if($result=mysql_query($query))
	{
		if(($num_rows=mysql_num_rows($result))>0)
		{
			$rowsx=mysql_fetch_assoc($result);
			if($user["id"]==$rowsx["observationer"])
			{
				if($rowsx["ob_view"]=="no")
				$newup="true";
			}
			if($user["id"]==$rowsx["interviewer"])
			{
				if($rowsx["inter_view"]=="no")
				$newup="true";
			}
			if(pView($user["type"]))
			{
				if($rowsx["u_view"]=="no")
					$newup="true";
			}
		}
	}
	return $newup;
}
function createSalesVar($array)
{
	$newar=array();
	$xgas=0;
	$xelec=0;
	$users="";
	if(sizeof($array)>0 && isset($_SESSION["rec_user"]))
	{
		for($i=0;$i<sizeof($array);$i++)
		{
			$xgas +=$array[$i]["xgas"];
			$xelec +=$array[$i]["xelec"];
			if(!empty($array[$i]["userid"]))
			{
				if($i==0)
					$users="'".$array[$i]["userid"]."'";
				else
					$users .=",'".$array[$i]["userid"]."'";
			}
		}
		$newar[]=array('xgas'=>$xgas,'xelec'=>$xelec,'aid'=>$users);
	}
	return $newar;
}
function checkEntryStatus($id,$task)
{
	$check=false;
	$qx="select * from rec_entries where id='".$id."'";
	if($result=mysql_query($qx))
	{
		if(($num_rows=mysql_num_rows($result))>0)
			$info=mysql_fetch_assoc($result);
	}
	if($task=="inter")
	{
		if($info["status"]=='8' || $info["status"]=='20')
			$check=true;
	}
	else if($task=="orien")
	{
		if($info["status"]=='9' || $info["status"]=='2' || $info["status"]=='18' || $info["status"]=='19')
			$check=true;
	}
	return $check;
}
function saveNShow_track($id,$status,$oid)
{
	$found=false;
	$query="select * from rec_nshow_track where entryid='".$id."'";
	if($result=mysql_query($query))
	{
		if(($num_rows=mysql_num_rows($result))>0)
			$found=true;
	}
	if(!$found)
	{
		$query="insert ignore into rec_nshow_track(entryid,office,cstatus)values('".$id."','".$oid."','".$status."')";
		@mysql_query($query);
	}
}
function getNumInterPerOffice($task,$office,$date1,$date2)
{
	$numint=0;
	if($task=="inter")
		$query="select * from rec_entries where DATE_ADD(idate, INTERVAL itime HOUR_SECOND) >='".$date1."' and office='".$office."' and status='1'";
	else if($task=="orien")
		$query="select * from rec_entries where orientation >='".$date1."' and orientation_office='".$office."' and status='3'";
	else if($task=="orienc")
		$query="select * from rec_entries where orientation_comp between '".$date1."' and '".$date2."' and orientation_office='".$office."'";
	if($result=mysql_query($query))
		$numint=mysql_num_rows($result);
	return $numint;
}
function getPhoneMatch($var)
{
	$id="";
	$phonex=fixOnSipPhone('family',$var);
	if(!empty($phonex))
	{
		$query="select * from rec_entries where cphonex='".trim($phonex)."' order by cdate limit 1";
		if($result=mysql_query($query))
		{
			if(($num_rows=mysql_num_rows($result))>0)
			{
				$info=mysql_fetch_assoc($result);
				$id=$info["id"];
			}
		}
	}
	return $id;
}
function cmp($a, $b) {
    if ($a == $b) {
        return 0;
    }
    return ($a < $b) ? -1 : 1;
}
function cmpx($a, $b) {
    if ($a == $b) {
        return 0;
    }
    return ($a > $b) ? -1 : 1;
}
function sortArray($task,$array,$ascdesc)
{
	if(!empty($task) && sizeof($array)>0)
	{
		$rows=$array;
		$nrows=array();
		$xrow=array();
		for($i=0;$i<sizeof($rows);$i++)
		{
			$nrows[$i]=$rows[$i][$task];
		}
		if($ascdesc=="asc")
			uasort($nrows, 'cmpx');
		else
			uasort($nrows, 'cmp');
		//uasort($nrows, 'cmp');
		foreach($nrows as $key=>$val)
			$xrow[]=$rows[$key];
		return $xrow;
	}
	return $array;
}
function getExcelUser($date1,$date2)
{
	$line_users="";
	$userxx=array();
	$userx=array();
	$phonex=array();
	$query="select distinct tphone from rec_phones where date between '".$date1."' and '".$date2."'";
	if($result=mysql_query($query))
	{
		if(($num_rows=mysql_num_rows($result))>0)
		{
			while($rows=mysql_fetch_array($result))
				$phonex[]=fixOnSipPhone('family',$rows["tphone"]);
		}
	}
	if(sizeof($phonex)>0)
	{
		for($i=0;$i<sizeof($phonex);$i++)
		{
			$query="select * from rec_entries where cphonex='".$phonex[$i]."'";
			if($result=mysql_query($query))
			{
				if(($num_rows=mysql_num_rows($result))>0)
				{
					while($rows=mysql_fetch_array($result))
						$userxx[]=array('id'=>$rows["id"],'acode'=>$rows["ccode"]);
				}
			}
		}
	}
	if(sizeof($userxx)>0)
	{
		for($i=0;$i<sizeof($userxx);$i++)
		{
			$query="select * from rec_timeline where entryid='".$userxx[$i]["id"]."'";
			if($result=mysql_query($query))
			{
				if(($num_rows=mysql_num_rows($result))>0)
				{
					while($rows=mysql_fetch_array($result))
						$userx[]=array('id'=>$rows["entryid"]);
				}
			}
		}
	}
	if(sizeof($userx)>0)
	{
		for($i=0;$i<sizeof($userx);$i++)
		{
			if($i==0)
				$line_users="'".$userx[$i]["id"]."'";
			else
				$line_users .=",'".$userx[$i]["id"]."'";
		}
	}
	return $line_users;
}
function getExcelPhone($date1,$date2)
{
	$line_phone="";
	$query="select distinct tphone from rec_phones where date between '".$date1."' and '".$date2."'";
	if($result=mysql_query($query))
	{
		if(($num_rows=mysql_num_rows($result))>0)
		{
			$count=0;
			while($rows=mysql_fetch_array($result))
			{
				if($count==0)
					$line_phone ="'".fixOnSipPhone('family',$rows["tphone"])."'";
				else
					$line_phone .=",'".fixOnSipPhone('family',$rows["tphone"])."'";
				$count++;
			}
		}
	}
	return $line_phone;
}
function getAllSales($id)
{
	$total=array();
	$xgas=0;
	$xelec=0;
	$query="select sum(xelec) as xelec from sales_report_real where userid='".$id."'";
	if($result=mysql_query($query))
	{
		if(($mnu=mysql_num_rows($result))>0)
		{
			$info=mysql_fetch_assoc($result);
			if($info["xelec"]>0)
				$xelec=$info["xelec"];
		}
	}
	$query="select sum(xgas) as xgas from sales_report_real where userid='".$id."'";
	if($result=mysql_query($query))
	{
		if(($mnu=mysql_num_rows($result))>0)
		{
			$info=mysql_fetch_assoc($result);
			if($info["xgas"]>0)
				$xgas=$info["xgas"];
		}
	}
	$total[]=array('xelec'=>$xelec,'xgas'=>$xgas);
	return $total;
}
function getAgent($id)
{
	$namea="";
	$query = "select * from sales_agent where id='".$id."'";
	if($result = mysql_query($query))
	{
		if(($num_rows = mysql_num_rows($result))>0)
		{
			$rows = mysql_fetch_assoc($result);
			$namea= stripslashes($rows["name"]);
		}
		else
			$namea="N/A";
	}
	else
		$namea = "N/A";
	return $namea;
}
function getAgentCode($id)
{
	$namea="";
	$query = "select * from sales_agent where id='".$id."'";
	if($result = mysql_query($query))
	{
		if(($num_rows = mysql_num_rows($result))>0)
		{
			$rows = mysql_fetch_assoc($result);
			$namea= stripslashes(trim($rows["acode"]));
		}
		else
			$namea="";
	}
	else
		$namea = "";
	return $namea;
}
function getInter($id)
{
	$namea="";
	$found=false;
	$query = "select * from sales_agent where id='".$id."'";
	if($result = mysql_query($query))
	{
		if(($num_rows = mysql_num_rows($result))>0)
		{
			$info= mysql_fetch_assoc($result);
			$found=true;
		}
		else
			$namea="N/A";
	}
	else
		$namea = "N/A";
	if($found)
	{
		$query = "select * from rec_entries where ccode='".trim($info["acode"])."'";
		if($result = mysql_query($query))
		{
			if(($num_rows = mysql_num_rows($result))>0)
			{
				$rows = mysql_fetch_assoc($result);
				$namea=getName($rows["interviewer"]);
				$found=true;
			}
			else
				$found=false;
		}
		else
			$found=false;
	}
	else
		$found=false;
	if(!$found)
	{
		$query = "select * from sales_report where agentid='".trim($info["id"])."' order by fromdate desc limit 1";
		if($result = mysql_query($query))
		{
			if(($num_rows = mysql_num_rows($result))>0)
			{
				$rows = mysql_fetch_assoc($result);
				$namea=getName($rows["userid"]);
			}
			else
				$namea = "N/A";
		}
		else
			$namea = "N/A";
	}
	else
		$namea = "N/A";
	return $namea;
}
function getInterByCode($ccode)
{
	$namea="";
	$found=false;
	$query = "select * from rec_entries where ccode='".trim($ccode)."'";
	if($result = mysql_query($query))
	{
		if(($num_rows = mysql_num_rows($result))>0)
		{
			$rows = mysql_fetch_assoc($result);
			$nameax="";
			$nameax=$rows["report_to"];
			if(!empty($nameax))
			{
				$qx="select * from task_users where id='".$nameax."' and type in('5','6')";
				if($rx=mysql_query($qx))
				{
					if(($nux=mysql_num_rows($rx))>0)
					{
						$infox=mysql_fetch_assoc($rx);
						$found=true;
						$namea=stripslashes($infox["id"]);
					}
				}
			}
			if(!$found)
			{
				$nameax="";
				$nameax=$rows["interviewer"];
				if(!empty($nameax))
				{
					$qx="select * from task_users where id='".$nameax."' and type in('5','6')";
					if($rx=mysql_query($qx))
					{
						if(($nux=mysql_num_rows($rx))>0)
						{
							$infox=mysql_fetch_assoc($rx);
							$found=true;
							$namea=$infox["id"];
						}
					}
				}
			}
		}
		else
			$found=false;
	}
	else
		$found=false;
	if(!$found)
	{
		$query = "select * from sales_agent where acode='".trim($ccode)."'";
		if($result = mysql_query($query))
		{
			if(($num_rows = mysql_num_rows($result))>0)
			{
				$info= mysql_fetch_assoc($result);
				$found=true;
			}
		}
		if($found)
		{
			$query = "select * from sales_report where agentid='".$info["id"]."' order by fromdate desc limit 1";
			if($result = mysql_query($query))
			{
				if(($num_rows = mysql_num_rows($result))>0)
				{
					$rows = mysql_fetch_assoc($result);
					$namea=$rows["userid"];
				}
			}
		}
	}
	return $namea;
}
function getSalesRequest($date1,$date2,$office)
{
	$array=array();
	if(!empty($date1) && !empty($date2) && !empty($office))
	{
		$query=" SELECT x.id, x.name, x.acode,DATE_FORMAT(c.date, '%Y-%m-%d') AS hired,d.report_to,a.xgas as xgas, a.xelec as xelec FROM sales_agent AS x
LEFT OUTER JOIN (SELECT a.ddate, a.office, a.userid, sum( xelec ) AS xelec, sum( xgas ) AS xgas FROM sales_report_real a where a.ddate 
 between '".$date1."' and '".$date2."' and office='".$office."' GROUP BY a.userid)a ON x.id = a.userid LEFT OUTER JOIN rec_entries AS b ON x.acode=b.ccode LEFT OUTER JOIN (select y.date,y.entryid from rec_timeline as y where status='5' group by y.entryid) AS c ON b.id = c.entryid LEFT OUTER JOIN task_users as d on b.report_to=d.id  WHERE a.ddate BETWEEN '".$date1."' AND '".$date2."' AND a.office = '".$office."' ORDER BY x.name";
		if($result=mysql_query($query))
		{
			if(($num_rows=mysql_num_rows($result))>0)
			{
				while($rows=mysql_fetch_array($result))
				{
					$man=$rows["report_to"];
					if(empty($man))
						$man=getInterByCode($rows["acode"]);
					$array[]=array('userid'=>$rows["id"],'name'=>stripslashes($rows["name"]),'ccode'=>$rows["acode"],'hired'=>$rows["hired"],'manager'=>$man,'xgas'=>$rows["xgas"],'xelec'=>$rows["xelec"]);
				}
			}
		}
	}
	return $array;
}
function getRetRequest($task,$parray,$date1,$date2,$office)
{
	$array=array();
	$idx="";
	if(!empty($task) && sizeof($parray)>0)
	{
		//if($task=='dret')
		//{
			for($i=0;$i<sizeof($parray);$i++)
			{
				if(empty($idx))
					$idx="'".$parray[$i]["id"]."'";
				else
					$idx .=",'".$parray[$i]["id"]."'";
			}
			$query=" SELECT x.id, x.name, x.acode,DATE_FORMAT(c.date, '%Y-%m-%d') AS hired,d.report_to,a.xgas as xgas, a.xelec as xelec FROM sales_agent AS x
	LEFT OUTER JOIN (SELECT a.ddate, a.office, a.userid, sum( xelec ) AS xelec, sum( xgas ) AS xgas FROM sales_report_real a where a.ddate 
	 between '".$date1."' and '".$date2."' and office='".$office."' GROUP BY a.userid)a ON x.id = a.userid LEFT OUTER JOIN rec_entries AS b ON x.acode=b.ccode LEFT OUTER JOIN (select y.date,y.entryid from rec_timeline as y where status='5' group by y.entryid) AS c ON b.id = c.entryid LEFT OUTER JOIN task_users as d on b.report_to=d.id  WHERE a.ddate BETWEEN '".$date1."' AND '".$date2."' AND a.office = '".$office."' and x.id in (".$idx.") ORDER BY x.name";
	 		//echo $query;
			if($result=mysql_query($query))
			{
				if(($num_rows=mysql_num_rows($result))>0)
				{
					while($rows=mysql_fetch_array($result))
					{
						$man=$rows["report_to"];
						if(empty($man))
							$man=getInterByCode($rows["acode"]);
						$array[]=array('userid'=>$rows["id"],'name'=>stripslashes($rows["name"]),'ccode'=>$rows["acode"],'hired'=>$rows["hired"],'manager'=>$man,'xgas'=>$rows["xgas"],'xelec'=>$rows["xelec"]);
					}
				}
			}
		//}
	}
	else
		$array=$parray;
	return $array;
}
function getStatusEntryPhone($office,$date1,$date2,$sort)
{
	$phones=array();
	if(!empty($office))
		$query="select * from rec_phones where date between '".$date1."' and '".$date2."' and office='".$office."' $sort";
	else
		$query="select * from rec_phones where date between '".$date1."' and '".$date2."' $sort";
	if($result=mysql_query($query))
	{
		if(($num_rows=mysql_num_rows($result))>0)
		{
			while($rows=mysql_fetch_array($result))
			{
				$phones[]=array('id'=>$rows["id"],'caller'=>stripslashes($rows["caller"]),'tphone'=>$rows["tphone"],'date'=>$rows["date"],'office'=>$rows["office"]);
			}
		}
	}
	return $phones;
}
function getStatusEntrySales($office,$date1,$date2,$sort)
{
	/*$sales=array();
	if(!empty($office))
		$query="select distinct userid from sales_report_real where ddate between '".$date1."' and '".$date2."' and office='".$office."' $sort";
	else
		$query="select distinct userid from sales_report_real where ddate between '".$date1."' and '".$date2."' $sort";
	if($result=mysql_query($query))
	{
		if(($num_rows=mysql_num_rows($result))>0)
		{
			while($rows=mysql_fetch_array($result))
			{
				//$aname=getAgent($rows["userid"]);
				$man=getInter($rows["userid"]);
				$allsales=getAllSales($rows["userid"]);
				$xgas=$allsales[0]["xgas"];
				$xelec=$allsales[0]["xelec"];
				$sales[]=array('userid'=>$rows["userid"],'manager'=>$man,'xelec'=>$xelec,'xgas'=>$xgas);
			}
		}
	}
	return $sales;*/
	$sales=array();
	$report=array();
	$reportb=array();
	$report_final=array();
	$agent=array();
	$phones=array();
	$fphones=array();
	$total=0;
	if(empty($office))
		$query="select * from rec_phones where date between '".$date1."' and '".$date2."'";
	else
		$query="select * from rec_phones where date between '".$date1."' and '".$date2."' and office='".$office."'";
	if($result=mysql_query($query))
	{
		if(($num_rows=mysql_num_rows($result))>0)
		{
			while($rows=mysql_fetch_array($result))
			{
				$phones[]=fixOnSipPhone('family',$rows["tphone"]);
				$fphones[]=trim($rows["fphone"]);
			}
		}
	}
	for($i=0;$i<sizeof($phones);$i++)
	{
		$query="select * from rec_entries where cphonex='".trim($phones[$i])."' or cphone='".trim($phones[$i])."' or cphonex='".trim($phones[$i])."' or cphonex='".trim($fphones[$i])."'";
		if($result=mysql_query($query))
		{
			if(($nux=mysql_num_rows($result))>0)
			{
				$info=mysql_fetch_assoc($result);
				$report[]=array('id'=>$info["id"],'acode'=>$info["ccode"]);
			}
		}
	}
	if(sizeof($report)>0)
	{
		for($i=0;$i<sizeof($report);$i++)
		{
			$query="select * from sales_agent where acode='".trim($report[$i]["acode"])."'";
			if($result=mysql_query($query))
			{
				if(($nux=mysql_num_rows($result))>0)
				{
					$info=mysql_fetch_assoc($result);
					$reportb[]=array('id'=>$info["id"],'acode'=>$info["acode"]);
				}
			}
		}
	}
	if(sizeof($reportb)>0)
	{
		for($i=0;$i<sizeof($reportb);$i++)
		{
			if(!empty($office))
				$query="select distinct userid from sales_report_real where userid='".trim($reportb[$i]["id"])."'  and office='".$office."' $sort";
			else
				$query="select distinct userid from sales_report_real where userid='".trim($reportb[$i]["id"])."' $sort";
			if($result=mysql_query($query))
			{
				if(($nux=mysql_num_rows($result))>0)
				{
					$info=mysql_fetch_assoc($result);
					//$report_final[]=array('userid'=>$info["userid"],'xelec'=>$info["xelec"],'xgas'=>$info["xgas"]);
					
					$man=getInter($info["userid"]);
					$allsales=getAllSales($info["userid"]);
					$xgas=$allsales[0]["xgas"];
					$xelec=$allsales[0]["xelec"];
					$sales[]=array('userid'=>$info["userid"],'manager'=>$man,'xelec'=>$xelec,'xgas'=>$xgas);
				}
			}
		}
	}
	return $sales;
}
function getStatusSales_fromar($office,$array)
{
	$total=0;
	$reportb=array();
	$report_final=array();
	if(sizeof($array)>0)
	{
		for($i=0;$i<sizeof($array);$i++)
		{
			$query="select * from sales_agent where acode='".trim($array[$i]["ccode"])."'";
			if($result=mysql_query($query))
			{
				if(($nux=mysql_num_rows($result))>0)
				{
					$info=mysql_fetch_assoc($result);
					$reportb[]=array('id'=>$info["id"],'acode'=>$info["acode"]);
				}
			}
		}
	}
	if(sizeof($reportb)>0)
	{
		for($i=0;$i<sizeof($reportb);$i++)
		{
			$query="select * from sales_report_real where userid='".trim($reportb[$i]["id"])."'";
			if($result=mysql_query($query))
			{
				if(($nux=mysql_num_rows($result))>0)
				{
					$xelec=0;
					$xgas=0;
					while($info=mysql_fetch_array($result))
					{
						$xelec +=$info["xelec"];
						$xgas +=$info["xgas"];
					}
					$report_final[]=array('userid'=>$info["userid"],'xelec'=>$xelec,'xgas'=>$xgas);
				}
			}
		}
	}
	/*if(!empty($office))
		$query="select * from sales_report_real where ddate between '".$date1."' and '".$date2."' and office='".$office."'";
	else
		$query="select * from sales_report_real where ddate between '".$date1."' and '".$date2."'";
	if($result=mysql_query($query))
	{
		if(($num_rows=mysql_num_rows($result))>0)
		{
			while($rows=mysql_fetch_array($result))
				$report[]=array('userid'=>$rows["userid"],'xelec'=>$rows["xelec"],'xgas'=>$rows["xgas"]);
		}
	}
	if(sizeof($report)>0)
	{
		for($i=0;$i<sizeof($report);$i++)
		{
			$query="select * from sales_agent where id='".$report[$i]["userid"]."'";
			if($result=mysql_query($query))
			{
				if(($nux=mysql_num_rows($result))>0)
				{
					$info=mysql_fetch_assoc($result);
					$reportb[]=array('userid'=>$report[$i]["userid"],'acode'=>$info["acode"],'xelec'=>$report[$i]["xelec"],'xgas'=>$report[$i]["xgas"]);
				}
			}
		}
	}
	if(sizeof($reportb)>0)
	{
		for($i=0;$i<sizeof($reportb);$i++)
		{
			$query="select * from rec_entries where ccode='".trim($reportb[$i]["acode"])."'";
			if($result=mysql_query($query))
			{
				if(($nux=mysql_num_rows($result))>0)
				{
					$info=mysql_fetch_assoc($result);
					$report_final[]=array('userid'=>$reportb[$i]["userid"],'acode'=>$reportb[$i]["acode"],'xelec'=>$reportb[$i]["xelec"],'xgas'=>$reportb[$i]["xgas"]);
				}
			}
		}
	}*/
	if(sizeof($report_final))
	{
		for($i=0;$i<sizeof($report_final);$i++)
			$total += $report_final[$i]["xelec"]+$report_final[$i]["xgas"];
	}
	return $total;
}
function getStatusSales_fromar_ar($office,$array)
{
	$total=0;
	$reportb=array();
	$report_final=array();
	if(sizeof($array)>0)
	{
		for($i=0;$i<sizeof($array);$i++)
		{
			$query="select * from sales_agent where acode='".trim($array[$i]["ccode"])."'";
			if($result=mysql_query($query))
			{
				if(($nux=mysql_num_rows($result))>0)
				{
					$info=mysql_fetch_assoc($result);
					$reportb[]=array('id'=>$info["id"],'acode'=>$info["acode"]);
				}
			}
		}
	}
	if(sizeof($reportb)>0)
	{
		for($i=0;$i<sizeof($reportb);$i++)
		{
			$query="select * from sales_report_real where userid='".trim($reportb[$i]["id"])."'";
			if($result=mysql_query($query))
			{
				if(($nux=mysql_num_rows($result))>0)
				{
					$xelec=0;
					$xgas=0;
					$man=getInter($reportb[$i]["id"]);
					while($info=mysql_fetch_array($result))
					{
						$xelec +=$info["xelec"];
						$xgas +=$info["xgas"];
					}
					$report_final[]=array('userid'=>$reportb[$i]["id"],'ccode'=>$reportb[$i]["acode"],'manager'=>$man,'xelec'=>$xelec,'xgas'=>$xgas);
				}
			}
		}
	}
	/*if(!empty($office))
		$query="select * from sales_report_real where ddate between '".$date1."' and '".$date2."' and office='".$office."'";
	else
		$query="select * from sales_report_real where ddate between '".$date1."' and '".$date2."'";
	if($result=mysql_query($query))
	{
		if(($num_rows=mysql_num_rows($result))>0)
		{
			while($rows=mysql_fetch_array($result))
				$report[]=array('userid'=>$rows["userid"],'xelec'=>$rows["xelec"],'xgas'=>$rows["xgas"]);
		}
	}
	if(sizeof($report)>0)
	{
		for($i=0;$i<sizeof($report);$i++)
		{
			$query="select * from sales_agent where id='".$report[$i]["userid"]."'";
			if($result=mysql_query($query))
			{
				if(($nux=mysql_num_rows($result))>0)
				{
					$info=mysql_fetch_assoc($result);
					$reportb[]=array('userid'=>$report[$i]["userid"],'acode'=>$info["acode"],'xelec'=>$report[$i]["xelec"],'xgas'=>$report[$i]["xgas"]);
				}
			}
		}
	}
	if(sizeof($reportb)>0)
	{
		for($i=0;$i<sizeof($reportb);$i++)
		{
			$query="select * from rec_entries where ccode='".trim($reportb[$i]["acode"])."'";
			if($result=mysql_query($query))
			{
				if(($nux=mysql_num_rows($result))>0)
				{
					$info=mysql_fetch_assoc($result);
					$report_final[]=array('userid'=>$reportb[$i]["userid"],'acode'=>$reportb[$i]["acode"],'xelec'=>$reportb[$i]["xelec"],'xgas'=>$reportb[$i]["xgas"]);
				}
			}
		}
	}*/
	return $report_final;
}
function getStatusSales($office,$date1,$date2)
{
	$total=0;
	$report=array();
	$reportb=array();
	$report_final=array();
	$agent=array();
	$phones=array();
	$fphones=array();
	if(empty($office))
		$query="select * from rec_phones where date between '".$date1."' and '".$date2."'";
	else
		$query="select * from rec_phones where date between '".$date1."' and '".$date2."' and office='".$office."'";
	if($result=mysql_query($query))
	{
		if(($num_rows=mysql_num_rows($result))>0)
		{
			while($rows=mysql_fetch_array($result))
				$phones[]=fixOnSipPhone('family',$rows["tphone"]);
				$fphones[]=trim($rows["fphone"]);
		}
	}
	for($i=0;$i<sizeof($phones);$i++)
	{
		$query="select * from rec_entries where (cphonex='".trim($phones[$i])."' or cphonex='".trim($fphones[$i])."') or (cphone='".trim($fphones[$i])."' or cphone='".trim($phones[$i])."')";
		if($result=mysql_query($query))
		{
			if(($nux=mysql_num_rows($result))>0)
			{
				$info=mysql_fetch_assoc($result);
				$report[]=array('id'=>$info["id"],'acode'=>$info["ccode"]);
			}
		}
	}
	if(sizeof($report)>0)
	{
		for($i=0;$i<sizeof($report);$i++)
		{
			$query="select * from sales_agent where acode='".trim($report[$i]["acode"])."'";
			if($result=mysql_query($query))
			{
				if(($nux=mysql_num_rows($result))>0)
				{
					$info=mysql_fetch_assoc($result);
					$reportb[]=array('id'=>$info["id"],'acode'=>$info["acode"]);
				}
			}
		}
	}
	if(sizeof($reportb)>0)
	{
		for($i=0;$i<sizeof($reportb);$i++)
		{
			$query="select * from sales_report_real where userid='".trim($reportb[$i]["id"])."'";
			if($result=mysql_query($query))
			{
				if(($nux=mysql_num_rows($result))>0)
				{
					$xelec=0;
					$xgas=0;
					while($info=mysql_fetch_array($result))
					{
						$xelec +=$info["xelec"];
						$xgas +=$info["xgas"];
					}
					$report_final[]=array('userid'=>$info["userid"],'xelec'=>$xelec,'xgas'=>$xgas);
				}
			}
		}
	}
	/*if(!empty($office))
		$query="select * from sales_report_real where ddate between '".$date1."' and '".$date2."' and office='".$office."'";
	else
		$query="select * from sales_report_real where ddate between '".$date1."' and '".$date2."'";
	if($result=mysql_query($query))
	{
		if(($num_rows=mysql_num_rows($result))>0)
		{
			while($rows=mysql_fetch_array($result))
				$report[]=array('userid'=>$rows["userid"],'xelec'=>$rows["xelec"],'xgas'=>$rows["xgas"]);
		}
	}
	if(sizeof($report)>0)
	{
		for($i=0;$i<sizeof($report);$i++)
		{
			$query="select * from sales_agent where id='".$report[$i]["userid"]."'";
			if($result=mysql_query($query))
			{
				if(($nux=mysql_num_rows($result))>0)
				{
					$info=mysql_fetch_assoc($result);
					$reportb[]=array('userid'=>$report[$i]["userid"],'acode'=>$info["acode"],'xelec'=>$report[$i]["xelec"],'xgas'=>$report[$i]["xgas"]);
				}
			}
		}
	}
	if(sizeof($reportb)>0)
	{
		for($i=0;$i<sizeof($reportb);$i++)
		{
			$query="select * from rec_entries where ccode='".trim($reportb[$i]["acode"])."'";
			if($result=mysql_query($query))
			{
				if(($nux=mysql_num_rows($result))>0)
				{
					$info=mysql_fetch_assoc($result);
					$report_final[]=array('userid'=>$reportb[$i]["userid"],'acode'=>$reportb[$i]["acode"],'xelec'=>$reportb[$i]["xelec"],'xgas'=>$reportb[$i]["xgas"]);
				}
			}
		}
	}*/
	if(sizeof($report_final))
	{
		for($i=0;$i<sizeof($report_final);$i++)
			$total += $report_final[$i]["xelec"]+$report_final[$i]["xgas"];
	}
	return $total;
}
function getStatusEntry_all($status,$date1,$date2)
{
	$total=0;
	$tphone=array();
	$fphone=array();
	$query="select * from rec_phones where date between '".$date1."' and '".$date2."'";
	if($result=mysql_query($query))
	{
		if(($num_rows=mysql_num_rows($result))>0)
		{
			while($row=mysql_fetch_array($result))
			{
				$tphone[]=$tphonex=fixOnSipPhone('family',$row["tphone"]);
				$fphone[]=trim($row["fphone"]);
			}
		}
	}
	if(sizeof($tphone)>0)
	{
	//$xp=" and cphonex IS NOT NULL ";
	if($status=='1')
	{
		//$xp=" where cphonex IS NOT NULL ";
		$qca="select * from rec_entries where office is not null $xp";
	}
	else if($status=='3' || $status=='7')
		$qca="select * from rec_entries where orientation_office IS NOT NULL $xp";
	if($result=mysql_query($qca))
	{
		if(($num_rows=mysql_num_rows($result))>0)
		{
			while($rowx=mysql_fetch_array($result))
			{
				$found=false;
				for($i=0;$i<sizeof($tphone);$i++)
				{
					if(trim($tphone[$i])==trim($rowx["cphonex"]) || trim($tphone[$i])==trim($rowx["cphone"]) || trim($fphone[$i])==trim($rowx["cphone"]))
					{
						$found=true;
						break;
					}
				}
				if($found)
				{
					//$phonex=$rowx["cphonex"];
					$qcax="select * from rec_timeline where entryid='".$rowx["id"]."' and status='".$status."' and date between '".$date1."' and '".$date2."'";
					if($rx=mysql_query($qcax))
					{
						if(($numx=mysql_num_rows($rx))>0)
							$total++;
					}
				}
			}
		}
	}
	}
	return $total;
}
function getStatusEntry_all_fromar($status,$array)
{
	$total=0;
	$narray=array();
	if(sizeof($array)>0)
	{
		for($i=0;$i<sizeof($array);$i++)
		{
			$qcax="select * from rec_timeline where entryid='".$array[$i]["id"]."' and status='".$status."'";
			if($rx=mysql_query($qcax))
			{
				if(($numx=mysql_num_rows($rx))>0)
				{
					$info=mysql_fetch_assoc($rx);
					$orien=$array[$i]["orientation"];
					$orienc=$array[$i]["orientation_comp"];
					$orien_of=$array[$i]["orientation_office"];
					if(empty($orien_of))
						$orien_of=$array[$i]["office"];
					switch($status)
					{
						case '3':
						{
							if(empty($array[$i]["orientation"]))
								$orien=$info["xdate"];
							break;
						}
						case '7':
						{
							if(empty($array[$i]["orientation_comp"]))
								$orienc=$info["xdate"];
							break;
						}
					}
					$narray[]=array('id'=>$array[$i]["id"],'name'=>stripslashes($array[$i]["name"]),'ccode'=>$array[$i]["ccode"],'manager'=>$array[$i]["manager"],'csource'=>$array[$i]["csource"],'csource_title'=>$array[$i]["csource_title"],'idate'=>$array[$i]["idate"],'itime'=>$array[$i]['itime'],'orientation'=>$orien,'orientation_comp'=>$orienc,'orientation_office'=>$orien_of,'office'=>$array[$i]["office"],'ocall'=>$array[$i]["ocall"]);
				}
			}
		}
	}
	return $narray;
}
function getStatusEntry_all_array_entry($status,$date1,$date2,$sfilter)
{
	$total=0;
	$userx=array();
	//$xp=" and cphonex IS NOT NULL ";
	if($status=='1')
	{
		//$xp=" where cphonex IS NOT NULL ";
		$qca="select * from rec_entries where office is not null and cdate between '".$date1."' and '".$date2."' $xp $sfilter";
	}
	else if($status=='3' || $status=='7')
		$qca="select * from rec_entries where orientation_office IS NOT NULL and cdate between '".$date1."' and '".$date2."' $xp $sfilter";
	if($result=mysql_query($qca))
	{
		if(($num_rows=mysql_num_rows($result))>0)
		{
			while($rowx=mysql_fetch_array($result))
			{
				$qcax="select * from rec_timeline where entryid='".$rowx["id"]."' and status='".$status."'";
				if($rx=mysql_query($qcax))
				{
					if(($numx=mysql_num_rows($rx))>0)
					{
						$info=mysql_fetch_assoc($rx);
						$orien=$rowx["orientation"];
						$orienc=$rowx["orientation_comp"];
						$orien_of=$rowx["orientation_office"];
						if(empty($orien_of))
							$orien_of=$rowx["office"];
						switch($status)
						{
							case '3':
							{
								if(empty($$rowx["orientation"]))
									$orien=$info["xdate"];
								break;
							}
							case '7':
							{
								if(empty($rowx["orientation_comp"]))
									$orienc=$info["xdate"];
								break;
							}
						}
						$man=getInterByCode($rowx["ccode"]);
						$userx[]=array('id'=>$rowx["id"],'name'=>stripslashes($rowx["cname"]),'ccode'=>$rowx["ccode"],'manager'=>$man,'csource'=>$rowx["csource"],'csource_title'=>$rowx["csource_title"],'idate'=>$rowx["idate"],'itime'=>$rowx['itime'],'orientation'=>$orien,'orientation_comp'=>$orienc,'orientation_office'=>$orien_of,'office'=>$rowx["office"],'ocall'=>$rowx["ocall"]);
					}
				}
			}
		}
	}
	return $userx;
}
function getStatusEntry_all_array($status,$date1,$date2)
{
	$total=0;
	$tphone=array();
	$fphone=array();
	$userx=array();
	$query="select * from rec_phones where date between '".$date1."' and '".$date2."'";
	if($result=mysql_query($query))
	{
		if(($num_rows=mysql_num_rows($result))>0)
		{
			while($row=mysql_fetch_array($result))
			{
				$tphone[]=$tphonex=fixOnSipPhone('family',$row["tphone"]);
				$fphone[]=trim($row["fphone"]);
			}
		}
	}
	if(sizeof($tphone)>0)
	{
	//$xp=" and cphonex IS NOT NULL ";
	if($status=='1')
	{
		//$xp=" where cphonex IS NOT NULL ";
		$qca="select * from rec_entries where office is not null $xp";
	}
	else if($status=='3' || $status=='7')
		$qca="select * from rec_entries where orientation_office IS NOT NULL $xp";
	if($result=mysql_query($qca))
	{
		if(($num_rows=mysql_num_rows($result))>0)
		{
			while($rowx=mysql_fetch_array($result))
			{
				$found=false;
				for($i=0;$i<sizeof($tphone);$i++)
				{
					if(trim($tphone[$i])==trim($rowx["cphonex"]) || trim($tphone[$i])==trim($rowx["cphone"]) || trim($fphone[$i])==trim($rowx["cphone"]))
					{
						$found=true;
						break;
					}
				}
				if($found)
				{
					$qcax="select * from rec_timeline where entryid='".$rowx["id"]."' and status='".$status."'";
					if($rx=mysql_query($qcax))
					{
						if(($numx=mysql_num_rows($rx))>0)
						{
							$info=mysql_fetch_assoc($rx);
							$orien=$array[$i]["orientation"];
							$orienc=$array[$i]["orientation_comp"];
							$orien_of=$array[$i]["orientation_office"];
							if(empty($orien_of))
								$orien_of=$array[$i]["office"];
							switch($status)
							{
								case '3':
								{
									if(empty($array[$i]["orientation"]))
										$orien=$info["xdate"];
									break;
								}
								case '7':
								{
									if(empty($array[$i]["orientation_comp"]))
										$orienc=$info["xdate"];
									break;
								}
							}
							$man=getInterByCode($rowx["ccode"]);
							$userx[]=array('id'=>$rowx["id"],'name'=>stripslashes($rowx["cname"]),'ccode'=>$rowx["ccode"],'manager'=>$man,'csource'=>$rowx["csource"],'csource_title'=>$rowx["csource_title"],'idate'=>$rowx["idate"],'itime'=>$rowx['itime'],'orientation'=>$orien,'orientation_comp'=>$orienc,'orientation_office'=>$orien_of,'office'=>$rowx["office"]);
						}
					}
				}
			}
		}
	}
	}
	return $userx;
}
function getStatusEntry($office,$date1,$date2,$status)
{
	$total=0;
	$tphone=array();
	$fphone=array();
	$query="select * from rec_phones where date between '".$date1."' and '".$date2."' and office='".$office."'";
	if($result=mysql_query($query))
	{
		if(($num_rows=mysql_num_rows($result))>0)
		{
			while($row=mysql_fetch_array($result))
			{
				$tphone[]=fixOnSipPhone('family',$row["tphone"]);
				$fphone[]=trim($row["fphone"]);
			}
		}
	}
	if(sizeof($tphone)>0)
	{
	//$xp=" and cphonex IS NOT NULL ";
	if($status=='1')
		$qca="select * from rec_entries where office is not null $xp";
	else if($status=='3' || $status=='7')
		$qca="select * from rec_entries where orientation_office is not null $xp";
	if($result=mysql_query($qca))
	{
		if(($num_rows=mysql_num_rows($result))>0)
		{
			while($rowx=mysql_fetch_array($result))
			{
				$found=false;
				for($i=0;$i<sizeof($tphone);$i++)
				{
					if(trim($tphone[$i])==trim($rowx["cphonex"]) || trim($tphone[$i])==trim($rowx["cphone"]) || trim($fphone[$i])==trim($rowx["cphone"]))
					{
						$found=true;
						break;
					}
				}
				if($found)
				{
					$phonex=$rowx["cphonex"];
					$qcax="select * from rec_timeline where entryid='".$rowx["id"]."' and status='".$status."' and date between '".$date1."' and '".$date2."'";
					if($rx=mysql_query($qcax))
					{
						if(($numx=mysql_num_rows($rx))>0)
							$total++;
					}
				}
			}
		}
	}
	}
	return $total;
}
function getStatusEntry_array_entry($office,$date1,$date2,$status,$sfilter)
{
	$total=0;
	$tphone=array();
	$fphone=array();
	$userx=array();
	//$xp=" and cphonex IS NOT NULL ";
	if($status=='1')
		$qca="select * from rec_entries where coffice='".$office."' and cdate between '".$date1."' and '".$date2."' $xp $sfilter";
	else if($status=='3' || $status=='7')
		$qca="select * from rec_entries where orientation_office='".$office." and cdate between '".$date1."' and '".$date2."' $xp $sfilter";
	if($result=mysql_query($qca))
	{
		if(($num_rows=mysql_num_rows($result))>0)
		{
			while($rowx=mysql_fetch_array($result))
			{
				$phonex=$rowx["cphonex"];
				$qcax="select * from rec_timeline where entryid='".$rowx["id"]."' and status='".$status."'";
				if($rx=mysql_query($qcax))
				{
					if(($numx=mysql_num_rows($rx))>0)
					{
						$man=getInterByCode($rowx["ccode"]);
						$info=mysql_fetch_assoc($rx);
						$orien=$rowx["orientation"];
						$orienc=$rowx["orientation_comp"];
						$orien_of=$rowx["orientation_office"];
						if(empty($orien_of))
							$orien_of=$rowx["office"];
						switch($status)
						{
							case '3':
							{
								if(empty($rowx["orientation"]))
									$orien=$info["xdate"];
								break;
							}
							case '7':
							{
								if(empty($rowx["orientation_comp"]))
									$orienc=$info["xdate"];
								break;
							}
						}
						$userx[]=array('id'=>$rowx["id"],'name'=>stripslashes($rowx["cname"]),'ccode'=>$rowx["ccode"],'manager'=>$man,'csource'=>$rowx["csource"],'csource_title'=>$rowx["csource_title"],'idate'=>$rowx["idate"],'itime'=>$rowx['itime'],'orientation'=>$orien,'orientation_comp'=>$orienc,'orientation_office'=>$orien_of,'office'=>$rowx["office"],'ocall'=>$rowx["ocall"]);
					}
				}
			}
		}
	}
	return $userx;
}
function getStatusEntry_array($office,$date1,$date2,$status)
{
	$total=0;
	$tphone=array();
	$fphone=array();
	$userx=array();
	$query="select * from rec_phones where date between '".$date1."' and '".$date2."' and office='".$office."'";
	if($result=mysql_query($query))
	{
		if(($num_rows=mysql_num_rows($result))>0)
		{
			while($row=mysql_fetch_array($result))
			{
				$tphone[]=fixOnSipPhone('family',$row["tphone"]);
				$fphone[]=trim($row["fphone"]);
			}
		}
	}
	if(sizeof($tphone)>0)
	{
	//$xp=" and cphonex IS NOT NULL ";
	if($status=='1')
		$qca="select * from rec_entries where office is not null $xp";
	else if($status=='3' || $status=='7')
		$qca="select * from rec_entries where orientation_office is not null $xp";
	if($result=mysql_query($qca))
	{
		if(($num_rows=mysql_num_rows($result))>0)
		{
			while($rowx=mysql_fetch_array($result))
			{
				$found=false;
				for($i=0;$i<sizeof($tphone);$i++)
				{
					if(trim($tphone[$i])==trim($rowx["cphonex"]) || trim($tphone[$i])==trim($rowx["cphone"]) || trim($fphone[$i])==trim($rowx["cphone"]))
					{
						$found=true;
						break;
					}
				}
				if($found)
				{
					$phonex=$rowx["cphonex"];
					$qcax="select * from rec_timeline where entryid='".$rowx["id"]."' and status='".$status."'";
					if($rx=mysql_query($qcax))
					{
						if(($numx=mysql_num_rows($rx))>0)
						{
							$man=getInterByCode($rowx["ccode"]);
							$info=mysql_fetch_assoc($rx);
							$orien=$array[$i]["orientation"];
							$orienc=$array[$i]["orientation_comp"];
							$orien_of=$array[$i]["orientation_office"];
							if(empty($orien_of))
								$orien_of=$array[$i]["office"];
							switch($status)
							{
								case '3':
								{
									if(empty($array[$i]["orientation"]))
										$orien=$info["xdate"];
									break;
								}
								case '7':
								{
									if(empty($array[$i]["orientation_comp"]))
										$orienc=$info["xdate"];
									break;
								}
							}
							$userx[]=array('id'=>$rowx["id"],'name'=>stripslashes($rowx["cname"]),'ccode'=>$rowx["ccode"],'manager'=>$man,'csource'=>$rowx["csource"],'csource_title'=>$rowx["csource_title"],'idate'=>$rowx["idate"],'itime'=>$rowx['itime'],'orientation'=>$orien,'orientation_comp'=>$orienc,'orientation_office'=>$orien_of,'office'=>$rowx["office"]);
						}
					}
				}
			}
		}
	}
	}
	return $userx;
}
function isTimeline($id)
{
	$save=false;
	$qx="select * from rec_timeline where entryid='".$id."'";
	if($result=mysql_query($qx))
	{
		if(($num_rows=mysql_num_rows($result))>0)
			$save=true;
	}
	return $save;
}
function getEntryInfo($task,$id)
{
	$str="";
	$query="select * from rec_entries where id='".$id."'";
	if($result=mysql_query($query))
	{
		if(($num_rows=mysql_num_rows($result))>0)
		{
			$info=mysql_fetch_assoc($result);
			if($task=="cdate")
				$str=$info["cdate"];
			else if($task=="date")
			{
				$str=$info["date"];
				if(empty($str))
					$str=$info["cdate"];
			}
			else if($task=="cr_time")
				$str=$info["cr_time"];
			else if($task=="name")
				$str=stripslashes($info["cname"]);
			else if($task=="orientation_comp")
				$str=$info["orientation_comp"];
			else if($task=="orientation_show")
				$str=$info["orientation_show"];
			else if($task=="orientation_office")
				$str=$info["orientation_office"];
			else if($task=="orientation")
				$str=$info["orientation"];
			else if($task=="interviewer")
				$str=$info["interviewer"];
			else if($task=="hired")
				$str=$info["hired"];
			else if($task=="int_show_date")
				$str=$info["int_show_date"];
			else if($task=="int_show")
				$str=$info["int_show"];
			else if($task=="status")
				$str=$info["status"];
			else if($task=="idate")
				$str=$info["idate"];
			else if($task=="itime")
				$str=$info["itime"];
			else if($task=="idate_comp")
				$str=$info["idate"]." ".$info["itime"];
			else if($task=="idate_comp")
				$str=$info["idate"]." ".$info["itime"];
			else if($task=="ccode")
				$str=$info["ccode"];
		}
	}
	return $str;
}
function checkTimeSaved($id,$status)
{
	$save=false;
	$qx="select * from rec_timeline where entryid='".$id."' and status='".$status."'";
	if($result=mysql_query($qx))
	{
		if(($num_rows=mysql_num_rows($result))>0)
			$save=true;
	}
	return $save;
}
function setTimeLinex($id)
{
	$test_check="";
	$checkc=isTimeline($id);
	$query="select * from rec_entries where id='".$id."'";
	if($result=mysql_query($query))
	{
		if(($num_rows=mysql_num_rows($result))>0)
			$info=mysql_fetch_assoc($result);
	}
	if(!$checkc)
	{
		$date=$info["date"];
		$dtime=$info["crtime"];
		if(empty($date))
		{
			$date=$info["cdate"];
			$dtime="12:00:00";
			$date=$date." ".$dtime;
		}
		else
		{
			if(empty($dtime))
				$dtime="12:00:00";
			$date=$date." ".$dtime;
		}
		$idate=$info["idate"];
		$itime=$info["itime"];
		if(empty($itime))
			$itime="12:00:00";
		$xdate=$idate." ".$itime;
		$qtime="insert ignore into rec_timeline(entryid,status,date,xdate)values('".$id."','1','".$date."','".$xdate."')";
		@mysql_query($qtime);
		//$test_check .=" [ Interview Set: ".$xdate." date: ".$date."] ";
		$xdate="";
		$idate="";
		$itime="";
		$date="";
		if($info["status"] !='1')
		{
			if(!empty($info["int_show_date"]))
			{
				$qtime="insert ignore into rec_timeline(entryid,status,date)values('".$id."','10','".$info["int_show_date"]."')";
				@mysql_query($qtime);
				//$test_check .=" [Interview Show Set: ".$info["int_show_date"]."] ";
			}
			if($info["hired"]=="yes")
			{
				$odate=$info["orientation"];
				if(!empty($odate))
				{
					$qtime="insert ignore into rec_timeline(entryid,status,date)values('".$id."','5','".$odate."')";
					@mysql_query($qtime);
					//$test_check .=" [Hired Set:".$odate."] ";
					$qtime="insert ignore into rec_timeline(entryid,status,date,xdate)values('".$id."','3','".$odate."','".$odate."')";
					@mysql_query($qtime);
					//$test_check .=" [Orientation Set:".$odate."] ";
					if(!empty($info["orientation_show"]))
					{
						$qtime="insert ignore into rec_timeline(entryid,status,date,xdate)values('".$id."','11','".$info["orientation_show"]."','".$info["orientation_show"]."')";
						@mysql_query($qtime);
						//$test_check .=" [Orient Show Set:".$info["orientation_show"]."] ";
					}
					if(!empty($info["orientation_comp"]))
					{
						$qtime="insert ignore into rec_timeline(entryid,status,date,xdate)values('".$id."','7','".$info["orientation_comp"]."','".$info["orientation_comp"]."')";
						@mysql_query($qtime);
						//$test_check .=" [Orient Comp Set:".$info["orientation_comp"]."] ";
					}
					if(!empty($info["ccode"]))
					{
						$qtime="insert ignore into rec_timeline(entryid,status,date,xdate)values('".$id."','17','".$info["orientation_comp"]."','".$info["orientation_comp"]."')";
						@mysql_query($qtime);
						//$test_check .=" [Orient Comp Set:".$info["orientation_comp"]."] ";
					}
				}
			}
			else
			{
				$xdate=$info["idate"];
				if(!empty($xdate))
				{
					if(!empty($info["itime"]))
						$xdate=$info["idate"]." ".$info["itime"];
					else
						$xdate=$info["idate"]." 12:00:00";
				}
				if(empty($xdate))
				{
					$xdate=$info["date"];
					if(!empty($info["crtime"]))
						$xdate=$xdate." ".$info["crtime"];
					else
						$xdate=$xdate." 12:00:00";
				}
				if(empty($xdate))
					$xdate=$info["cdate"]." 12:00:00";
				//if agent is not hired
				$odate=$info["orientation"];
				if(!empty($odate))
				{
					$qtime="insert ignore into rec_timeline(entryid,status,date,xdate)values('".$id."','3','".$odate."','".$odate."')";
					@mysql_query($qtime);
					//$test_check .=" [Orient Set Not Hired:".$odate."]  ";
	
				}
				if(!empty($info["orientation_show"]))
				{
					$qtime="insert ignore into rec_timeline(entryid,status,date,xdate)values('".$id."','11','".$info["orientation_show"]."','".$info["orientation_show"]."')";
					@mysql_query($qtime);
					//$test_check .=" [Orient Showed Not Hired:".$info["orientation_show"]." Date:".$info["orientation_show"]."]  ";
				}
				if(!empty($info["orientation_comp"]))
				{
					$qtime="insert ignore into rec_timeline(entryid,status,date,xdate)values('".$id."','7','".$info["orientation_comp"]."','".$info["orientation_comp"]."')";
					@mysql_query($qtime);
					//$test_check .=" [Orient Comp Set Not Hired:".$info["orientation_comp"]."]  ";
				}
				if(!empty($info["orientation_comp"]) || !empty($info["orientation_show"]) || !empty($odate))
				{
					$oxdate=$info["orientation_comp"];
					if(empty($oxdate))
						$oxdate=$info["orientation_show"];
					if(empty($oxdate))
						$oxdate=$odate;
				}
				else
					$oxdate=$xdate;
				if($info["status"]=='9')
				{
					$qtime="insert ignore into rec_timeline(entryid,status,date,xdate)values('".$id."','9','".$oxdate."','".$oxdate."')";
					@mysql_query($qtime);
					//$test_check .=" [Not Interested Set:".$oxdate."]  ";
				}
				else if($info["status"]=='8')
				{
					$qtime="insert ignore into rec_timeline(entryid,status,date)values('".$id."','8','".$oxdate."')";
					@mysql_query($qtime);
					//$test_check .=" [Not Show Set:".$oxdate."]  ";
				}
				else
				{
					$qtime="insert ignore into rec_timeline(entryid,status,date)values('".$id."','2','".$oxdate."')";
					@mysql_query($qtime);
					//$test_check .=" [Not Hired Set:".$oxdate."]  ";
				}
			}
		}
	}
	else
	{
		$status_s=array();
		$status_s[]=1;
		$status_s[]=8;
		$status_s[]=2;
		$status_s[]=10;
		$status_s[]=3;
		$status_s[]=11;
		$status_s[]=5;
		$status_s[]=7;
		$status_s[]=9;
		for($x=0;$x<sizeof($status_s);$x++)
		{
			$dosave=false;
			$date="";
			$xdate="";
			$itime="";
			if($status_s[$x]=="1")
			{
				$xdate=$info["idate"];
				$itime=$info["itime"];
				if(!empty($xdate))
				{
					if(empty($itime))
						$itime="12:00:00";
					$xdate=$xdate." ".$itime;
					$date=$info["date"];
					$itime=$info["crtime"];
					if(empty($date))
					{
						$date=$info["cdate"];
						$itime="12:00:00";
					}
					else
					{
						if(empty($itime))
							$itime='12:00:00';
					}
					$date=$date." ".$itime;
					$dosave=true;
				}
			}
			else if($status_s[$x]=="2")
			{
				if($info["status"]=='2')
				{
					$date=$info["orientation_comp"];
					if(empty($date))
					{
						if(empty($date))
							$date=$info["orientation"];
						if(empty($date))
						{
							$date=$info["idate"];
							$itime=$info["itime"];
							if(empty($date))
								$date=$info["cdate"];
							if(empty($itime))
							{
								$itime=$info["crtime"];
								if(empty($itime))
									$itime="12:00:00";
							}
							$date=$date." ".$itime;
						}
					}
					$dosave=true;
				}
			}
			else if($status_s[$x]=="3")
			{
				$xdate=$info["orientation"];
				if(!empty($xdate))
				{
					$date=$info["idate"];
					$itime=$info["itime"];
					if(empty($date))
						$date=$info["orientation"];
					else
						$date=$date." ".$itime;
					$dosave=true;
				}
			}
			else if($status_s[$x]=="5")
			{
				$xdate=$info["orientation"];
				if($info["status"] !='2' && $info["status"] !='8' && $info["status"] !='9')
				{

					if(!empty($xdate))
					{
						$date=$xdate;
						$dosave=true;
					}
				}
			}
			else if($status_s[$x]=="7")
			{
				$xdate=$info["orientation_comp"];
				$date=$xdate;
				if(!empty($xdate))
					$dosave=true;
			}
			else if($status_s[$x]=="8")
			{
				if($info["status"]=='8')
				{
					$date=$info["orientation"];
					if(empty($date))
					{
						$date=$info["idate"];
						$itime=$info["itime"];
						if(empty($date))
						{
							$date=$info["date"];
							$itime=$info["crtime"];
						}
						if(empty($date))
						{
							$date=$info["cdate"];
							$itime="12:00:00";
						}
						if(!empty($date))
						{
							$date=$date." ".$itime;
							$dosave=true;
						}
					}
					if(!empty($date))
						$dosave=true;
				}
			}
			else if($status_s[$x]=="9")
			{
				if($info["status"]=='9')
				{
					$date=$info["orientation_comp"];
					if(empty($date))
						$date=$info["orientation"];
					if(empty($date))
					{
						$date=$info["idate"];
						$itime=$info["itime"];
						if(empty($date))
						{
							$date=$info["date"];
							$itime=$info["crtime"];
						}
						if(empty($date))
						{
							$date=$info["cdate"];
							$itime="12:00:00";
						}
						if(!empty($date))
						{
							$date=$date." ".$itime;
							$dosave=true;
						}
					}
					if(!empty($date))
						$dosave=true;
				}
			}
			else if($status_s[$x]=="10")
			{
				$xdate=$info["int_show_date"];
				if(!empty($xdate))
				{
					$date=$info["int_show_date"];
					$dosave=true;
				}
			}
			else if($status_s[$x]=="11")
			{
				$xdate=$info["orientation_show"];
				if(!empty($xdate))
				{
					$date=$xdate;
					$dosave=true;
				}
			}
			if($dosave)
				setTimeLineSet($info["id"],$status_s[$x],$xdate,$date);	
		}
		if(!empty($info["ccode"]))
		{
			$xdate="";
			$date="";
			$date=$info["orientation_comp"];
			if(empty($date))
				$date=$info["orientation"];
			if(!empty($date))
				setTimeLineSet($info["id"],17,$xdate,$date);
		}
	}
	//return $test_check;
}
function setTimeLinex_test($id)
{
	$test_check="";
	$checkc=isTimeline($id);
	$query="select * from rec_entries where id='".$id."'";
	if($result=mysql_query($query))
	{
		if(($num_rows=mysql_num_rows($result))>0)
			$info=mysql_fetch_assoc($result);
	}
	if(!$checkc)
	{
		$date=$info["date"];
		$dtime=$info["crtime"];
		if(empty($date))
		{
			$date=$info["cdate"];
			$dtime="12:00:00";
			$date=$date." ".$dtime;
		}
		else
		{
			if(empty($dtime))
				$dtime="12:00:00";
			$date=$date." ".$dtime;
		}
		$idate=$info["idate"];
		$itime=$info["itime"];
		if(empty($itime))
			$itime="12:00:00";
		$xdate=$idate." ".$itime;
		$qtime="insert ignore into rec_timeline(entryid,status,date,xdate)values('".$id."','1','".$date."','".$xdate."')";
		//@mysql_query($qtime);
		$test_check .=" [ Interview Set: ".$xdate." date: ".$date."] ";
		$xdate="";
		$idate="";
		$itime="";
		$date="";
		if($info["status"] !='1')
		{
			if(!empty($info["int_show_date"]))
			{
				$qtime="insert ignore into rec_timeline(entryid,status,date)values('".$id."','10','".$info["int_show_date"]."')";
				//@mysql_query($qtime);
				$test_check .=" [Interview Show Set: ".$info["int_show_date"]."] ";
			}
			if($info["hired"]=="yes")
			{
				$odate=$info["orientation"];
				if(!empty($odate))
				{
					$qtime="insert ignore into rec_timeline(entryid,status,date)values('".$id."','5','".$odate."')";
					//@mysql_query($qtime);
					$test_check .=" [Hired Set:".$odate."] ";
					$qtime="insert ignore into rec_timeline(entryid,status,date,xdate)values('".$id."','3','".$odate."','".$odate."')";
					//@mysql_query($qtime);
					$test_check .=" [Orientation Set:".$odate."] ";
					if(!empty($info["orientation_show"]))
					{
						$qtime="insert ignore into rec_timeline(entryid,status,date,xdate)values('".$id."','11','".$odate."','".$info["orientation_show"]."')";
						//@mysql_query($qtime);
						$test_check .=" [Orient Show Set:".$info["orientation_show"]."] ";
					}
					if(!empty($info["orientation_comp"]))
					{
						$qtime="insert ignore into rec_timeline(entryid,status,date,xdate)values('".$id."','7','".$info["orientation_comp"]."','".$info["orientation_comp"]."')";
						//@mysql_query($qtime);
						$test_check .=" [Orient Comp Set:".$info["orientation_comp"]."] ";
					}
					if(!empty($info["ccode"]))
					{
						$qtime="insert ignore into rec_timeline(entryid,status,date,xdate)values('".$id."','17','".$info["orientation_comp"]."','".$info["orientation_comp"]."')";
						//@mysql_query($qtime);
						$test_check .=" [Orient Comp Set:".$info["orientation_comp"]."] ";
					}
				}
			}
			else
			{
				$xdate=$info["idate"];
				if(!empty($xdate))
				{
					if(!empty($info["itime"]))
						$xdate=$info["idate"]." ".$info["itime"];
					else
						$xdate=$info["idate"]." 12:00:00";
				}
				if(empty($xdate))
				{
					$xdate=$info["date"];
					if(!empty($info["crtime"]))
						$xdate=$xdate." ".$info["crtime"];
					else
						$xdate=$xdate." 12:00:00";
				}
				if(empty($xdate))
					$xdate=$info["cdate"]." 12:00:00";
				//if agent is not hired
				$odate=$info["orientation"];
				if(!empty($odate))
				{
					$qtime="insert ignore into rec_timeline(entryid,status,date,xdate)values('".$id."','3','".$odate."','".$odate."')";
					//@mysql_query($qtime);
					$test_check .=" [Orient Set Not Hired:".$odate."]  ";
	
				}
				if(!empty($info["orientation_show"]))
				{
					$qtime="insert ignore into rec_timeline(entryid,status,date,xdate)values('".$id."','11','".$info["orientation_show"]."','".$info["orientation_show"]."')";
					//@mysql_query($qtime);
					$test_check .=" [Orient Showed Not Hired:".$info["orientation_show"]." Date:".$info["orientation_show"]."]  ";
				}
				if(!empty($info["orientation_comp"]))
				{
					$qtime="insert ignore into rec_timeline(entryid,status,date,xdate)values('".$id."','7','".$info["orientation_comp"]."','".$info["orientation_comp"]."')";
					//@mysql_query($qtime);
					$test_check .=" [Orient Comp Set Not Hired:".$info["orientation_comp"]."]  ";
				}
				if(!empty($info["orientation_comp"]) || !empty($info["orientation_show"]) || !empty($odate))
				{
					$oxdate=$info["orientation_comp"];
					if(empty($oxdate))
						$oxdate=$info["orientation_show"];
					if(empty($oxdate))
						$oxdate=$odate;
				}
				else
					$oxdate=$xdate;
				if($info["status"]=='9')
				{
					$qtime="insert ignore into rec_timeline(entryid,status,date,xdate)values('".$id."','9','".$oxdate."','".$oxdate."')";
					//@mysql_query($qtime);
					$test_check .=" [Not Interested Set:".$oxdate."]  ";
				}
				else if($info["status"]=='8')
				{
					$qtime="insert ignore into rec_timeline(entryid,status,date)values('".$id."','8','".$oxdate."')";
					//@mysql_query($qtime);
					$test_check .=" [Not Show Set:".$oxdate."]  ";
				}
				else
				{
					$qtime="insert ignore into rec_timeline(entryid,status,date)values('".$id."','2','".$oxdate."')";
					//@mysql_query($qtime);
					$test_check .=" [Not Hired Set:".$oxdate."]  ";
				}
			}
		}
	}
	else
	{
		$status_s=array();
		$status_s[]=1;
		$status_s[]=8;
		$status_s[]=2;
		$status_s[]=10;
		$status_s[]=3;
		$status_s[]=11;
		$status_s[]=5;
		$status_s[]=7;
		$status_s[]=9;
		for($x=0;$x<sizeof($status_s);$x++)
		{
			$dosave=false;
			$date="";
			$xdate="";
			$itime="";
			if($status_s[$x]=="1")
			{
				$xdate=$info["idate"];
				$itime=$info["itime"];
				if(!empty($xdate))
				{
					if(empty($itime))
						$itime="12:00:00";
					$xdate=$xdate." ".$itime;
					$date=$info["date"];
					$itime=$info["crtime"];
					if(empty($date))
					{
						$date=$info["cdate"];
						$itime="12:00:00";
					}
					else
					{
						if(empty($itime))
							$itime='12:00:00';
					}
					$date=$date." ".$itime;
					$dosave=true;
				}
			}
			else if($status_s[$x]=="2")
			{
				if($info["status"]=='2')
				{
					$date=$info["orientation_comp"];
					if(empty($date))
					{
						if(empty($date))
							$date=$info["orientation"];
						if(empty($date))
						{
							$date=$info["idate"];
							$itime=$info["itime"];
							if(empty($date))
								$date=$info["cdate"];
							if(empty($itime))
							{
								$itime=$info["crtime"];
								if(empty($itime))
									$itime="12:00:00";
							}
							$date=$date." ".$itime;
						}
					}
					$dosave=true;
				}
			}
			else if($status_s[$x]=="3")
			{
				$xdate=$info["orientation"];
				if(!empty($xdate))
				{
					$date=$info["idate"];
					$itime=$info["itime"];
					if(empty($date))
						$date=$info["orientation"];
					else
						$date=$date." ".$itime;
					$dosave=true;
				}
			}
			else if($status_s[$x]=="5")
			{
				$xdate=$info["orientation"];
				if($info["status"] !='2' && $info["status"] !='8' && $info["status"] !='9')
				{

					if(!empty($xdate))
					{
						$date=$xdate;
						$dosave=true;
					}
				}
			}
			else if($status_s[$x]=="7")
			{
				$xdate=$info["orientation_comp"];
				$date=$xdate;
				if(!empty($xdate))
					$dosave=true;
			}
			else if($status_s[$x]=="8")
			{
				if($info["status"]=='8')
				{
					$date=$info["orientation"];
					if(empty($date))
					{
						$date=$info["idate"];
						$itime=$info["itime"];
						if(empty($date))
						{
							$date=$info["date"];
							$itime=$info["crtime"];
						}
						if(empty($date))
						{
							$date=$info["cdate"];
							$itime="12:00:00";
						}
						if(!empty($date))
						{
							$date=$date." ".$itime;
							$dosave=true;
						}
					}
					if(!empty($date))
						$dosave=true;
				}
			}
			else if($status_s[$x]=="9")
			{
				if($info["status"]=='9')
				{
					$date=$info["orientation_comp"];
					if(empty($date))
						$date=$info["orientation"];
					if(empty($date))
					{
						$date=$info["idate"];
						$itime=$info["itime"];
						if(empty($date))
						{
							$date=$info["date"];
							$itime=$info["crtime"];
						}
						if(empty($date))
						{
							$date=$info["cdate"];
							$itime="12:00:00";
						}
						if(!empty($date))
						{
							$date=$date." ".$itime;
							$dosave=true;
						}
					}
					if(!empty($date))
						$dosave=true;
				}
			}
			else if($status_s[$x]=="10")
			{
				$xdate=$info["int_show_date"];
				if(!empty($xdate))
				{
					$date=$info["int_show_date"];
					$dosave=true;
				}
			}
			else if($status_s[$x]=="11")
			{
				$xdate=$info["orientation_show"];
				if(!empty($xdate))
				{
					$date=$xdate;
					$dosave=true;
				}
			}
			if($dosave)
				$test_check .=setTimeLineSet_test($info["id"],$status_s[$x],$xdate,$date);	
		}
		if(!empty($info["ccode"]))
		{
			$xdate="";
			$date="";
			$date=$info["orientation_comp"];
			if(empty($date))
				$date=$info["orientation"];
			if(!empty($date))
				$test_check .=setTimeLineSet_test($info["id"],17,$xdate,$date);
		}
	}
	return $test_check;
}
function setTimeLine($id,$status,$xdate)
{
	date_default_timezone_set('America/New_York');
	$today=date('Y-m-d');
	$time=date('H:i:s');
	$nowtime=$today." ".$time;
	if(!empty($id) && !empty($status))
	{
		$save=true;
		$qx="select * from rec_timeline where entryid='".$id."' and status='".$status."'";
		if($result=mysql_query($qx))
		{
			if(($num_rows=mysql_num_rows($result))>0)
				$save=false;
		}
		if($save)
		{
			if(empty($xdate))
				$qtime="insert ignore into rec_timeline(entryid,status,date)values('".$id."','".$status."','".$nowtime."')";
			else
				$qtime="insert ignore into rec_timeline(entryid,status,date,xdate)values('".$id."','".$status."','".$nowtime."','".$xdate."')";
			@mysql_query($qtime);
		}
	}
}
function removeXTimeLine($id,$status)
{
	if(!empty($id) && !empty($status))
	{
		$del=false;
		$qx="select * from rec_timeline where entryid='".$id."' and status='".$status."'";
		if($result=mysql_query($qx))
		{
			if(($num_rows=mysql_num_rows($result))>0)
				$del=true;
		}
		if($del)
		{
			$qtime="delete from rec_timeline where entryid='".$id."' and status='".$status."'";
			@mysql_query($qtime);
		}
	}
}
function setXTimeLine($id,$status,$nowtime,$xdate)
{
	date_default_timezone_set('America/New_York');
	if(!empty($id) && !empty($status) && !empty($nowtime))
	{
		$save=true;
		$qx="select * from rec_timeline where entryid='".$id."' and status='".$status."'";
		if($result=mysql_query($qx))
		{
			if(($num_rows=mysql_num_rows($result))>0)
				$save=false;
		}
		if($save)
		{
			if(empty($xdate))
				$qtime="insert ignore into rec_timeline(entryid,status,date)values('".$id."','".$status."','".$nowtime."')";
			else
				$qtime="insert ignore into rec_timeline(entryid,status,date,xdate)values('".$id."','".$status."','".$xdate."','".$xdate."')";
			@mysql_query($qtime);
		}
	}
}
function setXFTimeLine($id,$status,$nowtime,$xdate)
{
	date_default_timezone_set('America/New_York');
	if(!empty($id) && !empty($status) && !empty($nowtime))
	{
		$save=true;
		if(empty($xdate))
			$qtime="insert ignore into rec_timeline(entryid,status,date)values('".$id."','".$status."','".$nowtime."')";
		else
			$qtime="insert ignore into rec_timeline(entryid,status,date,xdate)values('".$id."','".$status."','".$nowtime."','".$xdate."')";
		@mysql_query($qtime);
	}
}
function setFTimeLine($id,$status,$xdate)
{
	date_default_timezone_set('America/New_York');
	$today=date('Y-m-d');
	$time=date('H:i:s');
	$nowtime=$today." ".$time;
	if(!empty($id) && !empty($status))
	{
		$save=true;
		if(empty($xdate))
			$qtime="insert ignore into rec_timeline(entryid,status,date)values('".$id."','".$status."','".$nowtime."')";
		else
			$qtime="insert ignore into rec_timeline(entryid,status,date,xdate)values('".$id."','".$status."','".$nowtime."','".$xdate."')";
		@mysql_query($qtime);
	}
}
function setTimeLineSet_test($id,$status,$xdate,$date)
{
	date_default_timezone_set('America/New_York');
	$today=date('Y-m-d');
	$checktest="";
	if(!empty($id) && !empty($status))
	{
		$save=true;
		$qx="select * from rec_timeline where entryid='".$id."' and status='".$status."'";
		if($result=mysql_query($qx))
		{
			if(($num_rows=mysql_num_rows($result))>0)
				$save=false;
		}
		if($save)
		{
			if(empty($xdate))
				$qtime="insert ignore into rec_timeline(entryid,status,date)values('".$id."','".$status."','".$date."')";
			else
				$qtime="insert ignore into rec_timeline(entryid,status,date,xdate)values('".$id."','".$status."','".$date."','".$xdate."')";
			//@mysql_query($qtime);
			$checktest=" [set ".$status." date:".$date." xdate.".$xdate."] ";
		}
	}
	return $checktest;
}
function setTimeLineSet($id,$status,$xdate,$date)
{
	date_default_timezone_set('America/New_York');
	$today=date('Y-m-d');
	$checktest="";
	if(!empty($id) && !empty($status))
	{
		$save=true;
		$qx="select * from rec_timeline where entryid='".$id."' and status='".$status."'";
		if($result=mysql_query($qx))
		{
			if(($num_rows=mysql_num_rows($result))>0)
				$save=false;
		}
		if($save)
		{
			if(empty($xdate))
				$qtime="insert ignore into rec_timeline(entryid,status,date)values('".$id."','".$status."','".$date."')";
			else
				$qtime="insert ignore into rec_timeline(entryid,status,date,xdate)values('".$id."','".$status."','".$date."','".$xdate."')";
			@mysql_query($qtime);
			//$checktest=" [set ".$status." date:".$date." xdate.".$xdate."] ";
		}
	}
	//return $checktest;
}
function getCStatus($id)
{
	$status="";
	$qx="select status from rec_entries where id='".$id."'";
	if($result=mysql_query($qx))
	{
		if(($numrows=mysql_num_rows($result))>0)
		{
			$info=mysql_fetch_assoc($result);
			$status=$info["status"];
		}
	}
	return $status;
}
function getLastDay($cdate)
{
	$lday="";
	if(!empty($cdate))
	{
		$cdatex=explode("-",$cdate);
		$month=$cdatex[1];
		$year=$cdatex[0];
		date_default_timezone_set('America/New_York');
		$lday=date('Y-m-d',strtotime('-1 second',strtotime('+1 month',strtotime($month.'/01/'.$year.' 00:00:00'))));
	}
	return $lday;
}
function getFirstDay($cdate)
{
	$fday="";
	if(!empty($cdate))
	{
		$cdatex=explode("-",$cdate);
		$month=$cdatex[1];
		$year=$cdatex[0];
		$fday=$year."-".$month."-01";
	}
	return $fday;
}
/*function showChooseMan($type)
{
	if($type=="5" || $type=="6")
		return true;
	else
		return false;
}*/
/*function checkManSel($type,$office)
{
	if(($type=="5" || $type=="6") && empty($office))
	{
		return true;
	}
	else
		return false;
}*/
/*function checkReportTo($type)
{
	if($type=="5")
		return true;
	else
		return false;
}*/
function getRunTotal_by_office($id,$task,$checkdate,$datestr,$datestrb)
{
	//$restric = "and status !='4' and status !='6'";
	$restric ="";
	$total=0;
	$recphone=array();
	$famphone=array();
	$status ="";
	if($task !="all" && $task !='hired' && $task !='set')
	{
		$status ="and status='".$task."'";
	}
	else if($task =="hired")
		$status ="and hired='yes'";
	if(!$checkdate)
		$datestrb="";
	$query="select * from rec_phones where office='".$id."' $datestrb";
	if($result = mysql_query($query))
	{
		if(($num_rows = mysql_num_rows($result))>0)
		{
			while($rows = mysql_fetch_array($result))
			{
				$recphone[]=fixOnSipPhone('family',$rows["tphone"]);
			}
		}
	}
	if($task !="all")
	{
		if(sizeof($recphone)>0)
		{
			for($i=0;$i<sizeof($recphone);$i++)
			{
				$qx="select * from rec_entries where (cphone='".clean(trim($recphone[$i]))."' or cphonex='".clean(trim($recphone[$i]))."') $status  $restric";
				if($rx=mysql_query($qx))
				{
					if(($numx=mysql_num_rows($rx))>0)
						$total++;
				}
			}
		}
	}
	else
		$total = sizeof($recphone);
	return $total;
}
function checkGQuery_phone($id,$task,$checkdate,$datestr)
{
	$sort="";
	$queryo="";
	if($task=="all")
		$sort="order by cdate desc";
	else if($task=="noagent")
		$sort="order by cdate desc";
	else if($task=="1")
		$sort="order by idate desc,itime desc";
	else if($task=="2")
		$sort="order by cdate desc";
	else if($task=="3")
		$sort="order by orientation desc";
	else if($task=="5")
		$sort="order by orientation desc";
	else if($task=="7")
		$sort="order by orientation_comp desc";
	else if($task=="9")
		$sort="order by idate desc";
	$restric = "and status !='4' and status !='6'";
	if($task !="all" && $task !='hired' && $task !='noagent')
		$status ="and status='".$task."'";
	else if($task =="hired")
		$status ="and hired='yes'";
	else if($task =="noagent")
		$status = "and (status ='2' or status='9' or status='8')";
	if(!$checkdate)
		$queryo = "select * from rec_entries where office='".$id."' $status $restric $sort";
	else
		$queryo = "select * from rec_entries where office='".$id."' $status $restric $datestr $sort";
	return $queryo;
}
function isValidPhone($task,$str,$date)
{
	$testphone=fixOnSipPhone("family",$str);
	if($task=="inter")
		$query = "select * from rec_entries where status='1' and (cphone ='".$testphone."' or cphonex='".$testphone."')";
	else if($task=="orien")
		$query = "select * from rec_entries where status='3' and (cphone ='".$testphone."' or cphonex='".$testphone."') ";
	else if($task=="hired")
		$query = "select * from rec_entries where (status='7' or status='5' or status='3') and (cphone ='".$testphone."' or cphonex='".$testphone."')";
	if($result = mysql_query($query))
	{
		if(($num_rows=mysql_num_rows($result))>0)
			return true;
		else
			return false;
	}
	else
		return false;
}
function fixOnSipPhone($task,$str)
{
	$newphone="";
	if($task=="onsip")
	{
		if(!empty($str))
		{
			$xstr=explode("-",$str);
			if(sizeof($xstr)>0)
				$newphone="1".$xstr[0]."".$xstr[1]."".$xstr[2];
			else
				$newphone=$str;
		}
		else
			$newphone=$str;
	}
	else
	{
		if(!empty($str))
		{
			//$xphone=array("phone1"=>substr($str,1,3),"phone2"=>substr($str,4,3),"phone3"=>substr($str,7));
			$xphone=array("phone1"=>substr($str,(strlen($str)-10),3),"phone2"=>substr($str,(strlen($str)-7),3),"phone3"=>substr($str,(strlen($str)-4),4));
			$xphoneb=$xphone["phone1"]."-".$xphone["phone2"]."-".$xphone["phone3"];
			if(!empty($xphoneb))
				$newphone=$xphoneb;
			else
				$newphone=$str;
		}
		else
			$newphone=$str;
	}
	return $newphone;
}
function getPhoneWeek()
{
	date_default_timezone_set('America/New_York');
	$yesterday=date("Y-m-d", mktime(0, 0, 0, date("m"),date("d")-1,date("Y")));
	$lsunday=date("Y-m-d", mktime(0, 0, 0, date("m"),date("d")-7,date("Y")));
	$str=array("date1"=>$yesterday,"date2"=>$lsunday);
	return $str;
}
function getCalDate($str)
{
	date_default_timezone_set('America/New_York');
	if($str=="Saturday")
	{
		return date("Y-m-d", mktime(0, 0, 0, date("m"),date("d")-6,date("Y")));
	}
	else if($str=="Sunday")
	{
		return date("Y-m-d", mktime(0, 0, 0, date("m"),date("d")-7,date("Y")));
	}
	else if($str=="Monday")
	{
		return date("Y-m-d", mktime(0, 0, 0, date("m"),date("d")-1,date("Y")));
	}
	else if($str=="Tuesday")
	{
		return date("Y-m-d", mktime(0, 0, 0, date("m"),date("d")-2,date("Y")));
	}
	else if($str=="Wednesday")
	{
		return date("Y-m-d", mktime(0, 0, 0, date("m"),date("d")-3,date("Y")));
	}
	else if($str=="Thursday")
	{
		return date("Y-m-d", mktime(0, 0, 0, date("m"),date("d")-4,date("Y")));
	}
	else
	{
		return date("Y-m-d", mktime(0, 0, 0, date("m"),date("d"),date("Y")));
	}
}
function getRunTotal($tday,$ttoday)
{
	$query = "select count(*) as total from rec_phones where date between '".$tday."' and '".$ttoday."'";
	if($result=mysql_query($query))
	{
		if(($num_rows = mysql_num_rows($result))>0)
		{
			$info=mysql_fetch_assoc($result);
			if($info["total"]>0)
				return $info["total"];
			else
				return "0";
		}
		else
			return "0";
	}
	else
		return "0";
}
function getRunTotalo($office,$tday,$ttoday)
{
	$query = "select count(*) as total from rec_phones where office='".$office."' and date between '".$tday."' and '".$ttoday."'";
	if($result=mysql_query($query))
	{
		if(($num_rows = mysql_num_rows($result))>0)
		{
			$info=mysql_fetch_assoc($result);
			if($info["total"]>0)
				return $info["total"];
			else
				return "0";
		}
		else
			return "0";
	}
	else
		return "0";
}
function ocomphone($str)
{
	$newphone="";
	if(!empty($str))
	{
		$str1=explode("-",$str);
		if(sizeof($str)>0)
			$newphone="1".$str1[0]."".$str1[1]."".$str1[2];
		else
			$newphone=$str;
	}
	else
		$newphone="";
	return $newphone;
}
function getOnSip($id)
{
	if(empty($id))
	{
		$query = "select * from rec_access where name='OnSip'";
		if($result = mysql_query($query))
		{
			if(($numrows = mysql_num_rows($result))>0)
			{
				$info = mysql_fetch_assoc($result);
				$xpass = base64_decode($info["password"]);
				if(!empty($info["username"]) && !empty($xpass))
				{
					$count=0;
					$str=getJunction("susere",$info["username"],"");
					if(!empty($str))
					{
						$xpoint = $str->Result[0]->UserRead[0];
						$xuserid=$xpoint->User[$count]->UserId;
						$xacctid = $xpoint->User[$count]->AccountId;
						$xorganid = $xpoint->User[$count]->OrganizationId;
						$xusername=$xpoint->User[$count]->Username;
						$xpassword=$xpoint->User[$count]->Password;
						$xstatus=$xpoint->User[$count]->Status;
						$xdomain = $xpoint->User[$count]->Domain;
						//$xcreated = fixdate_comps("onsip",$xpoint->User[$count]->Created);
						//$xmodi = fixdate_comps("onsip",$xpoint->User[$count]->Modified);
						//contactr information
						$xname = $xpoint->User[$count]->Contact->Name;
						$xorgan = $xpoint->User[$count]->Contact->Organization;
						//$xadd = $xpoint->User[$count]->Contact->Address;
						//$xcity = $xpoint->User[$count]->Contact->City;
						//$xstate = $xpoint->User[$count]->Contact->State;
						//$xzip = $xpoint->User[$count]->Contact->Zipcode;
						//$xcountry = $xpoint->User[$count]->Contact->Country;
						//$xcountryid = $xpoint->User[$count]->Contact->CountryId;
						$xphone = $xpoint->User[$count]->Contact->Phone;
						$xemail = $xpoint->User[$count]->Contact->Email;
						//role
						$xrole = $xpoint->User[$count]->Roles->Role->Name;
						$xusersip = $xuserid."&".$xusername."@".$xdomain;
						$onsip=array('userid'=>$xuserid,'acctid'=>$xacctid,'organid'=>$xorganid,'realuser'=>$info["username"],'username'=>$xusername,'password'=>$xpassword,'status'=>$xstatus,'domain'=>$xdomain,'useronsip'=>$xusersip,'name'=>$xname,'organ'=>$xorgan,'phone'=>$xphone,'email'=>$xemail,'role'=>$xrole);
						return $onsip;
					}
					else
						return "";
				}
				else
					return "";
			}
			else
				return "";
		}
		else
			return "";
	}
	else
	{
		$count=0;
		$str=getJunction("susere",$id,"");
		if(!empty($str))
		{
			$xpoint = $str->Result[0]->UserRead[0];
			$xuserid=$xpoint->User[$count]->UserId;
			$xacctid = $xpoint->User[$count]->AccountId;
			$xorganid = $xpoint->User[$count]->OrganizationId;
			$xusername=$xpoint->User[$count]->Username;
			$xpassword=$xpoint->User[$count]->Password;
			$xstatus=$xpoint->User[$count]->Status;
			$xdomain = $xpoint->User[$count]->Domain;
			//$xcreated = fixdate_comps("onsip",$xpoint->User[$count]->Created);
			//$xmodi = fixdate_comps("onsip",$xpoint->User[$count]->Modified);
			//contactr information
			$xname = $xpoint->User[$count]->Contact->Name;
			$xorgan = $xpoint->User[$count]->Contact->Organization;
			//$xadd = $xpoint->User[$count]->Contact->Address;
			//$xcity = $xpoint->User[$count]->Contact->City;
			//$xstate = $xpoint->User[$count]->Contact->State;
			//$xzip = $xpoint->User[$count]->Contact->Zipcode;
			//$xcountry = $xpoint->User[$count]->Contact->Country;
			//$xcountryid = $xpoint->User[$count]->Contact->CountryId;
			$xphone = $xpoint->User[$count]->Contact->Phone;
			$xemail = $xpoint->User[$count]->Contact->Email;
			//role
			$xrole = $xpoint->User[$count]->Roles->Role->Name;
			$xusersip = $xuserid."&".$xusername."@".$xdomain;
			$onsip=array('userid'=>$xuserid,'acctid'=>$xacctid,'organid'=>$xorganid,'realuser'=>$info["username"],'username'=>$xusername,'password'=>$xpassword,'status'=>$xstatus,'domain'=>$xdomain,'useronsip'=>$xusersip,'name'=>$xname,'organ'=>$xorgan,'phone'=>$xphone,'email'=>$xemail,'role'=>$xrole);
			return $onsip;
		}
		else
			return "";
	}
}
function getJunction($task,$sname,$sort)
{
	//if(!isset($_SESSION["portuser"]))
	//	return "";
	//$onsipx = getOnSip();
	//$username=$onsipx["realuser"];
	//$pass=base64_decode($onsipx["password"]);
	$username="dmajor@familyenergy.onsip.com";
	//$pass="kQySXr7prTwzKnWN";
	$pass="wjhS6ingABhVfC6K";
	if(empty($username) || empty($pass))
		return "";
	$acctid="";
	$sessionid="";
	$orgid="";
	$domain="";
	$apiurl="https://www.jnctn.com/restapi";
	$curl = curl_init();
	//create the session
	$post_data="Action=SessionCreate&Username=".$username."&Password=".$pass;
	curl_setopt($curl, CURLOPT_URL, $apiurl);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
	curl_setopt($curl, CURLOPT_POST, true);
	$result = curl_exec($curl);
	curl_close($curl);
	if($result)
	{
		$feed = simplexml_load_string($result); //grab the returned xml
		$acctid = $feed->Context[0]->Session[0]->UserId; //get the account id
		$sessionid = $feed->Context[0]->Session[0]->SessionId; //get the sessionid
		//get user information
		if($task=="country")
		{
			$getjunc = getJunctionScript($task,$sname);
			if($getjunc !="error" || $getjunc !="")
			{
				if(empty($sort))
					$sort = "&OrderBy=CountryName";
				$post_data= $getjunc.$sort."&SessionId=$sessionid";
				$curl = curl_init();
				curl_setopt($curl, CURLOPT_URL, $apiurl);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
				curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
				curl_setopt($curl, CURLOPT_POST, true);
				$results = curl_exec($curl);
				curl_close($curl);
				if($results)
				{
					$feeduserx = simplexml_load_string($results); //grab the xml
					return $feeduserx;
				}
			}
		}
		else
		{
		$curl = curl_init();
		$post_data="Action=UserRead&AccountId=$acctid&SessionId=$sessionid";
		curl_setopt($curl, CURLOPT_URL, $apiurl);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
		curl_setopt($curl, CURLOPT_POST, true);
		$resultuser = curl_exec($curl);
		curl_close($curl);
		if($resultuser)
		{
			$feeduser = simplexml_load_string($resultuser); //grab the xml
			$checkvalid = $feeduser->Context[0]->Action[0]->IsCompleted;//if it's valid
			if($checkvalid=="false")
		    {
				return "";
			}
			else
			{
			$orgid = $feeduser->Result[0]->UserRead[0]->User[0]->OrganizationId;//get the organication id
			$domain = $feeduser->Result[0]->UserRead[0]->User[0]->Domain; //get the domain information
			//request the organization authorization
			$post_data="Action=OrganizationRead&OrganizationId=$orgid&SessionId=$sessionid";
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $apiurl);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
			curl_setopt($curl, CURLOPT_POST, true);
			$resultorg = curl_exec($curl);
			curl_close($curl);
			//echo $resultorg;
			//the following will get me the information from a sip address
			if($resultorg)
			{
				if(!empty($task))
				{
					if(empty($sname))
						$sname=$orgid;
					$getjunc = getJunctionScript($task,$sname);
					if($getjunc !="error" || $getjunc !="")
					{
						$post_data= $getjunc.$sort."&SessionId=$sessionid";
						$curl = curl_init();
						curl_setopt($curl, CURLOPT_URL, $apiurl);
						curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
						curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
						curl_setopt($curl, CURLOPT_POST, true);
						$results = curl_exec($curl);
						curl_close($curl);
						if($results)
						{
							$feeduserx = simplexml_load_string($results); //grab the xml
							return $feeduserx;
						}
					}
				}
			}
			else
				return "";
			}
		}
		else
			return "";
		}
	}
	else
		return "";
}
function getJunctionScript($task,$xitem)
{
	$str="";
	if($task=="allusers")
	{
		$str="Action=UserBrowse&Limit=100&OrganizationId=$xitem";
	}
	else if($task=="suser")
	{
		$str="Action=UserRead&AuthUsername=$xitem";
	}
	else if($task=="addnew")
	{
		$str="Action=UserAdd&".$xitem;
	}
	else if($task=="susere")
	{
		$str="Action=UserRead&UserAddress=$xitem";
	}
	else if($task=="suserid")
	{
		$str="Action=UserRead&UserId=$xitem";
	}
	else if($task=="country")
	{
		$str="Action=CountryBrowse";
	}
	else if($task=="savem")
	{
		$str="Action=UserEditContact&".$xitem;
	}
	else if($task=="organread")
	{
		$str="Action=OrganizationBrowse&".$xitem;
	}
	else if($task=="delete")
	{
		$str="Action=UserDelete&".$xitem;
	}
	else if($task=="aliasadd")
	{
		$str="Action=UserAliasAdd&".$xitem;
	}
	else if($task=="browsealias")
	{
		$str="Action=UserAddressBrowse&".$xitem;
	}
	else if($task=="cdr")
	{
		$str="Action=CdrBrowse&".$xitem;
	}
	else if($task=="did")
	{
		$str="Action=DidBrowse&".$xitem;
	}
	else if($task=="gate")
	{
		$str="Action=GatewayUserRead&UserId=".$xitem;
	}
	else if($task=="phonelistx")
	{
		$str="Action=TelephoneNumberAddressBrowse&AccountId=".$xitem;
	}
	else if($task=="phonebrowse")
	{
		$str="Action=PhoneBrowse&AccountId=".$xitem;
	}
	else if($task=="gaddress")
	{
		$str="Action=GroupAddressRead&Address=".$xitem;
	}
	return $str;
}
function familyredirect()
{
	if(!isset($_SESSION["rec_user"]))
	{
		$_SESSION["recresult"]="ERROR: Unauthorized Entry Detected";
		header("location:home.php");
		exit;
	}
}
function checkFamily()
{
	if(!isset($_SESSION["rec_user"]))
		return false;
	else
		return true;
}
function getTypeName($id)
{
	if(!empty($id))
	{
		$query = "select * from task_admin_type where id='".$id."'";
		if($result = mysql_query($query))
		{
			if(($num_rows=mysql_num_rows($result))>0)
			{
				$info = mysql_fetch_assoc($result);
				return stripslashes($info["name"]);
			}
			else
				return $id;
		}
		else
			return $id;
	}
	return "N/A";
}
function getExtraName($id)
{
	if(!empty($id))
	{
		$query = "select * from task_category where id='".$id."'";
		if($result = mysql_query($query))
		{
			if(($num_rows=mysql_num_rows($result))>0)
			{
				$info = mysql_fetch_assoc($result);
				return stripslashes($info["name"]);
			}
			else
				return $id;
		}
		else
			return $id;
	}
	return "N/A";
}
function getExpReason($str)
{
	if(!empty($str))
	{
		$query = "select * from rec_exp_reason where id='".$str."'";
		if($result = mysql_query($query))
		{
			if(($num_rows=mysql_num_rows($result))>0)
			{
				$info = mysql_fetch_assoc($result);
				return stripslashes($info["name"]);
			}
			else
				return $str;
		}
		else
			return $str;
	}
	return "No Reason";	
}
function getNDays()
{
	return "1";
}
function getDelOr()
{
	return " img=NULL, ori_show=NULL,ori_show_info=NULL, ori_comp=NULL,ori_comp_info=NULL,orientation=NULL,orientation_office=NULL,orientation_show=NULL,orientation_comp=NULL,observation=NULL,observation_office=NULL,observation_show=NULL,observationer=NULL,observation_comp=NULL,observation_comps=NULL,observation_note=NULL,eornotx='yes',textornotx='yes',ccode_aval=NULL,ccode_info=NULL,ccode=NULL,report_to=NULL,trained=NULL ";
}
function getDelOr_show()
{
	return " img=NULL, ori_show=NULL,ori_show_info=NULL, ori_comp=NULL,ori_comp_info=NULL,orientation_show=NULL,orientation_comp=NULL,observation=NULL,observation_office=NULL,observation_show=NULL,observationer=NULL,observation_comp=NULL,observation_comps=NULL,observation_note=NULL,ccode_aval=NULL,ccode_info=NULL,ccode=NULL,report_to=NULL,trained=NULL ";
}
function getDelQuery()
{
	return " img=NULL, int_show_date=NULL, hired=NULL,interview=NULL,inter_status=NULL,interviewer=NULL,interview_note=NULL,ori_show=NULL,ori_show_info=NULL, ori_comp=NULL,ori_comp_info=NULL,orientation=NULL,orientation_office=NULL,orientation_show=NULL,orientation_comp=NULL,observation=NULL,observation_office=NULL,observation_show=NULL,observationer=NULL,observation_comp=NULL,observation_comps=NULL,observation_note=NULL,ccode_aval=NULL,ccode_info=NULL,ccode=NULL,report_to=NULL,trained=NULL ";
}
function getDelQuerys()
{
	return " status='1', int_show=NULL,int_show_info=NULL,img=NULL, int_show_date=NULL, hired=NULL,interview=NULL,inter_status=NULL,interviewer=NULL,interview_note=NULL, ori_show=NULL,ori_show_info=NULL, ori_comp=NULL,ori_comp_info=NULL, orientation=NULL,orientation_office=NULL,orientation_show=NULL,orientation_comp=NULL,observation=NULL,observation_office=NULL,observation_show=NULL,observationer=NULL,observation_comp=NULL,observation_comps=NULL,observation_note=NULL,folstatus='1',folcome=NULL,folupdated_by=NULL,folupdated_date=NULL,compdate=NULL,compnote=NULL,foldate=NULL,folnote=NULL,ccode_aval=NULL,ccode_info=NULL,ccode=NULL,report_to=NULL,trained=NULL ";
}
function getdatename($str)
{
	$datename="";
	if(!empty($str))
	{
		if(base64_encode($str)==base64_encode("idate"))
			$datename ="int Date";
		else if(base64_encode($str)==base64_encode("cdate"))
			$datename = "Date Called";
		else if(base64_encode($str)==base64_encode("int_show_date"))
			$datename = "Int Show";
		else if(base64_encode($str)==base64_encode("orientation"))
			$datename = "Orientation";
		else if(base64_encode($str)==base64_encode("orientation_comp"))
			$datename = "Orientation Cmp";
		else
			$datename = "Int Date";
	}
	else
		$datename = "Int Date";
	return $datename;
}
function getdatename_b($str)
{
	
	$datename=array();
	if(!empty($str))
	{
		if($str=="int")
			$datename[]=array('name'=>"Interview",'namex'=>'idate');
		else if($str=="intcancel")
			$datename[]=array('name'=>"Interview",'namex'=>'idate');
		else if($str=="intshow")
			$datename[]=array('name'=>"Attendance",'namex'=>'int_show_date');
		else if($str=="intnoshow")
			$datename[]=array('name'=>"Interview",'namex'=>'idate');
		else if($str=="orinoshow")
			$datename[]=array('name'=>"Orientation",'namex'=>'orientation');
		else if($str=="nothired")
			$datename[]=array('name'=>"Interview",'namex'=>'idate');
		else if($str=="hired")
			$datename[]=array('name'=>"Completed",'namex'=>'orientation_comp');
		else if($str=="noint")
			$datename[]=array('name'=>"Interview",'namex'=>'idate');
		else if($str=="oset")
			$datename[]=array('name'=>"Orientation",'namex'=>'orientation');
		else if($str=="ocomp")
			$datename[]=array('name'=>"Completed",'namex'=>'orientation_comp');
		else if($str=="orishow")
			$datename[]=array('name'=>"Attendance",'namex'=>'orientation_show');
		else if($str=="oincomp")
			$datename[]=array('name'=>"Orientation",'namex'=>'orientation');
		else
		{
			//$datename[]=array('name'=>"Interview",'namex'=>'idate');
			$datename[]=array('name'=>"Date Status",'namex'=>'x');
		}
	}
	else
	{
		//$datename[]=array('name'=>"Interview",'namex'=>'idate');
		$datename[]=array('name'=>"Date Status",'namex'=>'x');
	}
	return $datename;
}
function get_sdvar($id,$status)
{
	$datavar='';
	if(!empty($id))
	{
		$query = "select * from rec_timeline where entryid='".$id."' and status='".$status."' order by date desc limit 1";
		if($result = mysql_query($query))
		{
			if(($num_rows=mysql_num_rows($result))>0)
				$timeline=mysql_fetch_assoc($result);
		}
		$query = "select * from rec_entries where id='".$id."'";
		if($result = mysql_query($query))
		{
			if(($num_rows=mysql_num_rows($result))>0)
			{
				$info = mysql_fetch_assoc($result);
				switch($status)
				{
					case '1':
						$datavar=$info["idate"];
					break;
					case '2':
						$datavar=$timeline["date"];
					break;
					case '3':
						$datavar=$info["orientation"];
					break;
					case '4':
						$datavar=$info["observation"];
					break;
					case '5':
						$datavar=$timeline["date"];
					break;
					case '6':
						$datavar=$info["observation_comps"];
					break;
					case '7':
						$datavar=$info["orientation_comp"];
					break;
					case '8':
						$datavar=$timeline["date"];
					break;
					case '9':
						$datavar=$timeline["date"];
					break;
					case '18':
						$datavar=$timeline["date"];
					break;
					case '19':
						$datavar=$timeline["date"];
					break;
					case '20':
						$datavar=$timeline["date"];
					break;
					default:
        				$datavar=$info["idate"];
   					break;
				}
			}
		}
	}
	return $datavar;
}
function getOfficeSdvar($str)
{
	if($str=="orientation" || $str=="orientation_show" || $str=="orientation_comp")
		$xoffice_x="orientation_office";
	else 
		$xoffice_x="office";
	return $xoffice_x;
}
function allParam()
{
	$vars="";
	$count=0;
	foreach($_GET as $key=>$value)
	{
		if($key !='msort' || $key !='msortx')
		{
			if($count==0)
				$vars .= $key.'='.$value;
			else
				$vars .= "&".$key.'='.$value;
			$count++;
		}
	}
	return $vars;
}
function getMsortFix($task,$str,$sort)
{
	$sortx=base64_encode("asc");
	if(!empty($task) && !empty($str) && !empty($sort))
	{
		if(strtolower(trim($task))==strtolower(trim($str)))
		{
			if($sort=="desc")
				$sortx=base64_encode("asc");
			else
				$sortx=base64_encode("desc");
		}
	}
	return $sortx;
}
function setSelected($str,$task)
{
	$selec="";
	if(trim(strtolower($str))==trim(strtolower($task)))
		$selec="selected='selected'";
	return $selec;
}
function getscat($str)
{
	if(!empty($str))
	{
		if($str=="all")
			return "";
		else if($str=="intshow")
			return "int_show='yes'";
		else if($str=="orishow")
			return "ori_show='yes'";
		else if($str=="int")
			return "status='1'";
		else if($str=="intnoshow")
			return "status='8'";
		else if($str=="intcancel")
			return "status='20'";
		else if($str=="hired")
			return "status='5'";
		else if($str=="nothired")
			return "status='2'";
		else if($str=="notint")
			return "status='9'";
		else if($str=="oset")
			return "status='3'";
		else if($str=="orinoshow")
			return "status='18'";
		else if($str=="ocomp")
			return "ori_comp='yes'";
		else if($str=="oincomp")
			return "status='19'";
		else
			return "";
	}
	return "";
}
function checkPassDate($id,$date)
{
	date_default_timezone_set('America/New_York');
	$today = date("Y-m-d H:i:s");
	$datetime1 = new DateTime($today);
	$newdatetime= new DateTime($date);
	if(!empty($id) && !empty($date))
	{
		$queryr = "select * from rec_entries where id='".$id."'";
		if($resultr = mysql_query($queryr))
		{
			if(($num_rowsr = mysql_num_rows($resultr))>0)
			{
				$recinfo = mysql_fetch_assoc($resultr);
				$idate = new DateTime($recinfo["idate"]." ".$recinfo["itime"]);
				if($idate > $datetime1)
				{
					if($idate > $newdatetime)
						return true;
					else
						return false;
				}
				else
					return false;
			}
		}
	}
	return true;
}
function checkSame($task,$id,$str)
{
	$check=false;
	if(!empty($task) && !empty($str) && !empty($id))
	{
		$queryr = "select * from rec_entries where id='".$id."'";
		if($resultr = mysql_query($queryr))
		{
			if(($num_rowsr = mysql_num_rows($resultr))>0)
			{
				$recinfo = mysql_fetch_assoc($resultr);
				if($task=="idate" && $str != $recinfo["idate"])
					$check=true;
				else if($task=="itime" && $str !=$recinfo["itime"])
					$check=true;
				else if($task=="idatetime" && $str !=$recinfo["idate"]." ".$recinfo["itime"])
					$check=true;
				else if($task=="odatetime" && $str !=$recinfo["orientation"])
					$check=true;
				else if($task=="ocdatetime" && $str !=$recinfo["orientation_comp"])
					$check=true;
				else if($task=="ioffice" && $str !=$recinfo["office"])
					$check=true;
				else if($task=="ooffice" && $str !=$recinfo["orientation_office"])
					$check=true;
				else if($task=="office" && ($str != "na" && ($recinfo["office"] != $str)))
					$check=true;
				else if($task=="email" && $recinfo["email"] != $str)
					$check=true;
				else if($task=="phone" && $recinfo["cphone"] != $str)
					$check=true;
				else
					$check=false;
			}
		}
	}
	return $check;
}
function sendSMS($phone,$text)
{
	$user="kakashi807";
    $password="family1";
    $api_id="3359877";
    $baseurl ="http://api.clickatell.com";
    $text = urlencode($text);
    $to = "1".$phone;
/*    // auth call
   	 $url = "$baseurl/http/auth?user=$user&password=$password&api_id=$api_id";
    // do auth call
    $ret = file($url);
    // explode our response. return string is on first line of the data returned
    $sess = explode(":",$ret[0]);
    if($sess[0]=="OK") 
	{
        $sess_id = trim($sess[1]); // remove any whitespace
       	// $url = "$baseurl/http/sendmsg?session_id=$sess_id&to=$to&text=$text&from=13474747952&mo=1";
		   $url = "$baseurl/http/sendmsg?session_id=$sess_id&to=$to&text=$text&from=18479735787&mo=1";
//$url="http://$baseurl/http/sendmsg?user=$user&password=$password&api_id=$api_id&from=13474747952&mo=1&to=$to&text=$text";
        // do sendmsg call
        $ret = file($url);
        $send = explode(":",$ret[0]);
        if ($send[0] == "ID") {
            return $send[1];
        } else {
            return "";
        }
    } else {
       return "fail";
    }*/
	$url = "$baseurl/http/sendmsg?user=$user&password=$password&api_id=$api_id&MO=1&from=18479735787&to=$to&text=$text";
	$ret = file($url);
	$send = explode(":",$ret[0]);
	if ($send[0]=="ID")
		return $send[1];
	else
		return "";
}
function sendSMSm($phone,$text)
{
	$user = "kakashi807";
    $password = "family1";
    $api_id = "3359877";
    $baseurl ="http://api.clickatell.com";
    $text = urlencode($text);
    $to = $phone;
    // auth call
   /*	 $url = "$baseurl/http/auth?user=$user&password=$password&api_id=$api_id";
    // do auth call
    $ret = file($url);
    // explode our response. return string is on first line of the data returned
    $sess = explode(":",$ret[0]);
    if ($sess[0] == "OK") 
	{
        $sess_id = trim($sess[1]); // remove any whitespace
       	//$url = "$baseurl/http/sendmsg?session_id=$sess_id&to=$to&text=$text&from=13474747952&mo=1";
		  $url = "$baseurl/http/sendmsg?session_id=$sess_id&to=$to&text=$text&from=18479735787&mo=1";
//$url="http://$baseurl/http/sendmsg?user=$user&password=$password&api_id=$api_id&from=13474747952&mo=1&to=$to&text=$text";
        // do sendmsg call
        $ret = file($url);
        $send = explode(":",$ret[0]);
        if ($send[0] == "ID") {
            return $send[1];
        } else {
            return "";
        }
    } else {
       return "fail";
    }*/
	$url = "$baseurl/http/sendmsg?user=$user&password=$password&api_id=$api_id&MO=1&from=18479735787&to=$to&text=$text";
	$ret = file($url);
	$send = explode(":",$ret[0]);
	if ($send[0]=="ID")
		return $send[1];
	else
		return "";
}
function showsetbutton($id)
{
	$query = "select * from rec_entries where id='".$id."'";
	if($result = mysql_query($query))
	{
		if(($numrows = mysql_num_rows($result))>0)
		{
			$info = mysql_fetch_assoc($result);
			if($info["int_show"] =="no" || $info["folcome"]=="no")
				return false;
			else
				return true;
		}
		else
			return true;
	}
	return true;
}
function checkGQuery($id,$task,$checkdate,$datestr)
{
	$sort="";
	$queryo="";
	if($task=="all")
		$sort="order by cdate desc";
	else if($task=="noagent")
		$sort="order by cdate desc";
	else if($task=="1")
		$sort="order by idate desc,itime desc";
	else if($task=="2")
		$sort="order by cdate desc";
	else if($task=="3")
		$sort="order by orientation desc";
	else if($task=="5")
		$sort="order by orientation desc";
	else if($task=="7")
		$sort="order by orientation_comp desc";
	else if($task=="9")
		$sort="order by idate desc";
	$restric = "and status !='4' and status !='6'";
	if($task !="all" && $task !='hired' && $task !='noagent')
		$status ="and status='".$task."'";
	else if($task =="hired")
		$status ="and hired='yes'";
	else if($task =="noagent")
		$status = "and (status ='2' or status='9' or status='8')";
	if(!$checkdate)
		$queryo = "select * from rec_entries where office='".$id."' $status $restric $sort";
	else
		$queryo = "select * from rec_entries where office='".$id."' $status $restric $datestr $sort";
	return $queryo;
}
function checkGTotal($id,$task,$checkdate,$datestr)
{
	$restric = "and status !='4' and status !='6'";
	if($task !="all" && $task !='hired')
		$status ="and status='".$task."'";
	else if($task =="hired")
		$status ="and hired='yes'";
	if(!$checkdate)
		$queryo = "select count(*) as counter from rec_entries where office='".$id."' $status  $restric";
	else
		$queryo="select count(*) as counter from rec_entries where office='".$id."' $status $restric $datestr";
	if($resulto = mysql_query($queryo))
	{
		if(($numrowso = mysql_num_rows($resulto))>0)
		{
			$infoo = mysql_fetch_assoc($resulto);
			return $infoo["counter"];
			//return $queryo;
		}
		else
			return "0";
	}
	return "0";
}
function checkNA($str)
{
	if(!empty($str) && $str !="" && $str !='0')
		return $str;
	else
		return "N/A";
}
function pView($type)
{
	if($type !='1' && $type !='2' && $type !='4')
		return false;
	return true;
}
function pViewb($type)
{
	if($type =='1' || $type =='2')
		return true;
	return false;
}
function getIP()
{
	 return $_SERVER['REMOTE_ADDR'];
}
function monthName($month_int)
{
	$month_int = (int)$month_int;
	$timestamp = mktime(0, 0, 0, $month_int);
	return date("F", $timestamp);
}
function fixtomilhour($str)
{
	date_default_timezone_set('UTC');
	if(!empty($str))
	{
		$time = date("H:i:s", strtotime($str));
		if(!empty($time))
			return $time;
		else
			return "";
	}
	else
	 	return "";
}
function getSourceName($str)
{
	if(!empty($str))
	{
		$query = "select * from rec_source where id='".$str."'";
		if($result = mysql_query($query))
		{
			if(($num_rows=mysql_num_rows($result))>0)
			{
				$info = mysql_fetch_assoc($result);
				return stripslashes($info["name"]);
			}
			else
				return $str;
		}
		else
			return $str;
	}
	return "N/A";
}
function getSourceInfo($str)
{
	if(!empty($str))
	{
		$strx = explode(" || ",$str);
		if(sizeof($strx)>0)
		{
			return stripslashes(trim($strx[0]));
		}
		else
			return $str;
	}
	return "N/A";
}
function fixnormhour($str)
{
	if(!empty($str))
	{
		$time = fixdate_comps("h",$str);
		if(!empty($time))
			return $time;
		else
			return "";
	}
	else
	 	return "";
}
function fixnum($str)
{
	if(!empty($str))
	{
		$numx = explode("-",$str);
		if(!empty($numx) && sizeof($numx)>2)
			return $numx;
		else if(!empty($numx) && sizeof($numx)>1)
		{
			$numa = substr($numx[1],0,3);
			$numb = substr($numx[1],3);
			$numc = array();
			$numc[]=$numx[0];
			$numc[]=$numa;
			$numc[]=$numb;
			return $numc;
		}
		else
		{
			$numa = substr($str,0,3);
			$numb = substr($str,3,3);
			$numc = substr($str,6);
			$numd = array();
			$numd[]=$numa;
			$numd[]=$numb;
			$numd[]=$numc;
			return $numd;
		}
	}
	return "";
}
function getGEO($address){
	// Initialize delay in geocode speed
	$delay = 0;
	$base_url = "http://" . MAPS_HOST . "/maps/geo?output=xml" . "&key=" . KEY;
	// Iterate through the rows, geocoding each address
  $geocode_pending = true;
  while ($geocode_pending) {
    $request_url = $base_url . "&q=" . urlencode($address);
   $xml = simplexml_load_file($request_url) or die("url not loading");
    $status = $xml->Response->Status->code;
    if (strcmp($status, "200") == 0) {
      // Successful geocode
      $geocode_pending = false;
      $coordinates = $xml->Response->Placemark->Point->coordinates;
      $coordinatesSplit = explode(",", $coordinates);
      // Format: Longitude, Latitude, Altitude
      $lat = $coordinatesSplit[1];
      $lng = $coordinatesSplit[0];
	  $values = array('lat'=>$lat,'lng'=>$lng);
 	  return $values;
    } else if (strcmp($status, "620") == 0) {
      // sent geocodes too fast
      $delay += 100000;
    } 
	else
	{
      // failure to geocode
	  $geocode_pending = false;
	 	$values = array('lat'=>"",'lng'=>"");
  		return $values;
    }
    usleep($delay);
  }
}
function clean($str) 
{
	$str = trim($str);
	if(get_magic_quotes_gpc()) 
	{
		$str = stripslashes($str);
	}
	return mysql_real_escape_string($str);
}
function adminlogin_exp()
{
	if(!isset($_SESSION["rec_user"]))
		return false;
	return true;
}
function adminstatus($value)
{
	if($value !="1")
	{
		$_SESSION["loginresult"]="Your Account is currently Blocked";
		header("location:index.php");
		exit;
	}
}
function adminPrev($str)
{
	if($str =="1" || $str=="5")
		return true;
	return false;
}
function adminMain($str)
{
	if($str !="5")
	{
		unset($_SESSION["rec_user"]);
		$_SESSION["loginresult"]="ACCESS DENIED: The Site is Under Maintenance";
		header("location:index.php");
		exit;
	}
}
function adminlogin()
{
	//$_SESSION["loginresult"]="System Under Maintenance, Please Come Back Later";
	//header("location:index.php");
	//exit;

	if(!isset($_SESSION["rec_user"]) && !isset($_SESSION["brownuser"]))
	{
		//$_SESSION["loginresult"]="Illegal Access";
		unset($_SESSION["rec_user"]);
		unset($_SESSION["brownuser"]);
		$_SESSION["loginresult"]="Please Login To Continue";
		header("location:index.php");
		exit;
	}
	else
	{
		if(isset($_SESSION["rec_user"]))
			$user=$_SESSION["rec_user"];
		else if(isset($_SESSION["brownuser"]))
			$user=$_SESSION["brownuser"];
		//adminMain($user["type"]);
		$query = "select * from task_users where id='".$user["id"]."'";
		if($result = mysql_query($query))
		{
			if(($num_rows =mysql_num_rows($result))>0)
			{
				$checkuser = mysql_fetch_assoc($result);
				if($checkuser["status"] !="1")
				{
					$_SESSION["loginresult"]="Your Account is blocked or Cancelled";
					unset($_SESSION["rec_user"]);
					unset($_SESSION["brownuser"]);
					header("location:index.php");
					exit;
				}
			}
			else
			{
				$_SESSION["loginresult"]="ERROR: Invalid Entry";
				unset($_SESSION["rec_user"]);
				unset($_SESSION["brownuser"]);
				header("location:index.php");
				exit;
			}
		}
		else
		{
			$_SESSION["loginresult"]="ERROR: Invalid Entry";
			unset($_SESSION["rec_user"]);
			unset($_SESSION["brownuser"]);
			header("location:index.php");
			exit;
		}
	}
}
function convertUS($number)

{
	return number_format($number, 2, '.', ',');
}
function fixdate($str)
{
	if(!empty($str))
	{
		$exp = explode("-",$str);
		if(sizeof($exp)>2)
		{
			$y = $exp[0];
			$m = $exp[1];
			$d = $exp[2];
			if($m<10)
				$m = "0".$m;
			if($d<10)
				$d = "0".$d;
			$newdate = $y."-".$m."-".$d;
			return $newdate;
		}
	}
	return "";
}
function fixdate_slash($str)
{
	if(!empty($str))
	{
		$exp = explode("/",$str);
		if(sizeof($exp)>2)
		{
			$y = $exp[2];
			$m = $exp[1];
			$d = $exp[0];
			$newdate = $y."-".$m."-".$d;
			return $newdate;
		}
	}
	return "";
}
function fixdateb($str)
{
	if(!empty($str))
	{
		$exp = explode(" ",$str);
		if(sizeof($exp)>1)
		{
			return $exp[0];
		}
	}
	return "";
}
function fixdate_comps($task,$value)
{
	date_default_timezone_set('UTC');
	$newtime ="";
	$double = false;
	if($task=="onsip" || $task=="onsip_mildate")
	{
		$xdate="";
		$xtime="";
		$xsplit = explode("T",$value);
		if(sizeof($xsplit)>1)
		{
			$xdate=$xsplit[0];
			$xtimex = explode("-",$xsplit[1]);
			if(sizeof($xtimex)>1)
				$xtime=$xtimex[0];
			else
				$xtime=$xtimex;
			$value=$xdate." ".$xtime;
		}
	}
	$valuexx = explode(" ",$value);
	if(sizeof($valuexx)>1)
		$doublex=true;
	$value=strtotime($value);
	$valuex=explode(" ",$value);
	if(sizeof($valuex)>1)
		$double=true;
	if(!empty($value) && !empty($task))
	{
		if($task=="h")
			$newtime =date("g:i a",$value);
		else if($task=="d")
			$newtime=date("F j, Y",$value);
		else if($task=="mildate")
			$newtime=date("Y-m-d",$value);
		else if($task=="y")
			$newtime=date("Y",$value);
		else if($task=="invdate_s")
			$newtime=date("m/d/Y",$value);
		else if($task=="m_text")
			$newtime=date( "F",$value);
		else if($task=="m_text_s")
			$newtime=date( "M",$value);
		else if($task=="m_text_l")
			$newtime=date( "m",$value);
		else if($task=="m_num")
			$newtime=date("n",$value);
		else if($task=="hx")
			$newtime=date("h:i",$value).":00";
		else if($task=="Hx")
			$newtime=date("H:i",$value).":00";
		else if($task=="onsip")
			$newtime=date("m/d/Y g:i a",$value);
		else if($task=="onsips")
			$newtime=date("m/d/Y g:ia",$value);
		else if($task=="onsip_s")
			$newtime=date("m/d/Y",$value);
		else if($task=="onsip_mildate")
			$newtime=date("Y-m-d H:i:s",$value);
		else if($task=="invdate")
		{
			if($doublex)
				$newtime=date("d/m/Y g:i:a",$value);
			else
				$newtime=date("d/m/Y",$value);
		}
		else if($task=="all")
			$newtime=date("F j, Y  g:i a",$value);
		else
			$newtime="";
	}
	else
		$newtime="";
	return $newtime;
}
function fixdate_comp($value)
{
	$date="";
	$ampm="am";
	if(!empty($value))
	{
		$exp = explode(" ",$value);
		if(sizeof($exp)>1)
		{
			$date = $exp[0];
			$exptime = explode(":",$exp[1]);
			if($exptime[0]>11)
			{
				if($exptime[0]!="12")
					$h = $exptime[0] - 12;
				else
					$h=$exptime[0];
				$ampm = "pm";
			}
			else
				$h = $exptime[0];
			$date .= " ".$h.":".$exptime[1]."".$ampm;
			return $date;
		}
		else
			$date ="";
	}
	else
		$date="";
	return $date;
}
function getRecStatus($value)
{
	$query = "select * from rec_status where id='$value'";
	if($result = mysql_query($query))
	{
		if(($num_rows = mysql_num_rows($result))>0)
		{
			$row = mysql_fetch_assoc($result);
			return stripslashes($row["name"]);
		}
		else
			return "N/A";
	}
	else
		return "N/A";
}
function getFolStatus($value)
{
	$query = "select * from rec_followup where id='$value'";
	if($result = mysql_query($query))
	{
		if(($num_rows = mysql_num_rows($result))>0)
		{
			$row = mysql_fetch_assoc($result);
			return stripslashes($row["name"]);
		}
		else
			return "N/A";
	}
	else
		return "N/A";
}
function getFolStatus_View($value)
{
	$query = "select * from rec_followup where id='$value'";
	if($result = mysql_query($query))
	{
		if(($num_rows = mysql_num_rows($result))>0)
		{
			$row = mysql_fetch_assoc($result);
			return "&nbsp;<span style='font-size:12pt;color:#666;'>(".stripslashes($row["name"]).")</span>";
		}
		else
			return "";
	}
	else
		return "";
}
function getStatus($value)
{
	$query = "select * from task_users_status where id='$value'";
	if($result = mysql_query($query))
	{
		if(($num_rows = mysql_num_rows($result))>0)
		{
			$row = mysql_fetch_assoc($result);
			return stripslashes($row["name"]);
		}
		else
			return "N/A";
	}
	else
		return "N/A";
}
function getTaskStatus($value)
{
	$query = "select * from task_status where id='$value'";
	if($result = mysql_query($query))
	{
		if(($num_rows = mysql_num_rows($result))>0)
		{
			$row = mysql_fetch_assoc($result);
			return stripslashes($row["name"]);
		}
		else
			return "N/A";
	}
	else
		return "N/A";
}
function getCoords($value)
{
	$coordss = $value;
	if(!empty($coordss))
	{
		$coorda=explode(",",$coordss);
		$cod = array("lat"=>$coorda[1],"lng"=>$coorda[0]);
	}
	else
		$cod=array("lat"=>"","lng"=>"");
	return $cod;
}
function getName($id)
{
	$namea="";
	$query = "select * from task_users where id='".$id."'";
	if($result = mysql_query($query))
	{
		if(($num_rows = mysql_num_rows($result))>0)
		{
			$rows = mysql_fetch_assoc($result);
			$namea= stripslashes($rows["name"]);
		}
		else
			$namea="N/A";
	}
	else
		$namea = "N/A";
	return $namea;
}
function getUserName($id)
{
	$namea="";
	$query = "select * from task_users where id='".$id."'";
	if($result = mysql_query($query))
	{
		if(($num_rows = mysql_num_rows($result))>0)
		{
			$rows = mysql_fetch_assoc($result);
			$namea= stripslashes($rows["username"]);
		}
		else
			$namea="N/A";
	}
	else
		$namea = "N/A";
	return $namea;
}
function rLongText($str)
{
	if(!empty($str))
	{
		if(strlen($str)>30)
		{
			$str = substr($str,0,30);
			$str = $str."....";
		}
		else
			return $str;
	}
	return $str;
}
function rLongTextb($str)
{
	if(!empty($str))
	{
		if(strlen($str)>30)
		{
			$str = substr($str,0,50);
			$str = $str."....";
		}
		else
			return $str;
	}
	return $str;
}
function rLongTextc($str)
{
	if(!empty($str))
	{
		if(strlen($str)>6)
		{
			$str = substr($str,0,6);
			$str = $str."...";
		}
		else
			return $str;
	}
	return $str;
}
function rLongTextd($str)
{
	if(!empty($str))
	{
		if(strlen($str)>17)
		{
			$str = substr($str,0,17);
			$str = $str."...";
		}
		else
			return $str;
	}
	return $str;
}
function getOfficeName($id)
{
	$query = "select * from rec_office where id='".$id."'";
	if($result = mysql_query($query))
	{
		if(($num_rows = mysql_num_rows($result))>0)
		{
			$row = mysql_fetch_assoc($result);
			return stripslashes($row["name"]);
		}
		else
			return "";
	}
	return "";
}
function getOfficeName_s($id)
{
	$query = "select * from rec_office where id='".$id."'";
	if($result = mysql_query($query))
	{
		if(($num_rows = mysql_num_rows($result))>0)
		{
			$row = mysql_fetch_assoc($result);
			$x=explode(" ",stripslashes($row["name"]));
			if(sizeof($x)>1)
				return $x[0];
			else
				return stripslashes($row["name"]);
		}
		else
			return "";
	}
	return "";
}
function getHost()
{
	return "http://www.familyenergymarketing.com/rec/";
}
function sendEmail($email_to,$title,$messages)
{
	//$host = "http://www.familyenergymarketing.com/portal";
	$host = getHost();
	if(empty($email_to))
		return false;
	$message = "<table width='100%' border='0' cellspacing='0' cellpadding='0'><tr><td align='center'><table width='800' border='0' cellspacing='0' cellpadding='0'><tr><td><img src='".$host."images/email1.jpg' width='800' height='210' alt='email_t' style='display:block;'/></td></tr><tr><td background='".$host."images/email2.jpg'><br/><br/>
<table width='100%' border='0' cellspacing='0' cellpadding='0'><tr><td width='7%'>&nbsp;</td><td width='84%' align='left' valign='top'>";
	$message .=$messages;
	$message .="</td><td width='9%'>&nbsp;</td></tr></table><br/></td></tr><tr><td><img src='".$host."images/email3.jpg' width='798.6' height='143' style='display:block;'/></td></tr></table></td></tr></table>";
	$headers  = 'MIME-Version: 1.0'."\r\n";
	$headers .='Content-type: text/html; charset=iso-8859-1' . "\r\n";
	$headers .="From: FamilyEnergy Team<no-reply@yourfamilyenergy.com>\r\n"."X-Mailer: PHP/".phpversion();
	if($result = mail($email_to,$title, $message,$headers))
		return true;
	else
		return false;
}
function verify_group($task,$id)
{
	$queryq = "select * from task_group where userid='".$id."' and task='".$task."'";
	if($resultq = mysql_query($queryq))
	{
		if(($num_rowsq = mysql_num_rows($resultq))>0)
			return true;
		else
			return false;
	}
	return false;
}
function verify_group_valid($task,$id)
{
	$user = $_SESSION["rec_user"];
	$queryq = "select * from task_group where userid='".$id."' and view_update='no' and task='".$task."'";
	if($resultq = mysql_query($queryq))
	{
		if(($num_rowsq = mysql_num_rows($resultq))>0)
			return true;
		else
			return false;
	}
	return false;
}
function verify_group_valid_task()
{
	$user = $_SESSION["rec_user"];
	$queryq = "select * from task_group where userid='".$user["id"]."'";
	if($resultq = mysql_query($queryq))
	{
		if(($num_rowsq = mysql_num_rows($resultq))>0)
			return true;
		else
			return false;
	}
	return false;
}
?>