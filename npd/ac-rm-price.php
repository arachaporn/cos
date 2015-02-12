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
<script type="text/javascript" src="http://code.jquery.com/jquery.min.js"></script>
<script type="text/javascript" src="rmm-js/responsivemobilemenu.js"></script>
<script src="js/vendor/custom.modernizr.js"></script>
</head>
<body>
	<?php include("menu.php");?>
	<div class="row">
		<div class="background">
			<?php
			if($_GET["id_u"]=='New'){
				$mode=$_GET["id_u"];
				$button='Save';
				$id='New';				
			}
			else{
				$id=$_GET["id_u"];
				$sql="select * from npd_rm_price where id_npd_rm='".$id."'";
				$res=mysql_query($sql) or die ('Error '.$sql);
				$rs=mysql_fetch_array($res);
				
				$mode='Edit '.$rs['name'];
				$button='Update';
			}
			?>
			<form method="post" action="dbrm-price.php">
			<input type="hidden" name="mode" value="<?php echo $id?>">
			<table style="border: 0; width: 100%;" cellpadding="0" cellspacing="0">
				<tr>
					<td class="b-bottom"><div class="large-4 columns"><h4><h4>RM Data >> <?php echo $mode;?></h4></h4></div></td>
				</tr>
				<tr>
					<td class="b-bottom">
						<div class="large-4 columns">
							<input type="submit" value="<?php echo $button?>" class="button-create">
							<input type="button" value="Close" class="button-create" onclick="window.location.href='rm-price.php'">
						</div>
					</td>
				</tr>
				<tr>
					<td style="background: #fff;">
						<div class="large-4 columns">						
							<table style="border: 0; width: 100%;" cellpadding="0" cellspacing="0" id="tb-add">
								<tr>
									<td><p class="title">RM Code</p></td>
									<td><input type="text" name="rm_code" value="<?php echo $rs['npd_rm_code']?>" style="width:20%;"></td>
								</tr>
								<tr>
									<td><p class="title">Type RM</p></td>
									<td>
										<input type="radio" name="type_rm" value="1" <?php if($rs['type_rm']==1){echo 'checked';}?>>วัตถุดิบ
										<input type="radio" name="type_rm" value="2" <?php if($rs['type_rm']==2){echo 'checked';}?>>สมุนไพร
										<input type="radio" name="type_rm" value="3" <?php if($rs['type_rm']==3){echo 'checked';}?>>Juice
										<input type="radio" name="type_rm" value="4" <?php if($rs['type_rm']==4){echo 'checked';}?>>Flavour
										<input type="radio" name="type_rm" value="5" <?php if($rs['type_rm']==5){echo 'checked';}?>>Oil
										<input type="radio" name="type_rm" value="6" <?php if($rs['type_rm']==6){echo 'checked';}?>>Capsule
										<input type="radio" name="type_rm" value="7" <?php if($rs['type_rm']==7){echo 'checked';}?>>PM
									</td>
								</tr>
								<tr>
									<td><p class="title">Name</p></td>
									<td><input type="text" name="name" value="<?php echo $rs['npd_rm_name']?>" style="width:20%;"></td>
								</tr>
								<tr>
									<td><p class="title">Supplier</p></td>
									<td><input type="text" name="supplier" value="<?php echo $rs['npd_supplier']?>" style="width:20%;"></td>
								</tr>
								<tr>
									<td><p class="title">Price/Unit</p></td>
									<td><input type="text" name="price_unit" value="<?php echo $rs['npd_rm_price']?>" style="width:20%;"></td>
								</tr>
							</table>							
						</div>
					</td>
				</tr>
				<tr>
					<td class="b-top">
						<div class="large-4 columns">
							<input type="submit" value="<?php echo $button?>" class="button-create">
							<input type="button" value="Close" class="button-create" onclick="window.location.href='rm-price.php'">
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
