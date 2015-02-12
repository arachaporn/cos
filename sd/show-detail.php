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
	$sql="select * from sd_device where id_type_device='".$_GET['device']."'";
	$sql .=" and id_employee='".$_GET['account']."'";
	$sql .=" order by id_sd_device desc";
	$res=mysql_query($sql) or die('Error '.$sql);
	$rs=mysql_fetch_array($res);
	?>
	<div class="row">
		<div class="background">
			<table style="border: 0; width: 100%;" cellpadding="0" cellspacing="0">
				<tr>
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
					?>
					<td class="b-bottom" colspan="6"><div class="large-4 columns"><h4>ทะเบียนอุปกรณ์ <?php echo $rs_device['title_device']?></h4></div></td>
				</tr>
				<tr>
					<td class="title">รหัสเครื่อง</td>
					<td colspan="5"><?php echo $rs['device_code']?></td>
				</tr>
				<tr>
					<td class="title">การตรวจรับ/ตรวจสอบ</td>
					<td><?php if($rs['check_device']==1){echo 'ตรวจรับครั้งแรก';}else{echo  'ตรวจตามแผนประจำปี'; }?></td>
					<td class="title" colspan="2">กำหนดตรวจสอบครั้งต่อไป</td>
					<td>
					<?php
						list($ckyear,$ckmonth,$ckday) = split('[/.-]', $rs['check_next_date']); 
						echo $ckstart= $ckday . "/". $ckmonth . "/" .$ckyear;
					?>
					</td>
				</tr>
				<tr>
					<td class="title">ประเภทของอุปกรณ์</td>
					<td colspan="5"><?php echo $rs_device['title_device']?></td>
				</tr>
				<tr>
					<td class="title">รหัสพนักงาน</td>
					<td class="left"><?php echo $rs_employee['id_employee']?></td>
					<td class="title">ผู้ใช้งาน</td>
					<td class="left"><?php echo $rs_employee['name']?></td>
					<td class="title">ฝ่าย/แผนก</td>
					<td><?php echo $rs_department['title']?></td>
				</tr>
				<tr>
					<td class="title" colspan="6">รายละเอียด</td>
				</tr>
				<tr>
					<td colspan="6">
						<?php
						$sql_department="select * from department where id_department='".$department."'";
						$res_department=mysql_query($sql_department) or die ('Error '.$sql_department);
						$rs_department=mysql_fetch_array($res_department);
						?>
						<table cellpadding="0" cellspacing="0" id="tb-ck-com">
							<tr>
								<td class="title center w10">ลำดับที่</td>
								<td class="title center w20">รายการ</td>
								<td class="title center w30">บันทึกผล</td>
								<td class="title center" colspan="2">โปรแกรมพื้นฐาน</td>
							</tr>
							<?php
							$sql_program="select * from sd_program where id_sd_device='".$rs['id_sd_device']."'";
							$res_program=mysql_query($sql_program) or die ('Error '.$sql_program);
							$rs_program=mysql_fetch_array($res_program);

							$default_program=split(",",$rs_program['default_program']);
							?>
							<tr>
								<td class="center">1</td>
								<td>Operating System</td>
								<td><?php echo $rs_program['os']?></td>
								<td><?php if(in_array('ms',$default_program)){echo 'Microsoft Office';}?></td>
								<td><?php if(in_array('acrobat',$default_program)){echo 'Adobe Acrobat X Pro';}?></td>
							</tr>
							<tr>
								<td class="center">2</td>
								<td>CPU</td>
								<td><?php echo $rs_program['cpu']?></td>
								<td><?php if(in_array('acd',$default_program)){echo 'ACD See';}?></td>
								<td><?php if(in_array('anti_virus',$default_program)){echo 'Anti Virus';}?></td>
							</tr>
							<tr>
								<td class="center">3</td>
								<td>Mainboard</td>
								<td><?php echo $rs_program['mainboard']?></td>
								<td><?php if(in_array('firefox',$default_program)){echo 'Firefox';}?></td>
								<td><?php if(in_array('chorme',$default_program)){echo 'Google chrome';}?></td>
							</tr>
							<tr>
								<td class="center">4</td>
								<td>RAM</td>
								<td><?php echo $rs_program['ram']?></td>
								<td><?php if(in_array('winrar',$default_program)){echo 'Winrar';}?></td>
								<td><?php if(in_array('vnc',$default_program)){echo 'VNC';}?></td>
							</tr>
							<tr>
								<td class="center">5</td>
								<td>HDD</td>
								<td><?php echo $rs_program['hdd']?></td>
								<td><?php if(in_array('advance_care',$default_program)){echo 'Advance System Care';}?></td>
								<td><?php if(in_array('lan_message',$default_program)){echo 'Lan Message';}?></td>
							</tr>
							<tr>
								<td class="center">6</td>
								<td>VGA</td>
								<td><?php echo $rs_program['vga']?></td>
								<td><?php if(in_array('reader',$default_program)){echo 'Adobe Reader';}?></td>
								<td></td>
							</tr>
							<tr>
								<td class="center">7</td>
								<td>Monitor</td>
								<td><?php echo $rs_program['monitor']?></td>
								<td colspan="2"></td>
							</tr>
							<tr>
								<td class="center">8</td>
								<td>ยี่ห้อ/รุ่น (ยกเว้น Personal Computer)</td>
								<td><?php if($rs_program['model']==''){echo '-';}else{echo $rs_program['model'];}?></td>
								<td colspan="2"></td>
							</tr>											
						</table>
					</td>
				</tr>
				<tr>
					<td class="title">โปรแกรมพิเศษที่ร้องขอ</td>
					<td colspan="5"><?php echo $rs_program['special_program']?></td>
				</tr>
				<tr>
					<td class="title">หมายเหตุ</td>
					<td colspan="5"><?php echo $rs_program['remark']?></td>
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
