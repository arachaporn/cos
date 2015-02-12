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
	<?php include("menu.php");
	include("paginator.php");
		
	$sql="select * from sd_device where id_type_device='".$_GET['device']."'";
	$res=mysql_query($sql) or die('Error '.$sql);
	$num_row = mysql_num_rows($res);

	$Per_Page = 30;   // Per Page

	$Page = $_GET["Page"];
	if(!$_GET["Page"]){
		$Page=1;
	}

	$Prev_Page = $Page-1;
	$Next_Page = $Page+1;

	$Page_Start = (($Per_Page*$Page)-$Per_Page);
	if($num_row<=$Per_Page){
		$Num_Pages =1;
	}
	else if(($num_row % $Per_Page)==0){
		$Num_Pages =($num_row/$Per_Page) ;
	}
	else{
		$Num_Pages =($num_row/$Per_Page)+1;
		$Num_Pages = (int)$Num_Pages;
	}

	$sql .=" order by id_sd_device desc LIMIT $Page_Start , $Per_Page";
	$res = mysql_query($sql) or die ('Error '.$sql);
	?>
	<div class="row">
		<div class="background">
		<form name="frmMain" action="del-check-computer.php" method="post">
		<table style="border: 0; width: 100%;" cellpadding="0" cellspacing="0">
			<tr>
				<td class="b-bottom" style="text-align:left;">
					<div class="large-4 columns"><a href="record-device.php"><?php echo '<ประวัติอุปกรณ์'?></a></div>	
				</td>
				<?php
				$sql_device="select * from type_device where id_type_device='".$_GET['device']."'";
				$res_device=mysql_query($sql_device) or die ('Error '.$sql_device);
				$rs_device=mysql_fetch_array($res_device)
				?>
				<td class="b-bottom"><div class="large-4 columns"><h4>ประวัติอุปกรณ์ <?php echo $rs_device['title_device']?></h4></div></td>
			</tr>
			<tr>
				<td style="background: #fff;" colspan="2">
					<div class="large-4 columns">
						<table style="border: 1 solid #eee; width: 100%;" cellpadding="0" cellspacing="0">
							<tr class="top">
								<td width="2%" class="center">ลำดับ</td>
								<td width="15%" class="center"">ฝ่าย</td>
								<td width="10%" class="center">รหัสเครื่อง</td>
								<td width="20%" class="center">ผู้รับผิดชอบ</td>
								<td width="10%" class="center">หมายเหตุ</td>
							</tr>
							<!--light box -->
							<!--<script type="text/javascript" src="js/fancybox/scripts/jquery-1.4.3.min.js"></script>-->
							<script type="text/javascript" src="js/fancybox/fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
							<script type="text/javascript" src="js/fancybox/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
							<link rel="stylesheet" type="text/css" href="js/fancybox/fancybox/jquery.fancybox-1.3.4.css" media="screen" />
							<!--<link rel="stylesheet" href="js/style/style.css" />-->
							<script type="text/javascript">
								$(document).ready(function($) {
									$(".various").fancybox({
										maxWidth	: '100%',
										maxHeight	: '100%',
										fitToView	: false,
										width		: '80%',
										height		: '100%',
										autoSize	: false,
										closeClick	: false,
										hideOnOverlayClick	: false,
										openEffect	: 'none',
										closeEffect	: 'none',
										type		: 'iframe',
										/*onClosed	:	function() {
											parent.location.reload(true); 
										}*/				 
									});
								});
							</script>	
							<?php
							$i=0;
							while($rs=mysql_fetch_array($res)){
								$i++;
								
								$sql_account2="select * from account where id_employee='".$rs['id_employee']."'";
								$res_account2=mysql_query($sql_account2) or die ('Error '.$sql_account2);
								$rs_account2=mysql_fetch_array($res_account2);
								$department=$rs_account2['id_department'];

								$sql_department="select * from department where id_department='".$department."'";
								$res_department=mysql_query($sql_department) or die ('Error '.$sql_department);
								$rs_department=mysql_fetch_array($res_department);
								
							?>
							<tr>
								<td class="center"><?php echo $i;?></td>								
								<td style="padding-left: 8%;"><?php echo $rs_department['title']?></td>
								<td style="padding-left: 6.5%;"><?php echo $rs['device_code']?></td>
								<td style="padding-left: 13%;"><?php echo $rs_account2['id_employee'].'&nbsp;&nbsp;&nbsp;'.$rs_account2['name']?></td>
								<td class="center"><a class='various' data-fancybox-type='iframe' href='show-record-detail.php?device=<?=$rs['id_type_device']?>&code=<?=$rs['device_code']?>&account=<?=$rs_account2['id_employee']?>'>รายละเอียด</a></td>
							</tr>
							<?php } ?>
						</table>
					</div>
				</td>
			</tr>
			<tr>
				<td>
					<div class="large-4 columns">Total <?= $num_row;?> Record 
						<?php
						$pages = new Paginator;
						$pages->items_total = $num_row;
						$pages->mid_range = 10;
						$pages->current_page = $Page;
						$pages->default_ipp = $Per_Page;
						$pages->url_next = $_SERVER["PHP_SELF"]."?QueryString=value&Page=";

						$pages->paginate();

						echo $pages->display_pages()
						?>	
					</div>
				</td>
			</tr>
		</table>
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
