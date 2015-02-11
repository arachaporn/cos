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
$date1=date("Y-m-d");
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
	document.frm.submit();
}
</script>
</head>
<body>
	<?php include("menu.php");?>
	<div class="row">
		<div class="background">
			<?php
			include("connect/connect.php");
			if($_REQUEST["date_visited"]){
				$date_visited=$_REQUEST["date_visited"];
				$month=$_REQUEST["month_visited"];
				$mode='Add data on '.$_REQUEST["date_visited"].'/'.$month.'/'.$_REQUEST["year_visited"];
			}
			else
			if($_REQUEST['id_p']){
				$id_itenary=$_REQUEST['id_p'];
				$sql_itenary2="select * from itenary_report where id_itenary='".$id_itenary."'";
				$res_itenary2=mysql_query($sql_itenary2) or die ('Error '.$sql_itenary2);
				$rs_itenary2=mysql_fetch_array($res_itenary2);
				$month=$_REQUEST["month_visited"];
				$mode='Data on '.$rs_itenary2['date_visited'].'&nbsp;'.$month.'&nbsp;'.$rs_itenary2['year_visited'];
			}
			
			//*** Add Condition ***//
			if($_POST["hdnCmd"] == "Add"){
				$date=date('Y-m-d');
				/*select company*/
				$sql_company="select * from company where company_name like '".$_POST['company_name']."'";
				$res_company=mysql_query($sql_company) or die ('Error '.$sql_company);
				$rs_company=mysql_fetch_array($res_company);
				if(!$rs_company){
					$sql_ins_com="insert into company(company_name) values";
					$sql_ins_com .=" ('".$_POST['company_name']."')";
					$res_ins_com=mysql_query($sql_ins_com) or die ('Error '.$sql_ins_com);
					$id_company=mysql_insert_id();
					
					$sql_contact="insert into company_contact(id_company,contact_name) values";
					$sql_contact .=" ('".$id_company."','".$_POST['company_contact']."')";
					$res_contact=mysql_query($sql_contact) or die('Error '.$sql_contact);
					$id_contact=mysql_insert_id();
				}else{ 
					$id_company=$rs_company['id_company'];
					$sql_contact="select * from company_contact where contact_name like '".$_POST['company_contact']."'";
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
						$sql_contact2="insert into company_contact(id_company,contact_name) values";
						$sql_contact2 .=" ('".$id_company."','".$_POST['company_contact']."')";
						$res_contact2=mysql_query($sql_contact2) or die('Error '.$sql_contact2);
						$id_contact=mysql_insert_id();
					}
				}				
				$date1=date('d');
				$date2=$_POST["date_visited"];
				$month1=date('m');
				$month2=$_POST["month_visited"];
				$year1=date('Y');
				$year2=$_POST["year_visited"];
				if($month1==$month2){
					$date_all=$date2-$date1;
					if($date_all>=0){
					$sql="insert into itenary_report(date_visited,month_visited,year_visited,time_visited";
					$sql .=",id_company,id_contact,objective_visiting,create_date,create_by)";
					$sql .=" values('".$_POST["date_visited"]."','".$_POST["month_visited"]."','".$_POST["year_visited"]."'";
					$sql .=",'".$_POST['time_visited']."','".$id_company."','".$id_contact."'";
					$sql .=",'".$_POST['objective_visiting']."','".$date."','".$rs_account['id_account']."')";
					$res = mysql_query($sql) or die ('Error '.$sql);
					}else{?>
						<script>
							window.alert('Over due');
							history.back();
						</script>
				<?php }
				}else{
					$sql="insert into itenary_report(date_visited,month_visited,year_visited,time_visited";
					$sql .=",id_company,id_contact,objective_visiting,create_date,create_by)";
					$sql .=" values('".$_POST["date_visited"]."','".$_POST["month_visited"]."','".$_POST["year_visited"]."'";
					$sql .=",'".$_POST['time_visited']."','".$id_company."','".$_POST['id_contact']."'";
					$sql .=",'".$_POST['objective_visiting']."','".$date."','".$rs_account['id_account']."')";
					$res = mysql_query($sql) or die ('Error '.$sql);
				}
				
			}

			//*** Update Condition ***//
			if($_POST["hdnCmd"] == "Update"){
				
				/*select company*/
				$sql_company="select * from company where company_name like '".$_POST['company_name2']."'";
				$res_company=mysql_query($sql_company) or die ('Error '.$sql_company);
				$rs_company=mysql_fetch_array($res_company);
				if(!$rs_company){
					$sql_ins_com="insert into company(company_name) values";
					$sql_ins_com .=" ('".$_POST['company_name2']."')";
					$res_ins_com=mysql_query($sql_ins_com) or die ('Error '.$sql_ins_com);
					$id_company=mysql_insert_id();

					$sql_contact="insert into company_contact(id_company,contact_name) values";
					$sql_contact .=" ('".$id_company."','".$_POST['company_contact']."')";
					$res_contact=mysql_query($sql_contact) or die('Error '.$sql_contact);
				}else{
					$id_company=$rs_company['id_company'];
					$sql_contact="select * from company_contact where contact_name like '".$_POST['company_contact2']."'";
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
						$sql_contact2="insert into company_contact(id_company,contact_name) values";
						$sql_contact2 .=" ('".$id_company."','".$_POST['company_contact2']."')";
						$res_contact2=mysql_query($sql_contact2) or die('Error '.$sql_contact2);
						$id_contact=mysql_insert_id();
					}
				}
				
				$sql = "update itenary_report set date_visited='".$_POST['date_visited2']."',month_visited= '".$_POST['month_visited2']."'";
				$sql .=",year_visited= '".$_POST['year_visited2']."',time_visited='".$_POST['time_visited2']."'";
				$sql .=",id_company='".$id_company."',id_contact='".$id_contact."',objective_visiting='".$_POST['objective_visiting2']."'";
				$sql .=" where id_itenary = '".$_POST["hdnEdit"]."' ";
				$res = mysql_query($sql) or die ('Error '.$sql);
			}

			//*** Delete Condition ***//
			if($_GET["action"] == "del"){
				$sql = "delete from itenary_report ";
				$sql .="where id_itenary = '".$_GET["id_p"]."'";
				$res = mysql_query($sql) or die ('Error '.$sql);
				//header("location:$_SERVER[PHP_SELF]");
				//exit();
			}?>
			<form name="frm" method="post" action="<?=$_SERVER["PHP_SELF"]."?id_u=".$id?>">
			<input type="hidden" name="hdnCmd" value="">
			<table style="border: 0; width: 100%;" cellpadding="0" cellspacing="0">
				<tr>
					<td class="b-bottom" colspan='5'><div class="large-4 columns"><h4>Itenary Report >> <?php echo $mode?></h4></div></td>
				</tr>
				<tr>
					<td style="background: #fff;">
						<div class="large-4 columns">						
							<input type="hidden" name="mode" value="<?php echo $id?>">
							<?php if($date_visited){?>
							<table style="border: 1px solid #eee; width: 100%;" cellpadding="0" cellspacing="0" id="tb-quotation">
								<tr>
									<!--<td class="bd-right center top b-bottom w10">Date of plan</td>-->
									<td class="bd-right center top b-bottom w10">Time to visited</td>
									<td class="bd-right center top b-bottom w10">Company</td>
									<td class="bd-right center top b-bottom w10">Contact person or department</td>
									<td class="bd-right center top b-bottom w10">Objective visiting</td>
								</tr>
								<?php
									$sql_itenary="select * from itenary_report where create_by='".$rs_account['id_account']."'";
									$sql_itenary .=" and date_visited='".$date_visited."' and month_visited='".$_REQUEST['month_visited']."'";
									$sql_itenary .=" and year_visited='".$_REQUEST['year_visited']."'";
									$res_itenary=mysql_query($sql_itenary) or die('Error '.$sql_itenary);
								
									while($rs_itenary=mysql_fetch_array($res_itenary)){									
									if($rs_itenary['id_itenary'] == $_GET['id_p'] and $_GET["action"] == 'edit'){
								?>								
								<tr>
									<input type="hidden" name="hdnEdit" value="<?php echo $rs_itenary['id_itenary']?>">
									<td class="bd-right b-bottom w10">
										<select name="time_visited2">
											<?php for($i = 1; $i <= 24; $i++): ?>
											<option value="<?= $i; ?>" <?php if($rs_itenary['time_visited']==$i){echo 'selected';}?>><?php echo $i.'.00'?></option>
											<?php endfor; ?>
										</select>
									</td>
									<script type="text/javascript" src="js/js-autocomplete/js/jquery-1.4.2.min.js"></script> 
									<script type="text/javascript" src="js/js-autocomplete/js/jquery-ui-1.8.2.custom.min.js"></script> 
									<script type="text/javascript"> 
										jQuery(document).ready(function(){
											$('.company_name2').autocomplete({
												source:'return-customer.php', 
												//minLength:2,
												select:function(evt, ui){
													this.form.id_company2.value = ui.item.id_company2;
													this.form.company_contact2.value = ui.item.company_contact2;
												}
											});
											$('.company_contact2').autocomplete({
												source:'return-contact.php', 
												//minLength:2,
												select:function(evt, ui){
													this.form.id_contact.value = ui.item.id_contact;
												}
											});
										});
									</script> 
									<link rel="stylesheet" href="js/js-autocomplete/css/smoothness/jquery-ui-1.8.2.custom.css" /> 
									<input type="hidden" name="date_visited2" value="<?php echo $rs_itenary["date_visited"]?>">
									<input type="hidden" name="month_visited2" value="<?php echo $rs_itenary["month_visited"]?>">
									<input type="hidden" name="year_visited2" value="<?php echo $rs_itenary["year_visited"]?>">
									<td class="bd-right b-bottom w20">																				
										<?php 
										$sql_company="select * from company where id_company='".$rs_itenary['id_company']."'";
										$res_company=mysql_query($sql_company) or die ('Error '.$sql_company);
										$rs_company=mysql_fetch_array($res_company);
										?>
										<input type="hidden" id="id_company2" name="id_company2" value="<?php echo $rs_company['id_company']?>">
										<input name="company_name2" type="text" id="company_name2" class="company_name2" value="<?php echo $rs_company['company_name'];?>"/>
									</td>	
									<td class="bd-right b-bottom w20">									
										<?php 
										$sql_contact="select * from company_contact where id_company='".$rs_company['id_company']."'";
										$sql_contact .=" and id_contact='".$rs_itenary['id_contact']."'";
										$res_contact=mysql_query($sql_contact) or die ('Error '.$sql_contact);
										$rs_contact=mysql_fetch_array($res_contact);
										?>
										<input type="hidden" id="id_company2" name="id_company2" value="<?php echo $rs_contact['id_contact']?>">
										<input type="text" name="company_contact2" id="company_contact2" class="company_contact2" value="<?php echo $rs_contact['contact_name']?>"></div>
									</td>	
									<td class="bd-right b-bottom w30">
										<div style="float:left;"><textarea cols="50" rows="20" name="objective_visiting2"><?php echo $rs_itenary['objective_visiting']?></textarea></div>
										<input name="btnAdd" type="button" id="btnUpdate" value="Update" OnClick="frm.hdnCmd.value='Update';JavaScript:return fncSubmit();" class="btn-update">
										<input name="btnAdd" type="button" id="btnCancel" value="Cancel" OnClick="window.location='<?=$_SERVER["PHP_SELF"].'?date_visited='.$rs_itenary['date_visited'].'&month_visited='.$rs_itenary['month_visited'].'&year_visited='.$rs_itenary['year_visited']?>';" class="btn-cancel">
									</td>
								</tr>
								<?php }else{?>
								<input type="hidden" name="date_visited" value="<?php echo $_REQUEST["date_visited"]?>">
								<input type="hidden" name="month_visited" value="<?php echo $_REQUEST["month_visited"]?>">
								<input type="hidden" name="year_visited" value="<?php echo $_REQUEST["year_visited"]?>">
								<tr>
									<td class="bd-right center b-bottom w10"><?php echo $rs_itenary['time_visited'].'.00'?></td>
									<td class="bd-right b-bottom w15">
										<?php 
										$sql_company2="select * from company where id_company='".$rs_itenary['id_company']."'";
										$res_company2=mysql_query($sql_company2) or die ('Error '.$sql_company2);
										$rs_company2=mysql_fetch_array($res_company2);
										echo $rs_company2['company_name'];
										?>
									</td>	
									<td class="bd-right b-bottom w20">
										<?php 										
										$sql_contact2="select * from company_contact where id_company='".$rs_company2['id_company']."'";
										$sql_contact2 .=" and id_contact='".$rs_itenary['id_contact']."'";
										$res_contact2=mysql_query($sql_contact2) or die ('Error '.$sql_contact2);
										$rs_contact2=mysql_fetch_array($res_contact2);
										echo $rs_contact2['contact_name'];
										?>
									</td>	
									<td class="b-bottom w30">
										<div style="float:left;"><?php echo $rs_itenary['objective_visiting']?></div>
										<a href="<?=$_SERVER["PHP_SELF"];?>?action=edit&id_p=<?=$rs_itenary['id_itenary'].'&date_visited='.$rs_itenary['date_visited'].'&month_visited='.$rs_itenary['month_visited'].'&year_visited='.$rs_itenary['year_visited'];?>"><img src="img/edit.png" style="width:20px;"></a>
										<a href="JavaScript:if(confirm('Confirm Delete?')==true){window.location='<?=$_SERVER["PHP_SELF"];?>?action=del&id_p=<? echo $rs_itenary['id_itenary'].'&date_visited='.$rs_itenary['date_visited'].'&month_visited='.$rs_itenary['month_visited'].'&year_visited='.$rs_itenary['year_visited'];?>';}"><img src="img/delete.png" style="width:20px;"></a>
									</td>
								</tr>
								<?php }  
									}//end while itenary
								?>
								<script type="text/javascript" src="js/js-autocomplete/js/jquery-1.4.2.min.js"></script> 
								<script type="text/javascript" src="js/js-autocomplete/js/jquery-ui-1.8.2.custom.min.js"></script> 
								<script type="text/javascript"> 
									jQuery(document).ready(function(){
										$('.company_name').autocomplete({
											source:'return-customer.php', 
											//minLength:2,
											select:function(evt, ui){
												this.form.id_company.value = ui.item.id_company;
												this.form.company_contact.value = ui.item.company_contact;
												this.form.id_contact.value = ui.item.id_contact;
											}
										});
										$('.company_contact').autocomplete({
											source:'return-contact.php', 
											//minLength:2,
											select:function(evt, ui){
												this.form.id_contact.value = ui.item.id_contact;
											}
										});
									});
								</script> 
								<link rel="stylesheet" href="js/js-autocomplete/css/smoothness/jquery-ui-1.8.2.custom.css" /> 
								<tr>
									<input type="hidden" name="date_visited" value="<?php echo $_REQUEST["date_visited"]?>">
									<input type="hidden" name="month_visited" value="<?php echo $_REQUEST["month_visited"]?>">
									<input type="hidden" name="year_visited" value="<?php echo $_REQUEST["year_visited"]?>">
									<td class="bd-right b-bottom top w10">
										<select name="time_visited">
											<?php for($i = 1; $i <= 24; $i++): ?>
											<option value="<?= $i; ?>" <?php if($i==9){echo 'selected';}?>><?php echo $i.'.00'?></option>
											<?php endfor; ?>
										</select>
									</td>
									<td class="bd-right b-bottom top w15">
										<input type="hidden" name="id_company" id="id_company">
										<input name="company_name" type="text" id="company_name" class="company_name">
									</td>	
									<td class="bd-right b-bottom top w20">
										<?php 
										$sql_contact="select * from company_contact where id_contact='".$rs_itenary['id_contact']."'";
										$res_contact=mysql_query($sql_contact) or die ('Error '.$sql_contact);
										$rs_contact=mysql_fetch_array($res_contact);
										?>
										<input type="hidden" name="id_contact" id="id_contact" value="<?php echo $rs_contact['id_contact']?>">
										<input type="text" name="company_contact" id="company_contact" class="company_contact" value="<?php echo $rs_contact['contact_name']?>"></div>
									</td>	
									<td class="b-bottom w30"><textarea cols="70" rows="20" name="objective_visiting"></textarea></td>
								</tr>
								<tr>
									<td class="bd-right center top" colspan="4"><input name="btnAdd" type="button" id="btnAdd" title="Add" value="Add"  OnClick="frm.hdnCmd.value='Add';JavaScript:return fncSubmit();" class="btn-new2"></td>
									</td>
								</tr>
							</table>
							<?php }//end date visited
							else{?>
							<table style="border: 1px solid #eee; border-bottom:none; width: 100%;" cellpadding="0" cellspacing="0" id="tb-quotation">
								<tr>
									<td class="bd-right center top b-bottom w10">Date of plan</td>
									<td class="bd-right center top b-bottom w10">Time to visited</td>
									<td class="bd-right center top b-bottom w10">Company</td>
									<td class="bd-right center top b-bottom w10">Contact person or department</td>
									<td class="bd-right center top b-bottom w10">Objective visiting</td>
								</tr>
								<?php
									$sql_itenary2="select * from itenary_report where id_itenary='".$id_itenary."'";
									$res_itenary2=mysql_query($sql_itenary2) or die ('Error '.$sql_itenary2);											
									while($rs_itenary2=mysql_fetch_array($res_itenary2)){
										//$month=date('F',strtotime($rs_itenary2['month_visited']));
								?>
								<tr>
									<td class="b-bottom bd-right"><?php echo $rs_itenary2['date_visited'].'/'.$rs_itenary2['month_visited'].'/'.$rs_itenary2['year_visited']?></td>
									<td class="b-bottom bd-right"><?php echo $rs_itenary2['time_visited']?></td>
									<td class="b-bottom bd-right">
										<?php 
											$sql_company="select * from company where id_company='".$rs_itenary2['id_company']."'";
											$res_company=mysql_query($sql_company) or die ('Error '.$sql_company);
											$rs_company=mysql_fetch_array($res_company);
											echo $rs_company['company_name'];
										?>
									</td>
									<td class="b-bottom bd-right">
										<?php 
											$sql_com_contact="select * from company_contact where id_contact='".$rs_itenary2['id_contact']."'";
											$res_com_contact=mysql_query($sql_com_contact) or die ('Error '.$sql_com_contact);
											$rs_com_contact=mysql_fetch_array($res_com_contact);
											echo $rs_com_contact['contact_name'];
										?>
									</td>
									<td class="b-bottom bd-right"><?php echo $rs_itenary2['objective_visiting']?></td>
								</tr>
							<?php } //end while ?>
							</table>
							<?php }?>
						</div>
					</td>
				</tr>
			</table>
			</form>
		</div>
	</div>

  <!--<script>
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
  
  <!--<script>
    $(document).foundation();
  </script>-->
</body>
</html>
