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
include("mpdf/mpdf.php");
ob_start();
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
$pages=$_POST['pages'];
$date=date("Y-m-d");

$sql="update roc set npd_print_roc='1'";
$sql .=",npd_date='".$date."'";
$sql .=" where id_roc='".$_GET['id_u']."'";
$res=mysql_query($sql) or die ('Error '.$sql);

?>
<script type='text/javascript'>
	window.location.href = "npd-roc.php";
</script>
</body>
</html>