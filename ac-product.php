<?php
ob_start();
session_start();
if($_SESSION["Username"] == ""){
	header("location:index.php");
	exit();
}
$_SESSION['start'] = time(); // taking now logged in time
if(!isset($_SESSION['expire'])){
	$_SESSION['expire'] = $_SESSION['start'] + 3600 ; // ending a session in 30 seconds
}
$now = time(); // checking the time now when home page starts

if($now > $_SESSION['expire']){
	session_destroy();
	//echo "Your session has expire !  <a href='logout.php'>Click Here to Login</a>";
}else{
	//echo "This should be expired in 1 min <a href='logout.php'>Click Here to Login</a>";
}
include("connect/connect.php");
$sql_account = "SELECT * FROM account WHERE username = '".$_SESSION["Username"]."'  ";
$res_account = mysql_query($sql_account) or die ('Error '.$sql_account);
$rs_account = mysql_fetch_array($res_account);

$_SESSION['id_product']=$_REQUEST['id_product'];
$_SESSION['product_name']=$_REQUEST['product_name'];
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
<script>
	function fncSubmit(){
		document.frm.submit();
	}
</script>
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
				$sql="select * from product_detail where id_product_detail='".$id."'";
				$res=mysql_query($sql) or die ('Error '.$sql);
				$rs=mysql_fetch_array($res);

				$sql_company="select * from company where id_company='".$rs['id_company']."'";
				$res_company=mysql_query($sql_company) or die ('Errro '.$sql_company);
				$rs_company=mysql_fetch_array($res_company);

				$sql_product="select * from product where id_product='".$rs['id_product']."'";
				$res_product=mysql_query($sql_product) or die ('Errro '.$sql_product);
				$rs_product=mysql_fetch_array($res_product);
				
				$mode='Edit '.$rs_product['product_name'];
				$button='Update';
			}
			?>
			<script type="text/javascript" src="js/js-autocomplete/js/jquery-1.4.2.min.js"></script> 
			<script type="text/javascript" src="js/js-autocomplete/js/jquery-ui-1.8.2.custom.min.js"></script> 
			<script type="text/javascript"> 
				jQuery(document).ready(function(){
					$('.customer').autocomplete({
						source:'return-customer.php', 
						//minLength:2,
						select:function(evt, ui){
							this.form.id_company.value = ui.item.id_company;
						}
					});
					$('.product_name').autocomplete({
						source:'return-product.php', 
						//minLength:2,
						select:function(evt, ui){
							this.form.id_product.value = ui.item.id_product;
						}
					});
					$('.factory_product').autocomplete({
						source:'return-factory.php', 
						//minLength:2,
						select:function(evt, ui){
							this.form.id_factory.value = ui.item.id_factory;
						}
					});
					$('.factory_packing').autocomplete({
						source:'return-factory.php', 
						//minLength:2,
						select:function(evt, ui){
							this.form.id_packing.value = ui.item.id_packing;
						}
					});
				});
			</script> 
			<link rel="stylesheet" href="js/js-autocomplete/css/smoothness/jquery-ui-1.8.2.custom.css" />
			<form name="frm" method="post" action="dbproduct.php">
			<input type="hidden" name="hdnCmd" value="">
			<input type="hidden" name="mode" value="<?php echo $id?>">
			<input type="hidden" name="factory" value="<?php echo $factory?>">
			<table style="border: 0; width: 100%;" cellpadding="0" cellspacing="0">
				<tr>
					<td class="b-bottom"><div class="large-4 columns"><h4>Product <?php echo $rs_fac['title']?>>> <?php echo $mode;?></h4></h4></div></td>
				</tr>
				<tr>
					<td class="b-bottom">
						<div class="large-4 columns">
							<?php if(!is_numeric($id)){?>
							<input type="button" name="save_data" id="save_data" value="<?php echo $button?>" class="button-create" OnClick="frm.hdnCmd.value='save_data';JavaScript:return fncSubmit();">
							<?php }else{?>							
							<input type="button" name="update_data" id="update_data" value="Update" class="button-create" OnClick="frm.hdnCmd.value='save_data';JavaScript:return fncSubmit();">
							<?php }?>
							<input type="button" value="Close" class="button-create" onclick="window.location.href='product.php'">
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="large-4 columns">
							<table style="border: none; width: 100%;" cellpadding="0" cellspacing="0" id="tb-add">
								<tr>
									<td colspan="2">	
										<fieldset>
											<legend>Product Detail</legend>
											<table style="border: none; width: 100%;" cellpadding="0" cellspacing="0">
												<tr>
													<td>Customer</td>
													<td>
														<input type="hidden" name="id_company" class="id_company" value="<?php echo $rs_company['id_company']?>">
														<input type="text" name="customer" id="customer" class="customer" value="<?php echo $rs_company['company_name']?>">
													</td>
													<td>product</td>
													<td>
														<input type="hidden" name="id_product" class="id_product" value="<?php echo $rs_product['id_product']?>">
														<input type="text" name="product_name" id="product_name" class="product_name" value="<?php echo $rs_product['product_name']?>">
													</td>
												</tr>
												<tr>
													<td>Reg no.</td>
													<td><input type="text" name="reg_no" value="<?php echo $rs['reg_no']?>"></td>
													<td>Size</td>
													<td><input type="text" name="product_size" value="<?php echo $rs['product_size']?>"></td>
												</tr>
												<tr>
													<td>Barcode No.</td>
													<td><input type="text" name="barcode" value="<?php echo $rs['barcode']?>"></td>
													<td>Code</td>
													<td><input type="text" name="product_code" value="<?php echo $rs['product_code']?>"></td>
												</tr>
												<tr>
													<td>Description</td>
													<td colspan="3"><textarea width="100%" name="description"><?php echo $rs['description']?></textarea></td>
												</tr>
												<tr>
													<td>Price ex vat/Unit (Baht)</td>
													<td><input type="text" name="price_ex_unit" value="<?php echo $rs['price_ex_unit']?>"></td>
												</tr>
												<tr>
													<td>Shelf Life</td>
													<td><input type="text" name="shelf_life" value="<?php echo $rs['shelf_life']?>"></td>
												</tr>
												<tr>
													<td>Images</td>
													<td><input type="file" name="image_upload"></td>
												</tr>
											</table>
										</fieldset>													
									</td>
								</tr>
								<tr>
									<td>	
										<fieldset>
											<legend>Producting Manufacture</legend>
											<input type="hidden" name="id_product_factory" value="<?php echo $rs['id_product_factory']?>">
											<?php
											$sql_factory="select * from product_factory where id_product_factory='".$rs['id_product_factory']."'";
											$res_factory=mysql_query($sql_factory) or die ('Error '.$sql_factory);
											$rs_factory=mysql_fetch_array($res_factory);

											$sql_fac="select * from type_manufactory where id_manufacturer='".$rs_factory['id_manufacturer']."'";
											$res_fac=mysql_query($sql_fac) or die ('Error '.$sql_fac);
											$rs_fac=mysql_fetch_array($res_fac);
											?>
											<table style="border: none; width: 100%;" cellpadding="0" cellspacing="0">
												<tr>
													<td>Plant</td>
													<td>
														<input type="hidden" name="id_factory" class="id_factory" value="<?php echo $rs_factory['id_manufacturer']?>">
														<input type="text" name="factory_product" class="factory_product" id="factory_product" value="<?php echo $rs_fac['title']?>">
													</td>
												</tr>
												<tr>
													<td>Detail</td>
													<td><input type="text" name="factory_product_detail" value="<?php echo $rs_factory['factory_product_detail']?>"></td>
												</tr>
												<tr>
													<td>Price</td>
													<td><input type="text" name="factory_product_price" value="<?php echo $rs_factory['factory_product_price']?>"></td>
												</tr>	
												<tr>
													<td>Unit</td>
													<td><input type="text" name="factory_product_unit" value="<?php echo $rs_factory['factory_product_unit']?>"></td>
												</tr>
											</table>
										</fieldset>													
									</td>
									<td>	
										<fieldset>
											<legend>Packing Manufacture</legend>
											<input type="hidden" name="id_product_packing" value="<?php echo $rs['id_packing_factory']?>">
											<?php
											$sql_packing="select * from product_packing where id_packing_factory='".$rs['id_packing_factory']."'";
											$res_packing=mysql_query($sql_packing) or die ('Error '.$sql_packing);
											$rs_packing=mysql_fetch_array($res_packing);
																						
											$sql_fac_pack="select * from type_manufactory";
											$res_fac_pack=mysql_query($sql_fac_pack) or die ('Error '.$sql_fac_pack);
											$rs_fac_pack=mysql_fetch_array($res_fac_pack);
											if($rs_fac_pack['manufacturer'] != 0){	
												$sql_fac_pack1="select * from type_manufactory where id_manufacturer='".$rs_packing['id_manufacturer']."'";
												$res_fac_pack1=mysql_query($sql_fac_pack1) or die ('Error '.$sql_fac_pack1);
												$rs_fac_pack1=mysql_fetch_array($res_fac_pack1);
												$fac_pack=$rs_fac_pack1['title'];
											}else{$fac_pack=$rs_packing['manufacturer'];}
											?>
											<table style="border: none; width: 100%;" cellpadding="0" cellspacing="0">
												<tr>
													<td>Plant</td>
													<td>
														<input type="hidden" name="id_packing" class="id_packing" value="<?php echo $rs_packing['id_manufacturer']?>">
														<input type="text" name="factory_packing" id="factory_packing" class="factory_packing" value="<?php echo $fac_pack?>">
													</td>
												</tr>
												<tr>
													<td>Detail</td>
													<td><input type="text" name="factory_packing_detail" value="<?php echo $rs_packing['factory_packing_detail']?>"></td>
												</tr>
												<tr>
													<td>Price</td>
													<td><input type="text" name="factory_packing_price" value="<?php echo $rs_packing['factory_packing_price']?>"></td>
												</tr>	
												<tr>
													<td>Unit</td>
													<td><input type="text" name="factory_packing_unit" value="<?php echo $rs_packing['factory_packing_unit']?>"></td>
												</tr>
											</table>
										</fieldset>													
									</td>
								</tr>
								<tr>
									<td>	
										<fieldset>
											<legend>Raw Mat</legend>
											<input type="hidden" name="id_product_rm" value="<?php echo $rs['id_product_rm']?>">
											<?php
											$sql_rm="select * from product_rm where id_product_rm='".$rs['id_product_rm']."'";
											$res_rm=mysql_query($sql_rm) or die ('Error '.$sql_rm);
											$rs_rm=mysql_fetch_array($res_rm);
											?>
											<table style="border: none; width: 100%;" cellpadding="0" cellspacing="0">
												<tr>
													<td>Vender</td>
													<td><input type="text" name="rm_vender" value="<?php echo $rs_rm['rm_vender']?>"></td>
												</tr>
												<tr>
													<td>Detail</td>
													<td><input type="text" name="rm_detail" value="<?php echo $rs_rm['rm_detail']?>"></td>
												</tr>
												<tr>
													<td>Price</td>
													<td><input type="text" name="rm_price" value="<?php echo $rs_rm['rm_price']?>"></td>
												</tr>	
												<tr>
													<td>Unit</td>
													<td><input type="text" name="rm_unit" value="<?php echo $rs_rm['rm_unit']?>"></td>
												</tr>
											</table>
										</fieldset>													
									</td>
									<td>	
										<fieldset>
											<legend>Box</legend>
											<input type="hidden" name="id_product_box" value="<?php echo $rs['id_product_box']?>">
											<?php
											$sql_box="select * from product_box where id_product_box='".$rs['id_product_box']."'";
											$res_box=mysql_query($sql_box) or die ('Error '.$sql_box);
											$rs_box=mysql_fetch_array($res_box);
											?>
											<table style="border: none; width: 100%;" cellpadding="0" cellspacing="0">
												<tr>
													<td>Vender</td>
													<td><input type="text" name="box_vender" value="<?php echo $rs_box['box_vender']?>"></td>
												</tr>
												<tr>
													<td>Detail</td>
													<td><input type="text" name="box_detail" value="<?php echo $rs_box['box_detail']?>"></td>
												</tr>
												<tr>
													<td>Price</td>
													<td><input type="text" name="box_price" value="<?php echo $rs_box['box_price']?>"></td>
												</tr>	
												<tr>
													<td>Min</td>
													<td><input type="text" name="box_min" value="<?php echo $rs_box['box_min']?>"></td>
												</tr>
											</table>
										</fieldset>													
									</td>
								</tr>
								<tr>
									<td>	
										<fieldset>
											<legend>Bottle</legend>
											<input type="hidden" name="id_product_bottle" value="<?php echo $rs['id_product_bottle']?>">
											<?php
											$sql_bottle="select * from product_bottle where id_product_bottle='".$rs['id_product_bottle']."'";
											$res_bottle=mysql_query($sql_bottle) or die ('Error '.$sql_bottle);
											$rs_bottle=mysql_fetch_array($res_bottle);
											?>
											<table style="border: none; width: 100%;" cellpadding="0" cellspacing="0">
												<tr>
													<td>Vender</td>
													<td><input type="text" name="bottle_vender" value="<?php echo $rs_bottle['bottle_vender']?>"></td>
												</tr>
												<tr>
													<td>Detail</td>
													<td><input type="text" name="bottle_detail" value="<?php echo $rs_bottle['bottle_detail']?>"></td>
												</tr>
												<tr>
													<td>Price</td>
													<td><input type="text" name="bottle_price" value="<?php echo $rs_bottle['bottle_price']?>"></td>
												</tr>	
												<tr>
													<td>Min</td>
													<td><input type="text" name="bottle_min" value="<?php echo $rs_bottle['bottle_min']?>"></td>
												</tr>
											</table>
										</fieldset>													
									</td>
									<td>	
										<fieldset>
											<legend>Sticker Label</legend>
											<input type="hidden" name="id_product_sticker" value="<?php echo $rs['id_product_sticker']?>">
											<?php
											$sql_sticker="select * from product_sticker where id_product_sticker='".$rs['id_product_sticker']."'";
											$res_sticker=mysql_query($sql_sticker) or die ('Error '.$sql_sticker);
											$rs_sticker=mysql_fetch_array($res_sticker);
											?>
											<table style="border: none; width: 100%;" cellpadding="0" cellspacing="0">
												<tr>
													<td>Vender</td>
													<td><input type="text" name="sticker_vender" value="<?php echo $rs_sticker['sticker_vender']?>"></td>
												</tr>
												<tr>
													<td>Detail</td>
													<td><input type="text" name="sticker_detail" value="<?php echo $rs_sticker['sticker_detail']?>"></td>
												</tr>
												<tr>
													<td>Price</td>
													<td><input type="text" name="sticker_price" value="<?php echo $rs_sticker['sticker_price']?>"></td>
												</tr>	
												<tr>
													<td>Min</td>
													<td><input type="text" name="sticker_min" value="<?php echo $rs_sticker['sticker_min']?>"></td>
												</tr>
											</table>
										</fieldset>													
									</td>
								</tr>
								<tr>
									<td>	
										<fieldset>
											<legend>Alu pouch</legend>
											<input type="hidden" name="id_product_alu" value="<?php echo $rs['id_product_alu']?>">
											<?php
											$sql_alu="select * from product_alu where id_product_alu='".$rs['id_product_alu']."'";
											$res_alu=mysql_query($sql_alu) or die ('Error '.$sql_alu);
											$rs_alu=mysql_fetch_array($res_alu);
											?>
											<table style="border: none; width: 100%;" cellpadding="0" cellspacing="0">
												<tr>
													<td>Vender</td>
													<td><input type="text" name="alu_vender" value="<?php echo $rs_alu['alu_vender']?>"></td>
												</tr>
												<tr>
													<td>Detail</td>
													<td><input type="text" name="alu_detail" value="<?php echo $rs_alu['alu_detail']?>"></td>
												</tr>
												<tr>
													<td>Price</td>
													<td><input type="text" name="alu_price" value="<?php echo $rs_alu['alu_price']?>"></td>
												</tr>	
												<tr>
													<td>Min</td>
													<td><input type="text" name="alu_min" value="<?php echo $rs_alu['alu_min']?>"></td>
												</tr>
											</table>
										</fieldset>													
									</td>
									<td>	
										<fieldset>
											<legend>Foil</legend>
											<input type="hidden" name="id_product_foil" value="<?php echo $rs['id_product_foil']?>">
											<?php
											$sql_foil="select * from product_foil where id_product_foil='".$rs['id_product_foil']."'";
											$res_foil=mysql_query($sql_foil) or die ('Error '.$sql_foil);
											$rs_foil=mysql_fetch_array($res_foil);
											?>
											<table style="border: none; width: 100%;" cellpadding="0" cellspacing="0">
												<tr>
													<td>Vender</td>
													<td><input type="text" name="foil_vender" value="<?php echo $rs_foil['foil_vender']?>"></td>
												</tr>
												<tr>
													<td>Detail</td>
													<td><input type="text" name="foil_detail" value="<?php echo $rs_foil['foil_detail']?>"></td>
												</tr>
												<tr>
													<td>Price</td>
													<td><input type="text" name="foil_price" value="<?php echo $rs_foil['foil_price']?>"></td>
												</tr>	
												<tr>
													<td>Min</td>
													<td><input type="text" name="foil_min" value="<?php echo $rs_foil['foil_min']?>"></td>
												</tr>
											</table>
										</fieldset>													
									</td>
								</tr>
								<tr>
									<td>	
										<fieldset>
											<legend>Other</legend>
											<input type="hidden" name="id_product_other" value="<?php echo $rs['id_product_other']?>">
											<?php
											$sql_other="select * from product_other where id_product_other='".$rs['id_product_other']."'";
											$res_other=mysql_query($sql_other) or die ('Error '.$sql_other);
											$rs_other=mysql_fetch_array($res_other);
											?>
											<table style="border: none; width: 100%;" cellpadding="0" cellspacing="0">
												<tr>
													<td>Vender</td>
													<td><input type="text" name="other_vender" value="<?php echo $rs_other['other_vender']?>"></td>
												</tr>
												<tr>
													<td>Detail</td>
													<td><input type="text" name="other_detail" value="<?php echo $rs_other['other_detail']?>"></td>
												</tr>
												<tr>
													<td>Price</td>
													<td><input type="text" name="other_price" value="<?php echo $rs_other['other_price']?>"></td>
												</tr>	
												<tr>
													<td>Min</td>
													<td><input type="text" name="other_min" value="<?php echo $rs_other['other_min']?>"></td>
												</tr>
												<tr>
													<td>Note</td>
													<td><input type="text" name="note" value="<?php echo $rs_other['note']?>"></td>
												</tr>
											</table>
										</fieldset>													
									</td>
								</tr>
							</table>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="large-4 columns">
							<?php if(!is_numeric($id)){?>
							<input type="button" name="save_data" id="save_data" value="<?php echo $button?>" class="button-create" OnClick="frm.hdnCmd.value='save_data';JavaScript:return fncSubmit();">
							<?php }else{?>							
							<input type="button" name="update_data" id="update_data" value="Update" class="button-create" OnClick="frm.hdnCmd.value='save_data';JavaScript:return fncSubmit();">
							<?php }?>
							<input type="button" value="Close" class="button-create" onclick="window.location.href='product.php'">
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
  
 <!-- <script>
    $(document).foundation();
  </script>-->
</body>
</html>
