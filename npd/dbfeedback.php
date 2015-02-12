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
include("connect/connect.php");

$date=date("Y-m-d");
$modify=date("Y-m-d H:i:s");
if($_POST['mode']=='New'){
	
	/*add product detail*/
	$roc_ps_detail_array=$_POST['ps_detail'];
	$tag_ps_detail_string="";
	while (list ($key_ps_detail,$val_ps_detail) = @each ($roc_ps_detail_array)) {
	//echo "$val,";
	$tag_ps_detail_string.=$val_ps_detail.",";
	}
	$ps_detail=substr($tag_ps_detail_string,0,(strLen($tag_ps_detail_string)-1));// remove the last , from string

	$sql="insert into npd_ps(doc_no,date_feedback,id_product";
	$sql .=",product_name,project_manager,id_customer";
	$sql .=",company_name,id_ps_detail,create_by,create_date";
	$sql .=",mail_to,mail_cc,mail_message,rev)";
	$sql .=" values('".$_POST['doc_no']."','".$date."'";
	$sql .=",'".$_POST['id_product']."','".$_POST['product_name']."'";
	$sql .=",'".$_POST['id_account2']."'";
	$sql .=",'".$_POST['id_company']."','".$_POST['company_name']."'";
	$sql .=",'".$ps_detail."','".$rs_account['id_account']."'";
	$sql .=",'".$date."','".$_POST['mail_to']."'";
	$sql .=",'".$_POST['mail_cc']."','".$_POST['mail_message']."','0')";
	$res=mysql_query($sql) or die ('Error '.$sql);
	$id_ps=mysql_insert_id();
?>
	<script>
		window.location.href='ac-ps-feedback.php?id_u=<?=$id_ps?>';
	</script>
<?php 
}else{
	$id_ps=$_POST['mode'];

	/*add product detail*/
	$roc_ps_detail_array=$_POST['ps_detail'];
	$tag_ps_detail_string="";
	while (list ($key_ps_detail,$val_ps_detail) = @each ($roc_ps_detail_array)) {
	//echo "$val,";
	$tag_ps_detail_string.=$val_ps_detail.",";
	}
	$ps_detail=substr($tag_ps_detail_string,0,(strLen($tag_ps_detail_string)-1));// remove the last , from string

	$sql="update npd_ps set doc_no='".$_POST['doc_no']."'";
	$sql .=",date_feedback='".$_POST['date_feedback']."'";
	$sql .=",id_product='".$_POST['id_product']."'";
	$sql .=",product_name='".$_POST['product_name']."'";
	$sql .=",project_manager='".$_POST['id_account2']."'";
	$sql .=",id_customer='".$_POST['id_company']."'";
	$sql .=",company_name='".$_POST['company_name']."'";
	$sql .=",id_ps_detail='".$ps_detail."'";
	$sql .=",feedback='".$_POST['approve']."'";
	$sql .=",remark='".$_POST['remark']."'";
	$sql .=",mail_to='".$_POST['mail_to']."'";
	$sql .=",mail_cc='".$_POST['mail_cc']."'";
	$sql .=",mail_message='".$_POST['mail_message']."'";
	$sql .=" where id_npd_ps='".$_POST['mode']."'";
	$res=mysql_query($sql) or die ('Error '.$sql);

	if($_GET['success']=='ok'){//by pm
		$date=date('Y-m-d');
		$acc=$_GET['acc'];
		$sql_ps="update npd_ps set approve_by='".$_GET['acc']."'";
		$sql_ps .=",feedback='".$_POST['approve']."'";
		$sql_ps .=",approve_date='".$date."'";
		$sql_ps .=" where id_npd_ps='".$_GET['id_u']."'";
		$res_ps=mysql_query($sql_ps) or die ('Error '.$sql_ps);
		$id_ps=$_GET['id_u'];

		$sql="select * from npd_ps where id_npd_ps='".$id_ps."'";
		$res=mysql_query($sql) or die ('Error '.$sql);
		$rs=mysql_fetch_array($res);

		$sql_acc="select * from account where id_account='".$acc."'";
		$res_acc=mysql_query($sql_acc) or die ('Error '.$sql_acc);
		$rs_acc=mysql_fetch_array($res_acc);

		$sql_product="select * from product where id_product='".$rs['id_product']."'";
		$res_product=mysql_query($sql_product) or die ('Error '.$sql_product);
		$rs_product=mysql_fetch_array($res_product);

		$MailTo ="piyarat.k@cdipthailand.com";
		$MailFrom = $rs_acc['email'];
		$MailSubject = "=?UTF-8?B?".base64_encode("NPD_PM : ".$rs_product['product_name'])."?=";
		$MailMessage ="เรียน แผนก PS";
		$MailMessage .="<br><br>";
		$MailMessage .="กดยืนยันเรียบร้อย";
		$MailMessage .="<br><br>";
		$MailMessage .="ตรวจสอบข้อมูลได้ที่  ระบบ COS เมนุ ";
		$MailMessage .="<a href='http://www.cdipthailand.com/cos' target='_blank'>Product Development ->	Product Detail Feedback</a>";
		$MailMessage .="<br><br>";
		$MailMessage .="Best regards,";
		$MailMessage .="<br><br>";
		$MailMessage .=$rs_acc['name'];
		$MailMessage .="<br>";
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

		$Headers = "MIME-Version: 1.0\r\n" ;
		$Headers .= "Content-type: text/html; charset=utf-8\r\n"; 
		$Headers .= "From: ".$rs_acc['email']." <".$rs_acc['name'].">\r\n";
		$Headers .= "Cc:jureewan.r@cdipthailand.com\r\n";
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
	}else
	if($_GET['success']=='ps'){
		$date=date('Y-m-d');
		$acc=$_GET['acc'];
		$sql_ps="update npd_ps set receive_by='".$_GET['acc']."'";
		$sql_ps .=",receive_date='".$date."'";
		$sql_ps .=" where id_npd_ps='".$_GET['id_u']."'";
		$res_ps=mysql_query($sql_ps) or die ('Error '.$sql_ps);
		$id_ps=$_GET['id_u'];


		$sql="select * from npd_ps where id_npd_ps='".$id_ps."'";
		$res=mysql_query($sql) or die ('Error '.$sql);
		$rs=mysql_fetch_array($res);

		$sql_acc="select * from account where id_account='".$rs['approve_by']."'";
		$res_acc=mysql_query($sql_acc) or die ('Error '.$sql_acc);
		$rs_acc=mysql_fetch_array($res_acc);

		$sql_product="select * from product where id_product='".$rs['id_product']."'";
		$res_product=mysql_query($sql_product) or die ('Error '.$sql_product);
		$rs_product=mysql_fetch_array($res_product);

		$MailTo =$rs['mail_to'];
		$MailFrom = $rs_acc['name'];
		$MailSubject = "=?UTF-8?B?".base64_encode("SM_NPD : ".$rs_product['product_name'])."?=";
		$MailMessage =$rs['mail_massage'];
		$MailMessage .="เรียน ".$rs_acc['name'];
		$MailMessage .="<br><br>";
		$MailMessage .="ได้รับข้อมูลเรียบร้อยแล้ว";
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
		$Headers .= "From: piyarat.k@cdipthailand.com <Piyarat Kongsawat>\r\n";
		$Headers .= "Cc:jureewan.r@cdipthailand.com\r\n";
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
	}
?>
	<script>
		window.location.href='ac-ps-feedback.php?id_u=<?=$id_ps?>';
	</script>
<?php 
}
?>
</body>
</html>