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
	if($rs_account['id_department']==1){$where=" where project_manager='".$rs_account['id_account']."'";}
	else{$where=" ";}

	$sql="select * from npd_ps";
	$sql .=$where;
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

	$sql .=" order  by id_npd_ps desc LIMIT $Page_Start , $Per_Page";
	$res = mysql_query($sql) or die ('Error '.$sql);
	?>
	<div class="row">
		<div class="background">
		<form name="frmMain" action="del-ps-feedback.php" method="post">
		<table style="border: 0; width: 100%;" cellpadding="0" cellspacing="0">
			<tr>
				<td class="b-bottom" style="text-align:left;"><div class="large-4 columns"><a href="../npd.php"><?php echo '< Innovation'?></a></div></td>
				<td class="b-bottom"><div class="large-4 columns"><h4>Product Detail Feedback</h4></div></td>
				<td class="b-bottom" style="text-align:right;">
					<div class="large-4 columns">
						<?php if($rs_account['id_account']==23){?>
						<input type="button" value="Create" class="btn-new" onclick="window.location.href='ac-ps-feedback.php?id_u=New'" title="New">
						<input type="submit" name="del" value="Delete" class="btn-trash" OnClick="return chkdel();" title="Delete">
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
						<?php }?>
					</div>
				</td>
			</tr>
			<tr>
				<td style="background: #fff;" colspan="3">
					<div class="large-4 columns">
						<table style="border: 1 solid #eee; width: 100%;" cellpadding="0" cellspacing="0">
							<tr class="top">
								<td width="2%"class="center"><input type="checkbox" name="ck_all" id="ck_all" value="Y" onclick="ckall(this);" style="margin:0;padding:0;"></td>
								<td width="3%">No.</td>
								<td width="12%">Doc no.</td>
								<td width="15%">Product name</td>
								<td width="10%">Customer</td>	
								<?php if($rs_account['id_department']!=1){?><td width="10%" class="center">Project Manager</td><?php }?>	
								<td width="5%" class="center">Feedback</td>
								<td width="10%" class="center">Approve by</td>
								<td width="8%" class="center">Approve Date</td>
								<td width="8%" class="center">Receive by</td>
								<td width="8%" class="center">Receive Date</td>
								<?php if($rs_account['id_department']!=1){?><td width="5%" class="center">Rev.</td><?php }?>
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
								<td class="center"><input type="checkbox" name="ck_del[]" id="ck_del<?php echo $i?>" value="<?php echo $rs['id_npd_ps'];?>"></td>
								<td><?php echo $i;?></td>
								<td>
									<?php if($rs['roc_rev']==0){$rev_link='';}else{$rev_link='&rev='.$rs['roc_rev'];}?>
									<a href="ac-ps-feedback.php?id_u=<?php echo $rs['id_npd_ps']?><?php echo $rev_link?>">
									<?php 
									echo $rs['doc_no'];
									if($rs['roc_rev']<10){$rev='0';}else{$rev='';}
									if($rs['roc_rev']==0){echo '';}else{echo 'Rev.'.$rs['roc_rev'];};?>
								</td>
								<td>
								<?php
								if($rs['product_name']==''){
									$sql_product="select * from product where id_product='".$rs['id_product']."'";
									$res_product=mysql_query($sql_product) or die ('Error '.$sql_product);
									$rs_product=mysql_fetch_array($res_product);
									echo $rs_product['product_name'];
								}else{echo $rs['product_name'];}	
								?>
								</td>
								<td>
								<?php 
									$sql_company="select * from company where id_company='".$rs['id_customer']."'";
									$res_company=mysql_query($sql_company) or die ('Error '.$sql_company);
									$rs_company=mysql_fetch_array($res_company);
									echo $rs_company['company_name'];?>
								</td>
								<?php 
								if($rs_account['id_department']!=1){?>
								<td class="center">
								<?php
									$sql_account3="select * from account where id_account='".$rs['project_manager']."'";
									$res_account3=mysql_query($sql_account3) or die ('Error '.$sql_account3);
									$rs_account3=mysql_fetch_array($res_account3);
									echo $rs_account3['name'];
								}
								?>
								</td>
								<td class="center"><?php if($rs['feedback']==1){echo 'Approve';}elseif($rs['feedback']==2){echo 'Improve';}?></td>
								<td class="center">
								<?php 
									$sql_account2="select * from account where id_account='".$rs['approve_by']."'";
									$res_account2=mysql_query($sql_account2) or die ('Error '.$sql_account2);
									$rs_account2=mysql_fetch_array($res_account2);
									echo $rs_account2['name'];
								?>
								</td>
								<td class="center"><?php if($rs['approve_date']=='0000-00-00'){echo '';}else{echo $rs['approve_date'];}?></td>
								<td class="center">
								<?php 
									$sql_account2="select * from account where id_account='".$rs['receive_by']."'";
									$res_account2=mysql_query($sql_account2) or die ('Error '.$sql_account2);
									$rs_account2=mysql_fetch_array($res_account2);
									echo $rs_account2['name'];
								?>
								</td>
								<td class="center"><?php if($rs['receive_date']=='0000-00-00'){echo '';}else{echo $rs['receive_date'];}?></td>
								<?php if($rs_account['id_department']!=1){?>
								<td class="center">
									<input type="button" value="Rev." class="button-create" onclick="window.location.href='ac-ps-feedback.php?id_u=<?=$rs['id_npd_ps']?>&rev=<?=$rs['roc_rev']+1?>'">
								</td>
								<?php }?>
							</tr>
							<?php } ?>
							<input type="hidden" name="hdnCount" value="<?=$i;?>">
						</table>
					</div>
				</td>
			</tr>
			<tr>
				<td>
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
