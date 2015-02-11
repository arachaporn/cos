<?
@session_start();
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
</head>
<body>
<?php
if($_POST['mode']=='New'){
	$date=date("Y-m-d");

	$sql_factory="insert into product_factory(id_manufacturer,factory_product_detail";
	$sql_factory .=",factory_product_price,factory_product_unit,create_by,create_date) value";
	$sql_factory .=" ('".$_POST['id_factory']."','".$_POST['factory_product_detail']."'";
	$sql_factory .=",'".$_POST['factory_product_price']."','".$_POST['factory_product_unit']."'";
	$sql_factory .=",'".$rs_account['id_account']."','".$date."')";
	$res_factory=mysql_query($sql_factory) or die ('Error '.$sql_factory);
	$id_factory=mysql_insert_id();

	$sql_factory_pack="insert into product_packing(id_manufacturer,manufacturer";
	$sql_factory_pack .=",factory_packing_detail,factory_packing_price";
	$sql_factory_pack .=",factory_packing_unit,create_by,create_date)";
	$sql_factory_pack .=" value('".$_POST['id_packing']."'";
	$sql_factory_pack .=",'".$_POST['factory_packing']."','".$_POST['factory_packing_detail']."'";
	$sql_factory_pack .=",'".$_POST['factory_packing_price']."'";
	$sql_factory_pack .=",'".$_POST['factory_packing_unit']."','".$rs_account['id_account']."','".$date."')";
	$res_factory_pack=mysql_query($sql_factory_pack) or die ('Error '.$sql_factory_pack);
	$id_factory_pack=mysql_insert_id();

	$sql_rm="insert into product_rm(rm_vender,rm_detail,rm_price,rm_unit";
	$sql_rm .=",create_by,create_date) value ('".$_POST['rm_vender']."'";
	$sql_rm .=" ,'".$_POST['rm_detail']."','".$_POST['rm_price']."'";
	$sql_rm .=",'".$_POST['rm_unit']."','".$rs_account['id_account']."','".$date."')";
	$res_rm=mysql_query($sql_rm) or die ('Error '.$sql_rm);
	$id_rm=mysql_insert_id();

	$sql_box="insert into product_box(box_vender,box_detail,box_price,box_min";
	$sql_box .=",create_by,create_date) value('".$_POST['box_vender']."'";
	$sql_box .=",'".$_POST['box_detail']."','".$_POST['box_price']."'";
	$sql_box .=",'".$_POST['box_min']."','".$rs_account['id_account']."','".$date."')";
	$res_box=mysql_query($sql_box) or die ('Error '.$sql_box);
	$id_box=mysql_insert_id();

	$sql_bottle="insert into product_bottle(bottle_vender,bottle_detail";
	$sql_bottle .=",bottle_price,bottle_min,create_by,create_date) value";
	$sql_bottle .="('".$_POST['bottle_vender']."','".$_POST['bottle_detail']."'";
	$sql_bottle .=",'".$_POST['bottle_price']."','".$_POST['bottle_min']."'";
	$sql_bottle .=",'".$rs_account['id_account']."','".$date."')";
	$res_bottle=mysql_query($sql_bottle) or die ('Error '.$sql_bottle);
	$id_bottle=mysql_insert_id();

	$sql_sticker ="insert into product_sticker(sticker_vender,sticker_detail";
	$sql_sticker .=",sticker_price,sticker_min,create_by,create_date) value";
	$sql_sticker .="('".$_POST['sticker_vender']."','".$_POST['sticker_detail']."'";
	$sql_sticker .=",'".$_POST['sticker_price']."','".$_POST['sticker_min']."'";
	$sql_sticker .=",'".$rs_account['id_account']."','".$date."')";
	$res_sticker=mysql_query($sql_sticker) or die ('Error '.$sql_sticker);
	$id_sticker=mysql_insert_id();

	$sql_alu ="insert into product_alu(alu_vender,alu_detail,alu_price,alu_min";
	$sql_alu .=",create_by,create_date) value('".$_POST['alu_vender']."'";
	$sql_alu .=",'".$_POST['alu_detail']."','".$_POST['alu_price']."'";
	$sql_alu .=",'".$_POST['alu_min']."','".$rs_account['id_account']."','".$date."')";
	$res_alu=mysql_query($sql_alu) or die ('Error '.$sql_alu);
	$id_alu=mysql_insert_id();

	$sql_foil ="insert into product_foil(foil_vender,foil_detail,foil_price,foil_min";
	$sql_foil .=",create_by,create_date) value ('".$_POST['foil_vender']."'";
	$sql_foil .=",'".$_POST['foil_detail']."','".$_POST['foil_price']."'";
	$sql_foil .=",'".$_POST['foil_min']."','".$rs_account['id_account']."','".$date."')";
	$res_foil=mysql_query($sql_foil) or die ('Error '.$sql_foil);
	$id_foil=mysql_insert_id();

	$sql_other ="insert into product_other(other_vender,other_detail";
	$sql_other .=",other_price,other_min,note,create_by,create_date)";
	$sql_other .=" value('".$_POST['other_vender']."','".$_POST['other_detail']."'";
	$sql_other .=",'".$_POST['other_price']."','".$_POST['other_min']."'";
	$sql_other .=",'".$_POST['note']."','".$rs_account['id_account']."','".$date."')";
	$res_other=mysql_query($sql_other) or die ('Error '.$sql_other);
	$id_other=mysql_insert_id();

	// ZAMACHITA meeting
	$post_product_size = mysql_real_escape_string($_POST['product_size']);
	$post_description = mysql_real_escape_string($_POST['description']);

	$sql="insert into product_detail(id_company,id_product,reg_no,product_size";
	$sql .=",barcode,product_code,description,price_ex_unit,shelf_life";
	$sql .=",id_product_factory,id_packing_factory,id_product_rm,id_product_box";
	$sql .=",id_product_bottle,id_product_sticker,id_product_alu,id_product_foil";
	$sql .=",id_product_other,create_by,create_date)";
	$sql .=" value ('".$_POST['id_company']."','".$_POST['id_product']."'";
	$sql .=",'".$_POST['reg_no']."','".$post_product_size."'";
	$sql .=",'".$_POST['barcode']."','".$_POST['product_code']."'";
	$sql .=",'".$post_description."','".$_POST['price_ex_unit']."'";
	$sql .=",'".$_POST['shelf_life']."'";
	$sql .=",'".$id_factory."','".$id_factory_pack."','".$id_rm."','".$id_box."'";
	$sql .=",'".$id_bottle."','".$id_sticker."','".$id_alu."','".$id_foil."'";
	$sql .=",'".$id_other."','".$rs_account['id_account']."','".$date."')";
	$res=mysql_query($sql) or die ('Error '.$sql);
	$id_product=mysql_insert_id();
	
?>
	<script>
		window.location.href='ac-product.php?id_u=<?=$id_product?>';
	</script>
<?php 
}else{	
	$id_product=$_POST['mode'];

	// ZAMACHITA meeting
	$post_product_size = mysql_real_escape_string($_POST['product_size']);
	$post_description = mysql_real_escape_string($_POST['description']);

	$sql="update product_detail set id_company='".$_POST['id_company']."'";
	$sql .=",id_product='".$_POST['id_product']."',reg_no='".$_POST['reg_no']."'";
	$sql .=",product_size='".$post_product_size."',barcode='".$_POST['barcode']."'";
	$sql .=",product_code='".$_POST['product_code']."'";
	$sql .=",shelf_life='".$_POST['shelf_life']."',description='".$post_description."'";
	$sql .=",price_ex_unit='".$_POST['price_ex_unit']."'";
	$sql .=" where id_product_detail='".$id_product."'";
	$res=mysql_query($sql) or die ('Error '.$sql);


	$sql_factory="update product_factory set id_manufacturer='".$_POST['id_factory']."'";
	$sql_factory .=",factory_product_detail='".$_POST['factory_product_detail']."'";
	$sql_factory .=",factory_product_price='".$_POST['factory_product_price']."'";
	$sql_factory .=",factory_product_unit='".$_POST['factory_product_unit']."'";
	$sql_factory .=" where id_product_factory='".$_POST['id_product_factory']."'";
	$res_factory=mysql_query($sql_factory) or die ('Error '.$sql_factory);

	$sql_factory_pack="update product_packing set id_manufacturer='".$_POST['id_packing']."'";
	$sql_factory_pack .=",manufacturer='".$_POST['factory_packing']."'";
	$sql_factory_pack .=",factory_packing_detail='".$_POST['factory_packing_detail']."'";
	$sql_factory_pack .=",factory_packing_price='".$_POST['factory_packing_price']."'";
	$sql_factory_pack .=",factory_packing_unit='".$_POST['factory_packing_unit']."'";
	$sql_factory_pack .=" where id_packing_factory='".$_POST['id_product_packing']."'";
	$res_factory_pack=mysql_query($sql_factory_pack) or die ('Error '.$sql_factory_pack);

	$sql_rm="update product_rm set rm_vender='".$_POST['rm_vender']."'";
	$sql_rm .=",rm_detail='".$_POST['rm_detail']."',rm_price='".$_POST['rm_price']."'";
	$sql_rm .=",rm_unit='".$_POST['rm_unit']."'";
	$sql_rm.=" where id_product_rm='".$_POST['id_product_rm']."'";
	$res_rm=mysql_query($sql_rm) or die ('Error '.$sql_rm);

	$sql_box="update product_box set box_vender='".$_POST['box_vender']."'";
	$sql_box .=",box_detail='".$_POST['box_detail']."',box_price='".$_POST['box_price']."'";
	$sql_box .=",box_min='".$_POST['box_min']."'";
	$sql_box.=" where id_product_box='".$_POST['id_product_box']."'";
	$res_box=mysql_query($sql_box) or die ('Error '.$sql_box);

	$sql_bottle="update product_bottle set bottle_vender='".$_POST['bottle_vender']."'";
	$sql_bottle .=",bottle_detail='".$_POST['bottle_detail']."'";
	$sql_bottle .=",bottle_price='".$_POST['bottle_price']."'";
	$sql_bottle .=",bottle_min='".$_POST['bottle_min']."'";
	$sql_bottle .=" where id_product_bottle='".$_POST['id_product_bottle']."'";
	$res_bottle=mysql_query($sql_bottle) or die ('Error '.$sql_bottle);

	$sql_sticker ="update product_sticker set sticker_vender='".$_POST['sticker_vender']."'";
	$sql_sticker .=",sticker_detail='".$_POST['sticker_detail']."'";
	$sql_sticker .=",sticker_price='".$_POST['sticker_price']."'";
	$sql_sticker .=",sticker_min='".$_POST['sticker_min']."'";
	$sql_sticker .=" where id_product_sticker='".$_POST['id_product_sticker']."'";
	$res_sticker=mysql_query($sql_sticker) or die ('Error '.$sql_sticker);

	$sql_alu ="update product_alu set alu_vender='".$_POST['alu_vender']."'";
	$sql_alu .=",alu_detail='".$_POST['alu_detail']."'";
	$sql_alu .=",alu_price='".$_POST['alu_price']."'";
	$sql_alu .=",alu_min='".$_POST['alu_min']."'";
	$sql_alu .=" where id_product_alu='".$_POST['id_product_alu']."'";
	$res_alu=mysql_query($sql_alu) or die ('Error '.$sql_alu);

	$sql_foil ="update product_foil set foil_vender='".$_POST['foil_vender']."'";
	$sql_foil .=",foil_detail='".$_POST['foil_detail']."'";
	$sql_foil .=",foil_price='".$_POST['foil_price']."'";
	$sql_foil .=",foil_min='".$_POST['foil_min']."'";
	$sql_foil .=" where id_product_foil='".$_POST['id_product_foil']."'";
	$res_foil=mysql_query($sql_foil) or die ('Error '.$sql_foil);

	$sql_other ="update product_other set other_vender='".$_POST['other_vender']."'";
	$sql_other .=",other_detail='".$_POST['other_detail']."'";
	$sql_other .=",other_price='".$_POST['other_price']."'";
	$sql_other .=",other_min='".$_POST['other_min']."',note='".$_POST['note']."'";
	$sql_other .=" where id_product_other='".$_POST['id_product_other']."'";
	$res_other=mysql_query($sql_other) or die ('Error '.$sql_other);
?>
	<script>
		window.location.href='ac-product.php?id_u=<?=$id_product?>';
	</script>
<?php }?>
</body>
</html>