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
	document.frm.submit();
}
</script>
<script type="text/javascript" src="js/jquery/js/jquery-1.4.2.min.js"></script> 
<script type="text/javascript" src="js/jquery/js/jquery-ui-1.8.2.custom.min.js"></script> 
<script type="text/javascript"> 
	jQuery(document).ready(function(){
		$('.company_name').autocomplete({
			source:'return-customer.php', 
			//minLength:2,
			select:function(evt, ui){
				this.form.id_company.value = ui.item.id_company;
				this.form.company_contact.value = ui.item.company_contact;
			}
		});
		$('.company_name2').autocomplete({
			source:'return-customer.php', 
			//minLength:2,
			select:function(evt, ui){
				this.form.id_company2.value = ui.item.id_company2;
				this.form.company_contact2.value = ui.item.company_contact2;
			}
		});
	});
</script> 
<link rel="stylesheet" href="js/jquery/css/smoothness/jquery-ui-1.8.2.custom.css" /> 
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
				$sql="select * from company where id_company='".$id."'";
				$res=mysql_query($sql) or die ('Error '.$sql);
				$rs=mysql_fetch_array($res);
				$mode='Edit '.$rs['company_name'];
				$button='Update';
			}

			//*** Add Condition ***//
			if($_POST["hdnCmd"] == "Add"){
				$date=date('Y-m-d');
				
				$sql_com_contact="insert into company_contact(id_company,contact_name,department,tel,email)";
				$sql_com_contact .=" values ('".$_POST['id_company']."','".$_POST['contact_name']."'";
				$sql_com_contact .=",'".$_POST['department']."','".$_POST['telephone']."','".$_POST['email']."')";
				$res_com_contact=mysql_query($sql_com_contact) or die ('Error '.$sql_com_contact);
				
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
				//header("location:$_SERVER[PHP_SELF]");
				//exit();
			}

			//*** Delete Condition ***//
			if($_GET["action"] == "del"){
				$sql = "delete from company_contact ";
				$sql .="where id_contact = '".$_GET["id_p"]."'";
				$res = mysql_query($sql) or die ('Error '.$sql);
				//header("location:$_SERVER[PHP_SELF]");
				//exit();
			}
			
			if($_POST["hdnCmd"] == "save"){

				$sql="insert into company(company_code,company_name,company_tel";
				$sql .=",company_fax,company_website,enroll,id_com_cat,create_by,create_date)";
				$sql .=" values('".$_POST['company_code']."','".$_POST['company_name']."'";
				$sql .=",'".$_POST['company_tel']."'";
				$sql .=",'".$_POST['company_fax']."','".$_POST['company_website']."'";
				$sql .=",'".$_POST['enroll']."','".$_POST['company_cat']."'";
				$sql .=",'".$_POST['project_manager']."','".$date."')";
				$res=mysql_query($sql) or die ('Error '.$sql);
				
				$id_company=mysql_insert_id();
				
				$sql_address="insert into company_address(address_no,road,sub_district";
				$sql_address .=",district,province,postal_code)";
				$sql_address .=" values('".$_POST['address_no']."','".$_POST['road']."'";
				$sql_address .=",'".$_POST['sub_district']."','".$_POST['district']."'";
				$sql_address .=",'".$_POST['province']."','".$_POST['postal_code']."')";
				$res_address=mysql_query($sql_address) or die ('Error '.$sql_address);

				$id_address=mysql_insert_id();

				$sql_com_contact="insert into company_contact(id_company,contact_name,department,tel,email)";
				$sql_com_contact .=" values ('".$id_company."','".$_POST['contact_name']."'";
				$sql_com_contact .=",'".$_POST['department']."','".$_POST['telephone']."','".$_POST['email']."')";
				$res_com_contact=mysql_query($sql_com_contact) or die ('Error '.$sql_com_contact);

				$sql_company="update company set id_address='".$id_address."' where id_address='0'";
				$res_company=mysql_query($sql_company) or die ('Error '.$sql_company);

				$sql_company_contact="update company_contact set id_company='".$id_company."' where id_company='0'";
				$res_company_contact=mysql_query($sql_company_contact) or die ('Error '.$sql_company_contact);
			?>
				<script>
					window.location.href='customer.php';
				</script>
			<?php }
			if($_POST["hdnCmd"] == "update_data"){
				$sql="update company set company_code='".$_POST['company_code']."'";
				$sql .=",company_name='".$_POST['company_name']."',company_tel='".$_POST['company_tel']."'";
				$sql .=",company_fax='".$_POST['company_fax']."',id_com_cat='".$_POST['company_cat']."'";
				$sql .=",modify_date='".$modify."',create_by='".$_POST['project_manager']."'";
				$sql .=" where id_company='".$_POST['mode']."'";
				$res=mysql_query($sql) or die ('Error '.$sql);

				$sql_address="update company_address set address_no='".$_POST['address_no']."'";
				$sql_address .=",road='".$_POST['road']."',sub_district='".$_POST['sub_district']."'";
				$sql_address .=",district='".$_POST['district']."',province='".$_POST['province']."'";
				$sql_address .=",postal_code='".$_POST['postal_code']."' where id_address='".$_POST['id_address']."'";
				$res_address=mysql_query($sql_address) or die ('Error '.$sql_address);
			?>
				<script>
					window.location.href='customer.php';
				</script>
			<?php }?>
			<form name="frm" method="post" action="<?=$_SERVER["PHP_SELF"]."?id_u=".$id?>">
			<input type="hidden" name="hdnCmd" value="">
			<table style="border: 0; width: 100%;" cellpadding="0" cellspacing="0">
				<tr>
					<td class="b-bottom"><div class="large-4 columns"><h4><h4>Customer >> <?php echo $mode;?></h4></h4></div></td>
				</tr>
				<tr>
					<td class="b-bottom">
						<div class="large-4 columns">
							<?php 
							if(!is_numeric($id)){
							?>
							<input type="button" name="save_data" id="save_data" value="<?php echo $button?>" class="button-create" OnClick="frm.hdnCmd.value='save';JavaScript:return fncSubmit();">
							<?php }else{ ?>
							<input type="button" name="update_data" value="<?php echo $button?>" class="button-create" OnClick="frm.hdnCmd.value='update_data';JavaScript:return fncSubmit();">
							<?php }?>
							<input type="button" value="Close" class="button-create" onclick="window.location.href='customer.php'">
						</div>
					</td>
				</tr>
				<tr>
					<td style="background: #fff;">
						<div class="large-4 columns">						
							<input type="hidden" name="mode" value="<?php echo $id?>">
							<table style="border: none; width: 100%;" cellpadding="0" cellspacing="0" id="tb-add">
								<tr>
									<td colspan="4"><h4>1.Information</h4></td>
								</tr>
								<tr>
									<td width="20%"><p class="title">Company Code</p>
									<input type="text" name="company_code"  value="<?php echo $rs['company_code']?>"></td>
									</td>
									<td colspan="2"><p class="title">Company Name</p>
									<input type="text" name="company_name" value="<?php echo $rs['company_name']?>" style="width:38%;"></td>
								</tr>
								<tr>
									<?php if(($rs_account['role_user']==1) && ($rs_account['id_department'] !=1)){?>
									<td><p class="title">Project Manager</p>
									<select name="project_manager" style="widht:auto;">
									<?php
									$sql_account2="select * from account where id_department='1'";
									$res_account2=mysql_query($sql_account2) or die ('Error '.$sql_account2);
									while($rs_account2=mysql_fetch_array($res_account2)){
									?>
										<option <?php if($rs['create_by']==$rs_account2['id_account']){echo 'selected';}?> value="<?php echo $rs_account2['id_account']?>"><?php echo $rs_account2['username']?></option>
									<?php }	?>
									</select>
									<?php }else{ ?>
									<input type="hidden" name="project_manager" value="<?php echo $rs_account['id_account']?>">
									<?php } ?>
									</td>
								</tr>
								<tr>
									<td class="b-bottom"><p class="title">Company Category</p>
										<select name="company_cat" style="width: auto; padding: 0.3%;">
											<option value="0">Select Company Category</optiion>
											<?
											$sql_company_cat="select * from company_category";
											$res_company_cat=mysql_query($sql_company_cat) or die ('Error '.$sql_company_cat);
											while($rs_company_cat=mysql_fetch_array($res_company_cat)){
											?>
											<option value="<?php echo $rs_company_cat['id_com_cat']?>" <?php if($rs_company_cat['id_com_cat']==$rs['id_com_cat']){echo 'selected';}?>><?php echo $rs_company_cat['title']?></option>
											<?php } ?>
										</select>
									</td>
									<td class="b-bottom" colspan="2"><p class="title">Enroll</p>
									<input type="text" name="enroll" value="<?php echo $rs['enroll']?>" style="width:38%;">
									</td>
									<td class="b-bottom" colspan="2"></td>
								</tr>
								<tr>
									<td colspan="2"><h4>2.Address</h4></td>
								</tr>
									<?php
									$sql_com_address="select * from company_address where id_address='".$rs['id_address']."'";
									$res_com_address=mysql_query($sql_com_address) or die ('Error '.$sql_com_address);
									$rs_com_address=mysql_fetch_array($res_com_address)
									?>
								<tr>
									<input type="hidden" name="id_address" value="<?php echo $rs_com_address['id_address']?>">
									<td><p class="title">Address</p>
									<input type="text" name="address_no" value="<?php echo $rs_com_address['address_no']?>"></td>
									<td colspan="2"><p class="title">Road</p>
									<input type="text" name="road" value="<?php echo $rs_com_address['road']?>" style="width:38%;"></td>
									</td>
								</tr>
								<tr>
									<td><p class="title">Sub-District</p>
									<input type="text" name="sub_district" value="<?php echo $rs_com_address['sub_district']?>"></td>
									</td>
									<td colspan="2"><p class="title">District</p>
									<input type="text" name="district" value="<?php echo $rs_com_address['district']?>" style="width:38%;"></td>
								</tr>
								<tr>
									<td><p class="title">Province</p>
									<input type="text" name="province" value="<?php echo $rs_com_address['province']?>"></td>
									</td>
									<td colspan="2"><p class="title">Postal Code</p>
									<input type="text" name="postal_code" value="<?php echo $rs_com_address['postal_code']?>" style="width:38%;"></td>
								</tr>
								<tr>
									<td><p class="title">Telephone</p>
									<input type="text" name="company_tel" value="<?php echo $rs['company_tel']?>"></td>
									</td>
									<td colspan="2"><p class="title">Fax</p>
									<input type="text" name="company_fax" value="<?php echo $rs['company_fax']?>" style="width:38%;"></td>
								</tr>
								<tr>
									<td colspan="2" class="b-bottom"><p class="title">Website</p>
									<input type="text" name="company_website" value="<?php echo $rs['company_website']?>" style="width:38%;"></td>
									<td class="b-bottom" colspan="2"></td>
								</tr>
								<tr>
									<td colspan="2"><h4>3.Contact Person</h4></td>
								</tr>
								<tr>
									<td style="border:1px solid #eee; border-right:none; padding: 0.5%; text-align:center;"><p class="title">Name</p></td>
									<td style="border:1px solid #eee; border-right:none; padding: 0.5%; text-align:center;"><p class="title">Department</p></td>
									<td style="border:1px solid #eee; border-right:none; padding: 0.5%; text-align:center;"><p class="title">Telephone</p></td>
									<td style="border:1px solid #eee; border-right:none; padding: 0.5%; text-align:center;"><p class="title">E-mail</p></td>
									<td style="border:1px solid #eee; border-left:none; padding: 0.5%; text-align:center;"></td>
								</tr> 
								<?php 
								if(is_numeric($id)){$where=" where id_company='".$rs['id_company']."'";}else{$where=" where id_company='0'";}
								$sql_contact="select * from company_contact";
								$sql_contact .=$where;
								$res_contact=mysql_query($sql_contact) or die ('Error '.$sql_contact);
								while($rs_contact=mysql_fetch_array($res_contact)){
								if($rs_contact['id_contact'] == $_GET['id_p'] and $_GET["action"] == 'edit'){ ?>	
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
										<a href="<?=$_SERVER["PHP_SELF"];?>?action=edit&id_p=<?=$rs_contact['id_contact'];?>&id_u=<?php echo $id?>"><img src="img/edit.png" style="width:20px;"></a>
										<a href="JavaScript:if(confirm('Confirm Delete?')==true){window.location='<?=$_SERVER["PHP_SELF"];?>?action=del&id_p=<? echo $rs_contact['id_contact'];?>&id_u=<?php echo $id?>';}"><img src="img/delete.png" style="width:20px;"></a>
									</td>
								</tr>
								<?php } 
								}//end while connet to company contact ?>
								<tr>
									<?php if(is_numeric($id)){$id_company=$id;}else{$id_company=0;}?><input type="hidden" name="id_company" value="<?php echo $id_company?>">
									<td class="contact-edit"><input name="contact_name" type="text"></td>
									<td class="contact-edit"><input name="department" type="text"></td>	
									<td class="contact-edit"><input type="text" name="telephone"></td>	
									<td class="contact-edit"><input type="text" name="email" id="email"></td>
									<td class="last-contact-edit"><input name="btnAdd" type="button" id="btnAdd" value="Add" OnClick="frm.hdnCmd.value='Add';JavaScript:return fncSubmit();" class="btn-new2"></td>
								</tr>
								<tr>
									<td colspan="5" class="b-bottom"><p class="title">&nbsp;</p></td>
								</tr>
								<tr>
									<td colspan="5"><h4>4.Other</h4></td>
								</tr>
								<tr>
									<td colspan="5"><p class="title">Description</p>
									<textarea style="width:50%; height:100px;" name="information"><?php echo $rs['information']?></textarea></td>
									</td>
								</tr>
							</table>
						</div>
					</td>
				</tr>
				<tr>
					<td class="b-top">
						<div class="large-4 columns">
							<?php 
							if(!is_numeric($id)){
							?>
							<input type="button" name="save_data" id="save_data" value="<?php echo $button?>" class="button-create" OnClick="frm.hdnCmd.value='save';JavaScript:return fncSubmit();">
							<?php }else{ ?>
							<input type="button" name="update_data" value="<?php echo $button?>" class="button-create" OnClick="frm.hdnCmd.value='update_data';JavaScript:return fncSubmit();">
							<?php }?>
							<input type="button" value="Close" class="button-create" onclick="window.location.href='customer.php'">
						</div>
					</td>
				</tr>
			</table>
			</form>
		</div>
	</div>

<!--  <script>
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
  
  
  <script>
    $(document).foundation();
  </script>-->
</body>
</html>
