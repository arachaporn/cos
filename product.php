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
<script type="text/javascript" src="http://code.jquery.com/jquery-1.7.2.min.js"></script>
</head>
<body>
	<?php include("menu.php");?>
	<div class="row">
		<div class="background">
			<form name="frmSearch" method="get" action="<?=$_SERVER['SCRIPT_NAME'];?>">
				<table style="border: 0; width: 100%;" cellpadding="0" cellspacing="0">
					<tr>
						<td class="b-bottom" style="text-align:left;">
							<div class="large-4 columns"><a href="index2.php"><?php echo '< Home'?></a></div>	
						</td>
						<td class="b-bottom"><div class="large-4 columns"><h4>Product</h4></div></td>
						<td class="b-bottom" style="text-align:right;">
							<div class="large-4 columns">
								<input type="button" value="Create" class="btn-new" onclick="window.location.href='ac-product.php?id_u=New'" title="New">
								<input type="botton" name="del" value="Delete" class="btn-trash" OnClick="return chkdel();" title="Delete">
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
									<td style="text-align:left; width: 10%; vertical-align:top;"><div class="large-4 columns">Search product</div>
									<td style="width: 20%;"><input name="txtKeyword" type="text" id="txtKeyword" value="<?=$_GET["txtKeyword"];?>"></td>
									<td style="vertical-align:top;"><input type="submit" value="Search"></td>
								</tr>
							</table>
						</td>						
					</tr>
				<tr>
					<td colspan="3">
						<?php
						if($_GET["txtKeyword"] != ""){
							$strSearch=$_GET["txtKeyword"];

							$sql= "SELECT * FROM product inner join product_detail";
							$sql .=" on product.product_name LIKE '%".$strSearch."%'";
							$sql .=" and product.id_product=product_detail.id_product";
							$res= mysql_query($sql) or die ('Error '.$sql);
							
							include("paginator.php");	
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

							$sql .=" order by product_detail.id_product desc LIMIT $Page_Start , $Per_Page";
							$res = mysql_query($sql) or die ('Error '.$sql);
						?>
						<table style="border:1 solid #eee; width:100%;" cellpadding="0" cellspacing="0">
							<tr class="top">
								<td width="2%"class="center"><input type="checkbox" name="ck_all" id="ck_all" value="Y" onclick="ckall(this);" style="margin:0;padding:0;"></td>
								<td class="center" width="7%">Item</td>
								<td class="center" width="20%">Customer</td>
								<td class="center" width="20%">Product</td>
								<td class="center" width="30%">Image</td>
								<td class="center" width="10%">Remark</td>
							</tr>
							<tr>
								<?
								$i=0;
								while($rs = mysql_fetch_array($res))
								{
									$i++;
									$sql_detail="select * from product_detail where id_product='".$rs['id_product']."'";
									$res_detail=mysql_query($sql_detail) or die ('Error '.$sql_detail);
									$rs_detail=mysql_fetch_array($res_detail);
								?>
								  <tr>
									<td class="center" style="vertical-align: top;"><input type="checkbox" name="ck_del[]" id="ck_del<?php echo $i?>" value="<?php echo $rs_detail['id_product_detail'];?>"></td>
									<td class="center" style="vertical-align: top;"><?php echo $i;?></td>
									<td style="vertical-align: top;"><?php if($rs_account['role_user']!=3){?><a href="ac-product.php?id_u=<?php echo $rs['id_product_detail']?>">
										<?php 
										$sql_company="select * from company where id_company='".$rs['id_company']."'";
										$res_company=mysql_query($sql_company) or die ('Error '.$sql_company);
										$rs_company=mysql_fetch_array($res_company);
										echo $rs_company['company_name'];
										?></a><?php }else{?>
										<?php 
										$sql_company="select * from company where id_company='".$rs['id_company']."'";
										$res_company=mysql_query($sql_company) or die ('Error '.$sql_company);
										$rs_company=mysql_fetch_array($res_company);
										echo $rs_company['company_name'];
										?>
										<?php }?>
									</td>
									<td style="vertical-align: top;">
										<?php 
										$sql_product="select * from product where id_product='".$rs['id_product']."'";
										$res_product=mysql_query($sql_product) or die ('Error '.$sql_product);
										$rs_product=mysql_fetch_array($res_product);
										echo $rs_product['product_name'];
										?>									
									</td>
									<!--light box -->
									<!--<script type="text/javascript" src="js/fancybox/scripts/jquery-1.4.3.min.js"></script>-->
									<script type="text/javascript" src="js/fancybox/fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
									<script type="text/javascript" src="js/fancybox/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
									<link rel="stylesheet" type="text/css" href="js/fancybox/fancybox/jquery.fancybox-1.3.4.css" media="screen" />
									<!--<link rel="stylesheet" href="js/style/style.css" />-->
									<script type="text/javascript">
										$(document).ready(function($) {
											$(".various").fancybox({
												maxWidth	: '100%',
												maxHeight	: '100%',
												fitToView	: false,
												width		: '80%',
												height		: '100%',
												autoSize	: false,
												closeClick	: false,
												hideOnOverlayClick	: false,
												openEffect	: 'none',
												closeEffect	: 'none',
												type		: 'iframe',
												/*onClosed	:	function() {  // close refresh page
													parent.location.reload(true); 
												}*/				 
											});

											/* This is basic - uses default settings */											
											$("a#single_image").fancybox();
															
											/* Using custom settings */											
											$("a#inline").fancybox({
												'hideOnContentClick': true
											});

											/* Apply fancybox to multiple items */											
											$("a.group").fancybox({
												'transitionIn'	:	'elastic',
												'transitionOut'	:	'elastic',
												'speedIn'		:	600, 
												'speedOut'		:	200, 
												'overlayShow'	:	false
											});
										});
									</script>
									<td class="center" style="vertical-align: top;">
									<?php 									
									$sql_img="select * from img_product where id_product='".$rs['id_product']."'";
									$res_img=mysql_query($sql_img) or die ('Error '.$sql_img);
									$rs_img=mysql_fetch_array($res_img);
									if($rs_img){
										if($rs['id_product']==134){
											if($rs['product_size']=='1x1x10 \'s'){
												$sql_img2="select * from img_product where id_img_product='24'";
												$res_img2=mysql_query($sql_img2) or die ('Error '.$sql_img2);
												$rs_img2=mysql_fetch_array($res_img2);
											?>
												<a id="single_image" href='images_product/<?php echo $rs_img2['img_title']?>'><img src="images_product/thumb/<?php echo $rs_img2['thum_img']?>"></a>
											<?php
											}elseif($rs['product_size']=='1x1x30 \'s'){
												$sql_img2="select * from img_product where id_img_product='10'";
												$res_img2=mysql_query($sql_img2) or die ('Error '.$sql_img2);
												$rs_img2=mysql_fetch_array($res_img2);
											?>
												<a id="single_image" href='images_product/<?php echo $rs_img2['img_title']?>'><img src="images_product/thumb/<?php echo $rs_img2['thum_img']?>"></a>
											<?php }elseif($rs['product_size']=='1x1x60 \'s'){
												$sql_img2="select * from img_product where id_img_product='40'";
												$res_img2=mysql_query($sql_img2) or die ('Error '.$sql_img2);
												$rs_img2=mysql_fetch_array($res_img2);
											?>
												<a id="single_image" href='images_product/<?php echo $rs_img2['img_title']?>'><img src="images_product/thumb/<?php echo $rs_img2['thum_img']?>"></a>
											<?php }												
											}//end product BIO-REZIPE KOREA GINSENG 
											else
											if($rs['id_product']==80){
												if($rs['product_size']=='1*30*30g'){echo 'aa';
													$sql_img2="select * from img_product where id_img_product='48'";
													$res_img2=mysql_query($sql_img2) or die ('Error '.$sql_img2);
													$rs_img2=mysql_fetch_array($res_img2);
												?>
													<a id="single_image" href='images_product/<?php echo $rs_img2['img_title']?>'><img src="images_product/thumb/<?php echo $rs_img2['thum_img']?>"></a>
												<?php
												}elseif($rs['product_size']=='1*10*30g'){		echo 'dd';										
												?>
													<a id="single_image" href='images_product/<?php echo $rs_img['img_title']?>'><img src="images_product/thumb/<?php echo $rs_img['thum_img']?>"></a>
												<?php }												
											}//end product Gluta Matrix
											else{
										?>
											<a id="single_image" href='images_product/<?php echo $rs_img['img_title']?>'><img src="images_product/thumb/<?php echo $rs_img['thum_img']?>"></a>
										<?php }
										}else{?>
											<img src="images_product/no_image.png">
										<?php }?>
									</td>
									<td class="center" style="vertical-align: top;"><a class='various' data-fancybox-type='iframe' href='product-detail.php?id_u=<?=$rs['id_product_detail']?>'>Detail</a></td>
								  </tr>
								<?php }?>
							</table>
							<div class="large-4 columns">Total <?= $num_row;?> Record 
							<?php
							$pages = new Paginator;
							$pages->items_total = $num_row;
							$pages->mid_range = 10;
							$pages->current_page = $Page;
							$pages->default_ipp = $Per_Page;
							$pages->url_next = $_SERVER["PHP_SELF"]."?txtKeyword=$_GET[txtKeyword]&Page=";

							$pages->paginate();
							echo $pages->display_pages()
							?>	
							</div>
						<?php }else{
							$sql="select * from product_detail";							
							$res=mysql_query($sql) or die('Error '.$sql);
							include("paginator.php");	
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

							$sql .=" order by id_product desc LIMIT $Page_Start , $Per_Page";
							$res = mysql_query($sql) or die ('Error '.$sql);
						?>
						<table style="border:1 solid #eee; width:100%;" cellpadding="0" cellspacing="0">
							<tr class="top">
								<td width="2%"class="center"><input type="checkbox" name="ck_all" id="ck_all" value="Y" onclick="ckall(this);" style="margin:0;padding:0;"></td>
								<td class="center" width="7%">Item</td>
								<td class="center" width="20%">Customer</td>
								<td class="center" width="20%">Product</td>
								<td class="center" width="30%">Image</td>
								<td class="center" width="10%">Remark</td>
							</tr>
							<tr>
								<?
								$i=0;
								while($rs = mysql_fetch_array($res))
								{
									$i++;
								?>
								  <tr>
									<td class="center" style="vertical-align: top;"><input type="checkbox" name="ck_del[]" id="ck_del<?php echo $i?>" value="<?php echo $rs['id_product_detail'];?>"></td>
									<td class="center" style="vertical-align: top;"><?php echo $i;?></td>
									<td style="vertical-align: top;"><?php if($rs_account['role_user']!=3){?><a href="ac-product.php?id_u=<?php echo $rs['id_product_detail']?>">
										<?php 
										$sql_company="select * from company where id_company='".$rs['id_company']."'";
										$res_company=mysql_query($sql_company) or die ('Error '.$sql_company);
										$rs_company=mysql_fetch_array($res_company);
										echo $rs_company['company_name'];
										?></a><?php }else{?>
										<?php 
										$sql_company="select * from company where id_company='".$rs['id_company']."'";
										$res_company=mysql_query($sql_company) or die ('Error '.$sql_company);
										$rs_company=mysql_fetch_array($res_company);
										echo $rs_company['company_name'];
										?>
										<?php }?>
									</td>
									<td style="vertical-align: top;">
										<?php 
										$sql_product="select * from product where id_product='".$rs['id_product']."'";
										$res_product=mysql_query($sql_product) or die ('Error '.$sql_product);
										$rs_product=mysql_fetch_array($res_product);
										echo $rs_product['product_name'];
										?>									
									</td>
									<!--light box -->
									<!--<script type="text/javascript" src="js/fancybox/scripts/jquery-1.4.3.min.js"></script>-->
									<script type="text/javascript" src="js/fancybox/fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
									<script type="text/javascript" src="js/fancybox/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
									<link rel="stylesheet" type="text/css" href="js/fancybox/fancybox/jquery.fancybox-1.3.4.css" media="screen" />
									<!--<link rel="stylesheet" href="js/style/style.css" />-->
									<script type="text/javascript">
										$(document).ready(function($) {
											$(".various").fancybox({
												maxWidth	: '100%',
												maxHeight	: '100%',
												fitToView	: false,
												width		: '80%',
												height		: '100%',
												autoSize	: false,
												closeClick	: false,
												hideOnOverlayClick	: false,
												openEffect	: 'none',
												closeEffect	: 'none',
												type		: 'iframe',
												/*onClosed	:	function() {  // close refresh page
													parent.location.reload(true); 
												}*/				 
											});

											/* This is basic - uses default settings */											
											$("a#single_image").fancybox();
															
											/* Using custom settings */											
											$("a#inline").fancybox({
												'hideOnContentClick': true
											});

											/* Apply fancybox to multiple items */											
											$("a.group").fancybox({
												'transitionIn'	:	'elastic',
												'transitionOut'	:	'elastic',
												'speedIn'		:	600, 
												'speedOut'		:	200, 
												'overlayShow'	:	false
											});
										});
									</script>
									<td class="center" style="vertical-align: top;">
									<?php 									
									$sql_img="select * from img_product where id_product='".$rs['id_product']."'";
									$res_img=mysql_query($sql_img) or die ('Error '.$sql_img);
									$rs_img=mysql_fetch_array($res_img);
									if($rs_img){
										if($rs['id_product']==134){
											if($rs['product_size']=='1x1x10 \'s'){
												$sql_img2="select * from img_product where id_img_product='24'";
												$res_img2=mysql_query($sql_img2) or die ('Error '.$sql_img2);
												$rs_img2=mysql_fetch_array($res_img2);
											?>
												<a id="single_image" href='images_product/<?php echo $rs_img2['img_title']?>'><img src="images_product/thumb/<?php echo $rs_img2['thum_img']?>"></a>
											<?php
											}elseif($rs['product_size']=='1x1x30 \'s'){
												$sql_img2="select * from img_product where id_img_product='10'";
												$res_img2=mysql_query($sql_img2) or die ('Error '.$sql_img2);
												$rs_img2=mysql_fetch_array($res_img2);
											?>
												<a id="single_image" href='images_product/<?php echo $rs_img2['img_title']?>'><img src="images_product/thumb/<?php echo $rs_img2['thum_img']?>"></a>
											<?php }elseif($rs['product_size']=='1x1x60 \'s'){
												$sql_img2="select * from img_product where id_img_product='40'";
												$res_img2=mysql_query($sql_img2) or die ('Error '.$sql_img2);
												$rs_img2=mysql_fetch_array($res_img2);
											?>
												<a id="single_image" href='images_product/<?php echo $rs_img2['img_title']?>'><img src="images_product/thumb/<?php echo $rs_img2['thum_img']?>"></a>
											<?php }												
											}//end product BIO-REZIPE KOREA GINSENG 
											else
											if($rs['id_product']==80){
												if($rs['product_size']=='1*30*30g'){
													$sql_img2="select * from img_product where id_img_product='48'";
													$res_img2=mysql_query($sql_img2) or die ('Error '.$sql_img2);
													$rs_img2=mysql_fetch_array($res_img2);
												?>
													<a id="single_image" href='images_product/<?php echo $rs_img2['img_title']?>'><img src="images_product/thumb/<?php echo $rs_img2['thum_img']?>"></a>
												<?php
												}else{												
												?>
													<img src="images_product/no_image.png">
												<?php }												
											}//end product Gluta Matrix
											else{
										?>
											<a id="single_image" href='images_product/<?php echo $rs_img['img_title']?>'><img src="images_product/thumb/<?php echo $rs_img['thum_img']?>"></a>
										<?php }
										}else{?>
											<img src="images_product/no_image.png">
										<?php }?>
									</td>
									<td class="center" style="vertical-align: top;"><a class='various' data-fancybox-type='iframe' href='product-detail.php?id_u=<?=$rs['id_product_detail']?>'>Detail</a></td>
								  </tr>
								<?php }?>
							</table>
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
							<?php }?>
						</td>
					</tr>
				</table>
			</form>
		</div>
	</div>
</body>
</html>