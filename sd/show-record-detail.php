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
<link rel="stylesheet" href="css/foundation.css">
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="rmm-css/responsivemobilemenu.css" type="text/css"/>
<script type="text/javascript" src="http://code.jquery.com/jquery.min.js"></script>
<script type="text/javascript" src="rmm-js/responsivemobilemenu.js"></script>
<script src="js/vendor/custom.modernizr.js"></script>

</head>
<body>
	<?php
	$account=$_GET['account'];
	$sql="select * from sd_device where id_type_device='".$_GET['device']."'";
	$sql .=" and device_code='".$_GET['code']."' and id_employee='".$_GET['account']."'";
	$res=mysql_query($sql) or die('Error '.$sql);
	$rs=mysql_fetch_array($res);
	?>
	<div class="row">
		<div class="background">
			<?php
			$sql_device="select * from type_device where id_type_device='".$_GET['device']."'";
			$res_device=mysql_query($sql_device) or die ('Error '.$sql_device);
			$rs_device=mysql_fetch_array($res_device);
					
			$sql_employee="select * from account where id_employee='".$rs['id_employee']."'";
			$res_employee=mysql_query($sql_employee) or die ('Error '.$sql_employee);
			$rs_employee=mysql_fetch_array($res_employee);
			$department=$rs_employee['id_department'];

			$sql_department="select * from department where id_department='".$department."'";
			$res_department=mysql_query($sql_department) or die ('Error '.$sql_department);
			$rs_department=mysql_fetch_array($res_department);

			$sql_position="select * from  positions where id_position='".$rs_employee['id_position']."'";
			$res_position=mysql_query($sql_position) or die ('Error '.$sql_position);
			$rs_position=mysql_fetch_array($res_position);

			$sql_program="select * from sd_program where id_sd_device='".$rs['id_sd_device']."'";
			$res_program=mysql_query($sql_program) or die ('Error '.$sql_program);
			$rs_program=mysql_fetch_array($res_program);

			$sql_break="select count(id_break_down) as sum_break from sd_breakdown";
			//$sql_break .=" where id_account='".$rs_employee['id_account']."'";
			$sql_break .=" where device_code='".$rs['device_code']."'";
			$res_break=mysql_query($sql_break) or die ('Error '.$sql_break);
			$rs_break=mysql_fetch_array($res_break);
			?>
			<table style="border: 0; width: 100%;" cellpadding="0" cellspacing="0">
				<tr>
					<td class="b-bottom" colspan="4"><div class="large-4 columns"><h4>ประวัติอุปกรณ์ <?php echo $rs_device['title_device']?></h4></div></td>
				</tr>
				<tr>
					<td class="title" width="15%" style="vertical-align:top;">ผู้รับผิดชอบ</td>
					<td width="15%" style="vertical-align:top;"><?php echo $rs_employee['name']?></td>
					<td class="title" width="15%" style="vertical-align:top;">ฝ่าย/แผนก</td>
					<td style="vertical-align:top;"><?php echo $rs_department['title'].'/'.$rs_position['title']?></td>
				</tr>
				<tr>
					<td class="title" style="vertical-align:top;">รหัสเครื่อง</td>
					<td style="vertical-align:top;"><?php echo $rs['device_code']?></td>
					<td class="title" style="vertical-align:top;">วันที่เริ่มใช้งาน</td>
					<td style="vertical-align:top;">
					<?php
						list($ckyear,$ckmonth,$ckday) = split('[/.-]', $rs['check_date']); 
						echo $ckstart= $ckday . "/". $ckmonth . "/" .$ckyear;
					?>
					</td>
				</tr>
				<tr>
					<td class="title" style="vertical-align:top;">ยี่ห้อ/รุ่น</td>
					<td style="vertical-align:top;"><?php echo $rs_program['model']?></td>
					<td class="title" style="vertical-align:top;">หมายเหตุ</td>
					<td style="vertical-align:top;"><?php echo $rs_program['remark']?></td>
				</tr>
				<tr>
					<td class="title" style="vertical-align:top;">ความถี่ในการ PM</td>
					<td style="vertical-align:top;"><?php echo $rs_break['sum_break']?></td>
					<td class="title" style="vertical-align:top;">สถานะ</td>
					<td style="vertical-align:top;"><?php if($rs['status']==1){echo 'Success';}else{echo 'On process';}?></td>
				</tr>
				<tr>
					<td colspan="4" style="background:#fff;">
						<table cellpadding="0" cellspacing="0" id="tb-ck-com">
							<tr>
								<td rowspan="2" class="center title">วันที่แจ้ง</td>
								<td colspan="2" class="center title">เอกสารอ้างอิง</td>
								<td rowspan="2" class="center title">บันทึกรายละเอียดการแก้ไข/ตรวจสอบ/การโอนย้าย</td>
								<td rowspan="2" class="center title">ผู้ใช้งาน</td>
							</tr>
							<tr>
								<td class="center title" style="background:#fff;">ใบแจ้งซ่อม</td>
								<td class="center title" style="background:#fff;">บันทึกการตรวจ<br>รับอุปกรณ์</td>
							</tr>
							<?php
						/*	$sql_device2="select * from sd_device where device_code='".$rs['device_code']."'";
							$res_device2=mysql_query($sql_device2) or die ('Error '.$sql_device2);							
							$rs_device2=mysql_fetch_array($res_device2);*/

							$sql_breakdown="select * from sd_breakdown";
							$sql_breakdown .=" where device_code='".$rs['device_code']."'";
							$sql_breakdown .=" order by break_down_code desc";
							$res_breakdown=mysql_query($sql_breakdown) or die ('Error '.$sql_breakdown);
							while($rs_breakdown=mysql_fetch_array($res_breakdown)){	
							?>
							<tr>
								<td style="border:left:1px solid #eee;background:#fff;border:left:1px solid #eee;background:#fff;">
									<?php
									list($ckyear1,$ckmonth1,$ckday1) = split('[/.-]', $rs_breakdown['break_down_date']); 
									echo $ckstart1= $ckday1 . "/". $ckmonth1 . "/" .$ckyear1;		
									?>
								</td>
								<td><?php echo $rs_breakdown['break_down_code']?></td>
								<td><?php echo ''?></td>
								<td>
									<?php 
									echo '<b>ปัญหา/อาการ : </b>';
									echo $rs_breakdown['problem'];
									echo '<br>';
									echo '<b>การแก้ไข :</b>';
									echo $rs_breakdown['repair_description'];
									echo ' วันที่ ';
									list($ckyear2,$ckmonth2,$ckday2) = split('[/.-]', $rs_breakdown['complete_date']); 
									echo $ckstart2= $ckday2 . "/". $ckmonth2 . "/" .$ckyear2;										
									?>	
								</td>
								<td><?php if($rs_breakdown['id_type_device']==4){echo 'ห้อง 204';}?></td>
							</tr>
							<?php }?>
						</table>
					</td>
				</tr>
			</table>
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
