<?php
include("connect/connect.php");
if($_POST['del']=='Delete'){
	for($i=0;$i<count($_POST['ck_del']);$i++){
		if($_POST['ck_del'][$i] != ""){
			$sql ="delete from call_report where id_call_report='".$_POST['ck_del'][$i]."'";
			$res = mysql_query($sql) or die ('Error '.$sql);

			$sql_call_report_date="delete from call_report_date where id_call_report='".$_POST['ck_del'][$i]."'";
			$res_call_report_date=mysql_query($sql_call_report_date) or die ('Error '.$sql_call_report_date);

			$sql_relationship="delete from call_report_relationship where id_call_report='".$_POST['ck_del'][$i]."'";
			$sql_relationship=mysql_query($sql_relationship) or die ('Error '.$sql_relationship);
		}
		$create_by=$_POST['create_by'];
	}
	?>
	<script type='text/javascript'>
		window.location.href = "call-report-list.php?create_by=<?=$create_by?>";
	</script>
	<?
}
mysql_close();
?>