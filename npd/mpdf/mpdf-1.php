<?
include("mpdf.php");
$mpdf=new mPDF('UTF-8');
$html = ' <b>hhh MM aกกกฟฟ</b> rrr ดดดดดดดดด ฟฟฟฟฟฟฟฟ รรรรรรรรร นนนนนนนนน a pl a<br>';
$html .= ' <a href="aaa.php">dfsfef</a><br>';
$mpdf-> SetAutoFont();
$mpdf-> WriteHTML($html);
$mpdf-> Output();
exit;
?>