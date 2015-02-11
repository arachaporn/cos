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
include("mpdf/mpdf.php");
ob_start();
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
	$pages=$_POST['pages'];
	/*add array roc function */
	if($_POST['roc_group_product']==1){$roc_function_array=$_POST['roc_function1'];}
	elseif($_POST['roc_group_product']==2){$roc_function_array=$_POST['roc_function2'];}
	elseif($_POST['roc_group_product']==3){$roc_function_array=$_POST['roc_function3'];}
	elseif($_POST['roc_group_product']==4){$roc_function_array=$_POST['roc_function4'];}
	$tag_string="";
	while (list ($key,$val) = @each ($roc_function_array)) {
	//echo "$val,";
	$tag_string.=$val.",";
	}
	$roc_function=substr($tag_string,0,(strLen($tag_string)-1));// remove the last , from string
	
	/*add array roc group functin*/
	$roc_group_product_array=$_POST['roc_group_product'];
	$tag_group_product_string="";
	while (list ($key_group_product,$val_group_product) = @each ($roc_group_product_array)) {
	//echo "$val,";
	$tag_group_product_string.=$val_group_product.",";
	}
	$roc_group_product2=substr($tag_group_product_string,0,(strLen($tag_group_product_string)-1));// remove the last , from string


	/*add array roc product value*/
	$roc_product_value_array=$_POST['roc_product_value'];
	$tag_string="";
	while (list ($key,$val) = @each ($roc_product_value_array)) {
	//echo "$val,";
	$tag_string.=$val.",";
	}
	$roc_product_value=substr($tag_string,0,(strLen($tag_string)-1));// remove the last , from string
	
	if($_POST['roc_group_product']==-1){$roc_group_product= '-1';}
	else{$roc_group_product=$_POST['roc_group_product'];}

	
	/*$roc_function_other_array=$_POST['roc_function_other'];
	$tag_string_other="";
	while (list ($key_other,$val_other) = @each ($roc_function_other_array)) {
		//echo "$val,";
		$tag_string_other.=$val_other.",";
	}
	$roc_function_other=substr($tag_string_other,0,(strLen($tag_string_other)-1));// remove the last , from string
	*/

	if($_POST['roc_group_product']==1){$roc_function_other=$_POST['roc_function_other1'];}
	elseif($_POST['roc_group_product']==2){$roc_function_other=$_POST['roc_function_other2'];}
	elseif($_POST['roc_group_product']==3){$roc_function_other=$_POST['roc_function_other3'];}
	elseif($_POST['roc_group_product']==4){$roc_function_other=$_POST['roc_function_other4'];}

	// ZAMACHITA meeting
	$post_company_name = mysql_real_escape_string($_POST['company_name']);
	$post_com_contact = mysql_real_escape_string($_POST['com_contact']);

	/*select company*/
	$sql_company="select * from company where company_name like '".$post_company_name."'";
	$res_company=mysql_query($sql_company) or die ('Error '.$sql_company);
	$rs_company=mysql_fetch_array($res_company);
	if(!$rs_company){

		$sql_address="insert into company_address(address_no) values";
		$sql_address .=" ('')";
		$res_address=mysql_query($sql_address) or die('Error '.$sql_contact);
		$id_address=mysql_insert_id();

		$sql_ins_com="insert into company(company_name,id_address,id_com_cat,company_tel";
		$sql_ins_com .=",company_fax) values('".$post_company_name."','".$id_address."'";
		$sql_ins_com .=",'".$_POST['com_cate']."','".$_POST['company_tel']."'";
		$sql_ins_com .=",'".$_POST['company_fax']."')";
		$res_ins_com=mysql_query($sql_ins_com) or die ('Error '.$sql_ins_com);
		$id_company=mysql_insert_id();
					
		$sql_contact="insert into company_contact(id_company,contact_name,mobile,email)";
		$sql_contact .=" values('".$id_company."','".$post_com_contact."'";
		$sql_contact .=",'".$_POST['mobile']."','".$_POST['company_email']."')";
		$res_contact=mysql_query($sql_contact) or die('Error '.$sql_contact);
		$id_contact=mysql_insert_id();
	}else{ 
		$id_company=$rs_company['id_company'];
		$id_address2=$_POST['id_address'];
		$sql_contact="select * from company_contact where contact_name like '".$post_com_contact."'";
		$sql_contact .=" and id_company='".$id_company."'";
		$res_contact=mysql_query($sql_contact) or die ('Error '.$sql_contact);
		$rs_contact=mysql_fetch_array($res_contact);
		if($rs_contact){
			//$sql_contact2="update company_contact where contact_name='".$_POST['company_contact2']."'";
			//echo$sql_contact2 .=" where id_contact='".$id_company."'";
			//$res_contact2=mysql_query($sql_contact2) or die('Error '.$sql_contact2);
			//$id_contact=mysql_insert_id();
			$id_contact=$rs_contact['id_contact'];
		}
		else{
			$sql_contact2="insert into company_contact(id_company,contact_name,mobile,email)";
			$sql_contact2 .=" values('".$id_company."','".$post_com_contact."'";
			$sql_contact2 .=",'".$_POST['mobile']."','".$_POST['company_email']."')";
			$res_contact2=mysql_query($sql_contact2) or die('Error '.$sql_contact2);
			$id_contact=mysql_insert_id();
		}
	}	
	
	// ZAMACHITA meeting
	$post_product_name = mysql_real_escape_string($_POST['product_name']);

	/*product*/
	$sql_product="select * from product where product_name like '".$post_product_name."'";
	$res_product=mysql_query($sql_product) or die ('Error '.$sql_product);
	$rs_product=mysql_fetch_array($res_product);
	if(!$rs_product){
		$sql_ins_pro="insert into product(id_company,product_name,create_by,create_date) values";
		$sql_ins_pro .="('".$id_company."','".$post_product_name."'";
		$sql_ins_pro .=",'".$rs_account['id_account']."','".$date."')";
		$res_ins_pro=mysql_query($sql_ins_pro) or die ('Error '.$sql_ins_pro);
		$id_product=mysql_insert_id();
	}else{
		$id_product=$_POST['id_product'];
	}

	if($_POST['id_address'] != 0){$id_address=$_POST['id_address'];}
	else{$id_address=0;}

		
	$sql_roc="insert into roc(roc_code,roc_month,roc_num,id_product,id_contact";
	$sql_roc .=",id_company,address,id_com_cat,date_roc,id_address,id_type_product";
	$sql_roc .=",id_group_product,other_group_product,id_roc_func";
	$sql_roc .=",roc_func_other,roc_status,create_by,create_date)";
	$sql_roc .=" values ('".$_POST['roc_code']."','".$_POST['roc_month']."'";
	$sql_roc .=",'".$_POST['roc_num']."','".$id_product."','".$id_contact."'";
	$sql_roc .=",'".$id_company."','".$_POST['company_address']."'";
	$sql_roc .=",'".$_POST['com_cate']."','".$_POST['date_roc']."'";
	$sql_roc .=",'".$id_address."','".$_POST['type_product']."'";
	$sql_roc .=",'".$roc_group_product2."','".$_POST['other_group_product']."'";
	$sql_roc .=",'".$roc_function."'";
	$sql_roc .=",'".$roc_function_other."','".$_POST['roc_status']."'";
	$sql_roc .=",'".$_POST['account']."','".$date."')";
	$res_roc=mysql_query($sql_roc) or die ('Error '.$sql_roc);
	$id=mysql_insert_id();
	$pages=$pages+1;

	if($post_company_name == ''){
	?>
		<script>
			window.alert('กรุณาระบุชื่อบริษัท');
			history.back();
		</script>
	<?php }elseif($post_com_contact == ''){?>
		<script>
			window.alert('กรุณาระบุชื่อผู้ติดต่อ');
			history.back();
		</script>
	<?php }elseif($_POST['company_tel'] == ''){?>
		<script>
			window.alert('กรุณาระบุเบอร์โทรศัพท์');
			history.back();
		</script>
	<?php }elseif($_POST['mobile'] == ''){?>
		<script>
			window.alert('กรุณาระบุเบอร์มือถือ');
			history.back();
		</script>
	<?php }elseif($_POST['company_email'] == ''){?>
		<script>
			window.alert('กรุณาระบุอีเมล์');
			history.back();
		</script>
	<?php }elseif($_POST['company_address'] == ''){?>
		<script>
			window.alert('กรุณาระบุที่อยู่');
			history.back();
		</script>
	<?php }elseif($_POST['com_cate'] == ''){?>
		<script>
			window.alert('กรุณาระบุ Identify Customer');
			history.back();
		</script>
	<?php }elseif($post_product_name == ''){?>
		<script>
			window.alert('กรุณาระบุ Project Name/Benchmark');
			history.back();
		</script>
	<?php }elseif($_POST['type_product'] == ''){?>
		<script>
			window.alert('กรุณาระบุชนิดของผลิตภัณฑ์');
			history.back();
		</script>
	<?php }elseif($roc_group_product2 == ''){?>
		<script>
			window.alert('กรุณาระบุฟังก์ชั่นการทำงาน');
			history.back();
		</script>
	<?php }else{?>
		<script>
			window.location.href='ac-roc.php?id_u=<?=$id?>&p=<?=$pages?>';
		</script>
	<?php }?>
