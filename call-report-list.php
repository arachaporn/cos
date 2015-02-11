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
	/*if($rs_account['role_user']==3){
		//$where=" where id_account='".$rs_account['id_account']."'";
	}
	else{// $where="";}*/
		
	$sql="select * from call_report inner join call_report_date";
	$sql .=" on call_report.create_by='".$_GET['create_by']."'";
	$sql .=" and call_report_date.date_visited='".$_GET['date']."'";
	$sql .=" and call_report.id_call_report=call_report_date.id_call_report";
	//$sql.= $where;
	
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

	$sql .=" order by call_report.id_call_report desc LIMIT $Page_Start , $Per_Page";
	$res = mysql_query($sql) or die ('Error '.$sql);
	?>
	<div class="row">
		<div class="background">
		<form name="frmMain" action="del-call-report.php" method="post">
		<table style="border: 0; width: 100%;" cellpadding="0" cellspacing="0">
			<tr>				
				<td class="b-bottom"><div class="large-4 columns"><h4>Call report >> <?php echo $_GET['date'].'/'.$_GET['month'].'/'.$_GET['year']?></h4></div></td>
				<td class="b-bottom" style="text-align:right;">
					<div class="large-4 columns">
						<input type="button" value="Create" class="btn-new" onclick="window.location.href='ac-call-report.php?id_u=New&create_by=<?=$_GET['create_by']?>'" title="New">
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
			<tr>
				<td style="background: #fff;" colspan="3">
					<div class="large-4 columns">
						<table style="border: 1 solid #eee; width: 100%;" cellpadding="0" cellspacing="0">
							<tr class="top">
								<td width="2%"class="center"><input type="checkbox" name="ck_all" id="ck_all" value="Y" onclick="ckall(this);" style="margin:0;padding:0;"></td>
								<td width="3%">No.</td>
								<td width="10%">Title</td>
								<td width="10%">Company name</td>
								<td width="10%" class="center">Project name</td>
								<td width="10%" class="center">Date visited</td>
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
								<input type="hidden" name="create_by" value="<?php echo $_GET['create_by']?>">
								<td class="center"><input type="checkbox" name="ck_del[]" id="ck_del<?php echo $i?>" value="<?php echo $rs['id_call_report'];?>"></td>
								<td><?php echo $i;?></td>
								<td><a href="ac-call-report.php?id_p=<?=$rs['id_call_report']?>&create_by=<?=$rs['create_by']?>&date=<?=$_GET['date']?>&month=<?=$_GET['month']?>&year=<?=$_GET['year']?>"><?php echo $rs['title_call_report']?></td>
								<td>
								<?php
								if($rs['type_customer']==1){$red_style='<span style="color:red;">';$red_style_end='</span>';}
								else{$red_style='';$red_style_end='';}
								if($rs['id_company'] != 0){
									$sql_company="select * from company where id_company='".$rs['id_company']."'";
									$res_company=mysql_query($sql_company) or die ('Error '.$sql_company);
									$rs_company=mysql_fetch_array($res_company);
									echo $red_style.$rs_company['company_name'].$red_style_end;
								}else{echo $red_style.$rs['company_name'].$red_style_end;}
								?>
								</td>
								<td class="center">
								<?php 
								$sql_product="select * from product where id_product='".$rs['id_product']."'";
								$res_product=mysql_query($sql_product) or die ('Error '.$sql_product);
								$rs_product=mysql_fetch_array($res_product);
								echo $rs_product['product_name'];
								?></td>
								<td class="center">
								<?php 
								$sql_date="select * from call_report_date where id_call_report='".$rs['id_call_report']."'";
								$res_date=mysql_query($sql_date) or die ('Error '.$sql_date);
								$rs_date=mysql_fetch_array($res_date);
								echo$title=$rs_date['date_visited'].'/'.$rs_date['month_visited'].'/'.$rs_date['year_visited'];		
								?>
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
						$pages->url_next = $_SERVER["PHP_SELF"]."?create_by=".$_GET['create_by']."&date=".$_GET['date']."&QueryString=value&Page=";

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
