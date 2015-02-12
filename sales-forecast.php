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
<script type="text/javascript" src="http://code.jquery.com/jquery-1.7.2.min.js"></script>
</head>
<body>
	<?php include("menu.php");?>	
	<div class="row">
		<div class="background">
		<!--light box -->
		<!--<script type="text/javascript" src="js/fancybox/scripts/jquery-1.4.3.min.js"></script>-->
		<script type="text/javascript" src="js/fancybox/fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
		<script type="text/javascript" src="js/fancybox/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
		<link rel="stylesheet" type="text/css" href="js/fancybox/fancybox/jquery.fancybox-1.3.4.css" media="screen" />
		<!--<link rel="stylesheet" href="js/style/style.css" />-->
		<script type="text/javascript">
			$(document).ready(function($) {
				$(".various").fancybox({
					maxWidth	: 800,
					maxHeight	: 600,
					fitToView	: false,
					width		: '80%',
					height		: '80%',
					autoSize	: false,
					closeClick	: false,
					hideOnOverlayClick	: false,
					openEffect	: 'none',
					closeEffect	: 'none',
					type		: 'iframe',
					onClosed	:	function() {
						parent.location.reload(true); 
					}				 
				});
			});
		</script>	
		<form name="frmMain" action="del-roc.php" method="post">
		<input type="hidden" name="forecast" value="<?php echo $forecast?>">
		<table style="border: 0; width: 100%;" cellpadding="0" cellspacing="0">
			<tr>
				<td class="b-bottom" style="text-align:left;">
					<div class="large-4 columns"><a href="sales-marketing.php"><?php echo '< New Business Development'?></a></div>	
				</td>
				<td class="b-bottom"><div class="large-4 columns"><h4><?php if($_GET['st']=='po'){echo 'PO ';}elseif($_GET['st']=='cr'){echo 'CR ';}?>Sales Forecast</h4></div></td>
				<!--<td class="b-bottom" style="text-align:right;">
					<div class="large-4 columns">
						<a class='various' data-fancybox-type='iframe' href='ac-sales-forecast.php?id_u=New&month=<?=date('m')?>&year=<?=date('Y')?>&st=<?php echo $forecast?>'><img src='img/add.png'></a>
						<input type="submit" name="del" value="Delete" class="btn-trash" OnClick="return chkdel();" title="Delete">
						<script language="JavaScript">
							function ckall(vol){					
								var i=1;
								for(i=1;i<=document.frmMain.hdnCount.value;i++){
									if(vol.checked == true){
										eval("document.frmMain.ck_del"+i+".checked=true");
									}
									else{
										eval("document.frmMain.ck_del"+i+".checked=false");
									}
								}
							}
						</script>
					</div>
				</td>-->
			</tr>
			<tr>
				<td style="background: #fff;" colspan="2">
					<div class="large-4 columns">
						<?php 						
						if($_GET['st']=='po'){$st='po';}elseif($_GET['st']=='cr'){$st='cr';}
						$thisYear = !empty($_GET['y']) ? (int) $_GET['y'] : date('Y'); 
						echo '<div id="main">';
						echo '<div id="nav">';
						echo '<div class="navLeft"><a style="color:#fff;" href="?y=', ( $thisYear - 1 ), '&st='.$st.'">', ( $thisYear - 1 ), '</a></div>';	
						echo '<div class="title_calendar" style="padding-left:6%;">';
						echo $thisYear;
						echo '</div>';
						echo '<div class="navRight"><a style="color:#fff;" href="?y=', ( $thisYear + 1 ), '&st='.$st.'">', ( $thisYear + 1 ), '</a></div>'; 
						echo '</div>';
						echo '<div style="clear:both"></div>';
						echo '<table id="tb_calendar" style="margin:0;" cellpadding="0" cellspacing="0">';
						for( $month = 1; $month <= 12; $month++ ) { 
							if( $month % 4 == 1 ) echo '<tr>'; 
							if($month<10){$month2='0'.$month;}else{$month2=$month;}
							if(($rs_account['role_user']==3) && ($rs_account['id_account']!=28)){$create=" and create_by='".$rs_account['id_account']."'";}else{if($rs_account['id_account']==28){$create=" ";}else{$create=" ";}}
							$sql_f="select * from sm_sales_forecast";
							$sql_f .=" where month_visited='".$month2."' and year_visited='".$thisYear."'"; 
							$sql_f .=" and status_forecast='".$st."'";
							$sql_f .=$create;
							$res_f=mysql_query($sql_f) or die ('Error '.$sql_f);
							$rs_f=mysql_fetch_array($res_f);
							if(($month2==$rs_f['month_visited']) && ($thisYear==$rs_f['year_visited'])){
								if($rs_account['id_account']==28){$bg="background: #F8E4DF;";}

								if($rs_account['role_user']==3){
									if(($rs_account['id_account']==$rs_f['create_by'])){
										$bg="background: #F8E4DF;";
									}
								}else{$bg="background: #F8E4DF;";}
							}
							else{$bg="";}
							   
							//echo '<td >', strtoupper(date('F/Y', strtotime(sprintf('%d-%d-01', 
							//$thisYear, str_pad($month, 2, '0', STR_PAD_LEFT))))), '</td>'; 
							echo '<td style="text-align:center;padding:4% 3%;'.$bg.'" class="th-calendar-right th-calendar-bottom">';	
							echo '<a class="various" data-fancybox-type="iframe" style="color:#000;"href="ac-sales-forecast.php?id_u=New&st='.$st.'&month='.$month2.'&year='.$thisYear.'">';
							echo strtoupper(date('F', strtotime(sprintf('%d-%d-01',$thisYear, str_pad($month, 2, '0', STR_PAD_LEFT)))));
							echo '</a>';
							echo '<br><br>';
							$month1=date('m');
							$year1=date('Y');
							$sql="select * from sm_sales_forecast";
							$sql .=" group by month_visited,year_visited";
							$res=mysql_query($sql) or die ('Error '.$sql);
							$rs=mysql_fetch_array($res);								
							?>	
								<span class="button-create" style="color:#fff;"><a class='various' data-fancybox-type='iframe' href='ac-sales-target.php?month=<?=$month2?>&year=<?=$thisYear?>&st=<?=$st?>' style="color:#fff;">Target of <?php echo $month_name?></a></span>
								<?php if($st=='po'){?>
								<input type="button" name="update_data" id="update_data" value="Export Excel <?php echo date('M', mktime(0, 0, 0, $month2))?>" class="button-create" Onclick="window.open('excel-sales-forecast.php?st=<?=$st?>&month=<?=$month2?>&year=<?=$thisYear?>')">
								<?php }elseif($st=='cr'){?>
								<input type="button" name="update_data" id="update_data" value="Export Excel <?php echo date('M', mktime(0, 0, 0, $month2))?>" class="button-create" Onclick="window.open('excel-sales-forecast-cr.php?st=<?=$st?>&month=<?=$month2?>&year=<?=$thisYear?>')">
								<?php }?>
							<?php
							
							echo '</td>';
							if( $month % 4 == 0 ) echo '</tr>'; 
						} 
						echo '</table>';
						?>
					</div>
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
