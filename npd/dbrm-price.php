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

	$sql="insert into npd_rm_price(npd_rm_code,type_rm,npd_rm_name";
	$sql .=",npd_supplier,npd_rm_price,create_date)";
	$sql .=" values('".$_POST['rm_code']."','".$_POST['type_rm']."'";
	$sql .=",'".$_POST['name']."','".$_POST['supplier']."'";
	$sql .=",'".$_POST['price_unit']."','".$date."')";
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
	$id_rm=$_POST['mode'];
	$sql="update npd_rm_price set npd_rm_code='".$_POST['rm_code']."'";
	$sql .=",type_rm='".$_POST['type_rm']."',npd_rm_name='".$_POST['name']."'";
	$sql .=",npd_supplier='".$_POST['supplier']."'";
	$sql .=",npd_rm_price='".$_POST['price_unit']."'";
	$sql .=" where id_npd_rm='".$_POST['mode']."'";
	$res=mysql_query($sql) or die ('Error '.$sql);

?>
	<script>
		window.location.href='rm-price.php?id_u=<?=$id_rm?>';
	</script>
<?php 
}
?>
</body>
</html>