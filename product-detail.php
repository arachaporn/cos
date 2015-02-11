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
	<?php 
		$id=$_GET["id_u"];
		$sql="select * from product_detail where id_product_detail='".$id."'";
		$res=mysql_query($sql) or die ('Error '.$sql);
		$rs=mysql_fetch_array($res);

		$sql_product="select * from product where id_product='".$rs['id_product']."'";
		$res_product=mysql_query($sql_product) or die ('Error '.$sql_product);
		$rs_product=mysql_fetch_array($res_product);

		$sql_company="select * from company where id_company='".$rs['id_company']."'";
		$res_company=mysql_query($sql_company) or die ('Error '.$sql_company);
		$rs_company=mysql_fetch_array($res_company);

		$sql_product_factory="select * from product_factory where id_product_factory='".$rs['id_product_factory']."'";
		$res_product_factory=mysql_query($sql_product_factory) or die ('Error '.$sql_product_factory);
		$rs_product_factory=mysql_fetch_array($res_product_factory);

		$sql_product_packing="select * from product_packing where id_packing_factory='".$rs['id_packing_factory']."'";
		$res_product_packing=mysql_query($sql_product_packing) or die ('Error '.$sql_product_packing);
		$rs_product_packing=mysql_fetch_array($res_product_packing);

		$sql_product_rm="select * from product_rm where id_product_rm='".$rs['id_product_rm']."'";
		$res_product_rm=mysql_query($sql_product_rm) or die ('Error '.$sql_product_rm);
		$rs_product_rm=mysql_fetch_array($res_product_rm);

		$sql_product_box="select * from product_box where id_product_box='".$rs['id_product_box']."'";
		$res_product_box=mysql_query($sql_product_box) or die ('Error '.$sql_product_box);
		$rs_product_box=mysql_fetch_array($res_product_box);

		$sql_product_bottle="select * from product_bottle where id_product_bottle='".$rs['id_product_bottle']."'";
		$res_product_bottle=mysql_query($sql_product_bottle) or die ('Error '.$sql_product_bottle);
		$rs_product_bottle=mysql_fetch_array($res_product_bottle);

		$sql_product_sticker="select * from product_sticker where id_product_sticker='".$rs['id_product_sticker']."'";
		$res_product_sticker=mysql_query($sql_product_sticker) or die ('Error '.$sql_product_sticker);
		$rs_product_sticker=mysql_fetch_array($res_product_sticker);
	
		$sql_product_alu="select * from product_alu where id_product_alu='".$rs['id_product_alu']."'";
		$res_product_alu=mysql_query($sql_product_alu) or die ('Error '.$sql_product_alu);
		$rs_product_alu=mysql_fetch_array($res_product_alu);

		$sql_product_foil="select * from product_foil where id_product_foil='".$rs['id_product_foil']."'";
		$res_product_foil=mysql_query($sql_product_foil) or die ('Error '.$sql_product_foil);
		$rs_product_foil=mysql_fetch_array($res_product_foil);

		$sql_product_other="select * from product_other where id_product_other='".$rs['id_product_other']."'";
		$res_product_other=mysql_query($sql_product_other) or die ('Error '.$sql_product_other);
		$rs_product_other=mysql_fetch_array($res_product_other);
	?>
	<div class="row">
		<div class="background">
			<input type="hidden" name="hdnCmd" value="">
			<input type="hidden" name="create_by" value="<?php echo $create_by?>">
			<table style="border:0; width:100%; text-align:left;" cellpadding="0" cellspacing="0">
				<tr>
					<td width="8%;"><b>Product :</b></td>
					<td width="25%;"><?php echo $rs_product['product_name']?></td>
					<td width="9%;"><b>Customer :</b></td>
					<td width="25%;"><?php echo $rs_company['company_name']?></td>
				</tr>
				<tr>
					<td><b>Reg no. :</b></td>
					<td><?php if($rs['reg_no']==''){echo '-';}else{echo $rs['reg_no'];}?></td>
					<td><b>Size :</b></td>				
					<td><?php if($rs['product_size']==''){echo '-';}else{echo $rs['product_size'];}?></td>
				</tr>
				<tr>
					<td><b>Barcode no. :</b></td>
					<td><?php if($rs['barcode']==''){echo '-';}else{echo $rs['barcode'];}?></td>
					<td><b>Code :</b></td>
					<td><?php echo $rs['product_code']?></td>
				</tr>
				<tr>					
					<td style="vertical-align:top;"><b>Description :</b></td>
					<td style="vertical-align:top;"><?php echo $rs['description']?></td>
					<td style="vertical-align:top;"><b>Price ex vat/Unit (Baht) :</b></td>
					<td style="vertical-align:top;"><?php echo $rs['price_ex_unit'].' Baht'?></td>
				</tr>
				<tr>					
					<td style="vertical-align:top;"><b>Shelf Life :</b></td>
					<td style="vertical-align:top;"><?php echo $rs['shelf_life'].' year'?></td>
					<td style="vertical-align:top;"></td>
					<td style="vertical-align:top;"></td>
				</tr>
				<tr>					
					<td style="vertical-align:top;"><b>Producing Manufacture :</b></td>
					<td style="vertical-align:top;">
						<p>Plant :
							<?php 
							$sql_factory="select * from type_manufactory where id_manufacturer='".$rs_product_factory['id_manufacturer']."'";
							$res_factory=mysql_query($sql_factory) or die ('Error '.$sql_factory);
							$rs_factory=mysql_fetch_array($res_factory);
							if($rs_factory['title']==''){echo '-';}
							else{echo $rs_factory['title'];}
							?>
						</p>
						<p>Detail : <?php if($rs_product_factory['factory_product_detail']==''){echo '-';}else{echo $rs_product_factory['factory_product_detail'];}?></p>
						<?php if($rs_account['role_user']!=3){?>
						<p>Price : <?php if($rs_product_factory['factory_product_price']==''){echo '-';}else{echo $rs_product_factory['factory_product_price'].' Baht';}?></p>
						<p>Unit : <?php if($rs_product_factory['factory_product_unit']==''){echo '-';}else{echo $rs_product_factory['factory_product_unit'];}?></p>
						<?php }?>
					</td>
					<td style="vertical-align:top;"><b>Packing Manufacture :</b></td>
					<td style="vertical-align:top;">
						<p>Plant : 
							<?php
							$sql_factory="select * from type_manufactory where id_manufacturer='".$rs_product_packing['id_manufacturer']."'";
							$res_factory=mysql_query($sql_factory) or die ('Error '.$sql_factory);
							$rs_factory=mysql_fetch_array($res_factory);
							if($rs_factory['title']==''){echo '-';}
							else{echo $rs_factory['title'];}
							?>
						</p>
						<p>Detail : <?php if($rs_product_packing['factory_packing_detail']==''){echo '-';}else{echo $rs_product_packing['factory_packing_detail'];}?></p>
						<?php if($rs_account['role_user']!=3){?>
						<p>Price : <?php if($rs_product_packing['factory_packing_price']==''){echo '-';}else{echo $rs_product_packing['factory_packing_price'].' Baht';}?></p>
						<p>Unit : <?php if($rs_product_packing['factory_packing_unit']==''){echo '-';}else{echo $rs_product_packing['factory_packing_unit'];}?></p>
						<?php }?>
					</td>
				</tr>
				<?php if($rs_account['role_user']!=3){?>
				<tr>					
					<td style="vertical-align:top;"><b>Raw Mat :</b></td>
					<td style="vertical-align:top;">
						<p>Vender : <?php if($rs_product_rm['rm_vender']==''){echo '-';}else{echo $rs_product_rm['rm_vender'];}?></p>
						<p>Detail : <?php if($rs_product_rm['rm_detail']==''){echo '-';}else{echo $rs_product_rm['rm_detail'];}?></p>
						<p>Price : <?php if($rs_product_rm['rm_price']==''){echo '-';}else{echo $rs_product_rm['rm_price'].' Baht';}?></p>
						<p>Unit : <?php if($rs_product_rm['rm_unit']==''){echo '-';}else{echo $rs_product_rm['rm_unit'];}?></p>
					</td>
					<td style="vertical-align:top;"><b>Box :</b></td>
					<td style="vertical-align:top;">
						<p>Vender : <?php if($rs_product_box['box_vender']==''){echo '-';}else{echo $rs_product_box['box_vender'];}?></p>
						<p>Detail : <?php if($rs_product_box['box_detail']==''){echo '-';}else{echo $rs_product_box['box_detail'];}?></p>
						<p>Price : <?php if($rs_product_box['box_price']==''){echo '-';}else{echo $rs_product_box['box_price'].' Baht';}?></p>
						<p>Min : <?php if($rs_product_box['box_min']==''){echo '-';}else{echo $rs_product_box['box_min'];}?></p>
					</td>
				</tr>
				<tr>					
					<td style="vertical-align:top;"><b>Bottle :</b></td>
					<td style="vertical-align:top;">
						<p>Vender : <?php if($rs_product_bottle['bottle_vender']==''){echo '-';}else{echo $rs_product_bottle['bottle_vender'];}?></p>
						<p>Detail : <?php if($rs_product_bottle['bottle_detail']==''){echo '-';}else{echo $rs_product_bottle['bottle_detail'];}?></p>
						<p>Price : <?php if($rs_product_bottle['bottle_price']==''){echo '-';}else{echo $rs_product_bottle['bottle_price'].' Baht';}?></p>
						<p>Unit : <?php if($rs_product_bottle['bottle_unit']==''){echo '-';}else{echo $rs_product_bottle['bottle_unit'];}?></p>
					</td>
					<td style="vertical-align:top;"><b>Sticker Label :</b></td>
					<td style="vertical-align:top;">
						<p>Vender : <?php if($rs_product_sticker['sticker_vender']==''){echo '-';}else{echo $rs_product_sticker['sticker_vender'];}?></p>
						<p>Detail : <?php if($rs_product_sticker['sticker_detail']==''){echo '-';}else{echo $rs_product_sticker['sticker_detail'];}?></p>
						<p>Price : <?php if($rs_product_sticker['sticker_price']==''){echo '-';}else{echo $rs_product_sticker['sticker_price'].' Baht';}?></p>
						<p>Min : <?php if($rs_product_sticker['sticker_min']==''){echo '-';}else{echo $rs_product_sticker['sticker_min'];}?></p>
					</td>
				</tr>
				<tr>					
					<td style="vertical-align:top;"><b>Alu pouch :</b></td>
					<td style="vertical-align:top;">
						<p>Vender : <?php if($rs_product_alu['alu_vender']==''){echo '-';}else{echo $rs_product_alu['alu_vender'];}?></p>
						<p>Detail : <?php if($rs_product_alu['alu_detail']==''){echo '-';}else{echo $rs_product_alu['alu_detail'];}?></p>
						<p>Price : <?php if($rs_product_alu['alu_price']==''){echo '-';}else{echo $rs_product_alu['alu_price'].' Baht';}?></p>
						<p>Unit : <?php if($rs_product_alu['alu_unit']==''){echo '-';}else{echo $rs_product_alu['alu_unit'];}?></p>
					</td>
					<td style="vertical-align:top;"><b>Foil :</b></td>
					<td style="vertical-align:top;">
						<p>Vender : <?php if($rs_product_foil['foil_vender']==''){echo '-';}else{echo $rs_product_foil['foil_vender'];}?></p>
						<p>Detail : <?php if($rs_product_foil['foil_detail']==''){echo '-';}else{echo $rs_product_foil['foil_detail'];}?></p>
						<p>Price : <?php if($rs_product_foil['foil_price']==''){echo '-';}else{echo $rs_product_foil['foil_price'].' Baht';}?></p>
						<p>Min : <?php if($rs_product_foil['foil_min']==''){echo '-';}else{echo $rs_product_foil['foil_min'];}?></p>
					</td>
				</tr>
				<tr>					
					<td style="vertical-align:top;"><b>Other :</b></td>
					<td style="vertical-align:top;">
						<p>Vender : <?php if($rs_product_other['other_vender']==''){echo '-';}else{echo $rs_product_other['other_vender'];}?></p>
						<p>Detail : <?php if($rs_product_other['other_detail']==''){echo '-';}else{echo $rs_product_other['other_detail'];}?></p>
						<p>Price : <?php if($rs_product_other['other_price']==''){echo '-';}else{echo $rs_product_other['other_price'].' Baht';}?></p>
						<p>Min : <?php if($rs_product_other['other_min']==''){echo '-';}else{echo $rs_product_other['other_min'];}?></p>
						<p>Note : <?php if($rs_product_other['note']==''){echo '-';}else{echo $rs_product_other['note'];}?></p>
					</td>
					<td style="vertical-align:top;"></td>
					<td style="vertical-align:top;"></td>
				</tr>
				<?php }?>
			</table>
		</div>
	</div> 
</body>
</html>