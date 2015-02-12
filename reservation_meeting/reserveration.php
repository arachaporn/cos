<?php
ob_start();
session_start();
if($_SESSION["Username"] == ""){
	header("location:index.php");
	exit();
}
/*$_SESSION['start'] = time(); // taking now logged in time
if(!isset($_SESSION['expire'])){
	$_SESSION['expire'] = $_SESSION['start'] + 3600 ; // ending a session in 30 seconds
}
$now = time(); // checking the time now when home page starts

if($now > $_SESSION['expire']){
	session_destroy();
	//echo "Your session has expire !  <a href='logout.php'>Click Here to Login</a>";
}else{
	//echo "This should be expired in 1 min <a href='logout.php'>Click Here to Login</a>";
}*/
include("../connect/connect.php");
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
<script type="text/javascript" src="../ckeditor-integrated/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="../ckeditor-integrated/ckfinder/ckfinder.js"></script>
<script language="javascript">
function fncSubmit(){	
	document.frm.submit();
	
}
</script>
<script type="text/javascript" src="js/js-autocomplete/js/jquery-1.4.2.min.js"></script> 
<script type="text/javascript" src="js/js-autocomplete/js/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript"> 
	jQuery(document).ready(function(){
		$('.employee_name').autocomplete({
			source:'return-user.php', 
			//minLength:2,
			select:function(evt, ui){
				this.form.id_account.value = ui.item.id_account;
				this.form.position.value = ui.item.position;
				this.form.email.value = ui.item.email;
			}
		});
	});
</script> 
<link rel="stylesheet" href="js/js-autocomplete/css/smoothness/jquery-ui-1.8.2.custom.css" />
<!-- Plugin files below -->
<link rel="stylesheet" type="text/css" href="js/time/src/jquery.ptTimeSelect.css" />
<script type="text/javascript" src="js/time/src/jquery.ptTimeSelect.js"></script>
<script language="JavaScript">
	var HttPRequest = false;

	function doCallAjax() {
		HttPRequest = false;
		if (window.XMLHttpRequest) { // Mozilla, Safari,...
			HttPRequest = new XMLHttpRequest();
			if (HttPRequest.overrideMimeType) {
				HttPRequest.overrideMimeType('text/html');
			}
		}else if (window.ActiveXObject) { // IE
			try {
				HttPRequest = new ActiveXObject("Msxml2.XMLHTTP");
			} catch (e) {
			try {
			   HttPRequest = new ActiveXObject("Microsoft.XMLHTTP");
			} catch (e) {}
		}
	} 
		  
	if (!HttPRequest) {
		alert('Cannot create XMLHTTP instance');
		return false;
	}
	
	var url = 'data_post.php';
	var pmeters = "myRoom=" + encodeURI( document.getElementById("room_type").value) +
				  "&myDate=" + encodeURI( document.getElementById("datepicker").value ) +
				  "&myTime1=" + encodeURI( document.getElementById("start_time1").value ) +
				  "&myTime2=" + encodeURI( document.getElementById("end_time1").value );
			
	//var pmeters = 'myName='+document.getElementById("txtName").value+'&my2='; // 2 Parameters
	HttPRequest.open('POST',url,true);

	HttPRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	HttPRequest.setRequestHeader("Content-length", pmeters.length);
	HttPRequest.setRequestHeader("Connection", "close");
	HttPRequest.send(pmeters);
			
			
	HttPRequest.onreadystatechange = function()
	{
		if(HttPRequest.readyState == 3)  // Loading Request
		{
		   document.getElementById("mySpan").innerHTML = "Now is Loading...";
		}

		if(HttPRequest.readyState == 4) // Return Request
		{
		   document.getElementById("mySpan").innerHTML = HttPRequest.responseText;
		}
		
	}

	/*
	HttPRequest.onreadystatechange = call function .... // Call other function
	*/
}
</script>
<?php
if(is_numeric($_REQUEST['mode'])){
	$id=$_REQUEST['mode'];
}else{
	$id=$_REQUEST['mode'];
}

