<?php
date_default_timezone_set('America/New_York');
adminlogin();
$showfamily=true;
if(adminlogin_exp())
	$user=$_SESSION["rec_user"];
else
{
	$user=$_SESSION["brownuser"];
	$showfamily=false;
}
$today=date("Y-m-d");
$yearx=base64_decode($_REQUEST["year_ret"]);
$yearx=trim($yearx);
$yearx_split=setRetYearVar($yearx);
$thisyear=trim($yearx_split[0]);
$nextyear=trim($yearx_split[1]);
$yearx_ar=getSalesYear_array();
$setyear=$thisyear;
$bdate=$setyear."-01-01";
$ldate=$nextyear."-3-31";
$month_aval=array();
$hwidth_div=200;
$xpoint="";
$lpoint="";
$start_date="";
$query="select distinct ddate from sales_report_real where ddate between '".$bdate."' and '".$ldate."' order by ddate asc";
if($result=mysql_query($query))
{
	if(($num_rows=mysql_num_rows($result))>0)
	{
		while($rows=mysql_fetch_array($result))
		{
			$smonth=fixdate_comps("m_text_s",$rows["ddate"]);
			$smonth_num=fixdate_comps("m_num",$rows["ddate"]);
			if(!empty($smonth) && !empty($smonth_num))
			{
				$found=false;
				if(sizeof($month_aval)>0)
				{
					for($i=0;$i<sizeof($month_aval);$i++)
					{
						if(trim($month_aval[$i]['name'])==trim($smonth))
						{
							$found=true;
							break;
						}
					}
					if(!$found)
					{
						$month_aval[]=array('name'=>$smonth,'num'=>$smonth_num,'date'=>$setyear."-".$smonth_num."-01");
						$lpoint=$smonth_num;
					}
				}
				else
				{
					$month_aval[]=array('name'=>$smonth,'num'=>$smonth_num,'date'=>$setyear."-".$smonth_num."-01");
					$xpoint=$smonth_num;
					$lpoint=$smonth_num;
					$start_date=fixdate_comps('d',$rows["ddate"]);
				}
			}
		}
	}
}
if(!empty($xpoint))
{
	$bpoint=$xpoint;
	if(sizeof($month_aval)<12)
	{
		$count_month=sizeof($month_aval);
		$x_date=fixdate_comps("m_num",$month_aval[$count_month-1]["date"]);
		while($count_month<12)
		{
			$x_date++;
			$smonth=date("M", mktime(0, 0, 0, $x_date,1,$setyear));
			$smonth_num=date("n", mktime(0, 0, 0, $x_date,1,$setyear));
			$smonth_numx=date("m", mktime(0, 0, 0, $x_date,1,$setyear));
			$x_date_x=date("Y-m-d", mktime(0, 0, 0, $x_date,1,$setyear));
			//$x_date_x=fixdate_comps('mildate',$x_date_x);
			$month_aval[]=array('name'=>$smonth,'num'=>$smonth_num,'date'=>$x_date_x);
			//$hwidth_div +=200;
			$count_month++;
		}
	}
	for($x=0;$x<sizeof($month_aval)-1;$x++)
		$hwidth_div +=200;
}
$track=false;
$hrows=0;
$hwidth="25px";
$drow=6;
$office_ret=base64_decode($_REQUEST["office_ret"]);
if(empty($office_ret))
{
	$query="select * from rec_office order by name limit 1";
	if($result=mysql_query($query))
	{
		if(($num_rows=mysql_num_rows($result))>0)
		{
			$rows=mysql_fetch_assoc($result);
			$office_ret=$rows["id"];
		}
	}
}
?>