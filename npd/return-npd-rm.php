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

$rs = mysql_query('select * from npd_rm_price where npd_rm_name like "%'. mysql_real_escape_string($term) .'%" order by id_npd_rm desc limit 0,20');

$data = array();
if ( $rs && mysql_num_rows($rs) )
{
    while( $row = mysql_fetch_array($rs, MYSQL_ASSOC) )
    {
        $data[] = array(
            'label' => $row['npd_rm_name'],
            'value' => $row['npd_rm_name'],
			'id_npd_rm' => $row['id_npd_rm'],
			'npd_supplier' => $row['npd_supplier'],
			'npd_rm_price' => $row['npd_rm_price'],
			'id_npd_rm2' => $row['id_npd_rm'],
			'npd_supplier2' => $row['npd_supplier'],
			'npd_rm_price2' => $row['npd_rm_price']
		);
    }
}

echo json_encode($data);
flush();

