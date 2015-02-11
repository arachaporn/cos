<?php
header("Content-type:text/html; charset=UTF-8");        
header("Cache-Control: no-store, no-cache, must-revalidate");       
header("Cache-Control: post-check=0, pre-check=0", false);       


if ( !isset($_REQUEST['term']) )
    exit;

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

$term=urldecode($_REQUEST['term']);

$rs = mysql_query('select * from jsp_rm_price where jsp_rm_name like "%'. mysql_real_escape_string($term) .'%" and factory_name like "cox" order by id_jsp_rm desc limit 0,20');

$data = array();
if ( $rs && mysql_num_rows($rs) )
{
    while( $row = mysql_fetch_array($rs, MYSQL_ASSOC) )
    {
        $data[] = array(
            'label' => $row['jsp_rm_name'].' - '.$row['factory_name'],
            'value' => $row['jsp_rm_name'],
			'id_rm5' => $row['id_jsp_rm'],
			//'npd_supplier' => $row['jsp_rm_price'],
			'rm_price5' => $row['jsp_rm_price'],
			'id_rm52' => $row['id_jsp_rm'],
			//'npd_supplier2' => $row['npd_supplier'],
			'rm_price52' => $row['jsp_rm_price']
		);
    }
}

echo json_encode($data);
flush();

