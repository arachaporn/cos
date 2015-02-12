<?php
session_start();
if($_SESSION["Username"] == ""){
	header("location:index.php");
	exit();
}
include("connect/connect.php");
$sql_account = "SELECT * FROM account WHERE username = '".$_SESSION["Username"]."'  ";
$res_account = mysql_query($sql_account) or die ('Error '.$sql_account);
$rs_account = mysql_fetch_array($res_account);

$date=date("Y-m-d");
$modify=date("Y-m-d H:i:s");
$time=date("H:i");
if($_POST['mode']=='New'){

	if($_POST['id_type_device']== -1){
		$sql_device="insert into ";
	}

	list($day, $month, $year) = split('[/.-]', $_POST['check_next_date']); 
	$check_next_date= $year . "-". $day . "-" . $month;

	$sql="insert into sd_device(check_device,check_next_date,id_type_device";
	$sql .=",other_device,device_code,id_employee,user_use,check_date,status,create_date)";
	$sql .=" values('".$_POST['check_device']."','".$check_next_date."'";
	$sql .=",'".$_POST['id_type_device']."','".$_POST['other_device']."'";
	$sql .=",'".$_POST['device_code']."','".$_POST['employee_code']."'";
	$sql .=",'".$_POST['name']."','".$date."','2','".$date."')";
	$res=mysql_query($sql) or die ('Error '.$sql);
	
	$id_sd_device=mysql_insert_id();

	/*add array default program */
	$default_program_array=$_POST['program'];
	$tag_string="";
	while (list ($key,$val) = @each ($default_program_array)) {
	//echo "$val,";
	$tag_string.=$val.",";
	}
	$default_program=substr($tag_string,0,(strLen($tag_string)-1));// remove the last , from string

	$sql_program="insert into sd_program(id_sd_device,device_code,os,cpu,mainboard,ram,hdd";
	$sql_program .=",vga,monitor,model,default_program,special_program,remark)";
	$sql_program .=" values('".$id_sd_device."','".$_POST['device_code']."','".$_POST['os']."'";
	$sql_program .=",'".$_POST['cpu']."','".$_POST['mainboard']."','".$_POST['ram']."'";
	$sql_program .=",'".$_POST['hdd']."','".$_POST['vga']."','".$_POST['monitor']."'";
	$sql_program .=",'".$_POST['model']."','".$default_program."','".$_POST['special_program']."'";
	$sql_program .=",'".$_POST['remark']."')";
	$res_program=mysql_query($sql_program) or die ('Error '.$sql_program);

	$sql_rela="insert into sd_relation(id_type_device,id_sd_device";
	$sql_rela .=",sd_rela_date,id_employee) values ('".$_POST['id_type_device']."'";
	$sql_rela .=",'".$id_sd_device."','".$date."','".$_POST['employee_code']."')";
	$res_rela=mysql_query($sql_rela) or die ($sql_rela);
?>
	<script>
		window.location.href='ac-check-computer.php?id_u=<?=$id_sd_device?>';
	</script>
<?php 
}else{
	if($_REQUEST['submit_device']=='ok'){
	$mode=$_REQUEST['id_u'];
	
	$sql ="update sd_breakdown set complete_time='".$time."'";
	$sql .=",status=1 where id_break_down='".$mode."' and status='2'";
	$res=mysql_query($sql) or die ('Error '.$sql);
?>                       
	<script>
		window.location.href='ac-breakdown.php?id_u=<?echo $mode?>&submit_device=ok';
	</script>
<?php }else{
	//กำหนดแล้วเสร็จ
	list($day, $month, $year) = split('[/.-]', $_POST['check_next_date']); 
	$check_next_date= $year . "-". $day . "-" . $month;

	/*add array default program */
	$default_program_array=$_POST['program'];
	$tag_string="";
	while (list ($key,$val) = @each ($default_program_array)) {
	//echo "$val,";
	$tag_string.=$val.",";
	}
	$default_program=substr($tag_string,0,(strLen($tag_string)-1));// remove the last , from string

	$id_sd_device=$_POST['mode'];
	
	$sql="select * from sd_device inner join sd_program";
	$sql .=" on sd_device.id_sd_device='".$id_sd_device."'";
	$sql .=" and sd_device.id_sd_device=sd_program.id_sd_device";
	$sql .=" order by sd_device.id_sd_device desc";
	$res=mysql_query($sql) or die ('Error '.$sql);
	$rs=mysql_fetch_array($res);
	if( ($rs['check_device'] != $_POST['check_device']) || ($rs['check_next_date'] != $check_next_date)
		|| ($rs['id_employee'] != $_POST['employee_code']) || ($rs['os'] != $_POST['os']) 
		|| ($rs['cpu'] != $_POST['cpu']) || ($rs['mainboard'] != $_POST['mainboard'])
		|| ($rs['ram'] != $_POST['ram']) || ($rs['hdd'] != $_POST['hdd'])
		|| ($rs['vga'] != $_POST['vga']) || ($rs['monitor'] != $_POST['monitor'])
		|| ($rs['model'] != $_POST['model']) || ($rs['default_program'] != $default_program)
		|| ($rs['special_program'] != $_POST['special_program']) || ($rs['remark'] != $_POST['remark']) ){
		$sql_device="insert into sd_device(check_device,check_next_date,id_type_device";
		$sql_device .=",other_device,device_code,id_employee,user_use,status)";
		$sql_device .=" values('".$_POST['check_device']."','".$check_next_date."'";
		$sql_device .=",'".$_POST['id_type_device']."','".$_POST['other_device']."'";
		$sql_device .=",'".$_POST['device_code']."','".$_POST['employee_code']."'";
		$sql_device .=",'".$_POST['name']."','2')";
		$res_device=mysql_query($sql_device) or die ('Error '.$sql_device);
		$id_sd_device=mysql_insert_id();

		$sql_program="insert into sd_program(id_sd_device,device_code,os,cpu,mainboard,ram,hdd";
		$sql_program .=",vga,monitor,model,default_program,special_program,remark)";
		$sql_program .=" values('".$id_sd_device."','".$_POST['device_code']."','".$_POST['os']."'";
		$sql_program .=",'".$_POST['cpu']."','".$_POST['mainboard']."','".$_POST['ram']."'";
		$sql_program .=",'".$_POST['hdd']."','".$_POST['vga']."','".$_POST['monitor']."'";
		$sql_program .=",'".$_POST['model']."','".$default_program."','".$_POST['special_program']."'";
		$sql_program .=",'".$_POST['remark']."')";
		$res_program=mysql_query($sql_program) or die ('Error '.$sql_program);

		$sql_rela="insert into sd_relation(id_type_device,id_sd_device";
		$sql_rela .=",sd_rela_date,id_employee) values ('".$_POST['id_type_device']."'";
		$sql_rela .=",'".$id_sd_device."','".$date."','".$_POST['employee_code']."')";
		$res_rela=mysql_query($sql_rela) or die ($sql_rela);
	}else{
		$sql="update sd_device set device_code='".$_POST['device_code']."',check_device='".$_POST['check_device']."'";
		$sql .=",check_next_date='".$check_next_date."',id_type_device='".$_POST['id_type_device']."'";
		$sql .=",other_device='".$_POST['other_device']."',id_employee='".$_POST['employee_code']."'";
		$sql .=",user_use='".$_POST['name']."' where id_sd_device='".$_POST['mode']."'";
		$res=mysql_query($sql) or die ('Error '.$sql);

		$sql_program="update sd_program set os='".$_POST['os']."',cpu='".$_POST['cpu']."'";
		$sql_program .=",mainboard='".$_POST['mainboard']."',ram='".$_POST['ram']."'";
		$sql_program .=",hdd='".$_POST['hdd']."',vga='".$_POST['vga']."'";
		$sql_program .=",monitor='".$_POST['monitor']."',model='".$_POST['model']."'";
		$sql_program .=",default_program='".$default_program."'";
		$sql_program .=",special_program='".$_POST['special_program']."'";
		$sql_program .=",remark='".$_POST['remark']."'";
		$sql_program .=" where id_sd_device='".$_POST['mode']."'";
		$res_program=mysql_query($sql_program) or die ('Error '.$sql_program);

		$sql_rela="update sd_relation set id_type_device='".$_POST['id_type_device']."'";
		$sql_rela .=",sd_rela_date='".$date."',id_employee='".$_POST['employee_code']."'";
		$sql_rela .=" where id_sd_device='".$id_sd_device."'";
		$res_rela=mysql_query($sql_rela) or die ($sql_rela);
	}
?>
	<script>
		window.location.href='ac-check-computer.php?id_u=<?=$id_sd_device?>';
	</script>
<?php 
	} 
}
?>