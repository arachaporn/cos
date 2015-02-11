<?php
/*@session_start();
$strUsername = trim($_POST["tUsername"]);
$strPassword = trim($_POST["tPassword"]);*/
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
			if($_GET["id_u"]=='new'){
				$mode=$_GET["id_u"];
				$button='Save';
				$id='New';
			}
			else{
				$id=$_GET["id_u"];
				$sql="select * from moh_profit where id_moh_profit='".$id."'";
				$res=mysql_query($sql) or die ('Error '.$sql);
				$rs=mysql_fetch_array($res);

				$sql_type_product="select * from type_product where id_type_product='".$rs['id_type_product']."'";
				$res_type_product=mysql_query($sql_type_product) or die ('Error '.$sql_type_product);
				$rs_type_product=mysql_fetch_array($res_type_product);
				
				$mode='Edit '.$rs_type_product['title'];
				$button='Update';
			}
			?>
			<form method="post" action="dbhq_profit.php">
			<input type="hidden" name="mode" value="<?php echo $id?>">
			<table style="border: 0; width: 100%;" cellpadding="0" cellspacing="0">
				<tr>
					<td class="b-bottom"><div class="large-4 columns"><h4><h4>MOH, MOQ and Profit >> <?php echo $mode;?></h4></h4></div></td>
				</tr>
				<tr>
					<td class="b-bottom">
						<div class="large-4 columns">
							<input type="submit" value="<?php echo $button?>" class="button-create">
							<input type="button" value="Close" class="button-create" onclick="window.location.href='hq-profit.php'">
						</div>
					</td>
				</tr>
				<tr>
					<td style="background: #fff;">
						<div class="large-4 columns">						
							<table style="border: none; width: 100%;" cellpadding="0" cellspacing="0" id="tb-add">
								<tr>
									<td><h4>Information</h4></td>
								</tr>
								<tr>
									<td>
									<script language="javascript">
										function chksatatus(type_product){
											if (type_product=="1"){
												document.getElementById('moh1').style.display='block';
												document.getElementById('moh2').style.display='none';
												document.getElementById('moh3').style.display='none';
												document.getElementById('moh4').style.display='none';
											}else 
											if (type_product=="2"){
												document.getElementById('moh1').style.display='none';
												document.getElementById('moh2').style.display='block';
												document.getElementById('moh3').style.display='none';
												document.getElementById('moh4').style.display='none';
											}
											else 
											if (type_product=="3"){
												document.getElementById('moh1').style.display='block';
												document.getElementById('moh2').style.display='none';
												document.getElementById('moh3').style.display='none';
												document.getElementById('moh4').style.display='none';
											}else 
											if (type_product=="4"){
												document.getElementById('moh1').style.display='none';
												document.getElementById('moh2').style.display='block';
												document.getElementById('moh3').style.display='none';
												document.getElementById('moh4').style.display='none';
											}else 
											if (type_product=="5"){
												document.getElementById('moh1').style.display='none';
												document.getElementById('moh2').style.display='none';
												document.getElementById('moh3').style.display='block';
												document.getElementById('moh4').style.display='none';
											}else 
											if (type_product=="6"){
												document.getElementById('moh1').style.display='none';
												document.getElementById('moh2').style.display='none';
												document.getElementById('moh3').style.display='none';
												document.getElementById('moh4').style.display='block';
											}
										}
									</script>
									<select onChange="chksatatus(this.value);" name="type_product"id="type_product" style="width: auto; padding: 0.3%;">
										<option value="0">Select Type Product</option>
										<?php
										$sql_type_product2="select * from type_product";
										$res_type_product2=mysql_query($sql_type_product2) or die ('Error '.$sql_type_product2);
										while($rs_type_product2=mysql_fetch_array($res_type_product2)){
										?>
										<option value="<?php echo $rs_type_product2['id_type_product']?>" <?php if($rs['id_type_product']==$rs_type_product2['id_type_product']){echo 'selected';}?>><?php echo $rs_type_product2['title']?></option>
										<?php } ?>
									</select>
									</td>
								</tr>
								<tr id='moh1' <?php if(is_numeric($id)){echo 'style="display:block"';}else{echo 'style="display:none"';}?>>
									<td width="60%">
										<table style="border: none; width: 100%;" cellpadding="0" cellspacing="0" id="tb-add">
											<tr>
												<td><h4>MOH rate</h4></td>
												<td><h4>Profit rate</h4></td>
											</tr>
											<tr>
												<td><p class="title">Weight per tablet (mg)</p>
													<input type="text" name="weight" value="<?php echo $rs['weight_per_product']?>">
												</td>
												<td><p class="title">Loss 10% + Vat (baht per tab)</p>
													<input type="text" name="loss" value="<?php echo $rs['loss']?>">
												</td>
											</tr>
											<tr>
												<td><p class="title">Appearance</p>
													<select name="appearance" style="width: auto; padding: 0.3%;">
														<option value="0">Select Appearance</option>
														<option value="core" <?php if($rs['appea_size']=="core"){echo 'selected';}?>>Core</option>
														<option value="fc" <?php if($rs['appea_size']=="fc"){echo 'selected';}?>>F/C</option>
													</select>
												</td>
												</td>
												<td><p class="title">Profit rate (baht per tab)</p>
													<input type="text" name="profit_rate" value="<?php echo $rs['profit_rate']?>">
												</td>
											</tr>
											<tr>
												<td><p class="title">MOH (baht per tab)</p>
													<input type="text" name="moh" value="<?php echo $rs['moh']?>">
												</td>
												<td><p class="title">MOQ (tab per batch)</p>
													<input type="text" name="moq" value="<?php echo $rs['moq']?>">
												</td>
											</tr>
										</table>
									</td>
								</tr>	
								<tr id='moh2' style="display:none; background: #fff;">
									<td width="50%">
										<table style="border: none; width: 100%;" cellpadding="0" cellspacing="0" id="tb-add">
											<tr>
												<td><h4>MOH and Capsule cost rate</h4></td>
											</tr>
											<tr>
												<td><p class="title">Group</p>
													<select style="width: auto; padding: 0.3%;">
														<option>Select Group</option>
														<option>Gelatin</option>
														<option>HPMC</option>
													</select>
												</td>
											</tr>
											<tr>
												<td><p class="title">Capsule size</p>
													<select style="width: auto; padding: 0.3%;">
														<option>Select Capsule size</option>
														<option>#00</option>
														<option>#0</option>
													</select>
												</td>
											</tr>
											<tr>
												<td><p class="title">Weight per tablet (mg)</p>
													<input type="text">
												</td>
											</tr>
											<tr>
												<td><p class="title">Capsule cost (baht per cap)</p>
													<input type="text">
												</td>
											</tr>
											<tr>
												<td><p class="title">MOQ (tab per batch)</p>
													<input type="text">
												</td>
											</tr>
											<tr>
												<td><p class="title">MOH (baht per tab)</p>
													<input type="text">
												</td>
											</tr>
										</table>
									</td>
								</tr>	
								<tr id='moh3' style="display:none; background: #fff;">
									<td width="50%">
										<table style="border: none; width: 30%;" cellpadding="0" cellspacing="0" id="tb-add">
											<tr>
												<td><h4>MOH and Profit rate</h4></td>
											</tr>
											<tr>
												<td><p class="title">Weight per bottle (cc)</p>
													<input type="text">
												</td>
											</tr>
											<tr>
												<td><p class="title">Profit rate (baht per bottle)</p>
													<input type="text">
												</td>
											</tr>
											<tr>
												<td><p class="title">MOH mixing (baht per bottle)</p>
													<input type="text">
												</td>
											</tr>
											<tr>
												<td><p class="title">MOH packing (baht per bottle)</p>
													<input type="text">
												</td>
											</tr>
											<tr>
												<td><p class="title">MOQ (bottle per batch)</p>
													<input type="text">
												</td>
											</tr>
										</table>
										<p class="title">***** In case of customer would like to screen cap, the MOQ of screen cap is 500,000 pcs. *****</p>
									</td>
								</tr>	
								<tr id='moh4' style="display:none; background: #fff;">
									<td width="50%">
										<table style="border: none; width: 30%;" cellpadding="0" cellspacing="0" id="tb-add">
											<tr>
												<td><h4>MOH and Profit rate</h4></td>
											</tr>
											<tr>
												<td><p class="title">Group</p>
													<select style="width: auto; padding: 0.3%;">
														<option>Select Group</option>
														<option>Instant drink</option>
														<option>Edible gel</option>
													</select>
												</td>
											</tr>
											<tr>
												<td><p class="title">Weight per sachet (gm)</p>
													<input type="text">
												</td>
											</tr>
											<tr>
												<td><p class="title">Profit rate (baht per sachet)</p>
													<input type="text">
												</td>
											</tr>
											<tr>
												<td><p class="title">MOH mixing + packing (baht per sachet)</p>
													<input type="text">
												</td>
											</tr>
											<tr>
												<td><p class="title">MOQ (sachet per batch)</p>
													<input type="text">
												</td>
											</tr>
										</table>
										<p class="title">In case of customer would like to screen sachet 1 color, the MOQ of screen sachet is 100,000 pcs.</p>
										<p class="title">In case of customer would like to screen sachet 4 color, the MOQ of screen sachet is 200,000 pcs.</p>
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
							<input type="button" value="Close" class="button-create" onclick="window.location.href='customer.php'">
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
