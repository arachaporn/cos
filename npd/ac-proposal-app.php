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

			if($_GET["id_u"]=='New'){
				$mode=$_GET["id_u"];
				$button='Save';
				$id='New';				
			}
			else{
				$id=$_GET["id_u"];
				$sql="select * from npd_inno_proposal where id_proposal='".$id."'";
				$res=mysql_query($sql) or die ('Error '.$sql);
				$rs=mysql_fetch_array($res);
				
				$mode='Edit '.$rs_type_product['title'];
				$button='Update';

				//*** Delete Condition ***//
				if($_GET["action"] == "del"){
					$sql = "delete from call_report_relationship ";
					$sql .="where id_cr_relation = '".$_GET["id_p"]."'";
					$res = mysql_query($sql) or die ('Error '.$sql);
					//header("location:$_SERVER[PHP_SELF]");
					//exit();
				}
			}
			?>
			<form name="frm" method="post" action="dbproposal.php">
			<input type="hidden" name="mode" value="<?php echo $id?>">
			<input type="hidden" name="hdnCmd" value="">
			<table style="border: 0; width: 100%;" cellpadding="0" cellspacing="0">
				<tr>
					<td class="b-bottom"><div class="large-4 columns"><h4><h4>Proposal Application >> <?php echo $mode;?></h4></h4></div></td>
				</tr>
				<tr>
					<td class="b-bottom">
						<div class="large-4 columns">
							<?php if(!is_numeric($id)){?>   
							<input type="button" name="save_data" id="save_data" value="<?php echo $button?>" class="button-create" OnClick="frm.hdnCmd.value='save_data';JavaScript:return fncSubmit();">
							<?php }else{?>
							<input type="button" name="update_data" id="update_data" value="<?php echo $button?>" class="button-create" OnClick="frm.hdnCmd.value='update_data';JavaScript:return fncSubmit();">
							<input type="button" name="finished_data" id="finished_data" value="Finished" class="button-create" OnClick="frm.hdnCmd.value='finished_data';JavaScript:return fncSubmit();">
							<?php }?>
							<input type="button" value="Close" class="button-create" onclick="window.location.href='proposal-app.php'">
						</div>
					</td>
				</tr>
				<tr>
					<td style="background: #fff;">
						<div class="large-4 columns">						
							<table style="border: 0; width: 100%;" cellpadding="0" cellspacing="0" id="tb-inno-proposal">
								<tr>
									<td class="b-bottom center" colspan="4">
										<div class="tb-h">
											<img src="img/logo.png" width="140" class="img-logo">
											<div class="header-text">บริษัท ซีดีไอพี (ประเทศไทย) จำกัด<br>
											CDIP (Thailand) Co.,Ltd.<br>
											ข้อเสนอโครงการ (Proposal Application)
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td colspan="3"><div id="message"><p></p></div></td>
								</tr>
								<?php if($_GET['p']==1){?>
								<tr>
									<td></td>
									<td class="proposal-right w40">เลขที่เอกสาร</td>
									<td>
										<?php $month=date("m")?>
										<input type="text" name="proposal_no" style="width:40%;" readonly value="<?php if(is_numeric($id)){echo $rs['proposal_no'];}else{echo 'NPD-INNO-';
											echo date("y").date("m").'-';
											if(date("y")==$rs['proposal_year']){
												$num = $rs['proposal_num']+1;
											}else{$num=1;}												
											echo sprintf("%03d",$num);
											echo $numf;
										}?>">
									</td>
								</tr>
								<tr>
									<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
									<script src="//code.jquery.com/jquery-1.9.1.js"></script>
									<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
									<script>
										$(function() {
											$( "#proposal_date" ).datepicker({
												showOn: "button",
												buttonImage: "img/calendar.gif",
												buttonImageOnly: true
											});
										});
									</script>
									<td></td>
									<td class="proposal-right">วันที่รับเอกสาร</td>
									<td>
									<?php
										list($day, $month, $year) = split('[/.-]', $rs['proposal_date']); 
										$proposal_date= $month."/".$year."/".$day;
									?>
									<input type="text" name="proposal_date" id="proposal_date" style="float:left;width:20%;margin-right:1%;" value="<?php if(is_numeric($id)){echo $proposal_date;}else{echo date('m/d/Y');}?>"></td>
								</tr>
								<tr>
									<td><input type="radio" name="proposal_type" value="1" <?php if($rs['inno_status']==1){echo 'checked';}?>>External</td>
									<td><input type="radio" name="proposal_type" value="2" <?php if($rs['inno_status']==2){echo 'checked';}?>>Internal</td>
									<td></td>
								</tr>
								<tr>
									<td colspan="3" class="top">1.ชื่อโครงการ</td>
								</tr>
								<tr>
									<td class="w10">ภาษาไทย</td>
									<td colspan="2"><input type="text" name="name_th_proposal" class="w50" value="<?php echo $rs['name_th_proposal']?>"></td>
								</tr>
								<tr>
									<td>ภาษาอังกฤษ</td>
									<td colspan="2"><input type="text" name="name_en_proposal" class="w50" value="<?php echo $rs['name_en_proposal']?>"></td>
								</tr>
								<tr>
									<td class="top">2.ผู้ร่วมวิจัย (ถ้ามี)</td>
									<td colspan="2"><input type="text" name="person_join" class="w50" value="<?php echo $rs['person_join']?>"></td>
								</tr>
								<tr>
									<td colspan="3" class="top">3.ภาพรวมของโครงการ</td>
								</tr>
								<tr>
									<td colspan="3" style="padding-left: 1.5%;"><textarea name="project_overview" class="w55"></textarea></td>
								</tr>
								<tr>
									<td colspan="3" class="top">4.คำสำคัญ</td>
								</tr>
								<tr>
									<td colspan="3" class="top" style="padding-left: 1.5%;">
										<table style="border: 1px solid #eee; width: 55%;" cellpadding="0" cellspacing="0" id="tb-inno-proposal">
											<tr>
												<td class="bd-right bd-bottom proposal-center w45">ภาษาไทย (5 คำ)</td>
												<td class="bd-bottom proposal-center w45">ภาษาอังกฤษ (5 คำ)</td>
												<td class="bd-bottom"></td>
											</tr>
											<?php
											if(is_numeric($id)){$id_proposal=$id;}else{$id_proposal=0;}
											$sql_keyword="select * from npd_inno_keyword where id_proposal='".$id_proposal."'";
											$res_keyword=mysql_query($sql_keyword) or die('Error '.$sql_keyword);
											while($rs_keyword=mysql_fetch_array($res_keyword)){									
												if($rs_keyword['id_keyword'] == $_GET['id_p'] and $_GET["action"] == 'edit_keyword'){
											?>	
												<tr>
													<input type="hidden" name="hdnEdit" value="<?php echo $rs_keyword['id_keyword']?>">
													<td class="bd-right bd-bottom"><input type="text" name="keyword_th2" value="<?php echo $rs_keyword['keyword_th']?>"></td>
													<td class="bd-bottom"><input type="text" name="keyword_en2" value="<?php echo $rs_keyword['keyword_en']?>"></td>
													<td class="bd-bottom">
														<input name="btnAdd" type="button" id="btnUpdate" value="Update"	OnClick="frm.hdnCmd.value='update_keyword';JavaScript:return fncSubmit();" class="btn-update">
														<input name="btnAdd" type="button" id="btnCancel" value="Cancel" OnClick="window.location='<?=$_SERVER["PHP_SELF"]."?id_u=".$id?>';" class="btn-cancel">
													</td>
												</tr>
												<?php }else{?>
												<tr>
													<td class="bd-right bd-bottom" style="font-weight: normal;"><?php echo $rs_keyword['keyword_th']?></td>
													<td class="bd-bottom" style="font-weight: normal;"><?php echo $rs_keyword['keyword_en']?></td>
													<td class="bd-bottom"><div style="float:left;">
														<a href="<?=$_SERVER["PHP_SELF"];?>?id_u=<?php echo $id?>&p=1&action=edit_keyword&id_p=<?=$rs_keyword['id_keyword'];?>"><img src="img/edit.png" style="width:20px;"></a>
														<a href="JavaScript:if(confirm('Confirm Delete?')==true){window.location='<?=$_SERVER["PHP_SELF"];?>?id_u=<?php echo $id?>&p=1&action=del_keyword&id_p=<? echo $rs_keyword['id_keyword'];?>';}"><img src="img/delete.png" style="width:20px;"></a></div>
													</td>
												</tr>
											<?php }//end if
											}//end keyword?>
												<tr>
													<input type="hidden" name="id_proposal" value="<?php echo $id?>">
													<td class="bd-right"><input type="text" name="keyword_th"></td>
													<td><input type="text" name="keyword_en"></td>
													<td><input name="btnAdd" type="button" id="btnAdd" value="Add"  OnClick="frm.hdnCmd.value='add_keyword';JavaScript:return fncSubmit();" class="btn-new2"></td>
												</tr>
										</table>										
									</td>
								</tr>
								<tr>
									<td colspan="3" class="top">5.วัตถุประสงค์</td>
								</tr>
								<tr>
									<td colspan="3" style="padding-left: 1.5%;"><textarea name="objective" class="w55"></textarea></td>
								</tr>
								<tr>
									<td colspan="3" class="top">6.เป้าหมายของโครงการ</td>
								</tr>
								<tr>
									<td colspan="3" style="padding-left: 1.5%;"><textarea name="target_project" class="w55"></textarea></td>
								</tr>
								<?php 
								}//end page1
								elseif($_GET['p']==2){
								?>
								<tr>
									<td colspan="3" class="top">7.ข้อบ่งชี้ความเป็นนวัตกรรมของโครงการ</td>
								</tr>
								<tr>
									<td colspan="3" style="padding-left: 2%;"><textarea name="indication_inno" class="w55"></textarea></td>
								</tr>
								<tr>
									<td colspan="3" class="top">8.ขั้นตอนและกระบวนการ</td>
								</tr>
								<tr>
									<td colspan="3" style="padding-left: 2%;"><textarea name="process" class="w55"></textarea></td>
								</tr>
								<tr>
									<td colspan="2" class="top">9.เอกสาร/งานวิจัยอ้างอิงทางวิชาการเกี่ยวกับโครงการ</td>
								</tr>
								<tr>
									<td colspan="2" style="padding-left: 2%;"><textarea name="doc_inno" class="w55"></textarea></td>
								</tr>
								<tr>
									<td colspan="2" class="top">10.ข้อมูลด้านการตลาด</td>
								</tr>
								<tr>
									<td colspan="2" style="padding-left: 2%;">10.1 ขนาดและแนวโน้มของตลาด</td>
								</tr>
								<tr>
									<td colspan="2" style="padding-left: 2%;"><textarea name="market_trend" class="w55"></textarea></td>
								</tr>
								<tr>
									<td colspan="2" style="padding-left: 2%;">10.2 ตลาดเป้าหมาย</td>
								</tr>
								<tr>
									<td colspan="2" style="padding-left: 2%;"><textarea name="target_market" class="w55"></textarea></td>
								</tr>
								<tr>
									<td colspan="2" style="padding-left: 2%;">10.3 ส่วนแบ่งทางการตลาด</td>
								</tr>
								<tr>
									<td colspan="2" style="padding-left: 2%;"><textarea name="market_share" class="w55"></textarea></td>
								</tr>
								<tr>
									<td colspan="2" style="padding-left: 2%;">10.4 คาดการณ์มูลค่าผลตอบแทนจากโครงการ</td>
								</tr>
								<tr>
									<td colspan="2" style="padding-left: 2%;"><textarea name="value_project" class="w55"></textarea></td>
								</tr>
								<?php }//end page2 	
								elseif($_GET['p']==3){
								?>
								<tr>
									<td colspan="2" class="top">11.กลยุทธ์ทางการตลาด</td>
								</tr>
								<tr>
									<td colspan="2" style="padding-left: 2%;">(การวิเคราะห์จุดแข็ง จุดอ่อน โอกาส และอุปสรรค (SWOT analysis))</td>
								</tr>
								<tr>
									<td colspan="2" style="padding-left: 2%;"><textarea name="swot_analysis" class="w55"></textarea></td>
								</tr>
								<tr>
									<td colspan="2" class="top">12.งบประมาณการดำเนินโครงการ</td>
								</tr>
								<tr>
									<td colspan="2" style="padding-left: 2%;"><textarea name="budget_project" class="w55"></textarea></td>
								</tr>
								<tr>
									<td colspan="2" class="top">13.แผนการดำเนินโครงการ</td>
								</tr>
								<tr>
									<td colspan="2" style="padding-left: 2%;"><textarea name="plan_project" class="w55"></textarea></td>
								</tr>
								<tr>
									<td colspan="2" class="top">14.ความคิดเห็นของผู้บริหาร</td>
								</tr>
								<tr>
									<td style="padding-left: 2%;"><input type="radio" name="approve">อนุมัติ</td>
									<td style="padding-left: 2%;"><input type="radio" name="approve">ไม่อนุมัติ</td>
								</tr>
								<tr>
									<td colspan="2" style="padding-left: 2%;">เนื่องจาก
									<textarea name="remark" class="w55"></textarea>
									</td>
								</tr>
								<?php }//end page3 ?>	
								<tr>
									<td class="title-group footer-right" colspan="3"><a href="ac-proposal-app.php?id_u=new&p=1">1</a> | <a href="ac-proposal-app.php?id_u=new&p=2">2</a> | <a href="ac-proposal-app.php?id_u=new&p=3">3</a></td>
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
<!-- auto save
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script type="text/javascript" src="js/jquery.autosave.js"></script>
<script type="text/javascript">
	$(function(){
		getDatabase();
		$("input,select,textarea").autosave({
			url: "inno-autosave.php",
			method: "post",
			grouped: true,
			success: function(data) {
				$("#message p").html("Data updated successfully").show();
				setTimeout('fadeMessage()',1500);
				getDatabase();
			},
			send: function(){
				$("#message p").html("Sending data....");
			},
			dataType: "html"
		});		
	});
	function fadeMessage(){
		$('#message p').fadeOut('slow');
	}
</script>
<!-- end auto save-->
</body>
</html>
