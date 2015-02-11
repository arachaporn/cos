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
<script type="text/javascript" src="rmm-js/responsivemobilemenu.js"></script>
<script src="js/vendor/custom.modernizr.js"></script>
<script language="javascript">
function fncSubmit(){
	document.frm.submit();
}
</script>
</head>  
<body> 
	<?php include("menu.php")?>
	<div class="row">
		<div class="background">
			<?php
			if($_GET["id_u"]=='New'){
				$mode='New';
				$button='Save';
				$id='New';				
			}
			else{
				$id=$_GET["id_u"];
				$sql="select * from roc where id_roc='".$id."'";
				$res=mysql_query($sql) or die ('Error '.$sql);
				$rs=mysql_fetch_array($res);
				$sql_type_product="select * from type_product where id_type_product='".$rs['id_type_product']."'";
				$res_type_product=mysql_query($sql_type_product) or die ('Error '.$sql_type_product);
				$rs_type_product=mysql_fetch_array($res_type_product);
				
				$mode='Edit '.$rs_type_product['title'];
				$button='Update';
			}			
			/*delete roc rm*/
			if($_GET["action"] == "del_rm"){
				$sql = "delete from roc_rm ";
				$sql .="where id_roc_rm = '".$_GET["id_p"]."'";
				$res = mysql_query($sql) or die ('Error '.$sql);
			}
			?> 
			<script type="text/javascript" src="js/js-autocomplete/js/jquery-1.4.2.min.js"></script> 
			<script type="text/javascript" src="js/js-autocomplete/js/jquery-ui-1.8.2.custom.min.js"></script> 
			<script type="text/javascript"> 
				jQuery(document).ready(function(){
					$('.company_name').autocomplete({
						source:'return-company.php', 
						//minLength:2,
						select:function(evt, ui)
						{
							// when a zipcode is selected, populate related fields in this form
							this.form.id_company.value = ui.item.id_company;
							this.form.com_contact.value = ui.item.com_contact;
							this.form.id_address.value = ui.item.id_address;
							this.form.company_address.value = ui.item.company_address;
							this.form.id_contact.value = ui.item.id_contact;
							this.form.company_tel.value = ui.item.company_tel;
							this.form.company_fax.value = ui.item.company_fax;
							this.form.mobile.value = ui.item.mobile;
							this.form.company_email.value = ui.item.company_email;
							this.form.com_cate.value = ui.item.com_cate;

						}
					});
					$('.com_contact').autocomplete({
						source:'return-contact.php', 
						//minLength:2,
						select:function(evt, ui){
							this.form.id_contact.value = ui.item.id_contact;
							this.form.mobile.value = ui.item.mobile;
							this.form.company_email.value = ui.item.company_email;
						}
					});
					$('.product_name').autocomplete({
						source:'return-product.php', 
						//minLength:2,
						select:function(evt, ui){
							this.form.id_product.value = ui.item.id_product;
						}
					});
				});
			</script> 
			<link rel="stylesheet" href="js/js-autocomplete/css/smoothness/jquery-ui-1.8.2.custom.css" /> 
			<form name="frm" method="post" action="dbroc.php">
			<input type="hidden" name="hdnCmd" value="">
			<input type="hidden" name="account" value="<?php echo $rs_account['id_account']?>">
			<table style="border: 0; width: 100%;" cellpadding="0" cellspacing="0">
				<tr>
					<td class="b-bottom"><div class="large-4 columns"><h4><h4>ROC >> <?php echo $mode;?></h4></h4></div></td>
				</tr>
				<tr>
					<td class="b-bottom">
						<div class="large-4 columns">							
							<?php if(!is_numeric($id)){?>
							<input type="button" name="save_data" id="save_data" value="<?php echo $button?>" class="button-create" OnClick="frm.hdnCmd.value='save_data';JavaScript:return fncSubmit();">
							<?php }else{?>
							<input type="button" name="update_data" id="update_data" value="<?php echo $button?>" class="button-create" OnClick="frm.hdnCmd.value='update_data';JavaScript:return fncSubmit();">
							<input type="button" name="finished_data" id="finished_data" value="Finished" class="button-create" OnClick="frm.hdnCmd.value='finished_data';JavaScript:return fncSubmit();">
							<?php }?>
							<input type="button" value="Close" class="button-create" onclick="window.location.href='roc.php'">
						</div>
					</td>
				</tr>
				<tr>
					<td style="background: #fff;">
						<div class="large-4 columns">
							<input type="hidden" name="mode" value="<?php echo $id?>">
							<table style="border: 0; width: 100%;" cellpadding="0" cellspacing="0" id="tb-req">
								<tr>
									<td class="b-bottom center" colspan="6">
										<div class="tb-h">
											<img src="img/logo.png" width="140" class="img-logo">
											<div class="header-text">บริษัท ซีดีไอพี (ประเทศไทย) จำกัด<br>
											CDIP (Thailand) Co.,Ltd.<br>
											บันทึกความต้องการของลูกค้า (Requisition of Customer)
											</div>
										</div>
									</td>
								</tr>
								<?php if($_GET['p']==1){?>
								<input type="hidden" name="pages" value="1">
								<tr>
									<td class="top w13">บริษัท</td>
									<td class="top w25">
									<?php 
										$sql_company="select * from company where id_company='".$rs['id_company']."'";
										$res_company=mysql_query($sql_company) or die ('Error '.$sql_company);
										$rs_company=mysql_fetch_array($res_company);
									?>
									<input name="company_name" type="text" id="company_name" class="company_name" value="<?php if(is_numeric($id)){echo $rs_company['company_name'];}?>"/>
									<input name="id_company" type="hidden" id="id_company" value="<?php if(is_numeric($id)){echo $rs_company['id_company'];}?>" />
									</td>
									<td class="top">ชื่อผู้ติดต่อ</td>
									<td class="top w30">
									<?php 
										$sql_contact="select * from company_contact where id_contact='".$rs['id_contact']."'";
										$res_contact=mysql_query($sql_contact) or die ('Error '.$sql_contact);
										$rs_contact=mysql_fetch_array($res_contact);
										?>
										<input name="com_contact" type="text" id="com_contact" class="com_contact" value="<?php if(is_numeric($id)){echo $rs_contact['contact_name'];}?>"/>
										<input name="id_contact" type="hidden" id="id_contact" value="<?php if(is_numeric($id)){echo $rs_contact['id_contact'];}?>" />							
									</td><td class="top w6">เลขที่เอกสาร</td>
									<td class="top">
										<?php $month=date("m")?>
										<input type="text" name="roc_code" readonly value="<?php if(is_numeric($id)){echo $rs['roc_code'];}else{echo 'ROC-';
											echo $rs_account['username'];
											echo date("y").date("m");
											$sql_roc_code="select * from roc order by id_roc desc";
											$res_roc_code=mysql_query($sql_roc_code) or die ('Error '.$sql_roc_code);
											$rs_roc_code=mysql_fetch_array($res_roc_code);
												if($month==$rs_roc_code['roc_month']){
													$num = $rs_roc_code['roc_num']+1;
												}else{$num=1;}												
											echo sprintf("%03d",$num);
											echo $numf;
										}?>">
										<input type="hidden" name="roc_month" value="<?php if(is_numeric($id)){echo $rs['roc_month'];}else{if($month==$rs_roc_code['roc_month']){echo $rs_roc_code['roc_month'];}else{ echo date("m");}}?>">
										<input type="hidden" name="roc_num" value="<?php if(is_numeric($id)){echo $rs['roc_num'];}else{if($month==$rs_roc_code['roc_month']){echo $num;}else{echo $num=1;}}?>">
									</td>
								</tr>
								<tr>
									<td class="top w10">เบอร์โทรศัพท์</td>
									<td class="top"><input type="text" name="company_tel" id="company_tel" value="<?php if(is_numeric($id)){echo $rs_company['company_tel'];}?>"></td>
									<td class="top">มือถือ</td>
									<td class="top"><input type="text" name="mobile" value="<?php if(is_numeric($id)){echo $rs_contact['mobile'];}?>"></td>
								</tr>
								<tr>
									<td class="top w13">วันที่</td>
									<td class="top"><input type="text"  name="date_roc" style="width: 70%; float: left; margin-right: 2%;" value="<?php if(is_numeric($id)){echo $rs['date_roc'];}else{echo date("Y-m-d");}?>" readonly></td>
									<td class="top">แฟกซ์</td>
									<td class="top"><input type="text" name="company_fax" id="company_fax" value="<?php if(is_numeric($id)){echo $rs_company['company_fax'];}?>"></td>
									<td class="top">อีเมล์</td>
									<td class="top"><input type="text" name="company_email" id="company_email" value="<?php if(is_numeric($id)){echo $rs_contact['email'];}?>"></td>
								</tr>
								<tr>
									<td class="top">ที่อยู่</td>
									<td class="top">
										<?php 
										$sql_address="select * from company_address where id_address='".$rs_company['id_address']."'";
										$res_address=mysql_query($sql_address) or die ('Error '.$sql_address);
										$rs_address=mysql_fetch_array($res_address);
										?>
										<input type="hidden" name="id_address" id="id_address" value="<?php if(is_numeric($id)){echo $rs['id_address'];}?>">
										<textarea name="company_address" id="company_address"><?php if(is_numeric($id)){echo $rs_address['address_no'].'&nbsp;'.$rs_address['road'].'&nbsp;'.$rs_address['sub_district'].'&nbsp;'.$rs_address['district'].'&nbsp;'.$rs_address['province'].'&nbsp;'.$rs_address['postal_code'];}?></textarea>
									</td>
								</tr>
								<?php
								$rows_com_cate=0;
								$j=0;
								$sql_com_cate="select * from company_category";
								$res_com_cate=mysql_query($sql_com_cate) or die ('Error '.$sql_com_cate);
								$max_row=mysql_num_rows($res_com_cate);
								while($rs_com_cate=mysql_fetch_array($res_com_cate)){
									if($rows_com_cate % 2 ==0){?><tr><?php }
										$j++;
										if($j==1){$title='Identify Customer';}
										else{$title='';}
								?>
									<td class="top"><?php echo $title?></td>
									<td class="top">
									<input type="checkbox" name="com_cate" id="com_cate" class="com_cate" value="<?php echo $rs_com_cate['id_com_cat']?>" <?php if($rs_com_cate['id_com_cat']==$rs['id_com_cat']){echo 'checked';}?>><?php echo $rs_com_cate['title']?></td>
									<?php if($j % 2 == 0){ ?></tr><?php } 
										$rows_com_cate++;
									}//end while type device
									if($max_row==$rows_com_cate){
									?>
									<td class="top"><?php echo $title?></td>
									<td class="top" colspan="2"><div style="float:left; margin: 0 0.5% 0 0;"><input type="checkbox" class="checkbox"></div><div style="float:left; margin: 0 0.5% 0 0;">อื่น ๆ</div><div style="float:left"><input type="text" style="width: 200%;"></div></td>
								<?php }?>
								</tr>
								<tr>
									<td class="top w10"><p>Project Name/Benchmark</p></td>
									<td class="top" colspan="2">
										<?php 
										$sql_product="select * from product where id_product='".$rs['id_product']."'";
										$res_product=mysql_query($sql_product) or die ('Error '.$sql_product);
										$rs_product=mysql_fetch_array($res_product);
										?>
										<input type="text" name="product_name" id="product_name" class="product_name"  value="<?php if(is_numeric($id)){echo $rs_product['product_name'];}?>">
										<input type="hidden" name="id_product" id="id_product" value="<?php if(is_numeric($id)){echo $rs_product['id_product'];}?>" />
									</td>
								</tr>
								<!--<tr>
									<td class="top w10"><p>Project ID</p></td>
									<td class="top"><input type="text" name="project_id" value="<?php echo $rs['project_id']?>"></td>
								</tr>-->
								<tr>
									<td class="top w10"><h5>วัตถุประสงค์ที่ต้องการ</h5></td>
								</tr>
								<tr>
									<td class="top w10"><p class="title">1.ฟังก์ชั่นการทำงาน</p></td>
								</tr>
								<?php
								$roc_func=split(",",$rs['id_roc_func']);
								$i=0;
								$sql_roc_group_product="select * from roc_group_product";
								$res_roc_group_product=mysql_query($sql_roc_group_product) or die ('Error '.$sql_roc_group_product);
								$max_row_g=mysql_num_rows($res_roc_group_product);
								while($rs_roc_group_product=mysql_fetch_array($res_roc_group_product)){
									$i++;
								?>
									<tr>
										<td class="title-group" colspan="6"><input type="radio" name="roc_group_product" id="roc_group_product<?php echo $i?>" value="<?php echo $rs_roc_group_product['id_group_product']?>" <?php if(is_numeric($id)){if($rs['id_group_product']==$rs_roc_group_product['id_group_product']){echo 'checked';}}?>>1.<?php echo $i.'&nbsp;'.$rs_roc_group_product['title'];?></td>
									</tr>
									<?php									
									$rows = 0;
									$j=0;
									$num=0;
									$sql_roc_function="select * from roc_function where id_group_product='".$rs_roc_group_product['id_group_product']."'";
									$res_roc_function=mysql_query($sql_roc_function) or die ('Error '.$sql_roc_function);
									$max_row=mysql_num_rows($res_roc_function);
									while($rs_roc_function=mysql_fetch_array($res_roc_function)){
										if($rows % 2 ==0){?><tr><?php }
										$j++;
										$num++;
									?>
										<td class="title-function w10" colspan="2">
										<input type="checkbox" class="checkbox" name="roc_function[]" id="roc_function<?php echo $rs_roc_function['id_roc_func']?>" rel="roc_group_product<?php echo $i?>" value="<?php echo $rs_roc_function['id_roc_func']?>" <?php if(in_array($rs_roc_function['id_roc_func'],$roc_func)){echo 'checked';}?>><?echo $rs_roc_function['title']?></td>
										<?php if($num==$max_row){?>
										<td class="title-function" colspan="2"><div style="float:left; margin: 0 0.5% 0 0;"><input type="checkbox" class="checkbox" value="0" name="roc_function[]"></div><div style="float:left; margin: 0 0.5% 0 0;">อื่น ๆ</div><div style="float:left"><input type="text" name="roc_functin_other" value="<?php echo $rs_roc_function['other_roc']?>"></div></td>
									<?php }
										if($j % 2 == 0){ ?></tr><?php } //display end row
										$rows++;
									}//end while roc function
								}//end while roc_group_product 
								$max_row_g=$max_row_g+1;
								?>
									<tr>
										<td class="title-group" colspan="6"><input type="radio" name="roc_group_product" id="roc_group_product<?php echo $i+1?>" <?php if($rs['id_group_product']== -1){echo 'checked';}?> value="-1">1.<?php echo $max_row_g.'&nbsp;อื่น ๆ'?>
										<textarea style="width:50%; height:100px;" name="roc_group_product_other"><?php if(is_numeric($id)){echo $rs['roc_group_product_other'];}?></textarea></td>
									</tr>
								<?php }elseif($_GET['p']==2){
								?>
									<input type="hidden" name="pages" value="2">
									<input type="hidden" name="id_product_appearance" value="<?php echo $rs['id_product_appearance']?>">
									<input type="hidden" name="id_product_value" value="<?php echo $rs['id_product_value']?>">
									<input type="hidden" name="id_type_product_pack" value="<?php echo $rs['id_type_product_pack']?>">
									<input type="hidden" name="id_product_weight" value="<?php echo $rs['id_product_weight']?>">
									<input type="hidden" name="id_type_product_color" value="<?php echo $rs['id_type_product_color']?>">
									<tr>										
										<td colspan="6"><p class="title" style="padding-top: 1%;">2.ระบุสารสำคัญที่ต้องการ/ข้อเสนอแนะอื่น ๆ (ถ้ามี)</p>
										<table style="border: none; width: 20%; margin-top: 1%;" cellpadding="0" cellspacing="0">
											<?php
											$sql_roc_rm="select * from roc_rm where id_roc='".$id."'";
											$res_roc_rm=mysql_query($sql_roc_rm) or die('Error '.$sql_roc_rm);
											while($rs_roc_rm=mysql_fetch_array($res_roc_rm)){									
												if($rs_roc_rm['id_roc_rm'] == $_GET['id_p'] and $_GET["action"] == 'edit_rm'){
											?>	
												<tr>
													<input type="hidden" name="hdnEdit" value="<?php echo $rs_roc_rm['id_roc_rm']?>">
													<td><input type="text" name="roc_rm2" value="<?php echo $rs_roc_rm['roc_rm']?>"></td>
													<td>
													<input name="btnAdd" type="button" id="btnUpdate" value="Update" OnClick="frm.hdnCmd.value='update_rm';JavaScript:return fncSubmit();" class="btn-update">
													<input name="btnAdd" type="button" id="btnCancel" value="Cancel" OnClick="window.location='<?=$_SERVER["PHP_SELF"]."?id_u=".$id."&p=2"?>';" class="btn-cancel">
													</td>
												</tr>
												<?php }else{?>
												<tr>
													<td><?php echo $rs_roc_rm['roc_rm']?></td>
													<td>
														<div style="float:left;"><?php echo $rs_itenary['objective_visiting']?></div>
														<a href="<?=$_SERVER["PHP_SELF"];?>?id_u=<?php echo $id?>&p=2&action=edit_rm&id_p=<?=$rs_roc_rm['id_roc_rm'];?>"><img src="img/edit.png" style="width:20px;"></a>
														<a href="JavaScript:if(confirm('Confirm Delete?')==true){window.location='<?=$_SERVER["PHP_SELF"];?>?id_u=<?php echo $id?>&p=2&action=del_rm&id_p=<? echo $rs_roc_rm['id_roc_rm'];?>';}"><img src="img/delete.png" style="width:20px;"></a>
													</td>
												</tr>
											<?php }//end if
											}//end roc rm?>
												<tr>
													<input type="hidden" name="id_roc2" value="<?php echo $rs['id_roc']?>">
													<td><input type="text" name="roc_rm"></td>
													<td><input name="btnAdd" type="button" id="btnAdd" value="Add"  OnClick="frm.hdnCmd.value='add_rm';JavaScript:return fncSubmit();" class="btn-new-itenary"></td>
												</tr>
										</table>
										</td>
									</tr>
									<tr>
										<td colspan="6"><p class="title">3.ลักษณะรูปแบบผลิตภัณฑ์</p></td>
									</tr>
									<?php
									$num=0;
									$sql_product_appearance="select * from product_appearance limit 0,3";
									$res_product_appearance=mysql_query($sql_product_appearance) or die ('Error '.$sql_product_appearance);
									$max_row=mysql_num_rows($res_product_appearance);
									while($rs_product_appearance=mysql_fetch_array($res_product_appearance)){
										$num++;
									?>		
										<tr>
											<td class="title-group" colspan="6"><input type="radio" name="id_product_appearance" value="<?php echo $rs_product_appearance['id_product_appearance']?>" <?php if($rs['id_product_appearance']==$rs_product_appearance['id_product_appearance']){echo 'checked';}?>><?php echo $rs_product_appearance['title_thai'].'('.$rs_product_appearance['title'].')'?></td>
										</tr>										
										<tr>
											<?php if($rs_product_appearance['id_product_appearance']==1){?><td class="title-weight">ลักษณะของเม็ด</td><?php }?>
											<?php if(($rs_product_appearance['id_product_appearance']==2) || ($rs_product_appearance['id_product_appearance']==3)){?><td class="title-weight">ชนิดของเปลือกแคปซูล</td><?php }?>
										</tr>
										<?php 
										$num_v=0;
										$rows_v = 0;
										$j_v=0;
										$sql_roc_product_v="select * from roc_product_value where id_type_product='".$rs_product_appearance['id_product_appearance']."'";
										$res_roc_product_v=mysql_query($sql_roc_product_v) or die ('Error '.$sql_roc_product_v);
										$max_row_v=mysql_num_rows($res_roc_product_v);
										while($rs_roc_product_v=mysql_fetch_array($res_roc_product_v)){
											if($rows_v % 2 ==0){?><tr><?php }
											$j_v++;
											$num_v++;											
										?>
											<td class="title-function"><input type="radio" class="checkbox" name="roc_product_value[]" value="<?php echo $rs_roc_product_v['id_product_value']?>" <?php if($rs['id_product_value']==$rs_roc_product_v['id_product_value']){echo 'checked';}?>><?echo $rs_roc_product_v['title']?></td>
											<?php if($num_v==$max_row_v){
												if(($rs_product_appearance['id_product_appearance']!= 2) && ($rs_product_appearance['id_product_appearance']!= 3)){
											?>
											<td class="title-function"><div style="float:left; margin: 0 0.5% 0 0;"><input type="radio" class="checkbox" name="roc_product_value" value="0"></div><div style="float:left; margin: 0 0.5% 0 0;">อื่น ๆ</div><div style="float:left"><input type="text" name="roc_product_value_other"></div></td>
											<?php }//end not equal capsule 
											}?>
										<?php if($j_v % 2 == 0){ ?></tr><?php } //display end row
											$rows_v++;
										}//end while roc_product_value
										if(($rs_product_appearance['id_product_appearance']== 2) || ($rs_product_appearance['id_product_appearance']== 3)){?>
										<tr>
											<td class="title-weight">ลักษณะผลิตภัณฑ์ที่บรรจุ</td>
										</tr>
										<?php 
										$num_p=0;
										$rows_p=0;
										$j_p=0;
										$sql_roc_product_p="select * from type_product_pack where id_product_appearance='".$rs_product_appearance['id_product_appearance']."'";
										$res_roc_product_p=mysql_query($sql_roc_product_p) or die ('Error '.$sql_roc_product_p);
										$max_row_p=mysql_num_rows($res_roc_product_p);
										while($rs_roc_product_p=mysql_fetch_array($res_roc_product_p)){
											if($rows_p % 2 ==0){?><tr><?php }
											$j_p++;
											$num_p++;
										?>
											<td class="title-function"><input type="radio" class="checkbox" name="type_product_pack" value="<?php echo $rs_roc_product_p['id_type_product_pack']?>" <?php if($rs['id_type_product_pack']==$rs_roc_product_p['id_type_product_pack']){echo 'checked';}?>><?echo $rs_roc_product_p['title_product_pack']?></td>
											<?php if($num_p==$max_row_p){
												if($rs_product_appearance['id_type_product']== 3){
											?>
											<td class="title-function"><div style="float:left; margin: 0 0.5% 0 0;"><input type="radio" class="checkbox" name="type_product_pack" value="0"></div><div style="float:left; margin: 0 0.5% 0 0;">อื่น ๆ</div><div style="float:left"><input type="text" name="type_product_pack_other"></div></td>
											<?php }//end not equal capsule 
											}
											if($j_p % 2 == 0){ ?></tr><?php } //display end row
											$rows_p++;
											}//end type product pack
										}//end capsule?>
										<tr>
											<td class="title-weight" colspan="6">น้ำหนักผลิตภัณฑ์ต่อหน่วย</td>
										<tr>
										<?php
										$num_w=0;
										$rows = 0;
										$j=0;
										$sql_roc_product_w="select * from roc_product_weight where id_product_appearance='".$rs_product_appearance['id_product_appearance']."'";
										$res_roc_product_w=mysql_query($sql_roc_product_w) or die ('Error '.$sql_roc_product_w);
										$max_row_w=mysql_num_rows($res_roc_product_w);
										while($rs_roc_product_w=mysql_fetch_array($res_roc_product_w)){
											if($rows % 4 ==0){?><tr><?php }
											$j++;
											$num_w++;											
										?>
											<td class="title-function"><input type="radio" class="checkbox" name="roc_product_weight" value="<?php echo $rs_roc_product_w['id_product_weight']?>" <?php if($rs['id_product_weight']==$rs_roc_product_w['id_product_weight']){echo 'checked';}?>><?echo $rs_roc_product_w['title']?></td>
											<?php if($num_w==$max_row_w){?>
											<td class="title-function"><div style="float:left; margin: 0 0.5% 0 0;"><input type="radio" class="checkbox" name="roc_product_weight" value="0"></div><div style="float:left; margin: 0 0.5% 0 0;">อื่น ๆ</div><div style="float:left"><input type="text" name="roc_product_weight_other"></div></td>
											<?php } ?>
										<?php if($j % 4 == 0){ ?></tr><?php } //display end row
											$rows++;
										}//end while roc_product_w
										if(($rs_product_appearance['id_product_appearance']==1) || ($rs_product_appearance['id_product_appearance']==2) || ($rs_product_appearance['id_product_appearance']==3)){?>
										<tr>
											<?php if($rs_product_appearance['id_product_appearance']==1){?><td class="title-weight">สี กลิ่น และรูปร่างของเม็ด</td>
											<?php }elseif($rs_product_appearance['id_product_appearance']==2){?><td class="title-weight">สีของแคปซูล</td>
											<?php }else{?><td class="title-weight">สีและกลิ่นเปลือกแคปซูล</td><?php }?>
										</tr>
										<?php
										$roc_color=split(",",$rs['id_type_product_color']);
										$num_c=0;
										$rows_c=0;
										$j_c=0;
										$sql_roc_product_c="select * from type_product_color where id_product_appearance='".$rs_product_appearance['id_product_appearance']."'";
										$res_roc_product_c=mysql_query($sql_roc_product_c) or die ('Error '.$sql_roc_product_c);
										$max_row_c=mysql_num_rows($res_roc_product_c);
										while($rs_roc_product_c=mysql_fetch_array($res_roc_product_c)){
											if($rows_c % 2 ==0){?><tr><?php }
											$j_c++;
											$num_c++;	
										?>
											<td class="title-function"><div style="float:left; margin: 0 0.5% 0 0;"><input type="checkbox" class="checkbox" name="type_product_color[]" value="<?php echo $rs_roc_product_c['id_type_product_color']?>" <?php if(in_array($rs_roc_product_c['id_type_product_color'],$roc_color)){echo 'checked';}?>></div><div style="float:left; margin: 0 0.5% 0 0;"><?echo $rs_roc_product_c['type_product_color']?></div>
											<?php
												if(($rs_product_appearance['id_product_appearance']==1) || ($rs_product_appearance['id_product_appearance']==3)){
													if(($rs_roc_product_c['type_product_color']== 'สี (Color)') ||($rs_roc_product_c['type_product_color']=='กลิ่น (Odor)') || ($rs_roc_product_c['type_product_color']=='รูปร่าง (Shape)') || ($rs_roc_product_c['type_product_color']=='สี') || ($rs_roc_product_c['type_product_color']=='กลิ่น')){
														echo '<div style="float:left"><input type="text" name="type_product_c_other"></div>';
													}//end color for soft gelation capsule
												}//end soft gelatin capsule
											?>
											</td>
											<?php if($num_c==$max_row_c){
												if($rs_type_product['id_type_product']!=3){
											?>
											<td class="title-function"><div style="float:left; margin: 0 0.5% 0 0;"><input type="checkbox" class="checkbox" name="type_product_color" value="0"></div><div style="float:left; margin: 0 0.5% 0 0;">อื่น ๆ</div><div style="float:left"><input type="text" name="type_product_color_other"></div></td>
											<?php } // not equal soft gelatin capsule
											}?>
										<?php if($j_c % 2 == 0){ ?></tr><?php } //display end row
											$rows_c++;
											}//end type product pack
										}//end hard capsule
									}//end type product 
								}//end page2
								elseif($_GET['p']==3){?>
									<input type="hidden" name="pages" value="3">
									<input type="hidden" name="id_product_appearance" value="<?php echo $rs['id_product_appearance']?>">
									<input type="hidden" name="id_product_value" value="<?php echo $rs['id_product_value']?>">
									<input type="hidden" name="id_type_product_pack" value="<?php echo $rs['id_type_product_pack']?>">
									<input type="hidden" name="id_product_weight" value="<?php echo $rs['id_product_weight']?>">
									<input type="hidden" name="id_type_product_color" value="<?php echo $rs['id_type_product_color']?>">
								<?php
									$num=0;
									$sql_product_appearance="select * from product_appearance limit 3,8";
									$res_product_appearance=mysql_query($sql_product_appearance) or die ('Error '.$sql_product_appearance);
									$max_row=mysql_num_rows($res_product_appearance);
									while($rs_product_appearance=mysql_fetch_array($res_product_appearance)){
										$num++;
									?>		
										<tr>
											<td class="title-group" colspan="6"><input type="radio" name="id_product_appearance" value="<?php echo $rs_product_appearance['id_product_appearance']?>" <?php if($rs['id_product_appearance']==$rs_product_appearance['id_product_appearance']){echo 'checked';}?>><?php echo $rs_product_appearance['title_thai'].'('.$rs_product_appearance['title'].')'?></td>
										</tr>		
										<?php 
										$roc_value=split(",",$rs['id_product_value']);
										$num_v=0;
										$rows_v = 0;
										$j_v=0;
										$sql_roc_product_v="select * from roc_product_value where id_type_product='".$rs_product_appearance['id_product_appearance']."'";
										$res_roc_product_v=mysql_query($sql_roc_product_v) or die ('Error '.$sql_roc_product_v);
										$max_row_v=mysql_num_rows($res_roc_product_v);
										while($rs_roc_product_v=mysql_fetch_array($res_roc_product_v)){
											if($rows_v % 2 ==0){?><tr><?php }
											$j_v++;
											$num_v++;
											if(($rs_product_appearance['id_product_appearance']==5) || ($rs_product_appearance['id_product_appearance']==6) || ($rs_product_appearance['id_product_appearance']==7)){$checkbox='checkbox';}else{$checkbox='radio';}
										?>
											<td class="w30 title-function"><div style="float:left; margin: 0 0.5% 0 0;"><input type="<?php echo $checkbox?>" class="checkbox" name="roc_product_value[]" value="<?php echo $rs_roc_product_v['id_product_value']?>" <?php if(in_array($rs_roc_product_v['id_product_value'],$roc_value)){echo 'checked';}?>></div><div style="float:left; margin: 0 0.5% 0 0;"><?echo $rs_roc_product_v['title']?></div>
											<?php 
												if(($rs_roc_product_v['title']== 'กลิ่น (Odor)') ||($rs_roc_product_v['title']=='รส (Taste)') || ($rs_roc_product_v['title']== 'สี (Color)') || ($rs_roc_product_v['title']== 'รูปร่าง (Shape)') ||
												($rs_roc_product_v['title']== 'น้ำหนัก')){
													echo '<div style="float:left"><input type="text" name="roc_product_value_other"></div>';
												}
											?>
											</td>
											<?php if($num_v==$max_row_v){
												if($rs_product_appearance['id_product_appearance']==4){
											?>
											<td class="title-function" colspan="6"><div style="float:left; margin: 0 0.5% 0 0;"><input type="radio" class="checkbox" name="roc_product_value[]" value="0"></div><div style="float:left; margin: 0 0.5% 0 0;">ผงชงดื่มประเภทอื่น</div><div style="float:left"><input type="text" name="roc_product_value_other"></div></td>
											<?php }//end not equal capsule 
											}?>
										<?php if($j_v % 2 == 0){ ?></tr><?php } //display end row
											$rows_v++;
										}//end while roc_product_value
										if($rs_product_appearance['id_product_appearance']==4){?>
										<tr>
											<td class="title-weight" colspan="6">สีและกลิ่นของผงชงดื่ม</td>
										</tr>
										<?php 
										$roc_color=split(",",$rs['id_type_product_color']);
										$num_c=0;
										$rows_c=0;
										$j_c=0;
										$sql_roc_product_c="select * from type_product_color where id_product_appearance='".$rs_product_appearance['id_product_appearance']."'";
										$res_roc_product_c=mysql_query($sql_roc_product_c) or die ('Error '.$sql_roc_product_c);
										$max_row_c=mysql_num_rows($res_roc_product_c);
										while($rs_roc_product_c=mysql_fetch_array($res_roc_product_c)){
											if($rows_c % 2 ==0){?><tr><?php }
											$j_c++;
											$num_c++;	
										?>
											<td class="title-function"><div style="float:left; margin: 0 0.5% 0 0;"><input type="checkbox" class="checkbox" name="type_product_color[]" value="<?php echo $rs_roc_product_c['id_type_product_color']?>"  <?php if(in_array($rs_roc_product_c['id_type_product_color'],$roc_color)){echo 'checked';}?>></div><div style="float:left; margin: 0 0.5% 0 0;"><?echo $rs_roc_product_c['type_product_color']?></div>
											<?php
												if($rs_product_appearance['id_product_appearance']==4){
													if(($rs_roc_product_c['type_product_color']== 'กลิ่น (Odor)') ||($rs_roc_product_c['type_product_color']=='รส (Taste)') || ($rs_roc_product_c['type_product_color']== 'สี (Color)')){
														echo '<div style="float:left"><input type="text" name="type_product_c_other"></div>';
													}//end color for Instant Drink
												}//end Instant Drink
											?>
											</td>
										<?php if($j_c % 2 == 0){ ?></tr><?php } //display end row
											$rows_c++;
											}//end type product color
										}//end Instant Drink
										if($rs_product_appearance['id_product_appearance'] != 7){
										?>
										<tr>
											<td class="title-weight" colspan="6">น้ำหนักผลิตภัณฑ์ต่อหน่วย</td>
										<tr>
										<?php
										$num_w=0;
										$rows = 0;
										$j=0;
										$sql_roc_product_w="select * from roc_product_weight where id_product_appearance='".$rs_product_appearance['id_product_appearance']."'";
										$res_roc_product_w=mysql_query($sql_roc_product_w) or die ('Error '.$sql_roc_product_w);
										$max_row_w=mysql_num_rows($res_roc_product_w);
										while($rs_roc_product_w=mysql_fetch_array($res_roc_product_w)){
											if($rows % 4 ==0){?><tr><?php }
											$j++;
											$num_w++;
										?>
											<td class="title-function"><input type="radio" name="roc_product_weight" value="<?php echo $rs_roc_product_w['id_product_weight']?>" <?php if($rs['id_product_weight']==$rs_roc_product_w['id_product_weight']){echo 'checked';}?>><?echo $rs_roc_product_w['title']?></td>
											<?php if($num_w==$max_row_w){?>
											<td class="title-function" colspan="2"><div style="float:left; margin: 0 0.5% 0 0;"><input type="radio" name="roc_product_weight"></div><div style="float:left; margin: 0 0.5% 0 0;">อื่น ๆ</div><div style="float:left"><input type="text" name="roc_product_weight_other"></div></td>
											<?php } ?>
										<?php if($j % 4 == 0){ ?></tr><?php } //display end row
											$rows++;
										}//end while roc_product_w
										}//end type product not equal gummy
										else{?>
										<tr>
											<td class="title-weight">ลักษณะการเคลือบ</td>
										</tr>
										<?php 
										$num_p=0;
										$rows_p=0;
										$j_p=0;
										$sql_roc_product_p="select * from type_product_pack where id_product_appearance='".$rs_product_appearance['id_product_appearance']."'";
										$res_roc_product_p=mysql_query($sql_roc_product_p) or die ('Error '.$sql_roc_product_p);
										$max_row_p=mysql_num_rows($res_roc_product_p);
										while($rs_roc_product_p=mysql_fetch_array($res_roc_product_p)){
											if($rows_p % 4 ==0){?><tr><?php }
											$j_p++;
											$num_p++;
										?>
											<td class="title-function"><input type="radio" class="checkbox" name="type_product_pack" value="<?php echo $rs_roc_product_p['id_type_product_pack']?>"><?echo $rs_roc_product_p['title_product_pack']?></td>
											<?php if($num_p==$max_row_p){?>
											<td class="title-function"><div style="float:left; margin: 0 0.5% 0 0;"><input type="radio" class="checkbox" name="type_product_pack" value="0"></div><div style="float:left; margin: 0 0.5% 0 0;">อื่น ๆ</div><div style="float:left"><input type="text" name="type_product_pack_other"></div></td>
											<?php }
											if($j_p % 4 == 0){ ?></tr><?php } //display end row
											$rows_p++;
											}//end type product pack
										} //end type product gummy
  									}//end type product ?>
									<tr>
									<?php 							
									 if($num==$max_row){echo '<td class="title-group" colspan="6"><input type="radio" name="roc_type_product" value="0">อื่น ๆ<textarea style="width:50%; height:100px;" name="roc_type_product_other"></textarea></td>';}
									?>
									</tr>
									<tr>
										<td colspan="6"><p class="title">4.บรรจุภัณฑ์</p></td>
									</tr>
									<tr>
									<?php
										$sql_relation_pack="select * from roc_relation_pack where id_relation_pack='".$rs['id_relation_pack']."'";
										$res_relation_pack=mysql_query($sql_relation_pack) or die ('Error '.$sql_relation_packs);
										$rs_relation_pack=mysql_fetch_array($res_relation_pack);
									?>
										<td class="title-group" colspan="6">
										<input type="hidden" name="id_relation_pack" value="<?php echo $rs_relation_pack['id_relation_pack']?>">
										<input type="radio" name="roc_pack" value="1,2,3" <?php if($rs_relation_pack['id_product_appearance']=='1,2,3'){echo 'checked';}?>>
										<?php
										$i_app=0;
										$sql_product_appearance="select * from product_appearance";
										$res_product_appearance=mysql_query($sql_product_appearance) or die ('Error '.$sql_product_appearance);
										while($rs_product_appearance=mysql_fetch_array($res_product_appearance)){
											$i++;
											if(($rs_product_appearance['id_product_appearance']==1) || ($rs_product_appearance['id_product_appearance']==2) || ($rs_product_appearance['id_product_appearance']==3)){
												if($i<3){$back="/";}else{$back="";}
												echo $rs_product_appearance['title_thai'].'&nbsp;'.$back.'&nbsp;';
												$pack_array=array($rs_product_appearance['id_type_package']);
												}//end if product apperance
										}//end product appearance
										?>										
										</td>
									</tr>
									<?php
									$pack=$pack_array[0];
									$roc_type_packaging=split(",",$pack);
									for($i_pack=0;$i_pack<=count($roc_type_packaging);$i_pack++){
									$sql_type_packaging="select * from type_packaging where id_type_package='".$roc_type_packaging[$i_pack]."'";
									$res_type_packaging=mysql_query($sql_type_packaging) or die ('Error '.$sql_type_packaging);
									while($rs_type_packaging=mysql_fetch_array($res_type_packaging)){
									?>	
									<tr>
										<td class="title-function" colspan="6"><input type="radio" name="type_packaging" value="<?php echo $rs_type_packaging['id_type_package']?>" <?php if($rs_relation_pack['id_type_package']==$rs_type_packaging['id_type_package']){echo 'checked';}?>><?php echo $rs_type_packaging['title_thai']?></td>
									</tr>
									<tr>
										<td class="title-sub-function" style="padding-bottom:1%;">ขนาดบรรจุ</td>
										<?php
										$j_box_size=0;
										$num_box_size=0;
										$rows_box_size=0;
										$sql_box_size="select * from roc_product_box_size where id_type_package='".$rs_type_packaging['id_type_package']."'";
										$res_box_size=mysql_query($sql_box_size) or die ('Error '.$sql_box_size);
										$max_row_box_size=mysql_num_rows($res_box_size);
										while($rs_box_size=mysql_fetch_array($res_box_size)){
											if($rows_box_size % 2 ==0){?><tr><?php }
											$j_box_size++;
											$num_box_size++;											
										?>
											<td class="title-sub-function"><div style="float:left; margin: 0 0.5% 0 0;"><input type="radio" class="checkbox" name="pack_size" value="<?php echo $rs_box_size['id_box_size']?>" <?php if($rs_relation_pack['id_pack_size']==$rs_box_size['id_box_size']){echo 'checked';}?>></div><div style="float:left; margin: 0 0.5% 0 0;"><?echo $rs_box_size['title_box_size']?></div></td>
											<?php if($num_box_size==$max_row_box_size){?>
											<td class="title-sub-function" colspan="4"><div style="float:left; margin: 0 0.5% 0 0;"><input type="checkbox" class="checkbox"></div><div style="float:left; margin: 0 0.5% 0 0;">อื่น ๆ</div><div style="float:left"><input type="text"></div></td>
											<?php } ?>
										</td>
										<?php if($j_box_size % 2 == 0){ ?></tr><?php } //display end row
											$rows_box_size++;
										}
										?>
									</tr>
									<?php if($rs_type_packaging['id_type_package']==5){?>
									<tr>
										<td class="title-sub-function">แผง & ฟอยล์</td>
										<?php
										$roc_foil=split(",",$rs_relation_pack['id_product_foil']);
										$i_foil=0;
										$sql_foil="select * from roc_product_foil where id_type_package='".$rs_type_packaging['id_type_package']."'";
										$res_foil=mysql_query($sql_foil) or die ('Error '.$sql_foil);
										while($rs_foil=mysql_fetch_array($res_foil)){
											$i_foil++;
											if($i_foil==1){
										?>
											<td class="title-function" style="padding:0;"><div style="float:left; margin: 0 0.5% 0 0;"><input type="checkbox" class="checkbox" name="foil[]" value="<?php echo $rs_foil['id_product_foil']?>" <?php if(in_array($rs_foil['id_product_foil'],$roc_foil)){echo 'checked';}?>></div><div style="float:left; margin: 0 0.5% 0 0;"><?php echo $rs_foil['title_foil']?></div></td>
											<?php }else{?>
											<tr>
											<td class="title-function" style="padding:0;"></td>
											<td class="title-function" style="padding:0;"><div style="float:left; margin: 0 0.5% 0 0;"><input type="checkbox" class="checkbox" name="foil[]" value="<?php echo $rs_foil['id_product_foil']?>" <?php if(in_array($rs_foil['id_product_foil'],$roc_foil)){echo 'checked';}?>></div><div style="float:left; margin: 0 0.5% 0 0;"><?php echo $rs_foil['title_foil']?></div></td>		
											</tr>
											<?php }?>
										</td>
										<?php 
										}
										?>
									</tr>
									<?php }else{?>
									<tr>
										<td class="title-sub-function">ชนิดขวด</td>
										<?php
										$i_bottle=0;
										$num_bottle=0;
										$sql_bottle="select * from roc_product_bottle where id_type_package='".$rs_type_packaging['id_type_package']."'";
										$res_bottle=mysql_query($sql_bottle) or die ('Error '.$sql_bottle);
										$max_row_bottle=mysql_num_rows($res_bottle);
										while($rs_bottle=mysql_fetch_array($res_bottle)){
											$i_bottle++;
											$num_bottle++;
										?>
											<td class="title-function" style="padding:0;"><div style="float:left; margin: 0 0.5% 0 0;"><input type="radio" class="checkbox" name="bottle" value="<?php echo $rs_bottle['id_product_bottle']?>" <?php if($rs_relation_pack['id_product_bottle']==$rs_bottle['id_product_bottle']){echo 'checked';}?>></div><div style="float:left; margin: 0 0.5% 0 0;"><?php echo $rs_bottle['title_bottle']?></div></td>		
										</td>
											<?php if($num_bottle==$max_row_bottle){?>
											<td class="title-function" style="padding:0;"></td>
											<td class="title-function" style="padding:0;"><div style="float:left; margin: 0 0.5% 0 0;"><input type="checkbox" class="checkbox"></div><div style="float:left; margin: 0 0.5% 0 0;">อื่น ๆ</div><div style="float:left"><input type="text"></div></td>
											<?php }//end max row ?>
										<?php 
										}
										?>
									</tr>
									<?}?>
									<tr>
										<td class="title-sub-function">วัสดุบรรจุประกอบ</td>
										<?php
										$roc_materials=split(",",$rs_relation_pack['id_material']);
										$i_materials=0;
										$num_material=0;
										$sql_materials="select * from roc_product_materials where id_type_product='".$rs_type_packaging['id_type_package']."'";
										$res_materials=mysql_query($sql_materials) or die ('Error '.$sql_materials);
										$max_row_material=mysql_num_rows($res_materials);
										while($rs_materials=mysql_fetch_array($res_materials)){
											$i_materials++;
											$num_material++;
											if($i_materials==1){
										?>	
											<td class="title-function" style="padding:0;"><div style="float:left; margin: 0 0.5% 0 0;"><input type="checkbox" class="checkbox" name="materials[]" value="<?php echo $rs_materials['id_materials']?>" <?php if(in_array($rs_materials['id_materials'],$roc_materials)){echo 'checked';}?>></div><div style="float:left; margin: 0 0.5% 0 0;"><?php echo $rs_materials['title_materials']?></div></td>
											<?php }else{?>
											<tr>
											<td class="title-function" style="padding:0;"></td>
											<td class="title-function" style="padding:0;"><div style="float:left; margin: 0 0.5% 0 0;"><input type="checkbox" class="checkbox" name="materials[]" value="<?php echo $rs_materials['id_materials']?>" <?php if(in_array($rs_materials['id_materials'],$roc_materials)){echo 'checked';}?>></div><div style="float:left; margin: 0 0.5% 0 0;"><?php echo $rs_materials['title_materials']?></div>
											<?php 
											if($rs_materials['title_materials']=='ขนาดกล่อง'){
												echo '<div style="float:left"><input type="text" name="type_product_c_other"></div>';
											}
											?>
											</td>
											</tr>
											<tr>
											<?php if($num_material==$max_row_material){?>
											<td class="title-function" style="padding:0;"></td>
											<td class="title-function" style="padding:0;"><div style="float:left; margin: 0 0.5% 0 0;"><input type="checkbox" class="checkbox"></div><div style="float:left; margin: 0 0.5% 0 0;">อื่น ๆ</div><div style="float:left"><input type="text"></div></td>
											<?php }//end max row ?>
											</tr>
											<?php }//end materials=bottle?>
										</td>
									</tr>
									<?php }//end materials
									}//end while type packaging
									}//end for array pack
									?>
								<?php
								}//end page3
								elseif($_GET['p']==4){
								?>
									<input type="hidden" name="pages" value="4">
									<script src="http://code.jquery.com/jquery-1.9.0.js"></script>
									<script>
										$(function(){
											$('#roc_pack4').click(function(){
												if($(this).prop('checked')){
													$('input[rel=roc_pack4]').each(function(index, element) {
														$(this).prop('checked',true);
													});	
												}else{
													$('input[rel=roc_pack4]').each(function(index, element) {
														$(this).prop('checked',false);
													});	
												}	
											});
											$('#roc_pack5').click(function(){
												if($(this).prop('checked')){
													$('input[rel=roc_pack5]').each(function(index, element) {
														$(this).prop('checked',true);
													});	
												}else{
													$('input[rel=roc_pack5]').each(function(index, element) {
														$(this).prop('checked',false);
													});	
												}	
											});
											$('#roc_pack6').click(function(){
												if($(this).prop('checked')){
													$('input[rel=roc_pack6]').each(function(index, element) {
														$(this).prop('checked',true);
													});	
												}else{
													$('input[rel=roc_pack6]').each(function(index, element) {
														$(this).prop('checked',false);
													});	
												}	
											});
										});
									</script>
									<?php
									$sql_relation_pack="select * from roc_relation_pack where id_roc='".$id."'";
									$res_relation_pack=mysql_query($sql_relation_pack) or die ('Error '.$sql_relation_packs);
									$rs_relation_pack=mysql_fetch_array($res_relation_pack);
									?>
									<input type="hidden" name="id_relation_pack" value="<?php echo $rs_relation_pack['id_relation_pack']?>">
									<input type="hidden" name="type_packaging" value="<?php echo $rs_relation_pack['id_product_appearance']?>">
									<input type="hidden" name="roc_pack" value="<?php echo $rs_relation_pack['id_type_package']?>">
									<input type="hidden" name="pack_size" value="<?php echo $rs_relation_pack['id_pack_size']?>">
									<input type="hidden" name="foil" value="<?php echo $rs_relation_pack['id_product_foil']?>">
									<input type="hidden" name="bottle" value="<?php echo $rs_relation_pack['id_product_bottle']?>">
									<input type="hidden" name="materials" value="<?php echo $rs_relation_pack['id_material']?>"> 
									<?php
										$i_app=0;
										$sql_product_appearance="select * from product_appearance";
										$res_product_appearance=mysql_query($sql_product_appearance) or die ('Error '.$sql_product_appearance);
										while($rs_product_appearance=mysql_fetch_array($res_product_appearance)){
											$i_app++;
											if(($rs_product_appearance['id_product_appearance']==4) || ($rs_product_appearance['id_product_appearance']==5) || ($rs_product_appearance['id_product_appearance']==6)){
									?>
									<tr>
										<td class="title-group" colspan="6"><input type="radio" name="roc_pack" id="roc_pack<?php echo $i_app?>" value="<?php echo $rs_product_appearance['id_product_appearance']?>" <?php if($rs_relation_pack['id_product_appearance']==$rs_product_appearance['id_product_appearance']){echo 'checked';}?>>
										<?php 
											echo $rs_product_appearance['title_thai'].'('.$rs_product_appearance['title'].')';
										?>
										</td>
									</tr>
									<tr>
										<?php if($rs_product_appearance['id_product_appearance']==4){?><td class="title-sub-function" style="padding-bottom:1%;">จำนวนบรรจุกล่อง</td><?php }?>
										<?php if($rs_product_appearance['id_product_appearance']==5){?><td class="title-sub-function" style="padding-bottom:1%;">จำนวนบรรจุ</td><?php }?>
										<?php if($rs_product_appearance['id_product_appearance']==6){?><td class="title-sub-function" style="padding-bottom:1%;">จำนวนบรรจุต่อกล่อง</td><?php }?>
										<?php
										$j_box_size=0;
										$num_box_size=0;
										$rows_box_size=0;
										$sql_box_size="select * from roc_product_box_size where id_type_product='".$rs_product_appearance['id_product_appearance']."'";
										$res_box_size=mysql_query($sql_box_size) or die ('Error '.$sql_box_size);
										$max_row_box_size=mysql_num_rows($res_box_size);
										while($rs_box_size=mysql_fetch_array($res_box_size)){
											if($rows_box_size % 2 ==0){?><tr><?php }
											$j_box_size++;
											$num_box_size++;											
										?>
											<td class="title-sub-function"><div style="float:left; margin: 0 0.5% 0 0;"><input type="radio" class="checkbox" name="pack_size" rel="roc_pack<?php echo $i_app?>" value="<?php echo $rs_box_size['id_box_size']?>" <?php if($rs_relation_pack['id_pack_size']==$rs_box_size['id_box_size']){echo 'checked';}?>></div><div style="float:left; margin: 0 0.5% 0 0;"><?echo $rs_box_size['title_box_size']?></div></td>
											<?php if($num_box_size==$max_row_box_size){?>
											<td class="title-sub-function" colspan="4"><div style="float:left; margin: 0 0.5% 0 0;"><input type="checkbox" class="checkbox"></div><div style="float:left; margin: 0 0.5% 0 0;">อื่น ๆ</div><div style="float:left"><input type="text"></div></td>
											<?php } ?>
										</td>
										<?php if($j_box_size % 2 == 0){ ?></tr><?php } //display end row
											$rows_box_size++;
										}
										?>
									</tr>
									<tr>
										<?php if($rs_product_appearance['id_product_appearance']==4){?><td class="title-sub-function" style="padding-bottom:1%;">ขนาดซอง</td><?php }?>
										<?php if($rs_product_appearance['id_product_appearance']==5){?><td class="title-sub-function" style="padding-bottom:1%;">รูปแบบซอง</td><?php }?>
										<?php
										$j_sachet=0;
										$num_sachet=0;
										$rows_sachet=0;
										$sql_sachet="select * from roc_product_sachet where id_type_package='".$rs_product_appearance['id_product_appearance']."'";
										$res_sachet=mysql_query($sql_sachet) or die ('Error '.$sql_sachet);
										$max_row_sachet=mysql_num_rows($res_sachet);
										while($rs_sachet=mysql_fetch_array($res_sachet)){
											if($rows_sachet % 2 ==0){?><tr><?php }
											$j_sachet++;
											$num_sachet++;											
										?>
											<td class="title-sub-function"><div style="float:left; margin: 0 0.5% 0 0;"><input type="radio" class="checkbox" name="sachet" rel="roc_pack<?php echo $i_app?>" value="<?php echo $rs_sachet['id_product_sachet']?>" <?php if($rs_relation_pack['id_product_sachet']==$rs_sachet['id_product_sachet']){echo 'checked';}?>></div><div style="float:left; margin: 0 0.5% 0 0;"><?echo $rs_sachet['title_sachet']?></div></td>
											<?php if($num_sachet==$max_row_sachet){?>
											<td class="title-sub-function" colspan="4"><div style="float:left; margin: 0 0.5% 0 0;"><input type="checkbox" class="checkbox"></div><div style="float:left; margin: 0 0.5% 0 0;">อื่น ๆ</div><div style="float:left"><input type="text"></div></td>
											<?php } ?>
										</td>
										<?php if($j_sachet % 2 == 0){ ?></tr><?php } //display end row
											$rows_sachet++;
										}
										?>
									</tr>
									<?php if($rs_product_appearance['id_product_appearance']==4){?>
									<tr>
										<td class="title-sub-function" style="padding-bottom:1%;">ฟอยล์</td>
										<?php
										$j_foil=0;
										$num_foil=0;
										$rows_foil=0;
										$sql_foil="select * from roc_product_foil where id_type_package='".$rs_product_appearance['id_product_appearance']."'";
										$res_foil=mysql_query($sql_foil) or die ('Error '.$sql_foil);
										while($rs_foil=mysql_fetch_array($res_foil)){
											if($rows_foil % 2 ==0){?><tr><?php }
											$j_foil++;
											$num_foil++;	
										?>
											<td class="title-sub-function"><div style="float:left; margin: 0 0.5% 0 0;"><input type="radio" class="checkbox" name="foil" rel="roc_pack<?php echo $i_app?>" value="<?php echo $rs_foil['id_product_foil']?>" <?php if($rs_relation_pack['id_product_foil']==$rs_foil['id_product_foil']){echo 'checked';}?>></div><div style="float:left; margin: 0 0.5% 0 0;"><?php echo $rs_foil['title_foil']?></div></td>
											
										</td>
										<?php if($j_foil % 2 == 0){ ?></tr><?php } //display end row
											$rows_foil++;
										}
										?>
									</tr>	
									<?php }?>
									<?php if($rs_product_appearance['id_product_appearance']==6){?>
									<tr>
										<td class="title-sub-function">รูปแบบขวด</td>
										<?php
										$i_bottle=0;
										$num_bottle=0;
										$sql_bottle="select * from roc_type_bottle where id_type_package='".$rs_product_appearance['id_product_appearance']."'";
										$res_bottle=mysql_query($sql_bottle) or die ('Error '.$sql_bottle);
										$max_row_bottle=mysql_num_rows($res_bottle);
										while($rs_bottle=mysql_fetch_array($res_bottle)){
											$i_bottle++;
											$num_bottle++;
										?>
											<td class="title-function" style="padding:0;"><div style="float:left; margin: 0 0.5% 0 0;"><input type="radio" class="checkbox" name="bottle_size" rel="roc_pack<?php echo $i_app?>" value="<?php echo $rs_bottle['id_type_bottle']?>" <?php if($rs_relation_pack['id_type_bottle']==$rs_bottle['id_type_bottle']){echo 'checked';}?>></div><div style="float:left; margin: 0 0.5% 0 0;"><?php echo $rs_bottle['title_type_bottle']?></div></td>		
										</td>
											<?php if($num_bottle==$max_row_bottle){?>
											<td class="title-function" style="padding:0;"></td>
											<td class="title-function" style="padding:0;"><div style="float:left; margin: 0 0.5% 0 0;"><input type="checkbox" class="checkbox"></div><div style="float:left; margin: 0 0.5% 0 0;">อื่น ๆ</div><div style="float:left"><input type="text"></div></td>
											<?php }//end max row ?>
										<?php 
										}
										?>
									</tr>
									<tr>
										<td class="title-sub-function">ลักษณะฝา</td>
										<?php
										$sql_lid="select * from roc_product_bottle_lid where id_type_package='".$rs_product_appearance['id_product_appearance']."'";
										$res_lid=mysql_query($sql_lid) or die ('Error '.$sql_lid);
										while($rs_lid=mysql_fetch_array($res_lid)){

										?>
											<td class="title-function" style="padding:0;"><div style="float:left; margin: 0 0.5% 0 0;"><input type="radio" class="checkbox" name="bottle_lid" rel="roc_pack<?php echo $i_app?>" value="<?php echo $rs_lid['id_bottle_lid']?>" <?php if($rs_relation_pack['id_bottle_lid']==$rs_lid['id_bottle_lid']){echo 'checked';}?>></div><div style="float:left; margin: 0 0.5% 0 0;"><?php echo $rs_lid['title_bottle_lid']?></div></td>		
										</td>
										<?php 
										}
										?>
									</tr>
									<?php }?>
									<tr>
										<td class="title-sub-function">วัสดุบรรจุประกอบ</td>
										<?php
										$roc_materials=split(",",$rs_relation_pack['id_material']);
										$i_materials=0;
										$num_material=0;
										$sql_materials="select * from roc_product_materials where id_type_package='".$rs_product_appearance['id_product_appearance']."'";
										$res_materials=mysql_query($sql_materials) or die ('Error '.$sql_materials);
										$max_row_material=mysql_num_rows($res_materials);
										while($rs_materials=mysql_fetch_array($res_materials)){
											$i_materials++;
											$num_material++;
											if($i_materials==1){
										?>	
											<td class="title-function" style="padding:0;"><div style="float:left; margin: 0 0.5% 0 0;"><input type="checkbox" class="checkbox" name="materials[]" rel="roc_pack<?php echo $i_app?>" value="<?php echo $rs_materials['id_materials']?>" <?php if(in_array($rs_materials['id_materials'],$roc_materials)){echo 'checked';}?>></div><div style="float:left; margin: 0 0.5% 0 0;"><?php echo $rs_materials['title_materials']?></div></td>
											<?php }else{?>
											<tr>
											<td class="title-function" style="padding:0;"></td>
											<td class="title-function" style="padding:0;"><div style="float:left; margin: 0 0.5% 0 0;"><input type="checkbox" class="checkbox" name="materials[]" rel="roc_pack<?php echo $i_app?>" value="<?php echo $rs_materials['id_materials']?>" <?php if(in_array($rs_materials['id_materials'],$roc_materials)){echo 'checked';}?>></div><div style="float:left; margin: 0 0.5% 0 0;"><?php echo $rs_materials['title_materials']?></div>
											<?php 
											if($rs_materials['title_materials']=='ขนาดกล่อง'){
												echo '<div style="float:left"><input type="text" name="type_product_c_other"></div>';
											}
											?>
											</td>
											</tr>
											<tr>
											<?php if($num_material==$max_row_material){?>
											<td class="title-function" style="padding:0;"></td>
											<td class="title-function" style="padding:0;"><div style="float:left; margin: 0 0.5% 0 0;"><input type="checkbox" class="checkbox"></div><div style="float:left; margin: 0 0.5% 0 0;">อื่น ๆ</div><div style="float:left"><input type="text"></div></td>
											<?php }//end max row ?>
											</tr>
										</td>
									</tr>
									<?php 
										}//end product appearance	
										}//end materials
									}//end while type packaging
									}//end for 
								}//end page4
								elseif($_GET['p']==5){
								?>
									<input type="hidden" name="pages" value="5">
									<script src="http://code.jquery.com/jquery-1.9.0.js"></script>
									<script>
										$(function(){
											$('#type_ink1').click(function(){
												if($(this).prop('checked')){
													$('input[rel=type_ink1]').each(function(index, element) {
														$(this).prop('checked',true);
													});	
												}else{
													$('input[rel=type_ink1]').each(function(index, element) {
														$(this).prop('checked',false);
													});	
												}	
											});
											$('#type_ink2').click(function(){
												if($(this).prop('checked')){
													$('input[rel=type_ink2]').each(function(index, element) {
														$(this).prop('checked',true);
													});	
												}else{
													$('input[rel=type_ink2]').each(function(index, element) {
														$(this).prop('checked',false);
													});	
												}	
											});
											$('#type_ink3').click(function(){
												if($(this).prop('checked')){
													$('input[rel=type_ink3]').each(function(index, element) {
														$(this).prop('checked',true);
													});	
												}else{
													$('input[rel=type_ink3]').each(function(index, element) {
														$(this).prop('checked',false);
													});	
												}	
											});
											$('#type_ink4').click(function(){
												if($(this).prop('checked')){
													$('input[rel=type_ink4]').each(function(index, element) {
														$(this).prop('checked',true);
													});	
												}else{
													$('input[rel=type_ink4]').each(function(index, element) {
														$(this).prop('checked',false);
													});	
												}	
											});
										});
									</script>
									<tr>
										<td class="title-group" colspan="2"><input type="checkbox" name="ink_jet" value="1" <?php if($rs['id_ink_jet']==1){echo 'checked';}?>>Ink Jet</td>
									</tr>
									<?php 
									$i_ink=0;
									$type_ink=split(",",$rs['id_type_ink_jet']);
									$detail_ink=split(",",$rs['id_detail_ink']);
									$sql_ink="select * from roc_ink_jet";
									$res_ink=mysql_query($sql_ink) or die ('Error '.$sql_ink);
									while($rs_ink=mysql_fetch_array($res_ink)){
										$i_ink++;
										if(($rs_ink['id_ink_jet']==1)||($rs_ink['id_ink_jet']==2)){$rowspan='rowspan="4"';}
										if(($rs_ink['id_ink_jet']==3)||($rs_ink['id_ink_jet']==4)){$rowspan='rowspan="3"';}
									?>
									<tr>
										<td <?php echo $rowspan?> class="title-function w20"><input type="checkbox" name="type_ink_jet[]" id="type_ink<?php echo $i_ink?>" value="<?php echo $rs_ink['id_ink_jet']?>" <?php if(in_array($rs_ink['id_ink_jet'],$type_ink)){echo 'checked';}?>><?php echo $rs_ink['title_ink_jet']?></td>
										<?php
										$j_ink_detail=0;
										$sql_ink_detail="select * from roc_ink_jet_detail where id_ink_jet='".$rs_ink['id_ink_jet']."'";
										$res_ink_detail=mysql_query($sql_ink_detail) or die ('Error '.$sql_ink_detail);
										while($rs_ink_detail=mysql_fetch_array($res_ink_detail)){
											$j_ink_detail++;
											if($j_ink_detail==1){
										?>
											<td class="title-function"><input type="checkbox" name="ink_jet_detail[]" rel="type_ink<?php echo $i_ink?>" value="<?php echo $rs_ink_detail['id_detail_ink']?>" <?php if(in_array($rs_ink_detail['id_detail_ink'],$detail_ink)){echo 'checked';}?>><?php echo $rs_ink_detail['title_detail_ink']?></td>
										<?php }else{?>
										<tr>
											<td class="title-function"><input type="checkbox" rel="type_ink<?php echo $i_ink?>" value="<?php echo $rs_ink_detail['id_detail_ink']?>" <?php if(in_array($rs_ink_detail['id_detail_ink'],$detail_ink)){echo 'checked';}?>><?php echo $rs_ink_detail['title_detail_ink']?></td>
										</tr>
										<?php }										
										}//end while ink jet detail?>
									</tr>
									<?php 
									}//end while ink jet
									?>
									<tr>
										<td class="title-group" colspan="2"><p>6.ราคาโดยประมาณของผลิตภัณฑ์สำเร็จรูปที่ต้องการ</p>
										<textarea style="width:50%; height:100px;" name="product_price"><?php echo $rs['product_price']?></textarea>
										</td>
									</tr>
									<tr>
										<td class="title-group" colspan="2"><p>7.ผลิตภัณฑ์ในท้องตลาดที่เป็นตัวเปรียบเทียบ</p>
										<textarea style="width:50%; height:100px;" name="product_compare"><?php echo $rs['product_compare']?></textarea>
										</td>
									</tr>
									<tr>
										<td class="title-group" colspan="2"><p>8.Product selling point</p>
										<textarea style="width:50%; height:100px;" name="product_selling"><?php echo $rs['product_selling']?></textarea>
										</td>
									</tr>
									<tr>
										<td class="title-group" colspan="2"><p>9.Market position</p>
										<textarea style="width:50%; height:100px;" name="market_position"><?php echo $rs['market_position']?></textarea>
										</td>
									</tr>
									<tr>
										<td class="title-group" colspan="2"><p>10.Selling channel</p>
										<textarea style="width:50%; height:100px;" name="selling_channel"><?php echo $rs['selling_channel']?></textarea>
										</td>
									</tr>
								<?php }//end page5?>
								<tr>
									<td class="title-group footer-right" colspan="8"><a href="ac-roc.php?id_u=<?php echo $_GET["id_u"]?>&p=1">1</a> | <a href="ac-roc.php?id_u=<?php echo $_GET["id_u"]?>&p=2">2</a> | <a href="ac-roc.php?id_u=<?php echo $_GET["id_u"]?>&p=3">3</a> | <a href="ac-roc.php?id_u=<?php echo $_GET["id_u"]?>&p=4">4</a> | <a href="ac-roc.php?id_u=<?php echo $_GET["id_u"]?>&p=5">5</a></td>
								</tr>
								<tr>
									<td class="title-group footer-right" colspan="8">SM-F001 Rev.1 Bffective Date: 15/06/12</td>
								</tr>
							</table>
						</div>
					</td>
				</tr>
				<tr>
					<td class="b-top">
						<div class="large-4 columns">
							<div class="large-4 columns">							
							<?php if(!is_numeric($id)){?>
							<input type="button" name="save_data" id="save_data" value="<?php echo $button?>" class="button-create" OnClick="frm.hdnCmd.value='save_data';JavaScript:return fncSubmit();">
							<?php }else{?>
							<input type="button" name="update_data" id="update_data" value="<?php echo $button?>" class="button-create" OnClick="frm.hdnCmd.value='update_data';JavaScript:return fncSubmit();">
							<input type="button" name="finished_data" id="finished_data" value="Finished" class="button-create" OnClick="frm.hdnCmd.value='finished_data';JavaScript:return fncSubmit();">
							<?php }?>
							<input type="button" value="Close" class="button-create" onclick="window.location.href='roc.php'">
						</div>
						</div>
					</td>
				</tr>
			</table>
			</form>
		</div>
	</div>

 <!-- <script>
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
  
<!--  <script>
    $(document).foundation();
  </script>-->
</body>
</html>
