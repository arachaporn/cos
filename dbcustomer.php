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

	/*add array type file */
	$type_file_array=$_POST['type_file'];
	$tag_string="";
	while (list ($key,$val) = @each ($type_file_array)) {
	//echo "$val,";
	$tag_string.=$val.",";
	}
	$type_file=substr($tag_string,0,(strLen($tag_string)-1));// remove the last , from string
	
	// ZAMACHITA meeting
	$post_company_name = mysql_real_escape_string($_POST['company_name']);

	if($rs_account['role_user'] != 3){$create_by=$_POST['project_manager'];}
	else{$create_by=$rs_account['id_account'];}
	$sql="insert into company(company_code,company_name,id_type_company,branch_name";
	$sql .=",company_tel,company_fax,company_website,enroll,id_com_cat,id_cate_bought";
	$sql .=",person_type,type_file,id_company_pay,type_pay,create_by,create_date)";
	$sql .=" values('".$_POST['company_code']."','".$post_company_name."'";
	$sql .=",'".$_POST['company_type']."','".$_POST['branch_name']."'";
	$sql .=",'".$_POST['company_tel']."','".$_POST['company_fax']."'";
	$sql .=",'".$_POST['company_website']."','".$_POST['enroll']."'";
	$sql .=",'".$_POST['company_cate']."','".$_POST['cate_bought']."'";
	$sql .=",'".$_POST['person_type']."','".$type_file."','".$_POST['company_pay']."'";
	$sql .=",'".$_POST['type_pay']."','".$create_by."','".$date."')";
	$res=mysql_query($sql) or die ('Error '.$sql);
	
	$id_company=mysql_insert_id();
	
	$sql_address="insert into company_address(address_no,road,sub_district";
	$sql_address .=",district,province,postal_code)";
	$sql_address .=" values('".$_POST['address_no']."','".$_POST['road']."'";
	$sql_address .=",'".$_POST['sub_district']."','".$_POST['district']."'";
	$sql_address .=",'".$_POST['province']."','".$_POST['postal_code']."')";
	$res_address=mysql_query($sql_address) or die ('Error '.$sql_address);

	$id_address=mysql_insert_id();
		
	$sql_update_com="update company set id_address='".$id_address."'";
	$sql_update_com .=" where id_company='".$id_company."'";
	$res_update_com=mysql_query($sql_update_com) or die ('Error '.$sql_update_com);

	for($i=0;$i<count($_POST['contact_name']);$i++){
		$sql_contact="insert into company_contact(id_company,contact_name,department";
		$sql_contact .=",contact_position,mobile,email) values('".$id_company."'";
		$sql_contact .=",'".$_POST['contact_name'][$i]."','".$_POST['department'][$i]."'";
		$sql_contact .=",'".$_POST['contact_position'][$i]."','".$_POST['contact_mobile'][$i]."'";
		$sql_contact .=",'".$_POST['contact_mail'][$i]."')";
		$res_contact=mysql_query($sql_contact) or die ('Error '.$sql_contact);
	}	
?>
	<script>
		window.location.href='ac-open-customer.php?id_u=<?=$id_company?>';
	</script>
<?php }else{
	$id=$_POST['mode'];

	/*add array type file */
	$type_file_array=$_POST['type_file'];
	$tag_string="";
	while (list ($key,$val) = @each ($type_file_array)) {
	//echo "$val,";
	$tag_string.=$val.",";
	}
	$type_file=substr($tag_string,0,(strLen($tag_string)-1));// remove the last , from string
	
	if($rs_account['role_user'] != 3){$create_by=$_POST['project_manager'];}
	else{$create_by=$rs_account['id_account'];}

	// ZAMACHITA meeting
	$post_company_name = mysql_real_escape_string($_POST['company_name']);

	$sql="update company set company_code='".$_POST['company_code']."'";
	$sql .=",company_name='".$post_company_name."'";
	$sql .=",id_type_company='".$_POST['company_type']."'";
	$sql .=",other_company_type='".$_POST['other_company_type']."'";
	$sql .=",branch_name='".$_POST['branch_name']."'";
	$sql .=",trade_regis='".$_POST['trade_regis']."'";
	$sql .=",company_tel='".$_POST['company_tel']."'";
	$sql .=",company_fax='".$_POST['company_fax']."'";
	$sql .=",id_com_cat='".$_POST['company_cate']."'";
	$sql .=",other_company_cate='".$_POST['other_company_cate']."'";
	$sql .=",id_cate_bought='".$_POST['cate_bought']."'";
	$sql .=",other_bought='".$_POST['other_bought']."'";
	$sql .=",person_type='".$_POST['person_type']."'";
	$sql .=",person_type='".$_POST['person_type']."'";
	$sql .=",type_file='".$type_file."'";
	$sql .=",id_company_pay='".$_POST['company_pay']."'";
	$sql .=",type_pay='".$_POST['type_pay']."'";
	$sql .=",pay_date='".$_POST['pay_date']."'";
	$sql .=",checkpay_date='".$_POST['checkpay_date']."'";
	$sql .=",create_by='".$create_by."'";
	$sql .=" where id_company='".$id."'";
	$res=mysql_query($sql) or die ('Error '.$sql);

	$sql_address="update company_address set address_no='".$_POST['address_no']."'";
	$sql_address .=",road='".$_POST['road']."',sub_district='".$_POST['sub_district']."'";
	$sql_address .=",district='".$_POST['district']."',province='".$_POST['province']."'";
	$sql_address .=",postal_code='".$_POST['postal_code']."'";
	$sql_address .=" where id_address='".$_POST['id_address']."'";
	$res_address=mysql_query($sql_address) or die ('Error '.$sql_address);

	for($i=0;$i<count($_POST['contact_name']);$i++){
		$sql_contact="update company_contact set contact_name='".$_POST['contact_name'][$i]."'";
		$sql_contact .=",contact_position='".$_POST['contact_position'][$i]."'";
		$sql_contact .=",mobile='".$_POST['contact_mobile'][$i]."',email='".$_POST['contact_mail'][$i]."'";
		$sql_contact .=" where id_contact='".$_POST['id_contact'][$i]."'";
		$res_contact=mysql_query($sql_contact) or die ('Error '.$sql_contact);
	}
?>
	<script>
		window.location.href='ac-open-customer.php?id_u=<?=$id?>';
	</script>
<?php
}
?>
</body>
</html>