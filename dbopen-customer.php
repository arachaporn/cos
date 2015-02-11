<?php
include("connect/connect.php");

$date=date("Y-m-d");
$modify=date("Y-m-d H:i:s");
if($_POST['mode']=='New'){
	$sql="insert into company(company_name,id_type_company,branch_name)";
	$sql .=" values('".$_POST['company_name']."','".$_POST['company_type']."'";
	$sql .=",'".$_POST['branch_name']."')";
	$res=mysql_query($sql) or die ('Error '.$sql);
	$id=mysql_insert_id();
	?>
	<script>
		window.location.href='ac-open-customer.php?id_u=<?=$id?>';
	</script>
<?php 
}else{
	$id=$_POST['mode'];
	$sql="update company set company_name='".$_POST['company_name']."'";
	$sql .=",id_type_company='".$_POST['company_type']."'";
	$sql .=",branch_name='".$_POST['branch_name']."'";
	$sql .=" where id_company='".$_POST['mode']."'";
	$res=mysql_query($sql) or die ('Error '.$sql);
	
	
?>
	<script>
		window.location.href='ac-open-customer.php?id_u=<?=$id?>';
	</script>
<?php }?>