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
$pages=$_POST['pages'];
$date=date("Y-m-d");
$modify=date("Y-m-d H:i:s");
	
if($_POST['mode']=='New'){
	$id=$_POST['id_roc'];
	$mode='&mode=New';
	/*add roc rm*/
	if($_POST["hdnCmd"] == "add_rm"){
		$total=0;
		$sql="insert into npd_project_relation(id_roc,npd_rm_name";
		$sql .=",rm_yield,npd_rm_quantity,npd_rm_equl";
		$sql .=",npd_rm_quantity_equl,npd_supplier,npd_rm_price)";
		$sql .=" values('".$_POST['id_roc']."','".$_POST['npd_rm']."','99'";
		$sql .=",'".$_POST['npd_rm_quantity']."','".$_POST['npd_rm_equl']."'";
		$sql .=",'".$_POST['npd_rm_quantity_equl']."','".$_POST['npd_supplier']."'";
		$sql .=",'".$_POST['npd_rm_price']."')";
		$rs=mysql_query($sql) or die ('Error '.$sql);				
	}

	/*update roc rm*/
	if($_POST["hdnCmd"] == "update_rm"){
		$id=$_POST['id_roc'];
		$sql = "update npd_project_relation set npd_rm_name='".$_POST['npd_rm2']."'";
		$sql .=",rm_yield='99',npd_rm_quantity='".$_POST['npd_rm_quantity2']."'";
		$sql .= ",npd_rm_equl='".$_POST['npd_rm_equl2']."'";
		$sql .= ",npd_rm_quantity_equl='".$_POST['npd_rm_quantity_equl2']."'";
		$sql .= ",npd_supplier='".$_POST['npd_supplier2']."'";
		$sql .= ",npd_rm_price='".$_POST['npd_rm_price2']."'";
		$sql .=" where id_npd_project_rela = '".$_POST["hdnEdit"]."' ";
		$res = mysql_query($sql) or die ('Error '.$sql);

		$sql_rela="select * from npd_project_relation";
		$sql_rela .=" where id_roc='".$_POST['id_roc']."'";
		$res_rela=mysql_query($sql_rela) or die ('Error '.$sql_rela);
		while($rs_rela=mysql_fetch_array($res_rela)){
			$total=$total+$rs_rela['npd_rm_quantity'];
		}

		$sql_project="update npd_project_evaluation set npd_total='".$total."'";
		$sql_project .=" where id_roc='".$_POST['id_roc']."'";
		$res_project=mysql_query($sql_project) or die ('Error '.$sql_project);
	}
		
	$sql_eva="select * from npd_project_evaluation where id_roc='".$_POST['id_roc']."'";
	$res_eva=mysql_query($sql_eva) or die ('Error '.$sql_eva);
	$rs_eva=mysql_fetch_array($res_eva);
	if(!$rs_eva){
		$sql_eva1="insert into npd_project_evaluation(id_roc,npd_code,npd_month";
		$sql_eva1 .=",npd_year,npd_num,npd_total,npd_unit,product_app_rd,how_use";
		$sql_eva1 .=",storage,id_manufacturer,id_pack,rd_account,type_fda,other_fda";
		$sql_eva1 .=",description,create_by,create_date,project_status) values";
		$sql_eva1 .=" ('".$id."','".$_POST['npd_code']."','".$_POST['npd_month']."'";
		$sql_eva1 .=",'".$_POST['npd_year']."','".$_POST['npd_num']."'";
		$sql_eva1 .=",'".$_POST['npd_unit']."','".$_POST['product_app']."'";
		$sql_eva1 .=",'".$_POST['npd_total']."','".$_POST['how_use']."'";
		$sql_eva1 .=",'".$_POST['storage']."','".$_POST['factory']."'";
		$sql_eva1 .=",'".$_POST['pack']."','".$_POST['id_rd_account']."'";
		$sql_eva1 .=",'".$_POST['npd_type_fda']."','".$_POST['other_fda']."'";
		$sql_eva1 .=",'".$_POST['description']."','".$rs_account['id_account']."'";
		$sql_eva1 .=",'".$date."','".$_POST['project_status']."')";
		$res_eva1=mysql_query($sql_eva1) or die ('Error '.$sql_eva1);

		$sql_rela="select * from npd_project_relation";
		$sql_rela .=" where id_roc='".$_POST['id_roc']."'";
		$res_rela=mysql_query($sql_rela) or die ('Error '.$sql_rela);
		while($rs_rela=mysql_fetch_array($res_rela)){
			$total=$total+$rs_rela['npd_rm_quantity'];
		}

		$sql_project="update npd_project_evaluation set npd_total='".$total."'";
		$sql_project .=" where id_roc='".$_POST['id_roc']."'";
		$res_project=mysql_query($sql_project) or die ('Error '.$sql_project);
	}else{
		$sql_rela="select * from npd_project_relation";
		$sql_rela .=" where id_roc='".$_POST['id_roc']."'";
		$res_rela=mysql_query($sql_rela) or die ('Error '.$sql_rela);
		while($rs_rela=mysql_fetch_array($res_rela)){
			$total=$total+$rs_rela['npd_rm_quantity'];
		}

		$sql_project="update npd_project_evaluation set npd_total='".$total."'";
		$sql_project .=" where id_roc='".$_POST['id_roc']."'";
		$res_project=mysql_query($sql_project) or die ('Error '.$sql_project);
	}

	$sql_costing="insert into costing_factory(id_roc,id_product)";
	$sql_costing .=" value ('".$_POST['id_roc']."','".$_POST['id_product']."')";
	$res_costing=mysql_query($sql_costing) or die ('Error '.$sql_costing);
	$id_costing=mysql_insert_id();

	$sql_blister="insert into costing_pack_blister(id_costing_factory)";
	$sql_blister .=" value ('".$id_costing."')";
	$res_blister=mysql_query($sql_blister) or die ('Error '.$sql_blister);
?>
	<script>
		window.location.href='ac-project-eva.php?id_u=<?=$id?>';
	</script>
<?php }else{
	$id=$_POST['mode'];
	if($_POST["hdnCmd"] == "add_rm"){
		$total=0;
		$sql="insert into npd_project_relation(id_roc,npd_rm_name";
		$sql .=",rm_yield,npd_rm_quantity,npd_rm_equl";
		$sql .=",npd_rm_quantity_equl,npd_supplier,npd_rm_price)";
		$sql .=" values('".$id."','".$_POST['npd_rm']."','99'";
		$sql .=",'".$_POST['npd_rm_quantity']."','".$_POST['npd_rm_equl']."'";
		$sql .=",'".$_POST['npd_rm_quantity_equl']."','".$_POST['npd_supplier']."'";
		$sql .=",'".$_POST['npd_rm_price']."')";
		$rs=mysql_query($sql) or die ('Error '.$sql);
				
		$sql_rela="select * from npd_project_relation";
		$sql_rela .=" where id_roc='".$_POST['id_roc']."'";
		$res_rela=mysql_query($sql_rela) or die ('Error '.$sql_rela);
		while($rs_rela=mysql_fetch_array($res_rela)){
			$total=$total+$rs_rela['npd_rm_quantity'];
		}

		$sql_project="update npd_project_evaluation set npd_total='".$total."'";
		$sql_project .=" where id_roc='".$_POST['id_roc']."'";
		$res_project=mysql_query($sql_project) or die ('Error '.$sql_project);
	}

		/*update roc rm*/
		if($_POST["hdnCmd"] == "update_rm"){
			$id=$_POST['id_roc'];
			$sql = "update npd_project_relation set npd_rm_name='".$_POST['npd_rm2']."'";
			$sql .=",rm_yield='99',npd_rm_quantity='".$_POST['npd_rm_quantity2']."'";
			$sql .= ",npd_rm_equl='".$_POST['npd_rm_equl2']."'";
			$sql .= ",npd_rm_quantity_equl='".$_POST['npd_rm_quantity_equl2']."'";
			$sql .= ",npd_supplier='".$_POST['npd_supplier2']."'";
			$sql .= ",npd_rm_price='".$_POST['npd_rm_price2']."'";
			$sql .=" where id_npd_project_rela = '".$_POST["hdnEdit"]."' ";
			$res = mysql_query($sql) or die ('Error '.$sql);

			$sql_rela="select * from npd_project_relation";
			$sql_rela .=" where id_roc='".$_POST['id_roc']."'";
			$res_rela=mysql_query($sql_rela) or die ('Error '.$sql_rela);
			while($rs_rela=mysql_fetch_array($res_rela)){
				$total=$total+$rs_rela['npd_rm_quantity'];
			}

			$sql_project="update npd_project_evaluation set npd_total='".$total."'";
			$sql_project .=" where id_roc='".$_POST['id_roc']."'";
			$res_project=mysql_query($sql_project) or die ('Error '.$sql_project);
		}

		if($_POST["hdnCmd"] == "update_data"){
			$sql="update npd_project_evaluation set npd_code='".$_POST['npd_code']."'";
			$sql .=",npd_month='".$_POST['npd_month']."',npd_year='".$_POSt['npd_year']."'";
			$sql .=",npd_num='".$_POST['npd_num']."',npd_unit='".$_POST['npd_unit']."'";
			$sql .=",product_app_rd='".$_POST['product_app']."',other_fda='".$_POST['other_fda']."'";
			$sql .=",how_use='".$_POST['how_use']."',npd_total='".$_POST['npd_total']."'";
			$sql .=",selling_point='".$_POST['selling_point']."',storage='".$_POST['storage']."'";
			$sql .=",id_manufacturer='".$_POST['factory']."',id_pack='".$_POST['pack']."'";
			$sql .=",rd_account='".$_POST['id_rd_account']."',type_fda='".$_POST['npd_type_fda']."'";
			$sql .=",description='".$_POST['description']."',project_status='".$_POST['project_status']."'";
			$sql .=" where id_roc='".$_POST['id_roc']."'";
			$res=mysql_query($sql) or die ('Error '.$sql);
		}
		
		if($_POST["hdnCmd"] == "send_email"){
			$sql_roc="select * from roc where id_roc='".$_POST['id_roc']."'";
			$res_roc=mysql_query($sql_roc) or die ('Error '.$sql_roc);
			$rs_rco=mysql_fetch_array($res_roc);
			
			$sql_emp="select * from account where id_account='".$rs_roc['create_by']."'";
			$res_emp=mysql_query($sql_emp) or die ('Error '.$sql_emp);
			$rs_emp=mysql_fetch_array($res_emp);

			$sql_eva="select * from npd_project_evaluation where id_roc='".$rs_roc['id_roc']."'";
			$res_eva=mysql_query($sql_eva) or die ('Error '.$sql_eva);
			$rs_eva=mysql_fetch_array($res_eva);

			$sql_rd="select * from account where id_account='".$rs_eva['rd_account']."'";
			$res_rd=mysql_query($sql_rd) or die ('Error '.$sql_rd);
			$rs_rd=mysql_fetch_array($res_rd);

			$MailTo = $rs_emp['email'];
			$MailFrom = $rs_rd['email'];
			$MailSubject = "=?UTF-8?B?".base64_encode("NPD_".strtoupper($rs_emp['username'])." : ".$_POST['roc_code']." ".$_POST['product_name'])."?=";
			$MailMessage = "เรียนผู้เกี่ยวข้อง";
			$MailMessage .="<br><br>";
			$MailMessage .="ฝ่าย SM ได้จัดทำ ".$_POST['roc_code'];
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
					window.location.href='project_eva.php';
				</script>
			<?php }else{?>
				<script>
					window.alert('Send mail False');
					history.back();
				</script>
			<?php }
		}
		
?>
	<script>
		window.location.href='ac-project-eva.php?id_u=<?=$id?>';
	</script>
<?php }?>
</body>
</html>