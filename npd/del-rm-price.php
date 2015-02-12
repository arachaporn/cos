<?php
include("connect/connect.php");
if($_POST['del']=='Delete'){
	for($i=0;$i<count($_POST['ck_del']);$i++){
		if($_POST['ck_del'][$i] != ""){
			
			$sql_del ="delete from rm_price where id_rm_price='".$_POST['ck_del'][$i]."'";
			$res_del = mysql_query($sql_del) or die ('Error '.$sql_del);
		}
	}
	?>
	<script type='text/javascript'>
		window.location.href = "rm-price.php";
	</script>
	<?
}
mysql_close();
?>