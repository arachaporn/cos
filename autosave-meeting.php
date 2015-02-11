<?php
ob_start();
include("connect/connect.php");
$sql_account = "SELECT * FROM account WHERE username = '".$_SESSION["Username"]."'  ";
$res_account = mysql_query($sql_account) or die ('Error '.$sql_account);
$rs_account = mysql_fetch_array($res_account);

$date=date('Y-m-d');
//*** Add Condition for attendee***//
if($_POST["hdnCmd"] == "add_attendee"){
	$id=$_POST['mode'];
	if(is_numeric($_POST['mode'])){
		$sql_attendee="select id_account from meeting_attendee";
		$sql_attendee .=" where id_account='".$_POST['id_account']."'";
		$res_attendee=mysql_query($sql_attendee) or die ('Error '.$sql_attendee);
		$rs_attendee=mysql_fetch_array($res_attendee);
		if($rs_attendee['id_account'] != 0){
			$sql="insert into meeting_attendee(id_meeting,id_account,attendee_name";
			$sql .=",attendee_position,company,attendee_email,create_by)";
			$sql .=" values('".$id."','".$_POST['id_account']."'";
			$sql .=",'".$_POST['employee_name']."','".$_POST['position']."'";
			$sql .=",'".$_POST['company']."','".$_POST['email']."'";
			$sql .=",'".$_POST['create_by']."')";
			$rs=mysql_query($sql) or die ('Error '.$sql);						
		}else{
			$sql="insert into meeting_attendee(id_meeting,attendee_name";
			$sql .=",attendee_position,company,attendee_email,create_by)";
			$sql .=" values('".$id."','".$_POST['employee_name']."'";
			$sql .=",'".$_POST['position']."','".$_POST['company']."'";
			$sql .=",'".$_POST['email']."','".$_POST['create_by']."')";
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
			$sql .=" values('".$id."','".$_POST['id_account']."'";
			$sql .=",'".$_POST['employee_name']."','".$_POST['position']."'";
			$sql .=",'".$_POST['company']."','".$_POST['email']."'";
			$sql .=",'".$_POST['create_by']."')";
			$rs=mysql_query($sql) or die ('Error '.$sql);
		}else{
			$sql="insert into meeting_attendee(id_meeting,attendee_name";
			$sql .=",attendee_position,company,attendee_email,create_by)";
			$sql .=" values('".$id."','".$_POST['employee_name']."'";
			$sql .=",'".$_POST['position']."','".$_POST['company']."'";
			$sql .=",'".$_POST['email']."','".$_POST['create_by']."')";
			$rs=mysql_query($sql) or die ('Error '.$sql);	
		}					
	}
}//end add attendee

						
//*** add_meeting_info***//
if($_POST["hdnCmd"] == "add_meeting_info"){
	if(is_numeric($_POST['mode'])){
		list($ckmonth,$ckday, $ckyear) = split('[/.-]', $_POST['finished_date']); 
		$ckstart= $ckyear."-".$ckmonth. "-" .$ckday;

		$sql="insert into meeting_info(id_meeting,title_meeting,create_by,create_date)";
		$sql .=" values('".$_POST['mode']."','".$_POST['title_meeting']."'";
		$sql .=",'".$rs_account['id_account']."','".$date."')";
		$rs=mysql_query($sql) or die ('Error '.$sql);
	}else{
		list($ckmonth,$ckday, $ckyear) = split('[/.-]', $_POST['finished_date']); 
		$ckstart= $ckyear."-".$ckmonth. "-" .$ckday;

		$sql="insert into meeting_info(title_meeting,create_by,create_date)";
		$sql .=" values('".$_POST['title_meeting']."','".$rs_account['id_account']."'";
		$sql .=",'".$date."')";
		$rs=mysql_query($sql) or die ('Error '.$sql);
	}
}//end add meeting info
			
//*** Delete meeting info ***//
if($_POST["action"] == "del_meeting_info"){
	$sql = "delete from meeting_info ";
	$sql .="where id_meeting_info = '".$_GET["id_p"]."'";
	$res = mysql_query($sql) or die ('Error '.$sql);
}//end delete meeting info

