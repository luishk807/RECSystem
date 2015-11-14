<?php
session_start();
include "include/config.php";
include "include/function.php";
include "include/ret_header_script.php";
?>
<div id="viewretx_holder" name='viewretx_holder' style="width:<?Php echo $hwidth_div; ?>px;">
<?php
$p_array=array();
for($x=0;$x<sizeof($month_aval);$x++)
{
	$setyearx=fixdate_comps('y',$month_aval[$x]["date"]);
	//echo $month_aval[$x]["date"];
	$getRetActive=getRetentionActive($office_ret,$month_aval[$x]["date"]);
	$date_detail=fixdate_comps('mildate',$month_aval[$x]["date"]);
	$getMonthSales=getRetMonthSales($month_aval[$x]["date"],$office_ret);
	$getMonthSales_link=fixret_modalpop_link($date_detail,'rsales',$getMonthSales,$office_ret,'');
	$total_sales=0;
	$week0sales=getRetSales($getRetActive[0]["week0s"],$month_aval[$x]["date"],$getMonthSales,$office_ret);
	/******links******/
	$week0ret_link=fixret_modalpop_link($date_detail,'dret',$getRetActive[0]['week0'],$office_ret,'week0s');
	$week0sales_link=fixret_modalpop_link($date_detail,'dsales',$week0sales[0]["total"],$office_ret,'week0s');
	$total_sales +=$week0sales[0]["total"];
	$week1sales=getRetSales($getRetActive[0]["week1s"],$month_aval[$x]["date"],$getMonthSales,$office_ret);
	/******links******/
	$week1ret_link=fixret_modalpop_link($date_detail,'dret',$getRetActive[0]['week1'],$office_ret,'week1s');
	$week1sales_link=fixret_modalpop_link($date_detail,'dsales',$week1sales[0]["total"],$office_ret,'week1s');
	$total_sales +=$week1sales[0]["total"];
	$week2sales=getRetSales($getRetActive[0]["week2s"],$month_aval[$x]["date"],$getMonthSales,$office_ret);
	/******links******/
	$week2ret_link=fixret_modalpop_link($date_detail,'dret',$getRetActive[0]['week2'],$office_ret,'week2s');
	$week2sales_link=fixret_modalpop_link($date_detail,'dsales',$week2sales[0]["total"],$office_ret,'week2s');
	$total_sales +=$week2sales[0]["total"];
	$week3sales=getRetSales($getRetActive[0]["week3s"],$month_aval[$x]["date"],$getMonthSales,$office_ret);
	/******links******/
	$week3ret_link=fixret_modalpop_link($date_detail,'dret',$getRetActive[0]['week3'],$office_ret,'week3s');
	$week3sales_link=fixret_modalpop_link($date_detail,'dsales',$week3sales[0]["total"],$office_ret,'week3s');
	$total_sales +=$week3sales[0]["total"];
	$month1sales=getRetSales($getRetActive[0]["month1s"],$month_aval[$x]["date"],$getMonthSales,$office_ret);
	/******links******/
	$month1ret_link=fixret_modalpop_link($date_detail,'dret',$getRetActive[0]['month1'],$office_ret,'month1s');
	$month1sales_link=fixret_modalpop_link($date_detail,'dsales',$month1sales[0]["total"],$office_ret,'month1s');
	$total_sales +=$month1sales[0]["total"];
	$month2sales=getRetSales($getRetActive[0]["month2s"],$month_aval[$x]["date"],$getMonthSales,$office_ret);
	/******links******/
	$month2ret_link=fixret_modalpop_link($date_detail,'dret',$getRetActive[0]['month2'],$office_ret,'month2s');
	$month2sales_link=fixret_modalpop_link($date_detail,'dsales',$month2sales[0]["total"],$office_ret,'month2s');
	$total_sales +=$month2sales[0]["total"];
	$month3sales=getRetSales($getRetActive[0]["month3s"],$month_aval[$x]["date"],$getMonthSales,$office_ret);
	/******links******/
	$month3ret_link=fixret_modalpop_link($date_detail,'dret',$getRetActive[0]['month3'],$office_ret,'month3s');
	$month3sales_link=fixret_modalpop_link($date_detail,'dsales',$month3sales[0]["total"],$office_ret,'month3s');
	$total_sales +=$month3sales[0]["total"];
	$month6sales=getRetSales($getRetActive[0]["month6s"],$month_aval[$x]["date"],$getMonthSales,$office_ret);
	/******links******/
	$month6ret_link=fixret_modalpop_link($date_detail,'dret',$getRetActive[0]['month6'],$office_ret,'month6s');
	$month6sales_link=fixret_modalpop_link($date_detail,'dsales',$month6sales[0]["total"],$office_ret,'month6s');
	$total_sales +=$month6sales[0]["total"];
	$month12sales=getRetSales($getRetActive[0]["month12s"],$month_aval[$x]["date"],$getMonthSales,$office_ret);
	/******links******/
	$month12ret_link=fixret_modalpop_link($date_detail,'dret',$getRetActive[0]['month12'],$office_ret,'month12s');
	$month12sales_link=fixret_modalpop_link($date_detail,'dsales',$month12sales[0]["total"],$office_ret,'month12s');
	$total_sales +=$month12sales[0]["total"];
	//echo $total_sales;
	$total_sales_link=fixret_modalpop_link($date_detail,'dsales_ret',$total_sales,$office_ret,'');
	$nagent_link=fixret_modalpop_link($date_detail,'dret_total',$getRetActive[0]['n_agent'],$office_ret,'');
	$totalSalesUsers=getRetMonthUsers($month_aval[$x]["date"],$office_ret);
	$totalMissing=$totalSalesUsers-$getRetActive[0]['n_agent'];
	$totalMissing_t="";
	//if($totalMissing>0)
	//	$totalMissing_t="<span style='color:#F00;font-size:12pt'>(-".$totalMissing.")</span>";
	if(!empty($date_detail))
		$p_array[$date_detail]=$getRetActive;
	echo "
	<div class='retdivcol'>
		<table width='100%' border='0' cellspacing='0' cellpadding='0'>
		<tr>
			<td colspan='2' align='center' valign='middle' class='retbordercol_header' height='34'>".$month_aval[$x]["name"]." ".$setyearx."</td>
		</tr>
		<tr>
			<td colspan='2' align='center' valign='middle' class='retbordercol' height='30' style='background-color:#e1fb51;'>".$getMonthSales_link."</td>
		</tr>
		<tr>
			<td width='51%' align='center' valign='middle' class='retbordercol'>Active</td>
			<td width='49%' align='center' valign='middle' class='retbordercol'>Sales %</td>
		</tr>
		<tr>
			<td align='center' valign='middle' class='retbordercol' height='34'>".$week0ret_link."</td>
			<td align='center' valign='middle' class='retbordercol'>".$week0sales_link."/".$week0sales[0]["per"]."%"."</td>
		</tr>
		<tr>
			<td align='center' valign='middle' class='retbordercol' height='34'>".$week1ret_link."</td>
			<td align='center' valign='middle' class='retbordercol'>".$week1sales_link."/".$week1sales[0]["per"]."%"."</td>
		</tr>
		<tr>
			<td align='center' valign='middle' class='retbordercol' height='34'>".$week2ret_link."</td>
			<td align='center' valign='middle' class='retbordercol'>".$week2sales_link."/".$week2sales[0]["per"]."%"."</td>
		</tr>
		<tr>
			<td align='center' valign='middle' class='retbordercol' height='34'>".$week3ret_link."</td>
			<td align='center' valign='middle' class='retbordercol'>".$week3sales_link."/".$week3sales[0]["per"]."%"."</td>
		</tr>
		<tr>
			<td align='center' valign='middle' class='retbordercol' height='34'>".$month1ret_link."</td>
			<td align='center' valign='middle' class='retbordercol'>".$month1sales_link."/".$month1sales[0]["per"]."%"."</td>
		</tr>
		<tr>
			<td align='center' valign='middle' class='retbordercol' height='34'>".$month2ret_link."</td>
			<td align='center' valign='middle' class='retbordercol'>".$month2sales_link."/".$month2sales[0]["per"]."%"."</td>
		</tr>
		<tr>
			<td align='center' valign='middle' class='retbordercol' height='34'>".$month3ret_link."</td>
			<td align='center' valign='middle' class='retbordercol'>".$month3sales_link."/".$month3sales[0]["per"]."%"."</td>
		</tr>
		<tr>
			<td align='center' valign='middle' class='retbordercol' height='34'>".$month6ret_link."</td>
			<td align='center' valign='middle' class='retbordercol'>".$month6sales_link."/".$month6sales[0]["per"]."%"."</td>
		</tr>
		<tr>
			<td align='center' valign='middle' class='retbordercol' height='34'>".$month12ret_link."</td>
			<td align='center' valign='middle' class='retbordercol'>".$month12sales_link."/".$month12sales[0]["per"]."%"."</td>
		</tr>
		<tr>
			<td align='center' valign='middle' class='retbordercol' height='34'>".$nagent_link." $totalMissing_t</td>
			<td align='center' valign='middle' class='retbordercol'>".$total_sales_link."</td>
		</tr>
		</table>
	</div>
";
}
$_SESSION["ret_array"]=$p_array;
?>
<div style="clear:both"></div>
</div>
<?php
	include "include/config.php";
?>