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
	if(document.frm.id_type_device.value == "")
	{
		alert('กรุณาเลือกอุปกรณ์');
		document.frm.id_type_device.focus();
		return false;
	}	
	else
	if(document.frm.device_code.value == "")
	{
		alert('กรุณาเลือกรหัสอุปกรณ์');
		document.frm.device_code.focus();
		return false;
	}	
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
				$sql="select * from sd_breakdown where id_break_down='".$id."'";
				$res=mysql_query($sql) or die('Error '.$sql);
				$rs=mysql_fetch_array($res);
				$mode='Edit ';
				$button='Update';
			}
			?>			
			<form method="post" name="frm" action="dbbreakdown.php">
			<table style="border: 0; width: 100%;" cellpadding="0" cellspacing="0">
				<tr>
					<td class="b-bottom"><div class="large-4 columns"><h4>ใบแจ้งซ่อม/บำรุงรักษา >> <?php echo $mode;?></h4></div></td>
				</tr>
				<tr>
					<td class="b-bottom">
						<div class="large-4 columns">
							<?php if(!is_numeric($id)){?><input type="button" value="<?php echo $button?>" class="button-create" onClick="JavaScript:return fncSubmit();"><?php }?>
							<input type="button" value="Close" class="button-create" onclick="window.location.href='breakdown.php'">
						</div>
					</td>
				</tr>
				<tr>
					<td style="background: #fff;">
						<div class="large-4 columns">	
							<input type="hidden" name="mode" value="<?php echo $id?>">
							<table style="border: 0; width: 100%;" cellpadding="0" cellspacing="0" id="tb-req">
								<tr>
									<td class="b-bottom center" colspan="4">
										<div class="tb-h">
											<img src="img/logo.png" width="140" class="img-logo"><br>
											<div class="header-text">บริษัท ซีดีไอพี (ประเทศไทย) จำกัด<br>
											CDIP (Thailand) Co.,Ltd.<br>
											ใบแจ้งซ่อม/บำรุงรักษา
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td class="top" colspan="2"><h5>ส่วนที่ 1 : ผู้แจ้งซ่อม</h5></td>
									<td class="top">เลขที่ใบแจ้งซ่อม</td>
									<td class="top">
										<?php 
										if(is_numeric($id)){ echo $rs['break_down_code'];}
										else{
										$month=date("m")?>
										<?php echo 'F';
											/*if($rs_account['id_account']<10){echo '0'.$rs_account['id_account'];}
											else{echo $rs_account['id_account'];}*/
											echo date("y").date("m");
											$sql_break_down="select * from sd_breakdown order by id_break_down desc";
											$res_break_down=mysql_query($sql_break_down) or die ('Error '.$sql_break_down);
											$rs_break_down=mysql_fetch_array($res_break_down);
												if($month==$rs_break_down['break_down_month']){
													$num = $rs_break_down['break_down_num']+1;
												}else{$num=1;}												
											echo sprintf("%03d",$num);
											echo $numf;
										?>
										<input type="hidden" name="break_down_code" value="<?php echo 'F';
											/*if($rs_account['id_account']<10){echo '0'.$rs_account['id_account'];}
											else{echo $rs_account['id_account'];}*/
											echo date("y").date("m");
											$sql_break_down="select * from sd_breakdown order by id_break_down desc";
											$res_break_down=mysql_query($sql_break_down) or die ('Error '.$sql_break_down);
											$rs_break_down=mysql_fetch_array($res_break_down);
												if($month==$rs_break_down['break_down_month']){
													$num = $rs_break_down['break_down_num']+1;
												}else{$num=1;}												
											echo sprintf("%03d",$num);
											echo $numf;;?>">										
										<input type="hidden" name="break_down_month" value="<?php if($month==$rs_break_down['break_down_month']){echo $rs_break_down['break_down_month'];}else{ echo date("m");}?>">
										<input type="hidden" name="break_down_num" value="<?php if($month==$rs_break_down['break_down_month']){echo $num;}else{echo $num=1;}?>">
									<?php }?>
									</td>
								</tr>
								<tr>
									<td class="top">วันที่ :<?php if(is_numeric($id)){
										list($ckyear,$ckmonth,$ckday) = split('[/.-]', $rs['break_down_date']); 
										echo $ckstart= $ckday . "/". $ckmonth . "/" .$ckyear;
										}else{echo date("d/m/Y");}?><input type="hidden" name="break_down_date" value="<?php if(is_numeric($id)){echo $rs['break_down_date'];}else{echo date("Y-m-d");}?>"></td>
									<td class="top" colspan="2">เวลา : <?php if(is_numeric($id)){echo $rs['break_down_time'];}else{echo date("H:i");}?><input type="hidden" name="break_down_time" value="<?php if(is_numeric($id)){echo $rs['break_down_time'];}else{echo date("H:i");}?>"></td>
								</tr>
								<tr>
									<td class="top">ผู้แจ้ง : 
									<?php 
									$sql_account2="select * from account where id_account='".$rs['id_account']."'";
									$res_account2=mysql_query($sql_account2) or die('Error '.$sql_account2);
									$rs_account2=mysql_fetch_array($res_account2);
									if(is_numeric($id)){echo $rs_account2['name'];}else{echo $rs_account['name'];}?>
									<input type="hidden" name="id_account" value="<?php if(is_numeric($id)){echo $rs_account2['id_account'];}else{echo $rs_account['id_account'];}?>"></td>
									<td class="top" colspan="2">ฝ่าย :
									<?php
										if(is_numeric($id)){
											$sql_department="select * from department where id_department='".$rs_account2['id_department']."'";
											$res_department=mysql_query($sql_department) or die ('Error '.$sql_department);
											$rs_department=mysql_fetch_array($res_department);
											echo $department=$rs_department['title'];
										}else{
											$sql_department="select * from department where id_department='".$rs_account2['id_department']."'";
											$res_department=mysql_query($sql_department) or die('Error '.$sql_department);
											$rs_department=mysql_fetch_array($res_department);
											echo $department=$rs_department['title'];
										}
									?>
									<input type="hidden" name="department" value="<?php echo $department?>">
									</td>
								</tr>
								<tr>
									<td class="top" style="width:8%;">อุปกรณ์ <span style="color:red;">*</span>:</td>
									<td class="top" style="width:20%;">
										<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
										<script src="js/jquery.chained.min.js"></script>
										<select name="id_type_device" id="type_device" style="width:auto;">
											<?php
											$sql_device="select * from type_device";
											$res_device=mysql_query($sql_device) or die ('Error '.$sql_device);
											while($rs_device=mysql_fetch_array($res_device)){
											?>
											<option <?php if($rs['id_type_device']==$rs_device['id_type_device']){echo 'selected';}?> value="<?php echo $rs_device['id_type_device']?>"><?php echo $rs_device['title_device']?></option>
											<?php } ?>
									</td>
									<td class="top" style="width:8%;">รหัสอุปกรณ์ <span style="color:red;">*</span>:</td>
									<td class="top" style="width:50%;">
										<select name="device_code" id="device_code" style="width:auto;">
											<option value="">เลือกรหัสอุปกรณ์</option>
											<?php
											if(is_numeric($id)){$where=" ";}else{$where=" ";}
											$sql_sd_device="select * from sd_device";
											$res_sd_device=mysql_query($sql_sd_device) or die ('Error '.$sql_sd_device);
											while($rs_sd_device=mysql_fetch_array($res_sd_device)){
											?>
											<option <?php if(is_numeric($id)){if($rs['device_code']==$rs_sd_device['device_code']){echo 'selected';}}else{if($rs_sd_device['id_employee']==$rs_account['id_employee']){echo 'selected';}}?> value="<?php echo $rs_sd_device['device_code']?>" class="<?php echo $rs_sd_device['id_type_device']?>"><?php echo $rs_sd_device['device_code']?></option>
											<?php } ?>
									</td>
									<script>
									$("#device_code").chained("#type_device"); /* or $("#series").chainedTo("#mark"); */
								</script>
								</tr>
								<tr>
									<td class="top b-bottom">ปัญหา/อาการเสีย :</td>
									<td class="top b-bottom" colspan="2"><textarea name="problem" style="height:150px;"><?php echo $rs['problem']?></textarea></td>
									<td class="top b-bottom"></td>
								</tr>
								<?php 
								if(is_numeric($id)){
								?>
								<tr>
									<td class="top" colspan="4"><h5>ส่วนที่ 2 : เจ้าหน้าที่คอมพิวเตอร์</h5></td>
								</tr>
								<tr>
									<td class="top"><input type="radio" name="type_repair" <?php if($rs['type_repair']==1){echo 'checked';} ?> value="1" <?php if($rs_account['id_department']==6){echo '';}else{echo 'disabled';}?>>ซ่อมภายใน</td>
									<td class="top"><input type="radio" name="type_repair" <?php if($rs['type_repair']==2){echo 'checked';}?> value="2" <?php if($rs_account['id_department']==6){echo '';}else{echo 'disabled';}?>>ส่งบริษัทภายนอก (ระบุ)</td>
									<?php if(($rs_account['id_department']==6) && (($rs_account['role_user']==1) || ($rs_account['role_user']==2))){?>
									<td>
									<input type="button" value="<?php echo $button?>" class="btn-save"  onClick="JavaScript:return fncSubmit();">
									<a href="send-mail-breakdown.php?break=<?php echo $id?>&device_code=<?php echo $rs_account2['id_account']?>&type=<?php echo $rs['device_code'] ?>&status=1"><img src="img/send-mail.png" title="Send e-amil"></a></td>
									<?php }?>
								</tr>
								<tr>
									<td class="top" colspan="2"><textarea name="repair_description" <?if($rs_account['id_department']==6){echo '';}else{echo 'readonly';}?>><?php echo $rs['repair_description']?></textarea></td>
								</tr>
								<tr>
									<td class="top b-bottom" colspan="2" style="padding-bottom:1%;">พนักงานซ่อม : <?php if($rs_account['id_department']==6){echo $rs_account['name'];}
									else{
										$sql_account2="select * from account where id_account='".$rs['id_account_repair']."'";
										$res_account2=mysql_query($sql_account2) or die ('Error '.$sql_account2);
										$rs_account2=mysql_fetch_array($res_account2);
										echo $rs_account2['name'];
									}?>
									<input type="hidden" name="id_account_repair" value="<?php if($rs_account['id_department']==6){echo $rs_account['id_account'];}?>"></td>
									<td class="top b-bottom" style="padding-bottom:1%;">กำหนดแล้วเสร็จ :	</td>
									<td class="top b-bottom" style="padding-bottom:1%;">
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
										<input type="text" id="datepicker" name="timeline" value="<?php 
											if(is_numeric($id)){
												list($ckyear,$ckmonth,$ckday) = split('[/.-]', $rs['timeline']); 
												echo $ckstart= $ckmonth . "/". $ckday . "/" .$ckyear;}
											else{echo date("m/d/Y");}?>" style="width: 50%; float: left; margin-right: 2%;"/>
										<?php }else{ 
											list($ckyear,$ckmonth,$ckday) = split('[/.-]', $rs['timeline']); 
											echo $ckstart= $ckday . "/". $ckmonth . "/" .$ckyear;
										}?>
									</td>									
								</tr>
								<tr>
									<td class="top" colspan="4"><h5>ส่วนที่ 3 : ส่งงาน</h5></td>
								</tr>
								<tr>
									<td class="top">วันที่ :</td>
									<td class="top">
										<?php if($rs_account['id_department']==6){?>
										<script>
											$(function() {
												$( "#datepicker2" ).datepicker({
													showOn: "button",
													buttonImage: "img/calendar.gif",
													buttonImageOnly: true
												});
											});
										</script>																				
										<input type="text" id="datepicker2" name="complete_date" value="<?php 
											if(is_numeric($id)){
												list($ckyear2,$ckmonth2,$ckday2) = split('[/.-]', $rs['complete_date']); 
												echo $ckstart2= $ckmonth2 . "/". $ckday2 . "/" .$ckyear2;
											}else{echo date("m/d/Y");}?>" style="width: 50%; float: left; margin-right: 2%;"/>
										<?php }else{ 
											list($ckyear2,$ckmonth2,$ckday2) = split('[/.-]', $rs['complete_date']); 
											echo $ckstart2= $ckday2 . "/". $ckmonth2 . "/" .$ckyear2;
										}?>
									</td>
									<td class="top">เวลา : 
									<?php if(is_numeric($id)){echo $rs['complete_time'];}else{echo '-';}?>
									<input type="hidden" name="complete_time" value="<?php if(is_numeric($id)){ echo $rs['complete_time'];}else{echo date("H:i");}?>"></td>
									<?php if(($rs_account['id_department']==6) && (($rs_account['role_user']==1) || ($rs_account['role_user']==2))){?>
									<td>
									<input type="button" value="<?php echo $button?>" class="btn-save"  onClick="JavaScript:return fncSubmit();">
									<a href="send-mail-breakdown.php?break=<?php echo $id?>&device_code=<?php echo $rs_account2['id_account']?>&type=<?php echo $rs['device_code'] ?>&status=2"><img src="img/send-mail.png" title="Send e-amil"></a></td>
									<?php }?>
								</tr>
								<tr>
									<td class="top">ข้อสรุปผลการซ่อม</td>
									<td class="top"><textarea name="result" <?php if($rs_account['id_department']==6){ echo '';}else{echo 'readonly';}?>><?php echo $rs['result']?></textarea></td>
								</tr>
								<tr>
									<td class="top" colspan="2">พนักงานซ่อม :  <?php if($rs_account['id_department']==6){echo $rs_account['name'];}
									else{echo $rs_account2['name'];}?>
									</td>
									<td class="top" colspan="2">ผู้รับเครื่อง :
									<?php if($_REQUEST['submit_device']=='ok'){
										$sql_sig="select * from signature where id_account='".$rs_account['id_account']."'";
										$res_sig=mysql_query($sql_sig) or die ('Error '.$sql_sig);
										$rs_sig=mysql_fetch_array($res_sig);
										if($rs_sig){$image=$rs_sig['image'];}else{$image='no-signal.png';}
									?>
									<img src="img/signature/<?php echo $image?>" style="width: 15%;">
									<input type="hidden" name="status" value="1">
									<?php }else{
										if($rs['status']==1){	
											$sql_sig="select * from signature where id_account='".$rs['id_account']."'";
											$res_sig=mysql_query($sql_sig) or die ('Error '.$sql_sig);
											$rs_sig=mysql_fetch_array($res_sig);
											if($rs_sig){$image=$rs_sig['image'];}else{$image='no-signal.png';}
									?> 
										<img src="../img/signature/<?php echo $image?>" style="width: 15%;">
										<?php }else{?>
										<input type="button" name="submit_device" value="กดยืนยันเพื่อรับอุปกรณ์" onclick="window.location.href='dbbreakdown.php?id_u=<?php echo $id?>&submit_device=ok'">
										<?php }}?>
									</td>
								</tr>
								<?php 
								}?>
							</table>
						</div>
					</td>
				</tr>
				<tr>
					<td class="b-top">
						<div class="large-4 columns">
							<?php if(!is_numeric($id)){?><input type="button" value="<?php echo $button?>" class="button-create" onClick="JavaScript:return fncSubmit();"><?php }?>
							<input type="button" value="Close" class="button-create" onclick="window.location.href='breakdown.php'">
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
