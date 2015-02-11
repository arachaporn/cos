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
<!--[if IE 8]><html class="no-js lt-ie9" lang="en" > <![endif]-->
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
<script src="ckeditor/ckeditor.js" type="text/javascript"></script> 
<script language="javascript">
function fncSubmit(){
	document.frm.submit();
}
</script> 
<!--<script type="text/javascript" src="js/js-autocomplete/js/jquery-1.4.2.min.js"></script>--> 
<script type="text/javascript" src="js/js-autocomplete/js/jquery-ui-1.8.2.custom.min.js"></script> 
<script type="text/javascript"> 
	jQuery(document).ready(function(){
		$('.meeting_name').autocomplete({
			source:'return-meeting.php', 
			//minLength:2,
			select:function(evt, ui){
				this.form.id_meeting.value = ui.item.id_meeting;
				this.form.meeting_time.value = ui.item.meeting_time;
			}
		});
		$('.employee_name').autocomplete({
			source:'return-user.php', 
			//minLength:2,
			select:function(evt, ui){
				this.form.id_account.value = ui.item.id_account;
				this.form.position.value = ui.item.position;
				this.form.email.value = ui.item.email;
			}
		});
		$('.writen_by').autocomplete({
			source:'return-user.php', 
			//minLength:2,
			select:function(evt, ui){
				this.form.id_writen.value = ui.item.id_writen;
				//this.form.department.value = ui.item.department;
			}
		});
		$('.action_by').autocomplete({
			source:'return-user.php', 
			//minLength:2,
			select:function(evt, ui){
				this.form.id_account2.value = ui.item.id_account2;
				//this.form.department.value = ui.item.department;
			}
		});
		$('.action_by2').autocomplete({
			source:'return-user.php', 
			//minLength:2,
			select:function(evt, ui){
				this.form.id_account3.value = ui.item.id_account3;
				//this.form.department.value = ui.item.department;
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
			if($_GET["id_u"]=='New'){
				$mode='New';
				$button='Save';
				$id='New';

				$_SESSION['meeting_name']=$_REQUEST['meeting_name'];
				$_SESSION['id_writen']=$_REQUEST['id_writen'];
				$_SESSION['writen_by']=$_REQUEST['writen_by'];
				$_SESSION['meeting_time']=$_REQUEST['meeting_time'];
				$_SESSION['date_meeting']=$_REQUEST['date_meeting'];
				$_SESSION['start_meeting']=$_REQUEST['start_meeting'];
				$_SESSION['end_meeting']=$_REQUEST['end_meeting'];
				$_SESSION['location_meeting']=$_REQUEST['location_meeting'];
				$_SESSION['purpose_meeting']=$_REQUEST['purpose_meeting'];
				$_SESSION['next_meeting']=$_REQUEST['next_meeting'];
				$_SESSION['date_next_meeting']=$_REQUEST['date_next_meeting'];
				$_SESSION['meeting_info']=$_REQUEST['meeting_info'];

			}
			else{
				if(($rs_account['role_user']==1) || ($rs_account['role_user']==2)){$and =" ";}
				else{$and=" and create_by='".$rs_account['id_account']."'";}
				$id=$_GET["id_u"];
				$sql="select * from meeting where id_meeting='".$id."'";
				$sql .=$and;
				$res=mysql_query($sql) or die ('Error '.$sql);
				$rs=mysql_fetch_array($res);
				$mode='Edit '.$rs['meeting_name'];
				$button='Update';

				$_SESSION['meeting_name']=$rs['meeting_name'];
				/*select account writen*/
				$sql_acc="select * from account where id_account='".$rs['writen_by']."'";
				$res_acc=mysql_query($sql_acc) or die ('Error '.$sql_acc);
				$rs_acc=mysql_fetch_array($res_acc);				
				/*end*/
				$_SESSION['id_writen']=$rs_acc['id_account'];
				$_SESSION['writen_by']=$rs_acc['name'];
				$_SESSION['meeting_time']=$rs['meeting_time'];
				list($meeting_m,$meeting_d, $meeting_y) = split('[/.-]', $rs['date_meeting']); 
				$date_meeting= $meeting_d."/".$meeting_y."/".$meeting_m;
				$_SESSION['date_meeting']=$date_meeting;
				$_SESSION['start_meeting']=$rs['start_meeting'];
				$_SESSION['end_meeting']=$rs['end_meeting'];
				$_SESSION['location_meeting']=$rs['location_meeting'];
				$_SESSION['purpose_meeting']=$rs['purpose_meeting'];
				$_SESSION['next_meeting']=$rs['next_meeting'];
				if($rs['next_meeting']=="Y"){
					list($meeting_next_m,$meeting_next_d, $meeting_next_y) = split('[/.-]', $rs['date_next_meeting']); 
					$date_next_meeting= $meeting_next_d."/".$meeting_next_y."/".$meeting_next_m;
					$_SESSION['date_next_meeting']=$date_next_meeting;
				}
			}//end numeric id

			if($_GET["action"] == "del_attendee"){
				$sql = "delete from meeting_attendee ";
				$sql .="where id_attendee = '".$_GET["id_p"]."'";
				$res = mysql_query($sql) or die ('Error '.$sql);
			}//end delete attendee
		?>
			<form name="frm" method="post" action="autosave-meeting.php">				
				<input type="hidden" name="hdnCmd" value="">
				<table style="border: 0; width: 100%;" cellpadding="0" cellspacing="0">
					<tr>
						<td class="b-bottom"><div class="large-4 columns"><h4>Minutes of Meeting >> <?php echo $mode;?></h4></div></td>
					</tr>
					<tr>
						<td class="b-bottom">
							<div class="large-4 columns">
								<?php if(!is_numeric($id)){?>
								<input type="button" name="save_data" id="save_data" value="<?php echo $button?>" class="button-create" OnClick="frm.hdnCmd.value='save_data';JavaScript:return fncSubmit();">
								<?php }else{?>
								<!--<input type="button" name="update_data" id="update_data" value="<?php echo $button?>" class="button-create" OnClick="frm.hdnCmd.value='update_data';JavaScript:return fncSubmit();">-->
								<input type="button" name="finished_data" id="finished_data" value="Send mail" class="button-create" OnClick="frm.hdnCmd.value='finished_data';JavaScript:return fncSubmit();">
								<?php }?>
								<input type="button" value="Close" class="button-create" onclick="window.location.href='meeting.php'">
							</div>
						</td>
					</tr>
					<tr>
						<td style="background: #fff;" colspan="5"><div id="message"><p></p></div></td>
					</tr>
					<tr>
					<td style="background: #fff;">
						<div class="large-4 columns">						
							<input type="hidden" name="mode" value="<?php echo $id?>">
							<table style="border: none; width: 100%;" cellpadding="0" cellspacing="0" id="tb-meeting">
								<tr>
									<td class="top" colspan="2">Meeting name</td>
									<td class="top">Written by</td>
									<td class="top">Meeting times</td>
								</tr>
								<tr>
									<td colspan="2">
										<input type="hidden" name="id_meeting" id="id_meeting" value="<?php echo $id?>">
										<input type="text" name="meeting_name" id="meeting_name" class="meeting_name" value="<?php echo $_SESSION['meeting_name']?>"></td>
									<td>
									<input type="hidden" name="id_writen" id="id_writen" value="<?php echo $_SESSION['id_writen']?>">
									<input type="text" name="writen_by" id="writen_by" class="writen_by" value="<?php echo $_SESSION['writen_by']?>"></td>	
									<td><input type="text" name="meeting_time" id="meeting_time" value="<?php echo $_SESSION['meeting_time']?>" readonly></td>
								</tr>
								<tr>
									<td class="top">Date of meeting</td>
									<td class="top">Start of meeting</td>
									<td class="top">End of meeting</td>
									<td class="top">Location of meeting</td>
								</tr>
								<tr>
									<td class="top">
										<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
										<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
										<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
										<script>
											$(function() {
												$( "#date_meeting" ).datepicker({
													showOn: "button",
													buttonImage: "img/calendar.gif",
													buttonImageOnly: true
												});
											});
										</script>
										<input type="text" id="date_meeting" name="date_meeting" value="<?php if(is_numeric($id)){echo $_SESSION['date_meeting'];}else{echo date('m/d/Y');}?>" style="width: 85%; float: left; margin-right: 2%;"/>
									</td>
									<td class="top">
										<select name="start_meeting">
											<?php for($i = 1; $i <= 24; $i++): ?>
											<option value="<?= $i; ?>" <?php if(is_numeric($id)){if($_SESSION['start_meeting']==$i){echo 'selected';}}else{if($i==9){echo 'selected';}}?>><?php echo $i.'.00'?></option>
											<?php endfor; ?>
										</select>
									</td>
									<td class="top">
										<select name="end_meeting">
											<?php for($i = 1; $i <= 24; $i++): ?>
											<option value="<?= $i; ?>" <?php if(is_numeric($id)){if($_SESSION['end_meeting']==$i){echo 'selected';}}else{if($i==9){echo 'selected';}}?>><?php echo $i.'.00'?></option>
											<?php endfor; ?>
										</select>
									</td>
									<td><input type="text" name="location_meeting" value="<?php echo $_SESSION['location_meeting']?>"></td>
								</tr>								
								<tr>
									<td colspan="3" class="top">Purpose of meeting</td>
									<td class="top">Next meeting</td>
								</tr>
								<tr>
									<td colspan="3">
										<textarea width="100%" id="purpose_meeting" name="purpose_meeting"><?php echo $_SESSION['purpose_meeting']?></textarea>  
									</td>
									<td>
										<div style="float:left;"><input type="radio" name="next_meeting" value="n" <?php if($_SESSION['next_meeting']=='N'){echo 'checked';}else{echo '';}?> onClick="javaScript:if(this.checked){document.frm.date_next_meeting.disabled=true;}">No</div><div class="clear"></div>
										<div style="float:left;"><input type="radio" name="next_meeting" value="y" <?php if($_SESSION['next_meeting']=='Y'){echo 'checked';}else{echo '';}?> onClick="javaScript:if(this.checked){document.frm.date_next_meeting.disabled=false;}">Yes</div>
										<script>
											$(function() {
												$( "#date_next_meeting" ).datepicker({
													showOn: "button",
													buttonImage: "img/calendar.gif",
													buttonImageOnly: true
												});
											});
										</script>
										<input type="text" id="date_next_meeting" name="date_next_meeting" value="<?php if(is_numeric($id)){echo $_SESSION['date_next_meeting'];}else{echo date('m/d/Y');}?>" style="width: 50%; float: left; margin-right: 2%;"/>
									</td>
								</tr>
								<tr>
									<td class="attened">Attendee</td>
								</tr>
								<tr>									
									<td class="top mt-center w20">Name</td>
									<td class="top mt-center w20">Position</td>
									<td class="top mt-center w20">Company</td>
									<td class="top mt-center w20">Email</td>
									<td class="top mt-center" colspan="1">Sign</td>
								</tr>
								<?php 
								//if(($rs_account['role_user']==1) || ($rs_account['role_user']==2)){$create_by='';}
								//else{$create_by=" and create_by='".$rs_account['id_account']."'";}
								if(!is_numeric($id)){$and=" and id_meeting='0'";}
								else{$and=" and id_meeting='".$id."'";}
								$sql_mt_account="select * from meeting_attendee where id_meeting='".$id."'";
								$sql_mt_account .=$and." and create_by='".$rs_account['id_account']."'";
								$sql_mt_account .=" order by id_attendee asc";
								$res_mt_account=mysql_query($sql_mt_account) or die ('Error '.$sql_mt_account);
								while($rs_mt_account=mysql_fetch_array($res_mt_account)){
									if($rs_mt_account['id_account']!= 0){
										$sql_account2="select * from account inner join positions";
										$sql_account2 .=" on account.id_position=positions.id_position";
										$sql_account2 .=" and account.id_account='".$rs_mt_account['id_account']."'";
										$res_account2=mysql_query($sql_account2) or die ('Error '.$sql_account2);
										$rs_account2=mysql_fetch_array($res_account2);
										$attendee_name=$rs_account2['name'];
										$attendee_position=$rs_account2['title'];
										$attendee_email=$rs_account2['email'];
									}else{ 
										$attendee_name=$rs_mt_account['attendee_name'];
										$attendee_position=$rs_mt_account['attendee_position'];
										$attendee_email=$rs_mt_account['attendee_email'];
									}
									$attendee_company=$rs_mt_account['company'];
								?>
								<tr>
									<td><?php echo $attendee_name?></td>
									<td><?php echo $attendee_position?></td>
									<td><?php echo $attendee_company?></td>
									<td><?php echo $attendee_email?></td>
									<td></td>
									<td>
										<a href="JavaScript:if(confirm('Confirm Delete?')==true){window.location='<?=$_SERVER["PHP_SELF"];?>?id_u=<?php echo $id ?>&action=del_attendee&id_p=<?php echo $rs_mt_account['id_attendee'];?>';}"><img src="img/delete.png" style="width:20px;" title="Delete"></a>
									</td>
								</tr>
								<?php 
								}//end while connet to company contact ?>
								<tr>
									<input type="hidden" name="id_account" id="id_account" >
									<input type="hidden" name="create_by" name="create_by" value="<?php echo $rs_account['id_account']?>">
									<td><input type="text" name="employee_name" id="employee_name" class="employee_name" ></td>
									<td><input type="text" name="position" id="position"></td>
									<td><input type="text" name="company" id="company" value="CDIP (Thailand) Co.,Ltd"></td>
									<td><input type="text" name="email" id="email"></td>
									<td></td>
									<td><input name="btnAdd" type="button" id="btnAdd" value="Add" OnClick="frm.hdnCmd.value='add_attendee';JavaScript:return fncSubmit();" class="btn-new2" title="Add"></td>
								</tr>
							</table>
							<table style="width: 100%;" cellpadding="0" cellspacing="0" id="tb-meeting">
								<tr>
									<td class="mt-td-right mt-td-bottom top mt-center w5">Items</td>
									<td colspan="4" class="mt-td-bottom top mt-center">Descriptions of meeting</td>						
								</tr>
								<?php 
								if(is_numeric($id)){$where=" where id_meeting='".$id."' and create_by='".$rs_account['id_account']."' order by id_meeting_info asc";}else{$where=" where id_meeting='0' and create_by='".$rs_account['id_account']."' order by id_meeting_info asc";}
								$i=0;
								$sql_mt_info="select * from meeting_info";
								$sql_mt_info .=$where;
								$res_mt_info=mysql_query($sql_mt_info) or die ('Error '.$sql_mt_info);
								while($rs_mt_info=mysql_fetch_array($res_mt_info)){
									$i++;
									if($rs_mt_info['id_meeting_info'] == $_GET['id_p'] and $_GET["action"] == 'edit_meeting_info'){ 
									$_SESSION['meeting_info']=$rs_mt_info['description'];
								?>	
								<tr>
									<input type="hidden" name="hdnEdit" value="<?php echo $rs_mt_info['id_meeting_info']?>">
									<td class="mt-td-right mt-td-bottom"><?php echo $i?></td>
									<td class="mt-td-right mt-td-bottom">
										<textarea width="100%" id="meeting_info2" name="meeting_info2"><?php echo $_SESSION['meeting_info']?></textarea>    
										<script type="text/javascript">  										  
											CKEDITOR.replace('meeting_info2', {    
												skin   : 'moono', //กำหนดรูปแบบหน้าตา  
												height   : '100%', //กำหนดความ สูง   if heigth is % = '100%' else px = 100 
												width    : '100%',//กำหนดความกว้าง  
											  	// toolbar: 'Myck' //เรียกใช้งาน ckeditor จากไฟล์ config.js toolbar all
												toolbar :
												[ ['Bold', 'Italic', 'Underline', '-', 'Subscript', 'Superscript', '-',  
												  'NumberedList', 'BulletedList', '-', 'Link', 'Unlink'],
												  ['Cut','Copy','Paste','Undo','Redo' ,'Find','Replace'],
												  ['Outdent', 'Indent', '-', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'],  
												  '/',											  
												  ['Styles', 'Format', 'Font', '-', 'FontSize', 'TextColor', 'BGColor','Table'] ] 
										});  
										  
										</script> 
									</td>	
									<td class="mt-td-right mt-td-bottom">
										<input type="hidden" name="id_account3" class="id_account3" value="<?php if($_GET['id_p']){echo $rs_mt_info['action_by'];}?>">
										<input type="text" name="action_by2" id="action_by2" class="action_by2" value="<?php echo $rs_mt_info['action_by']?>">
									</td>	
									<td class="mt-td-pd">
										<script>
											$(function() {
												$( "#finished_date2" ).datepicker({
													showOn: "button",
													buttonImage: "img/calendar.gif",
													buttonImageOnly: true
												});
											});
										</script>
										<?php
										list($ckmonth,$ckday, $ckyear) = split('[/.-]', $rs_mt_info['finished_date']); 
										$ckstart= $ckday."/".$ckyear."/".$ckmonth;
										?>
										<input type="text" id="finished_date2" name="finished_date2" value="<?php if($_GET['id_p']){echo $ckstart;}else{echo date('m/d/Y');}?>" style="width: 80%; float: left; margin-right: 2%;"/>
									</td>	
									<td>
										<input name="btnAdd" type="button" id="btnUpdate" value="Update" OnClick="frm.hdnCmd.value='update_meeting_info';JavaScript:return fncSubmit();" class="btn-update">
										<input name="btnAdd" type="button" id="btnCancel" value="Cancel" OnClick="window.location='<?=$_SERVER["PHP_SELF"]."?id_u=".$id?>';" class="btn-cancel">
									</td>
								</tr>
								<?php }else{?>												
								<tr>
									<td class="mt-td-right mt-td-bottom"><?php echo $i;?></td>
									<td class="mt-td-right mt-td-bottom"><?php echo $rs_mt_info['description']?></td>	
									<td class="mt-td-right mt-td-bottom"><?php echo $rs_mt_info['action_by']?></td>
									<td class="mt-td-bottom">
										<?php
										list($ckmonth,$ckday, $ckyear) = split('[/.-]', $rs_mt_info['finished_date']); 
										echo$ckstart= $ckyear."/".$ckday. "/". $ckmonth;
										?>
									</td>
									<td class="mt-td-bottom">
										<a href="<?=$_SERVER["PHP_SELF"];?>?action=edit_meeting_info&id_p=<?=$rs_mt_info['id_meeting_info'];?>&id_u=<?php echo $id?>"><img src="img/edit.png" style="width:20px;"></a>
										<a href="JavaScript:if(confirm('Confirm Delete?')==true){window.location='<?=$_SERVER["PHP_SELF"];?>?action=del_meeting_info&id_p=<? echo $rs_mt_info['id_meeting_info'];?>&id_u=<?php echo $id?>';}"><img src="img/delete.png" style="width:20px;"></a>
									</td>
								</tr>
								<?php } 
								}//end while connet to company contact ?>
								<tr>
									<td class="mt-td-right"><input type="text" name="item"></td>
									<td class="mt-center">		
										<div style="text-align:left;font-weight:bold;">Title</div><input type="text" name="title_meeting">
										<textarea width="100%" id="meeting_info" name="meeting_info"></textarea>    
										<script type="text/javascript">  										  
											CKEDITOR.replace('meeting_info', {    
												skin   : 'moono', //กำหนดรูปแบบหน้าตา  
												height   : '100%', //กำหนดความ สูง   if heigth is % = '100%' else px = 100 
												width    : '100%',//กำหนดความกว้าง  
											  	// toolbar: 'Myck' //เรียกใช้งาน ckeditor จากไฟล์ config.js toolbar all
												toolbar :
												[ ['Bold', 'Italic', 'Underline', '-', 'Subscript', 'Superscript', '-',  
												  'NumberedList', 'BulletedList', '-', 'Link', 'Unlink'],
												  ['Cut','Copy','Paste','Undo','Redo' ,'Find','Replace'],
												  ['Outdent', 'Indent', '-', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'],  
												  '/',											  
												  ['Styles', 'Format', 'Font', '-', 'FontSize', 'TextColor', 'BGColor','Table'] ] 
										});  
										  
										</script> 
									</td>
									<td class="mt-td-right" colspan="2">
										<input type="hidden" name="id_account2" id="id_account2">
										<strong>Action by</strong><input type="text" name="action_by" id="action_by" class="action_by">
										<strong>Finished date</strong><br>
										<script>
											$(function() {
												$( "#finished_date" ).datepicker({
													showOn: "button",
													buttonImage: "img/calendar.gif",
													buttonImageOnly: true
												});
											});
										</script>
										<input type="text" id="finished_date" name="finished_date" value="<?php echo date('m/d/Y')?>" style="width: 80%; float: left; margin-right: 2%;"/><br><br><br>	
										<input name="btnAdd" type="button" id="btnAdd" value="เพิ่มรายละเอียด"   class="button-create" OnClick="frm.hdnCmd.value='add_meeting_info_detail';JavaScript:return fncSubmit();">
									</td>
									<td>
										<input name="btnAdd" type="button" id="btnAdd" value="เพิ่มวาระใหม่"   class="button-create" OnClick="frm.hdnCmd.value='add_meeting_info';JavaScript:return fncSubmit();">
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
							<input type="button" name="save_data" id="save_data" value="<?php echo $button?>" class="button-create" OnClick="frm.hdnCmd.value='save_data';JavaScript:return fncSubmit();">
							<?php }else{?>
							<!--<input type="button" name="update_data" id="update_data" value="<?php echo $button?>" class="button-create" OnClick="frm.hdnCmd.value='update_data';JavaScript:return fncSubmit();">-->
							<input type="button" name="finished_data" id="finished_data" value="Send mail" class="button-create" OnClick="frm.hdnCmd.value='finished_data';JavaScript:return fncSubmit();">
							<?php }?>
							<input type="button" value="Close" class="button-create" onclick="window.location.href='meeting.php'">
						</div>
					</td>
				</tr>
				</table>
			</form>
		</div>
	</div>
<!-- auto save
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>-->
<script type="text/javascript" src="js/jquery.autosave.js"></script>
<script type="text/javascript">
	$(function(){
		getDatabase();
		$("input,select,textarea").autosave({
			url: "autosave-meeting.php",
			method: "post",
			grouped: true,
			success: function(data) {
				$("#message p").html("Data updated successfully").show();
				setTimeout('fadeMessage()',1500);
				getDatabase();
			},
			send: function(){
				$("#message p").html("Sending data....");
			},
			dataType: "html"
		});		
	});
	function getDatabase(){
		$.get('autosave3.php', function(data) {
			$('#database').html(data);
		});	
	}
	function fadeMessage(){
		$('#message p').fadeOut('slow');
	}
</script>
<!-- end auto save-->
</body>
</html>