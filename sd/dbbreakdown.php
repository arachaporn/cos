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
	$sql="insert into sd_breakdown(break_down_code,break_down_month";
	$sql .=",break_down_num,break_down_date,break_down_time,id_account";
	$sql .=",id_type_device,device_code,problem,status)";
	$sql .=" values('".$_POST['break_down_code']."','".$_POST['break_down_month']."'";
	$sql .=",'".$_POST['break_down_num']."','".$_POST['break_down_date']."'";
	$sql .=",'".$_POST['break_down_time']."','".$_POST['id_account']."'";
	$sql .=",'".$_POST['id_type_device']."','".$_POST['device_code']."'";
	$sql .=",'".$_POST['problem']."','2')";
	$res=mysql_query($sql) or die ('Error '.$sql);

	$MailTo = "itsupervisor.cdip@gmail.com";
	$MailFrom = $rs_account['email'];
	$MailSubject = "=?UTF-8?B?".base64_encode("แจ้งการซ่อมอุปกรณ์".$_POST['device_code'])."?=";
	$MailMessage = "มีการแจ้งอุปกรณ์".$_POST['device_code'];
	$MailMessage .="ตรวจสอบข้อมูลได้ที่";
	$MailMessage .="<a href='http://cdipthailand.com/cos/breakdown.php'>ฝ่าย SD หัวข้อ ใบแจ้งซ่อม</a>";
	$MailMessage .="คะ";

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
		</script>
	<?php }else{?>
		<script>
			window.alert('Send mail False');
		</script>
	<?php } 
?>
	<script>
		window.location.href='breakdown.php';
	</script>
<?php 
}else{
	if($_REQUEST['submit_device']=='ok'){
	$mode=$_REQUEST['id_u'];
	
	$sql ="update sd_breakdown set complete_time='".$time."'";
	$sql .=",status=1 where id_break_down='".$mode."' and status='2'";
	$res=mysql_query($sql) or die ('Error '.$sql);
?>
	<script>
		window.location.href='ac-breakdown.php?id_u=<?echo $mode?>';
	</script>
<?php }else{
	//กำหนดแล้วเสร็จ
	list($ckmonth,$ckday,$ckyear) = split('[/.-]', $_POST['timeline']); 
	$timeline= $ckyear . "-". $ckmonth . "-" .$ckday;
	//วันที่ส่งงาน
	list($ckmonth2,$ckday2,$ckyear2) = split('[/.-]', $_POST['complete_date']); 
	$complete_date= $ckyear2 . "-". $ckmonth2 . "-" .$ckday2;

	$sql="update sd_breakdown set type_repair='".$_POST['type_repair']."'";
	$sql .=",repair_description='".$_POST['repair_description']."'";
	$sql .=",id_account_repair='".$_POST['id_account_repair']."'";
	$sql .=",timeline='".$timeline."',complete_date='".$complete_date."'";
	$sql .=",result='".$_POST['result']."'";
	$sql .=" where id_break_down='".$_POST['mode']."'";
	$res=mysql_query($sql) or die ('Error '.$sql);
?>
	<script>
		window.location.href='ac-breakdown.php?id_u=<?=$_POST["mode"]?>';
	</script>
<?php 
	} 
}
?>
</body>
</html>