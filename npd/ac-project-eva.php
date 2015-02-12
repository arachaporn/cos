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
<script type="text/javascript" src="../ckeditor-integrated/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="../ckeditor-integrated/ckfinder/ckfinder.js"></script>
</head>  
<body> 
	<?php include("menu.php")?>
	<div class="row">
		<div class="background">
			<?php
			if($_GET["mode"]=='New'){
				$mode='New';
				$button='Save';
				$id_new =$_GET["id_u"];
				$id='New';
				
				$sql="select * from roc where id_roc='".$id_new."'";
				$res=mysql_query($sql) or die ('Error '.$sql);
				$rs=mysql_fetch_array($res);
								
				$sql_product="select * from product where id_product='".$rs['id_product']."'";
				$res_product=mysql_query($sql_product) or die ('Error '.$sql_product);
				$rs_product=mysql_fetch_array($res_product);
				
				$sql_type_product="select * from npd_type_product where id_npd_type_product='".$rs['id_type_product']."'";
				$res_type_product=mysql_query($sql_type_product) or die ('Error '.$sql_type_product);
				$rs_type_product=mysql_fetch_array($res_type_product);
				
				$sql_npd_code="select * from npd_project_evaluation where id_roc='".$id_new."' order by id_project_eva desc";
				$res_npd_code=mysql_query($sql_npd_code) or die ('Error '.$sql_npd_code);
				$rs_npd_code=mysql_fetch_array($res_npd_code);

				$sql_company="select * from company where id_company='".$rs['id_company']."'";
				$res_company=mysql_query($sql_company) or die ('Error '.$sql_company);
				$rs_company=mysql_fetch_array($res_company);

				if($_GET["action"] == "del_rm"){
					$sql = "delete from npd_project_relation ";
					$sql .="where id_npd_project_rela = '".$_GET["id_p"]."'";
					$res = mysql_query($sql) or die ('Error '.$sql);
								
					$sql_rela="select * from npd_project_relation";
					$sql_rela .=" where id_roc='".$rs_npd_code['id_roc']."'";
					$res_rela=mysql_query($sql_rela) or die ('Error '.$sql_rela);
					while($rs_rela=mysql_fetch_array($res_rela)){
						$total=$total+$rs_rela['npd_rm_quantity'];
					}

					$sql_project="update npd_project_evaluation set npd_total='".$total."'";
					$sql_project .=" where id_project_eva='".$rs_npd_code['id_project_eva']."'";
					$res_project=mysql_query($sql_project) or die ('Error '.$sql_project);
				?>
					<script>
						window.location.href='ac-project-eva.php?id_u=<?=$id_new.$mode?>';
					</script>
			<?php }
			}else{
				$id=$_GET["id_u"];
				$sql="select * from roc where id_roc='".$id."'";
				$res=mysql_query($sql) or die ('Error '.$sql);
				$rs=mysql_fetch_array($res);
								
				$sql_product="select * from product where id_product='".$rs['id_product']."'";
				$res_product=mysql_query($sql_product) or die ('Error '.$sql_product);
				$rs_product=mysql_fetch_array($res_product);
				
				$sql_type_product="select * from npd_type_product where id_npd_type_product='".$rs['id_type_product']."'";
				$res_type_product=mysql_query($sql_type_product) or die ('Error '.$sql_type_product);
				$rs_type_product=mysql_fetch_array($res_type_product);
				
				$sql_npd_code="select * from npd_project_evaluation where id_roc='".$id."' order by id_project_eva desc";
				$res_npd_code=mysql_query($sql_npd_code) or die ('Error '.$sql_npd_code);
				$rs_npd_code=mysql_fetch_array($res_npd_code);

				$sql_company="select * from company where id_company='".$rs['id_company']."'";
				$res_company=mysql_query($sql_company) or die ('Error '.$sql_company);
				$rs_company=mysql_fetch_array($res_company);

				$sql_contact="select * from company_contact where id_contact='".$rs['id_contact']."'";
				$res_contact=mysql_query($sql_contact) or die ('Error '.$sql_contact);
				$rs_contact=mysql_fetch_array($res_contact);
				
				$mode=$rs_product['product_name'];
				$button='Update';

				if($_GET["action"] == "del_rm"){
					$sql = "delete from npd_project_relation ";
					$sql .="where id_npd_project_rela = '".$_GET["id_p"]."'";
					$res = mysql_query($sql) or die ('Error '.$sql);
								
					$sql_rela="select * from npd_project_relation";
					$sql_rela .=" where id_roc='".$rs_npd_code['id_roc']."'";
					$res_rela=mysql_query($sql_rela) or die ('Error '.$sql_rela);
					while($rs_rela=mysql_fetch_array($res_rela)){
						$total=$total+$rs_rela['npd_rm_quantity'];
					}

					$sql_project="update npd_project_evaluation set npd_total='".$total."'";
					$sql_project .=" where id_project_eva='".$rs_npd_code['id_project_eva']."'";
					$res_project=mysql_query($sql_project) or die ('Error '.$sql_project);
				?>
					<script>
						window.location.href='ac-project-eva.php?id_u=<?=$id?>';
					</script>
			<?php }
			}				
			?> 
			<script type="text/javascript" src="js/js-autocomplete/js/jquery-1.4.2.min.js"></script> 
			<script type="text/javascript" src="js/js-autocomplete/js/jquery-ui-1.8.2.custom.min.js"></script> 
			<script type="text/javascript"> 
				jQuery(document).ready(function(){
					$('.rd_account').autocomplete({
						source:'returnUser.php', 
						//minLength:2,
						select:function(evt, ui){
							this.form.id_rd_account.value = ui.item.id_rd_account;
							this.form.rd_account.value = ui.item.rd_account;
						}
					});
					$('.npd_rm').autocomplete({
						source:'return-npd-rm.php', 
						//minLength:2,
						select:function(evt, ui){
							this.form.id_npd_rm.value = ui.item.id_npd_rm;
							this.form.npd_supplier.value = ui.item.npd_supplier;
							this.form.npd_rm_price.value = ui.item.npd_rm_price;
						}
					});
					$('.npd_rm2').autocomplete({
						source:'return-npd-rm.php', 
						//minLength:2,
						select:function(evt, ui){
							this.form.id_npd_rm2.value = ui.item.id_npd_rm2;
							this.form.npd_supplier2.value = ui.item.npd_supplier2;
							this.form.npd_rm_price2.value = ui.item.npd_rm_price2;
						}
					});
				});
			</script> 
			<link rel="stylesheet" href="js/js-autocomplete/css/smoothness/jquery-ui-1.8.2.custom.css" /> 
			<form name="frm" method="post" action="dbproject-eva.php">
			<input type="hidden" name="hdnCmd" value="">			
			<input type="hidden" name="account" value="<?php echo $rs_account['id_account']?>">
			<table style="border: 0; width: 100%;" cellpadding="0" cellspacing="0">
				<tr>
					<td class="b-bottom"><div class="large-4 columns"><h4><h4>Project Evaluation >> <?php echo $mode;?></h4></h4></div></td>
				</tr>
				<tr>
					<td class="b-bottom">
						<div class="large-4 columns">		
							<input type="button" value="Export Project Evaluation" class="button-create" onclick="window.open('pdf-project-eva.php?id_u=<?=$rs_npd_code['id_project_eva']?>');">
							<input type="button" value="Export NPD Costing" class="button-create" onclick="window.open('pdf-costing.php?id_u=<?=$rs_npd_code['id_project_eva']?>');">
							<?php if(!is_numeric($id)){?>   
							<input type="button" name="save_data" id="save_data" value="<?php echo $button?>" class="button-create" OnClick="frm.hdnCmd.value='save_data';JavaScript:return fncSubmit();">
							<?php }else{?>
							<input type="button" name="update_data" id="update_data" value="<?php echo $button?>" class="button-create" OnClick="frm.hdnCmd.value='update_data';JavaScript:return fncSubmit();">
							<?php }?>
							<input type="button" name="send_data" id="send_data" value="Send E-mail" class="button-create" OnClick="frm.hdnCmd.value='send_email';JavaScript:return fncSubmit();">
							<input type="button" value="Close" class="button-create" onclick="window.location.href='project-eva.php'">
						</div>
					</td>
				</tr>
				<tr>
					<td style="background: #fff;">
						<div class="large-4 columns">
							<script>
								$(document).ready(function(){
									$('.WorldphpTab>ul a').click( function(){
									  t = $(this).attr('href');
									  $('.WorldphpTab>ul li').removeClass('active');
									  $('.WorldphpTabData>div').removeClass('active');
									  $(this).parent().addClass('active'); 
									  $(t).addClass('active');
									});
								});
							</script>
							<!-- Tab -->
							<div class="WorldphpTab">
								<ul>
									<li class="active"><a href="#tab1">Project Evaluation</a></li>
									<li><a href="#tab2">Rawmaterail Cost</a></li>
								</ul>
								<div class="WorldphpTabData">
									<div id="tab1" class="active">
										<input type="hidden" name="mode" value="<?php echo $id?>">
										<table style="border: 0; width: 100%;" cellpadding="0" cellspacing="0" id="tb-req">
											<tr>
												<td class="b-bottom center" colspan="6">
													<div class="tb-h">
														<img src="img/logo.png" width="140" class="img-logo">
														<div class="header-text">บริษัท ซีดีไอพี (ประเทศไทย) จำกัด<br>
														CDIP (Thailand) Co.,Ltd.<br>
														แบบฟอร์มการตั้งสูตรผลิตภัณฑ์ (Project Evaluation)
														</div>
													</div>
												</td>
											</tr>
											<tr>
												<td class="top">สถานะเอกสาร</td>
												<td class="top">
													<input type="radio" name="project_status" value="0" <?php if($rs_npd_code['project_status']==0){echo 'checked';}?>>รอข้อมูลเพิ่มเติม
													<input type="radio" name="project_status" value="1" <?php if($rs_npd_code['project_status']==1){echo 'checked';}?>>เสร็จแล้ว
												</td>
												<td class="top">เลขที่เอกสาร</td>
												<td class="top">
													<?php $month=date("m");$year=date("y");?>
													<input type="text" name="npd_code" readonly class="textbox" value="<?php if($rs_npd_code['npd_code'] != ''){echo $rs_npd_code['npd_code'];}else{echo 'NPD-RD-';
														echo $rs_type_product['npd_group'].'-';
														echo date("y").date("m").'-';											
														if($year==$rs_npd_code['npd_year']){
															$num = $rs_npd_code['npd_num']+1;
														}else{$num=1;}												
														echo sprintf("%03d",$num);
														echo $numf;
													}?>">
													<input type="hidden" name="npd_year" value="<?php if($year==$rs_npd_code['npd_year']){echo $rs_npd_code['npd_year'];}else{ echo date("y");}?>">
													<input type="hidden" name="npd_month" value="<?php if($month==$rs_npd_code['npd_month']){echo $rs_npd_code['npd_month'];}else{ echo date("m");}?>">
													<input type="hidden" name="npd_num" value="<?php if(is_numeric($id)){echo $rs_npd_code['npd_num'];}else{if($month==$rs_roc_code['npd_month']){echo $num;}else{echo $num=1;}}?>">
													<input type="hidden" name="id_roc" value="<?php echo $rs['id_roc']?>">
												</td>
											</tr>
											<tr>
												<td></td>
												<td></td>
												<td class="top">เลขที่เอกสารอ้างอิง</td>
												<td class="top">
													<input type="hidden" name="id_roc" id="id_roc" value="<?php echo $rs['id_roc']?>" class="textbox">
													<input type="text" name="roc_code" id="roc_code" readonly value="<?php echo $rs['roc_code']?>" class="textbox">
												</td>
											</tr>
											<tr>
												<td class="top">Project name</td>
												<td class="top">
													<input name="id_product" type="hidden" id="id_product" class="textbox" value="<?php echo $rs['id_product']?>"/>
													<input name="product_name" type="text" id="product_name" class="textbox" readonly value="<?php echo $rs_product['product_name']?>" />
												</td>
											</tr>
											<tr>
												<td class="top">ชื่อลูกค้า/บริษัท</td>
												<td class="top">
													<input name="id_company" type="hidden" id="id_company" value="<?php echo $rs_company['id_company']?>" />
													<input name="company" type="text" id="company" class="textbox" readonly value="<?php if($rs_company['company_name'] != '' ){echo $rs_company['company_name'];}elseif($rs_contact['contact_name'] !=''){echo $rs_contact['contact_name'];}?>"/>
												</td>
											</tr>  
											<tr>
												<td colspan="4">
													<table style="border: 0; width: 100%;" cellpadding="0" cellspacing="0" id="tb-req">
														<tr>
															<td rowspan="2" class="w5 tb-h border-l border-r border-t border-b">ลำดับ</td>
															<td colspan="3" class="tb-h border-r border-t">ส่วนประกอบ</td>
															<td colspan="3" class="tb-h border-r border-t">เทียบเท่ากับ (Equivalent Active)</td>
															<td rowspan="2" class="w10 tb-h border-r border-t border-b">ผู้ขาย</td>
															<td rowspan="2" class="w10 tb-h border-r border-t border-b">ราคาวัตถุดิบต่อกิโลกรัม</td>
														</tr>
														<tr>
															<td class="w15 tb-h border-r border-t border-b">สารสำคัญ</td>
															<td class="w10 tb-h border-r border-t border-b">ปริมาณ</td>
															<td class="w5 tb-h border-r border-t border-b">หน่วย</td>
															<td class="w15 tb-h border-r border-t border-b">สารสำคัญ</td>
															<td class="w10 tb-h border-r border-t border-b">ปริมาณ</td>
															<td class="w5 tb-h border-r border-t border-b">หน่วย</td>
														</tr>
														<?php
														$i=0;
														$sql_npd_rm="select * from npd_project_relation where id_roc='".$rs['id_roc']."'";
														$res_npd_rm=mysql_query($sql_npd_rm) or die('Error '.$sql_npd_rm);
														while($rs_npd_rm=mysql_fetch_array($res_npd_rm)){	
															$i++;
															if($rs_npd_rm['id_npd_project_rela'] == $_GET['id_p'] and $_GET["action"] == 'edit_rm'){
														?>	
															<input type="hidden" name="hdnEdit" value="<?php echo $rs_npd_rm['id_npd_project_rela']?>">
															<tr>
																<td class="tb-h border-l border-r border-b"><?php echo $i?></td>
																<td class="border-r border-b">
																	<input type="hidden" name="id_npd_rm2" id="id_npd_rm2">
																	<input type="text" name="npd_rm2" id="npd_rm2" class="npd_rm2" value="<?php echo $rs_npd_rm['npd_rm_name']?>">
																</td>
																<td class="border-r border-b"><input type="text" name="npd_rm_quantity2" value="<?php echo $rs_npd_rm['npd_rm_quantity']?>"></td>
																<td class="tb-h border-r border-b">mg</td>
																<td class="border-r border-b"><input type="text" name="npd_rm_equl2" value="<?php echo $rs_npd_rm['npd_rm_equl']?>"></td>
																<td class="border-r border-b"><input type="text" name="npd_rm_quantity_equl2" value="<?php echo $rs_npd_rm['npd_rm_quantity_equl']?>"></td>
																<td class="tb-h border-r border-b">mg</td>
																<td class="tb-h border-r border-b"><input type="text" name="npd_supplier2" id="npd_supplier2" value="<?php echo $rs_npd_rm['npd_supplier']?>"></td>
																<td class="tb-h border-r border-b">
																	<div style="float:left; margin-right:2%;"><input type="text" name="npd_rm_price2" id="npd_rm_price2" value="<?php echo $rs_npd_rm['npd_rm_price']?>"></div>
																	<input name="btnAdd" type="button" id="btnUpdate" value="Update" OnClick="frm.hdnCmd.value='update_rm';JavaScript:return fncSubmit();" class="btn-update">
																	<input name="btnAdd" type="button" id="btnCancel" value="Cancel" OnClick="window.location='<?=$_SERVER["PHP_SELF"]."?id_u=".$id?>';" class="btn-cancel">
																</td>
															</tr>
														<?php }else{?>
															<tr>
																<td class="tb-h border-l border-r border-b"><?php echo $i?></td>
																<td class="border-r border-b"><?php echo $rs_npd_rm['npd_rm_name']?></td>
																<td class="border-r border-b"><?php echo $rs_npd_rm['npd_rm_quantity']?></td>
																<td class="tb-h border-r border-b">mg</td>
																<td class="border-r border-b"><?php echo $rs_npd_rm['npd_rm_equl']?></td>
																<td class="border-r border-b"><?php echo $rs_npd_rm['npd_rm_quantity_equl']?></td>
																<td class="tb-h border-r border-b">mg</td>
																<td class="tb-h border-r border-b"><?php echo $rs_npd_rm['npd_supplier']?></td>
																<td class="border-r border-b">
																	<?php if(is_numeric($mode)){$mode='';}else{$mode='&mode=New';}?>
																	<div style="float:left; margin-right:2%;"><?php echo $rs_npd_rm['npd_rm_price']?></div>
																	<a href="<?=$_SERVER["PHP_SELF"];?>?id_u=<?php echo $rs['id_roc'].$mode?>&action=edit_rm&id_p=<?=$rs_npd_rm['id_npd_project_rela'];?>"><img src="img/edit.png" style="width:20px;"></a>
																	<a href="JavaScript:if(confirm('Confirm Delete?')==true){window.location='<?=$_SERVER["PHP_SELF"];?>?id_u=<?php echo $rs['id_roc']?>&action=del_rm&id_p=<? echo $rs_npd_rm['id_npd_project_rela'];?>';}"><img src="img/delete.png" style="width:20px;"></a>
																</td>
															</tr>
														<?php }
														}?>
														<tr>
															<td class="tb-h border-l border-r border-b"></td>
															<td class="border-r border-b">
																<input type="hidden" name="id_npd_rm" id="id_npd_rm">
																<input type="text" name="npd_rm" id="npd_rm" class="npd_rm">
															</td>
															<td class="border-r border-b"><input type="text" name="npd_rm_quantity"></td>
															<td class="tb-h border-r border-b">mg</td>
															<td class="border-r border-b"><input type="text" name="npd_rm_equl"></td>
															<td class="border-r border-b"><input type="text" name="npd_rm_quantity_equl"></td>
															<td class="tb-h border-r border-b">mg</td>
															<td class="tb-h border-r border-b"><input type="text" name="npd_supplier" id="npd_supplier"></td>
															<td class="border-r border-b">
																<div style="float:left;"><input type="text" name="npd_rm_price" id="npd_rm_price"></div>
																<input name="btnAdd" type="button" id="btnAdd" value="Add"  OnClick="frm.hdnCmd.value='add_rm';JavaScript:return fncSubmit();" class="btn-new-itenary">
															</td>
														</tr>
														<tr>
															<td colspan="4" class="border-l border-r border-b" style="text-align:right;">น้ำหนักสุทธิต่อหน่วย</td>
															<td colspan="5" class="tb-h border-r border-b"><div style="float:left;margin:2% 0 0;"><input type="text" name="npd_total" value="<?php echo $rs_npd_code['npd_total']?>"></div><div style="float:left;margin:2.5% 2% 0;">  /unit </div><div style="float:left;margin:2% 0 0;"><input type="text" name="npd_unit" value="<?php echo $rs_npd_code['npd_unit']?>"></div></td>
														</tr>
													</table>
												</td>
											</tr>
											<tr>
												<td>รูปแบบของผลิตภัณฑ์</td>
												<td>
													<?php
													if($rs_npd_code['product_app_rd'] ==''){
														$sql_app="select * from product_appearance where id_product_appearance='".$rs['id_product_appearance']."'";
														$res_app=mysql_query($sql_app) or die ('Error '.$sql_app);
														$rs_app=mysql_fetch_array($res_app);
														$product=$rs_app['title'].' ('.$rs_app['title_thai'].')';
													}else{$product=$rs_npd_code['product_app_rd'];}?>
													<input type="text" name="product_app" value="<?php echo $product?>" class="textbox">
												</td>
											</tr>
											<tr>
												<td class="w5">วิธีรับประทาน</td>
												<td class="w15"><input type="text" name="how_use" class="textbox" value="<?php echo $rs_npd_code['how_use']?>"></td>
												<td class="w5">อายุการเก็บรักษา</td>
												<td class="w15"><input type="text" name="storage" class="textbox" value="<?php echo $rs_npd_code['storage']?>"></td>
											</tr>								
											<tr>
												<td>Manufacturer</td>
												<td colspan="3">
													<?php 
													$sql_factory="select * from type_manufactory";
													$res_factory=mysql_query($sql_factory) or die ('Error '.$sql_factory);
													while($rs_factory=mysql_fetch_array($res_factory)){
													?>
														<input type="radio" name="factory" <?php if($rs_npd_code['id_manufacturer']==$rs_factory['id_manufacturer']){echo 'checked';}?> value="<?php echo $rs_factory['id_manufacturer']?>"><?php echo $rs_factory['title'].'&nbsp;&nbsp;'?>
													<?php }?>
												</td>
											</tr>
											<tr>
												<td>Pack</td>
												<td colspan="3">
													<?php 
													$sql_factory="select * from type_manufactory";
													$res_factory=mysql_query($sql_factory) or die ('Error '.$sql_factory);
													while($rs_factory=mysql_fetch_array($res_factory)){
													?>
														<input type="radio" name="pack" <?php if($rs_npd_code['id_pack']==$rs_factory['id_manufacturer']){echo 'checked';}?> value="<?php echo $rs_factory['id_manufacturer']?>"><?php echo $rs_factory['title'].'&nbsp;&nbsp;'?>
													<?php }?>
												</td>
											</tr>
											<tr>
												<td>เจ้าหน้าที่ RD</td>
												<td>
													<input type="hidden" name="id_rd_account" id="id_rd_account" value="<?php echo $rs_npd_code['rd_account']?>">
													<?php
													$sql_acc="select * from account where id_account='".$rs_npd_code['rd_account']."'";
													$res_acc=mysql_query($sql_acc) or die ('Error '.$sql_acc);
													$rs_acc=mysql_fetch_array($res_acc)
													?>
													<input type="text" name="rd_account" id="rd_account" class="rd_account" value="<?php echo $rs_acc['name']?>">
												</td>
											</tr>
											<tr>
												<td>ประเภทการขึ้นทะเบียน</td>
												<td colspan="3">
													<?php
													$sql_npd_type_product="select * from npd_type_product";
													$res_npd_type_product=mysql_query($sql_npd_type_product) or die ('Error '.$sql_npd_type_product);
													while($rs_npd_type_product=mysql_fetch_array($res_npd_type_product)){
													?>
														<div style="float:left;"><input type="radio" name="npd_type_fda" <?php if($rs_npd_code['type_fda']==$rs_npd_type_product['id_npd_type_product']){echo 'checked';}?> value="<?php echo $rs_npd_type_product['id_npd_type_product']?>"></div><div style="float:left;"><?php echo $rs_npd_type_product['npd_title'].'&nbsp;&nbsp;&nbsp;'?></div>
													<?php }?>
														<div style="float:left;"><input type="radio" name="npd_type_fda" <?php if($rs_npd_code['type_fda']==0){echo 'checked';}?> value="0"></div><div style="float:left;"><?php echo 'อื่น ๆ&nbsp;&nbsp;&nbsp;'?></div><div style="float:left;"><input type="text" name="other_fda" value="<?php echo $rs_npd_code['other_fda']?>"></div>
												</td>
											</tr>
											<tr>
												<td>ข้อเสนอแนะเพิ่มเติม</td>
												<td colspan="3" style="padding-bottom:1.5%;">
													<textarea id="description" name="description" width="100%"><?php echo $rs_npd_code['description']?></textarea>
													<script type="text/javascript">
														// This is a check for the CKEditor class. If not defined, the paths must be checked.
														if ( typeof CKEDITOR == 'undefined' ){
															document.write(
																'<strong><span style="color: #ff0000">Error</span>: CKEditor not found</strong>.' +
																'This sample assumes that CKEditor (not included with CKFinder) is installed in' +
																'the "/ckeditor/" path. If you have it installed in a different place, just edit' +
																'this file, changing the wrong paths in the &lt;head&gt; (line 5) and the "BasePath"' +
																'value (line 32).' ) ;
														}else{
															var editor = CKEDITOR.replace( 'description',{
																filebrowserBrowseUrl : 'ckeditor-integrated/ckfinder/ckfinder.html',
																filebrowserImageBrowseUrl : 'ckeditor-integrated/ckfinder/ckfinder.html?type=Images',
																filebrowserFlashBrowseUrl : 'ckeditor-integrated/ckfinder/ckfinder.html?type=Flash',
																filebrowserUploadUrl : 'ckeditor-integrated/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
																filebrowserImageUploadUrl : 'ckeditor-integrated/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
																filebrowserFlashUploadUrl : 'ckeditor-integrated/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash',
																toolbar :
																	[ ['Bold', 'Italic', 'Underline', '-', 'Subscript', 'Superscript', '-',  
																	  'NumberedList', 'BulletedList', '-', 'Link', 'Unlink'],
																	  ['Cut','Copy','Paste','Undo','Redo' ,'Find','Replace'],
																	  ['Outdent', 'Indent', '-', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'],  
																	  '/',											  
																	  ['Styles', 'Format', 'Font', '-', 'FontSize', 'Image', 'TextColor', 'BGColor','Table'] ]
																} 
															);
															// Just call CKFinder.setupCKEditor and pass the CKEditor instance as the first argument.
															// The second parameter (optional), is the path for the CKFinder installation (default = "/ckfinder/").
															CKFinder.setupCKEditor( editor, '../' ) ;
															// It is also possible to pass an object with selected CKFinder properties as a second argument.
															// CKFinder.setupCKEditor( editor, { basePath : '../', skin : 'v1' } ) ;
														}
													</script>
												</td>
											</tr>
										</table>
									</div><!--end div project evaluation-->
									<div id="tab2">
										<input type="hidden" name="mode" value="<?php echo $id?>">
										<table style="border: 0; width: 100%;" cellpadding="0" cellspacing="0" id="tb-req">
											<tr>
												<td class="b-bottom center" colspan="6">
													<div class="tb-h">
														<img src="img/logo.png" width="140" class="img-logo">
														<div class="header-text">บริษัท ซีดีไอพี (ประเทศไทย) จำกัด<br>
														CDIP (Thailand) Co.,Ltd.<br>
														NPD Costing
														</div>
													</div>
												</td>
											</tr>
											<tr>
												<td class="top txt-b w8">Product name :</td>
												<td class="top w23"><?php echo $rs_product['product_name']?></td>
												<td></td>
												<td></td>
												<td class="top txt-b w5">REQ no:</td>
												<td class="top"><?php echo $rs_npd_code['npd_code']?></td>
											</tr>
											<tr>
												<td class="top txt-b w8" style="padding-bottom:1%;">Customer :</td>
												<td class="top w23"><?php echo $rs_company['company_name']?></td>
												<td class="top txt-b w5">Unit:</td>
												<td class="top w15">
													<?php 
														if($rs['id_product_appearance']==1){$unit='tablet';}
														elseif(($rs['id_product_appearance']==2)||($rs['id_product_appearance']==3)){$unit='cap';}
														elseif(($rs['id_product_appearance']==4)||($rs['id_product_appearance']==5)){$unit='sac';}
														echo $rs_npd_code['npd_total'].'mg/'.$unit;
													?>
												</td>
												<td class="top txt-b w5">Date:</td>
												<td class="top"><?php echo $rs['date_roc']?></td>
											</tr>
											<tr>
												<td colspan="6">
													<table style="border: 0; width: 100%; font-size: 1.1em;" cellpadding="0" cellspacing="0" id="tb-req">
														<tr>
															<td class="top txt-center txt-b border-r border-l border-t border-b w5">No.</td>
															<td class="top txt-center txt-b border-r border-t border-b w30">Ingredients</td>
															<td class="top txt-center txt-b border-r border-t border-b w8">R/M Yield<br><br>%</td>
															<td class="top txt-center txt-b border-r border-t border-b w8">Price/Unit<br><br>R/M Cost ฿/Kg.</td>
															<td class="top txt-center txt-b border-r border-t border-b w8">Formula Used<br>(mg)</td>
															<td class="top txt-center txt-b border-r border-t border-b w8">Formula<br><br>%</td>
															<td class="top txt-center txt-b border-r border-t border-b w8">Quantity<br><br>g./unit</td>
															<td class="top txt-center txt-b border-r border-t border-b w8">Cost of Product<br><br>฿/unit</td>
														</tr>
														<?php
														$i=0;
														$sum_formula=0;
														$sum_formula_use=0;
														$total_formula_use=0;
														$total_formula=0;
														$sum_product=0;
														$total_product=0;
														$sql_npd_rm="select * from npd_project_relation where id_roc='".$rs['id_roc']."'";
														$res_npd_rm=mysql_query($sql_npd_rm) or die('Error '.$sql_npd_rm);
														while($rs_npd_rm=mysql_fetch_array($res_npd_rm)){	
															$i++;
															$sum_formula_use=($rs_npd_rm['npd_rm_quantity']/$rs_npd_code['npd_total'])*100;
															$total_formula_use=$total_formula_use+$sum_formula_use;
															
															$sum_formula=$rs_npd_rm['npd_rm_quantity']/1000;
															$total_formula=$total_formula+$sum_formula;

															$sum_product=($sum_formula*$rs_npd_rm['npd_rm_price'])/1000;
															$total_product=$total_product+$sum_product;

														?>
														<tr>
															<td class="top txt-center border-r border-l border-b"><?php echo $i?></td>
															<td class="top border-r border-b"><?php echo $rs_npd_rm['npd_rm_name']?></td>
															<td class="top txt-center border-r border-b"><input type="text" name="rm_yield" value="99"></td>
															<td class="top txt-right border-r border-b"><?php echo number_format($rs_npd_rm['npd_rm_price'],2)?></td>
															<td class="top txt-right border-r border-b"><?php echo number_format($rs_npd_rm['npd_rm_quantity'],3)?></td>
															<td class="top txt-right border-r border-b"><?php echo number_format($sum_formula_use,3)?></td>
															<td class="top txt-right border-r border-b"><?php echo number_format($sum_formula,3)?></td>
															<td class="top txt-right border-r border-b"><?php echo number_format($sum_product,4)?></td>
														</tr>
														<?php }?>
														<tr>
															<td class="top border-r border-l border-b"></td>
															<td class="top border-r border-b">Recommended dose = <?php echo $rs_npd_code['how_use']?></td>
															<td class="top border-r border-b"></td>
															<td class="top border-r border-b"></td>
															<td class="top border-r border-b"></td>
															<td class="top border-r border-b"></td>
															<td class="top border-r border-b"></td>
															<td class="top border-r border-b"></td>
														</tr>
														<tr>
															<td class="top border-r border-l border-b"></td>
															<td class="top border-r border-b"><div style="float:left;">Selling point = </div><div style="float:left;"><input type="text" name="selling_point" value="<?php echo $rs_npd_code['selling_point']?>"></div></td>
															<td class="top border-r border-b"></td>															
															<td class="top border-r border-b"></td>
															<td class="top border-r border-b"></td>
															<td class="top border-r border-b"></td>
															<td class="top border-r border-b"></td>
															<td class="top border-r border-b"></td>
														</tr>
														<tr>
															<td class="top txt-center txt-b border-r border-l border-b" colspan="2">Total</td>
															<td class="top border-r border-b"></td>
															<td class="top border-r border-b"></td>
															<td class="top txt-center txt-b border-r border-b"><?php echo number_format($rs_npd_code['npd_total'],3)?></td>
															<td class="top txt-right txt-b border-r border-b"><?php echo number_format($total_formula_use,3)?></td>
															<td class="top txt-right txt-b border-r border-b"><?php echo number_format($total_formula,3)?></td>
															<td class="top txt-right txt-b border-r border-b"><?php echo number_format($total_product,4)?></td>
														</tr>
														<tr>
															<td class="top txt-b border-r border-l border-b" colspan="2">Weight after cooked</td>
															<td class="top border-r border-b"></td>
															<td class="top border-r border-b"></td>
															<td class="top border-r border-b"></td>
															<td class="top border-r border-b"></td>
															<td class="top border-r border-b"></td>
															<td class="top border-r border-b"></td>
														</tr>
														<tr>
															<td class="top txt-b border-r border-l border-b" colspan="2">%Yield Finished Product</td>
															<td class="top border-r border-b"></td>
															<td class="top border-r border-b"></td>
															<td class="top border-r border-b"></td>
															<td class="top txt-right txt-b border-r border-b">95</td>
															<td class="top border-r border-b"></td>
															<td class="top txt-right txt-b border-r border-b"><?php echo number_format(($total_product*100)/95,4)?></td>
														</tr>
													</table>
												</td>
											</tr>
										</table>
									</div>		
								</div>
							</div>
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
							<!--<input type="button" name="finished_data" id="finished_data" value="Finished" class="button-create" OnClick="frm.hdnCmd.value='finished_data';JavaScript:return fncSubmit();">-->
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
