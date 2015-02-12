<?php
include("connect/connect.php");
if($_POST['del']=='Delete'){
	for($i=0;$i<count($_POST['ck_del']);$i++){
		if($_POST['ck_del'][$i] != ""){
			$sql ="delete from sd_device where id_sd_device='".$_POST['ck_del'][$i]."'";
			$res = mysql_query($sql) or die ('Error '.$sql);
			
			$sql_program ="delete from sd_program where id_sd_device='".$_POST['ck_del'][$i]."'";
			$res_program = mysql_query($sql_program) or die ('Error '.$sql_program);
		}
	}
	?>
	<script type='text/javascript'>
		window.location.href = "check-computer.php";
	</script>
	<?
}
mysql_close();
?>