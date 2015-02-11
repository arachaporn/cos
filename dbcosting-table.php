<?php
ob_start();
@session_start();
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
</head>
<body>
<?php
$pages=$_POST['pages'];
$date=date("Y-m-d");
$modify=date("Y-m-d H:i:s");

$id=$_POST['mode'];
if($id=='New'){
	$pages=$_POST['nextstep']+1;
	$factory=$_POST['factory'];

	// ZAMACHITA meeting
	$post_product_name = mysql_real_escape_string($_POST['product_name']);

	$sql="insert into costing_factory(id_product_cate,id_product,product_name";
	$sql .=",id_manufacturer,id_product_appearance,id_type_sub_product";
	$sql .=",id_factory_weight,moq,create_by,create_date)";
	$sql .="value('".$_POST['product_cate']."','".$_POST['id_product']."'";
	$sql .=",'".$post_product_name."','".$_POST['factory']."'";
	$sql .=",'".$_POST['product_app']."','".$_POST['sub_app']."'";
	$sql .=",'".$_POST['weight']."','".$_POST['moq']."'";
	$sql .=",'".$rs_account['id_account']."','".$date."')";
	$res=mysql_query($sql) or die ('Error '.$sql);
	$id_q=mysql_insert_id();
?>
	<script>
		window.location.href='ac-costing-table.php?id_u=<?=$id_q?>&fac=<?=$factory?>&p=<?=$pages?>';
	</script>
<?php
}
else{
if($_POST['nextstep']==1){
	$pages=$_POST['nextstep']+1;
	$factory=$_POST['factory'];
	
	// ZAMACHITA meeting
	$post_product_name = mysql_real_escape_string($_POST['product_name']);

	$sql="update costing_factory set id_product_cate='".$_POST['product_cate']."'";
	$sql .=",id_product='".$_POST['id_product']."'";
	$sql .=",product_name='".$post_product_name."'";
	$sql .=",id_manufacturer='".$_POST['factory']."'";
	$sql .=",id_product_appearance='".$_POST['product_app']."'";
	$sql .=",id_type_sub_product='".$_POST['sub_app']."'";
	$sql .=",id_factory_weight='".$_POST['weight']."',moq='".$_POST['moq']."'";
	$sql .=" where id_costing_factory='".$_POST['costing']."'";
	$res=mysql_query($sql) or die ('Error '.$sql);
?>
	<script>
		window.location.href='ac-costing-table.php?id_u=<?=$id?>&fac=<?=$factory?>&p=<?=$pages?>';
	</script>
<?php }
elseif($_POST['nextstep']==2){	
	
	$pages=$_POST['nextstep']+1;
	$factory=$_POST['factory'];
	$costing=$_POST['costing'];
    $date=date('Y-m-d');
	$id_roc=$_POST['mode'];
	/*add roc rm*/
	if($_POST["hdnCmd"] == "add_rm"){		

		// ZAMACHITA meeting
		$post_rm_name = mysql_real_escape_string($_POST['rm_name']);


		$sql="insert into costing_rm(id_roc,id_product,id_rm";
		$sql .=",rm_name,quantities,unit,rm_cost,cost_unit";
		$sql .=",create_by,create_date,rev)";
		$sql .=" values('".$id_roc."','".$_POST['id_product']."'";
		$sql .=",'".$_POST['id_rm']."','".$post_rm_name."'";
		$sql .=",'".$_POST['rm_quantities']."','mg','".$_POST['rm_price']."'";
		$sql .=",'".$_POST['rm_cost']."','".$rs_account['id_account']."'";
		$sql .=",'".$date."','0')";
		$rs=mysql_query($sql) or die ('Error '.$sql);			
	?>
		<script>	
			window.location.href='ac-costing-table.php?id_u=<?=$id_roc?>&fac=<?=$factory?>&p=2';
		</script>
	<?php }

	/*update roc rm*/
	if($_POST["hdnCmd"] == "update_rm"){

		// ZAMACHITA meeting
		$post_rm_name2 = mysql_real_escape_string($_POST['rm_name2']);

		$sql = "update costing_rm set id_rm='".$_POST['id_rm2']."'";
		$sql .=",rm_name='".$post_rm_name2."'";
		$sql .=",quantities='".$_POST['rm_quantities2']."'";
		$sql .= ",rm_cost='".$_POST['rm_price2']."'";
		$sql .= ",cost_unit='".$_POST['rm_cost2']."'";
		$sql .=" where id_costing_rm = '".$_POST["id_costing_rm"]."' ";
		$res = mysql_query($sql) or die ('Error '.$sql);
	?>
		<script>
			window.location.href='ac-costing-table.php?id_u=<?=$id_roc?>&fac=<?=$factory?>&p=2';
		</script>
	<?php }

	if($factory==1){
		$sql="update costing_factory set ";
		$sql .="total_weight='".$_POST['total_weight']."'";
		$sql .=",total_weight_capsule='".$_POST['total_weight_capsule']."'";
		$sql .=",other_excipients='".$_POST['other_excipients']."'";
		$sql .=",sum_exp_cost='".$_POST['sum_other_exp']."'";
		$sql .=",cost_tab='".$_POST['cost_tab']."',lost_10='".$_POST['lost_10']."'";
		$sql .=",moh_mixing='".$_POST['moh']."',amber_glass='".$_POST['amber_glass']."'";
		$sql .=",moh_packing='".$_POST['moh_packing']."'";
		$sql .=",vat_7='".$_POST['vat_7']."',moh='".$_POST['moh']."'";
		$sql .=",profit='".$_POST['profit']."',total_cost='".$_POST['total_cost']."'";
		$sql .=",jsp_profit_cost='".$_POST['jsp_profit_cost']."'";
		$sql .=",cdip_profit_cost='".$_POST['cdip_profit_cost']."'";
		$sql .=",total_bluk='".$_POST['total_bluk']."'";
		$sql .="where id_costing_factory='".$_POST['costing']."'";
		$res=mysql_query($sql) or die ('Error '.$sql);	
		
		$sql_ck="select * from costing_pack_blister";
		$sql_ck .=" where id_costing_factory='".$_POST['costing']."'";
		$res_ck=mysql_query($sql_ck) or die ('Error '.$sql_ck);
		$rs_ck=mysql_fetch_array($res_ck);
		if(!$rs_ck){
			$sql_blister="insert into costing_pack_blister(id_costing_factory)";
			$sql_blister .=" value('".$_POST['costing']."')";
			$res_blister=mysql_query($sql_blister) or die ('Error '.$sql_blister);
		}
	}elseif($factory==3){
		$sql_costing="select * from costing_factory_prima";
		$sql_costing .=" where id_costing_factory='".$costing."'";
		$res_costing=mysql_query($sql_costing) or die ('Error '.$sql_costing);
		$rs_costing=mysql_fetch_array($res_costing);
		if($rs_costing){
			$sql_prima="update costing_factory_prima";
			$sql_prima .=" set total_weight='".$_POST['total_weight']."'";
			$sql_prima .=",other_excipients='".$_POST['other_excipients']."'";
			$sql_prima .=",sum_exp_cost='".$_POST['sum_other_exp']."'";
			$sql_prima .=",cost_tab='".$_POST['cost_tab']."'";
			$sql_prima .=",prima_filling_moh='".$_POST['filling_moh']."'";
			$sql_prima .=",prima_total_bottle='".$_POST['total_bottle']."'";
			$sql_prima .=",prima_bottle_set='".$_POST['bottle_set']."'";
			$sql_prima .=",prima_glass_bottle='".$_POST['glass_bottle']."'";
			$sql_prima .=",prima_screw_cap='".$_POST['screw_cap']."'";
			$sql_prima .=",prima_moh='".$_POST['moh']."'";
			$sql_prima .=",prima_total_cost='".$_POST['total_cost']."'";
			$sql_prima .=",prima_loss_15='".$_POST['loss_15']."'";
			$sql_prima .=",prima_vat_7='".$_POST['vat_7']."'";
			$sql_prima .=",prima_total_cost2='".$_POST['total_cost2']."'";
			$sql_prima .=",prima_profit_cost='".$_POST['prima_profit_cost']."'";
			$sql_prima .=",cdip_profit_cost='".$_POST['cdip_profit_cost']."'";
			$sql_prima .=",total_bluk='".$_POST['total_bluk']."'";
			$sql_prima .=" where id_costing_factory='".$costing."'";
			$res_prima=mysql_query($sql_prima) or die ('Error '.$sql_prima);
		}else{
			$sql_prima="insert into costing_factory_prima(id_costing_factory";
			$sql_prima .=",total_weight,other_excipients,sum_exp_cost,cost_tab";
			$sql_prima .=",prima_filling_moh,prima_total_bottle";
			$sql_prima .=",prima_bottle_set,prima_glass_bottle,prima_screw_cap";
			$sql_prima .=",prima_moh,prima_total_cost,prima_loss_15,prima_vat_7";
			$sql_prima .=",prima_total_cost2,prima_profit_cost,cdip_profit_cost";
			$sql_prima .=",total_bluk) values";
			$sql_prima .="('".$costing."','".$_POST['total_weight']."'";
			$sql_prima .=",'".$_POST['other_excipients']."'";
			$sql_prima .=",'".$_POST['sum_other_exp']."'";
			$sql_prima .=",'".$_POST['cost_tab']."','".$_POST['filling_moh']."'";
			$sql_prima .=",'".$_POST['total_bottle']."','".$_POST['bottle_set']."'";
			$sql_prima .=",'".$_POST['glass_bottle']."'";
			$sql_prima .=",'".$_POST['screw_cap']."','".$_POST['moh']."'";
			$sql_prima .=",'".$_POST['total_cost']."','".$_POST['loss_15']."'";
			$sql_prima .=",'".$_POST['vat_7']."','".$_POST['total_cost2']."'";
			$sql_prima .=",'".$_POST['prima_profit_cost']."'";
			$sql_prima .=",'".$_POST['cdip_profit_cost']."'";
			$sql_prima .=",'".$_POST['total_bluk']."')";
			$res_prima =mysql_query($sql_prima ) or die ('Error '.$sql_prima);	
		}
	}//factory prima food
}
elseif($_POST['nextstep']==3){	
	$pages=$_POST['nextstep']+1;
	$factory=$_POST['factory'];
	
	if($factory==1){
		if(($_POST['id_product_appearance']==1) || ($_POST['id_product_appearance']==2) || ($_POST['id_product_appearance']==3)){
			if($_POST['pack']==1){
				/*add blister box*/
				$blister_box_array=$_POST['blister_box'];
				$tag_string_blister_box="";
				while (list ($key_blister_box,$val_blister_box) = @each ($blister_box_array)) {
				//echo "$val,";
				$tag_string_blister_box.=$val_blister_box.",";
				}
				$blister_box=substr($tag_string_blister_box,0,(strLen($tag_string_blister_box)-1));// remove the last , from string
				
				/*add blister box*/
				$num_blister_array=$_POST['num_blister'];
				$tag_string_num_blister="";
				while (list ($key_num_blister,$val_num_blister) = @each ($num_blister_array)) {
				//echo "$val,";
				$tag_string_num_blister.=$val_num_blister.",";
				}
				$num_blister=substr($tag_string_num_blister,0,(strLen($tag_string_num_blister)-1));// remove the last , from string
				
				/*add blister box*/
				$cost_blister_array=$_POST['cost_blister'];
				$tag_string_cost_blister="";
				while (list ($key_cost_blister,$val_cost_blister) = @each ($cost_blister_array)) {
				//echo "$val,";
				$tag_string_cost_blister.=$val_cost_blister.",";
				}
				$cost=substr($tag_string_cost_blister,0,(strLen($tag_string_cost_blister)-1));// remove the last , from string
				
			}elseif($_POST['pack']==2){

				//if($_POST['bottle']==5){$bottle = ',5';}
				/*add blister box*/
				$cost_bottle_detail_array=$_POST['cost_bottle_detail'];
				$tag_string_cost_bottle_detail="";
				while (list ($key_cost_bottle_detail,$val_cost_bottle_detail) = @each ($cost_bottle_detail_array)) {
				//echo "$val,";
				$tag_string_cost_bottle_detail.=$val_cost_bottle_detail.",";
				}
				$cost=substr($tag_string_cost_bottle_detail,0,(strLen($tag_string_cost_bottle_detail)-1));// remove the last , from string

				/*add blister box*/
				$bottle_detail_array=$_POST['bottle_detail'];
				$tag_string_bottle_detail="";
				while (list ($key_bottle_detail,$val_bottle_detail) = @each ($bottle_detail_array)) {
				//echo "$val,";
				$tag_string_bottle_detail.=$val_bottle_detail.",";
				}
				$bottle2=substr($tag_string_bottle_detail,0,(strLen($tag_string_bottle_detail)-1));// remove the last , from string
				
				/*add blister box*/
				$bottle_detail2_array=$_POST['bottle_detail2'];
				$tag_string_cost_bottle_detail="";
				while (list ($key_bottle_detail2,$val_bottle_detail2) = @each ($bottle_detail2_array)) {
				//echo "$val,";
				$tag_string_bottle_detail2.=$val_bottle_detail2.",";
				}
				$bottle_detail2=substr($tag_string_bottle_detail2,0,(strLen($tag_string_bottle_detail2)-1));// remove the last , from string

				/*add blister box*/
				$cost_bottle_detail_array=$_POST['cost_bottle_detail2'];
				$tag_string_cost_bottle_detail="";
				while (list ($key_cost_bottle_detail,$val_cost_bottle_detail) = @each ($cost_bottle_detail_array)) {
				//echo "$val,";
				$tag_string_cost_bottle_detail.=$val_cost_bottle_detail.",";
				}
				$cost2=substr($tag_string_cost_bottle_detail,0,(strLen($tag_string_cost_bottle_detail)-1));// remove the last , from string
				
				//$blister_box =$bottle2.$bottle;
				$blister_box =$bottle2;			
				
				/*add blister box*/
				$num_blister_array=$_POST['num_bottle'];
				$tag_string_num_blister="";
				while (list ($key_num_blister,$val_num_blister) = @each ($num_blister_array)) {
				//echo "$val,";
				$tag_string_num_blister.=$val_num_blister.",";
				}
				$num_blister=substr($tag_string_num_blister,0,(strLen($tag_string_num_blister)-1));// remove the last , from string
				
				/*add blister box*/
				$cost_blister_array=$_POST['cost_blister'];
				$tag_string_cost_blister="";
				while (list ($key_cost_blister,$val_cost_blister) = @each ($cost_blister_array)) {
				//echo "$val,";
				$tag_string_cost_blister.=$val_cost_blister.",";
				}
				$cost=substr($tag_string_cost_blister,0,(strLen($tag_string_cost_blister)-1));// remove the last , from string
			}	
		}elseif($_POST['id_product_appearance']==6){
			/*instant drink*/
			if($_POST['bottle_cap']==1){
				/*add blister box*/
				$blister_box_array=$_POST['blister_box'];
				$tag_string_blister_box="";
				while (list ($key_blister_box,$val_blister_box) = @each ($blister_box_array)) {
				//echo "$val,";
				$tag_string_blister_box.=$val_blister_box.",";
				}
				$blister_box=substr($tag_string_blister_box,0,(strLen($tag_string_blister_box)-1));// remove the last , from string
				
				/*add blister box*/
				$num_blister_array=$_POST['num_blister'];
				$tag_string_num_blister="";
				while (list ($key_num_blister,$val_num_blister) = @each ($num_blister_array)) {
				//echo "$val,";
				$tag_string_num_blister.=$val_num_blister.",";
				}
				$num_blister=substr($tag_string_num_blister,0,(strLen($tag_string_num_blister)-1));// remove the last , from string
				
				/*add blister box*/
				$cost_blister_array=$_POST['cost_blister'];
				$tag_string_cost_blister="";
				while (list ($key_cost_blister,$val_cost_blister) = @each ($cost_blister_array)) {
				//echo "$val,";
				$tag_string_cost_blister.=$val_cost_blister.",";
				}
				$cost=substr($tag_string_cost_blister,0,(strLen($tag_string_cost_blister)-1));// remove the last , from string
			
			}elseif($_POST['bottle_cap']==2){

				//if($_POST['bottle']==5){$bottle = ',5';}
				$blister_box_array=$_POST['blister_box'];
				$tag_string_blister_box="";
				while (list ($key_blister_box,$val_blister_box) = @each ($blister_box_array)) {
				//echo "$val,";
				$tag_string_blister_box.=$val_blister_box.",";
				}
				$blister_box=substr($tag_string_blister_box,0,(strLen($tag_string_blister_box)-1));// remove the last , from string
				
				/*add blister box*/
				$num_blister_array=$_POST['num_blister'];
				$tag_string_num_blister="";
				while (list ($key_num_blister,$val_num_blister) = @each ($num_blister_array)) {
				//echo "$val,";
				$tag_string_num_blister.=$val_num_blister.",";
				}
				$num_blister=substr($tag_string_num_blister,0,(strLen($tag_string_num_blister)-1));// remove the last , from string
				
				/*add blister box*/
				$cost_blister_array=$_POST['cost_blister'];
				$tag_string_cost_blister="";
				while (list ($key_cost_blister,$val_cost_blister) = @each ($cost_blister_array)) {
				//echo "$val,";
				$tag_string_cost_blister.=$val_cost_blister.",";
				}
				$cost=substr($tag_string_cost_blister,0,(strLen($tag_string_cost_blister)-1));// remove the last , from string
				
				//$blister_box =$bottle2.$bottle;							
			}
		}elseif(($_POST['id_product_appearance']==4) || ($_POST['id_product_appearance']==5)){
			/*add blister box*/
			$blister_box_array=$_POST['blister_box'];
			$tag_string_blister_box="";
			while (list ($key_blister_box,$val_blister_box) = @each ($blister_box_array)) {
				//echo "$val,";
				$tag_string_blister_box.=$val_blister_box.",";
			}
			$blister_box=substr($tag_string_blister_box,0,(strLen($tag_string_blister_box)-1));// remove the last , from string
				
			/*add blister box*/
			$num_blister_array=$_POST['num_blister'];
			$tag_string_num_blister="";
			while (list ($key_num_blister,$val_num_blister) = @each ($num_blister_array)) {
				//echo "$val,";
				$tag_string_num_blister.=$val_num_blister.",";
			}
			$num_blister=substr($tag_string_num_blister,0,(strLen($tag_string_num_blister)-1));// remove the last , from string
				
			/*add blister box*/
			$cost_blister_array=$_POST['cost_blister'];
			$tag_string_cost_blister="";
			while (list ($key_cost_blister,$val_cost_blister) = @each ($cost_blister_array)) {
				//echo "$val,";
				$tag_string_cost_blister.=$val_cost_blister.",";
			}
			$cost=substr($tag_string_cost_blister,0,(strLen($tag_string_cost_blister)-1));// remove the last , from string			
		}

		if(($_POST['id_product_appearance']==1) || ($_POST['id_product_appearance']==2) || ($_POST['id_product_appearance']==3)){
			if($_POST['pack']==1){
				$total_pack=$_POST['total_packaging1'];
				$total_fg=$_POST['total_fg_cost1'];
			}else{
				$total_pack=$_POST['total_packaging2'];
				$total_fg=$_POST['total_fg_cost2'];}
		}elseif($_POST['id_product_appearance']==6){
			$total_pack=$_POST['total_packaging_fun'];
			$total_fg=$_POST['total_fg_cost_fun'];
		}elseif($_POST['id_product_appearance']==4){
			$total_pack=$_POST['total_packaging_drink'];
			$total_fg=$_POST['total_fg_cost_drink'];
		}
		$sql="update costing_pack_blister set num_cost='".$_POST['num_cost']."'";
		$sql .=",sum_cost_per='".$_POST['sum_cost_per']."'";
		$sql .=",type_pack='".$_POST['pack']."',bottle_cap='".$_POST['bottle_cap']."'";
		$sql .=",detail_blister='".$blister_box."',num_blister='".$num_blister."'";
		$sql .=",bottle_detail='".$bottle_detail2."',cost_bottle_detail='".$cost2."'";
		$sql .=",price_unit='".$cost."',total_pack='".$total_pack."'";
		$sql .=",total_fg='".$total_fg."'";
		$sql .=" where id_costing_factory='".$id."'";
		$res=mysql_query($sql) or die ('Error '.$sql);

		$month=date("m");
		$sql_quo="select * from costing_quotation where id_costing_factory='".$id."'";
		$res_quo=mysql_query($sql_quo) or die ('Error '.$sql_quo);
		$rs_quo=mysql_fetch_array($res_quo);
		if(!$rs_quo){	
			$quo='Q-';
			/*if($rs_account['id_account']<10){echo '0'.$rs_account['id_account'];}
			else{echo $rs_account['id_account'];}*/
			$date_m=date("y").date("m");
			if($month==$rs_quo['quo_month']){
				$quo_month=$month;
				$num = $rs_quo['quo_num']+1;
			}else{$num=1;$quo_month=$month;}												
			$numf=$quo.$date_m.sprintf("%03d",$num);
		
			$sql_insert="insert into costing_quotation(id_costing_factory,quotation_no,quo_month,quo_num)";
			$sql_insert .=" value('".$id."','".$numf."','".$quo_month."','".$num."')";
			$res_insert=mysql_query($sql_insert) or die ('Error '.$sql_insert);
		}else{}
	}//end factory jsp
	elseif($factory==3){//factory prima
		$sql_prima="update costing_factory_prima";
		$sql_prima .=" set prima_num_cost='".$_POST['prima_num_cost']."'";
		$sql_prima .=",prima_sum_cost_per='".$_POST['prima_sum_cost_per']."'";
		$sql_prima .=" where id_costing_factory_prima='".$_POST['id_costing_factory_prima']."'";
		$res_prima=mysql_query($sql_prima) or die ('Error '.$sql_prima);
		
		$month=date("m");
		$sql_quo="select * from costing_quotation where id_costing_factory='".$id."'";
		$res_quo=mysql_query($sql_quo) or die ('Error '.$sql_quo);
		$rs_quo=mysql_fetch_array($res_quo);
		if(!$rs_quo){		
			$quo='Q-';
			/*if($rs_account['id_account']<10){echo '0'.$rs_account['id_account'];}
			else{echo $rs_account['id_account'];}*/
			$date_m=date("y").date("m");
			if($month==$rs_quo['quo_month']){
				$quo_month=$month;
				$num = $rs_quo['quo_num']+1;
			}else{$num=1;$quo_month=$month;}												
			$numf=$quo.$date_m.sprintf("%03d",$num);
			
			$sql_insert="insert into costing_quotation(id_costing_factory,quotation_no,quo_month,quo_num";
			$sql_insert .=",create_by,create_date)";
			$sql_insert .=" value('".$id."','".$numf."','".$quo_month."','".$num."'";
			$sql_insert .=",'".$rs_account['id_account']."','".$date."')";
			$res_insert=mysql_query($sql_insert) or die ('Error '.$sql_insert);
		}
	}
}elseif($_POST['nextstep']==4){	
	$date=date('Y-m-d');
	$pages=$_POST['nextstep'];
	$factory=$_POST['factory'];

	// ZAMACHITA meeting
	$post_company_name = mysql_real_escape_string($_POST['company_name']);
	$post_company_contact = mysql_real_escape_string($_POST['company_contact']);
	$post_address = mysql_real_escape_string($_POST['address']);
	$post_customer_supply = mysql_real_escape_string($_POST['customer_supply']);

	/*add remark*/
	$remark_array=$_POST['remark'];
	$tag_string_remark="";
	while (list ($key_remark,$val_remark) = @each ($remark_array)) {
	//echo "$val,";
	$tag_string_remark.=$val_remark.",";
	}
	$remark=substr($tag_string_remark,0,(strLen($tag_string_remark)-1));// remove the last , from string12/6/2557

	$sql="update costing_quotation set id_company='".$_POST['id_company']."'";
	$sql .=",quotation_no='".$_POST['quotation_no']."'";
	$sql .=",company_name='".$post_company_name."',contact_name='".$post_company_contact."'";
	$sql .=",address='".$post_address."',email='".$_POST['company_email']."'";
	$sql .=",serial_number='".$_POST['serial_number']."'";
	$sql .=",packaging='".$_POST['packaging']."',quo_quatatity='".$_POST['quatatity']."'";
	$sql .=",quo_unit='".$_POST['quo_unit']."',quo_price='".$_POST['cost_unit']."'";
	$sql .=",quo_total='".$_POST['quo_total']."',quo_discount='".$_POST['discount']."'";
	$sql .=",total_discount='".$_POST['total_discount']."',vat_7='".$_POST['vat_7']."'";
	$sql .=",total_all='".$_POST['total_price']."',remark='".$remark."'";
	$sql .=",allowed_half='".$_POST['allowed']."',customer_supply='".$post_customer_supply."'";
	$sql .=" where id_costing_quotation='".$_POST['quo']."'";
	$res=mysql_query($sql) or die ('Error '.$sql);
}	
?>
<script>
	window.location.href='ac-costing-table.php?id_u=<?=$id?>&fac=<?=$factory?>&p=<?=$pages?>';
</script>
<?php } ?>
</body>
</html>
<?php ob_end_flush();?>
