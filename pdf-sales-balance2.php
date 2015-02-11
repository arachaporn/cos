<?php
ob_start();
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
?>
<?php
	$filName = "sales-balance.csv";
	//$objWrite = fopen("sales-balance.csv", "w");

	//fwrite($objWrite, "\"Date\",\"Customer\",\"PO. No.\",");
	//fwrite($objWrite, "\"Product\",\"Quantities(Unit)\",");
	//fwrite($objWrite, "\"Price per Unit(Baht)\",\"Total\",");
	//fwrite($objWrite, "\"Project Manager\",\"Note\" \n");
	
	//header
	//$sales ='<a href="img/logo.png" style="width:20%;"></a>';
	$sales = "http://www.cdipthailand.com/cos/img/logo.png;";
	$sales .='"Sales balance ';
	$sales .=  date('F', mktime(0, 0, 0, $_GET['month']));
	$sales .= $_GET['year'];
	$sales .= '"';
	$sales .= "\n";
	$sales .='"Date","Customer","PO. No.","Product",';
	$sales .='"Quantities(Unit)","Price per Unit(Baht)",';
	$sales .='"Total","Project Manager","Note"' ."\n";

	include('connect/connect.php');
	$sql = "SELECT * FROM sm_sales_balance where month_visited='".$_GET['month']."'";
	$sql .=" and year_visited='".$_GET['year']."'";
	$res = mysql_query($sql) or die ('Error '.$sql);
	while($rs = mysql_fetch_array($res))
	{
		$sql_customer="select * from company where id_company='".$rs['id_customer']."'";
		$res_customer=mysql_query($sql_customer) or die ('Error '.$sql_customer);
		$rs_customer=mysql_fetch_array($res_customer);

		$sql_product="select * from product where id_product='".$rs['id_product']."'";
		$res_product=mysql_query($sql_product) or die ('Error '.$sql_product);
		$rs_product=mysql_fetch_array($res_product);

		$sql_acc="select * from account where id_account='".$rs['project_manager']."'";
		$res_acc=mysql_query($sql_acc) or die ('Error '.$sql_acc);
		$rs_acc=mysql_fetch_array($res_acc);

		$date_po=$rs['date_visited'].'/'.$rs['month_visited'].'/'.$rs['year_visited'];
		
		$company_name=iconv('UTF-8','TIS-620',$rs_customer['company_name']);
		$product_name=iconv('UTF-8','TIS-620',$rs_product['product_name']);
		$note=iconv('UTF-8','TIS-620',$rs['note']);
		
		//data 
		$sales .= $date_po.','.$company_name.','.$rs['po_no'].',';
		$sales .= $product_name.','.$rs['quantities'].',';
		$sales .= $rs['price_per_baht'].','.$rs['total'].',';
		$sales .= $rs_acc['username'].','.$note;
		$sales .= "\n";

	//	fwrite($objWrite, "\"$date_po\",\"$company_name\",\"$rs[po_no]\",");
	//	fwrite($objWrite, "\"$product_name\",\"$rs[quantities]\",");
		//fwrite($objWrite, "\"$rs[price_per_baht]\",\"$rs[total]\",");
	//	fwrite($objWrite, "\"$rs_acc[username]\",\"$note\" \n");
	}
	//fclose($objWrite);
	//echo "<br>Generate CSV Done.<br><a href=$filName>Download</a>";

header("Content-type: text/csv");
header("Content-Disposition: attachment; filename=sales-balance.csv");
header("Pragma: no-cache");
header("Expires: 0");

echo($sales);
?>
