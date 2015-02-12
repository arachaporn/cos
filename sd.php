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
	<?php include("menu.php");?>
	<div class="row">
		<div class="background">
		<table style="border: 0; width: 100%;" cellpadding="0" cellspacing="0">
			<tr>
				<td class="b-bottom"><div class="large-4 columns"><h4>Administration</h4></div></td>
			</tr>
			<tr>
				<td class="b-bottom" style="background: #fff;">
					<div class="large-4 columns">
						<?php if(($rs_account['id_department']==6) && (($rs_account['role_user']==1) || ($rs_account['role_user']==2))){?>
						<a href="sd/check-computer.php"><div id="bg-sub-menu">บันทึกการตรวจเช็คคอมพิวเตอร์และอุปกรณ์ต่อพ่วง</div></a>
						<a href="sd/register-device.php"><div id="bg-sub-menu">ทะเบียนอุปกรณ์</div></a>
						<a href="sd/record-device.php"><div id="bg-sub-menu">ประวัติอุปกรณ์</div></a>	
						<?php }?>
						<a href="sd/breakdown.php"><div id="bg-sub-menu">ใบแจ้งซ่อม/บำรุงรักษา</div></a>	
						<a href="sd/withdraw-device.php"><div id="bg-sub-menu"><?php if(($rs_account['id_department']==6) && (($rs_account['role_user']==1) || ($rs_account['role_user']==2))){echo 'บันทึกการเบิกอุปกรณ์อิเล็กทรอนิกส์';}else{ echo 'เบิกอุปกรณ์อิเล็กทรอนิกส์';}?></div></a>	
					</div>
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
