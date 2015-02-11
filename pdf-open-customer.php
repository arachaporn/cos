<?php
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
ob_start();
?>
<!DOCTYPE html>
<body>
	<?php
	$id=$_GET["id_u"];
	$sql="select * from company where id_company='".$id."'";
	$res=mysql_query($sql) or die ('Error '.$sql);
	$rs=mysql_fetch_array($res);

	$sql_type_product="select * from type_product where id_type_product='".$rs['id_type_product']."'";
	$res_type_product=mysql_query($sql_type_product) or die ('Error '.$sql_type_product);
	$rs_type_product=mysql_fetch_array($res_type_product);
				
	$sql_product="select * from product where id_product='".$rs['id_product']."'";
	$res_product=mysql_query($sql_product) or die ('Error '.$sql_product);
	$rs_product=mysql_fetch_array($res_product);
	?>
	<table style="width:100%;font-size: 0.8em; line-height: 1.2em; text-align:left;" cellpadding="0" cellspacing="0">
		<tr>
			<tr>
				<td colspan="4"><strong>บริษัท ซีดีไอพี (ประเทศไทย) จำกัด</strong></td>
			</tr>
			<tr>
				<td style="vertical-align: middle;" colspan="4">247/1 ซ.สาธุประดิษฐ์ 58 แขวงบางโพงพาง เขตยานนาวา กทม. 10120</td>
				<td colspan="3">รหัส<input type="text" style="height: 2em; padding: 0;"></td>
			</tr>
			<tr>
				<td colspan="4">โทรศัพท์ 0-2564-7200 ต่อ 5227 โทรสาร 0-2564-7745</td>
				<td colspan="3">วันที่<input type="text" value="
					<?php 
					if($rs['create_date'] != ''){
						list($ckyear,$ckmonth,$ckday) = split('[/.-]', $rs['create_date']); 
						echo $ckstart= $ckday . "/". $ckmonth  . "/" .$ckyear;
					}else{echo '';}
					?>">
				</td>
			</tr>
		</tr>
	</table>
	<table style="width:100%;font-size: 0.9em; line-height: 1.5em; text-align:left;" cellpadding="0" cellspacing="0">
		<tr>
			<td colspan="9" style="text-align:center;font-size:1.2em;padding:0.5%;"><strong>ใบคำขอเปิดหน้าบัญชี - CUSTOMER</strong></td>
		</tr>
		<tr>
			<td colspan="9" style="font-size:1.0em;font-weight:bold;">1. รายละเอียดผู้ซื้อสินค้า</td>
		</tr>
		<?php
			$j_type_company=0;
			$rows_type_company=0;									
			$sql_type_company="select * from company_type";
			$res_type_company=mysql_query($sql_type_company) or die ('Error '.$sql_type_company);
			$max_type_company=mysql_num_rows($res_type_company);
			while($rs_type_company=mysql_fetch_array($res_type_company)){
				if($rows_type_company % 4 ==0){?><tr><?php }
					$j_type_company++;
			?>			
			<td style="width:2%;"><?php if($rs['id_type_company']==$rs_type_company['id_type_company']){echo '<img src="img/tick.png" style="width:15px;margin-bottom:12px;">';}else{?><input type="checkbox"><?php }?></td>
			<td style="width:14%;padding:0 0 1.2% 0;margin:0;"><?php echo $rs_type_company['title_type_company']?></td>
			<?php if($j_type_company % 4 == 0){ ?></tr><?php } 
				$rows_type_company++;
			}//end while type company
			if($max_type_company==$rows_type_company){
		?>
			<td style="width:2%;"><?php if($rs['id_type_company']== -1){echo '<img src="img/tick.png" style="width:15px;margin-bottom:12px;">';}else{?><input type="checkbox"><?php }?></td>
			<td style="width:25%;padding:0 0 1% 0;margin:0;vertical-align:middle;">อื่น ๆ<?php if($rs['other_company_type']==''){echo '<b>...................................</b>';}else{echo '&nbsp;&nbsp;<span style="color:#0D6BF8;"><b>'.$rs['other_company_type'].'</b></span>';}?></td>
			<td style="width:30%;padding:0 0 1% 0;margin:0;">ทะเบียนการค้าเลขที่&nbsp;&nbsp;<?php if($rs['trade_regis']==''){echo '<b>..........................</b>';}else{echo '<span style="color:#0D6BF8;"><b>'.$rs['trade_regis'].'</b></span>';}?></td>
		<?php }//end if?>
	</table>
	<table style="width:100%;font-size: 0.8em; line-height: 1.5em; text-align:left;" cellpadding="0" cellspacing="0">	
		<tr>
			<td colspan="2">ชื่อกิจการ<?php if($rs['company_name']==''){echo  '<b>..........................................................................................</b>';}else{echo '&nbsp;&nbsp;<span style="color:#0D6BF8;"><b>'.$rs['company_name'].'</b></span>';}?></td>
			<td colspan="2">สนญ./สาขา<?php if($rs['branch_name']==''){echo  '<b>.................................................................................</b>';}else{echo '&nbsp;&nbsp;<span style="color:#0D6BF8;"><b>'.$rs['branch_name'].'</b></span>';}?></td>
		</tr>
		<?php
			$sql_address="select * from company_address where id_address='".$rs['id_address']."'";
			$res_address=mysql_query($sql_address) or die ('Errro '.$sql_address);
			$rs_address=mysql_fetch_array($res_address);
		?>
	</table>
	<table style="width:100%;font-size: 0.8em; line-height: 1.5em; text-align:left;" cellpadding="0" cellspacing="0">	
		<tr>
			<td style="width:33.33%;vertical-align:top;">ที่อยู่เลขที่<?php if($rs_address['address_no']==''){echo '<b>............................................................</b>';}else{echo '&nbsp;&nbsp;<span style="color:#0D6BF8;"><b>'.$rs_address['address_no'].'</b></span>';}?></td>
			<td style="width:33.33%;vertical-align:top;">ถนน<?php if($rs_address['road']==''){echo '<b>....................................................................</b>';}else{echo '&nbsp;&nbsp;<span style="color:#0D6BF8;"><b>'.$rs_address['road'].'</b></span>';}?></td>
			<td style="width:33.33%;vertical-align:top;">ตำบล/แขวง<?php if($rs_address['sub_district']==''){echo '<b>.........................................................</b>';}else{echo '&nbsp;&nbsp;<span style="color:#0D6BF8;"><b>'.$rs_address['sub_district'].'</b></span>';}?></td>
		</tr>
		<tr>			
			<td>อำเภอ/เขต<?php if($rs_address['district']==''){echo '<b>...........................................................</b>';}else{echo '&nbsp;&nbsp;<span style="color:#0D6BF8;"><b>'.$rs_address['district'].'</b></span>';}?></td>
			<td>จังหวัด<?php if($rs_address['province']==''){echo '<b>.................................................................</b>';}else{echo '&nbsp;&nbsp;<span style="color:#0D6BF8;"><b>'.$rs_address['province'].'</b></span>';}?></td>
			<td>รหัสไปรษณีย์<?php if($rs_address['postal_code']==''){echo '<b>......................................................</b>';}else{echo '&nbsp;&nbsp;<span style="color:#0D6BF8;"><b>'.$rs_address['postal_code'].'</b></span>';}?></td>
		</tr>
		<tr>
			<td>โทรศัพท์<?php if($rs['company_tel']==''){echo '<b>..............................................................</b>';}else{echo '&nbsp;&nbsp;<span style="color:#0D6BF8;"><b>'.$rs['company_tel'].'</b></span>';}?></td>
			<td>โทรสาร<?php if($rs['company_fax']==''){echo '<b>................................................................</b>';}else{echo '&nbsp;&nbsp;<span style="color:#0D6BF8;"><b>'.$rs['company_fax'].'</b></span>';}?></td>
			<td colspan="2">E-mail<?php if($rs['company_fax']==''){echo '<b>................................................................</b>';}else{echo '&nbsp;&nbsp;<span style="color:#0D6BF8;"><b>'.$rs['company_fax'].'</b></span>';}?></td>
		</tr>
	</table>
	<table style="width:100%;font-size: 0.8em;line-height:1.5em;text-align:left;" cellpadding="0" cellspacing="0">
		<?php
		$sql_contact="select * from company_contact where id_company='".$id."'";
		$res_contact=mysql_query($sql_contact) or die ('Error '.$sql_contact);
		while($rs_contact=mysql_fetch_array($res_contact)){
			if($rs_contact['department']=='บัญชีการเงิน'){
				$bank=$rs_contact['id_contact'];
				$bank_name=$rs_contact['contact_name'];
				$bank_position=$rs_contact['contact_position'];
				$bank_mobile=$rs_contact['mobile'];
				$bank_mail=$rs_contact['email'];
			}else{
				$pur=$rs_contact['id_contact'];
				$pur_name=$rs_contact['contact_name'];
				$pur_position=$rs_contact['contact_position'];
				$pur_mobile=$rs_contact['mobile'];
				$pur_mail=$rs_contact['email'];
			}
		}
		?> 
		<tr>
			<td style="width:50%;">ชื่อผู้ติดต่อ/แผนกจัดซื้อ<?php if($pur_name == ' '){echo '<b>..............................................................................</b>';}else{echo '&nbsp;&nbsp;<span style="color:#0D6BF8;"><b>';echo $pur_name;echo '</b></span>';}?></td>
			<td style="width:25%;">ตำแหน่ง<?php if(($pur_position == ' ') || ($pur_name == ' ')){echo '<b>..........................................</b>';}else{echo '&nbsp;&nbsp;<span style="color:#0D6BF8;"><b>'.$pur_position.'</b></span>';}?></td>
			<td style="width:25%;">มือถือ<?php if($pur_mobile == ' '){echo '<b>..............................................</b>';}else{echo '&nbsp;&nbsp;<span style="color:#0D6BF8;"><b>'.$pur_mobile.'</b></span>';}?></td>
		</tr>
		<tr>
			<td>ชื่อผู้ติดต่อ/แผนกบัญชีการเงิน<?php if($bank_name == ''){echo '<b>...................................................................</b>';}else{echo '&nbsp;&nbsp;<span style="color:#0D6BF8;"><b>'.$bank_name.'</b></span>';}?></td>
			<td>ตำแหน่ง<?php if(($bank_position == '') || ($bank_name == '')){echo '<b>..........................................</b>';}else{echo '&nbsp;&nbsp;<span style="color:#0D6BF8;"><b>'.$bank_position.'</b></span>';}?></td>
			<td>มือถือ<?php if($bank_mobile == ''){echo '<b>..............................................</b>';}else{echo '&nbsp;&nbsp;<span style="color:#0D6BF8;"><b>'.$bank_mobile.'</b></span>';}?></td>
		</tr>
	</table>
	<table style="width:100%;line-height: 1.0em; text-align:left;" cellpadding="0" cellspacing="0">
		<tr>
			<td colspan="4" style="font-size:1.0em;font-weight:bold;"><h5>2. ประเภทกิจการ</h5></td>
		</tr>
		<?php
		$rows_com_cate=0;									
		$sql_com_cate="select * from company_category";
		$res_com_cate=mysql_query($sql_com_cate) or die ('Error '.$sql_com_cate);
		$max_com_cate=mysql_num_rows($res_com_cate);
		while($rs_com_cate=mysql_fetch_array($res_com_cate)){
			if($rows_com_cate % 4 == 0){?><tr><?php }
		?>
			<td style="width:2%;"><?php if($rs['id_com_cat']==$rs_com_cate['id_com_cat']){echo '<img src="img/tick.png" style="width:15px;margin-bottom:6px;">';}else{?><input type="checkbox"><?php }?></td>
			<td style="font-size:0.8em;padding:0 0 0;"><?php echo $rs_com_cate['title']?></td>
		<?php if($rows_com_cate % 4 == 0){ ?></tr><?php } 
			$rows_com_cate++;
		}//end while type company
		if($max_com_cate==$rows_com_cate){
		?>
			<td style="width:2%;"><?php if($rs['id_com_cat']== -1){echo '<img src="img/tick.png" style="width:15px;margin-bottom:6px;">';}else{?><input type="checkbox"><?php }?></td>
			<td style="font-size:0.8em;width:20%;padding:0;">อื่น ๆ<?php if($rs['other_company_cate']==''){echo '<b>...................................</b>';}else{echo '&nbsp;&nbsp;<span style="color:#0D6BF8;"><b>'.$rs['other_company_cate'].'</b></span>';}?></td>
		<?php }//end if?>
	</table>
	<table style="width:100%;line-height: 1.0em; text-align:left;" cellpadding="0" cellspacing="0">
		<tr>
			<td colspan="4" style="font-size:1.0em;font-weight:bold;"><h5>3. ประเภทสินค้าที่ซื้อ</h5></td>
		</tr>
		<tr>
		<?php
		$j_cate_bought=0;
		$rows_cate_bought=0;									
		$sql_cate_bought="select * from category_bought";
		$res_cate_bought=mysql_query($sql_cate_bought) or die ('Error '.$sql_cate_bought);
		$max_cate_bought=mysql_num_rows($res_cate_bought);
		while($rs_cate_bought=mysql_fetch_array($res_cate_bought)){
			if($rows_cate_bought % 4 ==0){?><tr><?php }
			$j_cate_bought++;
		?>
			<td style="font-size:0.8em;width:2%;"><?php if($rs['id_cate_bought']==$rs_cate_bought['id_cate_bought']){echo '<img src="img/tick.png" style="width:15px;margin-bottom:2px;">';}else{?><input type="checkbox"><?php }?></td>
			<td style="font-size:0.8em;width:20%;"><?php echo $rs_cate_bought['title_bought']?></td>
		<?php if($j_cate_bought % 4 == 0){ ?></tr><?php } 
			$rows_cate_bought++;
		}//end while type company
		if($max_cate_bought==$rows_cate_bought){
		?>
			<td style="font-size:0.8em;width:2%;"><?php if($rs['id_cate_bought']== -1){echo '<img src="img/tick.png" style="width:15px;margin-bottom:2px;">';}else{?><input type="checkbox"><?php }?></td>
			<td style="font-size:0.8em;width:20%;">อื่น ๆ<?php if($rs['other_bought']==''){echo '<b>..................................</b>';}else{echo '&nbsp;&nbsp;<span style="color:#0D6BF8;"><b>'.$rs['other_bought'].'</b></span>';}?></td>
		<?php }//end if?>
		</tr>
	</table>
	<table style="width:100%;line-height: 1.2em; text-align:left;" cellpadding="0" cellspacing="0">
		<tr>
			<td colspan="8" style="font-size:1.0em;font-weight:bold;"><h5>4. เอกสารประกอบการค้า</h5></td>
		</tr>
		<?php $type_file=split(",",$rs['type_file']);?>
		<tr>
			<td style="font-size:0.8em;width:2%;"><?php if($rs['person_type']==1){echo '<img src="img/tick.png" style="width:15px;margin-bottom:2px;">';}else{?><input type="checkbox"><?php }?></td>
			<td colspan="7" style="font-size:0.8em;width:20%;">บุคคล</td>
		</tr>
		<tr>
			<td style="font-size:0.8em;width:2%;"><?php if(in_array('1',$type_file)){echo '<img src="img/tick.png" style="width:15px;margin-bottom:2px;">';}else{?><input type="checkbox"><?php }?></td>
			<td style="font-size:0.8em;width:20%;">สำเนาบัตรประชาชน</td>
			<td style="font-size:0.8em;width:2%;"><?php if(in_array('2',$type_file)){echo '<img src="img/tick.png" style="width:15px;margin-bottom:2px;">';}else{?><input type="checkbox"><?php }?></td>
			<td style="font-size:0.8em;width:20%;">สำเนาทะเบียนบ้าน</td>
			<td style="font-size:0.8em;width:2%;"><?php if(in_array('3',$type_file)){echo '<img src="img/tick.png" style="width:15px;margin-bottom:2px;">';}else{?><input type="checkbox"><?php }?></td>
			<td style="font-size:0.8em;width:20%;">ทะเบียนการค้า</td>
			<td style="font-size:0.8em;width:2%;"><?php if(in_array('4',$type_file)){echo '<img src="img/tick.png" style="width:15px;margin-bottom:2px;">';}else{?><input type="checkbox"><?php }?></td>
			<td style="font-size:0.8em;width:20%;">แผนที่ตั้งกิจการและที่ส่งของ</td>
		</tr>
		<tr>
			<td style="font-size:0.8em;width:2%;"><?php if($rs['person_type']==2){echo '<img src="img/tick.png" style="width:15px;margin-bottom:2px;">';}else{?><input type="checkbox"><?php }?></td>
			<td colspan="7" style="font-size:0.8em;width:20%;">นิติบุคคล</td>
		</tr>
		<tr>
			<td style="font-size:0.8em;width:2%;"><?php if(in_array('5',$type_file)){echo '<img src="img/tick.png" style="width:15px;margin-bottom:2px;">';}else{?><input type="checkbox"><?php }?></td>
			<td style="font-size:0.8em;width:20%;">ภ.พ. 20 หรือ ภ.พ. 09</td>
			<td style="font-size:0.8em;width:2%;"><?php if(in_array('6',$type_file)){echo '<img src="img/tick.png" style="width:15px;margin-bottom:2px;">';}else{?><input type="checkbox"><?php }?></td>
			<td style="font-size:0.8em;width:20%;">หนังสือรับรองบริษัท (ไม่เกิน 6 เดือน)</td>
			<td style="font-size:0.8em;width:2%;"><?php if(in_array('7',$type_file)){echo '<img src="img/tick.png" style="width:15px;margin-bottom:2px;">';}else{?><input type="checkbox"><?php }?></td>
			<td style="font-size:0.8em;width:20%;">แผนที่ตั้งกิจการและที่ส่งของ</td>
		</tr>
	</table>
	<table style="width:100%;line-height: 1.4em; text-align:left;" cellpadding="0" cellspacing="0">
		<tr>
			<td colspan="2" style="font-size:1.0em;font-weight:bold;"><h5>5. เงื่อนไขการวางบิลรับเช็คของลูกค้าเครดิต</h5></td>
		</tr>
		<?php
		$j_company_pay=0;
		$sql_company_pay="select * from company_pay";
		$res_company_pay=mysql_query($sql_company_pay) or die ('Error '.$sql_ccompany_pay);
		while($rs_company_pay=mysql_fetch_array($res_company_pay)){
			if($rows_company_pay % 2 ==0){?><tr><?php }
			$j_company_pay++;
		?>
			<td style="font-size:0.8em;width:2%;vertical-align:top;"><?php if($rs['id_company_pay']==$rs_company_pay['id_company_pay']){echo '<img src="img/tick.png" style="width:15px;margin-bottom:0px;">';}else{?><input type="checkbox"><?php }?></td>
			<td style="font-size:0.8em;width:50%;"><?php echo $rs_company_pay['title_pay']?></td>
		<?php if($j_company_pay % 2 == 0){ ?></tr><?php } 
			$rows_company_pay++;
		}//end while type company
		?>
	</table>
	<table style="width:100%;line-height: 1.2em; text-align:left;" cellpadding="0" cellspacing="0">
		<tr>
			<td colspan="2" style="font-size:1.0em;font-weight:bold;"><h5>6. ระบบวางบิลและรับเช็ค</h5></td>
		</tr>
		<tr>
			<td style="font-size:0.8em;width:20%;padding:0;margin:0;">วางบิลวันที่&nbsp;&nbsp;<?php if($rs['pay_date']==''){echo '<b>....................</b>';}else{echo '<span style="color:#0D6BF8;"><b>'.$rs['pay_date'].'</b></span>';}?>&nbsp;&nbsp;ของเดือน</td>
			<td style="font-size:0.8em;width:20%;padding:0;margin:0;">รับเช็ค/โอนเงินวันที่&nbsp;&nbsp;<?php if($rs['checkpay_date']==''){echo '<b>....................</b>';}else{echo '<span style="color:#0D6BF8;"><b>'.$rs['checkpay_date'].'</b></span>';}?>&nbsp;&nbsp;ของเดือน</td>
		</tr>
	</table>
	<table style="width:100%;line-height: 1.2em; text-align:left;" cellpadding="0" cellspacing="0">
		<tr>
			<td colspan="4" style="font-size:1.0em;font-weight:bold;"><h5>7. วิธีการชำระเงิน</h5></td>
		</tr>	
		<tr>
			<td style="font-size:0.8em;width:2%;"><?php if($rs['type_pay']==1){echo '<img src="img/tick.png" style="width:15px;margin-bottom:2px;">';}else{?><input type="checkbox"><?php }?></td>
			<td style="font-size:0.8em;width:20%;">เงินสด</td>
			<td style="font-size:0.8em;width:2%;"><?php if($rs['type_pay']==2){echo '<img src="img/tick.png" style="width:15px;margin-bottom:2px;">';}else{?><input type="checkbox"><?php }?></td>
			<td style="font-size:0.8em;width:20%;">เช็คสั่งจ่ายในนาม " บริษัท ซีดีไอพี (ประเทศไทย) จำกัด "</td>
		</tr>
		<tr>
			<td style="font-size:0.8em;width:2%;vertical-align:top;"><?php if($rs['type_pay']==3){echo '<img src="img/tick.png" style="width:15px;margin-bottom:2px;">';}else{?><input type="checkbox"><?php }?></td>
			<td colspan="3" style="font-size:0.8em;width:20%;">โอนเงินผ่านบัญชีบริษัท ซีดีไอพี (ประเทศไทย) จำกัด
				<p>บัญชีธนาคารทหารไทย เลขที่บัญชี 073-1055-703 ชื่อบัญชี บริษัท ซีดีไอพี (ประเทศไทย) จำกัด สาขาสาธุประดิษฐ์ </p>
			</td>									
		</tr>
	</table>
	<table style="width:100%;line-height: 1.4em; text-align:left;" cellpadding="0" cellspacing="0">
		<tr>
			<td style="font-size:0.8em;vertical-align:middle;padding:1% 3% 0;">&nbsp;&nbsp;&nbsp;&nbsp;ลงชื่อ<b>...........................................................</b>ลูกค้า<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>(.........................................................)</b>&nbsp;&nbsp;&nbsp;&nbsp;ประทับตราบริษัท (ถ้ามี)<br>
				ตำแหน่ง&nbsp;<b>.......................................................</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="img/border.png" style="vertical-align:top;width:10%;">
			</td>
			<td style="font-size:0.8em;vertical-align:top;padding:1% 3% 0;"><br>&nbsp;<b>.........................................................</b><br>
				<b>(...........................................................)</b><br>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;พนักงานขาย<br><br>
				&nbsp;<b>.........................................................</b><br>
				<b>(...........................................................)</b><br>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ผู้จัดการฝ่ายขาย
			</td>
		</tr>
	</table>
	<table style="width:100%;font-size:0.8em;line-height:1.4em;" cellspacing="0" cellpadding="0">
		<tr>
			<td colspan="2" style="font-weight:bold;text-align:center;">ห้ามพิมพ์หรือเขียนข้อความใด ๆ ใต้เส้นนี้ Please do not write or Print below this line</td>
		</tr>
		<tr>
			<td rowspan="2" style="border-left:1px solid #000;border-top:1px solid #000;border-bottom:1px solid #000;padding: 2%;width:50%;">
				<span style="font-weight:bold;">ส่วนของบริษัท</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ในกรณีขายเครดิต<br>
				เรียน&nbsp;&nbsp;&nbsp;ฝ่ายบัญชี&nbsp;&nbsp;&nbsp;การเงิน<br>
				<span style="font-weight:bold;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;นำเสนอขายเป็นเครดิต</span><br>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;วงเงิน <b>........................................................</b>บาท<br>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ระยะเวลา <b>.......................................................</b>วัน<br>
				ความเห็นในการนำเสนอ <b>................................................................................................................</b><br>
				<b>................................................................................................................</b>				
				<br><br>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>......................................</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>.......................................</b><br>
				&nbsp;&nbsp;&nbsp;&nbsp;(<b>........................................</b>)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(<b>........................................</b>)<br>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ผู้ขอเสนอ/ผุ้จัดการฝ่ายขาย&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ผู้ทบทวน/ผู้จัดการทั่วไป
			</td>
			<td style="border-left:1px solid #000;border-top:1px solid #000;border-bottom:1px solid #000;border-right:1px solid #000;padding: 2% 2% 0 2%;width:50%;vertical-align:top;">
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<u>การอนุมัติ</u><br>
				&nbsp;&nbsp;<input type="checkbox">&nbsp;อนุมัติ&nbsp;&nbsp;วงเงิน <b>........................................</b> บาท <br>
				&nbsp;&nbsp;<input type="checkbox">&nbsp;ไม่อนุมัติ&nbsp;&nbsp;โดยให้ขายเป็นเงินสด<br>
				&nbsp;&nbsp;<input type="checkbox">&nbsp;อื่น ๆ <b>.................................................</b><br>
				<br>		
				ผู้อนุมัติ <b>..........................................</b>&nbsp;&nbsp;(ผู้จัดการฝ่ายบัญชี/การเงิน)<br>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(<b>.........................................</b>)
			</td>
		</tr>
		<tr>
			<td style="border-left:1px solid #000;border-bottom:1px solid #000;border-right:1px solid #000;padding:1% 2%;width:50%;vertical-align:top;">
				&nbsp;&nbsp;การบันทึกฐานข้อมูล<br>
				&nbsp;&nbsp;ลงชื่อ <b>...........................................</b>&nbsp;ผู้บันทึก&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ว/ด/ป<b>....................</b>  
				<br>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(<b>..........................................</b>)
			</td>
		</tr>
	</table>
</body>
</html>
<?php
	$html = ob_get_contents();
	ob_end_clean();
	$mpdf=new mPDF('th','A4',0,'',15,15,5,10,10,10,'THSaraban');
	$mpdf-> SetAutoFont();
	/*$mpdf-> SetHTMLHeader('<table style="width:100%;line-height:1.2em;" cellspacing="0" cellpadding="0"><tr>
			<td colspan="1" style="padding:1%;border-top:1px solid #000;border-left:1px solid #000;border-bottom:1px solid #000;"><img src="img/logo.png" style="width:20%;"></td>
			<td colspan="6" style="vertical-align:middle;border-top:1px solid #000;border-right:1px solid #000;font-family:Arial;padding:0 10% 0 0;text-align:center;font-size:0.9em;border-bottom:1px solid #000;">บริษัท ซีดีไอพี (ประเทศไทย) จำกัด<br>
			CDIP (Thailand) Co.,Ltd.<br>
			บันทึกความต้องการของลูกค้า (Requisition of Customer)
			</td>
		</tr></table>');*/
	$mpdf-> WriteHTML($html);
	$mpdf-> Output("roc/aaa.pdf","I");
?>