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


$sql_dep="select * from department where id_department='".$rs_account['id_department']."'";
$res_dep=mysql_query($sql_dep) or die ('Error dep : '.$sql_dep);
$rs_dep=mysql_fetch_array($res_dep);

$id=$_REQUEST['id_p'];
$date_visited=$_REQUEST['date_visited'];
$month_visited=$_REQUEST['month_visited'];
$year_visited=$_REQUEST['year_visited'];

$sql="select * from sm_sales_balance where po_no='".$id."'";
$sql .=" and date_visited='".$date_visited."'";
$sql .=" and month_visited='".$month_visited."'";
$sql .=" and year_visited='".$year_visited."'";
$res1=mysql_query($sql) or die ('Error sales balance : '.$sql);
$rs1=mysql_fetch_array($res1);

$sql_account1 = "SELECT * FROM account WHERE id_account = '".$rs1["project_manager"]."'  ";
$res_account1 = mysql_query($sql_account1) or die ('Error '.$sql_account1);
$rs_account1 = mysql_fetch_array($res_account1);

$sql_customer="select * from company where id_company='".$rs1['id_customer']."'";
$res_customer=mysql_query($sql_customer) or die ('Error customer : '.$sql_customer);
$rs_customer=mysql_fetch_array($res_customer);	
$i=0;

$cc="pichchakarn.cdip@gmail.com";
/*$account1="thariya.p@cdipthailand.com";
$account2="arachaporn.s@cdipthailand.com";*/
$MailTo = "wilailuk.a@cdipthailand.com,amnat.p@cdipthailand.com,oranit.s@cdipthailand.com";
$MailTo .= ",ketsirin.w@cdipthailand.com,pornpen.j@cdipthailand.com,ornanong.p@cdipthailand.com";
$MailTo .=",hansa.j@cdipthailand.com,supansa.h@cdipthailand.com";
$MailFrom = $rs_account['email'];
$MailSubject = "COM_".$rs_customer['company_name'].' : PO.No. '.$rs1['po_no'];
$MailMessage = "Dear All,";
$MailMessage .= "<br><br>";
$MailMessage .= "Order form : ".$rs_customer['company_name'];
$MailMessage .= "<br>";
$MailMessage .= "PO.No. : ".$rs1['po_no'];
$MailMessage .= "<br>";

/*$MailTo1 = "athit.i@cdipthailand.com,kanyarat.p@cdipthailand.com,padiphut.p@cdipthailand.com";
$MailTo1 .= ",paridchaya.s@cdipthailand.com,prapha.p@cdipthailand.com,sasipan.n@cdipthailand.com";*/
$MailTo1 = $rs_account1['email'];
$MailTo1 .= ",chawannas.w@cdipthailand.com,namthip.t@cdipthailand.com,jureewan.r@cdipthailand.com";
$MailTo1 .= ",pichchakarn.p@cdipthailand.com,sureeporn.m@cdipthailand.com,supansa.h@cdipthailand.com";
$MailFrom1 = $rs_account['email'];
$MailSubject1 = "COM_".$rs_customer['company_name'].' : PO.No. '.$rs1['po_no'];
$MailMessage1 = "Dear All,";
$MailMessage1 .= "<br><br>";
$MailMessage1 .= "Order form : ".$rs_customer['company_name'];
$MailMessage1 .= "<br>";
$MailMessage1 .= "PO.No. : ".$rs1['po_no'];
$MailMessage1 .= "<br>";


$res=mysql_query($sql) or die ('Error sales balance : '.$sql);
while($rs=mysql_fetch_array($res)){
	$i++;	
	$MailMessage .= $i.'&nbsp;.';
	$MailMessage .= $rs['pre_production'].'&nbsp;Qty : '.number_format($rs['quantities'],2).'&nbsp;'.$rs['product_unit'].'&nbsp;';
	$MailMessage .= "<br>";

	$MailMessage1 .= $i.'&nbsp;.';
	$MailMessage1 .= $rs['pre_production'].'&nbsp;Qty : '.number_format($rs['quantities'],2).'&nbsp;'.$rs['product_unit'].'&nbsp;';
	$MailMessage1 .= '&nbsp;'.number_format($rs['total'],2).'&nbsp;Baht';
	$MailMessage1 .= "<br>";
}
$MailMessage .= "<br><br>";
$MailMessage .= "Best Regards,";
$MailMessage .= "<br>";
$MailMessage .= $rs_account['name'];
$MailMessage .= "<br>";
$MailMessage .= $rs_dep['title'];

$MailMessage1 .= "<br><br>";
$MailMessage1 .= "Best Regards,";
$MailMessage1 .= "<br>";
$MailMessage1 .= $rs_account['name'];
$MailMessage1 .= "<br>";
$MailMessage1 .= $rs_dep['title'];

/*$MailMessage .= "Ms. Supansa Hongkanak (Goi)";
$MailMessage .= "<br>";
$MailMessage .= "(Sales Admin Officer)";
$MailMessage .= "<br>";
$MailMessage .= "CDIP (Thailand).Co.,Ltd.";
$MailMessage .= "<br>";
$MailMessage .= "Tel: +662-5647200 #5205";
$MailMessage .= "<br>";
$MailMessage .= "Fax: +662-5647745";
$MailMessage .= "<br>";
$MailMessage .= "Mobile: 091-1203373";
$MailMessage .= "<br>";
$MailMessage .= "Email: supansa.h@cdipthailand.com";
$MailMessage .= "<br>";
$MailMessage .= "www.cdipthailand.com";*/

$Headers = "MIME-Version: 1.0\r\n" ;
$Headers .= "Content-type: text/html; charset=utf-8\r\n"; 
$Headers .= "From:".$MailFrom. "<".$MailFrom.">\r\n";
$Headers .= $cc;
//$Headers .= "Reply-to: ".$MailFrom." <".$MailFrom.">\r\n" ;
$Headers .= "X-Priority: 3\r\n" ;

$flgSend = @mail($MailTo,$MailSubject,$MailMessage,$Headers,"-f  test@cdipthailand.com ");
$flgSend1 = @mail($MailTo1,$MailSubject1,$MailMessage1,$Headers,"-f  test@cdipthailand.com ");
if(($flgSend) && ($flgSend1)){
?>
	<script>
		window.alert('Send mail complete');
		window.location.href='ac-sales-balance.php?date_visited=<?=$date_visited?>&month_visited=<?=$month_visited?>&year_visited=<?=$year_visited?>';
	</script>
<?php }else{?>
	<script>
		window.alert('Send mail False');
		history.back();
	</script>
<?php }