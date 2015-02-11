<!DOCTYPE html>
<!--[if IE 8]> 	<html class="no-js lt-ie9" lang="en" > <![endif]-->
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
</head>
<body>
	<?php include("menu.php");?>
	<?php
	include("connect/connect.php");
	//*** Add Condition ***//
	if($_POST["hdnCmd"] == "Add"){
		$sql = "insert into positions(id_department,title) ";
		$sql .="values('0','".$_POST["position"]."') ";
		$res = mysql_query($sql) or die ('Error '.$sql);
		//header("location:$_SERVER[PHP_SELF]");
		//exit();
	}

	//*** Update Condition ***//
	if($_POST["hdnCmd"] == "Update"){
		$sql = "update positions set title= '".$_POST["edit_position"]."'";
		$sql .="where id_position = '".$_POST["hdnEdit"]."' ";
		$res = mysql_query($sql) or die ('Error '.$sql);
		//header("location:$_SERVER[PHP_SELF]");
		//exit();
	}

	//*** Delete Condition ***//
	if($_GET["action"] == "del"){
		$sql = "delete from positions ";
		$sql .="where id_position = '".$_GET["id_p"]."'";
		$res = mysql_query($sql) or die ('Error '.$sql);
		//header("location:$_SERVER[PHP_SELF]");
		//exit();
	}
	?>
	<div class="row">
		<div class="background">
		<table style="border: 0; width: 100%;" cellpadding="0" cellspacing="0">
			<tr>
				<td>
					<div class="large-4 columns">
						<?php
						if($_GET["id_u"]=='new'){
							$mode=$_GET["id_u"];
							$button='Save';
							$id='new';
						}
						else{
							$id=$_GET["id_u"];
							$sql="select * from department where id_department='".$id."'";
							$res=mysql_query($sql) or die ('Error '.$sql);
							$rs=mysql_fetch_array($res);
							$mode='Edit '.$rs['title'];
							$button='Update';
						}
						?>
						<h4>Department >> <?php echo $mode;?></h4>
						<form name="frmdb" method="post" action="dbdepartment.php" style="margin:0;">
						<input type="submit" name="btnDep" value="<?php echo $button?>" class="button-create">
						<input type="button" value="Close" class="button-create" onclick="window.location.href='department.php'">
						<input type="hidden" name="mode" value="<?php echo $id?>">
						<table style="border: none; width: 100%; margin:0;" cellpadding="0" cellspacing="0">
							<tr>
								<td style="width: 10%;">Title</td>
								<td><input type="text" name="title" id="txt" value="<?php echo $rs['title']?>"></td>
							</tr>
						</table>
						</form>
						<table style="border: none; width: 100%;" cellpadding="0" cellspacing="0">
							<tr style="background: #fff;">
								<td style="vertical-align: top; width: 10%;">Position</td>
								<td>
									<form name="frmMain" method="post" action="<?=$_SERVER["PHP_SELF"]."?id_u=".$id;?>">
										<input type="hidden" name="hdnCmd" value="">
										<table style="width:50%; border:none; font-size: 14px;">
											<tr class="top">
												<td width="5%">No.</td>
												<td width="30%" class="center">Title</td>
												<td width="5%" class="center">Action</td>
											</tr>
											<?php
											$i=0;
											$sql2 = "select * from positions where id_department='".$id."' or id_department='0'";
											$res2 = mysql_query($sql2) or die ('Error '.$sql2);
											while($rs2=mysql_fetch_array($res2)){
												$i++;
												if($rs2['id_position'] == $_GET['id_p'] and $_GET["action"] == 'edit'){
											?>
												<tr>
													<input type="hidden" name="hdnEdit" value="<?php echo $rs2['id_position']?>">
													<td><?php echo $i?></td>
													<td><input type="text" name="edit_position" value="<?php echo $rs2['title']?>"></td>
													<td class="center">
														<input name="btnAdd" type="button" id="btnUpdate" value="Update" OnClick="frmMain.hdnCmd.value='Update';frmMain.submit();" class="btn-update">
														<input name="btnAdd" type="button" id="btnCancel" value="Cancel" OnClick="window.location='<?=$_SERVER["PHP_SELF"]."?id_u=".$id;?>';" class="btn-cancel">
													</td>
												</tr>
											<?php
												}else{
											?>
												<tr>
													<td><?php echo $i?></td>
													<td><?php echo $rs2['title']?></td>
													<td class="center">
														<a href="<?=$_SERVER["PHP_SELF"];?>?action=edit&id_p=<?=$rs2['id_position'];?>&id_u=<?php echo $id?>"><img src="img/edit.png" style="width:20px;"></a>
														<a href="JavaScript:if(confirm('Confirm Delete?')==true){window.location='<?=$_SERVER["PHP_SELF"];?>?action=del&id_p=<? echo $rs2['id_position'];?>&id_u=<?php echo $id?>';}"><img src="img/delete.png" style="width:20px;"></a>	
													</td>
												</tr>
											<?php
												}
											}
											?>
											<tr>
												<td></td>
												<td><input type="text" name="position"></td>
												<td><input name="btnAdd" type="button" id="btnAdd" value="Add" OnClick="frmMain.hdnCmd.value='Add';frmMain.submit();" class="button-create"></td>
											</tr>
										</table>							
									</form>
								</td>
							</tr>
						</table>
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
