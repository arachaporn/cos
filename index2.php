<?php
session_start();
if($_SESSION["Username"] == ""){
	header("location:index.php");
	exit();
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

</head>
<body>
	<?php include("menu-index.php");?>
	<div class="row">
		<table style="border: 0; width: 100%;" cellpadding="0" cellspacing="0">
			<!--<tr>
				<td><div class="large-4 columns"><h4 style="font-size: 1.8em; color: #E55936; margin: 0;">CDIP Online System</h4></div></td>
			</tr>-->
			<tr style="background:none;">
				<td>
					<div class="large-4 columns">
					<?php
					$account_status=$rs_account['role_user']; 
					$sql="select * from menu where id_menu !='1' order by menu_order asc";
					$res=mysql_query($sql) or die ('Error '.$sql);
					while($rs=mysql_fetch_array($res)){
						$menu_array=split(",",$rs['menu_status']);
						if($menu_array[0]== $account_status){
					?>					
						<a href='<?php echo $rs['m_link']?>'>
						<div class="btn-home">
							<img src="img/<?php echo $rs['img']?>"><br><span style="text-align:center;"><?php echo $rs['title']?></a></span>					
						</div>
						</a>
					<?php }else
						if($menu_array[1]== $account_status){
					?>
						<a href='<?php echo $rs['m_link']?>'>
						<div class="btn-home">
							<img src="img/<?php echo $rs['img']?>"><br><span style="text-align:center;"><?php echo $rs['title']?></a></span>					
						</div>
						</a>
					<?php }else
						if($menu_array[2]== $account_status){
					?>
						<a href='<?php echo $rs['m_link']?>'>
						<div class="btn-home">
							<img src="img/<?php echo $rs['img']?>"><br><span style="text-align:center;"><?php echo $rs['title']?></a></span>					
						</div>
						</a>
					<?php }
					}?>
					</div>
				</td>
			</tr>
		</table>
		<?php include("footer.php")?>
		<br><br>
		<marquee><img src="img/cito/customer1.png">&nbsp;&nbsp;&nbsp;<img src="img/cito/inno1.png">&nbsp;&nbsp;&nbsp;<img src="img/cito/team1.png">&nbsp;&nbsp;&nbsp;<img src="img/cito/ontime1.png"></marquee>
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
