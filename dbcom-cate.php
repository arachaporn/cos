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

$date=date("Y-m-d");
$modify=date("Y-m-d H:i:s");
if($_POST['mode']=='New'){
	$sql="insert into company_category(title,create_by,create_date)";
	$sql .=" values('".$_POST['title']."'";
	$sql .=",'".$rs_account['id_account']."','".$date."')";
	$res=mysql_query($sql) or die ('Error '.$sql);
?>
	<script>
		window.location.href='company-cate.php';
	</script>
<?php 
}else{
	$sql="update company_category set title='".$_POST['title']."'";
	$sql .=",create_by='".$rs_account['id_account']."',modify_date='".$modify."'";
	$sql .=" where id_com_cat='".$_POST['mode']."'";
	$res=mysql_query($sql) or die ('Error '.$sql);
?>
	<script>
		window.location.href='company-cate.php';
	</script>
<?php 
}
