<?php
@session_start();
if($_SESSION["Username"] == ""){
	header("location:index.php");
	exit();
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
</head>
<body>
<?
$date=date("Y-m-d");
$modify=date("Y-m-d H:i:s");
$time=date("H:i");
if($_POST['mode']=='New'){
	if($_POST["hdnCmd"] == "save_data"){
		list($day, $month, $year) = split('[/.-]', $_POST['proposal_date']); 
		$check_next_date= $year . "-". $day . "-" . $month;

		$sql="insert into npd_inno_proposal(proposal_no,proposal_date";
		$sql .=",name_th_proposal,name_en_proposal,person_join";
		$sql .=",project_overview,objective,target_project,create_by";
		$sql .=",create_date,inno_status)";
		$sql .=" values('".$_POST['proposal_no']."','".$_POST['proposal_date']."'";
		$sql .=",'".$_POST['name_th_proposal']."','".$_POST['name_en_proposal']."'";
		$sql .=",'".$_POST['person_join']."','".$_POST['project_overview']."'";
		$sql .=",'".$_POST['objective']."','".$_POST['target_project']."'";
		echo$sql .=",'".$rs_account['id_account']."','".$date."','".$_POST['proposal_type']."')";
		//$res=mysql_query($sql) or die ('Error '.$sql);
		
		$id_proposal=mysql_insert_id();
		
		$sql_keyword="update npd_inno_keyword set id_proposal='".$id_proposal."'";
		echo$sql_keyword .=" where id_proposal='0'";
		//$res_keyword=mysql_query($sql_keyword) or die ('Error '.$sql_keyword);
	}

	//*** add keyword ***//
	if($_POST["hdnCmd"] == "add_keyword"){
		$id_proposal='New';
		$date=date('Y-m-d');
		
		//insert to keyword
		$sql_keyword="insert into npd_inno_keyword (keyword_th,keyword_en)";
		$sql_keyword .=" values('".$_POST['keyword_th']."','".$_POST['keyword_en']."')";
		$res_keyword=mysql_query($sql_keyword) or die ('Error '.$sql_keyword);
	}

	//*** update keyword ***//
	if($_POST["hdnCmd"] == "update_keyword"){
		$id_proposal='New';
		$sql = "update npd_inno_keyword set keyword_th='".$_POST['keyword_th2']."'";
		$sql .=",keyword_en= '".$_POST['keyword_en2']."' where id_keyword='".$_POST["hdnEdit"]."' ";
		$res = mysql_query($sql) or die ('Error '.$sql);
	}

?>
	<script>
		window.location.href='ac-proposal-app.php?id_u=<?=$id_proposal?>&p=1';
	</script>
<?php 
}else{
	$id_proposal=$_POST['mode'];
	//*** add keyword ***//
	if($_POST["hdnCmd"] == "add_keyword"){
		
		$date=date('Y-m-d');
		
		//insert to keyword
		$sql_keyword="insert into npd_inno_keyword (id_proposal,keyword_th,keyword_en)";
		$sql_keyword .=" values('".$id_proposal."','".$_POST['keyword_th']."'";
		$sql_keyword .=",'".$_POST['keyword_en']."')";
		//$res_keyword=mysql_query($sql_keyword) or die ('Error '.$sql_keyword);
	}
	
	//*** update keyword ***//
	if($_POST["hdnCmd"] == "update_keyword"){
		$sql = "update npd_inno_keyword set keyword_th='".$_POST['keyword_th2']."'";
		$sql .=",keyword_en= '".$_POST['keyword_en2']."' where id_keyword='".$_POST["hdnEdit"]."' ";
		//$res = mysql_query($sql) or die ('Error '.$sql);
	}
?>                       
	<script>
		window.location.href='ac-proposal-app.php?id_u=<?=$id_proposal?>&p=1';
	</script>
<?php } ?>
</body>
</html>