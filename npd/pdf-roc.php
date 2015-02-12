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
	<table style="width:100%;font-size:0.9em;text-align:left;" cellpadding="0" cellspacing="0">
		<tr>
			<td style="padding:2% 0 0 2%;border-left:1px solid #000;width:30%;vertical-align:top;">บริษัท
				<?php 
				$sql_company="select * from company where id_company='".$rs['id_company']."'";
				$res_company=mysql_query($sql_company) or die ('Error '.$sql_company);
				$rs_company=mysql_fetch_array($res_company);
				?>
				<?php if($rs_company['company_name']==''){echo '......................................................';}else{echo '<span style="padding:3% 0 0 0;color:#0079FC;">'.$rs_company['company_name'].'</span>';}?>				
			</td>
			<td style="padding:2% 0 0 0;width:32%;vertical-align:top;">ชื่อผู้ติดต่อ
				<?php 
				$sql_contact="select * from company_contact where id_contact='".$rs['id_contact']."'";
				$res_contact=mysql_query($sql_contact) or die ('Error '.$sql_contact);
				$rs_contact=mysql_fetch_array($res_contact);
				?>
				<?php if($rs_contact['contact_name']==''){echo '.................................................';}else{echo '<span style="padding:3% 0 0 0;color:#0079FC;">'.$rs_contact['contact_name'].'</span>';}?>							
			</td>
			<td style="padding:2% 2% 0 0;border-right:1px solid #000;vertical-align:top;">เลขที่เอกสาร
				<?php 
				if($rs['roc_rev']==0){echo $rev='';$roc_rev='';$num_rev='';}
				else{
					if($rs['roc_rev']<10){$rev=' Rev.';$roc_rev='0';$num_rev=$roc_rev['roc_rev']+1;}else{echo $rev=' Rev.';$roc_rev='';$num_rev=$roc_rev['roc_rev']+1;}
				}
				if($rs['roc_code']==''){echo '..................................................';}echo '<span style="padding:3% 2% 0 0;color:#0079FC;">'.$rs['roc_code'].$rev.$roc_rev.$num_rev.'</span>'?>
			</td>
		</tr>
		<tr>
			<td style="padding:1.5% 0 0 2%;border-left:1px solid #000;">เบอร์โทร		
				<?php if($rs_company['company_tel']==''){echo '.....................................';}else{echo '<span style="color:#0079FC;">';echo $rs_company['company_tel'];echo '</span>';}?></span>
			</td>
			<td style="padding:1.5% 0 0 2%;">มือถือ
				<?php if($rs_contact['mobile']==''){echo '.....................................................';}else{echo '<span style="color:#0079FC;">';echo $rs_contact['mobile'];echo '</span>';}?></span>
			</td>
			<td style="padding:1.5% 2% 0 0;border-right:1px solid #000;">วันที่
				<?php
					if($rs['date_roc']==''){echo '..............................................................';}
					else{
						list($ckyear,$ckmonth,$ckday) = split('[/.-]', $rs['date_roc']); 
						echo '<span style="color:#0079FC;">';
						echo $ckstart= $ckday . "/". $ckmonth . "/" .$ckyear;
						echo '</span>';
					}
				?>
			</td>
		</tr>
		<tr>
			<td style="padding:1.5% 0 0 2%;border-left:1px solid #000;">แฟกซ์
				<?php if($rs_company['company_fax']==''){echo '.....................................';}else{echo '<span style="color:#0079FC;">';echo $rs_company['company_fax'];echo '</span>';}?></span>
			</td>
			<td colspan="2" style="padding:1.5% 2% 0 0;border-right:1px solid #000;">อีเมล์
				<span style="padding:0 2% 0 0;"><?php if($rs_contact['email']==''){echo '..........................................................................................................';}else{echo '<span style="color:#0079FC;">';echo $rs_contact['email'];echo '</span>';}?></span>
			</td>
		</tr>
		<tr>
			<td colspan="3" style="padding:1.5% 2% 0 2%;border-left:1px solid #000;border-right:1px solid #000;">ที่อยู่				
				<?php 
				$sql_address="select * from company_address where id_address='".$rs_company['id_address']."'";
				$res_address=mysql_query($sql_address) or die ('Error '.$sql_address);
				$rs_address=mysql_fetch_array($res_address);
				if($rs['id_address']==0){
					if($rs['address']==''){
						echo '..........................................................................................................................................................................................................';
						echo '<br><br>';
						echo '...................................................................................................................................................................................................................';
					}
					else{
						echo '<span style="color:#0079FC;">';
						echo $rs['address'];
						echo '</span>';
					}
				}else{
					if(($rs_address['address_no']=='') && ($rs_address['road']=='') && ($rs_address['sub_district']=='') && ($rs_address['district']=='') && ($rs_address['province']=='') && ($rs_address['postal_code']=='')){
						echo '..........................................................................................................................................................................................................';
						echo '<br><br>';
						echo '...................................................................................................................................................................................................................';
					}else{
						echo '<span style="color:#0079FC;">';
						echo $rs_address['address_no'].'&nbsp;'.$rs_address['road'].'&nbsp;'.$rs_address['sub_district'].'&nbsp;'.$rs_address['district'].'&nbsp;'.$rs_address['province'].'&nbsp;'.$rs_address['postal_code'];
						echo '</span>';
					}
				}
				?>				
			</td>
		</tr>
	</table>
	<table style="width:100%;text-align:left;border-right:1px solid #000;border-left:1px solid #000;" cellpadding="0" cellspacing="0">
		<tr>			
			<td style="width:95%;">
				<table style="width:100%;font-size:0.9em;text-align:left;" cellpadding="0" cellspacing="0">
				<?php
				$rows_com_cate=0;
				$j=0;
				$sql_com_cate="select * from company_category";
				$res_com_cate=mysql_query($sql_com_cate) or die ('Error '.$sql_com_cate);
				$max_row=mysql_num_rows($res_com_cate);
				while($rs_com_cate=mysql_fetch_array($res_com_cate)){
					if($rows_com_cate % 2 ==0){?><tr><?php }
					$j++;
					if($j==1){$title='Identify Customer:';$border_left='vertical-align:top;';}
					else{$title='';$border_left='';}
				?>
					<td style="padding:1.5% 0 0 2%;<?php echo $border_left?>"><?php echo $title?></td>
					<td style="padding:1.5% 0 0 2%;vertical-align:top;">
						<input type="checkbox" <?php if($rs_com_cate['id_com_cat']==$rs['id_com_cat']){echo 'checked="checked"';}?>><?php if($rs_com_cate['id_com_cat']==$rs['id_com_cat']){$style_com_cate='<span style="color:#0079FC;">';}else{$style_com_cate='';}echo $style_com_cate.$rs_com_cate['title']?></td>
					<?php if($rows_com_cate % 2 == 0){ ?></tr><?php } 
						$rows_com_cate++;
				}//end while type device
				if($max_row==$rows_com_cate){
				?>
					<tr>
						<td><?php echo $title?></td>
						<td style="padding:1.5% 0 0 2%;">
							<input type="checkbox" <?php if($rs['id_com_cat']==-1){echo 'checked="checked"';}?>>อื่น ๆ ระบุ
							<?php if($rs['other_category']==''){echo '......................................';}else{echo '<span style="color:#0079FC;">';echo $rs['other_category'];echo '</span>';}?>
						</td>
					</tr>
				<?php }?>
				</table>
			</td>
		</tr>
	</table>
	<table style="width:100%;font-size:0.9em;text-align:left;border-left:1px solid #000;border-right:1px solid #000;" cellpadding="0" cellspacing="0">
		<tr>
			<td style="width:24%;padding:2% 0 0 2%;">Project Name/Benchmark :</td>
			<td colspan="2" style="padding:2% 0 0 2%;"><?php if($rs_product['product_name']==''){echo '.................................................................................................................................................';}else{echo '<span style="color:#0079FC;">'.$rs_product['product_name'].'</span>';}?>
			</td>
  		</tr>
		<tr>
			<td style="vertical-align:top;padding:2% 0 0 2%;">ชนิดของผลิตภัณฑ์ :</td>
			<td style="padding:0.5% 0 0 0;">
				<table style="width:100%;" cellpadding="0" cellspacing="0">
					<?php
					$i_product=0;
					$sql_type_product="select * from npd_type_product order by id_npd_type_product asc";
					$res_type_product=mysql_query($sql_type_product) or die ('Error '.$sql_type_product);
					while($rs_type_product=mysql_fetch_array($res_type_product)){
						if($i_product % 3 ==0){?><tr><?php }
					?>
						<td style="padding:1.5% 0 0 0;width:33.33%;">
							<input type="checkbox" <?php if($rs['id_type_product']==$rs_type_product['id_npd_type_product']){echo'checked="checked"';}?>><?php if($rs['id_type_product']==$rs_type_product['id_npd_type_product']){$style_type_product='<span style="color:#0079FC;">';}else{$style_type_product='';}echo $style_type_product.$rs_type_product['npd_title'].'&nbsp;&nbsp;&nbsp;'?>
						</td>
					<?php if($i_product % 3 == 0){ ?></tr><?php } 
						$i_product++;
					} ?>
				</table>
			</td>
			<td style="width:10%;"></td>
		</tr>
	</table>
	<table style="width:100%;text-align:left;border-left:1px solid #000;border-right:1px solid #000;border-bottom:1px solid #000;" cellpadding="0" cellspacing="0">
		<tr>
			<td colspan="2" style="padding:1.5% 0 0 2%;font-size:0.9em;"><u>วัตถุประสงค์ที่ต้องการ</u></td>
		</tr>
		<tr>
			<td colspan="2" style="padding:1.0% 0 1% 2%;font-size:0.9em;">1.ฟังก์ชั่นการทำงาน</td>
		</tr>
		<?php
		$roc_func=split(",",$rs['id_roc_func']);
		$roc_group_func=split(",",$rs['id_group_product']);
		$roc_other_func=split(",",$rs['roc_func_other']);
		$i=0;
		$j=0;
		$sql_roc_group_product="select * from roc_group_product";
		$res_roc_group_product=mysql_query($sql_roc_group_product) or die ('Error '.$sql_roc_group_product);
		$max_row_g=mysql_num_rows($res_roc_group_product);
		while($rs_roc_group_product=mysql_fetch_array($res_roc_group_product)){
			$i++;
		?>
		<tr>
			<td style="padding:0 0 0 3%;font-size:0.9em;border-right:1px solid #000;"><input type="checkbox" <?php if(is_numeric($id)){if(in_array($rs_roc_group_product['id_group_product'],$roc_group_func)){echo 'checked="checked"';}}?>>
			<?php if(in_array($rs_roc_group_product['id_group_product'],$roc_group_func)){$style_func='<span style="color:#0079FC;">';$style_end_func='</span>';}else{$style_func='';$style_end_func='';}?>
			<?php echo $style_func?>1.<?php echo $i.'&nbsp;'.$rs_roc_group_product['title'];?><?php echo $style_end_func;?></td>
		</tr>
		<tr>
			<td style="padding:0.5% 0 2.5% 3%;font-size:0.9em;border-right:1px solid #000;">
				<table style="width:100%;"cellpadding="0" cellspacing="0">
				<?php;
				$i_function=0;
				$max_row_g2=0;
				$num=0;
				$sql_roc_function="select * from roc_function where id_group_product='".$rs_roc_group_product['id_group_product']."'";
				$res_roc_function=mysql_query($sql_roc_function) or die ('Error '.$sql_roc_function);
				$max_row=mysql_num_rows($res_roc_function);
				while($rs_roc_function=mysql_fetch_array($res_roc_function)){
					if(($rs_roc_group_product['id_group_product']==1) || ($rs_roc_group_product['id_group_product']==2)){
					$num++;	
					if($i_function % 2 == 0){?><tr><?php } //display row
					if(in_array($rs_roc_function['id_roc_func'],$roc_func)){
						$style_func='<span style="color:#0079FC;">';
						$style_end_func='</span>';
					}else{
						$style_func='';
						$style_end_func='';
					}
				?>
						<td style="width:45%; padding:0.8% 0 0 3%;font-size:0.9em;"><input type="checkbox" <?php if(in_array($rs_roc_function['id_roc_func'],$roc_func)){echo 'checked="checked"';}?>><?echo $style_func.$rs_roc_function['title'].$style_end_func?></td>
					<?php if($i_function % 2 == 0){?></tr><?php } //display end row?>
					<?php if($num==$max_row){  ?>
					<tr>
						<td style="width:45%; padding:0.5% 0 0 3%;font-size:0.9em;"><input type="checkbox" <?php if($rs_roc_group_product['id_group_product']==1){if(in_array('0',$roc_func)){echo 'checked="checked"';$style_other_func='<span style="color:#0079FC;">';$style_end_other_func='</span>';}}elseif($rs_roc_group_product['id_group_product']==2){if(in_array('-1',$roc_func)){echo 'checked="checked"';$style_other_func='<span style="color:#0079FC;">';$style_end_other_func='</span>';}}elseif($rs_roc_group_product['id_group_product']==3){if(in_array('-2',$roc_func)){echo 'checked="checked"';$style_other_func='<span style="color:#0079FC;">';$style_end_other_func='</span>';}}elseif($rs_roc_group_product['id_group_product']==4){if(in_array('-3',$roc_func)){echo 'checked="checked"';$style_other_func='<span style="color:#0079FC;">';$style_end_other_func='</span>';}}?>><?php echo $style_other_func?>อื่น ๆ (โปรดระบุ)<?php echo $style_end_other_func?><?php if($roc_other_func[$j]==''){echo '........................';}else{echo '&nbsp;'.$style_other_func.$roc_other_func[$j].$style_end_other_func;}?></td>
					</tr>
					<?php $j++;$max_row_g2=$max_row_g+1;
					} $i_function++; 
					?>
				<?php }else{
					$num++;	
					if($i_function % 1 == 0){?><tr><?php } //display row
				?>
						<td style="width:45%; padding:0.8% 0 0 3%;font-size:0.9em;"><input type="checkbox" <?php if(in_array($rs_roc_function['id_roc_func'],$roc_func)){echo 'checked="checked"';}?>><?echo $rs_roc_function['title']?></td>
					<?php if($i_function % 1 == 0){?></tr><?php } //display end row?>
					<?php if($num==$max_row){  ?>
					<tr>
						<td style="width:45%; padding:0.5% 0 0 3%;font-size:0.9em;"><input type="checkbox" <?php if($rs_roc_group_product['id_group_product']==1){if(in_array('0',$roc_func)){echo 'checked="checked"';}}elseif($rs_roc_group_product['id_group_product']==2){if(in_array('-1',$roc_func)){echo 'checked="checked"';}}elseif($rs_roc_group_product['id_group_product']==3){if(in_array('-2',$roc_func)){echo 'checked="checked"';}}elseif($rs_roc_group_product['id_group_product']==4){if(in_array('-3',$roc_func)){echo 'checked="checked"';}}?>>อื่น ๆ (โปรดระบุ)<?php if($roc_other_func[$j]==''){echo '........................';}else{echo '&nbsp;'.$roc_other_func[$j];}?></td>
					</tr>
					<?php $j++;$max_row_g2=$max_row_g+1;
						} $i_function++; 
					}
					?>
				<?php }//end while?>
				</table>
			</td>
		</tr>	
		<?php }//end while function?>
	</table>
	<table style="width:100%;font-size:0.9em;text-align:left;border-left:1px solid #000;border-right:1px solid #000;" cellpadding="0" cellspacing="0">
		<tr>
			<td style="padding:1.5% 0 0 3%;"><input type="checkbox" disabled name="roc_group_product" id="roc_group_product<?php echo $i+1?>" <?php if($rs['id_group_product']== -1){echo 'checked="checked"';}?> value="-1" onclick="javascript:ShowFunc();">1.<?php echo $max_row_g2.'&nbsp;อื่น ๆ'?>
			<?php
			if($rs['other_group_product']!= ''){
				echo '<span style="color:#0079FC;">';
				echo $rs['other_group_product'];
				echo '</span>';
			}
			?>
			<br><br>
			....................................................................................................................................................................................................
			<br><br>
			....................................................................................................................................................................................................
			<br><br>
			....................................................................................................................................................................................................
			</td>
		</tr>
	</table>
	<table style="width:100%;font-size:0.9em;text-align:left;border-left:1px solid #000;border-right:1px solid #000;" cellpadding="0" cellspacing="0">
		<tr>
			<td style="padding:0.5% 0 0.5% 2%;">2.(โปรดระบุ)สารสำคัญที่ต้องการ/ข้อเสนอแนะอื่น ๆ (ถ้ามี)</td>
		</tr>
		<tr>
			<td style="padding:0.5% 0 0.5% 2%;">
			<?php
			$i_rm=0;
			$rm=0;
			$sql_roc_rm="select * from roc_rm where id_roc='".$id."'";
			$res_roc_rm=mysql_query($sql_roc_rm) or die('Error '.$sql_roc_rm);
			$num_rm=mysql_num_rows($res_roc_rm);
			while($rs_roc_rm=mysql_fetch_array($res_roc_rm)){									
				$i_rm++;				
				echo '<span style="color:#0079FC;">';
				echo $rs_roc_rm['roc_rm'];
				if($i_rm<$num_rm){echo ',';}
				echo '</span>';
			}//end roc rm
			?>
			<?php
			$rm=10-$num_rm;
			$i_rm2=0;
			if($i_rm2<$rm){
				echo '....................................................................................................................................................................................................';
				echo '<br><br>';
				echo '....................................................................................................................................................................................................';
				echo '<br><br>';
				echo '....................................................................................................................................................................................................';
				echo '<br><br>';
			}?>
			</td>
		</tr>		
	</table>
	<?php
	$sql_product_app1="select * from product_appearance";
	$res_product_app1=mysql_query($sql_product_app1) or die ('Error '.$sql_product_app1);
	while($rs_product_app1=mysql_fetch_array($res_product_app1)){
		if($rs_product_app1['id_product_appearance']<=2){
	?>
	<table style="width:100%;font-size:0.9em;text-align:left;<?php if($rs_product_app1['id_product_appearance']==1){?>border-left:1px solid #000;border-right:1px solid #000;<?}elseif($rs_product_app1['id_product_appearance']==2){?>border-left:1px solid #000;border-right:1px solid #000;border-bottom:1px solid #000;padding:0 0 20% 0;<?php }?>" cellpadding="0" cellspacing="0">
		<?php if($rs_product_app1['id_product_appearance']==1){?>
		<tr>
			<td style="padding:1.5% 0 0.5% 2%;">3.ลักษณะรูปแบบผลิตภัณฑ์</td>
		</tr>
		<?php }?>
		<?php
		$roc_product_value=split(",",$rs['id_product_value']);
		$i=0;
		$num=0;
		$sql_product_appearance="select * from product_appearance where id_product_appearance='".$rs_product_app1['id_product_appearance']."'";
		$res_product_appearance=mysql_query($sql_product_appearance) or die ('Error '.$sql_product_appearance);
		$max_row=mysql_num_rows($res_product_appearance);
		while($rs_product_appearance=mysql_fetch_array($res_product_appearance)){
			$i++;
			$num++;
			if($rs['id_product_appearance']==$rs_product_appearance['id_product_appearance']){
				$style_type_product='<span style="color:#0079FC;">';
				$style_end_type_product='</span>';
			}else{
				$style_type_product='';
				$style_end_type_product='';
			}
			if($rs_product_appearance['id_product_appearance'] == 3){echo '<br>';}
		?>	
		<tr>
			<td style="padding:0.5% 0 0 3%;">
				<input type="checkbox" <?php if($rs['id_product_appearance']==$rs_product_appearance['id_product_appearance']){echo 'checked="checked"';}?>>
				<?php echo '&nbsp;'.$style_type_product.$rs_product_appearance['title_thai'].'('.$rs_product_appearance['title'].')'.$style_end_type_product;?>
			<!--<?php echo '3.'.$i.'&nbsp;'.$rs_product_appearance['title_thai'].'('.$rs_product_appearance['title'].')'?>--></td>
		</tr>
		<tr>
			<td>
				<table style="width:100%;" cellpadding="0" cellspacing="0">
					<tr>
						<td colspan="3" style="padding:1.5% 0 0 6%;">
							<?php if($rs_product_appearance['id_product_appearance']==1){$i_value=0;?>ลักษณะของเม็ด<?php }?>
							<?php if(($rs_product_appearance['id_product_appearance']==2) || ($rs_product_appearance['id_product_appearance']==3)){?>ชนิดของเปลือกแคปซูล<?php }?>
						</td>
					</tr>
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
						if(($rs_product_appearance['id_product_appearance']==2) || ($rs_product_appearance['id_product_appearance']==3)){
							if($j_value % 1 == 0){?><tr><?php } //display row
						}else{
							if($j_value % 2 == 0){?><tr><?php } //display row
						}
						if(in_array($rs_roc_product_v['id_product_value'],$roc_product_value)){
							$style_product_v='<span style="color:#0079FC;">';
							$style_end_product_v='</span>';
						}else{
							$style_product_v='';
							$style_end_product_v='';						
						}
					?>
						<td <?php if($rs_product_appearance['id_product_appearance']==1){if(($j_value==1) ||($j_value==3)){echo 'colspan="2"';}}elseif(($rs_product_appearance['id_product_appearance']==2) || ($rs_product_appearance['id_product_appearance']==3)){echo 'colspan="3"';}elseif($rs_product_appearance['id_product_appearance']==7){echo 'colspan="2"';}?> style="padding:1.0% 0 0 6.5%;">
							<?php if($rs_product_appearance['id_product_appearance']==4){?>
							<input type="checkbox" <?php if(in_array($rs_roc_product_v['id_product_value'],$roc_product_value)){echo 'checked="checked"';}?>><?php echo $style_product_v.$rs_roc_product_v['title'].$style_end_product_v?>
							<?php }else{?>
							<input type="checkbox" <?php if(in_array($rs_roc_product_v['id_product_value'],$roc_product_value)){echo 'checked="checked"';}?>><?php echo $style_product_v.$rs_roc_product_v['title'].$style_end_product_v?>
							<?php }?>
							<?php 
							if(($rs_roc_product_v['title']== 'สี (Color)') ||($rs_roc_product_v['title']=='กลิ่น (Odor)') ||  ($rs_roc_product_v['title']=='รส (Taste)') || ($rs_roc_product_v['title']=='รูปร่าง (Shape)') || ($rs_roc_product_v['title']=='น้ำหนัก')){
							?>
								(โปรดระบุ)
							<?php 
								if(in_array($rs_roc_product_v['id_product_value'],$roc_product_value)){
									if($roc_value_title[$j_value] !=''){
										echo $style_product_v.$roc_value_title[$j_value].$style_end_product_v;
									}else{echo '...............';}
								}else{echo '...............';}
							}?>
						</td>
						<?php if($j_value % 2 == 0){?></tr><?php } //display end row?>
						<?php if($num_v==$max_row_v){
						if($rs_product_appearance['id_product_appearance']==4){
							if(in_array('0',$roc_product_value)){
								$style_product_v2='<span style="color:#0079FC;">';
								$style_end_product_v2='</span>';
							}else{
								$style_product_v2='';
								$style_end_product_v2='';						
							}
						?>
						<td style="padding:1.0% 0 0 5.5%;">
							<input type="checkbox" <?php if(in_array('0',$roc_product_value)){echo 'checked="checked"';}?>><?php echo $style_product_v2;
							echo 'ผงชงดื่มประเภทอื่น';
							echo $style_end_product_v2;
							echo '&nbsp;';
							echo '(โปรดระบุ)'?> 
							<?php if($rs['product_value_title']==''){echo '..............';}else{echo '<span style="color:#0079FC;">'.$rs['product_value_title'].'</span>';}?>
						</td>
						<?php }
						}?>
					<?php $rows_v++;
					$j_value++;
					}//end while product value?>
					<?php if(($rs_product_appearance['id_product_appearance']== 2) || ($rs_product_appearance['id_product_appearance']== 3)){?>
				</table>
				<table style="width:100%;" cellpadding="0" cellspacing="0">
					<tr>
						<td colspan="3" style="padding:1.5% 0 0 6%;">ลักษณะผลิตภัณฑ์ที่บรรจุ</td>
					</tr>
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
						if($j_p % 1 == 0){?><tr><?php } //display row	
						
						if($rs['id_type_product_pack']==$rs_roc_product_p['id_type_product_pack']){
							$style_product_v='<span style="color:#0079FC;">';
							$style_end_product_v='</span>';
						}else{
							$style_product_v='';
							$style_end_product_v='';						
						}						
					?>
						<td colspan="3" style="padding:1.0% 0 0 6.5%;">
							<input type="checkbox" value="<?php echo $rs_roc_product_p['id_type_product_pack']?>" <?php if($rs['id_type_product_pack']==$rs_roc_product_p['id_type_product_pack']){echo 'checked="checked"';}?>><?echo $style_product_v.$rs_roc_product_p['title_product_pack'].$style_end_product_v?>
							<?php 
							if(($rs_product_appearance['id_product_appearance']==6) || ($rs_product_appearance['id_product_appearance']==7)){
								if(($rs_roc_product_p['title']== 'สี (Color)') ||($rs_roc_product_p['title']=='กลิ่น (Odor)') || ($rs_roc_product_p['title']=='รูปร่าง (Shape)') || ($rs_roc_product_p['title']=='สี') || ($rs_roc_product_p['title']=='กลิ่น')){
									echo '(โปรดระบุ) ';
								
								}//end color for soft gelation capsule
							}//end functional drink and gummy
							?>
						</td>
						<?php if($j_p % 1 == 0){?></tr><?php } //display end row?>
						<?php if($num_p==$max_row_p){
						if($rs_product_appearance['id_type_product']== 3){?>
							<input type="checkbox" name="type_product_pack" value="0" <?php if($rs['id_type_product_pack']==0){echo 'checked="checked"';}?>>อื่น ๆ (โปรดระบุ)
						<?php }
						}?>
					<?php }//end while product pack?>
					<?php }//end if capsule and soft gel?>
					<?php if($rs_product_appearance['id_product_appearance'] != 7){//not equal gummy?>
					<tr>
						<td colspan="3" style="padding:1.5% 0 0 6%;">น้ำหนักผลิตภัณฑ์ต่อหน่วย</td>
					</tr>
					<?php
					$num_w=0;
					$rows = 0;
					$j_w=0;
					$sql_roc_product_w="select * from roc_product_weight where id_product_appearance='".$rs_product_appearance['id_product_appearance']."'";
					$res_roc_product_w=mysql_query($sql_roc_product_w) or die ('Error '.$sql_roc_product_w);
					$max_row_w=mysql_num_rows($res_roc_product_w);
					while($rs_roc_product_w=mysql_fetch_array($res_roc_product_w)){						
						if($rs['id_product_weight']==$rs_roc_product_w['id_product_weight']){
							$style_product_weight='<span style="color:#0079FC;">';
							$style_end_product_weight='</span>';
						}else{
							$style_product_weight='';
							$style_end_product_weight='';						
						}	
						if($j_w % 3 == 0){?><tr><?php } //display row
					?>
						<td <?php if(($j_w==0) || ($j_w==3)){?>style="padding:1.0% 0 0 6.5%;width:33.33%;"<?php }else{?>style="padding:1.0% 0 0 6.5%;width:33.33%;"<?php }?>>
							<input type="checkbox" <?php if($rs['id_product_weight']==$rs_roc_product_w['id_product_weight']){echo 'checked="checked"';}?>><?php echo $style_product_weight.$rs_roc_product_w['title'].$style_end_product_weight?>
						</td>
					<?php if($j_w % 3 == 0){?></tr><?php } //display end row?>					
					<?php $j_w++;
						$num_w++;
					}//end while product weight?>
					<?php if($num_w==$max_row_w){
						if(($rs_product_appearance['id_product_appearance']==2) || ($rs_product_appearance['id_product_appearance']==4) || ($rs_product_appearance['id_product_appearance']==5)){echo '<tr>';}?>
						<td style="padding:1.0% 0 0 6.5%;">
							<input type="checkbox" <?php if($rs['id_product_appearance']==$rs_product_appearance['id_product_appearance']){if($rs['id_product_weight']== -1){echo 'checked="checked"';}}?>>อื่น ๆ (โปรดระบุ)
							<?php
							if(($rs['id_product_appearance']==$rs_product_appearance['id_product_appearance'])){
								if($rs['other_product_weight']==''){echo '............';}
								else{
									if($rs['id_product_appearance']==$rs_product_appearance['id_product_appearance']){
										echo '<span style="color:#0079FC;">';
										echo $rs['other_product_weight'];
										echo '</span>';
									}
								}
							}else{echo '............';}
							?>
						</td>
						<?php if(($rs_product_appearance['id_product_appearance']==2) || ($rs_product_appearance['id_product_appearance']==4)|| ($rs_product_appearance['id_product_appearance']==5)){echo '</tr>';}?>
					<?php }?>
					<?php }//end if not equal gummy?>
					<tr>
						<td colspan="3" style="padding:1.0% 0 0 6%;" >
						<?php
						if(($rs_product_appearance['id_product_appearance']==1) || ($rs_product_appearance['id_product_appearance']==2) || ($rs_product_appearance['id_product_appearance']==3) || ($rs_product_appearance['id_product_appearance']==4)){?>
							<?php if($rs_product_appearance['id_product_appearance']==1){?>สี กลิ่น และรูปร่างของเม็ด
							<?php }elseif($rs_product_appearance['id_product_appearance']==2){?>สีของแคปซูล
							<?php }elseif($rs_product_appearance['id_product_appearance']==3){?>สีและกลิ่นเปลือกแคปซูล
							<?php }elseif($rs_product_appearance['id_product_appearance']==4){?>สีและกลิ่นของผงชงดื่ม<?php }?>
						<?php }//end if?>
						</td>
					</tr>
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
						if(in_array($rs_roc_product_c['id_type_product_color'],$roc_color)){
							$style_product_c='<span style="color:#0079FC;">';
							$style_end_product_c='</span>';
						}else{
							$style_product_c='';
							$style_end_product_c='';						
						}
						if($j_c % 2== 0){?><tr><?php } //display row
					?>						
						<td <?php if($j_c == 1){?>colspan="2"<?php }else{?>style="padding:1.0% 0 0 6.5%;"<?php }?>>
							<input type="checkbox" <?php if(in_array($rs_roc_product_c['id_type_product_color'],$roc_color)){echo 'checked="checked"';}?>><?php echo $style_product_c.$rs_roc_product_c['type_product_color'].$style_end_product_c?>
							<?php 
							if(($rs_product_appearance['id_product_appearance']==1) || ($rs_product_appearance['id_product_appearance']==3)){
								if(($rs_roc_product_c['type_product_color']== 'สี (Color)') ||($rs_roc_product_c['type_product_color']=='กลิ่น (Odor)') || ($rs_roc_product_c['type_product_color']=='รูปร่าง (Shape)') || ($rs_roc_product_c['type_product_color']=='สี') || ($rs_roc_product_c['type_product_color']=='กลิ่น')){
									
									$sql_rela_color="select * from roc_relation_color where id_roc='".$id."'";
									$sql_rela_color .=" and id_type_product_color='".$rs_roc_product_c['id_type_product_color']."'";
									$res_rela_color=mysql_query($sql_rela_color) or die ('Error '.$sql_rela_color);
									$rs_rela_color=mysql_fetch_array($res_rela_color);
									
									if(in_array($rs_roc_product_c['id_type_product_color'],$roc_color)){
										$style_product_c1='<span style="color:#0079FC;">';
										$style_end_product_c1='</span>';
									}else{
										$style_product_c1='';
										$style_end_product_c1='';						
									}

									echo '(โปรดระบุ) '; 
									if(in_array($rs_roc_product_c['id_type_product_color'],$roc_color)){echo $style_product_c1.$roc_color_title[$i_title].$style_end_product_c1;}
									else{echo '............';}
								}
							}//end if tablet and soft gel
							if($rs_product_appearance['id_product_appearance']==4){
								if(($rs_roc_product_c['type_product_color']== 'กลิ่น (Odor)') ||($rs_roc_product_c['type_product_color']=='รส (Taste)') || ($rs_roc_product_c['type_product_color']== 'สี (Color)')) {
		
									if(in_array($rs_roc_product_c['id_type_product_color'],$roc_color)){
										$style_product_c1='<span style="color:#0079FC;">';
										$style_end_product_c1='</span>';
									}else{
										$style_product_c1='';
										$style_end_product_c1='';						
									}

									echo '(โปรดระบุ) '; 
									if(in_array($rs_roc_product_c['id_type_product_color'],$roc_color)){echo $style_product_c1.$roc_color_title[$i_title].$style_end_product_c1;}
									else{echo '......................';}
								}
							}//end if instant drink
							?>
						</td>
					<?php if($j_c % 2 == 0){?></tr><?php } //display row
						if($num_c==$max_row_c){$i_title++;
							if($rs_type_product['id_type_product']!=3){
								if($rs_product_appearance['id_product_appearance']==2){$i_title=0;}
								if($rs_product_appearance['id_product_appearance']==3){$i_title=2;}
						?>
								<input type="checkbox" <?php if($rs['id_product_appearance']==$rs_product_appearance['id_product_appearance']){if(in_array('0',$roc_color)){echo 'checked="checked"';}}?> value="0" >อื่น ๆ (โปรดระบุ) <?php if($rs['id_product_appearance']==$rs_product_appearance['id_product_appearance']){if(in_array('0',$roc_color)){echo $roc_color_title[$i_title];}}else{echo '..................';}?>
						<?php  } // not equal soft gelatin capsule
						}
						$j_c++;
						$num_c++;	
					}//end while color	
					?>
					<?php if($rs_product_appearance['id_product_appearance'] == 7){?>
					<tr>
						<td colspan="3" style="padding:1.0% 0 0 5.0%;">ลักษณะการเคลือบ</td>
					</tr>
						<?php 
						$num_p2=0;
						$rows_p2=0;
						$j_p2=0;
						$sql_roc_product_p="select * from type_product_pack where id_product_appearance='".$rs_product_appearance['id_product_appearance']."'";
						$res_roc_product_p=mysql_query($sql_roc_product_p) or die ('Error '.$sql_roc_product_p);
						$max_row_p2=mysql_num_rows($res_roc_product_p);
						while($rs_roc_product_p=mysql_fetch_array($res_roc_product_p)){
							if($rs_roc_product_p['id_type_product_pack']==$rs['id_type_product_pack']){
								$style_product_p='<span style="color:#0079FC;">';
								$style_end_product_p='</span>';
							}else{
								$style_product_p='';
								$style_end_product_p='';						
							}
							if($j_p2 % 3== 0){?><tr><?php } //display row
						?>
							<td style="padding:1.5% 0 0 6.5%;">
								<input type="checkbox" <?php if($rs_roc_product_p['id_type_product_pack']==$rs['id_type_product_pack']){echo 'checked="checked"';}?>><?php echo $style_product_p.$rs_roc_product_p['title_product_pack'].$style_end_product_p?>
							</td>
						<?php if($j_p2 % 3== 0){?></tr><?php } //display row ?>						
						<?php
							$j_p2++;
							$num_p2++;
						}//end while product pack?>
						<?php if($num_p2==$max_row_p2){?>
							<td style="padding:1.5% 0 0 4.5%;">
								<input type="checkbox" class="checkbox" name="type_product_pack" value="0">อื่น ๆ (โปรดระบุ) <?php if($rs['id_product_appearance']==7){if(in_array('-1',$roc_color)){if($roc_color_title[$i_title] != ''){echo '<span style="color:#0079FC;">';echo $roc_color_title[$i_title];echo '</span>';}}else{echo '.....................';}}else{echo '.....................';}?>
							</td>
						<?php }?>
					<?php }//end gummy?>
				</table>
			</td>
		</tr>
		<?php }//end while?>
	</table>
	<?php }//end if product tablet and capsule
	elseif(($rs_product_app1['id_product_appearance'] >=3) && ($rs_product_app1['id_product_appearance'] <=6)){?>
		<table style="width:100%;font-size:0.9em;text-align:left;<?php if($rs_product_app1['id_product_appearance']==3){?>border-left:1px solid #000;border-right:1px solid #000;padding:5% 0 0 0;<?php }elseif($rs_product_app1['id_product_appearance']==6){?>border-left:1px solid #000;border-right:1px solid #000;border-bottom:1px solid #000;padding:0 0 7% 0;<?php }elseif($rs_product_app1['id_product_appearance']!=7){?>border-left:1px solid #000;border-right:1px solid #000;<?php }?>" cellpadding="0" cellspacing="0">
		<?php
		$roc_product_value=split(",",$rs['id_product_value']);
		$i=0;
		$num=0;
		$sql_product_appearance="select * from product_appearance where id_product_appearance='".$rs_product_app1['id_product_appearance']."'";
		$res_product_appearance=mysql_query($sql_product_appearance) or die ('Error '.$sql_product_appearance);
		$max_row=mysql_num_rows($res_product_appearance);
		while($rs_product_appearance=mysql_fetch_array($res_product_appearance)){
			$i++;
			$num++;
			if($rs['id_product_appearance']==$rs_product_appearance['id_product_appearance']){
				$style_type_product='<span style="color:#0079FC;">';
				$style_end_type_product='</span>';
			}else{
				$style_type_product='';
				$style_end_type_product='';
			}
			if($rs_product_appearance['id_product_appearance'] == 3){echo '<br>';}
		?>	
		<tr>
			<td style="padding:0.5% 0 0 3%;">
				<input type="checkbox" <?php if($rs['id_product_appearance']==$rs_product_appearance['id_product_appearance']){echo 'checked="checked"';}?>>
				<?php echo '&nbsp;'.$style_type_product.$rs_product_appearance['title_thai'].'('.$rs_product_appearance['title'].')'.$style_end_type_product;?>
			<!--<?php echo '3.'.$i.'&nbsp;'.$rs_product_appearance['title_thai'].'('.$rs_product_appearance['title'].')'?>--></td>
		</tr>
		<tr>
			<td>
				<table style="width:100%;" cellpadding="0" cellspacing="0">
					<tr>
						<td colspan="3" style="padding:1.5% 0 0 6%;">
							<?php if($rs_product_appearance['id_product_appearance']==1){$i_value=0;?>ลักษณะของเม็ด<?php }?>
							<?php if(($rs_product_appearance['id_product_appearance']==2) || ($rs_product_appearance['id_product_appearance']==3)){?>ชนิดของเปลือกแคปซูล<?php }?>
						</td>
					</tr>
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
						if(($rs_product_appearance['id_product_appearance']==2) || ($rs_product_appearance['id_product_appearance']==3)){
							if($j_value % 1 == 0){?><tr><?php } //display row
						}else{
							if($j_value % 2 == 0){?><tr><?php } //display row
						}
						if(in_array($rs_roc_product_v['id_product_value'],$roc_product_value)){
							$style_product_v='<span style="color:#0079FC;">';
							$style_end_product_v='</span>';
						}else{
							$style_product_v='';
							$style_end_product_v='';						
						}
					?>
						<td <?php if(($rs_product_appearance['id_product_appearance']==2) || ($rs_product_appearance['id_product_appearance']==3)){echo 'colspan="3"';}elseif($rs_product_appearance['id_product_appearance']==7){echo 'colspan="2"';}?> style="padding:1.0% 0 0 6.5%;">
							<?php if($rs_product_appearance['id_product_appearance']==4){?>
							<input type="checkbox" <?php if(in_array($rs_roc_product_v['id_product_value'],$roc_product_value)){echo 'checked="checked"';}?>><?php echo $style_product_v.$rs_roc_product_v['title'].$style_end_product_v?>
							<?php }else{?>
							<input type="checkbox" <?php if(in_array($rs_roc_product_v['id_product_value'],$roc_product_value)){echo 'checked="checked"';}?>><?php echo $style_product_v.$rs_roc_product_v['title'].$style_end_product_v?>
							<?php }?>
							<?php 
							if(($rs_roc_product_v['title']== 'สี (Color)') ||($rs_roc_product_v['title']=='กลิ่น (Odor)') ||  ($rs_roc_product_v['title']=='รส (Taste)') || ($rs_roc_product_v['title']=='รูปร่าง (Shape)') || ($rs_roc_product_v['title']=='น้ำหนัก')){
							?>
								(โปรดระบุ)
							<?php 
								if(in_array($rs_roc_product_v['id_product_value'],$roc_product_value)){
									if($roc_value_title[$j_value] !=''){
										echo $style_product_v.$roc_value_title[$j_value].$style_end_product_v;
									}else{echo '...............';}
								}else{echo '...............';}
							}?>
						</td>
						<?php if($j_value % 2 == 0){?></tr><?php } //display end row?>
						<?php if($num_v==$max_row_v){
						if($rs_product_appearance['id_product_appearance']==4){
							if(in_array('0',$roc_product_value)){
								$style_product_v2='<span style="color:#0079FC;">';
								$style_end_product_v2='</span>';
							}else{
								$style_product_v2='';
								$style_end_product_v2='';						
							}
						?>
						<td style="padding:1.0% 0 0 5.5%;">
							<input type="checkbox" <?php if(in_array('0',$roc_product_value)){echo 'checked="checked"';}?>><?php echo $style_product_v2;
							echo 'ผงชงดื่มประเภทอื่น';
							echo $style_end_product_v2;
							echo '&nbsp;';
							echo '(โปรดระบุ)'?> 
							<?php if($rs['product_value_title']==''){echo '..............';}else{echo '<span style="color:#0079FC;">'.$rs['product_value_title'].'</span>';}?>
						</td>
						<?php }
						}?>
					<?php $rows_v++;
					$j_value++;
					}//end while product value?>
					<?php if(($rs_product_appearance['id_product_appearance']== 2) || ($rs_product_appearance['id_product_appearance']== 3)){?>
				</table>
				<table style="width:100%;" cellpadding="0" cellspacing="0">
					<tr>
						<td colspan="3" style="padding:1.5% 0 0 6%;">ลักษณะผลิตภัณฑ์ที่บรรจุ</td>
					</tr>
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
						if($j_p % 1 == 0){?><tr><?php } //display row	
						
						if($rs['id_type_product_pack']==$rs_roc_product_p['id_type_product_pack']){
							$style_product_v='<span style="color:#0079FC;">';
							$style_end_product_v='</span>';
						}else{
							$style_product_v='';
							$style_end_product_v='';						
						}						
					?>
						<td colspan="3" style="padding:1.0% 0 0 6.5%;">
							<input type="checkbox" value="<?php echo $rs_roc_product_p['id_type_product_pack']?>" <?php if($rs['id_type_product_pack']==$rs_roc_product_p['id_type_product_pack']){echo 'checked="checked"';}?>><?echo $style_product_v.$rs_roc_product_p['title_product_pack'].$style_end_product_v?>
							<?php 
							if(($rs_product_appearance['id_product_appearance']==6) || ($rs_product_appearance['id_product_appearance']==7)){
								if(($rs_roc_product_p['title']== 'สี (Color)') ||($rs_roc_product_p['title']=='กลิ่น (Odor)') || ($rs_roc_product_p['title']=='รูปร่าง (Shape)') || ($rs_roc_product_p['title']=='สี') || ($rs_roc_product_p['title']=='กลิ่น')){
									echo '(โปรดระบุ) ';
								
								}//end color for soft gelation capsule
							}//end functional drink and gummy
							?>
						</td>
						<?php if($j_p % 1 == 0){?></tr><?php } //display end row?>
						<?php if($num_p==$max_row_p){
						if($rs_product_appearance['id_type_product']== 3){?>
							<input type="checkbox" name="type_product_pack" value="0" <?php if($rs['id_type_product_pack']==0){echo 'checked="checked"';}?>>อื่น ๆ (โปรดระบุ)
						<?php }
						}?>
					<?php }//end while product pack?>
					<?php }//end if capsule and soft gel?>
					<?php if($rs_product_appearance['id_product_appearance'] != 7){//not equal gummy?>
					<tr>
						<td colspan="3" style="padding:1.5% 0 0 6%;">น้ำหนักผลิตภัณฑ์ต่อหน่วย</td>
					</tr>
					<?php
					$num_w=0;
					$rows = 0;
					$j_w=0;
					$sql_roc_product_w="select * from roc_product_weight where id_product_appearance='".$rs_product_appearance['id_product_appearance']."'";
					$res_roc_product_w=mysql_query($sql_roc_product_w) or die ('Error '.$sql_roc_product_w);
					$max_row_w=mysql_num_rows($res_roc_product_w);
					while($rs_roc_product_w=mysql_fetch_array($res_roc_product_w)){						
						if($rs['id_product_weight']==$rs_roc_product_w['id_product_weight']){
							$style_product_weight='<span style="color:#0079FC;">';
							$style_end_product_weight='</span>';
						}else{
							$style_product_weight='';
							$style_end_product_weight='';						
						}	
						if($j_w % 3 == 0){?><tr><?php } //display row
					?>
						<td style="<?php if($rs_product_appearance['id_product_appearance']!=4){?>width:35%;padding:1.0% 0 0 6.5%;<?php }else{?>width:15%;padding:1.0% 0 0 6.5%;<?php }?>">
							<input type="checkbox" <?php if($rs['id_product_weight']==$rs_roc_product_w['id_product_weight']){echo 'checked="checked"';}?>><?php echo $style_product_weight.$rs_roc_product_w['title'].$style_end_product_weight?>
						</td>
					<?php if($j_w % 3 == 0){?></tr><?php } //display end row?>					
					<?php $j_w++;
						$num_w++;
					}//end while product weight?>
					<?php if($num_w==$max_row_w){
						if(($rs_product_appearance['id_product_appearance']==2) || ($rs_product_appearance['id_product_appearance']==4) || ($rs_product_appearance['id_product_appearance']==5)){echo '<tr>';}?>
						<td style="padding:1.0% 0 0 6.5%;">
							<input type="checkbox" <?php if($rs['id_product_appearance']==$rs_product_appearance['id_product_appearance']){if($rs['id_product_weight']== -1){echo 'checked="checked"';}}?>>อื่น ๆ (โปรดระบุ)
							<?php
							if(($rs['id_product_appearance']==$rs_product_appearance['id_product_appearance'])){
								if($rs['other_product_weight']==''){echo '............';}
								else{
									if($rs['id_product_appearance']==$rs_product_appearance['id_product_appearance']){
										echo '<span style="color:#0079FC;">';
										echo $rs['other_product_weight'];
										echo '</span>';
									}
								}
							}else{echo '............';}
							?>
						</td>
						<?php if(($rs_product_appearance['id_product_appearance']==2) || ($rs_product_appearance['id_product_appearance']==4)|| ($rs_product_appearance['id_product_appearance']==5)){echo '</tr>';}?>
					<?php }?>
					<?php }//end if not equal gummy?>
					<tr>
						<td colspan="3" style="padding:1.0% 0 0 6%;" >
						<?php
						if(($rs_product_appearance['id_product_appearance']==1) || ($rs_product_appearance['id_product_appearance']==2) || ($rs_product_appearance['id_product_appearance']==3) || ($rs_product_appearance['id_product_appearance']==4)){?>
							<?php if($rs_product_appearance['id_product_appearance']==1){?>สี กลิ่น และรูปร่างของเม็ด
							<?php }elseif($rs_product_appearance['id_product_appearance']==2){?>สีของแคปซูล
							<?php }elseif($rs_product_appearance['id_product_appearance']==3){?>สีและกลิ่นเปลือกแคปซูล
							<?php }elseif($rs_product_appearance['id_product_appearance']==4){?>สีและกลิ่นของผงชงดื่ม<?php }?>
						<?php }//end if?>
						</td>
					</tr>
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
						if(in_array($rs_roc_product_c['id_type_product_color'],$roc_color)){
							$style_product_c='<span style="color:#0079FC;">';
							$style_end_product_c='</span>';
						}else{
							$style_product_c='';
							$style_end_product_c='';						
						}
						if($j_c % 2== 0){?><tr><?php } //display row
					?>						
						<td style="padding:1.0% 0 0 6.5%;">
							<input type="checkbox" <?php if(in_array($rs_roc_product_c['id_type_product_color'],$roc_color)){echo 'checked="checked"';}?>><?php echo $style_product_c.$rs_roc_product_c['type_product_color'].$style_end_product_c?>
							<?php 
							if(($rs_product_appearance['id_product_appearance']==1) || ($rs_product_appearance['id_product_appearance']==3)){
								if(($rs_roc_product_c['type_product_color']== 'สี (Color)') ||($rs_roc_product_c['type_product_color']=='กลิ่น (Odor)') || ($rs_roc_product_c['type_product_color']=='รูปร่าง (Shape)') || ($rs_roc_product_c['type_product_color']=='สี') || ($rs_roc_product_c['type_product_color']=='กลิ่น')){
									
									$sql_rela_color="select * from roc_relation_color where id_roc='".$id."'";
									$sql_rela_color .=" and id_type_product_color='".$rs_roc_product_c['id_type_product_color']."'";
									$res_rela_color=mysql_query($sql_rela_color) or die ('Error '.$sql_rela_color);
									$rs_rela_color=mysql_fetch_array($res_rela_color);
									
									if(in_array($rs_roc_product_c['id_type_product_color'],$roc_color)){
										$style_product_c1='<span style="color:#0079FC;">';
										$style_end_product_c1='</span>';
									}else{
										$style_product_c1='';
										$style_end_product_c1='';						
									}

									echo '(โปรดระบุ) '; 
									if(in_array($rs_roc_product_c['id_type_product_color'],$roc_color)){echo $style_product_c1.$roc_color_title[$i_title].$style_end_product_c1;}
									else{echo '....................';}
								}
							}//end if tablet and soft gel
							if($rs_product_appearance['id_product_appearance']==4){
								if(($rs_roc_product_c['type_product_color']== 'กลิ่น (Odor)') ||($rs_roc_product_c['type_product_color']=='รส (Taste)') || ($rs_roc_product_c['type_product_color']== 'สี (Color)')) {
		
									if(in_array($rs_roc_product_c['id_type_product_color'],$roc_color)){
										$style_product_c1='<span style="color:#0079FC;">';
										$style_end_product_c1='</span>';
									}else{
										$style_product_c1='';
										$style_end_product_c1='';						
									}

									echo '(โปรดระบุ) '; 
									if(in_array($rs_roc_product_c['id_type_product_color'],$roc_color)){echo $style_product_c1.$roc_color_title[$i_title].$style_end_product_c1;}
									else{echo '......................';}
								}
							}//end if instant drink
							?>
						</td>
					<?php if($j_c % 2 == 0){?></tr><?php } //display row
						if($num_c==$max_row_c){$i_title++;
							if($rs_type_product['id_type_product']!=3){
								if($rs_product_appearance['id_product_appearance']==2){$i_title=0;}
								if($rs_product_appearance['id_product_appearance']==3){$i_title=2;}
						?>
								<input type="checkbox" <?php if($rs['id_product_appearance']==$rs_product_appearance['id_product_appearance']){if(in_array('0',$roc_color)){echo 'checked="checked"';}}?> value="0" >อื่น ๆ (โปรดระบุ) <?php if($rs['id_product_appearance']==$rs_product_appearance['id_product_appearance']){if(in_array('0',$roc_color)){echo $roc_color_title[$i_title];}}else{echo '..................';}?>
						<?php  } // not equal soft gelatin capsule
						}
						$j_c++;
						$num_c++;	
					}//end while color	
					?>
					<?php if($rs_product_appearance['id_product_appearance'] == 7){?>
					<tr>
						<td colspan="3" style="padding:1.0% 0 0 5.0%;">ลักษณะการเคลือบ</td>
					</tr>
						<?php 
						$num_p2=0;
						$rows_p2=0;
						$j_p2=0;
						$sql_roc_product_p="select * from type_product_pack where id_product_appearance='".$rs_product_appearance['id_product_appearance']."'";
						$res_roc_product_p=mysql_query($sql_roc_product_p) or die ('Error '.$sql_roc_product_p);
						$max_row_p2=mysql_num_rows($res_roc_product_p);
						while($rs_roc_product_p=mysql_fetch_array($res_roc_product_p)){
							if($rs_roc_product_p['id_type_product_pack']==$rs['id_type_product_pack']){
								$style_product_p='<span style="color:#0079FC;">';
								$style_end_product_p='</span>';
							}else{
								$style_product_p='';
								$style_end_product_p='';						
							}
							if($j_p2 % 3== 0){?><tr><?php } //display row
						?>
							<td style="padding:1.5% 0 0 6.5%;">
								<input type="checkbox" <?php if($rs_roc_product_p['id_type_product_pack']==$rs['id_type_product_pack']){echo 'checked="checked"';}?>><?php echo $style_product_p.$rs_roc_product_p['title_product_pack'].$style_end_product_p?>
							</td>
						<?php if($j_p2 % 3== 0){?></tr><?php } //display row ?>						
						<?php
							$j_p2++;
							$num_p2++;
						}//end while product pack?>
						<?php if($num_p2==$max_row_p2){?>
							<td style="padding:1.5% 0 0 4.5%;">
								<input type="checkbox" class="checkbox" name="type_product_pack" value="0">อื่น ๆ (โปรดระบุ) <?php if($rs['id_product_appearance']==7){if(in_array('-1',$roc_color)){if($roc_color_title[$i_title] != ''){echo '<span style="color:#0079FC;">';echo $roc_color_title[$i_title];echo '</span>';}}else{echo '.....................';}}else{echo '.....................';}?>
							</td>
						<?php }?>
					<?php }//end gummy?>
					<?php if(($rs_product_app1['id_product_appearance'] != 3) && ($rs_product_app1['id_product_appearance'] != 4) && ($rs_product_app1['id_product_appearance'] != 5) && ($rs_product_app1['id_product_appearance'] != 6) ){?>
					<tr>
						<td style="padding:1.5% 0 0 3.5%;" colspan="7"><input type="checkbox" <?php if($rs['id_product_appearance']== -1){echo 'checked="checked"';}?>><?php echo '&nbsp;อื่น ๆ';?>
						<?php 
						if($rs['other_product_app']== ''){
							echo '(โปรดระบุ)' ;
							echo '<br><br>';
							echo '...............................................................................................................................................................................................';
							echo '<br><br>';
							echo '...............................................................................................................................................................................................';
							echo '<br><br>';
							echo '...............................................................................................................................................................................................';
						}else{
							echo '<span style="color:#0079FC;">';
							echo $rs['other_product_app'];
							echo '</span>';
							echo '...............................................................................................................................................................................................';
							echo '<br><br>';
							echo '...............................................................................................................................................................................................';
						}
						?>
						</td>
					<tr>
					<?php }?>
				</table>
			</td>
		</tr>
		<?php }//end while?>
	</table>
	<?php }elseif($rs_product_app1['id_product_appearance']==7){//end if product == gummy?>
	<table style="width:100%;font-size:0.9em;text-align:left;border-left:1px solid #000;border-right:1px solid #000;padding:3.5% 0 0 0;" cellpadding="0" cellspacing="0">
		<?php
		$roc_product_value=split(",",$rs['id_product_value']);
		$i=0;
		$num=0;
		$sql_product_appearance="select * from product_appearance where id_product_appearance='7'";
		$res_product_appearance=mysql_query($sql_product_appearance) or die ('Error '.$sql_product_appearance);
		$max_row=mysql_num_rows($res_product_appearance);
		while($rs_product_appearance=mysql_fetch_array($res_product_appearance)){
			$i++;
			$num++;
			if($rs['id_product_appearance']==$rs_product_appearance['id_product_appearance']){
				$style_type_product='<span style="color:#0079FC;">';
				$style_end_type_product='</span>';
			}else{
				$style_type_product='';
				$style_end_type_product='';
			}
			if($rs_product_appearance['id_product_appearance'] == 3){echo '<br>';}
		?>	
		<tr>
			<td style="padding:0.5% 0 0 3%;">
				<input type="checkbox" <?php if($rs['id_product_appearance']==$rs_product_appearance['id_product_appearance']){echo 'checked="checked"';}?>>
				<?php echo '&nbsp;'.$style_type_product.$rs_product_appearance['title_thai'].'('.$rs_product_appearance['title'].')'.$style_end_type_product;?>
			<!--<?php echo '3.'.$i.'&nbsp;'.$rs_product_appearance['title_thai'].'('.$rs_product_appearance['title'].')'?>--></td>
		</tr>
		<tr>
			<td>
				<table style="width:100%;" cellpadding="0" cellspacing="0">
					<tr>
						<td colspan="3" style="padding:1.5% 0 0 5%;">
							<?php if($rs_product_appearance['id_product_appearance']==1){$i_value=0;?>ลักษณะของเม็ด<?php }?>
							<?php if(($rs_product_appearance['id_product_appearance']==2) || ($rs_product_appearance['id_product_appearance']==3)){?>ชนิดของเปลือกแคปซูล<?php }?>
						</td>
					</tr>
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
						if(($rs_product_appearance['id_product_appearance']==2) || ($rs_product_appearance['id_product_appearance']==3)){
							if($j_value % 1 == 0){?><tr><?php } //display row
						}else{
							if($j_value % 2 == 0){?><tr><?php } //display row
						}
						if(in_array($rs_roc_product_v['id_product_value'],$roc_product_value)){
							$style_product_v='<span style="color:#0079FC;">';
							$style_end_product_v='</span>';
						}else{
							$style_product_v='';
							$style_end_product_v='';						
						}
					?>
						<td <?php if(($rs_product_appearance['id_product_appearance']==2) || ($rs_product_appearance['id_product_appearance']==3)){echo 'colspan="3"';}elseif($rs_product_appearance['id_product_appearance']==7){echo 'colspan="2"';}?> style="padding:1.0% 0 0 6.5%;">
							<?php if($rs_product_appearance['id_product_appearance']==4){?>
							<input type="checkbox" <?php if(in_array($rs_roc_product_v['id_product_value'],$roc_product_value)){echo 'checked="checked"';}?>><?php echo $style_product_v.$rs_roc_product_v['title'].$style_end_product_v?>
							<?php }else{?>
							<input type="checkbox" <?php if(in_array($rs_roc_product_v['id_product_value'],$roc_product_value)){echo 'checked="checked"';}?>><?php echo $style_product_v.$rs_roc_product_v['title'].$style_end_product_v?>
							<?php }?>
							<?php 
							if(($rs_roc_product_v['title']== 'สี (Color)') ||($rs_roc_product_v['title']=='กลิ่น (Odor)') ||  ($rs_roc_product_v['title']=='รส (Taste)') || ($rs_roc_product_v['title']=='รูปร่าง (Shape)') || ($rs_roc_product_v['title']=='น้ำหนัก')){
							?>
								(โปรดระบุ)
							<?php 
								if(in_array($rs_roc_product_v['id_product_value'],$roc_product_value)){
									if($roc_value_title[$j_value] !=''){
										echo $style_product_v.$roc_value_title[$j_value].$style_end_product_v;
									}else{echo '...............';}
								}else{echo '...............';}
							}?>
						</td>
						<?php if($j_value % 2 == 0){?></tr><?php } //display end row?>
						<?php if($num_v==$max_row_v){
						if($rs_product_appearance['id_product_appearance']==4){
							if(in_array('0',$roc_product_value)){
								$style_product_v2='<span style="color:#0079FC;">';
								$style_end_product_v2='</span>';
							}else{
								$style_product_v2='';
								$style_end_product_v2='';						
							}
						?>
						<td style="padding:1.0% 0 0 5.5%;">
							<input type="checkbox" <?php if(in_array('0',$roc_product_value)){echo 'checked="checked"';}?>><?php echo $style_product_v2;
							echo 'ผงชงดื่มประเภทอื่น';
							echo $style_end_product_v2;
							echo '&nbsp;';
							echo '(โปรดระบุ)'?> 
							<?php if($rs['product_value_title']==''){echo '..............';}else{echo '<span style="color:#0079FC;">'.$rs['product_value_title'].'</span>';}?>
						</td>
						<?php }
						}?>
					<?php $rows_v++;
					$j_value++;
					}//end while product value?>
					<?php if(($rs_product_appearance['id_product_appearance']== 2) || ($rs_product_appearance['id_product_appearance']== 3)){?>
				</table>
				<table style="width:100%;" cellpadding="0" cellspacing="0">
					<tr>
						<td colspan="3" style="padding:1.5% 0 0 5%;">ลักษณะผลิตภัณฑ์ที่บรรจุ</td>
					</tr>
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
						if($j_p % 1 == 0){?><tr><?php } //display row	
						
						if($rs['id_type_product_pack']==$rs_roc_product_p['id_type_product_pack']){
							$style_product_v='<span style="color:#0079FC;">';
							$style_end_product_v='</span>';
						}else{
							$style_product_v='';
							$style_end_product_v='';						
						}						
					?>
						<td colspan="3" style="padding:1.0% 0 0 6.5%;">
							<input type="checkbox" value="<?php echo $rs_roc_product_p['id_type_product_pack']?>" <?php if($rs['id_type_product_pack']==$rs_roc_product_p['id_type_product_pack']){echo 'checked="checked"';}?>><?echo $style_product_v.$rs_roc_product_p['title_product_pack'].$style_end_product_v?>
							<?php 
							if(($rs_product_appearance['id_product_appearance']==6) || ($rs_product_appearance['id_product_appearance']==7)){
								if(($rs_roc_product_p['title']== 'สี (Color)') ||($rs_roc_product_p['title']=='กลิ่น (Odor)') || ($rs_roc_product_p['title']=='รูปร่าง (Shape)') || ($rs_roc_product_p['title']=='สี') || ($rs_roc_product_p['title']=='กลิ่น')){
									echo '(โปรดระบุ) ';
								
								}//end color for soft gelation capsule
							}//end functional drink and gummy
							?>
						</td>
						<?php if($j_p % 1 == 0){?></tr><?php } //display end row?>
						<?php if($num_p==$max_row_p){
						if($rs_product_appearance['id_type_product']== 3){?>
							<input type="checkbox" name="type_product_pack" value="0" <?php if($rs['id_type_product_pack']==0){echo 'checked="checked"';}?>>อื่น ๆ (โปรดระบุ)
						<?php }
						}?>
					<?php }//end while product pack?>
					<?php }//end if capsule and soft gel?>
					<?php if($rs_product_appearance['id_product_appearance'] != 7){//not equal gummy?>
					<tr>
						<td colspan="3" style="padding:1.5% 0 0 5%;">น้ำหนักผลิตภัณฑ์ต่อหน่วย</td>
					</tr>
					<?php
					$num_w=0;
					$rows = 0;
					$j_w=0;
					$sql_roc_product_w="select * from roc_product_weight where id_product_appearance='".$rs_product_appearance['id_product_appearance']."'";
					$res_roc_product_w=mysql_query($sql_roc_product_w) or die ('Error '.$sql_roc_product_w);
					$max_row_w=mysql_num_rows($res_roc_product_w);
					while($rs_roc_product_w=mysql_fetch_array($res_roc_product_w)){						
						if($rs['id_product_weight']==$rs_roc_product_w['id_product_weight']){
							$style_product_weight='<span style="color:#0079FC;">';
							$style_end_product_weight='</span>';
						}else{
							$style_product_weight='';
							$style_end_product_weight='';						
						}	
						if($j_w % 3 == 0){?><tr><?php } //display row
					?>
						<td style="<?php if($rs_product_appearance['id_product_appearance']!=4){?>width:35%;padding:1.0% 0 0 6.5%;<?php }else{?>width:15%;padding:1.0% 0 0 6.5%;<?php }?>">
							<input type="checkbox" <?php if($rs['id_product_weight']==$rs_roc_product_w['id_product_weight']){echo 'checked="checked"';}?>><?php echo $style_product_weight.$rs_roc_product_w['title'].$style_end_product_weight?>
						</td>
					<?php if($j_w % 3 == 0){?></tr><?php } //display end row?>					
					<?php $j_w++;
						$num_w++;
					}//end while product weight?>
					<?php if($num_w==$max_row_w){
						if(($rs_product_appearance['id_product_appearance']==2) || ($rs_product_appearance['id_product_appearance']==4) || ($rs_product_appearance['id_product_appearance']==5)){echo '<tr>';}?>
						<td style="padding:1.0% 0 0 6.5%;">
							<input type="checkbox" <?php if($rs['id_product_appearance']==$rs_product_appearance['id_product_appearance']){if($rs['id_product_weight']== -1){echo 'checked="checked"';}}?>>อื่น ๆ (โปรดระบุ)
							<?php
							if($rs['other_product_weight']==''){echo '............';}
							else{
								if($rs['id_product_appearance']==$rs_product_appearance['id_product_appearance']){
									echo '<span style="color:#0079FC;">';
									echo $rs['other_product_weight'];
									echo '</span>';
								}
							}
							?>
						</td>
						<?php if(($rs_product_appearance['id_product_appearance']==2) || ($rs_product_appearance['id_product_appearance']==4)|| ($rs_product_appearance['id_product_appearance']==5)){echo '</tr>';}?>
					<?php }?>
					<?php }//end if not equal gummy?>
					<tr>
						<td colspan="3" style="padding:1.0% 0 0 5.0%;" >
						<?php
						if(($rs_product_appearance['id_product_appearance']==1) || ($rs_product_appearance['id_product_appearance']==2) || ($rs_product_appearance['id_product_appearance']==3) || ($rs_product_appearance['id_product_appearance']==4)){?>
							<?php if($rs_product_appearance['id_product_appearance']==1){?>สี กลิ่น และรูปร่างของเม็ด
							<?php }elseif($rs_product_appearance['id_product_appearance']==2){?>สีของแคปซูล
							<?php }elseif($rs_product_appearance['id_product_appearance']==3){?>สีและกลิ่นเปลือกแคปซูล
							<?php }elseif($rs_product_appearance['id_product_appearance']==4){?>สีและกลิ่นของผงชงดื่ม<?php }?>
						<?php }//end if?>
						</td>
					</tr>
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
						if(in_array($rs_roc_product_c['id_type_product_color'],$roc_color)){
							$style_product_c='<span style="color:#0079FC;">';
							$style_end_product_c='</span>';
						}else{
							$style_product_c='';
							$style_end_product_c='';						
						}
						if($j_c % 2== 0){?><tr><?php } //display row
					?>						
						<td style="padding:1.0% 0 0 6.5%;">
							<input type="checkbox" <?php if(in_array($rs_roc_product_c['id_type_product_color'],$roc_color)){echo 'checked="checked"';}?>><?php echo $style_product_c.$rs_roc_product_c['type_product_color'].$style_end_product_c?>
							<?php 
							if(($rs_product_appearance['id_product_appearance']==1) || ($rs_product_appearance['id_product_appearance']==3)){
								if(($rs_roc_product_c['type_product_color']== 'สี (Color)') ||($rs_roc_product_c['type_product_color']=='กลิ่น (Odor)') || ($rs_roc_product_c['type_product_color']=='รูปร่าง (Shape)') || ($rs_roc_product_c['type_product_color']=='สี') || ($rs_roc_product_c['type_product_color']=='กลิ่น')){
									
									$sql_rela_color="select * from roc_relation_color where id_roc='".$id."'";
									$sql_rela_color .=" and id_type_product_color='".$rs_roc_product_c['id_type_product_color']."'";
									$res_rela_color=mysql_query($sql_rela_color) or die ('Error '.$sql_rela_color);
									$rs_rela_color=mysql_fetch_array($res_rela_color);
									
									if(in_array($rs_roc_product_c['id_type_product_color'],$roc_color)){
										$style_product_c1='<span style="color:#0079FC;">';
										$style_end_product_c1='</span>';
									}else{
										$style_product_c1='';
										$style_end_product_c1='';						
									}

									echo '(โปรดระบุ) '; 
									if(in_array($rs_roc_product_c['id_type_product_color'],$roc_color)){echo $style_product_c1.$roc_color_title[$i_title].$style_end_product_c1;}
									else{echo '....................';}
								}
							}//end if tablet and soft gel
							if($rs_product_appearance['id_product_appearance']==4){
								if(($rs_roc_product_c['type_product_color']== 'กลิ่น (Odor)') ||($rs_roc_product_c['type_product_color']=='รส (Taste)') || ($rs_roc_product_c['type_product_color']== 'สี (Color)')) {
		
									if(in_array($rs_roc_product_c['id_type_product_color'],$roc_color)){
										$style_product_c1='<span style="color:#0079FC;">';
										$style_end_product_c1='</span>';
									}else{
										$style_product_c1='';
										$style_end_product_c1='';						
									}

									echo '(โปรดระบุ) '; 
									if(in_array($rs_roc_product_c['id_type_product_color'],$roc_color)){echo $style_product_c1.$roc_color_title[$i_title].$style_end_product_c1;}
									else{echo '......................';}
								}
							}//end if instant drink
							?>
						</td>
					<?php if($j_c % 2 == 0){?></tr><?php } //display row
						if($num_c==$max_row_c){$i_title++;
							if($rs_type_product['id_type_product']!=3){
								if($rs_product_appearance['id_product_appearance']==2){$i_title=0;}
								if($rs_product_appearance['id_product_appearance']==3){$i_title=2;}
						?>
								<input type="checkbox" <?php if($rs['id_product_appearance']==$rs_product_appearance['id_product_appearance']){if(in_array('0',$roc_color)){echo 'checked="checked"';}}?> value="0" >อื่น ๆ (โปรดระบุ) <?php if($rs['id_product_appearance']==$rs_product_appearance['id_product_appearance']){if(in_array('0',$roc_color)){echo $roc_color_title[$i_title];}}else{echo '..................';}?>
						<?php  } // not equal soft gelatin capsule
						}
						$j_c++;
						$num_c++;	
					}//end while color	
					?>
					<?php if($rs_product_appearance['id_product_appearance'] == 7){?>
					<tr>
						<td colspan="3" style="padding:1.0% 0 0 5.0%;">ลักษณะการเคลือบ</td>
					</tr>
						<?php 
						$num_p2=0;
						$rows_p2=0;
						$j_p2=0;
						$sql_roc_product_p="select * from type_product_pack where id_product_appearance='".$rs_product_appearance['id_product_appearance']."'";
						$res_roc_product_p=mysql_query($sql_roc_product_p) or die ('Error '.$sql_roc_product_p);
						$max_row_p2=mysql_num_rows($res_roc_product_p);
						while($rs_roc_product_p=mysql_fetch_array($res_roc_product_p)){
							if($rs_roc_product_p['id_type_product_pack']==$rs['id_type_product_pack']){
								$style_product_p='<span style="color:#0079FC;">';
								$style_end_product_p='</span>';
							}else{
								$style_product_p='';
								$style_end_product_p='';						
							}
							if($j_p2 % 3== 0){?><tr><?php } //display row
						?>
							<td style="padding:1.5% 0 0 6.5%;">
								<input type="checkbox" <?php if($rs_roc_product_p['id_type_product_pack']==$rs['id_type_product_pack']){echo 'checked="checked"';}?>><?php echo $style_product_p.$rs_roc_product_p['title_product_pack'].$style_end_product_p?>
							</td>
						<?php if($j_p2 % 3== 0){?></tr><?php } //display row ?>						
						<?php
							$j_p2++;
							$num_p2++;
						}//end while product pack?>
						<?php if($num_p2==$max_row_p2){?>
							<td style="padding:1.5% 0 0 4.5%;">
								<input type="checkbox" class="checkbox" name="type_product_pack" value="0">อื่น ๆ (โปรดระบุ) <?php if($rs['id_product_appearance']==7){if(in_array('-1',$roc_color)){if($roc_color_title[$i_title] != ''){echo '<span style="color:#0079FC;">';echo $roc_color_title[$i_title];echo '</span>';}}else{echo '.....................';}}else{echo '.....................';}?>
							</td>
						<?php }?>
					<?php }//end gummy?>
					<?php if(($rs_product_app1['id_product_appearance'] != 3) && ($rs_product_app1['id_product_appearance'] != 4) && ($rs_product_app1['id_product_appearance'] != 5) && ($rs_product_app1['id_product_appearance'] != 6) ){?>
					<tr>
						<td style="padding:1.5% 0 0 3.5%;" colspan="7"><input type="checkbox" <?php if($rs['id_product_appearance']== -1){echo 'checked="checked"';}?>><?php echo '&nbsp;อื่น ๆ';?>
						<?php 
						if($rs['other_product_app']== ''){
							echo '(โปรดระบุ)' ;
							echo '<br><br>';
							echo '...............................................................................................................................................................................................';
							echo '<br><br>';
							echo '...............................................................................................................................................................................................';
							echo '<br><br>';
							echo '...............................................................................................................................................................................................';
						}else{
							echo '<span style="color:#0079FC;">';
							echo $rs['other_product_app'];
							echo '</span>';
							echo '...............................................................................................................................................................................................';
							echo '<br><br>';
							echo '...............................................................................................................................................................................................';
						}
						?>
						</td>
					<tr>
					<?php }?>
				</table>
			</td>
		</tr>
		<?php }//end while?>
	</table>
	<?php }
	}//end while product app?>
	<table style="width:100%;font-size:0.9em;padding:0 0 17% 0;margin:0;border-left:1px solid #000;border-right:1px solid #000;border-bottom:1px solid #000;" cellpadding="0" cellspacing="0">
		<tr>
			<td style="padding:1.5% 0 0 2%;">4.บรรจุภัณฑ์</td>
		</tr>
		<tr>
			<?php
			$sql_relation_pack="select * from roc_relation_pack where id_relation_pack='".$rs['id_relation_pack']."'";
			$res_relation_pack=mysql_query($sql_relation_pack) or die ('Error '.$sql_relation_packs);
			$rs_relation_pack=mysql_fetch_array($res_relation_pack);
			$product_app=$rs_relation_pack['id_product_appearance'];
			
			if($rs_relation_pack['id_product_appearance']=='1,2,3'){
				$style_relation_pack1='<span style="color:#0079FC;">';
				$style_end_relation_pack1='</span>';
			}else{
				$style_relation_pack1='';
				$style_end_relation_pack1='';						
			}
			?>
			<td style="padding:1.5% 0 0 3.5%;">
				<input type="checkbox" <?php if($rs_relation_pack['id_product_appearance']=='1,2,3'){echo 'checked="checked"';}?>>
				<?php
				$i_app=0;
				$sql_product_appearance="select * from product_appearance";
				$res_product_appearance=mysql_query($sql_product_appearance) or die ('Error '.$sql_product_appearance);
				while($rs_product_appearance=mysql_fetch_array($res_product_appearance)){
					$i_app++;
					if(($rs_product_appearance['id_product_appearance']==1) || ($rs_product_appearance['id_product_appearance']==2) || ($rs_product_appearance['id_product_appearance']==3)){
						if($i_app<3){$back="/";}else{$back="";}
							echo $style_relation_pack1;
							echo $rs_product_appearance['title_thai'].'&nbsp;';
							echo $back.'&nbsp;';
							$pack_array=array($rs_product_appearance['id_type_package']);
							echo $style_end_relation_pack1;
						}//end if product apperance
					}//end product appearance
				?>	
			</td>
		</tr>
		<tr>
			<td>
				<table style="width:100%;" cellpadding="0" cellspacing="0">
					<?php 
					$pack=$pack_array[0];
					$roc_type_packaging=split(",",$pack);
					for($i_pack=0;$i_pack<=count($roc_type_packaging);$i_pack++){
						$sql_type_packaging="select * from type_packaging where id_type_package='1' or id_type_package='5'";
						$sql_type_packaging .=" order by id_type_package desc";
						$res_type_packaging=mysql_query($sql_type_packaging) or die ('Error '.$sql_type_packaging);
						while($rs_type_packaging=mysql_fetch_array($res_type_packaging)){
							if($rs_relation_pack['id_type_package']==$rs_type_packaging['id_type_package']){
								$style_type_package1='<span style="color:#0079FC;">';
								$style_end_type_package1='</span>';
							}else{
								$style_type_package1='';
								$style_end_type_package1='';						
							}
					?>
						<tr>
							<td colspan="4" style="padding:1.5% 0 0 5.5%;"><input type="checkbox" <?php if($rs_relation_pack['id_type_package']==$rs_type_packaging['id_type_package']){echo 'checked="checked"';}?>><?php echo $style_type_package1.$rs_type_packaging['title_thai'].$style_end_type_package1?>
							</td>
						</tr>
						<tr>
							<td colspan="4" style="padding:1.5% 0 0 7.5%;">ขนาดบรรจุ</td>
						</tr>
							<?php
							$i_box=0;
							$num_box_size=0;
							$rows_box_size=0;
							$sql_box_size="select * from roc_product_box_size where id_type_package='".$rs_type_packaging['id_type_package']."'";
							$res_box_size=mysql_query($sql_box_size) or die ('Error '.$sql_box_size);
							$max_row_box_size=mysql_num_rows($res_box_size);
							while($rs_box_size=mysql_fetch_array($res_box_size)){
								$num_box_size++;
								$i_pack++;
								if($rs_relation_pack['id_pack_size']==$rs_box_size['id_box_size']){
									$style_pack_size1='<span style="color:#0079FC;">';
									$style_end_pack_size1='</span>';
								}else{
									$style_pack_size1='';
									$style_end_pack_size1='';						
								}
								if($i_box % 3 == 0){?><tr><?php } //display row 
							?>
								<td style="padding:1% 0 0 7.5%;">
									<input type="checkbox" <?php if($rs_relation_pack['id_pack_size']==$rs_box_size['id_box_size']){echo 'checked="checked"';}?>><?php echo $style_pack_size1.$rs_box_size['title_box_size'].$style_end_pack_size1?>
								</td>
							<?php if($i_box % 3 == 0){?></tr><?php } //display row ?>
							<?php if($num_box_size==$max_row_box_size){?>
								<?php if($rs_type_packaging['id_type_package']==1){?><tr><?php }?>
								<td style="padding:1% 0 0 7.5%;"><input type="checkbox" >อื่น ๆ (โปรดระบุ) ......................</td>
								<?php if($rs_type_packaging['id_type_package']==1){?></tr><?php }?>
							<?php }?>
							<?php $i_box++;}//end while box?>
							<?php if($rs_type_packaging['id_type_package']==5){?>
							<tr>
								<td colspan="4" style="padding:1.5% 0 0 7.5%;">แผง & ฟอยล์</td>
							</tr>
							<?php
							$roc_foil=split(",",$rs_relation_pack['id_product_foil']);
							$i_foil=0;
							$sql_foil="select * from roc_product_foil where id_type_package='".$rs_type_packaging['id_type_package']."'";
							$res_foil=mysql_query($sql_foil) or die ('Error '.$sql_foil);
							while($rs_foil=mysql_fetch_array($res_foil)){	
								if(in_array($rs_foil['id_product_foil'],$roc_foil)){
									$style_product_foil1='<span style="color:#0079FC;">';
									$style_end_product_foil1='</span>';
								}else{
									$style_product_foil1='';
									$style_end_product_foil1='';						
								}
								if($i_foil % 3 == 0){?><tr><?php } //display row 
							?>
								<td style="padding:1% 0 0 7.5%;">
									<input type="checkbox" <?php if(in_array($rs_foil['id_product_foil'],$roc_foil)){echo 'checked="checked"';}?>><?php echo $style_product_foil1.$rs_foil['title_foil'].$style_end_product_foil1?>
								</td>
							<?php if($i_foil % 3 == 0){?></tr><?php } //display row ?>
							<?php $i_foil++;}//end while foil?>
							<?php }else{?>
							<tr>
								<td colspan="4" style="padding:1.5% 0 0 7.5%;">ชนิดขวด</td>
							</tr>
							<?php
							$num_bottle=0;
							$sql_bottle="select * from roc_product_bottle where id_type_package='".$rs_type_packaging['id_type_package']."'";
							$res_bottle=mysql_query($sql_bottle) or die ('Error '.$sql_bottle);
							$max_row_bottle=mysql_num_rows($res_bottle);
							while($rs_bottle=mysql_fetch_array($res_bottle)){								
								if($rs_relation_pack['id_product_bottle']==$rs_bottle['id_product_bottle']){
									$style_product_bottle1='<span style="color:#0079FC;">';
									$style_end_product_bottle1='</span>';
								}else{
									$style_product_bottle1='';
									$style_end_product_bottle1='';						
								}
								if($num_bottle % 3 == 0){?><tr><?php } //display row 
							?>
								<td style="padding:1% 0 0 7.5%;">
									<input type="checkbox" <?php if($rs_relation_pack['id_product_bottle']==$rs_bottle['id_product_bottle']){echo 'checked="checked"';}?>><?php echo $style_product_bottle1.$rs_bottle['title_bottle'].$style_end_product_bottle1?>
								</td>
								<?php if($num_bottle % 3 == 0){?></tr><?php } //display row ?>
							<?php $num_bottle++;}//end while bottle?>
							<?php }//end if bottle?>
							<tr>
								<td colspan="4" style="padding:1.5% 0 0 7.5%;">วัสดุบรรจุประกอบ</td>
							</tr>
							<?php
							$roc_materials=split(",",$rs_relation_pack['id_material']);
							$num_material=0;
							$i_box=0;
							$sql_materials="select * from roc_product_materials where id_type_product='".$rs_type_packaging['id_type_package']."'";
							$res_materials=mysql_query($sql_materials) or die ('Error '.$sql_materials);
							$max_row_material=mysql_num_rows($res_materials);
							while($rs_materials=mysql_fetch_array($res_materials)){								
								if(in_array($rs_materials['id_materials'],$roc_materials)){
									$style_materials1='<span style="color:#0079FC;">';
									$style_end_materials1='</span>';
								}else{
									$style_materials1='';
									$style_end_materials1='';						
								}
								if($num_material % 3 == 0){?><tr><?php } //display row 
							?>	
								<td style="padding:1% 0 0 7.5%;">
									<input type="checkbox" <?php if(in_array($rs_materials['id_materials'],$roc_materials)){echo 'checked="checked"';}?>><?php echo $style_materials1.$rs_materials['title_materials'].$style_end_materials1?>
									<?php if($rs_materials['title_materials']=='ขนาดกล่อง'){
									$box_detail=split(",",$rs_relation_pack['box_detail']);
									?>
										<?php if(in_array($rs_materials['id_materials'],$roc_materials)){echo $box_detail[$i_box];}
										else{echo '..................';}?> 
									<?php $i_box++;}//end if box size?>
								</td>
							<?php if($num_material % 3 == 0){?></tr><?php } //display row ?>
							<?php $num_material++;
							if($num_material==$max_row_material){
								if($rs_type_packaging['id_type_package'] != 5){
							?>
							<tr>
								<td style="padding:1% 0 0 7.5%;"><input type="checkbox">อื่น ๆ...........................</td>
							</tr>
							<?php }
							}?>
							<?php }//end while materials?>
						<?php }//end while package box or bottle?>
					<?php }//end for?>
				</table>
			</td>
		</tr>
	</table>
	<table style="width:100%;font-size:0.9em;padding:0 0 26% 0;border-left:1px solid #000;border-right:1px solid #000;border-bottom:1px solid #000;" cellpadding="0" cellspacing="0">
		<tr>
			<td>
				<table style="width:100%;" cellpadding="0" cellspacing="0">
					<?php //Instant drink, function drink, edible gel
					$no=2;
					$sql_relation_pack="select * from roc_relation_pack where id_relation_pack='".$rs['id_relation_pack']."'";
					$res_relation_pack=mysql_query($sql_relation_pack) or die ('Error '.$sql_relation_packs);
					$rs_relation_pack=mysql_fetch_array($res_relation_pack);
					$i_app=0;
					$sql_product_appearance="select * from product_appearance";
					$res_product_appearance=mysql_query($sql_product_appearance) or die ('Error '.$sql_product_appearance);
					while($rs_product_appearance=mysql_fetch_array($res_product_appearance)){
						$i_app++;
						if(($rs_product_appearance['id_product_appearance']==4) || ($rs_product_appearance['id_product_appearance']==5) || ($rs_product_appearance['id_product_appearance']==6)){

							if($rs_relation_pack['id_product_appearance']==$rs_product_appearance['id_product_appearance']){
								$style_relation_pack='<span style="color:#0079FC;">';
								$style_end_relation_pack='</span>';
							}else{
								$style_relation_pack='';
								$style_end_relation_pack='';						
							}
					?>
					<tr>
						<td colspan="3" style="padding:1.5% 0 0 3.0%;"><input type="checkbox" <?php if($rs_relation_pack['id_product_appearance']==$rs_product_appearance['id_product_appearance']){echo 'checked="checked"';}?>><?php echo '&nbsp;'.$style_relation_pack.$rs_product_appearance['title_thai'].'('.$rs_product_appearance['title'].')'.$style_end_relation_pack?>
						</td>
					</tr>
					<tr>
						<td colspan="3" style="padding:1% 0 0 5.5%;">
							<?php if($rs_product_appearance['id_product_appearance']==4){echo 'จำนวนบรรจุกล่อง';}?>
							<?php if($rs_product_appearance['id_product_appearance']==5){echo 'จำนวนบรรจุ';}?>
							<?php if($rs_product_appearance['id_product_appearance']==6){echo 'จำนวนบรรจุต่อกล่อง';}?>										
						</td>
					</tr>
					<?php
					$num_box_size=0;
					$rows_box_size=0;
					$i_box2=0;
					$sql_box_size="select * from roc_product_box_size where id_type_product='".$rs_product_appearance['id_product_appearance']."'";
					$res_box_size=mysql_query($sql_box_size) or die ('Error '.$sql_box_size);
					$max_row_box_size=mysql_num_rows($res_box_size);
					while($rs_box_size=mysql_fetch_array($res_box_size)){
						$num_box_size++;
						if($rs_relation_pack['id_pack_size']==$rs_box_size['id_box_size']){
							$style_pack_size='<span style="color:#0079FC;">';
							$style_end_pack_size='</span>';
						}else{
							$style_pack_size='';
							$style_end_pack_size='';						
						}
						if($i_box2 % 3 == 0){?><tr><?php } //display row 
					?>
						<td style="padding:1% 0 0 5.5%;">
							<input type="checkbox" <?php if($rs_relation_pack['id_pack_size']==$rs_box_size['id_box_size']){echo 'checked="checked"';}?>><?php echo $style_pack_size.$rs_box_size['title_box_size'].$style_end_pack_size?>
						</td>
						<?php if($num_box_size==$max_row_box_size){?>
						<td style="padding:1% 0 0 5.5%;"><input type="checkbox">อื่น ๆ........<?php }?>
					<?php if($i_box2 % 3 == 0){?></tr><?php } //display row ?>									
					<?php  $i_box2++;}//end while box size?>
					<tr>
						<td style="padding:1.5% 0 0 5.5%;">
							<?php if($rs_product_appearance['id_product_appearance']==4){echo 'ขนาดซอง';}?>
							<?php if($rs_product_appearance['id_product_appearance']==5){echo 'รูปแบบซอง';}?>
						</td>
					</tr>
					<?php
					$num_sachet=0;
					$i_sachet=0;
					$sql_sachet="select * from roc_product_sachet where id_type_package='".$rs_product_appearance['id_product_appearance']."'";
					$res_sachet=mysql_query($sql_sachet) or die ('Error '.$sql_sachet);
					$max_row_sachet=mysql_num_rows($res_sachet);
					while($rs_sachet=mysql_fetch_array($res_sachet)){
						$num_sachet++;	
						if($rs_relation_pack['id_product_sachet']==$rs_sachet['id_product_sachet']){
							$style_product_sachet='<span style="color:#0079FC;">';
							$style_end_product_sachet='</span>';
						}else{
							$style_product_sachet='';
							$style_end_product_sachet='';						
						}
						if($i_sachet % 2 == 0){?><tr><?php } //display row 
					?>
						<td style="padding:1% 0 0 5.5%;">
							<input type="checkbox" <?php if($rs_relation_pack['id_product_sachet']==$rs_sachet['id_product_sachet']){echo 'checked="checked"';}?>><?php echo $style_product_sachet.$rs_sachet['title_sachet'].$style_end_product_sachet?>
						</td>
						<?php if($num_sachet==$max_row_sachet){?>
						<td style="padding:1% 0 0 5.5%;"><input type="checkbox" <?php if($rs_relation_pack['id_product_appearance']==$rs_product_appearance['id_product_appearance']){if($rs_relation_pack['id_product_sachet']== '-1'){echo 'checked="checked"';}}?>>						
						อื่น ๆ
						<?php 						
						if($rs_product_appearance['id_product_appearance']==$rs_relation_pack['id_product_appearance']){
							if($rs_relation_pack['other_sachet']==''){echo '..........';}
							else{echo '<span style="color:#0079FC;">'.$rs_relation_pack['other_sachet'].'</span>';}
						}
						?>
						</td>
						<?php }?>
					<?php if($i_sachet % 2 == 0){?></tr><?php } //display row ?>	
					<?php $i_sachet++;}//end while sachet?>
					<?php if($rs_product_appearance['id_product_appearance']==4){?>
					<tr>
						<td colspan="3" style="padding:1% 0 0 5.5%;">ฟอยล์</td>
					</tr>
					<?php
					$num_foil=0;
					$rows_foil=0;
					$sql_foil="select * from roc_product_foil where id_type_package='".$rs_product_appearance['id_product_appearance']."'";
					$res_foil=mysql_query($sql_foil) or die ('Error '.$sql_foil);
					while($rs_foil=mysql_fetch_array($res_foil)){
						if($rs_relation_pack['id_product_foil']==$rs_foil['id_product_foil']){
							$style_product_foil='<span style="color:#0079FC;">';
							$style_end_product_foil='</span>';
						}else{
							$style_product_foil='';
							$style_end_product_foil='';						
						}
						if($num_foil % 2 == 0){?><tr><?php } //display row 
					?>
						<td style="padding:1% 0 0 5.5%;">
							<input type="checkbox" <?php if($rs_relation_pack['id_product_foil']==$rs_foil['id_product_foil']){echo 'checked="checked"';}?>><?php echo $style_product_foil.$rs_foil['title_foil'].$style_end_product_foil?>
						</td>
					<?php if($num_foil % 2 == 0){?></tr><?php } //display row ?>
					<?php $num_foil++;
					$rows_foil++;}//end while foil?>
					<?php }elseif($rs_product_appearance['id_product_appearance']==6){?>
					<tr>
						<td colspan="3" style="padding:0.5% 0 0 5.5%;">รูปแบบขวด</td>
					</tr>
					<?php
					$num_bottle=0;
					$i_bottle=0;
					$sql_bottle="select * from roc_type_bottle where id_type_package='".$rs_product_appearance['id_product_appearance']."'";
					$res_bottle=mysql_query($sql_bottle) or die ('Error '.$sql_bottle);
					$max_row_bottle=mysql_num_rows($res_bottle);
					while($rs_bottle=mysql_fetch_array($res_bottle)){
						$num_bottle++;
						if($rs_relation_pack['id_type_bottle']==$rs_bottle['id_type_bottle']){
							$style_type_bottle='<span style="color:#0079FC;">';
							$style_end_type_bottle='</span>';
						}else{
							$style_type_bottle='';
							$style_end_type_bottle='';						
						}
						if($i_bottle % 2 == 0){?><tr><?php } //display row 
					?>
						<td style="padding:1% 0 0 5.5%;">
							<input type="checkbox" <?php if($rs_relation_pack['id_type_bottle']==$rs_bottle['id_type_bottle']){echo 'checked="checked"';}?>><?php echo $style_type_bottle.$rs_bottle['title_type_bottle'].$style_end_type_bottle?>
						</td>
						<?php if($i_bottle % 2 == 0){?></tr><?php } //display row ?>
						<?php if($num_bottle==$max_row_bottle){?>
						<td style="padding:1% 0 0 5.5%;"><input type="checkbox">อื่น ๆ...........</td>
						<?php }?>
					<?php $i_bottle++;}//end while bottle?>					
					<tr>
						<td colspan="3" style="padding:1% 0 0 5.5%;">ลักษณะฝา</td>
					</tr>
					<?php
					$num_lid=0;
					$sql_lid="select * from roc_product_bottle_lid where id_type_package='".$rs_product_appearance['id_product_appearance']."'";
					$res_lid=mysql_query($sql_lid) or die ('Error '.$sql_lid);
					while($rs_lid=mysql_fetch_array($res_lid)){		
						if($rs_relation_pack['id_bottle_lid']==$rs_lid['id_bottle_lid']){
							$style_bottle_lid='<span style="color:#0079FC;">';
							$style_end_bottle_lid='</span>';
						}else{
							$style_bottle_lid='';
							$style_end_bottle_lid='';						
						}
						if($num_lid % 3 == 0){?><tr><?php } //display row 
					?>
						<td style="padding:1% 0 0 5.5%;">
							<input type="checkbox" <?php if($rs_relation_pack['id_bottle_lid']==$rs_lid['id_bottle_lid']){echo 'checked="checked"';}?>><?php echo $style_bottle_lid.$rs_lid['title_bottle_lid'].$style_end_bottle_lid?>
						</td>
					<?php if($num_lid % 3 == 0){?></tr><?php } //display row ?>
					<?php $num_lid++;}//end while bottle lid?>
					<?php }//end if?>
					<tr>
						<td colspan="3" style="padding:1% 0 0 5.5%;">วัสดุบรรจุประกอบ</td>
					</tr>
					<?php
					$i_materials=0;
					$roc_materials=split(",",$rs_relation_pack['id_material']);
					$num_material=0;
					$sql_materials="select * from roc_product_materials where id_type_package='".$rs_product_appearance['id_product_appearance']."'";
					$res_materials=mysql_query($sql_materials) or die ('Error '.$sql_materials);
					$max_row_material=mysql_num_rows($res_materials);
					while($rs_materials=mysql_fetch_array($res_materials)){
						$num_material++;
						if(in_array($rs_materials['id_materials'],$roc_materials)){
							$style_materials='<span style="color:#0079FC;">';
							$style_end_materials='</span>';
						}else{
							$style_materials='';
							$style_end_materials='';						
						}
						if($i_materials % 3 == 0){?><tr><?php } //display row 
					?>
						<td style="padding:0.5% 0 0 5.5%;">
							<input type="checkbox" <?php if(in_array($rs_materials['id_materials'],$roc_materials)){echo 'checked="checked"';}?>><?php echo $style_materials.$rs_materials['title_materials'].$style_end_materials?>
							<?php if($rs_materials['title_materials']=='ขนาดกล่อง'){
							$box_detail=split(",",$rs_relation_pack['box_detail']);
							?>
							<?php if(in_array($rs_materials['id_materials'],$roc_materials)){echo $box_detail[$i_materials];}
							else{echo '..................';}?> 
							<?php }?>
						</td>						
					<?php if($i_materials % 3 == 0){?></tr><?php } //display row?>
					<?php if($num_material==$max_row_material){?>
					<?php if($rs_product_appearance['id_product_appearance']==5){?>
					<tr>
						<td colspan="3" style="padding:1% 0 0 5.5%;"><input type="checkbox" <?php if(in_array($rs_materials['id_materials'],$roc_materials)){echo 'checked="checked"';}?>>อื่น ๆ
						<?php if($rs_product_appearance['id_product_appearance']==$rs_relation_pack['id_product_appearance']){
							if($rs_relation_pack['other_materials'] != ''){
								echo '<span style="color:#0079FC;">'.$rs_relation_pack['other_materials'].'</span>';
							}else{echo '........';}
						}else{echo '........';}?>
					</td>
					</tr>
					<?php }elseif(($rs_product_appearance['id_product_appearance']==4) || ($rs_product_appearance['id_product_appearance']==6)){?>
						<td style="padding:1% 0 0 5.5%;"><input type="checkbox" <?php if(in_array($rs_materials['id_materials'],$roc_materials)){echo 'checked="checked"';}?>>อื่น ๆ
						<?php if($rs_product_appearance['id_product_appearance']==$rs_relation_pack['id_product_appearance']){
							if($rs_relation_pack['other_materials'] != ''){
								echo '<span style="color:#0079FC;">'.$rs_relation_pack['other_materials'].'</span>';
							}else{echo '........';}
						}else{echo '........';}?>
						</td>
					<?php }//end if product appearance not gel?>
					<?php }?>
					<?php $i_materials++;}//end while?>
					<?php $no++;}//end if?>
					<?php }//end while?>
				</table>
			</td>
		</tr>
	</table>
	<table style="width:100%;font-size:0.9em;border-left:1px solid #000;border-right:1px solid #000;border-bottom:1px solid #000;padding:0 0 15% 0;" cellpadding="0" cellspacing="0">
		<tr>
			<td>
				<table style="width:100%;" cellpadding="0" cellspacing="0">
					<tr>
						<?php
						if($rs['id_ink_jet']==1){
							$style_ink1='<span style="color:#0079FC;">';
							$style_end_ink1='</span>';
						}else{
							$style_ink1='';
							$style_end_ink1='';
						}
						?>
						<td colspan="4" style="padding:1% 0 0 5.5%;"><input type="checkbox" <?php if($rs['id_ink_jet']==1){echo 'checked="checked"';}?>><?php echo '&nbsp;'.$style_ink1.'Ink Jet'.$style_end_ink1?></td>
					</tr>
					<?php 
					$i_ink=0;
					$type_ink=split(",",$rs['id_type_ink_jet']);
					$detail_ink=split(",",$rs['id_detail_ink']);
					$sql_ink="select * from roc_ink_jet";
					$res_ink=mysql_query($sql_ink) or die ('Error '.$sql_ink);
					while($rs_ink=mysql_fetch_array($res_ink)){
						$i_ink++;
						if(in_array($rs_ink['id_ink_jet'],$type_ink)){
							$style_ink='<span style="color:#0079FC;">';
							$style_end_ink='</span>';
						}else{
							$style_ink='';
							$style_end_ink='';
						}
					?>
					<tr>
						<td style="width:290px;padding:1% 0 0 7.5%;"><input type="checkbox" <?php if(in_array($rs_ink['id_ink_jet'],$type_ink)){echo 'checked="checked"';}?>><?php echo $style_ink.$rs_ink['title_ink_jet'].$style_end_ink?></td>
						<?php
						$j_ink_detail=0;
						$sql_ink_detail="select * from roc_ink_jet_detail where id_ink_jet='".$rs_ink['id_ink_jet']."'";
						$res_ink_detail=mysql_query($sql_ink_detail) or die ('Error '.$sql_ink_detail);
						while($rs_ink_detail=mysql_fetch_array($res_ink_detail)){
							$j_ink_detail++;
							if(in_array($rs_ink_detail['id_detail_ink'],$detail_ink)){
								$style_ink_detail='<span style="color:#0079FC;">';
								$style_end_ink_detail='</span>';
							}else{
								$style_ink_detail='';
								$style_end_ink_detail='';
							}
							if($j_ink_detail==1){								
						?>
							<td style="width:200px;padding:1% 0 0 10%;vertical-align:top;"><input type="checkbox" <?php if(in_array($rs_ink_detail['id_detail_ink'],$detail_ink)){echo 'checked="checked"';}?>><?php echo $style_ink_detail.$rs_ink_detail['title_detail_ink'].$style_end_ink_detail?></td>
						<?php }else{?>
						<tr>
							<td style="width:290px;"></td>
							<td style="width:200px;padding:1% 0 0 10%;"><input type="checkbox" <?php if(in_array($rs_ink_detail['id_detail_ink'],$detail_ink)){echo 'checked="checked"';}?>><?php echo $style_ink_detail.$rs_ink_detail['title_detail_ink'].$style_end_ink_detail?></td>
						</tr>
						<?php }										
						}//end while ink jet detail?>
					</tr>
					<?php }//end while ink jet?>
					<tr>
						<td colspan="4" style="padding:1.5%; 0">5.ราคาโดยประมาณของผลิตภัณฑ์สำเร็จรูปที่ต้องการ
							<?php 
							if($rs['product_price']==''){
								echo '..................................................................................................................';
							}
							else{
								echo '<span style="color:#0079FC;">';
								echo '&nbsp;&nbsp;&nbsp;'.$rs['product_price'];
								echo '</span>';
							}
							?>
						</td>
					</tr>
					<tr>
						<td colspan="4" style="padding:1.5%; 0">6.ผลิตภัณฑ์ในท้องตลาดที่เป็นตัวเปรียบเทียบ
							<?php
							if($rs['product_compare']==''){
								echo '...........................................................................................................................';
							}
							else{
								echo '<span style="color:#0079FC;">';
								echo '&nbsp;&nbsp;&nbsp;'.$rs['product_compare'];
								echo '</span>';
							}
							?>
						</td>
					</tr>
					<tr>
						<td colspan="4" style="padding:1.5%; 0">7.Product selling point
							<?php 
							if($rs['product_selling']==''){
								echo '...........................................................................................................................................................';
							}
							else{
								echo '<span style="color:#0079FC;">';
								echo '&nbsp;&nbsp;&nbsp;'.$rs['product_selling'];
								echo '</span>';
							}
							?>
						</td>
					</tr>
					<tr>
						<td colspan="4" style="padding:1.5%; 0">8.Market position
							<?php
							if($rs['market_position']==''){
								echo '...................................................................................................................................................................';
							}
							else{
								echo '<span style="color:#0079FC;">';
								echo '&nbsp;&nbsp;&nbsp;'.$rs['market_position'];
								echo '</span>';
							}
							?>
						</td>
					</tr>
					<tr>
						<td colspan="4" style="padding:1.5%; 0">9.Selling channel
							<?php
							if($rs['selling_channel']==''){
								echo '....................................................................................................................................................................';
							}
							else{
								echo '<span style="color:#0079FC;">';
								echo '&nbsp;&nbsp;&nbsp;'.$rs['selling_channel'];
								echo '</span>';
							}
							?>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td style="text-align:center;padding:20% 0 0 20%;">
				ผู้บันทึก<br>
				<?php 
				$sql_signature="select * from signature where id_account='".$rs['create_by']."'";
				$res_signature=mysql_query($sql_signature) or die ('Error '.$sql_signature);
				$rs_signature=mysql_fetch_array($res_signature);
				if($rs_signature['image']==''){
					echo '<br><br><br><br><br><br>'; 
				}else{
				?>
				<img src="img/signature/<?php echo $rs_signature['image']?>" style="width:150px;"><br>				
				<?php }?>
				Sales & Marketing Representative Officer<br><br>
				<?php
					if($rs['date_roc']==''){echo '........../............/..........';}
					else{
						list($ckyear,$ckmonth,$ckday) = split('[/.-]', $rs['date_roc']); 
						echo $ckstart= $ckday . "/". $ckmonth . "/" .$ckyear;
					}
				?>
			</td>
		</tr>
	</table>
