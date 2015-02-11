<?
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
$date=date("Y-m-d");
$modify=date("Y-m-d H:i:s");
if($_POST['mode']=='New'){

	if($_POST['roc_group_product']==-1){$roc_group_product= '-1';}
	else{$roc_group_product=$_POST['roc_group_product'];}

	/*add array roc group functin*/
	$roc_group_product_array=$_POST['roc_group_product'];
	$tag_group_product_string="";
	while (list ($key_group_product,$val_group_product) = @each ($roc_group_product_array)) {
		//echo "$val,";
		$tag_group_product_string.=$val_group_product.",";
	}
	$roc_group_product2=substr($tag_group_product_string,0,(strLen($tag_group_product_string)-1));// remove the last , from string
	
	$roc_function_array=$_POST['roc_function'];
	$tag_string="";
	while (list ($key,$val) = @each ($roc_function_array)) {
		//echo "$val,";
		$tag_string.=$val.",";
	}
	$roc_function=substr($tag_string,0,(strLen($tag_string)-1));// remove the last , from string

	if($_POST['roc_group_product']==1){$roc_function_other=$_POST['roc_function_other1'];}
	elseif($_POST['roc_group_product']==2){$roc_function_other=$_POST['roc_function_other2'];}
	elseif($_POST['roc_group_product']==3){$roc_function_other=$_POST['roc_function_other3'];}
	elseif($_POST['roc_group_product']==4){$roc_function_other=$_POST['roc_function_other4'];}

	$roc_function_other_array=$_POST['roc_function_other'];
	$tag_string_other="";
	while (list ($key_other,$val_other) = @each ($roc_function_other_array)) {
		//echo "$val,";
		$tag_string_other.=$val_other.",";
	}
	$roc_function_other=substr($tag_string_other,0,(strLen($tag_string_other)-1));// remove the last , from string*/

	/*add array*/
	$channel_cdip_array=$_POST['channel_cdip'];
	$tag_string="";
	while (list ($key,$val) = @each ($channel_cdip_array)) {
	//echo "$val,";
	$tag_string.=$val.",";
	}
	$channel_cdip=substr($tag_string,0,(strLen($tag_string)-1));// remove the last , from string
	
	list($day, $month, $year) = split('[/.-]', $_POST['product_date']); 
	$product_date= $year . "-". $day . "-" . $month;

	$sql="insert into customer_business(name_bu,surname,company_name";
	$sql .=",id_com_cat,channel_cdip,keyword_web,reason_business,enroll";
	$sql .=",id_product_appearance,id_group_product,other_group_product";
	$sql .=",id_roc_func,roc_func_other,customer_group,market_sell";
	$sql .=",idea_price,capital,product_date,project_is,reason_project";
	$sql .=",create_by,create_date)";
	$sql .=" values('".$_POST['name_bu']."','".$_POST['surname']."'";
	$sql .=",'".$_POST['company_name']."','".$_POST['company_cate']."'";
	$sql .=",'".$channel_cdip."','".$_POST['keyword_web']."'";
	$sql .=",'".$_POST['reason_business']."','".$_POST['enroll']."'";
	$sql .=",'".$_POST['product']."','".$roc_group_product2."'";
	$sql .=",'".$_POST['other_group_product']."','".$roc_function."'";
	$sql .=",'".$roc_function_other."','".$_POST['customer_group']."'";
	$sql .=",'".$_POST['market_sell']."','".$_POST['idea_price']."'";
	$sql .=",'".$_POST['capital']."','".$product_date."','".$_POST['project_is']."'";
	$sql .=",'".$_POST['reason_project']."','".$rs_account['id_account']."'";
	$sql .=",'".$date."')";
	$res=mysql_query($sql) or die ('Error '.$sql);
	$id=mysql_insert_id();
?>
	<script>
		window.location.href='ac-customer-bu.php?id_u=<?=$id?>';
	</script>
<?php 
}else{
	$id=$_POST['mode'];
	
	if($_POST['roc_group_product']==-1){$roc_group_product= '-1';}
	else{$roc_group_product=$_POST['roc_group_product'];}

	/*add array roc group functin*/
	$roc_group_product_array=$_POST['roc_group_product'];
	$tag_group_product_string="";
	while (list ($key_group_product,$val_group_product) = @each ($roc_group_product_array)) {
		//echo "$val,";
		$tag_group_product_string.=$val_group_product.",";
	}
	$roc_group_product2=substr($tag_group_product_string,0,(strLen($tag_group_product_string)-1));// remove the last , from string
	
	$roc_function_array=$_POST['roc_function'];
	$tag_string="";
	while (list ($key,$val) = @each ($roc_function_array)) {
		//echo "$val,";
		$tag_string.=$val.",";
	}
	$roc_function=substr($tag_string,0,(strLen($tag_string)-1));// remove the last , from string

	if($_POST['roc_group_product']==1){$roc_function_other=$_POST['roc_function_other1'];}
	elseif($_POST['roc_group_product']==2){$roc_function_other=$_POST['roc_function_other2'];}
	elseif($_POST['roc_group_product']==3){$roc_function_other=$_POST['roc_function_other3'];}
	elseif($_POST['roc_group_product']==4){$roc_function_other=$_POST['roc_function_other4'];}

	$roc_function_other_array=$_POST['roc_function_other'];
	$tag_string_other="";
	while (list ($key_other,$val_other) = @each ($roc_function_other_array)) {
		//echo "$val,";
		$tag_string_other.=$val_other.",";
	}
	$roc_function_other=substr($tag_string_other,0,(strLen($tag_string_other)-1));// remove the last , from string*/

	/*add array*/
	$channel_cdip_array=$_POST['channel_cdip'];
	$tag_string="";
	while (list ($key,$val) = @each ($channel_cdip_array)) {
	//echo "$val,";
	$tag_string.=$val.",";
	}
	$channel_cdip=substr($tag_string,0,(strLen($tag_string)-1));// remove the last , from string
	
	list($day, $month, $year) = split('[/.-]', $_POST['product_date']); 
	$product_date= $year . "-". $day . "-" . $month;

	$sql="update customer_business set name_bu='".$_POST['name_bu']."'";
	$sql .=",surname='".$_POST['surname']."'";
	$sql .=",company_name='".$_POST['company_name']."'";
	$sql .=",id_com_cat='".$_POST['company_cate']."'";
	$sql .=",channel_cdip='".$channel_cdip."'";
	$sql .=",keyword_web='".$_POST['keyword_web']."'";
	$sql .=",reason_business='".$_POST['reason_business']."'";
	$sql .=",enroll='".$_POST['enroll']."'";
	$sql .=",id_product_appearance='".$_POST['product']."'";
	$sql .=",id_group_product='".$roc_group_product2."'";
	$sql .=",other_group_product='".$_POST['other_group_product']."'";
	$sql .=",id_roc_func='".$roc_function."'";
	$sql .=",roc_func_other='".$roc_function_other."'";
	$sql .=",customer_group='".$_POST['customer_group']."'";
	$sql .=",market_sell='".$_POST['market_sell']."'";
	$sql .=",idea_price='".$_POST['idea_price']."'";
	$sql .=",capital='".$_POST['capital']."'";
	$sql .=",product_date='".$product_date."'";
	$sql .=",project_is='".$_POST['project_is']."'";
	$sql .=",reason_project='".$_POST['reason_project']."'";
	$sql .=" where id_customer_bu='".$id."'";
	$res=mysql_query($sql) or die ('Error '.$sql);
?>
	<script>
		window.location.href='ac-customer-bu.php?id_u=<?=$id?>';
	</script>
<?php 
}
?>
</body>
</html>