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
$_SESSION["id_company"]=$_REQUEST['id_company'];
$_SESSION['company_name']=$_REQUEST['company_name'];
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
function fncSubmit()
{
	document.frm.submit();
}
function fncSubmit1()
{
	document.frm1.submit();
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
				$id='new';
			}
			else{
				$id=$_GET["id_u"];
				$sql="select * from quotation where id_quotation='".$id."'";
				$res=mysql_query($sql) or die ('Error '.$sql);
				$rs=mysql_fetch_array($res);
				$mode='Edit '.$rs['quotation_no'];
				$button='Update';
			}
			//*** Add Condition ***//
			if($_POST["hdnCmd"] == "Add"){
				if($_POST['mode']){$id_quo=$_POST['mode'];}else{$id_quo=0;}

				$sql="insert into quotation_relationship(id_quotation,id_pre_quotation";
				$sql .=",id_type_product,num_pre_quotation,price_product,sum_quotation)";
				$sql .=" values('".$id_quo."','".$_POST['id_pre_quotation']."'";
				$sql .=",'".$_POST['pre_quotation_price']."'";
				$sql .=",'".$_POST['id_type_product']."','".$_POST['num_pre_quotation']."'";
				$sql .=",'".$_POST['sum_quotation_price']."')";
				$res = mysql_query($sql) or die ('Error '.$sql);
				
				$total=0;
				$total_discount=0;
				$vat=0;
				$total_price=0;
				$sql_quo_rel="select * from quotation_relationship";
				$sql_qup_rel .=" where id_quotation='".$_POST['mode']."'";
				$res_quo_rel=mysql_query($sql_quo_rel) or die ('Error '.$sql_quo_rel);
				while($rs_quo_rel=mysql_fetch_array($res_quo_rel)){
					$total=$rs_quo_rel['sum_quotation']+$total;
				}
				$total_discount=$total - $_POST['discount'];
				$vat=($total_discount*7)/100;
				$total_price=$vat+$total_discount;

				$sql_quo="update quotation set id_company='".$_POST['id_company']."'";
				$sql_quo .=",id_product='".$id_product."',sum_price='".$total."'";
				$sql_quo .=",price_product='".$_POST['pre_quotation_price']."'";
				$sql_quo .=",discount='".$_POST['discount']."',total_discount='".$total_discount."'";
				$sql_quo .=",vat='".$vat."',total_price='".$total_price."'";
				$sql_quo .=" where id_quotation='".$_POST['mode']."'";
				$res_quo=mysql_query($sql_quo) or die ('Error '.$sql_quo);
			}

			//*** Update Condition ***//
			if($_POST["hdnCmd"] == "Update"){
				$sql = "update quotation_relationship set id_pre_quotation= '".$_POST['id_pre_quotation2']."'";
				$sql .=",id_type_product='".$_POST['id_type_product2']."'";
				$sql .=",num_pre_quotation='".$_POST['num_pre_quotation2']."'";
				$sql .=",price_product='".$_POST['pre_quotation_price2']."'";
				$sql .=",sum_quotation='".$_POST['sum_quotation_price2']."'";
				$sql .=" where id_quo_relation = '".$_POST["hdnEdit"]."' ";
				$res = mysql_query($sql) or die ('Error '.$sql);

				$total=0;
				$total_discount=0;
				$vat=0;
				$total_price=0;
				$sql_quo_rel="select * from quotation_relationship";
				$sql_qup_rel .=" where id_quotation='".$_POST['mode']."'";
				$res_quo_rel=mysql_query($sql_quo_rel) or die ('Error '.$sql_quo_rel);
				while($rs_quo_rel=mysql_fetch_array($res_quo_rel)){
					$total=$rs_quo_rel['sum_quotation']+$total;
				}
				$total_discount=$total - $_POST['discount'];
				$vat=($total_discount*7)/100;
				$total_price=$vat+$total_discount;

				$sql_quo="update quotation set id_company='".$_POST['id_company']."'";
				$sql_quo .=",id_product='".$id_product."',sum_price='".$total."'";				
				$sql_quo .=",discount='".$_POST['discount']."',total_discount='".$total_discount."'";
				$sql_quo .=",vat='".$vat."',total_price='".$total_price."'";
				$sql_quo .=" where id_quotation='".$_POST['mode']."'";
				$res_quo=mysql_query($sql_quo) or die ('Error '.$sql_quo);
				

			}

			//*** Delete Condition ***//
			if($_GET["action"] == "del"){
				$sql = "delete from quotation_relationship ";
				$sql .="where id_quo_relation = '".$_GET["id_p"]."'";
				$res = mysql_query($sql) or die ('Error '.$sql);

				$total=0;
				$total_discount=0;
				$vat=0;
				$total_price=0;
				$sql_quo_rel="select * from quotation_relationship";
				$sql_qup_rel .=" where id_quotation='".$_POST['mode']."'";
				$res_quo_rel=mysql_query($sql_quo_rel) or die ('Error '.$sql_quo_rel);
				while($rs_quo_rel=mysql_fetch_array($res_quo_rel)){
					$total=$rs_quo_rel['sum_quotation']+$total;
				}
				$total_discount=$total - $_POST['discount'];
				$vat=($total_discount*7)/100;
				$total_price=$vat+$total_discount;

				$sql="update quotation set id_company='".$_POST['id_company']."'";
				$sql .=",id_product='".$id_product."',sum_price='".$total."'";
				$sql .=",discount='".$_POST['discount']."',total_discount='".$total_discount."'";
				$sql .=",vat='".$vat."',total_price='".$total_price."'";
				$sql .=" where id_quotation='".$_POST['mode']."'";
				$res=mysql_query($sql) or die ('Error '.$sql);
			}
			
			if($_POST["hdnCmd"] == "save"){

				// ZAMACHITA meeting
				$post_product_name = mysql_real_escape_string($_POST['product_name']);

				$sql_product="select * from product where product_name like '".$_POST['product_name']."'";
				$res_product=mysql_query($sql_product) or die ('Error '.$sql_product);
				$rs_product=mysql_fetch_array($res_product);
				if(!$rs_product){
					$sql_ins_product="insert into product(product_name) values";
					$sql_ins_product .=" ('".$post_product_name."')";
					$res_ins_product=mysql_query($sql_ins_product) or die ('Error '.$sql_ins_product);
					$id_product=mysql_insert_id();
				}else{
					$id_product=$rs_product['id_product'];
				}
				$date=date('Y-m-d');
				$sql="insert into quotation(quotation_no,quotation_month,quotation_num";
				$sql .=",id_company,id_product,sum_price,discount,total_discount";
				$sql .=",vat,total_price,create_by,create_date,status)";
				$sql .=" values('".$_POST['quotation_no']."','".$_POST['quotation_month']."'";
				$sql .=",'".$_POST['quotation_num']."','".$_POST['id_company']."'";
				$sql .=",'".$id_product."','".$_POST['total']."'";
				$sql .=",'".$_POST['discount']."','".$_POST['total_discount']."'";
				$sql .=",'".$_POST['vat']."','".$_POST['total_price']."'";
				$sql .=",'".$rs_account['id_account']."','".$date."','1')";
				$res=mysql_query($sql) or die ('Error '.$sql);
				$id=mysql_insert_id();

				$sql_pre_relation="update quotation_relationship set id_quotation='".$id."'";
				$sql_pre_relation .=" where id_quotation='0'";
				$res_pre_relation=mysql_query($sql_pre_relation) or die ('Error '.$sql_pre_relation);
			?>
				<script>
					window.location.href='ac-quotation.php?id_u=<?=$id?>';
				</script>
			<?php }
			if($_POST["hdnCmd"] == "update"){
				$id=$_POST['mode'];

				// ZAMACHITA meeting
				$post_product_name = mysql_real_escape_string($_POST['product_name']);

				$sql_product="select * from product where product_name like '".$_POST['product_name']."'";
				$res_product=mysql_query($sql_product) or die ('Error '.$sql_product);
				$rs_product=mysql_fetch_array($res_product);
				if(!$rs_product){
					$sql_ins_product="insert into product(product_name) values";
					$sql_ins_product .=" ('".$post_product_name."')";
					$res_ins_product=mysql_query($sql_ins_product) or die ('Error '.$sql_ins_product);
					$id_product=mysql_insert_id();
				}else{
					$id_product=$rs_product['id_product'];
				}
				$total=0;
				$total_discount=0;
				$vat=0;
				$total_price=0;
				$sql_quo_rel="select * from quotation_relationship";
				$sql_quo_rel .=" where id_quotation='".$_POST['mode']."'";
				$res_quo_rel=mysql_query($sql_quo_rel) or die ('Error '.$sql_quo_rel);
				while($rs_quo_rel=mysql_fetch_array($res_quo_rel)){
					$total=$rs_quo_rel['sum_quotation']+$total;
				}
				$total_discount=$total - $_POST['discount'];
				$vat=($total_discount*7)/100;
				$total_price=$vat+$total_discount;
				
				// ZAMACHITA meeting
				$post_company_name = mysql_real_escape_string($_POST['company_name']);
				$post_company_contact = mysql_real_escape_string($_POST['company_contact']);

				$sql="update quotation set id_company='".$_POST['id_company']."'";
				$sql .=",company_name='".$post_company_name."'";
				$sql .=",contact_name='".$post_company_contact."'";
				$sql .=",address='".$_POST['company_address']."'";
				$sql .=",email='".$_POST['company_email']."'";
				$sql .=",id_product='".$id_product."',sum_price='".$total."'";
				$sql .=",discount='".$_POST['discount']."',total_discount='".$total_discount."'";
				$sql .=",vat='".$vat."',total_price='".$total_price."'";
				$sql .=" where id_quotation='".$_POST['mode']."'";
				$res=mysql_query($sql) or die ('Error '.$sql);	

			?>
				<script>
					window.location.href='ac-quotation.php?id_u=<?=$id?>';
				</script>
			<?php }
			?>
			<script type="text/javascript" src="js/js-autocomplete/js/jquery-1.4.2.min.js"></script> 
			<script type="text/javascript" src="js/js-autocomplete/js/jquery-ui-1.8.2.custom.min.js"></script> 
			<script type="text/javascript"> 
				jQuery(document).ready(function(){
					$('.title_pre_quotation').autocomplete({
						source:'return-pre-quotation.php', 
						//minLength:2,
						select:function(evt, ui)
						{
							// when a zipcode is selected, populate related fields in this form
							this.form.id_pre_quotation.value = ui.item.id_pre_quotation;
							this.form.id_type_product.value = ui.item.id_type_product;
							this.form.num_pre_quotation.value = ui.item.num_pre_quotation;
							this.form.unit_pre_quotation.value = ui.item.unit_pre_quotation;
							this.form.pre_quotation_price.value = ui.item.pre_quotation_price;
							this.form.sum_quotation_price.value = ui.item.sum_quotation_price;
							this.form.id_product_appearance.value = ui.item.id_product_appearance;
						}
					});
					$('.title_pre_quotation2').autocomplete({
						source:'return-pre-quotation.php', 
						//minLength:2,
						select:function(evt, ui)
						{
							// when a zipcode is selected, populate related fields in this form
							this.form.id_pre_quotation2.value = ui.item.id_pre_quotation2;
							this.form.id_type_product2.value = ui.item.id_type_product2;
							this.form.num_pre_quotation2.value = ui.item.num_pre_quotation2;
							this.form.unit_pre_quotation2.value = ui.item.unit_pre_quotation2;
							this.form.pre_quotation_price2.value = ui.item.pre_quotation_price2;
							this.form.sum_quotation_price2.value = ui.item.sum_quotation_price2;
						}
					});
					$('.company_name').autocomplete({
						source:'return-company.php', 
						//minLength:2,
						select:function(evt, ui)
						{
							// when a zipcode is selected, populate related fields in this form
							this.form.id_company.value = ui.item.id_company;
							this.form.company_address.value = ui.item.company_address;
							this.form.company_contact.value = ui.item.company_contact;
							this.form.company_email.value = ui.item.company_email;
						}
					});
					$('.product_name').autocomplete({
						source:'return-product.php', 
						//minLength:2,
						select:function(evt, ui)
						{
							// when a zipcode is selected, populate related fields in this form
							this.form.id_product.value = ui.item.id_product;
						}
					});
				});
			</script> 
			<link rel="stylesheet" href="js/js-autocomplete/css/smoothness/jquery-ui-1.8.2.custom.css" />
			<form name="frm" method="post" action="<?=$_SERVER["PHP_SELF"]."?id_u=".$id?>">
			<input type="hidden" name="hdnCmd" value="">
			<table style="border: 0; width: 100%;" cellpadding="0" cellspacing="0">
				<tr>
					<td class="b-bottom"><div class="large-4 columns"><h4>Pre Quotation >> <?php echo $mode;?></h4></div></td>
				</tr>
				<tr>
					<td class="b-bottom">
						<div class="large-4 columns">
							<?php 
							if(!is_numeric($id)){
							?>
							<input type="button" name="save_data" id="save_data" value="<?php echo $button?>" class="button-create" OnClick="frm.hdnCmd.value='save';JavaScript:return fncSubmit();">
							<?php }else{ ?>
							<input type="button" name="update_data" value="<?php echo $button?>" class="button-create" OnClick="frm.hdnCmd.value='update';JavaScript:return fncSubmit();">
							<?php }?>
							<input type="button" name="update_data" id="update_data" value="Print PDF" class="button-create" Onclick="window.open('pdf-pre-quo.php?id_u=<?=$id?>')">
							<input type="button" value="Close" class="button-create" onclick="window.location.href='quotation.php'">
						</div>
					</td>
				</tr>
				<tr>
					<td style="background: #fff;">
						<div class="large-4 columns">
							<input type="hidden" name="mode" value="<?php echo $id?>">
							<table style="border: 1px solid #eee; width: 100%;" cellpadding="0" cellspacing="0" id="tb-quotation">
								<tr>
									<td class="b-bottom" colspan="4">
										<h4>บริษัท ซีดีไอพี (ประเทศไทย) จำกัด<br>
										CDIP (Thailand) Co.,Ltd.<br>
										</h4>
										131 อาคารกลุ่มนวัตกรรม1 ห้อง227 อุทยานวิทยาศาสตร์ประเทศไทย ถ.พหลโยธิน ต.คลองหนึ่ง อ.คลองหลวง จ.ปทุมธานี 12120<br>
										131 INC1  No.227  Thailand Science park  Paholyothin Rd.  Klong1  Klong Luang  Pathumthani  12120  THAILAND  Tel: 0 2564 7200 # 5227  Fax: 0 2564 7745 
									</td>
									<td class="b-bottom" colspan="3"><img src="img/logo.png" width="140" class="img-logo"></td>
								</tr>
								<tr>
									<td class="b-bottom center" colspan="7"><h4>ใบเสนอราคา</h4></td>
								</tr>
								<tr>
									<td class="bd-right b-bottom top" rowspan="2">ชื่อบริษัท</td>
									<td class="bd-right b-bottom top" rowspan="2" colspan="2">
										<?php
										$sql_company="select * from company where id_company='".$rs['id_company']."'";
										$res_company=mysql_query($sql_company) or die ('Error '.$sql_company);
										$rs_company=mysql_fetch_array($res_company);
										?>
										<input type="hidden" name="id_company" value="<?php if(is_numeric($id)){echo $rs_company['id_company'];}else{echo $_SESSION['id_company'];}?>">
										<input type="text" name="company_name" id="company_name" class="company_name" value="<?php if($rs['company_name']==''){echo $rs_company['company_name'];}else{echo $rs['company_name'];}?>">
									</td>												
									<td class="bd-right b-bottom top" colspan="2">เลขที่ใบเสนอราคา </td>
									<td class="bd-right b-bottom">
										<?php 
										if(is_numeric($id)){ echo $rs['quotation_no'];}
										else{
											$month=date("m")?>
											<?php echo 'Q-PP';
											/*if($rs_account['id_account']<10){echo '0'.$rs_account['id_account'];}
											else{echo $rs_account['id_account'];}*/
											echo date("y").date("m");
											$sql_quotation="select * from quotation order by id_quotation desc";
											$res_quotation=mysql_query($sql_quotation) or die ('Error '.$sql_quotation);
											$rs_quotation=mysql_fetch_array($res_quotation);
											if($month==$rs_quotation['quotation_month']){
												$num = $rs_quotation['quotation_num']+1;
											}else{$num=1;}												
											echo sprintf("%03d",$num);
											echo $numf;
										?>
											<input type="hidden" name="quotation_no" value="<?php echo 'Q-PP';
											/*if($rs_account['id_account']<10){echo '0'.$rs_account['id_account'];}
											else{echo $rs_account['id_account'];}*/
											echo date("y").date("m");
											$sql_quotation="select * from quotation order by id_quotation desc";
											$res_quotation=mysql_query($sql_quotation) or die ('Error '.$sql_quotation);
											$rs_quotation=mysql_fetch_array($res_quotation);
											if($month==$rs_quotation['quotation_month']){
												$num = $rs_quotation['quotation_num']+1;
											}else{$num=1;}												
											echo sprintf("%03d",$num);
											echo $numf;;?>">										
											<input type="hidden" name="quotation_month" value="<?php if($month==$rs_quotation['quotation_month']){echo $rs_quotation['quotation_month'];}else{ echo date("m");}?>">
											<input type="hidden" name="quotation_num" value="<?php if($month==$rs_quotation['quotation_month']){echo $num;}else{echo $num=1;}?>">
										<?php }?>
									</td>
									</tr>
									<tr>
										<td class="bd-right b-bottom top" colspan="2">วันที่</td>
										<td class="bd-right b-bottom" colspan="3"><?php echo date("d/m/Y")?></td>
									</tr>
									<tr>
									<?php
									$sql_contact="select * from company_contact where id_company='".$rs_company['id_company']."'";
									$res_contact=mysql_query($sql_contact) or die ('Error '.$sql_contact);
									$rs_contact=mysql_fetch_array($res_contact);
									?>
										<td class="bd-right b-bottom top">ชื่อผู้ติดต่อ/เบอร์ </td>
										<td class="bd-right b-bottom top" colspan="2"><input type="text" name="company_contact" id="company_contact"  value="<?php if($rs['contact_name']==''){echo $rs_contact['contact_name'];}else{echo $rs['contact_name'];}?>"></td>
										<td class="bd-right b-bottom top" colspan="2">ผู้เสนอราคา</td>
										<td class="bd-right b-bottom"><?php echo $rs_account['name']?>
										</td>
									</tr>
									<tr>
									<?php
									$sql_address="select * from company_address where id_address='".$rs_company['id_address']."'";
									$res_address=mysql_query($sql_address) or die ('Error '.$sql_address);
									$rs_address=mysql_fetch_array($res_address);
									?>
										<td class="bd-right b-bottom top" rowspan="2">ที่อยู่</td>
										<td class="bd-right b-bottom top" rowspan="2" colspan="2"><textarea name="company_address" id="company_address" ><?php if($rs['address']==''){echo $rs_address['address_no'].'&nbsp;&nbsp;'.$rs_address['road'].'&nbsp;&nbsp;'.$rs_address['sub_district'].'&nbsp;&nbsp;'.$rs_address['district'].'&nbsp;&nbsp;'.$rs_address['province'].'&nbsp;&nbsp;'.$rs_address['postal_code'];}else{echo $rs['address'];}?></textarea></td>
										<td class="bd-right b-bottom top" colspan="2">ระยะเวลาในการส่งของ</td>
										<td class="bd-right b-bottom">75-90 วัน</td>
									</tr>
									<tr>
										<td class="bd-right b-bottom top" colspan="2">จำนวนวันเครดิต</td>
										<td class="bd-right b-bottom">0 วัน</td>
									</tr>
									<tr>
										<td class="bd-right b-bottom top">E-Mail:</td>
										<td class="bd-right b-bottom" colspan="2"><input type="text" name="company_email" id="company_email"  value="<?php if($rs['email']==''){echo $rs_contact['email'];}else{echo $rs['email'];}?>"></td>
										<td class="bd-right b-bottom top" colspan="2">กำหนดยืนราคา</td>
										<td class="bd-right b-bottom">30 วันนับจากวันที่เสนอราคา</td>
									</tr>
									<tr>
										<td class="bd-right b-bottom top center">ลำดับที่</td>
										<td class="bd-right b-bottom top center">รายการ</td>
										<td class="bd-right b-bottom top center">จำนวน</td>
										<td class="bd-right b-bottom top center">หน่วยนับ</td>
										<td class="bd-right b-bottom top center">ราคา/หน่วย</td>
										<td class="bd-right b-bottom top center">จำนวนเงิน</td>
									</tr>
									<tr>
										<td class="bd-right b-bottom center">1</td>
										<td class="bd-right b-bottom">ก่อนการผลิต</td>
										<td class="bd-right b-bottom"></td>
										<td class="bd-right b-bottom"></td>
										<td class="bd-right b-bottom"></td>
										<td class="bd-right b-bottom"></td>
									</tr>
									<tr>
									<?php
									$sql_product="select * from product where id_product='".$rs['id_product']."'";
									$res_product=mysql_query($sql_product) or die ('Error '.$sql_prodcut);
									$rs_product=mysql_fetch_array($res_product);
									?>
										<td class="bd-right b-bottom center"></td>
										<td class="bd-right b-bottom">ชื่อผลิตภัณฑ์ 
											<input type="hidden" name="id_product" value="<?php if(is_numeric($id)){echo $rs_product['id_product'];}?>">
											<input type="text" name="product_name" id="product_name" class="product_name" value="<?php if(is_numeric($id)){echo $rs_product['product_name'];}?>">
										</td>
										<td class="bd-right b-bottom"></td>
										<td class="bd-right b-bottom"></td>
										<td class="bd-right b-bottom"></td>
										<td class="bd-right b-bottom"></td>
									</tr>
									<?php
									if(is_numeric($id)){$id_quo=$rs['id_quotation'];}else{$id_quo=0;}
									$sql_quo_relationship="select * from quotation_relationship where id_quotation='".$id_quo."' order by id_quo_relation asc";
									$res_quo_relationship=mysql_query($sql_quo_relationship) or die ('Error '.$sql_quo_relationship);
									while($rs_quo_relationship=mysql_fetch_array($res_quo_relationship)){		
										$sql_pre_quotation_detail="select * from pre_quotation_detail where id_pre_quotation='".$rs_quo_relationship['id_pre_quotation']."'";
										$res_pre_quotation_detail=mysql_query($sql_pre_quotation_detail) or die ('Error '.$sql_pre_quotation_detail);
										$rs_pre_quotation_detail=mysql_fetch_array($res_pre_quotation_detail);

										if($rs_quo_relationship['id_quo_relation'] == $_GET['id_p'] and $_GET["action"] == 'edit'){
									?>	
										<script language="JavaScript">
											function fncSum2(){
												document.frm.sum_quotation_price2.value = parseFloat(document.frm.num_pre_quotation2.value) * parseFloat(document.frm.pre_quotation_price2.value);
											}
										</script>
									<tr>
										<input type="hidden" name="id_type_product2" id="id_type_product2" value="<?php echo $rs_pre_quotation_detail['id_type_product']?>">	
										<input type="hidden" name="hdnEdit" value="<?php echo $rs_quo_relationship['id_quo_relation']?>">
										<td class="bd-right b-bottom top"></td>
										<td class="bd-right b-bottom top">
											<input type="hidden" name="id_pre_quotation2" id="id_pre_quotation2" value="<?php echo $rs_quo_relationship['id_pre_quotation']?>">
											<input type="text" name="title_pre_quotation2" id="title_pre_quotation2" class="title_pre_quotation2" value="<?php echo $rs_pre_quotation_detail['title_pre_quotation']?>">
										</td>
										<td class="bd-right b-bottom top"><input type="text" name="num_pre_quotation2" id="num_pre_quotation2" value="<?php echo $rs_quo_relationship['num_pre_quotation']?>" OnChange="fncSum2();"></td>
										<td class="bd-right b-bottom top"><input type="text" name="unit_pre_quotation2" id="unit_pre_quotation2" value="<?php echo $rs_pre_quotation_detail['unit_pre_quotation']?>"></td>
										<td class="bd-right b-bottom top"><input type="text" id="pre_quotation_price2" name="pre_quotation_price2" value="<?php echo $rs_quo_relationship['price_product']?>" OnChange="fncSum2();"></td>
										<td class="bd-right b-bottom top">
											<input type="text" name="sum_quotation_price2" id="sum_quotation_price2" value="<?php echo $rs_quo_relationship['sum_quotation']?>" style="float: left; width: 70%;">
											<input name="btnAdd" type="button" id="btnUpdate" value="Update" OnClick="frm.hdnCmd.value='Update';JavaScript:return fncSubmit();" class="btn-update">
											<input name="btnAdd" type="button" id="btnCancel" value="Cancel" OnClick="window.location='<?=$_SERVER["PHP_SELF"]."?id_u=".$id;?>';" class="btn-cancel">
										</td>
									</tr>
									<?php }else{?>												
									<tr>
										<td class="bd-right b-bottom top"></td>
										<td class="bd-right b-bottom top"><?php echo $rs_pre_quotation_detail['title_pre_quotation']?></td>
										<td class="bd-right b-bottom top"><?php echo $rs_quo_relationship['num_pre_quotation']?></td>
										<td class="bd-right b-bottom top"><?php echo $rs_pre_quotation_detail['unit_pre_quotation']?></td>
										<td class="bd-right b-bottom top"><?php echo $rs_quo_relationship['price_product']?></td>
										<td class="bd-right b-bottom top right-price">
											<?php echo number_format($rs_quo_relationship['sum_quotation'],2)?>
											<a href="<?=$_SERVER["PHP_SELF"];?>?action=edit&id_p=<?=$rs_quo_relationship['id_quo_relation'];?>&id_u=<?php echo $id?>"><img src="img/edit.png" style="width:20px;"></a>
											<a href="JavaScript:if(confirm('Confirm Delete?')==true){window.location='<?=$_SERVER["PHP_SELF"];?>?action=del&id_p=<? echo $rs_quo_relationship['id_quo_relation'];?>&id_u=<?php echo $id?>';}"><img src="img/delete.png" style="width:20px;"></a>
										</td>
									</tr>
									<?php }
									}//end while pre quotation relationship
									?>
									<script language="JavaScript">
										function fncSum(){
											document.frm.sum_quotation_price.value = parseFloat(document.frm.num_pre_quotation.value) * parseFloat(document.frm.pre_quotation_price.value);
										}
									</script>
								<tr>
									<td class="bd-right b-bottom w8"></td>
									<td class="bd-right b-bottom w30">
										<input type="hidden" name="id_pre_quotation" id="id_pre_quotation">
										<input type="hidden" name="id_product_appearance" id="id_product_appearance">
										<input type="hidden" name="id_type_product" id="id_type_product">	
										<input type="text" name="title_pre_quotation" id="title_pre_quotation" class="title_pre_quotation"></td>
										<td class="bd-right b-bottom w5"><input type="text" name="num_pre_quotation" id="num_pre_quotation" value="" OnChange="fncSum();"></td>
										<td class="bd-right b-bottom w5"><input type="text" name="unit_pre_quotation" id="unit_pre_quotation"></td>
										<td class="bd-right b-bottom w10"><input type="text" id="pre_quotation_price" name="pre_quotation_price" value="" OnChange="fncSum();">
										<td class="bd-right b-bottom w10"><input type="text" name="sum_quotation_price" id="sum_quotation_price" value="" style="float: left; width: 85%;"><input name="btnAdd" type="button" id="btnAdd" value="Add" OnClick="frm.hdnCmd.value='Add';JavaScript:return fncSubmit();" class="btn-new2"></td>
								</tr>
								<tr>
									<td class="bd-right b-bottom top" rowspan="4">หมายเหตุ :</td>
									<td class="bd-right b-bottom" colspan="2" rowspan="4">
										<?php
										$sql_quo_rela="select * from quotation_relationship where id_quotation='".$id_quo."'";
										$res_quo_rela=mysql_query($sql_quo_rela) or die ('Error '.$sql_quo_rela);
										$rs_quo_rela=mysql_fetch_array($res_quo_rela);
										if($rs_quo_rela['id_type_product']==1){
											echo '1.ระยะเวลาในการพัฒนาผลิตภัณฑ์ประมาณ 60-90 วัน';
											echo '<br>';
											echo '2.ระยะเวลาในการจดทะเบียน อย. ประมาณ 90-180 วัน โดยเริ่มนับหลังจากวันที่ได้รับเอกสารครบถ้วนจากลูกค้า และได้รับชำระเงินเป็นที่เรียบร้อยแล้ว ไม่รวมค่าตรวจวิเคราะห์ตามประกาศกระทรวงฯ';
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
									<td class="bd-right b-bottom" colspan="2">รวมเป็นเงิน</td>
									<td class="bd-right b-bottom top right-price">
										<?php
										if(is_numeric($id)){$id1=$id_quo;}else{$id1=0;}
										$total=0;
										$sql_quo_relationship="select * from quotation_relationship";
										$sql_quo_relationship .=" where id_quotation='".$id1."'";
										$res_quo_relationship=mysql_query($sql_quo_relationship) or die ('Error '.$sql_quo_relationship);
										while($rs_quo_relationship=mysql_fetch_array($res_quo_relationship)){	
											$total=$rs_quo_relationship['sum_quotation']+$total;
										}
										echo number_format($total,2); 
										?>
										<input type="hidden" name="total" value="<?php echo $total?>">
									</td>
								</tr>
								<tr>												
									<td class="bd-right b-bottom" colspan="2">ส่วนลด</td>
									<td class="bd-right b-bottom">
										<script language="JavaScript">
											function fncTotal(){
												document.frm.total_discount.value = parseFloat(document.frm.total.value) - parseFloat(document.frm.discount.value);

												document.frm.vat.value = parseFloat(document.frm.total_discount.value) * 7/100;

												document.frm.total_price.value = parseFloat(document.frm.total_discount.value) + parseFloat(document.frm.vat.value);
											}
										</script>
										<input type="text" name="discount" value="<?php if($rs['discount'] != 0){echo $rs['discount'];}else{echo '';}?>" class="right-price" OnChange="fncTotal();">
									</td>
								</tr>
								<tr>
									<td class="bd-right b-bottom" colspan="2">รวมเงินหลังหักส่วนลด</td>
									<td class="bd-right b-bottom"><input type="text" name="total_discount" value="<?php echo number_format($rs['total_discount'],2)?>" class="right-price"></td>
								</tr>
								<tr>
									<td class="bd-right b-bottom" colspan="2">Vat 7%</td>
									<td class="bd-right b-bottom"><input type="text" name="vat" value="<?php echo number_format($rs['vat'],2)?>" class="right-price"></td>
								</tr>
								<tr>
									<td class="bd-right b-bottom center" colspan="3">
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
												if($number[1]=='0' OR $number[1]=='00' OR 
													$number[1]==''){ 
													$convert .= 'ถ้วน'; 
												}else{ 
													$strlen = strlen($number[1]); 
													for($i=0;$i<$strlen;$i++){ 
													$n = substr($number[1], $i,1); 
														if($n!=0){ 
														if($i==($strlen-1) AND $n==1){$convert 
														.= 'เอ็ด';} 
														elseif($i==($strlen-2) AND 
														$n==2){$convert .= 'ยี่';} 
														elseif($i==($strlen-2) AND 
														$n==1){$convert .= '';} 
														else{ $convert .= $txtnum1[$n];} 
														$convert .= $txtnum2[$strlen-$i-1]; 
													} 
												} 
												$convert .= 'สตางค์'; 
											} 
											return $convert; 
										} 
										//$x = '37840'; 
										echo  $x  .convert($rs['total_price']); 
									?>
									</td>
									<td class="bd-right b-bottom" colspan="2">จำนวนเงินทั้งสิ้น</td>
									<td class="bd-right b-bottom"><input type="text" name="total_price" value="<?php echo number_format($rs['total_price'],2)?>" class="right-price"></td>
								</tr>
								<tr>
									<td class="bd-right" colspan="2" rowspan="2"><b>เงื่อนไขอื่นๆ</b><br>
										(1) เงื่อนไขการชำระเงิน 100% พร้อมใบเสนอราคา<br>
										(2) กรุณาโอนเงินเข้าบัญชีธนาคารทหารไทย เลขที่บัญชี 073-1-05570-3<br>
											ชื่อบัญชี บริษัท ซีดีไอพี (ประเทศไทย) จำกัด <br>
										(3) ข้อสงสัยเรื่องการชำระเงินกรุณาติดต่อแผนกบัญชี เบอร์ 02 564 7200 ต่อ 5227<br>
										(4) บริษัทฯจะยืนยันวันส่งของกลับภายหลังได้รับใบสั่งซื้อภายใน 7 วันทำการ<br>
										(5) ในกรณีที่มีการยกเลิกใบสั่งซื้อบริษัทฯขอสงวนสิทธิ์ในการคืนเงินมัดจำ<br>
										(6) ผู้ซื้อต้องรับสินค้าทั้งหมดที่ผลิตเสร็จแล้ว ภายใน 7 วัน หลังได้รับแจ้งจาก บริษัท ซีดีไอพีฯ   กรณีรับสินค้าไม่หมด คิดค่าใช้จ่ายในการเก็บรักษาสินค้า วันละ 0.25% ของมูลค่าสินค้าที่ค้างส่ง

									</td>
									<td class="bd-right b-bottom" colspan="2">ผู้จัดทำ<br>
										<?php
										$sql_acc="select * from account where id_account='".$rs['create_by']."'";
										$res_acc=mysql_query($sql_acc) or die ('Error '.$sql_acc);
										$rs_acc=mysql_fetch_array($res_acc);
										echo $rs_acc['name'];
										?>
									</td>
									<td class="bd-right b-bottom" colspan="2" rowspan="2">อนุมัติโดย</td>
								</tr>
								<tr>
									<!--<td class="bd-right b-bottom" style="vertical-align:top;"colspan="2">
											<?php
											$i=0;
											$sql_remark="select * from quotation_relationship inner join pre_quotation_detail";
											$sql_remark .=" on quotation_relationship.id_pre_quotation=pre_quotation_detail.id_pre_quotation";
											$sql_remark .=" and quotation_relationship.id_quotation='".$id_quo."'";
											$sql_remark .=" inner join pre_quotation_remark on pre_quotation_remark.id_type_product =pre_quotation_detail.id_type_product and pre_quotation_remark.status='2'";
											$res_remark=mysql_query($sql_remark) or die ('Error '.$sql_remark);
											while($rs_remark=mysql_fetch_array($res_remark)){
												$i++;
												echo $i.'. '.$rs_remark['title_remark'].'<br>';
											}
											?>										
									</td>-->
									<td class="bd-right b-bottom" colspan="2">ผู้ตรวจสอบ</td>
								</tr>
							</table>
						</div>
					</td>
				</tr>
				<tr>
					<td class="b-top">
						<div class="large-4 columns">
							<?php 
							if(!is_numeric($id)){
							?>
							<input type="button" name="save_data" id="save_data" value="<?php echo $button?>" class="button-create" OnClick="frm.hdnCmd.value='save';JavaScript:return fncSubmit();">
							<?php }else{ ?>
							<input type="button" name="update_data" value="<?php echo $button?>" class="button-create" OnClick="frm.hdnCmd.value='update';JavaScript:return fncSubmit();">
							<?php }?>
							<input type="button" name="update_data" id="update_data" value="Print PDF" class="button-create" Onclick="window.open('pdf-pre-quo.php?id_u=<?=$id?>')">
							<input type="button" value="Close" class="button-create" onclick="window.location.href='quotation.php'">
						</div>
					</td>
				</tr>
			</table>
			</form>
		</div>
	</div>

  <!--<script>
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
  
  
  
  <script>
    $(document).foundation();
  </script>-->
</body>
</html>
