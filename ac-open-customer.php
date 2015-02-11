<?php
ob_start();
session_start();
if($_SESSION["Username"] == ""){
	header("location:index.php");
	exit();
}
$_SESSION['start'] = time(); // taking now logged in time
if(!isset($_SESSION['expire'])){
	$_SESSION['expire'] = $_SESSION['start'] + 3600 ; // ending a session in 30 seconds
}
$now = time(); // checking the time now when home page starts

if($now > $_SESSION['expire']){
	session_destroy();
	//echo "Your session has expire !  <a href='logout.php'>Click Here to Login</a>";
}else{
	//echo "This should be expired in 1 min <a href='logout.php'>Click Here to Login</a>";
}
include("connect/connect.php");
$sql_account = "SELECT * FROM account WHERE username = '".$_SESSION["Username"]."'  ";
$res_account = mysql_query($sql_account) or die ('Error '.$sql_account);
$rs_account = mysql_fetch_array($res_account);
?>
<!DOCTYPE html>
<!--[if IE 8]> 				 <html class="no-js lt-ie9" lang="en" > <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en" > <!--<![endif]-->

<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"/>
<title>COS Project</title>
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/foundation.css">
<link rel="stylesheet" href="rmm-css/responsivemobilemenu.css" type="text/css"/>
<script type="text/javascript" src="http://code.jquery.com/jquery.min.js"></script>
<script type="text/javascript" src="rmm-js/responsivemobilemenu.js"></script>
<script src="js/vendor/custom.modernizr.js"></script>
<script language="javascript">
function fncSubmit(){
	document.frm.submit();
}
</script> 
</head>
<body>
	<?php include("menu.php");?>
	<div class="row">
		<div class="background">
			<?php
			include("connect/connect.php");
			if($_GET["id_u"]=='New'){
				$mode='New';
				$button='Save';
				$id='New';
			}
			else{
				$id=$_GET["id_u"];
				$sql="select * from company where id_company='".$id."'";
				$res=mysql_query($sql) or die ('Error '.$sql);
				$rs=mysql_fetch_array($res);
				$mode='Edit '.$rs['company_name'];
				$button='Update';		
			}
			?>
			<form name="frm" method="post" action="dbcustomer.php">
			<input type="hidden" name="hdnCmd" value="">
			<input type="hidden" name="account" value="<?php echo $rs_account['id_account']?>">
			<table style="border: 0; width: 100%;" cellpadding="0" cellspacing="0">
				<tr>
					<td class="b-bottom"><div class="large-4 columns"><h5><h5>ใบคำขอเปิดหน้าบัญชี - Customer >> <?php echo $mode;?></h5></h5></div></td>
				</tr>
				<tr>
					<td class="b-bottom">
						<div class="large-4 columns">
							<?php if(!is_numeric($id)){?>
							<input type="button" name="save_data" id="save_data" value="<?php echo $button?>" class="button-create" OnClick="JavaScript:return fncSubmit();">
							<?php }else{?>
							<input type="button" name="update_data" id="update_data" value="<?php echo $button?>" class="button-create" OnClick="frm.hdnCmd.value='update_data';JavaScript:return fncSubmit();">
							<?php }?>
							<input type="button" name="update_data" id="update_data" value="Print PDF" class="button-create" Onclick="window.open('pdf-open-customer.php?id_u=<?=$id?>')">
							<input type="button" value="Close" class="button-create" onclick="window.location.href='open-customer.php'">
						</div>
					</td>
				</tr>
				<tr>
					<td style="background: #fff;">
						<div class="large-4 columns">						
							<input type="hidden" name="mode" value="<?php echo $id?>">
							<input type="hidden" name="new_customer" value="1">
							<table style="border: none; width: 100%; line-height: 1.5em;" cellpadding="0" cellspacing="0" id="tb-add">
								<tr>
									<td colspan="4"><strong>บริษัท ซีดีไอพี (ประเทศไทย) จำกัด</strong></td>
								</tr>
								<tr>
									<td style="vertical-align: middle;" colspan="4">247/1 ซ.สาธุประดิษฐ์ 58 แขวงบางโพงพาง เขตยานนาวา กทม. 10120</td>
									<td colspan="3"><div style="float: left;margin: 0 2% 0 0;">รหัส</div><div style="float:left;"><input type="text" name="company_code" style="height: 2em; padding: 0;" value="<?php echo $rs['company_code']?>"></div></td>
								</tr>
								<tr>
									<td colspan="4">โทรศัพท์ 0-2564-7200 ต่อ 5227 โทรสาร 0-2564-7745</td>
									<td colspan="3"><div style="float: left;margin: 0 2% 0 0;">วันที่</div><input type="text" id="datepicker" name="create_date" value="<?php 
											if(is_numeric($id)){
												list($ckyear,$ckmonth,$ckday) = split('[/.-]', $rs['create_date']); 
												echo $ckstart= $ckday . "/". $ckmonth . "/" .$ckyear;
											}else{echo date("d/m/Y");}?>" style="width: 50%; float: left; margin-right: 2%;"/>
									</td>
								</tr>	
								<?php if($rs_account['role_user']!=3){?>
								<tr>
									<td colspan="4">Project Manager</p>
									<select name="project_manager" style="widht:auto;">
									<?php
									$sql_account3="select * from account where id_department='1' or id_department='7'";
									$res_account3=mysql_query($sql_account3) or die ('Error '.$sql_account3);
									while($rs_account3=mysql_fetch_array($res_account3)){
									?>
										<option <?php if($rs['create_by']==$rs_account3['id_account']){echo 'selected';}?> value="<?php echo $rs_account3['id_account']?>"><?php echo $rs_account3['username']?></option>
									<?php }	?>
									</select>
									</td>
								</tr>
								<?php }?>
								<tr>
									<td colspan="4"><h5>1. รายละเอียดผู้ซื้อสินค้า</h5></td>
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
									<td><input type="radio" name="company_type" <?php if($rs['id_type_company']==$rs_type_company['id_type_company']){echo 'checked';}?> value="<?php echo $rs_type_company['id_type_company']?>"><?php echo $rs_type_company['title_type_company']?></td>
									<?php if($j_type_company % 4 == 0){ ?></tr><?php } 
										$rows_type_company++;
									}//end while type company
									if($max_type_company==$rows_type_company){
									?>
									<td colspan="2"><div style="float:left; margin: 0 0.5% 0 0;"><input type="radio" name="company_type" value="-1" <?php if($rs['id_type_company']== -1){echo 'checked';}?>></div><div style="float:left; margin: 0 0.5% 0 0;">อื่น ๆ</div><div style="float:left;"><input type="text" name="other_company_type" value="<?php echo $rs['other_company_type']?>"></div></td>
									<?php }//end if?>
								<tr>
									<td>ชื่อกิจการ</td>
									<td><input type="text" name="company_name" value="<?php echo $rs['company_name']?>"></td>
									<td width="10%">สนญ./สาขา</td>
									<td><input type="text" name="branch_name" value="<?php echo $rs['branch_name']?>"></td>
									<td>ทะเบียนการค้า</td>
									<td><input type="text" name="trade_regis" value="<?php echo $rs['trade_regis']?>"></td>
								</tr>
								<?php
								$sql_address="select * from company_address where id_address='".$rs['id_address']."'";
								$res_address=mysql_query($sql_address) or die ('Errro '.$sql_address);
								$rs_address=mysql_fetch_array($res_address);
								?>
								<input type="hidden" name="id_address" value="<?php echo $rs_address['id_address']?>">
								<tr>
									<td>ที่อยู่เลขที่</td>
									<td><input type="text" name="address_no" value="<?php echo $rs_address['address_no']?>"></td>
									<td>ถนน</td>
									<td><input type="text" name="road" value="<?php echo $rs_address['road']?>"></td>
								</tr>
								<tr>
									<td width="15%">ตำบล/แขวง</td>
									<td width="18%"><input type="text" name="sub_district" value="<?php echo $rs_address['sub_district']?>"></td>
									<td width="15%">อำเภอ/เขต</td>
									<td width="15%"><input type="text" name="district" value="<?php echo $rs_address['district']?>"></td>
								</tr>
								<tr>
									<td width="8%">จังหวัด</td>
									<td width="12%"><input type="text" name="province" value="<?php echo $rs_address['province']?>"></td>
									<td width="8%">รหัสไปรษณีย์</td>
									<td width="15%"><input type="text" name="postal_code" value="<?php echo $rs_address['postal_code']?>"></td>
								</tr>
								<tr>
									<td>โทรศัพท์</td>
									<td><input type="text" name="company_tel" value="<?php echo $rs['company_tel']?>"></td>
									<td>โทรสาร</td>
									<td><input type="text" name="company_fax" value="<?php echo $rs['company_fax']?>"></td>									
								</tr>
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
									<td>ชื่อผู้ติดต่อ/แผนกจัดซื้อ</td>
									<td><input type="hidden" name="id_contact[]" value="<?php echo $pur?>">
									<input type="text" name="contact_name[]" value="<?php echo $pur_name?>"></td>
									<td>ตำแหน่ง</td>
									<td><input type="hidden" name="department[]" value="<?php echo $pur_position?>">
									<input type="text" name="contact_position[]" value="<?php echo $pur_position?>" ></td>
								</tr>
								<tr>
									<td>มือถือ</td>
									<td><input type="text" name="contact_mobile[]" value="<?php echo $pur_mobile?>"></td>
									<td>E-mail</td>
									<td><input type="text" name="contact_mail[]" value="<?php echo $pur_mail?>"></td>
								</tr>								
								<tr>
									<td>ชื่อผู้ติดต่อ/แผนกบัญชีการเงิน</td>
									<td><input type="hidden" name="id_contact[]" value="<?php echo $bank?>">
									<input type="text" name="contact_name[]" value="<?php echo $bank_name?>"></td>
									<td>ตำแหน่ง</td>
									<td><input type="hidden" name="department[]" value="บัญชีการเงิน">
									<input type="text" name="contact_position[]" value="บัญชีการเงิน" readonly></td>
								</tr>
								<tr>
									<td>มือถือ</td>
									<td><input type="text" name="contact_mobile[]" value="<?php echo $bank_mobile?>"></td>
									<td>E-mail</td>
									<td><input type="text" name="contact_mail[]" value="<?php echo $bank_mail?>"></td>
								</tr>
								
								<tr>
									<td colspan="2"><h5>2. ประเภทกิจการ</h5></td>
								</tr>								
								<tr>
									<?php
									$j_com_cate=0;
									$rows_com_cate=0;									
									$sql_com_cate="select * from company_category";
									$res_com_cate=mysql_query($sql_com_cate) or die ('Error '.$sql_com_cate);
									$max_com_cate=mysql_num_rows($res_com_cate);
									while($rs_com_cate=mysql_fetch_array($res_com_cate)){
										if($rows_com_cate % 4 ==0){?><tr><?php }
										$j_com_cate++;
									?>
									<td><input type="radio" name="company_cate" <?php if($rs['id_com_cat']==$rs_com_cate['id_com_cat']){echo 'checked';}?> value="<?php echo $rs_com_cate['id_com_cat']?>"><?php echo $rs_com_cate['title']?></td>
									<?php if($j_com_cate % 4 == 0){ ?></tr><?php } 
										$rows_com_cate++;
									}//end while type company
									if($max_com_cate==$rows_com_cate){
									?>
									<td colspan="4"><div style="float:left; margin: 0 0.5% 0 0;"><input type="radio" name="company_cate" value="-1" <?php if($rs['id_com_cat']== -1){echo 'checked';}?>></div><div style="float:left; margin: 0 0.5% 0 0;">อื่น ๆ</div><div style="float:left"><input type="text" name="other_company_cate" value="<?php echo $rs['other_company_cate']?>"></div></td>
									<?php }//end if?>
								</tr>
								<tr>
									<td colspan="2"><h5>3. ประเภทสินค้าที่ซื้อ</h5></td>
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
									<td><input type="radio" name="cate_bought" <?php if($rs['id_cate_bought']==$rs_cate_bought['id_cate_bought']){echo 'checked';}?> value="<?php echo $rs_cate_bought['id_cate_bought']?>"><?php echo $rs_cate_bought['title_bought']?></td>
									<?php if($j_cate_bought % 4 == 0){ ?></tr><?php } 
										$rows_cate_bought++;
									}//end while type company
									if($max_cate_bought==$rows_cate_bought){
									?>
									<td colspan="4"><div style="float:left; margin: 0 0.5% 0 0;"><input type="radio" name="cate_bought" value="-1" <?php if($rs['id_cate_bought']== -1){echo 'checked';}?>></div><div style="float:left; margin: 0 0.5% 0 0;">อื่น ๆ</div><div style="float:left"><input type="text" name="other_bought" value="<?php echo $rs['other_bought']?>"></div></td>
									<?php }//end if?>
								</tr>
								<tr>
									<td colspan="2"><h5>4. เอกสารประกอบการค้า</h5></td>
								</tr>
								<?php $type_file=split(",",$rs['type_file']);?>
								<tr>
									<td><input type="radio" name="person_type" value="1" <?php if($rs['person_type']==1){echo 'checked';}?>>บุคคล</td>
								</tr>
								<tr>
									<td><input type="checkbox" name="type_file[]" <?php if(in_array('1',$type_file)){echo 'checked';}?> value="1">สำเนาบัตรประชาชน</td>
									<td><input type="checkbox" name="type_file[]" <?php if(in_array('2',$type_file)){echo 'checked';}?> value="2">สำเนาทะเบียนบ้าน</div></td>
									<td><input type="checkbox" name="type_file[]" <?php if(in_array('3',$type_file)){echo 'checked';}?> value="3">ทะเบียนการค้า</td>
									<td><input type="checkbox" name="type_file[]" <?php if(in_array('4',$type_file)){echo 'checked';}?> value="4">แผนที่ตั้งกิจการและที่ส่งของ</td>
								</tr>
								<tr>
									<td><input type="radio" name="person_type" value="2" <?php if($rs['person_type']==2){echo 'checked';}?>>นิติบุคคล</td>
								</tr>
								<tr>
									<td><input type="checkbox" name="type_file[]" <?php if(in_array('5',$type_file)){echo 'checked';}?> value="5">ภ.พ. 20 หรือ ภ.พ. 09</td>
									<td><input type="checkbox" name="type_file[]" <?php if(in_array('6',$type_file)){echo 'checked';}?> value="6">หนังสือรับรองบริษัท (ไม่เกิน 6 เดือน)</td>
									<td><input type="checkbox" name="type_file[]" <?php if(in_array('7',$type_file)){echo 'checked';}?> value="7">แผนที่ตั้งกิจการและที่ส่งของ</td>
								</tr>
								<tr>
									<td colspan="4">
										<script type="text/javascript" src="js/fancybox/scripts/jquery-1.4.3.min.js"></script>
										<script type="text/javascript" src="js/fancybox/fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
										<script type="text/javascript" src="js/fancybox/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
										<link rel="stylesheet" type="text/css" href="js/fancybox/fancybox/jquery.fancybox-1.3.4.css" media="screen" />
										<script type="text/javascript">
											$(document).ready(function($) {
												$(".various").fancybox({
													maxWidth	: 800,
													maxHeight	: 600,
													fitToView	: false,
													width		: '60%',
													height		: '70%',
													autoSize	: false,
													closeClick	: false,
													hideOnOverlayClick	: false,
													openEffect	: 'none',
													closeEffect	: 'none',
													type		: 'iframe',
													onClosed	:	function() {
														parent.location.reload(true); 
													}				 
												});
											});
										</script>		
										<span style="font-size:1.2em;"><a class="various" data-fancybox-type="iframe" href="attach-file.php?id_com='<?=$id?>">เอกสารแนบ</a></span>
									</td>
								</tr>
								<tr>
									<td colspan="8"><h5>5. เงื่อนไขการวางบิลรับเช็คของลูกค้าเครดิต</h5></td>
								</tr>								
								<?php
									$j_company_pay=0;
									$sql_company_pay="select * from company_pay";
									$res_company_pay=mysql_query($sql_company_pay) or die ('Error '.$sql_ccompany_pay);
									while($rs_company_pay=mysql_fetch_array($res_company_pay)){
										if($rows_company_pay % 2 ==0){?><tr><?php }
										$j_company_pay++;
									?>
									<td colspan="3"><input type="radio" name="company_pay" <?php if($rs['id_company_pay']==$rs_company_pay['id_company_pay']){echo 'checked';}?> value="<?php echo $rs_company_pay['id_company_pay']?>"><?php echo $rs_company_pay['title_pay']?></td>
									<?php if($j_company_pay % 2 == 0){ ?></tr><?php } 
										$rows_company_pay++;
									}//end while type company
								?>
								<tr>
									<td colspan="8"><h5>6. ระบบวางบิลและรับเช็ค</h5></td>
								</tr>
								<tr>
									<td colspan="2"><div style="float:left; margin: 0 0.5% 0 0;">วางบิลวันที่</div><div style="float:left"><input type="text" name="pay_date" value="<?php echo $rs['pay_date']?>"></div><div style="float:left;">ของเดือน</div></td>
									<td colspan="6"><div style="float:left; margin: 0 0.5% 0 0;">รับเช็ค/โอนเงินวันที่</div><div style="float:left"><input type="text" name="checkpay_date" value="<?php echo $rs['checkpay_date']?>"></div><div style="float:left;">ของเดือน</div></td>
								</tr>
								<tr>
									<td colspan="8"><h5>7. วิธีการชำระเงิน</h5></td>
								</tr>	
								<tr>
									<td><input type="radio" name="type_pay" <?php if($rs['type_pay']==1){echo 'checked';}?> value="1">เงินสด</td>
									<td colspan="6"><input type="radio" name="type_pay" <?php if($rs['type_pay']==2){echo 'checked';}?> value="2">เช็คสั่งจ่ายในนาม " บริษัท ซีดีไอพี (ประเทศไทย) จำกัด "</td>
								</tr>
								<tr>
									<td colspan="8"><input type="radio" name="type_pay" <?php if($rs['type_pay']==3){echo 'checked';}?> value="3">โอนเงินผ่านบัญชีบริษัท ซีดีไอพี (ประเทศไทย) จำกัด
									<p>บัญชีธนาคารทหารไทย เลขที่บัญชี 073-1055-703 ชื่อบัญชี บริษัท ซีดีไอพี (ประเทศไทย) จำกัด สาขาสาธุประดิษฐ์ </p>
									</td>									
								</tr>
							</table>
						</div>
					</td>
				</tr>
				<tr>
					<td class="b-top">
						<div class="large-4 columns">
							<?php if(!is_numeric($id)){?>
							<input type="button" name="save_data" id="save_data" value="<?php echo $button?>" class="button-create" OnClick="JavaScript:return fncSubmit();">
							<?php }else{?>
							<input type="button" name="update_data" id="update_data" value="<?php echo $button?>" class="button-create" OnClick="JavaScript:return fncSubmit();">
							<?php }?>
							<input type="button" value="Close" class="button-create" onclick="window.location.href='open-customer.php'">
						</div>
					</td>
				</tr>
			</table>
			</form>
		</div>
	</div>

  <script>
  document.write('<script src=' +
  ('__proto__' in {} ? 'js/vendor/zepto' : 'js/vendor/jquery') +
  '.js><\/script>')
  </script>
  
  <script src="js/foundation.min.js"></script>
  <!--
  
  <script src="js/foundation/foundation.js"></script>
  
  <script src="js/foundation/foundation.alerts.js"></script>
  
  <script src="js/foundation/foundation.clearing.js"></script>
  
  <script src="js/foundation/foundation.cookie.js"></script>
  
  <script src="js/foundation/foundation.dropdown.js"></script>
  
  <script src="js/foundation/foundation.forms.js"></script>
  
  <script src="js/foundation/foundation.joyride.js"></script>
  
  <script src="js/foundation/foundation.magellan.js"></script>
  
  <script src="js/foundation/foundation.orbit.js"></script>
  
  <script src="js/foundation/foundation.reveal.js"></script>
  
  <script src="js/foundation/foundation.section.js"></script>
  
  <script src="js/foundation/foundation.tooltips.js"></script>
  
  <script src="js/foundation/foundation.topbar.js"></script>
  
  <script src="js/foundation/foundation.interchange.js"></script>
  
  <script src="js/foundation/foundation.placeholder.js"></script>
  
  <script src="js/foundation/foundation.abide.js"></script>
  
  -->
  
  <script>
    $(document).foundation();
  </script>
</body>
</html>
