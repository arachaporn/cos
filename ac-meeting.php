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
<script type="text/javascript" src="js/js-autocomplete/js/jquery-1.4.2.min.js"></script> 
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
			include("connect/connect.php");
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

			}

			//*** Add Condition for attendee***//
			if($_POST["hdnCmd"] == "add_attendee"){
				$date=date('Y-m-d');
				if(is_numeric($_POST['mode'])){
					$sql_attendee="select id_account from meeting_attendee";
					$sql_attendee .=" where id_account='".$_POST['id_account']."'";
					$res_attendee=mysql_query($sql_attendee) or die ('Error '.$sql_attendee);
					$rs_attendee=mysql_fetch_array($res_attendee);
					if($rs_attendee['id_account'] != 0){
						$sql="insert into meeting_attendee(id_meeting,id_account,attendee_name";
						$sql .=",attendee_position,company,attendee_email,create_by)";
						$sql .=" values('".$_POST['mode']."','".$_POST['id_account']."'";
						$sql .=",'".$_POST['employee_name']."','".$_POST['position']."'";
						$sql .=",'".$_POST['company']."','".$_POST['email']."'";
						$sql .=",'".$rs_account['id_account']."')";
						$rs=mysql_query($sql) or die ('Error '.$sql);						
					}else{
						$sql="insert into meeting_attendee(id_meeting,attendee_name";
						$sql .=",attendee_position,company,attendee_email,create_by)";
						$sql .=" values('".$_POST['mode']."','".$_POST['employee_name']."'";
						$sql .=",'".$_POST['position']."','".$_POST['company']."'";
						$sql .=",'".$_POST['email']."','".$rs_account['id_account']."')";
						$rs=mysql_query($sql) or die ('Error '.$sql);	
					}					
				}else{
					$sql_attendee="select id_account from meeting_attendee";
					$sql_attendee .=" where id_account='".$_POST['id_account']."'";
					$res_attendee=mysql_query($sql_attendee) or die ('Error '.$sql_attendee);
					$rs_attendee=mysql_fetch_array($res_attendee);
					if($rs_attendee['id_account'] != 0){
						$sql="insert into meeting_attendee(id_meeting,id_account,attendee_name";
						$sql .=",attendee_position,company,attendee_email,create_by)";
						$sql .=" values('".$_POST['mode']."','".$_POST['id_account']."'";
						$sql .=",'".$_POST['employee_name']."','".$_POST['position']."'";
						$sql .=",'".$_POST['company']."','".$_POST['email']."'";
						$sql .=",'".$rs_account['id_account']."')";
						$rs=mysql_query($sql) or die ('Error '.$sql);
					}else{
						$sql="insert into meeting_attendee(id_meeting,attendee_name";
						$sql .=",attendee_position,company,attendee_email,create_by)";
						$sql .=" values('".$_POST['mode']."','".$_POST['employee_name']."'";
						$sql .=",'".$_POST['position']."','".$_POST['company']."'";
						$sql .=",'".$_POST['email']."','".$rs_account['id_account']."')";
						$rs=mysql_query($sql) or die ('Error '.$sql);	
					}					
				}
				//header("location:$_SERVER[PHP_SELF]");
				//exit();
			}

			//*** Delete Condition ***//
			if($_GET["action"] == "del_attendee"){
				$sql = "delete from meeting_attendee ";
				$sql .="where id_attendee = '".$_GET["id_p"]."'";
				$res = mysql_query($sql) or die ('Error '.$sql);
				//header("location:$_SERVER[PHP_SELF]");
				//exit();
			}
						
			//*** Add Condition for meeting description***//
			if($_POST["hdnCmd"] == "add_meeting_info"){
				$date=date('Y-m-d');
				if($_POST['mode']){
					list($ckmonth,$ckday, $ckyear) = split('[/.-]', $_POST['finished_date']); 
					$ckstart= $ckyear."-".$ckmonth. "-" .$ckday;

					$sql="insert into meeting_info(id_meeting,description,action_by,finished_date";
					$sql .=",create_by,create_date)";
					$sql .=" values('".$_POST['mode']."','".$_POST['meeting_info']."'";
					$sql .=",'".$_POST['action_by']."','".$ckstart."','".$rs_account['id_account']."'";
					$sql .=",'".$date."')";
					$rs=mysql_query($sql) or die ('Error '.$sql);
				}else{
					list($ckmonth,$ckday, $ckyear) = split('[/.-]', $_POST['finished_date']); 
					$ckstart= $ckyear."-".$ckmonth. "-" .$ckday;

					$sql="insert into meeting_info(description,action_by,finished_date";
					$sql .=",create_by,create_date)";
					$sql .=" values('".$_POST['meeting_info']."','".$_POST['action_by']."'";
					$sql .=",'".$ckstart."','".$rs_account['id_account']."','".$date."')";
					$rs=mysql_query($sql) or die ('Error '.$sql);
				}
				//header("location:$_SERVER[PHP_SELF]");
				//exit();

			}
			
			//*** Delete Condition ***//
			if($_GET["action"] == "del_meeting_info"){
				$sql = "delete from meeting_info ";
				$sql .="where id_meeting_info = '".$_GET["id_p"]."'";
				$res = mysql_query($sql) or die ('Error '.$sql);
				//header("location:$_SERVER[PHP_SELF]");
				//exit();
			}

			//*** Update Condition ***//
			if($_POST["hdnCmd"] == "update_meeting_info"){

				list($ckmonth,$ckday, $ckyear) = split('[/.-]', $_POST['finished_date2']); 
				$ckstart= $ckyear."-".$ckmonth. "-" .$ckday;

				$sql = "update meeting_info set description='".$_POST['meeting_info2']."'";
				$sql .=",action_by= '".$_POST['action_by2']."',finished_date='".$ckstart."'";
				$sql .=" where id_meeting_info = '".$_POST["hdnEdit"]."' ";
				$res = mysql_query($sql) or die ('Error '.$sql);
				//header("location:$_SERVER[PHP_SELF]");
				//exit();
			}
			if($_POST["hdnCmd"] == "save_data"){			
				$date=date('Y-m-d');
				list($ckmonth,$ckday, $ckyear) = split('[/.-]', $_POST['date_meeting']); 
				$date_meeting= $ckyear."-".$ckmonth. "-" .$ckday;
				switch($_POST['next_meeting']){
					case 'n' : $next_meeting='N';
							   $date_next_meeting='';
					break;
					case 'y' : $next_meeting='Y';
							   list($ckmonth,$ckday, $ckyear) = split('[/.-]', $_POST['date_next_meeting']); 
							   $date_next_meeting= $ckyear."-".$ckmonth. "-" .$ckday;
					break;
				}
				$sql2="insert into meeting(meeting_name,writen_by,meeting_time,date_meeting";
				$sql2 .=",start_meeting,end_meeting,location_meeting,purpose_meeting,next_meeting";
				$sql2 .=",date_next_meeting,create_date,create_by)";
				$sql2 .=" values('".$_POST['meeting_name']."','".$_POST['id_writen']."'";
				$sql2 .=",'1','".$date_meeting."','".$_POST['start_meeting']."'";
				$sql2 .=",'".$_POST['end_meeting']."','".$_POST['location_meeting']."'";
				$sql2 .=",'".$_POST['purpose_meeting']."','".$next_meeting."'";
				$sql2 .=",'".$date_next_meeting."','".$date."','".$rs_account['id_account']."')";
				$res2=mysql_query($sql2) or die ('Error '.$sql2);

				$id_meeting=mysql_insert_id();

				$sql_up_attendee="update meeting_attendee set id_meeting='".$id_meeting."'";
				$sql_up_attendee .=" where id_meeting='0'";
				$res_up_attendee=mysql_query($sql_up_attendee) or die ('Error '.$sql_up_attendee);

				$sql_up_info="update meeting_info set id_meeting='".$id_meeting."'";
				$sql_up_info .=" where id_meeting='0'";
				$res_up_info=mysql_query($sql_up_info) or die ('Error '.$sql_up_info);	
				
				$sql="select * from meeting where id_meeting='".$id_meeting."'";
				$res=mysql_query($sql) or die ('Error '.$sql);
				$rs=mysql_fetch_array($res);
					$sql_attendee2="select * from meeting_attendee";
					$sql_attendee2 .=" where id_meeting='".$id_meeting."'";
					$res_attendee2=mysql_query($sql_attendee2) or die ('Error '.$sql_attendee2);
					while($rs_attendee2=mysql_fetch_array($res_attendee2)){
						$att_email2 .=$rs_attendee2['attendee_email'].',';
					}
					
					$sql_acc2="select * from account where id_account='".$rs['create_by']."'";
					$res_acc2=mysql_query($sql_acc2) or die ('Error '.$sql_acc2);
					$rs_acc2=mysql_fetch_array($res_acc2);
					
					include("mpdf/mpdf.php");
					ob_start();
					?>
					<html>
					<body>
						<table style="border:none; width:100%; font-size: 0.8em; line-height: 1.2em;text-align:left;">
							<tr>
								<td colspan="2" style="padding-bottom: 2%;"><img src="img/logo.png" style="width:20%;"></td>
								<td colspan="4" style="vertical-align:bottom;padding-bottom:2%; padding-left:2%;font-size: 1.2em;">บริษัท ซีดีไอพี (ประเทศไทย) จำกัด<br>
								CDIP (Thailand) Co.,Ltd.<br>
								Minutes of Meeting<br>
								131 อาคารกลุ่มนวัตกรรม 1 อุทยานวิทยาศาสตร์ประเทศไทย <br> 
								ถนนพหลโยธิน ตำบลคลองหนึ่ง อำเภอคลองหลวง จังหวัดปทุมธานี 12120
								</td>
							</tr>
							<tr>
								<td style="border-top:1px solid #eee;padding-top:2%;width:15%;">Meeting name :</td>
								<td style="border-top:1px solid #eee;padding-top:2%;width:20%;"><?php echo $rs['meeting_name']?></td>
								<td style="border-top:1px solid #eee;padding-top:2%;width:10%;">Writen by :</td>
								<td style="border-top:1px solid #eee;padding-top:2%;width:15%;"><?php echo $rs_acc2['name']?></td>
								<td style="border-top:1px solid #eee;padding-top:2%;width:20%;">Meeting time :</td>
								<td style="border-top:1px solid #eee;padding-top:2%;"><?php echo $rs['meeting_time']?></td>
							</tr>
							<tr>
								<td>Date of meeting :</td>
								<td><?php echo $rs['date_meeting']?></td>
								<td>Duration :</td>
								<td><?php echo $rs['start_meeting'].'.00 - '.$rs['end_meeting'].'.00'?></td>
								<td>Location of meeting :</td>
								<td><?php if($rs['location_meeting']==null){echo '-';}else{echo $rs['location_meeting'];}?></td>
							</tr>
							<tr>
								<td colspan="2">Purpose of meeting :</td>
								<td colspan="2"><?php if($rs['purpose_meeting']==null){echo '-';}else{echo $rs['purpose_meeting'];}?></td>
								<td>Next meeting :</td>
								<td><?php if($rs['next_meeting']=="N"){echo '-';}else{ echo $rs['date_next_meeting'];}?></td>
							</tr>
						</table><br>
						<table cellpadding="0" cellspacing="0" style="width:100%; font-size: 0.8em; line-height: 1.2em;text-align:left;">
							<tr>
								<td colspan="4"><div style="font-weight:bold;padding-bottom:2%;">Attendee</div></td>
							</tr>
							<tr>
								<td style="border:1px solid #eee;width:25%;text-align:center;padding:0.5%;">Name</td>
								<td style="border-top:1px solid #eee;border-bottom:1px solid #eee;border-right:1px solid #eee;width:25%;text-align:center;padding:0.5%;">Positon</td>
								<td style="border-top:1px solid #eee;border-bottom:1px solid #eee;border-right:1px solid #eee;width:25%;text-align:center;padding:0.5%;">Company</td>
								<td style="border-top:1px solid #eee;border-bottom:1px solid #eee;border-right:1px solid #eee;width:25%;text-align:center;padding:0.5%;">Email</td>
							</tr>
							<?php
							$sql_atten = "select * from meeting_attendee where id_meeting='".$id."'";
							$res_atten = mysql_query($sql_atten) or die ('Error '.$sql_atten);
							while($rs_atten=mysql_fetch_array($res_atten)){
							?>
							<tr>
								<td style="border-bottom:1px solid #eee;border-left:1px solid #eee;border-right:1px solid #eee;padding:0.5%;"><?php echo $rs_atten["attendee_name"]?></td>
								<td style="border-bottom:1px solid #eee;border-right:1px solid #eee;padding:0.5%;"><?php echo $rs_atten["attendee_position"]?></td>
								<td style="border-bottom:1px solid #eee;border-right:1px solid #eee;padding:0.5%;"><?php echo $rs_atten["company"]?></td>
								<td style="border-bottom:1px solid #eee;border-right:1px solid #eee;padding:0.5%;"><?php echo $rs_atten["attendee_email"]?></td>
							</tr>
							<?php }	?>
						</table><br>
						<div style="font-size: 0.8em;">
						<div style="font-weight:bold;">Descriptions of meeting</div>
						<?php
						$sql_info= "select * from meeting_info where id_meeting='".$id."'";
						$res_info = mysql_query($sql_info) or die ('Error '.$sql_info);
						$i_info=0;
						while($rs_info=mysql_fetch_array($res_info)){
							$i_info++;
							echo '<div style="padding-top:1.5%;font-size:1.2em;font-weight:bold;">';
						    echo 'หัวข้อที่ '.$i_info;
							echo '</div>';
							echo $rs_info['description'];
							echo 'Action by : ';
							if($rs_info['action_by']== ''){echo '-';}else{echo$rs_info['action_by'];}
							echo '&nbsp;&nbsp;&nbsp;';
							echo 'Finished date : ';
							echo $rs_info['finished_date'].'<br>';
							echo '<div style="border-bottom:1px solid #eee;">&nbsp;</div>';
						}	
						?>
						</div>
					</body>
					</html>
					<?
					$html = ob_get_contents();
					ob_end_clean();
					$mpdf=new mPDF('UTF-8');
					$mpdf-> SetAutoFont();
					$mpdf-> WriteHTML($html);
					$mpdf-> Output("file/meeting/Minutes of Meeting ".$rs['meeting_name']." ".$rs['date_meeting'].".pdf");

					$file_name="Minutes of Meeting ".$rs['meeting_name']." ".$rs['date_meeting'].".pdf";
					
					/*select flie pdf*/
					$sql_files="select * from meeting_file where id_meeting='".$id."'";
					$res_files=mysql_query($sql_files) or die ('Error '.$sql_files);
					$rs_files=mysql_fetch_array($res_files);					
					if($rs_files['id_meeting'] != $id){
						/*insert file pdf to database*/
						$sql_file="insert into meeting_file(id_meeting,meeting_file)";
						$sql_file .=" values('".$id_meeting."','".$file_name."')";
						$res_file=mysql_query($sql_file) or die ('Error '.$sql_file);
					}else{
						$sql_file="update meeting_file set meeting_file='".$file_name."'";
						$sql_file .=" where id_meeting='".$id."'";
						$res_file=mysql_query($sql_file) or die ('Error '.$sql_file);
					}
			?>
				<script>
					window.location.href='ac-meeting.php?id_u=<?=$id_meeting?>';
				</script>
			<?php }
				if($_POST["hdnCmd"] == "update_data"){
					list($ckmonth,$ckday, $ckyear) = split('[/.-]', $_POST['date_meeting']); 
					$date_meeting= $ckyear."-".$ckmonth. "-" .$ckday;
					switch($_POST['next_meeting']){
						case 'n' : $next_meeting='N';
								   $date_next_meeting='';
						break;
						case 'y' : $next_meeting='Y';
								   list($ckmonth,$ckday, $ckyear) = split('[/.-]', $_POST['date_next_meeting']); 
								   $date_next_meeting= $ckyear."-".$ckmonth. "-" .$ckday;
						break;
					}
					$sql2="update meeting set meeting_name='".$_POST['meeting_name']."'";
					$sql2 .=",writen_by='".$_POST['id_writen']."'";
					$sql2 .=",meeting_time='".$_POST['meeting_time']."'";
					$sql2 .=",date_meeting='".$date_meeting."'";
					$sql2 .=",start_meeting='".$_POST['start_meeting']."'";
					$sql2 .=",end_meeting='".$_POST['end_meeting']."'";
					$sql2 .=",location_meeting='".$_POST['location_meeting']."'";
					$sql2 .=",purpose_meeting='".$_POST['purpose_meeting']."'";
					$sql2 .=",next_meeting='".$next_meeting."'";
					$sql2 .=",date_next_meeting='".$date_next_meeting."'";
					$sql2 .=" where id_meeting='".$_POST['mode']."'";
					$res2=mysql_query($sql2) or die ('Error '.$sql2);
					
					$sql="select * from meeting where id_meeting='".$_POST['mode']."'";
					$res=mysql_query($sql) or die ('Error '.$sql);
					$rs=mysql_fetch_array($res);
					$sql_attendee2="select * from meeting_attendee";
					$sql_attendee2 .=" where id_meeting='".$id."'";
					$res_attendee2=mysql_query($sql_attendee2) or die ('Error '.$sql_attendee2);
					while($rs_attendee2=mysql_fetch_array($res_attendee2)){
						$att_email2 .=$rs_attendee2['attendee_email'].',';
					}
					
					$sql_acc2="select * from account where id_account='".$rs['create_by']."'";
					$res_acc2=mysql_query($sql_acc2) or die ('Error '.$sql_acc2);
					$rs_acc2=mysql_fetch_array($res_acc2);
					
					include("mpdf/mpdf.php");
					ob_start();
					?>
					<html>
					<body>
						<table style="border:none; width:100%; font-size: 0.8em; line-height: 1.2em;text-align:left;">
							<tr>
								<td colspan="2" style="padding-bottom: 2%;"><img src="img/logo.png" style="width:20%;"></td>
								<td colspan="4" style="vertical-align:bottom;padding-bottom:2%; padding-left:2%;">บริษัท ซีดีไอพี (ประเทศไทย) จำกัด<br>
								CDIP (Thailand) Co.,Ltd.<br>
								Minutes of Meeting<br>
								131 อาคารกลุ่มนวัตกรรม 1 อุทยานวิทยาศาสตร์ประเทศไทย <br> 
								ถนนพหลโยธิน ตำบลคลองหนึ่ง อำเภอคลองหลวง จังหวัดปทุมธานี 12120
								</td>
							</tr>
							<tr>
								<td style="border-top:1px solid #eee;padding-top:2%;width:15%;">Meeting name :</td>
								<td style="border-top:1px solid #eee;padding-top:2%;width:20%;"><?php echo $rs['meeting_name']?></td>
								<td style="border-top:1px solid #eee;padding-top:2%;width:10%;">Writen by :</td>
								<td style="border-top:1px solid #eee;padding-top:2%;width:15%;"><?php echo $rs_acc2['name']?></td>
								<td style="border-top:1px solid #eee;padding-top:2%;width:20%;">Meeting time :</td>
								<td style="border-top:1px solid #eee;padding-top:2%;"><?php echo $rs['meeting_time']?></td>
							</tr>
							<tr>
								<td>Date of meeting :</td>
								<td><?php echo $rs['date_meeting']?></td>
								<td>Duration :</td>
								<td><?php echo $rs['start_meeting'].'.00 - '.$rs['end_meeting'].'.00'?></td>
								<td>Location of meeting :</td>
								<td><?php if($rs['location_meeting']==null){echo '-';}else{echo $rs['location_meeting'];}?></td>
							</tr>
							<tr>
								<td colspan="2">Purpose of meeting :</td>
								<td colspan="2"><?php if($rs['purpose_meeting']==null){echo '-';}else{echo $rs['purpose_meeting'];}?></td>
								<td>Next meeting :</td>
								<td><?php if($rs['next_meeting']=="N"){echo '-';}else{ echo $rs['date_next_meeting'];}?></td>
							</tr>
						</table><br>
						<table cellpadding="0" cellspacing="0" style="width:100%; font-size: 0.8em; line-height: 1.2em;text-align:left;">
							<tr>
								<td colspan="4"><div style="font-weight:bold;padding-bottom:2%;">Attendee</div></td>
							</tr>
							<tr>
								<td style="border:1px solid #eee;width:25%;text-align:center;padding:0.5%;">Name</td>
								<td style="border-top:1px solid #eee;border-bottom:1px solid #eee;border-right:1px solid #eee;width:25%;text-align:center;padding:0.5%;">Positon</td>
								<td style="border-top:1px solid #eee;border-bottom:1px solid #eee;border-right:1px solid #eee;width:25%;text-align:center;padding:0.5%;">Company</td>
								<td style="border-top:1px solid #eee;border-bottom:1px solid #eee;border-right:1px solid #eee;width:25%;text-align:center;padding:0.5%;">Email</td>
							</tr>
							<?php
							$sql_atten = "select * from meeting_attendee where id_meeting='".$id."'";
							$res_atten = mysql_query($sql_atten) or die ('Error '.$sql_atten);
							while($rs_atten=mysql_fetch_array($res_atten)){
							?>
							<tr>
								<td style="border-bottom:1px solid #eee;border-left:1px solid #eee;border-right:1px solid #eee;padding:0.5%;"><?php echo $rs_atten["attendee_name"]?></td>
								<td style="border-bottom:1px solid #eee;border-right:1px solid #eee;padding:0.5%;"><?php echo $rs_atten["attendee_position"]?></td>
								<td style="border-bottom:1px solid #eee;border-right:1px solid #eee;padding:0.5%;"><?php echo $rs_atten["company"]?></td>
								<td style="border-bottom:1px solid #eee;border-right:1px solid #eee;padding:0.5%;"><?php echo $rs_atten["attendee_email"]?></td>
							</tr>
							<?php }	?>
						</table><br>
						<div style="font-size: 0.8em;">
						<div style="font-weight:bold;">Descriptions of meeting</div>
						<?php
						$sql_info= "select * from meeting_info where id_meeting='".$id."'";
						$res_info = mysql_query($sql_info) or die ('Error '.$sql_info);
						$i_info=0;
						while($rs_info=mysql_fetch_array($res_info)){
							$i_info++;
							echo '<div style="padding-top:1.5%;font-size:1.2em;font-weight:bold;">';
						    echo 'หัวข้อที่ '.$i_info;
							echo '</div>';
							echo $rs_info['description'];
							echo 'Action by : ';
							if($rs_info['action_by']== ''){echo '-';}else{echo$rs_info['action_by'];}
							echo '&nbsp;&nbsp;&nbsp;';
							echo 'Finished date : ';
							echo $rs_info['finished_date'].'<br>';
							echo '<div style="border-bottom:1px solid #eee;">&nbsp;</div>';
						}	
						?>
						</div>
					</body>
					</html>
					<?
					$html = ob_get_contents();
					ob_end_clean();
					$mpdf=new mPDF('UTF-8');
					$mpdf-> SetAutoFont();
					$mpdf-> WriteHTML($html);
					$mpdf-> Output("file/meeting/Minutes of Meeting ".$rs['meeting_name']." ".$rs['date_meeting'].".pdf",'F');

					$file_name="Minutes of Meeting ".$rs['meeting_name']." ".$rs['date_meeting'].".pdf";
					
					/*select flie pdf*/
					$sql_files="select * from meeting_file where id_meeting='".$id."'";
					$res_files=mysql_query($sql_files) or die ('Error '.$sql_files);
					$rs_files=mysql_fetch_array($res_files);					
					if($rs_files['id_meeting'] != $id){
						/*insert file pdf to database*/
						$sql_file="insert into meeting_file(id_meeting,meeting_file)";
						$sql_file .=" values('".$id."','".$file_name."')";
						$res_file=mysql_query($sql_file) or die ('Error '.$sql_file);
					}else{
						$sql_file="update meeting_file set meeting_file='".$file_name."'";
						$sql_file .=" where id_meeting='".$id."'";
						$res_file=mysql_query($sql_file) or die ('Error '.$sql_file);
					}
			?>
				<script>
					window.location.href='ac-meeting.php?id_u=<?=$id?>';
				</script>
			<?php }
				if($_POST["hdnCmd"] == "finished_data"){
					$sql_attendee2="select * from meeting_attendee";
					$sql_attendee2 .=" where id_meeting='".$id."'";
					$res_attendee2=mysql_query($sql_attendee2) or die ('Error '.$sql_attendee2);
					while($rs_attendee2=mysql_fetch_array($res_attendee2)){
						$att_email2 .=$rs_attendee2['attendee_email'].',';
					}
					
					$sql_acc2="select * from account where id_account='".$rs['create_by']."'";
					$res_acc2=mysql_query($sql_acc2) or die ('Error '.$sql_acc2);
					$rs_acc2=mysql_fetch_array($res_acc2);		
					
					$sql_files="select * from meeting_file where id_meeting='".$id."'";
					$res_files=mysql_query($sql_files) or die ('Error '.$sql_files);
					$rs_files=mysql_fetch_array($res_files);
										
					$MailTo = $att_email2;
					$MailFrom = $rs_acc2['email'];
					$MailSubject = "=?UTF-8?B?".base64_encode("Minutes of Meeting ".$rs['meeting_name']." ".$rs['date_meeting'])."?=";
					$MailMessage = "เรียนผู้เข้าร่วมประชุมทุกท่าน";
					$MailMessage .="<br>";
					$MailMessage .= "รายละเอียดการประชุมตามเอกสารแนบคะ";
					
					$MailMessage .="<br><br><br><br>";
					$MailMessage .="Best regards,";
					$MailMessage .="<br>";
					$MailMessage .= $rs_acc2['name'];	

					$Sid = md5(uniqid(time()));
					$Headers = "";
					$Headers .= "From: ".$MailFrom." <".$MailFrom.">\r\n" ;
					//$Headers .= "Reply-to: ".$MailFrom." <".$MailFrom.">\r\n" ;
					$Headers .="Mailed-by : cdipthailand.com";

					$Headers .= "MIME-Version: 1.0\r\n" ;
					$Headers .= "Content-Type: multipart/mixed; boundary=\"".$Sid."\"\n\n";
					$Headers .= "This is a multi-part message in MIME format.\n";

					$Headers .= "--".$Sid."\n";
					$Headers .= "Content-type: text/html; charset=utf-8\r\n"; 					
					$Headers .= "Content-Transfer-Encoding: 7bit\n\n";
					$Headers .= $MailMessage."\n\n";

					//$Headers .= "X-Priority: 3\r\n" ;
					//$Headers .= "X-Mailer: PHP mailer\r\n" ;

					
					//*** Files 1 ***//
					$strFilesName1 = "file/meeting/".$rs_files['meeting_file'];
					$strContent1 = chunk_split(base64_encode(file_get_contents($strFilesName1))); 
					$Headers .= "--".$Sid."\n";
					$Headers .= "Content-Type: application/octet-stream; name=\"".$strFilesName1."\"\n"; 
					$Headers .= "Content-Transfer-Encoding: base64\n";
					$Headers .= "Content-Disposition: attachment; filename=\"".$strFilesName1."\"\n\n";
					$Headers .= $strContent1."\n\n";

					$flgSend = @mail($MailTo,$MailSubject,$MailMessage,$Headers,"-f  test@cdipthailand.com ");
					if($flgSend){
					?>
						<script>
							window.alert('Send mail complete');
							//window.location.href='meeting.php';
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
					<td class="b-bottom"><div class="large-4 columns"><h4>Minutes of Meeting >> <?php echo $mode;?></h4></div></td>
				</tr>
				<tr>
					<td class="b-bottom">
						<div class="large-4 columns">
							<?php if(!is_numeric($id)){?>
							<input type="button" name="save_data" id="save_data" value="<?php echo $button?>" class="button-create" OnClick="frm.hdnCmd.value='save_data';JavaScript:return fncSubmit();">
							<?php }else{?>
							<input type="button" name="update_data" id="update_data" value="<?php echo $button?>" class="button-create" OnClick="frm.hdnCmd.value='update_data';JavaScript:return fncSubmit();">
							<input type="button" name="finished_data" id="finished_data" value="Send mail" class="button-create" OnClick="frm.hdnCmd.value='finished_data';JavaScript:return fncSubmit();">
							<?php }?>
							<input type="button" value="Close" class="button-create" onclick="window.location.href='meeting.php'">
						</div>
					</td>
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
									<td colspan="3"><textarea name="purpose_meeting"><?php echo $_SESSION['purpose_meeting']?></textarea></td>
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
								if($rs_account['role_user']==1){$create_by='';}
								else{$create_by=" and create_by='".$rs_account['id_account']."'";}
								if(!is_numeric($id)){$and=" and id_meeting='0'";}
								else{$and=" and id_meeting='".$id."'";}
								$sql_mt_account="select * from meeting_attendee where id_meeting='".$id."'";
								$sql_mt_account .=$and.$create_by;
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
							</table>
							<table style="width: 100%;" cellpadding="0" cellspacing="0" id="tb-meeting">
								<tr>
									<td class="mt-td-right mt-td-bottom top mt-center" width="5%">Items</td>
									<td class="mt-td-right mt-td-bottom top mt-center" width="50%">Descriptions of meeting</td>						
									<td class="mt-td-right mt-td-bottom top mt-center" width="10%">Action by</td>
									<td colspan="2" class="mt-td-bottom top mt-center" width="10%">Finished date</td>
								</tr>
								<?php 
								if(is_numeric($id)){$where=" where id_meeting='".$id."' order by id_meeting_info asc";}else{$where=" where id_meeting='0' order by id_meeting_info asc";}
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
												// This is a check for the CKEditor class. If not defined, the paths must be checked.
												if ( typeof CKEDITOR == 'undefined' ){
													document.write(
														'<strong><span style="color: #ff0000">Error</span>: CKEditor not found</strong>.' +
														'This sample assumes that CKEditor (not included with CKFinder) is installed in' +
														'the "/ckeditor/" path. If you have it installed in a different place, just edit' +
														'this file, changing the wrong paths in the &lt;head&gt; (line 5) and the "BasePath"' +
														'value (line 32).' ) ;
												}else{
													var editor = CKEDITOR.replace( 'meeting_info2',{
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
									<td class="mt-td-bottom"  width="8%">
										<?php
										list($ckmonth,$ckday, $ckyear) = split('[/.-]', $rs_mt_info['finished_date']); 
										echo$ckstart= $ckyear."/".$ckday. "/". $ckmonth;
										?>
									</td>
									<td class="mt-td-bottom" width="5%">
										<a href="<?=$_SERVER["PHP_SELF"];?>?action=edit_meeting_info&id_p=<?=$rs_mt_info['id_meeting_info'];?>&id_u=<?php echo $id?>"><img src="img/edit.png" style="width:20px;"></a>
										<a href="JavaScript:if(confirm('Confirm Delete?')==true){window.location='<?=$_SERVER["PHP_SELF"];?>?action=del_meeting_info&id_p=<? echo $rs_mt_info['id_meeting_info'];?>&id_u=<?php echo $id?>';}"><img src="img/delete.png" style="width:20px;"></a>
									</td>
								</tr>
								<?php } 
								}//end while connet to company contact ?>
								<tr>
									<td class="mt-td-right"><input type="text" name="item"></td>
									<td class="mt-td-right mt-center">										
										<textarea width="100%" id="meeting_info" name="meeting_info"></textarea>    
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
													var editor = CKEDITOR.replace( 'meeting_info',{
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
									<td class="mt-td-right">
										<input type="hidden" name="id_account2" id="id_account2">
										<input type="text" name="action_by" id="action_by" class="action_by">
									</td>
									<td class="mt-td-pd">
										<script>
											$(function() {
												$( "#finished_date" ).datepicker({
													showOn: "button",
													buttonImage: "img/calendar.gif",
													buttonImageOnly: true
												});
											});
										</script>
										<input type="text" id="finished_date" name="finished_date" value="<?php echo date('m/d/Y')?>" style="width: 80%; float: left; margin-right: 2%;"/>				
									</td>
									<td>
										<input name="btnAdd" type="button" id="btnAdd" value="Add" OnClick="frm.hdnCmd.value='add_meeting_info';JavaScript:return fncSubmit();" class="btn-new2" title="Add">
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
							<input type="button" name="update_data" id="update_data" value="<?php echo $button?>" class="button-create" OnClick="frm.hdnCmd.value='update_data';JavaScript:return fncSubmit();">
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
