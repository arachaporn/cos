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
	<?php include("menu.php");?>
	<div class="row">
		<div class="background">
		<form name="frmMain" action="del-costing-table.php" method="post">
			<table style="border: 0; width: 100%;" cellpadding="0" cellspacing="0">
				<tr>
					<td class="b-bottom" style="text-align:left;">
						<div class="large-4 columns"><a href="costing-table.php"><?php echo '< Costing table'?></a></div>	
					</td>
					<td class="b-bottom">
						<?php
						$sql_fac="select * from type_manufactory where id_manufacturer='".$_GET['fac']."'";
						$res_fac=mysql_query($sql_fac) or die ('Error '.$sql_fac);
						$rs_fac=mysql_fetch_array($res_fac);
						?>
						<div class="large-4 columns"><h4>Costing table <?php echo $rs_fac['title']?></h4></div>
					</td>					
				</tr>
				<tr>
					<td style="background: #fff;" colspan="2">
						<div class="large-4 columns">
							<?php if($_GET['fac']==1){?>
							<table cellpadding="0" cellspacing="0" style="float: left; margin-right: 5%;">
								<tr>
									<td colspan="3" style="background: #FFC000; text-align: center;font-weight: bold; font-size: 1em;">Tablet Cost</td>
								</tr>
								<tr>
									<td colspan="3" style="background: #FFFF53; font-weight: bold;">MOH rate</td>
								</tr>
								<tr>
									<td class="center">Weight per tablet (mg)</td>
									<td class="center">Appearance</td>
									<td class="center">MOH (baht per tab)</td>
								</tr>
								<tr>
									<td class="center">300-500</td>
									<td class="center">Core tablet</td>
									<td style="text-align: right;">0.50</td>
								</tr>
								<tr>
									<td class="center">300-500</td>
									<td class="center">FC</td>
									<td style="text-align: right;">0.70</td>
								</tr>
								<tr>
									<td class="center">500-800</td>
									<td class="center">Core tablet</td>
									<td style="text-align: right;">0.70</td>
								</tr>
								<tr>
									<td class="center">500-800</td>
									<td class="center">FC</td>
									<td style="text-align: right;">1.00</td>
								</tr>
								<tr>
									<td class="center">800-1500</td>
									<td class="center">Core tablet</td>
									<td style="text-align: right;">1.00</td>
								</tr>
								<tr>
									<td class="center">800-1500</td>
									<td class="center">FC</td>
									<td style="text-align: right;">1.20</td>
								</tr>
								<tr>
									<td colspan="3" style="background: #FFFF53; font-weight: bold;">Profit per tablet</td>
								</tr>
								<tr>
									<td class="center">Cost per tab inc Vat 7%</td>
									<td colspan="2" class="center">Profit rate (baht per tab)</td>
								</tr>
								<tr>
									<td class="center">1.00-3.50</td>
									<td colspan="2" class="center">0.375</td>
								</tr>
								<tr>
									<td class="center">3.51-4.50</td>
									<td colspan="2" class="center">0.500</td>
								</tr>
								<tr>
									<td class="center">4.51-5.50</td>
									<td colspan="2" class="center">0.625</td>
								</tr>
								<tr>
									<td class="center">5.51-6.50</td>
									<td colspan="2" class="center">0.750</td>
								</tr>
								<tr>
									<td class="center">6.51-7.50</td>
									<td colspan="2" class="center">0.875</td>
								</tr>
								<tr>
									<td class="center">up to 7.50</td>
									<td colspan="2" class="center">1.000</td>
								</tr>
								<tr>
									<td colspan="3" style="background: #FFFF53; font-weight: bold;">MOQ</td>
								</tr>
								<tr>
									<td class="center">Quantities</td>
									<td class="center">JSP Profit</td>
									<td class="center">CDIP Profit</td>
								</tr>
								<tr>
									<td class="left">75,000-149,999 tab</td>
									<td class="center">25%</td>
									<td class="center">35%</td>
								</tr>
								<tr>
									<td class="left">150,000-299,999 tab</td>
									<td class="center">20%</td>
									<td class="center">25%</td>
								</tr>
								<tr>
									<td class="left">300,000-499,999 tab</td>
									<td class="center">15%</td>
									<td class="center">20%</td>
								</tr>
								<tr>
									<td class="left">500,000-999,999 tab</td>
									<td class="center">10%</td>
									<td class="center">15%</td>
								</tr>
								<tr>
									<td class="left">up to 1,000,000 tab</td>
									<td class="center">5%</td>
									<td class="center">10%</td>
								</tr>
								<tr>	
									<td colspan="3"><b>**การคำนวณ JSP Profit และ CDIP Profit เกิดจากการนำ JSP Profit หรือ CDIP Profit * Total cost per tablet</b></td>
								</tr>
							</table>
							<table cellpadding="0" cellspacing="0" style="margin-bottom: 5%;">
								<tr>
									<td colspan="5" style="background: #33CC33; text-align: center;font-weight: bold; font-size: 1em;">Capsule Cost</td>
								</tr> 
								<tr>
									<td colspan="5" style="background: #99FF33; font-weight: bold;">MOH and Capsule cost rate</td>
								</tr>
								<tr>
									<td class="center">Group</td>
									<td class="center">Capsule size</td>
									<td class="center">Weight per cap (mg)</td>
									<td class="center">Capsule cost (baht per cap)</td>
								</tr>								
								<tr>
									<td class="center" rowspan="2">Gelatin</td>								
									<td class="center">#0</td>
									<td class="center">96</td>
									<td style="text-align: right;">0.20</td>								
								</tr>
								<tr>
									<td class="center">#00</td>
									<td class="center">118</td>
									<td style="text-align: right;">0.25</td>								
								</tr>
								<tr>
									<td class="center" rowspan="2" style="background:#fff;">HPMC</td>								
									<td class="center">#0</td>
									<td class="center">96</td>
									<td style="text-align: right;">0.30</td>								
								</tr>
								<tr>
									<td class="center">#00</td>
									<td class="center">118</td>
									<td style="text-align: right;">0.35</td>								
								</tr>
								<tr>
									<td colspan="2" class="center">MOH (baht per cap)</td>
									<td colspan="2" class="center">0.40</td>
								</tr>
								<tr>
									<td colspan="4" style="background: #99FF33; font-weight: bold;">Profit per tablet</td>
								</tr>
								<tr>
									<td colspan="2" class="center">Cost per tab inc Vat 7%</td>
									<td colspan="2" class="center">Profit rate (baht per tab)</td>
								</tr>
								<tr>
									<td colspan="2" class="center">1.00-3.50</td>
									<td colspan="2" class="center">0.375</td>
								</tr>
								<tr>
									<td colspan="2" class="center">3.51-4.50</td>
									<td colspan="2" class="center">0.500</td>
								</tr>
								<tr>
									<td colspan="2" class="center">4.51-5.50</td>
									<td colspan="2" class="center">0.625</td>
								</tr>
								<tr>
									<td colspan="2" class="center">5.51-6.50</td>
									<td colspan="2" class="center">0.750</td>
								</tr>
								<tr>
									<td colspan="2" class="center">6.51-7.50</td>
									<td colspan="2" class="center">0.875</td>
								</tr>
								<tr>
									<td colspan="2" class="center">up to 7.50</td>
									<td colspan="2" class="center">1.000</td>
								</tr>
								<tr>
									<td colspan="4" style="background: #99FF33; font-weight: bold;">MOQ</td>
								</tr>
								<tr>
									<td colspan="2" class="center">Quantities</td>
									<td class="center">JSP Profit</td>
									<td class="center">CDIP Profit</td>
								</tr>
								<tr>
									<td colspan="2">75,000-149,999 tab</td>
									<td class="center">25%</td>
									<td class="center">35%</td>
								</tr>
								<tr>
									<td colspan="2">150,000-299,999 tab</td>
									<td class="center">20%</td>
									<td class="center">25%</td>
								</tr>
								<tr>
									<td colspan="2">300,000-499,999 tab</td>
									<td class="center">15%</td>
									<td class="center">20%</td>
								</tr>
								<tr>
									<td colspan="2">500,000-999,999 tab</td>
									<td class="center">10%</td>
									<td class="center">15%</td>
								</tr>
								<tr>
									<td colspan="2">up to 1,000,000 tab</td>
									<td class="center">5%</td>
									<td class="center">10%</td>
								</tr>
								<tr>	
									<td colspan="4"><b>**การคำนวณ JSP Profit และ CDIP Profit เกิดจากการนำ JSP Profit หรือ CDIP Profit * Total cost per tablet</b></td>
								</tr>
							</table>
							<table style="float:left;margin-right:5%;" cellpadding="0" cellspacing="0">
								<tr>
									<td colspan="7" style="background: #92CDDC; text-align: center;font-weight: bold; font-size: 1em;">Functional drink Cost</td>
								</tr> 								
								<tr>
									<td class="center">Weight per bottle (cc)</td>
									<td class="center">MOH Mixing</td>
									<td class="center">Profit per bottle</td>									
									<td class="center">Amber glass Bottle</td>
									<td class="center">Aluminum screw cap 1 color</td>
									<td class="center">MOH Packing</td>
									<td class="center">Sticker label</td>
								</tr>
								<tr>
									<td class="center">50</td>
									<td class="center">2.00</td>									
									<td class="center">0.50</td>
									<td class="center">1.90</td>
									<td class="center">0.60</td>
									<td class="center">2.00</td>
									<td class="center">1.50</td>
								</tr>
								<tr>
									<td colspan="7" style="background: #DAEEF3; font-weight: bold;">MOQ and Profit rate</td>
								</tr>
								<tr>
									<td colspan="3" class="center">Quantities</td>
									<td colspan="2" class="center">JSP Profit</td>
									<td colspan="2" class="center">CDIP Profite</td>									
								</tr>
								<tr>
									<td colspan="3" class="center">75,000-149,999</td>
									<td colspan="2" class="center">15%</td>
									<td colspan="2" class="center">15%</td>									
								</tr>
								<tr>
									<td colspan="3" class="center">150,000-299,999</td>
									<td colspan="2" class="center">10%</td>
									<td colspan="2" class="center">10%</td>									
								</tr>
								<tr>
									<td colspan="3" class="center">300,000-499,999</td>
									<td colspan="2" class="center">8%</td>
									<td colspan="2"class="center">8%</td>									
								</tr>
								<tr>
									<td colspan="3" class="center">up to 500,000</td>
									<td colspan="2" class="center">5%</td>
									<td colspan="2" class="center">5%</td>									
								</tr>
								<!--<tr>
									<td colspan="5" class="center" style="font-weight: bold;">***** In case of customer would like to screen cap, the MOQ of screen cap is 500,000 pcs. *****</td>
								</tr>-->
							</table>	
							<table cellpadding="0" cellspacing="0">
								<tr>
									<td colspan="4" style="background: #9148C8; text-align: center;font-weight: bold; font-size: 1em;">Instant drink Cost</td>
								</tr>
								<tr>
									<td colspan="4" style="background: #BAABCD; font-weight: bold;">MOH</td>
								</tr>
								<tr>
									<td class="center">Weight per sachet (gm)</td>
									<td class="center">MOH</td>
									<td class="center">Profit per sac</td>
									<td class="center">Sachet cost 4 color</td>
								</tr>
								<tr>					
									<td class="center">10,15,30</td>
									<td style="text-align: right;">0.50</td>	
									<td style="text-align: right;">0.50</td>
									<td style="text-align: right;">0.75</td>
								</tr>
								<tr>					
									<td class="center">50</td>
									<td style="text-align: right;">1.50</td>	
									<td style="text-align: right;">0.50</td>
									<td style="text-align: right;">0.75</td>
								</tr>
								<tr>
									<td colspan="4" style="background: #BAABCD; font-weight: bold;">MOQ and Profit rate</td>
								</tr>
								<tr>
									<td colspan="2" class="center">Quantities</td>
									<td class="center">JSP Profit</td>
									<td class="center">CDIP Profite</td>									
								</tr>
								<tr>
									<td colspan="2" class="center">75,000-149,999</td>
									<td class="center">15%</td>
									<td class="center">15%</td>									
								</tr>
								<tr>
									<td colspan="2" class="center">150,000-299,999</td>
									<td class="center">10%</td>
									<td class="center">10%</td>									
								</tr>
								<tr>
									<td colspan="2" class="center">300,000-499,999</td>
									<td class="center">8%</td>
									<td class="center">8%</td>									
								</tr>
								<tr>
									<td colspan="2" class="center">up to 500,000</td>
									<td class="center">5%</td>
									<td class="center">5%</td>									
								</tr>
							</table>
							<table cellpadding="0" cellspacing="0" style="float:left; margin-right: 5%;">
								<tr>
									<td colspan="5" style="background: #FABF8F; text-align: center;font-weight: bold; font-size: 1em;">Soft gelatin Cost</td>
								</tr> 
								<tr>
									<td colspan="5" style="background: #FDE9D9; font-weight: bold;">MOH rate</td>
								</tr>
								<tr>
									<td class="center">Weight per tablet (mg)</td>
									<td colspan="2" class="center">MOH (baht per cap)</td>
								</tr>								
								<tr>
									<td class="center">250</td>
									<td colspan="2" class="center">0.25</td>
								</tr>
								<tr>
									<td class="center">500</td>
									<td colspan="2" class="center">0.35</td>
								</tr>
								<tr>
									<td class="center">1000</td>
									<td colspan="2" class="center">0.60</td>
								</tr>
								<tr>
									<td colspan="5" style="background: #FDE9D9; font-weight: bold;">MOQ and Profit</td>
								</tr>
								<tr>
									<td class="center">JSP Profit</td>
									<td class="center">MOQ(Quantities)</td>
									<td class="center">CDIP Profit</td>
								</tr>
								<tr>
									<td class="center">0.25</td>
									<td class="center">75,000-149,999 tab</td>
									<td class="center">35%</td>
								</tr>
								<tr>
									<td class="center">0.25</td>
									<td class="center">150,000-299,999 tab</td>
									<td class="center">25%</td>
								</tr>
								<tr>
									<td class="center">0.25</td>
									<td class="center">300,000-499,999 tab</td>
									<td class="center">20%</td>
								</tr>
								<tr>
									<td class="center">0.25</td>
									<td class="center">500,000-999,999 tab</td>
									<td class="center">15%</td>
								</tr>
								<tr>
									<td class="center">0.25</td>
									<td class="center">up to 1,000,000 tab</td>
									<td class="center">10%</td>
								</tr>
								<tr>	
									<td colspan="3"><b>**การคำนวณ CDIP Profit เกิดจากการนำ  CDIP Profit * Total cost per tablet</b></td>
								</tr>
							</table>
							<?php }elseif($_GET['fac']==3){?>
							<table style="float:left;margin-right:5%;" cellpadding="0" cellspacing="0">
								<tr>
									<td colspan="5" style="background: #92CDDC; text-align: center;font-weight: bold; font-size: 1em;">Functional drink Cost</td>
								</tr> 								
								<tr>
									<td class="center">Weight per bottle (cc)</td>
									<td class="center">Filling MOH</td>
									<td class="center">Glass Bottle</td>									
									<td class="center">Screw Cap</td>
									<td class="center">MOH</td>
								</tr>
								<tr>
									<td class="center">40-50</td>
									<td class="center">1.75</td>									
									<td class="center">2.33</td>
									<td class="center">2.00</td>
									<td class="center">0.25</td>
								</tr>
								<tr>
									<td class="center">100</td>
									<td class="center">2.00</td>									
									<td class="center">2.90</td>
									<td class="center">1.10</td>
									<td class="center">0.25</td>
								</tr>
								<tr>
									<td class="center">250</td>
									<td class="center">2.00</td>									
									<td class="center">3.50</td>
									<td class="center">1.10</td>
									<td class="center">0.25</td>
								</tr>
								<tr>
									<td class="center">250(retort)</td>
									<td class="center">2.00</td>									
									<td class="center">3.50</td>
									<td class="center">1.90</td>
									<td class="center">0.25</td>
								</tr>
								<tr>
									<td colspan="5" style="background: #DAEEF3; font-weight: bold;">MOQ and Profit rate</td>
								</tr>
								<tr>
									<td colspan="2" class="center">Quantities</td>
									<td colspan="2" class="center">Prima Profit</td>
									<td class="center">CDIP Profite</td>									
								</tr>
								<tr>
									<td colspan="2" class="center">100,000-300,000 bottle</td>
									<td colspan="2" class="center">15%</td>
									<td class="center">15%</td>									
								</tr>
								<tr>
									<td colspan="2" class="center">300,001-500,000 bottle</td>
									<td colspan="2" class="center">12%</td>
									<td class="center">12%</td>									
								</tr>
								<tr>
									<td colspan="2	" class="center">500,001 bottle</td>
									<td colspan="2" class="center">10%</td>
									<td class="center">10%</td>									
								</tr>
								<tr>
									<td colspan="5" class="center" style="font-weight: bold;">***** MOQ 100,000 Bottle. *****</td>
								</tr>
							</table>	
							<?php }?>
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
