<?php
ob_start();
session_start();
if($_SESSION["Username"] == ""){
	header("location:index.php");
	exit();
}
include("connect/connect.php");
$sql_account = "SELECT * FROM account WHERE username = '".$_SESSION["Username"]."'  ";
$res_account = mysql_query($sql_account) or die ('Error '.$sql_account);
$rs_account = mysql_fetch_array($res_account);
$_SESSION["id_company"]=$_REQUEST['id_company'];
$_SESSION['company_name']=$_REQUEST['company_name'];

//create pdf
include("mpdf/mpdf.php");
?>
<!DOCTYPE html>
<body>
	<?php
	$id=$_GET["id_u"];
	/*$sql="select * from roc where id_roc='".$id."'";
	$res=mysql_query($sql) or die ('Error '.$sql);
	$rs=mysql_fetch_array($res);*/

	$sql_fac="select * from type_manufactory where id_manufacturer='".$_GET['fac']."'";
	$res_fac=mysql_query($sql_fac) or die ('Error '.$sql_fac);
	$rs_fac=mysql_fetch_array($res_fac);

	$sql_costing="select * from costing_factory where id_costing_factory='".$id."'";
	$res_costing=mysql_query($sql_costing) or die ('Error '.$sql_costing);
	$rs_costing=mysql_fetch_array($res_costing);

	$sql_product="select * from product where id_product='".$rs_costing['id_product']."'";
	$res_product=mysql_query($sql_product) or die ('Errro '.$sql_product);
	$rs_product=mysql_fetch_array($res_product);

	$sql_npd_eva="select * from npd_project_evaluation where id_roc='".$rs['id_roc']."'";
	$res_npd_eva=mysql_query($sql_npd_eva) or die ('Error '.$sql_npd_eva);
	$rs_npd_eva=mysql_fetch_array($res_npd_eva);
				
	$sql_product_value="select * from  roc_product_value";
	$sql_product_value .=" where id_product_value='".$rs['id_product_value']."'";
	$res_product_value=mysql_query($sql_product_value) or die ('Error '.$sql_product_value);
	$rs_product_value=mysql_fetch_array($res_product_value);

	$sql_pack_blister="select * from costing_pack_blister";
	$sql_pack_blister .=" where id_costing_factory='".$rs_costing['id_costing_factory']."'";
	$res_pack_blister=mysql_query($sql_pack_blister) or die ('Error '.$sql_pack_blister);
	$rs_pack_blister=mysql_fetch_array($res_pack_blister);

	$sql_acc="select * from account where id_account='".$rs_costing['create_by']."'";
	$res_acc=mysql_query($sql_acc) or die ('Error '.$sql_acc);
	$rs_acc=mysql_fetch_array($res_acc);

	$sql_prima="select * from costing_factory_prima";
	$sql_prima .=" where id_costing_factory='".$rs_costing['id_costing_factory']."'";
	$res_prima=mysql_query($sql_prima) or die ('Error '.$sql_prima);
	$rs_prima=mysql_fetch_array($res_prima);

	$sql_quo="select * from costing_quotation where id_costing_factory='".$rs_costing['id_costing_factory']."'";
	$res_quo=mysql_query($sql_quo) or die ('Error '.$sql_quo);
	$rs_quo=mysql_fetch_array($res_quo);
	?>
	<input type="hidden" name="quo" value="<?php echo $rs_quo['id_costing_quotation']?>">
	<table style="border: none; width: 100%;line-height:1.95em;" cellpadding="0" cellspacing="0">
		<tr>
			<td colspan="7" style="border-top:1px solid #000;font-size:2.5em; padding:3.5% 0 2% 0; text-align:center;"><b>ใบเสนอราคา</b></td>
		</tr>
		<tr>
			<td rowspan="2" style="font-size:1.5em; text-align:left; padding:1.5%; border-left:1px solid #000; border-top:1px solid #000;border-bottom:1px solid #000;border-right:1px solid #000;">ชื่อผู้ติดต่อ/เบอร์ </td>
			<td rowspan="2" colspan="2" style="font-size:1.5em; text-align:left; padding:2.5% 1.5% 1.5%; border-top:1px solid #000;border-right:1px solid #000;border-bottom:1px solid #000;vertical-align:top;">
				<?php
				if($rs_quo['contact_name'] == ' '){
					$sql_contact="select * from company_contact where id_company='".$rs_company['id_company']."'";
					$res_contact=mysql_query($sql_contact) or die ('Error '.$sql_contact);
					$rs_contact=mysql_fetch_array($res_contact);
					echo $rs_contact['contact_name'];
				}else{echo $rs_quo['contact_name'];}
				?>
			</td>												
			<td colspan="2" style="border-bottom:1px solid #000;font-size:1.5em; text-align:left; padding:1.5%; border-top:1px solid #000; border-right:1px solid #000;">เลขที่ใบเสนอราคา </td>
			<td style="border-bottom:1px solid #000;font-size:1.5em; text-align:left; padding:1.5%; border-top:1px solid #000; border-right:1px solid #000;"><?php echo $rs_quo['quotation_no']?></td>
		</tr>
		<tr>
			<td colspan="2" style="font-size:1.5em; text-align:left; padding:1.5%; border-right:1px solid #000;border-bottom:1px solid #000;">วันที่</td>
			<td colspan="3" style="font-size:1.5em; text-align:left; padding:1.5%; border-right:1px solid #000;border-bottom:1px solid #000;"><?php echo date("d/m/Y")?></td>
		</tr>
		<tr>
			<td colspan="7"><p style="font-size:0.2em;line-height:0.5em;">&nbsp;</p></td>
		</tr>
		<tr>		
			<td style="font-size:1.5em; text-align:left; padding:1.5%;border-top:1px solid #000;border-left:1px solid #000;border-right:1px solid #000;">ชื่อบริษัท</td>
			<td colspan="2" style="font-size:1.5em; text-align:left;border-top:1px solid #000;padding:1.5%;border-right:1px solid #000;">
			<?php
			if($rs_quo['company_name'] == ''){
				$sql_company="select * from company where id_company='".$rs_quo['id_company']."'";
				$res_company=mysql_query($sql_company) or die ('Error '.$sql_company);
				$rs_company=mysql_fetch_array($res_company);
				echo $rs_company['company_name'];
			}else{echo $rs_quo['company_name'];}
			?>
			</td>
			<td colspan="2" style="font-size:1.5em; text-align:left; padding:1.5%;border-right:1px solid #000;border-top:1px solid #000;">ผู้เสนอราคา</td>
			<td style="font-size:1.5em; text-align:left; padding:1.5%;border-right:1px solid #000;border-top:1px solid #000;"><?php echo $rs_acc['name']?></td>
		</tr>
		<tr>
		<?php
		if($rs_quo['address'] == ''){
			$sql_address="select * from company_address where id_address='".$rs_company['id_address']."'";
			$res_address=mysql_query($sql_address) or die ('Error '.$sql_address);
			$rs_address=mysql_fetch_array($res_address);
			$address=$rs_address['address_no'].'&nbsp;&nbsp;'.$rs_address['road'].'&nbsp;&nbsp;'.$rs_address['sub_district'].'&nbsp;&nbsp;'.$rs_address['district'].'&nbsp;&nbsp;'.$rs_address['province'].'&nbsp;&nbsp;'.$rs_address['postal_code'];
		}else{$address=$rs_quo['address'];}
		?>
			<td rowspan="2" style="font-size:1.5em; text-align:left; padding:1.5%; border-left:1px solid #000; border-right:1px solid #000;">ที่อยู่</td>
			<td rowspan="2" colspan="2" style="font-size:1.5em; text-align:left; padding:1.5%; border-right:1px solid #000;"><?php echo $address?></td>
			<td colspan="2" style="font-size:1.5em; text-align:left; padding:1.5%; border-right:1px solid #000;">ระยะเวลาในการส่งของ</td>
			<td style="font-size:1.5em; text-align:left; padding:1.5%; border-right:1px solid #000;">75-90 วัน</td>
		</tr>
		<tr>
			<td colspan="2" style="font-size:1.5em; text-align:left; padding:1.5%; border-right:1px solid #000;">จำนวนวันเครดิต</td>
			<td style="font-size:1.5em; text-align:left; padding:1.5%; border-right:1px solid #000;">0 วัน</td>
		</tr>
		<tr>
			<td style="font-size:1.5em; text-align:left; padding:1.5%; border-left:1px solid #000; border-bottom:1px solid #000; border-right:1px solid #000;">อีเมล์ :</td>
			<td colspan="2" style="font-size:1.5em; text-align:left; padding:1.5%; border-bottom:1px solid #000; border-right:1px solid #000;"><?php if($rs_quo['email'] == '' ){echo $rs_contact['email'];}else{echo $rs_quo['email'];}?></td>
			<td colspan="2" style="font-size:1.5em; text-align:left; padding:1.5%; border-bottom:1px solid #000; border-right:1px solid #000;">กำหนดยืนราคา</td>
			<td style="font-size:1.5em; text-align:left; padding:1.5%; border-bottom:1px solid #000; border-right:1px solid #000;">30วันนับจากวันที่เสนอราคา</td>
		</tr>
		<tr>
			<td colspan="7"><p style="font-size:0.2em;line-height:0.5em;">&nbsp;</p></td>
		</tr>
		<tr>
			<td style="font-size:1.5em; text-align:center; padding:1.5%; border-top:1px solid #000; border-left:1px solid #000; border-bottom:1px solid #000; border-right:1px solid #000; width:20%;">ลำดับที่</td>
			<td style="font-size:1.5em; text-align:center; padding:1.5%; border-top:1px solid #000; border-bottom:1px solid #000; border-right:1px solid #000; width:80%;">รายการ</td>
			<td style="font-size:1.5em; text-align:center; padding:1.5%; border-top:1px solid #000; border-bottom:1px solid #000; border-right:1px solid #000; width:20%;">จำนวน</td>
			<td style="font-size:1.5em; text-align:center; padding:1.5%; border-top:1px solid #000; border-bottom:1px solid #000; border-right:1px solid #000; width:20%;">หน่วยนับ</td>
			<td style="font-size:1.5em; text-align:center; padding:1.5%; border-top:1px solid #000; border-bottom:1px solid #000; border-right:1px solid #000; width:20%;">ราคา/หน่วย</td>
			<td style="font-size:1.5em; text-align:center; padding:1.5%; border-top:1px solid #000; border-bottom:1px solid #000; border-right:1px solid #000;">จำนวนเงิน</td>
		</tr>
		<tr>
			<td style="font-size:1.5em; text-align:center; padding:1.5%; border-left:1px solid #000; border-bottom:1px solid #000; border-right:1px solid #000; vertical-align: top;">1</td>
			<td style="font-size:1.5em;text-align:left; padding:1.5%;border-bottom:1px solid #000; border-right:1px solid #000;"> 
				<?php 
				$sql_npd="select * from npd_type_product where id_npd_type_product='".$rs_costing['id_product_cate']."'";
				$res_npd=mysql_query($sql_npd) or die('Error '.$sql_npd);
				$rs_npd=mysql_fetch_array($res_npd);
				echo $rs_npd['npd_title'].'&nbsp;:&nbsp;';
				$i_blister=0;
				$detail_blister=split(",",$rs_pack_blister['detail_blister']);
				$price_unit=split(",",$rs_pack_blister['price_unit']);
				$num_blister=split(",",$rs_pack_blister['num_blister']);
				echo '<span style="margin-right:2%;color:#0440DA;">';
				echo $rs_costing['product_name'];
				echo '</span>';
				echo '<br><br>';
				echo 'ขนาดบรรจุ :';
				echo '&nbsp;';
				echo '<span style="margin-right:2%;color:#0440DA;">';
				echo $rs_quo['packaging'];
				echo '</span>';
				if(($rs_costing['id_product_appearance']==1) || ($rs_costing['id_product_appearance']==2)){echo '&nbsp;\'s';}
				elseif(($rs_costing['id_product_appearance']==4) || ($rs_costing['id_product_appearance']==6)){echo '&nbsp;cc';}
				echo '<br>';				
				echo 'เลขสารบบ อย. :';
				echo '&nbsp;';
				echo '<span style="margin-right:2%;color:#0440DA;">';
				if($rs_quo['serial_number']==''){echo '-';}else{echo $rs_quo['serial_number'];}
				echo '</span>';				
				echo '<div style="float:left;">';
				echo '<br>';
				if($_GET['fac']==1){
				echo 'บรรจุภัณฑ์ประกอบด้วย :';
				echo '&nbsp;';
				$count_blister=count($detail_blister)-2;
				$j=0;
				if(($rs_costing['id_product_appearance']==1) || ($rs_costing['id_product_appearance']==2) || ($rs_costing['id_product_appearance']==3)){
					if($rs_pack_blister['type_pack']==1){
						$i_blister=0;
						$j=0;
						$count_blister=count($detail_blister)-1;
						$sql_jsp_blister="select * from jsp_pack_blister";
						$res_jsp_blister=mysql_query($sql_jsp_blister) or die ('Error '.$sql_jsp_blister);
						while($rs_jsp_blister=mysql_fetch_array($res_jsp_blister)){
							if($detail_blister[$i_blister]==$rs_jsp_blister['id_jsp_pack_blister']){
								if(($detail_blister[$i_blister]==1) || ($detail_blister[$i_blister]==2)){echo '';}
								else{
									if($rs_jsp_blister['description']=='Box'){echo $rs_jsp_blister['description_thai'];}
									else{
										if($rs_jsp_blister['description']=='Delivery'){echo $rs_jsp_blister['description_thai'];}
										else{echo $rs_jsp_blister['description_thai'];}}
									
									if($i_blister<$count_blister){	
										echo '&nbsp;&nbsp;';
										//echo $num_blister[$j];	
										//if($rs_jsp_blister['description']=='Blister'){echo 'pcs';}
										//elseif($rs_jsp_blister['description']=='Silica gel'){echo 'pcs';}
										//elseif($rs_jsp_blister['description']=='Aliminum pouch'){echo 'pcs';}
										echo '+';
										echo '&nbsp;&nbsp;';
										$j++;
									}
								}
							$i_blister++;
						}															
					}
					}elseif($rs_pack_blister['type_pack']==2){
						$i_blister=0;
						$j=0;
						$count_blister=count($detail_blister)-1;
						$sql_jsp_blister="select * from jsp_pack_bottle";
						$res_jsp_blister=mysql_query($sql_jsp_blister) or die ('Error '.$sql_jsp_blister);
						while($rs_jsp_blister=mysql_fetch_array($res_jsp_blister)){
							if($detail_blister[$i_blister]==$rs_jsp_blister['id_jsp_pack_bottle']){
								if(($detail_blister[$i_blister]==1) || ($detail_blister[$i_blister]==2)){echo '';}
								else{
									if($rs_jsp_blister['description']=='Box'){echo $rs_jsp_blister['specification'];}
									else{																		
										if($rs_jsp_blister['description']=='Delivery'){echo 'Delivery BKK and Vicinity area';}
										else{
											if($rs_jsp_blister['description']=='Bottle'){
												$sql_bdetail="select * from jsp_pack_bottle_detail";
												$sql_bdetail .=" where id_jsp_pack_bottle_detail='".$rs_pack_blister['bottle_detail']."'";
												$res_bdetail=mysql_query($sql_bdetail) or die ('Error '.$sql_bdetail);
												$rs_bdetail=mysql_fetch_array($res_bdetail);
												echo $rs_jsp_blister['description'].'&nbsp;&nbsp;'.$rs_bdetail['description'];
											}else{echo $rs_jsp_blister['description'];}
										}
									}
									if($i_blister<$count_blister){	
										echo '&nbsp;&nbsp;';
										//if($num_blister[$j]=='Array'){echo '';}else{echo /*$num_blister[$j]*/'';}	//if($rs_jsp_blister['description']=='Silica gel'){echo 'pcs';}
										echo '+';
										echo '&nbsp;&nbsp;';
										$j++;
									}
								}
								$i_blister++;
							}														
						}
					}
				}//end table softgel capsule
				elseif($rs_costing['id_product_appearance']==6){
					if($rs_pack_blister['bottle_cap']==1){
						$count_blister=count($detail_blister);	
						for($i_blister==-1;$i_blister<$count_blister;$i_blister++){
							if($detail_blister[$i_blister]==1){echo '';}
							else{
								if($detail_blister[$i_blister]==2){echo 'สารกันชื้น';}
								elseif($detail_blister[$i_blister]==3){echo 'ฉลากสติ๊กเกอร์';}
								elseif($detail_blister[$i_blister]==4){echo 'ฟิล์มหดหุ้มกล่อง 6 ขวด';}
								elseif($detail_blister[$i_blister]==5){echo 'Glass bottle';}
								elseif($detail_blister[$i_blister]==6){echo 'Cap Alu 1 color';}
								elseif($detail_blister[$i_blister]==7){echo 'ลัง';}
								elseif($detail_blister[$i_blister]==8){echo 'ขนส่งในกรุงเทพฯ และปริมณฑล 1 เที่ยว';}
																			
								if($i_blister<($count_blister-1)){	
									echo '&nbsp;&nbsp;';																	
									echo '+';
									echo '&nbsp;&nbsp;';
								}
							}																
						}//end for															
					}elseif($rs_pack_blister['bottle_cap']==2){															
						$count_blister=count($detail_blister);	
						$sum=$count_blister-1;
						for($i_blister==-1;$i_blister<$count_blister;$i_blister++){
							if($detail_blister[$i_blister]==9){echo '';}
							else{
								if($detail_blister[$i_blister]==10){echo 'Box';}
								elseif($detail_blister[$i_blister]==11){echo 'ฉลากสติ๊กเกอร์';}
								elseif($detail_blister[$i_blister]==12){echo 'Holder Carton';}
								elseif($detail_blister[$i_blister]==13){echo 'ขวดแก้ว';}
								elseif($detail_blister[$i_blister]==14){echo 'Cap Alu 1 color';}
								elseif($detail_blister[$i_blister]==15){echo 'ลัง';}
								elseif($detail_blister[$i_blister]==16){echo 'ขนส่งในกรุงเทพฯ และปริมณฑล 1 เที่ยว';}																	
							
								if($i_blister<$sum){	
									echo '&nbsp;&nbsp;';																	
									echo '+';
									echo '&nbsp;&nbsp;';
								}
							}																
						}//end for
					}
				}
				elseif(($rs_costing['id_product_appearance']==4) || ($rs_costing['id_product_appearance']==5)){
					$count_blister=count($detail_blister);	
					$sum=$count_blister-1;
					for($i_blister==-1;$i_blister<$count_blister;$i_blister++){
						if($detail_blister[$i_blister]==1){echo '';}
						else{
							if($detail_blister[$i_blister]==2){echo 'กล่องอารต์การ์ด 300 แกรม พิมพ์ 4 สี่ ';}
							elseif($detail_blister[$i_blister]==3){echo 'ฟิล์มหดหุ้มกล่อง';}
							elseif($detail_blister[$i_blister]==4){echo 'ลัง';}
							elseif($detail_blister[$i_blister]==5){echo 'ขนส่งในกรุงเทพฯ และปริมณฑล 1 เที่ยว';}
							
							if($i_blister<$sum){	
								echo '&nbsp;&nbsp;';																	
								echo '+';
								echo '&nbsp;&nbsp;';
							}
						}																
					}//end for
				}
				echo '<br><br>';
				}
				echo '<span style="color:#F11212;">';
				echo 'ส่วนประกอบที่สำคัญต่อ 1 หน่วย';
				echo '</span>';
				echo '<br>';
				echo '<table style="border:none; width:100%;" cellpadding="0" cellspacing="0">';
				$a=0;
				$sql_npd_rela="select * from costing_rm where id_roc='".$rs_costing['id_costing_factory']."'";
				$sql_npd_rela .=" order by quantities desc";
				$res_npd_rela=mysql_query($sql_npd_rela) or die ('Error '.$sql_npd_rela);
				$num_row=mysql_num_rows($res_npd_rela);
				while($rs_npd_rela=mysql_fetch_array($res_npd_rela)){
					$a=14-$num_row;
					
					echo '<tr>';
					echo '<td style="width:70%;font-size:1.5em;">';
					echo $rs_npd_rela['rm_name'];
					echo '</td>';
					echo '<td style="width:30%;text-align:right;font-size:1.5em;">';
					echo number_format($rs_npd_rela['quantities'],2).' mg';
					echo '</td>';
					echo '</tr>';
				 }
				echo '</table>';
				for($i=0;$i<=$a;$i++){echo '<br>';}
				 ?>
			</td>
			<td style="vertical-align: top; font-size:1.5em; text-align:center;padding:1.5%; border-top:1px solid #000; border-bottom:1px solid #000; border-right:1px solid #000;"><?php echo number_format($rs_quo['quo_quatatity'],2)?></td>
			<td style="vertical-align: top; font-size:1.5em; text-align:center; padding:1.5%; border-top:1px solid #000; border-bottom:1px solid #000; border-right:1px solid #000;"><?php echo $rs_quo['quo_unit']?></td>
			<td style="vertical-align: top; font-size:1.5em; text-align:center; padding:1.5%; border-top:1px solid #000; border-bottom:1px solid #000; border-right:1px solid #000;"><?php echo number_format($rs_quo['quo_price'],2)?></td>
			<td style="vertical-align: top; font-size:1.5em; text-align:right; padding:1.5% 2.0% 1.5% 1.5%; border-top:1px solid #000; border-bottom:1px solid #000; border-right:1px solid #000;"><?php echo number_format($rs_quo['quo_total'],2)?></td>
		</tr>
		<tr>
			<td rowspan="4" style="font-size:1.5em; text-align:left; padding:1.5%; border-left:1px solid #000; border-bottom:1px solid #000; border-right:1px solid #000; vertical-align: top;">หมายเหตุ :</td>
			<td colspan="2" rowspan="4" style="vertical-align: top; font-size:1.5em; text-align:left; padding:1.5%; border-top:1px solid #000; border-bottom:1px solid #000; border-right:1px solid #000;">
				<?php $remark=split(",",$rs_quo['remark']);?>
				<?php if(in_array('1',$remark)){echo 'ลูกค้าจัดหา '.$rs_quo['customer_supply'].'<br>';}?>
				<?php if(in_array('2',$remark)){echo '1. ระยะเวลาในการดำเนินการผลิตประมาณ 90 วัน สำหรับการผลิต Lot แรก<br>'; }?>
				<?php if(in_array('3',$remark)){echo '2. การแจ้งยกเลิก PO สามารถแจ้งยกเลิกได้ภายใน 7 วันหลังเปิด PO มาแล้ว<br>'; }?>
				<?php if(in_array('4',$remark)){					
					if($rs_costing['id_product_appearance']==1){						
						if($rs_quo['allowed_half']==1){
							echo '3. MOQ การผลิตปกติคือ ';
							echo '150,000  เม็ด ';
							echo 'แต่ในกรณีที่เป็น Lot 1 ทางบริษัทฯอนุโลมลดให้เหลือครึ่งหนึ่ง';
						}else{
							echo '3. MOQ การผลิตปกติคือ ';
							echo '150,000  เม็ด ';
						}
					}
					//Capsule
					elseif($rs_costing['id_product_appearance']==2){
						if($rs_quo['allowed_half']==1){
							echo '3. MOQ การผลิตปกติคือ ';
							echo '150,000  เม็ด ';
							echo 'แต่ในกรณีที่เป็น Lot 1 ทางบริษัทฯอนุโลมลดให้เหลือครึ่งหนึ่ง';
						}else{
							echo '3. MOQ การผลิตปกติคือ ';
							echo '150,000  เม็ด ';
						}
					} 
					//Softgel
					elseif($rs_costing['id_product_appearance']==3){echo '3. MOQ การผลิตปกติคือ ';echo '150,000 แคปซูล';}
					//Instant Drink
					elseif($rs_costing['id_product_appearance']==4){echo '3. MOQ การผลิตปกติคือ ';echo '250,000 ซอง';}
					//Functional Drink
					elseif($rs_costing['id_product_appearance']==6){echo '3. MOQ การผลิตปกติคือ ';echo '150,000 ขวด' ;}
				 }
				?>
			</td>
			<td colspan="2" style="vertical-align: top; font-size:1.5em; text-align:left; padding:1.5%; border-top:1px solid #000; border-bottom:1px solid #000; border-right:1px solid #000;">รวมเป็นเงิน</td>
			<td style="vertical-align: top; font-size:1.5em; text-align:right; padding:1.5% 2.0% 1.5% 1.5%; border-top:1px solid #000; border-bottom:1px solid #000; border-right:1px solid #000;"><?php echo number_format($rs_quo['quo_total'],2)?></td>
		</tr>
		<tr>												
			<td colspan="2" style="vertical-align: top; font-size:1.5em; text-align:left; padding:1.5%; border-top:1px solid #000; border-bottom:1px solid #000; border-right:1px solid #000;">ส่วนลด</td>
			<td style="vertical-align: top; font-size:1.5em; text-align:right; padding:1.5% 2.0% 1.5% 1.5%; border-top:1px solid #000; border-bottom:1px solid #000; border-right:1px solid #000;"><?php echo number_format($rs_quo['quo_discount'],2)?></td>
		</tr>
		<tr>
			<td colspan="2" style="vertical-align: top; font-size:1.5em; text-align:left; padding:1.5%; border-top:1px solid #000; border-bottom:1px solid #000; border-right:1px solid #000;">รวมเงินหลังหักส่วนลด</td>
			<td style="vertical-align: top; font-size:1.5em; text-align:right; padding:1.5% 2.0% 1.5% 1.5%; border-top:1px solid #000; border-bottom:1px solid #000; border-right:1px solid #000;"><?php echo number_format($rs_quo['total_discount'],2)?></td>
		</tr>
		<tr>
			<td colspan="2" style="vertical-align: top; font-size:1.5em; text-align:left; padding:1.5%; border-top:1px solid #000; border-bottom:1px solid #000; border-right:1px solid #000;">Vat 7%</td>
			<td style="vertical-align: top; font-size:1.5em; text-align:right; padding:1.5% 2.0% 1.5% 1.5%; border-top:1px solid #000; border-bottom:1px solid #000; border-right:1px solid #000;"><?php echo number_format($rs_quo['vat_7'],2)?></td>
		</tr>
		<tr>
			<td colspan="3" style="vertical-align: top; font-size:1.5em; text-align:center; padding:1.5%; border-left:1px solid #000; border-top:1px solid #000; border-bottom:1px solid #000; border-right:1px solid #000;">
				<?php
				function convert($number){ 
					$txtnum1 = array('ศูนย์','หนึ่ง','สอง','สาม','สี่','ห้า','หก','เจ็ด','แปด','เก้า','สิบ'); 
					$txtnum2 = array('','สิบ','ร้อย','พัน','หมื่น','แสน','ล้าน','สิบ','ร้อย','พัน','หมื่น','แสน','ล้าน'); 
					$number = str_replace(",","",$number); 
					$number = str_replace(" ","",$number); 
					$number = str_replace("บาท","",$number); 
					$number = explode(".",$number); 
					if(sizeof($number)>2){ 
						return 'ทศนิยมหลายตัวนะจ๊ะ'; 
						exit; 
					} 
					$strlen = strlen($number[0]); 
					$convert = ''; 
					for($i=0;$i<$strlen;$i++){ 
						$n = substr($number[0], $i,1); 
						if($n!=0){ 
							if($i==($strlen-1) AND $n==1){ $convert .= 'เอ็ด'; } 
							elseif($i==($strlen-2) AND $n==2){  $convert .= 'ยี่'; } 
							elseif($i==($strlen-2) AND $n==1){ $convert .= ''; } 
							else{ $convert .= $txtnum1[$n]; } 
							$convert .= $txtnum2[$strlen-$i-1]; 
						} 
					} 

					$convert .= 'บาท'; 
					if($number[1]=='0' OR $number[1]=='00' OR $number[1]==''){ 
						$convert .= 'ถ้วน'; 
					}else{ 
						$strlen = strlen($number[1]); 
						for($i=0;$i<$strlen;$i++){ 
							$n = substr($number[1], $i,1); 
							if($n!=0){ 
								if($i==($strlen-1) AND $n==1){$convert .= 'เอ็ด';} 
								elseif($i==($strlen-2) AND $n==2){$convert .= 'ยี่';} 
								elseif($i==($strlen-2) AND $n==1){$convert .= '';} 
								else{ $convert .= $txtnum1[$n];} 
								$convert .= $txtnum2[$strlen-$i-1]; 
							} 
						} 
						$convert .= 'สตางค์'; 
					} 
					return $convert; 
				} 
				echo  $x  .convert($rs_quo['total_all']); 
				?>
			</td>
			<td colspan="2" colspan="2" style="vertical-align: top; font-size:1.5em; text-align:left; padding:1.5%; border-top:1px solid #000; border-bottom:1px solid #000; border-right:1px solid #000;">จำนวนเงินทั้งสิ้น</td>
			<td style="vertical-align: top; font-size:1.5em; text-align:right; padding:1.5% 2.0% 1.5% 1.5%; border-top:1px solid #000; border-bottom:1px solid #000; border-right:1px solid #000;"><?php echo number_format($rs_quo['total_all'],2)?></td>
		</tr>
		<tr>
			<td colspan="3" rowspan="2" style="vertical-align: top; font-size:1.5em; text-align:left; padding:1.5%; border-left:1px solid #000; border-top:1px solid #000; border-bottom:1px solid #000; border-right:1px solid #000;"><b>เงื่อนไขอื่นๆ</b><br>
				(1) เงื่อนไขการชำระเงิน 50% พร้อมใบเสนอราคาและ 50% ณ วันส่งสินค้า<br>
				(2) กรุณาโอนเงินเข้าบัญชีธนาคารทหารไทย เลขที่บัญชี 073-1055-703<br>
				ชื่อบัญชี บริษัท ซีดีไอพี (ประเทศไทย) จำกัด สาขาสาธุประดิษฐ์<br>
				(3) ข้อสงสัยเรื่องการชำระเงินกรุณาติดต่อแผนกบัญชี เบอร์ 02 564 7200 ต่อ 5227<br>
				(4) บริษัทฯจะยืนยันวันส่งของกลับภายหลังได้รับใบสั่งซื้อภายใน 7 วันทำการ<br>
				(5) สินค้าเป็นแบบการสั่งผลิตเฉพาะ อาจได้รับสินค้าในปริมาณ <u>+</u>10% ของจำนวนการสั่งซื้อ<br>
				(6) ผู้ซื้อต้องรับสินค้าทั้งหมดที่ผลิตเสร็จแล้วภายใน 7 วัน หลังได้รับแจ้งจาก บริษัท ซีดีไอพีฯ กรณีรับสินค้าไม่หมด คิดค่าใช้จ่ายในการเก็บรักษาสินค้า วันละ 0.25% ของมูลค่าสินค้าที่ค้างส่ง<br>
				(7) ในกรณีที่มีการยกเลิกใบสั่งซื้อบริษัทฯขอสงวนสิทธิ์ในการคืนเงินมัดจำ
			</td>
			<td colspan="2" style="vertical-align: top; font-size:1.5em; text-align:center; padding:1.5% 2.0% 1.5% 1.5%; border-top:1px solid #000; border-bottom:1px solid #000; border-right:1px solid #000;">ผู้จัดทำ
				<br><br><br><br>............../............../...............<br><?php echo $rs_acc['name']?>
			</td>
			<td colspan="2" rowspan="2" style="vertical-align: top; font-size:1.5em; text-align:center; padding:1.5% 2.0% 1.5% 1.5%; border-top:1px solid #000; border-bottom:1px solid #000; border-right:1px solid #000;">อนุมัติโดย</div><br><br><br><br>
				......../......../.........<br><br>ลูกค้า
			</td>
		</tr>
		<tr>
			<td colspan="2" style="vertical-align: top; font-size:1.5em; text-align:center; padding:1.5% 2.0% 1.5% 1.5%; border-top:1px solid #000; border-bottom:1px solid #000; border-right:1px solid #000;">ผู้ตรวจสอบ
				<br><br><br><br>............../............../...............<br>
			</td>
		</tr>
	</table>
