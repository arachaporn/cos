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
$_SESSION['id_company']=$_REQUEST['id_company'];
$_SESSION['id_product']=$_REQUEST['id_product'];
$_SESSION['company_name']=$_REQUEST['company_name'];
$_SESSION['title_call_report']=$_REQUEST['title_call_report'];
$_SESSION['product_name']=$_REQUEST['product_name'];
$_SESSION['sample_cost']=$_REQUEST['sample_cost'];
$_SESSION['package']=$_REQUEST['package'];
$_SESSION['sample_baht']=$_REQUEST['sample_baht'];
$_SESSION['other_cost']=$_REQUEST['other_cost'];

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
<script language="javascript">
function fncSubmit(){
	if(document.frm.company_name.value == "")
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
	}
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
</head>
<body>
	<?php include("menu.php");?>
	<div class="row">
		<div class="background">
			<?php
			include("connect/connect.php");
			if($_GET["id_u"]=='New'){
				$mode='New';
				$button='Save';
				$id='New';
			}
			else{
				$id=$_GET["id_u"];
				$sql="select * from call_report where id_call_report='".$id."'";
				$res=mysql_query($sql) or die ('Error '.$sql);
				$rs=mysql_fetch_array($res);
				$mode='Edit '.$rs['title_call_report'];
				$button='Update';
			}
				
			//*** Add Condition ***//
			if($_POST["hdnCmd"] == "Add"){
				$date=date('Y-m-d');

				if(is_numeric($_POST['mode'])){$id_call_report=$_POST['mode'];}
				else{$id_call_report=0;}
				//insert to call report relationship
				$sql_cr_relation="insert into call_report_relationship(id_call_report,id_contact)";
				$sql_cr_relation .=" values('".$id_call_report."','".$_POST['id_contact']."')";
				$rs_cr_relation=mysql_query($sql_cr_relation) or die ('Error '.$sql_cr_relation);
				//header("location:$_SERVER[PHP_SELF]");
				//exit();

			}

			//*** Update Condition ***//
			if($_POST["hdnCmd"] == "Update"){

				$sql = "update company_contact set contact_name='".$_POST['contact_name2']."'";
				$sql .=",department= '".$_POST['department2']."',tel='".$_POST['telephone2']."'";
				$sql .=",email='".$_POST['email2']."'";
				$sql .=" where id_contact = '".$_POST["hdnEdit"]."' ";
				$res = mysql_query($sql) or die ('Error '.$sql);
			}

			//*** Delete Condition ***//
			if($_GET["action"] == "del"){
				$sql = "delete from call_report_relationship ";
				$sql .="where id_cr_relation = '".$_GET["id_p"]."'";
				$res = mysql_query($sql) or die ('Error '.$sql);
			}
			if($_POST["hdnCmd"]=="save_data"){
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
							else{$date_st='n';$month_st='mn';}
						}
							else{$month_st='mn';}
					}
				}
				if(($date_st=='n') || ($month_st=='mn')){?>	
					<script>
						window.alert('Can not save');
						window.location.href='call-report.php';
					</script>
				<?php 
				}else{
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
						$start= $year . "-". $month . "-" . $day;

					$sql_date="insert into call_report_date(id_call_report,visited_date,s_h_time";
					$sql_date .=",s_e_time,s_type,e_h_time,e_e_time,e_type,description,time_status)";
					$sql_date .=" values('".$id_call_report."','".$start."','".$h_time[$key_time]."'";
					$sql_date .=",'".$s_time[$key_time]."','".$w_ap_time[$key_time]."'";
					$sql_date .=",'".$h_time2[$key_time]."','".$s_time2[$key_time]."'";
					$sql_date .=",'".$w_ap_time2[$key_time]."','".$description[$key_time]."'";
					$sql_date .=",'".$time_status[$key_time]."')";
					$res_date=mysql_query($sql_date) or die ('Error '.$sql_date);
				}//end save call report date
								
				$name=$_POST['contact_name'];
				$department=$_POSR['department'];
				$telephone=$_POST['telephone'];
				$email=$_POST['email'];
				$length = count($name);
				for($key=0;$key<$length;$key++){
					$sql_com_contact="insert into company_contact(id_company";
					$sql_com_contact .=",contact_name,department,mobile,email)";
					$sql_com_contact .=" values ('".$id_company."'";
					$sql_com_contact .=",'".$name[$key]."','".$department[$key]."'";
					$sql_com_contact .=",'".$telephone[$key]."','".$email[$key]."')";
					$res_com_contact=mysql_query($sql_com_contact) or die ('Error '.$sql_com_contact);
					$id_com_contact=mysql_insert_id();
				} 

				$sql_cr_update="update call_report_relationship set id_call_report='".$id_call_report."'";
				$sql_cr_update .=" where id_call_report='0'";
				$res_cr_update=mysql_query($sql_cr_update) or die ('Error '.$sql_cr_update);
			}
			?>
				<script>
					window.location.href='ac-call-report.php?id_u=<?=$id_call_report?>';
				</script>
			<?php }
			if($_POST["hdnCmd"]=="update_data"){
				$id_call_report=$_POST['mode'];
				/*updatea data to table call report*/
				$sql="update call_report set id_company='".$_POST['id_company']."'";
				$sql .=",title_call_report='".$_POST['title_call_report']."'";
				$sql .=",id_product='".$_POST['id_product']."'";
				$sql .=",sample_cost='".$_POST['sample_cost']."'";
				$sql .=",id_type_package='".$_POST['id_type_package']."'";
				$sql .=",sample_baht='".$_POST['sample_baht']."'";
				$sql .=",other_cost='".$_POST['other_cost']."'";
				$sql .=" where id_call_report='".$_POST['mode']."'";
				$res=mysql_query($sql) or die ('Error '.$sql);
				/*end table call report*/
				/*Get date and time*/
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
					$start= $year . "-". $month . "-" . $day;
				/*update data to table call report date and time*/
				$sql_date="update call_report_date set visited_date='".$start."'";
				$sql_date .=",s_h_time='".$h_time[$key_time]."'";
				$sql_date .=",s_e_time='".$s_time[$key_time]."'";
				$sql_date .=",s_type='".$w_ap_time[$key_time]."'";
				$sql_date .=",e_h_time='".$h_time2[$key_time]."'";
				$sql_date .=",e_e_time='".$s_time2[$key_time]."'";
				$sql_date .=",e_type='".$w_ap_time2[$key_time]."'";
				$sql_date .=",description='".$description[$key_time]."'";
				$sql_date .=",time_status='".$time_status[$key_time]."'";
				$sql_date  .=" where id_call_report='".$_POST['mode']."'";
				$res_date=mysql_query($sql_date) or die ('Error '.$sql_date);
				/*end table call report date and time*/
				}

				$sql_cr_update="update call_report_relationship set id_call_report='".$id_call_report."'";
				$sql_cr_update .=" where id_call_report='0'";
				$res_cr_update=mysql_query($sql_cr_update) or die ('Error '.$sql_cr_update);
				?>
				<script>
					window.location.href='ac-call-report.php?id_u=<?=$id_call_report?>';
				</script>
			<?php }
				if($_POST["hdnCmd"]=="finished_data"){
					$MailTo = "oemmanager.cdip@gmail.com";
					$MailFrom = $rs_account['email'];
					$MailSubject = "Call Report ".$rs['title_call_report']." by ".$rs_account['name'];
					$MailMessage = $rs_account['name']." have created call report ".$rs['title_call_report']." on ".date('d/m/Y').".<br>";
					$MailMessage .= "Please see link : <a href='http://cdipthailand.com/cos/call-report.php'>Sales&Marketing -> Call Report</a>";
					
					$Headers = "";
					$Headers .= "From: ".$MailFrom." <".$MailFrom.">\r\n" ;
					//$Headers .= "Reply-to: ".$MailFrom." <".$MailFrom.">\r\n" ;
					$Headers .="Mailed-by : cdipthailand.com";

					$Headers .= "MIME-Version: 1.0\r\n" ;
					$Headers .= "Content-type: text/html; charset=utf-8\r\n"; 
					$Headers .= $MailMessage."\n\n";


					$flgSend = @mail($MailTo,$MailSubject,null,$Headers,"-f  test@cdipthailand.com ");
					if($flgSend){
					?>
						<script>
							window.alert('Send mail complete');
							window.location.href='call-report.php';
						</script>
					<?php }else{?>
						<script>
							window.alert('Send mail False');
							history.back();
						</script>
					<?php } 
				}
			?>
			<form name="frm" method="post" action="<?=$_SERVER["PHP_SELF"]."?id_u=".$id?>">
			<input type="hidden" name="hdnCmd" value="">
			<table style="border: 0; width: 100%;" cellpadding="0" cellspacing="0">
				<tr>
					<td class="b-bottom"><div class="large-4 columns"><h4>Call Report >> <?php echo $mode;?></h4></div></td>
				</tr>
				<tr>
					<td class="b-bottom">
						<div class="large-4 columns">
							<?php if(!is_numeric($id)){?>
							<input type="button" name="save_data" id="save_data" value="<?php echo $button?>" class="button-create" OnClick="frm.hdnCmd.value='save_data';JavaScript:return fncSubmit();">
							<?php }else{?>
							<input type="button" name="update_data" id="update_data" value="<?php echo $button?>" class="button-create" OnClick="frm.hdnCmd.value='update_data';JavaScript:return fncSubmit();">
							<!--<input type="button" name="finished_data" id="finished_data" value="Send mail" class="button-create" OnClick="frm.hdnCmd.value='finished_data';JavaScript:return fncSubmit();">-->
							<?php }?>
							<input type="button" value="Close" class="button-create" onclick="window.location.href='call-report.php'">							
						</div>
					</td>
				</tr>
				<tr>
					<td style="background: #fff;">
						<div class="large-4 columns">						
							<input type="hidden" name="mode" value="<?php echo $id?>">
							<script>
								$(document).ready(function(){
									$('.WorldphpTab>ul a').click( function(){
									  t = $(this).attr('href');
									  $('.WorldphpTab>ul li').removeClass('active');
									  $('.WorldphpTabData>div').removeClass('active');
									  $(this).parent().addClass('active'); 
									  $(t).addClass('active');
									});
								});
							</script>
							<?php
								$weekDay = array( 'Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');
								$EngMonth = array( "01" => "January", "02" => "February", "03" => "March", "04" => "April",
											"05" => "May","06" => "June", "07" => "July", "08" => "August",
											"09" => "September", "10" => "October", "11" => "November", "12" => "December");
										 
								//Sun - Sat
								$month = isset($_GET['month']) ? $_GET['month'] : date('m'); //ถ้าส่งค่าเดือนมาใช้ค่าที่ส่งมา ถ้าไม่ส่งมาด้วย ใช้เดือนปัจจุบัน
								$year = isset($_GET['year']) ? $_GET['year'] : date('Y'); //ถ้าส่งค่าปีมาใช้ค่าที่ส่งมา ถ้าไม่ส่งมาด้วย ใช้ปีปัจจุบัน
								//วันที่
								$startDay = $year.'-'.$month."-01";   //วันที่เริ่มต้นของเดือน
										 
								$timeDate = strtotime($startDay);   //เปลี่ยนวันที่เป็น timestamp
								$lastDay = date("t", $timeDate);   //จำนวนวันของเดือน
										 
								$endDay = $year.'-'.$month."-". $lastDay;  //วันที่สุดท้ายของเดือน
								 
								$startPoint = date('w', $timeDate);   //จุดเริ่มต้น วันในสัปดาห์
										 
								//echo "
								$data ;
								//print_r($data);
								//echo "<hr>";
							?>
							<script type='text/javascript'>
								function goTo(month, year){
									window.location.href = "itenary-report.php?year="+ year +"&month="+ month;
								}
							</script>
							<?php
							$title = $EngMonth[$month];
							$date2=date('d');
							$month2=date('F');
							$year2=date('Y');
							//ลดเวลาลง 1 เดือน
							$prevMonTime = strtotime ( '-1 month' , $timeDate  );
							$prevMon = date('m', $prevMonTime);
							$prevYear = date('Y', $prevMonTime);
							$prevMonText = date('F', $prevMonTime);
							//เพิ่มเวลาขึ้น 1 เดือน
							$nextMonTime = strtotime ( '+1 month' , $timeDate  );
							$nextMon = date('m', $nextMonTime);
							$nextYear = date('Y', $nextMonTime);
							$nextMonText = date('F', $nextMonTime);
							
							$date21=date("Y-m-d");							
							//echo$week  = date("W", strtotime($date21));
							$sql_month="select * from month where no_month='".$_GET['month']."'";
							$res_month=mysql_query($sql_month) or die ('Error '.$sql_month);
							$rs_month=mysql_fetch_array($res_month);
							if(is_numeric($id)){echo '<h5>'.$rs_month['title_month'].' '.$_GET['year'].'</h5>';}
							else{echo '<h5>'.$month2.' '.$year2.'</h5>';}
							?>
							<!-- Tab -->
							<div class="WorldphpTab">
								<ul>
									<?php
										$sql_rela_month="select * from call_report_relation_month";
										$sql_rela_month .=" where call_year='".$year2."'";
										$sql_rela_month .=" and no_month='".$month."'";
										$sql_rela_month .=" and call_date='".$_REQUEST['call_date']."'";
										$res_rela_month=mysql_query($sql_rela_month) or die ('Error '.$sql_rela_month);
										$rs_rela_month=mysql_fetch_array($res_rela_month);
										
										$sql_call_date="select * from call_report_date where id_call_report='".$id."'";
										$res_call_date=mysql_query($sql_call_date) or die ('Error '.$sql_call_date);
										$rs_call_date=mysql_fetch_array($res_call_date);
										$col=0;
										for($i=1; $i <= $lastDay; $i++){ //วนลูป ตั้งแต่วันที่ 1 ถึงวันสุดท้ายของเดือน
											$col++;
											//echo date('d');
											//echo  date("M").'&nbsp;'.$i;
											//echo '&nbsp;';
											$date_array[] = array($i);
											if($col % 7 == 0){   //ถ้าครบ 7 คอลัมน์ให้ขึ้นบรรทัดใหม่
												//echo '<br>';
												$col = 0; 
											}
											/*if($col < 7){ 
												echo str_repeat('&nbsp;',7-$col); //สร้างคอลัมน์ให้ครบตามจำนวนที่ขาด
											}*/						
									?>
									<li <?php if(is_numeric($id)){if($_GET['call_date']==$i){echo 'class="active"';}}else{if(date("d")==$i){echo 'class="active"';}}?>><a href="ac-call-report.php?id_u=<?=$_GET['id_u']?>&year=<?=$_GET['year']?>&month=<?=$_GET['month']?>&call_date=<?=$i?>#tab<?=$i?>"><?php echo $i?></a></li>									
									<?php }?>
								</ul>
								<div class="WorldphpTabData">
									<?php for($j=1; $j <= $lastDay; $j++){ ?>
									<div id="tab<?=$j?>" <?php if(is_numeric($id)){if($_GET['call_date']==$j){echo 'class="active"';}}else{if(date("d")==$j){echo 'class="active"';}}?>>
										<table style="border: none; width: 100%;" cellpadding="0" cellspacing="0" id="tb-add">
											<tr>
												<td colspan="2"><h4>1.Information</h4></td>
											</tr>
											<tr>
												<td width="25%">
													<p class="title">Company Name<span style="color:red;">*</span></p>										
													<?php 
													$sql_call_report="select * from call_report where id_call_report='".$rs_rela_month['id_call_report']."'";
													$res_call_report=mysql_query($sql_call_report) or die ('Error '.$sql_call_report);
													$rs_call_report=mysql_fetch_array($res_call_report);

													$sql_company="select * from company where id_company='".$rs_call_report['id_company']."'";
													$res_company=mysql_query($sql_company) or die ('Error '.$sql_company);
													$rs_company=mysql_fetch_array($res_company);
													?>		
													<input type="hidden" name="id_u" value="<?php echo $rs_call_report['id_call_report']?>">
													<input type="hidden" name="id_company" id="id_company" value="<?php if(is_numeric($id)){echo $rs_company['id_company'];}else{echo $_SESSION['id_company'];}?>">
													<input name="company_name" type="text" id="company_name" class="company_name" value="<?php if(is_numeric($id)){echo $rs_company['company_name'];}else{echo $_SESSION['company_name'];}?>">
												</td>	
											</tr>
											<tr>
												<td><p class="title">Title<span style="color:red;">*</span></p>
												<input type="text" name="title_call_report" value="<?php if(is_numeric($id)){echo $rs['title_call_report'];}else{echo $_SESSION['title_call_report'];}?>"></td>
												<td></td>
											</tr>
											<tr>
												<td>
												<?php 
												$sql_product="select * from product where id_product='".$rs_call_report['id_product']."'";
												$res_product=mysql_query($sql_product) or die ('Error '.$sql_product);
												$rs_product=mysql_fetch_array($res_product);
												?>
												<p class="title">Project Name<span style="color:red;">*</span></p>
												<input type="hidden" name="id_product" id="id_product" value="<?php if(is_numeric($id)){echo $rs_product['id_product'];}else{echo $_SESSION['id_product'];}?>">
												<input type="text" name="product_name" id="product_name" class="product_name" value="<?php if(is_numeric($id)){echo $rs_product['product_name'];}else{echo $_SESSION['product_name'];}?>"></td>
											</tr>
											<tr>
												<td><p class="title">Sample or cost</p>
													<input type="text" name="sample_cost" style="width: 20%; float: left; margin-right: 2%;" value="<?php if(is_numeric($id)){echo $rs['sample_cost'];}else{echo $_SESSION['sample_cost'];}?>">
													<select name="package" style="width: auto; padding: 0.3%; float: left; margin-right: 2%;">
													<?php
													$sql_sample="select * from type_packaging";
													$res_sample=mysql_query($sql_sample) or die ('Error '.$sql_sample);
													while($rs_sample=mysql_fetch_array($res_sample)){
													?>
														<option value="<?php echo $rs_sample['id_type_package']?>" <?php if(is_numeric($id)){$id_type_package=$rs['id_type_package'];}else{$id_type_package=$_SESSION['package'];} if($rs_sample['id_type_package']==$id_type_package){echo 'selected';}?>><?php echo $rs_sample['title']?></option>
													<?php } ?>
													</select>
													<input type="text" name="sample_baht" style="width: 40%; float: left; margin-right: 2%;" value="<?php if(is_numeric($id)){echo $rs['sample_baht'];}else{echo $_SESSION['sample_baht'];}?>">Baht
												</td>
												<td></td>
											</tr>
											<tr>
												<td><p class="title">Other cost</p>
												<input type="text" name="other_cost" style="width: 40%; float: left; margin-right: 2%;" value="<?php if(is_numeric($id)){echo $rs['other_cost'];}else{echo $_SESSION['other_cost'];}?>">Baht
												</td>
												<td></td>
											<tr>
												<td>
													<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
													<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
													<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
													<script>
														$(function() {
															$( "#datepicker" ).datepicker({
																showOn: "button",
																buttonImage: "img/calendar.gif",
																buttonImageOnly: true
															});
														});
													</script>
													<p class="title">Date of visited<span style="color: red;">*</span></p>
													<?php
													$sql_date="select * from call_report_date where id_call_report='".$rs['id_call_report']."'";
													$sql_date .=" and time_status = '1'";
													$res_date=mysql_query($sql_date) or die ('Error '.$sql_date);
													$rs_date=mysql_fetch_array($res_date);
													?>
													<input type="text" id="datepicker" name="visited_date[]" value="<?php if(is_numeric($id)){echo $rs_date['visited_date'];}else{echo date('m/d/Y');}?>" style="width: 50%; float: left; margin-right: 2%;"/>(Ex. month/day/year)
												</td>
												<td></td>
											</tr>								
											<tr>
												<td><p class="title">Start Time</p>
												H:<select name='h_time[]' style="width: auto;">
												<?php for($i = 1; $i <= 12; $i++): ?>
													<option value="<?= $i; ?>" <?php if($rs_date['s_h_time']==$i){echo 'selected';}?>><?php echo $i?></option>
												<?php endfor; ?>
												</select>
												S:<select name='s_time[]' style="width: auto;">
												<?php for($i = 0; $i <= 59; $i++): ?>
													<option value="<?= $i; ?>" <?php if($rs_date['s_e_time']==$i){echo 'selected';}?>><?php echo $i?></option>
												<?php endfor; ?>
												</select>
												<select name='ap_time[]' style="width: auto;">
													<option value='am' <?php if($rs_date['s_type']=='am'){echo 'selected';}?>>AM</option>
													<option value='pm' <?php if($rs_date['s_type']=='pm'){echo 'selected';}?>>PM</option>
												</select>
												<p class="title">End Time</p>
												H:<select name='h_time2[]' style="width: auto;">
												<?php for($i = 1; $i <= 12; $i++): ?>
													<option value="<?= $i; ?>" <?php if($rs_date['e_h_time']==$i){echo 'selected';}?>><?php echo $i?></option>
												<?php endfor; ?>
												</select>
												S:<select name='s_time2[]' style="width: auto;">
												<?php for($i = 0; $i <= 59; $i++): ?>
													<option value="<?= $i; ?>" <?php if($rs_date['e_e_time']==$i){echo 'selected';}?>><?php echo $i?></option>
												<?php endfor; ?>
												</select>
												<select name='ap_time2[]' style="width: auto;">
													<option value='am' <?php if($rs_date['s_type']=='am'){echo 'selected';}?>>AM</option>
													<option value='pm' <?php if($rs_date['s_type']=='pm'){echo 'selected';}?>>PM</option>
												</select>
												<input type="hidden" name="time_status[]" value="1">
												</td>
												<td></td>
											</tr>
											<tr>
												<td colspan="2" class="b-bottom"><p class="title">Description</p>
												<textarea style="width:50%; height:100px;" name="description[]"><?php echo $rs_date['description']?></textarea></td>
												<td class="b-bottom"></td>
											</tr>
											<tr>
												<td colspan="2"><h4>2.Contact Person</h4></td>
											</tr>								
											<tr>
												<td style="border:1px solid #eee; border-right:none; padding: 0.5%; text-align:center;"><p class="title">Name</p></td>
												<td style="border:1px solid #eee; border-right:none; padding: 0.5%; text-align:center;"><p class="title">Department</p></td>
												<td style="border:1px solid #eee; border-right:none; padding: 0.5%; text-align:center;"><p class="title">Telephone</p></td>
												<td style="border:1px solid #eee; border-right:none; padding: 0.5%; text-align:center;"><p class="title">E-mail</p></td>
												<td style="border:1px solid #eee; border-left:none; padding: 0.5%; text-align:center;"></td>
											</tr> 
											<?php 
											$sql_contact="select * from company_contact inner join call_report_relationship";
											$sql_contact .=" on company_contact.id_contact=call_report_relationship.id_contact";
											$sql_contact .=" and call_report_relationship.id_call_report='".$id ."'";
											$res_contact=mysql_query($sql_contact) or die ('Error '.$sql_contact);
											while($rs_contact=mysql_fetch_array($res_contact)){
											if($rs_contact['id_cr_relation'] == $_GET['id_p'] and $_GET["action"] == 'edit'){ ?>	
											<tr>
												<input type="hidden" name="hdnEdit" value="<?php echo $rs_contact['id_contact']?>">
												<td style="border:1px solid #eee; border-right:none; padding: 0.5%; text-align:center;"><input name="contact_name2" type="text" value="<?php echo $rs_contact['contact_name']?>"></td>
												<td style="border:1px solid #eee; border-right:none; padding: 0.5%; text-align:center;"><input name="department2" type="text" value="<?php echo $rs_contact['department']?>"></td>	
												<td style="border:1px solid #eee; border-right:none; padding: 0.5%; text-align:center;"><input type="text" name="telephone2" value="<?php echo $rs_contact['tel']?>"></td>	
												<td style="border:1px solid #eee; border-right:none; padding: 0.5%; text-align:center;"><input type="text" name="email2" value="<?php echo $rs_contact['email']?>"></td>	
												<td>
													<input name="btnAdd" type="button" id="btnUpdate" value="Update" OnClick="frm.hdnCmd.value='Update';JavaScript:return fncSubmit();" class="btn-update">
													<input name="btnAdd" type="button" id="btnCancel" value="Cancel" OnClick="window.location='<?=$_SERVER["PHP_SELF"]."?id_u=".$id?>';" class="btn-cancel">
												</td>
											</tr>
											<?php }else{?>												
											<tr>
												<td class="contact-edit"><?php echo $rs_contact['contact_name']?></td>
												<td class="contact-edit"><?php echo $rs_contact['department']?></td>	
												<td class="contact-edit"><?php echo $rs_contact['tel']?></td>
												<td class="contact-edit"><?php echo $rs_contact['email']?></td>
												<td class="last-contact-edit">
													<a href="<?=$_SERVER["PHP_SELF"];?>?action=edit&id_p=<?=$rs_contact['id_cr_relation'];?>&id_u=<?php echo $id?>"><img src="img/edit.png" style="width:20px;"></a>
													<a href="JavaScript:if(confirm('Confirm Delete?')==true){window.location='<?=$_SERVER["PHP_SELF"];?>?action=del&id_p=<? echo $rs_contact['id_cr_relation'];?>&id_u=<?php echo $id?>';}"><img src="img/delete.png" style="width:20px;"></a>
												</td>
											</tr>
											<?php } 
											}//end while connet to company contact ?>
											<tr>
												<?php if(is_numeric($id)){$id_company2=$id;}else{$id_company2=0;}?><input type="hidden" name="id_company2" id="id_company2" value="<?php echo $id_company2?>"><input type="hidden" name="id_contact" id="id_contact" >
												<td class="contact-edit"><input type="text" name="contact_name" id="contact_name" class="contact_name" ></td>
												<td class="contact-edit"><input type="text" name="department" id="department"></td>	
												<td class="contact-edit"><input type="text" name="telephone" id="telephone"></td>	
												<td class="contact-edit"><input type="text" name="email" id="email"></td>
												<td class="last-contact-edit"><input name="btnAdd" type="button" id="btnAdd" value="Add" OnClick="frm.hdnCmd.value='Add';JavaScript:return fncSubmit();" class="btn-new2"></td>
											</tr>
											<tr>
												<td colspan="5" class="b-bottom"><p class="title">&nbsp;</p></td>
											</tr>
											<tr>
												<td colspan="2"><h4>3.Work Plan</h4></td>
											</tr>
											<tr>
												<td>
												<?php
													$sql_date2="select * from call_report_date where id_call_report='".$rs['id_call_report']."'";
													$sql_date2 .=" and time_status = '2'";
													$res_date2=mysql_query($sql_date2) or die ('Error '.$sql_date2);
													$rs_date2=mysql_fetch_array($res_date2);
												?>
													<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
													<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
													<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
													<script>
														$(function() {
															$( "#datepicker2" ).datepicker({
																showOn: "button",
																buttonImage: "img/calendar.gif",
																buttonImageOnly: true
															});
														});
													</script>
													<p class="title">Date of next visited</p>
													<?php
													$sql_date="select * from call_report_date where id_call_report='".$rs['id_call_report']."'";
													$sql_date .=" and time_status = '2'";
													$res_date=mysql_query($sql_date) or die ('Error '.$sql_date);
													$rs_date=mysql_fetch_array($res_date);
													?>
													<input type="text" id="datepicker2" name="visited_date[]" value="<?php if(is_numeric($id)){echo $rs_date['visited_date'];}else{echo date('m/d/Y');}?>" style="width: 50%; float: left; margin-right: 2%;"/>(Ex. month/day/year)
												</td>
												<td></td>
											</tr>								
											<tr>
												<td><p class="title">Start Time</p>
												H:<select name='h_time[]' style="width: auto;">
												<?php for($i = 1; $i <= 12; $i++): ?>
													<option value="<?= $i; ?>" <?php if($rs_date2['s_h_time']==$i){echo 'selected';}?>><?php echo $i?></option>
												<?php endfor; ?>
												</select>
												S:<select name='s_time[]' style="width: auto;">
												<?php for($i = 0; $i <= 59; $i++): ?>
													<option value="<?= $i; ?>" <?php if($rs_date2['s_e_time']==$i){echo 'selected';}?>><?php echo $i?></option>
												<?php endfor; ?>
												</select>
												<select name='ap_time[]' style="width: auto;">
													<option value='am' <?php if($rs_date2['s_type']=='am'){echo 'selected';}?>>AM</option>
													<option value='pm' <?php if($rs_date2['s_type']=='pm'){echo 'selected';}?>>PM</option>
												</select>
												<p class="title">End Time</p>
												H:<select name='h_time2[]' style="width: auto;">
												<?php for($i = 1; $i <= 12; $i++): ?>
													<option value="<?= $i; ?>" <?php if($rs_date2['e_h_time']==$i){echo 'selected';}?>><?php echo $i?></option>
												<?php endfor; ?>
												</select>
												S:<select name='s_time2[]' style="width: auto;">
												<?php for($i = 0; $i <= 59; $i++): ?>
													<option value="<?= $i; ?>" <?php if($rs_date2['e_e_time']==$i){echo 'selected';}?>><?php echo $i?></option>
												<?php endfor; ?>
												</select>
												<select name='ap_time2[]' style="width: auto;">
													<option value='am' <?php if($rs_date2['s_type']=='am'){echo 'selected';}?>>AM</option>
													<option value='pm' <?php if($rs_date2['s_type']=='pm'){echo 'selected';}?>>PM</option>
												</select>
												<input type="hidden" name="time_status[]" value="2">
												</td>
												<td></td>
											</tr>
											<tr>
												<td colspan="2"><p class="title">Description</p>
												<textarea style="width:50%; height:100px;" name="description[]"><?php echo $rs_date2['description']?></textarea></td>
												</td>
											</tr>
										</table>
									</div>
									<?php }?><!-- end show date-->
								</div><!-- end div tab-->
							
						</div>
					</td>
				</tr>
				<tr>
					<td class="b-top">
						<div class="large-4 columns">
							<?php if(!is_numeric($id)){?>
							<input type="button" name="save_data" id="save_data" value="<?php echo $button?>" class="button-create" OnClick="frm.hdnCmd.value='save_data';JavaScript:return fncSubmit();">
							<?php }else{?>
							<input type="button" name="update_data" id="update_data" value="<?php echo $button?>" class="button-create" OnClick="frm.hdnCmd.value='update_data';JavaScript:return fncSubmit();">
							<!--<input type="button" name="finished_data" id="finished_data" value="Send mail" class="button-create" OnClick="frm.hdnCmd.value='finished_data';JavaScript:return fncSubmit();">-->
							<?php }?>
							<input type="button" value="Close" class="button-create" onclick="window.location.href='call-report.php'">		
						</div>
					</td>
				</tr>
			</table>
			</form>
		</div>
	</div>

 <!-- <script>
  document.write('<script src=' +
  ('__proto__' in {} ? 'js/vendor/zepto' : 'js/vendor/jquery') +
  '.js><\/script>')
  </script>
  
  <script src="js/foundation.min.js"></script>
  <!--
  
  <script src="js/foundation/foundation.js"></script>
  
  <script src="js/foundation/foundation.alerts.js"></script>
  
  <script src="js/foundation/foundation.clearing.js"></script>
  
  <script src="js/foundation/foundation.cookie.js"></script>
  
  <script src="js/foundation/foundation.dropdown.js"></script>
  
  <script src="js/foundation/foundation.forms.js"></script>
  
  <script src="js/foundation/foundation.joyride.js"></script>
  
  <script src="js/foundation/foundation.magellan.js"></script>
  
  <script src="js/foundation/foundation.orbit.js"></script>
  
  <script src="js/foundation/foundation.reveal.js"></script>
  
  <script src="js/foundation/foundation.section.js"></script>
  
  <script src="js/foundation/foundation.tooltips.js"></script>
  
  <script src="js/foundation/foundation.topbar.js"></script>
  
  <script src="js/foundation/foundation.interchange.js"></script>
  
  <script src="js/foundation/foundation.placeholder.js"></script>
  
  <script src="js/foundation/foundation.abide.js"></script>
  
  -->
  
 <!-- <script>
    $(document).foundation();
  </script>-->
</body>
</html>
