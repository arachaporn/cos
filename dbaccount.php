<?php
include("connect/connect.php");

$date=date("Y-m-d");
$modify=date("Y-m-d H:i:s");
if($_POST['mode']=='New'){
	$sql="insert into account(username,name,password,id_department,id_position,id_employee";
	$sql .=",email,tel,role_user,information,create_date,create_by)";
	$sql .=" values('".$_POST['username']."','".$_POST['name']."','".$_POST['password']."'";
	$sql .=",'".$_POST['department']."','".$_POST['position']."','".$_POST['id_employee']."'";
	$sql .=",'".$_POST['email']."','".$_POST['tel']."'";
	$sql .=",'".$_POST['role_user']."','".$_POST['information']."','".$date."','admin')";
	$res=mysql_query($sql) or die ('Error '.$sql);
	?>
	<script>
		window.location.href='account.php';
	</script>
<?php 
}elseif(is_numeric($_POST['mode'])){
	if($_POST['edit_account']){
		$id=$_POST['edit_account'];
		$sql="update account set username='".$_POST['username']."',name='".$_POST['name']."'";
		$sql .=",password='".$_POST['password']."',email='".$_POST['email']."'";
		$sql .=" where id_account='".$id."'";
		$res=mysql_query($sql) or die ('Error '.$sql);
	}
	else{
		$id=$_POST['mode'];
		$sql="update account set username='".$_POST['username']."',name='".$_POST['name']."'";
		$sql .=",password='".$_POST['password']."',id_department='".$_POST['department']."'";
		$sql .=",id_position='".$_POST['position']."',id_employee='".$_POST['id_employee']."'";
		$sql .=",email='".$_POST['email']."',tel='".$_POST['tel']."',information='".$_POST['information']."'";
		$sql .=",role_user='".$_POST['role_user']."',modify_date='".$modify."',create_by='admin'";
		$sql .=" where id_account='".$id."'";
		$res=mysql_query($sql) or die ('Error '.$sql);
	}	
	if(!$_POST['edit_account']){
?>
	<script>
		window.location.href='account.php';
	</script>
<?php 
	exit();
	}else{
?>	
	<script>
		window.location.href='index2.php';
	</script>
<?php
	}
}elseif($_POST['mode_change']){
	$sql="update signature set password_sign='".$_POST['password_sign']."'";
	$sql .=" where id_account='".$_POST['mode_change']."'";
	$res=mysql_query($sql) or die ('Error '.$sql);
?>
	<script>
		window.location.href='account-license.php';
	</script>
<?php }?>