//*** Add Condition for attendee***//
if($_POST["hdnCmd"] == "add_attendee"){
	$date=date('Y-m-d');
	if(is_numeric($_POST['mode'])){
		$sql_attendee="select id_account from meeting_room_attendee";
		$sql_attendee .=" where id_account='".$_POST['id_account']."'";
		$res_attendee=mysql_query($sql_attendee) or die ('Error '.$sql_attendee);
		$rs_attendee=mysql_fetch_array($res_attendee);
		if($rs_attendee['id_account'] != 0){
			$sql="insert into meeting_room_attendee(id_room_list,id_account,attendee_name";
			$sql .=",attendee_position,company,attendee_email,create_by)";
			$sql .=" values('".$_POST['mode']."','".$_POST['id_account']."'";
			$sql .=",'".$_POST['employee_name']."','".$_POST['position']."'";
			$sql .=",'".$_POST['company']."','".$_POST['email']."'";
			$sql .=",'".$rs_account['id_account']."')";
			$rs=mysql_query($sql) or die ('Error zam : '.$sql);						
		}else{
			$sql="insert into meeting_room_attendee(id_room_list,attendee_name";
			$sql .=",attendee_position, company, attendee_email,create_by)";
			$sql .=" values('".$_POST['mode']."','".$_POST['employee_name']."'";
			$sql .=",'".$_POST['position']."','".$_POST['company']."'";
			$sql .=",'".$_POST['email']."','".$rs_account['id_account']."')";
			$rs=mysql_query($sql) or die ('Error xxx '.$sql);	
		}					
	}else{		
		list($month,$day,$year) = split('[/.-]', $_POST["reserv_date"]); 
		$date=$year.'-'.$month.'-'.$day;
			
		list($start_time) = split('[ AM. PM]',$_POST["start_time1"]); 
		list($end_time) = split('[ AM. PM]',$_POST["end_time1"]);

		$sql="insert into meeting_room_list";
		$sql .="(start_date,start_time,end_time,id_room_type";
		$sql .=",title,id_room_device,total_device,create_by) values";
		$sql .="('".$date."','".$start_time."','".$end_time."'";
		$sql .=",'".$_POST['room_type']."','".$_POST['title']."'";
		$sql .=",'".$_POST['room_device']."','1','".$rs_account['id_account']."')";
		$res=mysql_query($sql) or die ('Error insert meeting room list : '.$sql);

		$id_room_list=mysql_insert_id();

		$sql="insert into meeting_room_attendee(id_room_list,attendee_name";
		$sql .=",attendee_position,company,attendee_email,create_by)";
		$sql .=" values('".$id_room_list."','".$_POST['employee_name']."'";
		$sql .=",'".$_POST['position']."','".$_POST['company']."'";
		$sql .=",'".$_POST['email']."','".$rs_account['id_account']."')";
		$rs=mysql_query($sql) or die ('Error bbb : '.$sql);	

		$id=$id_room_list;
	}
}

//*** Delete Condition ***//
if($_GET["action"] == "del_attendee"){
	$id=$_GET['id_u'];
	$sql = "delete from meeting_room_attendee ";
	$sql .="where id_attendee = '".$_GET["id_p"]."'";
	$res = mysql_query($sql) or die ('Error ccc : '.$sql);
}

