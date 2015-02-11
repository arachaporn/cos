<html>
<body>
<?php
	if(move_uploaded_file($_FILES["filUpload"]["tmp_name"],"file/open_customer/".$_FILES["filUpload"]["name"]))
	{
		
		$id=substr($_POST['id_comp'], -2);
		include("connect/connect.php");
		$sql = "insert into company_file(id_company,company_file) ";
		$sql .=" values ('".$id."','".$_FILES["filUpload"]["name"]."')";
		$res = mysql_query($sql) or die ('Error '.$sql);		
	}

	if($_GET["action"] == "del"){
		$sql = "delete from company_file ";
		echo$sql .="where id_company_file = '".$_GET["id_p"]."'";
		$res = mysql_query($sql) or die ('Error '.$sql);
	}
?>
<script>
	window.location.href='attach-file.php?id_com=<?=$id?>';
</script>
</body>
</html>