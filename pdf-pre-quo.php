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
	$sql="select * from quotation where id_quotation='".$id."'";
	$res=mysql_query($sql) or die ('Error '.$sql);
	$rs=mysql_fetch_array($res);

	$sql_product="select * from product where id_product='".$rs['id_product']."'";
	$res_product=mysql_query($sql_product) or die ('Error '.$sql_product);
	$rs_product=mysql_fetch_array($res_product);

	$sql_acc="select * from account where id_account='".$rs['create_by']."'";
	$res_acc=mysql_query($sql_acc) or die ('Error '.$sql_acc);
	$rs_acc=mysql_fetch_array($res_acc);

	?>
	<input type="hidden" name="quo" value="<?php echo $rs_quo['id_costing_quotation']?>">
	<table style="border: none; width: 100%; line-height:2.5em;" cellpadding="0" cellspacing="0">
		<tr>
			<td colspan="7" style="font-size:2.5em; padding:3.5% 0 2% 0; text-align:center;"><b>ใบเสนอราคา</b></td>
		</tr>
		<tr>
			<td rowspan="2" style="font-size:1.7em; text-align:left; padding:1.7%; border-left:1px solid #000; border-top:1px solid #000;border-right:1px solid #000;">ชื่อบริษัท</td>
			<td rowspan="2" colspan="2" style="font-size:1.7em; text-align:left; padding:1.7%; border-top:1px solid #000;border-right:1px solid #000;">
				<?php
				if($rs['company_name']==''){
					$sql_company="select * from company where id_company='".$rs['id_company']."'";
					$res_company=mysql_query($sql_company) or die ('Error '.$sql_company);
					$rs_company=mysql_fetch_array($res_company);
					echo $rs_company['company_name'];
				}else{echo $rs['company_name'];}
				?>
			</td>												
			<td colspan="2" style="font-size:1.7em; text-align:left; padding:1.7%; border-top:1px solid #000; border-right:1px solid #000;">เลขที่ใบเสนอราคา </td>
			<td style="font-size:1.7em; text-align:left; padding:1.7%; border-top:1px solid #000; border-right:1px solid #000;"><?php echo $rs['quotation_no']?></td>
		</tr>
		<tr>
			<td colspan="2" style="font-size:1.7em; text-align:left; padding:1.7%; border-right:1px solid #000;">วันที่</td>
			<td colspan="3" style="font-size:1.7em; text-align:left; padding:1.7%; border-right:1px solid #000;"><?php echo date("d/m/Y")?></td>
		</tr>
		<tr>
		<?php
		$sql_contact="select * from company_contact where id_company='".$rs_company['id_company']."'";
		$res_contact=mysql_query($sql_contact) or die ('Error '.$sql_contact);
		$rs_contact=mysql_fetch_array($res_contact);
		?>
			<td style="font-size:1.7em; text-align:left; padding:1.7%; border-left:1px solid #000;border-right:1px solid #000;">ชื่อผู้ติดต่อ/เบอร์ </td>
			<td colspan="2" style="font-size:1.7em; text-align:left; padding:1.7%;border-right:1px solid #000; vertical-align:top;"><?php if($rs['contact_name']==''){echo $rs_contact['contact_name'];}else{echo $rs['contact_name'];}?></td>
			<td colspan="2" style="font-size:1.7em; text-align:left; padding:1.7%;border-right:1px solid #000;">ผู้เสนอราคา</td>
			<td style="font-size:1.7em; text-align:left; padding:1.7%;border-right:1px solid #000;"><?php echo $rs_acc['name']?></td>
		</tr>
		<tr>
		<?php
		$sql_address="select * from company_address where id_address='".$rs_company['id_address']."'";
		$res_address=mysql_query($sql_address) or die ('Error '.$sql_address);
		$rs_address=mysql_fetch_array($res_address);
		?>
			<td rowspan="2" style="font-size:1.7em; text-align:left; padding:1.7%; border-left:1px solid #000; border-right:1px solid #000;">ที่อยู่</td>
			<td rowspan="2" colspan="2" style="font-size:1.7em; text-align:left; padding:1.7%; border-right:1px solid #000;"><?php if($rs['address']==''){echo $rs_address['address_no'].'&nbsp;&nbsp;'.$rs_address['road'].'&nbsp;&nbsp;'.$rs_address['sub_district'].'&nbsp;&nbsp;'.$rs_address['district'].'&nbsp;&nbsp;'.$rs_address['province'].'&nbsp;&nbsp;'.$rs_address['postal_code'];}else{echo $rs['address'];}?></td>
			<td colspan="2" style="font-size:1.7em; text-align:left; padding:1.7%; border-right:1px solid #000;">ระยะเวลาในการส่งของ</td>
			<td style="font-size:1.7em; text-align:left; padding:1.7%; border-right:1px solid #000;">75-90 วัน</td>
		</tr>
		<tr>
			<td colspan="2" style="font-size:1.7em; text-align:left; padding:1.7%; border-right:1px solid #000;">จำนวนวันเครดิต</td>
			<td style="font-size:1.7em; text-align:left; padding:1.7%; border-right:1px solid #000;">0 วัน</td>
		</tr>
		<tr>
			<td style="font-size:1.7em; text-align:left; padding:1.7%; border-left:1px solid #000; border-bottom:1px solid #000; border-right:1px solid #000;">E-Mail:</td>
			<td colspan="2" style="font-size:1.7em; text-align:left; padding:1.7%; border-bottom:1px solid #000; border-right:1px solid #000;"><?php if($rs['email']==''){echo $rs_contact['email'];}else{echo $rs['email'];}?></td>
			<td colspan="2" style="font-size:1.7em; text-align:left; padding:1.7%; border-bottom:1px solid #000; border-right:1px solid #000;">กำหนดยืนราคา</td>
			<td style="font-size:1.7em; text-align:left; padding:1.7%; border-bottom:1px solid #000; border-right:1px solid #000;">30วันนับจากวันที่เสนอราคา</td>
		</tr>
		<tr>
			<td colspan="7">&nbsp;</td>
		</tr>
		<tr>
			<td style="font-size:1.7em; text-align:center; padding:1.7%; border-top:1px solid #000; border-left:1px solid #000; border-bottom:1px solid #000; border-right:1px solid #000; width:20%;">ลำดับที่</td>
			<td style="font-size:1.7em; text-align:center; padding:1.7%; border-top:1px solid #000; border-bottom:1px solid #000; border-right:1px solid #000; width:80%;">รายการ</td>
			<td style="font-size:1.7em; text-align:center; padding:1.7%; border-top:1px solid #000; border-bottom:1px solid #000; border-right:1px solid #000; width:20%;">จำนวน</td>
			<td style="font-size:1.7em; text-align:center; padding:1.7%; border-top:1px solid #000; border-bottom:1px solid #000; border-right:1px solid #000; width:20%;">หน่วยนับ</td>
			<td style="font-size:1.7em; text-align:center; padding:1.7%; border-top:1px solid #000; border-bottom:1px solid #000; border-right:1px solid #000; width:20%;">ราคา/หน่วย</td>
			<td style="font-size:1.7em; text-align:center; padding:1.7%; border-top:1px solid #000; border-bottom:1px solid #000; border-right:1px solid #000;">จำนวนเงิน</td>
		</tr>
		<?php 
		$a=0;
		$sql_quo_relationship="select * from quotation_relationship where id_quotation='".$id."' order by id_quo_relation asc";
		$res_quo_relationship=mysql_query($sql_quo_relationship) or die ('Error '.$sql_quo_relationship);
		$num_row=mysql_num_rows($res_quo_relationship);
		?>
		<tr>
			<td style="font-size:1.7em; text-align:center; padding:1.7%; border-left:1px solid #000;border-right:1px solid #000; vertical-align: top;" rowspan="<?php echo $num_row+2;?>">1</td>
			<td style="font-size:1.7em; text-align:left; padding:1.7% 1.7% 0;border-right:1px solid #000;">ก่อนผลิตภัณฑ์</td>			
			<td style="vertical-align: top; font-size:1.7em; text-align:center; padding:1.7%; border-top:1px solid #000; border-right:1px solid #000;"></td>
			<td style="vertical-align: top; font-size:1.7em; text-align:center; padding:1.7%; border-top:1px solid #000; border-right:1px solid #000;"></td>
			<td style="vertical-align: top; font-size:1.7em; text-align:center; padding:1.7%; border-top:1px solid #000; border-right:1px solid #000;"></td>
			<td style="vertical-align: top; font-size:1.7em; text-align:right; padding:1.7% 2.0% 1.7% 1.7%; border-top:1px solid #000;border-right:1px solid #000;"></td>
		</tr>		
		<tr>
			<td style="font-size:1.7em; text-align:left; padding:1.7%;border-right:1px solid #000; vertical-align: top;">
			<?php 
				echo 'ชื่อผลิตภัณฑ์';					
				echo '&nbsp;&nbsp;';		
				echo '<span style="float:left;margin-right:2%;color:#0440DA;">';
				echo $rs_product['product_name'];
				echo '</span>';
				echo '&nbsp;&nbsp;&nbsp;';
				echo '<br>';
			?>
			</td>
			<td style="vertical-align: top; font-size:1.7em; text-align:center;padding:1.7%;border-right:1px solid #000;"></td>
			<td style="vertical-align: top; font-size:1.7em; text-align:center; padding:1.7%;border-right:1px solid #000;"></td>
			<td style="vertical-align: top; font-size:1.7em; text-align:center; padding:1.7%;border-right:1px solid #000;"></td>
			<td style="vertical-align: top; font-size:1.7em; text-align:right; padding:1.7% 2.0% 1.7% 1.7%;border-right:1px solid #000;"></td>
		</tr>
		<?php
		while($rs_quo_relationship=mysql_fetch_array($res_quo_relationship)){		
			$a=10-$num_row;
			$sql_pre_quotation_detail="select * from pre_quotation_detail where id_pre_quotation='".$rs_quo_relationship['id_pre_quotation']."'";
			$res_pre_quotation_detail=mysql_query($sql_pre_quotation_detail) or die ('Error '.$sql_pre_quotation_detail);
			$rs_pre_quotation_detail=mysql_fetch_array($res_pre_quotation_detail);
			
		?>
		<tr>
			<td style="font-size:1.7em; text-align:left; padding:1.7%;border-right:1px solid #000; vertical-align: top;"><?php echo $rs_pre_quotation_detail['title_pre_quotation']?></td>
			<td style="vertical-align: top; font-size:1.7em; text-align:center;padding:1.7%;border-right:1px solid #000;"><?php echo number_format($rs_quo_relationship['num_pre_quotation'],2)?></td>
			<td style="vertical-align: top; font-size:1.7em; text-align:center; padding:1.7%;border-right:1px solid #000;"><?php echo $rs_pre_quotation_detail['unit_pre_quotation']?></td>
			<td style="vertical-align: top; font-size:1.7em; text-align:center; padding:1.7%;border-right:1px solid #000;"><?php echo number_format($rs_quo_relationship['price_product'],2)?></td>
			<td style="vertical-align: top; font-size:1.7em; text-align:right; padding:1.7% 2.0% 1.7% 1.7%;border-right:1px solid #000;"><?php echo number_format($rs_quo_relationship['sum_quotation'],2)?></td>
			<?php }?>
			<?php for($i=0;$i<=$a;$i++){echo '<br>';}?>
		</tr>
		<tr>
			<td rowspan="4" style="text-align:left; padding:1.7%;font-size:1.7em; border-left:1px solid #000;border-top:1px solid #000; border-bottom:1px solid #000;border-right:1px solid #000; vertical-align: top;">หมายเหตุ :</td>
			<td colspan="2" rowspan="4" style="vertical-align: top;font-size:1.7em;text-align:left; padding:1.7%; border-top:1px solid #000; border-bottom:1px solid #000; border-right:1px solid #000;">
				<?php
				$sql_quo_rela="select * from quotation_relationship where id_quotation='".$id."'";
				$res_quo_rela=mysql_query($sql_quo_rela) or die ('Error '.$sql_quo_rela);
				$rs_quo_rela=mysql_fetch_array($res_quo_rela);
				if($rs_quo_rela['id_type_product']==1){
					echo '1.ระยะเวลาในการพัฒนาผลิตภัณฑ์ประมาณ 60-90 วัน';
					echo '<br>';
					echo '2.ระยะเวลาในการจดทะเบียน อย. ประมาณ 90-1,095 วัน โดยเริ่มนับหลังจากวันที่ได้รับเอกสารครบถ้วนจากลูกค้า และได้รับชำระเงินเป็นที่เรียบร้อยแล้ว ไม่รวมค่าตรวจวิเคราะห์ตามประกาศกระทรวงฯ';
				}elseif($rs_quo_rela['id_type_product']==2){
					echo '1.ระยะเวลาในการพัฒนาผลิตภัณฑ์ประมาณ 60-90 วัน';
					echo '<br>';
					echo '2.ระยะเวลาในการจดทะเบียน อย. ประมาณ 90-180 วัน โดยเริ่มนับหลังจากวันที่ได้รับเอกสารครบถ้วนจากลูกค้า และได้รับชำระเงินเป็นที่เรียบร้อยแล้ว ไม่รวมค่าตรวจวิเคราะห์ตามประกาศกระทรวงฯ';
				}elseif($rs_quo_rela['id_type_product']==3){
					echo '1.ระยะเวลาในการพัฒนาผลิตภัณฑ์ประมาณ 30 วัน';
					echo '<br>';
					echo '2.ระยะเวลาในการจดทะเบียน อย. ประมาณ 30-60 วัน โดยเริ่มนับหลังจากวันที่ได้รับเอกสารครบถ้วนจากลูกค้า และได้รับชำระเงินเป็นที่เรียบร้อยแล้ว ไม่รวมค่าตรวจวิเคราะห์ตามประกาศกระทรวงฯ';
				}elseif($rs_quo_rela['id_type_product']==4){
					echo '1.ระยะเวลาในการพัฒนาผลิตภัณฑ์ประมาณ 60-90 วัน ';
					echo '<br>';
					echo '2.ระยะเวลาในการจดทะเบียน อย. ประมาณ 1-2 ปีโดยเริ่มนับหลังจากวันที่ได้รับเอกสารครบถ้วนจากลูกค้า และได้รับชำระเงินเป็นที่เรียบร้อยแล้ว ไม่รวมค่าตรวจวิเคราะห์ตามประกาศกระทรวงฯ';
				}elseif($rs_quo_rela['id_type_product']==5){
					echo '1.ระยะเวลาในการพัฒนาผลิตภัณฑ์ประมาณ 60-90 วัน';
					echo '<br>';
					echo '2.ระยะเวลาในการจดทะเบียน อย. ประมาณ 5-8 ปีโดยเริ่มนับหลังจากวันที่ได้รับเอกสารครบถ้วนจากลูกค้า และได้รับชำระเงินเป็นที่เรียบร้อยแล้ว ไม่รวมค่าตรวจวิเคราะห์ตามประกาศกระทรวงฯ';
				}elseif($rs_quo_rela['id_type_product']== -1){
					echo '1.ระยะเวลาในการทดสอบความคงสภาพของผลิตภัณฑ์ประมาณ 180-190 วัน ';
					echo '<br>';
					echo '2.สามารถยกเลิก PO ได้ภายใน 7 วันหลังบริษัท ซีดีไอพี ได้รับใบสั่งซื้อจากลูกค้า';
				}elseif($rs_quo_rela['id_type_product']== -2){
					echo '1.ระยะเวลาในการดำเนินการประมาณ 60-90 วัน';
					echo '<br>';
					echo '2.การยื่นขอฮาลาลในแต่ละโรงงานจะมีเพียง 2 ครั้งต่อปีเท่านั้น';
					echo '<br>';
					echo '3.สามารถยกเลิก PO ได้ภายใน 7 วันหลังบริษัท ซีดีไอพี ได้รับใบสั่งซื้อจากลูกค้า';
				}elseif($rs_quo_rela['id_type_product']== -3){
					echo '1.ระยะเวลาในการดำเนินการณ์ประมาณ 90-120 วัน';
					echo '<br>';
					echo '2.จำเป็นที่ต้องดำเนินการเนื่องจากใช้ผลเพื่อยื่นในการขึ้นทะเบียน อย.';
					echo '<br>';
					echo '3.สามารถยกเลิก PO ได้ภายใน 7 วันหลังบริษัท ซีดีไอพี ได้รับใบสั่งซื้อจากลูกค้า';
				}
				?>
			</td>
			<td colspan="2" style="vertical-align: top;text-align:left; font-size:1.7em;padding:1.7%; border-top:1px solid #000; border-bottom:1px solid #000; border-right:1px solid #000;">รวมเป็นเงิน</td>
			<td style="vertical-align: top;text-align:right;font-size:1.7em; padding:1.7% 2.0% 1.7% 1.7%; border-top:1px solid #000; border-bottom:1px solid #000; border-right:1px solid #000;">
				<?php 
				$total=0;
				$sql_quo_relationship="select * from quotation_relationship where id_quotation='".$id."' order by id_quo_relation asc";
				$res_quo_relationship=mysql_query($sql_quo_relationship) or die ('Error '.$sql_quo_relationship);
				while($rs_quo_relationship=mysql_fetch_array($res_quo_relationship)){	
					$total=$rs_quo_relationship['sum_quotation']+$total;
				}
				echo number_format($total,2)?>
			</td>
		</tr>
		<tr>												
			<td colspan="2" style="vertical-align: top;text-align:left;font-size:1.7em; padding:1.7%; border-top:1px solid #000; border-bottom:1px solid #000; border-right:1px solid #000;">ส่วนลด</td>
			<td style="vertical-align: top;text-align:right; padding:1.7% 2.0% 1.7% 1.7%;font-size:1.7em; border-top:1px solid #000; border-bottom:1px solid #000; border-right:1px solid #000;"><?php echo number_format($rs['discount'],2)?></td>
		</tr>
		<tr>
			<td colspan="2" style="vertical-align: top;text-align:left; padding:1.7%;font-size:1.7em; border-top:1px solid #000; border-bottom:1px solid #000; border-right:1px solid #000;">รวมเงินหลังหักส่วนลด</td>
			<td style="vertical-align: top; font-size:1.7em; text-align:right; padding:1.7% 2.0% 1.7% 1.7%; border-top:1px solid #000; border-bottom:1px solid #000; border-right:1px solid #000;"><?php echo number_format($rs['total_discount'],2)?></td>
		</tr>
		<tr>
			<td colspan="2" style="vertical-align: top;text-align:left; padding:1.7%;font-size:1.7em; border-top:1px solid #000; border-bottom:1px solid #000; border-right:1px solid #000;">Vat 7%</td>
			<td style="vertical-align: top;text-align:right; padding:1.7% 2.0% 1.7% 1.7%;font-size:1.7em; border-top:1px solid #000; border-bottom:1px solid #000; border-right:1px solid #000;"><?php echo number_format($rs['vat'],2)?></td>
		</tr>
		<tr>
			<td colspan="3" style="vertical-align: top;text-align:center; padding:1.7%; font-size:1.7em;border-left:1px solid #000;border-bottom:1px solid #000; border-right:1px solid #000;">
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
				echo  $x  .convert($rs['total_price']); 
				?>
			</td>
			<td colspan="2" colspan="2" style="vertical-align: top;text-align:left; font-size:1.7em;padding:1.7%; border-bottom:1px solid #000; border-right:1px solid #000;">จำนวนเงินทั้งสิ้น</td>
			<td style="vertical-align: top;text-align:right; padding:1.7% 2.0% 1.7% 1.7%;font-size:1.7em; border-bottom:1px solid #000; border-right:1px solid #000;"><?php echo number_format($rs['total_price'],2)?></td>
		</tr>
	</table>
	<table style="border: none; width: 100%; line-height:1.5em; font-size:0.8em;" cellpadding="0" cellspacing="0">
		<tr>
			<td colspan="2" rowspan="2" style="vertical-align: top; text-align:left; padding:1.7%; border-left:1px solid #000;border-bottom:1px solid #000; border-right:1px solid #000;"><b>เงื่อนไขอื่นๆ</b><br>
				(1) เงื่อนไขการชำระเงิน 100% พร้อมใบเสนอราคา<br>
				(2) กรุณาโอนเงินเข้าบัญชีธนาคารทหารไทย เลขที่บัญชี 073-1-05570-3<br>
					ชื่อบัญชี บริษัท ซีดีไอพี (ประเทศไทย) จำกัด <br>
				(3) ข้อสงสัยเรื่องการชำระเงินกรุณาติดต่อแผนกบัญชี เบอร์ 02 564 7200 ต่อ 5227<br>
				(4) บริษัทฯจะยืนยันวันส่งของกลับภายหลังได้รับใบสั่งซื้อภายใน 7 วันทำการ<br>
				(5) ในกรณีที่มีการยกเลิกใบสั่งซื้อบริษัทฯขอสงวนสิทธิ์ในการคืนเงินมัดจำ<br>
				(6) ผู้ซื้อต้องรับสินค้าทั้งหมดที่ผลิตเสร็จแล้ว ภายใน 7 วัน หลังได้รับแจ้งจาก บริษัท ซีดีไอพีฯ   กรณีรับสินค้าไม่หมด คิดค่าใช้จ่ายในการเก็บรักษาสินค้า วันละ 0.25% ของมูลค่าสินค้าที่ค้างส่ง
			</td>
			<td colspan="2" style="vertical-align: top; text-align:center; padding:1.7% 2.0% 1.7% 1.7%; border-bottom:1px solid #000; border-right:1px solid #000;">ผู้จัดทำ
				<br><br><br><br>............../............../...............<br><?php echo $rs_acc['name']?>
			</td>
			<td colspan="2" rowspan="2" style="vertical-align: top; text-align:center; padding:1.7% 5.0% 1.7% 5.0%;border-bottom:1px solid #000; border-right:1px solid #000;">อนุมัติโดย</div><br><br><br><br>
				......../......../.........<br><br>ลูกค้า
			</td>
		</tr>
		<tr>
			<td colspan="2" style="vertical-align: top; text-align:center; padding:1.7% 2.0% 1.7% 1.7%;border-bottom:1px solid #000; border-right:1px solid #000;">ผู้ตรวจสอบ
				<br><br><br><br>............../............../...............<br>
			</td>
		</tr>
	</table>