//add data to room list
if($_POST["hdnCmd"] == "save_data"){

	list($month,$day,$year) = split('[/.-]', $_POST["reserv_date"]); 
	$date=$year.'-'.$month.'-'.$day;
	$date2=date('Y-m-d');
	list($start_time) = split('[ AM. PM]',$_POST["start_time1"]); 
	list($end_time) = split('[ AM. PM]',$_POST["end_time1"]);

	$sql="insert into meeting_room_list (start_date,start_time";
	$sql .=",end_time,time_room,end_time_room,id_room_type";
	$sql .=",title,id_room_device,create_by,create_date) values";
	$sql .="('".$date."','".$start_time."','".$end_time."'";
	$sql .=",'".$_POST["start_time1"]."','".$_POST["end_time1"]."'";
	$sql .=",'".$_POST['room_type']."','".$_POST['title']."'";
	$sql .=",'".$_POST['room_device']."','".$rs_account['id_account']."'";
	$sql .=",'".$date2."')";
	$res=mysql_query($sql) or die ('Error insert meeting room list : '.$sql);

	$id_room_list=mysql_insert_id();
	
	$sql_attendee="update meeting_room_attendee";
	$sql_attendee .=" set id_room_list='".$id_room_list."'";
	$sql_attendee .=" where id_room_list='0'";
	$res_attendee=mysql_query($sql_attendee) or die ('Error update attendee : '.$sql_attendee);
}elseif($_POST["hdnCmd"] == "update_data"){
	if($_POST['create_by']==$_POST['account']){
		list($month,$day,$year) = split('[/.-]', $_POST["reserv_date"]); 
		$date=$year.'-'.$month.'-'.$day;
		$date2=date('Y-m-d');
		list($start_time) = split('[ AM. PM]',$_POST["start_time1"]); 
		list($end_time) = split('[ AM. PM]',$_POST["end_time1"]);

		$sql="update meeting_room_list set start_date='".$date."'";
		$sql .=",start_time='".$start_time."',end_time='".$end_time."'";
		$sql .=",time_room='".$_POST["start_time1"]."'";
		$sql .=",end_time_room='".$_POST["end_time1"]."'";
		$sql .=",id_room_type='".$_POST['room_type']."'";
		$sql .=",title='".$_POST['title']."'";
		$sql .=",id_room_device='".$_POST['room_device']."'";
		$sql .=" where id_room_list='".$_POST['mode']."'";
		$res=mysql_query($sql) or die ('Error update meeting room list : '.$sql);
	}
}?>
</head>
<body>
	<?php
	$sql="select * from meeting_room_list where id_room_list='".$id."'";
	$res=mysql_query($sql) or die ('Error room list : '.$sql);
	$rs=mysql_fetch_array($res);
	?>
	<div class="row">
		<div class="background">
			<form name="frm" method="post" action="<?=$_SERVER["PHP_SELF"]."?id_p=".$id?>">
				<input type="hidden" name="hdnCmd" value="">
				<input type="hidden" name="create_by" value="<?php echo $rs['create_by']?>">
				<input type="hidden" name="account" value="<?php echo $rs_account['id_account']?>">
				<table style="border: 0; width: 100%;" cellpadding="0" cellspacing="0">
					<tr>
						<td class="b-bottom"><div class="large-4 columns"><h4>จองห้องประชุม <?php echo $rs['title']?></h4></div></td>
					</tr>
					<tr>
						<td class="b-bottom">
							<div class="large-4 columns">
								<?php if(!is_numeric($id)){?>
								<input type="button" name="save" id="save_data" value="Save" class="button-create" OnClick="frm.hdnCmd.value='save_data';JavaScript:return fncSubmit();">
								<?php }else{?>
								<input type="button" name="update_data" id="update_data" value="Save" class="button-create" OnClick="frm.hdnCmd.value='update_data';JavaScript:return fncSubmit();">								
								<?php }?>
								<!--<input type="button" name="finished_data" id="finished_data" value="Send mail" class="button-create" OnClick="frm.hdnCmd.value='finished_data';JavaScript:return fncSubmit();">-->
							</div>
						</td>
					</tr>
					<tr>
						<td style="background: #fff;">
							<div class="large-4 columns">
								<input type="hidden" name="mode" value="<?php echo $id?>">
								<table style="border: none; width: 100%;" cellpadding="0" cellspacing="0" id="tb-add">									
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
										<td class="title">Date of reserveration<span style="color: red;">*</span></td>
										<td colspan="2">
											<?php 
											list($year,$day,$month) = split('[/.-]', $rs["start_date"]); 											
											?>
											<input type="text" id="datepicker" name="reserv_date" value="<?php if(is_numeric($id)){echo$day.'/'.$month.'/'.$year;}else{echo date('m/d/Y');}?>" style="width: 50%; float: left; margin-right: 2%;"/>
										</td>
									</tr>
									<tr>
										<td class="title">Start time</td>
										<td class="w20"><div id="sample1"><input type="text" name="start_time1" id="start_time1" value="<?php echo $rs['time_room']?>"></div></td>
										<td class="title w10">End time</td>
										<td><div id="sample1"><input type="text" name="end_time1" id="end_time1" value="<?php echo $rs['end_time_room']?>" style="width: 40%;"></div></td>
											<script type="text/javascript">
												$(document).ready(function(){
													$('#sample1 input').ptTimeSelect();
												});
											</script>
									</tr>
									<tr>
										<td class="title">Room</td>
										<td class="w20">
											<select name="room_type" id="room_type">
											<?php
											$sql_meeting_room_type="select * from meeting_room_type";
											$res_meeting_room_type=mysql_query($sql_meeting_room_type) or die ('Error meeting room type : '.$sql_meeting_room_type);
											while($rs_meeting_room_type=mysql_fetch_array($res_meeting_room_type)){
											?>
												<option value="<?php echo $rs_meeting_room_type['id_meeting_room_type']?>" <?php if($rs['id_room_type']==$rs_meeting_room_type['id_meeting_room_type']){echo 'selected';}?>><?php echo $rs_meeting_room_type['meetimg_room_name']?></option>
											<?php }?>
											</select>											
										</td>
									</tr>
									<tr>
										<td class="title" style="padding-bottom:2%;">Create by</td>
										<td class="w20" style="padding-bottom:2%;">
											<?php 
											$sql_acc="select * from account where id_account='".$rs['create_by']."'";
											$res_acc=mysql_query($sql_acc) or die ('Error account : '.$sql_acc);
											$rs_acc=mysql_fetch_array($res_acc);
											echo $rs_acc['name'];
											?>
										</td>
									</tr>
									<tr>
										<td colspan="4"><input type="button" value="Check room" onClick="JavaScript:doCallAjax();" class="button-create"><span id="mySpan" style="color:#FF3333;margin:0 0 0 2%;font-size:14px;font-weight:bold;">คลิ๊กเพื่อตรวจสอบห้องประชุม</span></strong></td>
									</tr>
									<tr>
										<td class="title" style="padding:2% 0 0 0;">Title</td>
										<td colspan="3" style="padding:2% 0 0 0;"><input type="text" name="title" value="<?php echo $rs['title']?>"></td>
									</tr>
									<!--<tr>
										<td class="title">Device</td>
										<td class="w20">
											<?php
											$total=0;
											$sql_meeting_room_device="select * from meeting_room_device";
											$res_meeting_room_device=mysql_query($sql_meeting_room_device) or die ('Error meeting room device : '.$sql_meeting_room_device);
											while($rs_meeting_room_device=mysql_fetch_array($res_meeting_room_device)){
												if($rs_meeting_room_device['id_room_device']==1){
													$total=$rs_meeting_room_device['total']-1;
												}else{
													$total=$rs_meeting_room_device['total'];
												}
											?>
												<input type="checkbox" name="room_device" value="<?php echo $rs_meeting_room_device['id_room_device']?>"><?php echo $rs_meeting_room_device['title_device'].' คงเหลือ : '.$total?>
												<br>
											<?php }?>
										</td>
									</tr>
									<tr>
										<td colspan="4"><h4>Attendee</h4></td>
									</tr>
									<tr>									
										<td class="top mt-center w20">Name</td>
										<td class="top mt-center w20">Position</td>
										<td class="top mt-center w20">Company</td>
										<td class="top mt-center w20">Email</td>
									</tr>
									<?php 
									if($rs_account['role_user']==1){$create_by='';}
									else{$create_by=" and create_by='".$rs_account['id_account']."'";}
									$sql_mt_account="select * from meeting_room_attendee where id_room_list='".$id."'";
									$sql_mt_account .=$create_by;
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
										<td><input type="text" name="employee_name" id="employee_name" class="employee_name" ></td>
										<td><input type="text" name="position" id="position"></td>
										<td><input type="text" name="company" id="company" value="CDIP (Thailand) Co.,Ltd"></td>
										<td><input type="text" name="email" id="email"></td>
										<td></td>
										<td><input name="btnAdd" type="button" id="btnAdd" value="Add" OnClick="frm.hdnCmd.value='add_attendee';JavaScript:return fncSubmit();" class="btn-new2" title="Add"></td>
									</tr>
								</table>-->
							</div>
						</td>
					</tr>
					<tr>
						<td class="b-top">
							<div class="large-4 columns">
								<?php if(!is_numeric($id)){?>
								<input type="button" name="save" id="save_data" value="Save" class="button-create" OnClick="frm.hdnCmd.value='save_data';JavaScript:return fncSubmit();parent.$.fancybox.close();">
								<?php }else{?>
								<input type="button" name="update_data" id="update_data" value="Save" class="button-create" OnClick="frm.hdnCmd.value='update_data';JavaScript:return fncSubmit();parent.$.fancybox.close();">								
								<?php }?>
								<!--<input type="button" name="finished_data" id="finished_data" value="Send mail" class="button-create" OnClick="frm.hdnCmd.value='finished_data';JavaScript:return fncSubmit();parent.$.fancybox.close();">-->
							</div>
						</td>
					</tr>					
				</table>
			</form>
		</div>
	</div> 
</body>
</html>