<?php
}else{
	$roc_rev=$_POST['roc_rev'];
	$id=$_POST['mode'];
	if($roc_rev<1){
		/*add roc rm*/
		if($_POST["hdnCmd"] == "add_rm"){
			$sql="insert into roc_rm(id_roc,roc_rm)";
			$sql .=" values('".$_POST['id_roc2']."','".$_POST['roc_rm']."')";
			$rs=mysql_query($sql) or die ('Error '.$sql);
			$pages2=$_POST['pages'];
		?>	
			<script>
				window.location.href='ac-roc.php?id_u=<?=$id?>&p=2';
			</script>
		<?php }
		/*update roc rm*/
		if($_POST["hdnCmd"] == "update_rm"){
			$sql = "update roc_rm set roc_rm='".$_POST['roc_rm2']."'";
			$sql .=" where id_roc_rm = '".$_POST["hdnEdit"]."' ";
			$res = mysql_query($sql) or die ('Error '.$sql);
		?>	
			<script>
				window.location.href='ac-roc.php?id_u=<?=$id?>&p=2';
			</script>
		<?php }

		/*delete roc rm*/
		if($_GET["action"] == "del_rm"){
			$sql = "delete from roc_rm ";
			$sql .="where id_roc_rm = '".$_GET["id_p"]."'";
			$res = mysql_query($sql) or die ('Error '.$sql);
		?>	
			<script>
				window.location.href='ac-roc.php?id_u=<?=$id?>&p=2';
			</script>
		<?php }

		if($_POST["hdnCmd"] == "sign"){
			$pages=$_POST['pages'];
			$id=$_POST['mode'];
			if($_POST['code_sign']==''){
		?>
			<script>
				window.alert('Please insert password signature');
				history.back();
			</script>
		<?php }else{
			$sql_roc="select * from roc where id_roc='".$id."'";
			$res_roc=mysql_query($sql_roc) or die ('Error '.$sql_roc);
			$rs_roc=mysql_fetch_array($res_roc);

			$sql_sign="select * from signature where id_account='".$rs_roc['create_by']."'";
			$res_sign=mysql_query($sql_sign) or die ('Error '.$sql_sign);
			$rs_sign=mysql_fetch_array($res_sign);
			if($_POST['code_sign']==$rs_sign['password_sign']){
				$sql="update roc set roc_sign='1'";
				$sql .=" where id_roc='".$id."'";
				$res=mysql_query($sql) or die ('Error '.$sql);			
			}else{
			?>
				<script>
					window.alert('Password incorret!!!');
					history.back();
				</script>
			<?php }?>
			<script>
				window.location.href='ac-roc.php?id_u=<?=$id?>&p=<?=$pages?>';
			</script>
		<?php }
		}

		if($_POST['pages']==1){
			$pages=$_POST['pages']+1;

			// ZAMACHITA meeting
			$post_company_name = mysql_real_escape_string($_POST['company_name']);
			$post_com_contact = mysql_real_escape_string($_POST['com_contact']);
			/*select company*/
			$sql_company="select * from company where company_name like '".$post_company_name."'";
			$res_company=mysql_query($sql_company) or die ('Error '.$sql_company);
			$rs_company=mysql_fetch_array($res_company);
			if(!$rs_company){

				$sql_address="insert into company_address(address_no) values";
				$sql_address .=" ('')";
				$res_address=mysql_query($sql_address) or die('Error '.$sql_contact);
				$id_address=mysql_insert_id();

				$sql_ins_com="insert into company(company_name,id_address,id_com_cat,company_tel";
				$sql_ins_com .=",company_fax) values('".$post_company_name."','".$id_address."'";
				$sql_ins_com .=",'".$_POST['com_cate']."','".$_POST['company_tel']."'";
				$sql_ins_com .=",'".$_POST['company_fax']."')";
				$res_ins_com=mysql_query($sql_ins_com) or die ('Error '.$sql_ins_com);
				$id_company=mysql_insert_id();
							
				$sql_contact="insert into company_contact(id_company,contact_name,mobile,email)";
				$sql_contact .=" values('".$id_company."','".$post_com_contact."'";
				$sql_contact .=",'".$_POST['mobile']."','".$_POST['email']."')";
				$res_contact=mysql_query($sql_contact) or die('Error '.$sql_contact);
				$id_contact=mysql_insert_id();
			}else{ 
				$id_company=$rs_company['id_company'];
				$id_address2=$_POST['id_address'];
				$sql_contact="select * from company_contact where contact_name like '".$post_com_contact."'";
				$sql_contact .=" and id_company='".$id_company."'";
				$res_contact=mysql_query($sql_contact) or die ('Error '.$sql_contact);
				$rs_contact=mysql_fetch_array($res_contact);
				if($rs_contact){
					//$sql_contact2="update company_contact where contact_name='".$_POST['company_contact2']."'";
					//echo$sql_contact2 .=" where id_contact='".$id_company."'";
					//$res_contact2=mysql_query($sql_contact2) or die('Error '.$sql_contact2);
					//$id_contact=mysql_insert_id();
					$id_contact=$rs_contact['id_contact'];
				}
				else{
					$sql_contact2="insert into company_contact(id_company,contact_name,mobile,email)";
					$sql_contact2 .=" values('".$id_company."','".$post_com_contact."'";
					$sql_contact2 .=",'".$_POST['mobile']."','".$_POST['email']."')";
					$res_contact2=mysql_query($sql_contact2) or die('Error '.$sql_contact2);
					$id_contact=mysql_insert_id();
				}
			}	
			
			// ZAMACHITA meeting
			$post_product_name = mysql_real_escape_string($_POST['product_name']);

			/*product*/
			$sql_product="select * from product where product_name like '".$post_product_name."'";
			$res_product=mysql_query($sql_product) or die ('Error '.$sql_product);
			$rs_product=mysql_fetch_array($res_product);
			if(!$rs_product){
				$sql_ins_pro="insert into product(id_company,product_name,create_by,create_date) values";
				$sql_ins_pro .="('".$id_company."','".$post_product_name."'";
				$sql_ins_pro .=",'".$rs_account['id_account']."','".$date."')";
				$res_ins_pro=mysql_query($sql_ins_pro) or die ('Error '.$sql_ins_pro);
				$id_product=mysql_insert_id();
			}else{
				$id_product=$_POST['id_product'];
			}

			/*add array roc function
			if($_POST['roc_group_product']==1){$roc_function_array=$_POST['roc_function1'];}
			elseif($_POST['roc_group_product']==2){$roc_function_array=$_POST['roc_function2'];}
			elseif($_POST['roc_group_product']==3){$roc_function_array=$_POST['roc_function3'];}
			elseif($_POST['roc_group_product']==4){$roc_function_array=$_POST['roc_function4'];}
			*/

			$roc_function_array=$_POST['roc_function'];
			$tag_string="";
			while (list ($key,$val) = @each ($roc_function_array)) {
			//echo "$val,";
			$tag_string.=$val.",";
			}
			$roc_function=substr($tag_string,0,(strLen($tag_string)-1));// remove the last , from string
			
			
			$roc_function_other_array=$_POST['roc_function_other'];
			$tag_string_other="";
			while (list ($key_other,$val_other) = @each ($roc_function_other_array)) {
			//echo "$val,";
			$tag_string_other.=$val_other.",";
			}
			$roc_function_other=substr($tag_string_other,0,(strLen($tag_string_other)-1));// remove the last , from string*/
			

			/*add array roc group functin*/
			$roc_group_product_array=$_POST['roc_group_product'];
			$tag_group_product_string="";
			while (list ($key_group_product,$val_group_product) = @each ($roc_group_product_array)) {
			//echo "$val,";
			$tag_group_product_string.=$val_group_product.",";
			}
			$roc_group_product2=substr($tag_group_product_string,0,(strLen($tag_group_product_string)-1));// remove the last , from string
			
			/*add array roc product value*/
			$roc_product_value_array=$_POST['roc_product_value'];
			$tag_string="";
			while (list ($key,$val) = @each ($roc_product_value_array)) {
			//echo "$val,";
			$tag_string.=$val.",";
			}
			$roc_product_value=substr($tag_string,0,(strLen($tag_string)-1));// remove the last , from string

			if($_POST['roc_group_product']==-1){$roc_group_product= '-1';}
			else{$roc_group_product=$_POST['roc_group_product'];}

			if($_POST['id_address'] != 0){$id_address=$_POST['id_address'];}
			else{$id_address=0;}

			$sql_up_company="update company set company_tel='".$_POST['company_tel']."'";
			$sql_up_company .=",company_fax='".$_POST['company_fax']."'";
			$sql_up_company .=" where id_company='".$_POST['id_company']."'";
			$res_up_company=mysql_query($sql_up_company) or die ('Error '.$sql_up_company);

			$sql_up_contact="update company_contact set mobile='".$_POST['mobile']."'";
			$sql_up_contact .=",contact_name='".$_POST['com_contact']."'";
			$sql_up_contact .=",email='".$_POST['company_email']."'";
			$sql_up_contact .=" where id_contact='".$_POST['id_contact']."'";
			$res_up_contact=mysql_query($sql_up_contact) or die ('Error '.$sql_up_contact);

			$sql_up_roc="update roc set id_product='".$id_product."'";
			$sql_up_roc .=",id_contact='".$_POST['id_contact']."'";
			$sql_up_roc .=",id_company='".$id_company."'";
			$sql_up_roc .=",address='".$_POST['company_address']."'";
			$sql_up_roc .=",id_com_cat='".$_POST['com_cate']."'";
			$sql_up_roc .=",other_category='".$_POST['other_category']."'";
			$sql_up_roc .=",date_roc='".$_POST['date_roc']."'";
			$sql_up_roc .=",id_address='".$_POST['id_address']."'";
			$sql_up_roc .=",address='".$_POST['company_address']."'";
			$sql_up_roc .=",id_type_product='".$_POST['type_product']."'";
			$sql_up_roc .=",id_group_product='".$roc_group_product2."'";
			$sql_up_roc .=",other_group_product='".$_POST['other_group_product']."'";
			$sql_up_roc .=",id_roc_func='".$roc_function."'";
			$sql_up_roc .=",roc_func_other='".$roc_function_other."'";
			$sql_up_roc .=",roc_status='".$_POST['roc_status']."'";
			$sql_up_roc .=" where id_roc='".$_POST['mode']."'";
			$res_roc=mysql_query($sql_up_roc) or die ('Error '.$sql_up_roc);
			
			if($post_company_name == ''){
			?>
				<script>
					window.alert('กรุณาระบุชื่อบริษัท');
					history.back();
				</script>
			<?php }elseif($post_com_contact == ''){?>
				<script>
					window.alert('กรุณาระบุชื่อผู้ติดต่อ');
					history.back();
				</script>
			<?php }elseif($_POST['company_tel'] == ''){?>
				<script>
					window.alert('กรุณาระบุเบอร์โทรศัพท์');
					history.back();
				</script>
			<?php }elseif($_POST['mobile'] == ''){?>
				<script>
					window.alert('กรุณาระบุเบอร์มือถือ');
					history.back();
				</script>
			<?php }elseif($_POST['company_email'] == ''){?>
				<script>
					window.alert('กรุณาระบุอีเมล์');
					history.back();
				</script>
			<?php }elseif($_POST['company_address'] == ''){?>
				<script>
					window.alert('กรุณาระบุที่อยู่');
					history.back();
				</script>
			<?php }elseif($_POST['com_cate'] == ''){?>
				<script>
					window.alert('กรุณาระบุ Identify Customer');
					history.back();
				</script>
			<?php }elseif($post_product_name == ''){?>
				<script>
					window.alert('กรุณาระบุ Project Name/Benchmark');
					history.back();
				</script>
			<?php }elseif($_POST['type_product'] == ''){?>
				<script>
					window.alert('กรุณาระบุชนิดของผลิตภัณฑ์');
					history.back();
				</script>
			<?php }elseif($roc_group_product2 == ''){?>
				<script>
					window.alert('กรุณาระบุฟังก์ชั่นการทำงาน');
					history.back();
				</script>			
			<?php }else{?>
				<script>
					window.location.href='ac-roc.php?id_u=<?=$id?>&p=<?=$pages?>';
				</script>
			<?php }?>
		<?php
		}
		elseif($_POST['pages']==2){	
			$id_roc=$_POST['mode'];
			$pages=$_POST['pages']+1;
				
			$product_app=$_POST['id_product_appearance'];
			if($product_app != 7){$product_weight=$_POST['roc_product_weight'];}
			else{$product_weight='0';}
			
			if($product_app==1){$roc_value_array=$_POST['roc_product_value1'];}
			elseif($product_app==2){$roc_value_array=$_POST['roc_product_value2'];}
			elseif($product_app==3){$roc_value_array=$_POST['roc_product_value3'];}
			elseif($product_app==4){$roc_value_array=$_POST['roc_product_value4'];}
			elseif($product_app==5){$roc_value_array=$_POST['roc_product_value5'];}
			elseif($product_app==6){$roc_value_array=$_POST['roc_product_value6'];}
			elseif($product_app==7){$roc_value_array=$_POST['roc_product_value7'];}
			/*add array roc product value*/
			$roc_product_value_array=$roc_value_array;
			$tag_string_value.="";
			while (list ($key_value,$val_value) = @each ($roc_product_value_array)) {
				//echo "$val,";
				$tag_string_value.=$val_value.",";
			}
			$product_value=substr($tag_string_value,0,(strLen($tag_string_value)-1));// remove the last , from string
			
			if(($product_app==5) || ($product_app==6) || ($product_app==7)){
				if($product_app==5){$roc_product_value_o_array=$_POST['roc_product_value_other'];}
				elseif($product_app==6){$roc_product_value_o_array=$_POST['roc_product_value_other2'];}
				elseif($product_app==7){$roc_product_value_o_array=$_POST['roc_product_value_other3'];}
				$tag_string_value_o.="";
				while (list ($key_value_o,$val_value_o) = @each ($roc_product_value_o_array)) {
					//echo "$val,";
					$tag_string_value_o.=$val_value_o.",";
				}
				$product_value_o=substr($tag_string_value_o,0,(strLen($tag_string_value_o)-1));// remove the last , from string

				if($_POST['relation_value'] == null){
					$sql_rela_color="insert into roc_relation_value(id_roc,id_product_value,title_value)";
					$sql_rela_color .=" values('".$id_roc."','".$product_value."','".$product_value_o."')";
					$res_rela_color=mysql_query($sql_rela_color) or die ('Error '.$sql_rela_color);	
					$id_relation_value=mysql_insert_id();
				}else{
					$sql_rela_color="update roc_relation_value set id_product_value='".$product_value."'";
					$sql_rela_color .=",title_value='".$product_value_o."'";
					$sql_rela_color .=" where id_relation_value='".$_POST['relation_value']."'";
					$res_rela_color=mysql_query($sql_rela_color) or die ('Error '.$sql_rela_color);
					$id_relation_value=$_POST['relation_value'];
				}
			}

			/*add array roc product color*/
			if($product_app==1){
				$roc_product_color_array=$_POST['type_product_color1'];
				$roc_product_color_title_array=$_POST['type_product_c_other1'];
			}
			elseif($product_app==2){
				$roc_product_color_array=$_POST['type_product_color2'];
				$roc_product_color_title_array=$_POST['type_product_c_other2'];
			}
			elseif($product_app==3){
				$roc_product_color_array=$_POST['type_product_color3'];
				$roc_product_color_title_array=$_POST['type_product_c_other3'];
			}
			elseif($product_app==4){
				$roc_product_color_array=$_POST['type_product_color4'];
				$roc_product_color_title_array=$_POST['type_product_c_other4'];
			}
			
			$tag_string_color.="";
			while (list ($key_color,$val_color) = @each ($roc_product_color_array)) {
				//echo "$val,";
				$tag_string_color.=$val_color.",";
			}
			$product_color=substr($tag_string_color,0,(strLen($tag_string_color)-1));// remove the last , from string
			
			$tag_string_color_title.="";
			while (list ($key_color_title,$val_color_title) = @each ($roc_product_color_title_array)) {
				//echo "$val,";
				$tag_string_color_title.=$val_color_title.",";
			}
			$product_color_title=substr($tag_string_color_title,0,(strLen($tag_string_color_title)-1));// remove the last , from string

			if(($product_app==1) || ($product_app==2) || ($product_app==3) || ($product_app==4)){
				if($_POST['relation_color'] == null){
					$sql_rela_color="insert into roc_relation_color(id_roc,id_type_product_color,title_color)";
					$sql_rela_color .=" values('".$id_roc."','".$product_color."','".$product_color_title."')";
					$res_rela_color=mysql_query($sql_rela_color) or die ('Error '.$sql_rela_color);	
					$id_relation=mysql_insert_id();
				}else{
					$sql_rela_color="update roc_relation_color set id_type_product_color='".$product_color."'";
					$sql_rela_color .=",title_color='".$product_color_title."'";
					$sql_rela_color .=" where id_relation_color='".$_POST['relation_color']."'";
					$res_rela_color=mysql_query($sql_rela_color) or die ('Error '.$sql_rela_color);
					$id_relation=$_POST['relation_color'];
				}
			}
			if(($product_app==1) || ($product_app==2) || ($product_app==3) || ($product_app==7)){$type_product_pack=$_POST['type_product_pack'];}
			else{$type_product_pack=0;}

			if($product_app==4){
				$roc_product_value_other=$_POST['roc_product_value_instant'];
			}else{$roc_product_value_other='';}
			
			$sql_rela_pack="select * from roc_relation_pack";
			$sql_rela_pack .=" where id_relation_pack='".$_POST['id_relation_pack']."'";
			$res_rela_pack=mysql_query($sql_rela_pack) or die ('Error '.$sql_rela_pack);
			$rs_rela_pack=mysql_fetch_array($res_rela_pack);
			if($rs_rela_pack['id_relation_pack']==$_POST['id_relation_pack']){
				$id_relation_pack=$_POST['id_relation_pack'];
			}else{
				$sql_relation_pack="insert into roc_relation_pack(id_roc)";
				$sql_relation_pack .=" values('".$_POST['mode']."')";
				$res_relation_pack=mysql_query($sql_relation_pack) or die ('Error '.$sql_relation_pack);
				$id_relation_pack=mysql_insert_id();
			}

			/*add array roc product weight*/
			if($product_app==1){
				$product_weight_array=$_POST['roc_product_weight_other1'];
			}
			elseif($product_app==2){
				$product_weight_array=$_POST['roc_product_weight_other2'];
			}
			elseif($product_app==3){
				$product_weight_array=$_POST['roc_product_weight_other3'];
			}
			elseif($product_app==4){
				$product_weight_array=$_POST['roc_product_weight_other4'];
			}
			elseif($product_app==5){
				$product_weight_array=$_POST['roc_product_weight_other5'];
			}
			elseif($product_app==6){
				$product_weight_array=$_POST['roc_product_weight_other6'];
			}
			$tag_string_weight.="";
			while (list ($key_weight,$val_weight) = @each ($product_weight_array)) {
				//echo "$val,";
				$tag_string_weight.=$val_weight.",";
			}
			$other_product_weight=substr($tag_string_weight,0,(strLen($tag_string_weight)-1));// remove the last , from string
			

			$sql_up_roc="update roc set id_product_appearance='".$product_app."'";
			$sql_up_roc .=",other_product_app='".$_POST['other_product_app']."'";
			$sql_up_roc .=",id_product_value='".$product_value."'";
			$sql_up_roc .=",product_value_title='".$roc_product_value_other."'";
			$sql_up_roc .=",id_product_weight='".$product_weight."'";
			$sql_up_roc .=",other_product_weight='".$other_product_weight."'";
			$sql_up_roc .=",id_type_product_pack='".$type_product_pack."'";
			$sql_up_roc .=",id_relation_value='".$id_relation_value."'";
			$sql_up_roc .=",id_relation_color='".$id_relation."'";
			$sql_up_roc .=",id_relation_pack='".$id_relation_pack."'";
			$sql_up_roc .=" where id_roc='".$_POST['mode']."'";
			$res_roc=mysql_query($sql_up_roc) or die ('Error '.$sql_up_roc);
			
			if($product_app==0){
			?>
				<script>
					window.alert('กรุณาระบุลักษณะรูปแบบผลิตภัณฑ์');
					history.back();
				</script>
			<?php
			}else{
			?>
				<script>
					window.location.href='ac-roc.php?id_u=<?=$id?>&p=<?=$pages?>';
				</script>
		<?php }
		}
		elseif($_POST['pages']==3){	
			$id_roc=$_POST['mode'];
			$pages=$_POST['pages']+1;

			$product_weight=$_POST['roc_product_weight'];
			$roc_pack=$_POST['roc_pack'];
					
			/*add array foil*/
			if($roc_pack=='1,2,3'){$roc_foil_array=$_POST['foil1'];}
			elseif($roc_pack==4){$roc_foil_array=$_POST['foil4'];}
			$tag_string_foil.="";
			while (list ($key_foil,$val_foil) = @each ($roc_foil_array)) {
				//echo "$val,";
				$tag_string_foil.=$val_foil.",";
			}
			$pack_foil=substr($tag_string_foil,0,(strLen($tag_string_foil)-1));// remove the last , from string

			/*add array materials*/
			if($roc_pack=='1,2,3'){$roc_materials_array=$_POST['materials1'];}
			elseif($roc_pack==4){$roc_materials_array=$_POST['materials4'];}
			elseif($roc_pack==5){$roc_materials_array=$_POST['materials5'];}
			elseif($roc_pack==6){$roc_materials_array=$_POST['materials6'];}
			$tag_string_materials.="";
			while (list ($key_materials,$val_materials) = @each ($roc_materials_array)) {
				//echo "$val,";
				$tag_string_materials.=$val_materials.",";
			}
			$pack_materials=substr($tag_string_materials,0,(strLen($tag_string_materials)-1));// remove the last , from string
			
			/*box detail*/
			if($roc_pack=='1,2,3'){
				$roc_box_array=$_POST['box_detail'];}
			elseif($roc_pack=4){$roc_box_array=$_POST['box_detail2'];}
			elseif($roc_pack==5){$roc_box_array=$_POST['box_detail3'];}
			elseif($roc_pack==6){$roc_box_array=$_POST['box_detail4'];}
			$tag_string_box.="";
			while (list ($key_box,$val_box) = @each ($roc_box_array)) {
				//echo "$val,";
				$tag_string_box .=$val_box.",";
			}
			$pack_box=substr($tag_string_box,0,(strLen($tag_string_box)-1));// remove the last , from string
			
			if($_POST['roc_pack']=='1,2,3'){
				$type_packaging=$_POST['type_packaging'];
				$pack_size=$_POST['pack_size'];
				$other_materials=$_POST['other_materials1'];
				$other_bottle=$other_bottle=$_POST['other_bottle1'];
				if($_POST['type_packaging']==5){
					$other_pack=$_POST['other_pack55'];
				}elseif($_POST['type_packaging']==1){
					$other_pack=$_POST['other_pack11'];
				}
			}
			elseif($roc_pack=='4'){$type_packaging='';$pack_size=$_POST['pack_size'];}
			else{$type_packaging='';$pack_size='';}

			if($_POST['roc_pack']==6){$bottle_lid=$_POST['bottle_lid'];$bottle_size=$_POST['bottle_size'];}
			else{$bottle_lid='';$bottle_size='';}

			if(($_POST['roc_pack']==4) || ($_POST['roc_pack']==5)){$sachet=$_POST['sachet'];}
			else{$sachet='';}

			if($_POST['roc_pack']==4){
				$other_pack=$_POST['other_pack4'];
				$other_sachet=$_POST['other_sachet4'];
				$other_materials=$_POST['other_materials4'];
				$other_bottle='';
			}elseif($_POST['roc_pack']==5){
				$other_pack=$_POST['other_pack5'];
				$other_sachet=$_POST['other_sachet5'];
				$other_materials=$_POST['other_materials5'];
				$other_bottle='';
			}elseif($_POST['roc_pack']==6){
				$other_pack=$_POST['other_pack6'];
				$other_sachet='';
				$other_materials=$_POST['other_materials6'];
				$other_bottle=$_POST['other_bottle'];
			}

			$sql_relation_pack="select * from roc_relation_pack where id_relation_pack='".$_POST['id_relation_pack']."'";
			$res_relation_pack=mysql_query($sql_relation_pack) or die ('Error '.$sql_relation_pack);
			$rs_relation_pack=mysql_fetch_array($res_relation_pack);
			$relation_pack=$rs_relation_pack['id_relation_pack'];
			if($relation_pack['id_relation_pack']){
				$sql_pack="update roc_relation_pack set id_product_appearance='".$_POST['roc_pack']."'";
				$sql_pack .=",id_type_package='".$type_packaging."',id_pack_size='".$pack_size."'";
				$sql_pack .=",id_product_foil='".$pack_foil."',id_product_bottle='".$_POST['bottle']."'";
				$sql_pack .=",id_type_bottle='".$bottle_size."',other_bottle='".$other_bottle."'";
				$sql_pack .=",id_bottle_lid='".$bottle_lid."'";
				$sql_pack .=",other_pack='".$other_pack."',id_product_sachet='".$sachet."'";
				$sql_pack .=",other_sachet='".$other_sachet."',id_material='".$pack_materials."'";
				$sql_pack .=",other_materials='".$other_materials."',box_detail='".$pack_box."'";
				$sql_pack .=" where id_relation_pack='".$_POST['id_relation_pack']."'";
				$res_pack=mysql_query($sql_pack) or die ('Error '.$sql_pack);
				$relation_pack=$_POST['id_relation_pack'];
			}
			$sql_roc="update roc set id_relation_pack='".$_POST['id_relation_pack']."'";
			$sql_roc .=",roc_status='".$_POST['roc_status']."'";
			$sql_roc .=" where id_roc='".$_POST['mode']."'";
			$res_roc=mysql_query($sql_roc) or die ('Error '.$sql_roc);
			
			if($_POST['roc_pack']==0){
			?>
				<script>
					window.alert('กรุณาระบุบรรจุภัณฑ์');
					history.back();
				</script>
			<?php
			}else{
			?>
				<script>
					window.location.href='ac-roc.php?id_u=<?=$id?>&p=<?=$pages?>';
				</script>
		<?php }
		}
		elseif($_POST['pages']==4){
			$pages=$_POST['pages'];

			$roc_type_ink_array=$_POST['type_ink_jet'];
			$tag_string_type_ink.="";
			while (list ($key_type_ink,$val_type_ink) = @each ($roc_type_ink_array)) {
				//echo "$val,";
				$tag_string_type_ink.=$val_type_ink.",";
			}
			$pack_ink=substr($tag_string_type_ink,0,(strLen($tag_string_type_ink)-1));// remove the last , from string

			/*add array materials*/
			$roc_detail_array=$_POST['ink_jet_detail'];
			$tag_string_detail.="";
			while (list ($key_detail,$val_detail) = @each ($roc_detail_array)) {
				//echo "$val,";
				$tag_string_detail.=$val_detail.",";
			}
			$pack_detail=substr($tag_string_detail,0,(strLen($tag_string_detail)-1));// remove the last , from string

			$sql="update roc set id_ink_jet='".$_POST['ink_jet']."'";
			$sql .=",id_type_ink_jet='".$pack_ink."',id_detail_ink='".$pack_detail."'";
			$sql .=",product_price='".$_POST['product_price']."'";
			$sql .=",product_compare='".$_POST['product_compare']."'";
			$sql .=",product_selling='".$_POST['product_selling']."'";
			$sql .=",market_position='".$_POST['market_position']."'";
			$sql .=",selling_channel='".$_POST['selling_channel']."'";
			$sql .=",roc_status='".$_POST['roc_status']."'";
			$sql .=" where id_roc='".$_POST['mode']."'";
			$res=mysql_query($sql) or die ('Error '.$sql);
		?>
			<script>
				window.location.href='ac-roc.php?id_u=<?=$id?>&p=<?=$pages?>';
			</script>
		<?}	
		
		$sql_pdf="select * from roc_file where id_roc='".$id."'";
		$res_pdf=mysql_query($sql_pdf) or die ('Error '.$sql_pdf);
		$rs_pdf=mysql_fetch_array($res_pdf);
		if(!$rs_pdf){
			$sql_pdf2="insert into roc_file(id_roc,roc_file)";
			$sql_pdf2 .=" value('".$id."','".$file_name."')";
			$res_pdf2=mysql_query($sql_pdf2) or die ('Error' .$sql_pdf2);
		}
	}//rev00
	else{
		$roc_rev=$_POST['roc_rev'];
		$sql="select * from roc where id_roc='".$id."'";
		$res=mysql_query($sql) or die ('Error '.$sql);
		$rs=mysql_fetch_array($res);
		if($rs['roc_rev']!=$_POST['roc_rev']){
			$sql_roc="insert into roc(roc_code,roc_month,roc_num,id_product,id_contact";
			$sql_roc .=",id_company,address,id_com_cat,date_roc,id_address,id_type_product";
			$sql_roc .=",id_group_product,other_group_product,id_roc_func";
			$sql_roc .=",roc_func_other,id_roc_rm,id_product_appearance,other_product_app";
			$sql_roc .=",id_product_value,product_value_title,id_type_product_pack";
			$sql_roc .=",id_product_weight,id_relation_value,id_relation_color,id_relation_pack";
			$sql_roc .=",id_ink_jet,id_type_ink_jet,id_detail_ink,product_price,product_compare";
			$sql_roc .=",product_selling,market_position,selling_channel,create_by,roc_status)";
			$sql_roc .="select roc_code,roc_month,roc_num,id_product,id_contact";
			$sql_roc .=",id_company,address,id_com_cat,date_roc,id_address,id_type_product";
			$sql_roc .=",id_group_product,other_group_product,id_roc_func";
			$sql_roc .=",roc_func_other,id_roc_rm,id_product_appearance,other_product_app";
			$sql_roc .=",id_product_value,product_value_title,id_type_product_pack";
			$sql_roc .=",id_product_weight,id_relation_value,id_relation_color,id_relation_pack";
			$sql_roc .=",id_ink_jet,id_type_ink_jet,id_detail_ink,product_price,product_compare";
			$sql_roc .=",product_selling,market_position,selling_channel,create_by,roc_status";
			$sql_roc .=" from roc where id_roc='".$id."'";
			$res_roc=mysql_query($sql_roc) or die ('Error '.$sql_roc);
			$id_roc=mysql_insert_id();					
			
			/*insert rm
			$sql_rm="insert into roc_rm(id_roc,roc_rm)";
			$sql_rm .="select id_roc,roc_rm";
			$sql_rm .=" from roc_rm where id_roc='".$id."'";
			$res_rm=mysql_query($sql_rm) or die ('Error '.$sql_rm);*/
			
			/*insert roc_relation_value*/
			$sql_rela_value="insert into roc_relation_value(id_roc,id_product_value,title_value)";
			$sql_rela_value .="select id_roc,id_product_value,title_value";
			$sql_rela_value .=" from roc_relation_value where id_roc='".$id."'";
			$res_rela_value=mysql_query($sql_rela_value) or die ('Error '.$sql_rela_value);
            $id_rela_value=mysql_insert_id();

			/*insert roc_relation_color*/
			$sql_rela_color="insert into roc_relation_color(id_roc,id_type_product_color,title_color)";
			$sql_rela_color .="select id_roc,id_type_product_color,title_color";
			$sql_rela_color.=" from roc_relation_color where id_roc='".$id."'";
			$res_rela_color=mysql_query($sql_rela_color) or die ('Error '.$sql_rela_color);
			$id_rela_color=mysql_insert_id();

			/*insert roc_relation_pack*/
			$sql_pack="insert into roc_relation_pack(id_roc,id_product_appearance,id_type_package";
			$sql_pack .=",id_pack_size,id_product_foil,id_product_bottle,id_type_bottle,other_bottle";
			$sql_pack .=",id_bottle_lid,other_pack,id_product_sachet,other_sachet,id_material";
			$sql_pack .=",other_materials,box_detail)";
			$sql_pack .="select id_roc,id_product_appearance,id_type_package";
			$sql_pack .=",id_pack_size,id_product_foil,id_product_bottle,id_type_bottle,other_bottle";
			$sql_pack .=",id_bottle_lid,other_pack,id_product_sachet,other_sachet,id_material";
			$sql_pack .=",other_materials,box_detail from roc_relation_pack";
			$sql_pack .=" where id_roc='".$id."'";
			$res_pack=mysql_query($sql_pack) or die ('Error '.$sql_pack);
			$id_pack=mysql_insert_id();

			$up_roc="update roc set id_relation_value='".$id_rela_value."'";
			$up_roc .=",id_relation_color='".$id_rela_color."'";
			$up_roc .=",id_relation_pack='".$id_pack."'";
			$up_roc .=",roc_status='".$_POST['roc_status']."'";
			$up_roc .=",roc_rev='".$roc_rev."'";
			$up_roc .=",create_by='".$_POST['account']."'";
			$up_roc .=",create_date='".$date."'";
			$up_roc .=" where id_roc='".$id_roc."'";
			$res_up_roc=mysql_query($up_roc) or die ('Error '.$up_roc);

			
			/*$up_rela_value="update roc_relation_value set id_roc='".$id_roc."'";
			$up_rela_value .=" where id_relation_value='".$id_rela_value."'";
			$res_rela_value=mysql_query($up_rela_value) or die ('Error '.$up_rela_value);	*/

			$pages=$pages+1;			

			?>
			<script>
				window.location.href='ac-roc.php?id_u=<?=$id_roc?>&p=<?=$pages?>&rev=<?=$roc_rev?>';
			</script>
	<?php }//new rev
		else{
			$roc_rev=$_POST['roc_rev'];
			/*add roc rm*/
			if($_POST["hdnCmd"] == "add_rm"){
				$sql="insert into roc_rm(id_roc,roc_rm,roc_rev)";
				$sql .=" values('".$_POST['id_roc2']."','".$_POST['roc_rm']."','".$roc_rev."')";
				$rs=mysql_query($sql) or die ('Error '.$sql);
				$pages2=$_POST['pages'];
			?>	
				<script>
					window.location.href='ac-roc.php?id_u=<?=$id?>&p=2&rev=<?=$roc_rev?>';
				</script>
			<?php }
			/*update roc rm*/
			if($_POST["hdnCmd"] == "update_rm"){
				$sql = "update roc_rm set roc_rm='".$_POST['roc_rm2']."'";
				$sql .=" where id_roc_rm = '".$_POST["hdnEdit"]."' and roc_rev='".$roc_rev."' ";
				$res = mysql_query($sql) or die ('Error '.$sql);
			?>	
				<script>
					window.location.href='ac-roc.php?id_u=<?=$id?>&p=2&rev=<?=$roc_rev?>';
				</script>
			<?php }

			/*delete roc rm*/
			if($_GET["action"] == "del_rm"){
				$sql = "delete from roc_rm ";
				$sql .="where id_roc_rm = '".$_GET["id_p"]."' and roc_rev='".$roc_rev."' ";
				$res = mysql_query($sql) or die ('Error '.$sql);
			?>	
				<script>
					window.location.href='ac-roc.php?id_u=<?=$id?>&p=2&rev=<?=$roc_rev?>';
				</script>
			<?php }

			if($_POST["hdnCmd"] == "sign"){
				if($_POST['code_sign']==''){
				?>
					<script>
						window.alert('Please insert password signature');
						history.back();
					</script>
				<?php }else{
					$sql_roc="select * from roc where id_roc='".$id."'";
					$res_roc=mysql_query($sql_roc) or die ('Error '.$sql_roc);
					$rs_roc=mysql_fetch_array($res_roc);

					$sql_sign="select * from signature where id_account='".$rs_roc['create_by']."'";
					$res_sign=mysql_query($sql_sign) or die ('Error '.$sql_sign);
					$rs_sign=mysql_fetch_array($res_sign);
					if($_POST['code_sign']==$rs_sign['password_sign']){
						$sql="update roc set roc_sign='1'";
						$sql .=" where id_roc='".$id."'";
						$res=mysql_query($sql) or die ('Error '.$sql);			
					}else{
					?>
						<script>
							window.alert('Password incorret!!!');
							history.back();
						</script>
					<?php }?>
					<script>
						window.location.href='ac-roc.php?id_u=<?=$id?>&p=<?=$pages?>&rev=<?=$roc_rev?>';
					</script>
			<?php }
			}

			if($_POST['pages']==1){
				$pages=$_POST['pages']+1;
				/*select company*/
				$sql_company="select * from company where company_name like '".$_POST['company_name']."'";
				$res_company=mysql_query($sql_company) or die ('Error '.$sql_company);
				$rs_company=mysql_fetch_array($res_company);
				if(!$rs_company){

					$sql_address="insert into company_address(address_no) values";
					$sql_address .=" ('')";
					$res_address=mysql_query($sql_address) or die('Error '.$sql_contact);
					$id_address=mysql_insert_id();

					$sql_ins_com="insert into company(company_name,id_address,id_com_cat,company_tel";
					$sql_ins_com .=",company_fax) values('".$_POST['company_name']."','".$id_address."'";
					$sql_ins_com .=",'".$_POST['com_cate']."','".$_POST['company_tel']."'";
					$sql_ins_com .=",'".$_POST['company_fax']."')";
					$res_ins_com=mysql_query($sql_ins_com) or die ('Error '.$sql_ins_com);
					$id_company=mysql_insert_id();
								
					$sql_contact="insert into company_contact(id_company,contact_name,mobile,email)";
					$sql_contact .=" values('".$id_company."','".$_POST['com_contact']."'";
					$sql_contact .=",'".$_POST['mobile']."','".$_POST['email']."')";
					$res_contact=mysql_query($sql_contact) or die('Error '.$sql_contact);
					$id_contact=mysql_insert_id();
				}else{ 
					$id_company=$rs_company['id_company'];
					$id_address2=$_POST['id_address'];
					$sql_contact="select * from company_contact where contact_name like '".$_POST['com_contact']."'";
					$sql_contact .=" and id_company='".$id_company."'";
					$res_contact=mysql_query($sql_contact) or die ('Error '.$sql_contact);
					$rs_contact=mysql_fetch_array($res_contact);
					if($rs_contact){
						//$sql_contact2="update company_contact where contact_name='".$_POST['company_contact2']."'";
						//echo$sql_contact2 .=" where id_contact='".$id_company."'";
						//$res_contact2=mysql_query($sql_contact2) or die('Error '.$sql_contact2);
						//$id_contact=mysql_insert_id();
						$id_contact=$rs_contact['id_contact'];
					}
					else{
						$sql_contact2="insert into company_contact(id_company,contact_name,mobile,email)";
						$sql_contact2 .=" values('".$id_company."','".$_POST['com_contact']."'";
						$sql_contact2 .=",'".$_POST['mobile']."','".$_POST['email']."')";
						$res_contact2=mysql_query($sql_contact2) or die('Error '.$sql_contact2);
						$id_contact=mysql_insert_id();
					}
				}	

				/*product*/
				$sql_product="select * from product where product_name like '".$_POST['product_name']."'";
				$res_product=mysql_query($sql_product) or die ('Error '.$sql_product);
				$rs_product=mysql_fetch_array($res_product);
				if(!$rs_product){
					$sql_ins_pro="insert into product(id_company,product_name) values";
					$sql_ins_pro .="('".$id_company."','".$_POST['product_name']."')";
					$res_ins_pro=mysql_query($sql_ins_pro) or die ('Error '.$sql_ins_pro);
					$id_product=mysql_insert_id();
				}else{
					$id_product=$_POST['id_product'];
				}

				/*add array roc function
				if($_POST['roc_group_product']==1){$roc_function_array=$_POST['roc_function1'];}
				elseif($_POST['roc_group_product']==2){$roc_function_array=$_POST['roc_function2'];}
				elseif($_POST['roc_group_product']==3){$roc_function_array=$_POST['roc_function3'];}
				elseif($_POST['roc_group_product']==4){$roc_function_array=$_POST['roc_function4'];}
				*/

				$roc_function_array=$_POST['roc_function'];
				$tag_string="";
				while (list ($key,$val) = @each ($roc_function_array)) {
				//echo "$val,";
				$tag_string.=$val.",";
				}
				$roc_function=substr($tag_string,0,(strLen($tag_string)-1));// remove the last , from string
				
				
				$roc_function_other_array=$_POST['roc_function_other'];
				$tag_string_other="";
				while (list ($key_other,$val_other) = @each ($roc_function_other_array)) {
				//echo "$val,";
				$tag_string_other.=$val_other.",";
				}
				$roc_function_other=substr($tag_string_other,0,(strLen($tag_string_other)-1));// remove the last , from string*/
				

				/*add array roc group functin*/
				$roc_group_product_array=$_POST['roc_group_product'];
				$tag_group_product_string="";
				while (list ($key_group_product,$val_group_product) = @each ($roc_group_product_array)) {
				//echo "$val,";
				$tag_group_product_string.=$val_group_product.",";
				}
				$roc_group_product2=substr($tag_group_product_string,0,(strLen($tag_group_product_string)-1));// remove the last , from string
				
				/*add array roc product value*/
				$roc_product_value_array=$_POST['roc_product_value'];
				$tag_string="";
				while (list ($key,$val) = @each ($roc_product_value_array)) {
				//echo "$val,";
				$tag_string.=$val.",";
				}
				$roc_product_value=substr($tag_string,0,(strLen($tag_string)-1));// remove the last , from string

				if($_POST['roc_group_product']==-1){$roc_group_product= '-1';}
				else{$roc_group_product=$_POST['roc_group_product'];}

				if($_POST['id_address'] != 0){$id_address=$_POST['id_address'];}
				else{$id_address=0;}

				$sql_up_company="update company set company_tel='".$_POST['company_tel']."'";
				$sql_up_company .=",company_fax='".$_POST['company_fax']."'";
				$sql_up_company .=" where id_company='".$_POST['id_company']."'";
				$res_up_company=mysql_query($sql_up_company) or die ('Error '.$sql_up_company);

				$sql_up_contact="update company_contact set mobile='".$_POST['mobile']."'";
				$sql_up_contact .=",email='".$_POST['company_email']."'";
				$sql_up_contact .=" where id_contact='".$_POST['id_contact']."'";
				$res_up_contact=mysql_query($sql_up_contact) or die ('Error '.$sql_up_contact);

				$sql_up_roc="update roc set id_product='".$id_product."'";
				$sql_up_roc .=",id_contact='".$_POST['id_contact']."'";
				$sql_up_roc .=",id_company='".$id_company."'";
				$sql_up_roc .=",address='".$_POST['company_address']."'";
				$sql_up_roc .=",id_com_cat='".$_POST['com_cate']."'";
				$sql_up_roc .=",other_category='".$_POST['other_category']."'";
				$sql_up_roc .=",date_roc='".$_POST['date_roc']."'";
				$sql_up_roc .=",id_address='".$_POST['id_address']."'";
				$sql_up_roc .=",address='".$_POST['company_address']."'";
				$sql_up_roc .=",id_type_product='".$_POST['type_product']."'";
				$sql_up_roc .=",id_group_product='".$roc_group_product2."'";
				$sql_up_roc .=",other_group_product='".$_POST['other_group_product']."'";
				$sql_up_roc .=",id_roc_func='".$roc_function."'";
				$sql_up_roc .=",roc_func_other='".$roc_function_other."'";
				$sql_up_roc .=",roc_status='".$_POST['roc_status']."'";
				$sql_up_roc .=" where id_roc='".$_POST['mode']."'";
				$res_roc=mysql_query($sql_up_roc) or die ('Error '.$sql_up_roc);
			?>
				<script>
					window.location.href='ac-roc.php?id_u=<?=$id?>&p=<?=$pages?>&rev=<?=$roc_rev?>';
				</script>
			<?php
			}
			elseif($_POST['pages']==2){	
				$id_roc=$_POST['mode'];
				$pages=$_POST['pages']+1;
					
				$product_app=$_POST['id_product_appearance'];
				if($product_app != 7){$product_weight=$_POST['roc_product_weight'];}
				else{$product_weight='0';}

				/*add array roc product value*/
				$roc_product_value_array=$_POST['roc_product_value'];
				$tag_string_value.="";
				while (list ($key_value,$val_value) = @each ($roc_product_value_array)) {
					//echo "$val,";
					$tag_string_value.=$val_value.",";
				}
				$product_value=substr($tag_string_value,0,(strLen($tag_string_value)-1));// remove the last , from string
				
				if(($product_app==5) || ($product_app==6) || ($product_app==7)){
					if($product_app==5){$roc_product_value_o_array=$_POST['roc_product_value_other'];}
					elseif($product_app==6){$roc_product_value_o_array=$_POST['roc_product_value_other2'];}
					elseif($product_app==7){$roc_product_value_o_array=$_POST['roc_product_value_other3'];}
					$tag_string_value_o.="";
					while (list ($key_value_o,$val_value_o) = @each ($roc_product_value_o_array)) {
						//echo "$val,";
						$tag_string_value_o.=$val_value_o.",";
					}
					$product_value_o=substr($tag_string_value_o,0,(strLen($tag_string_value_o)-1));// remove the last , from string

					if($_POST['relation_value'] == null){
						$sql_rela_color="insert into roc_relation_value(id_roc,id_product_value,title_value)";
						$sql_rela_color .=" values('".$id_roc."','".$product_value."','".$product_value_o."')";
						$res_rela_color=mysql_query($sql_rela_color) or die ('Error '.$sql_rela_color);	
						$id_relation_value=mysql_insert_id();
					}else{
						$sql_rela_color="update roc_relation_value set id_product_value='".$product_value."'";
						$sql_rela_color .=",title_value='".$product_value_o."'";
						$sql_rela_color .=" where id_relation_value='".$_POST['relation_value']."'";
						$res_rela_color=mysql_query($sql_rela_color) or die ('Error '.$sql_rela_color);
						$id_relation_value=$_POST['relation_value'];
					}
				}

				/*add array roc product color*/
				if($product_app==1){
					$roc_product_color_array=$_POST['type_product_color1'];
					$roc_product_color_title_array=$_POST['type_product_c_other1'];
				}
				elseif($product_app==2){
					$roc_product_color_array=$_POST['type_product_color2'];
					$roc_product_color_title_array=$_POST['type_product_c_other2'];
				}
				elseif($product_app==3){
					$roc_product_color_array=$_POST['type_product_color3'];
					$roc_product_color_title_array=$_POST['type_product_c_other3'];
				}
				elseif($product_app==4){
					$roc_product_color_array=$_POST['type_product_color4'];
					$roc_product_color_title_array=$_POST['type_product_c_other4'];
				}
				
				$tag_string_color.="";
				while (list ($key_color,$val_color) = @each ($roc_product_color_array)) {
					//echo "$val,";
					$tag_string_color.=$val_color.",";
				}
				$product_color=substr($tag_string_color,0,(strLen($tag_string_color)-1));// remove the last , from string
				
				$tag_string_color_title.="";
				while (list ($key_color_title,$val_color_title) = @each ($roc_product_color_title_array)) {
					//echo "$val,";
					$tag_string_color_title.=$val_color_title.",";
				}
				$product_color_title=substr($tag_string_color_title,0,(strLen($tag_string_color_title)-1));// remove the last , from string

				/*add array roc product weight*/
				if($product_app==1){
					$product_weight_array=$_POST['roc_product_weight_other1'];
				}
				elseif($product_app==2){
					$product_weight_array=$_POST['roc_product_weight_other2'];
				}
				elseif($product_app==3){
					$product_weight_array=$_POST['roc_product_weight_other3'];
				}
				elseif($product_app==4){
					$product_weight_array=$_POST['roc_product_weight_other4'];
				}
				elseif($product_app==5){
					$product_weight_array=$_POST['roc_product_weight_other5'];
				}
				elseif($product_app==6){
					$product_weight_array=$_POST['roc_product_weight_other6'];
				}
				$tag_string_weight.="";
				while (list ($key_weight,$val_weight) = @each ($product_weight_array)) {
					//echo "$val,";
					$tag_string_weight.=$val_weight.",";
				}
				$other_product_weight=substr($tag_string_weight,0,(strLen($tag_string_weight)-1));// remove the last , from string

				if(($product_app==1) || ($product_app==2) || ($product_app==3) || ($product_app==4)){
					if($_POST['relation_color'] == null){
						$sql_rela_color="insert into roc_relation_color(id_roc,id_type_product_color,title_color)";
						$sql_rela_color .=" values('".$id_roc."','".$product_color."','".$product_color_title."')";
						$res_rela_color=mysql_query($sql_rela_color) or die ('Error '.$sql_rela_color);	
						$id_relation=mysql_insert_id();
					}else{
						$sql_rela_color="update roc_relation_color set id_type_product_color='".$product_color."'";
						$sql_rela_color .=",title_color='".$product_color_title."'";
						$sql_rela_color .=" where id_relation_color='".$_POST['relation_color']."'";
						$res_rela_color=mysql_query($sql_rela_color) or die ('Error '.$sql_rela_color);
						$id_relation=$_POST['relation_color'];
					}
				}
				if(($product_app==1) || ($product_app==2) || ($product_app==3) || ($product_app==7)){$type_product_pack=$_POST['type_product_pack'];}
				else{$type_product_pack=0;}

				if($product_app==4){
					$roc_product_value_other=$_POST['roc_product_value_instant'];
				}else{$roc_product_value_other='';}
				
				$sql_rela_pack="select * from roc_relation_pack";
				$sql_rela_pack .=" where id_relation_pack='".$_POST['id_relation_pack']."'";
				$res_rela_pack=mysql_query($sql_rela_pack) or die ('Error '.$sql_rela_pack);
				$rs_rela_pack=mysql_fetch_array($res_rela_pack);
				if($rs_rela_pack['id_relation_pack']==$_POST['id_relation_pack']){
					$id_relation_pack=$_POST['id_relation_pack'];
				}else{
					$sql_relation_pack="insert into roc_relation_pack(id_roc)";
					$sql_relation_pack .=" values('".$_POST['mode']."')";
					$res_relation_pack=mysql_query($sql_relation_pack) or die ('Error '.$sql_relation_pack);
					$id_relation_pack=mysql_insert_id();
				}

				$sql_up_roc="update roc set id_product_appearance='".$product_app."'";
				$sql_up_roc .=",other_product_app='".$_POST['other_product_app']."'";
				$sql_up_roc .=",id_product_value='".$product_value."'";
				$sql_up_roc .=",product_value_title='".$roc_product_value_other."'";
				$sql_up_roc .=",id_product_weight='".$product_weight."'";
				$sql_up_roc .=",other_product_weight='".$other_product_weight."'";
				$sql_up_roc .=",id_type_product_pack='".$type_product_pack."'";
				$sql_up_roc .=",id_relation_value='".$id_relation_value."'";
				$sql_up_roc .=",id_relation_color='".$id_relation."'";
				$sql_up_roc .=",id_relation_pack='".$id_relation_pack."'";
				$sql_up_roc .=" where id_roc='".$_POST['mode']."'";
				$res_roc=mysql_query($sql_up_roc) or die ('Error '.$sql_up_roc);

				if($product_app==0){
				?>
					<script>
						window.alert('กรุณาระบุลักษณะรูปแบบผลิตภัณฑ์');
						history.back();
					</script>
				<?php
				}else{
				?>				
					<script>
						window.location.href='ac-roc.php?id_u=<?=$id?>&p=<?=$pages?>&rev=<?=$roc_rev?>';
					</script>
			<?php }
			}
			elseif($_POST['pages']==3){	
				$id_roc=$_POST['mode'];
				$pages=$_POST['pages']+1;

				$product_weight=$_POST['roc_product_weight'];
				$roc_pack=$_POST['roc_pack'];
						
				/*add array foil*/
				if($roc_pack=='1,2,3'){$roc_foil_array=$_POST['foil1'];}
				elseif($roc_pack==4){$roc_foil_array=$_POST['foil4'];}
				$tag_string_foil.="";
				while (list ($key_foil,$val_foil) = @each ($roc_foil_array)) {
					//echo "$val,";
					$tag_string_foil.=$val_foil.",";
				}
				$pack_foil=substr($tag_string_foil,0,(strLen($tag_string_foil)-1));// remove the last , from string

				/*add array materials*/
				if($roc_pack=='1,2,3'){$roc_materials_array=$_POST['materials1'];}
				elseif($roc_pack==4){$roc_materials_array=$_POST['materials4'];}
				elseif($roc_pack==5){$roc_materials_array=$_POST['materials5'];}
				elseif($roc_pack==6){$roc_materials_array=$_POST['materials6'];}
				$tag_string_materials.="";
				while (list ($key_materials,$val_materials) = @each ($roc_materials_array)) {
					//echo "$val,";
					$tag_string_materials.=$val_materials.",";
				}
				$pack_materials=substr($tag_string_materials,0,(strLen($tag_string_materials)-1));// remove the last , from string
				
				/*box detail*/
				if($roc_pack=='1,2,3'){
					$roc_box_array=$_POST['box_detail'];}
				elseif($roc_pack=4){$roc_box_array=$_POST['box_detail2'];}
				elseif($roc_pack==5){$roc_box_array=$_POST['box_detail3'];}
				elseif($roc_pack==6){$roc_box_array=$_POST['box_detail4'];}
				$tag_string_box.="";
				while (list ($key_box,$val_box) = @each ($roc_box_array)) {
					//echo "$val,";
					$tag_string_box .=$val_box.",";
				}
				$pack_box=substr($tag_string_box,0,(strLen($tag_string_box)-1));// remove the last , from string
				
				if($_POST['roc_pack']=='1,2,3'){
					$type_packaging=$_POST['type_packaging'];
					$pack_size=$_POST['pack_size'];
					$other_materials=$_POST['other_materials1'];
					$other_bottle=$other_bottle=$_POST['other_bottle1'];
					if($_POST['type_packaging']==5){
						$other_pack=$_POST['other_pack55'];
					}elseif($_POST['type_packaging']==1){
						$other_pack=$_POST['other_pack11'];
					}
				}
				elseif($roc_pack=='4'){$type_packaging='';$pack_size=$_POST['pack_size'];}
				else{$type_packaging='';$pack_size='';}

				if($_POST['roc_pack']==6){$bottle_lid=$_POST['bottle_lid'];$bottle_size=$_POST['bottle_size'];}
				else{$bottle_lid='';$bottle_size='';}

				if(($_POST['roc_pack']==4) || ($_POST['roc_pack']==5)){$sachet=$_POST['sachet'];}
				else{$sachet='';}

				if($_POST['roc_pack']==4){
					$other_pack=$_POST['other_pack4'];
					$other_sachet=$_POST['other_sachet4'];
					$other_materials=$_POST['other_materials4'];
					$other_bottle='';
				}elseif($_POST['roc_pack']==5){
					$other_pack=$_POST['other_pack5'];
					$other_sachet=$_POST['other_sachet5'];
					$other_materials=$_POST['other_materials5'];
					$other_bottle='';
				}elseif($_POST['roc_pack']==6){
					$other_pack=$_POST['other_pack6'];
					$other_sachet='';
					$other_materials=$_POST['other_materials6'];
					$other_bottle=$_POST['other_bottle'];
				}

				$sql_relation_pack="select * from roc_relation_pack where id_relation_pack='".$_POST['id_relation_pack']."'";
				$res_relation_pack=mysql_query($sql_relation_pack) or die ('Error '.$sql_relation_pack);
				$rs_relation_pack=mysql_fetch_array($res_relation_pack);
				$relation_pack=$rs_relation_pack['id_relation_pack'];
				if($relation_pack['id_relation_pack']){
					$sql_pack="update roc_relation_pack set id_product_appearance='".$_POST['roc_pack']."'";
					$sql_pack .=",id_type_package='".$type_packaging."',id_pack_size='".$pack_size."'";
					$sql_pack .=",id_product_foil='".$pack_foil."',id_product_bottle='".$_POST['bottle']."'";
					$sql_pack .=",id_type_bottle='".$bottle_size."',other_bottle='".$other_bottle."'";
					$sql_pack .=",id_bottle_lid='".$bottle_lid."'";
					$sql_pack .=",other_pack='".$other_pack."',id_product_sachet='".$sachet."'";
					$sql_pack .=",other_sachet='".$other_sachet."',id_material='".$pack_materials."'";
					$sql_pack .=",other_materials='".$other_materials."',box_detail='".$pack_box."'";
					$sql_pack .=" where id_relation_pack='".$_POST['id_relation_pack']."'";
					$res_pack=mysql_query($sql_pack) or die ('Error '.$sql_pack);
					$relation_pack=$_POST['id_relation_pack'];
				}
				$sql_roc="update roc set id_relation_pack='".$_POST['id_relation_pack']."'";
				$sql_roc .=",roc_status='".$_POST['roc_status']."'";
				$sql_roc .=" where id_roc='".$_POST['mode']."'";
				$res_roc=mysql_query($sql_roc) or die ('Error '.$sql_roc);

				if($_POST['roc_pack']==0){
				?>
					<script>
						window.alert('กรุณาระบุบรรจุภัณฑ์');
						history.back();
					</script>
				<?php
				}else{
				?>
					<script>
						window.location.href='ac-roc.php?id_u=<?=$id?>&p=<?=$pages?>&rev=<?=$roc_rev?>';
					</script>
			<?php }
			}
			elseif($_POST['pages']==4){
				$pages=$_POST['pages'];

				$roc_type_ink_array=$_POST['type_ink_jet'];
				$tag_string_type_ink.="";
				while (list ($key_type_ink,$val_type_ink) = @each ($roc_type_ink_array)) {
					//echo "$val,";
					$tag_string_type_ink.=$val_type_ink.",";
				}
				$pack_ink=substr($tag_string_type_ink,0,(strLen($tag_string_type_ink)-1));// remove the last , from string

				/*add array materials*/
				$roc_detail_array=$_POST['ink_jet_detail'];
				$tag_string_detail.="";
				while (list ($key_detail,$val_detail) = @each ($roc_detail_array)) {
					//echo "$val,";
					$tag_string_detail.=$val_detail.",";
				}
				$pack_detail=substr($tag_string_detail,0,(strLen($tag_string_detail)-1));// remove the last , from string

				$sql="update roc set id_ink_jet='".$_POST['ink_jet']."'";
				$sql .=",id_type_ink_jet='".$pack_ink."',id_detail_ink='".$pack_detail."'";
				$sql .=",product_price='".$_POST['product_price']."'";
				$sql .=",product_compare='".$_POST['product_compare']."'";
				$sql .=",product_selling='".$_POST['product_selling']."'";
				$sql .=",market_position='".$_POST['market_position']."'";
				$sql .=",selling_channel='".$_POST['selling_channel']."'";
				$sql .=",roc_status='".$_POST['roc_status']."'";
				$sql .=" where id_roc='".$_POST['mode']."'";
				$res=mysql_query($sql) or die ('Error '.$sql);
			?>
				<script>
					window.location.href='ac-roc.php?id_u=<?=$id?>&p=<?=$pages?>&rev=<?=$roc_rev?>';
				</script>
			<?}	
			
			$sql_pdf="select * from roc_file where id_roc='".$id."'";
			$res_pdf=mysql_query($sql_pdf) or die ('Error '.$sql_pdf);
			$rs_pdf=mysql_fetch_array($res_pdf);
			if(!$rs_pdf){
				$sql_pdf2="insert into roc_file(id_roc,roc_file)";
				$sql_pdf2 .=" value('".$id."','".$file_name."')";
				$res_pdf2=mysql_query($sql_pdf2) or die ('Error' .$sql_pdf2);
			}
		}
	}
?>
	
<?php	

}
?>
</body>
</html>