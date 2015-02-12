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

$rs = mysql_query('select * from account where  account.name like "%'. mysql_real_escape_string($term) .'%" order by name asc limit 0,10');

$data = array();
if ( $rs && mysql_num_rows($rs) )
{
    while( $row = mysql_fetch_array($rs, MYSQL_ASSOC) )
    {
        $data[] = array(
            'label' => $row['name'],
            'value' => $row['name'],
			'id_rd_account' => $row['id_account'],
			'rd_account' => $row['name'],
			'id_account2' => $row['id_account'],
			'pm_name' => $row['name']
        );
    }
}

echo json_encode($data);
flush();

