<?php
session_start();
include "include/config.php";
include "include/function.php";
include "include/stats_header_script.php";
				?>
                  <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                            	 <?php
									//internal team rows
									$ototal=0;
									$inter_per=0;
									$orien_per=0;
									$orienc_per=0;
									echo "<tr $rowstyle>
										<td align='center' valign='middle' height='30'>Internal Team</td>";
										/*$qca="select * from rec_phones where date between '".$date1."' and '".$date2."'";
										if($rca=mysql_query($qca))
										{
											$numcax=mysql_num_rows($rca);
											if($numcax>0)
												$numcax_link="<a class='statslink' href='javascript:showmodal(\"task=".base64_encode("phone")."&status=&office=&date1=".$date1."&date2=".$date2."\")'>".$numcax."</a>";
											else
												$numcax_link=$numcax;
										}
										echo "
											<td align='center' valign='middle'>0</td>";*/
										//$inter=getStatusEntry_all('1',$date1,$date2);
										if(sizeof($allphones)>0)
										{
											$numcax_link="<a class='statslink' href='javascript:showmodal(\"task=".base64_encode("phone")."&status=&office=&date1=".$date1."&date2=".$date2."\")'>".sizeof($allphones)."</a>";
										}
										else
											$numcax_link=sizeof($allphones);
										$numcax=sizeof($allphones);
										echo "<td align='center' valign='middle'>".$numcax_link."</td>";
										$inter_ar=getStatusEntry_all_array_entry('1',$date1,$date2,$sfilterqx);
										$inter=sizeof($inter_ar);
										$inter_per=@round(($inter/$numcax)*100)."%";
										if(($inter_per>100 || $inter_per <1) && $inter>0)
											$inter_per="100%";
										if($inter>0)
											$inter_link="<a class='statslink' href='javascript:showmodal(\"task=".base64_encode("inter")."&status=".base64_encode('1')."&office=&date1=".$date1."&date2=".$date2."\")'>".$inter."</a>";
										else
											$inter_link=$inter;
										echo "
											<td align='center' valign='middle'>".$inter_link."/".$inter_per."</td>";
										//echo "
										//	<td align='center' valign='middle'>".$inter_link."</td>";
										$orien_ar=getStatusEntry_all_fromar('3',$inter_ar);
										$orien=sizeof($orien_ar);
										$orien_per=@round(($orien/$inter)*100)."%";
										if($orien>0)
											$orien_link="<a class='statslink' href='javascript:showmodal(\"task=".base64_encode("orien")."&status=".base64_encode('3')."&office=&date1=".$date1."&date2=".$date2."\")'>".$orien."</a>";
										else
											$orien_link=$orien;
										echo "
											<td align='center' valign='middle'>".$orien_link."/".$orien_per."</td>";
										$orienc_ar=getStatusEntry_all_fromar('7',$orien_ar);
										$orienc=sizeof($orienc_ar);
										$orienc_per=@round(($orienc/$orien)*100)."%";
										if($orienc>0)
											$orienc_link="<a class='statslink' href='javascript:showmodal(\"task=".base64_encode("orienc")."&status=".base64_encode('7')."&office=&date1=".$date1."&date2=".$date2."\")'>".$orienc."</a>";
										else
											$orienc_link=$orienc;
										echo "
											<td align='center' valign='middle'>".$orienc_link."/".$orienc_per."</td>";
										$ototal=getStatusSales_fromar("",$orienc_ar);
										$ototal_ar=getStatusSales_fromar_ar("",$orienc_ar);
										if($ototal>0)
											$ototal_link="<a class='statslink' href='javascript:showmodal(\"task=".base64_encode("ototal")."&status=&office=&date1=".$date1."&date2=".$date2."\")'>".$ototal."</a>";
										else
											$ototal_link=$ototal;
										echo "
											<td align='center' valign='middle'>".$ototal_link."</td>
								   </tr>";
									//end of internal team row
									$allarray[0]=array('tphone'=>$allphones,'inter'=>$inter_ar,'orien'=>$orien_ar,'orienc'=>$orienc_ar,'ototal'=>$ototal_ar);
									$query="select * from rec_office order by name";
									if($result=mysql_query($query))
									{
										if(($num_rows=mysql_num_rows($result))>0)
										{
											$countx=1;
											$totalx=0;
											while($rows=mysql_fetch_array($result))
											{
												$inter=0;
												$orien=0;
												$orienc=0;
												$ototal=0;
												$inter_per=0;
												$orien_per=0;
												$orienc_per=0;
												$totalx = $countx%2;
												 if($totalx==0)
													$rowstyle="style='font-size:15pt'";
												else
													$rowstyle="style='background-color:#e1fb51; font-size:15pt'";
												echo "
												<tr $rowstyle>
												<td align='center' valign='middle' height='30' width='22%'>".stripslashes($rows["name"])."</td>";
												$qca="select * from rec_phones where date between '".$date1."' and '".$date2."' and office='".$rows["id"]."'";
												$tphone=$allxphones[$rows["id"]];
												/*if($rca=mysql_query($qca))
												{
													$numcax=mysql_num_rows($rca);
													if($numcax>0)
														$numcax_link="<a class='statslink' href='javascript:showmodal(\"task=".base64_encode("phone")."&status=&office=".base64_encode($rows["id"])."&date1=".$date1."&date2=".$date2."\")'>".$numcax."</a>";
													else
														$numcax_link=$numcax;
												}
												echo "
												<td align='center' valign='middle'>".$numcax_link."</td>";*/
												if(sizeof($tphone)>0)
													$inter_link="<a class='statslink' href='javascript:showmodal(\"task=".base64_encode("phone")."&status=&office=".base64_encode($rows["id"])."&date1=".$date1."&date2=".$date2."\")'>".sizeof($tphone)."</a>";
												else
													$inter_link=sizeof($tphone);
												$total_p=sizeof($tphone);
												echo "<td align='center' valign='middle' width='15%'>".$inter_link."</td>";
											//	$inter=getStatusEntry($rows["id"],$date1,$date2,'1');
											//	$inter_per=@round(($inter/$numcax)*100)."%";
												$inter_ar=NULL;
												$inter_ar=getStatusEntry_array_entry($rows["id"],$date1,$date2,'1',$sfilterqx);
												/*echo sizeof($inter_ar)."<br/>";
												for($i=0;$i<sizeof($inter_ar);$i++)
													echo "<a target='_blank' href='setrec.php?id=".base64_encode($inter_ar[$i]["id"])."'>".$inter_ar[$i]["name"]."</a><br/>";*/
												$inter=sizeof($inter_ar);
												$inter_per=@round(($inter/$total_p)*100)."%";
												if(($inter_per>100 || $inter_per <1) && $inter>0)
													$inter_per='100%';
												if($inter>0)
													$inter_link="<a class='statslink' href='javascript:showmodal(\"task=".base64_encode("inter")."&status=".base64_encode('1')."&office=".base64_encode($rows["id"])."&date1=".$date1."&date2=".$date2."\")'>".$inter."</a>";
												else
													$inter_link=$inter;
												echo "
												<td align='center' valign='middle' width='16%'>".$inter_link."/".$inter_per."</td>";
												//$orien=getStatusEntry($rows["id"],$date1,$date2,'3');
												$orien_ar=getStatusEntry_all_fromar('3',$inter_ar);
												$orien=sizeof($orien_ar);
												$orien_per=@round(($orien/$inter)*100)."%";
												if($orien>0)
													$orien_link="<a class='statslink' href='javascript:showmodal(\"task=".base64_encode("orien")."&status=".base64_encode('3')."&office=".base64_encode($rows["id"])."&date1=".$date1."&date2=".$date2."\")'>".$orien."</a>";
												else
													$orien_link=$orien;
												echo "
												<td align='center' valign='middle' width='16%'>".$orien_link."/".$orien_per."</td>";
												//$orienc=getStatusEntry($rows["id"],$date1,$date2,'7');
												$orienc_ar=getStatusEntry_all_fromar('7',$orien_ar);
												$orienc=sizeof($orienc_ar);
												$orienc_per=@round(($orienc/$orien)*100)."%";
												if($orienc>0)
													$orienc_link="<a class='statslink' href='javascript:showmodal(\"task=".base64_encode("orienc")."&status=".base64_encode('7')."&office=".base64_encode($rows["id"])."&date1=".$date1."&date2=".$date2."\")'>".$orienc."</a>";
												else
													$orienc_link=$orienc;
												echo "
												<td align='center' valign='middle' width='17%'>".$orienc_link."/".$orienc_per."</td>";
												//$ototal=getStatusSales($rows["id"],$date1,$date2);
												$ototal=getStatusSales_fromar($rows["id"],$orienc_ar);
												$ototal_ar=getStatusSales_fromar_ar($rows["id"],$orienc_ar);
												/*echo print_r($ototal_ar);
												echo "<br/>----------------<Br/>";*/
												if($ototal>0)
													$ototal_link="<a class='statslink' href='javascript:showmodal(\"task=".base64_encode("ototal")."&status=&office=".base64_encode($rows["id"])."&date1=".$date1."&date2=".$date2."\")'>".$ototal."</a>";
												else
													$ototal_link=$ototal;
												echo "<td align='center' valign='middle' width='14%'>".$ototal_link."</td>
											  </tr>
												";
												$allarray[$rows["id"]]=array('tphone'=>$tphone,'inter'=>$inter_ar,'orien'=>$orien_ar,'orienc'=>$orienc_ar,'ototal'=>$ototal_ar);
												$countx++;
											}
										}
									}
									$_SESSION["parray"]=$allarray;
								  ?>
                             <tr><td colspan='6' align='center' valign='middle'><hr/></td></tr>
                            </table>
<?php
	include "include/config.php";
?>