</body>
</html>
<?php
	$html = ob_get_contents();
	ob_end_clean();
	$mpdf=new mPDF('th','A4',0,'',10,10,32,5,5,5,'THSaraban');
	$mpdf-> SetAutoFont();
	$mpdf-> SetHTMLHeader('<table cellspacing="0" cellpadding="0"><tr>
			<td colspan="2" style="padding-bottom: 2%;border-bottom:1px solid #000;"><img src="img/logo.png" style="width:20%;"></td>
			<td colspan="4" style="vertical-align:bottom;padding-bottom:2%;font-family:Arial;padding-left:2%;border-bottom:1px solid #000;"><span style="font-size:1.2em;"><b>บริษัท ซีดีไอพี (ประเทศไทย) จำกัด</b><br>
			<b>CDIP (Thailand) Co.,Ltd.</b></span><br>
			<span style="font-size:0.5em;">131 อาคารกลุ่มนวัตกรรม1 ห้อง227 อุทยานวิทยาศาสตร์ประเทศไทย ถ.พหลโยธิน ต.คลองหนึ่ง อ.คลองหลวง จ.ปทุมธานี 12120<br>
			131 INC1  No.227  Thailand Science park  Paholyothin Rd.  Klong1  Klong Luang  Pathumthani  12120  THAILAND  Tel: 0 2564 7200 # 5227 Fax: 0 2564 7745</span> 
			</td>
		</tr></table>');
	$mpdf-> WriteHTML($html);
	$mpdf-> Output("roc/aaa.pdf","I");
?>