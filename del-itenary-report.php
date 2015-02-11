<?php
include("connect/connect.php");
if($_POST['del']=='Delete'){
	for($i=0;$i<count($_POST['ck_del']);$i++){
		if($_POST['ck_del'][$i] != ""){
			$sql ="delete from itenary_report where itenary_week='".$_POST['ck_del'][$i]."'";
			$res = mysql_query($sql) or die ('Error '.$sql);
		}
	}
	?>
	<script type='text/javascript'>
		window.location.href = "itenary-report.php";
	</script>
	<?
}
mysql_close();
?>