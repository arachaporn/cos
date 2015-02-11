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
	
		var url = 'user-login.php';
		var pmeters = "tUsername=" + encodeURI( document.getElementById("txtUsername").value) +
					  "&tPassword=" + encodeURI( document.getElementById("txtPassword").value );

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
					window.location = 'index2.php';
				}
				else
				{
					document.getElementById("mySpan").innerHTML = HttPRequest.responseText;
				}
			}
		}
	}
</script>
<body style="background: url('img/cdip-green.png') repeat center right;">
<div class="header-bg"></div>
<form name="frmMain" method="post" action="user-login.php">
<table style="border: none; width: 100%;">
	<tr>
		<td colspan="2" style="text-align: center; border-bottom: 1px dashed #eee; padding-bottom: 2%;"><img src="img/logo.png" style="width: 15%;"></td>
	</tr>
	<tr style="background: none;">
		<td colspan="2" style="text-align: center; font-size: 1.8em;"><h4 style="color: #E55936; margin: 0;">CDIP Online System</h4></td>
	</tr>
	<tr>
		<td colspan="2"  style="text-align: center;">
			<div class="border-login">
			<table style="border: none; width: 95%; margin: 0;">		
				<tr>
					<td colspan="2"><h3>Login</h3></td>
				</tr>
				<tr style="background:none;">
					<td colspan="2" style="padding: 0;"><span id="mySpan"></span></td>
				</tr>
				<tr>
					<th style="text-align: right;">Username<span style="color:red;">*</span> : </th>
					<th><input type="text" name="txtUsername" id="txtUsername" style="margin: 0;"></th>
				</tr>
				<tr style="background: none;">
					<th style="text-align: right;">Password<span style="color:red;">*</span> : </th>
					<th><input type="password" name="txtPassword" id="txtPassword" style="margin: 0;"></th>
				</tr> 
				<tr style="background: none;">
					<td colspan="2" style="text-align: center;"><input name="btnLogin" type="submit" id="btnLogin" OnClick="JavaScript:doCallAjax();" value="Login"></td>
				</tr>
			</table>
			</div>
		</td>
	</tr>
</table>
</form>
<br><br>
<marquee><img src="img/cito/customer1.png">&nbsp;&nbsp;&nbsp;<img src="img/cito/inno1.png">&nbsp;&nbsp;&nbsp;<img src="img/cito/team1.png">&nbsp;&nbsp;&nbsp;<img src="img/cito/ontime1.png"></marquee>
</body>
</html>