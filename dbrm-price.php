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
include("connect/connect.php");

$date=date("Y-m-d");
$modify=date("Y-m-d H:i:s");
if($_POST['mode']=='New'){

	/*$sql_supplier="select * from supplier where title = '".$_POST['supplier']."'";
	$res_supplier=mysql_query($sql_supplier) or die ('Error '.$sql_supplier);
	$rs_supplier=mysql_fetch_array($res_supplier);
	if(!$rs_supplier){
		$sql2="insert into supplier(title) values('".$_POST['supplier']."')";	
		$res2=mysql_query($sql2) or die ('Error '.$sql2);
		$id_supplier=mysql_insert_id();
	}
	else{ $id_supplier=$rs_supplier['id_supplier'];}*/

	$sql="insert into rm_price(rm_code,rm_name,rm_from,unit";
	$sql .=",price_unit,standard_price,remark,create_date)";
	$sql .=" values('".$_POST['rm_code']."','".$_POST['name']."'";
	$sql .=",'".$_POST['rm_from']."','".$_POST['unit']."'";
	$sql .=",'".$_POST['price_unit']."','".$_POST['standard_price']."'";
	$sql .=",'".$_POST['remark']."','".$date."')";
	$res=mysql_query($sql) or die ('Error '.$sql);
	$id_rm=mysql_insert_id();
?>
	<script>
		window.location.href='rm-price.php?id_u=<?=$id_rm?>';
	</script>
<?php 
}else{
	/*$sql_supplier="select * from supplier where title like '".$_POST['supplier']."'";
	$res_supplier=mysql_query($sql_supplier) or die ('Error '.$sql_supplier);
	$rs_supplier=mysql_fetch_array($res_supplier);
	if($rs_supplier){
		 $id_supplier=$rs_supplier['id_supplier'];
	}*/

	$sql="update rm_price set rm_name='".$_POST['name']."',rm_from='".$_POST['rm_from']."'";
	$sql .=",unit='".$_POST['unit']."',price_unit='".$_POST['price_unit']."'";
	$sql .=",standard_price='".$_POST['standard_price']."',remark='".$_POST['remark']."'";
	$sql .=",modify_date='".$modify."' where id_rm_price='".$_POST['mode']."'";
	$res=mysql_query($sql) or die ('Error '.$sql);

?>
	<script>
		window.location.href='rm-price.php';
	</script>
<?php 
}
?>
</body>
</html>