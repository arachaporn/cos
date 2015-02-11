<?php
/*@session_start();
$strUsername = trim($_POST["tUsername"]);
$strPassword = trim($_POST["tPassword"]);*/
require('fpdf.php');
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
</head>
<body>
	<?php include("menu.php");?>
	<div class="row">
		<div class="background">
			<?php
			include("connect/connect.php");

			if($_GET["id_u"]=='new'){
				$mode=$_GET["id_u"];
				$button='Save';
				$id='New';				
			}
			else{
				$id=$_GET["id_u"];
				$sql="select * from moh_profit where id_moh_profit='".$id."'";
				$res=mysql_query($sql) or die ('Error '.$sql);
				$rs=mysql_fetch_array($res);

				$sql_type_product="select * from type_product where id_type_product='".$rs['id_type_product']."'";
				$res_type_product=mysql_query($sql_type_product) or die ('Error '.$sql_type_product);
				$rs_type_product=mysql_fetch_array($res_type_product);
				
				$mode='Edit '.$rs_type_product['title'];
				$button='Update';
			}
			?>
			<form method="post" action="dbhq_profit.php">
			<input type="hidden" name="mode" value="<?php echo $id?>">
			<table style="border: 0; width: 100%;" cellpadding="0" cellspacing="0">
				<tr>
					<td class="b-bottom"><div class="large-4 columns"><h4><h4>NPD Meeting Form >> <?php echo $mode;?></h4></h4></div></td>
				</tr>
				<tr>
					<td class="b-bottom">
						<div class="large-4 columns">
							<input type="submit" value="<?php echo $button?>" class="button-create">
							<input type="button" value="Close" class="button-create" onclick="window.location.href='npd-meeting.php'">
						</div>
					</td>
				</tr>
				<tr>
					<td style="background: #fff;">
						<div class="large-4 columns">						
							<table style="border: 0; width: 100%;" cellpadding="0" cellspacing="0" id="tb-req">
								<tr>
									<td class="b-bottom center" colspan="4">
										<div class="tb-h">
											<img src="img/logo.png" width="140" class="img-logo">
											<div class="header-text">บริษัท ซีดีไอพี (ประเทศไทย) จำกัด<br>
											CDIP (Thailand) Co.,Ltd.<br>
											แบบฟอร์มการตั้งสูตรผลิตภัณฑ์ใหม่ (NPD Meeting Form)
											</div>
										</div>
									</td>
								</tr>
								<?php if($_GET['p']==1){?>
								<tr>
									<td></td>
									<td></td>
									<td></td>
									<td class="top"><div style="float:left; margin-right: 2%;">เลขที่เอกสาร</div><div style="float:left;"><input type="text"></div></td>
								</tr>
								<tr>
									<td></td>
									<td></td>
									<td></td>
									<td class="top"><div style="float:left; margin-right: 2%;">เลขที่เอกสารอ้างอิง</div><div style="float:left;"><input type="text"></div></td>
								</tr>
								<tr>
									<td class="top">ชื่อผลิตภัณฑ์ (Product Name)</td>
									<td class="top" colspan="2"><input type="text"></td>
								</tr>
								<tr>
									<td class="top">ชื่อลูกค้า /บริษัท</td>
									<td class="top"><input type="text"></td>
									<td class="top" colspan="2"></td>
								</tr>
								<tr>
									<td colspan="4">
									<table style="width: 100%;" cellpadding="0" cellspacing="0" id="tb-ele">
										<tr>
											<td rowspan="2" class="center">ลำดับที่</td>
											<td colspan="3" class="center">ส่วนประกอบ</td>
											<td colspan="3" class="center">เทียบเท่ากับ (Equivalent of Active)</td>
											<td rowspan="2" class="center">ผู้ชาย</td>
										</tr>
										<tr>
											<td class="center" style="background: #fff;">สารสำคัญ</td>
											<td class="center" style="background: #fff;">ปริมาณ</td>
											<td class="center" style="background: #fff;">หน่วย</td>
											<td class="center" style="background: #fff;">สารสำคัญ</td>
											<td class="center" style="background: #fff;">ปริมาณ</td>
											<td class="center" style="background: #fff;">หน่วย</td>
										</tr>
										<?php
										for($i=1;$i<=21;$i++){ ?>	
										<tr>
											<td class="center"><?php echo $i?></td>
											<td><input type="text"></td>
											<td><input type="text"></td>
											<td><input type="text"></td>
											<td><input type="text"></td>
											<td><input type="text"></td>
											<td><input type="text"></td>
											<td><input type="text"></td>
										</tr>
										<?php } ?>
										<tr>	
											<td class="center" colspan="2">น้ำหนักสุทธิต่อหน่วย</td>
											<td></td>
											<td></td>
											<td colspan="4" style="background: #eee;"></td>
											</tr>
										</table>
										</td>
									</tr>								
								<?php 
								}//end page1
								elseif($_GET['p']==2){
								?>
									<tr>
										<td class="top"><p>วิธีรับประทาน</p></td>
										<td class="top"><input type="text"></td>
										<td class="top"><p>อายุการเก็บรักษา</p></td>
										<td class="top"><input type="text"></td>
									</tr>									
									<tr>
										<td colspan="4"><p>ข้อเสนอแนะเพิ่มเติม</p>
										<textarea style="width:50%; height:100px;" name="information"></textarea></td>
									</tr>
								<?php }//end page3 ?>								
								<tr>
									<td class="title-group footer-right" colspan="4"><a href="ac-npd-meeting.php?id_u=new&p=1">1</a> | <a href="ac-npd-meeting.php?id_u=new&p=2">2</a></td>
								</tr>
								<tr>
									<td class="title-group footer-right" colspan="6">NPD-F002 Rev.0 Bffective Date: 5/05/12</td>
								</tr>
							</table>
						</div>
					</td>
				</tr>
				<tr>
					<td class="b-top">
						<div class="large-4 columns">
							<input type="submit" value="<?php echo $button?>" class="button-create">
							<input type="button" value="Close" class="button-create" onclick="window.location.href='npd-meeting.php'">
						</div>
					</td>
				</tr>
			</table>
			</form>
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
