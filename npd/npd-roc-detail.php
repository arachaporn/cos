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
				
				$sql_product="select * from product where id_product='".$rs['id_product']."'";
				$res_product=mysql_query($sql_product) or die ('Error '.$sql_product);
				$rs_product=mysql_fetch_array($res_product);

				$mode='Edit '.$rs_product['product_name'];
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
							<!--<input type="button" name="update_data" id="update_data" value="<?php echo $button?>" class="button-create" OnClick="frm.hdnCmd.value='update_data';JavaScript:return fncSubmit();">
							<input type="button" name="sendmail" id="sendmail" value="Send mail" class="button-create" OnClick="frm.hdnCmd.value='sendmail';JavaScript:return fncSubmit();">-->
							<input type="button" value="Close" class="button-create" onclick="window.location.href='npd-roc.php'">
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
									<?php if($rs_company['company_name']==''){echo '-';}else{echo $rs_company['company_name'];}?>
									</td>
									<td class="top">ชื่อผู้ติดต่อ</td>
									<td class="top w30">
									<?php 
										$sql_contact="select * from company_contact where id_contact='".$rs['id_contact']."'";
										$res_contact=mysql_query($sql_contact) or die ('Error '.$sql_contact);
										$rs_contact=mysql_fetch_array($res_contact);
										?>
										<?php if($rs_contact['contact_name']==''){echo '-';}else{echo $rs_contact['contact_name'];}?>							
									</td>
									<td class="top w6">เลขที่เอกสาร</td>
									<td class="top"><?php echo $rs['roc_code']?></td>
								</tr>
								<tr>
									<td class="top w10">เบอร์โทรศัพท์</td>
									<td class="top"><?php if($rs_company['company_tel']==''){echo '-';}else{echo $rs_company['company_tel'];}?></td>
									<td class="top">มือถือ</td>
									<td class="top"><?php if($rs_contact['mobile']==''){echo '-';}else{echo $rs_contact['mobile'];}?></td>
								</tr>
								<tr>
									<td class="top w13">วันที่</td>
									<td class="top">
										<?php
										list($ckyear,$ckmonth,$ckday) = split('[/.-]', $rs['date_roc']); 
										echo $ckstart= $ckday . "/". $ckmonth . "/" .$ckyear;
										?>
									</td>
									<td class="top">แฟกซ์</td>
									<td class="top"><?php if($rs_company['company_fax']==''){echo '-';}else{echo $rs_company['company_fax'];}?></td>
									<td class="top">อีเมล์</td>
									<td class="top"><?php if($rs_contact['email']==''){echo '-';}else{echo $rs_contact['email'];}?></td>
								</tr>
								<tr>
									<td class="top">ที่อยู่</td>
									<td class="top">
										<?php 
										$sql_address="select * from company_address where id_address='".$rs_company['id_address']."'";
										$res_address=mysql_query($sql_address) or die ('Error '.$sql_address);
										$rs_address=mysql_fetch_array($res_address);
										?>
										<?php if($rs['id_address']==0){if($rs['address']==''){echo '-';}else{echo $rs['address'];}}else{echo $rs_address['address_no'].'&nbsp;'.$rs_address['road'].'&nbsp;'.$rs_address['sub_district'].'&nbsp;'.$rs_address['district'].'&nbsp;'.$rs_address['province'].'&nbsp;'.$rs_address['postal_code'];}?>
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
									<input type="checkbox" name="com_cate" id="com_cate" class="com_cate" value="<?php echo $rs_com_cate['id_com_cat']?>" <?php if($rs_com_cate['id_com_cat']==$rs['id_com_cat']){echo 'checked';}?> disabled><?php echo $rs_com_cate['title']?></td>
									<?php if($j % 2 == 0){ ?></tr><?php } 
										$rows_com_cate++;
									}//end while type device
									if($max_row==$rows_com_cate){
									?>
									<td class="top"><?php echo $title?></td>
									<td class="top" colspan="2"><div style="float:left; margin: 0 0.5% 0 0;"><input type="checkbox" class="checkbox" disabled></div><div style="float:left; margin: 0 0.5% 0 0;">อื่น ๆ</div><div style="float:left">.................................................</div></td>
								<?php }?>
								</tr>
								<tr>
									<td class="top w10">Project Name/Benchmark</td>
									<td class="top" colspan="2"><?php if($rs_product['product_name']==''){echo '-';}else{echo $rs_product['product_name'];}?>
									</td>
								</tr>
								<tr>
									<td class="top w10">ชนิดของผลิตภัณฑ์</td>
									<td class="top" colspan="3">
										<?php
										$sql_type_product="select * from npd_type_product";
										$res_type_product=mysql_query($sql_type_product) or die ('Error '.$sql_type_product);
										while($rs_type_product=mysql_fetch_array($res_type_product)){
										?>
										<input type="checkbox" name="type_product" disabled <?php if($rs['id_type_product']==$rs_type_product['id_npd_type_product']){echo 'checked';}?> value="<?php echo $rs_type_product['id_npd_type_product']?>"><?php echo $rs_type_product['npd_title'].'&nbsp;&nbsp;&nbsp;'?>
										<?php } ?>
									</td>
								</tr>
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
										<td class="title-group" colspan="6"><input type="checkbox" name="roc_group_product" id="roc_group_product<?php echo $i?>" value="<?php echo $rs_roc_group_product['id_group_product']?>" disabled <?php if(is_numeric($id)){if($rs['id_group_product']==$rs_roc_group_product['id_group_product']){echo 'checked';}}?> onclick="javascript:ShowFunc();">1.<?php echo $i.'&nbsp;'.$rs_roc_group_product['title'];?></td>
									</tr>
									<tr>
									<td colspan="6">
										<div id="show_func<?php echo $i?>" <?php if($rs['id_group_product']==$i){echo '';}else{echo 'style="display: none;"';}?>>
										<?php									
										$num=0;
										$sql_roc_function="select * from roc_function where id_group_product='".$rs_roc_group_product['id_group_product']."'";
										$res_roc_function=mysql_query($sql_roc_function) or die ('Error '.$sql_roc_function);
										$max_row=mysql_num_rows($res_roc_function);
										while($rs_roc_function=mysql_fetch_array($res_roc_function)){
											$num++;
											if(($i==3) || ($i==4)){$br='<div class="clear"></div>';$width='width:40%';}else{if($num %2 == 0){$br='<div class="clear"></div>';$width='width:20%';}else{$br='';$width='width:20%';}}
										?>
											<div class="title-function" style="<?php echo $width?>">
											<input type="checkbox" class="checkbox" disabled <?php if($rs_roc_group_product['id_group_product']==1){echo 'name="roc_function1[]"';}elseif($rs_roc_group_product['id_group_product']==2){echo 'name="roc_function2[]"';}elseif($rs_roc_group_product['id_group_product']==3){echo 'name="roc_function3[]"';}elseif($rs_roc_group_product['id_group_product']==4){echo 'name="roc_function4[]"';}?> id="roc_function<?php echo $rs_roc_function['id_roc_func']?>" rel="roc_group_product<?php echo $i?>" value="<?php echo $rs_roc_function['id_roc_func']?>" <?php if(in_array($rs_roc_function['id_roc_func'],$roc_func)){echo 'checked';}?>><?echo $rs_roc_function['title']?>
											</div><?php echo $br?>
											<?php if($num==$max_row){?>
											<div class="title-function" style="<?php echo $width?>"><div style="float:left; margin: 0 1% 0 0;"><input type="checkbox" class="checkbox" value="0" disabled <?php if($rs_roc_group_product['id_group_product']==1){echo 'name="roc_function1[]"';}elseif($rs_roc_group_product['id_group_product']==2){echo 'name="roc_function2[]"';}elseif($rs_roc_group_product['id_group_product']==3){echo 'name="roc_function3[]"';}elseif($rs_roc_group_product['id_group_product']==4){echo 'name="roc_function4[]"';}?> <?php if(in_array(0,$roc_func)){echo 'checked';}?>></div><div style="float:left; margin: 0 0.5% 0 0;">อื่น ๆ</div><div style="float:left"><?php if($rs['roc_func_other']==''){echo '';}else{echo $rs['roc_func_other'];}?></div></div>
											<?php echo $br?>
										<?php }
										}//end while roc function
									}//end while roc_group_product 
									$max_row_g=$max_row_g+1;
									?>	
										</div><!--end div function show -->
									</td>
								</tr>
									<tr>
										<td class="title-group" colspan="6"><input type="checkbox" disabled name="roc_group_product" id="roc_group_product<?php echo $i+1?>" <?php if($rs['id_group_product']== -1){echo 'checked';}?> value="-1" onclick="javascript:ShowFunc();">1.<?php echo $max_row_g.'&nbsp;อื่น ๆ'?>
										<?php if($rs['id_group_product']== -1){$style='';}else{$style='display: none;';}?>
										<textarea style="width:50%; height:100px;<?php echo $style?>" name="roc_group_product_other" id="show_func<?php echo $i+1?>"><?php if(is_numeric($id)){echo $rs['roc_group_product_other'];}?></textarea></td>
									</tr>
								<?php }elseif($_GET['p']==2){
								?>
									<input type="hidden" name="pages" value="2">
									<tr>										
										<td colspan="6"><p class="title" style="padding-top: 1%;">2.ระบุสารสำคัญที่ต้องการ/ข้อเสนอแนะอื่น ๆ (ถ้ามี)</p>
										<table style="border: none; width: 20%; margin-top: 1%;" cellpadding="0" cellspacing="0">
											<?php
											$i_rm=0;
											$sql_roc_rm="select * from roc_rm where id_roc='".$id."'";
											$res_roc_rm=mysql_query($sql_roc_rm) or die('Error '.$sql_roc_rm);
											while($rs_roc_rm=mysql_fetch_array($res_roc_rm)){									
												$i_rm++;
											?>	
												<tr>
													<td style="line-height: 1.8em;font-size: 1em;"><?php echo $i_rm.'.'.$rs_roc_rm['roc_rm']?></td>
												</tr>
											<?php 
											}//end roc rm?>
										</table>
										</td>
									</tr>									
									<tr>
										<td colspan="6"><p class="title">3.ลักษณะรูปแบบผลิตภัณฑ์</p></td>
									</tr>
									<?php
									$roc_product_value=split(",",$rs['id_product_value']);
									$i=0;
									$num=0;
									$sql_product_appearance="select * from product_appearance";
									$res_product_appearance=mysql_query($sql_product_appearance) or die ('Error '.$sql_product_appearance);
									$max_row=mysql_num_rows($res_product_appearance);
									while($rs_product_appearance=mysql_fetch_array($res_product_appearance)){
										$i++;
										$num++;
									?>		
										<tr>
											<td class="title-group" colspan="6"><input type="checkbox" name="id_product_appearance" id="product_appearance<?php echo $i?>" disabled value="<?php echo $rs_product_appearance['id_product_appearance']?>" <?php if($rs['id_product_appearance']==$rs_product_appearance['id_product_appearance']){echo 'checked';}?> onclick="javascript:ShowProductApp();"><?php echo $rs_product_appearance['title_thai'].'('.$rs_product_appearance['title'].')'?></td>
										</tr>										
										<tr>
											<td>
												<div id="show_product_detail<?php echo $i?>" <?php if($rs['id_product_appearance']==$i){echo '';}else{echo 'style="display: none;"';}?>>
													<?php if($rs_product_appearance['id_product_appearance']==1){$i_value=0;?><div class="title-weight">ลักษณะของเม็ด</div><?php }?>
													<?php if(($rs_product_appearance['id_product_appearance']==2) || ($rs_product_appearance['id_product_appearance']==3)){?><div class="title-weight">ชนิดของเปลือกแคปซูล</div><?php }?>
												<?php 
												$sql_rela_value="select * from roc_relation_value where id_relation_value='".$rs['id_relation_value']."'";
												$res_rela_value=mysql_query($sql_rela_value) or die ('Error '.$sql_rela_value);
												$rs_rela_value=mysql_fetch_array($res_rela_value);
												$roc_value_title=split(",",$rs_rela_value['title_value']);

												$num_v=0;
												$rows_v = 0;
												$j_v=0;
												$j_value=0;
												$sql_roc_product_v="select * from roc_product_value where id_type_product='".$rs_product_appearance['id_product_appearance']."'";
												$res_roc_product_v=mysql_query($sql_roc_product_v) or die ('Error '.$sql_roc_product_v);
												$max_row_v=mysql_num_rows($res_roc_product_v);
												while($rs_roc_product_v=mysql_fetch_array($res_roc_product_v)){
													$j_v++;
													$num_v++;	
													$i_value++;
													if(($rs_product_appearance['id_product_appearance']==2) || ($rs_product_appearance['id_product_appearance']==3)){
														$br='<div class="clear"></div>';
														$width='width:40%';
													}
													else{
														if($num_v %2 == 0){
															$br='<div class="clear"></div>';
															if(($rs_product_appearance['id_product_appearance']==5) || ($rs_product_appearance['id_product_appearance']==6) || ($rs_product_appearance['id_product_appearance']==7)){
																$width='width:13%;';
															}
															else{$width='width:20%;';}
															$float='float:left;';
														}
														else{
															$br='';
															if(($rs_product_appearance['id_product_appearance']==5) || ($rs_product_appearance['id_product_appearance']==6) || ($rs_product_appearance['id_product_appearance']==7)){
																$width='width:13%;';																
															}
															else{$width='width:20%;';}
															$float='float:left;';
														}
													}
												?>
													<div class="title-function" style="<?php echo $width.$float?>">
													<?php if($rs_product_appearance['id_product_appearance']==4){?>
													<input type="checkbox" id="roc_product_value<?php echo $i_value?>" name="roc_product_value[]" disabled value="<?php echo $rs_roc_product_v['id_product_value']?>" <?php if(in_array($rs_roc_product_v['id_product_value'],$roc_product_value)){echo 'checked';}?>><?echo $rs_roc_product_v['title']?>
													<?php }else{?>
													<input type="checkbox" id="roc_product_value<?php echo $i_value?>" name="roc_product_value[]" disabled value="<?php echo $rs_roc_product_v['id_product_value']?>" <?php if(in_array($rs_roc_product_v['id_product_value'],$roc_product_value)){echo 'checked';}?>><?echo $rs_roc_product_v['title']?>
													<?php }?>
													</div>
													<?php 
														if(($rs_roc_product_v['title']== 'สี (Color)') ||($rs_roc_product_v['title']=='กลิ่น (Odor)') ||  ($rs_roc_product_v['title']=='รส (Taste)') || ($rs_roc_product_v['title']=='รูปร่าง (Shape)') || ($rs_roc_product_v['title']=='น้ำหนัก')){
													?>	
														<div style="float:left">ระบุ &nbsp;&nbsp;<?php if(in_array($rs_roc_product_v['id_product_value'],$roc_product_value)){echo $roc_value_title[$j_value];}?></div>
													<?php }													
													echo $br;
													?>													
													<?php if($num_v==$max_row_v){
														
														if($rs_product_appearance['id_product_appearance']==4){
													?>
															<div class="title-function" style="width:40%;float:left;"><div style="float:left; margin: 0 0.5% 0 0;"><input type="checkbox" name="roc_product_value[]" id="roc_product_value0" disabled value="0" <?php if(in_array('0',$roc_product_value)){echo 'checked';}?>></div><div style="float:left; margin: 0 0.5% 0 0;">ผงชงดื่มประเภทอื่น</div><div style="float:left">ระบุ &nbsp;&nbsp;<?php echo $rs['product_value_title']?></div></div>
															<?php }//end equal Instant Drink
														}
													$rows_v++;
													$j_value++;
												}//end while roc_product_value
												if(($rs_product_appearance['id_product_appearance']== 2) || ($rs_product_appearance['id_product_appearance']== 3)){?>
													<div class="title-weight">ลักษณะผลิตภัณฑ์ที่บรรจุ</div>
												<?php 
												$num_p=0;
												$rows_p=0;
												$j_p=0;
												$sql_roc_product_p="select * from type_product_pack where id_product_appearance='".$rs_product_appearance['id_product_appearance']."'";
												$res_roc_product_p=mysql_query($sql_roc_product_p) or die ('Error '.$sql_roc_product_p);
												$max_row_p=mysql_num_rows($res_roc_product_p);
												while($rs_roc_product_p=mysql_fetch_array($res_roc_product_p)){
													$j_p++;
													$num_p++;
													$br='<div class="clear"></div>';
													$width='width:20%;';
													$float='float:left;';
												?>
													<div class="title-function" style="<?php echo $width.$float?>"><input type="checkbox" id="type_product_pack<?php echo $i_value?>" name="type_product_pack" disabled value="<?php echo $rs_roc_product_p['id_type_product_pack']?>" <?php if($rs['id_type_product_pack']==$rs_roc_product_p['id_type_product_pack']){echo 'checked';}?>><?echo $rs_roc_product_p['title_product_pack']?></div>
													<?php 
													if(($rs_product_appearance['id_product_appearance']==6) || ($rs_product_appearance['id_product_appearance']==7)){
															if(($rs_roc_product_p['title']== 'สี (Color)') ||($rs_roc_product_p['title']=='กลิ่น (Odor)') || ($rs_roc_product_p['title']=='รูปร่าง (Shape)') || ($rs_roc_product_p['title']=='สี') || ($rs_roc_product_p['title']=='กลิ่น')){
																echo '<div style="float:left">ระบุ &nbsp;&nbsp;<input type="text" name="type_product_pack_other[]"></div>';
															}//end color for soft gelation capsule
													}//end functional drink and gummy
													echo $br;
													?>
													<?php if($num_p==$max_row_p){
														if($rs_product_appearance['id_type_product']== 3){
													?>
													<div class="title-function" style="<?php echo $width.$float?>"><div style="float:left; margin: 0 0.5% 0 0;"><input type="radio" id="type_product_pack<?php echo $i_value?>" name="type_product_pack" value="0" <?php if($rs['id_type_product_pack']==0){echo 'checked';}?> disabled></div><div style="float:left; margin: 0 0.5% 0 0;">อื่น ๆ</div><div style="float:left">ระบุ &nbsp;&nbsp;</div></div>
													<?php }//end not equal capsule 
													}
													$rows_p++;
													}//end type product pack
												}//end capsule?>
												<div class="clear"></div>
												<?php if($rs_product_appearance['id_product_appearance'] != 7){?>
													<div class="title-weight" colspan="6">น้ำหนักผลิตภัณฑ์ต่อหน่วย</div>
													<?php
													$num_w=0;
													$rows = 0;
													$j=0;
													$sql_roc_product_w="select * from roc_product_weight where id_product_appearance='".$rs_product_appearance['id_product_appearance']."'";
													$res_roc_product_w=mysql_query($sql_roc_product_w) or die ('Error '.$sql_roc_product_w);
													$max_row_w=mysql_num_rows($res_roc_product_w);
													while($rs_roc_product_w=mysql_fetch_array($res_roc_product_w)){
														$j++;
														$num_w++;
														if($num_w %4 == 0){
															$br='<div class="clear"></div>';
															$width='width:20%;';
															$float='float:left;';
														}
														else{
															$br='';
															$width='width:20%;';
															$float='float:left;';
														}
													?>
														<div class="title-function" style="<?php echo $width.$float?>"><input type="checkbox" class="checkbox" name="roc_product_weight" value="<?php echo $rs_roc_product_w['id_product_weight']?>" <?php if($rs['id_product_weight']==$rs_roc_product_w['id_product_weight']){echo 'checked';}?> disabled><?echo $rs_roc_product_w['title']?></div><?php echo $br?>
														<?php if($num_w==$max_row_w){?>
														<div class="title-function" style="<?php echo $width.$float?>"><div style="float:left; margin: 0 0.5% 0 0;"><input type="checkbox" class="checkbox" name="roc_product_weight" value="0" disabled></div><div style="float:left; margin: 0 0.5% 0 0;">อื่น ๆ</div><div style="float:left">ระบุ &nbsp;&nbsp;</div></div<?php echo $br?>>
														<?php } 
														$rows++;
													}//end while roc_product_w
												}
												?>
												<div class="clear"></div>
												<?php
												if(($rs_product_appearance['id_product_appearance']==1) || ($rs_product_appearance['id_product_appearance']==2) || ($rs_product_appearance['id_product_appearance']==3) || ($rs_product_appearance['id_product_appearance']==4)){?>
													<?php if($rs_product_appearance['id_product_appearance']==1){?><div class="title-weight">สี กลิ่น และรูปร่างของเม็ด</div>
													<?php }elseif($rs_product_appearance['id_product_appearance']==2){?><div class="title-weight">สีของแคปซูล</div>
													<?php }elseif($rs_product_appearance['id_product_appearance']==3){?><div class="title-weight">สีและกลิ่นเปลือกแคปซูล</div>
													<?php }elseif($rs_product_appearance['id_product_appearance']==4){?><div class="title-weight" colspan="6">สีและกลิ่นของผงชงดื่ม</div><?php }}?>
												<?php
												$sql_rela_color="select * from roc_relation_color where id_relation_color='".$rs['id_relation_color']."'";
												$res_rela_color=mysql_query($sql_rela_color) or die ('Error '.$sql_rela_color);
												$rs_rela_color=mysql_fetch_array($res_rela_color);

												$roc_color=split(",",$rs_rela_color['id_type_product_color']);
												$roc_color_title=split(",",$rs_rela_color['title_color']);
												$num_c=0;
												$rows_c=0;
												$j_c=0;
												$i_title=0;
												$sql_roc_product_c="select * from type_product_color where id_product_appearance='".$rs_product_appearance['id_product_appearance']."'";
												$res_roc_product_c=mysql_query($sql_roc_product_c) or die ('Error '.$sql_roc_product_c);
												$max_row_c=mysql_num_rows($res_roc_product_c);
												while($rs_roc_product_c=mysql_fetch_array($res_roc_product_c)){
													$j_c++;
													$num_c++;	
													if($num_c %2 == 0){
														$br='<div class="clear"></div>';
														$width='width:25%;';
														$float='float:left;';
													}
													else{
														$br='';
														$width='width:25%;';
														$float='float:left;';
													}	
												?>
													<input type="hidden" name="relation_color" value="<?php echo $rs_rela_color['id_relation_color']?>">
													<div class="title-function" style="<?php echo $width.$float?>"><div style="float:left; margin: 0 0.5% 0 0;"><input type="checkbox" class="checkbox" <?php if($rs_product_appearance['id_product_appearance']==1){?>name="type_product_color1[]" <?php }elseif($rs_product_appearance['id_product_appearance']==2){?>name="type_product_color2[]"<?php }elseif($rs_product_appearance['id_product_appearance']==3){?>name="type_product_color3[]"<?php }elseif($rs_product_appearance['id_product_appearance']==4){?>name="type_product_color4[]"<?php }?> disabled value="<?php echo $rs_roc_product_c['id_type_product_color']?>" <?php if(in_array($rs_roc_product_c['id_type_product_color'],$roc_color)){echo 'checked';}?>></div><div style="float:left; margin: 0 0.5% 0 0;"><?echo $rs_roc_product_c['type_product_color']?></div>
													<?php 
														if(($rs_product_appearance['id_product_appearance']==1) || ($rs_product_appearance['id_product_appearance']==3)){
															if(($rs_roc_product_c['type_product_color']== 'สี (Color)') ||($rs_roc_product_c['type_product_color']=='กลิ่น (Odor)') || ($rs_roc_product_c['type_product_color']=='รูปร่าง (Shape)') || ($rs_roc_product_c['type_product_color']=='สี') || ($rs_roc_product_c['type_product_color']=='กลิ่น')){
																$sql_rela_color="select * from roc_relation_color where id_roc='".$id."'";
																$sql_rela_color .=" and id_type_product_color='".$rs_roc_product_c['id_type_product_color']."'";
																$res_rela_color=mysql_query($sql_rela_color) or die ('Error '.$sql_rela_color);
																$rs_rela_color=mysql_fetch_array($res_rela_color);
															?><div style="float:left">ระบุ &nbsp;&nbsp;<?php if(in_array($rs_roc_product_c['id_type_product_color'],$roc_color)){ echo $roc_color_title[$i_title];}?></div>
														<?php }//end color for soft gelation capsule
														}//end soft gelatin capsule
														if($rs_product_appearance['id_product_appearance']==4){
															if(($rs_roc_product_c['type_product_color']== 'กลิ่น (Odor)') ||($rs_roc_product_c['type_product_color']=='รส (Taste)') || ($rs_roc_product_c['type_product_color']== 'สี (Color)')) {
														?>
															<div style="float:left">ระบุ &nbsp;&nbsp;<?php if(in_array($rs_roc_product_c['id_type_product_color'],$roc_color)){ echo $roc_color_title[$i_title];}?></div>
														<?php  }//end color for Instant Drink
														}
													?>
													</div><?php echo $br?>
													<?php if($num_c==$max_row_c){$i_title++;
														if($rs_type_product['id_type_product']!=3){
															if($rs_product_appearance['id_product_appearance']==2){$i_title=0;}
															if($rs_product_appearance['id_product_appearance']==3){$i_title=2;}
													?>
													<div class="title-function" style="<?php echo $width.$float?>"><div style="float:left; margin: 0 0.5% 0 0;"><input type="checkbox" class="checkbox" <?php if($rs_product_appearance['id_product_appearance']==1){?>name="type_product_color1[]" <?php }elseif($rs_product_appearance['id_product_appearance']==2){?>name="type_product_color2[]"<?php }elseif($rs_product_appearance['id_product_appearance']==3){?>name="type_product_color3[]"<?php }elseif($rs_product_appearance['id_product_appearance']==4){?>name="type_product_color4[]"<?php }?> disabled <?php if($rs['id_product_appearance']==$rs_product_appearance['id_product_appearance']){if(in_array('0',$roc_color)){echo 'checked';}}?> value="0" ></div><div style="float:left; margin: 0 0.5% 0 0;">อื่น ๆ</div><div style="float:left">ระบุ &nbsp;&nbsp;<?php if($rs['id_product_appearance']==$rs_product_appearance['id_product_appearance']){if(in_array('0',$roc_color)){echo $roc_color_title[$i_title];}}?></div></div><?php echo $br?>
													<?php  } // not equal soft gelatin capsule
													}
													$i_title++;
													$rows_c++;
													}//end type product pack
												
												if($rs_product_appearance['id_product_appearance'] == 7){
												?>
													<div class="title-weight">ลักษณะการเคลือบ</div>
												<?php 
												$num_p2=0;
												$rows_p2=0;
												$j_p2=0;
												$sql_roc_product_p="select * from type_product_pack where id_product_appearance='".$rs_product_appearance['id_product_appearance']."'";
												$res_roc_product_p=mysql_query($sql_roc_product_p) or die ('Error '.$sql_roc_product_p);
												$max_row_p2=mysql_num_rows($res_roc_product_p);
												while($rs_roc_product_p=mysql_fetch_array($res_roc_product_p)){
													$j_p2++;
													$num_p2++;
													if($num_p2 % 2 == 0){
														$br='<div class="clear"></div>';
														$width='width:20%';
														$float='float:left;';
													}
													else{
														$br='';
														$width='width:20%';
														$float='float:left;';
													}
												?>
														<div class="title-function" style="<?php echo $width.$float?>"><input type="checkbox" class="checkbox" name="type_product_pack" value="<?php echo $rs_roc_product_p['id_type_product_pack']?>" disabled <?php if($rs_roc_product_p['id_type_product_pack']==$rs['id_type_product_pack']){echo 'checked';}?>><?echo $rs_roc_product_p['title_product_pack']?></div><?php echo $br?>
														<?php if($num_p2==$max_row_p2){?>
														<div class="title-function" style="<?php echo $width.$float?>"><div style="float:left; margin: 0 1% 0 0;"><input type="checkbox" class="checkbox" name="type_product_pack" disabled value="0"></div><div style="float:left; margin: 0 0.5% 0 0;">อื่น ๆ</div><div style="float:left">ระบุ &nbsp;&nbsp;</div></div><?php echo $br?>
														<?php }
														$rows_p2++;
														}//end type product pack
													} //end type product gumm  	
											}//end while all product
											?>
											</div><!--end div function show product appearance-->
										</td>
									</tr>
									<?php if($num==$max_row){?>
									<tr>
										<td class="title-group" colspan="6"><input type="checkbox" name="id_product_appearance" id="product_appearance<?php echo $i+1?>" <?php if($rs['id_group_product']== -1){echo 'checked';}?> disabled value="-1" onclick="javascript:ShowProductApp();">อื่น ๆ
										<?php if($rs['id_product_appearance']== -1){$style='';}else{$style='display: none;';}?>
										<?php echo 'ระบุ' ;echo '<br>';echo $rs['roc_type_product_other']?></td>
									<tr>
									<?php } //end data other product apperance
									}//end page 2
									elseif($_GET['p']==3){?>
									<input type="hidden" name="pages" value="3">
									<tr>
										<td colspan="6"><p class="title">4.บรรจุภัณฑ์</p></td>
									</tr>
									<tr>
									<?php
										$sql_relation_pack="select * from roc_relation_pack where id_relation_pack='".$rs['id_relation_pack']."'";
										$res_relation_pack=mysql_query($sql_relation_pack) or die ('Error '.$sql_relation_packs);
										$rs_relation_pack=mysql_fetch_array($res_relation_pack);
										$product_app=$rs_relation_pack['id_product_appearance'];
									?>
										<td class="title-group" colspan="6">
										<input type="hidden" name="id_relation_pack" value="<?php echo $rs_relation_pack['id_relation_pack']?>">
										<input type="checkbox" name="roc_pack" value="1,2,3" id="roc_pack1" onclick="javascript:ShowPack();" <?php if($rs_relation_pack['id_product_appearance']=='1,2,3'){echo 'checked';}?> disabled>
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
									<tr>
										<td>
											<div id="show_pack1" <?php if($product_app=='1,2,3'){echo '';}else{echo 'style="display: none;"';}?>>
											<?php 
											$pack=$pack_array[0];
											$roc_type_packaging=split(",",$pack);
											for($i_pack=0;$i_pack<=count($roc_type_packaging);$i_pack++){
											$sql_type_packaging="select * from type_packaging where id_type_package='1' or id_type_package='5'";
											$sql_type_packaging .=" order by id_type_package desc";
											$res_type_packaging=mysql_query($sql_type_packaging) or die ('Error '.$sql_type_packaging);
											while($rs_type_packaging=mysql_fetch_array($res_type_packaging)){
											?>	
												<div class="title-function" style="float:left;" colspan="6"><input type="checkbox" name="type_packaging" id="type_packaging<?php echo $i_pack?>" disabled value="<?php echo $rs_type_packaging['id_type_package']?>" <?php if($rs_relation_pack['id_type_package']==$rs_type_packaging['id_type_package']){echo 'checked';}?>><?php echo $rs_type_packaging['title_thai']?></div>
												<div class="clear"></div>
												<div class="title-sub-function" style="padding-bottom:1%;float:left;">ขนาดบรรจุ</div>
												<?php
												//$i_pack=0;
												$num_box_size=0;
												$rows_box_size=0;
												$sql_box_size="select * from roc_product_box_size where id_type_package='".$rs_type_packaging['id_type_package']."'";
												$res_box_size=mysql_query($sql_box_size) or die ('Error '.$sql_box_size);
												$max_row_box_size=mysql_num_rows($res_box_size);
												while($rs_box_size=mysql_fetch_array($res_box_size)){
													$num_box_size++;
													$i_pack++;
													if($num_box_size % 3 == 0){
														$br='<div class="clear"></div>';
														$width='width:20%;';
														$padding='padding-left: 5.6%;';
														$float='float:left;';
													}
													else{
														$br='';
														$width='width:19%;';
														$padding='padding-left: 5.6%;';
														$float='float:left;';
													}
												?>
													<div class="title-function" style="<?php echo $padding.$width.$float?>"><input type="checkbox" name="pack_size" id="pack_size<?php echo $i_pack?>" disabled value="<?php echo $rs_box_size['id_box_size']?>" <?php if($rs_relation_pack['id_pack_size']==$rs_box_size['id_box_size']){echo 'checked';}?>><?echo $rs_box_size['title_box_size']?></div><?php echo $br?>
													<?php if($num_box_size==$max_row_box_size){?>
													<div class="title-function" style="width: 30%;padding-left: 16%;float:left;"><div style="float:left; margin: 0 0.5% 0 0;"><input type="checkbox" class="checkbox" disabled></div><div style="float:left; margin: 0 0.5% 0 0;">อื่น ๆ</div><div style="float:left">ระบุ &nbsp;&nbsp;................................</div></div><?php echo $br?>
													<?php }
												}
												?>
											<div class="clear"></div>
											<?php if($rs_type_packaging['id_type_package']==5){?>
												<div class="title-sub-function" style="float:left;">แผง & ฟอยล์</div>
												<?php
												$roc_foil=split(",",$rs_relation_pack['id_product_foil']);
												$i_foil=0;
												$sql_foil="select * from roc_product_foil where id_type_package='".$rs_type_packaging['id_type_package']."'";
												$res_foil=mysql_query($sql_foil) or die ('Error '.$sql_foil);
												while($rs_foil=mysql_fetch_array($res_foil)){
													$i_foil++;
													if($i_foil % 2 ==0){
														$padding='padding-left: 16%;';
														$width='width:30%;';
														$float='float:left;';
													}else{
														$padding='';
														$width='width:20%;';
														$float='float:left;';
													}
													if($i_foil==1){		
												?>
													<div class="title-function" style="width:20%;float:left;"><input type="checkbox" <?php if($rs_type_packaging['id_type_package']==5){?>id="foil1<?php echo $i_materials?>"<?php }?> name="foil1[]" disabled value="<?php echo $rs_foil['id_product_foil']?>" <?php if(in_array($rs_foil['id_product_foil'],$roc_foil)){echo 'checked';}?>><?php echo $rs_foil['title_foil']?></div><div class="clear"></div>
													<?php }else{?>
													<div class="title-function" style="<?php echo $padding.$width.$float?>"><input type="checkbox" <?php if($rs_type_packaging['id_type_package']==5){?>id="foil2<?php echo $i_materials?>"<?php }?> name="foil1[]" disabled value="<?php echo $rs_foil['id_product_foil']?>" <?php if(in_array($rs_foil['id_product_foil'],$roc_foil)){echo 'checked';}?>><?php echo $rs_foil['title_foil']?></div>		
													<?php }
												}
											}else{?>
											<div class="clear"></div>
												<div class="title-sub-function" style="float:left;">ชนิดขวด</div>
												<?php
												$num_bottle=0;
												$sql_bottle="select * from roc_product_bottle where id_type_package='".$rs_type_packaging['id_type_package']."'";
												$res_bottle=mysql_query($sql_bottle) or die ('Error '.$sql_bottle);
												$max_row_bottle=mysql_num_rows($res_bottle);
												while($rs_bottle=mysql_fetch_array($res_bottle)){
													$num_bottle++;
													if($num_bottle != 1){
														$padding='padding-left:0;';
														$width='width:10%;';
														$float='float:left;';
													}else{
														$padding='padding-left: 6.4%;';
														$width='width:15%;';
														$float='float:left;';
													}
												?>
													<div class="title-function" style="<?php echo $padding.$width.$float?>"><input type="checkbox" class="checkbox" name="bottle" disabled value="<?php echo $rs_bottle['id_product_bottle']?>" <?php if($rs_relation_pack['id_product_bottle']==$rs_bottle['id_product_bottle']){echo 'checked';}?>><?php echo $rs_bottle['title_bottle']?></div>		
													<?php if($num_bottle==$max_row_bottle){?>
													<div class="title-function" style="width:20%;float:left;"><div style="float:left; margin: 0 0.5% 0 0;"><input type="checkbox" class="checkbox" disabled></div><div style="float:left; margin: 0 0.5% 0 0;">อื่น ๆ</div><div style="float:left">ระบุ &nbsp;&nbsp;.....................</div></div>
													<?php }//end max row ?>
												<?php 
												}
											}?>
											<div class="clear"></div>
												<div class="title-sub-function" style="float:left;">วัสดุบรรจุประกอบ</div>
												<?php
												$roc_materials=split(",",$rs_relation_pack['id_material']);
												$num_material=0;
												$sql_materials="select * from roc_product_materials where id_type_product='".$rs_type_packaging['id_type_package']."'";
												$res_materials=mysql_query($sql_materials) or die ('Error '.$sql_materials);
												$max_row_material=mysql_num_rows($res_materials);
												while($rs_materials=mysql_fetch_array($res_materials)){
													$num_material++;
													if($num_material != 1){
														$padding='padding-left: 16%;';
														$width='width: 50%;';
														$float='float:left;';
													}else{
														$padding='padding-left: 3%;';
														$width='width: 50%;';
														$float='float:left;';
													}
												?>	
													<div class="title-function" style="<?php echo $padding.$width.$float?>"><div style="float:left; margin: 0 0.5% 0 0;"><input type="checkbox" <?php if($rs_type_packaging['id_type_package']==1){?>id="materials1<?php echo $i_materials?>"<?php }elseif($rs_type_packaging['id_type_package']==5){?>id="materials2<?php echo $i_materials?>"<?php }?> name="materials1[]" disabled value="<?php echo $rs_materials['id_materials']?>" <?php if(in_array($rs_materials['id_materials'],$roc_materials)){echo 'checked';}?>></div><div style="float:left; margin: 0 0.5% 0 0;"><?php echo $rs_materials['title_materials']?></div>
													<?php 
													if($rs_materials['title_materials']=='ขนาดกล่อง'){
													?>
														<div style="float:left">ระบุ &nbsp;&nbsp;<?php if(in_array($rs_materials['id_materials'],$roc_materials)){echo $rs_relation_pack['box_detail'];}?></div>
													<?php ;}?>
													</div>
													<div class="clear"></div>
													<?php if($num_material==$max_row_material){
														if($rs_type_packaging['id_type_package'] != 5){
													?>
													<div class="title-function" style="padding-left:16%;width:30%;float:left;"><div style="float:left; margin: 0 0.5% 0 0;"><input type="checkbox" class="checkbox" disabled></div><div style="float:left; margin: 0 0.5% 0 0;">อื่น ๆ</div><div style="float:left">ระบุ &nbsp;&nbsp;</div></div><div class="clear"></div>
													<?php } 
													}//end max row 
												}//end while type packaging
												}//end for array pack
											}
											?>
											</div><div class="clear"></div>
										</td>
									</tr><!--end product tablet,capsule-->
									<?php
									$sql_relation_pack="select * from roc_relation_pack where id_relation_pack='".$rs['id_relation_pack']."'";
									$res_relation_pack=mysql_query($sql_relation_pack) or die ('Error '.$sql_relation_packs);
									$rs_relation_pack=mysql_fetch_array($res_relation_pack);
									$i_app=0;
									$sql_product_appearance="select * from product_appearance";
									$res_product_appearance=mysql_query($sql_product_appearance) or die ('Error '.$sql_product_appearance);
									while($rs_product_appearance=mysql_fetch_array($res_product_appearance)){
										$i_app++;
										if(($rs_product_appearance['id_product_appearance']==4) || ($rs_product_appearance['id_product_appearance']==5) || ($rs_product_appearance['id_product_appearance']==6)){
									?>
									<tr>
										<td class="title-group" colspan="6"><input type="checkbox" name="roc_pack" id="roc_pack<?php echo $i_app?>" disabled value="<?php echo $rs_product_appearance['id_product_appearance']?>" onclick="javascript:ShowPack();" <?php if($rs_relation_pack['id_product_appearance']==$rs_product_appearance['id_product_appearance']){echo 'checked';}?>>
										<?php 
											echo $rs_product_appearance['title_thai'].'('.$rs_product_appearance['title'].')';
										?>
										</td>
									</tr>
									<tr>
										<td>
											<div id="show_pack<?php echo $i_app?>" <?php if($rs_relation_pack['id_product_appearance']==$i_app){echo '';}else{echo 'style="display: none;"';}?>>										
												<?php if($rs_product_appearance['id_product_appearance']==4){?><div class="title-function" style="padding-bottom:1%;float:left;">จำนวนบรรจุกล่อง</div><?php }?>
												<?php if($rs_product_appearance['id_product_appearance']==5){?><div class="title-function" style="padding-bottom:1%;float:left;">จำนวนบรรจุ</div><?php }?>
												<?php if($rs_product_appearance['id_product_appearance']==6){?><div class="title-function" style="padding-bottom:1%;float:left;">จำนวนบรรจุต่อกล่อง</div><?php }?>
												<?php
												$num_box_size=0;
												$rows_box_size=0;
												$sql_box_size="select * from roc_product_box_size where id_type_product='".$rs_product_appearance['id_product_appearance']."'";
												$res_box_size=mysql_query($sql_box_size) or die ('Error '.$sql_box_size);
												$max_row_box_size=mysql_num_rows($res_box_size);
												while($rs_box_size=mysql_fetch_array($res_box_size)){
													$num_box_size++;	
													if($num_box_size % 3 == 0){														
														$br='<div class="clear"></div>';
														$width='width:15%;';
														$float='float:left;';
														if($num_box_size == 1){$padding='padding-left: 4%;';}
														else{$padding='padding-left: 2%;';}
													}else{
														$br='';
														$width='width:15%;';
														$float='float:left;';
														if($rs_product_appearance['id_product_appearance']==4){
															if($num_box_size == 4){$width='width:25%;';$padding='padding-left: 15.6%;';}
															else{$padding='padding-left: 4%;';}
														}else
														if($rs_product_appearance['id_product_appearance']==5){
															if($num_box_size == 4){$width='width:25%;';$padding='padding-left: 15.6%;';}
															else{$padding='padding-left: 6.2%;';}
														}else
														if($rs_product_appearance['id_product_appearance']==6){
															if($num_box_size == 4){$width='width:25%;';$padding='padding-left: 15.6%;';}
															else{$width='width:10%;';$padding='padding-left: 3%;';}
														}
													}
												?>
													<div class="title-function" style="<?php echo $width.$padding.$float?>"><input type="checkbox" class="checkbox" name="pack_size" disabled value="<?php echo $rs_box_size['id_box_size']?>" <?php if($rs_relation_pack['id_pack_size']==$rs_box_size['id_box_size']){echo 'checked';}?>><?echo $rs_box_size['title_box_size']?></div><?php echo $br?>
													<?php if($num_box_size==$max_row_box_size){
														if($rs_product_appearance['id_product_appearance']==6){  
															$padding='padding-left: 4%;';
														}else{$padding='padding-left: 5.5%;';}
													?>
													<div class="title-function" style="width:20%;float:left;<?php echo $padding?>"><div style="float:left; margin: 0 0.5% 0 0;"><input type="checkbox" class="checkbox" disabled></div><div style="float:left; margin: 0 0.5% 0 0;">อื่น ๆ</div><div style="float:left">ระบุ &nbsp;&nbsp;............</div></div><?php echo $br?>
													<?php } ?>
												<?php 
													$rows_box_size++;
												}
												?>
											<div class="clear"></div>
												<?php if($rs_product_appearance['id_product_appearance']==4){?><div class="title-function" style="padding-bottom:1%;float:left;">ขนาดซอง</div><?php }?>
												<?php if($rs_product_appearance['id_product_appearance']==5){?><div class="title-function" style="padding-bottom:1%;float:left;">รูปแบบซอง</div><?php }?>
												<?php
												$num_sachet=0;
												$rows_sachet=0;
												$sql_sachet="select * from roc_product_sachet where id_type_package='".$rs_product_appearance['id_product_appearance']."'";
												$res_sachet=mysql_query($sql_sachet) or die ('Error '.$sql_sachet);
												$max_row_sachet=mysql_num_rows($res_sachet);
												while($rs_sachet=mysql_fetch_array($res_sachet)){
													$num_sachet++;	
													if($num_sachet % 2 == 0){
														$br='<div class="clear"></div>';
														$float='float:left;';
														if($rs_product_appearance['id_product_appearance']==4){
															$width='width:20%;';
															$padding='padding-left: 0.5%;';
														}else
														if($rs_product_appearance['id_product_appearance']==5){
															$width='width:15%;';
															$padding='padding-left: 1.2%;';
														}
													}else{
														$br='';			
														$float='float:left;';
														if($rs_product_appearance['id_product_appearance']==4){
															if(($num_sachet == 3) || ($num_sachet == 5)){$width='width:30%;';$padding='padding-left: 15.6%;';}
															else{$width='width:21.4%;';$padding='padding-left: 6.9%;';}
														}else
														if($rs_product_appearance['id_product_appearance']==5){
															if(($num_sachet == 3) || ($num_sachet == 5)){$width='width:30%;';$padding='padding-left: 15.6%;';}
															else{$width='width:20%;';$padding='padding-left: 6.2%;';}
														}
													}
												?>
													<div class="title-function" style="<?php echo $width.$padding.$float?>"><input type="checkbox" class="checkbox" name="sachet" disabled value="<?php echo $rs_sachet['id_product_sachet']?>" <?php if($rs_relation_pack['id_product_sachet']==$rs_sachet['id_product_sachet']){echo 'checked';}?>><?echo $rs_sachet['title_sachet']?></div><?php echo $br?>
													<?php if($num_sachet==$max_row_sachet){?>
													<div class="title-function" style="width:20%;padding-left:0.5%;float:left;"><div style="float:left; margin: 0 0.5% 0 0;"><input type="checkbox" class="checkbox" disabled></div><div style="float:left; margin: 0 0.5% 0 0;">อื่น ๆ</div><div style="float:left">ระบุ &nbsp;&nbsp;..................</div></div>
													<?php } ?>
												<?php 
													$rows_sachet++;
												}
												?>
											<div class="clear"></div>
											<?php if($rs_product_appearance['id_product_appearance']==4){?>
												<div class="title-function" style="padding-bottom:1%;float:left;">ฟอยล์</div>
												<?php
												$num_foil=0;
												$rows_foil=0;
												$sql_foil="select * from roc_product_foil where id_type_package='".$rs_product_appearance['id_product_appearance']."'";
												$res_foil=mysql_query($sql_foil) or die ('Error '.$sql_foil);
												while($rs_foil=mysql_fetch_array($res_foil)){
													$num_foil++;	
													$width='width:20%;';
													$float='float:left;';
													if($num_foil==1){$padding='padding-left:8.5%;';}
													else{$padding='padding-left:3.5%;';}
												?>
													<div class="title-function" style="<?php echo $width.$padding.$float?>"><input type="checkbox" class="checkbox" name="foil4[]" disabled value="<?php echo $rs_foil['id_product_foil']?>" <?php if($rs_relation_pack['id_product_foil']==$rs_foil['id_product_foil']){echo 'checked';}?>><?php echo $rs_foil['title_foil']?></div>
												<?php 
													$rows_foil++;
												}
												?>
											<div class="clear"></div>	
											<?php }else
											if($rs_product_appearance['id_product_appearance']==6){?>
												<div class="title-function" style="float:left;">รูปแบบขวด</div>
												<?php
												$num_bottle=0;
												$sql_bottle="select * from roc_type_bottle where id_type_package='".$rs_product_appearance['id_product_appearance']."'";
												$res_bottle=mysql_query($sql_bottle) or die ('Error '.$sql_bottle);
												$max_row_bottle=mysql_num_rows($res_bottle);
												while($rs_bottle=mysql_fetch_array($res_bottle)){
													$num_bottle++;
													if($num_bottle % 3 == 0){														
														$br='<div class="clear"></div>';
														$width='width:15%;';
														$padding='padding-left: 6.5%;';
														$float='float:left;';
													}else{
														$br='';
														$width='width:15%;';
														$padding='padding-left: 6.4%;';
														$float='float:left;';
													}
												?>
													<div class="title-function" style="<?php echo $width.$padding.$float?>"><input type="checkbox" class="checkbox" name="bottle_size" disabled value="<?php echo $rs_bottle['id_type_bottle']?>" <?php if($rs_relation_pack['id_type_bottle']==$rs_bottle['id_type_bottle']){echo 'checked';}?>><?php echo $rs_bottle['title_type_bottle']?></div><?php echo $br?>		
													<?php if($num_bottle==$max_row_bottle){?>
													<div class="title-function" style="width:30%;padding-left:15.6%;float:left;"><div style="float:left; margin: 0 0.5% 0 0;"><input type="checkbox" class="checkbox" disabled></div><div style="float:left; margin: 0 0.5% 0 0;">อื่น ๆ</div><div style="float:left">ระบุ &nbsp;&nbsp;...................</div></div>
													<?php }//end max row ?>
												<?php 
												}//end while
												?>
											<div class="clear"></div>
												<div class="title-function" style="float:left;">ลักษณะฝา</div>
												<?php
												$num_lid=0;
												$sql_lid="select * from roc_product_bottle_lid where id_type_package='".$rs_product_appearance['id_product_appearance']."'";
												$res_lid=mysql_query($sql_lid) or die ('Error '.$sql_lid);
												while($rs_lid=mysql_fetch_array($res_lid)){
													$num_lid++;
													if($num_lid % 3 == 0){														
														$br='<div class="clear"></div>';
														$width='width:15%;';
														$padding='padding-left: 6.4%;';
														$float='float:left;';
													}else{
														$br='';
														$width='width:13%;';
														$padding='padding-left: 6.7%;';
														$float='float:left;';
													}
												?>
													<div class="title-function" style="<?php echo $width.$padding.$float?>"><input type="checkbox" class="checkbox" name="bottle_lid" disabled value="<?php echo $rs_lid['id_bottle_lid']?>" <?php if($rs_relation_pack['id_bottle_lid']==$rs_lid['id_bottle_lid']){echo 'checked';}?>><?php echo $rs_lid['title_bottle_lid']?></div><?php echo $br?>		
												<?php 
												}//end while
												?>
											<div class="clear"></div>
											<?php }//end if product=6?>
												<div class="title-function" style="float:left;">วัสดุบรรจุประกอบ</div>
												<?php
												$i_materials=0;
												$roc_materials=split(",",$rs_relation_pack['id_material']);
												$num_material=0;
												$sql_materials="select * from roc_product_materials where id_type_package='".$rs_product_appearance['id_product_appearance']."'";
												$res_materials=mysql_query($sql_materials) or die ('Error '.$sql_materials);
												$max_row_material=mysql_num_rows($res_materials);
												while($rs_materials=mysql_fetch_array($res_materials)){
													$num_material++;
													$i_materials++;
													if($num_material != 1){
														$padding='padding-left: 15.6%;';
														$width='width: 50%;';
														$float='float:left;';
													}else{
														$padding='padding-left: 4%;';
														$width='width: 50%;';
														$float='float:left;';
													}
												?>	
													<div class="title-function" style="<?php echo $padding.$width.$float?>"><div style="float:left; margin: 0 0.5% 0 0;"><input type="checkbox" disabled <?php if($rs_product_appearance['id_product_appearance']==4){?>id="materials4" name="materials4[]" <?php echo $i_materials?><?php }elseif($rs_product_appearance['id_product_appearance']==5){?>id="materials5<?php echo $i_materials?>" name="materials5[]"<?php }elseif($rs_product_appearance['id_product_appearance']==6){?>id="materials6<?php echo $i_materials?>" name="materials6[]"<?php }?> value="<?php echo $rs_materials['id_materials']?>" <?php if(in_array($rs_materials['id_materials'],$roc_materials)){echo 'checked';}?>></div><div style="float:left; margin: 0 0.5% 0 0;"><?php echo $rs_materials['title_materials']?></div>
													<?php 
													if($rs_materials['title_materials']=='ขนาดกล่อง'){
													?>
														<div style="float:left">ระบุ &nbsp;&nbsp;<?php if(in_array($rs_materials['id_materials'],$roc_materials)){echo $rs_relation_pack['box_detail'];}?></div>
													<?php ;}?>
													</div>
													<div class="clear"></div>
													<?php if($num_material==$max_row_material){
														if($rs_type_packaging['id_type_package'] != 5){
													?>
													<div class="title-function" style="padding-left: 15.6%;width:30%;float:left;"><div style="float:left; margin: 0 0.5% 0 0;"><input type="checkbox" class="checkbox" disabled></div><div style="float:left; margin: 0 0.5% 0 0;">อื่น ๆ</div><div style="float:left">ระบุ &nbsp;&nbsp;....................</div></div><div class="clear"></div>
													<?php }//end max row ?>
											<?php 
												}//end product appearance	
												}//end materials
											}//end while type packaging
											}//end for 
										?>
											</div>
										</td>
									</tr>
								<?}//end page3								
								elseif($_GET['p']==4){
								?>
									<input type="hidden" name="pages" value="4">
									<tr>
										<td class="title-group" colspan="2"><input type="checkbox" name="ink_jet" disabled value="1" <?php if($rs['id_ink_jet']==1){echo 'checked';}?>>Ink Jet</td>
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
										<td <?php echo $rowspan?> class="title-function" style="width:18%;"><input type="checkbox" name="type_ink_jet[]" id="type_ink<?php echo $i_ink?>" disabled value="<?php echo $rs_ink['id_ink_jet']?>" <?php if(in_array($rs_ink['id_ink_jet'],$type_ink)){echo 'checked';}?>><?php echo $rs_ink['title_ink_jet']?></td>
										<?php
										$j_ink_detail=0;
										$sql_ink_detail="select * from roc_ink_jet_detail where id_ink_jet='".$rs_ink['id_ink_jet']."'";
										$res_ink_detail=mysql_query($sql_ink_detail) or die ('Error '.$sql_ink_detail);
										while($rs_ink_detail=mysql_fetch_array($res_ink_detail)){
											$j_ink_detail++;
											if($j_ink_detail==1){
										?>
											<td class="title-function"><input type="checkbox" name="ink_jet_detail[]" rel="type_ink<?php echo $i_ink?>" value="<?php echo $rs_ink_detail['id_detail_ink']?>" disabled <?php if(in_array($rs_ink_detail['id_detail_ink'],$detail_ink)){echo 'checked';}?>><?php echo $rs_ink_detail['title_detail_ink']?></td>
										<?php }else{?>
										<tr>
											<td class="title-function"><input type="checkbox" rel="type_ink<?php echo $i_ink?>" name="ink_jet_detail[]" value="<?php echo $rs_ink_detail['id_detail_ink']?>" disabled <?php if(in_array($rs_ink_detail['id_detail_ink'],$detail_ink)){echo 'checked';}?>><?php echo $rs_ink_detail['title_detail_ink']?></td>
										</tr>
										<?php }										
										}//end while ink jet detail?>
									</tr>
									<?php 
									}//end while ink jet
									?>
									<tr>
										<td class="title-group" colspan="2"><b>5.ราคาโดยประมาณของผลิตภัณฑ์สำเร็จรูปที่ต้องการ </b><?php echo '&nbsp;&nbsp;&nbsp;';echo $rs['product_price']?></td>
									</tr>
									<tr>
										<td class="title-group" colspan="2"><b>6.ผลิตภัณฑ์ในท้องตลาดที่เป็นตัวเปรียบเทียบ</b><?php echo '&nbsp;&nbsp;&nbsp;';echo $rs['product_compare']?></td>
									</tr>
									<tr>
										<td class="title-group" colspan="2"><b>7.Product selling point</b><?php echo '&nbsp;&nbsp;&nbsp;';echo $rs['product_selling']?></td>
									</tr>
									<tr>
										<td class="title-group" colspan="2"><b>8.Market position</b><?php echo '&nbsp;&nbsp;&nbsp;';echo $rs['market_position']?></td>
									</tr>
									<tr>
										<td class="title-group" colspan="2"><b>9.Selling channel</b><?php echo '&nbsp;&nbsp;&nbsp;';echo $rs['selling_channel']?></td>
									</tr>
								<?php }//end page5?>
								<tr>
									<td class="title-group footer-right" colspan="8"><a href="npd-roc-detail.php?id_u=<?php echo $_GET["id_u"]?>&p=1" <?php if($_GET['p']==1){echo 'style="font-size: 1.8em;"';}?>>1</a> | <a href="npd-roc-detail.php?id_u=<?php echo $_GET["id_u"]?>&p=2" <?php if($_GET['p']==2){echo 'style="font-size: 1.8em;"';}?>>2</a> | <a href="npd-roc-detail.php?id_u=<?php echo $_GET["id_u"]?>&p=3" <?php if($_GET['p']==3){echo 'style="font-size: 1.8em;"';}?>>3</a> | <a href="npd-roc-detail.php?id_u=<?php echo $_GET["id_u"]?>&p=4" <?php if($_GET['p']==4){echo 'style="font-size: 1.8em;"';}?>>4</a></td>
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
							<!--<input type="button" name="update_data" id="update_data" value="<?php echo $button?>" class="button-create" OnClick="frm.hdnCmd.value='update_data';JavaScript:return fncSubmit();">
							<input type="button" name="sendmail" id="sendmail" value="Send mail" class="button-create" OnClick="frm.hdnCmd.value='sendmail';JavaScript:return fncSubmit();">-->
							<input type="button" value="Close" class="button-create" onclick="window.location.href='npd-roc.php'">						
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
