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
	if($rs_account['role_user']==3){
		$where=" where create_by='".$rs_account['id_account']."'";
	}
	else{ $where="";}
		
	$sql="select * from customer_business";
	$sql.= $where;
	
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

	$sql .=" order by id_customer_bu desc LIMIT $Page_Start , $Per_Page";
	$res = mysql_query($sql) or die ('Error '.$sql);
	?>
	<div class="row">
		<div class="background">
		<form name="frmMain" action="del-customer-bu.php" method="post">
		<table style="border: 0; width: 100%;" cellpadding="0" cellspacing="0">
			<tr>
				<td class="b-bottom" style="text-align:left;width:42%;">
					<div class="large-4 columns"><a href="sales-marketing.php"><?php echo '< New Business Development'?></a></div>	
				</td>
				<td class="b-bottom"><div class="large-4 columns"><h4>Customer Business Evaluation</h4></div></td>
				<td class="b-bottom" style="text-align:right;">
					<div class="large-4 columns">
						<input type="button" value="Create" class="btn-new" onclick="window.location.href='ac-customer-bu.php?id_u=New'" title="New">
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
					</div>
				</td>				
			</tr>
			<?php if($rs_account['role_user'] !=3){?>
			<tr>
				<td colspan="3" style="padding:0;">
					<div class="large-4 columns">
						<div style="float:left;margin:1% 0 0 0;">Form (Ex:01/12/2014) :</div><div style="float:left;margin:0.5%;"><input type="text" name="date_form" value="<?php echo date("d/m/Y");?>"></div>
						<div style="float:left;margin:1% 0 0 0;">To :</div><div style="float:left;margin:0.5%;"><input type="text" name="date_to" value="<?php echo date("d/m/Y");?>"></div>
						<div style="float:left;margin:0.6% 0 0 0;"><input type="submit" id="export_excel" name="export_excel" value="Export Excel"></div>
					</div>
				</td>
			</tr>
			<?php }?>
			<tr>
				<td style="background: #fff;" colspan="3">
					<div class="large-4 columns">
						<table style="border: 1 solid #eee; width: 100%;" cellpadding="0" cellspacing="0">
							<tr class="top">
								<td width="2%"class="center"><input type="checkbox" name="ck_all" id="ck_all" value="Y" onclick="ckall(this);" style="margin:0;padding:0;"></td>
								<td width="3%">No.</td>
								<td width="8%">Name</td>
								<td width="20%">Company name</td>
								<td width="15%">Company Category</td>
								<td width="20%" class="center">Product</td>
								<td width="10%" class="center">Create date</td>
								<?php if(($rs_account['role_user']==1) || ($rs_account['role_user']==2)){?>
								<td width="10%" class="center">Create by</td>
								<?php }?>
							</tr>
							<?php
							$i=0;
							while($rs=mysql_fetch_array($res)){
								$i++;
							?>
							<script language="JavaScript">
								function chkdel(){
									if(confirm('Are you sure you want to delete?')){
										return true; // delete 
									}else{
										return false; // Cancel 
									}
								}
							</script>
							<tr class="row-top">
								<td class="center"><input type="hidden" name="open-customer" value="1">
								<input type="checkbox" name="ck_del[]" id="ck_del<?php echo $i?>" value="<?php echo $rs['id_customer_bu'];?>"></td>
								<td style="line-height: 2em;"><?php echo $i;?></td>
								<td style="line-height: 2em;"><a href="ac-customer-bu.php?id_u=<?php echo $rs['id_customer_bu']?>"><?php echo $rs['name_bu']."&nbsp;&nbsp;".$rs['surname']?></a></td>
								<td style="line-height: 2em;"><?php echo $rs['company_name']?></td>
								<td style="line-height: 2em;">
									<?php
									$sql_contact_name="select * from company_category where id_com_cat='".$rs['id_com_cat']."'";
									$res_contact_name=mysql_query($sql_contact_name) or die('Error '.$sql_contact_name);
									while($rs_contact_name=mysql_fetch_array($res_contact_name)){
										echo $rs_contact_name['title'].'<br>';
									}
									?>
								</td>
								<td style="line-height: 2em;">
									<?php
									switch($rs['id_product_appearance']){
										case 1 : echo 'Tablet';
										break;
										case 2 : echo 'Hard Capsule';
										break;
										case 3 : echo 'Soft Gelatin Capsule';
										break;
										case 4 : echo 'Instant Drink';
										break;
										case 5 : echo 'Edible Gel';
										break;
										case 6 : echo 'Functional Drink';
										break;
										case 7 : echo 'Gummy';
										break;
										case 8 : echo 'Cosmetic';
										break;
										case 9 : echo 'FDA Regulation Service';
										break;
									}
									?>
								</td>
								<td style="line-height: 2em;" class="center"><?php echo $rs['create_date'];?></td>
								<?php if(($rs_account['role_user']==1) || ($rs_account['role_user']==2)){?>
								<td width="10%" class="center">
								<?php
								$sql_account2="select * from account where id_account='".$rs['create_by']."'";
								$res_account2=mysql_query($sql_account2) or die ('Error '.$sql_account2);
								$rs_account2=mysql_fetch_array($res_account2);
								echo $rs_account2['name'];
								?>
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
				<td style="background: #fff;">
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
