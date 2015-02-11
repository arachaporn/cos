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
<body 
	<?php
		if (($_GET["p"]==2) && ($_GET["fac"]==1)) {
			echo 'onload="fncSumTotal_Tablet()"';
		}
	?>
>
	<?php include("menu.php");?>
	<div class="row">
		<div class="background">
			<?php
			include("connect/connect.php");
			$date=date('Y-m-d');
			if($_GET["id_u"]=='New'){
				$mode=$_GET["id_u"];
				$button='Save';
				$id='New';	
				$factory=$_GET['fac'];
			}
			else{
				$factory=$_GET['fac'];
				$id=$_GET["id_u"];
			/*	$sql="select * from roc where id_roc='".$id."'";
				$res=mysql_query($sql) or die ('Error '.$sql);
				$rs=mysql_fetch_array($res);*/				
				
				$mode='Edit '.$rs_product['product_name'];
				$button='Update';

				$sql_fac="select * from type_manufactory where id_manufacturer='".$_GET['fac']."'";
				$res_fac=mysql_query($sql_fac) or die ('Error '.$sql_fac);
				$rs_fac=mysql_fetch_array($res_fac);

				$sql_costing="select * from costing_factory where id_costing_factory='".$id."'";
				$res_costing=mysql_query($sql_costing) or die ('Error '.$sql_costing);
				$rs_costing=mysql_fetch_array($res_costing);

				$sql_product="select * from product where id_product='".$rs_costing['id_product']."'";
				$res_product=mysql_query($sql_product) or die ('Errro '.$sql_product);
				$rs_product=mysql_fetch_array($res_product);
			
				$sql_product_value="select * from  roc_product_value";
				$sql_product_value .=" where id_product_value='".$rs['id_product_value']."'";
				$res_product_value=mysql_query($sql_product_value) or die ('Error '.$sql_product_value);
				$rs_product_value=mysql_fetch_array($res_product_value);

				$sql_pack_blister="select * from costing_pack_blister";
				$sql_pack_blister .=" where id_costing_factory='".$rs_costing['id_costing_factory']."'";
				$res_pack_blister=mysql_query($sql_pack_blister) or die ('Error '.$sql_pack_blister);
				$rs_pack_blister=mysql_fetch_array($res_pack_blister);

				$sql_acc="select * from account where id_account='".$rs_costing['create_by']."'";
				$res_acc=mysql_query($sql_acc) or die ('Error '.$sql_acc);
				$rs_acc=mysql_fetch_array($res_acc);

				$sql_prima="select * from costing_factory_prima";
				$sql_prima .=" where id_costing_factory='".$rs_costing['id_costing_factory']."'";
				$res_prima=mysql_query($sql_prima) or die ('Error '.$sql_prima);
				$rs_prima=mysql_fetch_array($res_prima);

				/*delete roc rm*/
				if($_GET["action"] == "del_rm"){
					$sql = "delete from costing_rm ";
					$sql .="where id_costing_rm = '".$_GET["id_p"]."'";
					$res = mysql_query($sql) or die ('Error '.$sql);
				}
			}
			?>
			<script type="text/javascript" src="js/js-autocomplete/js/jquery-1.4.2.min.js"></script> 
			<script type="text/javascript" src="js/js-autocomplete/js/jquery-ui-1.8.2.custom.min.js"></script> 
			<script type="text/javascript"> 
				jQuery(document).ready(function(){
					$('.product_name').autocomplete({
						source:'return-product.php', 
						//minLength:2,
						select:function(evt, ui){
							this.form.id_product.value = ui.item.id_product;
						}
					});
					$('.rm_name').autocomplete({
						source:'return-costing-rm.php', 
						//minLength:2,
						select:function(evt, ui){
							this.form.id_rm.value = ui.item.id_rm;
						//	this.form.npd_supplier.value = ui.item.npd_supplier;
							this.form.rm_price.value = ui.item.rm_price;
						}
					});
					$('.rm_name2').autocomplete({
						source:'return-costing-rm.php', 
						//minLength:2,
						select:function(evt, ui){
							this.form.id_rm2.value = ui.item.id_rm2;
						//	this.form.npd_supplier.value = ui.item.npd_supplier;
							this.form.rm_price2.value = ui.item.rm_price2;
						}
					});
					$('.rm_name5').autocomplete({
						source:'return-costing-cox-rm.php', 
						//minLength:2,
						select:function(evt, ui){
							this.form.id_rm5.value = ui.item.id_rm5;
						//	this.form.npd_supplier.value = ui.item.npd_supplier;
							this.form.rm_price5.value = ui.item.rm_price5;
						}
					});
					$('.rm_name52').autocomplete({
						source:'return-costing-cox-rm.php', 
						//minLength:2,
						select:function(evt, ui){
							this.form.id_rm52.value = ui.item.id_rm52;
						//	this.form.npd_supplier.value = ui.item.npd_supplier;
							this.form.rm_price52.value = ui.item.rm_price52;
						}
					});
				});
			</script> 
			<link rel="stylesheet" href="js/js-autocomplete/css/smoothness/jquery-ui-1.8.2.custom.css" /> 
			<form name="frm" method="post" action="dbcosting-table.php">
			<input type="hidden" name="hdnCmd" value="">
			<input type="hidden" name="mode" value="<?php echo $id?>">
			<input type="hidden" name="factory" value="<?php echo $factory?>">
			<input type="hidden" name="costing" value="<?php echo $rs_costing['id_costing_factory']?>">
			<input type="hidden" name="id_roc" id="id_roc" value="<?php echo $rs['id_roc']?>">
			<table style="border: 0; width: 100%;" cellpadding="0" cellspacing="0">
				<tr>
					<td class="b-bottom"><div class="large-4 columns"><h4>Costing Table <?php echo $rs_fac['title']?>>> <?php echo $mode;?></h4></div></td>
				</tr>
				<tr>
					<td class="b-bottom">
						<div class="large-4 columns">
							<?php if(!is_numeric($id)){?> 
							<input type="button" name="save_data" id="save_data" value="<?php echo $button?>" class="button-create" OnClick="frm.hdnCmd.value='save_data';JavaScript:return fncSubmit();">
							<?php }else{if($_GET['p']==1){?>	
							<input type="button" name="next_step" id="update_data" value="Next step" class="button-create" OnClick="frm.hdnCmd.value='update_data';JavaScript:return fncSubmit();">
							<?php }elseif($_GET['p']==2){?>
							<input type="button" name="back_step_one" id="update_data" value="Back step one" class="button-create" onclick="window.location.href='ac-costing-table.php?id_u=<?php echo $id?>&fac=<?php echo $factory?>&p=1'">
							<input type="button" name="next_step" id="update_data" value="Next step" class="button-create" OnClick="frm.hdnCmd.value='update_data';JavaScript:return fncSubmit();">
							<?php }elseif($_GET['p']==3){?>
							<input type="button" name="back_step_one" id="update_data" value="Back step two" class="button-create" onclick="window.location.href='ac-costing-table.php?id_u=<?php echo $id?>&fac=<?php echo $factory?>&p=2'">
							<input type="button" name="next_step" id="update_data" value="Next step" class="button-create" OnClick="frm.hdnCmd.value='update_data';JavaScript:return fncSubmit();">
							<?php }elseif($_GET['p']==4){?>
							<input type="button" name="back_step_one" id="update_data" value="Back step three" class="button-create" onclick="window.location.href='ac-costing-table.php?id_u=<?php echo $id?>&fac=<?php echo $factory?>&p=3'">
							<input type="button" name="update_data" id="update_data" value="Save" class="button-create" OnClick="frm.hdnCmd.value='update_data';JavaScript:return fncSubmit();">
							<input type="button" name="update_data" id="update_data" value="Print PDF Thai" class="button-create" Onclick="window.open('pdf-costing-thai.php?id_u=<?php echo $id?>&fac=<?php echo $factory?>')">
							<input type="button" name="update_data" id="update_data" value="Print PDF Eng" class="button-create" Onclick="window.open('pdf-costing-eng.php?id_u=<?php echo $id?>&fac=<?php echo $factory?>')">
							<?php }?>	
							<?php }?>
							<input type="button" value="Close" class="button-create" onclick="window.location.href='costing-factory.php?fac=<?php echo $factory?>'">
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="large-4 columns">
							<table style="border: none; width: 100%;" cellpadding="0" cellspacing="0" id="tb-add">
								<!--<tr>
									<td class="w15">ROC</td>
									<td><input type="text" name="roc" id="roc" class="roc" value="<?php echo $rs['roc_code']?>" readonly style="width: 20%;"></td>
								</tr>-->
								<tr>
									<td width="15%">Product name</td>
									<td>
										<input type="hidden" name="id_product" class="id_product" id="id_product" value="<?php echo $rs_product['id_product']?>">
										<input type="text" name="product_name" id="product_name" class="product_name" value="<?php if($rs_costing['id_product']==0){echo $rs_costing['product_name'];}else{echo $rs_product['product_name'];}?>" style="width: 30%;">
									</td>
								</tr>
								<?php if($_GET['p']==1){?>
								<input type="hidden" name="nextstep" value="1">
								<tr>
									<td colspan="2"><h3>Step 1 : Dosage form</h3></td>
								</tr>
								<tr>
									<td>Product category</td>
									<td>
										<select name="product_cate" style="width: auto;">
										<?php
										$sql_npd="select * from npd_type_product";
										$res_npd=mysql_query($sql_npd) or die ('Error '.$sql_npd);
										while($rs_npd=mysql_fetch_array($res_npd)){								
										?>
											<option value="<?php echo $rs_npd['id_npd_type_product'];?>" <?php if($rs_costing['id_product_cate']==$rs_npd['id_npd_type_product']){echo 'selected';}?>><?php echo $rs_npd['npd_title']?></option>
										<?php } ?>
										</select>										
									</td>
								</tr>	
								<?php if($_GET['fac']==1){?>
								<tr>
									<td>Product appearance</td>
									<td>
										<!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>-->
										<script src="js/jquery.chained.min.js"></script>
										<select name="product_app" id="product_app" style="width: auto;">
										<?php
										$sql_app="select * from product_appearance";
										$res_app=mysql_query($sql_app) or die ('Error '.$sql_app);
										while($rs_app=mysql_fetch_array($res_app)){								
										?>
											<option value="<?php echo $rs_app['id_product_appearance'];?>" <?php if($rs_costing['id_product_appearance']==$rs_app['id_product_appearance']){echo 'selected';}?>><?php echo $rs_app['title']?></option>
										<?php } ?>
										</select>										
									</td>
								</tr>	
								<tr>
									<td>Sub product appearance</td>
									<td>
										<select id="sub_app" name="sub_app" style="width: auto;">
											<?php
											$sql_sub_app="select * from factory_type_product";
											$res_sub_app=mysql_query($sql_sub_app) or die ('Error '.$sql_sub_app);
											while($rs_sub_app=mysql_fetch_array($res_sub_app)){										
											?>
											<option value="<?php echo $rs_sub_app['id_factory_type_product'];?>" class="<?echo $rs_sub_app['id_product_appearance']?>" <?php if($rs_product_value['title']==$rs_sub_app['title_type_product']){echo 'selected';}?>><?php echo $rs_sub_app['title_type_product']?></option>
											<?php } ?>
										</select>
									</td>									
								</tr>
								<tr>
									<td>Size/Weight</td>
									<td>
										<select id="weight" name="weight" style="width: auto;">
											<?php
											$sql_weight="select * from factory_weight";
											$res_weight=mysql_query($sql_weight) or die ('Error '.$sql_weight);
											while($rs_weight=mysql_fetch_array($res_weight)){										
											?>
											<option value="<?php echo $rs_weight['id_factory_weight'];?>" class="<?php echo $rs_weight['id_product_appearance']?>" <?php if($rs_costing['id_factory_weight']==$rs_weight['id_factory_weight']){echo 'selected';}?>><?php echo $rs_weight['factory_weight']?></option>
											<?php } ?>
										</select>
									</td>
									<script>
										$("#sub_app").chained("#product_app"); /* or $("#series").chainedTo("#mark"); */
										$("#weight").chained("#product_app");										
									</script>
								</tr>
								<tr>
									<td>MOQ</td>
									<td>
										<select name="moq" style="width: auto;">
											<option value="75,000-149,999" <?php if($rs_costing['moq']=='75,000-149,999'){echo 'selected';}?>><?php echo '75,000-149,999';?></option>
											<option value="150,000-299,999" <?php if($rs_costing['moq']=='150,000-299,999'){echo 'selected';}?>><?php echo '150,000-299,999';?></option>
											<option value="300,000-499,999" <?php if($rs_costing['moq']=='300,000-499,999'){echo 'selected';}?>><?php echo '1300,000-499,999';?></option>
											<option value="up to 500,000" <?php if($rs_costing['moq']=='up to 500,000'){echo 'selected';}?>><?php echo 'up to 500,000';?></option>
											<option value="others" <?php if($rs_costing['moq']=='others'){echo 'selected';}?>><?php echo 'Others';?></option>
										</select>										
									</td>
								</tr>
								<?php }elseif($_GET['fac']==3){?>
								<tr>
									<td>Product appearance</td>
									<td>
										<select name="product_app" id="product_app" style="width: auto;">
											<option value="6" <?php if($rs['id_product_appearance']==1){echo 'selected';}?>><?php echo 'Functional drink '?></option>
										</select>										
									</td>
								</tr>	
								<tr>
									<td>Weight</td>
									<td>
										<select id="weight" name="weight" style="width: auto;">
											<option value="1" <?php if($rs_costing['id_factory_weight']==$rs_weight['id_factory_weight']){echo 'selected';}?>><?php echo '40-50 cc'?></option>
											<option value="2" <?php if($rs_costing['id_factory_weight']==$rs_weight['id_factory_weight']){echo 'selected';}?>><?php echo '100 cc'?></option>
											<option value="3" <?php if($rs_costing['id_factory_weight']==$rs_weight['id_factory_weight']){echo 'selected';}?>><?php echo '250 cc'?></option>
											<option value="4" <?php if($rs_costing['id_factory_weight']==$rs_weight['id_factory_weight']){echo 'selected';}?>><?php echo '250 cc Retort'?></option>
										</select>
									</td>
								</tr>
								<tr>
									<td>MOQ</td>
									<td>
										<select name="moq" style="width: auto;">
											<option value="100,000-300,000" <?php if($rs_costing['moq']=='100,000-300,000'){echo 'selected';}?>><?php echo '100,000-300,000';?></option>
											<option value="300,001-500,000" <?php if($rs_costing['moq']=='300,001-500,000'){echo 'selected';}?>><?php echo '300,001-500,000';?></option>
											<option value="up to 500,001" <?php if($rs_costing['moq']=='up to 500,001'){echo 'selected';}?>><?php echo 'up to 500,001';?></option>
											<option value="others" <?php if($rs_costing['moq']=='others'){echo 'selected';}?>><?php echo 'Others';?></option>
										</select>										
									</td>
								</tr>
								<?php }?>								
								<?php }elseif($_GET['p']==2){?>
									<input type="hidden" name="nextstep" value="2">
									<tr>
										<td colspan="2"><h3>Step 2 : Costing table</h3></td>
									</tr>
									<?php if($_GET['fac']==1){?>
									<!--function calculate total cost-->
									<script language="JavaScript">
										function fncSumTotal_Tablet(){
											var vat;		
											document.frm.other_excipients.value = (parseFloat(document.frm.total_weight.value) - parseFloat(document.frm.total_quantities.value)).toFixed(3);//fix float=3

											document.frm.sum_other_exp.value = (parseFloat(document.frm.other_excipients.value) * parseFloat(document.frm.other_exp_cost.value) / 1000000).toFixed(3);//fix float=3

											document.frm.cost_tab.value = (parseFloat(document.frm.sum_other_exp.value) + parseFloat(document.frm.sum_cost_unit.value)).toFixed(3);//fix float=3
														
											//calculate lost 10%
											document.frm.lost_10.value = ((parseFloat(document.frm.cost_tab.value)*100 )/ 90).toFixed(3);//fix float=3

											//calculate vat 7%
											document.frm.vat_7.value = (parseFloat(document.frm.lost_10.value) * 1.07).toFixed(3);//fix float=3

											//calculate total cos per tablet
											document.frm.total_cost.value = (parseFloat(document.frm.vat_7.value) + parseFloat(document.frm.moh.value) + parseFloat(document.frm.profit.value)).toFixed(3);
											
											//cal jsp profit
											document.frm.jsp_profit_cost.value = (parseFloat(document.frm.total_cost.value) * parseFloat(document.frm.jsp_profit.value)).toFixed(3);
														
											//cal cdip profit
											document.frm.cdip_profit_cost.value = (parseFloat(document.frm.jsp_profit_cost.value) * parseFloat(document.frm.cdip_profit.value)).toFixed(3);

											//totla bluk
											document.frm.total_bluk.value = (parseFloat(document.frm.total_cost.value) + parseFloat(document.frm.jsp_profit_cost.value) + parseFloat(document.frm.cdip_profit.value)).toFixed(3);
										}
										function fncSumTotal_Capsule(){
											var vat;		
											document.frm.other_excipients.value = (parseFloat(document.frm.total_weight.value) - parseFloat(document.frm.total_quantities.value)).toFixed(3);//fix float=3

											document.frm.total_weight_capsule.value = (parseFloat(document.frm.total_weight.value) + parseFloat(document.frm.cost_capsule.value)).toFixed(3);//fix float=3

											document.frm.sum_other_exp.value = (parseFloat(document.frm.other_excipients.value) * parseFloat(document.frm.other_exp_cost.value) / 1000000).toFixed(3);//fix float=3

											document.frm.cost_tab.value = (parseFloat(document.frm.sum_other_exp.value) + parseFloat(document.frm.sum_cost_unit.value)+ parseFloat(document.frm.capsule_no.value)).toFixed(3);//fix float=3
														
											//calculate lost 10%
											document.frm.lost_10.value = ((parseFloat(document.frm.cost_tab.value)*100 )/ 90).toFixed(3);//fix float=3

											//calculate vat 7%
											document.frm.vat_7.value = (parseFloat(document.frm.lost_10.value) * 1.07).toFixed(3);//fix float=3

											//calculate total cos per tablet
											document.frm.total_cost.value = (parseFloat(document.frm.vat_7.value) + parseFloat(document.frm.moh.value) + parseFloat(document.frm.profit.value)).toFixed(3);											
											
											//cal jsp profit
											document.frm.jsp_profit_cost.value = (parseFloat(document.frm.total_cost.value) * parseFloat(document.frm.jsp_profit.value)).toFixed(3);
														
											//cal cdip profit
											document.frm.cdip_profit_cost.value = (parseFloat(document.frm.jsp_profit_cost.value) * parseFloat(document.frm.cdip_profit.value)).toFixed(3);

											//totla bluk
											document.frm.total_bluk.value = (parseFloat(document.frm.total_cost.value) + parseFloat(document.frm.jsp_profit_cost.value) + parseFloat(document.frm.cdip_profit_cost.value)).toFixed(3);
										}
										function fncSumTotal_Softgel(){
											var vat;		

											document.frm.cost_tab.value = (parseFloat(document.frm.sum_other_exp.value) + parseFloat(document.frm.sum_cost_unit.value)+ parseFloat(document.frm.capsule_no.value)).toFixed(3);//fix float=3
														
											//calculate lost 10%
											document.frm.lost_10.value = ((parseFloat(document.frm.cost_tab.value)*100 )/ 90).toFixed(3);//fix float=3

											//calculate vat 7%
											document.frm.vat_7.value = (parseFloat(document.frm.lost_10.value) * 1.07).toFixed(3);//fix float=3

											//calculate total cos per tablet
											document.frm.total_cost.value = (parseFloat(document.frm.vat_7.value) + parseFloat(document.frm.moh.value) + parseFloat(document.frm.profit.value)).toFixed(3);											
											
											//cal jsp profit
											document.frm.jsp_profit_cost.value = (parseFloat(document.frm.total_cost.value) * parseFloat(document.frm.jsp_profit.value)).toFixed(3);
														
											//cal cdip profit
											document.frm.cdip_profit_cost.value = (parseFloat(document.frm.jsp_profit_cost.value) * parseFloat(document.frm.cdip_profit.value)).toFixed(3);

											//totla bluk
											document.frm.total_bluk.value = (parseFloat(document.frm.total_cost.value) + parseFloat(document.frm.jsp_profit_cost.value) + parseFloat(document.frm.cdip_profit_cost.value)).toFixed(3);
										}
										function fncSumTotal_Functional_drink(){
											var vat;		
											document.frm.other_excipients.value = (parseFloat(document.frm.total_weight.value) - parseFloat(document.frm.total_quantities.value)).toFixed(3);//fix float=3

											document.frm.sum_other_exp.value = (parseFloat(document.frm.other_excipients.value) * parseFloat(document.frm.other_exp_cost.value) / 1000000).toFixed(3);//fix float=3

											document.frm.cost_tab.value = (parseFloat(document.frm.sum_other_exp.value) + parseFloat(document.frm.sum_cost_unit.value)).toFixed(3);//fix float=3
														
											//calculate lost 10%
											document.frm.lost_10.value = ((parseFloat(document.frm.cost_tab.value)*100 )/ 90).toFixed(3);//fix float=3

											//calculate vat 7%
											document.frm.vat_7.value = (parseFloat(document.frm.lost_10.value) * 1.07).toFixed(3);//fix float=3		
											
											document.frm.total_cost.value=(parseFloat(document.frm.vat_7.value) + parseFloat(document.frm.moh.value) + parseFloat(document.frm.profit.value) + parseFloat(document.frm.amber_glass.value) + parseFloat(document.frm.aluminum_screw.value) + parseFloat(document.frm.moh_packing.value) + parseFloat(document.frm.sticker_label.value)).toFixed(3);

											//cal jsp profit
											document.frm.jsp_profit_cost.value = (parseFloat(document.frm.total_cost.value) * parseFloat(document.frm.jsp_profit.value)).toFixed(3);
														
											//cal cdip profit
											document.frm.cdip_profit_cost.value = (parseFloat(document.frm.jsp_profit_cost.value) * parseFloat(document.frm.cdip_profit.value)).toFixed(3);

											//totla bluk
											document.frm.total_bluk.value = (parseFloat(document.frm.total_cost.value) + parseFloat(document.frm.jsp_profit_cost.value) + parseFloat(document.frm.cdip_profit_cost.value)).toFixed(3);
										}
										function fncBottle_Functional(){
											document.frm.total_cost.value = (parseFloat(document.frm.vat_7.value) + parseFloat(document.frm.moh.value) + parseFloat(document.frm.profit.value) + parseFloat(document.frm.amber_glass.value) + parseFloat(document.frm.aluminum_screw.value) + parseFloat(document.frm.moh_packing.value) + parseFloat(document.frm.sticker_label.value)).toFixed(3);//fix float=3

											//cal jsp profit
											document.frm.jsp_profit_cost.value = (parseFloat(document.frm.total_cost.value) * parseFloat(document.frm.jsp_profit.value)).toFixed(3);
														
											//cal cdip profit
											document.frm.cdip_profit_cost.value = (parseFloat(document.frm.jsp_profit_cost.value) * parseFloat(document.frm.cdip_profit.value)).toFixed(3);

											//totla bluk
											document.frm.total_bluk.value = (parseFloat(document.frm.total_cost.value) + parseFloat(document.frm.jsp_profit_cost.value) + parseFloat(document.frm.cdip_profit_cost.value)).toFixed(3);
										}
										function fncSumTotal_Instant(){
											var vat;
											document.frm.other_excipients.value = (parseFloat(document.frm.total_weight.value) - parseFloat(document.frm.total_quantities.value)).toFixed(3);//fix float=3

											document.frm.sum_other_exp.value = (parseFloat(document.frm.other_excipients.value) * parseFloat(document.frm.other_exp_cost.value) / 1000000).toFixed(3);//fix float=3

											document.frm.cost_tab.value = (parseFloat(document.frm.sum_other_exp.value) + parseFloat(document.frm.sum_cost_unit.value)).toFixed(3);//fix float=3
														
											//calculate lost 10%
											document.frm.lost_10.value = ((parseFloat(document.frm.cost_tab.value)*100 )/ 90).toFixed(3);//fix float=3

											//calculate vat 7%
											document.frm.vat_7.value = (parseFloat(document.frm.lost_10.value) * 1.07).toFixed(3);//fix float=3

											
											document.frm.total_cost.value=(parseFloat(document.frm.vat_7.value) + parseFloat(document.frm.moh.value) + parseFloat(document.frm.profit.value) + parseFloat(document.frm.sachet_cost.value)).toFixed(3);

											//cal jsp profit
											document.frm.jsp_profit_cost.value = (parseFloat(document.frm.total_cost.value) * parseFloat(document.frm.jsp_profit.value)).toFixed(3);
														
											//cal cdip profit
											document.frm.cdip_profit_cost.value = (parseFloat(document.frm.jsp_profit_cost.value) * parseFloat(document.frm.cdip_profit.value)).toFixed(3);

											//totla bluk
											document.frm.total_bluk.value = (parseFloat(document.frm.total_cost.value) + parseFloat(document.frm.jsp_profit_cost.value) + parseFloat(document.frm.cdip_profit_cost.value)).toFixed(3);
										}
										function fncInstant_drink(){
											document.frm.total_cost.value=(parseFloat(document.frm.vat_7.value) + parseFloat(document.frm.moh.value) + parseFloat(document.frm.profit.value) + parseFloat(document.frm.sachet_cost.value)).toFixed(3);

											//cal jsp profit
											document.frm.jsp_profit_cost.value = (parseFloat(document.frm.total_cost.value) * parseFloat(document.frm.jsp_profit.value)).toFixed(3);
														
											//cal cdip profit
											document.frm.cdip_profit_cost.value = (parseFloat(document.frm.jsp_profit_cost.value) * parseFloat(document.frm.cdip_profit.value)).toFixed(3);

											//totla bluk
											document.frm.total_bluk.value = (parseFloat(document.frm.total_cost.value) + parseFloat(document.frm.jsp_profit_cost.value) + parseFloat(document.frm.cdip_profit_cost.value)).toFixed(3);
										}

										function fncSumTotal_RM(){
											document.frm.rm_cost.value=(parseFloat(document.frm.rm_quantities.value) * parseFloat(document.frm.rm_price.value) / 1000000).toFixed(3);	
										}
										function fncSumTotal_RM2(){
											document.frm.rm_cost2.value=(parseFloat(document.frm.rm_quantities2.value) * parseFloat(document.frm.rm_price2.value) / 1000000).toFixed(3);	
										}
									</script>
									<!--end function calculate total cost-->
									<?php }?>
									<table width="100%" cellpadding="0" cellspacing="0" id="tb-cost">	
										<tr>
											<td class="w30 cost-td"><b>Ingredients</b></td>
											<td class="w15 cost-td"><b>Quantities</b></td>
											<td class="w10 cost-td"><b>Unit</b></td>
											<td class="w15 cost-td"><b>RM cost</b></td>
											<td class="w15 cost-td-bottm"><b>Cost/unit</b></td>
										</tr>
										<?php
										$total_quantities=0;
										$cost_unit=0;
										$sum_cost_unit=0;
										$sql_rm="select * from costing_rm where id_roc='".$rs_costing['id_costing_factory']."'";
										$res_rm=mysql_query($sql_rm) or die ('Error '.$sql_rm);
										while($rs_rm=mysql_fetch_array($res_rm)){
											$total_quantities=$total_quantities+$rs_rm['quantities'];
											$cost_unit=($rs_rm['cost_unit']);
											$sum_cost_unit=$sum_cost_unit+$cost_unit;
											if($rs_rm['id_costing_rm'] == $_GET['id_p'] and $_GET["action"] == 'edit_rm'){
										?>
										<tr>
											<td class="w30 cost-td">
												<input type="hidden" name="id_costing_rm" value="<?php echo $rs_rm['id_costing_rm']?>">
												<input type="hidden" <?php if($_GET['fac']==5){?>name="id_rm52" id="id_rm52"<?php }else{?> name="id_rm2" id="id_rm2"<?php }?>>
												<input type="text" <?php if($_GET['fac']==5){?>name="rm_name52" id="rm_name52" class="rm_name52"<?php }else{?> name="rm_name2" id="rm_name2" class="rm_name2"<?php }?>value="<?php echo $rs_rm['rm_name']?>">
											</td>
											<td class="w15 cost-td"><input type="text" name="rm_quantities2" id="rm_quantities2" value="<?php echo $rs_rm['quantities']?>" OnChange="fncSumTotal_RM2();"></td>
											<td class="w10 cost-td"><?php echo 'mg';?></td>
											<td class="w15 cost-td cost-right"><input type="text" <?php if($_GET['fac']==5){?>name="rm_price52" id="rm_price52"<?php }else{?>name="rm_price2" id="rm_price2"<?php }?> value="<?php echo number_format($rs_rm['rm_cost'],2)?>" OnChange="fncSumTotal_RM2();"></td>
											<td class="w15 cost-td-bottm">
												<div style="float:left; margin-right:2%;"><input type="text" name="rm_cost2" id="rm_cost2" value="<?php echo number_format($rs_rm['cost_unit'],3)?>"></div>
												<input name="btnAdd" type="button" id="btnUpdate" value="Update" OnClick="frm.hdnCmd.value='update_rm';JavaScript:return fncSubmit();" class="btn-update">
												<input name="btnAdd" type="button" id="btnCancel" value="Cancel" OnClick="window.location='<?=$_SERVER["PHP_SELF"]."?id_u=".$id?>&fac=<?php echo $_GET['fac']?>&p=2';" class="btn-cancel">
											</td>
										</tr>
										<?php }else{?>
										<tr>
											<td class="w30 cost-td"><?php echo $rs_rm['rm_name']?></td>
											<td class="w15 cost-td"><?php echo $rs_rm['quantities']?></td>
											<td class="w10 cost-td"><?php echo 'mg';?></td>
											<td class="w15 cost-td cost-right"><?php echo number_format($rs_rm['rm_cost'],2)?></td>
											<td class="w15 cost-td-bottm cost-right">
												<?php echo number_format($rs_rm['cost_unit'],3)?>
												<a href="<?=$_SERVER["PHP_SELF"];?>?id_u=<?php echo $rs_rm['id_roc']?>&fac=<?php echo $_GET['fac']?>&p=2&action=edit_rm&id_p=<?=$rs_rm['id_costing_rm'];?>"><img src="img/edit.png" style="width:20px;"></a>
												<a href="JavaScript:if(confirm('Confirm Delete?')==true){window.location='<?=$_SERVER["PHP_SELF"];?>?id_u=<?php echo $rs_rm['id_roc']?>&id_p=<?php echo $rs_rm['id_costing_rm']?>&fac=<?php echo $factory?>&action=del_rm&p=2';}"><img src="img/delete.png" style="width:20px;"></a>
											
											</td>
										</tr>
										<?php }?>
										<?php }?>
										<tr>
											<td class="w30 cost-td">
												<input type="hidden" <?php if($_GET['fac']==5){?>name="id_rm" id="id_rm5"<?php }else{?>name="id_rm" id="id_rm"<?php }?>>
												<input type="text" <?php if($_GET['fac']==5){?>name="rm_name" id="rm_name5" class="rm_name5"<?php }else{?>name="rm_name" id="rm_name" class="rm_name"<?php }?>>
											</td>
											<td class="w15 cost-td"><input type="text" name="rm_quantities" id="rm_quantities" OnChange="fncSumTotal_RM();"></td>
											<td class="w10 cost-td"><?php echo 'mg';?></td>
											<td class="w15 cost-td cost-right"><input type="text" <?php if($_GET['fac']==5){?>name="rm_price" id="rm_price5"<?php }else{?>name="rm_price" id="rm_price"<?php }?> OnChange="fncSumTotal_RM();"></td>
											<td class="w15 cost-td-bottm"><div style="float:left;"><input type="text" name="rm_cost" id="rm_cost"></div><input name="btnAdd" type="button" id="btnAdd" value="Add"  OnClick="frm.hdnCmd.value='add_rm';JavaScript:return fncSubmit();" class="btn-new-itenary"></td>
										</tr>										
										<?php if($_GET['fac']==1){?>
										<?php if($rs_costing['id_product_appearance']!=3){?>
										<tr>
											<input type="hidden" name="total_quantities" id="total_quantities" value="<?php echo $total_quantities?>">
											<input type="hidden" name="sum_cost_unit" id="sum_cost_unit" value="<?php echo number_format($sum_cost_unit,3)?>">
											<td class="bd-right b-bottom cost-left">Other excipient</td>
											<td class="bd-right b-bottom"><input type="text" name="other_excipients" id="other_excipients" value="<?php echo $rs_costing['other_excipients']?>"></td>
											<td class="bd-right b-bottom">mg</td>
											<td class="bd-right cost-td b-bottom cost-right"><input type="hidden" name="other_exp_cost" id="other_exp_cost" value="<?php if(($rs_costing['id_product_appearance']==1) || ($rs_costing['id_product_appearance']==2)){echo '500';}elseif(($rs_costing['id_product_appearance']==6) || ($rs_costing['id_product_appearance']==4) || ($rs_costing['id_product_appearance']==5)){echo '80';}?>"><?php if(($rs_costing['id_product_appearance']==1) || ($rs_costing['id_product_appearance']==2)){echo number_format('500',2);}elseif(($rs_costing['id_product_appearance']==4) || ($rs_costing['id_product_appearance']==6) || ($rs_costing['id_product_appearance']==5)){echo number_format('80',2);}?></td>
											<td class="b-bottom w10"><input type="text" name="sum_other_exp" id="sum_other_exp" value="<?php echo $rs_costing['sum_exp_cost']?>" <?php if($rs_account['role_user']==3){echo 'readonly';}?>>
										</tr>
										<?php }		
										if($rs_costing['id_product_appearance']==1){?>
										<input type="hidden" name="capsule_no" id="capsule_no" value="0.00">
										<tr>
											<td class="bd-right b-bottom cost-left">Total weight</td>
											<td class="bd-right b-bottom"><input type="text" name="total_weight" id="total_weight" OnChange="fncSumTotal_Tablet();" value="<?php echo $rs_costing['total_weight']?>"></td>
											<td class="bd-right b-bottom">mg</td>
											<td class="bd-right b-bottom cost-left">Cost per tab</td>
											<td class="b-bottom w10"><input type="text" name="cost_tab" id="cost_tab" value="<?php echo $rs_costing['cost_tab']?>" <?php if($rs_account['role_user']==3){echo 'readonly';}?>>
										</tr>
										<?php }elseif($rs_costing['id_product_appearance']==2){?>
										<tr>
											<td class="bd-right b-bottom cost-left">Total</td>
											<td class="bd-right b-bottom"><input type="text" name="total_weight" id="total_weight" OnChange="fncSumTotal_Capsule();" value="<?php echo $rs_costing['total_weight']?>"></td>
											<td class="bd-right b-bottom">mg</td>
											<td class="bd-right b-bottom cost-left"></td>
											<td class="b-bottom w10">
										</tr>
										<tr>
											<td class="bd-right b-bottom cost-left"><?php if($rs_costing['id_factory_weight']==4){echo 'Capsule no.0';}else{echo 'Capsule no.00';}?></td>
											<td class="bd-right b-bottom"><?php if($rs_costing['id_type_sub_product']==3){if($rs_costing['id_factory_weight']==4){echo '96';echo '<input type="hidden" name="cost_capsule" value="96.00">';}else{echo '118';echo '<input type="hidden" name="cost_capsule" value="118.00">';}}elseif($rs_costing['id_type_sub_product']==4){if($rs_costing['id_factory_weight']==4){echo '96';echo '<input type="hidden" name="cost_capsule" value="96.00">';}else{echo '118';echo '<input type="hidden" name="cost_capsule" value="118.00">';}}?></td>
											<td class="bd-right b-bottom">mg</td>
											<td class="bd-right b-bottom cost-left"></td>
											<td class="b-bottom w10"><?php if($rs_costing['id_type_sub_product']==3){if($rs_costing['id_factory_weight']==4){?><input type="hidden" name="capsule_no" id="capsule_no" value="0.20">0.20<?php }else{?><input type="hidden" name="capsule_no" id="capsule_no" value="0.25">0.25<?php }}elseif($rs_costing['id_type_sub_product']==4){if($rs_costing['id_factory_weight']==4){?><input type="hidden" name="capsule_no" id="capsule_no" value="0.30">0.30<?php }else{?><input type="hidden" name="capsule_no" id="capsule_no" value="0.35">0.35<?php }}?></td>
										</tr>
										<tr>
											<td class="bd-right b-bottom cost-left">Total weight</td>
											<td class="bd-right b-bottom"><input type="text" name="total_weight_capsule" id="total_weight_capsule" value="<?php echo $rs_costing['total_weight_capsule']?>"></td>
											<td class="bd-right b-bottom">mg</td>
											<td class="bd-right b-bottom cost-left">Cost per tab</td>
											<td class="b-bottom w10"><input type="text" name="cost_tab" id="cost_tab" value="<?php echo $rs_costing['cost_tab']?>" <?php if($rs_account['role_user']==3){echo 'readonly';}?>>
										</tr>										
										<?php }elseif($rs_costing['id_product_appearance']==3){?>
										<input type="hidden" name="capsule_no" id="capsule_no" value="0.00">
										<tr>
											<td class="bd-right b-bottom cost-left">Total weight</td>
											<td class="bd-right b-bottom"><input type="text" name="total_weight" id="total_weight" value="<?php echo $total_quantities?>"></td>
											<td class="bd-right b-bottom">mg</td>
											<td class="bd-right b-bottom cost-left">Cost per tab</td>
											<td class="b-bottom w10"><input type="text" name="cost_tab" id="cost_tab" value="<?php echo number_format($sum_cost_unit,3)?>" <?php if($rs_account['role_user']==3){echo 'readonly';}?>>
										</tr>
										<?php }elseif($rs_costing['id_product_appearance']==6){ //Functional drink?>
										<tr>
											<td class="bd-right b-bottom cost-left">Total</td>
											<td class="bd-right b-bottom"><input type="text" name="total_weight" id="total_weight" OnChange="fncSumTotal_Functional_drink();" value="<?php echo $rs_costing['total_weight']?>"></td>
											<td class="bd-right b-bottom">mg</td>
											<td class="bd-right b-bottom cost-left">Cost per bottle</td>
											<td class="b-bottom w10"><input type="text" name="cost_tab" id="cost_tab" value="<?php echo $rs_costing['cost_tab']?>" <?php if($rs_account['role_user']==3){echo 'readonly';}?>>
										</tr>
										<?php }elseif(($rs_costing['id_product_appearance']==4) || ($rs_costing['id_product_appearance']==5)){ //Instant drink?>
										<tr>
											<td class="bd-right b-bottom cost-left">Total</td>
											<td class="bd-right b-bottom"><input type="text" name="total_weight" id="total_weight" OnChange="fncSumTotal_Instant();" value="<?php echo $rs_costing['total_weight']?>"></td>
											<td class="bd-right b-bottom">mg</td>
											<td class="bd-right b-bottom cost-left">Cost per sachet</td>
											<td class="b-bottom w10"><input type="text" name="cost_tab" id="cost_tab" value="<?php echo $rs_costing['cost_tab']?>" <?php if($rs_account['role_user']==3){echo 'readonly';}?>>
										</tr>
										<?php }?>
										<tr>
											<td rowspan="5" colspan="2"></td>
											<td class="bd-right"></td>
											<td class="bd-right b-bottom cost-left">Loss 10%</td>
											<td class="b-bottom w10"><input type="text" name="lost_10" id="lost_10" value="<?php if($rs_costing['id_product_appearance']==3){echo number_format(($sum_cost_unit*100)/90,3);}else{echo $rs_costing['lost_10'];}?>" <?php if($rs_account['role_user']==3){echo 'readonly';}?>>
										</tr>
										<tr>
											<td class="bd-right"></td>
											<td class="bd-right b-bottom cost-left"><?php if($rs_costing['id_product_appearance']==1){echo 'Cost per tab inc Vat 7%';}elseif(($rs_costing['id_product_appearance']==2) || ($rs_costing['id_product_appearance']==3)){echo 'Cost per cap inc Vat 7%';}elseif($rs_costing['id_product_appearance']==6){echo 'Cost per bottle inc Vat 7%';}elseif(($rs_costing['id_product_appearance']==4) || ($rs_costing['id_product_appearance']==5)){echo 'Cost per bottle inc Vat 7%';}?></td>
											<td class="b-bottom w10"><input type="text" name="vat_7" id="vat_7" value="<?php if($rs_costing['id_product_appearance']==3){$vat=(($sum_cost_unit*100)/90)*1.07; echo number_format((($sum_cost_unit*100)/90)*1.07,3);}else{echo $rs_costing['vat_7'];}?>" <?php if($rs_account['role_user']==3){echo 'readonly';}?>>
										</tr>
										<tr>
											<?php if($rs_costing['id_product_appearance']==6){ //Functional drink?>	
											<td class="bd-right"></td>
											<td class="bd-right b-bottom cost-left">MOH Mixing</td>
											<td class="b-bottom w10"><input type="text" name="moh" id="moh" value="<?php if($rs_costing['moh_mixing']==''){echo '2.00';}else{echo $rs_costing['moh_mixing'];}?>" OnChange="fncBottle_Functional();"></td>
											<?php }else{?>
											<td class="bd-right"></td>
											<td class="bd-right b-bottom cost-left">MOH</td>
											<td class="b-bottom w10">
												<?php 
												if(($rs_costing['id_product_appearance']==4) || ($rs_costing['id_product_appearance']==5)){
													if($rs_costing['id_factory_weight']!=13){
														echo '<input type="text" name="moh" id="moh" value="0.50" OnChange="fncInstant_drink();">';
													}else{
														echo '<input type="text" name="moh" id="moh" value="1.50" OnChange="fncInstant_drink();">';
													}
												}

												if($rs_costing['id_type_sub_product']==1){ // Core Tablet
													if($rs_costing['id_factory_weight']==1){
														echo '1.00';
														echo '<input type="hidden" name="moh" value="1.00">';
													}elseif($rs_costing['id_factory_weight']==2){
														echo '0.70';
														echo '<input type="hidden" name="moh" value="0.70">';
													}elseif($rs_costing['id_factory_weight']==3){
														echo '0.50';
														echo '<input type="hidden" name="moh" value="0.50">';
													}
												}elseif($rs_costing['id_type_sub_product']==2){ // F/C Tablet
													if($rs_costing['id_factory_weight']==1){
														echo '1.20';
														echo '<input type="hidden" name="moh" value="1.00">';
													}elseif($rs_costing['id_factory_weight']==2){
														echo '1.00';
														echo '<input type="hidden" name="moh" value="0.70">';
													}elseif($rs_costing['id_factory_weight']==3){
														echo '0.70';
														echo '<input type="hidden" name="moh" value="0.50">';
													}
												}elseif(($rs_costing['id_type_sub_product']==3) || ($rs_costing['id_type_sub_product']==4)){ // capsule
														echo '0.40';
														echo '<input type="hidden" name="moh" value="0.40">';
												}elseif($rs_costing['id_product_appearance']==3){ //softgel
													if($rs_costing['id_factory_weight']==6){ //softgel 250
														echo '0.25';
														echo '<input type="hidden" name="moh" value="0.25">';
													}else
													if($rs_costing['id_factory_weight']==7){ //softgel 500
														echo '0.35';
														echo '<input type="hidden" name="moh" value="0.35">';
													}else
													if($rs_costing['id_factory_weight']==8){ //softgel 500
														echo '0.60';
														echo '<input type="hidden" name="moh" value="0.60">';
													}
												}
												?>
												</td>
												<?php }?>
											</tr>											
											<?php //profit per tablet
											if(($rs_costing['vat_7'] >= 1) || ($rs_costing['vat_7'] <= 3.50)){
												$profit=0.375;
											}elseif(($rs_costing['vat_7'] >= 3.51) || ($rs_costing['vat_7'] <= 4.50)){
												$profit=0.50;
											}elseif(($rs_costing['vat_7'] >= 4.51) || ($rs_costing['vat_7'] <= 5.50)){
												$profit=0.625;
											}elseif(($rs_costing['vat_7'] >= 5.51) || ($rs_costing['vat_7'] <= 6.50)){
												$profit=0.750;
											}elseif(($rs_costing['vat_7'] >= 6.51) || ($rs_costing['vat_7'] <= 7.50)){
												$profit=0.875;
											}elseif($rs_costing['vat_7'] >= 7.51){
												$profit=1.00;
											}
											?>
											<tr>
											<?php if($rs_costing['id_product_appearance']!=3){?>
												<td class="bd-right"></td>
												<td class="bd-right b-bottom cost-left"><?php if($rs_costing['id_product_appearance']==1){echo 'Profit per tablet';}elseif($rs_costing['id_product_appearance']==2){echo 'Profit per cap';}elseif($rs_costing['id_product_appearance']==6){echo 'Profit per bottle';}elseif(($rs_costing['id_product_appearance']==4) || ($rs_costing['id_product_appearance']==5)){echo 'Profit per sac';}?></td>
												<td class="b-bottom w10"><?php if(($rs_costing['id_product_appearance']==1) || ($rs_costing['id_product_appearance']==2)){?><input type="hidden" name="profit" id="profit" value="<?php echo $profit?>"><?php echo $profit?><?php }elseif($rs_costing['id_product_appearance']==6){?><input type="text" name="profit" id="profit" value="0.50" OnChange="fncBottle_Functional();"><?php }elseif(($rs_costing['id_product_appearance']==4) || ($rs_costing['id_product_appearance']==5)){?><input type="text" name="profit" id="profit" value="0.50" OnChange="fncInstant_drink();"><?php }?>
											<?php }?>
											</tr>
											<?php if($rs_costing['id_product_appearance']==6){?>
											<tr>											
												<td class="bd-right"></td>
												<td class="bd-right b-bottom cost-left">Amber glass Bottle</td>
												<td class="b-bottom w10"><input type="text" name="amber_glass" id="amber_glass" value="<?php if($rs_costing['amber_glass']==0){echo '1.90';}else{echo $rs_costing['amber_glass'];}?>" OnChange="fncBottle_Functional();"></td>
											</tr>
											<tr>											
												<td class="bd-right" colspan="3"></td>
												<td class="bd-right b-bottom cost-left">Aluminum screw cap 1 color</td>
												<td class="b-bottom w10"><input type="text" name="aluminum_screw" id="aluminum_screw" value="<?php if($rs_costing['aluminum_screw']==0){echo '0.60';}else{echo $rs_costing['aluminum_screw'];}?>" OnChange="fncBottle_Functional();"></td>
											</tr>
											<tr>											
												<td class="bd-right" colspan="3"></td>
												<td class="bd-right b-bottom cost-left">MOH Packing</td>
												<td class="b-bottom w10"><input type="text" name="moh_packing" id="moh_packing" value="2.00" OnChange="fncBottle_Functional();"></td>
											</tr>
											<tr>											
												<td class="bd-right" colspan="3"></td>
												<td class="bd-right b-bottom cost-left">Sticker label</td>
												<td class="b-bottom w10"><input type="text" name="sticker_label" id="sticker_label" value="1.50" OnChange="fncBottle_Functional();"></td>
											</tr>
											<?php }?>
											<?php if(($rs_costing['id_product_appearance']==4) || ($rs_costing['id_product_appearance']==5)){?>
											<tr>											
												<td class="bd-right"></td>
												<td class="bd-right b-bottom cost-left">Sachet cost 4 color</td>
												<td class="b-bottom w10"><input type="text" name="sachet_cost" id="sachet_cost" value="0.75" OnChange="fncInstant_drink();"></td>
											</tr>
											<?php }?>
											<tr>
											<?php if($rs_costing['id_product_appearance']!=3){?>
												<td class="bd-right" <?php if(($rs_costing['id_product_appearance']==6) || ($rs_costing['id_product_appearance']==4) || ($rs_costing['id_product_appearance']==5) ){echo 'colspan="3"';}?>></td>
												<td class="bd-right b-bottom cost-left"><?php if($rs_costing['id_product_appearance']==1){echo 'Total cost per tablet';}elseif($rs_costing['id_product_appearance']==2){echo 'Total cost per cap';}elseif($rs_costing['id_product_appearance']==6){echo 'Total cost per bottle';}elseif(($rs_costing['id_product_appearance']==4) || ($rs_costing['id_product_appearance']==5)){echo 'Total cost per sac';}?></td>
												<td class="b-bottom"><input type="text" name="total_cost" id="total_cost" value="<?php echo number_format($rs_costing['total_cost'],3)?>" <?php if($rs_account['role_user']==3){echo 'readonly';}?>>
											<?php }?>
											</tr>											
											<?php
											if(($rs_costing['id_product_appearance']==1) || ($rs_costing['id_product_appearance']==2)){
												if($rs_costing['moq']=='75,000-149,999'){
													echo '<input type="hidden" name="jsp_profit" id="jsp_profit" value="0.25">';
													echo '<input type="hidden" name="cdip_profit" id="cdip_profit" value="0.35">';
												}
												elseif($rs_costing['moq']=='150,000-299,999'){
													echo '<input type="hidden" name="jsp_profit" id="jsp_profit" value="0.20">';
													echo '<input type="hidden" name="cdip_profit" id="cdip_profit" value="0.25">';
												}
												elseif($rs_costing['moq']=='300,000-499,999'){
													echo '<input type="hidden" name="jsp_profit" id="jsp_profit" value="0.15">';
													echo '<input type="hidden" name="cdip_profit" id="cdip_profit" value="0.20">';
												}
												elseif($rs_costing['moq']=='500,000-999,999'){
													echo '<input type="hidden" name="jsp_profit" id="jsp_profit" value="0.10">';
													echo '<input type="hidden" name="cdip_profit" id="cdip_profit" value="0.15">';
												}
												elseif($rs_costing['moq']=='up to 1,000,000'){
													echo '<input type="hidden" name="jsp_profit" id="jsp_profit" value="0.05">';
													echo '<input type="hidden" name="cdip_profit" id="cdip_profit" value="0.10">';
												}
											}elseif(($rs_costing['id_product_appearance']==6) || ($rs_costing['id_product_appearance']==4) || ($rs_costing['id_product_appearance']==5)){
												if($rs_costing['moq']=='75,000-149,999'){
													echo '<input type="hidden" name="jsp_profit" id="jsp_profit" value="0.15">';
													echo '<input type="hidden" name="cdip_profit" id="cdip_profit" value="0.15">';
												}
												elseif($rs_costing['moq']=='150,000-299,999'){
													echo '<input type="hidden" name="jsp_profit" id="jsp_profit" value="0.10">';
													echo '<input type="hidden" name="cdip_profit" id="cdip_profit" value="0.10">';
												}
												elseif($rs_costing['moq']=='300,000-499,999'){
													echo '<input type="hidden" name="jsp_profit" id="jsp_profit" value="0.08">';
													echo '<input type="hidden" name="cdip_profit" id="cdip_profit" value="0.08">';
												}
												elseif($rs_costing['moq']=='500,000-999,999'){
													echo '<input type="hidden" name="jsp_profit" id="jsp_profit" value="0.05">';
													echo '<input type="hidden" name="cdip_profit" id="cdip_profit" value="0.05">';
												}
											}
											?>
											<?php if($rs_costing['id_product_appearance']!=3){?>
											<tr>											
												<td class="bd-right" colspan="3"></td>
												<td class="bd-right b-bottom cost-left">JSP Profit</td>
												<td class="b-bottom">
													<input type="text" name="jsp_profit_cost" id="jsp_profit_cost" value="<?php echo $rs_costing['jsp_profit_cost']?>">
												</td>
											</tr>
											<tr>
												<td class="bd-right" colspan="3"></td>
												<td class="bd-right b-bottom cost-left">CDIP Profit</td>
												<td class="b-bottom"><input type="text" name="cdip_profit_cost" id="cdip_profit_cost" value="<?php echo $rs_costing['cdip_profit_cost']?>"></td>
											</tr>
											<?php }elseif($rs_costing['id_product_appearance']==3){?>
											<tr>											
												<td class="bd-right" colspan="3"></td>
												<td class="bd-right b-bottom cost-left">JSP Profit per cap</td>
												<td class="b-bottom"><input type="hidden" name="jsp_profit_cost" id="jsp_profit_cost" value="0.25"><?php echo '0.25'?></td>
											</tr>
											<tr>
												<td class="bd-right" colspan="3"></td>
												<td class="bd-right b-bottom cost-left">Total cost per cap</td>
												<td class="b-bottom"><input type="text" name="total_cost" id="total_cost" value="<?php if($rs_costing['id_factory_weight']==6){$total_cost=$vat+0.25+0.25; echo number_format($total_cost,3);}elseif($rs_costing['id_factory_weight']==7){$total_cost=$vat+0.35+0.25; echo number_format($total_cost,3);}elseif($rs_costing['id_factory_weight']==8){$total_cost=$vat+0.60+0.25; echo number_format($total_cost,3);}?>" <?php if($rs_account['role_user']==3){echo 'readonly';}?>>
											</tr>
											<tr>
											<?php if($rs_costing['moq']=='75,000-149,999'){?>
												<td class="bd-right" colspan="3"></td>
												<td class="bd-right b-bottom cost-left">CDIP Profit</td>
												<td><input type="text" name="cdip_profit_cost" id="cdip_profit_cost" value="<?php echo number_format($cdip_profit=$total_cost*0.35,3)?>"></td>
											<?php }elseif($rs_costing['moq']=='150,000-299,999'){?>
												<td class="bd-right" colspan="3"></td>
												<td class="bd-right b-bottom cost-left">CDIP Profit</td>
												<td><input type="text" name="cdip_profit_cost" id="cdip_profit_cost" value="<?php echo number_format($cdip_profit=$total_cost*0.25,3)?>"></td>
											<?php }elseif($rs_costing['moq']=='300,000-499,999'){?>
												<td class="bd-right" colspan="3"></td>
												<td class="bd-right b-bottom cost-left">CDIP Profit</td>
												<td><input type="text" name="cdip_profit_cost" id="cdip_profit_cost" value="<?php echo number_format($cdip_profit=$total_cost*0.20,3)?>"></td>
											<?php }elseif($rs_costing['moq']=='500,000-999,999'){?>
												<td class="bd-right" colspan="3"></td>
												<td class="bd-right b-bottom cost-left">CDIP Profit</td>
												<td><input type="text" name="cdip_profit_cost" id="cdip_profit_cost" value="<?php echo number_format($cdip_profit=$total_cost*0.15,3)?>"></td>
											<?php }elseif($rs_costing['moq']=='up to 1,000,000'){?>
												<td class="bd-right" colspan="3"></td>
												<td class="bd-right b-bottom cost-left">CDIP Profit</td>
												<td><input type="text" name="cdip_profit_cost" id="cdip_profit_cost" value="<?php echo number_format($cdip_profit=$total_cost*0.10,3)?>"></td>
											<?php }?>												
											</tr>
											<?php }?>
											<tr>
												<td class="bd-right" colspan="3"></td>
												<td class="bd-right cost-left">Total bluk</td>
												<td><input type="text" name="total_bluk" id="total_bluk" value="<?php if($rs_costing['id_product_appearance']!=3){echo $rs_costing['total_bluk'];}elseif($rs_costing['id_product_appearance']==3){echo number_format($total_cost+$cdip_profit,3);}?>"></td>
											</tr>
											<?php }//end factory jsp
											elseif($_GET['fac']==3){?>
											<!--function calculate total cost-->
											<script language="JavaScript">
												function fncSumTotal_Tablet(){
													var vat;		
													document.frm.other_excipients.value = (parseFloat(document.frm.total_weight.value) - parseFloat(document.frm.total_quantities.value)).toFixed(3);//fix float=3

													document.frm.sum_other_exp.value = (parseFloat(document.frm.other_excipients.value) * parseFloat(document.frm.other_exp_cost.value) / 1000000).toFixed(3);//fix float=3

													document.frm.cost_tab.value = (parseFloat(document.frm.sum_other_exp.value) + parseFloat(document.frm.sum_cost_unit.value)).toFixed(3);//fix float=3
													
													document.frm.total_bottle.value = (parseFloat(document.frm.cost_tab.value) + parseFloat(document.frm.filling_moh.value)).toFixed(3);//fix float=3

													document.frm.bottle_set.value = (parseFloat(document.frm.glass_bottle.value) + parseFloat(document.frm.screw_cap.value) + parseFloat(document.frm.moh.value)).toFixed(3);//fix float=3

													document.frm.total_cost.value = (parseFloat(document.frm.total_bottle.value) + parseFloat(document.frm.bottle_set.value)).toFixed(3);//fix float=3

													//calculate loss 15%
													document.frm.loss_15.value = ((parseFloat(document.frm.total_cost.value)*100 )/ 85).toFixed(3);//fix float=3

													//calculate vat 7%
													document.frm.vat_7.value = (parseFloat(document.frm.loss_15.value) * 1.07).toFixed(3);//fix float=3

													//calculate total cos per tablet
													document.frm.total_cost2.value = parseFloat(document.frm.vat_7.value).toFixed(3);
													
													//cal prima profit
													document.frm.prima_profit_cost.value = (parseFloat(document.frm.total_cost2.value) * parseFloat(document.frm.prima_profit.value)).toFixed(3);
															
													//cal cdip profit
													document.frm.cdip_profit_cost.value = (parseFloat(document.frm.total_cost2.value) * parseFloat(document.frm.cdip_profit.value)).toFixed(3);

													//totla bluk
													document.frm.total_bluk.value = (parseFloat(document.frm.total_cost2.value) + parseFloat(document.frm.prima_profit_cost.value) + parseFloat(document.frm.cdip_profit_cost.value)).toFixed(3);
												}												
											</script>
											<!--end function calculate total cost-->
											<tr>
												<input type="hidden" name="total_quantities" id="total_quantities" value="<?php echo $total_quantities?>">
												<input type="hidden" name="sum_cost_unit" id="sum_cost_unit" value="<?php echo $sum_cost_unit?>">
												<td class="bd-right b-bottom cost-left">Other excipients</td>
												<td class="bd-right b-bottom"><input type="text" name="other_excipients" id="other_excipients" value="<?php echo $rs_prima['other_excipients']?>"></td>
												<td class="bd-right b-bottom">mg</td>
												<td class="bd-right cost-td b-bottom cost-right"><input type="hidden" name="other_exp_cost" id="other_exp_cost" value="80"><?php echo number_format('80',2)?></td>
												<td class="b-bottom w10"><input type="text" name="sum_other_exp" id="sum_other_exp" value="<?php echo $rs_prima['sum_exp_cost']?>" <?php if($rs_account['role_user']==3){echo 'readonly';}?>>
											</tr>
											<tr>
											<td class="bd-right b-bottom cost-left">Total</td>
											<td class="bd-right b-bottom"><input type="text" name="total_weight" id="total_weight" OnChange="fncSumTotal_Tablet();" value="<?php echo $rs_prima['total_weight']?>"></td>
											<td class="bd-right b-bottom">mg</td>
											<td class="bd-right b-bottom cost-left">Cost per tab</td>
											<td class="b-bottom w10"><input type="text" name="cost_tab" id="cost_tab" value="<?php echo $rs_prima['cost_tab']?>" <?php if($rs_account['role_user']==3){echo 'readonly';}?>>
											</tr>
											<tr>											
												<td class="bd-right" colspan="3"></td>
												<td class="bd-right b-bottom cost-left">Filling MOH</td>
												<td class="b-bottom"><input type="text" name="filling_moh" id="filling_moh" value="<?php if($rs_costing['id_factory_weight']==1){if($rs_prima['prima_filling_moh'] != 0){echo $rs_prima['prima_filling_moh'];}else{echo '1.75';}}elseif($rs_costing['id_factory_weight']==2){if($rs_prima['prima_filling_moh'] != 0){echo $rs_prima['prima_filling_moh'];}else{echo '2.00';}}elseif($rs_costing['id_factory_weight']==3){if($rs_prima['prima_filling_moh'] != 0){echo $rs_prima['prima_filling_moh'];}else{echo '2.00';}}elseif($rs_costing['id_factory_weight']==4){if($rs_prima['prima_filling_moh'] != 0){echo $rs_prima['prima_filling_moh'];}else{echo '2.50';}}?>"></td>
											</tr>
											<tr>											
												<td class="bd-right" colspan="3"></td>
												<td class="bd-right b-bottom cost-left">Total cost per bottle</td>
												<td class="b-bottom"><input type="text" name="total_bottle" id="total_bottle" value="<?php echo $rs_prima['prima_total_bottle']?>"></td>
											</tr>
											<tr>											
												<td class="bd-right" colspan="3"></td>
												<td class="bd-right b-bottom cost-left">Bottle set</td>
												<td class="b-bottom"><input type="text" name="bottle_set" id="bottle_set" value="<?php echo $rs_prima['prima_bottle_set']?>"></td>
											</tr>
											<tr>											
												<td class="bd-right" colspan="3"></td>
												<td class="bd-right b-bottom cost-left">-Glass Bottle</td>
												<td class="b-bottom"><input type="text" name="glass_bottle" id="glass_bottle" value="<?php if($rs_costing['id_factory_weight']==1){if($rs_prima != 0){echo $rs_prima['prima_glass_bottle'];}else{echo '2.33';}}elseif($rs_costing['id_factory_weight']==2){if($rs_prima['prima_glass_bottle'] != 0){echo $rs_prima['prima_glass_bottle'];}else{echo '2.90';}}elseif($rs_costing['id_factory_weight']==3){if($rs_prima['prima_glass_bottle'] != 0){echo $rs_prima['prima_glass_bottle'];}else{echo '3.50';}}elseif($rs_costing['id_factory_weight']==4){if($rs_prima['prima_glass_bottle'] != 0){echo $rs_prima['prima_glass_bottle'];}else{echo '3.50';}}?>"></td>
											</tr>
											<tr>											
												<td class="bd-right" colspan="3"></td>
												<td class="bd-right b-bottom cost-left">-Screw Cap</td>
												<td class="b-bottom"><input type="text" name="screw_cap" id="screw_cap" value="<?php if($rs_costing['id_factory_weight']==1){if($rs_prima != 0){echo $rs_prima['prima_screw_cap'];}else{echo '2.00';}}elseif($rs_costing['id_factory_weight']==2){if($rs_prima['prima_screw_cap'] != 0){echo $rs_prima['prima_screw_cap'];}else{echo '1.10';}}elseif($rs_costing['id_factory_weight']==3){if($rs_prima['prima_screw_cap'] != 0){echo $rs_prima['prima_screw_cap'];}else{echo '1.10';}}elseif($rs_costing['id_factory_weight']==4){if($rs_prima['prima_screw_cap'] != 0){echo $rs_prima['prima_screw_cap'];}else{echo '1.90';}}?>"></td>
											</tr>		
											<tr>											
												<td class="bd-right" colspan="3"></td>
												<td class="bd-right b-bottom cost-left">-MOH</td>
												<td class="b-bottom"><input type="text" name="moh" id="moh" value="<?php if($rs_prima['prima_moh'] != 0){echo $rs_prima['prima_moh'];}else{echo '0.25';}?>"></td>
											</tr>
											<tr>
												<td class="bd-right" colspan="3"></td>
												<td class="bd-right b-bottom cost-left">Total cost per bottle</td>
												<td class="b-bottom"><input type="text" name="total_cost" id="total_cost" value="<?php echo $rs_prima['prima_total_cost']?>">
											</tr>
											<tr>
												<td class="bd-right" colspan="3"></td>
												<td class="bd-right b-bottom cost-left">Loss 15%</td>
												<td class="b-bottom"><input type="text" name="loss_15" id="loss_15" value="<?php echo $rs_prima['prima_loss_15']?>">
											</tr>
											<tr>
												<td class="bd-right" colspan="3"></td>
												<td class="bd-right b-bottom cost-left">Cost per bottle inc Vat 7%</td>
												<td class="b-bottom"><input type="text" name="vat_7" id="vat_7" value="<?php echo $rs_prima['prima_vat_7']?>">
											</tr>
											<tr>
												<td class="bd-right" colspan="3"></td>
												<td class="bd-right b-bottom cost-left">Total cost per bottle</td>
												<td class="b-bottom"><input type="text" name="total_cost2" id="total_cost2" value="<?php echo $rs_prima['prima_total_cost2']?>">
											</tr>
											<tr>
											<?php if($rs_costing['moq']=='100,000-300,000'){?>
												<td class="bd-right" colspan="3"></td>
												<td class="bd-right b-bottom cost-left">Prima Profit 1.15</td>
												<td class="bd-right b-bottom cost-left">
													<input type="hidden" name="prima_profit" id="prima_profit" value="0.15">
													<input type="text" name="prima_profit_cost" id="prima_profit_cost" value="<?php echo $rs_prima['prima_profit_cost']?>">
												</td>
											<?php }elseif($rs_costing['moq']=='300,001-500,000'){?>
												<td class="bd-right" colspan="3"></td>
												<td class="bd-right b-bottom cost-left">Prima Profit 1.15</td>
												<td class="bd-right b-bottom cost-left">
													<input type="hidden" name="prima_profit" id="prima_profit" value="0.12">
													<input type="text" name="prima_profit_cost" id="prima_profit_cost" value="<?php echo $rs_prima['prima_profit_cost']?>">
												</td>
											<?php }elseif($rs_costing['moq']=='up to 500,001'){?>
												<td class="bd-right" colspan="3"></td>
												<td class="bd-right b-bottom cost-left">Prima Profit 1.15</td>
												<td class="bd-right b-bottom cost-left">
													<input type="hidden" name="prima_profit" id="prima_profit" value="0.10">
													<input type="text" name="prima_profit_cost" id="prima_profit_cost" value="<?php echo $rs_prima['prima_profit_cost']?>">
												</td>
											<?php }?>												
											</tr>
											<tr>
												<?php if($rs_costing['moq']=='100,000-300,000'){?>
												<td class="bd-right" colspan="3"></td>
												<td class="bd-right b-bottom cost-left">CDIP Profit</td>
												<td class="bd-right b-bottom cost-left">
													<input type="hidden" name="cdip_profit" id="cdip_profit" value="0.15">
													<input type="text" name="cdip_profit_cost" id="cdip_profit_cost" value="<?php echo $rs_prima['cdip_profit_cost']?>">
												</td>
											<?php }elseif($rs_costing['moq']=='300,001-500,000'){?>
												<td class="bd-right" colspan="3"></td>
												<td class="bd-right b-bottom cost-left">CDIP Profit</td>
												<td class="bd-right b-bottom cost-left">
													<input type="hidden" name="cdip_profit" id="cdip_profit" value="0.12">
													<input type="text" name="cdip_profit_cost" id="cdip_profit_cost" value="<?php echo $rs_prima['cdip_profit_cost']?>">
												</td>
											<?php }elseif($rs_costing['moq']=='up to 500,001'){?>
												<td class="bd-right" colspan="3"></td>
												<td class="bd-right b-bottom cost-left">CDIP Profit</td>
												<td class="bd-right b-bottom cost-left">
													<input type="hidden" name="cdip_profit" id="cdip_profit" value="0.10">
													<input type="text" name="cdip_profit_cost" id="cdip_profit_cost" value="<?php echo $rs_prima['cdip_profit_cost']?>">
												</td>
											<?php }?>	
											</tr>
											<tr>
												<td class="bd-right" colspan="3"></td>
												<td class="bd-right cost-left">Total bluk</td>
												<td><input type="text" name="total_bluk" id="total_bluk" value="<?php echo $rs_prima['total_bluk']?>"></td>
											</tr>
											<?php }//end factory prima food?>
									</table>
								<?php }elseif($_GET['p']==3){?>
									<input type="hidden" name="nextstep" value="3">
									<input type="hidden" name="id_product_appearance" value="<?php echo $rs_costing['id_product_appearance']?>">
									<tr>
										<td colspan="2"><h3>Step 3 : Packaging detail</h3></td>
									</tr>
									<?php if($_GET['fac']==1){?>
									<script type="text/javascript">
										function ShowPack() {
											if (document.getElementById('boxCheck').checked) {
												document.getElementById('tb-cost').style.display = '';
												document.getElementById('tb-cost2').style.display = 'none';
											}
											else
											if (document.getElementById('bottleCheck').checked) {
												document.getElementById('tb-cost').style.display = 'none';
												document.getElementById('tb-cost2').style.display = '';
											}
										}
									</script>
									<!--function calculate sum cost per tablet-->
									<script language="JavaScript">
										function fnccheck_blister(){

											var t1; var t2; var t3; var t4; var t5; var t6; var t7; var t8; var t9;
											var t10; var t11; var sum;											

											//moh_packing_oem
											if(document.frm.jsp_blister0.checked == true){
												document.frm.jsp_cost0.value= parseFloat(4.00).toFixed(2);
												t1=parseFloat(document.frm.jsp_cost0.value).toFixed(3);

											}else{	
												t1=0;
												document.frm.jsp_cost0.value='';
											} 
											
											//moh_packing_fee
											if(document.frm.jsp_blister1.checked == true){
												document.frm.jsp_cost1.value= parseFloat(10.00).toFixed(2);
												t2=parseFloat(document.frm.jsp_cost1.value).toFixed(3);
											}else{	
												t2=0;	
												document.frm.jsp_cost1.value='';
											} 

											//blister
											if(document.frm.jsp_blister2.checked == true){
												document.frm.jsp_cost2.value= (parseFloat(1.50) * parseFloat(document.frm.num_blister2.value)).toFixed(2);
												t3=parseFloat(document.frm.jsp_cost2.value).toFixed(3);
											}else{	
												t3=0;
												document.frm.jsp_cost2.value='';
											}

											//silica_gel
											if(document.frm.jsp_blister3.checked == true){
												document.frm.jsp_cost3.value= (parseFloat(0.50) * parseFloat(document.frm.num_blister3.value)).toFixed(2);
												t4=parseFloat(document.frm.jsp_cost3.value).toFixed(3);
											}else{	
												t4=0;	
												document.frm.jsp_cost3.value='';
											}

											//aliminum_pouch
											if(document.frm.jsp_blister4.checked == true){
												document.frm.jsp_cost4.value= (parseFloat(1.50) * parseFloat(document.frm.num_blister4.value)).toFixed(2);
												t5=parseFloat(document.frm.jsp_cost4.value).toFixed(3);
											}else{	
												t5=0;	
												document.frm.jsp_cost4.value='';
											}
											
											//film_shrink
											if(document.frm.jsp_blister5.checked == true){
												document.frm.jsp_cost5.value= parseFloat(10.00).toFixed(2);
												t6=parseFloat(document.frm.jsp_cost5.value).toFixed(3);
											}else{	
												t6=0;	
												document.frm.jsp_cost5.value='';
											}

											//carton
											if(document.frm.jsp_blister6.checked == true){
												document.frm.jsp_cost6.value= parseFloat(12.00).toFixed(2);
												t7=parseFloat(document.frm.jsp_cost6.value).toFixed(3);
											}else{	
												t7=0;	
												document.frm.jsp_cost6.value='';
											}

											//delivery
											if(document.frm.jsp_blister7.checked == true){
												document.frm.jsp_cost7.value= parseFloat(15.00).toFixed(2);
												t8=parseFloat(document.frm.jsp_cost7.value).toFixed(3);
											}else{	
												t8=0;
												document.frm.jsp_cost7.value='';
											}

											//box310
											if(document.frm.jsp_blister8.checked == true){
												document.frm.jsp_cost8.value= parseFloat(10.00).toFixed(2);
												t9=parseFloat(document.frm.jsp_cost8.value).toFixed(3);
											}else{	
												t9=0;	
												document.frm.jsp_cost8.value='';
											}

											//box350
											if(document.frm.jsp_blister9.checked == true){
												document.frm.jsp_cost9.value= parseFloat(0.50).toFixed(2);
												t10=parseFloat(document.frm.jsp_cost9.value).toFixed(3);
											}else{	
												t10=0;	
												document.frm.jsp_cost9.value='';
											}

											//box350>2
											if(document.frm.jsp_blister10.checked == true){
												document.frm.jsp_cost10.value= parseFloat(0.50).toFixed(2);
												t11=parseFloat(document.frm.jsp_cost10.value).toFixed(3);
											}else{	
												t11=0;	
												document.frm.jsp_cost10.value='';
											}											

											sum=parseFloat(t1)+parseFloat(t2)+parseFloat(t3)+parseFloat(t4)+parseFloat(t5)+parseFloat(t6)
												+parseFloat(t7)+parseFloat(t8)+parseFloat(t9)+parseFloat(t10)+parseFloat(t11);
											
											document.frm.total_blister1.value=parseFloat(sum).toFixed(3);

											document.frm.total_fg_cost1.value=(parseFloat(sum) + parseFloat(document.frm.sum_cost_per.value)).toFixed(3);	
										}										
										function fncSum_cos(){																	
											document.frm.sum_cost_per.value=(parseFloat(document.frm.num_cost.value) * parseFloat(document.frm.total_bluk.value)).toFixed(3);
										}		
										
									</script>
									<!--end function calculate sum cost per tablet-->
									<tr>	
										<td><div style="float:left; margin:2% 2% 0 0;">Cost per</div><input type="text" name="num_cost" id="num_cost" value="<?php echo $rs_pack_blister['num_cost']?>" OnChange="fncSum_cos();" style="width:20%;float:left;margin:0 2% 0 0;"><div style="float:left;margin:2% 0 0 0;">Unit</div></td>
										<td>
											<input type="hidden" name="total_bluk" id="total_bluk" value="<?php echo $rs_costing['total_bluk']?>">
											<input type="text" name="sum_cost_per" id="sum_cost_per" value="<?php echo $rs_pack_blister['sum_cost_per']?>" style="width:20%;">
										</td>
									</tr>
									<tr>
										<?php
										if( ($rs_costing['id_product_appearance']==1) || ($rs_costing['id_product_appearance']==2) || ($rs_costing['id_product_appearance']==3) ){
										?>
										<td>Package</td>
										<td>
											<input type="radio" onclick="javascript:ShowPack();" name="pack" id="boxCheck" value="1" <?php if($rs_pack_blister['type_pack']==1){echo 'checked';}?>>Box set
											<input type="radio" onclick="javascript:ShowPack();" name="pack" id="bottleCheck" value="2" <?php if($rs_pack_blister['type_pack']==2){echo 'checked';}?>>Bottle set
										</td>
										<?php }elseif($rs_costing['id_product_appearance']==6){?>
										<td>Package</td>
										<td>
											<input type="hidden" name="pack" id="bottle" value="2" <?php if($rs_pack_blister['type_pack']==2){echo 'checked';}?>>Bottle set
										</td>
										<?php }?>
									</tr>
									<?php if( ($rs_costing['id_product_appearance']==1) || ($rs_costing['id_product_appearance']==2) || ($rs_costing['id_product_appearance']==3) ){
										$detail_blister=split(",",$rs_pack_blister['detail_blister']);
										$price_unit=split(",",$rs_pack_blister['price_unit']);
										$num_blister=split(",",$rs_pack_blister['num_blister']);
									?>
										<table width="100%" style="border: none;<?php if($rs_pack_blister['type_pack'] == 1){echo '';}else{echo 'display:none;';}?>" cellpadding="0" cellspacing="0" id="tb-cost">	
											<tr>
												<td class="b-left bd-right b-top b-bottom w20"><b>Description</b></td>
												<td class="bd-right b-top b-bottom"><b>Specification</b></td>
												<td class="bd-right b-top b-bottom"><b>Unit</b></td>
												<td class="bd-right b-top b-bottom"><b>Price (baht)/Unit</b></td>
												<td class="bd-right b-top b-bottom"><b>Note</b></td>
											</tr>
											<?php
											$i=0;
											$sql_jsp_blister="select * from jsp_pack_blister";
											$res_jsp_blister=mysql_query($sql_jsp_blister) or die ('Error '.$sql_jsp_blister);
											while($rs_jsp_blister=mysql_fetch_array($res_jsp_blister)){
											?>
											<tr>
												<td class="b-left bd-right b-bottom cost-left">
													<div style="float:left; margin-right:2%;"><input type="checkbox" name="blister_box[]" id="jsp_blister<?=$i?>" value="<?php echo $rs_jsp_blister['id_jsp_pack_blister']?>" <?php if(in_array($rs_jsp_blister['id_jsp_pack_blister'],$detail_blister)){echo 'checked';}?> OnClick="JavaScript:return fnccheck_blister();"></div><div style="float:left; margin-right:2%;"><?php echo $rs_jsp_blister['description']?></div>
													<?php if($rs_jsp_blister['description']=='PVC Blister'){?>
														<div style="float:left;"><input type="text" name="num_blister[]" id="num_blister<?=$i?>"  OnChange="fnccheck_blister();" value="<?php if(in_array($rs_jsp_blister['id_jsp_pack_blister'],$detail_blister)){echo $num_blister[0];}else{echo '1';}?>"></div>
													<?php }if($rs_jsp_blister['description']=='Silica gel'){?>
														<div style="float:left;"><input type="text" name="num_blister[]" id="num_blister<?=$i?>" OnChange="fnccheck_blister();" value="<?php if(in_array($rs_jsp_blister['id_jsp_pack_blister'],$detail_blister)){echo $num_blister[1];}else{echo '1';}?>"></div>
													<?php }if($rs_jsp_blister['description']=='Aliminum pouch'){?>
														<div style="float:left;"><input type="text" name="num_blister[]" id="num_blister<?=$i?>"  OnChange="fnccheck_blister();" value="<?php if(in_array($rs_jsp_blister['id_jsp_pack_blister'],$detail_blister)){echo $num_blister[2];}else{echo '1';}?>"></div>
													<?php }?>
												</td>
												<td class="bd-right b-bottom"><?php if($rs_jsp_blister['specification']==''){echo '-';}else{echo $rs_jsp_blister['specification'];}?></td>
												<td class="bd-right b-bottom"><?php echo $rs_jsp_blister['unit']?></td>
												<td class="bd-right b-bottom"><input type="text" name="cost_blister[]" id="jsp_cost<?=$i?>" value="<?php if($price_unit[$i]==''){echo $rs_jsp_blister['price_unit'];}else{echo $price_unit[$i];}?>" style="width: 20%;"></td>
												<td class="bd-right b-bottom"><?php if($rs_jsp_blister['note']==''){echo '-';}else{echo $rs_jsp_blister['note'];}?></td>
											</tr>
											<?php  $i++;}?>											
											<tr>
												<td class="b-left bd-right b-bottom cost-left cost-center" colspan="3"><b>Total</b></td>
												<td class="bd-right b-bottom" colspan="2"><input type="text" name="total_packaging1" id="total_blister1" value="<?php echo $rs_pack_blister['total_pack']?>"></td>
											</tr>
											<tr>
												<td class="b-left bd-right b-bottom cost-left cost-center" colspan="3"><b>Total FG cost</b></td>
												<td class="bd-right b-bottom" colspan="2"><input type="text" name="total_fg_cost1" id="total_fg_cost1" value="<?php echo $rs_pack_blister['total_fg']?>"></td>
											</tr>
										</table>
										<!--costing packaging bottle-->
										<script>
											//packing bottle
											function fnccheck_bottle(){
												var t1; var t2; var t3; var t4; var t5; var t6; var t7; var t8; var t9;
												var t10; var t11; var t12; var t13; var t14; var t15; var t16; var t17; 
												var t18; var t19; var sum;

												//moh_packing_oem
												if(document.frm.jsp_bottle0.checked == true){
													document.frm.jsp_cost_bottle0.value=parseFloat(4.00).toFixed(2);
													t1=parseFloat(document.frm.jsp_cost_bottle0.value).toFixed(3);
												}else{	
													t1=0;	
													document.frm.jsp_cost_bottle0.value='';
												} 
												
												//moh_packing_fee
												if(document.frm.jsp_bottle1.checked == true){
													document.frm.jsp_cost_bottle1.value=parseFloat(10.00).toFixed(2);
													t2=parseFloat(document.frm.jsp_cost_bottle1.value).toFixed(3);
												}else{	
													t2=0;	
													document.frm.jsp_cost_bottle1.value='';
												} 

												//silica gel
												if(document.frm.jsp_bottle2.checked == true){
													document.frm.jsp_cost_bottle2.value= (parseFloat(0.50) * parseFloat(document.frm.num_bottle2.value)).toFixed(2);
													t3=parseFloat(document.frm.jsp_cost_bottle2.value).toFixed(3);
												}else{	
													t3=0;	
													document.frm.jsp_cost_bottle2.value='';
												}

												//sponge
												if(document.frm.jsp_bottle3.checked == true){
													document.frm.jsp_cost_bottle3.value=parseFloat(1.50).toFixed(2); 
													t4=parseFloat(document.frm.jsp_cost_bottle3.value).toFixed(3);
												}else{	
													t4=0;
													document.frm.jsp_cost_bottle3.value='';
												}										

												//sticker
												if(document.frm.jsp_bottle5.checked == true){
													document.frm.jsp_cost_bottle5.value=parseFloat(3.00).toFixed(2); 
													t5=parseFloat(document.frm.jsp_cost_bottle5.value).toFixed(3);
												}else{	
													t5=0;	
													document.frm.jsp_cost_bottle5.value='';
												}

												//box
												if(document.frm.jsp_bottle6.checked == true){
													document.frm.jsp_cost_bottle6.value=parseFloat(10.00).toFixed(2); 
													t6=parseFloat(document.frm.jsp_cost_bottle6.value).toFixed(3);
												}else{	
													t6=0;	
													document.frm.jsp_cost_bottle6.value='';
												}

												//film shrink
												if(document.frm.jsp_bottle7.checked == true){
													document.frm.jsp_cost_bottle7.value=parseFloat(0.50).toFixed(2); 
													t7=parseFloat(document.frm.jsp_cost_bottle7.value).toFixed(3);
												}else{	
													t7=0;	
													document.frm.jsp_cost_bottle7.value='';
												}

												//carton
												if(document.frm.jsp_bottle8.checked == true){
													document.frm.jsp_cost_bottle8.value=parseFloat(0.50).toFixed(2); 
													t8=parseFloat(document.frm.jsp_cost_bottle8.value).toFixed(3);
												}else{	
													t8=0;	
													document.frm.jsp_cost_bottle8.value='';
												}

												//delivery
												if(document.frm.jsp_bottle9.checked == true){
													document.frm.jsp_cost_bottle9.value=parseFloat(0.50).toFixed(2); 
													t9=parseFloat(document.frm.jsp_cost_bottle9.value).toFixed(3);
												}else{	
													t9=0;
													document.frm.jsp_cost_bottle9.value='';
												}

												//Plastic PE 150 cc + screw or tear cap
												if(document.frm.bottle_detail0.checked == true){
													document.frm.cost_bottle_detail0.value=parseFloat(5.00).toFixed(2); 
													t10=parseFloat(document.frm.cost_bottle_detail0.value).toFixed(3);
												}else{	
													t10=0;
													document.frm.cost_bottle_detail0.value='';
												}

												//Plastic PE 150 cc + screw or tear cap
												if(document.frm.bottle_detail1.checked == true){
													document.frm.cost_bottle_detail1.value=parseFloat(7.00).toFixed(2); 
													t11=parseFloat(document.frm.cost_bottle_detail1.value).toFixed(3);
												}else{	
													t11=0;
													document.frm.cost_bottle_detail1.value='';
												}

												//Plastic PET 150 cc + tear cap
												if(document.frm.bottle_detail2.checked == true){
													document.frm.cost_bottle_detail2.value=parseFloat(8.00).toFixed(2); 
													t12=parseFloat(document.frm.cost_bottle_detail2.value).toFixed(3);
												}else{	
													t12=0;
													document.frm.cost_bottle_detail2.value='';
												}

												//Plastic PET 200 cc + tear cap
												if(document.frm.bottle_detail3.checked == true){
													document.frm.cost_bottle_detail3.value=parseFloat(9.00).toFixed(2); 
													t13=parseFloat(document.frm.cost_bottle_detail3.value).toFixed(3);
												}else{	
													t13=0;
													document.frm.cost_bottle_detail3.value='';
												}

												//Plastic PET 250 cc + tear cap
												if(document.frm.bottle_detail4.checked == true){
													document.frm.cost_bottle_detail4.value=parseFloat(10.00).toFixed(2); 
													t14=parseFloat(document.frm.cost_bottle_detail4.value).toFixed(3);
												}else{	
													t14=0;
													document.frm.cost_bottle_detail4.value='';
												}

												//Plastic PET 150 cc + child lock cap
												if(document.frm.bottle_detail5.checked == true){
													document.frm.cost_bottle_detail5.value=parseFloat(12.00).toFixed(2); 
													t15=parseFloat(document.frm.cost_bottle_detail5.value).toFixed(3);
												}else{	
													t15=0;
													document.frm.cost_bottle_detail5.value='';
												}

												//Plastic PET 200 cc + child lock cap
												if(document.frm.bottle_detail6.checked == true){
													document.frm.cost_bottle_detail6.value=parseFloat(13.00).toFixed(2); 
													t16=parseFloat(document.frm.cost_bottle_detail6.value).toFixed(3);
												}else{	
													t16=0;
													document.frm.cost_bottle_detail6.value='';
												}

												//Amber Glass bottle 150 cc + tear cap
												if(document.frm.bottle_detail7.checked == true){
													document.frm.cost_bottle_detail7.value=parseFloat(6.00).toFixed(2); 
													t17=parseFloat(document.frm.cost_bottle_detail7.value).toFixed(3);
												}else{	
													t17=0;
													document.frm.cost_bottle_detail7.value='';
												}

												//Amber Glass bottle 150 cc + child lock cap
												if(document.frm.bottle_detail8.checked == true){
													document.frm.cost_bottle_detail8.value=parseFloat(7.00).toFixed(2); 
													t18=parseFloat(document.frm.cost_bottle_detail8.value).toFixed(3);
												}else{	
													t18=0;
													document.frm.cost_bottle_detail8.value='';
												}

												//Amber Glass bottle 250 cc + child lock cap
												if(document.frm.bottle_detail9.checked == true){
													document.frm.cost_bottle_detail9.value=parseFloat(9.00).toFixed(2); 
													t19=parseFloat(document.frm.cost_bottle_detail9.value).toFixed(3);
												}else{	
													t19=0;
													document.frm.cost_bottle_detail9.value='';
												}

												

												sum=parseFloat(t1)+parseFloat(t2)+parseFloat(t3)+parseFloat(t4)+parseFloat(t5)+parseFloat(t6)
													+parseFloat(t7)+parseFloat(t8)+parseFloat(t9)+parseFloat(t10)+parseFloat(t11)+parseFloat(t12)
													+parseFloat(t13)+parseFloat(t14)+parseFloat(t15)+parseFloat(t16)+parseFloat(t17)
													+parseFloat(t18)+parseFloat(t19);
												
												parseFloat(document.frm.bottle_total_packaging.value=sum).toFixed(3);

												document.frm.bottle_total_fg_cost.value=(parseFloat(document.frm.bottle_total_packaging.value) + parseFloat(document.frm.sum_cost_per.value)).toFixed(3);	
											}
										</script>
										<table width="100%" style="border: none;<?php if($rs_pack_blister['type_pack'] == 2){echo '';}else{echo 'display:none;';}?>" cellpadding="0" cellspacing="0" id="tb-cost2">	
											<tr>
												<td class="b-left bd-right b-top b-bottom w20"><b>Description</b></td>
												<td class="bd-right b-top b-bottom"><b>Specification</b></td>
												<td class="bd-right b-top b-bottom"><b>Unit</b></td>
												<td class="bd-right b-top b-bottom"><b>Price (baht)/Unit</b></td>
												<td class="bd-right b-top b-bottom"><b>Note</b></td>
											</tr>
											<?php
											$i=0;
											$sql_jsp_bottle="select * from jsp_pack_bottle";
											$res_jsp_bottle=mysql_query($sql_jsp_bottle) or die ('Error '.$sql_jsp_bottle);
											while($rs_jsp_bottle=mysql_fetch_array($res_jsp_bottle)){
											?>
											<tr>
												<td class="b-left bd-right b-bottom cost-left <?php if($rs_jsp_bottle['description']=='Sticker label'){echo 'b-top';}?>">
													<?php if($rs_jsp_bottle['description']=='Bottle'){echo '<div id="dialog_title">';}?><div style="float:left; margin-right:2%;"><input type="checkbox" id="jsp_bottle<?php echo $i?>" name="bottle_detail[]"  value="<?php echo $rs_jsp_bottle['id_jsp_pack_bottle']?>" <?php if(in_array($rs_jsp_bottle['id_jsp_pack_bottle'],$detail_blister)){echo 'checked';}?> OnClick="JavaScript:return fnccheck_bottle();"></div><div style="float:left; margin-right:2%;"><?php echo $rs_jsp_bottle['description']?><?php if($rs_jsp_bottle['description']=='Bottle'){echo '</div>';}?></div>
													<?php if($rs_jsp_bottle['description']=='Silica gel'){?>
														<div style="float:left;"><input type="text" name="num_bottle[]" id="num_bottle<?=$i?>" OnChange="fnccheck_bottle();" value="<?php if(in_array($rs_jsp_bottle['id_jsp_pack_bottle'],$detail_blister)){echo $num_blister[0];}else{echo '1';}?>"></div>
													<?php }?>
												</td>
												<td class="bd-right b-bottom <?php if($rs_jsp_bottle['description']=='Sticker label'){echo 'b-top';}?>"><?php if($rs_jsp_bottle['specification']==''){echo '-';}else{echo $rs_jsp_bottle['specification'];}?></td>
												<td class="bd-right b-bottom <?php if($rs_jsp_bottle['description']=='Sticker label'){echo 'b-top';}?>"><?php echo $rs_jsp_bottle['unit']?></td>
												<td class="bd-right b-bottom <?php if($rs_jsp_bottle['description']=='Sticker label'){echo 'b-top';}?>"><?php if($rs_jsp_bottle['description']!='Bottle'){?><input type="text" name="cost_bottle_detail[]" id="jsp_cost_bottle<?=$i?>" value="<?php if($price_unit[$i]==''){echo $rs_jsp_bottle['price_unit'];}else{echo $price_unit[$i];}?>" style="width: 20%;"><?php }?></td>
												<td class="bd-right b-bottom"><?php if($rs_jsp_bottle['note']==''){echo '-';}else{echo $rs_jsp_bottle['note'];}?></td>
											</tr>
											<?php if($rs_jsp_bottle['description']=='Bottle'){?>
											<script>
												$(document).ready(function() {
													$('#Show').hide();
													$("input[name=bottle[]").click(function () {
														$('#Show').toggle();
													});
												});
											</script>											
											<tr>
												<td class="b-left bd-right cost-left" colspan="5" style="padding:0;">
													<!--<div <?php if(in_array('5',$detail_blister)){echo '';}else{echo 'id="Show"';}?>>-->
														<table width="100%" style="border: none;margin:0;" cellpadding="0" cellspacing="0" id="tb-cost2">
															<?php
															$detail_bottle=split(",",$rs_pack_blister['bottle_detail']);
															$price_unit2=split(",",$rs_pack_blister['cost_bottle_detail']);
															$j=0;
															$sql_jsp_bottle_detail="select * from jsp_pack_bottle_detail where id_jsp_pack_bottle='5'";
															$res_jsp_bottle_detail=mysql_query($sql_jsp_bottle_detail) or die ('Error '.$sql_jsp_bottle_detail);
															while($rs_jsp_bottle_detail=mysql_fetch_array($res_jsp_bottle_detail)){
															?>
															<tr>
																<td class="bd-right cost-left" width="20%;" style="font-size:1em;padding-left:2%;"><input type="checkbox" name="bottle_detail2[]" id="bottle_detail<?=$j?>" <?php if(in_array($rs_jsp_bottle_detail['id_jsp_pack_bottle_detail'],$detail_bottle)){echo 'checked';}?>  OnClick="JavaScript:return fnccheck_bottle();" value="<?php echo $rs_jsp_bottle_detail['id_jsp_pack_bottle_detail']?>"><?php echo $rs_jsp_bottle_detail['description']?></td>
																<td class="bd-right" width="37%;" style="font-size:1em;"><?php if($rs_jsp_bottle_detail['specification']==''){echo '-';}else{echo $rs_jsp_bottle_detail['specification'];}?></td>
																<td class="bd-right" width="4.3%;" style="font-size:1em;"><?php echo $rs_jsp_bottle_detail['unit']?></td>
																<td class="bd-right" width="17.35%;" style="font-size:1em;"><input type="text" name="cost_bottle_detail2[]" id="cost_bottle_detail<?=$j?>" value="<?php if($price_unit2[$j]==''){echo $rs_jsp_bottle_detail['price_unit'];}else{echo $price_unit2[$j];}?>" style="width: 20%;"></td>
																<td style="font-size:1em;"><?php if($rs_jsp_bottle_detail['note']==''){echo '-';}else{echo $rs_jsp_bottle_detail['note'];}?></td>
															</tr>		
															<?php $j++;}?>
														</table>
													<!--</div>-->
												</td>
											</tr>	
											<?php }$i++;?>
											<?php }?>						
											<tr>
												<td class="b-left bd-right b-bottom cost-left cost-center" colspan="3"><b>Total</b></td>
												<td class="bd-right b-bottom" colspan="2"><input type="text" name="total_packaging2" id="bottle_total_packaging" value="<?php echo $rs_pack_blister['total_pack']?>"></td>
											</tr>
											<tr>
												<td class="b-left bd-right b-bottom cost-left cost-center" colspan="3"><b>Total FG cost</b></td>
												<td class="bd-right b-bottom" colspan="2"><input type="text" name="total_fg_cost2" id="bottle_total_fg_cost" value="<?php echo $rs_pack_blister['total_fg']?>"></td>
											</tr>
										</table>
									<?php }elseif($rs_costing['id_product_appearance']==6){?>
										<script>
											function fnccheck_screw(){
												var t1; var t2; var t3; var t4; var t5; var t6; var t7; var t8; 
												var sum;

												//moh_packing
												if(document.frm.drink_moh_packing.checked == true){
													document.frm.cost_drink_moh_packing.value=parseFloat(2.00).toFixed(2);
													t1=parseFloat(document.frm.cost_drink_moh_packing.value).toFixed(3);
												}else{	
													t1=0;	
													document.frm.cost_drink_moh_packing.value='';
												} 

												//shrink
												if(document.frm.drink_shrink.checked == true){
													document.frm.cost_drink_shrink.value=parseFloat(0.80).toFixed(2);
													t2=parseFloat(document.frm.cost_drink_shrink.value).toFixed(3);
												}else{	
													t2=0;	
													document.frm.cost_drink_shrink.value='';
												} 

												//sticker
												if(document.frm.drink_sticker.checked == true){
													document.frm.cost_drink_sticker.value=parseFloat(1.50).toFixed(2);
													t3=parseFloat(document.frm.cost_drink_sticker.value).toFixed(3);
												}else{	
													t3=0;	
													document.frm.cost_drink_sticker.value='';
												}
												
												//shrink 6
												if(document.frm.drink_shrink_6.checked == true){
													document.frm.cost_drink_shrink_6.value=parseFloat(0.20).toFixed(2);
													t4=parseFloat(document.frm.cost_drink_shrink_6.value).toFixed(3);
												}else{	
													t4=0;	
													document.frm.cost_drink_shrink_6.value='';
												}

												//shrink 6
												if(document.frm.drink_bottle_glass.checked == true){
													document.frm.cost_drink_bottle_glass.value=parseFloat(1.90).toFixed(2);
													t5=parseFloat(document.frm.cost_drink_bottle_glass.value).toFixed(3);
												}else{	
													t5=0;	
													document.frm.cost_drink_bottle_glass.value='';
												}

												//cap alu
												if(document.frm.drink_alu.checked == true){
													document.frm.cost_drink_alu.value=parseFloat(1.90).toFixed(2);
													t6=parseFloat(document.frm.cost_drink_alu.value).toFixed(3);
												}else{	
													t6=0;	
													document.frm.cost_drink_alu.value='';
												}

												//carton
												if(document.frm.drink_carton.checked == true){
													document.frm.cost_drink_carton.value=parseFloat(0.50).toFixed(2);
													t7=parseFloat(document.frm.cost_drink_carton.value).toFixed(3);
												}else{	
													t7=0;	
													document.frm.cost_drink_carton.value='';
												}

												//delivery
												if(document.frm.drink_delivery.checked == true){
													document.frm.cost_drink_delivery.value=parseFloat(0.50).toFixed(2);
													t8=parseFloat(document.frm.cost_drink_delivery.value).toFixed(3);
												}else{	
													t8=0;	
													document.frm.cost_drink_delivery.value='';
												}

												sum=parseFloat(t1)+parseFloat(t2)+parseFloat(t3)+parseFloat(t4)+parseFloat(t5)+parseFloat(t6)
													+parseFloat(t7)+parseFloat(t8);
													
												parseFloat(document.frm.drink_total_packaging.value=sum).toFixed(3);
												
												document.frm.drink_total_fg_cost.value=(parseFloat(document.frm.drink_total_packaging.value) 
												+ parseFloat(document.frm.sum_cost_per.value)).toFixed(3);
											}
											function fnccheck_twist(){
												var t1; var t2; var t3; var t4; var t5; var t6; var t7; var t8; 
												var sum;

												//moh_packing
												if(document.frm.drink_twist_moh_packing.checked == true){
													document.frm.cost_drink_twist_moh_packing.value=parseFloat(2.00).toFixed(2);
													t1=parseFloat(document.frm.cost_drink_twist_moh_packing.value).toFixed(3);
												}else{	
													t1=0;	
													document.frm.cost_drink_twist_moh_packing.value='';
												} 

												//box
												if(document.frm.drink_twist_box.checked == true){
													document.frm.cost_drink_twist_box.value=parseFloat(1.50).toFixed(2);
													t2=parseFloat(document.frm.cost_drink_twist_box.value).toFixed(3);
												}else{	
													t2=0;	
													document.frm.cost_drink_twist_box.value='';
												} 

												//sticker
												if(document.frm.drink_twist_sticker.checked == true){
													document.frm.cost_drink_twist_sticker.value=parseFloat(0.25).toFixed(2);
													t3=parseFloat(document.frm.cost_drink_twist_sticker.value).toFixed(3);
												}else{	
													t3=0;	
													document.frm.cost_drink_twist_sticker.value='';
												}
												
												//holder carton
												if(document.frm.drink_holder.checked == true){
													document.frm.cost_drink_holder.value=parseFloat(0.75).toFixed(2);
													t4=parseFloat(document.frm.cost_drink_holder.value).toFixed(3);
												}else{	
													t4=0;	
													document.frm.cost_drink_holder.value='';
												}

												//glass bottle
												if(document.frm.drink_twist_bottle_glass.checked == true){
													document.frm.cost_drink_twist_bottle_glass.value=parseFloat(1.90).toFixed(2);
													t4=parseFloat(document.frm.cost_drink_twist_bottle_glass.value).toFixed(3);
												}else{	
													t4=0;	
													document.frm.cost_drink_twist_bottle_glass.value='';
												}

												//cap alu
												if(document.frm.drink_twist_alu.checked == true){
													document.frm.cost_drink_twist_alu.value=parseFloat(1.90).toFixed(2);
													t5=parseFloat(document.frm.cost_drink_twist_alu.value).toFixed(3);
												}else{	
													t5=0;	
													document.frm.cost_drink_twist_alu.value='';
												}

												//carton
												if(document.frm.drink_twist_carton.checked == true){
													document.frm.cost_drink_twist_carton.value=parseFloat(0.50).toFixed(2);
													t6=parseFloat(document.frm.cost_drink_twist_carton.value).toFixed(3);
												}else{	
													t6=0;	
													document.frm.cost_drink_twist_carton.value='';
												}

												//delivery
												if(document.frm.drink_twist_delivery.checked == true){
													document.frm.cost_drink_twist_delivery.value=parseFloat(0.50).toFixed(2);
													t7=parseFloat(document.frm.cost_drink_twist_delivery.value).toFixed(3);
												}else{	
													t7=0;	
													document.frm.cost_drink_twist_delivery.value='';
												}

												sum=parseFloat(t1)+parseFloat(t2)+parseFloat(t3)+parseFloat(t4)+parseFloat(t5)+parseFloat(t6)
													+parseFloat(t7);
													
												parseFloat(document.frm.drink_twist_total_packaging.value=sum).toFixed(3);
												
												document.frm.drink_twist_total_fg_cost.value=(parseFloat(document.frm.drink_twist_total_packaging.value) 
												+ parseFloat(document.frm.sum_cost_per.value)).toFixed(3);
											}
											
											function fncSum_cos(){																	
												document.frm.sum_cost_per.value=(parseFloat(document.frm.num_cost.value) * parseFloat(document.frm.total_bluk.value)).toFixed(3);
											}												
										</script>
										<script type="text/javascript">
											function ShowPack() {
												if (document.getElementById('bottle_screw_cap').checked) {
													document.getElementById('tb-cost').style.display = '';
													document.getElementById('tb-cost2').style.display = 'none';
												}
												else
												if (document.getElementById('bottle_twist_off').checked) {
													document.getElementById('tb-cost').style.display = 'none';
													document.getElementById('tb-cost2').style.display = '';
												}
											}
										</script>
										<tr>
											<td style="padding:1% 0;">Bottle cap</td>
											<td style="padding:1% 0 0 0;">
												<input type="radio" onclick="javascript:ShowPack();" name="bottle_cap" id="bottle_screw_cap" value="1" <?php if($rs_pack_blister['bottle_cap']==1){echo 'checked';}?>>Screw Cap
												<input type="radio" onclick="javascript:ShowPack();" name="bottle_cap" id="bottle_twist_off" value="2" <?php if($rs_pack_blister['bottle_cap']==2){echo 'checked';}?>>Twist Off
											</td>
										</tr>
										<?php
										$detail_blister=split(",",$rs_pack_blister['detail_blister']);
										$price_unit=split(",",$rs_pack_blister['price_unit']);
										$num_blister=split(",",$rs_pack_blister['num_blister']);
										?>
										<table width="100%" style="border: none;<?php if($rs_pack_blister['bottle_cap'] == 1){echo '';}else{echo 'display:none;';}?>" cellpadding="0" cellspacing="0" id="tb-cost">	
											<tr>
												<td class="b-left bd-right b-top b-bottom w20"><b>Description</b></td>
												<td class="bd-right b-top b-bottom"><b>Specification</b></td>
												<td class="bd-right b-top b-bottom"><b>Unit</b></td>
												<td class="bd-right b-top b-bottom"><b>Price (baht)/Unit</b></td>
												<td class="bd-right b-top b-bottom"><b>Note</b></td>
											</tr>
											<tr>
												<td class="b-left bd-right b-bottom cost-left"><input type="checkbox" name="blister_box[]" id="drink_moh_packing" OnClick="JavaScript:return fnccheck_screw();" <?php if(in_array('1',$detail_blister)){echo 'checked';}?> value="1">MOH packing</td>
												<td class="bd-right b-bottom">-</td>
												<td class="bd-right b-bottom">bottle</td>
												<td class="bd-right b-bottom"><input type="text" name="cost_blister[]" id="cost_drink_moh_packing" value="2.00" style="width: 20%;"></td>
												<td class="bd-right b-bottom">-</td>
											</tr>
											<tr>
												<td class="b-left bd-right b-bottom cost-left"><input type="checkbox" name="blister_box[]" id="drink_shrink" OnClick="JavaScript:return fnccheck_screw();" <?php if(in_array('2',$detail_blister)){echo 'checked';}?> value="2">Shrink</td>
												<td class="bd-right b-bottom">-</td>
												<td class="bd-right b-bottom">pcs</td>
												<td class="bd-right b-bottom"><input type="text" name="cost_blister[]" id="cost_drink_shrink" value="0.80" style="width: 20%;"></td>
												<td class="bd-right b-bottom">-</td>
											</tr>	
											<tr>
												<td class="b-left bd-right b-bottom cost-left"><input type="checkbox" name="blister_box[]" id="drink_sticker" OnClick="JavaScript:return fnccheck_screw();" <?php if(in_array('3',$detail_blister)){echo 'checked';}?> value="3">Label Sticker Paper</td>
												<td class="bd-right b-bottom">-</td>
												<td class="bd-right b-bottom">pcs</td>
												<td class="bd-right b-bottom"><input type="text" name="cost_blister[]" id="cost_drink_sticker" value="1.50" style="width: 20%;"></td>
												<td class="bd-right b-bottom">-</td>
											</tr>
											<tr>
												<td class="b-left bd-right b-bottom cost-left"><input type="checkbox" name="blister_box[]" id="drink_shrink_6" OnClick="JavaScript:return fnccheck_screw();" <?php if(in_array('4',$detail_blister)){echo 'checked';}?> value="4">Shrink 6 bottle</td>
												<td class="bd-right b-bottom">-</td>
												<td class="bd-right b-bottom">pcs</td>
												<td class="bd-right b-bottom"><input type="text" name="cost_blister[]" id="cost_drink_shrink_6" value="0.20" style="width: 20%;"></td>
												<td class="bd-right b-bottom">-</td>
											</tr>	
											<tr>
												<td class="b-left bd-right b-bottom cost-left"><input type="checkbox" name="blister_box[]" id="drink_bottle_glass" OnClick="JavaScript:return fnccheck_screw();" <?php if(in_array('5',$detail_blister)){echo 'checked';}?> value="5">Glass bottle</td>
												<td class="bd-right b-bottom">-</td>
												<td class="bd-right b-bottom">set</td>
												<td class="bd-right b-bottom"><input type="text" name="cost_blister[]" id="cost_drink_bottle_glass" value="1.90" style="width: 20%;"></td>
												<td class="bd-right b-bottom">-</td>
											</tr>	
											<tr>
												<td class="b-left bd-right b-bottom cost-left"><input type="checkbox" name="blister_box[]" id="drink_alu" OnClick="JavaScript:return fnccheck_screw();" <?php if(in_array('6',$detail_blister)){echo 'checked';}?> value="6">Cap Alu 1 color</td>
												<td class="bd-right b-bottom">-</td>
												<td class="bd-right b-bottom">pcs</td>
												<td class="bd-right b-bottom"><input type="text" name="cost_blister[]" id="cost_drink_alu" value="0.60" style="width: 20%;"></td>
												<td class="bd-right b-bottom">-</td>
											</tr>
											<tr>
												<td class="b-left bd-right b-bottom cost-left"><input type="checkbox" name="blister_box[]" id="drink_carton" OnClick="JavaScript:return fnccheck_screw();" <?php if(in_array('7',$detail_blister)){echo 'checked';}?> value="7">Carton</td>
												<td class="bd-right b-bottom">-</td>
												<td class="bd-right b-bottom">set</td>
												<td class="bd-right b-bottom"><input type="text" name="cost_blister[]" id="cost_drink_carton" value="0.50" style="width: 20%;"></td>
												<td class="bd-right b-bottom">-</td>
											</tr>
											<tr>
												<td class="b-left bd-right b-bottom cost-left"><input type="checkbox" name="blister_box[]" id="drink_delivery" OnClick="JavaScript:return fnccheck_screw();" <?php if(in_array('8',$detail_blister)){echo 'checked';}?> value="8">Delivery</td>
												<td class="bd-right b-bottom">-</td>
												<td class="bd-right b-bottom">set</td>
												<td class="bd-right b-bottom"><input type="text" name="cost_blister[]" id="cost_drink_delivery" value="0.50" style="width: 20%;"></td>
												<td class="bd-right b-bottom">-</td>
											</tr>
											<tr>
												<td class="b-left bd-right b-bottom cost-left cost-center" colspan="3"><b>Total</b></td>
												<td class="bd-right b-bottom" colspan="2"><input type="text" name="total_packaging_fun" id="drink_total_packaging" value="<?php echo $rs_pack_blister['total_pack']?>"></td>
											</tr>
											<tr>
												<td class="b-left bd-right b-bottom cost-left cost-center" colspan="3"><b>Total FG cost</b></td>
												<td class="bd-right b-bottom" colspan="2"><input type="text" name="total_fg_cost_fun" id="drink_total_fg_cost" value="<?php echo $rs_pack_blister['total_fg']?>"></td>
											</tr>
										</table>
										<!--twist off-->
										<table width="100%" style="border: none;<?php if($rs_pack_blister['bottle_cap'] == 2){echo '';}else{echo 'display:none;';}?>" cellpadding="0" cellspacing="0" id="tb-cost2">	
											<tr>
												<td class="b-left bd-right b-top b-bottom w20"><b>Description</b></td>
												<td class="bd-right b-top b-bottom"><b>Specification</b></td>
												<td class="bd-right b-top b-bottom"><b>Unit</b></td>
												<td class="bd-right b-top b-bottom"><b>Price (baht)/Unit</b></td>
												<td class="bd-right b-top b-bottom"><b>Note</b></td>
											</tr>
											<tr>
												<td class="b-left bd-right b-bottom cost-left"><input type="checkbox" name="blister_box[]" id="drink_twist_moh_packing" OnClick="JavaScript:return fnccheck_twist();" <?php if(in_array('9',$detail_blister)){echo 'checked';}?> value="9">MOH packing</td>
												<td class="bd-right b-bottom">-</td>
												<td class="bd-right b-bottom">bottle</td>
												<td class="bd-right b-bottom"><input type="text" name="cost_blister[]" id="cost_drink_twist_moh_packing" value="2.00" style="width: 20%;"></td>
												<td class="bd-right b-bottom">-</td>
											</tr>
											<tr>
												<td class="b-left bd-right b-bottom cost-left"><input type="checkbox" name="blister_box[]" id="drink_twist_box" OnClick="JavaScript:return fnccheck_twist();" <?php if(in_array('10',$detail_blister)){echo 'checked';}?> value="10">Box</td>
												<td class="bd-right b-bottom">Art card 300 gram 4 color</td>
												<td class="bd-right b-bottom">pcs</td>
												<td class="bd-right b-bottom"><input type="text" name="cost_blister[]" id="cost_drink_twist_box" value="1.50" style="width: 20%;"></td>
												<td class="bd-right b-bottom">-</td>
											</tr>	
											<tr>
												<td class="b-left bd-right b-bottom cost-left"><input type="checkbox" name="blister_box[]" id="drink_twist_sticker" OnClick="JavaScript:return fnccheck_twist();" <?php if(in_array('11',$detail_blister)){echo 'checked';}?> value="11">Label Sticker Paper</td>
												<td class="bd-right b-bottom">-</td>
												<td class="bd-right b-bottom">pcs</td>
												<td class="bd-right b-bottom"><input type="text" name="cost_blister[]" id="cost_drink_twist_sticker" value="0.25" style="width: 20%;"></td>
												<td class="bd-right b-bottom">-</td>
											</tr>
											<tr>
												<td class="b-left bd-right b-bottom cost-left"><input type="checkbox" name="blister_box[]" id="drink_holder" OnClick="JavaScript:return fnccheck_twist();" <?php if(in_array('12',$detail_blister)){echo 'checked';}?> value="12">Holder Carton</td>
												<td class="bd-right b-bottom">-</td>
												<td class="bd-right b-bottom">set</td>
												<td class="bd-right b-bottom"><input type="text" name="cost_blister[]" id="cost_drink_holder" value="0.75" style="width: 20%;"></td>
												<td class="bd-right b-bottom">-</td>
											</tr>
											<tr>
												<td class="b-left bd-right b-bottom cost-left"><input type="checkbox" name="blister_box[]" id="drink_twist_bottle_glass" OnClick="JavaScript:return fnccheck_twist();" <?php if(in_array('13',$detail_blister)){echo 'checked';}?> value="13">Glass bottle</td>
												<td class="bd-right b-bottom">-</td>
												<td class="bd-right b-bottom">set</td>
												<td class="bd-right b-bottom"><input type="text" name="cost_blister[]" id="cost_drink_twist_bottle_glass" value="1.90" style="width: 20%;"></td>
												<td class="bd-right b-bottom">-</td>
											</tr>	
											<tr>
												<td class="b-left bd-right b-bottom cost-left"><input type="checkbox" name="blister_box[]" id="drink_twist_alu" OnClick="JavaScript:return fnccheck_twist();" <?php if(in_array('14',$detail_blister)){echo 'checked';}?> value="14">Cap Alu 1 color</td>
												<td class="bd-right b-bottom">-</td>
												<td class="bd-right b-bottom">pcs</td>
												<td class="bd-right b-bottom"><input type="text" name="cost_blister[]" id="cost_drink_twist_alu" value="1.90" style="width: 20%;"></td>
												<td class="bd-right b-bottom">-</td>
											</tr>
											<tr>
												<td class="b-left bd-right b-bottom cost-left"><input type="checkbox" name="blister_box[]" id="drink_twist_carton" OnClick="JavaScript:return fnccheck_twist();" <?php if(in_array('15',$detail_blister)){echo 'checked';}?> value="15">Carton</td>
												<td class="bd-right b-bottom">-</td>
												<td class="bd-right b-bottom">set</td>
												<td class="bd-right b-bottom"><input type="text" name="cost_blister[]" id="cost_drink_twist_carton" value="0.50" style="width: 20%;"></td>
												<td class="bd-right b-bottom">-</td>
											</tr>
											<tr>
												<td class="b-left bd-right b-bottom cost-left"><input type="checkbox" name="blister_box[]" id="drink_twist_delivery" OnClick="JavaScript:return fnccheck_twist();" <?php if(in_array('16',$detail_blister)){echo 'checked';}?> value="16">Delivery</td>
												<td class="bd-right b-bottom">-</td>
												<td class="bd-right b-bottom">set</td>
												<td class="bd-right b-bottom"><input type="text" name="cost_blister[]" id="cost_drink_twist_delivery" value="0.50" style="width: 20%;"></td>
												<td class="bd-right b-bottom">-</td>
											</tr>
											<tr>
												<td class="b-left bd-right b-bottom cost-left cost-center" colspan="3"><b>Total</b></td>
												<td class="bd-right b-bottom" colspan="2"><input type="text" name="total_packaging" id="drink_twist_total_packaging" value="<?php echo $rs_pack_blister['total_pack']?>"></td>
											</tr>
											<tr>
												<td class="b-left bd-right b-bottom cost-left cost-center" colspan="3"><b>Total FG cost</b></td>
												<td class="bd-right b-bottom" colspan="2"><input type="text" name="total_fg_cost" id="drink_twist_total_fg_cost" value="<?php echo $rs_pack_blister['total_fg']?>"></td>
											</tr>																	
										</table>
									<?php }elseif(($rs_costing['id_product_appearance']==4) || ($rs_costing['id_product_appearance']==5)){?>
									<?php
										$detail_blister=split(",",$rs_pack_blister['detail_blister']);
										$price_unit=split(",",$rs_pack_blister['price_unit']);
										$num_blister=split(",",$rs_pack_blister['num_blister']);
									?>
										<script language="JavaScript">
											function fnccheck_ins(){
												var t1; var t2; var t3; var t4; var t5; var sum;											

												//moh
												if(document.frm.ins_moh.checked == true){
													document.frm.cost_ins_moh.value= parseFloat(5.00).toFixed(2);
													t1=parseFloat(document.frm.cost_ins_moh.value).toFixed(3);

												}else{	
													t1=0;
													document.frm.cost_ins_moh.value='';
												} 
												
												//box
												if(document.frm.ins_box.checked == true){
													document.frm.cost_ins_box.value= parseFloat(7.00).toFixed(2);
													t2=parseFloat(document.frm.cost_ins_box.value).toFixed(3);
												}else{	
													t2=0;	
													document.frm.cost_ins_box.value='';
												} 

												//shrink
												if(document.frm.ins_shrink.checked == true){
													document.frm.cost_ins_shrink.value= parseFloat(0.50).toFixed(2);
													t3=parseFloat(document.frm.cost_ins_shrink.value).toFixed(3);
												}else{	
													t3=0;	
													document.frm.cost_ins_shrink.value='';
												} 

												//carton
												if(document.frm.ins_carton.checked == true){
													document.frm.cost_ins_carton.value= parseFloat(0.50).toFixed(2);
													t4=parseFloat(document.frm.cost_ins_carton.value).toFixed(3);
												}else{	
													t4=0;	
													document.frm.cost_ins_carton.value='';
												}

												//delivery
												if(document.frm.ins_delivery.checked == true){
													document.frm.cost_ins_delivery.value= parseFloat(0.75).toFixed(2);
													t5=parseFloat(document.frm.cost_ins_delivery.value).toFixed(3);
												}else{	
													t5=0;
													document.frm.cost_ins_delivery.value='';
												}																			

												sum=parseFloat(t1)+parseFloat(t2)+parseFloat(t3)+parseFloat(t4)+parseFloat(t5);
												
												document.frm.ins_total_packaging.value=parseFloat(sum).toFixed(3);

												document.frm.ins_total_fg_cost.value=(parseFloat(sum) + parseFloat(document.frm.sum_cost_per.value)).toFixed(3);	
											}										
											function fncSum_cos(){																	
												document.frm.sum_cost_per.value=(parseFloat(document.frm.num_cost.value) * parseFloat(document.frm.total_bluk.value)).toFixed(3);
											}		
											
										</script>
										<table width="100%" style="border:none;" cellpadding="0" cellspacing="0" id="tb-cost">	
											<tr>
												<td class="b-left bd-right b-top b-bottom w20"><b>Description</b></td>
												<td class="bd-right b-top b-bottom"><b>Specification</b></td>
												<td class="bd-right b-top b-bottom"><b>Unit</b></td>
												<td class="bd-right b-top b-bottom"><b>Price (baht)/Unit</b></td>
												<td class="bd-right b-top b-bottom"><b>Note</b></td>
											</tr>
											<tr>
												<td class="b-left bd-right b-bottom cost-left"><input type="checkbox" name="blister_box[]" id="ins_moh" value="1" OnClick="JavaScript:return fnccheck_ins();" <?php if(in_array('1',$detail_blister)){echo 'checked';}?>>MOH</td>
												<td class="bd-right b-bottom">-</td>
												<td class="bd-right b-bottom">bottle</td>
												<td class="bd-right b-bottom"><input type="text" name="cost_blister[]" id="cost_ins_moh" value="<?php if($price_unit[0]==''){echo '5.00';}else{echo $price_unit[0];}?>" style="width: 20%;"></td>
												<td class="bd-right b-bottom">-</td>
											</tr>
											<tr>
												<td class="b-left bd-right b-bottom cost-left"><input type="checkbox" name="blister_box[]" id="ins_box" value="2" OnClick="JavaScript:return fnccheck_ins();" <?php if(in_array('2',$detail_blister)){echo 'checked';}?>>Box</td>
												<td class="bd-right b-bottom">Art card 300 gram 4 color</td>
												<td class="bd-right b-bottom">set</td>
												<td class="bd-right b-bottom"><input type="text" name="cost_blister[]" id="cost_ins_box" value="<?php if($price_unit[1]==''){echo '7.00';}else{echo $price_unit[1];}?>" style="width: 20%;"></td>
												<td class="bd-right b-bottom">-</td>
											</tr>
											<tr>
												<td class="b-left bd-right b-bottom cost-left"><input type="checkbox" name="blister_box[]" id="ins_shrink" value="3" OnClick="JavaScript:return fnccheck_ins();" <?php if(in_array('3',$detail_blister)){echo 'checked';}?>>Shrink</td>
												<td class="bd-right b-bottom">-</td>
												<td class="bd-right b-bottom">pcs</td>
												<td class="bd-right b-bottom"><input type="text" name="cost_blister[]" id="cost_ins_shrink" value="<?php if($price_unit[2]==''){echo '0.50';}else{echo $price_unit[2];}?>" style="width: 20%;"></td>
												<td class="bd-right b-bottom">-</td>
											</tr>
											<tr>
												<td class="b-left bd-right b-bottom cost-left"><input type="checkbox" name="blister_box[]" id="ins_carton" value="4" OnClick="JavaScript:return fnccheck_ins();" <?php if(in_array('4',$detail_blister)){echo 'checked';}?>>Carton</td>
												<td class="bd-right b-bottom">-</td>
												<td class="bd-right b-bottom">set</td>
												<td class="bd-right b-bottom"><input type="text" name="cost_blister[]" id="cost_ins_carton" value="<?php if($price_unit[3]==''){echo '0.5';}else{echo $price_unit[3];}?>" style="width: 20%;"></td>
												<td class="bd-right b-bottom">-</td>
											</tr>
											<tr>
												<td class="b-left bd-right b-bottom cost-left"><input type="checkbox" name="blister_box[]" id="ins_delivery" value="5" OnClick="JavaScript:return fnccheck_ins();" <?php if(in_array('5',$detail_blister)){echo 'checked';}?>>Delivery</td>
												<td class="bd-right b-bottom">-</td>
												<td class="bd-right b-bottom">set</td>
												<td class="bd-right b-bottom"><input type="text" name="cost_blister[]" id="cost_ins_delivery" value="<?php if($price_unit[4]==''){echo '0.75';}else{echo $price_unit[4];}?>" style="width: 20%;"></td>
												<td class="bd-right b-bottom">-</td>
											</tr>
											<tr>
												<td class="b-left bd-right b-bottom cost-left cost-center" colspan="3"><b>Total</b></td>
												<td class="bd-right b-bottom" colspan="2"><input type="text" name="total_packaging_drink" id="ins_total_packaging" value="<?php echo $rs_pack_blister['total_pack']?>"></td>
											</tr>
											<tr>
												<td class="b-left bd-right b-bottom cost-left cost-center" colspan="3"><b>Total FG cost</b></td>
												<td class="bd-right b-bottom" colspan="2"><input type="text" name="total_fg_cost_drink" id="ins_total_fg_cost" value="<?php echo $rs_pack_blister['total_fg']?>"></td>
											</tr>
										</table>
									<?php }?>
									<?php }elseif($_GET['fac']==3){//factory prima?>
									<script>
										function fncSum_Prima(){																	
											document.frm.prima_sum_cost_per.value=(parseFloat(document.frm.prima_num_cost.value) * parseFloat(document.frm.prima_total_bluk.value)).toFixed(3);
										}
									</script>
									<input type="hidden" name="id_costing_factory_prima" value="<?php echo $rs_prima['id_costing_factory_prima']?>">
									<tr>	
										<td><div style="float:left; margin-right: 2%;">Cost per</div><input type="text" name="prima_num_cost" id="prima_num_cost" value="<?php echo $rs_prima['prima_num_cost']?>" OnChange="fncSum_Prima();" style="width:20%;"></td>
										<td>
											<input type="hidden" name="prima_total_bluk" id="prima_total_bluk" value="<?php echo $rs_prima['total_bluk']?>">
											<input type="text" name="prima_sum_cost_per" id="prima_sum_cost_per" value="<?php echo $rs_prima['prima_sum_cost_per']?>" style="width:20%;">
										</td>
									</tr>
									<?php }?>
								<?php }elseif($_GET['p']==4){?>
								<script type="text/javascript" src="js/js-autocomplete/js/jquery-1.4.2.min.js"></script> 
								<script type="text/javascript" src="js/js-autocomplete/js/jquery-ui-1.8.2.custom.min.js"></script> 
								<script type="text/javascript"> 
									jQuery(document).ready(function(){
										$('.title_pre_quotation').autocomplete({
											source:'return-pre-quotation.php', 
											//minLength:2,
											select:function(evt, ui)
											{
												// when a zipcode is selected, populate related fields in this form
												this.form.id_pre_quotation.value = ui.item.id_pre_quotation;
												this.form.num_pre_quotation.value = ui.item.num_pre_quotation;
												this.form.unit_pre_quotation.value = ui.item.unit_pre_quotation;
												this.form.pre_quotation_price.value = ui.item.pre_quotation_price;
												this.form.sum_quotation_price.value = ui.item.sum_quotation_price;
												this.form.id_product_appearance.value = ui.item.id_product_appearance;
											}
										});
										$('.title_pre_quotation2').autocomplete({
											source:'return-pre-quotation.php', 
											//minLength:2,
											select:function(evt, ui)
											{
												// when a zipcode is selected, populate related fields in this form
												this.form.id_pre_quotation2.value = ui.item.id_pre_quotation2;
												this.form.num_pre_quotation2.value = ui.item.num_pre_quotation2;
												this.form.unit_pre_quotation2.value = ui.item.unit_pre_quotation2;
												this.form.pre_quotation_price2.value = ui.item.pre_quotation_price2;
												this.form.sum_quotation_price2.value = ui.item.sum_quotation_price2;
											}
										});
										$('.company_name').autocomplete({
											source:'return-company.php', 
											//minLength:2,
											select:function(evt, ui)
											{
												// when a zipcode is selected, populate related fields in this form
												this.form.id_company.value = ui.item.id_company;
												this.form.company_address.value = ui.item.company_address;
												this.form.company_contact.value = ui.item.company_contact;
												this.form.company_email.value = ui.item.company_email;
											}
										});
										$('.product_name').autocomplete({
											source:'return-product.php', 
											//minLength:2,
											select:function(evt, ui)
											{
												// when a zipcode is selected, populate related fields in this form
												this.form.id_product.value = ui.item.id_product;
											}
										});
									});
								</script> 
								<link rel="stylesheet" href="js/js-autocomplete/css/smoothness/jquery-ui-1.8.2.custom.css" />
									<input type="hidden" name="nextstep" value="4">
									<tr>
										<td colspan="2"><h3>Step 4 : Quotation</h3></td>
									</tr>									
									<?php 									
									$sql_quo="select * from costing_quotation where id_costing_factory='".$id."'";
									$res_quo=mysql_query($sql_quo) or die ('Error '.$sql_quo);
									$rs_quo=mysql_fetch_array($res_quo);
									?>
									<input type="hidden" name="quo" value="<?php echo $rs_quo['id_costing_quotation']?>">									
									<table style="border: 1px solid #eee; width: 100%;" cellpadding="0" cellspacing="0" id="tb-quotation">
											<tr>
												<td class="b-bottom" colspan="4">
													<h4>บริษัท ซีดีไอพี (ประเทศไทย) จำกัด<br>
													CDIP (Thailand) Co.,Ltd.<br>
													</h4>
													131 อาคารกลุ่มนวัตกรรม1 ห้อง227 อุทยานวิทยาศาสตร์ประเทศไทย ถ.พหลโยธิน ต.คลองหนึ่ง อ.คลองหลวง จ.ปทุมธานี 12120<br>
													131 INC1  No.227  Thailand Science park  Paholyothin Rd.  Klong1  Klong Luang  Pathumthani  12120  THAILAND  Tel: 0 2564 7200 # 5227  Fax: 0 2564 7745 
												</td>
												<td class="b-bottom" colspan="3"><img src="img/logo.png" width="140" class="img-logo"></td>
											</tr>
											<tr>
												<td class="b-bottom center" colspan="7"><h4>ใบเสนอราคา</h4></td>
											</tr>
											<tr>
												<td class="bd-right b-bottom top" rowspan="2">ชื่อบริษัท</td>
												<td class="bd-right b-bottom top" rowspan="2" colspan="2">
													<?php
													if($rs_quo['company_name'] == ''){
														$sql_company="select * from company where id_company='".$rs_quo['id_company']."'";
														$res_company=mysql_query($sql_company) or die ('Error '.$sql_company);
														$rs_company=mysql_fetch_array($res_company);
														$id_company=$rs_company['id_company'];
														$company_name=$rs_company['company_name'];
													}else{$company_name=$rs_quo['company_name'];}
													?>
													<input type="hidden" name="id_company" value="<?php echo $id_company?>">
													<input type="text" name="company_name" id="company_name" class="company_name" value="<?php echo $company_name?>">	
												</td>												
												<td class="bd-right b-bottom top" colspan="2">เลขที่ใบเสนอราคา </td>
												<td class="bd-right b-bottom"><input type="text" name="quotation_no" value="<?php echo $rs_quo['quotation_no']?>">
												</td>
											</tr>
											<tr>
												<td class="bd-right b-bottom top" colspan="2">วันที่</td>
												<td class="bd-right b-bottom" colspan="3"><?php echo date("d/m/Y")?></td>
											</tr>
											<tr>
												<?php
												if($rs_quo['contact_name'] == ''){
													$sql_contact="select * from company_contact where id_company='".$rs_company['id_company']."'";
													$res_contact=mysql_query($sql_contact) or die ('Error '.$sql_contact);
													$rs_contact=mysql_fetch_array($res_contact);
													$contact=$rs_contact['contact_name'];
												}else{$contact=$rs_quo['contact_name'];}
												?>
												<td class="bd-right b-bottom top">ชื่อผู้ติดต่อ/เบอร์ </td>
												<td class="bd-right b-bottom top" colspan="2"><input type="text" name="company_contact" id="company_contact" value="<?php echo $contact?>"></td>
												<td class="bd-right b-bottom top" colspan="2">ผู้เสนอราคา</td>
												<td class="bd-right b-bottom"><?php echo $rs_acc['name']?>
												</td>
											</tr>
											<tr>
												<?php
												$sql_address="select * from company_address where id_address='".$rs_company['id_address']."'";
												$res_address=mysql_query($sql_address) or die ('Error '.$sql_address);
												$rs_address=mysql_fetch_array($res_address);
												?>
												<td class="bd-right b-bottom top" rowspan="2">ที่อยู่</td>
												<td class="bd-right b-bottom top" rowspan="2" colspan="2"><textarea name="address" id="company_address"><?php if($rs_quo['address'] == ''){echo $rs_address['address_no'].'&nbsp;&nbsp;'.$rs_address['road'].'&nbsp;&nbsp;'.$rs_address['sub_district'].'&nbsp;&nbsp;'.$rs_address['district'].'&nbsp;&nbsp;'.$rs_address['province'].'&nbsp;&nbsp;'.$rs_address['postal_code'];}else{echo $rs_quo['address'];}?></textarea></td>
												<td class="bd-right b-bottom top" colspan="2">ระยะเวลาในการส่งของ</td>
												<td class="bd-right b-bottom">75-90 วัน</td>
											</tr>
											<tr>
												<td class="bd-right b-bottom top" colspan="2">จำนวนวันเครดิต</td>
												<td class="bd-right b-bottom">0 วัน</td>
											</tr>
											<tr>
												<td class="bd-right b-bottom top">E-Mail:</td>
												<td class="bd-right b-bottom" colspan="2"><input type="text" name="company_email" id="company_email" value="<?php if($rs_quo['email'] == ''){echo $rs_contact['email'];}else{echo $rs_quo['email'];}?>"></td>
												<td class="bd-right b-bottom top" colspan="2">กำหนดยืนราคา</td>
												<td class="bd-right b-bottom">30 วันนับจากวันที่เสนอราคา</td>
											</tr>
											<tr>
												<td class="bd-right b-bottom top center">ลำดับที่</td>
												<td class="bd-right b-bottom top center" style="width:40%;">รายการ</td>
												<td class="bd-right b-bottom top center">จำนวน</td>
												<td class="bd-right b-bottom top center">หน่วยนับ</td>
												<td class="bd-right b-bottom top center">ราคา/หน่วย</td>
												<td class="bd-right b-bottom top center">จำนวนเงิน</td>
											</tr>
											<script>
												function fncSumFG(){
													document.frm.quo_total.value = (parseFloat(document.frm.quatatity.value) * parseFloat(document.frm.cost_unit.value)).toFixed(3);//fix float=3

													document.frm.total.value = parseFloat(document.frm.quo_total.value).toFixed(3);
												}
											</script>
											<tr>
												<?php
												$sql_product="select * from product where id_product='".$rs['id_product']."'";
												$res_product=mysql_query($sql_product) or die ('Error '.$sql_prodcut);
												$rs_product=mysql_fetch_array($res_product);													
												?>
												<td class="bd-right b-bottom center">1</td>
												<td class="bd-right b-bottom"> 
													<?php 
													$sql_npd="select * from npd_type_product where id_npd_type_product='".$rs_costing['id_product_cate']."'";
													$res_npd=mysql_query($sql_npd) or die('Error '.$sql_npd);
													$rs_npd=mysql_fetch_array($res_npd);
													echo $rs_npd['npd_title'].'&nbsp;:&nbsp;';
													$i_blister=0;
													$detail_blister=split(",",$rs_pack_blister['detail_blister']);
													$price_unit=split(",",$rs_pack_blister['price_unit']);
													$num_blister=split(",",$rs_pack_blister['num_blister']);
													$bottle_detail=split(",",$rs_pack_blister['bottle_detail']);
													echo $rs_costing['product_name'];
													echo '&nbsp;&nbsp;&nbsp;';
													echo '<br>';
													echo '<div style="float:left;margin-right:2%;">';
													echo 'เลขสารบบ อย.';
													echo '</div>';
													echo '<div style="float:left;">';
													?>
													<input type="text" name="serial_number" value="<?php echo $rs_quo['serial_number']?>"></div>
													<?php
													echo '</div>';													
													echo '<br><br>';
													echo '<div style="float:left;margin-right:2%;">';
													echo 'ขนาดบรรจุ';
													echo '</div>';
													echo '<div style="float:left;margin-right:2%;">';
													?>
													<input type="text" name="packaging" value="<?php echo $rs_quo['packaging']?>"></div>
													<?php
													if(($rs_costing['id_product_appearance']==1) || ($rs_costing['id_product_appearance']==2)){echo '<div style="float:left;">\'s</div>';}
													elseif(($rs_costing['id_product_appearance']==4) || ($rs_costing['id_product_appearance']==5) || ($rs_costing['id_product_appearance']==6)){echo '<div style="float:left;">cc</div>';}
													echo '<br><br>';
													if($_GET['fac']==1){
													echo 'บรรจุภัณฑ์ประกอบด้วย';
													echo '&nbsp;&nbsp;&nbsp;';		
													if(($rs_costing['id_product_appearance']==1) || ($rs_costing['id_product_appearance']==2) || ($rs_costing['id_product_appearance']==3)){
														if($rs_pack_blister['type_pack']==1){
															$i_blister=0;
															$j=0;
															$count_blister=count($detail_blister)-1;
															$sql_jsp_blister="select * from jsp_pack_blister";
															$res_jsp_blister=mysql_query($sql_jsp_blister) or die ('Error '.$sql_jsp_blister);
															while($rs_jsp_blister=mysql_fetch_array($res_jsp_blister)){
																if($detail_blister[$i_blister]==$rs_jsp_blister['id_jsp_pack_blister']){
																	if(($detail_blister[$i_blister]==1) || ($detail_blister[$i_blister]==2)){echo '';}
																	else{
																		if($rs_jsp_blister['description']=='Box'){echo $rs_jsp_blister['specification'];}
																		else{if($rs_jsp_blister['description']=='Delivery'){echo 'Delivery BKK and Vicinity area';}else{echo $rs_jsp_blister['description'];}}
																		if($i_blister<$count_blister){	
																			echo '&nbsp;&nbsp;';
																			//echo $num_blister[$j];	
																			//if($rs_jsp_blister['description']=='Blister'){echo 'pcs';}
																			//elseif($rs_jsp_blister['description']=='Silica gel'){echo 'pcs';}
																			//elseif($rs_jsp_blister['description']=='Aliminum pouch'){echo 'pcs';}
																			echo '+';
																			echo '&nbsp;&nbsp;';
																			$j++;
																		}
																	}
																	$i_blister++;
																}															
															}
														}elseif($rs_pack_blister['type_pack']==2){
															$i_blister=0;
															$j=0;
															$count_blister=count($detail_blister)-1;
															$sql_jsp_blister="select * from jsp_pack_bottle";
															$res_jsp_blister=mysql_query($sql_jsp_blister) or die ('Error '.$sql_jsp_blister);
															while($rs_jsp_blister=mysql_fetch_array($res_jsp_blister)){
																if($detail_blister[$i_blister]==$rs_jsp_blister['id_jsp_pack_bottle']){
																	if(($detail_blister[$i_blister]==1) || ($detail_blister[$i_blister]==2)){echo '';}
																	else{
																		if($rs_jsp_blister['description']=='Box'){echo $rs_jsp_blister['specification'];}
																		else{																		
																			if($rs_jsp_blister['description']=='Delivery'){echo 'Delivery BKK and Vicinity area';}
																			else{
																				if($rs_jsp_blister['description']=='Bottle'){
																					$sql_bdetail="select * from jsp_pack_bottle_detail";
																					$sql_bdetail .=" where id_jsp_pack_bottle_detail='".$rs_pack_blister['bottle_detail']."'";
																					$res_bdetail=mysql_query($sql_bdetail) or die ('Error '.$sql_bdetail);
																					$rs_bdetail=mysql_fetch_array($res_bdetail);
																					echo $rs_jsp_blister['description'].'&nbsp;&nbsp;'.$rs_bdetail['description'];
																				}else{echo $rs_jsp_blister['description'];}
																			}
																		}
																		if($i_blister<$count_blister){	
																			echo '&nbsp;&nbsp;';
																			//if($num_blister[$j]=='Array'){echo '';}else{echo /*$num_blister[$j]*/'';}	//if($rs_jsp_blister['description']=='Silica gel'){echo 'pcs';}
																			echo '+';
																			echo '&nbsp;&nbsp;';
																			$j++;
																		}
																	}
																	$i_blister++;
																}															
															}
														}
													}//end table softgel capsule
													elseif($rs_costing['id_product_appearance']==6){
														if($rs_pack_blister['bottle_cap']==1){
															$count_blister=count($detail_blister);	
															for($i_blister==-1;$i_blister<$count_blister;$i_blister++){
																if($detail_blister[$i_blister]==1){echo '';}
																else{
																	if($detail_blister[$i_blister]==2){echo 'Shrink';}
																	elseif($detail_blister[$i_blister]==3){echo 'Label Sticker Paper';}
																	elseif($detail_blister[$i_blister]==4){echo 'Shrink 6 bottle';}
																	elseif($detail_blister[$i_blister]==5){echo 'Glass bottle';}
																	elseif($detail_blister[$i_blister]==6){echo 'Cap Alu 1 color';}
																	elseif($detail_blister[$i_blister]==7){echo 'Carton';}
																	elseif($detail_blister[$i_blister]==8){echo 'Delivery BKK and Vicinity area';}
																	
																	if($i_blister<($count_blister-1)){	
																		echo '&nbsp;&nbsp;';																	
																		echo '+';
																		echo '&nbsp;&nbsp;';
																	}
																}																
															}//end for															
														}elseif($rs_pack_blister['bottle_cap']==2){															
															$count_blister=count($detail_blister);	
															$sum=$count_blister-1;
															for($i_blister==-1;$i_blister<$count_blister;$i_blister++){
																if($detail_blister[$i_blister]==9){echo '';}
																else{
																	if($detail_blister[$i_blister]==10){echo 'Box';}
																	elseif($detail_blister[$i_blister]==11){echo 'Label Sticker Paper';}
																	elseif($detail_blister[$i_blister]==12){echo 'Holder Carton';}
																	elseif($detail_blister[$i_blister]==13){echo 'Glass bottle';}
																	elseif($detail_blister[$i_blister]==14){echo 'Cap Alu 1 color';}
																	elseif($detail_blister[$i_blister]==15){echo 'Carton';}
																	elseif($detail_blister[$i_blister]==16){echo 'Delivery BKK and Vicinity area';}																	
																	if($i_blister<$sum){	
																		echo '&nbsp;&nbsp;';																	
																		echo '+';
																		echo '&nbsp;&nbsp;';
																	}
																}																
															}//end for
														}
													}elseif(($rs_costing['id_product_appearance']==4) || ($rs_costing['id_product_appearance']==5)){
														$count_blister=count($detail_blister);	
														$sum=$count_blister-1;
														for($i_blister==-1;$i_blister<$count_blister;$i_blister++){
															if($detail_blister[$i_blister]==1){echo '';}
															else{
																if($detail_blister[$i_blister]==2){echo 'Art card 300 gram 4 color';}
																elseif($detail_blister[$i_blister]==3){echo 'Shrink';}
																elseif($detail_blister[$i_blister]==4){echo 'Carton';}
																elseif($detail_blister[$i_blister]==5){echo 'Delivery BKK and Vicinity area';}
																if($i_blister<$sum){	
																	echo '&nbsp;&nbsp;';																	
																	echo '+';
																	echo '&nbsp;&nbsp;';
																}
															}																
														}//end for
													}
													}
													echo '<br><br>';													
													echo 'ส่วนประกอบที่สำคัญต่อ 1 หน่วย';
													echo '<br>';
													$sql_npd_rela="select * from costing_rm where id_roc='".$rs_costing['id_costing_factory']."'";
													$sql_npd_rela .=" order by quantities desc";
													$res_npd_rela=mysql_query($sql_npd_rela) or die ('Error '.$sql_npd_rela);
													while($rs_npd_rela=mysql_fetch_array($res_npd_rela)){
														echo '<div style="width: 40%; float:left;">';
														echo $rs_npd_rela['rm_name'];
														echo '</div>';
														echo '<div style="width: 20%; float:left; text-align:right;">';
														echo number_format($rs_npd_rela['quantities'],2).' mg';
														echo '</div>';
														echo '<br>';

													 }?>
												<td class="bd-right b-bottom"><input type="text" name="quatatity" OnChange="fncSumFG();" value="<?php echo $rs_quo['quo_quatatity']?>"></td>
												<td class="bd-right b-bottom"><input type="text" name="quo_unit" value="<?php echo $rs_quo['quo_unit']?>"></td>
												<td class="bd-right b-bottom"><input type="text" name="cost_unit" value="<?php if($_GET['fac']==1){if($rs_quo['quo_price'] != 0){echo $rs_quo['quo_price'];}else{echo number_format($rs_pack_blister['total_fg'],2);}}elseif($_GET['fac']==3){if($rs_quo['quo_price'] != 0){echo $rs_quo['quo_price'];}else{echo $rs_prima['prima_sum_cost_per'];}}?>" OnChange="fncSumFG();"></td>
												<td class="bd-right b-bottom"><input type="text" name="quo_total" value="<?php echo $rs_quo['quo_total']?>" class="right-price"></td>
											</tr>
											<tr>
												<td class="bd-right b-bottom top" rowspan="4">หมายเหตุ :</td>
												<td class="bd-right b-bottom" colspan="2" rowspan="4">
													<?php $remark=split(",",$rs_quo['remark']);?>
													<div style="float:left;"><input type="checkbox" name="remark[]" <?php if(in_array('1',$remark)){echo 'checked';}?> value="1"></div><div style="float:left;margin-right:2%;">ลูกค้า supply</div><div style="float:left;"><input type="text" name="customer_supply" value="<?php echo $rs_quo['customer_supply']?>"></div><br><br>
													<input type="checkbox" name="remark[]" <?php if(in_array('2',$remark)){echo 'checked';}?> value="2">1. ระยะเวลาในการดำเนินการผลิตประมาณ 90 วัน สำหรับการผลิต Lot แรก<br>
													<input type="checkbox" name="remark[]" <?php if(in_array('3',$remark)){echo 'checked';}?> value="3">2. การแจ้งยกเลิก PO สามารถแจ้งยกเลิกได้ภายใน 7 วันหลังเปิด PO มาแล้ว<br>
													<input type="checkbox" name="remark[]" <?php if(in_array('4',$remark)){echo 'checked';}?> value="4">3. MOQ การผลิตปกติคือ  
													<?php 
													if($rs_costing['id_product_appearance']==1){
														echo '150,000  เม็ด ';
														echo '<br>';
													?>
														<input type="checkbox" name="allowed" value="1" <?php if($rs_quo['allowed_half']==1){echo 'checked';}?> style="margin:0 0 0 5%;">
													<?php
														echo '<span style="vertical-align:middle;">';
														echo 'แต่ในกรณีที่เป็น Lot 1 ทางบริษัทฯอนุโลมลดให้เหลือครึ่งหนึ่ง';
														echo '</span>';
													}
													//Capsule
													elseif($rs_costing['id_product_appearance']==2){
														echo '150,000 แคปซูล ';
														echo '<br>';
													?>
														<input type="checkbox" name="allowed" value="1" <?php if($rs_quo['allowed_half']==1){echo 'checked';}?> style="margin:0 0 0 5%;">
													<?php
														echo '<span style="vertical-align:middle;">';
														echo 'แต่ในกรณีที่เป็น Lot 1 ทางบริษัทฯอนุโลมลดให้เหลือครึ่งหนึ่ง';
														echo '</span>';
													}
													//Softgel
													elseif($rs_costing['id_product_appearance']==3){echo '150,000 แคปซูล';}
													//Instant Drink
													elseif(($rs_costing['id_product_appearance']==4) || ($rs_costing['id_product_appearance']==5)){echo '250,000 ซอง';}
													//Functional Drink
													elseif($rs_costing['id_product_appearance']==6){
														echo '150,000 ขวด';
													}
													?>
												</td>
												<td class="bd-right b-bottom" colspan="2">รวมเป็นเงิน</td>
												<td class="bd-right b-bottom top">
													<input type="text" name="total" value="<?php echo $rs_quo['quo_total']?>" class="right-price"
												</td>
											</tr>
											<tr>												
												<td class="bd-right b-bottom" colspan="2">ส่วนลด</td>
												<td class="bd-right b-bottom">
												<script language="JavaScript">
													function fncTotal(){
														document.frm.total_discount.value = parseFloat(document.frm.total.value) - parseFloat(document.frm.discount.value);

														document.frm.vat_7.value = parseFloat(document.frm.total_discount.value) * 7/100;

														document.frm.total_price.value = parseFloat(document.frm.total_discount.value) + parseFloat(document.frm.vat_7.value);
													}
												</script>
												<input type="text" name="discount" value="<?php if($rs_quo['quo_discount']==0){echo '';}else{echo $rs_quo['quo_discount'];}?>" class="right-price" OnChange="fncTotal();"></td>
											</tr>
											<tr>
												<td class="bd-right b-bottom" colspan="2">รวมเงินหลังหักส่วนลด</td>
												<td class="bd-right b-bottom"><input type="text" name="total_discount" value="<?php echo $rs_quo['total_discount']?>" class="right-price"></td>
											</tr>
											<tr>
												<td class="bd-right b-bottom" colspan="2">Vat 7%</td>
												<td class="bd-right b-bottom"><input type="text" name="vat_7" value="<?php echo $rs_quo['vat_7']?>" class="right-price"></td>
											</tr>
											<tr>
												<td class="bd-right b-bottom center" colspan="3">
												<?php
												function convert($number){ 
													$txtnum1 = array('ศูนย์','หนึ่ง','สอง','สาม','สี่','ห้า','หก','เจ็ด','แปด','เก้า','สิบ'); 
													$txtnum2 = array('','สิบ','ร้อย','พัน','หมื่น','แสน','ล้าน','สิบ','ร้อย','พัน','หมื่น','แสน','ล้าน'); 
													$number = str_replace(",","",$number); 
													$number = str_replace(" ","",$number); 
													$number = str_replace("บาท","",$number); 
													$number = explode(".",$number); 
													if(sizeof($number)>2){ 
													return 'ทศนิยมหลายตัวนะจ๊ะ'; 
													exit; 
													} 
													$strlen = strlen($number[0]); 
													$convert = ''; 
													for($i=0;$i<$strlen;$i++){ 
														$n = substr($number[0], $i,1); 
														if($n!=0){ 
															if($i==($strlen-1) AND $n==1){ $convert .= 'เอ็ด'; } 
															elseif($i==($strlen-2) AND $n==2){  $convert .= 'ยี่'; } 
															elseif($i==($strlen-2) AND $n==1){ $convert .= ''; } 
															else{ $convert .= $txtnum1[$n]; } 
															$convert .= $txtnum2[$strlen-$i-1]; 
														} 
													} 

													$convert .= 'บาท'; 
													if($number[1]=='0' OR $number[1]=='00' OR 
													$number[1]==''){ 
													$convert .= 'ถ้วน'; 
													}else{ 
													$strlen = strlen($number[1]); 
													for($i=0;$i<$strlen;$i++){ 
													$n = substr($number[1], $i,1); 
														if($n!=0){ 
														if($i==($strlen-1) AND $n==1){$convert 
														.= 'เอ็ด';} 
														elseif($i==($strlen-2) AND 
														$n==2){$convert .= 'ยี่';} 
														elseif($i==($strlen-2) AND 
														$n==1){$convert .= '';} 
														else{ $convert .= $txtnum1[$n];} 
														$convert .= $txtnum2[$strlen-$i-1]; 
														} 
													} 
													$convert .= 'สตางค์'; 
													} 
													return $convert; 
												} 
												//$x = '37840'; 
												echo  $x  .convert($rs_quo['total_all']); 
												?>
												</td>
												<td class="bd-right b-bottom" colspan="2">จำนวนเงินทั้งสิ้น</td>
												<td class="bd-right b-bottom"><input type="text" name="total_price" value="<?php echo $rs_quo['total_all']?>" class="right-price"></td>
											</tr>
											<tr>
												<td class="bd-right" colspan="2" rowspan="2"><b>เงื่อนไขอื่นๆ</b><br>
													(1) เงื่อนไขการชำระเงิน 50% พร้อมใบเสนอราคาและ 50% ณ วันส่งสินค้า<br>
													(2) กรุณาโอนเงินเข้าบัญชีธนาคารทหารไทย เลขที่บัญชี 073-1-05570-3<br>
													ชื่อบัญชี บริษัท ซีดีไอพี (ประเทศไทย) จำกัด <br>
													(3) ข้อสงสัยเรื่องการชำระเงินกรุณาติดต่อแผนกบัญชี เบอร์ 02 564 7200 ต่อ 5227<br>
													(4) บริษัทฯจะยืนยันวันส่งของกลับภายหลังได้รับใบสั่งซื้อภายใน 7 วันทำการ<br>
													(5) สินค้าเป็นแบบการสั่งผลิตเฉพาะ อาจได้รับสินค้าในปริมาณ <u>+</u>10% ของจำนวนการสั่งซื้อ<br>
													(6) ผู้ซื้อต้องรับสินค้าทั้งหมดที่ผลิตเสร็จแล้วภายใน 7 วัน หลังได้รับแจ้งจาก บริษัท ซีดีไอพีฯ กรณีรับสินค้าไม่หมด คิดค่าใช้จ่ายในการเก็บรักษาสินค้า วันละ 0.25% ของมูลค่าสินค้าที่ค้างส่ง<br>
													(7) ในกรณีที่มีการยกเลิกใบสั่งซื้อบริษัทฯขอสงวนสิทธิ์ในการคืนเงินมัดจำ
												</td>
												<td class="bd-right b-bottom" colspan="2"><div style="text-align:center;">ผู้จัดทำ</div>
												<br><br><br><div style="text-align:center;">.........../........../............<br><?php echo $rs_acc['name']?></div>
												</td>
												<td class="bd-right b-bottom" colspan="2" rowspan="2"><div style="text-align:center;">อนุมัติโดย</div><br><br><br><br>
												<div style="text-align:center;">.........../........../............<br>ลูกค้า</div>
												</td>
											</tr>
											<tr>
												<td class="bd-right b-bottom" colspan="2"><div style="text-align:center;">ผู้ตรวจสอบ<br><br><br><br>.........../........../............<br>
												</div></td>
											</tr>
										</table>
								<?php }?>
							</table>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="large-4 columns">
							<?php if(!is_numeric($id)){?>
							<input type="button" name="save_data" id="save_data" value="<?php echo $button?>" class="button-create" OnClick="frm.hdnCmd.value='save_data';JavaScript:return fncSubmit();">
							<?php }else{if($_GET['p']==1){?>	
							<input type="button" name="next_step" id="update_data" value="Next step" class="button-create" OnClick="frm.hdnCmd.value='update_data';JavaScript:return fncSubmit();">
							<?php }elseif($_GET['p']==2){?>
							<input type="button" name="back_step_one" id="update_data" value="Back step one" class="button-create" onclick="window.location.href='ac-costing-table.php?id_u=<?php echo $id?>&fac=<?php echo $factory?>&p=1'">
							<input type="button" name="next_step" id="update_data" value="Next step" class="button-create" OnClick="frm.hdnCmd.value='update_data';JavaScript:return fncSubmit();">
							<?php }elseif($_GET['p']==3){?>
							<input type="button" name="back_step_one" id="update_data" value="Back step two" class="button-create" onclick="window.location.href='ac-costing-table.php?id_u=<?php echo $id?>&fac=<?php echo $factory?>&p=2'">
							<input type="button" name="next_step" id="update_data" value="Next step" class="button-create" OnClick="frm.hdnCmd.value='update_data';JavaScript:return fncSubmit();">
							<?php }elseif($_GET['p']==4){?>
							<input type="button" name="back_step_one" id="update_data" value="Back step three" class="button-create" onclick="window.location.href='ac-costing-table.php?id_u=<?php echo $id?>&fac=<?php echo $factory?>&p=3'">
							<input type="button" name="update_data" id="update_data" value="Save" class="button-create" OnClick="frm.hdnCmd.value='update_data';JavaScript:return fncSubmit();">
							<input type="button" name="update_data" id="update_data" value="Print PDF Thai" class="button-create" Onclick="window.open('pdf-costing-thai.php?id_u=<?php echo $id?>&fac=<?php echo $factory?>')">
							<input type="button" name="update_data" id="update_data" value="Print PDF Eng" class="button-create" Onclick="window.open('pdf-costing-eng.php?id_u=<?php echo $id?>&fac=<?php echo $factory?>')">
							<?php }?>	
							<?php }?>
							<input type="button" value="Close" class="button-create" onclick="window.location.href='costing-factory.php?fac=<?php echo $factory?>'">
						</div><br><br><br>
					</td>
				</tr>
			</table>
			</form>
		</div>
	</div>
<?php if(($_GET['p']==2) || ($_GET['p']==3)){?>
<!-- auto save
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>-->
<script type="text/javascript" src="js/jquery.autosave.js"></script>
<script type="text/javascript">
	$(function(){
		getDatabase();
		$("input,select,textarea").autosave({
			url: "dbcosting-table.php",
			method: "post",
			grouped: true,
			success: function(data) {
				$("#message p").html("Data updated successfully").show();
				setTimeout('fadeMessage()',1500);
				getDatabase();
			},
			send: function(){
				$("#message p").html("Sending data....");
			},
			dataType: "html"
		});		
	});
	function getDatabase(){
		$.get('autosave3.php', function(data) {
			$('#database').html(data);
		});	
	}
	function fadeMessage(){
		$('#message p').fadeOut('slow');
	}
</script>
<!-- end auto save-->
<?php }?>
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
