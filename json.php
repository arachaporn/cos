<?php
header("Content-type:text/html; charset=UTF-8");        
header("Cache-Control: no-store, no-cache, must-revalidate");       
header("Cache-Control: post-check=0, pre-check=0", false);       


//if ( !isset($_REQUEST['term']) )
//    exit;

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

//$term=urldecode($_REQUEST['term']);

$rs = mysql_query('select * from meeting where meeting_name like "%test%" order by meeting_name desc limit 0,10');

$data = array();
if ( $rs && mysql_num_rows($rs) )
{
    while( $row = mysql_fetch_array($rs, MYSQL_ASSOC) )
    {
        $data[] = array(
            'label' => $row['meeting_name'],
            'value' => $row['meeting_name'],
			'id_meeting' => $row['id_meeting'],
			'meeting_time' => $row['meeting_time']+1
        );
    }
}

echo json_encode($data);
flush();

$phone_number='0212345678';
$phone_number= preg_replace('/[^0-9]+/', '', $phone_number); //Strip all non number characters
echo preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "($1) $2-$3", $phone_number); //Re Format it