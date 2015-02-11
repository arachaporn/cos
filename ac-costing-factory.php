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

$_SESSION['id_product']=$_REQUEST['id_product'];
$_SESSION['product_name']=$_REQUEST['product_name'];
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
			<?php
			$date=date('Y-m-d');
			if($_GET["id_u"]=='New'){
				$mode=$_GET["id_u"];
				$button='Save';
				$id='New';				
			}
			else{
				$id=$_GET["id_u"];
				$sql="select * from costing_table where id_costing_table='".$id."'";
				$res=mysql_query($sql) or die ('Error '.$sql);
				$rs=mysql_fetch_array($res);
				
				$mode='Edit ';
				$button='Update';
			}

			//*** Add Condition ***//
			if($_POST["hdnCmd"] == "Add"){				
				if(is_numeric($_POST['mode'])){$id_costing_table=$_POST['mode'];}
				else{$id_costing_table=0;}
				
				$sql_rm="select * from rm_price where rm_name='".$_POST['rm_name']."'";
				$res_rm=mysql_query($sql_rm) or die ('Error '.$sql_rm);
				$rs_rm=mysql_fetch_array($res_rm);
				if($rs_rm){
					$id_rm=$rs_rm['id_rm_price'];
					$rm_name=$_POST['rm_name'];
				}else{
					$id_rm= -1;
					$rm_name=$_POST['rm_name'];
				}
				
				$sql = "insert into costing_rm(id_costing_table,id_product,id_rm_price";
				$sql .=",rm_name,quantities,unit,rm_cost,cost_unit,create_by,create_date,rev) ";
				$sql .="values('".$id_costing_table."','".$_POST['id_product']."'";
				$sql .=",'".$id_rm."','".$rm_name."','".$_POST['num_quantities']."'";
				$sql .=",'".$_POST['rm_unit']."','".$_POST['rm_cost']."'";
				$sql .=",'".$_POST['sum_cost']."','".$rs_account['id_account']."'";
				$sql .=",'".$date."','".$_POST['rev']."') ";
				$res = mysql_query($sql) or die ('Error '.$sql);
			}

			//*** Update Condition ***//
			if($_POST["hdnCmd"] == "Update"){
				$sql = "update costing_rm set id_product='".$_POST["id_product"]."'";
				$sql .=",id_rm_price='".$_POST["id_rm_price2"]."',rm_name='".$_POST['rm_name2']."'";
				$sql .=",quantities='".$_POST["num_quantities2"]."',unit='".$_POST['rm_unit2']."'";
				$sql .=",rm_cost='".$_POST["rm_cost2"]."',cost_unit='".$_POST["sum_cost2"]."'";
				$sql .="where id_costing_rm = '".$_POST["hdnEdit"]."' ";
				$res = mysql_query($sql) or die ('Error '.$sql);
			}

			//*** Delete Condition ***//
			if($_GET["action"] == "del"){
				$sql = "delete from costing_rm";
				$sql .=" where id_costing_rm = '".$_GET["id_p"]."'";
				$res = mysql_query($sql) or die ('Error '.$sql);
			}
			if($_POST['hdnCmd']=="save_data"){
				$sql="insert into costing_table(id_product,id_product_appearance,appearance";
				$sql .=",rm_total,rm_excipients,rm_other_cost,total_cost_excipients";
				$sql .=",cost_product,lost10,vat,moh,profit_product,total_cost_product";
				$sql .=",id_type_package,create_by,create_date,rev,status) values ";
				$sql .="('".$_POST['id_product']."','".$_POST['id_product_appearance']."'";
				$sql .=",'".$_POST['appearance']."','".$_POST['total']."'";
				$sql .=",'".$_POST['other_excipients']."','".$_POST['rm_cost_other']."'";
				$sql .=",'".$_POST['sum_cost_other']."','".$_POST['cost_tab']."'";
				$sql .=",'".$_POST['lost_10']."','".$_POST['vat_7']."','".$_POST['moh']."'";
				$sql .=",'".$_POST['profit']."','".$_POST['total_cost']."','".$_POST['pack']."'";
				$sql .=",'".$rs_account['id_account']."','".$date."','0','2')";
				$res=mysql_query($sql) or die ('Error '.$sql);
				$id_costing_table=mysql_insert_id();
				
				$sql_rm="update costing_rm set id_costing_table='".$id_costing_table."'";
				$sql_rm .=",id_product='".$_POST['id_product']."'";
				$sql_rm .=" where id_costing_table=0";
				$res_rm=mysql_query($sql_rm) or die ('Error '.$sql_rm);
				
				if($_POST['pack']==5){
					$ck_set_array=array($_POST['box_set_bulk'],$_POST['box_moh'],$_POST['box_set_blister'],$_POST['box_silica_gel'],$_POST['box_aluminum'],$_POST['box_box'],$_POST['box_film_shrink'],$_POST['box_carton']);
					$tag_string="";
					while (list ($key,$val) = @each ($ck_set_array)) {
						//echo "$val,";
						$tag_string.=$val.",";			
					}
					$ck_set=substr($tag_string,0,(strLen($tag_string)-1));// remove the last , from string	

					$sql_pack="insert into costing_pack(id_costing_table,id_type_package,num_unit,sum_unit";
					$sql_pack .=",sum_per_set,ck_set,moh,blister,silica_gel,aluminum,box,film_shrik,carton";
					$sql_pack .=",bulk,bulk_cost,total_set,jsp_profit,jsp_profit_cost,cdip_profit";
					$sql_pack .=",cdip_profit_cost) values";
					$sql_pack .="('".$id_costing_table."','".$_POST['pack']."','".$_POST['num_cos_per']."'";
					$sql_pack .=",'".$_POST['sum_cost_per']."','".$_POST['total_all_box']."'";
					$sql_pack .=",'".$ck_set."','".$_POST['box_moh_cost']."'";
					$sql_pack .=",'".$_POST['box_blister_cost']."','".$_POST['silica_gel_cost']."'";
					$sql_pack .=",'".$_POST['aluminum_cost']."','".$_POST['box_box_cost']."'";
					$sql_pack .=",'".$_POST['film_shrink_cost']."','".$_POST['carton_cost']."'";
					$sql_pack .=",'".$_POST['box_bulk_cost']."','".$_POST['total_bulk_cost']."'";
					$sql_pack .=",'".$_POST['total_per_unit2']."','".$_POST['jsp_profit']."'";
					$sql_pack .=",'".$_POST['total_jsp_profit']."','".$_POST['cdip_profit']."'";
					$sql_pack .=",'".$_POST['total_cdip_profit']."')";
					$res_pack=mysql_query($sql_pack) or die ('Error '.$sql_pack);

				}elseif($_POST['pack']==1){
					$ck_set_array=array($_POST['bottle_set_bulk'],$_POST['bottle_moh_cost'],$_POST['bottle_silica_gel_cost'],$_POST['bottle_bottle_cost'],$_POST['bottle_sticker_cost'],$_POST['bottle_box_cost'],$_POST['bottle_film_cost'],$_POST['bottle_carton_cost']);
					$tag_string="";
					while (list ($key,$val) = @each ($ck_set_array)) {
						//echo "$val,";
						$tag_string.=$val.",";			
					}
					$ck_set=substr($tag_string,0,(strLen($tag_string)-1));// remove the last , from string

					$sql_pack="insert into costing_pack(id_costing_table,id_type_package,num_unit,sum_unit";
					$sql_pack .=",sum_per_set,ck_set,moh,silica_gel,bottle,sticker,box,film_shrik,carton";
					$sql_pack .=",bulk,bulk_cost,total_set,jsp_profit,jsp_profit_cost,cdip_profit";
					$sql_pack .=",cdip_profit_cost) values";
					$sql_pack .="('".$id_costing_table."','".$_POST['pack']."','".$_POST['bottle_num_cos_per']."'";
					$sql_pack .=",'".$_POST['bottle_sum_cost_per']."','".$_POST['total_all_bottle2']."'";
					$sql_pack .=",'".$ck_set."','".$_POST['bottle_moh_cost']."','".$_POST['bottle_silica_gel_cost']."'";
					$sql_pack .=",'".$_POST['bottle_bottle_cost']."','".$_POST['bottle_sticker_cost']."'";
					$sql_pack .=",'".$_POST['bottle_box_cost']."','".$_POST['bottle_film_cost']."'";
					$sql_pack .=",'".$_POST['bottle_carton_cost']."','".$_POST['bottle_bulk_cost']."'";
					$sql_pack .=",'".$_POST['bottle_total_bulk_cost']."','".$_POST['bottle_total_per_unit2']."'";
					$sql_pack .=",'".$_POST['bottle_jsp_profit']."','".$_POST['bottle_total_jsp_profit']."'";
					$sql_pack .=",'".$_POST['bottle_cdip_profit']."','".$_POST['bottle_total_cdip_profit']."')";
					$res_pack=mysql_query($sql_pack) or die ('Error '.$sql_pack);
				}				
			?>
				<script>
					window.location.href='ac-costing-table.php?id_u=<?=$id_costing_table?>';
				</script>
			<?php } 
			if($_POST['hdnCmd']=="update_data"){
				$id_costing_table=$_POST['mode'];
				
				$sql="update costing_table set id_roc='".$_POST['id_roc']."'";
				$sql .=",id_product_appearance='".$_POST['id_product_appearance']."'";
				$sql .=",appearance='".$_POST['appearance']."'";
				$sql .=",id_product='".$_POST['id_product']."',rm_total='".$_POST['total']."'";
				$sql .=",rm_excipients='".$_POST['other_excipients']."'";
				$sql .=",rm_other_cost='".$_POST['rm_cost_other']."'";
				$sql .=",total_cost_excipients='".$_POST['sum_cost_other']."'";
				$sql .=",cost_product='".$_POST['sum_cost_other']."'";
				$sql .=",lost10='".$_POST['lost_10']."',vat='".$_POST['vat_7']."'";
				$sql .=",moh='".$_POST['moh']."',profit_product='".$_POST['profit']."'";
				$sql .=",total_cost_product='".$_POST['total_cost']."'";
				$sql .=",id_type_package='".$_POST['pack']."'";
				$sql .=" where id_costing_table='".$id_costing_table."'";
				$res=mysql_query($sql) or die ('Error '.$sql);

				$sql_rm="update costing_rm set id_product='".$_POST['id_product']."'";
				$sql_rm .=",id_product='".$_POST['id_product']."'";
				$sql_rm .=" where id_costing_table='".$_POST['mode']."'";
				$res_rm=mysql_query($sql_rm) or die ('Error '.$sql_rm);
				
				$sql_pack2="select * from costing_pack where id_costing_table='".$id_costing_table."'";
				$sql_pack2=mysql_query($sql_pack2) or die ('Error '.$sql_pack2);
				$rs_pack2=mysql_fetch_array($sql_pack2);
				if($rs_pack2){
					if($_POST['pack']==1){
						$ck_set_array=array($_POST['bottle_set_bulk'],$_POST['bottle_moh_cost'],$_POST['bottle_silica_gel_cost'],$_POST['bottle_bottle_cost'],$_POST['bottle_sticker_cost'],$_POST['bottle_box_cost'],$_POST['bottle_film_cost'],$_POST['bottle_carton_cost']);
						$tag_string="";
						while (list ($key,$val) = @each ($ck_set_array)) {
							//echo "$val,";
							$tag_string.=$val.",";
						}
						$ck_set=substr($tag_string,0,(strLen($tag_string)-1));// remove the last , from string

						$sql_pack="update costing_pack set id_type_package='".$_POST['pack']."'";
						$sql_pack .=",num_unit='".$_POST['bottle_num_cos_per']."'";
						$sql_pack .=",sum_unit='".$_POST['bottle_sum_cost_per']."'";
						$sql_pack .=",sum_per_set='".$_POST['total_all_bottle2']."'";
						$sql_pack .=",ck_set='".$ck_set."',moh='".$_POST['bottle_moh_cost']."'";
						$sql_pack .=",silica_gel='".$_POST['bottle_silica_gel_cost']."'";
						$sql_pack .=",bottle='".$_POST['bottle_bottle_cost']."'";
						$sql_pack .=",box='".$_POST['bottle_box_cost']."'";
						$sql_pack .=",sticker='".$_POST['bottle_sticker_cost']."'";
						$sql_pack .=",film_shrik='".$_POST['bottle_film_cost']."'";
						$sql_pack .=",carton='".$_POST['bottle_carton_cost']."'";
						$sql_pack .=",bulk='".$_POST['bottle_bulk_cost']."'";
						$sql_pack .=",bulk_cost='".$_POST['bottle_total_bulk_cost']."'";
						$sql_pack .=",total_set='".$_POST['bottle_total_per_unit2']."'";
						$sql_pack .=",jsp_profit='".$_POST['bottle_jsp_profit']."'";
						$sql_pack .=",jsp_profit_cost='".$_POST['bottle_total_jsp_profit']."'";
						$sql_pack .=",cdip_profit='".$_POST['bottle_cdip_profit']."'";
						$sql_pack .=",cdip_profit_cost='".$_POST['bottle_total_cdip_profit']."'";
						$sql_pack .=" where id_costing_table='".$_POST['mode']."'";
						$sql_pack=mysql_query($sql_pack) or die ('Error '.$sql_pack);
					}
					elseif($_POST['pack']==5){
						$ck_set_array=array($_POST['box_set_bulk'],$_POST['box_moh'],$_POST['box_set_blister'],$_POST['box_silica_gel'],$_POST['box_aluminum'],$_POST['box_box'],$_POST['box_film_shrink'],$_POST['box_carton']);
						$tag_string="";
						while (list ($key,$val) = @each ($ck_set_array)) {
							//echo "$val,";
							$tag_string.=$val.",";
						}
						$ck_set=substr($tag_string,0,(strLen($tag_string)-1));// remove the last , from string

						$sql_pack="update costing_pack set id_type_package='".$_POST['pack']."'";
						$sql_pack .=",num_unit='".$_POST['num_cos_per']."'";
						$sql_pack .=",sum_unit='".$_POST['sum_cost_per']."'";
						$sql_pack .=",sum_per_set='".$_POST['total_all_box']."'";
						$sql_pack .=",ck_set='".$ck_set."',moh='".$_POST['box_moh_cost']."'";
						$sql_pack .=",blister='".$_POST['box_blister_cost']."'";
						$sql_pack .=",silica_gel='".$_POST['silica_gel_cost']."'";
						$sql_pack .=",aluminum='".$_POST['aluminum_cost']."'";
						$sql_pack .=",box='".$_POST['box_box_cost']."'";
						$sql_pack .=",film_shrik='".$_POST['film_shrink_cost']."'";
						$sql_pack .=",carton='".$_POST['carton_cost']."'";
						$sql_pack .=",bulk='".$_POST['box_bulk_cost']."'";
						$sql_pack .=",bulk_cost='".$_POST['total_bulk_cost']."'";
						$sql_pack .=",total_set='".$_POST['total_per_unit2']."'";
						$sql_pack .=",jsp_profit='".$_POST['jsp_profit']."'";
						$sql_pack .=",jsp_profit_cost='".$_POST['total_jsp_profit']."'";
						$sql_pack .=",cdip_profit='".$_POST['cdip_profit']."'";
						$sql_pack .=",cdip_profit_cost='".$_POST['total_cdip_profit']."'";
						$sql_pack .=" where id_costing_table='".$_POST['mode']."'";
						$sql_pack=mysql_query($sql_pack) or die ('Error '.$sql_pack);
					}
				}else{
					if($_POST['pack']==5){
						$ck_set_array=array($_POST['bottle_set_bulk'],$_POST['box_moh'],$_POST['box_set_blister'],$_POST['box_silica_gel'],$_POST['box_aluminum'],$_POST['box_box'],$_POST['box_film_shrink'],$_POST['box_carton']);
						$tag_string="";
						while (list ($key,$val) = @each ($ck_set_array)) {
							//echo "$val,";
							$tag_string.=$val.",";			
						}
						$ck_set=substr($tag_string,0,(strLen($tag_string)-1));// remove the last , from string
						

						$sql_pack="insert into costing_pack(id_costing_table,id_type_package";
						$sql_pack .=",num_unit,sum_unit,sum_per_set,ck_set,moh,blister";
						$sql_pack .=",silica_gel,aluminum,box,film_shrik,carton,bulk,bulk_cost";
						$sql_pack .=",total_set,jsp_profit,jsp_profit_cost,cdip_profit,cdip_profit_cost)";
						$sql_pack .=" values ('".$id_costing_table."','".$_POST['pack']."'";
						$sql_pack .=",'".$_POST['num_cos_per']."','".$_POST['sum_cost_per']."'";
						$sql_pack .=",'".$_POST['total_all_box']."','".$ck_set."'";
						$sql_pack .=",'".$_POST['box_moh_cost']."','".$_POST['box_blister_cost']."'";
						$sql_pack .=",'".$_POST['silica_gel_cost']."','".$_POST['aluminum_cost']."'";
						$sql_pack .=",'".$_POST['box_box_cost']."','".$_POST['film_shrink_cost']."'";
						$sql_pack .=",'".$_POST['carton_cost']."','".$_POST['bottle_bulk_cost']."'";
						$sql_pack .=",'".$_POST['bottle_total_bulk_cost']."','".$_POST['total_per_unit2']."'";
						$sql_pack .=",'".$_POST['jsp_profit']."','".$_POST['total_jsp_profit']."'";
						$sql_pack .=",'".$_POST['cdip_profit']."','".$_POST['total_cdip_profit']."')";
						$res_pack=mysql_query($sql_pack) or die ('Error '.$sql_pack);

					}elseif($_POST['pack']==1){
						$ck_set_array=array($_POST['box_set_bulk'],$_POST['bottle_moh_cost'],$_POST['bottle_silica_gel_cost'],$_POST['bottle_bottle_cost'],$_POST['bottle_sticker_cost'],$_POST['bottle_box_cost'],$_POST['bottle_film_cost'],$_POST['bottle_carton_cost']);
						$tag_string="";
						while (list ($key,$val) = @each ($ck_set_array)) {
							//echo "$val,";
							$tag_string.=$val.",";			
						}
						$ck_set=substr($tag_string,0,(strLen($tag_string)-1));// remove the last , from string
					
						$sql_pack="insert into costing_pack(id_costing_table,id_type_package";
						$sql_pack .=",num_unit,sum_unit,sum_per_set,ck_set,moh,silica_gel";
						$sql_pack .=",bottle,sticker,box,film_shrik,carton,bulk,bulk_cost";
						$sql_pack .=",total_set,jsp_profit,jsp_profit_cost,cdip_profit,cdip_profit_cost)";
						$sql_pack .=" values ('".$id_costing_table."','".$_POST['pack']."'";
						$sql_pack .=",'".$_POST['bottle_num_cos_per']."'";
						$sql_pack .=",'".$_POST['bottle_sum_cost_per']."'";
						$sql_pack .=",'".$_POST['total_all_bottle2']."','".$ck_set."'";
						$sql_pack .=",'".$_POST['bottle_moh_cost']."','".$_POST['bottle_bottle_cost']."'";
						$sql_pack .=",'".$_POST['bottle_silica_gel_cost']."'";
						$sql_pack .=",'".$_POST['bottle_sticker_cost']."','".$_POST['bottle_box_cost']."'";
						$sql_pack .=",'".$_POST['bottle_film_cost']."','".$_POST['bottle_carton_cost']."'";
						$sql_pack .=",'".$_POST['bottle_bulk_cost']."','".$_POST['bottle_total_bulk_cost']."'";
						$sql_pack .=",'".$_POST['bottle_total_per_unit2']."','".$_POST['bottle_jsp_profit']."'";
						$sql_pack .=",'".$_POST['bottle_total_jsp_profit']."','".$_POST['bottle_cdip_profit']."'";
						$sql_pack .=",'".$_POST['bottle_total_cdip_profit']."')";
						$res_pack=mysql_query($sql_pack) or die ('Error '.$sql_pack);
					}		
				}
			?>
				<script>
					window.location.href='ac-costing-table.php?id_u=<?=$id_costing_table?>';
				</script>
			<?php } 
			if($_POST['hdnCmd']=="finished_data"){
				$sql="update costing_table set status='1' where id_costing_table='".$_POST['mode']."'";
				$res=mysql_query($sql) or die ('Error '.$sql);
			?>
				<script>
					window.location.href='costing-table.php';
				</script>
			<?php } ?>
			<script type="text/javascript" src="js/js-autocomplete/js/jquery-1.4.2.min.js"></script> 
			<script type="text/javascript" src="js/js-autocomplete/js/jquery-ui-1.8.2.custom.min.js"></script> 
			<script type="text/javascript"> 
				jQuery(document).ready(function(){
					$('.roc').autocomplete({
						source:'return-roc.php', 
						//minLength:2,
						select:function(evt, ui){
							this.form.id_roc.value = ui.item.id_roc;
							this.form.id_product.value = ui.item.id_product;
							this.form.product_name.value = ui.item.product_name;
						}
					});
					$('.product_name').autocomplete({
						source:'return-product.php', 
						//minLength:2,
						select:function(evt, ui){
							this.form.id_product.value = ui.item.id_product;
						}
					});
					$('.rm_name').autocomplete({
						source:'return-rm.php', 
						//minLength:2,
						select:function(evt, ui){
							this.form.id_rm_price.value = ui.item.id_rm_price;
							this.form.rm_cost.value = ui.item.rm_cost;							
						}
					});
					$('.rm_name2').autocomplete({
						source:'return-rm.php', 
						//minLength:2,
						select:function(evt, ui){
							this.form.id_rm_price2.value = ui.item.id_rm_price2;
							this.form.rm_cost2.value = ui.item.rm_cost2;
							}
					});
				});
			</script> 
			<link rel="stylesheet" href="js/js-autocomplete/css/smoothness/jquery-ui-1.8.2.custom.css" />
			<form name="frm" method="post" action="<?=$_SERVER["PHP_SELF"]."?id_u=".$id?>">
			<input type="hidden" name="hdnCmd" value="">
			<table style="border: 0; width: 100%;" cellpadding="0" cellspacing="0">
				<tr>
					<td class="b-bottom"><div class="large-4 columns"><h4><h4>Costing Table >> <?php echo $mode;?></h4></h4></div></td>
				</tr>
				<tr>
					<td class="b-bottom">
						<div class="large-4 columns">
							<?php if(!is_numeric($id)){?>
							<input type="button" name="save_data" id="save_data" value="<?php echo $button?>" class="button-create" OnClick="frm.hdnCmd.value='save_data';JavaScript:return fncSubmit();">
							<?php }else{if($rs['status']==1){?>
							<input type="button" name="rev_data" id="rev_data" value="Rev." class="button-create" OnClick="frm.hdnCmd.value='rev_data';JavaScript:return fncSubmit();">
							<?php }else{?>
							<input type="button" name="update_data" id="update_data" value="<?php echo $button?>" class="button-create" OnClick="frm.hdnCmd.value='update_data';JavaScript:return fncSubmit();">							
							<input type="button" name="finished_data" id="finished_data" value="Finished" class="button-create" OnClick="frm.hdnCmd.value='finished_data';JavaScript:return fncSubmit();">
							<?php }}?>
							<input type="button" value="Close" class="button-create" onclick="window.location.href='costing-table.php'">
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="large-4 columns">
						<?php
						$sql_roc="select * from roc where id_roc='".$rs['id_roc']."'";
						$res_roc=mysql_query($sql_roc) or die ('Error '.$sql_roc);
						$rs_roc=mysql_fetch_array($res_roc);

						$sql_product="select * from product where id_product='".$rs['id_product']."'";
						$res_product=mysql_query($sql_product) or die ('Errro '.$sql_product);
						$rs_product=mysql_fetch_array($res_product);
						?>
						เลขที่ ROC<input type="text" name="roc" id="roc" class="roc" value="<?php echo $rs_roc['roc_code']?>" style="width:20%;"><input type="hidden" name="id_roc" id="id_roc" value="<?php echo $rs_roc['id_roc']?>">
						<input type="hidden" name="id_product" id="id_product" value="<?php echo $rs_product['id_product']?>">
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
						Product name<br><div style="float:left;width:30%;margin-right:0.5%;"><input type="text" name="product_name" id="product_name" class="product_name" value="<?php echo $rs_product['product_name']?>" readonly></div><div style="float:left;margin-top:1%;"><a class='various' data-fancybox-type='iframe' href='ac-product.php?id_u=<?=$rs_product['id_product']?>'>Edit product name</a></div>
						<div class="clear"></div>
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
									<li <?php if($mode='New'){echo 'class="active"';}else{if($rs['id_product_appearance']==1){echo 'class="active"';}}?>><a href="#tab1">Tablet</a></li>
									<li <?php if($rs['id_product_appearance']==2){?>class="active"<?php }?>><a href="#tab2">Capsule</a></li>
									<li><a href="#tab3">Soft gelatin</a></li>
									<li><a href="#tab4">Liquid capsule</a></li>
									<li><a href="#tab5">Functional drink</a></li>
									<li><a href="#tab6">Edible gel/Instant drink</a></li>
								</ul>
								<div class="WorldphpTabData">
									<div id="tab1" <?php if($mode='New'){echo 'class="active"';}else{if($rs['id_product_appearance']==1){?>class="active"<?php }} ?>>
										<input type="hidden" name="mode" value="<?php echo $id?>">
										<input type="hidden" name="rev" value="">
										<input type="hidden" name="id_product_appearance" value="1"><!--tablet-->
										<table width="10%" cellpadding="0" cellspacing="0" style="border:none;">
											<tr>
												<td><input type="radio" name="appearance" id="appearance" value="1" <?php if($rs['appearance']==1){echo 'checked';}?> style="vertical-align: middle;margin:0;">Core</td>
												<td><input type="radio" name="appearance" id="appearance" value="2" <?php if($rs['appearance']==2){echo 'checked';}?> style="vertical-align: middle;margin:0;">Film coate
												<input type="text" name="appearance1" id="appearance1"></td>
											</tr>
										</table>
										<table width="100%" cellpadding="0" cellspacing="0" id="tb-cost">	
											<tr>
												<td class="w30 cost-td"><b>Ingredients</b></td>
												<td class="w15 cost-td"><b>Quantities</b></td>
												<td class="w10 cost-td"><b>Unit</b></td>
												<td class="w15 cost-td"><b>RM cost</b></td>
												<td class="w15 cost-td-bottm"><b>Cost/unit</b></td>
											</tr>
											<?php
												$sql_costing_rm="select * from costing_rm where id_costing_table='".$id."' and  create_by='".$rs_account['id_account']."'";
												$res_costing_rm=mysql_query($sql_costing_rm) or die ('Error '.$sql_costing_rm);
												while($rs_costing_rm=mysql_fetch_array($res_costing_rm)){

												if($rs_costing_rm['id_costing_rm'] == $_GET['id_p'] and $_GET["action"] == 'edit'){
											?>	
												<!--function calculate rm edit-->
												<script language="JavaScript">
													function fncSum2(){
														document.frm.sum_cost2.value = (parseFloat(document.frm.num_quantities2.value) * parseFloat(document.frm.rm_cost2.value) / 1000000).toFixed(3);//fix float=3
													}
												</script>
												<!--end function calculate rm edit-->
												<tr>
													<input type="hidden" name="hdnEdit" value="<?php echo $rs_costing_rm['id_costing_rm']?>">
													<?php
													$sql_rm="select * from rm_price where id_rm_price='".$rs_costing_rm['id_rm_price']."'";
													$res_rm=mysql_query($sql_rm) or die ('Error '.$sql_rm);
													$rs_rm=mysql_fetch_array($res_rm);
													?>
													<input type="hidden" name="product_appearance2" value="1"><!--tablet-->
													<input type="hidden" name="id_rm_price2" id="id_rm_price2" value="<?php  if($rs_costing_rm['id_rm_price']== -1){echo $rs_costing_rm['id_rm_price'];}else{echo $rs_costing_rm['id_rm_price'];}?>">
													<td class="bd-right b-bottom top"><input type="text" name="rm_name2" id="rm_name2" class="rm_name2" value="<?php if($rs_costing_rm['id_rm_price']== -1){echo $rs_costing_rm['rm_name'];}else{echo $rs_rm['rm_name'];}?>"></td>
													<td class="bd-right b-bottom top"><input type="text" name="num_quantities2" id="num_quantities2" value="<?php echo $rs_costing_rm['quantities']?>" OnChange="fncSum2();"></td>
													<td class="bd-right b-bottom top"><input type="text" name="rm_unit2" id="rm_unit2" value="<?php echo $rs_costing_rm['unit']?>"></td>
													<td class="bd-right b-bottom top"><input type="text" id="rm_cost2" name="rm_cost2" value="<?php echo $rs_costing_rm['rm_cost']?>" OnChange="fncSum2();"></td>
													<td class="bd-right b-bottom top">
														<input type="text" name="sum_cost2" id="sum_cost2" value="<?php echo $rs_costing_rm['cost_unit']?>" style="float: left; width: 85%;">
														<input name="btnAdd" type="button" id="btnUpdate" value="Update" OnClick="frm.hdnCmd.value='Update';JavaScript:return fncSubmit();" class="btn-update">
														<input name="btnAdd" type="button" id="btnCancel" value="Cancel" OnClick="window.location='<?=$_SERVER["PHP_SELF"]."?id_u=".$id;?>';" class="btn-cancel">
													</td>
												</tr>
											<?php }else{?>												
												<tr>
													<?php
														$sql_rm="select * from rm_price where id_rm_price='".$rs_costing_rm['id_rm_price']."'";
														$res_rm=mysql_query($sql_rm) or die ('Error '.$sql_rm);
														$rs_rm=mysql_fetch_array($res_rm);
													?>
													<td class="bd-right b-bottom cost-left"><?php if($rs_costing_rm['id_rm_price']== -1){echo $rs_costing_rm['rm_name'];}else{echo $rs_rm['rm_name'];}?></td>
													<td class="bd-right b-bottom"><?php echo $rs_costing_rm['quantities']?></td>
													<td class="bd-right b-bottom"><?php echo $rs_costing_rm['unit']?></td>
													<td class="bd-right b-bottom"><?php echo $rs_costing_rm['rm_cost']?></td>
													<td class="b-bottom right-price cost-left"><?php echo number_format($rs_costing_rm['cost_unit'],3)?>
													<a href="<?=$_SERVER["PHP_SELF"];?>?action=edit&id_p=<?=$rs_costing_rm['id_costing_rm'];?>&id_u=<?php echo $id?>"><img src="img/edit.png" style="width:20px;"></a>
													<a href="JavaScript:if(confirm('Confirm Delete?')==true){window.location='<?=$_SERVER["PHP_SELF"];?>?action=del&id_p=<? echo $rs_costing_rm['id_costing_rm'];?>&id_u=<?php echo $id?>';}"><img src="img/delete.png" style="width:20px;"></a>
													</td>
												</tr>
											<?php }
												}//end while select data from costing table
											?>
											<!--function calculate rm-->
											<script language="JavaScript">
												function fncSum(){
													document.frm.sum_cost.value = (parseFloat(document.frm.num_quantities.value) * parseFloat(document.frm.rm_cost.value) / 1000000).toFixed(3);//fix float=3
												}
											</script>
											<!--end function calculate rm-->
											<tr>												
												<input type="hidden" name="id_rm_price" id="id_rm_price">
												<td class="bd-right b-bottom w30"><input type="text" name="rm_name" id="rm_name" class="rm_name"></td>
												<td class="bd-right b-bottom w5"><input type="text" name="num_quantities" id="num_quantities" value="" OnChange="fncSum();"></td>
												<td class="bd-right b-bottom w5"><input type="text" name="rm_unit" id="rm_unit" value="mg" readonly></td>
												<td class="bd-right b-bottom w10"><input type="text" id="rm_cost" name="rm_cost" value="" OnChange="fncSum();">
												<td class="b-bottom w10"><input type="text" name="sum_cost" id="sum_cost" value="" style="float: left; width: 85%;"><input name="btnAdd" type="button" id="btnAdd" value="Add" OnClick="frm.hdnCmd.value='Add';JavaScript:return fncSubmit();" class="btn-new2"></td>
											</tr>
											<tr>
												<!--function calculate rm-->
												<script language="JavaScript">
													function fncSumExc(){
														document.frm.sum_cost_other.value = (parseFloat(document.frm.other_excipients.value) * parseFloat(document.frm.rm_cost_other.value) / 1000000).toFixed(3);//fix float=3
													}
												</script>
												<!--end function calculate rm-->
												<td class="bd-right b-bottom cost-left">Other excipients</td>
												<td class="bd-right b-bottom"><input type="text" name="other_excipients" id="other_excipients" OnChange="fncSumExc();" value="<?php echo $rs['rm_excipients']?>" <?php if($rs_account['role_user']==3){echo 'readonly';}?>></td>
												<td class="bd-right b-bottom"><input type="text" name="rm_unit_other" id="rm_unit_other" value="mg" readonly></td>
												<td class="bd-right b-bottom"><input type="text" name="rm_cost_other" id="rm_cost_other" value="500" OnChange="fncSumExc();" value="<?php echo $rs['rm_other_cost']?>"  <?php if($rs_account['role_user']==3){echo 'readonly';}?>></td>
												<td class="b-bottom w10"><input type="text" name="sum_cost_other" id="sum_cost_other"  value="<?php echo $rs['total_cost_excipients']?>"  <?php if($rs_account['role_user']==3){echo 'readonly';}?>>
											</tr>
											<tr>
												<?php 
												$total_quantities=0;
												$total_cost_unit=0;
												$sql_costing_rm2="select * from costing_rm where create_by='".$rs_account['id_account']."'";
												$res_costing_rm2=mysql_query($sql_costing_rm2) or die ('Error '.$sql_costing_rm2);
												while($rs_costing_rm2=mysql_fetch_array($res_costing_rm2)){
													$total_quantities=$total_quantities+$rs_costing_rm2['quantities'];
													$total_cost_unit=$total_cost_unit+$rs_costing_rm2['cost_unit'];
												}
												?>
												<!--function calculate total cost-->
												<script language="JavaScript">
													function fncSumTotal(){
														
														document.frm.other_excipients.value = (parseFloat(document.frm.total.value) - parseFloat(document.frm.total_quantities.value)).toFixed(3);//fix float=3

														document.frm.sum_cost_other.value = (parseFloat(document.frm.other_excipients.value) * parseFloat(document.frm.rm_cost_other.value) / 1000000).toFixed(3);//fix float=3

														document.frm.cost_tab.value = (parseFloat(document.frm.total_cost_unit.value) + parseFloat(document.frm.sum_cost_other.value)).toFixed(3);//fix float=3
														
														//calculate lost 10%
														document.frm.lost_10.value = (parseFloat(document.frm.cost_tab.value) *100 / 90).toFixed(3);//fix float=3

														//calculate vat 7%
														document.frm.vat_7.value = (parseFloat(document.frm.lost_10.value) * 0.07).toFixed(3);//fix float=3
			
														//moh film coate
														var app;
														if(document.getElementById('appearance').checked){
														app=document.getElementById('appearance').value;
														document.frm.appearance1.value=app;
														}
															if((parseFloat(document.frm.total.value)>=100)  && (parseFloat(document.frm.total.value)<=300)){
																document.frm.moh.value=0.7;															
															}else															
															if((parseFloat(document.frm.total.value)>=500) && (parseFloat(document.frm.total.value)<=550)){
																document.frm.moh.value=1.0;															
															}else
															if((parseFloat(document.frm.total.value)>=800) && (parseFloat(document.frm.total.value)<=1500)){
																document.frm.moh.value=1.2;															
															}
														
														var profit;
														//profit
														profit=parseFloat(document.frm.lost_10.value)+parseFloat(document.frm.vat_7.value);
														if ((profit>=1.00) && (profit<=3.50)){														
															document.frm.profit.value=0.375;
														}else
														if ((profit>=3.51) && (profit<=4.50)){														
															document.frm.profit.value=0.500;
														}else
														if ((profit>=4.51) && (profit<=5.50)){														
															document.frm.profit.value=0.625;
														}else
														if ((profit>=5.51) && (profit<=6.50)){														
															document.frm.profit.value=0.750;
														}else
														if ((profit>=6.51) && (profit<=7.50)){														
															document.frm.profit.value=0.875;
														}else
														if (profit>7.50){														
															document.frm.profit.value=1.00;
														}

														

														document.frm.total_cost.value = (parseFloat(document.frm.lost_10.value) + parseFloat(document.frm.vat_7.value) + parseFloat(document.frm.moh.value) + parseFloat(document.frm.profit.value)).toFixed(3);

														var bulk;
														//bulk
														if(document.frm.box_set_bulk.checked == true){
															bulk=(parseFloat(document.frm.box_bulk_cost.value) * parseFloat(document.frm.total_cost.value)).toFixed(3);
														}else{	
															bulk=0;	
														}
														document.frm.total_bulk_cost.value=parseFloat(bulk);

														//calculate cost box per tablet 
														document.frm.sum_cost_per.value = (parseFloat(document.frm.total_cost.value) * 30).toFixed(3);//fix float=3

														//box sum total
														document.frm.total_per_unit2.value=parseFloat(document.frm.total_all_box.value) + parseFloat(document.frm.sum_cost_per.value) + parseFloat(bulk);
														//box cal jsp profit
														document.frm.total_jsp_profit.value = (parseFloat(document.frm.total_per_unit2.value) * 1.2).toFixed(3);
														
														//box cal cdip profit
														document.frm.total_cdip_profit.value = (parseFloat(document.frm.total_jsp_profit.value) * 1.2).toFixed(3);
														
														//------------------------------------------------------------------------------//

														var bottle_bulk;
														//bulk
														if(document.frm.bottle_set_bulk.checked == true){
															bottle_bulk=(parseFloat(document.frm.bottle_bulk_cost.value) * parseFloat(document.frm.bottle_total_cost.value)).toFixed(3);
														}else{	
															bottle_bulk=0;	
														}
														document.frm.total_bulk_cost.value=parseFloat(bulk);
														//calculate cost bottle per tablet 
														document.frm.bottle_sum_cost_per.value = (parseFloat(document.frm.total_cost.value) * 60).toFixed(3);//fix float=3

														//bottle sum total
														document.frm.total_per_unit2.value=parseFloat(document.frm.total_all_box.value) + parseFloat(document.frm.sum_cost_per.value) + parseFloat(bulk);

														//bottle cal jsp profit
														document.frm.bottle_total_jsp_profit.value = parseFloat(document.frm.bottle_total_per_unit2.value) * 1.2;
														
														//bottle cal cdip profit
														document.frm.bottle_total_cdip_profit.value = parseFloat(document.frm.bottle_total_jsp_profit.value) * 1.2;

													}
												</script>
												<!--end function calculate total cost-->
												<input type="hidden" name="total_quantities" value="<?php echo $total_quantities?>">
												<input type="hidden" name="total_cost_unit" value="<?php echo $total_cost_unit?>">
												<td class="bd-right b-bottom cost-left">Total</td>
												<td class="bd-right b-bottom"><input type="text" name="total" id="total" OnChange="fncSumTotal();" value="<?php echo $rs['rm_total']?>"></td>
												<td class="bd-right b-bottom"><input type="text" name="total_rm_unit" id="total_rm_unit" value="mg" readonly></td>
												<td class="bd-right b-bottom cost-left">Cost per tab</td>
												<td class="b-bottom w10"><input type="text" name="cost_tab" id="cost_tab" value="<?php echo $rs['cost_product']?>" <?php if($rs_account['role_user']==3){echo 'readonly';}?>>
											</tr>
											<tr>
												<td rowspan="5" colspan="2"></td>
												<td class="bd-right"></td>
												<td class="bd-right b-bottom cost-left">Loss 10%</td>
												<td class="b-bottom w10"><input type="text" name="lost_10" id="lost_10" value="<?php echo $rs['lost10']?>" <?php if($rs_account['role_user']==3){echo 'readonly';}?>>
											</tr>
											<tr>
												<td class="bd-right"></td>
												<td class="bd-right b-bottom cost-left">Vat 7%</td>
												<td class="b-bottom w10"><input type="text" name="vat_7" id="vat_7" value="<?php echo $rs['vat']?>" <?php if($rs_account['role_user']==3){echo 'readonly';}?>>
											</tr>
											<tr>
												<td class="bd-right"></td>
												<td class="bd-right b-bottom cost-left">MOH</td>
												<td class="b-bottom w10"><input type="text" name="moh" id="moh" value="<?php echo $rs['moh']?>" <?php if($rs_account['role_user']==3){echo 'readonly';}?>>
											</tr>
											<tr>
												<td class="bd-right"></td>
												<td class="bd-right b-bottom cost-left">Profit per tablet</td>
												<td class="b-bottom w10"><input type="text" name="profit" id="profit" OnChange="fncSumTotal_All();" value="<?php echo $rs['profit_product']?>" <?php if($rs_account['role_user']==3){echo 'readonly';}?>>
											</tr>
											<tr>
												<!--function calculate total cost-->
												<script language="JavaScript">
													function fncSumTotal_All(){
														//calculate total all  
														document.frm.total_cost.value = (parseFloat(document.frm.lost_10.value) + parseFloat(document.frm.vat_7.value) + parseFloat(document.frm.moh.value) + parseFloat(document.frm.profit.value)).toFixed(3);//fix float=3
														
														//calculate cost box per tablet 
														document.frm.sum_cost_per.value = (parseFloat(document.frm.total_cost.value) * 30).toFixed(3);//fix float=3

														//box sum total
														document.frm.total_per_unit2.value = parseFloat(document.frm.sum_cost_per.value) + 25;

														//box cal jsp profit
														document.frm.total_jsp_profit.value = parseFloat(document.frm.total_per_unit2.value) * 1.2;
														
														//box cal cdip profit
														document.frm.total_cdip_profit.value = parseFloat(document.frm.total_jsp_profit.value) * 1.2;
														/*------------------------------------------------------------------------------------------*/

														//calculate cost bottle per tablet 
														document.frm.bottle_sum_cost_per.value = (parseFloat(document.frm.total_cost.value) * 60).toFixed(3);//fix float=3

														//bottle sum total
														document.frm.bottle_total_per_unit2.value = parseFloat(document.frm.bottle_sum_cost_per.value) + 30;

														//bottle cal jsp profit
														document.frm.bottle_total_jsp_profit.value = parseFloat(document.frm.bottle_total_per_unit2.value) * 1.2;
														
														//bottle cal cdip profit
														document.frm.bottle_total_cdip_profit.value = parseFloat(document.frm.bottle_total_jsp_profit.value) * 1.2;
													}
												</script>
												<!--end function calculate total cost-->
												<td class="bd-right"></td>
												<td class="bd-right cost-left">Total cost per tablet</td>
												<td><input type="text" name="total_cost" id="total_cost" value="<?php echo number_format($rs['total_cost_product'],3)?>" <?php if($rs_account['role_user']==3){echo 'readonly';}?>>
											</tr>
										</table>
										<?php
										$sql_pack="select * from costing_pack where id_costing_table='".$id."'";
										$res_pack=mysql_query($sql_pack) or die ('Error '.$sql_pack);
										$rs_pack=mysql_fetch_array($res_pack);
										$ck_set=split(",",$rs_pack['ck_set']);
										?>
										<script type="text/javascript">
											function ShowPack() {
												if (document.getElementById('boxCheck').checked) {
													document.getElementById('tbcost').style.display = '';
													document.getElementById('tbcost2').style.display = 'none';
												}
												else
												if (document.getElementById('bottleCheck').checked) {
													document.getElementById('tbcost').style.display = 'none';
													document.getElementById('tbcost2').style.display = '';
												}
											}
										</script>
										<input type="radio" onclick="javascript:ShowPack();" name="pack" id="boxCheck" value="5" <?php if($rs['id_type_package']==5){echo 'checked';}?>>
										Box set
										<input type="radio" onclick="javascript:ShowPack();" name="pack" id="bottleCheck" value="1" <?php if($rs['id_type_package']==1){echo 'checked';}?>>
										Bottle set
										<table width="30%" cellpadding="0" cellspacing="0" id="tbcost" <?php if($rs['id_type_package']!=5){echo 'style="display: none;"';}?>>
											<tr>
												<!--function calculate sum cost per tablet-->
												<script language="JavaScript">
													function fncSumCostTablet(){
														document.frm.sum_cost_per.value = (parseFloat(document.frm.num_cos_per.value) * parseFloat(document.frm.total_cost.value)).toFixed(3);//fix float=3
														
														var t1;
														var t2;
														var t3;
														var t4;
														var t5;
														var t6;
														var t7;
														var sum;
														//MOH
														if(document.frm.box_moh.checked == true){
															t1=parseFloat(document.frm.box_moh_cost.value).toFixed(3);
														}else{	
															t1=0;	
														} 	
														//Blister
														if(document.frm.box_set_blister.checked == true){
															t2=parseFloat(document.frm.box_blister_cost.value).toFixed(3);
														}else{	
															t2=0;	
														}
														//silica_gel
														if(document.frm.box_silica_gel.checked == true){
															t3=parseFloat(document.frm.silica_gel_cost.value).toFixed(3);
														}else{	
															t3=0;	
														}
														//aluminum
														if(document.frm.box_aluminum.checked == true){
															t4=parseFloat(document.frm.aluminum_cost.value).toFixed(3);
														}else{	
															t4=0;	
														}
														//box
														if(document.frm.box_box.checked == true){
															t5=parseFloat(document.frm.box_box_cost.value).toFixed(3);
														}else{	
															t5=0;	
														}
														//film shrink
														if(document.frm.box_film_shrink.checked == true){
															t6=parseFloat(document.frm.film_shrink_cost.value).toFixed(3);
														}else{	
															t6=0;	
														}
														//carton
														if(document.frm.box_carton.checked == true){
															t7=parseFloat(document.frm.carton_cost.value).toFixed(3);
														}else{	
															t7=0;	
														}
														sum=parseFloat(t1)+parseFloat(t2)+parseFloat(t3)+parseFloat(t4)+parseFloat(t5)+parseFloat(t6)+parseFloat(t7)+3;
														parseFloat(document.frm.total_all_box.value=sum).toFixed(3);
														
														var total_per;
														var total_all;
														total_per=parseFloat(document.frm.sum_cost_per.value);
														total_all=parseFloat(total_per)+parseFloat(sum);

														var bulk;
														//bulk
														if(document.frm.box_set_bulk.checked == true){
															bulk=(parseFloat(document.frm.box_bulk_cost.value) * parseFloat(document.frm.total_cost.value)).toFixed(3);
														}else{	
															bulk=0;	
														}
														document.frm.total_bulk_cost.value=parseFloat(bulk);

														document.frm.total_per_unit2.value=parseFloat(document.frm.total_all_box.value) + parseFloat(document.frm.sum_cost_per.value) + parseFloat(bulk);

														//cal jsp profit
														document.frm.total_jsp_profit.value=(parseFloat(document.frm.total_per_unit2.value) * 1.2).toFixed(3);
													
														//cal cdip profit
														document.frm.total_cdip_profit.value=(parseFloat(document.frm.total_jsp_profit.value) * 1.2).toFixed(3);
													}
												</script>
												<!--end function calculate sum cost per tablet-->
												<td class="bd-right b-bottom"><div style="float:left;margin-right:1%;">Cost per</div><div style="float:left;margin-right:1%;"><input type="text" name="num_cos_per" value="<?php if($rs['id_type_package']==5){if(is_numeric($id)){echo $rs_pack['num_unit'];}}else{echo '30';}?>" OnChange="fncSumCostTablet();"></div><div style="float:left;">'s</div></td>
												<td class="bd-right b-bottom"><input type="text" name="sum_cost_per" value="<?php if($rs['id_type_package']==5){echo number_format($rs_pack['sum_unit'],3);}?>" <?php if($rs_account['role_user']==3){echo 'readonly';}?>></td>								
											</tr>
											<script language="javascript">
												function fnccheck(){
													var t1;
													var t2;
													var t3;
													var t4;
													var t5;
													var t6;
													var t7;
													var t8;
													var sum;
													//MOH
													if(document.frm.box_moh.checked == true){
														t1=parseFloat(document.frm.box_moh_cost.value).toFixed(3);
													}else{	
														t1=0;	
													} 	
													//Blister
													if(document.frm.box_set_blister.checked == true){
														t2=parseFloat(document.frm.box_blister_cost.value).toFixed(3);
													}else{	
														t2=0;	
													}
													//silica_gel
													if(document.frm.box_silica_gel.checked == true){
														t3=parseFloat(document.frm.silica_gel_cost.value).toFixed(3);
													}else{	
														t3=0;	
													}
													//aluminum
													if(document.frm.box_aluminum.checked == true){
														t4=parseFloat(document.frm.aluminum_cost.value).toFixed(3);
													}else{	
														t4=0;	
													}
													//box
													if(document.frm.box_box.checked == true){
														t5=parseFloat(document.frm.box_box_cost.value).toFixed(3);
													}else{	
														t5=0;	
													}
													//film shrink
													if(document.frm.box_film_shrink.checked == true){
														t6=parseFloat(document.frm.film_shrink_cost.value).toFixed(3);
													}else{	
														t6=0;	
													}
													//carton
													if(document.frm.box_carton.checked == true){
														t7=parseFloat(document.frm.carton_cost.value).toFixed(3);
													}else{	
														t7=0;	
													}

													sum=parseFloat(t1)+parseFloat(t2)+parseFloat(t3)+parseFloat(t4)+parseFloat(t5)+parseFloat(t6)+parseFloat(t7)=+parseFloat(t8)+3;
													parseFloat(document.frm.total_all_box.value=sum).toFixed(3);
													
													var total_per;
													var total_all;
													total_per=parseFloat(document.frm.sum_cost_per.value);
													total_all=parseFloat(total_per)+parseFloat(sum);
													
													//cal total cost per unit
													parseFloat(document.frm.total_per_unit2.value=total_all);

													//cal jsp profit
													document.frm.total_jsp_profit.value=(parseFloat(document.frm.total_per_unit2.value) * 1.2).toFixed(3);
													
													//cal cdip profit
													document.frm.total_cdip_profit.value=(parseFloat(document.frm.total_jsp_profit.value) * 1.2).toFixed(3);
													
												}
												function fncBoxSet(){

													document.frm.total_per_unit2.value=(parseFloat(document.frm.total_all_box.value) + parseFloat(document.frm.sum_cost_per.value));

													document.frm.total_bulk_cost.value=parseFloat(document.frm.box_bulk_cost.value)*parseFloat(document.frm.total_cost.value);

													document.frm.total_per_unit2.value=(parseFloat(document.frm.total_all_box.value) + parseFloat(document.frm.sum_cost_per.value)+ parseFloat(document.frm.total_bulk_cost.value));

													//cal jsp profit
													document.frm.total_jsp_profit.value=(parseFloat(document.frm.total_per_unit2.value) * 1.2).toFixed(3);
													
													//cal cdip profit
													document.frm.total_cdip_profit.value=(parseFloat(document.frm.total_jsp_profit.value) * 1.2).toFixed(3);  
												}
												function fncBulkCost(){
													document.frm.total_bulk_cost.value=parseFloat(document.frm.box_bulk_cost.value)*parseFloat(document.frm.total_cost.value);

													document.frm.total_per_unit2.value=(parseFloat(document.frm.total_all_box.value) + parseFloat(document.frm.sum_cost_per.value)+ parseFloat(document.frm.total_bulk_cost.value));

													//cal jsp profit
													document.frm.total_jsp_profit.value=(parseFloat(document.frm.total_per_unit2.value) * 1.2).toFixed(3);
													
													//cal cdip profit
													document.frm.total_cdip_profit.value=(parseFloat(document.frm.total_jsp_profit.value) * 1.2).toFixed(3);
												}
												function fncBulk(){
													var bulk;
													//bulk
													if(document.frm.box_set_bulk.checked == true){
														bulk=(parseFloat(document.frm.box_bulk_cost.value) * parseFloat(document.frm.total_cost.value)).toFixed(3);
													}else{	
														bulk=0;	
													}
													document.frm.total_bulk_cost.value=parseFloat(bulk);

													document.frm.total_per_unit2.value=parseFloat(document.frm.total_all_box.value) + parseFloat(document.frm.sum_cost_per.value) + parseFloat(bulk);

													//cal jsp profit
													document.frm.total_jsp_profit.value=(parseFloat(document.frm.total_per_unit2.value) * 1.2).toFixed(3);
													
													//cal cdip profit
													document.frm.total_cdip_profit.value=(parseFloat(document.frm.total_jsp_profit.value) * 1.2).toFixed(3);
												}
											</script>
											<tr>
												<td class="bd-right b-bottom cost-left"><b>Box set</b></td>
												<td class="bd-right b-bottom cost-left"><input type="text" name="total_all_box" id="total_all_box" value="<?php if($rs['id_type_package']==5){if(is_numeric($id)){echo number_format($rs_pack['sum_per_set'],3);}}else{echo '25.000';}?>" OnChange="fncBoxSet();" <?php if($rs_account['role_user']==3){echo 'readonly';}?>></td>		
											</tr>
											<tr>
												<td class="bd-right b-bottom cost-left">
													<div style="float:left;margin-right:1%;"><input type="checkbox" name="box_set_bulk" id="box_set_bulk" value="15" OnClick="JavaScript:return fncBulk();" <?php if($rs['id_type_package']==5){if(is_numeric($id)){if(in_array('15',$ck_set)){echo 'checked';}}}else{echo 'checked';}?>>Bulk</div>
													<div style="float:left;"><input type="text" name="box_bulk_cost" id="box_bulk_cost" OnChange="fncBulkCost();" value="<?php if($rs['id_type_package']==5){if(is_numeric($id)){echo number_format($rs_pack['bulk'],3);}}else{echo '500';}?>" <?php if($rs_account['role_user']==3){echo 'readonly';}?>></div>
												</td>
												<td class="bd-right b-bottom cost-left"><input type="text" name="total_bulk_cost" id="total_bulk_cost" value="<?php if($rs['id_type_package']==5){if(is_numeric($id)){echo number_format($rs_pack['bulk_cost'],3);}}else{echo '500';}?>" <?php if($rs_account['role_user']==3){echo 'readonly';}?>></td>		
											</tr>
											<tr>
												<td class="bd-right b-bottom cost-left"><input type="checkbox" name="box_moh" id="box_moh" value="1" OnClick="JavaScript:return fnccheck();" <?php if($rs['id_type_package']==5){if(is_numeric($id)){if(in_array('1',$ck_set)){echo 'checked';}}}else{echo 'checked';}?>>MOH</td>
												<td class="bd-right b-bottom cost-left"><input type="text" name="box_moh_cost" id="box_moh_cost" value="<?php if($rs['id_type_package']==5){if(is_numeric($id)){echo number_format($rs_pack['moh'],3);}}else{echo '4';}?>" <?php if($rs_account['role_user']==3){echo 'readonly';}?>></td>			
											</tr>
											<tr>
												<td class="bd-right b-bottom cost-left"><input type="checkbox" name="box_set_blister" id="box_set_blister" value="2" OnClick="JavaScript:return fnccheck();" <?php if($rs['id_type_package']==5){if(is_numeric($id)){if(in_array('2',$ck_set)){echo 'checked';}}}else{echo 'checked';}?>>Blister</td>
												<td class="bd-right b-bottom cost-left"><input type="text" name="box_blister_cost" id="box_blister_cost" value="<?php if($rs['id_type_package']==5){if(is_numeric($id)){echo number_format($rs_pack['blister'],3);}}else{echo '4.50';}?>" <?php if($rs_account['role_user']==3){echo 'readonly';}?>></td>		
											</tr>
											<tr>
												<td class="bd-right b-bottom cost-left"><input type="checkbox" name="box_silica_gel" id="box_silica_gel" value="3" OnClick="JavaScript:return fnccheck();" <?php if($rs['id_type_package']==5){if(is_numeric($id)){if(in_array('3',$ck_set)){echo 'checked';}}}else{echo 'checked';}?>>Silica gel</td>
												<td class="bd-right b-bottom cost-left"><input type="text" name="silica_gel_cost" id="silica_gel_cost" value="<?php if($rs['id_type_package']==5){if(is_numeric($id)){echo number_format($rs_pack['silica_gel'],3);}}else{echo '0.50';}?>" <?php if($rs_account['role_user']==3){echo 'readonly';}?>></td>	
											</tr>
											<tr>
												<td class="bd-right b-bottom cost-left"><input type="checkbox" name="box_aluminum" id="box_aluminum" value="4" OnClick="JavaScript:return fnccheck();" <?php if($rs['id_type_package']==5){if(is_numeric($id)){if(in_array('4',$ck_set)){echo 'checked';}}}else{echo 'checked';}?>>Aluminum pouch</td>
												<td class="bd-right b-bottom cost-left"><input type="text" name="aluminum_cost" id="aluminum_cost" value="<?php if($rs['id_type_package']==5){if(is_numeric($id)){echo number_format($rs_pack['aluminum'],3);}}else{echo '1.50';}?>" <?php if($rs_account['role_user']==3){echo 'readonly';}?>></td>		
											</tr>
											<tr>
												<td class="bd-right b-bottom cost-left"><input type="checkbox" name="box_box" id="box_box" value="5" OnClick="JavaScript:return fnccheck();" <?php if($rs['id_type_package']==5){if(is_numeric($id)){if(in_array('5',$ck_set)){echo 'checked';}}}else{echo 'checked';}?>>Box</td>
												<td class="bd-right b-bottom cost-left"><input type="text" name="box_box_cost" id="box_box_cost" value="<?php if($rs['id_type_package']==5){if(is_numeric($id)){echo number_format($rs_pack['box'],3);}}else{echo '10.00';}?>" <?php if($rs_account['role_user']==3){echo 'readonly';}?>></td>		
											</tr>
											<tr>
												<td class="bd-right b-bottom cost-left"><input type="checkbox" name="box_film_shrink" id="box_film_shrink" value="6" OnClick="JavaScript:return fnccheck();" <?php if($rs['id_type_package']==5){if(is_numeric($id)){if(in_array('6',$ck_set)){echo 'checked';}}}else{echo 'checked';}?>>Film shrink</td>
												<td class="bd-right b-bottom cost-left"><input type="text" name="film_shrink_cost" id="film_shrink_cost" value="<?php if($rs['id_type_package']==5){if(is_numeric($id)){echo number_format($rs_pack['film_shrik'],3);}}else{echo '0.50';}?>" <?php if($rs_account['role_user']==3){echo 'readonly';}?>></td>		
											</tr>
											<tr>
												<td class="bd-right b-bottom cost-left"><input type="checkbox" name="box_carton" id="box_carton" value="7" OnClick="JavaScript:return fnccheck();" <?php if($rs['id_type_package']==5){if(is_numeric($id)){if(in_array('7',$ck_set)){echo 'checked';}}}else{echo 'checked';}?>>Carton + Delivery</td>
												<td class="bd-right b-bottom cost-left"><input type="text" name="carton_cost" id="carton_cost" value="<?php if($rs['id_type_package']==5){if(is_numeric($id)){echo number_format($rs_pack['carton'],3);}}else{echo '1.00';}?>" <?php if($rs_account['role_user']==3){echo 'readonly';}?>></td>		
											</tr>											
											<tr>
												
												<td class="bd-right b-bottom">Total cost per Unit <?php if($rs['id_type_package']==5){echo $rs_pack['num_unit'];}?> 's</td>
												<td class="bd-right b-bottom cost-left"><input type="text" name="total_per_unit2" id="total_per_unit2" value="<?php if($rs['id_type_package']==5){echo $rs_pack['total_set'];}?>" <?php if($rs_account['role_user']==3){echo 'readonly';}?>></td>		
											</tr>
											<tr>
												<!--function calculate sum cost per tablet-->
												<script language="JavaScript">
													function fncSumCostTablet3(){
														document.frm.total_jsp_profit.value = (parseFloat(document.frm.total_per_unit2.value) * parseFloat(document.frm.jsp_profit.value)).toFixed(3);//fix float=3

														document.frm.total_cdip_profit.value = (parseFloat(document.frm.total_jsp_profit.value) *1.2).toFixed(3);//fix float=3
													}
												</script>
												<!--end function calculate sum cost per tablet-->
												<td class="bd-right b-bottom cost-left"><div style="float:left;margin-right:1%;">JSP profit</div><div style="float:left;margin-right:1%;"><input type="text" name="jsp_profit" id="jsp_profit" value="<?php if($rs['id_type_package']==5){if(is_numeric($id)){echo $rs_pack['jsp_profit'];}}else{echo '1.2';}?>" OnChange="fncSumCostTablet3();" <?php if($rs_account['role_user']==3){echo 'readonly';}?>></div><div style="float:left;"></div></td>
												<td class="bd-right b-bottom cost-left"><input type="text" name="total_jsp_profit" id="total_jsp_profit" value="<?php if($rs['id_type_package']==5){echo $rs_pack['jsp_profit_cost'];}?>" <?php if($rs_account['role_user']==3){echo 'readonly';}?>></td>		
											</tr>
											<tr>
												<!--function calculate sum cost per tablet-->
												<script language="JavaScript">
													function fncSumCostTablet4(){
														document.frm.total_cdip_profit.value = (parseFloat(document.frm.total_jsp_profit.value) * parseFloat(document.frm.cdip_profit.value)).toFixed(3);//fix float=3
													}
												</script>
												<!--end function calculate sum cost per tablet-->
												<td class="bd-right b-bottom cost-left"><div style="float:left;margin-right:2%;">CDIP profit</div><div style="float:left;margin-right:2%;"><input type="text" name="cdip_profit" id="cdip_profit" value="<?php if($rs['id_type_package']==5){if(is_numeric($id)){echo $rs_pack['cdip_profit'];}}else{echo '1.2';}?>" OnChange="fncSumCostTablet4();" <?php if($rs_account['role_user']==3){echo 'readonly';}?>></div><div style="float:left;"></div></td>
												<td class="bd-right b-bottom cost-left"><input type="text" name="total_cdip_profit" id="total_cdip_profit" value="<?php if($rs['id_type_package']==5){echo $rs_pack['cdip_profit_cost'];}?>" <?php if($rs_account['role_user']==3){echo 'readonly';}?>></td>	
											</tr>
										</table>
										<table width="30%" cellpadding="0" cellspacing="0" id="tbcost2" <?php if($rs['id_type_package']!=1){echo 'style="display: none;"';}?>>
											<tr>
												<!--function calculate sum cost per tablet-->
												<script language="JavaScript">
													function fncSumCostTabletBottle(){
														document.frm.bottle_sum_cost_per.value = (parseFloat(document.frm.bottle_num_cos_per.value) * parseFloat(document.frm.total_cost.value)).toFixed(3);//fix float=3
													}
												</script>
												<!--end function calculate sum cost per tablet-->
												<td class="bd-right b-bottom"><div style="float:left;margin-right:1%;">Cost per</div><div style="float:left;margin-right:1%;"><input type="text" name="bottle_num_cos_per" value="<?php if($rs['id_type_package']==1){if(is_numeric($id)){echo $rs_pack['num_unit'];}}else{echo '60';}?>" OnChange="fncSumCostTabletBottle();"></div><div style="float:left;">'s</div></td>
												<td class="bd-right b-bottom"><input type="text" name="bottle_sum_cost_per" value="<?php if($rs['id_type_package']==1){echo $rs_pack['sum_unit'];}?>" <?php if($rs_account['role_user']==3){echo 'readonly';}?>></td>								
											</tr>
											<script language="javascript">
												function fnccheckBottle(){
													var bottle1;
													var bottle2;
													var bottle3;
													var bottle4;
													var bottle5;
													var bottle6;
													var bottle7;
													var bottleSum;
													//MOH
													if(document.frm.bottle_moh.checked == true){
														bottle1=parseFloat(document.frm.bottle_moh_cost.value).toFixed(3);
													}else{	
														bottle1=0;	
													} 	
													//silica_gel
													if(document.frm.bottle_silica_gel.checked == true){
														bottle2=parseFloat(document.frm.bottle_silica_gel_cost.value).toFixed(3);
													}else{	
														bottle2=0;	
													}
													//bottle
													if(document.frm.bottle_bottle.checked == true){
														bottle3=parseFloat(document.frm.bottle_bottle_cost.value).toFixed(3);
													}else{	
														bottle3=0;	
													}
													//Sticker label
													if(document.frm.bottle_sticker.checked == true){
														bottle4=parseFloat(document.frm.bottle_sticker_cost.value).toFixed(3);
													}else{	
														bottle4=0;	
													}
													//box
													if(document.frm.bottle_box.checked == true){
														bottle5=parseFloat(document.frm.bottle_box_cost.value).toFixed(3);
													}else{	
														bottle5=0;	
													}
													//film shrink
													if(document.frm.bottle_film_shrink.checked == true){
														bottle6=parseFloat(document.frm.bottle_film_cost.value).toFixed(3);
													}else{	
														bottle6=0;	
													}
													//carton
													if(document.frm.bottle_carton.checked == true){
														bottle7=parseFloat(document.frm.bottle_carton_cost.value).toFixed(3);
													}else{	
														bottle7=0;	
													}
													
													bottleSum=parseFloat(bottle1)+parseFloat(bottle2)+parseFloat(bottle3)+parseFloat(bottle4)+parseFloat(bottle5)+parseFloat(bottle6)+parseFloat(bottle7)-1;
													parseFloat(document.frm.total_all_bottle2.value=bottleSum).toFixed(3);

													var bottle_total_per;
													var bottle_total_all;
													bottle_total_per=parseFloat(document.frm.bottle_sum_cost_per.value);
													bottle_total_all=parseFloat(bottle_total_per)+parseFloat(bottleSum);
													
													//cal total cost per unit
													parseFloat(document.frm.bottle_total_per_unit2.value=bottle_total_all);

													//cal jsp profit
													document.frm.bottle_total_jsp_profit.value=(parseFloat(document.frm.bottle_total_per_unit2.value) * 1.2).toFixed(3);
													
													//cal cdip profit
													document.frm.bottle_total_cdip_profit.value=(parseFloat(document.frm.bottle_total_jsp_profit.value) * 1.2).toFixed(3);
													
													
													
												}
											</script>											
											<tr>
												<td class="bd-right b-bottom cost-left"><b>Bottle set</b></td>
												<td class="bd-right b-bottom cost-left"><input type="text" name="total_all_bottle2" id="total_all_bottle2" value="<?php if($rs['id_type_package']==1){if(is_numeric($id)){echo $rs_pack['sum_per_set'];}}else{echo '30.000';}?>" <?php if($rs_account['role_user']==3){echo 'readonly';}?>></td>			
											</tr>
											<tr>
												<td class="bd-right b-bottom cost-left">
													<div style="float:left;margin-right:1%;"><input type="checkbox" name="bottle_set_bulk" id="bottle_set_bulk" value="15" OnClick="JavaScript:return fncBulk();" <?php if($rs['id_type_package']==5){if(is_numeric($id)){if(in_array('15',$ck_set)){echo 'checked';}}}else{echo 'checked';}?>>Bulk</div>
													<div style="float:left;"><input type="text" name="bottle_bulk_cost" id="bottle_bulk_cost" OnChange="fncBulkCost();" value="<?php if($rs['id_type_package']==5){if(is_numeric($id)){echo number_format($rs_pack['bulk'],3);}}else{echo '500';}?>" <?php if($rs_account['role_user']==3){echo 'readonly';}?>></div>
												</td>
												<td class="bd-right b-bottom cost-left"><input type="text" name="bottle_total_bulk_cost" id="bottle_total_bulk_cost" value="<?php if($rs['id_type_package']==5){if(is_numeric($id)){echo number_format($rs_pack['bulk_cost'],3);}}else{echo '500';}?>" <?php if($rs_account['role_user']==3){echo 'readonly';}?>></td>		
											</tr>
											<tr>
												<td class="bd-right b-bottom cost-left"><input type="checkbox" name="bottle_moh" id="bottle_moh" value="8" OnClick="JavaScript:return fnccheckBottle();" <?php if($rs['id_type_package']==1){if(is_numeric($id)){if(in_array('8',$ck_set)){echo 'checked';}}}else{echo 'checked';}?>>MOH</td>
												<td class="bd-right b-bottom cost-left"><input type="text" name="bottle_moh_cost" id="bottle_moh_cost" value="<?php if($rs['id_type_package']==1){if(is_numeric($id)){echo $rs_pack['moh'];}}else{echo '4';}?>" <?php if($rs_account['role_user']==3){echo 'readonly';}?>></td>			
											</tr>
											<tr>
												<td class="bd-right b-bottom cost-left"><input type="checkbox" name="bottle_silica_gel" id="bottle_silica_gel" value="9" OnClick="JavaScript:return fnccheckBottle();"  <?php if($rs['id_type_package']==1){if(is_numeric($id)){if(in_array('9',$ck_set)){echo 'checked';}}}else{echo 'checked';}?>>Silica gel</td>
												<td class="bd-right b-bottom cost-left"><input type="text" name="bottle_silica_gel_cost" id="bottle_silica_gel_cost" value="<?php if($rs['id_type_package']==1){if(is_numeric($id)){echo $rs_pack['silica_gel'];}}else{echo '0.50';}?>" <?php if($rs_account['role_user']==3){echo 'readonly';}?>></td>	
											</tr>
											<tr>
												<td class="bd-right b-bottom cost-left"><input type="checkbox" name="bottle_bottle" id="bottle_bottle" value="10" OnClick="JavaScript:return fnccheckBottle();" <?php if($rs['id_type_package']==1){if(is_numeric($id)){if(in_array('10',$ck_set)){echo 'checked';}}}else{echo 'checked';}?>>Bottle</td>
												<td class="bd-right b-bottom cost-left"><input type="text" name="bottle_bottle_cost" id="bottle_bottle_cost" value="<?php if($rs['id_type_package']==1){if(is_numeric($id)){echo $rs_pack['bottle'];}}else{echo '10.00';}?>" <?php if($rs_account['role_user']==3){echo 'readonly';}?>></td>		
											</tr>
											<tr>
												<td class="bd-right b-bottom cost-left"><input type="checkbox" name="bottle_sticker" id="bottle_sticker" value="11" OnClick="JavaScript:return fnccheckBottle();" <?php if($rs['id_type_package']==1){if(is_numeric($id)){if(in_array('11',$ck_set)){echo 'checked';}}}else{echo 'checked';}?>>Sticker label</td>
												<td class="bd-right b-bottom cost-left"><input type="text" name="bottle_sticker_cost" id="bottle_sticker_cost" value="<?php if($rs['id_type_package']==1){if(is_numeric($id)){echo $rs_pack['sticker'];}}else{echo '5.00';}?>" <?php if($rs_account['role_user']==3){echo 'readonly';}?>></td>	
											</tr>
											<tr>
												<td class="bd-right b-bottom cost-left"><input type="checkbox" name="bottle_box" id="bottle_box" value="12" OnClick="JavaScript:return fnccheckBottle();" <?php if($rs['id_type_package']==1){if(is_numeric($id)){if(in_array('12',$ck_set)){echo 'checked';}}}else{echo 'checked';}?>>Box</td>
												<td class="bd-right b-bottom cost-left"><input type="text" name="bottle_box_cost" id="bottle_box_cost" value="<?php if($rs['id_type_package']==1){if(is_numeric($id)){echo $rs_pack['box'];}}else{echo '10.00';}?>" <?php if($rs_account['role_user']==3){echo 'readonly';}?>></td>		
											</tr>
											<tr>
												<td class="bd-right b-bottom cost-left"><input type="checkbox" name="bottle_film_shrink" id="bottle_film_shrink" value="13" OnClick="JavaScript:return fnccheckBottle();" <?php if($rs['id_type_package']==1){if(is_numeric($id)){if(in_array('13',$ck_set)){echo 'checked';}}}else{echo 'checked';}?>>Film shrink</td>
												<td class="bd-right b-bottom cost-left"><input type="text" name="bottle_film_cost" id="bottle_film_cost" value="<?php if($rs['id_type_package']==1){if(is_numeric($id)){echo $rs_pack['film_shrink'];}}else{echo '0.50';}?>" <?php if($rs_account['role_user']==3){echo 'readonly';}?>></td>		
											</tr>
											<tr>
												<td class="bd-right b-bottom cost-left"><input type="checkbox" name="bottle_carton" id="bottle_carton" value="14" OnClick="JavaScript:return fnccheckBottle();" <?php if($rs['id_type_package']==1){if(is_numeric($id)){if(in_array('14',$ck_set)){echo 'checked';}}}else{echo 'checked';}?>>Carton + Delivery</td>
												<td class="bd-right b-bottom cost-left"><input type="text" name="bottle_carton_cost" id="bottle_carton_cost" value="<?php if($rs['id_type_package']==1){if(is_numeric($id)){echo $rs_pack['carton'];}}else{echo '1.00';}?>" <?php if($rs_account['role_user']==3){echo 'readonly';}?>></td>		
											</tr>
											<tr>
												
												<td class="bd-right b-bottom">Total cost per Unit <?php if($rs['id_type_package']==1){echo $rs_pack['num_unit'];}?> 's</td>
												<td class="bd-right b-bottom cost-left"><input type="text" name="bottle_total_per_unit2" id="bottle_total_per_unit2" value="<?php if($rs['id_type_package']==1){echo $rs_pack['total_set'];}?>" <?php if($rs_account['role_user']==3){echo 'readonly';}?>></td>		
											</tr>
											<tr>
												<!--function calculate sum cost per tablet-->
												<script language="JavaScript">
													function fncSumCostTabletBottle3(){
														document.frm.bottle_total_jsp_profit.value = (parseFloat(document.frm.bottle_total_per_unit2.value) * parseFloat(document.frm.bottle_jsp_profit.value)).toFixed(3);//fix float=3

														document.frm.bottle_total_cdip_profit.value = (parseFloat(document.frm.bottle_total_jsp_profit.value) *1.2).toFixed(3);//fix float=3
													}
												</script>
												<!--end function calculate sum cost per tablet-->
												<td class="bd-right b-bottom cost-left"><div style="float:left;margin-right:1%;">JSP profit</div><div style="float:left;margin-right:1%;"><input type="text" name="bottle_jsp_profit" id="bottle_jsp_profit" value="<?php if($rs['id_type_package']==1){echo $rs_pack['jsp_profit'];}else{echo '1.2';}?>" OnChange="fncSumCostTabletBottle3();" <?php if($rs_account['role_user']==3){echo 'readonly';}?>></div><div style="float:left;"></div></td>
												<td class="bd-right b-bottom cost-left"><input type="text" name="bottle_total_jsp_profit" id="bottle_total_jsp_profit" value="<?php if($rs['id_type_package']==1){echo $rs_pack['jsp_profit_cost'];}?>" <?php if($rs_account['role_user']==3){echo 'readonly';}?>></td>		
											</tr>
											<tr>
												<!--function calculate sum cost per tablet-->
												<script language="JavaScript">
													function fncSumCostTabletBottle4(){
														document.frm.bottle_total_cdip_profit.value = (parseFloat(document.frm.bottle_total_jsp_profit.value) * parseFloat(document.frm.bottle_cdip_profit.value)).toFixed(3);//fix float=3
													}
												</script>
												<!--end function calculate sum cost per tablet-->
												<td class="bd-right b-bottom cost-left"><div style="float:left;margin-right:2%;">CDIP profit</div><div style="float:left;margin-right:2%;"><input type="text" name="bottle_cdip_profit" id="bottle_cdip_profit" value="<?php if($rs['id_type_package']==1){echo $rs_pack['cdip_profit'];}else{echo '1.2';}?>" OnChange="fncSumCostTabletBottle4();" <?php if($rs_account['role_user']==3){echo 'readonly';}?>></div><div style="float:left;"></div></td>
												<td class="bd-right b-bottom cost-left"><input type="text" name="bottle_total_cdip_profit" id="bottle_total_cdip_profit" value="<?php if($rs['id_type_package']==1){echo $rs_pack['cdip_profit_cost'];}?>" <?php if($rs_account['role_user']==3){echo 'readonly';}?>></td>	
											</tr>
										</table>
									</div><!--end tab1-->
									
								</div>
						</div>
					</td>		
				<tr>
					<td class="b-top">
						<div class="large-4 columns">
							<?php if(!is_numeric($id)){?>
							<input type="button" name="save_data" id="save_data" value="<?php echo $button?>" class="button-create" OnClick="frm.hdnCmd.value='save_data';JavaScript:return fncSubmit();">
							<?php }else{?>
							<input type="button" name="update_data" id="update_data" value="<?php echo $button?>" class="button-create" OnClick="frm.hdnCmd.value='update_data';JavaScript:return fncSubmit();">
							<input type="button" name="finished_data" id="finished_data" value="Finished" class="button-create" OnClick="frm.hdnCmd.value='finished_data';JavaScript:return fncSubmit();">
							<?php }?>
							<input type="button" value="Close" class="button-create" onclick="window.location.href='costing-table.php'">
						</div>
					</td>
				</tr>
			</table>
			</form>
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
