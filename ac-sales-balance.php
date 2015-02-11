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
			if($_REQUEST["date_visited"]){
				$date_visited=$_REQUEST["date_visited"];
				$month=$_REQUEST["month_visited"];
				$mode='Add data on '.$_REQUEST["date_visited"].'/'.$month.'/'.$_REQUEST["year_visited"];
			}
			else
			if($_REQUEST['id_p']){
				$id_balance=$_REQUEST['id_p'];
				$sql_balance2="select * from sm_sales_balance where id_sales_balance='".$id_balance."'";
				$res_balance2=mysql_query($sql_balance2) or die ('Error '.$sql_balance2);
				$rs_balance2=mysql_fetch_array($res_balance2);
				$month=$_REQUEST["month_visited"];
				$mode='Data on '.$rs_balance2['date_visited'].'/'.$month.'/'.$rs_balance2['year_visited'];
			}
			
			//*** Add Condition ***//
			if($_POST["hdnCmd"] == "Add"){
				$date=date('Y-m-d');
	
				if($_POST['pm']==''){$id_pm=0;}else{$id_pm=$_POST['id_pm'];}

				// ZAMACHITA meeting
				$post_product_name = mysql_real_escape_string($_POST['product_name']);
							
				$date_po=$_POST["year_visited"].'/'.$_POST["month_visited"].'/'.$_POST["date_visited"];			
				if($_POST['po_reject']){$po_reject=1;}else{$po_reject=0;}
				$sql="insert into sm_sales_balance(date_visited,month_visited,year_visited";
				$sql .=",id_customer,po_no,id_product,pre_production,quantities,product_unit";
				$sql .=",price_per_baht,total,project_manager,note,po_reject,create_by,create_date)";
				$sql .=" values('".$_POST["date_visited"]."','".$_POST["month_visited"]."'";
				$sql .=",'".$_POST["year_visited"]."','".$_POST["id_company"]."'";
				$sql .=",'".$_POST["po_no"]."','".$_POST['id_product']."'";
				$sql .=",'".$post_product_name."','".$_POST['quantities']."'";
				$sql .=",'".$_POST['product_unit']."','".$_POST['price_per_baht']."'";
				$sql .=",'".$_POST['total']."','".$id_pm  ."','".$_POST['note']."'";
				$sql .=",'".$po_reject."','".$rs_account['id_account']."','".$date."')";
				$res = mysql_query($sql) or die ('Error '.$sql);
			}

			//*** Update Condition ***//
			if($_POST["hdnCmd"] == "Update"){	
				if($_POST['po_reject2']){$po_reject=1;}else{$po_reject=0;}

				if($_POST['pm2']==''){$id_pm=0;}else{$id_pm=$_POST['id_pm2'];}

				// ZAMACHITA meeting
				$post_product_name2 = mysql_real_escape_string($_POST['product_name2']);

				$sql = "update sm_sales_balance set date_visited='".$_POST['date_visited2']."'";
				$sql .=",month_visited= '".$_POST['month_visited2']."'";
				$sql .=",year_visited= '".$_POST['year_visited2']."'";
				$sql .=",id_customer='".$_POST['id_company2']."',po_no='".$_POST["po_no2"]."'";
				$sql .=",id_product='".$_POST['id_product2']."'";
				$sql .=",pre_production='".$post_product_name2."'";
				$sql .=",product_unit='".$_POST['product_unit2']."'";
				$sql .=",quantities='".$_POST['quantities2']."',total='".$_POST['total2']."'";
				$sql .=",price_per_baht='".$_POST['price_per_baht2']."'";
				$sql .=",project_manager='".$id_pm."',note='".$_POST['note2']."'";
				$sql .=",po_reject='".$po_reject."'";
				$sql .=" where id_sales_balance = '".$_POST["hdnEdit"]."' ";
				$res = mysql_query($sql) or die ('Error '.$sql);
			}

			//*** Delete Condition ***//
			if($_GET["action"] == "del"){
				$sql = "delete from sm_sales_balance ";
				$sql .="where id_sales_balance = '".$_GET["id_p"]."'";
				$res = mysql_query($sql) or die ('Error '.$sql);
				//header("location:$_SERVER[PHP_SELF]");
				//exit();
			}?>
			<form name="frm" method="post" action="<?=$_SERVER["PHP_SELF"]."?id_u=".$id?>">
			<input type="hidden" name="hdnCmd" value="">
			<table style="border: 0; width: 100%;" cellpadding="0" cellspacing="0">
				<tr>
					<td class="b-bottom" colspan='5'><div class="large-4 columns"><h4>Sales Balance >> <?php echo $mode?></h4></div></td>
				</tr>
				<tr>
					<td style="background: #fff;">
						<div class="large-4 columns">						
							<input type="hidden" name="mode" value="<?php echo $id?>">
							<?php if($date_visited){?>
							<table style="border: 1px solid #eee; width: 100%;" cellpadding="0" cellspacing="0" id="tb-quotation">
								<tr>
									<td class="bd-right center top b-bottom" style="width:8%;">Customer</td>
									<td class="bd-right center top b-bottom" style="width:10%;">PO.No.</td>
									<td class="bd-right center top b-bottom" style="width:8%;">Product</td>
									<td class="bd-right center top b-bottom" style="width:8%;">Quantities<br>(Unit)</td>
									<td class="bd-right center top b-bottom" style="width:8%;">Unit</td>
									<td class="bd-right center top b-bottom" style="width:10%;">Price Per Unit(Baht)</td>
									<td class="bd-right center top b-bottom" style="width:10%;">Total</td>
									<td class="bd-right center top b-bottom" style="width:8%;">Project Manager</td>
									<td class="center top b-bottom bd-right" style="width:10%;">Note</td>
									<td class="top b-bottom bd-right" style="width:10%;">Reject</td>
									<td class="top b-bottom">Action</td>
								</tr>
								<?php
									$sql_balance="select * from sm_sales_balance where ";
									$sql_balance .=" date_visited='".$date_visited."' and month_visited='".$_REQUEST['month_visited']."'";
									$sql_balance .=" and year_visited='".$_REQUEST['year_visited']."'";
									$res_balance=mysql_query($sql_balance) or die('Error '.$sql_balance);
								
									while($rs_balance=mysql_fetch_array($res_balance)){										
									if($rs_balance['id_sales_balance'] == $_GET['id_p'] and $_GET["action"] == 'edit'){										
								?>								
								<tr>
									<input type="hidden" name="hdnEdit" value="<?php echo $rs_balance['id_sales_balance']?>">
									<script type="text/javascript" src="js/js-autocomplete/js/jquery-1.4.2.min.js"></script> 
									<script type="text/javascript" src="js/js-autocomplete/js/jquery-ui-1.8.2.custom.min.js"></script> 
									<script type="text/javascript"> 
										jQuery(document).ready(function(){
											$('.company_name2').autocomplete({
												source:'return-sales-balance.php',
												//minLength:2,
												select:function(evt, ui){
													this.form.id_company2.value = ui.item.id_company2;
													this.form.id_pm2.value = ui.item.id_pm2;
													this.form.pm2.value = ui.item.pm2;
												}
											});
											$('.product_name2').autocomplete({
												source:'return-product.php', 
												//minLength:2,
												select:function(evt, ui){
													this.form.id_product2.value = ui.item.id_product2;
												}
											});
											$('.pm2').autocomplete({
												source:'return-sales.php', 
												//minLength:2,
												select:function(evt, ui){
													this.form.id_pm2.value = ui.item.id_pm2;
											}
										});
										});
										function fncTotal2(){
											document.frm.total2.value=(parseFloat(document.frm.price_per_baht2.value) * parseFloat(document.frm.quantities2.value)).toFixed(3);	
										}
									</script> 
									<link rel="stylesheet" href="js/js-autocomplete/css/smoothness/jquery-ui-1.8.2.custom.css" /> 
									<input type="hidden" name="date_visited2" value="<?php echo $rs_balance["date_visited"]?>">
									<input type="hidden" name="month_visited2" value="<?php echo $rs_balance["month_visited"]?>">
									<input type="hidden" name="year_visited2" value="<?php echo $rs_balance["year_visited"]?>">
									<td class="bd-right b-bottom top">
										<?php 
											$sql_company2="select * from company where id_company='".$rs_balance['id_customer']."'";
											$res_company2=mysql_query($sql_company2) or die ('Error '.$sql_company2);
											$rs_company2=mysql_fetch_array($res_company2);
										?>
										<input type="hidden" name="id_company2" id="id_company2" value="<?php echo $rs_company2['id_company']?>">
										<input name="company_name" type="text" id="company_name2" class="company_name2" value="<?php echo $rs_company2['company_name']?>">
									</td>	
									<td class="bd-right b-bottom top"><input type="text" name="po_no2" value="<?php echo $rs_balance['po_no']?>"></td>
									<td class="bd-right b-bottom top">
										<?php 
											$sql_product="select * from product where id_product='".$rs_balance['id_product']."'";
											$res_product=mysql_query($sql_product) or die ('Error '.$sql_product);
											$rs_product=mysql_fetch_array($res_product);
											?>
											<input type="hidden" name="id_product2" id="id_product2" value="<?php echo $rs_product['id_product']?>">
											<input type="text" name="product_name2" id="product_name2" class="product_name2" value="<?php echo $rs_balance['pre_production']; ?>">
									</td>	
									<td class="b-bottom bd-right"><input type="text" name="quantities2" id="quantities2" value="<?php echo $rs_balance['quantities']?>" OnChange="fncTotal2();"></td>
									<td class="b-bottom bd-right"><input type="text" name="product_unit2" value="<?php echo $rs_balance['product_unit']?>"></td>
									<td class="b-bottom bd-right"><input type="text" name="price_per_baht2" id="price_per_baht2" value="<?php echo $rs_balance['price_per_baht']?>" OnChange="fncTotal2();"></td>
									<td class="b-bottom bd-right"><input type="text" name="total2" id="total2" value="<?php echo $rs_balance['total']?>"></td>
									<td class="b-bottom bd-right">
										<?php 
										if($rs_balance['project_manager']==0){$project_manager='N/A';}
										else{
											$sql_acc="select * from account where id_account='".$rs_balance['project_manager']."'";
											$res_acc=mysql_query($sql_acc) or die ('Error '.$sql_acc);
											$rs_acc=mysql_fetch_array($res_acc);
											$project_manager=$rs_acc['username'];
										}
										?>
										<input type="hidden" name="id_pm2" id="id_pm2" value="<?php echo $rs_balance['project_manager']?>">
										<input type="text" name="pm2" id="pm2" class="pm2" value="<?php echo $project_manager?>">
									</td>
									<td class="b-bottom bd-right"><input type="text" name="note2" id="note2" value="<?php echo $rs_balance['note']?>"></td>
									<td class="b-bottom bd-right"><input type="checkbox" name="po_reject2" <?php if($rs_balance['po_reject']==1){echo 'checked';}?>></td>
									<td class="b-bottom">
										<input name="btnAdd" type="button" id="btnUpdate" value="Update" OnClick="frm.hdnCmd.value='Update';JavaScript:return fncSubmit();" class="btn-update">
										<input name="btnAdd" type="button" id="btnCancel" value="Cancel" OnClick="window.location='<?=$_SERVER["PHP_SELF"].'?date_visited='.$rs_balance['date_visited'].'&month_visited='.$rs_balance['month_visited'].'&year_visited='.$rs_balance['year_visited']?>';" class="btn-cancel">
									</td>
								</tr>
								<?php }else{?>
								<?php 
									if($rs_balance['po_reject']==1){$style_reject='<span style="color:red;">';$style_end_reject='</span">';}
									else{$style_reject='';$style_end_reject='';}
								?>
								<input type="hidden" name="date_visited" value="<?php echo $_REQUEST["date_visited"]?>">
								<input type="hidden" name="month_visited" value="<?php echo $_REQUEST["month_visited"]?>">
								<input type="hidden" name="year_visited" value="<?php echo $_REQUEST["year_visited"]?>">
								<tr>
									<td class="bd-right b-bottom">
										<?php 
										$sql_company2="select * from company where id_company='".$rs_balance['id_customer']."'";
										$res_company2=mysql_query($sql_company2) or die ('Error '.$sql_company2);
										$rs_company2=mysql_fetch_array($res_company2);
										echo $style_reject.$rs_company2['company_name'].$style_end_reject;
										?>
									</td>	
									<td class="bd-right b-bottom"><?php echo $style_reject.$rs_balance['po_no'].$style_end_reject;?></td>
									<td class="bd-right b-bottom">
										<?php $pre_production=$rs_balance['pre_production'];
										/*if($rs_balance['id_product'] != '0'){
											$sql_product2="select * from product where id_product='".$rs_balance['id_product']."'";
											$res_product2=mysql_query($sql_product2) or die ('Error '.$sql_product2);
											$rs_product2=mysql_fetch_array($res_product2);
											$product2=$rs_product2['product_name'];
										}else{$product2=$rs_balance['pre_production'];}*/

										echo $style_reject.$pre_production.$style_end_reject;
										?>
									</td>
									<td class="center bd-right b-bottom" style="text-align:right;"><?php echo $style_reject;echo number_format($rs_balance['quantities'],2);echo $style_end_reject;?></td>
									<td class="b-bottom bd-right"><?php echo $rs_balance['product_unit']?></td>
									<td class="bd-right b-bottom" style="text-align:right;"><?php echo $style_reject;echo number_format($rs_balance['price_per_baht'],2);echo $style_end_reject;?></td>
									<td class="bd-right b-bottom" style="text-align:right;"><?php echo $style_reject;echo number_format($rs_balance['total'],2);echo $style_end_reject;?></td>
									<td class="bd-right center b-bottom">
										<?php 
										if($rs_balance['project_manager']==0){echo $style_reject.'N/A'.$style_end_reject;}
										else{
											$sql_acc="select * from account where id_account='".$rs_balance['project_manager']."'";
											$res_acc=mysql_query($sql_acc) or die ('Error '.$sql_acc);
											$rs_acc=mysql_fetch_array($res_acc);
											echo $style_reject.$rs_acc['username'].$style_end_reject;
										}
										?>
									</td>
									<td class="bd-right b-bottom"><?php echo $style_reject.$rs_balance['note'].$style_end_reject?></td>
									<td class="b-bottom bd-right"><?php if($rs_balance['po_reject']==1){echo '<img src="img/accepted-red.png">';}else{echo '';}?></td>
									<td class="b-bottom bd-right" style="padding:0 0 0 1%;">
										<a href="<?=$_SERVER["PHP_SELF"];?>?action=edit&id_p=<?=$rs_balance['id_sales_balance'].'&date_visited='.$rs_balance['date_visited'].'&month_visited='.$rs_balance['month_visited'].'&year_visited='.$rs_balance['year_visited'];?>"><img src="img/edit.png" style="width:20px;"></a>
										<a href="JavaScript:if(confirm('Confirm Delete?')==true){window.location='<?=$_SERVER["PHP_SELF"];?>?action=del&id_p=<? echo $rs_balance['id_sales_balance'].'&date_visited='.$rs_balance['date_visited'].'&month_visited='.$rs_balance['month_visited'].'&year_visited='.$rs_balance['year_visited'];?>';}"><img src="img/trash_green.png" style="width:20px;"></a>
										<a href="send-mail-sales-balance.php?id_p=<?=$rs_balance  ['po_no'].'&date_visited='.$rs_balance['date_visited'].'&month_visited='.$rs_balance['month_visited'].'&year_visited='.$rs_balance['year_visited'];?>"><img src="img/send-mail.png" style="width:20px;"></a>
									</td>
								</tr>
								<?php }  
									}//end while itenary
								?>
								<script type="text/javascript" src="js/js-autocomplete/js/jquery-1.4.2.min.js"></script> 
								<script type="text/javascript" src="js/js-autocomplete/js/jquery-ui-1.8.2.custom.min.js"></script> 
								<script type="text/javascript"> 
									jQuery(document).ready(function(){
										$('.company_name').autocomplete({
											source:'return-sales-balance.php', 
											//minLength:2,
											select:function(evt, ui){
												this.form.id_company.value = ui.item.id_company;
												this.form.id_pm.value = ui.item.id_pm;
												this.form.pm.value = ui.item.pm;
											}
										});
										$('.product_name').autocomplete({
											source:'return-product.php', 
											//minLength:2,
											select:function(evt, ui){
												this.form.id_product.value = ui.item.id_product;
											}
										});
										$('.pm').autocomplete({
											source:'return-sales.php', 
											//minLength:2,
											select:function(evt, ui){
												this.form.id_pm.value = ui.item.id_pm;
											}
										});
									});
									function fncTotal(){
										document.frm.total.value=(parseFloat(document.frm.price_per_baht.value) * parseFloat(document.frm.quantities.value)).toFixed(3);	
									}
								</script> 
								<link rel="stylesheet" href="js/js-autocomplete/css/smoothness/jquery-ui-1.8.2.custom.css" /> 								
								<tr>
									<input type="hidden" name="date_visited" value="<?php echo $_REQUEST["date_visited"]?>">
									<input type="hidden" name="month_visited" value="<?php echo $_REQUEST["month_visited"]?>">
									<input type="hidden" name="year_visited" value="<?php echo $_REQUEST["year_visited"]?>">
									<td class="bd-right b-bottom top w15">
										<input type="hidden" name="id_company" id="id_company">
										<input name="company_name" type="text" id="company_name" class="company_name">
									</td>	
									<td class="bd-right b-bottom top w15"><input type="text" name="po_no" value="<?php echo $rs_balance['po_no']?>"></td>
									<td class="bd-right b-bottom top w20">
										<?php 											
											$sql_product="select * from product where id_product='".$rs_balance['id_product']."'";
											$res_product=mysql_query($sql_product) or die ('Error '.$sql_product);
											$rs_product=mysql_fetch_array($res_product);
										?>
											<input type="hidden" name="id_product" id="id_product" value="<?php echo $rs_product['id_product']?>">
											<input type="text" name="product_name" id="product_name" class="product_name" value="<?php if($rs_balance['pre_production'] !=''){echo $rs_product['product_name'];}else{echo $rs_balance['pre_production'];}?>">
									</td>	
									<td class="b-bottom bd-right"><input type="text" name="quantities" id="quantities" value="<?php echo $rs_balance['quantities']?>" OnChange="fncTotal();"></td>
									<td class="b-bottom bd-right"><input type="text" name="product_unit" value="<?php echo $rs_balance['product_unit']?>"></td>
									<td class="b-bottom bd-right"><input type="text" name="price_per_baht" id="price_per_baht" value="<?php echo $rs_balance['price_per_baht']?>" OnChange="fncTotal();"></td>
									<td class="b-bottom bd-right"><input type="text" name="total" id="total" value="<?php echo $rs_balance['total']?>"></td>
									<td class="b-bottom bd-right">
										<input type="hidden" name="id_pm" id="id_pm">
										<input type="text" name="pm" id="pm" class="pm">
									</td>
									<td class="b-bottom bd-right"><input type="text" name="note"></td>
									<td class="b-bottom" colspan="2"><input type="checkbox" name="po_reject"></td>
								</tr>
								<tr>
									<td class="center top" colspan="11"><input name="btnAdd" type="button" id="btnAdd" title="Add" value="Add"  OnClick="frm.hdnCmd.value='Add';JavaScript:return fncSubmit();" class="btn-new2"></td>
									</td>
								</tr>
							</table>
							<?php }//end date visited
							else{?>
							<table style="border: 1px solid #eee; border-bottom:none; width: 100%;" cellpadding="0" cellspacing="0" id="tb-quotation">
								<tr>
									<td class="bd-right center top b-bottom w10">Customer</td>
									<td class="bd-right center top b-bottom w10">PO.No.</td>
									<td class="bd-right center top b-bottom w10">Product</td>
									<td class="bd-right center top b-bottom w10">Quantities(Unit)</td>
									<td class="bd-right center top b-bottom w10">Price Per Unit(Baht)</td>
									<td class="bd-right center top b-bottom w10">Total</td>
									<td class="bd-right center top b-bottom w10">Project Manager</td>
									<td class="bd-right center top b-bottom w10">Note</td>
									<td class="center top b-bottom w10">Sendmail</td>
								</tr>
								<?php
									$sql_sales2="select * from sm_sales_balance where id_sales_balance='".$id_balance."'";
									$res_sales2=mysql_query($sql_sales2) or die ('Error '.$sql_sales2);											
									while($rs_sales2=mysql_fetch_array($res_sales2)){
										//$month=date('F',strtotime($rs_itenary2['month_visited']));
										if($rs_sales2['po_reject']==1){$style_reject='<span style="color:red;">';$style_end_reject='</span">';}
										else{$style_reject='';$style_end_reject='';}
								?>
								<tr>
									<td class="b-bottom bd-right">
										<?php 
											$sql_company3="select * from company where id_company='".$rs_sales2['id_customer']."'";
											$res_company3=mysql_query($sql_company3) or die ('Error '.$sql_company3);
											$rs_company3=mysql_fetch_array($res_company3);
											echo $style_reject.$rs_company3['company_name'].$style_end_reject;
										?>
									</td>
									<td class="b-bottom bd-right"><?php echo $style_reject.$rs_sales2['po_no'].$style_end_reject;?></td>
									<td class="b-bottom bd-right">
										<?php 			
											/*if($rs_sales2['pre_production'] != ''){
												$sql_product1="select * from product where id_product='".$rs_sales2['id_product']."'";
												$res_product1=mysql_query($sql_product1) or die ('Error '.$sql_product1);
												$rs_product1=mysql_fetch_array($res_product1);
												$product1=$rs_product1['product_name'];
											}else{$product1=$rs_sales2['pre_production'];}*/
											$product1=$rs_sales2['pre_production'];
											echo $style_reject.$product1.$style_end_reject;
										?>
									</td>
									<td class="b-bottom bd-right" style="text-align:right;"><?php echo $style_reject;echo number_format($rs_sales2['quantities'],2);echo $style_end_reject;?></td>
									<td class="b-bottom bd-right" style="text-align:right;"><?php echo $style_reject;echo number_format($rs_sales2['price_per_baht'],2);echo $style_end_reject;?></td>
									<td class="b-bottom bd-right" style="text-align:right;"><?php echo $style_reject;echo number_format($rs_sales2['total'],2);echo $style_end_reject;?></td>
									<td class="b-bottom bd-right center">
										<?php 
										if($rs_sales2['project_manager']==0){echo $style_reject.'N/A'.$style_end_reject;}
										else{
											$sql_acc="select * from account where id_account='".$rs_sales2['project_manager']."'";
											$res_acc=mysql_query($sql_acc) or die ('Error '.$sql_acc);
											$rs_acc=mysql_fetch_array($res_acc);										
											echo $style_reject.$rs_acc['username'].$style_end_reject;
										}
										?>
									</td>
									<td  class="b-bottom bd-right center"><?php echo $style_reject;echo $rs_sales2['note'];echo $style_end_reject;?></td>
									<td class="b-bottom"><a href="send-mail-sales-balance.php?id_p=<?=$rs_balance  ['po_no'].'&date_visited='.$_GET['date_visited'].'&month_visited='.$_GET['month_visited'].'&year_visited='.$_GET['year_visited'];?>"><img src="img/send-mail.png" style="width:20px;"></a></td>
								</tr>
							<?php } //end while ?>
							</table>
							<?php }?>
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
