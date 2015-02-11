<?php
include("connect/connect.php");
if($_POST['del']=='Delete'){
	for($i=0;$i<count($_POST['ck_del']);$i++){
		if($_POST['ck_del'][$i] != ""){
			$sql ="delete from roc where id_roc='".$_POST['ck_del'][$i]."'";
			$res = mysql_query($sql) or die ('Error '.$sql);

			$sql_rm="delete from roc_rm where id_roc='".$_POST['ck_del'][$i]."'";
			$res_rm=mysql_query($sql_rm) or die ('Error '.$sql_rm);

			$sql_rela_color="delete from roc_relation_color where id_roc='".$_POST['ck_del'][$i]."'";
			$res_rela_color=mysql_query($sql_rela_color) or die ('Error '.$sql_rela_color);

			$sql_rela_pack="delete from roc_relation_pack where id_roc='".$_POST['ck_del'][$i]."'";
			$res_rela_pack=mysql_query($sql_rela_pack) or die ('Error '.$sql_rela_pack);

			$sql_rela_value="delete from roc_relation_value where id_roc='".$_POST['ck_del'][$i]."'";
			$res_rela_value=mysql_query($sql_rela_value) or die ('Error '.$sql_rela_value);


		}
	}
	?>
	<script type='text/javascript'>
		window.location.href = "roc.php";
	</script>
	<?
}
mysql_close();
?>