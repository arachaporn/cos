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
			$month=$_GET['month'];
			$year=$_GET['year'];
			$st=$_GET['st'];
			//*** Add Condition ***//
			if($_POST["hdnCmd"] == "Add"){
				$date=date('Y-m-d');							
				
				$sql="insert into sm_sales_target(month_visited,year_visited";
				$sql .=",sales_target,create_by,create_date,type_target)";
				$sql .=" values('".$_POST["month"]."','".$_POST["year"]."'";
				$sql .=",'".$_POST['sales_target']."'";
				$sql .=",'".$rs_account['id_account']."','".$date."'";
				$sql .=",'".$_POST['st2']."')";
				$res = mysql_query($sql) or die ('Error '.$sql);
				$st=$_POST['st2'];
			}

			//*** Delete Condition ***//
			if($_GET["action"] == "del"){
				$sql = "delete from sm_sales_target ";
				$sql .="where id_sales_target = '".$_GET["id_p"]."'";
				$res = mysql_query($sql) or die ('Error '.$sql);
				//header("location:$_SERVER[PHP_SELF]");
				//exit();
			}?>
			<form name="frm" method="post" action="<?=$_SERVER["PHP_SELF"]?>?month=<?=$month?>&year=<?=$year?>">
			<input type="hidden" name="hdnCmd" value="">
			<table style="border: 0; width: 100%;" cellpadding="0" cellspacing="0">
				<tr>
					<td class="b-bottom" colspan='5'><div class="large-4 columns"><h4>Target >> <?php echo date('F', mktime(0, 0, 0, $_GET['month'])).'&nbsp;'.$_GET['year']?></h4></div></td>
				</tr>
				<tr>
					<td style="background: #fff;">
						<div class="large-4 columns">						
							<input type="hidden" name="mode" value="<?php echo $id?>">
							<input type="hidden" name="month" value="<?php echo $_GET['month']?>">
							<input type="hidden" name="year" value="<?php echo $_GET['year']?>">
							<input type="hidden" name="st2" value="<?php echo $st?>">
							<table style="border:none;width: 100%;" cellpadding="0" cellspacing="0" id="tb-quotation">
								<?php
								$sql="select * from sm_sales_target where month_visited='".$_GET['month']."'";
								$sql .=" and year_visited='".$_GET['year']."' and type_target='".$st."'";
								$res=mysql_query($sql) or die ('Error '.$sql);
								while($rs=mysql_fetch_array($res)){
								?>
								<tr>
									<td><?php echo number_format($rs['sales_target'],2)?></td>
									<td><a href="JavaScript:if(confirm('Confirm Delete?')==true){window.location='<?=$_SERVER["PHP_SELF"];?>?action=del&id_p=<? echo $rs['id_sales_target'].'&month='.$rs['month_visited'].'&year='.$rs['year_visited'].'&st='.$st;?>';}"><img src="img/delete.png" style="width:20px;"></a></td>
								</tr>
								<?php }?>
								<tr>
									<td><input type="text" name="sales_target"></td>
									<td><input name="btnAdd" type="button" id="btnAdd" title="Add" value="Add"  OnClick="frm.hdnCmd.value='Add';JavaScript:return fncSubmit();" class="btn-new2"></td>
									<td style="width:80%;"></td>
								</tr>
							</table>
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
