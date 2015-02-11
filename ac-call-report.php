<?php
ob_start();
session_start();
if($_SESSION["Username"] == ""){
	header("location:index.php");
	exit();
}
$_SESSION['start'] = time(); // taking now logged in time
if(!isset($_SESSION['expire'])){
	$_SESSION['expire'] = $_SESSION['start'] + 3600 ; // ending a session in 30 seconds
}
$now = time(); // checking the time now when home page starts

if($now > $_SESSION['expire']){
	session_destroy();
	//echo "Your session has expire !  <a href='logout.php'>Click Here to Login</a>";
}else{
	//echo "This should be expired in 1 min <a href='logout.php'>Click Here to Login</a>";
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
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/foundation.css">
<link rel="stylesheet" href="rmm-css/responsivemobilemenu.css" type="text/css"/>
<script type="text/javascript" src="http://code.jquery.com/jquery.min.js"></script>
<script type="text/javascript" src="rmm-js/responsivemobilemenu.js"></script>
<script src="js/vendor/custom.modernizr.js"></script>
<script type="text/javascript" src="ckeditor-integrated/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="ckeditor-integrated/ckfinder/ckfinder.js"></script>
<script language="javascript">
function fncSubmit(){
	/*if(document.frm.company_name.value == "")
	{
		alert('Please input company');
		document.frm.company_name.focus();
		return false;
	}	
	else
	if(document.frm.title_call_report.value == "")
	{
		alert('Please input title call report');
		document.frm.title_call_report.focus();
		return false;
	}	
	else
	if(document.frm.product_name.value == "")
	{
		alert('Please input product');
		document.frm.product_name.focus();
		return false;
	}*/
	document.frm.submit();
}
</script>
<script type="text/javascript" src="js/js-autocomplete/js/jquery-1.4.2.min.js"></script> 
<script type="text/javascript" src="js/js-autocomplete/js/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript"> 
	jQuery(document).ready(function(){
		$('.company_name').autocomplete({
			source:'return-call-report.php', 
			//minLength:2,
			select:function(evt, ui){
				this.form.id_company.value = ui.item.id_company;
				//this.form.id_product.value = ui.item.id_product;
				//this.form.product_name.value = ui.item.product_name;
			}
		});
		$('.product_name').autocomplete({
			source:'return-product.php', 
			//minLength:2,
			select:function(evt, ui){
				this.form.id_product.value = ui.item.id_product;
				this.form.product_name.value = ui.item.product_name;
			}
		});
		$('.contact_name').autocomplete({
			source:'return-contact2.php', 
			//minLength:2,
			select:function(evt, ui){
				this.form.id_contact.value = ui.item.id_contact;
				this.form.department.value = ui.item.department;
				this.form.telephone.value = ui.item.telephone;
				this.form.email.value = ui.item.email;
			}
		});
	});
</script> 
<link rel="stylesheet" href="js/js-autocomplete/css/smoothness/jquery-ui-1.8.2.custom.css" />
<!-- Plugin files below -->
<link rel="stylesheet" type="text/css" href="js/time/src/jquery.ptTimeSelect.css" />
<script type="text/javascript" src="js/time/src/jquery.ptTimeSelect.js"></script>

</head>
<body>
	<?php 
		if($_REQUEST["date_visited"]){
			$title=$_GET['date_visited'].'/'.$_GET['month_visited'].'/'.$_GET['year_visited'];
			$mode='New';
			$button='Save';
			$id='New';
			$create_by=$rs_account['id_account'];
			$date=$_GET['date_visited'];
			$month=$_GET['month_visited'];
			$year=$_GET['year_visited'];
		}
		else
		if($_REQUEST['id_p']){
			$id=$_GET["id_p"];
			$sql="select * from call_report where id_call_report='".$id."'";
			$res=mysql_query($sql) or die ('Error '.$sql);
			$rs=mysql_fetch_array($res);

			$sql_date="select * from call_report_date where id_call_report='".$rs['id_call_report']."'";
			$res_date=mysql_query($sql_date) or die ('Error '.$sql_date);
			$rs_date=mysql_fetch_array($res_date);
			$title=$rs_date['date_visited'].'/'.$rs_date['month_visited'].'/'.$rs_date['year_visited'];

			$mode='Edit '.$rs['title_call_report'];
			$button='Update';
			$create_by=$rs['create_by'];
			$date=$_GET['date'];
			$month=$_GET['month'];
			$year=$_GET['year'];
		}

		if($_POST['hdnCmd']=='save_data'){		
			
			/*$sql_company="select * from company where company_name like '".$_POST['company_name']."'";
			$res_company=mysql_query($sql_company) or die ('Error '.$sql_company);
			$rs_company=mysql_fetch_array($res_company);
			if(!$rs_company){
				$sql_ins_com="insert into company(company_name) values";
				$sql_ins_com .=" ('".$_POST['company_name']."')";
				$res_ins_com=mysql_query($sql_ins_com) or die ('Error '.$sql_ins_com);
				$id_company=mysql_insert_id();
			}else{*/
				$id_company=$_POST['id_company'];
		/*	}*/

		/*	$sql_product="select * from product where product_name like '".$_POST['product_name']."'";
			$res_product=mysql_query($sql_product) or die ('Error '.$sql_product);
			$rs_product=mysql_fetch_array($res_product);
			if(!$rs_product){
				$sql_ins_product="insert into product(id_company,product_name) values";
				$sql_ins_product .=" ('".$id_company."','".$_POST['product_name']."')";
				$res_ins_product=mysql_query($sql_ins_product) or die ('Error '.$sql_ins_product);
				$id_product=mysql_insert_id();
			}else{
				$id_product=$rs_product['id_product'];
			}*/
			$date=date("Y-m-d");

			if($_POST['roc_group_product']==-1){$roc_group_product= '-1';}
			else{$roc_group_product=$_POST['roc_group_product'];}

			/*add array roc group functin*/
			$roc_group_product_array=$_POST['roc_group_product'];
			$tag_group_product_string="";
			while (list ($key_group_product,$val_group_product) = @each ($roc_group_product_array)) {
			//echo "$val,";
			$tag_group_product_string.=$val_group_product.",";
			}
			$roc_group_product2=substr($tag_group_product_string,0,(strLen($tag_group_product_string)-1));// remove the last , from string
			
			$roc_function_array=$_POST['roc_function'];
			$tag_string="";
			while (list ($key,$val) = @each ($roc_function_array)) {
			//echo "$val,";
			$tag_string.=$val.",";
			}
			$roc_function=substr($tag_string,0,(strLen($tag_string)-1));// remove the last , from string

			if($_POST['roc_group_product']==1){$roc_function_other=$_POST['roc_function_other1'];}
			elseif($_POST['roc_group_product']==2){$roc_function_other=$_POST['roc_function_other2'];}
			elseif($_POST['roc_group_product']==3){$roc_function_other=$_POST['roc_function_other3'];}
			elseif($_POST['roc_group_product']==4){$roc_function_other=$_POST['roc_function_other4'];}

			$roc_function_other_array=$_POST['roc_function_other'];
			$tag_string_other="";
			while (list ($key_other,$val_other) = @each ($roc_function_other_array)) {
				//echo "$val,";
				$tag_string_other.=$val_other.",";
			}
			$roc_function_other=substr($tag_string_other,0,(strLen($tag_string_other)-1));// remove the last , from string*/

			/*status office*/
			$status_office_array=$_POST['status_office'];
			$tag_string_other="";
			while (list ($key_status_office,$val_status_office) = @each ($status_office_array)) {
				//echo "$val,";
				$tag_status_office.=$val_status_office.",";
			}
			$status_office=substr($tag_status_office,0,(strLen($tag_status_office)-1));// remove the last , from string*/
			
			// ZAMACHITA meeting
			$post_company_name = mysql_real_escape_string($_POST['company_name']);
			$post_contact_name = mysql_real_escape_string($_POST['contact_name']);
			$post_title_call_report = mysql_real_escape_string($_POST['title_call_report']);

			$sql="insert into call_report(id_company,company_name,type_office,status_office";
			$sql .=",type_customer,id_group_product,other_group_product";
			$sql .=",id_roc_func,roc_func_other,title_call_report,id_product";
			$sql .=",contact_name,sample_cost,id_type_package,sample_baht,other_cost";
			$sql .=",description,workplan_desc,create_by,create_date)";
			$sql .=" values('".$id_company."','".$post_company_name."'";
			$sql .=",'".$_POST['type_office']."','".$status_office."'";
			$sql .=",'".$_POST['type_customer']."','".$roc_group_product2."'";
			$sql .=",'".$_POST['other_group_product']."','".$roc_function."'";
			$sql .=",'".$roc_function_other."','".$post_title_call_report."'";
			$sql .=",'".$id_product."','".$post_contact_name."'";
			$sql .=",'".$_POST['sample_cost']."','".$_POST['package']."'";
			$sql .=",'".$_POST['sample_baht']."','".$_POST['other_cost']."'";
			$sql .=",'".$_POST['description']."','".$_POST['description2']."'";
			$sql .=",'".$rs_account['id_account']."','".$date."')";
			$res=mysql_query($sql) or die ('Error '.$sql);
			$id_call_report=mysql_insert_id();
			
			list($day, $month, $year) = split('[/.-]', $_POST['visited_date']); 

			if($_POST['next_call']=='n'){$year_next='';$day_next='';$month_next='';}
			else{
				list($day_next, $month_next, $year_next) = split('[/.-]', $_POST['date_next_call']); 
			}
			
			
			$sql_date="insert into call_report_date(id_call_report,date_visited,month_visited";
			$sql_date .=",year_visited,start_time_visited,end_time_visited,next_call";
			$sql_date .=",date_next_call,month_next_call,year_next_call,start_time_next";
			$sql_date .=",end_time_next)";
			$sql_date .=" values('".$id_call_report."','".$month."'";
			$sql_date .=",'".$day."','".$year."','".$_POST['start_time1']."'";
			$sql_date .=",'".$_POST['end_time1']."','".$_POST['next_call']."'";
			$sql_date .=",'".$month_next."','".$day_next."','".$year_next."'";
			$sql_date .=",'".$_POST['start_time2']."','".$_POST['end_time2']."')";
			$res_date=mysql_query($sql_date) or die ('Error '.$sql_date);
			$id=$id_call_report;
			$create_by=$rs_account['id_account'];
			?>
			<script>
				window.location.href='ac-call-report.php?id_p=<?=$id?>&create_by=<?=$create_by?>';
			</script>
		<?php
		}
		else
		if($_POST['hdnCmd']=='update_data'){
			$id=$_POST['mode'];

			/*$sql_company="select * from company where company_name like '".$_POST['company_name']."'";
			$res_company=mysql_query($sql_company) or die ('Error '.$sql_company);
			$rs_company=mysql_fetch_array($res_company);
			if(!$rs_company){
				$sql_ins_com="insert into company(company_name) values";
				$sql_ins_com .=" ('".$_POST['company_name']."')";
				$res_ins_com=mysql_query($sql_ins_com) or die ('Error '.$sql_ins_com);
				$id_company=mysql_insert_id();
			}else{*/
				$id_company=$_POST['id_company'];
		/*	}

			$sql_product="select * from product where product_name like '".$_POST['product_name']."'";
			$res_product=mysql_query($sql_product) or die ('Error '.$sql_product);
			$rs_product=mysql_fetch_array($res_product);
			if(!$rs_product){
				/*$sql_ins_product="insert into product(id_company,product_name) values";
				$sql_ins_product .=" ('".$id_company."','".$_POST['product_name']."')";
				$res_ins_product=mysql_query($sql_ins_product) or die ('Error '.$sql_ins_product);
				$id_product=mysql_insert_id();*/
			/*}else{*/
				$id_product=$rs_product['id_product'];
			/*}*/
			
			if($_POST['roc_group_product']==-1){$roc_group_product= '-1';}
			else{$roc_group_product=$_POST['roc_group_product'];}

			/*add array roc group functin*/
			$roc_group_product_array=$_POST['roc_group_product'];
			$tag_group_product_string="";
			while (list ($key_group_product,$val_group_product) = @each ($roc_group_product_array)) {
			//echo "$val,";
			$tag_group_product_string.=$val_group_product.",";
			}
			$roc_group_product2=substr($tag_group_product_string,0,(strLen($tag_group_product_string)-1));// remove the last , from string
			
			$roc_function_array=$_POST['roc_function'];
			$tag_string="";
			while (list ($key,$val) = @each ($roc_function_array)) {
			//echo "$val,";
			$tag_string.=$val.",";
			}
			$roc_function=substr($tag_string,0,(strLen($tag_string)-1));// remove the last , from string

			if($_POST['roc_group_product']==1){$roc_function_other=$_POST['roc_function_other1'];}
			elseif($_POST['roc_group_product']==2){$roc_function_other=$_POST['roc_function_other2'];}
			elseif($_POST['roc_group_product']==3){$roc_function_other=$_POST['roc_function_other3'];}
			elseif($_POST['roc_group_product']==4){$roc_function_other=$_POST['roc_function_other4'];}

			$roc_function_other_array=$_POST['roc_function_other'];
			$tag_string_other="";
			while (list ($key_other,$val_other) = @each ($roc_function_other_array)) {
				//echo "$val,";
				$tag_string_other.=$val_other.",";
			}
			$roc_function_other=substr($tag_string_other,0,(strLen($tag_string_other)-1));// remove the last , from string*/

			/*status office*/
			$status_office_array=$_POST['status_office'];
			$tag_string_other="";
			while (list ($key_status_office,$val_status_office) = @each ($status_office_array)) {
				//echo "$val,";
				$tag_status_office.=$val_status_office.",";
			}
			$status_office=substr($tag_status_office,0,(strLen($tag_status_office)-1));// remove the last , from string*/

			// ZAMACHITA meeting
			$post_company_name = mysql_real_escape_string($_POST['company_name']);
			$post_contact_name = mysql_real_escape_string($_POST['contact_name']);
			$post_title_call_report = mysql_real_escape_string($_POST['title_call_report']);

			/*updatea data to table call report*/
			$sql="update call_report set id_company='".$id_company."'";
			$sql .=",company_name='".$post_company_name."'";
			$sql .=",type_office='".$_POST['type_office']."'";
			$sql .=",status_office='".$status_office."'";
			$sql .=",type_customer='".$_POST['type_customer']."'";
			$sql .=",id_group_product='".$roc_group_product2."'";
			$sql .=",other_group_product='".$_POST['other_group_product']."'";
			$sql .=",id_roc_func='".$roc_function."'";
			$sql .=",roc_func_other='".$roc_function_other."'";
			$sql .=",title_call_report='".$post_title_call_report."'";
			$sql .=",id_product='".$id_product."'";
			$sql .=",contact_name='".$post_contact_name."'";
			$sql .=",sample_cost='".$_POST['sample_cost']."'";
			$sql .=",id_type_package='".$_POST['package']."'";
			$sql .=",sample_baht='".$_POST['sample_baht']."'";
			$sql .=",other_cost='".$_POST['other_cost']."'";
			$sql .=",description='".$_POST['description']."'";
			$sql .=",workplan_desc='".$_POST['description2']."'";
			$sql .=" where id_call_report='".$_POST['mode']."'";
			$res=mysql_query($sql) or die ('Error '.$sql);
			
			list($day, $month, $year) = split('[/.-]', $_POST['visited_date']); 
			$visited_date= $year . "-". $day . "-" . $month;

			if($_POST['next_call']=='n'){$year_next='';$day_next='';$month_next='';}
			else{
				list($day_next, $month_next, $year_next) = split('[/.-]', $_POST['date_next_call']); 
				$visited_date_next= $year_next . "-". $day_next . "-" . $month_next;
			}

			$sql_date="update call_report_date set date_visited='".$month."'";
			$sql_date .=",month_visited='".$day."',year_visited='".$year."'";
			$sql_date .=",start_time_visited='".$_POST['start_time1']."'";
			$sql_date .=",end_time_visited='".$_POST['end_time1']."'";
			$sql_date .=",next_call='".$_POST['next_call']."'";
			$sql_date .=",date_next_call='".$month_next."'";
			$sql_date .=",month_next_call='".$day_next."'";
			$sql_date .=",year_next_call='".$year_next."'";
			$sql_date .=",start_time_next='".$_POST['start_time2']."'";
			$sql_date .=",end_time_next='".$_POST['end_time2']."'";
			$sql_date .=" where id_call_report='".$_POST['mode']."'";
			$res_date=mysql_query($sql_date) or die ('Error '.$sql_date);
			?>
			<script>
				window.location.href='ac-call-report.php?id_p=<?=$id?>';
			</script>
		<?php
		}
	?>
	<div class="row">
		<div class="background">
			<form name="frm" method="post" action="<?=$_SERVER["PHP_SELF"]."?id_p=".$id?>">
				<input type="hidden" name="hdnCmd" value="">
				<input type="hidden" name="create_by" value="<?php echo $create_by?>">
				<table style="border: 0; width: 100%;" cellpadding="0" cellspacing="0">
					<?php if($rs_account['role_user']==3){?>
					<tr>
						<td class="b-bottom"><div class="large-4 columns"><h4>Call Report >> <?php echo $mode.' '.$title;?></h4></div></td>
					</tr>
					<tr>
						<td class="b-bottom">
							<div class="large-4 columns">
								<?php if(!is_numeric($id)){?>
								<input type="button" name="save" id="save_data" value="save" class="button-create" OnClick="frm.hdnCmd.value='save_data';JavaScript:return fncSubmit();">
								<?php }else{?>
								<input type="button" name="update_data" id="update_data" value="<?php echo $button?>" class="button-create" OnClick="frm.hdnCmd.value='update_data';JavaScript:return fncSubmit();">
								<!--<input type="button" name="finished_data" id="finished_data" value="Send mail" class="button-create" OnClick="frm.hdnCmd.value='finished_data';JavaScript:return fncSubmit();">-->
								<?php }?>
								<input type="button" value="Close" class="button-create" onclick="window.location.href='call-report-list.php?create_by=<?=$create_by?>&date=<?=$date?>&month=<?=$month?>&year=<?=$year?>'">			
							</div>
						</td>
					</tr>
					<tr>
						<td style="background: #fff;">
							<div class="large-4 columns">
								<input type="hidden" name="mode" value="<?php echo $id?>">
								<input type="hidden" name="date_visited" value="<?php if(is_numeric($id)){echo $rs_date['date_visited'];}else{echo $_GET['date_visited'];}?>">
								<input type="hidden" name="month_visited" value="<?php if(is_numeric($id)){echo $rs_date['month_visited'];}else{echo $_GET['month_visited'];}?>">
								<input type="hidden" name="year_visited" value="<?php if(is_numeric($id)){echo $rs_date['year_visited'];}else{echo $_GET['year_visited'];}?>">
								<script type="text/javascript">
									function ShowTypeOffice() {
										if (document.getElementById('type_office1').checked) {
											document.getElementById('show_office1').style.display = 'none';
											document.getElementById('show_customer').style.display = '';
										}else
										if (document.getElementById('type_office2').checked) {
											document.getElementById('show_office1').style.display = '';
										}
									}
									function ShowType() {
										if (document.getElementById('status_office1').checked) {
											document.getElementById('show_customer').style.display = '';
										}else
										if (document.getElementById('status_office2').checked) {
											document.getElementById('show_customer').style.display = '';
										}else
										if (document.getElementById('status_office3').checked) {
											document.getElementById('show_customer').style.display = 'none';
										}
									}
								</script>
								<table style="border: none; width: 100%;" cellpadding="0" cellspacing="0" id="tb-add">
									<tr>
										<td class="title w15">Type Office<span style="color:red;">*</span></td>	
										<td>
											<input type="radio" id="type_office1" name="type_office" value="1" <?php if($rs['type_office']==1){echo 'checked';}?> onclick="javascript:ShowTypeOffice();">In office
											<input type="radio" id="type_office2" name="type_office" value="2" <?php if($rs['type_office']==2){echo 'checked';}?> onclick="javascript:ShowTypeOffice();">Out office
										</td>
									</tr>
									<tr>
										<td class="title w15"></td>	
										<td>
											<?php $status_office2=split(",",$rs['status_office']);?>
											<div id="show_office1" <?php if($rs['type_office']==2){echo '';}else{echo 'style="display: none;"';}?>>
												<input type="checkbox" id="status_office1" name="status_office[]" value="1"  onclick="javascript:ShowType();" <?php if(in_array('1',$status_office2)){echo 'checked';}?>>Visited
												<input type="checkbox" id="status_office2" name="status_office[]" value="2" onclick="javascript:ShowType();" <?php if(in_array('2',$status_office2)){echo 'checked';}?>>Booth
												<input type="checkbox" id="status_office3" name="status_office[]" value="3"  onclick="javascript:ShowType();" <?php if(in_array('3',$status_office2)){echo 'checked';}?>>Leave
											</div>
										</td>
									</tr>
									<tr>
										<td class="title w15"></td>	
										<td>
											<div id="show_customer" <?php if((in_array('1',$status_office2)) || ($rs['type_office']==1)){echo '';}else{echo 'style="display: none;"';}?>>
												<input type="radio" id="type_customer1" name="type_customer" value="1" <?php if($rs['type_customer']==1){echo 'checked';}?>>New
												<input type="radio" id="type_customer2" name="type_customer" value="2" <?php if($rs['type_customer']==2){echo 'checked';}?>>Exiting
											</div>
										</td>
									</tr>
									<tr>
										<td class="title w15">Company Name<span style="color:red;">*</span></td>	
										<td>	
											<?php 
											if($rs['type_customer']==1){$customer_style= 'color:red';}
											else{$customer_style='';}
											if($rs['id_company_name'] != 0){
												$sql_company="select * from company where id_company='".$rs['id_company']."'";
												$res_company=mysql_query($sql_company) or die ('Error '.$sql_company);
												$rs_company=mysql_fetch_array($res_company);
												$id_company=$rs_company['id_company'];
												$company_name=$rs_company['company_name'];
											}else{$company_name=$rs['company_name'];}
											?>		
											<input type="hidden" name="id_u" value="<?php echo $rs_call_report['id_call_report']?>">
											<input type="hidden" name="id_company" id="id_company" value="<?php echo $id_company?>">
											<input type="text" id="company_name" name="company_name" class="company_name" style="<?php echo $customer_style?>" value="<?php echo $company_name?>">
										</td>
									</tr>
									<tr>
										<td class="title">Title<span style="color:red;">*</span></td>
										<td><input type="text" name="title_call_report" value="<?php echo $rs['title_call_report']?>"></td>
									</tr>
									<tr>
										<td class="title">Project Name</td>
										<td colspan="2">
											<!--<?php 
											$sql_product="select * from product where id_product='".$rs['id_product']."'";
											$res_product=mysql_query($sql_product) or die ('Error '.$sql_product);
											$rs_product=mysql_fetch_array($res_product);
											?>
											<input type="hidden" name="id_product" id="id_product" value="<?php echo $rs_product['id_product']?>">
											<input type="text" name="product_name" id="product_name" class="product_name" value="<?php echo $rs_product['product_name']?>">
											-->
											<table style="width:310%;text-align:left;border:none;" cellpadding="0" cellspacing="0">
												<tr>
													<td colspan="2" style="padding:0 0 1% 0;font-size:14px;">ฟังก์ชั่นการทำงาน</td>
												</tr>
												<?php
												$roc_func=split(",",$rs['id_roc_func']);
												$roc_group_func=split(",",$rs['id_group_product']);
												$roc_other_func=split(",",$rs['roc_func_other']);
												$i=0;
												$j=0;
												$sql_roc_group_product="select * from roc_group_product";
												$res_roc_group_product=mysql_query($sql_roc_group_product) or die ('Error '.$sql_roc_group_product);
												$max_row_g=mysql_num_rows($res_roc_group_product);
												while($rs_roc_group_product=mysql_fetch_array($res_roc_group_product)){
													$i++;
												?>
												<tr>
													<td style="padding:0 0 0 0.5%;margin:0;font-size:12px;"><input type="checkbox" name="roc_group_product[]" id="roc_group_product<?php echo $i?>" value="<?php echo $rs_roc_group_product['id_group_product']?>" <?php if(is_numeric($id)){if(in_array($rs_roc_group_product['id_group_product'],$roc_group_func)){echo 'checked';}}?> onclick="javascript:ShowFunc();">1.<?php echo $i.'&nbsp;'.$rs_roc_group_product['title'];?></td>
												</tr>
												<tr>
													<td>
														<table style="width:100%;border:none;font-size:14px;padding:0 0 1% 0;margin:0;"cellpadding="0" cellspacing="0">
														<?php;
														$i_function=0;
														$i_function2=0;
														$max_row_g2=0;
														$num=0;
														$sql_roc_function="select * from roc_function where id_group_product='".$rs_roc_group_product['id_group_product']."'";
														$res_roc_function=mysql_query($sql_roc_function) or die ('Error '.$sql_roc_function);
														$max_row=mysql_num_rows($res_roc_function);
														while($rs_roc_function=mysql_fetch_array($res_roc_function)){		
															$num++;	
															if($i_function % 3 == 0){?><tr><?php } //display row
															$i_function2++;
														?>
															<td style="width:33.33%;padding:1.0% 0 0 3%;"><input type="checkbox" class="checkbox" name="roc_function[]" id="roc_function<?php echo $rs_roc_function['id_roc_func']?>" rel="roc_group_product<?php echo $i?>" value="<?php echo $rs_roc_function['id_roc_func']?>" <?php if(in_array($rs_roc_function['id_roc_func'],$roc_func)){echo 'checked';}?>><?echo $rs_roc_function['title']?></td>
															<?php if($i_function2 % 3 == 0){?></tr><?php } //display end row?>
															<?php if($num==$max_row){  ?>
															<?php if($rs_roc_group_product['id_group_product']==1){?><tr><?php }?>
															<td style="width:33.33%;padding:1.0% 0 0 3%;"><div style="float:left; margin: 0 1% 0 0;"><input type="checkbox" class="checkbox" <?php if($rs_roc_group_product['id_group_product']==1){?>value="0"<?php }elseif($rs_roc_group_product['id_group_product']==2){?>value="-1"<?php }elseif($rs_roc_group_product['id_group_product']==3){?>value="-2"<?php }elseif($rs_roc_group_product['id_group_product']==4){?>value="-3"<?php }?> name="roc_function[]" <?php if($rs_roc_group_product['id_group_product']==1){if(in_array('0',$roc_func)){echo 'checked';}}elseif($rs_roc_group_product['id_group_product']==2){if(in_array('-1',$roc_func)){echo 'checked';}}elseif($rs_roc_group_product['id_group_product']==3){if(in_array('-2',$roc_func)){echo 'checked';}}elseif($rs_roc_group_product['id_group_product']==4){if(in_array('-3',$roc_func)){echo 'checked';}}?>></div><div style="float:left; margin: 0 0.5% 0 0;">อื่น ๆ</div><div style="float:left"><input type="text" name="roc_function_other[]" value="<?php echo $roc_other_func[$j];?>"></td>
															<?php if($rs_roc_group_product['id_group_product']==1){?></tr><?php }?>
															<?php $j++;$max_row_g2=$max_row_g+1;
															} $i_function++; 
															?>														
														<?php }//end while?>
														</table>
													</td>
												</tr>	
												<?php }//end while function
												$max_row_g=$max_row_g+1;
												?>
												<tr>
													<td style="font-size:12px;"><div style="float:left;margin: 0 0.5% 0 0;"><input type="checkbox" name="roc_group_product[]" id="roc_group_product<?php echo $i+1?>" <?php if(in_array(-1,$roc_group_func)){echo 'checked';}?> value="-1" onclick="javascript:ShowFunc();">1.<?php echo $max_row_g.'&nbsp;อื่น ๆ'?></div>
													<!--<?php if($rs['id_group_product']== -1){$style='';}else{$style='display: none;';}?>-->
													<div style="float:left;margin: 0 0.5% 0 0;"><input type="text" name="other_group_product" id="show_func<?php echo $i+1?>" value="<?php if(is_numeric($id)){echo $rs['other_group_product'];}?>"></div></td>
												</tr>
											</table>
										</td>
									</tr>
									<tr>
										<td class="title w15">Contact name<span style="color:red;">*</span></td>
										<td>
											<input type="text" name="contact_name" value="<?php echo $rs['contact_name']?>">
										</td>
									</tr>
									<tr>
										<td class="title">Sample or cost</td>
										<td><input type="text" name="sample_cost" value="<?php echo $rs['sample_cost']?>"></td>
										<td>
											<select name="package">
											<?php
											$sql_sample="select * from type_packaging";
											$res_sample=mysql_query($sql_sample) or die ('Error '.$sql_sample);
											while($rs_sample=mysql_fetch_array($res_sample)){
											?>
												<option value="<?php echo $rs_sample['id_type_package']?>" <?php if($rs_sample['id_type_package']==$rs['id_type_package']){echo 'selected';}?>><?php echo $rs_sample['title']?></option>
											<?php } ?>
											</select>
										</td>
										<td><input type="text" name="sample_baht" style="width: 40%; float: left; margin-right: 2%;" value="<?php echo $rs['sample_baht']?>">Baht</td>
									</tr>
									<tr>
										<td class="title">Other cost</td>
										<td><input type="text" name="other_cost" style="width: 50%; float: left; margin-right: 2%;" value="<?php echo $rs['other_cost']?>">Baht</td>
									</tr>
									<tr>
										<script>
											$(function() {
												$( "#datepicker" ).datepicker({
													showOn: "button",
													buttonImage: "img/calendar.gif",
													buttonImageOnly: true
												});
											});
										</script>
										<!--<?php if($rs_date['date_visited']<10){$date_z='0'.$rs_date['date_visited'];}else{$date_z=$rs_date['date_visited'];}?>-->
										<td class="title">Date of visited<span style="color: red;">*</span></td>
										<td colspan="2">
											<input type="text" id="datepicker" name="visited_date" value="<?php if(is_numeric($id)){echo $rs_date['month_visited'].'/'.$rs_date['date_visited'].'/'.$rs_date['year_visited'];}else{echo date('m/d/Y');}?>" style="width: 50%; float: left; margin-right: 2%;"/>
										</td>
									</tr>
									<tr>
										<td class="title">Start time</td>
										<td class="w20"><div id="sample1"><input type="text" name="start_time1" value="<?php echo $rs_date['start_time_visited']?>"></div></td>
										<td class="title w10">End time</td>
										<td><div id="sample1"><input type="text" name="end_time1" value="<?php echo $rs_date['end_time_visited']?>" style="width: 40%;"></div></td>
											<script type="text/javascript">
												$(document).ready(function(){
													$('#sample1 input').ptTimeSelect();
												});
											</script>
									</tr>
									<tr>
										<td class="title">Description</td>
										<td colspan="3">
											<textarea id="description" name="description" width="100%" ><?php echo $rs['description']?></textarea>
											<script type="text/javascript">
												// This is a check for the CKEditor class. If not defined, the paths must be checked.
												if ( typeof CKEDITOR == 'undefined' ){
													document.write(
														'<strong><span style="color: #ff0000">Error</span>: CKEditor not found</strong>.' +
														'This sample assumes that CKEditor (not included with CKFinder) is installed in' +
														'the "/ckeditor/" path. If you have it installed in a different place, just edit' +
														'this file, changing the wrong paths in the &lt;head&gt; (line 5) and the "BasePath"' +
														'value (line 32).' ) ;
												}else{
													var editor = CKEDITOR.replace( 'description',{
														filebrowserBrowseUrl : 'ckeditor-integrated/ckfinder/ckfinder.html',
														filebrowserImageBrowseUrl : 'ckeditor-integrated/ckfinder/ckfinder.html?type=Images',
														filebrowserFlashBrowseUrl : 'ckeditor-integrated/ckfinder/ckfinder.html?type=Flash',
														filebrowserUploadUrl : 'ckeditor-integrated/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
														filebrowserImageUploadUrl : 'ckeditor-integrated/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
														filebrowserFlashUploadUrl : 'ckeditor-integrated/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash',
														toolbar :
															[ ['Bold', 'Italic', 'Underline', '-', 'Subscript', 'Superscript', '-',  
															  'NumberedList', 'BulletedList', '-', 'Link', 'Unlink'],
															  ['Cut','Copy','Paste','Undo','Redo' ,'Find','Replace'],
															  ['Outdent', 'Indent', '-', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'],  
															  '/',											  
															  ['Styles', 'Format', 'Font', '-', 'FontSize', 'Image', 'TextColor', 'BGColor','Table'] ]
														} 

													);
													// Just call CKFinder.setupCKEditor and pass the CKEditor instance as the first argument.
													// The second parameter (optional), is the path for the CKFinder installation (default = "/ckfinder/").
													CKFinder.setupCKEditor( editor, '../' ) ;
													// It is also possible to pass an object with selected CKFinder properties as a second argument.
													// CKFinder.setupCKEditor( editor, { basePath : '../', skin : 'v1' } ) ;
												}
											</script>
										</td>
									</tr>
									<tr>
										<td colspan="4"><h3>Work plan</h3></td>
									</tr>
									<tr>
										<td class="title">Next call object</td>
										<td colspan="2">
											<script>
												$(":radio[name='next_call']").click(function(){
													var value = $(this).val();
													if(value === "n"){
														$(".date_next_call").attr("disabled", "disabled");
														return;
													}
													$(".date_next_call").removeAttr("disabled");
												}).click();
											</script>
											<div style="float:left;"><input type="radio" name="next_call" class="next_call_n" value="n" <?php if($rs_date['next_call']=='n'){echo 'checked';}?>>No</div>
											<div style="float:left;"><input type="radio" name="next_call" class="next_call_y" value="y" <?php if($rs_date['next_call']=='y'){echo 'checked';}?>>Yes</div>
											<script>
												$(function() {
													jQuery.noConflict();
													jQuery( ".date_next_call" ).datepicker({
														showOn: "button",
														buttonImage: "img/calendar.gif",
														buttonImageOnly: true
													});
												});
											</script>
											<input type="text" class="date_next_call" name="date_next_call" value="<?php if($rs['next_call']=='y'){echo $rs_date['month_next_call'].'/'.$rs_date['date_next_call'].'/'.$rs_date['year_next_call'];}?>" style="width: 50%; float: left; margin-right: 2%;"/>
										</td>
									</tr>
									<tr>											
										<td class="title">Start time</td>
										<td><div id="sample1"><input type="text" name="start_time2" value="<?php echo $rs_date['start_time_next']?>"></div></td>
										<td class="title">End time</td>
										<td><div id="sample1"><input type="text" name="end_time2" value="<?php echo $rs_date['end_time_next']?>" style="width: 40%;"></div></td>												
									</tr>
									<tr>
										<td class="title">Description</td>
										<td colspan="3">
											<textarea width="100%" class="description2" name="description2"><?php echo $rs['workplan_desc']?></textarea>   
											<script type="text/javascript">  										  
												// This is a check for the CKEditor class. If not defined, the paths must be checked.
												if ( typeof CKEDITOR == 'undefined' ){
													document.write(
														'<strong><span style="color: #ff0000">Error</span>: CKEditor not found</strong>.' +
														'This sample assumes that CKEditor (not included with CKFinder) is installed in' +
														'the "/ckeditor/" path. If you have it installed in a different place, just edit' +
														'this file, changing the wrong paths in the &lt;head&gt; (line 5) and the "BasePath"' +
														'value (line 32).' ) ;
												}else{
													var editor = CKEDITOR.replace( 'description2',{
														filebrowserBrowseUrl : 'ckeditor-integrated/ckfinder/ckfinder.html',
														filebrowserImageBrowseUrl : 'ckeditor-integrated/ckfinder/ckfinder.html?type=Images',
														filebrowserFlashBrowseUrl : 'ckeditor-integrated/ckfinder/ckfinder.html?type=Flash',
														filebrowserUploadUrl : 'ckeditor-integrated/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
														filebrowserImageUploadUrl : 'ckeditor-integrated/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
														filebrowserFlashUploadUrl : 'ckeditor-integrated/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash',
														toolbar :
															[ ['Bold', 'Italic', 'Underline', '-', 'Subscript', 'Superscript', '-',  
															  'NumberedList', 'BulletedList', '-', 'Link', 'Unlink'],
															  ['Cut','Copy','Paste','Undo','Redo' ,'Find','Replace'],
															  ['Outdent', 'Indent', '-', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'],  
															  '/',											  
															  ['Styles', 'Format', 'Font', '-', 'FontSize', 'Image', 'TextColor', 'BGColor','Table'] ] 
														} 
													);
													// Just call CKFinder.setupCKEditor and pass the CKEditor instance as the first argument.
													// The second parameter (optional), is the path for the CKFinder installation (default = "/ckfinder/").
													CKFinder.setupCKEditor( editor, '../' ) ;
													// It is also possible to pass an object with selected CKFinder properties as a second argument.
													// CKFinder.setupCKEditor( editor, { basePath : '../', skin : 'v1' } ) ;
												}											  
											</script> 
										</td>
									</tr>
								</table>
							</div>
						</td>
					</tr>
					<tr>
						<td class="b-top">
							<div class="large-4 columns">
								<?php if(!is_numeric($id)){?>
								<input type="button" name="save" id="save_data" value="save" class="button-create" OnClick="frm.hdnCmd.value='save_data';JavaScript:return fncSubmit();">
								<?php }else{?>
								<input type="button" name="update_data" id="update_data" value="<?php echo $button?>" class="button-create" OnClick="frm.hdnCmd.value='update_data';JavaScript:return fncSubmit();">
								<!--<input type="button" name="finished_data" id="finished_data" value="Send mail" class="button-create" OnClick="frm.hdnCmd.value='finished_data';JavaScript:return fncSubmit();">-->
								<?php }?>
								<input type="button" value="Close" class="button-create" onclick="window.location.href='call-report-list.php?create_by=<?=$create_by?>&date=<?=$_GET['date']?>&month=<?=$_GET['month']?>&year=<?=$_GET['year']?>'">
							</div>
						</td>
					</tr>
					<?php }else{?>
					<tr>
						<td colspan="2" style="text-align:right;"><input type="button" value="Back" class="button-create" onclick="window.location.href='call-report-list.php?create_by=<?=$create_by?>&date=<?=$date?>&month=<?=$month?>&year=<?=$year?>'"></td>
					</tr>
					<tr>
						<td colspan="2"><h4>Call report by 
							<?php 
							$sql_acc="select * from account where id_account='".$rs['create_by']."'";
							$res_acc=mysql_query($sql_acc) or die ('Error '.$sql_acc);
							$rs_acc=mysql_fetch_array($res_acc);
							echo $rs_acc['username'];
							?></h4>
						</td>
					</tr>
					<tr>
						<?php $status_office2=split(",",$rs['status_office']);?>
						<td class="title" style="width: 20%;">Status:</td>
						<td>
							<?php
							if($rs['type_office'] == 1){echo 'In Office';}
							elseif($rs['type_office'] == 2){
									echo 'Out Office : ';
									if(in_array('1',$status_office2)){echo 'Visited';}
									elseif(in_array('2',$status_office2)){echo 'Booth';}
									elseif(in_array('3',$status_office2)){echo 'Leave';}
							}
							?>
						</td>
					</tr>
					<tr>
						<td class="title" style="width: 20%;">Company:</td>
						<td>
							<?php
							if($rs['type_customer']==1){$style_red='<span style="color:red;">';$style_end_red='</span>';}
							elseif($rs['type_customer']==2){$style_red='';$style_end_red='';}
							if($rs['id_company'] != 0){
								$sql_company="select * from company where id_company='".$rs['id_company']."'";
								$res_company=mysql_query($sql_company) or die ('Error '.$sql_company);
								$rs_company=mysql_fetch_array($res_company);
								echo $style_red.$rs_company['company_name'].$style_end_red;
							}else{echo $style_red.$rs['company_name'].$style_end_red;}
							?>
						</td>
					</tr>
					<tr>
						<td class="title">Contact name:</td>
						<td><?php echo $rs['contact_name']?></td>
					</tr>
					<tr>
						<td class="title">Title:</td>
						<td><?php echo $rs['title_call_report']?></td>
					</tr>
					<tr>
						<td class="title">Date&Time of visit:</td>
						<td><?php echo $rs_date['date_visited'].'/'.$rs_date['month_visited'].'/'.$rs_date['year_visited'].'&nbsp;&nbsp;&nbsp;'.$rs_date['start_time_visited'].'-'.$rs_date['end_time_visited']?></td>
					</tr>
					<tr>
						<td class="title">Project name:</td>
						<td>
							<?php
							$sql_product="select * from product where id_product='".$rs['id_product']."'";
							$res_product=mysql_query($sql_product) or die ('Error '.$sql_product);
							$rs_product=mysql_fetch_array($res_product);
							echo $rs_product['product_name'];
							?>
						</td>
					</tr>
					<tr>
						<td class="title" style="vertical-align:top;">Description</td>
						<td><?php echo $rs['description']?></td>
					</tr>
					<tr>
						<td class="title" style="vertical-align:top;">Next call objective</td>
						<td>
							<?php 
							if($rs_date['next_call']=='y'){
								echo $rs_date['date_next_call'].'/'.$rs_date['month_next_call'].'/'.$rs_date['year_next_call'];
								echo '&nbsp;&nbsp;&nbsp;';
								echo $rs_date['start_time_next'].'-'.$rs_date['end_time_next'];
							}else{echo '-';}
							?>
						</td>
					</tr>
					<tr>
						<td class="title" style="vertical-align:top;">Description next objective</td>
						<td><?php if($rs_date['next_call']=='y'){echo $rs['workplan_desc'];}else{echo '-';}?></td>
					</tr>
					<?php }?>
				</table>
			</form>
		</div>
	</div> 
</body>
</html>