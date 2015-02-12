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
	?>
	<div class="row">
		<div class="background">
		<form name="frmMain" action="del-withdraw.php" method="post">
		<table style="border: 0; width: 100%;" cellpadding="0" cellspacing="0">
			<tr>
				<td class="b-bottom" style="text-align:left;">
					<div class="large-4 columns"><a href="../sd.php"><?php echo '< Administration'?></a></div>	
				</td>
				<td class="b-bottom"><div class="large-4 columns"><h4>บันทึกการเบิกอุปกรณ์อิเล็กทรอนิกส์</h4></div></td>
			</tr>
			<?php if($rs_account['id_department']==6){?>
			<tr>
				<td style="background: #fff;" colspan="3">
					<div class="large-4 columns">
						<script>
							$(document).ready(function(){
								$('.WorldphpTab>ul a').click( function(){
								  t = $(this).attr('href');
								  $('.WorldphpTab>ul li').removeClass('active');
								  $('.WorldphpTabData>div').removeClass('active');
								  $(this).parent().addClass('active'); 
								  $(t).addClass('active');
								});
							});
						</script>
						<!-- Tab -->
						<div class="WorldphpTab">
							<ul>
								<li class="active"><a href="#tab1">บันทึกการรับเข้า</a></li>
								<li><a href="#tab2">บันทึกการจ่ายออก</a></li>
							</ul>
							<div class="WorldphpTabData">
								<div id="tab1" class="active" style="font-size:1em;">
									<?php
									$sql="select * from sd_withdraw ";
									$res=mysql_query($sql) or die('Error '.$sql);
									$num_row = mysql_num_rows($res);

									$Per_Page = 20;   // Per Page

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

									$sql .=" order by id_sd_withdraw desc LIMIT $Page_Start , $Per_Page";
									$res = mysql_query($sql) or die ('Error '.$sql);
									?>
									<div class="large-4 columns" style="text-align: right;">
										<input type="button" value="Create" class="btn-new" onclick="window.location.href='ac-withdraw-device.php?id_u=New&get_device=1'" title="New">
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
									<table style="border: 1 solid #eee; width: 100%;" cellpadding="0" cellspacing="0">
										<tr class="top">
											<td width="2%"class="center"><input type="checkbox" name="ck_all" id="ck_all" value="Y" onclick="ckall(this);" style="margin:0;padding:0;"></td>
											<td width="5%">No.</td>
											<td width="15%" class="center">Date</td>
											<td width="15%" class="center">Device</td>
											<td width="20%" class="center">จำนวนที่รับเข้า</td>
											<td width="20%" class="center">จำนวนที่จ่ายออก</td>
											<td width="10%" class="center">จำนวนคงเหลือ</td>
											<td width="15%" class="center">ผู้รับ</td>
											<td width="15%" class="center">ผู้จ่าย</td>											
										</tr>
										<?php
										$i=0;
										$total=0;
										$b=0;
										$a=0;
										while($rs=mysql_fetch_array($res)){
											$i++;
										?>
										<tr>
											<script language="JavaScript">
												function chkdel(){
													if(confirm('Are you sure you want to delete?')){
														return true; // delete 
													}else{
														return false; // Cancel 
													}
												}
											</script>
											<td class="center"><input type="checkbox" name="ck_del[]" id="ck_del<?php echo $i?>" value="<?php echo $rs['id_break_down'];?>"></td>
											<td><?php echo $i;?></td>
											<td class="center">
											<?php
												list($ckyear,$ckmonth,$ckday) = split('[/.-]', $rs['recip_date']); 
												echo$date_get2= $ckday . "/". $ckmonth . "/" .$ckyear;
											?>
											</td>
											<td class="center">
												<?php 
												$sql_other_device="select * from type_other_device";
												$sql_other_device .=" where id_type_other_device='".$rs['id_type_other_device']."'";
												$res_other_device=mysql_query($sql_other_device) or die ('Error '.$sql_other_device);
												$rs_other_device=mysql_fetch_array($res_other_device);
												?>
												<a href="ac-withdraw-device.php?id_u=<?php echo $rs['id_sd_withdraw']?>&get_device=1"><?php echo $rs_other_device['type_other_device']?></td>	
											<td class="center"><?php echo $rs['device_quantity']?></td>														
											<td class="center"><?php echo $rs['withdraw_device']?></td>
											<td class="center"><?php echo $total=$rs['balance']?></td>
											<td class="center">
												<?php
												$sql_emp="select * from account where id_account='".$rs['id_recipients']."'";
												$res_emp=mysql_query($sql_emp) or die ('Error '.$sql_emp);
												$rs_emp=mysql_fetch_array($res_emp);
												echo $rs_emp['name'];
												?>
											</td>
											<td class="center">
												<?php
												$sql_emp="select * from account where id_account='".$rs['id_recipients']."'";
												$res_emp=mysql_query($sql_emp) or die ('Error '.$sql_emp);
												$rs_emp=mysql_fetch_array($res_emp);
												echo $rs_emp['name'];
												?>
											</td>
											
										</tr>
										<?php } ?>
										<input type="hidden" name="hdnCount" value="<?=$i;?>">
									</table>
									<div class="large-4 columns" style="padding: 2% 0;">Total <?= $num_row;?> Record 
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
								</div>
								<div id="tab2">
									<?php
									$sql="select * from sd_withdraw_account";
									$res=mysql_query($sql) or die('Error '.$sql);
									$num_row = mysql_num_rows($res);

									$Per_Page = 20;   // Per Page

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

									$sql .=" order by date_withdraw desc LIMIT $Page_Start , $Per_Page";
									$res = mysql_query($sql) or die ('Error '.$sql);
									?>
									<div class="large-4 columns" style="text-align: right;">
										<input type="button" value="Create" class="btn-new" onclick="window.location.href='ac-withdraw-device.php?id_u=New&get_device=2'" title="New">
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
									<table style="border: 1 solid #eee; width: 100%;" cellpadding="0" cellspacing="0">
										<tr class="top">
											<td width="2%"class="center"><input type="checkbox" name="ck_all" id="ck_all" value="Y" onclick="ckall(this);" style="margin:0;padding:0;"></td>
											<td width="3%">No.</td>
											<td width="10%">Date</td>
											<td width="10%">Device</td>
											<td width="10%">ผู้เบิก</td>
											<td width="10%">จำนวนที่เบิก</td>
											<td width="10%" class="center">จำนวนคงเหลือ</td>
										</tr>
										<?php
										$sum=0;
										$i=0;
										while($rs=mysql_fetch_array($res)){
											$i++;
										?>
										<tr>
											<script language="JavaScript">
												function chkdel(){
													if(confirm('Are you sure you want to delete?')){
														return true; // delete 
													}else{
														return false; // Cancel 
													}
												}
											</script>
											<td class="center"><input type="checkbox" name="ck_del[]" id="ck_del<?php echo $i?>" value="<?php echo $rs['id_break_down'];?>"></td>
											<td><?php echo $i;?></td>
											<td class="center">
												<?php
												list($ckyear,$ckmonth,$ckday) = split('[/.-]', $rs['date_withdraw']); 
												echo$date_get2= $ckday . "/". $ckmonth . "/" .$ckyear;
												?>
											</td>
											<td class="center">
												<?php
												$sql_other_device="select * from type_other_device";
												$sql_other_device .=" where id_type_other_device='".$rs['id_type_other_device']."'";
												$res_other_device=mysql_query($sql_other_device) or die ('Error '.$sql_other_device);
												$rs_other_device=mysql_fetch_array($res_other_device);
												?>
												<a href="ac-withdraw-device.php?id_u=<?php echo $rs['id_withdraw_account']?>&get_device=2"><?php echo $rs_other_device['type_other_device'];?>
											</td>
											<td class="center">
												<?php
												$sql_emp="select * from account";
												$sql_emp .=" where id_account='".$rs['id_account_withdraw']."'";
												$res_emp=mysql_query($sql_emp) or die ('Error '.$sql_emp);
												$rs_emp=mysql_fetch_array($res_emp);
												echo $rs_emp['name'];
												?>
											</td>
											<td class="center"><?php echo $rs['quantity']?></td>
											<td class="center">
												<?php 
												$sql_withdraw="select * from sd_withdraw";
												$sql_withdraw .=" where id_type_other_device='".$rs['id_type_other_device']."'";
												$res_withdraw=mysql_query($sql_withdraw) or die ('Error '.$sql_withdraw);
												$rs_withdraw=mysql_fetch_array($res_withdraw);
												echo $rs_withdraw['device_quantity']-$rs['quantity'];
												?>
											</td>
										</tr>
										<?php } ?>
										<input type="hidden" name="hdnCount" value="<?=$i;?>">
									</table>
									<div class="large-4 columns" style="padding: 2% 0;">Total <?= $num_row;?> Record 
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
								</div>
							</div>
						</div>
					</div>
				</td>
			</tr>
			<?php }else{?>
			<tr>
				<td style="background: #fff;" colspan="3">
					<?php
					$sql="select * from sd_withdraw_account where id_account_withdraw='".$rs_account['id_account']."'";
					$res=mysql_query($sql) or die('Error '.$sql);
					$num_row = mysql_num_rows($res);

					$Per_Page = 20;   // Per Page
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

					$sql .=" order by id_withdraw_account desc LIMIT $Page_Start , $Per_Page";
					$res = mysql_query($sql) or die ('Error '.$sql);
					?>
					<div class="large-4 columns" style="text-align: right;">
						<input type="button" value="Create" class="btn-new" onclick="window.location.href='ac-withdraw-device.php?id_u=New'" title="New">
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
					<table style="border: 1 solid #eee; width: 100%;" cellpadding="0" cellspacing="0">
						<tr class="top">
							<td width="2%"class="center"><input type="checkbox" name="ck_all" id="ck_all" value="Y" onclick="ckall(this);" style="margin:0;padding:0;"></td>
							<td width="5%">No.</td>							
							<td width="15%" class="center">อุปกรณ์</td>
							<td width="15%" class="center">จำนวน</td>
							<td width="15%" class="center">Date</td>
						</tr>
						<?php
						$i=0;
						$total=0;
						$b=0;
						$a=0;
						while($rs=mysql_fetch_array($res)){
							$i++;
						?>
						<tr>
							<script language="JavaScript">
								function chkdel(){
									if(confirm('Are you sure you want to delete?')){
										return true; // delete 
									}else{
										return false; // Cancel 
									}
								}
							</script>
							<td class="center"><input type="checkbox" name="ck_del[]" id="ck_del<?php echo $i?>" value="<?php echo $rs['id_withdraw_account'];?>"></td>
							<td><?php echo $i;?></td>							
							<td class="center">
								<?php 
								$sql_other_device="select * from type_other_device";
								$sql_other_device .=" where id_type_other_device='".$rs['id_type_other_device']."'";
								$res_other_device=mysql_query($sql_other_device) or die ('Error '.$sql_other_device);
								$rs_other_device=mysql_fetch_array($res_other_device);
								?>
								<a href="ac-withdraw-device.php?id_u=<?php echo $rs['id_sd_withdraw']?>&get_device=1"><?php echo $rs_other_device['type_other_device']?>
							</td>		
							<td class="center"><?php echo $rs['quantity']?></td>
							<td class="center">
								<?php
									list($ckyear,$ckmonth,$ckday) = split('[/.-]', $rs['date_withdraw']); 
									echo$date_get2= $ckday . "/". $ckmonth . "/" .$ckyear;
								?>
							</td>
						</tr>
						<?php }?>
						<input type="hidden" name="hdnCount" value="<?=$i;?>">
					</table>
					<div class="large-4 columns" style="padding: 2% 0;">Total <?= $num_row;?> Record 
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
			<?php }?>
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
