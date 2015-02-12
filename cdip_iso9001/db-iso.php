<html>
<head>
</head>
<body>
<?php
include("connect/connect.php");
$dep=$_POST['dep'];
$type=$_POST['type_iso'];
if($dep==6){$dep2= 'ADM';}
elseif($dep==1){$dep2= 'NBD';}
elseif($dep==2){$dep2= 'Inno';}
elseif($dep==5){$dep2= 'SCM';}
elseif($dep==9){$dep2= 'Lab';}

$file="file_iso9001/".$dep2."/".$type."/".$_FILES["filUpload"]["name"];
if(move_uploaded_file($_FILES["filUpload"]["tmp_name"],$file))
{
	$sql = "insert into iso_file(id_department,type_iso,iso_file) ";
	$sql .=" values ('".$dep."','".$type."','".$_FILES["filUpload"]["name"]."')";
	$res = mysql_query($sql) or die ('Error '.$sql);		
}
?>
<script>
	window.location.href='iso_file.php?dep=<?=$dep?>&type=<?=$type?>';
</script>

</body>
</html>