//*** Update meeting info ***//
if($_POST["hdnCmd"] == "update_meeting_info"){
	list($ckmonth,$ckday, $ckyear) = split('[/.-]', $_POST['finished_date2']); 
	$ckstart= $ckyear."-".$ckmonth. "-" .$ckday;

	$sql = "update meeting_info set description='".$_POST['meeting_info2']."'";
	$sql .=",action_by= '".$_POST['action_by2']."',finished_date='".$ckstart."'";
	$sql .=" where id_meeting_info = '".$_POST["hdnEdit"]."' ";
	$res = mysql_query($sql) or die ('Error '.$sql);
}//end update meeting info

if($_POST["hdnCmd"] == "add_meeting_info_detail"){
	if(is_numeric($_POST['mode'])){
		list($ckmonth,$ckday, $ckyear) = split('[/.-]', $_POST['finished_date']); 
		$ckstart= $ckyear."-".$ckmonth. "-" .$ckday;
					
		$sql="select * from meeting_info";
		$res=mysql_query($sql) or die ('Error '.$sql);
		$rs=mysql_fetch_array($res);
		if($rs['id_meeting_info']==$_POST['id_meeting_info']){
			$sql_detail="insert into meeting_info_detail(id_meeting_info";
			$sql_detail .=",description,action_by,finished_date) values";
			$sql_detail .=" ('".$id_info."','".$_POST['meeting_info']."'";
			$sql_detail .=",'".$_POST['action_by']."','".$ckstart."')";
			$res_detail=mysql_query($sql_detail) or die ($sql_detail);
		}else{
			$sql_info="insert into meeting_info(id_meeting,title_meeting";
			$sql_info .=",create_by,create_date)";
			$sql_info .=" values('".$_POST['mode']."','".$_POST['title_meeting']."'";
			$sql_info .=",'".$rs_account['id_account']."','".$date."')";
			$rs_info=mysql_query($sql_info) or die ('Error '.$sql_info);
			$id_info=mysql_insert_id();

			$sql_detail="insert into meeting_info_detail(id_meeting_info";
			$sql_detail .=",description,action_by,finished_date) values";
			$sql_detail .=" ('".$id_info."','".$_POST['meeting_info']."'";
			$sql_detail .=",'".$_POST['action_by']."','".$ckstart."')";
			$res_detail=mysql_query($sql_detail) or die ($sql_detail);
		}
	}else{
		list($ckmonth,$ckday, $ckyear) = split('[/.-]', $_POST['finished_date']); 
		$ckstart= $ckyear."-".$ckmonth. "-" .$ckday;

		$sql="insert into meeting_info(title_meeting,create_by,create_date)";
		$sql .=" values('".$_POST['title_meeting']."'";
		$sql .=",'".$rs_account['id_account']."','".$date."')";
		$rs=mysql_query($sql) or die ('Error '.$sql);
	}
}//end add meeting info deatail

//save data to db
//if($_POST["hdnCmd"] == "save_data"){	
if($_POST["mode"] == "New"){	
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
	$sql2 .=",'".$_POST['meeting_time']."','".$date_meeting."','".$_POST['start_meeting']."'";
	$sql2 .=",'".$_POST['end_meeting']."','".$_POST['location_meeting']."'";
	$sql2 .=",'".$_POST['purpose_meeting']."','".$next_meeting."'";
echo	$sql2 .=",'".$date_next_meeting."','".$date."','".$rs_account['id_account']."')";
//	$res2=mysql_query($sql2) or die ('Error '.$sql2);

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
					
	//create pdf
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
					<td colspan="2" style="text-align:left;"><?php if($rs['purpose_meeting']==null){echo '-';}else{echo $rs['purpose_meeting'];}?></td>
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
				<?php }//end whil attendee	?>
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
						echo 'วาระที่ '.$i_info;
						echo '</div>';
						echo $rs_info['description'];
						echo 'Action by : ';
						echo $rs_info['action_by'];
						echo 'Finished date : ';
						echo $rs_info['finished_date'].'<br>';
						echo '<div style="border-bottom:1px solid #eee;">&nbsp;</div>';
					}//end meetin info	
					?>
				</div>
		</body>
	</html>
	<?php
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
		$sql_file .=" values('".$id."','".$file_name."')";
		$res_file=mysql_query($sql_file) or die ('Error '.$sql_file);
	}//end if files
