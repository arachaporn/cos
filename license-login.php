<?php
session_start();
session_start();
if($_SESSION["Username"] == ""){
	header("location:index.php");
	exit();
}
include("connect/connect.php");
$sql_account = "SELECT * FROM account WHERE username = '".$_SESSION["Username"]."'  ";
$res_account = mysql_query($sql_account) or die ('Error '.$sql_account);
$rs_account = mysql_fetch_array($res_account);

if(trim($_POST["tPassword"])){$strPassword = trim($_POST["tPassword"]);}
else{$strPassword = trim($_POST["txtPassword"]);}
		
//*** Check Password ***//
if(trim($strPassword) == ""){
	echo "<font color=red>**</font> Plase input [Password]";
	exit();
}
	
include("connect/connect.php");

//*** Check Username & Password ***//

$strSQL = "SELECT * FROM signature WHERE password_sign = '".$strPassword."' ";
$strSQL .=" and id_account='".$rs_account['id_account']."'";
$objQuery = mysql_query($strSQL) or die ("Error Query [".$strSQL."]");
$objResult = mysql_fetch_array($objQuery);
if($objResult)
{
	if(trim($_POST["tPassword"])){echo 'Y';}else{
?>

		<script>
			window.location = 'ac-license2.php';
		</script>
<?php }
		//*** Session ***//
		$_SESSION["sign_password"] = $strPassword;
		session_write_close();
	}
	else
	{?>
		<script>
			window.alert('Password is wrong!!');
			history.back();
		</script>
<?	}

?>