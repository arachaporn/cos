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
			if($_GET["id_u"]=='new'){
				$mode='New';
				$button='Save';
				$id='New';
			}
			else{
				$id=$_GET["id_u"];
				$sql="select * from sd_device where id_sd_device='".$id."'";
				$sql .=" order by id_sd_device asc";
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
			<form method="post" name="frm" action="dbcheck_computer.php">	
				<table style="border: 0; width: 100%;" cellpadding="0" cellspacing="0">
				<tr>
					<td class="b-bottom"><div class="large-4 columns"><h4>บันทึกการตรวจเช็คคอมพิวเตอร์และอุปกรณ์ต่อพ่วง >> <?php echo $mode;?></h4></div></td>
				</tr>
				<tr>
					<td class="b-bottom">
						<div class="large-4 columns">
							<input type="button" value="<?php echo $button?>" class="button-create" onClick="JavaScript:return fncSubmit();">
							<input type="button" value="Close" class="button-create" onclick="window.location.href='check-computer.php'">
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
											บันทึกการตรวจเช็คคอมพิวเตอร์ และอุปกรณ์ต่อพ่วง
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td class="top" colspan="4" style="text-align:right;">รหัสเครื่อง</td>
									<td class="top"><input type="text" name="device_code" id="device_code" class="device_code" value="<?php echo $rs['device_code']?>"></td>
								</tr>
								<tr>
									<td colspan="5">การตรวจรับ/ตรวจสอบ</td>
								</tr>
								<tr>
									<td class="top"><input type="radio" name="check_device" <?php if($rs['check_device']==1){echo 'checked';}?> value="1">ตรวจรับครั้งแรก</td>
									<td class="top"><input type="radio" name="check_device" <?php if($rs['check_device']==2){echo 'checked';}?> value="2">ตรวจตามแผนประจำปี</td>
									<td class="top">กำหนดตรวจสอบครั้งต่อไป</td>
									<td class="top">
										<?if($rs_account['id_department']==6){?>
										<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
										<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
										<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
										<script>
											$(function() {
												$( "#datepicker" ).datepicker({
													showOn: "button",
													buttonImage: "img/calendar.gif",
													buttonImageOnly: true
												});
											});
										</script>
										<input type="text" id="datepicker" name="check_next_date" value="<?php 
											if(is_numeric($id)){
												list($ckyear,$ckmonth,$ckday) = split('[/.-]', $rs['check_next_date']); 
												echo $ckstart= $ckmonth . "/". $ckday . "/" .$ckyear;
											}else{echo date("m/d/Y");}?>" style="width: 50%; float: left; margin-right: 2%;"/>
										<?php }else{ 
											list($ckyear,$ckmonth,$ckday) = split('[/.-]', $rs['check_next_date']); 
											echo $ckstart= $ckday . "/". $ckmonth . "/" .$ckyear;
										}?>
									</td>
								</tr>
								<tr>
									<td colspan="5">ประเภทของอุปกรณ์</td>
								</tr>
								<tr>
									<?php
									$rows = 0;
									$j=0;
									$sql_device="select * from type_device";
									$res_device=mysql_query($sql_device) or die ('Error '.$sql_device);
									$max_row=mysql_num_rows($res_device);
									while($rs_device=mysql_fetch_array($res_device)){
										if($rows % 4 ==0){?><tr><?php }
										$j++;
									?>
									<td class="top"><input type="radio" name="id_type_device" <?php if($rs['id_type_device']==$rs_device['id_type_device']){echo 'checked';}?> value="<?php echo $rs_device['id_type_device']?>"><?php echo $rs_device['title_device']?></td>
									<?php if($j % 4 == 0){ ?></tr><?php } 
										$rows++;
									}//end while type device
									?>								
									<td class="top"><div style="float:left; margin: 0 0.5% 0 0;"><input type="radio" name="id_type_device" value="-1" <?php if($rs['id_type_device']== -1){echo 'checked';}?> ></div><div style="float:left; margin: 0 0.5% 0 0;">อื่น ๆ</div><div style="float:left"><input type="text" name="other_device" value="<?php echo $rs['other_device']?>"></div></td>
								</tr>
								<tr>
									<?php
										if(is_numeric($id)){
											$sql_employee="select * from account where id_employee='".$rs['id_employee']."'";
											$res_employee=mysql_query($sql_employee) or die ('Error '.$sql_employee);
											$rs_employee=mysql_fetch_array($res_employee);
											$department=$rs_employee['id_department'];
										}
									?>
									<td>รหัสพนักงาน</td>
									<td><input type="text" id="employee_code" class="employee_code" name="employee_code" value="<?php if($rs_employee['id_employee']==0){echo '';}else{echo $rs_employee['id_employee'];}?>"></td>
									<td>ผู้ใช้งาน</td>
									<td><input type="text" id="name" name="name" value="<?php if($rs_employee['id_employee']==0){echo $rs['user_use'];}else{echo$rs_employee['name'];}?>"></td>									
									<td>ฝ่าย/แผนก</td>
									<td>
									<?php
										$sql_department="select * from department where id_department='".$department."'";
										$res_department=mysql_query($sql_department) or die ('Error '.$sql_department);
										$rs_department=mysql_fetch_array($res_department);
									?>
									<input type="text" id="department" name="department" value="<?php  if($rs_employee['id_employee']==0){echo '';}else{echo $rs_department['title'];}?>"></td>
								</tr>
								<tr>
									<td colspan="5">
										<table cellpadding="0" cellspacing="0" id="tb-ck-com">
											<tr>
												<td class="center w5">ลำดับที่</td>
												<td class="center w20">รายการ</td>
												<td class="center w30">บันทึกผล</td>
												<td class="center">โปรแกรมพื้นฐาน</td>
											</tr>
											<?php
											$sql_program="select * from sd_program where id_sd_device='".$rs['id_sd_device']."'";
											$res_program=mysql_query($sql_program) or die ('Error '.$sql_program);
											$rs_program=mysql_fetch_array($res_program);

											$default_program=split(",",$rs_program['default_program']);
											?>
											<input type="hidden" name="id_sd_program" value="<?php echo $rs_program['id_sd_program']?>">
											<tr>
												<td class="center">1</td>
												<td>Operating System</td>
												<td><input type="text" name="os" value="<?php echo $rs_program['os']?>"></td>
												<td><input type="checkbox" name="program[]" <?php if(in_array('ms',$default_program)){echo 'checked';}?> value="ms">Microsoft Office</td>
												<td><input type="checkbox" name="program[]" <?php if(in_array('acrobat',$default_program)){echo 'checked';}?> value="acrobat" >Adobe Acrobat X Pro</td>
											</tr>
											<tr>
												<td class="center">2</td>
												<td>CPU</td>
												<td><input type="text" name="cpu" value="<?php echo $rs_program['cpu']?>"></td>
												<td><input type="checkbox" name="program[]" <?php if(in_array('acd',$default_program)){echo 'checked';}?> value="acd">ACD See</td>
												<td><input type="checkbox" name="program[]" <?php if(in_array('anti_virus',$default_program)){echo 'checked';}?> value="anti_virus">Anti Virus</td>
											</tr>
											<tr>
												<td class="center">3</td>
												<td>Mainboard</td>
												<td><input type="text" name="mainboard" value="<?php echo $rs_program['mainboard']?>"></td>
												<td><input type="checkbox" name="program[]" <?php if(in_array('firefox',$default_program)){echo 'checked';}?> value="firefox">Firefox</td>
												<td><input type="checkbox" name="program[]" <?php if(in_array('chorme',$default_program)){echo 'checked';}?> value="chorme">Google chrome</td>
											</tr>
											<tr>
												<td class="center">4</td>
												<td>RAM</td>
												<td><input type="text" name="ram" value="<?php echo $rs_program['ram']?>"></td>
												<td><input type="checkbox" name="program[]" <?php if(in_array('winrar',$default_program)){echo 'checked';}?> value="winrar">Winrar</td>
												<td><input type="checkbox" name="program[]" <?php if(in_array('vnc',$default_program)){echo 'checked';}?> value="vnc">VNC</td>
											</tr>
											<tr>
												<td class="center">5</td>
												<td>HDD</td>
												<td><input type="text" name="hdd" value="<?php echo $rs_program['hdd']?>"></td>
												<td><input type="checkbox" name="program[]" <?php if(in_array('advance_care',$default_program)){echo 'checked';}?> value="advance_care">Advance System Care</td>
												<td><input type="checkbox" name="program[]" <?php if(in_array('lan_message',$default_program)){echo 'checked';}?> value="lan_message">Lan Message</td>
											</tr>
											<tr>
												<td class="center">6</td>
												<td>VGA</td>
												<td><input type="text" name="vga" value="<?php echo $rs_program['vga']?>"></td>
												<td><input type="checkbox" name="program[]" <?php if(in_array('reader',$default_program)){echo 'checked';}?> value="reader">Adobe Reader</td>
												<td></td>
											</tr>
											<tr>
												<td class="center">7</td>
												<td>Monitor</td>
												<td><input type="text" name="monitor" value="<?php echo $rs_program['monitor']?>"></td>
												<td colspan="2"></td>
											</tr>
											<tr>
												<td class="center">8</td>
												<td>ยี่ห้อ/รุ่น (ยกเว้น Personal Computer)</td>
												<td><input type="text" name="model" value="<?php if($rs_program['model']==''){echo '-';}else{echo $rs_program['model'];}?>"></td>
												<td colspan="2"></td>
											</tr>											
										</table>
									</td>
								</tr>
								<tr>
									<td>โปรแกรมพิเศษที่ร้องขอ</td>
									<td colspan="3"><textarea style="width:50%; height:100px;" name="special_program"><?php echo $rs_program['special_program']?></textarea></td>
								</tr>
								<tr>
									<td>หมายเหตุ</td>
									<td colspan="3"><textarea style="width:50%; height:100px;" name="remark"><?php echo $rs_program['remark']?></textarea></td>
								</tr>
								<tr>
									<td>ผู้ตรวจเช็ค</td>
									<td>
										<?php if($_REQUEST['submit_device']=='ok'){?>
										<img src="img/signature/sm_oem.png" style="width: 15%;">
										<input type="hidden" name="status" value="1">
										<?php }else{
											if($rs['status']==1){	
										?>
												<img src="img/signature/sm_oem.png" style="width: 15%;">
											<?php }else{?>
												<input type="button" name="submit_device" value="กดยืนยัน" onclick="window.location.href='dbcheck_computer.php?id_u=<?php echo $id?>&submit_device=ok'">
											<?php }
										}?>
									</td>
									<td>ผู้ใช้งาน</td>
									<td>
										<?php if($_REQUEST['submit_device']=='ok'){?>
										<img src="img/signature/sm_oem.png" style="width: 15%;">
										<input type="hidden" name="status" value="1">
										<?php }else{
											if($rs['status']==1){	
										?>
												<img src="../img/signature/sm_oem.png" style="width: 15%;">
											<?php }else{?>
												<input type="button" name="submit_device" value="กดยืนยัน" onclick="window.location.href='dbcheck_computer.php?id_u=<?php echo $id?>&submit_device=ok'">
											<?php }
										}?>
									</td>
								</tr>
								<tr>
									<td>วันที่</td>
									<td><?php echo date("d/m/Y")?></td>
									<td>วันที่</td>
									<td><?php echo date("d/m/Y")?></td>
								</tr>
							</table>
						</div>
					</td>
				</tr>
				<tr>
					<td class="b-top">
						<div class="large-4 columns">
							<input type="button" value="<?php echo $button?>" class="button-create" onClick="JavaScript:return fncSubmit();">
							<input type="button" value="Close" class="button-create" onclick="window.location.href='check-computer.php'">
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