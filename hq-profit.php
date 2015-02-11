<?php
include("connect/connect.php");
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
		<table style="border: 0; width: 100%;" cellpadding="0" cellspacing="0">
			<tr>
				<td class="b-bottom"><div class="large-4 columns"><h4>MOH, MOQ and Profit</h4></div></td>
			</tr>
			<tr>
				<td style="background: #fff;">
					<div class="large-4 columns">
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
							<?php
							$sql_cost_detail="select * from cost_detail where id_type_product=1";
							$res_cost_detail=mysql_query($sql_cost_detail) or die ('Error '.$sql_cost_detail);
							while($rs_cost_detail=mysql_fetch_array($res_cost_detail)){
							?>
							<tr>
								<td class="center"><?php echo $rs_cost_detail['weight']?></td>
								<td class="center"><?php echo $rs_cost_detail['appea_size']?></td>
								<td style="text-align: right;">
									<?php
									$sql_moh="select * from cost_moh where id_moh='".$rs_cost_detail['id_moh']."'";
									$res_moh=mysql_query($sql_moh) or die ('Error '.$sql_moh);
									while($rs_moh=mysql_fetch_array($res_moh)){
										echo number_format($rs_moh['moh'],2);
									} //end query moh
									?>
								</td>
							</tr>
							<?php } //end query cost detail?>
							<tr>
								<td colspan="3" style="background: #FFFF53; font-weight: bold;">Profit rate</td>
							</tr>
							<tr>
								<td class="center">Loss 10% + Vat (baht per tab)</td>
								<td class="center">Profit rate (baht per tab)</td>
								<td class="center">MOQ (tab per batch)</td>
							</tr>
							<?php
							$sql_cost_profit="select * from cost_profit";
							$res_cost_profit=mysql_query($sql_cost_profit) or die ('Error '.$sql_cost_profit);
							while($rs_cost_profit=mysql_fetch_array($res_cost_profit)){
								if($rs_cost_profit['id_profit'] <= 6){
							?>
								<tr>
									<td class="center"><?php echo $rs_cost_profit['loss_10']?></td>
									<td class="right"><?php echo number_format($rs_cost_profit['profit_rate'],4)?></td>
									<td style="text-align: right;">
										<?php
										$sql_moq="select * from cost_moq where id_moq='".$rs_cost_profit['id_moq']."'";
										$res_moq=mysql_query($sql_moq) or die ('Error '.$sql_moq);
										while($rs_moq=mysql_fetch_array($res_moq)){
											echo number_format($rs_moq['moq'],2);
										} //end query moq
										?>
									</td>
								</tr>
							<?php } //end if id profit < 6
							} //end query cost profit?>
						</table>
						<table cellpadding="0" cellspacing="0" style="margin-bottom: 21%;">
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
								<td class="center">MOQ (cap per batch)</td>
							</tr>
							<?php
							$l=0;
							$sql_cost_detail="select * from cost_detail where id_type_product=2";
							$res_cost_detail=mysql_query($sql_cost_detail) or die ('Error '.$sql_cost_detail);
							while($rs_cost_detail=mysql_fetch_array($res_cost_detail)){
								if($rs_cost_detail['id_type_sub_product'] <= 2){
									$l++;
									$id_moh=$rs_cost_detail['id_moh'];
							?>
							<tr>
								<td class="center">
									<?php 
									$sql_type_sub_product="select * from type_sub_product";
									$sql_type_sub_product .=" where id_type_sub_product='".$rs_cost_detail['id_type_sub_product']."'";
									$sql_type_sub_product .=" group by title";
									$res_type_sub_product=mysql_query($sql_type_sub_product) or die ('Error '.$sql_type_sub_product);
									while($rs_type_sub_product=mysql_fetch_array($res_type_sub_product)){
										if($rs_type_sub_product['id_type_sub_product'] <= 2)
											if(($l==1) || ($l==3))	
											echo $rs_type_sub_product['title'];
									}
									?>
								</td>								
								<td class="center"><?php echo $rs_cost_detail['appea_size']?></td>
								<td class="center"><?php echo $rs_cost_detail['weight']?></td>
								<td style="text-align: right;">
									<?php
									$sql_cost_capsule="select * from cost_capsule";
									$sql_cost_capsule .=" where id_cost_detail='".$rs_cost_detail['id_cost_detail']."'";
									$res_cost_capsule=mysql_query($sql_cost_capsule) or die ('Error '.$sql_cost_capsule);
									while($rs_cost_capsule=mysql_fetch_array($res_cost_capsule)){
										echo number_format($rs_cost_capsule['cost_capsule'],2);
									}
									?>
								</td>								
								<td style="text-align: right;">
									<?php
									$sql_moq="select * from cost_moq where id_moq='".$rs_cost_detail['id_moq']."'";
									$res_moq=mysql_query($sql_moq) or die ('Error '.$sql_moq);
									while($rs_moq=mysql_fetch_array($res_moq)){
										echo number_format($rs_moq['moq'],2);
									} //end query moq
									?>
								</td>
							</tr>
							<?php  }//end if id type sub product < 2 
							}//end query cost detail?>
							<tr>
								<td><td>
								<td colspan="2">MOH (baht per cap)</td>
								<td colspan="2" style="text-align: right;">
									<?php
									$sql_moh="select * from cost_moh where id_moh='".$id_moh."'";
									$res_moh=mysql_query($sql_moh) or die ('Error '.$sql_moh);
									while($rs_moh=mysql_fetch_array($res_moh)){
										echo number_format($rs_moh['moh'],2);
									} //end query moh
									?>
								</td>
							</tr>
						</table>
						<table cellpadding="0" cellspacing="0" style="float: left; margin-right: 5%;">
							<tr>
								<td colspan="5" style="background: #FABF8F; text-align: center;font-weight: bold; font-size: 1em;">Soft gelatin Cost</td>
							</tr> 
							<tr>
								<td colspan="5" style="background: #FDE9D9; font-weight: bold;">MOH rate</td>
							</tr>
							<tr>
								<td class="center">Weight per tablet (mg)</td>
								<td class="center">Appearance</td>
								<td class="center">MOH (baht per cap)</td>
							</tr>
							<?php
							$sql_cost_detail="select * from cost_detail where id_type_product=3";
							$res_cost_detail=mysql_query($sql_cost_detail) or die ('Error '.$sql_cost_detail);
							while($rs_cost_detail=mysql_fetch_array($res_cost_detail)){
							?>
							<tr>
								<td class="center"><?php echo $rs_cost_detail['weight']?></td>
								<td class="center"><?php echo $rs_cost_detail['appea_size']?></td>
								<td style="text-align: right;">
									<?php
									$sql_moh="select * from cost_moh where id_moh='".$rs_cost_detail['id_moh']."'";
									$res_moh=mysql_query($sql_moh) or die ('Error '.$sql_moh);
									while($rs_moh=mysql_fetch_array($res_moh)){
										echo number_format($rs_moh['moh'],2);
									} //end query moh
									?>
								</td>
							</tr>
							<?php } //end query cost detail?>
						</table>
						<table cellpadding="0" cellspacing="0" style="margin-bottom: 8%;">
							<tr>
								<td colspan="5" style="background: #92CDDC; text-align: center;font-weight: bold; font-size: 1em;">Liquid Capsule Cost</td>
							</tr> 
							<tr>
								<td colspan="5" style="background: #DAEEF3; font-weight: bold;">MOH and Capsule cost rate</td>
							</tr>
							<tr>
								<td class="center">Group</td>
								<td class="center">Capsule size</td>
								<td class="center">Weight per cap (mg)</td>
								<td class="center">Capsule cost (baht per cap)</td>
								<td class="center">MOQ (cap per batch)</td>
							</tr>
							<?php
							$k=0;
							$sql_cost_detail="select * from cost_detail where id_type_product=2";
							$res_cost_detail=mysql_query($sql_cost_detail) or die ('Error '.$sql_cost_detail);
							while($rs_cost_detail=mysql_fetch_array($res_cost_detail)){
								if($rs_cost_detail['id_type_sub_product'] > 2){
									$k++;
									$id_moh=$rs_cost_detail['id_moh'];
							?>
							<tr>
								<td class="center">
									<?php 
									
									$sql_type_sub_product="select * from type_sub_product";
									$sql_type_sub_product .=" where id_type_sub_product='".$rs_cost_detail['id_type_sub_product']."'";
									$sql_type_sub_product .=" group by title";
									$res_type_sub_product=mysql_query($sql_type_sub_product) or die ('Error '.$sql_type_sub_product);
									while($rs_type_sub_product=mysql_fetch_array($res_type_sub_product)){
										if($rs_type_sub_product['id_type_sub_product'] > 2)
											if(($k==1) || ($k==3))
												echo $rs_type_sub_product['title'];
									}
									?>
								</td>								
								<td class="center"><?php echo $rs_cost_detail['appea_size']?></td>
								<td class="center"><?php echo $rs_cost_detail['weight']?></td>
								<td style="text-align: right;">
									<?php
									$sql_cost_capsule="select * from cost_capsule";
									$sql_cost_capsule .=" where id_cost_detail='".$rs_cost_detail['id_cost_detail']."'";
									$res_cost_capsule=mysql_query($sql_cost_capsule) or die ('Error '.$sql_cost_capsule);
									while($rs_cost_capsule=mysql_fetch_array($res_cost_capsule)){
										echo number_format($rs_cost_capsule['cost_capsule'],2);
									}
									?>
								</td>								
								<td style="text-align: right;">
									<?php
									$sql_moq="select * from cost_moq where id_moq='".$rs_cost_detail['id_moq']."'";
									$res_moq=mysql_query($sql_moq) or die ('Error '.$sql_moq);
									while($rs_moq=mysql_fetch_array($res_moq)){
										echo number_format($rs_moq['moq'],2);
									} //end query moq
									?>
								</td>
							</tr>
							<?php  }//end if id type sub product < 2 
							}//end query cost detail?>
							<tr>
								<td><td>
								<td colspan="2">MOH (baht per cap)</td>
								<td colspan="2" style="text-align: right;">
									<?php
									$sql_moh="select * from cost_moh where id_moh='".$id_moh."'";
									$res_moh=mysql_query($sql_moh) or die ('Error '.$sql_moh);
									while($rs_moh=mysql_fetch_array($res_moh)){
										echo number_format($rs_moh['moh'],2);
									} //end query moh
									?>
								</td>
							</tr>
						</table>
						<table cellpadding="0" cellspacing="0">
							<tr>
								<td colspan="5" style="background: #92CDDC; text-align: center;font-weight: bold; font-size: 1em;">Functional drink Cost</td>
							</tr> 
							<tr>
								<td colspan="5" style="background: #DAEEF3; font-weight: bold;">MOH and Profit rate</td>
							</tr>
							<tr>
								<td class="center">Weight per bottle (cc)</td>
								<td class="center">Profit rate (baht per bottle)</td>
								<td class="center">MOH mixing (baht per bottle)</td>
								<td class="center">MOH Packing (baht per bottle)</td>
								<td class="center">MOQ (bottle per batch)</td>
							</tr>
							<?php
							$sql_cost_detail="select * from cost_detail where id_type_product=6";
							$res_cost_detail=mysql_query($sql_cost_detail) or die ('Error '.$sql_cost_detail);
							while($rs_cost_detail=mysql_fetch_array($res_cost_detail)){
							?>
							<tr>
								<td class="center"><?php echo $rs_cost_detail['weight']?></td>
								<td style="text-align: right;">
								<?php
								$sql_cost_profit="select * from cost_profit where id_profit='".$rs_cost_detail['id_profit']."'";
								$res_cost_profit=mysql_query($sql_cost_profit) or die ('Error '.$sql_cost_profit);
								while($rs_cost_profit=mysql_fetch_array($res_cost_profit)){
									echo number_format($rs_cost_profit['profit_rate'],2);
								} //end query cost profit
								?>
								</td>
								<?php
								$sql_moh="select * from cost_moh where id_moh='".$rs_cost_detail['id_moh']."'";
								$res_moh=mysql_query($sql_moh) or die ('Error '.$sql_moh);
								while($rs_moh=mysql_fetch_array($res_moh)){
								?>
								<td style="text-align: right;"><?php echo number_format($rs_moh['moh_mixing'],2)?></td>
								<td style="text-align: right;"><?php echo number_format($rs_moh['moh_ packing'],2)?></td>
								<?php } //end query moh?>
								<td style="text-align: right;">
								<?php
								$sql_moq="select * from cost_moq where id_moq='".$rs_cost_detail['id_moq']."'";
								$res_moq=mysql_query($sql_moq) or die ('Error '.$sql_moq);
								while($rs_moq=mysql_fetch_array($res_moq)){
									echo number_format($rs_moq['moq'],2);
								} //end query moq
								?>
								</td>
							</tr>
							<?php } //end query cost detail?>
							<tr>
								<td colspan="5" class="center" style="font-weight: bold;">***** In case of customer would like to screen cap, the MOQ of screen cap is 500,000 pcs. *****</td>
							</tr>
						</table>
						<table cellpadding="0" cellspacing="0">
							<tr>
								<td colspan="5" style="background: #9148C8; text-align: center;font-weight: bold; font-size: 1em;">Edible gel and Instant drink Cost</td>
							</tr>
							<tr>
								<td colspan="5" style="background: #BAABCD; font-weight: bold;">MOH and Profit rate</td>
							</tr>
							<tr>
								<td class="center">Group</td>
								<td class="center">Weight per sachet (gm)</td>
								<td class="center">Profit rate (baht per sachet)</td>
								<td class="center">MOH mixing + packing (baht per sachet)</td>
								<td class="center">MOQ (sachet per batch)</td>
							</tr>
							<?php
							$i=0;
							$sql_cost_detail="select * from cost_detail where id_type_product=4";
							$res_cost_detail=mysql_query($sql_cost_detail) or die ('Error '.$sql_cost_detail);
							while($rs_cost_detail=mysql_fetch_array($res_cost_detail)){
								$i++;
							?>
							<tr>					
								<td class="center"11/21/2013>
									<?php
									$sql_type_product="select * from type_product";
									$sql_type_product .=" where id_type_product='".$rs_cost_detail['id_type_product']."'";
									$sql_type_product .=" group by title";
									$res_type_product=mysql_query($sql_type_product) or die ('Error '.$sql_type_product);
									$rs_type_product=mysql_fetch_array($res_type_product);
									if($i<=1)
										echo $rs_type_product['title']
									?>
								</td>
								<td class="center"><?php echo $rs_cost_detail['weight']?></td>
								<td style="text-align: right;">
									<?php
									$sql_cost_profit="select * from cost_profit where id_profit='".$rs_cost_detail['id_profit']."'";
									$res_cost_profit=mysql_query($sql_cost_profit) or die ('Error '.$sql_cost_profit);
									while($rs_cost_profit=mysql_fetch_array($res_cost_profit)){
										echo number_format($rs_cost_profit['profit_rate'],2);
									} //end query cost profit
									?>
								</td>	
								<?php
								$sql_moh="select * from cost_moh where id_moh='".$rs_cost_detail['id_moh']."'";
								$res_moh=mysql_query($sql_moh) or die ('Error '.$sql_moh);
								while($rs_moh=mysql_fetch_array($res_moh)){
									$sum=$rs_moh['moh_mixing']+$rs_moh['moh_ packing'];
								?>
								<td style="text-align: right;"><?php echo number_format($sum,2)?></td>
								<?php } //end query moh?>
								<td style="text-align: right;">
									<?php
									$sql_moq="select * from cost_moq where id_moq='".$rs_cost_detail['id_moq']."'";
									$res_moq=mysql_query($sql_moq) or die ('Error '.$sql_moq);
									while($rs_moq=mysql_fetch_array($res_moq)){
										echo number_format($rs_moq['moq'],2);
									} //end query moq
									?>
								</td>
							</tr>
							<?php  }//end query cost detail
							$j=0;
							$sql_cost_detail="select * from cost_detail where id_type_product=5";
							$res_cost_detail=mysql_query($sql_cost_detail) or die ('Error '.$sql_cost_detail);
							while($rs_cost_detail=mysql_fetch_array($res_cost_detail)){
								$j++;
							?>
							<tr>					
								<td class="center">
								<?php
								$sql_type_product="select * from type_product";
								$sql_type_product .=" where id_type_product='".$rs_cost_detail['id_type_product']."'";
								$sql_type_product .=" group by id_type_product";
								$res_type_product=mysql_query($sql_type_product) or die ('Error '.$sql_type_product);
								$rs_type_product=mysql_fetch_array($res_type_product);
								if($j<=1)
									echo $rs_type_product['title'];								
								?>
								</td>
								<td class="center"><?php echo $rs_cost_detail['weight']?></td>
								<td style="text-align: right;">
									<?php
									$sql_cost_profit="select * from cost_profit where id_profit='".$rs_cost_detail['id_profit']."'";
									$res_cost_profit=mysql_query($sql_cost_profit) or die ('Error '.$sql_cost_profit);
									while($rs_cost_profit=mysql_fetch_array($res_cost_profit)){
										echo number_format($rs_cost_profit['profit_rate'],2);
									} //end query cost profit
									?>
								</td>	
								<?php
								$sql_moh="select * from cost_moh where id_moh='".$rs_cost_detail['id_moh']."'";
								$res_moh=mysql_query($sql_moh) or die ('Error '.$sql_moh);
								while($rs_moh=mysql_fetch_array($res_moh)){
									$sum=$rs_moh['moh_mixing']+$rs_moh['moh_ packing'];
								?>
								<td style="text-align: right;"><?php echo number_format($sum,2)?></td>
								<?php } //end query moh?>
								<td style="text-align: right;">
									<?php
									$sql_moq="select * from cost_moq where id_moq='".$rs_cost_detail['id_moq']."'";
									$res_moq=mysql_query($sql_moq) or die ('Error '.$sql_moq);
									while($rs_moq=mysql_fetch_array($res_moq)){
										echo number_format($rs_moq['moq'],2);
									} //end query moq
									?>
								</td>
							</tr>
							<?php  }//end query cost detail?>
							<tr>
								<td colspan="5" class="center">
									<p style="font-weight: bold;">In case of customer would like to screen sachet 1 color, the MOQ of screen sachet is 100,000 pcs.</p>
									<p style="font-weight: bold;">In case of customer would like to screen sachet 4 color, the MOQ of screen sachet is 200,000 pcs.</p>
								</td>
							</tr>
						</table>
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
