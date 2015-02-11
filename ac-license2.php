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
include("connect/connect.php");
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
<script language="javascript">
function fncSubmit(){
	document.frm.submit();
}
</script>
</head>
<body>
	<?php include("menu.php");?>
	<div class="row">
		<div class="background">		
		<form name="frm" method="post" action="dbaccount.php">
		<table style="border: 0; width: 100%;" cellpadding="0" cellspacing="0">
			<tr>
				<td class="b-bottom"><div class="large-4 columns"><h4>Account License</h4></div></td>
			</tr>
			<tr>
				<td class="b-bottom">
					<div class="large-4 columns">
						<input type="button" name="change_password" id="change_password" value="Change password" class="button-create" OnClick="JavaScript:return fncSubmit();">
						<input type="button" value="Close" class="button-create" onclick="window.location.href='ac-account.php'">
					</div>
				</td>
			</tr>
			<tr>
				<td style="background: #fff;">
					<div class="large-4 columns">		
						<input type="hidden" name="mode_change" value="<?php echo $rs_account['id_account']?>">
						<table style="border: none; width: 100%;" cellpadding="0" cellspacing="0" id="tb-add">
							<tr>
								<td>
									<?php
									$sql_account2 = "SELECT * FROM signature WHERE password_sign = '".$_SESSION["sign_password"]."'  ";
									$res_account2 = mysql_query($sql_account2) or die ('Error '.$sql_account2);
									$rs_account2 = mysql_fetch_array($res_account2);
									?>
									<img src="img/signature/<?php echo $rs_account2['image']?>">
								</td>
							</tr>
							<tr>
								<td style="padding:2% 2% 1% 0;font-size:1.2em;font-weight:bold;">Change password signature</td>
							</tr>
							<tr>
								<td><input type="password" name="password_sign" value="<?php echo $rs_account2['password_sign']?>" style="width:20%;"></td>
							</tr>
							<tr>
								<td><input type="password" name="re_password_sign" value="<?php echo $rs_account2['password_sign']?>" style="width:20%;"></td>
							</tr>
						</table>		
				</td>
			</tr>
		</table>
		</form>
		</div>
	</div>

  <script>
  document.write('<script src=' +
  ('__proto__' in {} ? 'js/vendor/zepto' : 'js/vendor/jquery') +
  '.js><\/script>')
  </script>
  
  <script src="js/foundation.min.js"></script>
  <!--
  
  <script src="js/foundation/foundation.js"></script>
  
  <script src="js/foundation/foundation.alerts.js"></script>
  
  <script src="js/foundation/foundation.clearing.js"></script>
  
  <script src="js/foundation/foundation.cookie.js"></script>
  
  <script src="js/foundation/foundation.dropdown.js"></script>
  
  <script src="js/foundation/foundation.forms.js"></script>
  
  <script src="js/foundation/foundation.joyride.js"></script>
  
  <script src="js/foundation/foundation.magellan.js"></script>
  
  <script src="js/foundation/foundation.orbit.js"></script>
  
  <script src="js/foundation/foundation.reveal.js"></script>
  
  <script src="js/foundation/foundation.section.js"></script>
  
  <script src="js/foundation/foundation.tooltips.js"></script>
  
  <script src="js/foundation/foundation.topbar.js"></script>
  
  <script src="js/foundation/foundation.interchange.js"></script>
  
  <script src="js/foundation/foundation.placeholder.js"></script>
  
  <script src="js/foundation/foundation.abide.js"></script>
  
  -->
  
  <script>
    $(document).foundation();
  </script>
</body>
</html>
