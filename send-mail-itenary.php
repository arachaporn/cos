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


// ชื่อไฟล์ sendmail.php เพื่อรับค่าจาก form

$MailTo = "oemmanager.cdip@gmail.com";
$MailFrom = $rs_account['email'];
$MailSubject = "Itenary Report by".$rs_account['name'];
$MailMessage = $rs_account['name']." have created itenary report on ".date('d/m/Y').".<br>";
$MailMessage .= "Please see link : <a href='http://cdipthailand.com/cos/itenary-report.php'>cdipthailand.com/cos/itenary-report</a>";

$Headers = "MIME-Version: 1.0\r\n" ;
$Headers .= "Content-type: text/html; charset=windows-874\r\n";
 // ส่งข้อความเป็นภาษาไทย ใช้ "windows-874"
$Headers .= "From: ".$MailFrom." <".$MailFrom.">\r\n" ;
$Headers .= "Mailed-by: cdipthailand.com";
$Headers .= "X-Priority: 3\r\n" ;
$Headers .= "X-Mailer: PHP mailer\r\n" ;

if(mail($MailTo, $MailSubject , $MailMessage, $Headers, $MailFrom,"-f  test@cdipthailand.com ")){
?>
	<script>
		window.alert('Send mail complete');
		history.back();
	</script>
<?php }else{?>
	<script>
		window.alert('Send mail False');
		history.back();
	</script>
<?php } ?>