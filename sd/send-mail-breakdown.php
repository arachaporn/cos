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

$sql_account2 = "SELECT * FROM account WHERE id_account = '".$_REQUEST['device_code']."'  ";
$res_account2 = mysql_query($sql_account2) or die ('Error '.$sql_account2);
$rs_account2 = mysql_fetch_array($res_account2);

$sql_device="select * from sd_device where device_code='".$_REQUEST['type']."'";
$res_device=mysql_query($sql_device) or die ('Error '.$sql_device);
$rs_device=mysql_fetch_array($res_device);

$sql_break="select * from sd_breakdown where id_break_down='".$_REQUEST['break']."'";
$res_break=mysql_query($sql_break) or die ('Error '.$sql_break);
$rs_break=mysql_fetch_array($res_break);
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
$MailTo = $rs_account2['email'];
$MailFrom = "arachaporn.s@cdipthailand.com";
$MailSubject = "=?UTF-8?B?".base64_encode("ความคืบหน้าการซ่อมอุปกรณ์".$rs_device['device_code'])."?=";
if($_REQUEST['status']==1){
//$MailMessage = "ตรวจสอบความคืบหน้าการแจ้งซ่อมได้ที่";
//$MailMessage .="<a href='http://cdipthailand.com/cos/sd/breakdown.php'>ฝ่าย SD หัวข้อ ใบแจ้งซ่อม</a>";
//$MailMessage .="คะ";
$MailMessage .="เรียนผู้ที่เกี่ยวข้อง";
$MailMessage .="<br>";
$MailMessage .= $rs_break['repair_description'];
}else{
//$MailMessage .="คะ";
$MailMessage .="เรียนผู้ที่เกี่ยวข้อง";
$MailMessage .="<br>";
$MailMessage .=$rs_break['result'];
$MailMessage .="<br><br>";
$MailMessage .= "ตรวจรับอุปกรณ์ที่แจ้งซ่อมที่";
$MailMessage .="<a href='http://cdipthailand.com/cos/sd/breakdown.php'>ฝ่าย SD หัวข้อ ใบแจ้งซ่อม</a>";
$MailMessage .="<br><br>";
$MailMessage .="กรุณากดปุ่ม <b>'กดยืนยันเพื่อรับอุปกรณ์' </b>ด้วยคะ";
}
$MailMessage .="<br><br><br><br>";
$MailMessage .="Best regards,";
$MailMessage .="<br>";
$MailMessage .="System Development";

$Headers = "MIME-Version: 1.0\r\n" ;
$Headers .= "Content-type: text/html; charset=utf-8\r\n"; 
$Headers .= "From: ".$MailFrom." <".$MailFrom.">\r\n";
$Headers .= "Cc: namthip.t@cdipthailand.com,arachaporn.s@cdipthailand.com\r\n";
$Headers .= "Mailed-by: cdipthailand.com"; 
//$Headers .= "Reply-to: ".$MailFrom." <".$MailFrom.">\r\n" ;
$Headers .= "X-Priority: 3\r\n" ;
$Headers .= "X-Mailer: PHP mailer\r\n" ;

$flgSend = @mail($MailTo,$MailSubject,$MailMessage,$Headers,"-f  test@cdipthailand.com ");
if($flgSend){
?>
	<script>
		window.alert('Send mail complete');
		window.location.href='breakdown.php';
	</script>
<?php }else{?>
	<script>
		window.alert('Send mail False');
		window.location.href='breakdown.php';
	</script>
<?php } ?>
</body>
</html>