?>
	<script>
		//window.location.href='ac-meeting.php?id_u=<?=$id_meeting?>';
	</script>
<?php }//end save data
//update data
//if($_POST["hdnCmd"] == "update_data"){
else{
	$id=$_POST['mode'];
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

	$sql_meeting="select * from meeting where meeting_name like '".$_POST['meeting_name']."'";
	$sql_meeting .=" and id_meeting='".$id."'";
	$res_meeting=mysql_query($sql_meeting) or die ('Error '.$sql_meeting);
	$rs_meeting=mysql_fetch_array($res_meeting);
	if($rs_meeting){$meeting_time=$_POST['meeting_time'];}
	else{$meeting_time=1;}
	
	$sql2="update meeting set meeting_name='".$_POST['meeting_name']."'";
	$sql2 .=",writen_by='".$_POST['id_writen']."'";
	$sql2 .=",meeting_time='".$meeting_time."'";
	$sql2 .=",date_meeting='".$date_meeting."'";
	$sql2 .=",start_meeting='".$_POST['start_meeting']."'";
	$sql2 .=",end_meeting='".$_POST['end_meeting']."'";
	$sql2 .=",location_meeting='".$_POST['location_meeting']."'";
	$sql2 .=",purpose_meeting='".$_POST['purpose_meeting']."'";
	$sql2 .=",next_meeting='".$next_meeting."'";
	$sql2 .=",date_next_meeting='".$date_next_meeting."'";
	$sql2 .=" where id_meeting='".$id."'";
	$res2=mysql_query($sql2) or die ('Error '.$sql2);
					
	$sql="select * from meeting where id_meeting='".$id."'";
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
					<td colspan="2" style="text-align:left;"><?php if($rs['purpose_meeting']==null){echo '-';}else{echo $rs['purpose_meeting'];}?></td>
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
				$sql_info= "select * from meeting_info where id_meeting='".$id."' order by id_meeting_info asc";
				$res_info = mysql_query($sql_info) or die ('Error '.$sql_info);
				$i_info=0;
				while($rs_info=mysql_fetch_array($res_info)){
					$i_info++;
					echo '<div style="padding-top:1.5%;font-size:1.2em;font-weight:bold;">';
				    echo 'วาระที่ '.$i_info;
					echo '</div>';
					echo $rs_info['description'];
					echo 'Action by : ';
					echo $rs_info['action_by'];
					echo 'Finished date : ';
					echo $rs_info['finished_date'].'<br>';
					echo '<div style="border-bottom:1px solid #eee;">&nbsp;</div>';
				}	
				?>
			</div>
		</body>
	</html>
	<?php
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
		$sql_file .=" values('".$id."','".$file_name."')";
		$res_file=mysql_query($sql_file) or die ('Error '.$sql_file);
	}
	else{
		$sql_file="update meeting_file set meeting_file='".$file_name."'";
		$sql_file .=" where id_meeting_file='".$rs_files['id_meeting_file']."'";
		$res_file=mysql_query($sql_file) or die ('Error '.$sql_file);
	}
	?>
	<script>
		//window.location.href='ac-meeting.php?id_u=<?=$id?>';
	</script>
<?php }//end update data

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

	$flgSend = @mail($MailTo,$MailSubject,null,$Headers,"-f  test@cdipthailand.com ");
	if($flgSend){
	?>
		<script>
			window.alert('Send mail complete');
			window.location.href='meeting.php';
		</script>
	<?php }else{?>
		<script>
			window.alert('Send mail False');
			window.location.href='meeting.php';
		</script>
	<?php } 
}

ob_end_flush();
?>
