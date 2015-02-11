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
<link rel="stylesheet" href="css/foundation.css">
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="rmm-css/responsivemobilemenu.css" type="text/css"/>
<script type="text/javascript" src="http://code.jquery.com/jquery.min.js"></script>
<script type="text/javascript" src="rmm-js/responsivemobilemenu.js"></script>
<script src="js/vendor/custom.modernizr.js"></script>
<script language="JavaScript">
	var HttPRequest = false;
	function doCallAjax() {
		HttPRequest = false;
		if (window.XMLHttpRequest) { // Mozilla, Safari,...
			HttPRequest = new XMLHttpRequest();
			if (HttPRequest.overrideMimeType) {
				HttPRequest.overrideMimeType('text/html');
			}
		} else if (window.ActiveXObject) { // IE
			try {
				HttPRequest = new ActiveXObject("Msxml2.XMLHTTP");
			} catch (e) {
				try {
					HttPRequest = new ActiveXObject("Microsoft.XMLHTTP");
				} catch (e) {}
			}
		} 
		  
		if (!HttPRequest) {
			alert('Cannot create XMLHTTP instance');
			return false;
		}
	
		var url = 'license-login.php';
		var pmeters = "tPassword=" + encodeURI( document.getElementById("txtPassword").value );

		HttPRequest.open('POST',url,true);

		HttPRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		HttPRequest.setRequestHeader("Content-length", pmeters.length);
		HttPRequest.setRequestHeader("Connection", "close");
		HttPRequest.send(pmeters);
			
		HttPRequest.onreadystatechange = function()
		{
			if(HttPRequest.readyState == 3)  // Loading Request
			{
				document.getElementById("mySpan").innerHTML = "Now is Loading...";
			}

			if(HttPRequest.readyState == 4) // Return Request
			{
				if(HttPRequest.responseText == 'Y')
				{
					window.location = 'ac-license2.php';
				}
				else
				{
					document.getElementById("mySpan").innerHTML = HttPRequest.responseText;
				}
			}
		}
	}
</script>
<body>
	<?php include("menu.php");?>
	<div class="row">
		<div class="background">
			<form name="frmMain" method="post" action="license-login.php">
			<table style="border: none; width: 100%;">
				<tr>
					<td class="b-bottom"><div class="large-4 columns"><a href="ac-account.php">< User</a></div></td>
					<td class="b-bottom"><div class="large-4 columns"><h4>Account License</h4></div></td>
				</tr>
				<tr>
					<td colspan="2"  style="background: #fff;">
						<table style="border: none; width: 95%; margin: 0;">	
							<tr style="background:none;">
								<td colspan="2" style="padding: 0;text-align: center;"><span id="mySpan"></span></td>
							</tr>
							<tr style="background: none;">
								<th style="text-align: right; width:45%;">Password<span style="color:red;">*</span> : </th>
								<th><input type="password" name="txtPassword" id="txtPassword" style="width: 30%;margin: 0;"></th>
							</tr> 
							<tr style="background: none;">
								<td colspan="2" style="text-align: center;"><input name="btnLogin" type="button" id="btnLogin" OnClick="JavaScript:doCallAjax();" value="Login"></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			</form>
		</div>
	</div>
</body>
</html>