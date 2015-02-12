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
<?php
	$id=$_GET['id_u'];
	
	$sql="select * from npd_ps where id_npd_ps='".$id."'";
	$res=mysql_query($sql) or die ('Error '.$sql);
	$rs=mysql_fetch_array($res);

	$sql_product="select * from product where id_product='".$rs['id_product']."'";
	$res_product=mysql_query($sql_product) or die ('Error '.$sql_product);
	$rs_product=mysql_fetch_array($res_product);

	$MailTo =$rs['mail_to'];
	$MailFrom = $rs_account['email'];
	$MailSubject = "=?UTF-8?B?".base64_encode("NPD_PM : ".$rs_product['product_name'])."?=";
	$MailMessage =$rs['mail_message'];
	$MailMessage .="<br><br>";
	$MailMessage .="รบกวนเข้าไปทำ Feedback ในระบบ COS เมนุ New Product Development ->	Product Detail Feedback";
	$MailMessage .="<br><br>";
	$MailMessage .="Best regards,";
	$MailMessage .="<br><br>";
	$MailMessage .="Ms.Piyarat Kongsawat";
	$MailMessage .="<br>";
	$MailMessage .="(Product Specialist)";
	$MailMessage .="<br><br>";
	$MailMessage .="CDIP (Thailand).Co.,Ltd.";
	$MailMessage .="<br>";
	$MailMessage .="Tel: +662 564 7000 #5204,#5205";
	$MailMessage .="<br>";
	$MailMessage .="Fax: +662 564 7745";
	$MailMessage .="<br>";
	$MailMessage .="<a href='http://www.facebook.com/CDIPThailand' target='_blank'>facebook.com/CDIPThailand</a>";
	$MailMessage .="<br>";
	$MailMessage .="<a href='http://www.cdipthailand.com' target='_blank'>cdipthailand.com</a>";
	$MailMessage .="<br>";
	$MailMessage .="piyarat.k@cdipthailand.com";

	$Headers = "MIME-Version: 1.0\r\n" ;
	$Headers .= "Content-type: text/html; charset=utf-8\r\n"; 
	$Headers .= "From: ".$MailFrom." <".$MailFrom.">\r\n";
	$Headers .= "Cc:".$rs['mail_cc']."\r\n";
	//$Headers .= "Reply-to: ".$MailFrom." <".$MailFrom.">\r\n" ;
	$Headers .= "X-Priority: 3\r\n" ;

	$flgSend = @mail($MailTo,$MailSubject,$MailMessage,$Headers,"-f  test@cdipthailand.com ");
	if($flgSend){
	?>
		<script>
			window.alert('Send mail complete');
			window.location.href='ps-feedback.php';
		</script>
	<?php }else{?>
		<script>
			window.alert('Send mail False');;
			history.back();
		</script>
	<?php }
?>
</body>
</html>