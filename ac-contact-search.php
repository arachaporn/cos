<?php
header("Content-type:text/html; charset=UTF-8");        
header("Cache-Control: no-store, no-cache, must-revalidate");       
header("Cache-Control: post-check=0, pre-check=0", false);       
// เชื่อมต่อฐานข้อมูล
include("connect/connect.php");
mysql_query("SET NAMES UTF8");
mysql_query("SET character_set_results=UTF8");
mysql_query("SET character_set_client=UTF8");
mysql_query("SET character_set_connection=UTF8");
mb_internal_encoding('UTF-8');
mb_http_output('UTF-8');
mb_http_input('UTF-8');
mb_language('uni');
mb_regex_encoding('UTF-8');
ob_start('mb_output_handler');
setlocale(LC_ALL, 'th_TH');
 
$q = urldecode($_GET["q"]);
//$q= iconv('utf-8', 'tis-620', $_GET['test']);
$pagesize = 50; // จำนวนรายการที่ต้องการแสดง
$table_db="company_contact"; // ตารางที่ต้องการค้นหา
$find_field="contact_name"; // ฟิลที่ต้องการค้นหา
$sql = "select * from $table_db  where locate('$q', $find_field) > 0 group by locate('$q', $find_field) order by locate('$q', $find_field), $find_field limit $pagesize";
$results = mysql_query($sql);
while ($row = mysql_fetch_array( $results )) {
	$id = $row["id_contact"]; // ฟิลที่ต้องการส่งค่ากลับ
	$name =$row["contact_name"]; // ฟิลที่ต้องการแสดงค่า
	// ป้องกันเครื่องหมาย '
	$name = str_replace("'", "'", $name);
	// กำหนดตัวหนาให้กับคำที่มีการพิมพ์
	$display_name = preg_replace("/(" . $q . ")/i", "<b>$1</b>", $name);
	echo "<li onselect=\"this.setText('$name').setValue('$id');\">$display_name</li>";
}
mysql_close();
?>