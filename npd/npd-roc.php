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
	$sql="select * from roc where roc_status='1'";
	
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

	$sql .=" order by id_roc desc LIMIT $Page_Start , $Per_Page";
	$res = mysql_query($sql) or die ('Error '.$sql);
	?>
	<div class="row">
		<div class="background">
		<form name="frmMain" action="dbroc.php" method="post">
		<table style="border: 0; width: 100%;" cellpadding="0" cellspacing="0">
			<tr>
				<td class="b-bottom" style="text-align:left;width:20%;">
					<div class="large-4 columns"><a href="../npd.php"><?php echo '< Innovation'?></a></div>	
				</td>
				<td class="b-bottom" style="text-align:left;padding:0.5% 0 0 25%;"><div class="large-4 columns"><h4>ROC</h4></div></td>
			</tr>
			<tr>
				<td style="background: #fff;" colspan="2">
					<div class="large-4 columns">
						<table style="border: 1 solid #eee; width: 100%;" cellpadding="0" cellspacing="0">
							<tr class="top">
								<td width="3%" class="center">No.</td>
								<td width="15%" class="center">Roc no.</td>
								<td width="20%" class="center">Project name</td>
								<td width="10%" class="center">Category</td>
								<td width="15%" class="center">Company</td>								
								<td width="8%" class="center">PDF</td>
								<td width="5%" class="center">Attach file</td>
								<td width="5%" class="center">Complete</td>
								<td width="5%" class="center">Create by</td>
								<td width="5%" class="center">Create Date</td>
							</tr>
							<?php
							$i=0;
							while($rs=mysql_fetch_array($res)){
								$i++;
							?>
							<tr>
								<td class="center"><?php echo $i?></td>
								<td>
									<?php if($rs['roc_rev']==0){$rev_link='';}else{$rev_link='&rev='.$rs['roc_rev'];}?>
									<a href="npd-roc-detail.php?id_u=<?php echo $rs['id_roc']?>&p=1<?php echo $rev_link?>">
									<?php 
									echo $rs['roc_code'];
									if($rs['roc_rev']<10){$rev='0';}else{$rev='';}
									if($rs['roc_rev']==0){echo '';}else{echo 'Rev.'.$rs['roc_rev'];};?>
									
								</td>
								<td>
								<?php
									$sql_product="select * from product where id_product='".$rs['id_product']."'";
									$res_product=mysql_query($sql_product) or die ('Error '.$sql_product);
									$rs_product=mysql_fetch_array($res_product);
									echo $rs_product['product_name'];
								?>
								</td>
								<td class="center">
								<?php
									$sql_cate="select * from npd_type_product where id_npd_type_product='".$rs['id_type_product']."'";
									$res_cate=mysql_query($sql_cate) or die ('Error '.$sql_cate);
									$rs_cate=mysql_fetch_array($res_cate);
									echo $rs_cate['npd_title'];
								?>
								</td>
								<td class="center">
								<?php 
									$sql_company="select * from company where id_company='".$rs['id_company']."'";
									$res_company=mysql_query($sql_company) or die ('Error '.$sql_company);
									$rs_company=mysql_fetch_array($res_company);
									echo $rs_company['company_name'];?>
								</td>								
								<td class="center"><a href="pdf-roc.php?id_u=<?php echo $rs['id_roc']?>" target=_blank><img src="img/icon-pdf.png" width="28px"></a></td>
								<td>
									<?php 
									$i_file=0;
									$sql_roc_file="select * from roc_file where id_roc='".$rs['id_roc']."'";
									$res_roc_file=mysql_query($sql_roc_file) or die ('Error '.$sql_roc_file);
									while($rs_roc_file=mysql_fetch_array($res_roc_file)){
										$i_file++;
										if($rs_roc_file['roc_file'] !=''){
											echo '<a href="../file/roc_file/'.$rs_roc_file['roc_file'].'" target="_blank">';
											echo $i_file.'.';										
											echo $rs_roc_file['roc_file'];
											echo '</a>';
											echo '<br>';
										}
									}
									?>	
								</td>
								<td class="center">
								<?php 
									$sql_account2="select * from account where id_account='".$rs['create_by']."'";
									$res_account2=mysql_query($sql_account2) or die ('Error '.$sql_account2);
									$rs_account2=mysql_fetch_array($res_account2);
									echo $rs_account2['name'];
								?>
								</td>
								<td class="center">
									<?php
									list($ckyear,$ckmonth,$ckday) = split('[/.-]', $rs['date_roc']); 
									echo $ckstart= $ckday . "/". $ckmonth . "/" .$ckyear;	
									?>
								</td>
								<td class="center">
									<?php if($rs['npd_print_roc']==1){echo 'Completed';}else{?>
									<input type="button" value="On process" class="button-create" onclick="window.location.href='dbroc.php?id_u=<?=$rs['id_roc']?>'">
									<?php }?>
								</td>
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
