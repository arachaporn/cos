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
$_SESSION["id_company"]=$_REQUEST['id_company'];
$_SESSION['company_name']=$_REQUEST['company_name'];

//create pdf
include("mpdf/mpdf.php");
ob_start();
?>
<!DOCTYPE html>
<body>
	<?php
	$id=$_GET["id_u"];
	$sql="select * from roc where id_roc='".$id."'";
	$res=mysql_query($sql) or die ('Error '.$sql);
	$rs=mysql_fetch_array($res);

	$sql_type_product="select * from type_product where id_type_product='".$rs['id_type_product']."'";
	$res_type_product=mysql_query($sql_type_product) or die ('Error '.$sql_type_product);
	$rs_type_product=mysql_fetch_array($res_type_product);
				
	$sql_product="select * from product where id_product='".$rs['id_product']."'";
	$res_product=mysql_query($sql_product) or die ('Error '.$sql_product);
	$rs_product=mysql_fetch_array($res_product);
	?>
	<table style="border:none; width:100%; font-size: 1em; line-height: 1.45em; text-align:left;" cellpadding="0" cellspacing="0">
		<tr>
			<td style="padding-top:1.5%;">บริษัท</td>
			<td style="padding-top:1.5%;">
				<?php 
				$sql_company="select * from company where id_company='".$rs['id_company']."'";
				$res_company=mysql_query($sql_company) or die ('Error '.$sql_company);
				$rs_company=mysql_fetch_array($res_company);
				?>
				<?php if($rs_company['company_name']==''){echo '-';}else{echo $rs_company['company_name'];}?>
			</td>
			<td style="padding-top:1.5%;">ชื่อผู้ติดต่อ</td>
			<td style="padding-top:1.5%;">
				<?php 
				$sql_contact="select * from company_contact where id_contact='".$rs['id_contact']."'";
				$res_contact=mysql_query($sql_contact) or die ('Error '.$sql_contact);
				$rs_contact=mysql_fetch_array($res_contact);
				?>
				<?php if($rs_contact['contact_name']==''){echo '-';}else{echo $rs_contact['contact_name'];}?>							
			</td>
			<td style="padding-top:1.5%;">เลขที่เอกสาร</td>
			<td style="padding-top:1.5%;"><?php echo $rs['roc_code']?></td>
		</tr>
		<tr>
			<td>เบอร์โทรศัพท์</td>
			<td><?php if($rs_company['company_tel']==''){echo '-';}else{echo $rs_company['company_tel'];}?></td>
			<td>มือถือ</td>
			<td><?php if($rs_contact['mobile']==''){echo '-';}else{echo $rs_contact['mobile'];}?></td>
		</tr>
		<tr>
			<td>วันที่</td>
			<td>
				<?php
					list($ckyear,$ckmonth,$ckday) = split('[/.-]', $rs['date_roc']); 
					echo $ckstart= $ckday . "/". $ckmonth . "/" .$ckyear;
				?>
			</td>
			<td>แฟกซ์</td>
			<td><?php if($rs_company['company_fax']==''){echo '-';}else{echo $rs_company['company_fax'];}?></td>
			<td>อีเมล์</td>
			<td><?php if($rs_contact['email']==''){echo '-';}else{echo $rs_contact['email'];}?></td>
		</tr>
		<tr>
			<td>ที่อยู่</td>
			<td>
				<?php 
				$sql_address="select * from company_address where id_address='".$rs_company['id_address']."'";
				$res_address=mysql_query($sql_address) or die ('Error '.$sql_address);
				$rs_address=mysql_fetch_array($res_address);
				?>
				<?php if($rs['id_address']==0){if($rs['address']==''){echo '-';}else{echo $rs['address'];}}else{echo $rs_address['address_no'].'&nbsp;'.$rs_address['road'].'&nbsp;'.$rs_address['sub_district'].'&nbsp;'.$rs_address['district'].'&nbsp;'.$rs_address['province'].'&nbsp;'.$rs_address['postal_code'];}?>
			</td>
		</tr>
		<?php
		$rows_com_cate=0;
		$j=0;
		$sql_com_cate="select * from company_category";
		$res_com_cate=mysql_query($sql_com_cate) or die ('Error '.$sql_com_cate);
		$max_row=mysql_num_rows($res_com_cate);
		while($rs_com_cate=mysql_fetch_array($res_com_cate)){
		if($rows_com_cate % 2 ==0){?><tr><?php }
			$j++;
			if($j==1){$title='Identify Customer';}
			else{$title='';}
		?>
			<td><?php echo $title?></td>
			<td>
			<input type="checkbox" name="com_cate" value="<?php echo $rs_com_cate['id_com_cat']?>" <?php if($rs_com_cate['id_com_cat']==$rs['id_com_cat']){echo 'checked="checked"';}?>><?php echo $rs_com_cate['title']?></td>
			<?php if($j % 2 == 0){ ?></tr><?php } 
			$rows_com_cate++;
		}//end while type device
		if($max_row==$rows_com_cate){
		?>
			<td><?php echo $title?></td>
			<td colspan="3"><input type="checkbox" class="checkbox">อื่น ๆ.....................</td>
		<?php }?>
		</tr>
		<tr>
			<td>Project Name/Benchmark</td>
			<td colspan="5"><?php if($rs_product['product_name']==''){echo '-';}else{echo $rs_product['product_name'];}?>
			</td>
		</tr>
		<tr>
			<td>ชนิดของผลิตภัณฑ์</td>
			<td colspan="5">
				<?php
				$sql_type_product="select * from npd_type_product";
				$res_type_product=mysql_query($sql_type_product) or die ('Error '.$sql_type_product);
				while($rs_type_product=mysql_fetch_array($res_type_product)){
				?>
					<input type="checkbox" name="type_product"  <?php if($rs['id_type_product']==$rs_type_product['id_npd_type_product']){echo'checked="checked"';}?> value="<?php echo $rs_type_product['id_npd_type_product']?>"><?php echo $rs_type_product['npd_title'].'&nbsp;&nbsp;&nbsp;'?>
				<?php } ?>
			</td>
		</tr>
	</table>
	<table>
		<tr>
			<td class="top w10"><b>วัตถุประสงค์ที่ต้องการ</b></td>
		</tr>
		<tr>
			<td class="top w10">1.ฟังก์ชั่นการทำงาน</td>
		</tr>
		<?php
		$roc_func=split(",",$rs['id_roc_func']);
		$i=0;
		$sql_roc_group_product="select * from roc_group_product";
		$res_roc_group_product=mysql_query($sql_roc_group_product) or die ('Error '.$sql_roc_group_product);
		$max_row_g=mysql_num_rows($res_roc_group_product);
		while($rs_roc_group_product=mysql_fetch_array($res_roc_group_product)){
			$i++;
		?>
		<tr>
			<td class="title-group" colspan="6"><input type="checkbox" name="roc_group_product" value="<?php echo $rs_roc_group_product['id_group_product']?>" <?php if(is_numeric($id)){if($rs['id_group_product']==$rs_roc_group_product['id_group_product']){echo 'checked="checked"';}}?>></td>
			<td>1.<?php echo $i.'&nbsp;'.$rs_roc_group_product['title'];?></td>
		</tr>
		<tr>
			<td colspan="6">
				<?php
				$max_row_g2=0;
				$num=0;
				$sql_roc_function="select * from roc_function where id_group_product='".$rs_roc_group_product['id_group_product']."'";
				$res_roc_function=mysql_query($sql_roc_function) or die ('Error '.$sql_roc_function);
				$max_row=mysql_num_rows($res_roc_function);
				while($rs_roc_function=mysql_fetch_array($res_roc_function)){
					$num++;
					if(($i==3) || ($i==4)){
						$br='<div class="clear"></div>';
						$width='width:40%;margin-left:10%';
					}else{
						if($num %2 == 0){
							$br='<div class="clear"></div>';
							$width='width:20%margin-left:10%;';
						}
						else{
							$br='';
							$width='width:20;margin-left:10%';
						}
					}
				?>
					<div style="<?php echo $width?>">
						<input type="checkbox" class="checkbox" value="<?php echo $rs_roc_function['id_roc_func']?>" <?php if(in_array($rs_roc_function['id_roc_func'],$roc_func)){echo 'checked="checked"';}?>><?echo $rs_roc_function['title']?>
					</div><?php echo $br?>
					<?php if($num==$max_row){?>
						<div style="<?php echo $width?>"><input type="checkbox" class="checkbox" value="0" <?php if($rs['id_group_product']==$i){if(in_array('0',$roc_func)){echo 'checked="checked"';}}?>>อื่น ๆ<?php if($rs['id_group_product']==$i){if($rs['roc_func_other']==''){echo '';}else{echo $rs['roc_func_other'];}}?></div>
					<?php echo $br;
					$max_row_g2=$max_row_g+1;
					}//end max
				}?>
			</td>
		</tr>
		<?php }//end while group product function?>
		<tr>
			<td class="title-group" colspan="6"><input type="checkbox" disabled name="roc_group_product" id="roc_group_product<?php echo $i+1?>" <?php if($rs['id_group_product']== -1){echo 'checked';}?> value="-1" onclick="javascript:ShowFunc();">1.<?php echo $max_row_g2.'&nbsp;อื่น ๆ'?>
			............................................................................................................................................................................................................................<br>................................................................................................................................................................................................<br>................................................................................................................................................................................................<br>................................................................................................................................................................................................
			</td>
		</tr>
		</table><br>
		<table style="border:none; width:100%; font-size: 1em; line-height: 1.45em; text-align:left;" cellpadding="0" cellspacing="0">
		<tr>										
			<td colspan="6"><p class="title" style="padding-top: 1%;">2.ระบุสารสำคัญที่ต้องการ/ข้อเสนอแนะอื่น ๆ (ถ้ามี)</p>
				<table style="border: none; width: 20%; margin-top: 1%;" cellpadding="0" cellspacing="0">
					<?php
					$i_rm=0;
					$sql_roc_rm="select * from roc_rm where id_roc='".$id."'";
					$res_roc_rm=mysql_query($sql_roc_rm) or die('Error '.$sql_roc_rm);
					while($rs_roc_rm=mysql_fetch_array($res_roc_rm)){									
						$i_rm++;
					?>	
					<tr>
						<td style="line-height: 1.8em;font-size: 1em;"><?php echo $i_rm.'.'.$rs_roc_rm['roc_rm']?></td>
					</tr>
					<?php }//end roc rm?>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="6"><p class="title">3.ลักษณะรูปแบบผลิตภัณฑ์</p></td>
		</tr>
		<?php
		$roc_product_value=split(",",$rs['id_product_value']);
		$i=0;
		$num=0;
		$sql_product_appearance="select * from product_appearance";
		$res_product_appearance=mysql_query($sql_product_appearance) or die ('Error '.$sql_product_appearance);
		$max_row=mysql_num_rows($res_product_appearance);
		while($rs_product_appearance=mysql_fetch_array($res_product_appearance)){
			$i++;
			$num++;
		?>		
		<tr>
			<td class="title-group" colspan="6"><input type="checkbox" name="id_product_appearance" value="<?php echo $rs_product_appearance['id_product_appearance']?>" <?php if($rs['id_product_appearance']==$rs_product_appearance['id_product_appearance']){echo 'checked="checked"';}?>><?php echo $rs_product_appearance['title_thai'].'('.$rs_product_appearance['title'].')'?></td>
		</tr>
		<tr>
			<td>
				<?php if($rs_product_appearance['id_product_appearance']==1){$i_value=0;?><div class="title-weight">ลักษณะของเม็ด</div><?php }?>
				<?php if(($rs_product_appearance['id_product_appearance']==2) || ($rs_product_appearance['id_product_appearance']==3)){?><div class="title-weight">ชนิดของเปลือกแคปซูล</div><?php }?>
				<?php 
				$sql_rela_value="select * from roc_relation_value where id_relation_value='".$rs['id_relation_value']."'";
				$res_rela_value=mysql_query($sql_rela_value) or die ('Error '.$sql_rela_value);
				$rs_rela_value=mysql_fetch_array($res_rela_value);
				$roc_value_title=split(",",$rs_rela_value['title_value']);

				$num_v=0;
				$rows_v = 0;
				$j_v=0;
				$j_value=0;
				$sql_roc_product_v="select * from roc_product_value where id_type_product='".$rs_product_appearance['id_product_appearance']."'";
				$res_roc_product_v=mysql_query($sql_roc_product_v) or die ('Error '.$sql_roc_product_v);
				$max_row_v=mysql_num_rows($res_roc_product_v);
				while($rs_roc_product_v=mysql_fetch_array($res_roc_product_v)){
					$j_v++;
					$num_v++;	
					$i_value++;
					if(($rs_product_appearance['id_product_appearance']==2) || ($rs_product_appearance['id_product_appearance']==3)){
						$br='<div class="clear"></div>';
						$width='width:40%';
					}
					else{
						if($num_v %2 == 0){
							$br='<div class="clear"></div>';
							if(($rs_product_appearance['id_product_appearance']==5) || ($rs_product_appearance['id_product_appearance']==6) || ($rs_product_appearance['id_product_appearance']==7)){
								$width='width:13%;';
							}
							else{$width='width:20%;';}
							$float='float:left;';
						}else{
							$br='';
							if(($rs_product_appearance['id_product_appearance']==5) || ($rs_product_appearance['id_product_appearance']==6) || ($rs_product_appearance['id_product_appearance']==7)){
								$width='width:13%;';																
							}else{$width='width:20%;';}
							$float='float:left;';
						}
					}
				?>
				<div class="title-function" style="<?php echo $width.$float?>">
					<?php if($rs_product_appearance['id_product_appearance']==4){?>
					<input type="checkbox" id="roc_product_value<?php echo $i_value?>" name="roc_product_value[]" value="<?php echo $rs_roc_product_v['id_product_value']?>" <?php if(in_array($rs_roc_product_v['id_product_value'],$roc_product_value)){echo 'checked="checked"';}?>><?echo $rs_roc_product_v['title']?>
					<?php }else{?>
					<input type="checkbox" id="roc_product_value<?php echo $i_value?>" name="roc_product_value[]" value="<?php echo $rs_roc_product_v['id_product_value']?>" <?php if(in_array($rs_roc_product_v['id_product_value'],$roc_product_value)){echo 'checked="checked"';}?>><?echo $rs_roc_product_v['title']?>
					<?php }?>
				</div>
				<?php 
					if(($rs_roc_product_v['title']== 'สี (Color)') ||($rs_roc_product_v['title']=='กลิ่น (Odor)') ||  ($rs_roc_product_v['title']=='รส (Taste)') || ($rs_roc_product_v['title']=='รูปร่าง (Shape)') || ($rs_roc_product_v['title']=='น้ำหนัก')){
				?>	
					<div style="float:left">ระบุ &nbsp;&nbsp;<?php if(in_array($rs_roc_product_v['id_product_value'],$roc_product_value)){echo $roc_value_title[$j_value];}?></div>
				<?php }													
				echo $br;
				if($num_v==$max_row_v){
					if($rs_product_appearance['id_product_appearance']==4){
				?>
						<div class="title-function" style="width:40%;float:left;"><div style="float:left; margin: 0 0.5% 0 0;"><input type="checkbox" name="roc_product_value[]" id="roc_product_value0"  value="0" <?php if(in_array('0',$roc_product_value)){echo 'checked="checked"';}?>></div><div style="float:left; margin: 0 0.5% 0 0;">ผงชงดื่มประเภทอื่น</div><div style="float:left">ระบุ &nbsp;&nbsp;<?php echo $rs['product_value_title']?></div></div>
				<?php }//end equal Instant Drink
						}
					$rows_v++;
					$j_value++;
				}//end while roc_product_value
				if(($rs_product_appearance['id_product_appearance']== 2) || ($rs_product_appearance['id_product_appearance']== 3)){?>
					<div class="title-weight">ลักษณะผลิตภัณฑ์ที่บรรจุ</div>
					<?php 
					$num_p=0;
					$rows_p=0;
					$j_p=0;
					$sql_roc_product_p="select * from type_product_pack where id_product_appearance='".$rs_product_appearance['id_product_appearance']."'";
					$res_roc_product_p=mysql_query($sql_roc_product_p) or die ('Error '.$sql_roc_product_p);
					$max_row_p=mysql_num_rows($res_roc_product_p);
					while($rs_roc_product_p=mysql_fetch_array($res_roc_product_p)){
						$j_p++;
						$num_p++;
						$br='<div class="clear"></div>';
						$width='width:20%;';
						$float='float:left;';
					?>
						<div class="title-function" style="<?php echo $width.$float?>"><input type="checkbox" id="type_product_pack<?php echo $i_value?>" name="type_product_pack" value="<?php echo $rs_roc_product_p['id_type_product_pack']?>" <?php if($rs['id_type_product_pack']==$rs_roc_product_p['id_type_product_pack']){echo 'checked="checked"';}?>><?echo $rs_roc_product_p['title_product_pack']?></div>
						<?php 
						if(($rs_product_appearance['id_product_appearance']==6) || ($rs_product_appearance['id_product_appearance']==7)){
							if(($rs_roc_product_p['title']== 'สี (Color)') ||($rs_roc_product_p['title']=='กลิ่น (Odor)') || ($rs_roc_product_p['title']=='รูปร่าง (Shape)') || ($rs_roc_product_p['title']=='สี') || ($rs_roc_product_p['title']=='กลิ่น')){
							echo 'ระบุ &nbsp;&nbsp;<input type="text" name="type_product_pack_other[]">';
							}//end color for soft gelation capsule
						}//end functional drink and gummy
					echo $br;
					if($num_p==$max_row_p){
						if($rs_product_appearance['id_type_product']== 3){
					?>
							<div class="title-function" style="<?php echo $width.$float?>"><input type="checkbox" name="type_product_pack" value="0" <?php if($rs['id_type_product_pack']==0){echo 'checked="checked"';}?>>อื่น ๆ &nbsp;&nbsp;ระบุ &nbsp;&nbsp;
					<?php }//end not equal capsule 
					}
					$rows_p++;
					}//end type product pack
				}//end capsule?>
				<div class="clear"></div>
				<?php if($rs_product_appearance['id_product_appearance'] != 7){?>
					<div class="title-weight" colspan="6">น้ำหนักผลิตภัณฑ์ต่อหน่วย</div>
					<?php
					$num_w=0;
					$rows = 0;
					$j=0;
					$sql_roc_product_w="select * from roc_product_weight where id_product_appearance='".$rs_product_appearance['id_product_appearance']."'";
					$res_roc_product_w=mysql_query($sql_roc_product_w) or die ('Error '.$sql_roc_product_w);
					$max_row_w=mysql_num_rows($res_roc_product_w);
					while($rs_roc_product_w=mysql_fetch_array($res_roc_product_w)){
						$j++;
						$num_w++;
						if($num_w %4 == 0){
							$br='<div class="clear"></div>';
							$width='width:20%;';
							$float='float:left;';
						}
						else{
							$br='';
							$width='width:20%;';
							$float='float:left;';
						}
					?>
						<div class="title-function" style="<?php echo $width.$float?>"><input type="checkbox" class="checkbox" name="roc_product_weight" value="<?php echo $rs_roc_product_w['id_product_weight']?>" <?php if($rs['id_product_weight']==$rs_roc_product_w['id_product_weight']){echo 'checked="checked"';}?> disabled><?echo $rs_roc_product_w['title']?></div><?php echo $br?>
						<?php if($num_w==$max_row_w){?>
							<div class="title-function" style="<?php echo $width.$float?>"><div style="float:left; margin: 0 0.5% 0 0;"><input type="checkbox" class="checkbox" name="roc_product_weight" value="0" disabled></div><div style="float:left; margin: 0 0.5% 0 0;">อื่น ๆ</div><div style="float:left">ระบุ &nbsp;&nbsp;</div></div<?php echo $br?>>
						<?php } 
						$rows++;
					}//end while roc_product_w
				}//end if gummy
				?>
				<div class="clear"></div>
				<?php
				if(($rs_product_appearance['id_product_appearance']==1) || ($rs_product_appearance['id_product_appearance']==2) || ($rs_product_appearance['id_product_appearance']==3) || ($rs_product_appearance['id_product_appearance']==4)){?>
				<?php if($rs_product_appearance['id_product_appearance']==1){?><div class="title-weight">สี กลิ่น และรูปร่างของเม็ด</div>
				<?php }elseif($rs_product_appearance['id_product_appearance']==2){?><div class="title-weight">สีของแคปซูล</div>
				<?php }elseif($rs_product_appearance['id_product_appearance']==3){?><div class="title-weight">สีและกลิ่นเปลือกแคปซูล</div>
				<?php }elseif($rs_product_appearance['id_product_appearance']==4){?><div class="title-weight" colspan="6">สีและกลิ่นของผงชงดื่ม</div>
				<?php }
				}?>
				<?php
				$sql_rela_color="select * from roc_relation_color where id_relation_color='".$rs['id_relation_color']."'";
				$res_rela_color=mysql_query($sql_rela_color) or die ('Error '.$sql_rela_color);
				$rs_rela_color=mysql_fetch_array($res_rela_color);

				$roc_color=split(",",$rs_rela_color['id_type_product_color']);
				$roc_color_title=split(",",$rs_rela_color['title_color']);
				$num_c=0;
				$rows_c=0;
				$j_c=0;
				$i_title=0;
				$sql_roc_product_c="select * from type_product_color where id_product_appearance='".$rs_product_appearance['id_product_appearance']."'";
				$res_roc_product_c=mysql_query($sql_roc_product_c) or die ('Error '.$sql_roc_product_c);
				$max_row_c=mysql_num_rows($res_roc_product_c);
				while($rs_roc_product_c=mysql_fetch_array($res_roc_product_c)){
					$j_c++;
					$num_c++;	
					if($num_c %2 == 0){
						$br='<div class="clear"></div>';
						$width='width:25%;';
						$float='float:left;';
					}
					else{
						$br='';
						$width='width:25%;';
						$float='float:left;';
					}	
				?>
					<input type="hidden" name="relation_color" value="<?php echo $rs_rela_color['id_relation_color']?>">
					<div class="title-function" style="<?php echo $width.$float?>"><div style="float:left; margin: 0 0.5% 0 0;"><input type="checkbox" class="checkbox" value="<?php echo $rs_roc_product_c['id_type_product_color']?>" <?php if(in_array($rs_roc_product_c['id_type_product_color'],$roc_color)){echo 'checked="checked"';}?>></div><div style="float:left; margin: 0 0.5% 0 0;"><?echo $rs_roc_product_c['type_product_color']?></div>
					<?php 
					if(($rs_product_appearance['id_product_appearance']==1) || ($rs_product_appearance['id_product_appearance']==3)){
						if(($rs_roc_product_c['type_product_color']== 'สี (Color)') ||($rs_roc_product_c['type_product_color']=='กลิ่น (Odor)') || ($rs_roc_product_c['type_product_color']=='รูปร่าง (Shape)') || ($rs_roc_product_c['type_product_color']=='สี') || ($rs_roc_product_c['type_product_color']=='กลิ่น')){
						
							$sql_rela_color="select * from roc_relation_color where id_roc='".$id."'";
							$sql_rela_color .=" and id_type_product_color='".$rs_roc_product_c['id_type_product_color']."'";
							$res_rela_color=mysql_query($sql_rela_color) or die ('Error '.$sql_rela_color);
							$rs_rela_color=mysql_fetch_array($res_rela_color);
					?>
							<div style="float:left">ระบุ &nbsp;&nbsp;<?php if(in_array($rs_roc_product_c['id_type_product_color'],$roc_color)){ echo $roc_color_title[$i_title];}?></div>
					<?php }//end color for soft gelation capsule
					}//end soft gelatin capsule
					if($rs_product_appearance['id_product_appearance']==4){
						if(($rs_roc_product_c['type_product_color']== 'กลิ่น (Odor)') ||($rs_roc_product_c['type_product_color']=='รส (Taste)') || ($rs_roc_product_c['type_product_color']== 'สี (Color)')) {
					?>
							<div style="float:left">ระบุ &nbsp;&nbsp;<?php if(in_array($rs_roc_product_c['id_type_product_color'],$roc_color)){ echo $roc_color_title[$i_title];}?></div>
					<?php  }//end color for Instant Drink
					}
					?>
					</div><?php echo $br?>
					<?php 
					if($num_c==$max_row_c){$i_title++;
						if($rs_type_product['id_type_product']!=3){
							if($rs_product_appearance['id_product_appearance']==2){$i_title=0;}
							if($rs_product_appearance['id_product_appearance']==3){$i_title=2;}
					?>
							<div class="title-function" style="<?php echo $width.$float?>"><div style="float:left; margin: 0 0.5% 0 0;"><input type="checkbox" class="checkbox" <?php if($rs['id_product_appearance']==$rs_product_appearance['id_product_appearance']){if(in_array('0',$roc_color)){echo 'checked';}}?> value="0" ></div><div style="float:left; margin: 0 0.5% 0 0;">อื่น ๆ</div><div style="float:left">ระบุ &nbsp;&nbsp;<?php if($rs['id_product_appearance']==$rs_product_appearance['id_product_appearance']){if(in_array('0',$roc_color)){echo $roc_color_title[$i_title];}}?></div></div><?php echo $br?>
					<?php  } // not equal soft gelatin capsule
					}
					$i_title++;
					$rows_c++;
				}//end type product pack
								
				if($rs_product_appearance['id_product_appearance'] == 7){
				?>
					<div class="title-weight">ลักษณะการเคลือบ</div>
					<?php 
					$num_p2=0;
					$rows_p2=0;
					$j_p2=0;
					$sql_roc_product_p="select * from type_product_pack where id_product_appearance='".$rs_product_appearance['id_product_appearance']."'";
					$res_roc_product_p=mysql_query($sql_roc_product_p) or die ('Error '.$sql_roc_product_p);
					$max_row_p2=mysql_num_rows($res_roc_product_p);
					while($rs_roc_product_p=mysql_fetch_array($res_roc_product_p)){
						$j_p2++;
						$num_p2++;
						if($num_p2 % 2 == 0){
							$br='<div class="clear"></div>';
							$width='width:20%';
							$float='float:left;';
						}
						else{
							$br='';
							$width='width:20%';
							$float='float:left;';
						}
					?>
						<div class="title-function" style="<?php echo $width.$float?>"><input type="checkbox" class="checkbox" name="type_product_pack" value="<?php echo $rs_roc_product_p['id_type_product_pack']?>" disabled <?php if($rs_roc_product_p['id_type_product_pack']==$rs['id_type_product_pack']){echo 'checked="checked"';}?>><?echo $rs_roc_product_p['title_product_pack']?></div><?php echo $br?>
						<?php if($num_p2==$max_row_p2){?>
						<div class="title-function" style="<?php echo $width.$float?>"><div style="float:left; margin: 0 1% 0 0;"><input type="checkbox" class="checkbox" name="type_product_pack" value="0"></div><div style="float:left; margin: 0 0.5% 0 0;">อื่น ๆ</div><div style="float:left">ระบุ &nbsp;&nbsp;</div></div><?php echo $br?>
					<?php }
						$rows_p2++;
					}//end type product pack
				} //end type product gumm  	
				?>
			</td>
		</tr>
		<?php }//end while product appearanc
		if($num==$max_row){?>
		<tr>
			<td class="title-group" colspan="6"><input type="checkbox" name="id_product_appearance" id="product_appearance<?php echo $i+1?>" <?php if($rs['id_group_product']== -1){echo 'checked';}?> disabled value="-1" onclick="javascript:ShowProductApp();">อื่น ๆ
			<?php if($rs['id_product_appearance']== -1){$style='';}else{$style='display: none;';}?>
			<?php echo 'ระบุ' ;echo '<br>';?>
			.............................................................................................................................................................................<br>.............................................................................................................................................................................<br>.............................................................................................................................................................................
			</td>
		<tr>
		<?php }?>
		<tr>
			<td colspan="6"><p class="title">4.บรรจุภัณฑ์</p></td>
		</tr>
		<tr>
			<?php
			$sql_relation_pack="select * from roc_relation_pack where id_relation_pack='".$rs['id_relation_pack']."'";
			$res_relation_pack=mysql_query($sql_relation_pack) or die ('Error '.$sql_relation_packs);
			$rs_relation_pack=mysql_fetch_array($res_relation_pack);
			$product_app=$rs_relation_pack['id_product_appearance'];
			?>
			<td class="title-group" colspan="6">
				<input type="checkbox" name="roc_pack" value="1,2,3" id="roc_pack1" onclick="javascript:ShowPack();" <?php if($rs_relation_pack['id_product_appearance']=='1,2,3'){echo 'checked';}?> disabled>
				<?php
				$i_app=0;
				$sql_product_appearance="select * from product_appearance";
				$res_product_appearance=mysql_query($sql_product_appearance) or die ('Error '.$sql_product_appearance);
				while($rs_product_appearance=mysql_fetch_array($res_product_appearance)){
					$i++;
					if(($rs_product_appearance['id_product_appearance']==1) || ($rs_product_appearance['id_product_appearance']==2) || ($rs_product_appearance['id_product_appearance']==3)){
						if($i<3){$back="/";}else{$back="";}
							echo $rs_product_appearance['title_thai'].'&nbsp;'.$back.'&nbsp;';
							$pack_array=array($rs_product_appearance['id_type_package']);
						}//end if product apperance
					}//end product appearance
				?>										
			</td>
		</tr>
		<tr>
			<td>
				<?php 
				$pack=$pack_array[0];
				$roc_type_packaging=split(",",$pack);
				for($i_pack=0;$i_pack<=count($roc_type_packaging);$i_pack++){
					$sql_type_packaging="select * from type_packaging where id_type_package='1' or id_type_package='5'";
					$sql_type_packaging .=" order by id_type_package desc";
					$res_type_packaging=mysql_query($sql_type_packaging) or die ('Error '.$sql_type_packaging);
					while($rs_type_packaging=mysql_fetch_array($res_type_packaging)){
				?>	
						<div class="title-function" style="float:left;" colspan="6"><input type="checkbox" name="type_packaging" id="type_packaging<?php echo $i_pack?>" disabled value="<?php echo $rs_type_packaging['id_type_package']?>" <?php if($rs_relation_pack['id_type_package']==$rs_type_packaging['id_type_package']){echo 'checked';}?>><?php echo $rs_type_packaging['title_thai']?></div>
						<div class="clear"></div>
						<div class="title-sub-function" style="padding-bottom:1%;float:left;">ขนาดบรรจุ</div>
						<?php
						//$i_pack=0;
						$num_box_size=0;
						$rows_box_size=0;
						$sql_box_size="select * from roc_product_box_size where id_type_package='".$rs_type_packaging['id_type_package']."'";
						$res_box_size=mysql_query($sql_box_size) or die ('Error '.$sql_box_size);
						$max_row_box_size=mysql_num_rows($res_box_size);
						while($rs_box_size=mysql_fetch_array($res_box_size)){
							$num_box_size++;
							$i_pack++;
							if($num_box_size % 3 == 0){
								$br='<div class="clear"></div>';
								$width='width:20%;';
								$padding='padding-left: 5.6%;';
								$float='float:left;';
							}
							else{
								$br='';
								$width='width:19%;';
								$padding='padding-left: 5.6%;';
								$float='float:left;';
							}
						?>
						<div class="title-function" style="<?php echo $padding.$width.$float?>"><input type="checkbox" name="pack_size" id="pack_size<?php echo $i_pack?>" disabled value="<?php echo $rs_box_size['id_box_size']?>" <?php if($rs_relation_pack['id_pack_size']==$rs_box_size['id_box_size']){echo 'checked';}?>><?echo $rs_box_size['title_box_size']?></div><?php echo $br?>
						<?php if($num_box_size==$max_row_box_size){?>
							<div class="title-function" style="width: 30%;padding-left: 16%;float:left;"><div style="float:left; margin: 0 0.5% 0 0;"><input type="checkbox" class="checkbox" disabled></div><div style="float:left; margin: 0 0.5% 0 0;">อื่น ๆ</div><div style="float:left">ระบุ &nbsp;&nbsp;................................</div></div><?php echo $br?>
						<?php }
					}
				?>
				<div class="clear"></div>
				<?php if($rs_type_packaging['id_type_package']==5){?>
					<div class="title-sub-function" style="float:left;">แผง & ฟอยล์</div>
					<?php
					$roc_foil=split(",",$rs_relation_pack['id_product_foil']);
					$i_foil=0;
					$sql_foil="select * from roc_product_foil where id_type_package='".$rs_type_packaging['id_type_package']."'";
					$res_foil=mysql_query($sql_foil) or die ('Error '.$sql_foil);
					while($rs_foil=mysql_fetch_array($res_foil)){
						$i_foil++;
						if($i_foil % 2 ==0){
							$padding='padding-left: 16%;';
							$width='width:30%;';
							$float='float:left;';
						}else{
							$padding='';
							$width='width:20%;';
							$float='float:left;';
						}
						if($i_foil==1){		
					?>
							<div class="title-function" style="width:20%;float:left;"><input type="checkbox" <?php if($rs_type_packaging['id_type_package']==5){?>id="foil1<?php echo $i_materials?>"<?php }?> name="foil1[]" disabled value="<?php echo $rs_foil['id_product_foil']?>" <?php if(in_array($rs_foil['id_product_foil'],$roc_foil)){echo 'checked';}?>><?php echo $rs_foil['title_foil']?></div><div class="clear"></div>
					<?php }else{?>
							<div class="title-function" style="<?php echo $padding.$width.$float?>"><input type="checkbox" <?php if($rs_type_packaging['id_type_package']==5){?>id="foil2<?php echo $i_materials?>"<?php }?> name="foil1[]" disabled value="<?php echo $rs_foil['id_product_foil']?>" <?php if(in_array($rs_foil['id_product_foil'],$roc_foil)){echo 'checked';}?>><?php echo $rs_foil['title_foil']?></div>		
					<?php }
					}
				}else{?>
				<div class="clear"></div>
				<div class="title-sub-function" style="float:left;">ชนิดขวด</div>
				<?php
				$num_bottle=0;
				$sql_bottle="select * from roc_product_bottle where id_type_package='".$rs_type_packaging['id_type_package']."'";
				$res_bottle=mysql_query($sql_bottle) or die ('Error '.$sql_bottle);
				$max_row_bottle=mysql_num_rows($res_bottle);
				while($rs_bottle=mysql_fetch_array($res_bottle)){
					$num_bottle++;
					if($num_bottle != 1){
						$padding='padding-left:0;';
						$width='width:10%;';
						$float='float:left;';
					}else{
						$padding='padding-left: 6.4%;';
						$width='width:15%;';
						$float='float:left;';
					}
				?>
					<div class="title-function" style="<?php echo $padding.$width.$float?>"><input type="checkbox" class="checkbox" name="bottle" disabled value="<?php echo $rs_bottle['id_product_bottle']?>" <?php if($rs_relation_pack['id_product_bottle']==$rs_bottle['id_product_bottle']){echo 'checked';}?>><?php echo $rs_bottle['title_bottle']?></div>		
					<?php if($num_bottle==$max_row_bottle){?>
						<div class="title-function" style="width:20%;float:left;"><div style="float:left; margin: 0 0.5% 0 0;"><input type="checkbox" class="checkbox" disabled></div><div style="float:left; margin: 0 0.5% 0 0;">อื่น ๆ</div><div style="float:left">ระบุ &nbsp;&nbsp;.....................</div></div>
					<?php }//end max row ?>
				<?php 
					}
				}?>
				<div class="clear"></div>
				<div class="title-sub-function" style="float:left;">วัสดุบรรจุประกอบ</div>
				<?php
				$roc_materials=split(",",$rs_relation_pack['id_material']);
				$num_material=0;
				$sql_materials="select * from roc_product_materials where id_type_product='".$rs_type_packaging['id_type_package']."'";
				$res_materials=mysql_query($sql_materials) or die ('Error '.$sql_materials);
				$max_row_material=mysql_num_rows($res_materials);
				while($rs_materials=mysql_fetch_array($res_materials)){
					$num_material++;
					if($num_material != 1){
						$padding='padding-left: 16%;';
						$width='width: 50%;';
						$float='float:left;';
					}else{
						$padding='padding-left: 3%;';
						$width='width: 50%;';
						$float='float:left;';
					}
				?>	
					<div class="title-function" style="<?php echo $padding.$width.$float?>"><div style="float:left; margin: 0 0.5% 0 0;"><input type="checkbox" <?php if($rs_type_packaging['id_type_package']==1){?>id="materials1<?php echo $i_materials?>"<?php }elseif($rs_type_packaging['id_type_package']==5){?>id="materials2<?php echo $i_materials?>"<?php }?> name="materials1[]" disabled value="<?php echo $rs_materials['id_materials']?>" <?php if(in_array($rs_materials['id_materials'],$roc_materials)){echo 'checked';}?>></div><div style="float:left; margin: 0 0.5% 0 0;"><?php echo $rs_materials['title_materials']?></div>
					<?php if($rs_materials['title_materials']=='ขนาดกล่อง'){?>
						<div style="float:left">ระบุ &nbsp;&nbsp;<?php if(in_array($rs_materials['id_materials'],$roc_materials)){echo $rs_relation_pack['box_detail'];}?></div>
					<?php ;}?>
				</div>
				<div class="clear"></div>
				<?php 
				if($num_material==$max_row_material){	
					if($rs_type_packaging['id_type_package'] != 5){
				?>
						<div class="title-function" style="padding-left:16%;width:30%;float:left;"><div style="float:left; margin: 0 0.5% 0 0;"><input type="checkbox" class="checkbox" disabled></div><div style="float:left; margin: 0 0.5% 0 0;">อื่น ๆ</div><div style="float:left">ระบุ &nbsp;&nbsp;</div></div><div class="clear"></div>
				<?php	}	 
				}//end max row 
				}//end while type packaging
				}//end for array pack
				}
				?>
				</div><div class="clear"></div>
			</td>
		</tr>
		<?php
		$sql_relation_pack="select * from roc_relation_pack where id_relation_pack='".$rs['id_relation_pack']."'";
		$res_relation_pack=mysql_query($sql_relation_pack) or die ('Error '.$sql_relation_packs);
		$rs_relation_pack=mysql_fetch_array($res_relation_pack);
		$i_app=0;
		
		$sql_product_appearance="select * from product_appearance";
		$res_product_appearance=mysql_query($sql_product_appearance) or die ('Error '.$sql_product_appearance);
		while($rs_product_appearance=mysql_fetch_array($res_product_appearance)){
			$i_app++;
			if(($rs_product_appearance['id_product_appearance']==4) || ($rs_product_appearance['id_product_appearance']==5) || ($rs_product_appearance['id_product_appearance']==6)){
		?>
		<tr>
			<td class="title-group" colspan="6"><input type="checkbox" name="roc_pack" id="roc_pack<?php echo $i_app?>" disabled value="<?php echo $rs_product_appearance['id_product_appearance']?>" onclick="javascript:ShowPack();" <?php if($rs_relation_pack['id_product_appearance']==$rs_product_appearance['id_product_appearance']){echo 'checked';}?>>
			<?php echo $rs_product_appearance['title_thai'].'('.$rs_product_appearance['title'].')';?>
			</td>
		</tr>
		<tr>
			<td>
				<?php if($rs_product_appearance['id_product_appearance']==4){?><div class="title-function" style="padding-bottom:1%;float:left;">จำนวนบรรจุกล่อง</div><?php }?>
				<?php if($rs_product_appearance['id_product_appearance']==5){?><div class="title-function" style="padding-bottom:1%;float:left;">จำนวนบรรจุ</div><?php }?>
				<?php if($rs_product_appearance['id_product_appearance']==6){?><div class="title-function" style="padding-bottom:1%;float:left;">จำนวนบรรจุต่อกล่อง</div><?php }?>
				<?php
				$num_box_size=0;
				$rows_box_size=0;
				$sql_box_size="select * from roc_product_box_size where id_type_product='".$rs_product_appearance['id_product_appearance']."'";
				$res_box_size=mysql_query($sql_box_size) or die ('Error '.$sql_box_size);
				$max_row_box_size=mysql_num_rows($res_box_size);
				while($rs_box_size=mysql_fetch_array($res_box_size)){
					$num_box_size++;	
					if($num_box_size % 3 == 0){														
						$br='<div class="clear"></div>';
						$width='width:15%;';
						$float='float:left;';
						if($num_box_size == 1){$padding='padding-left: 4%;';}
						else{$padding='padding-left: 2%;';}
					}else{
						$br='';
						$width='width:15%;';
						$float='float:left;';
						if($rs_product_appearance['id_product_appearance']==4){
							if($num_box_size == 4){$width='width:25%;';$padding='padding-left: 15.6%;';}
							else{$padding='padding-left: 4%;';}
						}else
						if($rs_product_appearance['id_product_appearance']==5){
							if($num_box_size == 4){$width='width:25%;';$padding='padding-left: 15.6%;';}
							else{$padding='padding-left: 6.2%;';}
						}else
							if($rs_product_appearance['id_product_appearance']==6){
								if($num_box_size == 4){$width='width:25%;';$padding='padding-left: 15.6%;';}
								else{$width='width:10%;';$padding='padding-left: 3%;';}
							}
					}
				?>
					<div class="title-function" style="<?php echo $width.$padding.$float?>"><input type="checkbox" class="checkbox" name="pack_size" disabled value="<?php echo $rs_box_size['id_box_size']?>" <?php if($rs_relation_pack['id_pack_size']==$rs_box_size['id_box_size']){echo 'checked';}?>><?echo $rs_box_size['title_box_size']?></div><?php echo $br?>
					<?php if($num_box_size==$max_row_box_size){
						if($rs_product_appearance['id_product_appearance']==6){  
							$padding='padding-left: 4%;';
						}else{$padding='padding-left: 5.5%;';}
					?>
					<div class="title-function" style="width:20%;float:left;<?php echo $padding?>"><div style="float:left; margin: 0 0.5% 0 0;"><input type="checkbox" class="checkbox" disabled></div><div style="float:left; margin: 0 0.5% 0 0;">อื่น ๆ</div><div style="float:left">ระบุ &nbsp;&nbsp;............</div></div><?php echo $br?>
					<?php } 
					$rows_box_size++;
					}
				?>
				<div class="clear"></div>
				<?php if($rs_product_appearance['id_product_appearance']==4){?><div class="title-function" style="padding-bottom:1%;float:left;">ขนาดซอง</div><?php }?>
				<?php if($rs_product_appearance['id_product_appearance']==5){?><div class="title-function" style="padding-bottom:1%;float:left;">รูปแบบซอง</div><?php }?>
				<?php
				$num_sachet=0;
				$rows_sachet=0;
				$sql_sachet="select * from roc_product_sachet where id_type_package='".$rs_product_appearance['id_product_appearance']."'";
				$res_sachet=mysql_query($sql_sachet) or die ('Error '.$sql_sachet);
				$max_row_sachet=mysql_num_rows($res_sachet);
				while($rs_sachet=mysql_fetch_array($res_sachet)){
					$num_sachet++;	
					if($num_sachet % 2 == 0){
						$br='<div class="clear"></div>';
						$float='float:left;';
						if($rs_product_appearance['id_product_appearance']==4){
							$width='width:20%;';
							$padding='padding-left: 0.5%;';
						}else
							if($rs_product_appearance['id_product_appearance']==5){
								$width='width:15%;';
								$padding='padding-left: 1.2%;';
							}
					}else{
						$br='';			
						$float='float:left;';
						if($rs_product_appearance['id_product_appearance']==4){
							if(($num_sachet == 3) || ($num_sachet == 5)){$width='width:30%;';$padding='padding-left: 15.6%;';}
							else{$width='width:21.4%;';$padding='padding-left: 6.9%;';}
						}else
							if($rs_product_appearance['id_product_appearance']==5){
								if(($num_sachet == 3) || ($num_sachet == 5)){$width='width:30%;';$padding='padding-left: 15.6%;';}
								else{$width='width:20%;';$padding='padding-left: 6.2%;';}
						}
					}
				?>
					<div class="title-function" style="<?php echo $width.$padding.$float?>"><input type="checkbox" class="checkbox" name="sachet" disabled value="<?php echo $rs_sachet['id_product_sachet']?>" <?php if($rs_relation_pack['id_product_sachet']==$rs_sachet['id_product_sachet']){echo 'checked';}?>><?echo $rs_sachet['title_sachet']?></div><?php echo $br?>
					<?php if($num_sachet==$max_row_sachet){?>
						<div class="title-function" style="width:20%;padding-left:0.5%;float:left;"><div style="float:left; margin: 0 0.5% 0 0;"><input type="checkbox" class="checkbox" disabled></div><div style="float:left; margin: 0 0.5% 0 0;">อื่น ๆ</div><div style="float:left">ระบุ &nbsp;&nbsp;..................</div></div>
					<?php } ?>
				<?php 
					$rows_sachet++;
				}
				?>
				<div class="clear"></div>
				<?php if($rs_product_appearance['id_product_appearance']==4){?>
					<div class="title-function" style="padding-bottom:1%;float:left;">ฟอยล์</div>
					<?php
					$num_foil=0;
					$rows_foil=0;
					$sql_foil="select * from roc_product_foil where id_type_package='".$rs_product_appearance['id_product_appearance']."'";
					$res_foil=mysql_query($sql_foil) or die ('Error '.$sql_foil);
					while($rs_foil=mysql_fetch_array($res_foil)){
						$num_foil++;	
						$width='width:20%;';
						$float='float:left;';
						if($num_foil==1){$padding='padding-left:8.5%;';}
						else{$padding='padding-left:3.5%;';}
					?>
						<div class="title-function" style="<?php echo $width.$padding.$float?>"><input type="checkbox" class="checkbox" name="foil4[]" disabled value="<?php echo $rs_foil['id_product_foil']?>" <?php if($rs_relation_pack['id_product_foil']==$rs_foil['id_product_foil']){echo 'checked';}?>><?php echo $rs_foil['title_foil']?></div>
						<?php 
						$rows_foil++;
					}
					?>
					<div class="clear"></div>
				<?php }elseif($rs_product_appearance['id_product_appearance']==6){?>
					<div class="title-function" style="float:left;">รูปแบบขวด</div>
					<?php
					$num_bottle=0;
					$sql_bottle="select * from roc_type_bottle where id_type_package='".$rs_product_appearance['id_product_appearance']."'";
					$res_bottle=mysql_query($sql_bottle) or die ('Error '.$sql_bottle);
					$max_row_bottle=mysql_num_rows($res_bottle);
					while($rs_bottle=mysql_fetch_array($res_bottle)){
						$num_bottle++;
						if($num_bottle % 3 == 0){														
							$br='<div class="clear"></div>';
							$width='width:15%;';
							$padding='padding-left: 6.5%;';
							$float='float:left;';
						}else{
							$br='';
							$width='width:15%;';
							$padding='padding-left: 6.4%;';
							$float='float:left;';
						}
					?>
						<div class="title-function" style="<?php echo $width.$padding.$float?>"><input type="checkbox" class="checkbox" name="bottle_size" disabled value="<?php echo $rs_bottle['id_type_bottle']?>" <?php if($rs_relation_pack['id_type_bottle']==$rs_bottle['id_type_bottle']){echo 'checked';}?>><?php echo $rs_bottle['title_type_bottle']?></div><?php echo $br?>		
						<?php if($num_bottle==$max_row_bottle){?>
							<div class="title-function" style="width:30%;padding-left:15.6%;float:left;"><div style="float:left; margin: 0 0.5% 0 0;"><input type="checkbox" class="checkbox" disabled></div><div style="float:left; margin: 0 0.5% 0 0;">อื่น ๆ</div><div style="float:left">ระบุ &nbsp;&nbsp;...................</div></div>
				<?php }//end max row 
					}//end while
				?>
				<div class="clear"></div>
				<div class="title-function" style="float:left;">ลักษณะฝา</div>
				<?php
				$num_lid=0;
				$sql_lid="select * from roc_product_bottle_lid where id_type_package='".$rs_product_appearance['id_product_appearance']."'";
				$res_lid=mysql_query($sql_lid) or die ('Error '.$sql_lid);
				while($rs_lid=mysql_fetch_array($res_lid)){
					$num_lid++;
					if($num_lid % 3 == 0){														
						$br='<div class="clear"></div>';
						$width='width:15%;';
						$padding='padding-left: 6.4%;';
						$float='float:left;';
					}else{
						$br='';
						$width='width:13%;';
						$padding='padding-left: 6.7%;';
						$float='float:left;';
					}
				?>
					<div class="title-function" style="<?php echo $width.$padding.$float?>"><input type="checkbox" class="checkbox" name="bottle_lid" disabled value="<?php echo $rs_lid['id_bottle_lid']?>" <?php if($rs_relation_pack['id_bottle_lid']==$rs_lid['id_bottle_lid']){echo 'checked';}?>><?php echo $rs_lid['title_bottle_lid']?></div><?php echo $br?>	
				<?php 
				}//end while
				?>
				<div class="clear"></div>
				<?php }//end if product=6?>
				<div class="title-function" style="float:left;">วัสดุบรรจุประกอบ</div>
				<?php
				$i_materials=0;
				$roc_materials=split(",",$rs_relation_pack['id_material']);
				$num_material=0;
				$sql_materials="select * from roc_product_materials where id_type_package='".$rs_product_appearance['id_product_appearance']."'";
				$res_materials=mysql_query($sql_materials) or die ('Error '.$sql_materials);
				$max_row_material=mysql_num_rows($res_materials);
				while($rs_materials=mysql_fetch_array($res_materials)){
					$num_material++;
					$i_materials++;
					if($num_material != 1){
						$padding='padding-left: 15.6%;';
						$width='width: 50%;';
						$float='float:left;';
					}else{
						$padding='padding-left: 4%;';
						$width='width: 50%;';
						$float='float:left;';
					}
				?>	
					<div class="title-function" style="<?php echo $padding.$width.$float?>"><div style="float:left; margin: 0 0.5% 0 0;"><input type="checkbox" disabled <?php if($rs_product_appearance['id_product_appearance']==4){?>id="materials4" name="materials4[]" <?php echo $i_materials?><?php }elseif($rs_product_appearance['id_product_appearance']==5){?>id="materials5<?php echo $i_materials?>" name="materials5[]"<?php }elseif($rs_product_appearance['id_product_appearance']==6){?>id="materials6<?php echo $i_materials?>" name="materials6[]"<?php }?> value="<?php echo $rs_materials['id_materials']?>" <?php if(in_array($rs_materials['id_materials'],$roc_materials)){echo 'checked';}?>></div><div style="float:left; margin: 0 0.5% 0 0;"><?php echo $rs_materials['title_materials']?></div>
				<?php 
					if($rs_materials['title_materials']=='ขนาดกล่อง'){
				?>
						<div style="float:left">ระบุ &nbsp;&nbsp;<?php if(in_array($rs_materials['id_materials'],$roc_materials)){echo $rs_relation_pack['box_detail'];}?></div>
				<?php }?>
				<div class="clear"></div>
				<?php if($num_material==$max_row_material){
					if($rs_type_packaging['id_type_package'] != 5){
				?>
						<div class="title-function" style="padding-left: 15.6%;width:30%;float:left;"><div style="float:left; margin: 0 0.5% 0 0;"><input type="checkbox" class="checkbox" disabled></div><div style="float:left; margin: 0 0.5% 0 0;">อื่น ๆ</div><div style="float:left">ระบุ &nbsp;&nbsp;....................</div></div><div class="clear"></div>
				<?php }//end max row ?>
				<?php 
					}//end product appearance	
					}//end materials
					}//end while type packaging
				}//end for 
				?>
				<tr>
										<td class="title-group" colspan="2"><input type="checkbox" name="ink_jet" disabled value="1" <?php if($rs['id_ink_jet']==1){echo 'checked';}?>>Ink Jet</td>
									</tr>
									<?php 
									$i_ink=0;
									$type_ink=split(",",$rs['id_type_ink_jet']);
									$detail_ink=split(",",$rs['id_detail_ink']);
									$sql_ink="select * from roc_ink_jet";
									$res_ink=mysql_query($sql_ink) or die ('Error '.$sql_ink);
									while($rs_ink=mysql_fetch_array($res_ink)){
										$i_ink++;
										if(($rs_ink['id_ink_jet']==1)||($rs_ink['id_ink_jet']==2)){$rowspan='rowspan="4"';}
										if(($rs_ink['id_ink_jet']==3)||($rs_ink['id_ink_jet']==4)){$rowspan='rowspan="3"';}
									?>
									<tr>
										<td <?php echo $rowspan?> class="title-function" style="width:18%;"><input type="checkbox" name="type_ink_jet[]" id="type_ink<?php echo $i_ink?>" disabled value="<?php echo $rs_ink['id_ink_jet']?>" <?php if(in_array($rs_ink['id_ink_jet'],$type_ink)){echo 'checked';}?>><?php echo $rs_ink['title_ink_jet']?></td>
										<?php
										$j_ink_detail=0;
										$sql_ink_detail="select * from roc_ink_jet_detail where id_ink_jet='".$rs_ink['id_ink_jet']."'";
										$res_ink_detail=mysql_query($sql_ink_detail) or die ('Error '.$sql_ink_detail);
										while($rs_ink_detail=mysql_fetch_array($res_ink_detail)){
											$j_ink_detail++;
											if($j_ink_detail==1){
										?>
											<td class="title-function"><input type="checkbox" name="ink_jet_detail[]" rel="type_ink<?php echo $i_ink?>" value="<?php echo $rs_ink_detail['id_detail_ink']?>" disabled <?php if(in_array($rs_ink_detail['id_detail_ink'],$detail_ink)){echo 'checked';}?>><?php echo $rs_ink_detail['title_detail_ink']?></td>
										<?php }else{?>
										<tr>
											<td class="title-function"><input type="checkbox" rel="type_ink<?php echo $i_ink?>" name="ink_jet_detail[]" value="<?php echo $rs_ink_detail['id_detail_ink']?>" disabled <?php if(in_array($rs_ink_detail['id_detail_ink'],$detail_ink)){echo 'checked';}?>><?php echo $rs_ink_detail['title_detail_ink']?></td>
										</tr>
										<?php }										
										}//end while ink jet detail?>
									</tr>
									<?php 
									}//end while ink jet
									?>
									<tr>
										<td class="title-group" colspan="2"><b>5.ราคาโดยประมาณของผลิตภัณฑ์สำเร็จรูปที่ต้องการ </b><?php echo '&nbsp;&nbsp;&nbsp;';echo $rs['product_price']?></td>
									</tr>
									<tr>
										<td class="title-group" colspan="2"><b>6.ผลิตภัณฑ์ในท้องตลาดที่เป็นตัวเปรียบเทียบ</b><?php echo '&nbsp;&nbsp;&nbsp;';echo $rs['product_compare']?></td>
									</tr>
									<tr>
										<td class="title-group" colspan="2"><b>7.Product selling point</b><?php echo '&nbsp;&nbsp;&nbsp;';echo $rs['product_selling']?></td>
									</tr>
									<tr>
										<td class="title-group" colspan="2"><b>8.Market position</b><?php echo '&nbsp;&nbsp;&nbsp;';echo $rs['market_position']?></td>
									</tr>
									<tr>
										<td class="title-group" colspan="2"><b>9.Selling channel</b><?php echo '&nbsp;&nbsp;&nbsp;';echo $rs['selling_channel']?></td>
									</tr>
			</td>
		</tr>
	</table>
</body>
</html>
<?php
	$html = ob_get_contents();
	ob_end_clean();
	$mpdf=new mPDF('th','A4',0,'',15,15,32,5,5,5,'THSaraban');
	$mpdf-> SetAutoFont();
	$mpdf-> SetHTMLHeader('<table cellspacing="0" cellpadding="0"><tr>
			<td colspan="2" style="padding-bottom: 2%;border-bottom:1px solid #000;"><img src="img/logo.png" style="width:20%;"></td>
			<td colspan="4" style="vertical-align:bottom;padding-bottom:2%;font-family:Arial;padding-left:2%;font-size: 1.2em;border-bottom:1px solid #000;">บริษัท ซีดีไอพี (ประเทศไทย) จำกัด<br>
			CDIP (Thailand) Co.,Ltd.<br>
			บันทึกความต้องการของลูกค้า (Requisition of Customer)<br>
			</td>
		</tr></table>');
	$mpdf-> WriteHTML($html);
	$mpdf-> Output("roc/aaa.pdf","I");
?>