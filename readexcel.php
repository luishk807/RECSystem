<?Php
session_start();
include 'include/config.php';
include "include/function.php";
require_once dirname(__FILE__) . 'include/excel/PHPExcel.php';
$ext = explode(".",$_FILES['file']['name']);
if($ext[1] != "xlsx")
{
	$_SESSION["fmapresult"]="Invalid File Type";
	header("location:import.php");
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
	$array_data[$rowIndex] = array('A'=>'','B'=>'','C'=>'','D'=>'','E'=>'','F'=>'','G'=>'','H'=>'','I'=>'','J'=>'','K'=>'','L'=>'');
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
        }else if('K' == $cell->getColumn()){
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getValue();
        }else if('L' == $cell->getColumn()){
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getValue();
        }
    }
}
$user = $array_data;

$entryid = mysql_insert_id();
for($i=$def;$i<(sizeof($user)+$def);$i++)
{
	@$phpdate = PHPExcel_Style_NumberFormat::toFormattedString($user[$i]["C"], "YYYY-M-D");
	$query = "insert ignore into rec_entries(cname,cphone,cdate,office,csource,csource_title)values('".clean(ucwords(strtolower($user[$i]["A"])))."','".clean(ucwords(strtolower($user[$i]["B"])))."','".$phpdate."','".clean(ucwords(strtolower($user[$i]["D"])))."','".clean(ucwords(strtolower($user[$i]["E"])))."','".clean(ucwords(strtolower($user[$i]["F"])))."')";
	if($result = mysql_query($query))
	{
		$fileid = mysql_insert_id();
		if(!empty($fileid))
		{
			$str="";
			if(!empty($$user[$i]["G"]))
			{
				@$str= PHPExcel_Style_NumberFormat::toFormattedString($user[$i]["G"], "YYYY-M-D");
				$query = "update rec_entries set interview='".$str."' where id='".$fileid."'";
				@mysql_query($query);
			}
			if(!empty($$user[$i]["H"]))
			{
				@$str= PHPExcel_Style_NumberFormat::toFormattedString($user[$i]["H"], "YYYY-M-D");
				$query = "update rec_entries set orientation='".$str."' where id='".$fileid."'";
				@mysql_query($query);
			}
			if(!empty($$user[$i]["I"]))
			{
				@$str= PHPExcel_Style_NumberFormat::toFormattedString($user[$i]["I"], "YYYY-M-D");
				$query = "update rec_entries set observation='".$str."' where id='".$fileid."'";
				@mysql_query($query);
			}
			if(!empty($$user[$i]["J"]))
			{
				$str=clean(ucwords(strtolower($user[$i]["J"])));
				$query = "update rec_entries set trained='$str' where id='".$fileid."'";
				@mysql_query($query);
			}
			if(!empty($$user[$i]["K"]))
			{
				$str=clean(ucwords(strtolower($user[$i]["K"])));
				$query = "update rec_entries set note='$str' where id='".$fileid."'";
				@mysql_query($query);
			}
			if(!empty($$user[$i]["L"]))
			{
				$str=clean(ucwords(strtolower($user[$i]["L"])));
				$query = "update rec_entries set updated='$str' where id='".$fileid."'";
				@mysql_query($query);
			}
		}
	}
}
header('location:showentries.php');
exit;
}
include 'include/unconfig.php';
?>