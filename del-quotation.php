<?php
include("connect/connect.php");
if($_POST['del']=='Delete'){
	for($i=0;$i<count($_POST['ck_del']);$i++){
		if($_POST['ck_del'][$i] != ""){
			$sql ="delete from quotation where id_quotation='".$_POST['ck_del'][$i]."'";
			$res = mysql_query($sql) or die ('Error '.$sql);

			$sql_relation="delete from quotation_relationship where id_quotation='".$_POST['ck_del'][$i]."'";
			$res_relation=mysql_query($sql_relation) or die ('Error '.$sql_relation);
		}
	}
	?>
	<script type='text/javascript'>
		window.location.href = "quotation.php";
	</script>
	<?
}
mysql_close();
?>