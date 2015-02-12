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
</head>
<body>
<?
$date=date("Y-m-d");
$modify=date("Y-m-d H:i:s");
$time=date("H:i");
if($_POST['mode']=='New'){
	if($_POST['withdraw']==1){
		$get_device=1;
		list($ckmonth,$ckday,$ckyear) = split('[/.-]', $_POST['get_date']); 
		$get_date= $ckyear . "-".$ckday. "-" .$ckmonth;

		$sql="insert into sd_withdraw(id_type_other_device,recip_date";
		$sql .=",device_quantity,device_unit,id_recipients,create_date)";
		$sql .=" values('".$_POST['get_device']."','".$get_date."'";
		$sql .=",'".$_POST['device_quantity']."','".$_POST['device_unit']."'";
		$sql .=",'".$_POST['id_account']."','".$date."')";
		$res=mysql_query($sql) or die ('Error '.$sql);
		$id_withdraw=mysql_insert_id();
	?>
		<script>
			window.location.href='ac-withdraw-device.php?id_u=<?=$id_withdraw?>&get_device=<?=$get_device?>';
		</script>

	<?php }
	else
	if($_POST['withdraw']==2){
		$get_device=2;
		list($ckmonth2,$ckday2,$ckyear2) = split('[/.-]', $_POST['get_date2']); 
		$get_date2= $ckyear2 . "-".$ckday2. "-" .$ckmonth2;

		$sql="insert into sd_withdraw_account(id_type_other_device,date_withdraw";
		$sql .=",quantity,description,id_account_withdraw)";
		$sql .=" values('".$_POST['get_device2']."','".$get_date2."'";
		$sql .=",'".$_POST['quantity']."','".$_POST['description']."'";
		$sql .=",'".$_POST['id_account2']."')";
		$res=mysql_query($sql) or die ('Error '.$sql);
		$id_withdraw=mysql_insert_id();
		
		$sql_device="select * from type_other_device";
		$sql_device .=" where id_type_other_device='".$_POST['get_device2']."'";
		$res_device=mysql_query($sql_device) or die ('Error '.$sql_device);
		$rs_device=mysql_fetch_array($res_device);

		$MailTo = "itsupervisor.cdip@gmail.com";
		$MailFrom = $rs_account['email'];
		$MailSubject = "=?UTF-8?B?".base64_encode("บันทึกการเบิกอุปกรณ์".$rs_device['type_other_device'])."?=";
		$MailMessage = "แจ้งการเบิกอุปกรณ์ ".$rs_device['type_other_device'];
		$MailMessage .=" ตรวจสอบข้อมูลได้ที่";
		$MailMessage .="<a href='http://cdipthailand.com/cos/sd/ac-withdraw-device.php'>ฝ่าย SD เมนูบันทึกการเบิกอุปกรณ์อิเล็กทรอนิกส์</a>";

		$Headers = "MIME-Version: 1.0\r\n" ;
		$Headers .= "Content-type: text/html; charset=utf-8\r\n"; 
		$Headers .= "From: ".$MailFrom." <".$MailFrom.">\r\n";
		$Headers .= "Cc: sdmanager.cdip@gmail.com\r\n";
		//$Headers .= "Reply-to: ".$MailFrom." <".$MailFrom.">\r\n" ;
		$Headers .= "X-Priority: 3\r\n" ;
		$Headers .= "X-Mailer: PHP mailer\r\n" ;

		$flgSend = @mail($MailTo,$MailSubject,$MailMessage,$Headers,"-f  test@cdipthailand.com ");
		if($flgSend){
		?>
			<script>
				window.alert('Send mail complete');
				window.location.href='withdraw-device.php';
			</script>
		<?php }else{?>
			<script>
				window.alert('Send mail False');
			</script>
		<?php } 
	?>
	<?php 
	}	
?>
<?php 
}else{
	$id_withdraw=$_POST['mode'];

	list($ckmonth,$ckday,$ckyear) = split('[/.-]', $_POST['get_date']); 
	$get_date= $ckyear . "-".$ckday. "-" .$ckmonth;

	$sql ="update sd_withdraw set id_type_other_device='".$_POST['get_device']."'";
	$sql .=",recip_date='".$get_date."',device_quantity='".$_POST['device_quantity']."'";
	$sql .=",device_unit='".$_POST['device_unit']."'";
	$sql .=" where id_sd_withdraw='".$id_withdraw."'";
	$res=mysql_query($sql) or die ('Error '.$sql);
?>
	<script>
		window.location.href='ac-withdraw-device.php?id_u=<?=$id_withdraw?>';
	</script>
<?php 
}
?>
</body>
</html>