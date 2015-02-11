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
if($_POST['mode']=='New'){
	$date=date("Y-m-d");
	$month1=date("m");
	$day1=date("d");
	$modify=date("Y-m-d H:i:s");
	
	echo $_POST['mode'];
	echo $_POST['date_visited'];
	echo $_POST['month_visited'];
	echo $_POST['year_visited'];
	echo $_POST['title_call_report'];
	echo $_POST['aaa1'];
	echo $_POST['sample_cost'];
	echo $_POST['num_date1'];
	
	$sql_company="select * from company where company_name like '".$_POST['company_name']."'";
	$res_company=mysql_query($sql_company) or die ('Error '.$sql_company);
	$rs_company=mysql_fetch_array($res_company);
	if(!$rs_company){
		$sql_ins_com="insert into company(company_name) values";
		$sql_ins_com .=" ('".$_POST['company_name']."')";
		//$res_ins_com=mysql_query($sql_ins_com) or die ('Error '.$sql_ins_com);
		$id_company=mysql_insert_id();
	}else{
		$id_company=$rs_company['id_company'];
	}

	$sql_product="select * from product where product_name like '".$_POST['product_name']."'";
	$res_product=mysql_query($sql_product) or die ('Error '.$sql_product);
	$rs_product=mysql_fetch_array($res_product);
	if(!$rs_product){
		$sql_ins_product="insert into product(id_company,product_name) values";
		$sql_ins_product .=" ('".$id_company."','".$_POST['product_name']."')";
		//$res_ins_product=mysql_query($sql_ins_product) or die ('Error '.$sql_ins_product);
		$id_product=mysql_insert_id();
	}else{
		$id_product=$rs_product['id_product'];
	}
		
		$sql="insert into call_report(id_company,title_call_report,id_product";
		$sql .=",sample_cost,id_type_package,sample_baht,other_cost";
		$sql .=",create_by,create_date)";
		$sql .=" values('".$id_company."','".$_POST['title_call_report']."'";
		$sql .=",'".$id_product."','".$_POST['sample_cost']."'";
		$sql .=",'".$_POST['id_type_package']."','".$_POST['sample_baht']."'";
		$sql .=",'".$_POST['other_cost']."','".$rs_account['id_account']."','".$date."')";
		//$res=mysql_query($sql) or die ('Error '.$sql);
		$id_call_report=mysql_insert_id();

		$sql_date="insert into call_report_date(id_call_report,visited_date,s_h_time";
		$sql_date .=",s_e_time,s_type,e_h_time,e_e_time,e_type,description,time_status)";
		$sql_date .=" values('".$id_call_report."','".$start."','".$h_time[$key_time]."'";
		$sql_date .=",'".$s_time[$key_time]."','".$w_ap_time[$key_time]."'";
		$sql_date .=",'".$h_time2[$key_time]."','".$s_time2[$key_time]."'";
		$sql_date .=",'".$w_ap_time2[$key_time]."','".$description[$key_time]."'";
		$sql_date .=",'".$time_status[$key_time]."')";
		//$res_date=mysql_query($sql_date) or die ('Error '.$sql_date);
					
	$sql_cr_update="update call_report_relationship set id_call_report='".$id_call_report."'";
	$sql_cr_update .=" where id_call_report='0'";
	//$res_cr_update=mysql_query($sql_cr_update) or die ('Error '.$sql_cr_update);
?>
	<script>
		//window.location.href='ac-call-report.php?id_u=<?=$id_call_report?>';
	</script>
<?php 
}else{

	/*updatea data to table call report*/
	$sql="update call_report set id_company='".$_POST['id_company']."'";
	$sql .=",title_call_report='".$_POST['title_call_report']."'";
	$sql .=",id_product='".$_POST['id_product']."'";
	$sql .=",sample_cost='".$_POST['sample_cost']."'";
	$sql .=",id_type_package='".$_POST['id_type_package']."'";
	$sql .=",sample_baht='".$_POST['sample_baht']."'";
	$sql .=",other_cost='".$_POST['other_cost']."'";
	$sql .=" where id_call_report='".$_POST['mode']."'";
	$res=mysql_query($sql) or die ('Error '.$sql);
	/*end table call report*/
	/*Get date and time*/
	$visited_date=$_POST['visited_date'];	
	$h_time=$_POST['h_time'];
	$s_time=$_POST['s_time'];
	$w_ap_time=$_POST['ap_time'];
	$h_time2=$_POST['h_time2'];
	$s_time2=$_POST['s_time2'];
	$w_ap_time2=$_POST['ap_time2'];
	$description=$_POST['description'];
	$time_status=$_POST['time_status'];
	$length_time = count($visited_date);
	for($key_time=0;$key_time<$length_time;$key_time++){
		list($month, $day, $year) = split('[/.-]', $visited_date[$key_time]); 
		$start= $year . "-". $month . "-" . $day;
	/*update data to table call report date and time*/
	$sql_date="update call_report_date set visited_date='".$start."'";
	$sql_date .=",s_h_time='".$h_time[$key_time]."'";
	$sql_date .=",s_e_time='".$s_time[$key_time]."'";
	$sql_date .=",s_type='".$w_ap_time[$key_time]."'";
	$sql_date .=",e_h_time='".$h_time2[$key_time]."'";
	$sql_date .=",e_e_time='".$s_time2[$key_time]."'";
	$sql_date .=",e_type='".$w_ap_time2[$key_time]."'";
	$sql_date .=",description='".$description[$key_time]."'";
	$sql_date .=",time_status='".$time_status[$key_time]."'";
	$sql_date  .=" where id_call_report='".$_POST['mode']."'";
	$res_date=mysql_query($sql_date) or die ('Error '.$sql_date);
	/*end table call report date and time*/
	}
	?>
	<script>
		//window.location.href='ac-call-report.php?id_u=<?=$id_call_report?>&call_report=y';
	</script>
<?php }?>
</body>
</html>