<?php
@session_start();
if($_SESSION["Username"] == ""){
	header("location:index.php");
	exit();
}
include("connect/connect.php");
$sql_account = "SELECT * FROM account WHERE username = '".$_SESSION["Username"]."'  ";
$res_account = mysql_query($sql_account) or die ('Error '.$sql_account);
$rs_account = mysql_fetch_array($res_account);

$date=date("Y-m-d");
$modify=date("Y-m-d H:i:s");
if($_REQUEST['mode']=='new'){
	$sql="insert into quotation(quotation_no,quotation_month,quotation_num";
	$sql .=",id_company,company_name,contact_name,address";
	$sql .=",sum_price,discount,total_discount,vat,total_price,,create_by,";
	$sql .=",create_datestatus)";
	$sql .=" values('".$_REQUEST['quotation_no']."','".$_REQUEST['quotation_month']."'";
	$sql .=",'".$_POST['quotation_num']."','".$_POST['total']."'";
	$sql .=",'".$_POST['discount']."','".$_POST['total_discount']."'";
	$sql .=",'".$_POST['vat']."','".$_POST['total_price']."'";
	$sql .=",'".$rs_account['id_account']."','".$date."','1')";
	
	$id=mysql_insert_id();

	?>
	<script>
		window.location.href='ac-quotation.php?id_u=<?=$id?>';
	</script>
<?php 
}else{
	$id=$_POST['mode'];
	$sql_product="select * from product where product_name like '".$_POST['product_name']."'";
	$res_product=mysql_query($sql_product) or die ('Error '.$sql_product);
	$rs_product=mysql_fetch_array($res_product);
	if(!$rs_product){
		$sql_ins_product="insert into product(product_name) values";
		$sql_ins_product .=" ('".$_POST['product_name']."')";
		$res_ins_product=mysql_query($sql_ins_product) or die ('Error '.$sql_ins_product);
		$id_product=mysql_insert_id();
	}else{
		$id_product=$rs_product['id_product'];
	}

	$sql="update quotation set id_company='".$_POST['id_company']."'";
	$sql .=",company_name='".$_POST['company_name']."'";
	$sql .=",contact_name='".$_POST['contact_name']."'";
	$sql .=",address='".$_POST['company_address']."'";
	$sql .=",email='".$_POST['company_email']."'";
	$sql .=",id_product='".$id_product."'";
	echo$sql .=" where id_quotation='".$_POST['mode']."'";
	$res=mysql_query($sql) or die ('Error '.$sql);

	
?>
	<script>
		//window.location.href='ac-quotation.php?id_u=<?=$id?>';
	</script>
<?php 
}
