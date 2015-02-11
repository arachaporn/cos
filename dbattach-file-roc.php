<html>
<body>
<?php
	if(move_uploaded_file($_FILES["filUpload"]["tmp_name"],"file/roc_file/".$_FILES["filUpload"]["name"]))
	{
		
		$id=substr($_POST['id_roc'], -2);
		include("connect/connect.php");
		$sql = "insert into roc_file(id_roc,roc_file) ";
		$sql .=" values ('".$id."','".$_FILES["filUpload"]["name"]."')";
		$res = mysql_query($sql) or die ('Error '.$sql);		
	}

	if($_GET["action"] == "del"){
		$sql = "delete from roc_file ";
		$sql .="where id_roc_file = '".$_GET["id_p"]."'";
		$res = mysql_query($sql) or die ('Error '.$sql);
	}
?>
<script>
	window.location.href='attach-file-roc.php?id_u=<?=$id?>';
</script>
</body>
</html>