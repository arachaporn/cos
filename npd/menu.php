<?php
@session_start();
if($_SESSION["Username"] == ""){
	header("location:index.php");
	exit();
}
include("connect/connect.php");
$sql_account = "select * from account where username = '".$_SESSION["Username"]."'  ";
$res_account = mysql_query($sql_account) or die ('Error '.$sql_account);
$rs_account = mysql_fetch_array($res_account);
?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="menu/style.css">
</head>
<body>
<div class="container">	
	<marquee style="position: absolute;padding:1.5%;width:100%;"><img src="img/cito/customer.png">&nbsp;&nbsp;&nbsp;<img src="img/cito/inno.png">&nbsp;&nbsp;&nbsp;<img src="img/cito/team.png">&nbsp;&nbsp;&nbsp;<img src="img/cito/ontime.png"></marquee>
	<a class="toggleMenu" href="#">Menu</a>
	<ul class="nav">
		<a href="../index2.php"><img src="img/logo.png" width="6%" style="margin: 0.3% 2.5% 0.3% 1.5%;"></a>
	<!--<?php
	$account_status=$rs_account['role_user']; 
	$sql="select * from menu";
	$res=mysql_query($sql) or die ('Error '.$sql);
	while($rs=mysql_fetch_array($res)){
		$menu_array=split(",",$rs['menu_status']);
		if($menu_array[0]== $account_status){
	?>
		<li><a href='<?php echo $rs['m_link']?>'><?php echo $rs['title']?></a></li>
	<?php }else
		if($menu_array[1]== $account_status){
	?>
		<li><a href='<?php echo $rs['m_link']?>'><?php echo $rs['title']?></a></li>
	<?php }else
		if($menu_array[2]== $account_status){
	?>
		<li><a href='<?php echo $rs['m_link']?>'><?php echo $rs['title']?></a></li>
	<?php }
	}?>-->
		<li style="float:right;padding:1% 2% 0 0;"><a href='#'>Account</a>
			<ul>
				<li><a href='../ac-account.php'>Setting</a></li>
				<li><a href=javascript:if(confirm('ยืนยันการออกจากระบบ')==true){window.location='../logout.php';}>Logout</a></li>
			</ul>
		</li>
		<li style="float:right;padding-top:1%;"><a href='../index2.php'><img src="img/home.png"></a></li>
	</ul>	
</div>

<script type="text/javascript" src="http://code.jquery.com/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="menu/script.js"></script>
</body>
</html>
	

	
