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
?>
<!DOCTYPE html>
<!--[if IE 8]> 				 <html class="no-js lt-ie9" lang="en" > <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en" > <!--<![endif]-->

<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"/>
<title>COS Project</title>
<link rel="stylesheet" href="css/foundation.css">
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="rmm-css/responsivemobilemenu.css" type="text/css"/>
<script type="text/javascript" src="http://code.jquery.com/jquery.min.js"></script>
<script type="text/javascript" src="rmm-js/responsivemobilemenu.js"></script>
<script src="js/vendor/custom.modernizr.js"></script>

</head>
<body>
	<?php include("menu.php");
	include("paginator.php");
	?>
	<div class="row">
		<div class="background">
		<form name="frmSearch" method="get" action="<?=$_SERVER['SCRIPT_NAME'];?>">
		<table style="border: 0; width: 100%;" cellpadding="0" cellspacing="0">
			<tr>
				<td class="b-bottom" style="text-align:left;">
					<div class="large-4 columns"><a href="../sd.php"><?php echo '< Administration'?></a></div>	
				</td>
				<td class="b-bottom"><div class="large-4 columns"><h4>บันทึกการตรวจเช็คคอมพิวเตอร์และอุปกรณ์ต่อพ่วง</h4></div></td>
				<td class="b-bottom" style="text-align:right;">
					<div class="large-4 columns">
						<input type="button" value="Create" class="btn-new" onclick="window.location.href='ac-check-computer.php?id_u=new'">
						<input type="button" name="del" value="Delete" class="btn-trash" OnClick="return chkdel();">
						<script language="JavaScript">
							function ckall(vol){					
								var i=1;
								for(i=1;i<=document.frmMain.hdnCount.value;i++){
									if(vol.checked == true){
										eval("document.frmMain.ck_del"+i+".checked=true");
									}
									else{
										eval("document.frmMain.ck_del"+i+".checked=false");
									}
								}
							}
						</script>
					</div>
				</td>
			</tr>
			<tr>
				<td colspan="3">
					<table style="border: 0; width: 100%; margin:0;" cellpadding="0" cellspacing="0">
						<tr>
							<td style="text-align:left; width: 15%; vertical-align:top;"><div class="large-4 columns">Search device</div>
							<td style="width: 20%;"><input name="txtKeyword" type="text" id="txtKeyword" value="<?=$_GET["txtKeyword"];?>"></td>
							<td style="vertical-align:top;"><input type="submit" value="Search"></td>
						</tr>
					</table>
				</td>						
			</tr>
			<tr>
				<td style="background: #fff;" colspan="3">
					<?php
					if($_GET["txtKeyword"] != ""){
						$strSearch=$_GET["txtKeyword"];
						
						$sql="select * from sd_device inner join type_device";
						$sql .=" on sd_device.id_type_device=type_device.id_type_device";
						$sql .=" and type_device.title_device like '%".$strSearch."%' ";
						$res=mysql_query($sql) or die('Error '.$sql);
						$num_row = mysql_num_rows($res);

						$Per_Page = 20;   // Per Page

						$Page = $_GET["Page"];
						if(!$_GET["Page"]){
							$Page=1;
						}

						$Prev_Page = $Page-1;
						$Next_Page = $Page+1;

						$Page_Start = (($Per_Page*$Page)-$Per_Page);
						if($num_row<=$Per_Page){
							$Num_Pages =1;
						}
						else if(($num_row % $Per_Page)==0){
							$Num_Pages =($num_row/$Per_Page) ;
						}
						else{
							$Num_Pages =($num_row/$Per_Page)+1;
							$Num_Pages = (int)$Num_Pages;
						}

						$sql .=" order by id_sd_device desc LIMIT $Page_Start , $Per_Page";
						$res = mysql_query($sql) or die ('Error '.$sql);
					?>
						<div class="large-4 columns">
							<table style="border: 1 solid #eee; width: 100%;" cellpadding="0" cellspacing="0">
								<tr class="top">
									<td width="2%"class="center"><input type="checkbox" name="ck_all" id="ck_all" value="Y" onclick="ckall(this);" style="margin:0;padding:0;"></td>
									<td width="3%">No.</td>
									<td width="10%">Code computer</td>
									<td width="10%">Empolyee name</td>
									<td width="10%" class="center">Department</td>
									<td width="10%" class="center">Device</td>
									<td width="10%" class="center">Next Time</td>
									<td width="10%" class="center">Status</td>
								</tr>
								<?php
								$i=0;
								while($rs=mysql_fetch_array($res)){
									$i++;
								?>
								<tr>
									<script language="JavaScript">
										function chkdel(){
											if(confirm('Are you sure you want to delete?')){
												return true; // delete 
											}else{
												return false; // Cancel 
											}
										}
									</script>
									<td class="center"><input type="checkbox" name="ck_del[]" id="ck_del<?php echo $i?>" value="<?php echo $rs['id_sd_device'];?>"></td>
									<td><?php echo $i;?></td>
									<td><a href="ac-check-computer.php?id_u=<?php echo $rs['id_sd_device']?>"><?php echo $rs['device_code'];?></td>
									<td>
									<?php 
										$sql_account2="select * from account where id_employee='".$rs['id_employee']."'";
										$res_account2=mysql_query($sql_account2) or die ('Error '.$sql_account2);
										$rs_account2=mysql_fetch_array($res_account2);
										echo $rs_account2['name'];
										$department=$rs_account2['id_department'];
									?>
									</td>
									<td class="center">
									<?php
										$sql_department="select * from department where id_department='".$department."'";
										$res_department=mysql_query($sql_department) or die ('Error '.$sql_department);
										$rs_department=mysql_fetch_array($res_department);
										echo $rs_department['title'];
									?>
									</td>
									<td class="center"><?php if($rs['id_type_device']== -1){echo $rs['other_device'];}else{echo $rs['title_device'];}?>
									</td>
									<td class="center"><?php echo $rs['check_next_date'];?></td>
									<td class="center"><?php if($rs['status']==1){echo 'Success';}else{echo 'On process';}?></td>
								</tr>
								<?php } ?>
								<input type="hidden" name="hdnCount" value="<?=$i;?>">
							</table>
						</div>
					<?php }else{
						$sql="select * from sd_device";
						$res=mysql_query($sql) or die('Error '.$sql);
						$num_row = mysql_num_rows($res);

						$Per_Page = 20;   // Per Page

						$Page = $_GET["Page"];
						if(!$_GET["Page"]){
							$Page=1;
						}

						$Prev_Page = $Page-1;
						$Next_Page = $Page+1;

						$Page_Start = (($Per_Page*$Page)-$Per_Page);
						if($num_row<=$Per_Page){
							$Num_Pages =1;
						}
						else if(($num_row % $Per_Page)==0){
							$Num_Pages =($num_row/$Per_Page) ;
						}
						else{
							$Num_Pages =($num_row/$Per_Page)+1;
							$Num_Pages = (int)$Num_Pages;
						}

						$sql .=" order by id_sd_device desc LIMIT $Page_Start , $Per_Page";
						$res = mysql_query($sql) or die ('Error '.$sql);
					?>
						<div class="large-4 columns">
							<table style="border: 1 solid #eee; width: 100%;" cellpadding="0" cellspacing="0">
								<tr class="top">
									<td width="2%"class="center"><input type="checkbox" name="ck_all" id="ck_all" value="Y" onclick="ckall(this);" style="margin:0;padding:0;"></td>
									<td width="3%">No.</td>
									<td width="10%">Code computer</td>
									<td width="10%">Empolyee name</td>
									<td width="10%" class="center">Department</td>
									<td width="10%" class="center">Device</td>
									<td width="10%" class="center">Next Time</td>
									<td width="10%" class="center">Status</td>
								</tr>
								<?php
								$i=0;
								while($rs=mysql_fetch_array($res)){
									$i++;
								?>
								<tr>
									<script language="JavaScript">
										function chkdel(){
											if(confirm('Are you sure you want to delete?')){
												return true; // delete 
											}else{
												return false; // Cancel 
											}
										}
									</script>
									<td class="center"><input type="checkbox" name="ck_del[]" id="ck_del<?php echo $i?>" value="<?php echo $rs['id_sd_device'];?>"></td>
									<td><?php echo $i;?></td>
									<td><a href="ac-check-computer.php?id_u=<?php echo $rs['id_sd_device']?>"><?php echo $rs['device_code'];?></td>
									<td>
									<?php 
										$sql_account2="select * from account where id_employee='".$rs['id_employee']."'";
										$res_account2=mysql_query($sql_account2) or die ('Error '.$sql_account2);
										$rs_account2=mysql_fetch_array($res_account2);
										echo $rs_account2['name'];
										$department=$rs_account2['id_department'];
									?>
									</td>
									<td class="center">
									<?php
										$sql_department="select * from department where id_department='".$department."'";
										$res_department=mysql_query($sql_department) or die ('Error '.$sql_department);
										$rs_department=mysql_fetch_array($res_department);
										echo $rs_department['title'];
									?>
									</td>
									<td class="center">
									<?php 
									if($rs['id_type_device']== -1){echo $rs['other_device'];}
									else{
										$sql_type_device="select * from type_device where id_type_device='".$rs['id_type_device']."'";
										$res_type_device=mysql_query($sql_type_device) or die ('Error '.$sql_type_device);
										$rs_type_device=mysql_fetch_array($res_type_device);
										echo $rs_type_device['title_device'];
									}
									?>
									</td>
									<td class="center"><?php echo $rs['check_next_date'];?></td>
									<td class="center"><?php if($rs['status']==1){echo 'Success';}else{echo 'On process';}?></td>
								</tr>
								<?php } ?>
								<input type="hidden" name="hdnCount" value="<?=$i;?>">
							</table>
						</div>
					<?php }?>
				</td>
			</tr>
			<tr>
				<td colspan="3">
					<div class="large-4 columns">Total <?= $num_row;?> Record 
						<?php
						$pages = new Paginator;
						$pages->items_total = $num_row;
						$pages->mid_range = 10;
						$pages->current_page = $Page;
						$pages->default_ipp = $Per_Page;
						$pages->url_next = $_SERVER["PHP_SELF"]."?QueryString=value&Page=";

						$pages->paginate();

						echo $pages->display_pages()
						?>	
					</div>
				</td>
			</tr>
		</table>
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
