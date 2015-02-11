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

			class PDF extends FPDF
			{
			//Load data
			function LoadData($file)
			{
				//Read file lines
				$lines=file($file);
				$data=array();
				foreach($lines as $line)
					$data[]=explode(';',chop($line));
				return $data;
			}

			//Simple table
			function BasicTable($header,$data)
			{
				//Header
				$w=array(30,30,55,25,20,20);
				//Header
				for($i=0;$i<count($header);$i++)
					$this->Cell($w[$i],7,$header[$i],1,0,'C');
				$this->Ln();
				//Data
				foreach ($data as $eachResult) 
				{
					$this->Cell(30,6,$eachResult["id_group_product"],1);
					$this->Cell(30,6,iconv( 'UTF-8','TIS-620',$eachResult["title"]),1,0,'C');
					$this->Ln();
				}
			}

			//Better table
			function ImprovedTable($header,$data)
			{
				//Column widths
				$w=array(20,30,55,25,25,25);
				//Header
				for($i=0;$i<count($header);$i++)
					$this->Cell($w[$i],7,$header[$i],1,0,'C');
				$this->Ln();
				//Data

				foreach ($data as $eachResult) 
				{
					$this->Cell(20,6,$eachResult["id_group_product"],1);
					$this->Cell(30,6,iconv( 'UTF-8','TIS-620',$eachResult["title"]),1,0,'C');
					$this->Ln();
				}
				//Closure line
				$this->Cell(array_sum($w),0,'','T');
			}

			//Colored table
			function FancyTable($header,$data)
			{
				//Colors, line width and bold font
				$this->SetFillColor(255,0,0);
				$this->SetTextColor(255);
				$this->SetDrawColor(128,0,0);
				$this->SetLineWidth(.3);
				$this->SetFont('','B');
				//Header
				$w=array(20,30,55,25,25,25);
				for($i=0;$i<count($header);$i++)
					$this->Cell($w[$i],7,$header[$i],1,0,'C',true);
				$this->Ln();
				//Color and font restoration
				$this->SetFillColor(224,235,255);
				$this->SetTextColor(0);
				$this->SetFont('');
				//Data
				$fill=false;
				foreach($data as $row)
				{
					$this->Cell($w[0],6,$row[0],'LR',0,'L',$fill);
					$this->Cell($w[1],6,$row[1],'LR',0,'L',$fill);
					$this->Ln();
					$fill=!$fill;
				}
				$this->Cell(array_sum($w),0,'','T');
			}
			}

			$pdf=new PDF();

			//Column titles
			$header=array('id_group_product','title');
			//Data loading

			//*** Load MySQL Data ***//
			$strSQL = "SELECT * FROM roc_group_product";
			$objQuery = mysql_query($strSQL);
			$resultData = array();
			for ($i=0;$i<mysql_num_rows($objQuery);$i++) {
				$result = mysql_fetch_array($objQuery);
				array_push($resultData,$result);
			}
			//************************//
			
			$pdf->AddFont('angsana','','angsa.php');
			$pdf->AddFont('angsana','B','angsab.php');
			$pdf->AddFont('angsana','I','angsai.php');
			$pdf->AddFont('angsana','BI','angsaz.php');
			$pdf->SetFont('angsana','',12);

			//*** Table 1 ***//
			$pdf->AddPage();
			$pdf->Ln(35);
			$pdf->BasicTable($header,$resultData);

			//*** Table 2 ***//
			$pdf->AddPage();
			$pdf->Ln(35);
			$pdf->ImprovedTable($header,$resultData);

			//*** Table 3 ***//
			$pdf->AddPage();
			$pdf->Ln(35);
			$pdf->FancyTable($header,$resultData);

			$pdf->Output("pdf/dd.pdf","F");


			//echo 'PDF Created Click <a href="pdf/dd.pdf">here</a> to Download';

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
					<td class="b-bottom"><div class="large-4 columns"><h4><h4>Sample Request >> <?php echo $mode;?></h4></h4></div></td>
				</tr>
				<tr>
					<td class="b-bottom">
						<div class="large-4 columns">
							<input type="submit" value="<?php echo $button?>" class="button-create">
							<input type="button" value="Close" class="button-create" onclick="window.location.href='roc.php'">
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
											ใบร้องขอตัวอย่าง (Samle Request)
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
									<td class="top w23"><input type="checkbox" class="checkbox">ร้องขอสูตร (Formulation)</td>
									<td class="top w30" colspan="2"><input type="checkbox" class="checkbox">ร้องขอการทดสอบความคงตัว (Stability Test)</td>
								</tr>
								<tr>
									<td class="top w23"><input type="checkbox" class="checkbox">ร้องขอตัวอย่าง (Sample)</td>
									<td class="top w30" colspan="2"><input type="checkbox" class="checkbox">ร้องขอตัวอย่างสำหรับขึ้นทะเบียนหรือส่งตรวจ (FDA Registation)</td>
								</tr>
								<tr>
									<td class="top w23"><input type="checkbox" class="checkbox">ร้องขอรายละเอียดผลิตภัณฑ์ (Product Description)</td>
									<td class="top" colspan="2"><div style="float:left; margin: 0 0.5% 0 0;"><input type="checkbox" class="checkbox"></div><div style="float:left; margin: 0 0.5% 0 0;">อื่น ๆ</div><div style="float:left"><input type="text"></div></td>
								</tr>
								<tr>
									<td class="top">1 ชื่อผลิตภัณฑ์ (Product Name)</td>
									<td class="top" colspan="2"><input type="text"></td>
								</tr>
								<tr>
									<td class="top">2 ชื่อลูกค้า (Customer Name)</td>
									<td class="top"><input type="text"></td>
									<td class="top">บริษัท (Company)</td>
									<td class="top"><input type="text" class="txt"></td>
								</tr>
								<tr>
									<td class="top">3 จุดประสงค์การขอตัวอย่าง (Objective)</td>
									<td class="top"><input type="text"></td>
								</tr>
								<tr>
									<td class="top">4 จำนวน (Quantity)</td>
									<td class="top"><input type="text"></td>
									<td class="top">ขนาดบรรจุ</td>
									<td class="top"><div style="float: left; margin-right: 2%;"><input type="text"></div>ต่อขวด /ซอง</td>
								</tr>
								<tr>
									<td class="top">5 วันที่รับงาน  (Receive Job Date)</td>
									<td class="top">
										<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
										<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
										<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
										<script>
											$(function() {
												$( "#datepicker" ).datepicker({
													showOn: "button",
													buttonImage: "img/calendar.gif",
													buttonImageOnly: true
												});
											});
										</script>
										<input type="text" id="datepicker" style="width: 70%; float: left; margin-right: 2%;"/>
									</td>
									<td class="top">กำหนดส่งตัวอย่าง (Delivery Date)</td>
									<td class="top">
										<script>
											$(function() {
												$( "#datepicker2" ).datepicker({
													showOn: "button",
													buttonImage: "img/calendar.gif",
													buttonImageOnly: true
												});
											});
										</script>
										<input type="text" id="datepicker2" style="width: 20%; float: left; margin-right: 2%;"/>
									</td>
								</tr>
								<tr>
									<td  class="top" colspan="4">6 ลักษณะรูปแบบผลิตภัณฑ์</td>
								</tr>
								<?php
									$num=0;
									$sql_type_product="select * from type_product";
									$res_type_product=mysql_query($sql_type_product) or die ('Error '.$sql_type_product);
									$max_row=mysql_num_rows($res_type_product);
									while($rs_type_product=mysql_fetch_array($res_type_product)){
										$num++;
									?>		
										<tr>
											<td class="title-group" colspan="4"><input type="radio"><?php echo $rs_type_product['title_thai'].'('.$rs_type_product['title'].')';?></td>
										</tr>
									<?php
										$rows = 0;
										$j=0;
										$sql_roc_product_v="select * from roc_product_value where id_type_product='".$rs_type_product['id_type_product']."'";
										$res_roc_product_v=mysql_query($sql_roc_product_v) or die ('Error '.$sql_roc_product_v);
										while($rs_roc_product_v=mysql_fetch_array($res_roc_product_v)){
											if($rows % 2 ==0){?><tr><?php }
											$j++;	
										?>
											<td class="title-function" colspan="2"><div style="float:left; margin: 0 0.5% 0 0;"><input type="checkbox" class="checkbox"></div>
											<div style="float:left; margin: 0 0.5% 0 0;"><?echo $rs_roc_product_v['title']?></div>
											<?php if($rs_roc_product_v['id_type_product']==4){ ?>
											<td class="title-function" colspan="2"><div style="float:left; margin: 0 0.5% 0 0;"><input type="checkbox" class="checkbox"></div><div style="float:left; margin: 0 0.5% 0 0;">ผงชงดิ่มประเภทอื่น ๆ</div><div style="float:left"><input type="text"></div></td>
											<?php } ?>
										<?php									
											if(($rs_roc_product_v['title']=='สี (Color)') ||($rs_roc_product_v['title']=='กลิ่น (Odor)') || 
												($rs_roc_product_v['title']=='รส (Taste)') ||($rs_roc_product_v['title']=='สีของเปลือกแคปซูล')) {
												echo '<div style="float:left"><input type="text"></div>';
											}
										?>
											</td>											
										<?php 
										
											if($rs_roc_product_v['id_sub_product']==1){
										?>
											<tr>
											<?php
												$rows_sub = 0;
												$j_sub=0;
												$sql_sub_product="select * from roc_sub_product where id_product_value='".$rs_roc_product_v['id_product_value']."'";
												$res_sub_product=mysql_query($sql_sub_product) or die ('Error '.$sql_sub_product);
												while($rs_sub_product=mysql_fetch_array($res_sub_product)){
													if($rows_sub % 2 ==0){?><tr><?php }
													$j_sub++;
												?>
													<td class="title-sub-function" colspan="2"><div style="float:left; margin: 0 0.5% 0 0;"><input type="checkbox" class="checkbox"></div><div style="float:left; margin: 0 0.5% 0 0;"><?echo $rs_sub_product['title']?></div>
												<?php
													if(($rs_sub_product['title']=='สี (Color)') || ($rs_sub_product['title']=='กลิ่น (Odor)') || 
														($rs_sub_product['title']=='รส (Taste)') ||($rs_sub_product['title']=='สีของเปลือกแคปซูล') ||
														($rs_sub_product['title']=='รูปร่าง (Shape)')) {
														echo '<div style="float:left"><input type="text"></div>';
													}
												?>
													</td>
												<?php 
													if($j_sub % 2 == 0){ ?></tr><?php } //display end row
													$rows_sub++;
												}//end while roc sub product
											?>
											</tr>
										<?php
											}//end if id sub product=1
											if($rs_type_product['id_type_product']==3){
												$rows = 0;
												$j=0;
												$sql_roc_product_w="select * from roc_product_weight where id_type_product='".$rs_type_product['id_type_product']."'";
												$res_roc_product_w=mysql_query($sql_roc_product_w) or die ('Error '.$sql_roc_product_w);
												while($rs_roc_product_w=mysql_fetch_array($res_roc_product_w)){
													if(($rs_roc_product_w['id_group_product']==3) ||($rs_roc_product_w['id_group_product']==4)){
												?>	
													<tr>
														<td class="title-function" colspan="2"><input type="checkbox" class="checkbox"><?echo $rs_roc_product_w['title']?></td>
													</tr>
												<?php }else{
													if($rows % 2 ==0){?><tr><?php }
													$j++;
													if(($rs_roc_product_w['id_group_product']==3) ||($rs_roc_product_w['id_group_product']==3)){
												?>
												<?php }
												?>
													<td class="title-function" colspan="2"><input type="checkbox" class="checkbox"><?echo $rs_roc_product_w['title']?></td>
												<?php if($j % 2 == 0){ ?></tr><?php } //display end row
													$rows++;
													}//end if id_group_product
												}//end while roc_product_w
											}
										?>
											</td>
										<?php if($j % 2 == 0){ ?></tr><?php } //display end row
											$rows++;
										}//end while roc product value
									}//end type product 
								?>
									<tr>
										<td class="title-group" colspan="4"><input type="radio"><?php echo 'อื่น ๆ'?>
										<textarea style="width:50%; height:100px;" name="information"><?php echo $rs['information']?></textarea></td>
									</tr>
								<?php 
								}//end page1
								elseif($_GET['p']==2){
								?>
									<tr>
										<td class="top" colspan="4"><p>7 ส่วนประกอบ</p></td>
									</tr>
									<tr>
										<td colspan="4">
											<table style="width: 100%;" cellpadding="0" cellspacing="0" id="tb-ele">
												<tr>
													<td rowspan="2" class="center">ลำดับที่</td>
													<td colspan="3" class="center">ส่วนประกอบ</td>
													<td colspan="3" class="center">เทียบเท่ากับ (Equivalent of Active)</td>
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
												for($i=1;$i<=21;$i++){
												?>	
												<tr>
													<td class="center"><?php echo $i?></td>
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
													<td colspan="3" style="background: #eee;"></td>
												</tr>
											</table>
										</td>
									</tr>
									<tr>
										<td class="top" colspan="4">8 ประเภทของทะเบียน</td>
									</tr>
									<?php
									$num=0;
									$rows = 0;
									$j=0;
									$sql_type_register="select * from type_register order by id_type_register asc";
									$res_type_register=mysql_query($sql_type_register) or die ('Error '.$sql_type_register);
									$max_row=mysql_num_rows($res_type_register);
									while($rs_type_register=mysql_fetch_array($res_type_register)){
										if($rows % 4 ==0){?><tr><?php }
										$j++;
										$num++;
									?>
										<td class="title-group w10"><input type="checkbox" class="checkbox"><?echo $rs_type_register['title']?></td>
										<?php if($num==$max_row){?>
										<td class="title-group"><div style="float:left; margin: 0 0.5% 0 0;"><input type="checkbox" class="checkbox"></div><div style="float:left; margin: 0 0.5% 0 0;">อื่น ๆ</div><div style="float:left"><input type="text"></div></td>
										<?php } ?>
									<?php if($j % 4 == 0){ ?></tr><?php } //display end row
										$rows++;
									}//end while type manufactory
								}//end page2
								elseif($_GET['p']==3){
								?>
									<tr>
										<td class="top" colspan="4">9 โรงงานผลิต</td>
									</tr>
									<?php
									$num=0;
									$rows = 0;
									$j=0;
									$sql_type_manufactory="select * from type_manufactory order by id_manufactory asc";
									$res_type_manufactory=mysql_query($sql_type_manufactory) or die ('Error '.$sql_type_manufactory);
									$max_row=mysql_num_rows($res_type_manufactory);
									while($rs_type_manufactory=mysql_fetch_array($res_type_manufactory)){
										if($rows % 4 ==0){?><tr><?php }
										$j++;
										$num++;
									?>
										<td class="title-group w10"><input type="checkbox" class="checkbox"><?echo $rs_type_manufactory['title']?></td>
										<?php if($num==$max_row){?>
										<td class="title-group"><div style="float:left; margin: 0 0.5% 0 0;"><input type="checkbox" class="checkbox"></div><div style="float:left; margin: 0 0.5% 0 0;">อื่น ๆ</div><div style="float:left"><input type="text"></div></td>
										<?php } ?>
									<?php if($j % 4 == 0){ ?></tr><?php } //display end row
										$rows++;
									}//end while type manufactory
								?>
									<tr>
										<td class="top" colspan="4">10 โรงงานแบ่งบรรจุ</td>
									</tr>
									<?php
									$num=0;
									$rows = 0;
									$j=0;
									$sql_type_manufactory="select * from type_manufactory order by id_manufactory asc";
									$res_type_manufactory=mysql_query($sql_type_manufactory) or die ('Error '.$sql_type_manufactory);
									$max_row=mysql_num_rows($res_type_manufactory);
									while($rs_type_manufactory=mysql_fetch_array($res_type_manufactory)){
										if($rows % 4 ==0){?><tr><?php }
										$j++;
										$num++;
									?>
										<td class="title-group w10"><input type="checkbox" class="checkbox"><?echo $rs_type_manufactory['title']?></td>
										<?php if($num==$max_row){?>
										<td class="title-group"><div style="float:left; margin: 0 0.5% 0 0;"><input type="checkbox" class="checkbox"></div><div style="float:left; margin: 0 0.5% 0 0;">อื่น ๆ</div><div style="float:left"><input type="text"></div></td>
										<?php } ?>
									<?php if($j % 4 == 0){ ?></tr><?php } //display end row
										$rows++;
									}//end while type manufactory
									?>
									<tr>
										<td colspan="4"><p>11 บันทึกเพิ่มเติม</p>
										<textarea style="width:50%; height:100px;" name="information"></textarea></td>
									</tr>
								<?php }//end page3 ?>								
								<tr>
									<td class="title-group footer-right" colspan="6"><a href="ac-req-rd.php?id_u=new&p=1">1</a> | <a href="ac-req-rd.php?id_u=new&p=2">2</a> | <a href="ac-req-rd.php?id_u=new&p=3">3</a></td>
								</tr>
								<tr>
									<td class="title-group footer-right" colspan="6">RD-F005 Rev.0 Bffective Date: 5/05/12</td>
								</tr>
							</table>
						</div>
					</td>
				</tr>
				<tr>
					<td class="b-top">
						<div class="large-4 columns">
							<input type="submit" value="<?php echo $button?>" class="button-create">
							<input type="button" value="Close" class="button-create" onclick="window.location.href='roc.php'">
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
