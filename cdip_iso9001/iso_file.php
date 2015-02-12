<?php
ob_start();
session_start();
if($_SESSION["Username"] == ""){
	header("location:../index.php");
	exit();
}
$_SESSION['start'] = time(); // taking now logged in time
if(!isset($_SESSION['expire'])){
	$_SESSION['expire'] = $_SESSION['start'] + 3600 ; // ending a session in 1 Hr.
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
<script>
	function fncSubmit(){
		document.frm.submit();
	}
</script>
</head>
<body>
	<?php include("menu.php");?>
	<div class="row">
		<div class="background">
			<table style="border: 0; width: 100%;" cellpadding="0" cellspacing="0">
				<tr>
					<td class="b-bottom"><div class="large-4 columns"><h4>CDIP ISO 9001</h4></div></td>
				</tr>
				<tr>
					<td class="b-bottom" style="background: #fff;">
						<div class="large-4 columns"><a href="iso_folder.php?dep=<?php echo $_GET['dep']?>"><?php echo '< Back'?></a><h4>CDIP ISO 9001 -  <?php if($_GET['dep']==6){echo 'ADM';$dep='ADM';}elseif($_GET['dep']==1){echo 'NBD';$dep='NBD';}elseif($_GET['dep']==2){echo 'Inno';$dep='Inno';}elseif($_GET['dep']==5){echo 'SCM';$dep='SCM';}elseif($_GET['dep']==9){echo 'Lab&Pilot';$dep='Lab';}?> - <?php echo $_GET['type']?></h4>
							<?php 
							if(($rs_account['id_department']==6) && ($rs_account['role_user']!=3)){
							?>
								<form name="form1" method="post" action="db-iso.php" enctype="multipart/form-data">
									<input type="hidden" name="type_iso" value="<?php echo $_GET['type']?>">
									<input type="hidden" name="dep" value="<?php echo $_GET['dep']?>">
									<input type="file" name="filUpload">
									<input name="btnSubmit" type="submit" value="Submit">
								</form>
							<?php }
							if($_GET["action"] == "del"){
								$sql = "delete from iso_file";
								$sql .=" where id_iso_file='".$_GET["id_p"]."'";
								$res = mysql_query($sql) or die ('Error '.$sql);
							}
							?>
							<table style="border: 0; width: 100%;" cellpadding="0" cellspacing="0">
							<?php
							$rows_iso=0;
							$j=0;
							$sql_iso="select * from iso_file where id_department='".$_GET['dep']."'";
							$sql_iso .=" and type_iso='".$_GET['type']."'";
							$sql_iso .=" order by iso_file asc";
							$res_iso=mysql_query($sql_iso) or die ('Error '.$sql_iso);
							while($rs_iso=mysql_fetch_array($res_iso)){
								if($rows_iso % 1 ==0){?><tr><?php }
								$j++;
							?>
									<td>
										<a href="file_iso9001/<?php echo $dep?>/<?php echo $_GET['type']?>/<?php echo $rs_iso['iso_file']?>" target="_blank"><img src="img/icon-pdf.png" width="24"><?php echo $rs_iso['iso_file']?></a>
										<?php if(($rs_account['id_department']==6) && ($rs_account['role_user']!=3)){?>
										<a href="JavaScript:if(confirm('Confirm Delete?')==true){window.location='<?=$_SERVER["PHP_SELF"];?>?id_p=<?php echo $rs_iso['id_iso_file'] ?>&action=del&dep=<?php echo $_GET['dep']?>&type=<?php echo $_GET['type']?>';}"><img src="img/delete.png" style="margin:0 0 0 1%;" width="24"></a>
										<?php }?>
									</td>
							<?php if($j % 1 == 0){ ?></tr><?php } //display end row
								$rows_iso++;
							}?>
						</div>
					</td>
				</tr>			
			</table>
		</div>
	</div>
 <!-- <script>
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
  
 <!-- <script>
    $(document).foundation();
  </script>-->
</body>
</html>
