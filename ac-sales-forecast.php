<?php
ob_start();
@session_start();
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
$date1=date("Y-m-d");
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
<script language="javascript">
function fncSubmit(){
	document.frm.submit();
}   
</script>
</head>
<body>
	<?php include("menu.php");?>
	<div class="row">
		<div class="background">
			<?php
			include("connect/connect.php");
			$id=$_REQUEST['id_p'];
			if($_GET['st']=='po'){$mode_fr='po';}
			elseif($_GET['st']=='cr'){$mode_fr='cr';}
				
			$month=$_GET["month"];
			$zero=$month;

			$year=$_GET['year'];
			/*$sql="select * from sm_sales_forecast where month_visited='".$zero."'";
			$sql .=" and year_visited='".$year."'";
			$res=mysql_query($sq) or die ('Error '.$sql);
			$rs=mysql_fetch_array($res);*/
				
			$month=date('F', mktime(0, 0, 0, $_REQUEST["month"]));
			$mode = $mode_fr.' forecast '.$month.'&nbsp;'.$_REQUEST["year"];         
			
			
			//*** Add Condition ***//
			if($_POST["hdnCmd"] == "Add"){
				$date=date('Y-m-d');
				
				if($_POST['po_reject']){$po_reject=1;}else{$po_reject=0;}
				
				if($_POST['total_grand']==0){$total_grand=$_POST['total'];}
				else{$total_grand=$_POST['total_grand'];}

				// ZAMACHITA meeting
				$post_product_name = mysql_real_escape_string($_POST['product_name']);


				$sql="insert into sm_sales_forecast(month_visited,year_visited";
				$sql .=",id_customer,po_no,id_product,product_name,quantities";
				$sql .=",price_per_baht,total,close_lot,total_grand,note";
				$sql .=",po_reject,create_by,create_date,status_forecast)";
				$sql .=" values('".$_POST["month"]."'";
				$sql .=",'".$_POST["year"]."','".$_POST["id_company"]."'";
				$sql .=",'".$_POST["po_no"]."','".$_POST['id_product']."'";
				$sql .=",'".$post_product_name."'";
				$sql .=",'".$_POST['quantities']."','".$_POST['price_per_baht']."'";
				$sql .=",'".$_POST['total']."','".$_POST['num_lot']."'";
				$sql .=",'".$total_grand."','".$_POST['note']."'";
				$sql .=",'".$po_reject."','".$rs_account['id_account']."'";
				$sql .=",'".$date."','".$_POST['forecast']."')";
				$res = mysql_query($sql) or die ('Error '.$sql);
			}

			//*** Update Condition ***//
			if($_POST["hdnCmd"] == "Update"){	
				if($_POST['po_reject2']){$po_reject=1;}else{$po_reject=0;}

				if($_POST['pm2']==''){$id_pm=0;}else{$id_pm=$_POST['id_pm2'];}
				$id_q=$_POST["hdnEdit"];
				$month=$_POST['month2'];
				$year=$_POST['year2'];
				$st=$_POST['st2'];

				if($_POST['total_grand2']==0){$total_grand2=$_POST['total2'];}
				else{$total_grand2=$_POST['total_grand2'];}

				// ZAMACHITA meeting
				$post_product_name2 = mysql_real_escape_string($_POST['product_name2']);

				$sql = "update sm_sales_forecast set month_visited= '".$_POST['month2']."'";
				$sql .=",year_visited= '".$_POST['year2']."',id_customer='".$_POST['id_company2']."'";
				$sql .=",po_no='".$_POST["po_no2"]."',id_product='".$_POST['id_product2']."'";
				$sql .=",product_name='".$post_product_name2 ."'";
				$sql .=",quantities='".$_POST['quantities2']."',total='".$_POST['total2']."'";
				$sql .=",price_per_baht='".$_POST['price_per_baht2']."'";
				$sql .=",close_lot='".$_POST['num_lot2']."'";
				$sql .=",total_grand='".$total_grand2."'";
				$sql .=",note='".$_POST['note2']."',po_reject='".$po_reject."'";
				$sql .=",status_forecast='".$_POST['st2']."'";
				$sql .=" where id_sales_forecast = '".$_POST["hdnEdit"]."' ";
				$res = mysql_query($sql) or die ('Error '.$sql);
			?>
				<script>
					window.location.href='ac-sales-forecast.php?id_u=<?=$id_q?>&month=<?=$month?>&year=<?=$year?>&st=<?=$st?>';
				</script>
			<?php 
			}

			//*** Delete Condition ***//
			if($_GET["action"] == "del"){
				$sql = "delete from sm_sales_forecast ";
				$sql .="where id_sales_forecast = '".$_GET["id_p"]."'";
				$res = mysql_query($sql) or die ('Error '.$sql);
				//header("location:$_SERVER[PHP_SELF]");
				//exit();
			}?>
			<form name="frm" method="post" action="">
			<input type="hidden" name="hdnCmd" value="">
			<input type="hidden" name="forecast" value="<?php echo $mode_fr?>">
			<table style="border: 0; width: 100%;" cellpadding="0" cellspacing="0">
				<tr>
					<td class="b-bottom" colspan='5'><div class="large-4 columns"><h4>Sales Forecast >> <?php echo $mode?></h4></div></td>
				</tr>
				<tr>
					<td style="background: #fff;">
						<div class="large-4 columns">						
							<input type="hidden" name="mode" value="<?php echo $id?>">
							<table style="border: 1px solid #eee; width: 100%;" cellpadding="0" cellspacing="0" id="tb-quotation">
								<tr>
									<td class="bd-right center top b-bottom" style="width:8%;">Customer</td>
									<?php if($mode_fr=='cr'){?><td class="bd-right center top b-bottom" style="width:10%;">PO.No.</td><?php }?>
									<td class="bd-right center top b-bottom" style="width:8%;">Product</td>
									<td class="bd-right center top b-bottom" style="width:8%;">Quantities<br>(Unit)</td>
									<td class="bd-right center top b-bottom" style="width:10%;">Price Per Unit(Baht)</td>
									<td class="bd-right center top b-bottom" style="width:10%;">Total</td>
									<?php if($mode_fr=='cr'){?>
									<td class="bd-right center top b-bottom" style="width:10%;">ปิด Lot(%)</td>
									<td class="bd-right center top b-bottom" style="width:10%;">Total Grand</td>
									<?php }?>
									<td class="center top b-bottom bd-right" style="width:10%;">Note</td>
									<td class="top b-bottom bd-right" style="width:10%;">Reject</td>
									<?php  if($rs_account['role_user']!=3){?><td class="top b-bottom bd-right">Create by</td><?php }?>
									<?php  if(($rs_account['role_user']==3) || ($rs_account['role_user']==1)){?><td class="top b-bottom">Action</td><?php }?>
								</tr>
								<?php
									if($rs_account['role_user']==3){$and1=" and create_by='".$rs_account['id_account']."'";}
									else{$and1=" ";}
									$sql_forecast="select * from sm_sales_forecast where ";
									$sql_forecast .=" month_visited='".$zero."'";
									$sql_forecast .=" and year_visited='".$year."'";
									$sql_forecast .=$and1;		
									$sql_forecast .=" and status_forecast='".$mode_fr."'";																
									$res_forecast=mysql_query($sql_forecast) or die('Error '.$sql_forecast);
								
									while($rs_forecast=mysql_fetch_array($res_forecast)){										
									if($rs_forecast['id_sales_forecast'] == $_GET['id_p'] and $_GET["action"] == 'edit'){										
								?>								
								<tr>
									<input type="hidden" name="hdnEdit" value="<?php echo $rs_forecast['id_sales_forecast']?>">
									<script type="text/javascript" src="js/js-autocomplete/js/jquery-1.4.2.min.js"></script> 
									<script type="text/javascript" src="js/js-autocomplete/js/jquery-ui-1.8.2.custom.min.js"></script> 
									<script type="text/javascript"> 
										jQuery(document).ready(function(){
											$('.company_name2').autocomplete({
												source:'return-sales-forecast.php',
												//minLength:2,
												select:function(evt, ui){
													this.form.id_company2.value = ui.item.id_company2;												
												}
											});
											$('.product_name2').autocomplete({
												source:'return-product.php', 
												//minLength:2,
												select:function(evt, ui){
													this.form.id_product2.value = ui.item.id_product2;
												}
											});
										});
										function fncTotal2(){
											document.frm.total2.value=(parseFloat(document.frm.price_per_baht2.value) * parseFloat(document.frm.quantities2.value)).toFixed(3);	
										}
										function fncLot2(){
											document.frm.total_grand2.value=(parseFloat(document.frm.total2.value) * (parseFloat(document.frm.num_lot2.value)/100)).toFixed(3);	
										}
									</script> 
									<link rel="stylesheet" href="js/js-autocomplete/css/smoothness/jquery-ui-1.8.2.custom.css" /> 
									<input type="hidden" name="month2" value="<?php echo $zero?>">
									<input type="hidden" name="year2" value="<?php echo $rs_forecast["year_visited"]?>">
									<input type="hidden" name="st2" value="<?php echo $rs_forecast["status_forecast"]?>">
									<td class="bd-right b-bottom top">
										<?php 
											$sql_company2="select * from company where id_company='".$rs_forecast['id_customer']."'";
											$res_company2=mysql_query($sql_company2) or die ('Error '.$sql_company2);
											$rs_company2=mysql_fetch_array($res_company2);
										?>
										<input type="hidden" name="id_company2" id="id_company2" value="<?php echo $rs_company2['id_company']?>">
										<input name="company_name" type="text" id="company_name2" class="company_name2" value="<?php echo $rs_company2['company_name']?>">
									</td>	
									<?php if($mode_fr=='cr'){?><td class="bd-right b-bottom top"><input type="text" name="po_no2" value="<?php echo $rs_forecast['po_no']?>"></td><?php }?>
									<td class="bd-right b-bottom top">
										<?php 
											$sql_product="select * from product where id_product='".$rs_forecast['id_product']."'";
											$res_product=mysql_query($sql_product) or die ('Error '.$sql_product);
											$rs_product=mysql_fetch_array($res_product);
											?>
											<input type="hidden" name="id_product2" id="id_product2" value="<?php echo $rs_product['id_product']?>">
											<input type="text" name="product_name2" id="product_name2" class="product_name2" value="<?php if($rs_forecast['product_name']==''){echo $rs_product['product_name'];}else{echo $rs_forecast['product_name'];}?>">
									</td>	
									<td class="b-bottom bd-right"><input type="text" name="quantities2" id="quantities2" value="<?php echo $rs_forecast['quantities']?>" OnChange="fncTotal2();"></td>
									<td class="b-bottom bd-right"><input type="text" name="price_per_baht2" id="price_per_baht2" value="<?php echo $rs_forecast['price_per_baht']?>" OnChange="fncTotal2();"></td>
									<td class="b-bottom bd-right"><input type="text" name="total2" id="total2" value="<?php echo $rs_forecast['total']?>"></td>
									<?php if($mode_fr=='cr'){?>
									<td class="b-bottom bd-right"><input type="text" name="num_lot2" id="num_lot2" value="<?php echo $rs_forecast['close_lot']?>" OnChange="fncLot2();"></td>
									<td class="b-bottom bd-right"><input type="text" name="total_grand2" id="total_grand2" value="<?php echo $rs_forecast['total_grand']?>"></td>
									<?php }?>
									<td class="b-bottom bd-right"><input type="text" name="note2" id="note2" value="<?php echo $rs_forecast['note']?>"></td>
									<td class="b-bottom bd-right"><input type="checkbox" name="po_reject2" <?php if($rs_forecast['po_reject']==1){echo 'checked';}?>></td>
									<?php  if(($rs_account['role_user']==3) || ($rs_account['role_user']==1)){?>
									<td class="b-bottom">
										<input name="btnAdd" type="button" id="btnUpdate" value="Update" OnClick="frm.hdnCmd.value='Update';JavaScript:return fncSubmit();" class="btn-update">
										<input name="btnAdd" type="button" id="btnCancel" value="Cancel" OnClick="window.location='<?=$_SERVER["PHP_SELF"].'?month='.$rs_forecast['month_visited'].'&year='.$rs_forecast['year_visited'].'&st='.$mode_fr?>';" class="btn-cancel">
									</td>
									<?php }?>
								</tr>
								<?php }else{?>
								<?php 
									if($rs_forecast['po_reject']==1){$style_reject='<span style="color:red;">';$style_end_reject='</span">';}
									else{$style_reject='';$style_end_reject='';}
								?>
								<input type="hidden" name="month" value="<?php echo $zero?>">
								<input type="hidden" name="year" value="<?php echo $_REQUEST["year"]?>">
								<input type="hidden" name="st" value="<?php echo $_REQUEST["st"]?>">
								<tr>
									<td class="bd-right b-bottom">
										<?php 
										$sql_company2="select * from company where id_company='".$rs_forecast['id_customer']."'";
										$res_company2=mysql_query($sql_company2) or die ('Error '.$sql_company2);
										$rs_company2=mysql_fetch_array($res_company2);
										echo $style_reject.$rs_company2['company_name'].$style_end_reject;
										?>
									</td>	
									<?php if($mode_fr=='cr'){?><td class="bd-right b-bottom"><?php echo $style_reject.$rs_forecast['po_no'].$style_end_reject;?></td><?php }?>
									<td class="bd-right b-bottom">
										<?php 
										if($rs_forecast['id_product']!=0){
											$sql_product2="select * from product where id_product='".$rs_forecast['id_product']."'";
											$res_product2=mysql_query($sql_product2) or die ('Error '.$sql_product2);
											$rs_product2=mysql_fetch_array($res_product2);
											echo $style_reject.$rs_product2['product_name'].$style_end_reject;
										}else{echo $style_reject.$rs_forecast['product_name'].$style_end_reject;}
										?>
									</td>
									<td class="center bd-right b-bottom" style="text-align:right;"><?php echo $style_reject;echo number_format($rs_forecast['quantities'],2);echo $style_end_reject;?></td>
									<td class="bd-right b-bottom" style="text-align:right;"><?php echo $style_reject;echo number_format($rs_forecast['price_per_baht'],2);echo $style_end_reject;?></td>
									<td class="bd-right b-bottom" style="text-align:right;"><?php echo $style_reject;echo number_format($rs_forecast['total'],2);echo $style_end_reject;?></td>	
									<?php if($mode_fr=='cr'){?>
									<td class="bd-right b-bottom" style="text-align:right;"><?php echo $style_reject;echo number_format($rs_forecast['close_lot'],2);echo $style_end_reject;?></td>	
									<td class="bd-right b-bottom" style="text-align:right;"><?php if($rs_forecast['total_grand']==0){echo $style_reject;echo number_format($rs_forecast['total'],2);echo $style_end_reject;}else{echo $style_reject;echo number_format($rs_forecast['total_grand'],2);echo $style_end_reject;}?></td>	
									<?php }?>
									<td class="bd-right b-bottom"><?php echo $style_reject;echo$rs_forecast['note'];echo$style_end_reject;?></td>
									<td class="b-bottom bd-right"><?php if($rs_forecast['po_reject']==1){echo '<img src="img/accepted-red.png">';}else{echo '';}?></td>
									<?php if($rs_account['role_user']!=3){?>
									<td class="top b-bottom bd-right">
										<?php
										$sql_acc="select * from account where id_account='".$rs_forecast['create_by']."'";
										$res_acc=mysql_query($sql_acc) or die ('Error '.$sql_acc);
										$rs_acc=mysql_fetch_array($res_acc);
										echo $rs_acc['username'];
										?>
									</td>
									<?php }?>
									<?php  if(($rs_account['role_user']==3) || ($rs_account['role_user']==1)){?>
									<td class="b-bottom bd-right" style="padding:0 0 0 1%;">
										<a href="<?=$_SERVER["PHP_SELF"];?>?action=edit&id_p=<?=$rs_forecast['id_sales_forecast'].'&month='.$rs_forecast['month_visited'].'&year='.$rs_forecast['year_visited'].'&st='.$mode_fr;?>"><img src="img/edit.png" style="width:20px;"></a>
										<a href="JavaScript:if(confirm('Confirm Delete?')==true){window.location='<?=$_SERVER["PHP_SELF"];?>?action=del&id_p=<? echo $rs_forecast['id_sales_forecast'].'&month='.$rs_forecast['month_visited'].'&year='.$rs_forecast['year_visited'].'&st='.$mode_fr;?>';}"><img src="img/trash_green.png" style="width:20px;"></a>
									</td>
									<?php }?>
								</tr>
								<?php }  
									}//end while itenary
								?>
								<script type="text/javascript" src="js/js-autocomplete/js/jquery-1.4.2.min.js"></script> 
								<script type="text/javascript" src="js/js-autocomplete/js/jquery-ui-1.8.2.custom.min.js"></script> 
								<script type="text/javascript"> 
									jQuery(document).ready(function(){
										$('.company_name').autocomplete({
											source:'return-sales-forecast.php', 
											//minLength:2,
											select:function(evt, ui){
												this.form.id_company.value = ui.item.id_company;
											}
										});
										$('.product_name').autocomplete({
											source:'return-product.php', 
											//minLength:2,
											select:function(evt, ui){
												this.form.id_product.value = ui.item.id_product;
											}
										});
									});
									function fncTotal(){
										document.frm.total.value=(parseFloat(document.frm.price_per_baht.value) * parseFloat(document.frm.quantities.value)).toFixed(2);	
									}
									function fncLot(){
										document.frm.total_grand.value=(parseFloat(document.frm.total.value) * (parseFloat(document.frm.num_lot.value)/100)).toFixed(2);	
									}
								</script> 
								<link rel="stylesheet" href="js/js-autocomplete/css/smoothness/jquery-ui-1.8.2.custom.css" /> 								
								<tr>
									<input type="hidden" name="month" value="<?php echo $zero?>">
									<input type="hidden" name="year" value="<?php echo $_REQUEST["year"]?>">
									<td class="bd-right b-bottom top w15">
										<input type="hidden" name="id_company" id="id_company">
										<input name="company_name" type="text" id="company_name" class="company_name">
									</td>	
									<?php if($mode_fr=='cr'){?><td class="bd-right b-bottom top w15"><input type="text" name="po_no" value="<?php echo $rs_forecast['po_no']?>"></td><?php }?>
									<td class="bd-right b-bottom top w20">
										<?php 											
											$sql_product="select * from product where id_product='".$rs_forecast['id_product']."'";
											$res_product=mysql_query($sql_product) or die ('Error '.$sql_product);
											$rs_product=mysql_fetch_array($res_product);
										?>
											<input type="hidden" name="id_product" id="id_product" value="<?php echo $rs_product['id_product']?>">
											<input type="text" name="product_name" id="product_name" class="product_name" value="<?php echo $rs_product['product_name']?>">
									</td>	
									<td class="b-bottom bd-right"><input type="text" name="quantities" id="quantities" OnChange="fncTotal();"></td>
									<td class="b-bottom bd-right"><input type="text" name="price_per_baht" id="price_per_baht" OnChange="fncTotal();"></td>
									<td class="b-bottom bd-right"><input type="text" name="total" id="total"></td>
									<?php if($mode_fr=='cr'){?>
									<td class="b-bottom bd-right"><input type="text" name="num_lot" id="num_lot" OnChange="fncLot();"></td>
									<td class="b-bottom bd-right"><input type="text" name="total_grand" id="total_grand"></td>
									<?php }?>
									<td class="b-bottom bd-right"><input type="text" name="note"></td>									
									<td class="b-bottom" colspan="2"><input type="checkbox" name="po_reject"></td>									
								</tr>								
								<tr>
									<td class="center top" colspan="11"><input name="btnAdd" type="button" id="btnAdd" title="Add" value="Add"  OnClick="frm.hdnCmd.value='Add';JavaScript:return fncSubmit();" class="btn-new2"></td>
									</td>
								</tr>
								<tr>
									<td <?php if($mode_fr=='cr'){?>colspan="7"<?php }else{?>colspan="4"<?php }?>style="text-align:right;font-weight:bold;font-size:14px;">Total : </td>
									<td colspan="5" style="font-weight:bold;font-size:14px;">
										<?php 
										$month2=$_REQUEST["month"];
										if($rs_account['role_user']==3){$account=$rs_account['id_account'];$and="and create_by='".$account."'";}
										else{$and='';}
										$sql_total="select sum(total_grand) as total_grand,sum(total) as total from sm_sales_forecast";
										$sql_total .=" where status_forecast='".$mode_fr."' ";
										$sql_total .=" and month_visited='".$month2."' and year_visited='".$year."'";
										$sql_total .=$and;
										$res_total=mysql_query($sql_total) or die ('Error '.$sql_total);
										$rs_total=mysql_fetch_array($res_total);
										if($rs_total['total_grand']==0){echo number_format($rs_total['total'],2);}
										else{echo number_format($rs_total['total_grand'],2);}
										?>
									</td>
								</tr>
							</table>	
						</div>
					</td>
				</tr>
			</table>
			</form>
		</div>
	</div>

  <!--<script>
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
  
  <!--<script>
    $(document).foundation();
  </script>-->
</body>
</html>
