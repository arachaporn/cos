<?php
include("connect/connect.php");
if($_POST['del']=='Delete'){
	for($i=0;$i<count($_POST['ck_del']);$i++){
		if($_POST['ck_del'][$i] != ""){
			$sql ="delete from sd_withdraw_account where id_withdraw_account='".$_POST['ck_del'][$i]."'";
			$res = mysql_query($sql) or die ('Error '.$sql);
		}
	}
	?>
	<script type='text/javascript'>
		window.location.href = "withdraw-device.php";
	</script>
	<?
}
mysql_close();
?>