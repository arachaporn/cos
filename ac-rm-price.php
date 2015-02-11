<?php
/*@session_start();
$strUsername = trim($_POST["tUsername"]);
$strPassword = trim($_POST["tPassword"]);*/
require('fpdf.php');
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
			include("connect/connect.php");

			if($_GET["id_u"]=='New'){
				$mode=$_GET["id_u"];
				$button='Save';
				$id='New';				
			}
			else{
				$id=$_GET["id_u"];
				$sql="select * from rm_price where id_rm_price='".$id."'";
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
									<td width="28%;"><p class="title">RM Code</p><input type="text" name="rm_code" <?php if(is_numeric($id)){echo 'readonly';}?> value="<?php echo $rs['rm_code']?>"></td>
									<td><p class="title">From</p>
										<input type="radio" name="rm_from" <?php if($rs['rm_from']==1){echo 'checked';}?> value="1">Factory
										<input type="radio" name="rm_from" <?php if($rs['rm_from']==2){echo 'checked';}?> value="2">CDIP
									</td>
								</tr>
								<tr>
									<td width="28%;"><p class="title">Name</p><input type="text" name="name" value="<?php echo $rs['rm_name']?>"></td>
									<td width="28%;"><p class="title">Unit</p><input type="text" name="unit" value="<?php echo $rs['unit']?>"></td>
								</tr>
								<tr>
									<td width="28%;"><p class="title">Price/Unit</p><input type="text" name="price_unit" value="<?php echo $rs['price_unit']?>"></td>
									<td width="28%;"><p class="title">Standard Price</p><input type="text" name="standard_price" value="<?php echo $rs['standard_price']?>"></td>
								</tr>
								<tr>
									<td colspan="2"><p class="title">Description</p>
									<textarea style="width:50%; height:100px;" name="remark"><?php echo $rs['remark']?></textarea></td>
									</td>
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