</body>
</html>
<?php
	$html = ob_get_contents();
	ob_end_clean();
	$mpdf=new mPDF('th','A4',0,'',10,10,28,5,5,5,'THSaraban');
	$mpdf-> SetAutoFont();
	$mpdf-> SetHTMLHeader('<table cellspacing="0" cellpadding="0" style="width:100%;"><tr>			
			<td colspan="4" style="vertical-align:bottom;padding-bottom:2%;font-family:Arial;border-bottom:1px solid #000;"><span style="font-size:1.2em;"><b>บริษัท ซีดีไอพี (ประเทศไทย) จำกัด</b><br>
			<b>CDIP (Thailand) Co.,Ltd.</b></span><br>
			<span style="font-size:0.5em;">131 อาคารกลุ่มนวัตกรรม1 ห้อง227 อุทยานวิทยาศาสตร์ประเทศไทย ถ.พหลโยธิน ต.คลองหนึ่ง อ.คลองหลวง จ.ปทุมธานี 12120<br>
			131 INC1  No.227  Thailand Science park  Paholyothin Rd.  Klong1  Klong Luang  Pathumthani  12120  THAILAND  Tel: 0 2564 7200 # 5227 Fax: 0 2564 7745</span> 
			</td>
			<td colspan="2" style="padding-bottom: 2%;border-bottom:1px solid #000;"><img src="img/logo.png" style="width:15%;"></td>
		</tr></table>');
	$mpdf-> WriteHTML($html);
	$mpdf-> Output("roc/aaa.pdf","I");
?>