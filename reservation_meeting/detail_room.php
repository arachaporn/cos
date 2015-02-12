<?php
ob_start();
session_start();
if($_SESSION["Username"] == ""){
	header("location:index.php");
	exit();
}
$_SESSION['start'] = time(); // taking now logged in time
if(!isset($_SESSION['expire'])){
	$_SESSION['expire'] = $_SESSION['start'] + 3600 ; // ending a session in 30 seconds
}
$now = time(); // checking the time now when home page starts

if($now > $_SESSION['expire']){
	session_destroy();
	//echo "Your session has expire !  <a href='logout.php'>Click Here to Login</a>";
}else{
	//echo "This should be expired in 1 min <a href='logout.php'>Click Here to Login</a>";
}
include("../connect/connect.php");
$sql_account = "SELECT * FROM account WHERE username = '".$_SESSION["Username"]."'  ";
$res_account = mysql_query($sql_account) or die ('Error '.$sql_account);
$rs_account = mysql_fetch_array($res_account);
?>
<!DOCTYPE html>
<!--[if IE 8]> 				 <html class="no-js lt-ie9" lang="en" > <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en" > <!--<![endif]-->

<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"/>
<title>COS Project</title>
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/foundation.css">
<link rel="stylesheet" href="rmm-css/responsivemobilemenu.css" type="text/css"/>
<script type="text/javascript" src="http://code.jquery.com/jquery.min.js"></script>
<script type="text/javascript" src="rmm-js/responsivemobilemenu.js"></script>
<script src="js/vendor/custom.modernizr.js"></script>
</head>
<body>
	<div class="row">
		<div class="background">
			<form name="frm" method="post" action="<?=$_SERVER["PHP_SELF"]."?id_p=".$id?>">
				<input type="hidden" name="hdnCmd" value="">
				<input type="hidden" name="create_by" value="<?php echo $create_by?>">
				<table style="border: 0; width: 100%;" cellpadding="0" cellspacing="0">
					<tr>
						<td class="b-bottom" colspan="2"><div class="large-4 columns"><h4>รายละเอียดห้องปะชุม</h4></div></td>
					</tr>
					<tr>
						<td>ชื่อห้องปะชุม</td>
						<td>ที่นั่ง</td>
					</tr>
					<?php 
					$sql_meeting_room_type="select * from meeting_room_type";
					$res_meeting_room_type=mysql_query($sql_meeting_room_type) or die ('Error meeting room type : '.$sql_meeting_room_type);
					while($rs_meeting_room_type=mysql_fetch_array($res_meeting_room_type)){
					?>
					<tr>	
						<td><?php echo $rs_meeting_room_type['meetimg_room_name']?></td>
						<td><?php echo $rs_meeting_room_type['seat']?></td>
					</tr>
					<?php }?>
				</table>
			</form>
		</div>
	</div> 
</body>
</html>