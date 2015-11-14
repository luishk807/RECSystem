<?Php
session_start();
include 'include/config.php';
include "include/function.php";
require_once 'include/excel/PHPExcel.php';
$ext = explode(".",$_FILES['file']['name']);
if($ext[1] != "xlsx")
{
	$_SESSION["fmapresult"]="Invalid File Type";
	header("location:test_acode.html");
	exit;
}
else
{
$objReader = PHPExcel_IOFactory::createReader('Excel2007');
$objReader->setReadDataOnly(true);
//end of read 2007 format
date_default_timezone_set('UTC');
$objPHPExcel = $objReader->load($_FILES['file']['tmp_name']);
//second try
$rowIterator = $objPHPExcel->getActiveSheet()->getRowIterator();
$array_data = array();
foreach($rowIterator as $row){
    $cellIterator = $row->getCellIterator();
    $cellIterator->setIterateOnlyExistingCells(false); // Loop all cells, even if it is not set
    if(1 == $row->getRowIndex ()) continue;//skip first row
    $rowIndex = $row->getRowIndex();
	$array_data[$rowIndex] = array('A'=>'','B'=>'','C'=>'','D'=>'','E'=>'','F'=>'','G'=>'','H'=>'','I'=>'','J'=>'');
	foreach ($cellIterator as $cell) {
        if('A' == $cell->getColumn()){
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getValue();
        } else if('B' == $cell->getColumn()){
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getValue();
        } else if('C' == $cell->getColumn()){
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getValue();
        }else if('D' == $cell->getColumn()){
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getValue();
        }else if('E' == $cell->getColumn()){
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getValue();
        }else if('F' == $cell->getColumn()){
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getValue();
        }else if('G' == $cell->getColumn()){
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getValue();
        }else if('H' == $cell->getColumn()){
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getValue();
        }else if('I' == $cell->getColumn()){
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getValue();
        }else if('J' == $cell->getColumn()){
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getValue();
        }
    }
}
$user = $array_data;
$count=1;
$suser=array();
$query="select distinct userid from sales_report_real order by userid";
if($result=mysql_query($query))
{
	if(($num_rows=mysql_num_rows($result))>0)
	{
		while($rows=mysql_fetch_array($result))
		{
			$xacode="";
			$xname="";
			$xacode=getAgentCode($rows["userid"]);
			$xname=getAgent($rows["userid"]);
			if($xname=="N/A")
				$xname="";
			//if($xacode=="FE1120")
			//	echo "found here $xname and ".$rows["userid"]."<br/>";
			$suser[]=array("id"=>$rows["id"],'name'=>$xname,'acode'=>$xacode);
		}
	}
}
//print_r($suser);
for($i=$def;$i<(sizeof($user)+$def);$i++)
{
	$man_id="";
	$man_name="";
	$email="";
	$phone="";
	$office="";
	$phpdate="";
	$office_x="";
	$echeck=false;
	$einsert=false;
	$status="";
	$cname="";
	$office_n="";
	$office_x="";
	$man_id_q="";
	$man_id_qb="";
	$office_q="";
	$rec_id="";
	$rec_m="";
	$recruiter="";
	$phpdatex="";
	$rec_split="";
	$call_office="";
	$cname=ucwords(strtolower(trim($user[$i]["D"])));
	$office_n=ucwords(strtolower(trim($user[$i]["A"])));
	$office_nx=explode(" ",$office_n);
	$man_name=ucwords(strtolower(trim($user[$i]["J"])));
	$recruiter=ucwords(strtolower(trim($user[$i]["H"])));
	$rec_split=explode(" ",$recruiter);
	if(sizeof($rec_split)>0)
		$rec_m=$rec_split[0];
	else
		$rec_m=$recruiter;
	if($rec_m=="Vanessa")
		$rec_id="28";
	else if($rec_m=="Sofiya")
		$rec_id="17";
	else if($rec_m=="Biana")
		$rec_id="34";
	else if($rec_m=="Charlee")
		$rec_id="20";
	else if($rec_m=="JennIfer" || $rec_m=="Jennifer")
		$rec_id="33";
	else if($rec_m=="Michelle")
		$rec_id="81";
	else
		$rec_id='1';
	$man_id=getManId($man_name);
	if(!empty($man_id))
	{
		$man_id_q=",report_to='".$man_id."',trained='".$man_id."' ";
		$man_id_qb=",interviewer='".$man_id."' ";
	}
	else
		$man_id_qb=",interviewer='".$rec_id."' ";
	$office_x=$office_nx[2];
	if($office_x=="Brooklyn")
		$office="1";
	else if($office_x=="Manhattan")
		$office="2";
	else if($office_x=="Bronx")
		$office="3";
	else if($office_x=="Syracuse")
		$office='8';
	if(!empty($office))
		$office_q=",office='".$office."' ";
	if(isEmail(trim($user[$i]["F"])))
		$email=trim($user[$i]["F"]);
	else
		$phone=fixToRecPhone(trim($user[$i]["F"]));
	@$acode=strtoupper(trim($user[$i]["C"]));
	@$phpdate=PHPExcel_Style_NumberFormat::toFormattedString($user[$i]["E"], "YYYY-M-D");
	if(empty($phpdate))
		@$phpdate=PHPExcel_Style_NumberFormat::toFormattedString($user[$i]["E"], "YYYY-M-D");
	if(!empty($phpdate))
	{
		$phpdate=fixdate_comps('mildate',$phpdate);
		$phpdatex=$phpdate." 10:00:00";
	}
	if(sizeof($suser)>0)
	{
		for($x=0;$x<sizeof($suser);$x++)
		{
			$iuser=$suser[$x];
			//if($acode=='FE1120')
			//	echo "--->compare ".$iuser["acode"]." and ".$acode;
			if(trim($suser[$x]["acode"])==trim($acode))
			{
				//if($acode=='FE1120')
				//	echo "..found<br/>";
				$echeck=true;
				break;
			}
			//else
			//	echo "..not found<br/>";
		}
	}
	if(!empty($cname) && $echeck==true)
	{
		$query="select * from rec_entries where cname='".$cname."'";
		if($result=mysql_query($query))
		{
			if(($num_rows=mysql_num_rows($result))>0)
			{
				$rows=mysql_fetch_assoc($result);
				if(!empty($acode) && !empty($phpdate))
				{
					$qx="";
					$status=$rows["status"];
					if($status=='7'  && !empty($rows["orientation_comp"]))
					{
						$ocomp_date="";
						$ocomp_date=$rows["orientation_comp"];
						if(!empty($ocomp_date) && $ocomp_date =='0000-00-00 00:00:00')
							$ocomp_date=$rows["orientation"];
						if(!empty($ocomp_date) && $ocomp_date =='0000-00-00 00:00:00')
							$ocomp_date=$rows["idate"]." ".$rows["itime"];
						if(!empty($ocomp_date) && $ocomp_date =='0000-00-00 00:00:00')
							$ocomp_date=$phpdatex;
						removeXTimeLine($rows["id"],'17');
						removeXTimeLine($rows["id"],'5');
						setXTimeLine($rows["id"],'17',$ocomp_date,'');
						setXTimeLine($rows["id"],'5',$ocomp_date,'');
						$qx="update rec_entries set ccode_aval='yes',ccode='".strtoupper($acode)."',status='5' where id='".$rows["id"]."'";
						if($rx=mysql_query($qx))
							echo $count.".".$rows["cname"]." [orientation completed]  agent code inserted:".$acode."<br/>";
						else
							echo $count.".".$rows["cname"]." [orientation completed]  agent code can't be inserted:".$acode."<br/>";
						//echo $count.".".$rows["cname"]." [orientation completed]  agent code inserted:".$acode." on date: ".$ocomp_date."<br/>";
						//echo "--->query:".$qx."<br/>";
						$count++;
					}
					else if($status=='3')
					{
						//remove timeline if exists
						removeXTimeLine($rows["id"],'15');
						removeXTimeLine($rows["id"],'11');
						removeXTimeLine($rows["id"],'7');
						removeXTimeLine($rows["id"],'17');
						removeXTimeLine($rows["id"],'5');
						//add timeline
						setXFTimeLine($rows["id"],'15',$phpdatex,'');
						setXTimeLine($rows["id"],'11',$phpdatex,$phpdatex);
						setXTimeLine($rows["id"],'7',$phpdatex,$phpdatex);
						setXTimeLine($rows["id"],'17',$phpdatex,'');
						setXTimeLine($rows["id"],'5',$phpdatex,'');
						$qx="update rec_entries set ccode_aval='yes',ccode='".strtoupper($acode)."',status='5',hired='yes',ori_show='yes',ori_show_info=NULL,orientation_show='".$phpdatex."',ori_comp='yes',ori_comp_info=NULL,orientation_comp='".$phpdate."' $man_id_q  where id='".$rows["id"]."'";
						if($rx=mysql_query($qx))
							echo $count.".".$rows["cname"]." [orientation set]  agent code inserted:".$acode."<br/>";
						else
							echo $count.".".$rows["cname"]." [orientation set]  agent code can't be inserted:".$acode."<br/>";
						//echo $count.".".$rows["cname"]." [orientation set]  agent code inserted:".$acode." on date: ".$phpdatex."<br/>";
						//echo "--->query:".$qx."<br/>";
						$count++;
					}
					else if($status=='1')
					{
						//remove timeline if exists
						removeXTimeLine($rows["id"],'15');
						removeXTimeLine($rows["id"],'10');
						removeXTimeLine($rows["id"],'3');
						removeXTimeLine($rows["id"],'11');
						removeXTimeLine($rows["id"],'7');
						removeXTimeLine($rows["id"],'17');
						removeXTimeLine($rows["id"],'5');
						//add timeline
						setXFTimeLine($rows["id"],'15',$phpdatex,'');
						setXTimeLine($rows["id"],'10',$phpdatex,$phpdatex);
						setXTimeLine($rows["id"],'3',$phpdatex,$phpdatex);
						setXTimeLine($rows["id"],'11',$phpdatex,$phpdatex);
						setXTimeLine($rows["id"],'7',$phpdatex,$phpdatex);
						setXTimeLine($rows["id"],'17',$phpdatex,'');
						setXTimeLine($rows["id"],'5',$phpdatex,'');
						$qx="update rec_entries set ccode_aval='yes',ccode='".strtoupper($acode)."',status='5',int_show='yes',int_show_info=NULL,int_show_date='".$phpdatex."' $man_id_qb ,hired='yes',orientation='".$phpdatex."', ori_show='yes',ori_show_info=NULL,orientation_show='".$phpdatex."',ori_comp='yes',ori_comp_info=NULL,orientation_comp='".$phpdate."' $man_id_q  where id='".$rows["id"]."'";
						if($rx=mysql_query($qx))
							echo $count.".".$rows["cname"]." [interview set]  agent code inserted:".$acode."<br/>";
						else
							echo $count.".".$rows["cname"]." [interview set]  agent code can't be inserted:".$acode."<br/>";
						//echo $count.".".$rows["cname"]." [interview set]  agent code inserted:".$acode." on date: ".$phpdatex."<br/>";
						//echo "--->query:".$qx."<br/>";
						$count++;
					}
					else
					{
						$intdatex=$rows["idate"]." ".$rows["itime"];
						$intdate=$rows["idate"];
						$int_office=$rows["office"];
						$o_office=$rows["orientation_office"];
						$createdby=$rows["createdby"];
						if(empty($createdby))
							$createdby=$rec_id;
						if(!empty($intdatex) && $intdatex !='0000-00-00 00:00:00')
							$phpdatex=$intdatex;
						if(!empty($intdate) && $intdate !='0000-00-00')
							$phpdate=$intdate;
						if(empty($int_office))
							$int_office=$office;
						if(empty($o_office))
							$o_office=$office;
						if($status=='8' || $status=='20' || $status=='9' || $status=='6' || $status=='4')
						{
							$qx="update rec_entries set ccode_aval='yes',ccode='".strtoupper($acode)."'  ,status='5',int_show='yes',int_show_info=NULL,int_show_date='".$phpdatex."' $man_id_qb ,hired='yes',orientation='".$phpdatex."', ori_show='yes',orientation_office='".$o_office."', ori_show_info=NULL,orientation_show='".$phpdatex."',ori_comp='yes',ori_comp_info=NULL,orientation_comp='".$phpdate."' $man_id_q  where id='".$rows["id"]."'";
						}
						else if($status='18' || $status=='2')
						{
							$qx="update rec_entries set ccode_aval='yes',ccode='".strtoupper($acode)."'  ,status='5' ,hired='yes', ori_show='yes', ori_show_info=NULL,orientation_show='".$phpdatex."',ori_comp='yes',ori_comp_info=NULL,orientation_comp='".$phpdate."' $man_id_q  where id='".$rows["id"]."'";
						}
						else if($status='19')
						{
							$qx="update rec_entries set ccode_aval='yes',ccode='".strtoupper($acode)."'  ,status='5' ,hired='yes',  ori_comp='yes',ori_comp_info=NULL,orientation_comp='".$phpdate."' $man_id_q  where id='".$rows["id"]."'";
						}
						//remove timeline if exists
						removeXTimeLine($rows["id"],'15');
						removeXTimeLine($rows["id"],'10');
						removeXTimeLine($rows["id"],'3');
						removeXTimeLine($rows["id"],'11');
						removeXTimeLine($rows["id"],'7');
						removeXTimeLine($rows["id"],'17');
						removeXTimeLine($rows["id"],'5');
						//add timeline
						setXFTimeLine($rows["id"],'15',$phpdatex,'');
						setXTimeLine($rows["id"],'10',$phpdatex,$phpdatex);
						setXTimeLine($rows["id"],'3',$phpdatex,$phpdatex);
						setXTimeLine($rows["id"],'11',$phpdatex,$phpdatex);
						setXTimeLine($rows["id"],'7',$phpdatex,$phpdatex);
						setXTimeLine($rows["id"],'17',$phpdatex,'');
						setXTimeLine($rows["id"],'5',$phpdatex,'');
						if($rx=mysql_query($qx))
							echo $count.".".$rows["cname"]." [other]  agent code inserted:".$acode."<br/>";
						else
							echo $count.".".$rows["cname"]." [other]  agent code can't be inserted:".$acode."<br/>";
						echo $count.".".$rows["cname"]." [other]  agent code inserted:".$acode." on date: ".$phpdatex."<br/>";
						echo "--->query:".$qx."<br/>";
						$count++;
					}
				}
			}
			else
				$einsert=true;
		}
		else
			$einsert;
		if($einsert)
		{
			//insert this entry to entries and timeline
			if(!empty($phpdate) && !empty($acode))
			{
				$query="insert ignore into rec_entries(catid, cname ";
				if(!empty($email))
					$query .=",email ";
				if(!empty($phone))
					$query .=",cphone";
				if(!empty($office))
					$query .=", office, orientation_office ";
				if(!empty($man_id))
					$query .=", trained,report_to ";
				$query .=",idate, itime, cdate ,ccode, ccode_aval , csource, csource_title, status, int_show, int_show_date, hired, interviewer,  orientation,ori_show,orientation_show, ori_comp, orientation_comp,  createdby,enotx,textnotx,eornotx,textornotx,ocall,date,cr_time, coffice)";
				$query .="values(";
				$query .="'1', '".clean($cname)."' ";
				if(!empty($email))
					$query .=", '".clean($email)."' ";
				if(!empty($phone))
					$query .=", '".clean($phone)."' ";
				if(!empty($office))
					$query .=", '".$office."', '".$office."' ";
				if(!empty($man_id))
					$query .=", '".$man_id."', '".$man_id."' ";
				$query .=",'".$phpdate."','10:00:00', '".$phpdate."','".clean($acode)."', 'yes', '10','Not Avaliable', '5', 'yes', '".$phpdate." 10:00:00', 'yes' ";
				if(!empty($man_id))
					$query .=", '".$man_id."' ";
				else
					$query .=", '".$rec_id."' ";
				$query .=", '".$phpdatex."', 'yes', '".$phpdatex."', 'yes', '".$phpdatex."', '".$rec_id."', 'no', 'no', 'no', 'no', 'no', '".$phpdate."', '10:00:00', '".$office."' ";
				$query .=")";
				//if($acode=="FE1120")
				//{
					if($result=mysql_query($query))
					{
						$id_c=mysql_insert_id();
						setXTimeLine($id_c,'1',$phpdatex,$phpdatex);
						setXTimeLine($id_c,'10',$phpdatex,$phpdatex);
						setXTimeLine($id_c,'3',$phpdatex,$phpdatex);
						setXTimeLine($id_c,'11',$phpdatex,$phpdatex);
						setXTimeLine($id_c,'7',$phpdatex,$phpdatex);
						setXTimeLine($id_c,'17',$phpdatex,'');
						setXTimeLine($id_c,'5',$phpdatex,'');
						echo $count.".".$cname." [new entry]  agent code inserted:".$acode."<br/>";
					}
					else
						echo $count.".".$cname." [new entry]  agent code can't be inserted:".$acode."<br/>";
				//}
				//else
				//{
				//	echo $count.".".$cname." [new entry]  agent code inserted:".$acode." on date:".$phpdatex."<br/>";
				//	echo "--->query:".$query."<br/>";
				//}
				$count++;
			}
		}
	}
	else
	{
		if(!$echeck)
		{
			echo $count.".".$cname." [False Entry] not allowed for insertion or edit<br/>";
			$count++;
		}
	}
}
}
include 'include/unconfig.php';
?>