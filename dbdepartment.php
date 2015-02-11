<?php
include("connect/connect.php");

$date=date("Y-m-d");
$modify=date("Y-m-d H:i:s");
if($_POST['mode']=='new'){
	$sql="insert into department(title,create_date,create_by)";
	$sql .=" values('".$_POST['title']."','".$date."','admin')";
	$res=mysql_query($sql) or die ('Error '.$sql);
	
	$id=mysql_insert_id();

	$sql2="update positions set id_department='".$id."' where id_department='0'";
	$res2=mysql_query($sql2) or die ('Error '.$sql2);
	?>
	<script>
		window.location.href='department.php';
	</script>
<?php 
}else{
	$sql="update department set title='".$_POST['title']."'";
	$sql .=" where id_department='".$_POST['mode']."'";
	$res=mysql_query($sql) or die ('Error '.$sql);

	$sql2="update positions set id_department='".$_POST['mode']."' where id_department='0'";
	$res2=mysql_query($sql2) or die ('Error '.$sql2);
?>
	<script>
		window.location.href='department.php';
	</script>
<?php 
}
