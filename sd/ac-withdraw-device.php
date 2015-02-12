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
			include("connect/connect.php");
			if($_GET["id_u"]=='New'){
				$mode='New';
				$button='Save';
				$id='New';
			}
			else{
				$id=$_GET["id_u"];
				$sql="select * from sd_withdraw where id_sd_withdraw='".$id."'";
				$res=mysql_query($sql) or die ('Error '.$sql);
				$rs=mysql_fetch_array($res);
				$mode='Edit '.$rs['title_call_report'];
				$button='Update';
			}
			?> 
			<script type="text/javascript" src="js/js-autocomplete/js/jquery-1.4.2.min.js"></script> 
			<script type="text/javascript" src="js/js-autocomplete/js/jquery-ui-1.8.2.custom.min.js"></script> 
			<script type="text/javascript"> 
				jQuery(document).ready(function(){
					$('.employee_code').autocomplete({
						source:'returnUser.php', 
						//minLength:2,
						select:function(evt, ui)
						{
							// when a zipcode is selected, populate related fields in this form
							this.form.name.value = ui.item.name;
							this.form.department.value = ui.item.department;
						}
					});
				});
				jQuery(document).ready(function(){
					$('.device_code').autocomplete({
						source:'return-device-code.php', 
						//minLength:2,
					});
				});
			</script> 
			<link rel="stylesheet" href="js/js-autocomplete/css/smoothness/jquery-ui-1.8.2.custom.css" /> 
			<form method="post" name="frm" action="dbwithdraw-device.php">	
				<table style="border: 0; width: 100%;" cellpadding="0" cellspacing="0">
				<tr>
					<td class="b-bottom"><div class="large-4 columns"><h4>บันทึกการเบิกอุปกรณ์อิเล็กทรอนิกส์ >> <?php echo $mode;?></h4></div></td>
				</tr>
				<tr>
					<td class="b-bottom">
						<div class="large-4 columns">
							<input type="button" value="<?php echo $button?>" class="button-create" onClick="JavaScript:return fncSubmit();">
							<input type="button" value="Close" class="button-create" onclick="window.location.href='withdraw-device.php'">
						</div>
					</td>
				</tr>
				<tr>
					<td style="background: #fff;">
						<div class="large-4 columns">
							<input type="hidden" name="mode" value="<?php echo $id?>">
							<table style="border: 0; width: 100%;" cellpadding="0" cellspacing="0" id="tb-req">
								<tr>
									<td class="b-bottom center" colspan="5">
										<div class="tb-h">
											<img src="img/logo.png" width="140" class="img-logo"><br>
											<div class="header-text">บริษัท ซีดีไอพี (ประเทศไทย) จำกัด<br>
											CDIP (Thailand) Co.,Ltd.<br>
											บันทึกการเบิกอุปกรณ์อิเล็กทรอนิกส์
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td class="top">
										<?php if( ($_GET['get_device']==1)){
											if($rs_account['id_department'] == 6){
												if($rs_account['role_user'] !=3){
										?>
												<fieldset>
													<legend>Device Record</legend>
													<input type="hidden" name="withdraw" value="1">
													<table style="border: none; width: 100%;" cellpadding="0" cellspacing="0">
														<tr>
															<td class="top">Date</td>
															<td colspan="3">
															<?php
															list($ckyear,$ckmonth,$ckday) = split('[/.-]', $rs['recip_date']); 
															$date_get= $ckday . "/". $ckmonth . "/" .$ckyear;
															?>
															<input type="text" name="get_date" value="<?php if(is_numeric($id)){echo $date_get;}else{echo date('d/m/Y');}?>" style="width: 20%;"></td>
														</tr>
														<tr>
															<td class="top">Device</td>
															<td colspan="3">
																<?php 
																$sql_other_device="select * from type_other_device";
																$res_other_device=mysql_query($sql_other_device) or die ('Error '.$sql_other_device);
																while($rs_other_device=mysql_fetch_array($res_other_device)){
																?>
																	<input type="radio" name="get_device" <?php if($rs['id_type_other_device']==$rs_other_device['id_type_other_device']){echo 'checked';}?> value="<?php echo $rs_other_device['id_type_other_device']?>"><?php echo $rs_other_device['type_other_device']?>
																<?php }?>
															</td>
														</tr>
														<tr>
															<td width="5%" class="top">Quantity</td>
															<td width="20%"><input type="text" name="device_quantity" value="<?php echo $rs['device_quantity']?>" style="width: 95%;"></td>
															<td width="5%" class="top">Unit</td>
															<td><input type="text" name="device_unit" value="<?php echo $rs['device_unit']?>" style="width: 10%;"></td>
														</tr>
														<tr>
															<td class="top">ผู้รับของ</td>
															<td colspan="3">
																<input type="hidden" name="id_account" value="<?php echo $rs_account['id_account']?>">
																<input type="text" name="account_recip" value="<?php echo $rs_account['name']?>" style="width: 20%;">
															</td>
														</tr>
													</table>
												</fieldset>	
											<?php }}}else{?>
											<fieldset>
												<?php 
												$sql_withdraw="select * from sd_withdraw_account where id_withdraw_account='".$id."'";
												$res_withdraw=mysql_query($sql_withdraw) or die ('Error '.$sql_withdraw);
												$rs_withdraw=mysql_fetch_array($res_withdraw);
												?>
												<legend>Withdraw Device</legend>
												<input type="hidden" name="withdraw" value="2">
												<table style="border: none; width: 100%;" cellpadding="0" cellspacing="0">
													<tr>
														<td class="top">Date</td>
														<td colspan="3">
														<?php
															list($ckyear,$ckmonth,$ckday) = split('[/.-]', $rs_withdraw['date_withdraw']); 
															$date_get2= $ckday . "/". $ckmonth . "/" .$ckyear;
														?>
														<input type="text" name="get_date2" value="<?php if(is_numeric($id)){echo $date_get2;}else{echo date('d/m/Y');}?>" style="width: 20%;"></td>
													</tr>
													<tr>
														<td class="top">Device</td>
														<td colspan="3">
															<?php 
															$sql_other_device2="select * from type_other_device";
															$res_other_device2=mysql_query($sql_other_device2) or die ('Error '.$sql_other_device2);
															while($rs_other_device2=mysql_fetch_array($res_other_device2)){
															?>
																<input type="radio" name="get_device2" <?php if($rs_withdraw['id_type_other_device']==$rs_other_device2['id_type_other_device']){echo 'checked';}?> value="<?php echo $rs_other_device2['id_type_other_device']?>"><?php echo $rs_other_device2['type_other_device']?>
															<?php }?>
														</td>
													</tr>
													<tr>
														<td width="3%" class="top">Quantity</td>
														<td width="20%"><input type="text" name="quantity" value="<?php echo $rs_withdraw['quantity']?>" style="width: 20%;"></td>
													</tr>
													<tr>
														<td class="top">Description</td>
														<td><textarea name="description" style="width: 30%;"><?php echo $rs_withdraw['description']?></textarea></td>
													</tr>
													<tr>
														<td class="top">ผู้เบิก</td>
														<td>
															<?php
															$sql_emp="select * from account where id_account='".$rs_withdraw['id_account_withdraw']."'";
															$res_emp=mysql_query($sql_emp) or die ('Error '.$sql_emp);
															$rs_emp=mysql_fetch_array($res_emp);
															?>
															<input type="hidden" name="id_account2" value="<?php if(is_numeric($id)){echo $rs_emp['id_account'];}else{echo $rs_account['id_account'];  }?>">
															<input type="text" name="account_recip2" value="<?php if(is_numeric($id)){echo $rs_emp['name'];}else{echo $rs_account['name'];}?>" style="width: 20%;">
														</td>
													</tr>
												</table>
											</fieldset>
											<?php }?>
										</td>
									</tr>								
								</table>
							</div>
						</td>
					</tr>
					<tr>
						<td class="b-top">
							<div class="large-4 columns">
								<input type="button" value="<?php echo $button?>" class="button-create" onClick="JavaScript:return fncSubmit();">
							<input type="button" value="Close" class="button-create" onclick="window.location.href='withdraw-device.php'">
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
  <script>
    $(document).foundation();
  </script>
</body>
</html>