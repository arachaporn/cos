<?php
include("connect/connect.php");
if($_POST['del']=='Delete'){
	for($i=0;$i<count($_POST['ck_del']);$i++){
		if($_POST['ck_del'][$i] != ""){
			$sql ="delete from meeting where id_meeting='".$_POST['ck_del'][$i]."'";
			$res = mysql_query($sql) or die ('Error '.$sql);

			$sql_attendee ="delete from meeting_attendee where id_meeting='".$_POST['ck_del'][$i]."'";
			$res_attendee = mysql_query($sql_attendee) or die ('Error '.$sql_attendee);

			$sql_info ="delete from meeting_info where id_meeting='".$_POST['ck_del'][$i]."'";
			$res_info = mysql_query($sql_info) or die ('Error '.$sql_info);
		}
	}
	?>
	<script type='text/javascript'>
		window.location.href = "meeting.php";
	</script>
	<?
}
mysql_close();
?>