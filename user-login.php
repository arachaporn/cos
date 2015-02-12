<?php
session_start();

//$strUsername = trim($_POST["tUsername"]);
//$strPassword = trim($_POST["tPassword"]);
$strUsername = $_POST["txtUsername"];	
$strPassword = $_POST["txtPassword"];
//*** Check Username ***//
if(trim($strUsername) == ""){
	//echo "<font color=red>**</font> Please input [Username]";
?>
	<script>
		window.alert('Please input [Username]');
		history.back();
	</script>
<?php	exit();
}
	
//*** Check Password ***//
if(trim($strPassword) == ""){
	//echo "<font color=red>**</font> Please input [Password]";
?>
	<script>
		window.alert('Please input [Password]');
		history.back();
	</script>
<?php exit();
}
	
include("connect/connect.php");

//*** Check Username & Password ***//

$strSQL = sprintf("SELECT * FROM account WHERE username = '%s' and password = '%s' ", 
	mysql_real_escape_string($strUsername),
	mysql_real_escape_string($strPassword)
);
$objQuery = mysql_query($strSQL) or die ("Error Query [".$strSQL."]");
$objResult = mysql_fetch_array($objQuery);
if($objResult)
	{?>
		<script>
		window.location = 'index2.php';
		</script>

	<?php	//*** Session ***//
		$_SESSION["Username"] = $strUsername;
		session_write_close();
	}
	else
	{
		//echo "<font color=red>**</font> Username & Password is wrong";
	?>	<script>
			window.alert('Username & Password is wrong!!');
			history.back();
		</script>
	<?php }

?>