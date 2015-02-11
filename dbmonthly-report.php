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
$month1=date("m");
$day1=date("d");
$modify=date("Y-m-d H:i:s");

/*check date of visited >7 can't save*/
$ckvisited_date=$_POST['visited_date'];	
$ckh_time2=$_POST['h_time'];
$cks_time2=$_POST['s_time'];
$ckw_ap_time=$_POST['ap_time'];
$ckh_time2=$_POST['h_time2'];
$cks_time2=$_POST['s_time2'];
$ckw_ap_time2=$_POST['ap_time2'];
$ckdescription=$_POST['description'];
$cktime_status=$_POST['time_status'];
$cklength_time = count($visited_date);
for($ckkey_time=0;$ckkey_time<$cklength_time;$ckkey_time++){
	if($ckkey_time==0){
		list($ckmonth,$ckday, $ckyear) = split('[/.-]', $ckvisited_date[$ckkey_time]); 
		$ckstart= $ckyear . "-". $ckday . "-" .$ckmonth;
		$cknumday=$day1-$ckday;
		if($month1==$ckmonth){
			if(($cknumday)<=7){$date_st='y';$month_st='my';}
			else{$date_st='n';$month_st='mn';};
		}
		else{$month_st='mn';}
	}
}
if($_POST['mode']=='New'){
	if(($date_st=='n') || ($month_st=='mn')){
	?>	
		<script>
			window.alert('Can not save');
			window.location.href='call-report.php';
		</script>
	<?}else{
		$sql_company="select * from company where company_name like '".$_POST['company_name']."'";
		$res_company=mysql_query($sql_company) or die ('Error '.$sql_company);
		$rs_company=mysql_fetch_array($res_company);
		if(!$rs_company){
			$sql_ins_com="insert into company(company_name) values";
			$sql_ins_com .=" ('".$_POST['company_name']."')";
			$res_ins_com=mysql_query($sql_ins_com) or die ('Error '.$sql_ins_com);
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
			$res_ins_product=mysql_query($sql_ins_product) or die ('Error '.$sql_ins_product);
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
		$res=mysql_query($sql) or die ('Error '.$sql);
		$id_call_report=mysql_insert_id();

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
			$start= $year . "-". $day . "-" . $month;

			$sql_date="insert into call_report_date(id_call_report,visited_date,s_h_time";
			$sql_date .=",s_e_time,s_type,e_h_time,e_e_time,e_type,description,time_status)";
			$sql_date .=" values('".$id_call_report."','".$start."','".$h_time[$key_time]."'";
			$sql_date .=",'".$s_time[$key_time]."','".$w_ap_time[$key_time]."'";
			$sql_date .=",'".$h_time2[$key_time]."','".$s_time2[$key_time]."'";
			$sql_date .=",'".$w_ap_time2[$key_time]."','".$description[$key_time]."'";
			$sql_date .=",'".$time_status[$key_time]."')";
			$res_date=mysql_query($sql_date) or die ('Error '.$sql_date);
		}
		
		$name=$_POST['contact_name'];
		$department=$_POSR['department'];
		$telephone=$_POST['telephone'];
		$email=$_POST['email'];
		$length = count($name);
		for($key=0;$key<$length;$key++){
			$sql_com_contact="insert into company_contact(id_company,id_call_report";
			$sql_com_contact .=",contact_name,department,tel,email)";
			$sql_com_contact .=" values ('".$id_company."','".$id_call_report."'";
			$sql_com_contact .=",'".$name[$key]."','".$department[$key]."'";
			$sql_com_contact .=",'".$telephone[$key]."','".$email[$key]."')";
			$res_com_contact=mysql_query($sql_com_contact) or die ('Error '.$sql_com_contact);
			$id_com_contact=mysql_insert_id();
		} 
		
		
		/*require_once('class.phpmailer.php');
		$mail = new PHPMailer();
		$mail->IsHTML(true);
		$mail->IsSMTP();
		$mail->SMTPAuth = true; // enable SMTP authentication
		$mail->SMTPSecure = "ssl"; // sets the prefix to the servier
		$mail->Host = "smtp.gmail.com"; // sets GMAIL as the SMTP server
		$mail->Port = 465; // set the SMTP port for the GMAIL server
		//$mail->Username = "itsupervisor.cdip@gmail.com"; // GMAIL username
		//$mail->Password = "p6olbdhk"; // GMAIL password
		$mail->From = "itsupervisor.cdip@gmail.com"; // "name@yourdomain.com";
		//$mail->AddReplyTo = "support@thaicreate.com"; // Reply
		$mail->FromName = "Mr.Weerachai Nukitram";  // set from Name
		$mail->Subject = "Test sending mail."; 
		$mail->Body = "My Body & <b>My Description</b>";

		$mail->AddAddress("itsupervisor.cdip@gmail.com", "Arachaporn"); // to Address
		
		$mail->set('X-Priority', '1'); //Priority 1 = High, 3 = Normal, 5 = low

		$mail->Send();  */
	?>
	<script>
		window.location.href='call-report.php';
	</script>
<?php }
}else{?>
	<script>
		window.location.href='call-report.php';
	</script>
<?php } ?>
</body>
</html>