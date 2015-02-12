<?php
include("../connect/connect.php");
$month=$_GET['month'];
$year=$_GET['year'];

$sql ="delete from meeting_room_list where id_room_list='".$_GET['mode']."'";
$res = mysql_query($sql) or die ('Error '.$sql);

$sql_attendee ="delete from meeting_room_attendee where id_room_list='".$_GET['mode']."'";
$res_attendee = mysql_query($sql_attendee) or die ('Error '.$sql_attendee);

?>
<script type='text/javascript'>
	window.location.href = "index.php?year=<?=$year?>&month=<?=$month?>";
</script>
