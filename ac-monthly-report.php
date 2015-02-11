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
<script type="text/javascript" src="js/autocomplete.js"></script>
<link rel="stylesheet" href="js/autocomplete.css"  type="text/css"/>
<script language="javascript">
function fncSubmit()
{
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

</head>
<body>
	<?php include("menu.php");?>
	<div class="row">
		<div class="background">
			<?php
			include("connect/connect.php");
			if($_GET["id_u"]=='new'){
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
			?>
			<form method="post" name="frm" action="dbmonthly-report.php">
			<table style="border: 0; width: 100%;" cellpadding="0" cellspacing="0">
				<tr>
					<td class="b-bottom"><div class="large-4 columns"><h4>Monthly Report >> <?php echo $mode;?></h4></div></td>
				</tr>
				<tr>
					<td class="b-bottom">
						<div class="large-4 columns">
							<?php 
							if(!is_numeric($id)){
							?>
							<input type="button" value="<?php echo $button?>" class="button-create" onClick="JavaScript:return fncSubmit();">
							<?php } ?>
							<input type="button" value="Close" class="button-create" onclick="window.location.href='monthly-report.php'">
							<input type="button" value="Delete Row" onclick="deleteRow('dataTable')" class="btn-trash" />
						</div>
					</td>
				</tr>
				<tr>
					<td style="background: #fff;">
						<div class="large-4 columns">
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
							<!-- Tab -->
							<div class="WorldphpTab">
								<ul>
									<?php if(($rs_account['role_user']==1) || ($rs_account['role_user']==2)){?>
									<li class="active"><a href="#tab1">Sales report</a></li>
									<li><a href="#tab2">Sales Forecast</a></li>
									<li><a href="#tab3">Sales performace</a></li>
									<li><a href="#tab4">Market situation</a></li>
									<li><a href="#tab5">Performance analysis</a></li>
									<?php }else{?>
									<li class="active"><a href="#tab3">Sales performace</a></li>
									<li><a href="#tab4">Market situation</a></li>
									<li><a href="#tab5">Performance analysis</a></li>
									<?php }?>
								</ul>
								<div class="WorldphpTabData">
									<?php if(($rs_account['role_user']==1) || ($rs_account['role_user']==2)){?>
									<div id="tab1" class="active">
										<input type="hidden" name="mode" value="<?php echo $id?>">
										<table style="border: 1px solid #eee; width: 100%;" cellpadding="0" cellspacing="0" id="tb-monthly">
											<tr>
												<td class="b-bottom center" colspan="9"><h4>Sales Report Month <?php echo date("F").'&nbsp;'?><?php echo date("Y")?></h4></td>
											</tr>
											<tr>
												<td class="b-bottom center" colspan="9"><h4>Vat</h4></td>
											</tr>
											<tr>
												<td class="bd-right b-bottom"></td>
												<td class="bd-right b-bottom top center">Date</td>
												<td class="bd-right b-bottom top center">Customer</td>
												<td class="bd-right b-bottom top center">PO. No.</td>
												<td class="bd-right b-bottom top center">Product</td>
												<td class="bd-right b-bottom top center">Quantities (Unit)</td>
												<td class="bd-right b-bottom top center">Price per Unit (Baht)</td>
												<td class="bd-right b-bottom top center">Total</td>
												<td class="b-bottom top center">Remark</td>
											</tr>
											<tr>
												<SCRIPT language="javascript">
													function addRow(tableID) {
											 
														var table = document.getElementById(tableID);
											 
														var rowCount = table.rows.length;
														var row = table.insertRow(rowCount);
											 
														var colCount = table.rows[0].cells.length;
											 
														for(var k=0; k<colCount; k++) {
											 
															var newcell = row.insertCell(k);
											 
															newcell.innerHTML = table.rows[0].cells[k].innerHTML;
															//alert(newcell.childNodes);
															switch(newcell.childNodes[0].type) {
																case "text":
																		newcell.childNodes[0].value = "";
																		break;
																case "checkbox":
																		newcell.childNodes[0].checked = false;
																		break;
															}
														}
													}
											 
													function deleteRow(tableID) {
														try {
														var table = document.getElementById(tableID);
														var rowCount = table.rows.length;
											 
														for(var i=0; i<rowCount; i++) {
															var row = table.rows[i];
															var chkbox = row.cells[0].childNodes[0];
															if(null != chkbox && true == chkbox.checked) {
																if(rowCount <= 1) {
																	alert("Cannot delete all the rows.");
																	break;
																}
																table.deleteRow(i);
																rowCount--;
																i--;
															}							 
														}
														}catch(e) {
															alert(e);
														}
													}											 
												</SCRIPT>
												<td colspan="9">
													<table id="dataTable" style="border: none; width: 100%;" cellpadding="0" cellspacing="0">
														<tr>
															<td style="width:1%;"><input type="checkbox" name="chk"/></td>
															<td style="width:25%;">
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
																<input type="text" id="datepicker" name="visited_date[]" value="<?php if(is_numeric($id)){echo $rs_date['visited_date'];}else{echo '';}?>" style="width: 25%; float: left; margin-right: 2%;"/>
															</td>
															<td style="width:25%;">
																<?php 
																$sql_company="select * from company where id_company='".$rs['id_company']."'";
																$res_company=mysql_query($sql_company) or die ('Error '.$sql_company);
																$rs_company=mysql_fetch_array($res_company);
																?>
																<input name="company_name" type="text" id="company_name" style="width:80%;" value="<?php echo $rs_company['company_name'];?>"/>
																<input name="id_company" type="hidden" id="id_company" value="" />
																<script type="text/javascript">
																function make_autocom(autoObj,showObj){
																	var mkAutoObj=autoObj; 
																	var mkSerValObj=showObj; 
																	new Autocomplete(mkAutoObj, function() {
																		this.setValue = function(id) {		
																			document.getElementById(mkSerValObj).value = id;
																		}
																		if ( this.isModified )
																			this.setValue("");
																		if ( this.value.length < 1 && this.isNotClick ) 
																			return ;	
																		return "ac-customer-search.php?q=" +encodeURIComponent(this.value);
																	});	
																}	
																 
																// การใช้งาน
																// make_autocom(" id ของ input ตัวที่ต้องการกำหนด "," id ของ input ตัวที่ต้องการรับค่า");
																make_autocom("company_name","id_company");
																</script>
															</td>
															<td style="width:10%;">
															<input type="text" name="telephone[]" value="<?php echo $rs_contact['tel']?>"></td>
															<td style="width:10%;">
															<input type="text" name="telephone[]" value="<?php echo $rs_contact['tel']?>"></td>
															<td style="width:10%;">
															<input type="text" name="telephone[]" value="<?php echo $rs_contact['tel']?>"></td>
															<td style="width:10%;">
															<input type="text" name="telephone[]" value="<?php echo $rs_contact['tel']?>"></td>
															<td style="width:10%;">
															<input type="text" name="telephone[]" value="<?php echo $rs_contact['tel']?>"></td>
															<td style="width:10%;">
															<input type="text" name="email[]" value="<?php echo $rs_contact['email']?>"></td>
															<td style="width:10%;">
															<input type="button" value="Add Row" onclick="addRow('dataTable')" class="btn-new" />
															</td>
														</tr>
													</table>
												</td>
											</tr>
										</table>
									</div>
									<div id="tab2">แสดงข้อมูลแท็บ   เเขียน CSS</div>
									<div id="tab3">
										<table style="border: 1px solid #eee; width: 100%;" cellpadding="0" cellspacing="0" id="tb-monthly">
											<tr>
												<td><h4>Plan to success more</h4></td>
											</tr>
											<tr>
												<td></td>
											</tr>
										</table>
									</div>
									<div id="tab4">
										<table style="border: 1px solid #eee; width: 100%;" cellpadding="0" cellspacing="0" id="tb-monthly">
											<tr>
												<td class="bd-right b-bottom top center">Month</td>
												<td class="bd-right b-bottom top center"><?php echo date("M-y")?></td>
												<td class="bd-right b-bottom top center">PM Code</td>
												<td class="bd-right b-bottom top center"><?php echo $rs_account['username']?></td>
												<td colspan="4" class="b-bottom top center"></td>
											</tr>
											<tr>
												<td class="bd-right b-bottom top center">Customer</td>
												<td class="bd-right b-bottom top center">Key Decision</td>
												<td class="bd-right b-bottom top center">Issues (+Opportunities/-Challenges)</td>
												<td class="bd-right b-bottom top center">Action Plans/ Discussion</td>
												<td class="bd-right b-bottom top center">Timeline</td>
												<td class="bd-right b-bottom top center">Done</td>
												<td class="b-bottom top center">Remark (results & follow up)</td>
											</tr>
											<tr>
												<td>
													<?php 
													$sql_company="select * from company where id_company='".$rs['id_company']."'";
													$res_company=mysql_query($sql_company) or die ('Error '.$sql_company);
													$rs_company=mysql_fetch_array($res_company);
													?>
													<input name="company_name2" type="text" id="company_name2" size="20" value="<?php echo $rs_company['company_name'];?>"/>
													<input name="id_company2" type="hidden" id="id_company2" value="" />
													<script type="text/javascript">
													function make_autocom(autoObj,showObj){
														var mkAutoObj=autoObj; 
														var mkSerValObj=showObj; 
														new Autocomplete(mkAutoObj, function() {
															this.setValue = function(id) {		
																document.getElementById(mkSerValObj).value = id;
															}
															if ( this.isModified )
																this.setValue("");
															if ( this.value.length < 1 && this.isNotClick ) 
																return ;	
															return "ac-customer-search.php?q=" +encodeURIComponent(this.value);
														});	
													}	
													 
													// การใช้งาน
													// make_autocom(" id ของ input ตัวที่ต้องการกำหนด "," id ของ input ตัวที่ต้องการรับค่า");
													make_autocom("company_name2","id_company2");
													</script>
												</td>
												<td><input type="text"></td>
												<td><textarea cols="50" rows="10"></textarea></td>
												<td><textarea cols="50" rows="10"></textarea></td>
												<td><input type="text"></td>
												<td><input type="text"></td>
												<td><textarea cols="50" rows="10"></textarea></td>
											</tr>
											<tr>
												<td colspan="2">Performance Issues (vs target)</td>
												<td><textarea cols="50" rows="10"></textarea></td>
												<td><textarea cols="50" rows="10"></textarea></td>
												<td><input type="text"></td>
												<td><input type="text"></td>
												<td><textarea cols="50" rows="10"></textarea></td>
											</tr>
											<tr>
												<td colspan="2">Sales objectives (monthly,quarterly,half year)</td>
												<td><textarea cols="50" rows="10"></textarea></td>
												<td><textarea cols="50" rows="10"></textarea></td>
												<td><input type="text"></td>
												<td><input type="text"></td>
												<td><textarea cols="50" rows="10"></textarea></td>
											</tr>
										</table>
									</div>
									<div id="tab5">แสดงข้อมูลแท็บ เขียน jQuery</div>
									<?php }else{?>
									<div id="tab3" class="active">
										<table style="border: 1px solid #eee; width: 100%;" cellpadding="0" cellspacing="0" id="tb-monthly">
											<tr>
												<td><h4><?php echo date("F Y")?></h4></td>
											</tr>
											<tr>
												<td><h4>Plan to success more</h4></td>
											</tr>
											<tr>
												<SCRIPT language="javascript">
													function addRow(tableID) {
												 
														var table = document.getElementById(tableID);
											 
														var rowCount = table.rows.length;
														var row = table.insertRow(rowCount);
												 
														var colCount = table.rows[0].cells.length;
											 
														for(var k=0; k<colCount; k++) {
											 
															var newcell = row.insertCell(k);
												 
															newcell.innerHTML = table.rows[0].cells[k].innerHTML;
															//alert(newcell.childNodes);
															switch(newcell.childNodes[0].type) {
																case "text":
																		newcell.childNodes[0].value = "";
																		break;
																case "textarea":
																		newcell.childNodes[0].value = "";
																		break;
																case "checkbox":
																		newcell.childNodes[0].checked = false;
																		break;
															}
														}
													}
												 
													function deleteRow(tableID) {
														try {
														var table = document.getElementById(tableID);
														var rowCount = table.rows.length;
												
														for(var i=0; i<rowCount; i++) {
															var row = table.rows[i];
															var chkbox = row.cells[0].childNodes[0];
															if(null != chkbox && true == chkbox.checked) {
																if(rowCount <= 1) {
																	alert("Cannot delete all the rows.");
																	break;
																}
																table.deleteRow(i);
																rowCount--;
																i--;
															}							 
														}
														}catch(e) { alert(e); }
													}											 
												</SCRIPT>
												<td>
													<table id="dataTable" style="border: none; width: 20%;" cellpadding="0" cellspacing="0">
														<tr>
															<td>
																<?php 
																$sql_company="select * from company where id_company='".$rs['id_company']."'";
																$res_company=mysql_query($sql_company) or die ('Error '.$sql_company);
																$rs_company=mysql_fetch_array($res_company);
																?>
																<p>Company name</p>
																<input name="company_name[]" type="text" id="company_name[]" size="20" value="<?php echo $rs_company['company_name'];?>"/>
																<input name="id_company[]" type="hidden" id="id_company[]" value="" />
																<script type="text/javascript">
																function make_autocom(autoObj,showObj){
																	var mkAutoObj=autoObj; 
																	var mkSerValObj=showObj; 
																	new Autocomplete(mkAutoObj, function() {
																		this.setValue = function(id) {		
																			document.getElementById(mkSerValObj).value = id;
																		}
																		if ( this.isModified )
																			this.setValue("");
																		if ( this.value.length < 1 && this.isNotClick ) 
																			return ;	
																		return "ac-customer-search.php?q=" +encodeURIComponent(this.value);
																	});	
																}	
																 
																// การใช้งาน
																// make_autocom(" id ของ input ตัวที่ต้องการกำหนด "," id ของ input ตัวที่ต้องการรับค่า");
																make_autocom("company_name[]","id_company[]");
																</script>
																<br>
																<p>Description</p>
																<textarea cols="20" rows="50"></textarea>
															</td>
														</tr>
														<tr>															
															<td><input type="button" value="Add Row" onclick="addRow('dataTable')" class="btn-new" /></td>
														</tr>
														
													</table>
												</td>
											</tr>
										</table>
									</div>
									<div id="tab4">
										<table style="border: 1px solid #eee; width: 100%;" cellpadding="0" cellspacing="0" id="tb-monthly">
											<tr>
												<td class="bd-right b-bottom top center">Month</td>
												<td class="bd-right b-bottom top center"><?php echo date("M-y")?></td>
												<td class="bd-right b-bottom top center">PM Code</td>
												<td class="bd-right b-bottom top center"><?php echo $rs_account['username']?></td>
												<td colspan="4" class="b-bottom top center"></td>
											</tr>
											<tr>
												<td class="bd-right b-bottom top center">Customer</td>
												<td class="bd-right b-bottom top center">Key Decision</td>
												<td class="bd-right b-bottom top center">Issues (+Opportunities/-Challenges)</td>
												<td class="bd-right b-bottom top center">Action Plans/ Discussion</td>
												<td class="bd-right b-bottom top center">Timeline</td>
												<td class="bd-right b-bottom top center">Done</td>
												<td class="b-bottom top center">Remark (results & follow up)</td>
											</tr>
											<tr>
												<td>
													<?php 
													$sql_company="select * from company where id_company='".$rs['id_company']."'";
													$res_company=mysql_query($sql_company) or die ('Error '.$sql_company);
													$rs_company=mysql_fetch_array($res_company);
													?>
													<input name="company_name2" type="text" id="company_name2" size="20" value="<?php echo $rs_company['company_name'];?>"/>
													<input name="id_company2" type="hidden" id="id_company2" value="" />
													<script type="text/javascript">
													function make_autocom(autoObj,showObj){
														var mkAutoObj=autoObj; 
														var mkSerValObj=showObj; 
														new Autocomplete(mkAutoObj, function() {
															this.setValue = function(id) {		
																document.getElementById(mkSerValObj).value = id;
															}
															if ( this.isModified )
																this.setValue("");
															if ( this.value.length < 1 && this.isNotClick ) 
																return ;	
															return "ac-customer-search.php?q=" +encodeURIComponent(this.value);
														});	
													}	
													 
													// การใช้งาน
													// make_autocom(" id ของ input ตัวที่ต้องการกำหนด "," id ของ input ตัวที่ต้องการรับค่า");
													make_autocom("company_name2","id_company2");
													</script>
												</td>
												<td><input type="text"></td>
												<td><textarea cols="50" rows="10"></textarea></td>
												<td><textarea cols="50" rows="10"></textarea></td>
												<td><input type="text"></td>
												<td><input type="text"></td>
												<td><textarea cols="50" rows="10"></textarea></td>
											</tr>
											<tr>
												<td colspan="2">Performance Issues (vs target)</td>
												<td><textarea cols="50" rows="10"></textarea></td>
												<td><textarea cols="50" rows="10"></textarea></td>
												<td><input type="text"></td>
												<td><input type="text"></td>
												<td><textarea cols="50" rows="10"></textarea></td>
											</tr>
											<tr>
												<td colspan="2">Sales objectives (monthly,quarterly,half year)</td>
												<td><textarea cols="50" rows="10"></textarea></td>
												<td><textarea cols="50" rows="10"></textarea></td>
												<td><input type="text"></td>
												<td><input type="text"></td>
												<td><textarea cols="50" rows="10"></textarea></td>
											</tr>
										</table>
									</div>
									<div id="tab5">แสดงข้อมูลแท็บ เขียน jQuery</div>
									<?php }?>
								</div>
							</div>
							
						</div>
					</td>
				</tr>
				<tr>
					<td class="b-top">
						<div class="large-4 columns">
							<input type="button" value="<?php echo $button?>" class="button-create" onClick="JavaScript:return fncSubmit();">
							<input type="button" value="Close" class="button-create" onclick="window.location.href='call-report.php'">
						</div>
					</td>
				</tr>
			</table>
			</form>
		</div>
	</div>

  <script>
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
  
  <script>
    $(document).foundation();
  </script>
</body>
</html>
