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
			<form name="frmMain" action="del-costing-table.php" method="post">
				<table style="border: 0; width: 100%;" cellpadding="0" cellspacing="0">
					<tr>
						<td class="b-bottom" style="width:45%;text-align:left;"><div class="large-4 columns"><a href="sales-marketing.php"><?php echo '< New Business Development'?></a></div></td>
						<td class="b-bottom" style="text-align:left;"><div class="large-4 columns"><h4>Costing table</h4></div></td>
					</tr>
					<tr>
						<td colspan="2" class="b-bottom" style="background:#fff;">
							<div class="large-4 columns">
								<h4>ตาราง Costing table</h4>
								<?php
								$sql_fac="select * from type_manufactory";
								$res_fac=mysql_query($sql_fac) or die ('Error '.$sql_fac);
								while($rs_fac=mysql_fetch_array($res_fac)){
								?>
									<a href='costing-factory-table.php?fac=<?php echo $rs_fac['id_manufacturer']?>'>
									<div class="btn-home">
										<img src="img/logo-factory/<?php echo $rs_fac['logo']?>" style="weight:163px;height:79px;padding:1.5%;"><br><br><span style="text-align:center;"><?php echo $rs_fac['title']?></a></span>					
									</div>
									</a>
								<?php }?>
							</div>
						</td>
					</tr>
					<!--<tr>
						<td colspan="2">
							<div class="large-4 columns">
								<h4>คำนวณ Costing table by ROC</h4>
								<?php
								$sql_fac="select * from type_manufactory";
								$res_fac=mysql_query($sql_fac) or die ('Error '.$sql_fac);
								while($rs_fac=mysql_fetch_array($res_fac)){
								?>
									<a href='costing-factory.php?fac=<?php echo $rs_fac['id_manufacturer']?>'>
									<div class="btn-home">
										<img src="img/logo-factory/<?php echo $rs_fac['logo']?>" style="weight:163px;height:79px;padding:1.5%;"><br><br><span style="text-align:center;"><?php echo $rs_fac['title']?></a></span>					
									</div>
									</a>
								<?php }?>
							</div>
						</td>					
					</tr>-->
					<tr>
						<td colspan="2">
							<div class="large-4 columns">
								<h4>คำนวณ Costing table</h4>
								<?php
								$sql_fac="select * from type_manufactory";
								$res_fac=mysql_query($sql_fac) or die ('Error '.$sql_fac);
								while($rs_fac=mysql_fetch_array($res_fac)){
								?>
									<a href='costing-factory.php?fac=<?php echo $rs_fac['id_manufacturer']?>'>
									<div class="btn-home">
										<img src="img/logo-factory/<?php echo $rs_fac['logo']?>" style="weight:163px;height:79px;padding:1.5%;"><br><br><span style="text-align:center;"><?php echo $rs_fac['title']?></a></span>					
									</div>
									</a>
								<?php }?>
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
