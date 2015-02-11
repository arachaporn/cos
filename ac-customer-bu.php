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
<script type="text/javascript" src="js/autocomplete.js"></script>
<link rel="stylesheet" href="js/autocomplete.css"  type="text/css"/>
<script language="javascript">
function fncSubmit()
{
	document.frm.submit();
}
</script>
</head>
<body>
	<?php include("menu.php");?>
	<div class="row">
		<div class="background">
			<?php
			if($_GET["id_u"]=='New'){
				$mode='New';
				$button='Save';
				$id='New';
			}
			else{
				$id=$_GET["id_u"];
				$sql="select * from customer_business where id_customer_bu='".$id."'";
				$res=mysql_query($sql) or die ('Error '.$sql);
				$rs=mysql_fetch_array($res);
				$mode='Edit '.$rs['name_bu'].'&nbsp;&nbsp;'.$rs['surname'];
				$button='Update';
			}
			?>
			<form name="frm" method="post" action="db-customer-bu.php">
			<input type="hidden" name="hdnCmd" value="">
			<table style="border: 0; width: 100%;" cellpadding="0" cellspacing="0">
				<tr>
					<td class="b-bottom"><div class="large-4 columns"><h4><h4>Customer Business Evaluation >> <?php echo $mode;?></h4></h4></div></td>
				</tr>
				<tr>
					<td class="b-bottom">
						<div class="large-4 columns">
							<?php 
							if(!is_numeric($id)){
							?>
							<input type="button" name="save_data" id="save_data" value="<?php echo $button?>" class="button-create" OnClick="frm.hdnCmd.value='save';JavaScript:return fncSubmit();">
							<?php }else{ ?>
							<input type="button" name="update_data" value="<?php echo $button?>" class="button-create" OnClick="frm.hdnCmd.value='update_data';JavaScript:return fncSubmit();">
							<?php }?>
							<input type="button" value="Close" class="button-create" <?php if($_GET["dep"==2]){?>onclick="window.location.href='customer-bu.php?dep=2'"<?php }else{?>onclick="window.location.href='customer-bu.php'"<?php }?>>
						</div>
					</td>
				</tr>
				<tr>
					<td style="background: #fff;">
						<div class="large-4 columns">						
							<input type="hidden" name="mode" value="<?php echo $id?>">
							<table style="border: none; width: 100%;" cellpadding="0" cellspacing="0" id="tb-add">
								<tr>
									<td class="w20">ชื่อ</td>
									<td class="w20"><input type="text" name="name_bu" value="<?php echo $rs['name_bu']?>"></td>
									<td class="w15">นามสกุล</td>
									<td><input type="text" name="surname" value="<?php echo $rs['surname']?>"></td>
								</tr>
								<tr>
									<td>ชื่อบริษัท</td>
									<td><input type="text" name="company_name" value="<?php echo $rs['company_name']?>"></td>
									<td>ประเภทธุรกิจ</td>
									<td>
										<select name="company_cate">
										<?php
										$sql_cate="select * from company_category";
										$res_cate=mysql_query($sql_cate) or die ('Error '.$sql_cate);
										while($rs_cate=mysql_fetch_array($res_cate)){
										?>	
											<option value="<?php echo $rs_cate['id_com_cat']?>" <?php if($rs['id_com_cat']==$rs_cate['id_com_cat']){echo 'selected';}?>><?php echo $rs_cate['title']?></option>
										<?php }?>
										</select>
									</td>
								</tr>
								<tr>
									<td>ช่องทางที่รู้จัก</td>
									<td colspan="3">
										<?php $channel_cdip=split(",",$rs['channel_cdip']);?>
										<input type="checkbox" name="channel_cdip[]" <?php if(in_array('1',$channel_cdip)){echo 'checked';}?> value="1">Fanpage
										<input type="checkbox" name="channel_cdip[]" <?php if(in_array('2',$channel_cdip)){echo 'checked';}?> value="2">Website
										<input type="checkbox" name="channel_cdip[]" <?php if(in_array('3',$channel_cdip)){echo 'checked';}?> value="3">Booth Exibitior<br>
										<input type="checkbox" name="channel_cdip[]" <?php if(in_array('4',$channel_cdip)){echo 'checked';}?> value="4">Advertising/Newspaper
										<input type="checkbox" name="channel_cdip[]" <?php if(in_array('5',$channel_cdip)){echo 'checked';}?> value="5">TV Program
										<input type="checkbox" name="channel_cdip[]" <?php if(in_array('6',$channel_cdip)){echo 'checked';}?> value="6">Agency Recommend<br>		<div style="float:left;"><input type="checkbox" name="channel_cdip[]" <?php if(in_array('7',$channel_cdip)){echo 'checked';}?> value="7">Partner Recommend
										<input type="checkbox" name="channel_cdip[]" <?php if(in_array('0',$channel_cdip)){echo 'checked';}?> value="0">Other</div><div style="float:left;"><input type="text" name="channel_other"></div>
									</td>
								</tr>
								<tr>
									<td>Key word ที่ทางลูกค้าใช้ค้นหาทาง Web site </td>
									<td colspan="3"><textarea name="keyword_web"><?php echo $rs['keyword_web']?></textarea></td>
								</tr>
								<tr>
									<td>เหตุผลความสนใจในธุรกิจอาหารเสริม เหตผลที่เริ่มตั้งธุรกิจสุขภาพ</td>
									<td colspan="3"><textarea name="reason_business"><?php echo $rs['reason_business']?></textarea></td>
								</tr>
								<tr>
									<td>ทุนจดทะเบียนบริษัท</td>
									<td><input type="text" name="enroll" value="<?php echo $rs['enroll']?>"></td>
								</tr>
								<tr>
									<td>สินค้าที่จะขาย</td>
									<td colspan="3">
										<input type="radio" name="product" <?php if($rs['id_product_appearance']==1){echo 'checked';}?> value="1">Tablet&nbsp;&nbsp;
										<input type="radio" name="product" <?php if($rs['id_product_appearance']==2){echo 'checked';}?> value="2">Hard Capsule&nbsp;&nbsp;
										<input type="radio" name="product" <?php if($rs['id_product_appearance']==3){echo 'checked';}?> value="3">Soft Gelatin Capsule&nbsp;&nbsp;
										<input type="radio" name="product" <?php if($rs['id_product_appearance']==4){echo 'checked';}?> value="4">Instant Drink&nbsp;&nbsp;
										<input type="radio" name="product" <?php if($rs['id_product_appearance']==5){echo 'checked';}?> value="5">Edible Gel&nbsp;&nbsp;
										<input type="radio" name="product" <?php if($rs['id_product_appearance']==6){echo 'checked';}?> value="6">Functional Drink&nbsp;&nbsp;
										<input type="radio" name="product" <?php if($rs['id_product_appearance']==7){echo 'checked';}?> value="7">Gummy&nbsp;&nbsp;
										<input type="radio" name="product" <?php if($rs['id_product_appearance']==8){echo 'checked';}?> value="8">Cosmetic&nbsp;&nbsp;
										<input type="radio" name="product" <?php if($rs['id_product_appearance']==9){echo 'checked';}?> value="9">FDA Regulation Service&nbsp;&nbsp;
									</td>								
								</tr>
								<tr>
									<td colspan="3">
									<table style="width:100%;text-align:left;border:none;" cellpadding="0" cellspacing="0">
										<tr>
											<td colspan="2" style="padding:0 0 1% 0;font-size:12px;">ฟังก์ชั่นการทำงาน</td>
										</tr>
												<?php
												$roc_func=split(",",$rs['id_roc_func']);
												$roc_group_func=split(",",$rs['id_group_product']);
												$roc_other_func=split(",",$rs['roc_func_other']);
												$i=0;
												$j=0;
												$sql_roc_group_product="select * from roc_group_product";
												$res_roc_group_product=mysql_query($sql_roc_group_product) or die ('Error '.$sql_roc_group_product);
												$max_row_g=mysql_num_rows($res_roc_group_product);
												while($rs_roc_group_product=mysql_fetch_array($res_roc_group_product)){
													$i++;
												?>
												<tr>
													<td style="padding:0 0 0 0.5%;margin:0;font-size:12px;"><input type="checkbox" name="roc_group_product[]" id="roc_group_product<?php echo $i?>" value="<?php echo $rs_roc_group_product['id_group_product']?>" <?php if(is_numeric($id)){if(in_array($rs_roc_group_product['id_group_product'],$roc_group_func)){echo 'checked';}}?> onclick="javascript:ShowFunc();">1.<?php echo $i.'&nbsp;'.$rs_roc_group_product['title'];?></td>
												</tr>
												<tr>
													<td>
														<table style="width:100%;border:none;font-size:14px;padding:0 0 1% 0;margin:0;"cellpadding="0" cellspacing="0">
														<?php;
														$i_function=0;
														$i_function2=0;
														$max_row_g2=0;
														$num=0;
														$sql_roc_function="select * from roc_function where id_group_product='".$rs_roc_group_product['id_group_product']."'";
														$res_roc_function=mysql_query($sql_roc_function) or die ('Error '.$sql_roc_function);
														$max_row=mysql_num_rows($res_roc_function);
														while($rs_roc_function=mysql_fetch_array($res_roc_function)){		
															$num++;	
															if($i_function % 3 == 0){?><tr><?php } //display row
															$i_function2++;
														?>
															<td style="width:33.33%;padding:1.0% 0 0 3%;"><input type="checkbox" class="checkbox" name="roc_function[]" id="roc_function<?php echo $rs_roc_function['id_roc_func']?>" rel="roc_group_product<?php echo $i?>" value="<?php echo $rs_roc_function['id_roc_func']?>" <?php if(in_array($rs_roc_function['id_roc_func'],$roc_func)){echo 'checked';}?>><?echo $rs_roc_function['title']?></td>
															<?php if($i_function2 % 3 == 0){?></tr><?php } //display end row?>
															<?php if($num==$max_row){  ?>
															<?php if($rs_roc_group_product['id_group_product']==1){?><tr><?php }?>
															<td style="width:33.33%;padding:1.0% 0 0 3%;"><div style="float:left; margin: 0 1% 0 0;"><input type="checkbox" class="checkbox" <?php if($rs_roc_group_product['id_group_product']==1){?>value="0"<?php }elseif($rs_roc_group_product['id_group_product']==2){?>value="-1"<?php }elseif($rs_roc_group_product['id_group_product']==3){?>value="-2"<?php }elseif($rs_roc_group_product['id_group_product']==4){?>value="-3"<?php }?> name="roc_function[]" <?php if($rs_roc_group_product['id_group_product']==1){if(in_array('0',$roc_func)){echo 'checked';}}elseif($rs_roc_group_product['id_group_product']==2){if(in_array('-1',$roc_func)){echo 'checked';}}elseif($rs_roc_group_product['id_group_product']==3){if(in_array('-2',$roc_func)){echo 'checked';}}elseif($rs_roc_group_product['id_group_product']==4){if(in_array('-3',$roc_func)){echo 'checked';}}?>></div><div style="float:left; margin: 0 0.5% 0 0;">อื่น ๆ</div><div style="float:left"><input type="text" name="roc_function_other[]" value="<?php echo $roc_other_func[$j];?>"></td>
															<?php if($rs_roc_group_product['id_group_product']==1){?></tr><?php }?>
															<?php $j++;$max_row_g2=$max_row_g+1;
															} $i_function++; 
															?>														
														<?php }//end while?>
														</table>
													</td>
												</tr>	
												<?php }//end while function
												$max_row_g=$max_row_g+1;
												?>
												<tr>
													<td style="font-size:12px;"><div style="float:left;margin: 0 0.5% 0 0;"><input type="checkbox" name="roc_group_product[]" id="roc_group_product<?php echo $i+1?>" <?php if(in_array(-1,$roc_group_func)){echo 'checked';}?> value="-1" onclick="javascript:ShowFunc();">1.<?php echo $max_row_g.'&nbsp;อื่น ๆ'?></div>
													<!--<?php if($rs['id_group_product']== -1){$style='';}else{$style='display: none;';}?>-->
													<div style="float:left;margin: 0 0.5% 0 0;"><input type="text" name="other_group_product" id="show_func<?php echo $i+1?>" value="<?php if(is_numeric($id)){echo $rs['other_group_product'];}?>"></div></td>
												</tr>
											</table>
									</td>
								</tr>
								<tr>
									<td>กลุ่มลูกค้าที่มุ่งหวัง</td>
									<td><input type="text" name="customer_group" value="<?php echo $rs['customer_group']?>"></td>
								</tr>
								<tr>
									<td>ช่องทางการขาย</td>
									<td><input type="text" name="market_sell" value="<?php echo $rs['market_sell']?>"></td>
								</tr>
								<tr>
									<td>Idea Price</td>
									<td><input type="text" name="idea_price" value="<?php echo $rs['idea_price']?>"></td>
								</tr>
								<tr>
									<td>เงินที่ใช้ลงทุนในส่วนการตลาด ส่วนดำเนินการ</td>
									<td colspan="3"><input type="text" name="capital" value="<?php echo $rs['capital']?>"></td>
								</tr>
								<tr>
									<td>ต้องการขายในช่วงใด</td>
									<td>
										<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
										<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
										<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
										<script>
											$(function() {
												$( "#datepicker" ).datepicker({
													showOn: "button",
													buttonImage: "img/calendar.gif",
													buttonImageOnly: true
												});
											});
										</script>
										<input type="text" id="datepicker" name="product_date" value="<?php 
											if(is_numeric($id)){
												list($ckyear,$ckmonth,$ckday) = split('[/.-]', $rs['product_date']); 
												echo $ckstart= $ckmonth . "/". $ckday . "/" .$ckyear;
											}else{echo date("m/d/Y");}?>" style="width: 50%; float: left; margin-right: 2%;"/>
									</td>
								</tr>
								<tr>
									<td>โครงการเหมาะสมกับบริษัทหรือไม่</td>
									<td colspan="2">
										<input type="radio" name="project_is" <?php if($rs['project_is']=='y'){echo 'checked';}?> value="y">เหมาะสม
										<input type="radio" name="project_is" <?php if($rs['project_is']=='n'){echo 'checked';}?> value="n">ไม่เหมาะสม<br>
										<textarea name="reason_project"><?php if($rs['project_is']=='n'){echo $rs['reason_project'];}?></textarea>
									</td>
								</tr>
							</table>
						</div>
					</td>
				</tr>
				<tr>
					<td class="b-top">
						<div class="large-4 columns">
							<?php 
							if(!is_numeric($id)){
							?>
							<input type="button" name="save_data" id="save_data" value="<?php echo $button?>" class="button-create" OnClick="frm.hdnCmd.value='save';JavaScript:return fncSubmit();">
							<?php }else{ ?>
							<input type="button" name="update_data" value="<?php echo $button?>" class="button-create" OnClick="frm.hdnCmd.value='update_data';JavaScript:return fncSubmit();">
							<?php }?>
							<input type="button" value="Close" class="button-create" onclick="window.location.href='customer-bu.php'">
						</div>
					</td>
				</tr>
			</table>
			</form>
		</div>
	</div>

<!--  <script>
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
  
  
  <script>
    $(document).foundation();
  </script>-->
</body>
</html>