</body>
</html>
<?php
	$html = ob_get_contents();
	ob_end_clean();
	$mpdf=new mPDF('th','A4',0,'',15,15,38.5,6,6,6,'THSaraban');
	$mpdf-> SetAutoFont();
	$mpdf-> SetHTMLHeader('<table style="width:100%;line-height:1.5em;" cellspacing="0" cellpadding="0">
			<tr>
				<td colspan="7" style="text-align:right;font-size:0.7em;">FR-COM-OS-01 Rev.01 Effective date 23/09/14</td>
			</tr>
			<tr>
			<td colspan="1" style="padding:1%;border-top:1px solid #000;border-left:1px solid #000;"><img src="img/logo.png" style="width:15%;"></td>
			<td colspan="6" style="vertical-align:middle;border-top:1px solid #000;border-right:1px solid #000;font-family:Arial;padding:1.5% 30% 0 0;text-align:center;font-size:0.9em;">บริษัท ซีดีไอพี (ประเทศไทย) จำกัด<br>
			CDIP (Thailand) Co.,Ltd.
			</td>
			</tr>
			<tr>
				<td colspan="7" style="vertical-align:middle;border-top:1px solid #000;border-left:1px solid #000;border-right:1px solid #000;font-family:Arial;text-align:center;padding:0.5% 0 0 0;text-align:center;font-size:0.9em;border-bottom:1px solid #000;">บันทึกความต้องการของลูกค้า (Requisition of Customer)</td>
			</tr>
		</table>');
	$mpdf->SetWatermarkText('Controlled Document');
	$mpdf->showWatermarkText = true; 
	$mpdf->pagenumPrefix = 'Page ';
	//$mpdf->pagenumSuffix = ' - ';
	$mpdf->nbpgPrefix = ' of ';
	//$mpdf->nbpgSuffix = ' pages';
	
	$mpdf->SetHTMLFooter('<div style="text-align:center;font-size:0.8em;">{PAGENO}{nbpg}</div>');

	$mpdf-> WriteHTML($html);
	$mpdf-> Output("roc/roc.pdf","I");
?>