<?php
header("Content-type:text/html; charset=UTF-8");        
header("Cache-Control: no-store, no-cache, must-revalidate");       
header("Cache-Control: post-check=0, pre-check=0", false);       


if ( !isset($_REQUEST['term']) )
    exit;

include("../connect/connect.php");
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

$rs = mysql_query('select * from account inner join positions on account.name like "%'. mysql_real_escape_string($term) .'%" and account.id_position=positions.id_position order by account.name asc limit 0,10');

$data = array();
if ( $rs && mysql_num_rows($rs) )
{
    while( $row = mysql_fetch_array($rs, MYSQL_ASSOC) )
    {
        $data[] = array(
            'label' => $row['name'],
            'value' => $row['name'],
			'id_account' => $row['id_account'],
            'position' => $row['title'],
			'email' => $row['email'],
			'id_writen' => $row['id_account'],
			'id_account2' => $row['id_account'],
			'id_account3' => $row['id_account']
        );
    }
}

echo json_encode($data);
flush();

