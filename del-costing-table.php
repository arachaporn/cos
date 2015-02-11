<?php
include("connect/connect.php");
if($_POST['del']=='Delete'){
	for($i=0;$i<count($_POST['ck_del']);$i++){
		if($_POST['ck_del'][$i] != ""){
			$fac=$_POST['fac'];
			$sql ="delete from costing_factory where id_costing_factory='".$_POST['ck_del'][$i]."'";
			$res = mysql_query($sql) or die ('Error '.$sql);

			$sql_attendee ="delete from costing_rm where id_roc='".$_POST['ck_del'][$i]."'";
			$res_attendee = mysql_query($sql_attendee) or die ('Error '.$sql_attendee);

		}
	}
	?>
	<script type='text/javascript'>
		window.location.href = "costing-factory.php?fac=<?=$fac?>";
	</script>
	<?
}
mysql_close();
?>