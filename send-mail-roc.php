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
	
	$sql_roc="select * from roc where id_roc='".$id."'";
	$res_roc=mysql_query($sql_roc) or die ('Error '.$sql_roc);
	$rs_roc=mysql_fetch_array($res_roc);

	$sql_product="select * from product where id_product='".$rs_roc['id_product']."'";
	$res_product=mysql_query($sql_product) or die ('Error '.$sql_product);
	$rs_product=mysql_fetch_array($res_product);
	
	$sql_rela_pack="select * from roc_relation_pack";
	$sql_rela_pack .=" where id_relation_pack='".$rs_roc['id_relation_pack']."'";
	$res_rela_pack=mysql_query($sql_rela_pack) or die ('Error '.$sql_rela_pack);
	$rs_rela_pack=mysql_fetch_array($res_rela_pack);

	if($rs_roc['roc_status'] ==1){
		if($rs_roc['id_product_appearance']==0){
		?>
			<script>
				window.alert('กรุณาระบุบรรจุภัณฑ์');
				history.back();
			</script>
		<?php }elseif($rs_rela_pack['id_product_appearance']==''){
		?>
			<script>
				window.alert('กรุณาระบุบรรจุภัณฑ์');
				history.back();
			</script>
		<?php }elseif($rs_roc['id_product']== 0 ){?>
			<script>
					window.alert('กรุณาระบุชื่อผลิตภัณฑ์');
					history.back();
			</script>
		<?php }elseif($rs_roc['id_contact']==0){?>
			<script>
					window.alert('Please insert contact name');
					history.back();
			</script>
		<?php }elseif($rs_roc['id_company']==0){?>
			<script>
					window.alert('Please insert company name');
					history.back();
			</script>
		<?php }elseif($rs_roc['id_com_cat']==0){?>
			<script>
					window.alert('กรุณาระบุ Identify Customer');
					history.back();
			</script>
		<?php }elseif($rs_roc['id_type_product']==0){?>
			<script>
					window.alert('กรุณาระบุชนิดของผลิตภัณฑ์');
					history.back();
			</script>
		<?php	
		}else{			

			if($rs_roc['id_type_product']==1){$mail_to="wilailuk.a@cdipthailand.com";$cc="Cc: jureewan.r@cdipthailand.com,chawannas.w@cdipthailand.com\r\n";}
			elseif($rs_roc['id_type_product']==2){$mail_to="qa.cdip@gmail.com";$cc="Cc: jureewan.r@cdipthailand.com,chawannas.w@cdipthailand.com\r\n";}
			else{$mail_to="jureewan.r@cdipthailand.com";$cc="Cc: chawannas.w@cdipthailand.com\r\n";}
		
			$MailTo =$mail_to;;
			$MailFrom = $rs_account['email'];
			$MailSubject = "=?UTF-8?B?".base64_encode("SM_NPD : ".$rs_roc['roc_code']." ".$rs_product['product_name'])."?=";
			$MailMessage = "เรียนผู้เกี่ยวข้อง";
			$MailMessage .="<br><br>";
			$MailMessage .="ฝ่าย SM ได้จัดทำ ".$rs_roc['roc_code'];
			$MailMessage .=" สามารถตรวจสอบข้อมูลได้ที่ ";
			$MailMessage .="<a href='http://cdipthailand.com/cos'>cdipthailand.com/cos</a> ในฝ่าย NPD > ROC";
			$MailMessage .="<br><br><br>";
			$MailMessage .="Best regards,";
			$MailMessage .="<br>";
			$MailMessage .=$rs_account['name'];
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

			$Headers = "MIME-Version: 1.0\r\n" ;
			$Headers .= "Content-type: text/html; charset=utf-8\r\n"; 
			$Headers .= "From: ".$MailFrom." <".$MailFrom.">\r\n";
			$Headers .= $cc;
			//$Headers .= "Reply-to: ".$MailFrom." <".$MailFrom.">\r\n" ;
			$Headers .= "X-Priority: 3\r\n" ;

			$flgSend = @mail($MailTo,$MailSubject,$MailMessage,$Headers,"-f  test@cdipthailand.com ");
			if($flgSend){
		?>
				<script>
					window.alert('Send mail complete');
					window.location.href='roc.php';
				</script>
		<?php }else{?>
				<script>
					window.alert('Send mail False');
					history.back();
				</script>
			<?php }
		}//end if success
	}//end if roc complete	
	else{?>
		<script>
			window.alert('Please select complete');
			history.back();
		</script>
	<?php }
?>
</body>
</html>