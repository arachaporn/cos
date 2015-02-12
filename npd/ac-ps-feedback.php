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
			if($_GET["id_u"]=='New'){
				$mode='New';
				$button='Save';
				$id_new =$_GET["id_u"];
				$id='New';
				
			}else{
				$id=$_GET["id_u"];
				$sql="select * from npd_ps where id_npd_ps='".$id."'";
				$res=mysql_query($sql) or die ('Error '.$sql);
				$rs=mysql_fetch_array($res);
				$button='Update';
				
				if($rs['id_product']==0){$mode=$rs['product_name'];}
				else{
					$sql_product="select * from product where id_product='".$rs['id_product']."'";
					$res_product=mysql_query($sql_product) or die ('Error '.$sql_product);
					$rs_product=mysql_fetch_array($res_product);
					$mode=$rs_product['product_name'];
				}
			}				
			?> 
			<script type="text/javascript" src="js/js-autocomplete/js/jquery-1.4.2.min.js"></script> 
			<script type="text/javascript" src="js/js-autocomplete/js/jquery-ui-1.8.2.custom.min.js"></script> 
			<script type="text/javascript"> 
				jQuery(document).ready(function(){
					$('.product_name').autocomplete({
						source:'return-product.php', 
						//minLength:2,
						select:function(evt, ui){
							this.form.id_product.value = ui.item.id_product;
						}
					});
					$('.company_name').autocomplete({
						source:'return-company.php', 
						//minLength:2,
						select:function(evt, ui){
							this.form.id_company.value = ui.item.id_company;
						}
					});
					$('.pm_name').autocomplete({
						source:'returnUser.php', 
						//minLength:2,
						select:function(evt, ui){
							this.form.id_account2.value = ui.item.id_account2;
						}
					});
				});
			</script> 
			<link rel="stylesheet" href="js/js-autocomplete/css/smoothness/jquery-ui-1.8.2.custom.css" /> 
			<form name="frm" method="post" action="dbfeedback.php">
				<input type="hidden" name="hdnCmd" value="">			
				<input type="hidden" name="mode" value="<?php echo $id?>">
				<table style="border: 0; width: 100%;" cellpadding="0" cellspacing="0">
					<tr>
						<td class="b-bottom"><div class="large-4 columns"><h4>Product Detail Feedback >> <?php echo $mode;?></h4></div></td>
					</tr>
					<tr>
						<td class="b-bottom">
							<div class="large-4 columns">		
								<?php if(!is_numeric($id)){?>   
								<input type="button" name="save_data" id="save_data" value="<?php echo $button?>" class="button-create" OnClick="frm.hdnCmd.value='save_data';JavaScript:return fncSubmit();">
								<?php }else{?>
								<input type="button" name="update_data" id="update_data" value="<?php echo $button?>" class="button-create" OnClick="frm.hdnCmd.value='update_data';JavaScript:return fncSubmit();">
								<!--<?php if($rs['approve_by'] == 0){?><input type="button" name="sendmail" id="sendmail" value="Send mail" class="button-create" OnClick="window.location.href='send-mail-ps.php?id_u=<?=$id?>'"><?php }?>-->
								<?php }?>
								<input type="button" value="Close" class="button-create" onclick="window.location.href='ps-feedback.php'">
							</div>
						</td>
					</tr>
					<tr>
						<td style="background: #fff;">
							<div class="large-4 columns">
								<?php 
								if($rs_account['id_department'] != 2){$readonly='readonly';$disabled='disabled';}else{$readonly='';$disabled='';}
								?>
								<table style="border: 0; width: 80%;" cellpadding="0" cellspacing="0" id="tb-req">
									<!--<tr>	
										<td style="width:5%;padding:1% 0.5% 0.5% 0.5%;vertical-align:middle;">Mail to</td>
										<td style="width:20%;padding:1% 0 0 0;vertical-align:middle;"><input type="text" name="mail_to" value="<?php echo $rs['mail_to']?>" <?php echo $readonly?>></td>-->
										<td style="text-align:right;padding:1% 0.5% 0.5% 0.5%;width:70%;vertical-align:middle;">Document No</td>
										<td style="padding:1% 0 0 0;vertical-align:middle;"><input type="text" name="doc_no" value="<?php echo $rs['doc_no']?>" <?php echo $readonly?>></td>
									</tr>
									<tr>
										<!--<td style="width:5%;padding:1% 0.5% 0.5% 0.5%;vertical-align:middle;">CC</td>
										<td style="padding:1% 0 0 0;vertical-align:middle;"><input type="text" name="mail_cc" value="<?php echo $rs['mail_cc']?>" <?php echo $readonly?>></td>-->
										<td style="text-align:right;padding:1% 0.5% 0.5% 0.5%;vertical-align:middle;">Date</td>
										<td style="padding:1% 0 0 0;vertical-align:middle;"><input type="text" name="date_feedback" value="<?php if($rs['date_feedback']){echo $rs['date_feedback'];}else{echo date('d/m/Y');}?>" style="width:50%" <?php echo $readonly?>></td>
									</tr>
									<!--<tr>
										<td>Message</td>
										<td colspan="2"><textarea name="mail_message" style="width:40%;" <?php echo $readonly?>><?php echo $rs['mail_message']?></textarea></td>
									</tr>-->
								</table>								
								<table style="border: 0; width: 100%;" cellpadding="0" cellspacing="0" id="tb-req">
									<tr>										
										<td style="width:15%;"><b>Product name</b></td>
										<td>
											<input type="hidden" name="id_product" id="id_product" value="<?php echo $rs_product['id_product']?>">
											<input type="text" name="product_name" id="product_name" class="product_name" value="<?php if($rs['product_name'] !=''){echo $rs['product_name'];}else{echo $rs_product['product_name'];}?>" style="width:20%;" <?php echo $readonly?>>
										</td>
									</tr>
									<tr>
										<td><b>Project Manager by</b></td>
										<td>
											<?php
											$sql_pm="select * from account where id_account='".$rs['project_manager']."'";
											$res_pm=mysql_query($sql_pm) or die ('Error '.$sql_pm);
											$rs_pm=mysql_fetch_array($res_pm);
											?>
											<input type="hidden" name="id_account2" id="id_account2" value="<?php echo $rs['project_manager']?>">
											<input type="text" name="pm_name" id="pm_name" class="pm_name" value="<?php echo $rs_pm['name']?>" style="width:20%;" <?php echo $readonly?>>
										</td>
									</tr>
									<tr>
										<?php 
										$sql_customer="select * from company where id_company='".$rs['id_customer']."'";
										$res_customer=mysql_query($sql_customer) or die ('Error '.$sql_customer);
										$rs_customer=mysql_fetch_array($res_customer);
										?>
										<td><b>Customer</b></td>
										<td>
											<input type="hidden" name="id_company" id="id_company" value="<?php echo $rs_customer['id_company']?>">
											<input type="text" name="company_name" id="company_name" class="company_name" value="<?php echo $rs_customer['company_name']?>" style="width:20%;" <?php echo $readonly?>>
										</td>
									</tr>									
									<tr>
										<td colspan="2" style="padding:1% 0;"><b>Detail</b></td>
									</tr>
									<?php
									$ps_detail=split(",",$rs['id_ps_detail']);
									$rows_detail=0;
									$j=0;
									$sql_detail="select * from npd_ps_detail";
									$res_detail=mysql_query($sql_detail) or die ('Error '.$sql_detail);
									$max_row=mysql_num_rows($res_detail);
									while($rs_detail=mysql_fetch_array($res_detail)){
									if($rows_detail % 2 ==0){?><tr><?php }
										$j++;
									?>
										<td><input type="checkbox" name="ps_detail[]" value="<?php echo $rs_detail['id_ps_detail']?>" <?php if(in_array($rs_detail['id_ps_detail'],$ps_detail)){echo 'checked="checked"';}?> <?php echo $disabled?>><?php echo $rs_detail['ps_detail']?></td>
										<?php if($j % 2 == 0){ ?></tr><?php } 
										$rows_detail++;
									}//end while type device
									if($max_row==$rows_detail){
									?>
										<td><div style="float:left;"><input type="checkbox" class="checkbox" <?php echo $disabled?>></div><div style="float:left;">Other</div><div style="float:left;margin: 0 0 0 1%;"><input type="text" name="other_detail" <?php echo $readonly?>></div></td>
									<?php }?>									
									<tr>
										<td colspan="2" style="padding:1% 0;"><b>Feedback for Project Manager</b></td>
									</tr>
									<tr>
										<td><input type="radio" name="approve" value="1" <?php if($rs['feedback']==1){echo 'checked';}?> <?php if($rs_account['id_account']==23){echo 'disabled';}?>>Approve</td>
										<td><input type="radio" name="approve" value="2" <?php if($rs['feedback']==2){echo 'checked';}?> <?php if($rs_account['id_account']==23){echo 'disabled';}?>>Improve</td>
									</tr>
									<tr>
										<td colspan="2" style="padding:1% 0;"><b>Remark</b></td>
									</tr>
									<tr>
										<td colspan="2"><textarea name="remark" style="width:40%;" <?php if($rs_account['id_account']==23){echo 'readonly';}?>><?php echo $rs['remark']?></textarea></td>
									</tr>
									<tr>
										<td style="padding:2% 0 0 0 ;">											
											<?php
											if($rs['approve_by'] != 0){	
												$sql_sig="select * from signature where id_account='".$rs['approve_by']."'";
												$res_sig=mysql_query($sql_sig) or die ('Error '.$sql_sig);
												$rs_sig=mysql_fetch_array($res_sig);
												if($rs_sig){$image=$rs_sig['image'];}else{$image='no-signal.png';}
											?> 
											<img src="../img/signature/<?php echo $image?>" style="width: 60%;">
											<?php }else{?>
											<input type="button" name="submit_device" value="ยืนยัน" onclick="window.location.href='dbfeedback.php?id_u=<?php echo $id?>&success=ok&acc=<?php echo $rs_account['id_account']?>'" <?php if($rs_account['id_account']== 23){echo 'disabled';}?>>
											<?php }?><br><br>
											Project Manager<br><br>
											<?php if($rs['approve_date'] !='0000-00-00'){echo $rs['approve_date'];}else{echo date('d/m/Y');}?>
										</td>
										<td style="padding:2% 0 0 0 ;">
											<?php
											if($rs['receive_by'] != 0){	
												$sql_sig2="select * from signature where id_account='".$rs['receive_by']."'";
												$res_sig2=mysql_query($sql_sig2) or die ('Error '.$sql_sig2);
												$rs_sig2=mysql_fetch_array($res_sig2);
												if($rs_sig2){$image2=$rs_sig2['image'];}else{$image2='no-signal.png';}
											?> 
											<img src="../img/signature/<?php echo $image2?>" style="width: 15%;">
											<?php }else{?>
											<input type="button" name="submit_device" value="ยืนยัน" onclick="window.location.href='dbfeedback.php?id_u=<?php echo $id?>&success=ps&acc=<?php echo $rs_account['id_account']?>'" <?php if($rs_account['id_account']!= 23){echo 'disabled';}?>>
											<?php }?><br><br>
											Product Specialist Officer<br><br>
											<?php if($rs['receive_date'] !='0000-00-00'){echo $rs['receive_date'];}else{echo date('d/m/Y');}?>
										</td>
										<td></td>
									</tr>
								</table>
							</div>
						</td>
					</tr>
					<tr>
						<td class="b-top">
							<div class="large-4 columns">		
								<?php if(!is_numeric($id)){?>   
								<input type="button" name="save_data" id="save_data" value="<?php echo $button?>" class="button-create" OnClick="frm.hdnCmd.value='save_data';JavaScript:return fncSubmit();">
								<?php }else{?>
								<input type="button" name="update_data" id="update_data" value="<?php echo $button?>" class="button-create" OnClick="frm.hdnCmd.value='update_data';JavaScript:return fncSubmit();">
								<!--<?php if($rs['approve_by'] == 0){?><input type="button" name="sendmail" id="sendmail" value="Send mail" class="button-create" OnClick="window.location.href='send-mail-ps.php?id_u=<?=$id?>'"><?php }?>-->
								<?php }?>
								<input type="button" value="Close" class="button-create" onclick="window.location.href='ps-feedback.php'">
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
