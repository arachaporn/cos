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

$rs = mysql_query('select roc.id_roc,roc.roc_code,id_type_product,product.id_product,product.product_name from roc inner join product on roc.roc_code like "%'. mysql_real_escape_string($term) .'%" and roc.id_product=product.id_product order by roc.id_roc desc limit 0,20');

$data = array();
if ( $rs && mysql_num_rows($rs) )
{
    while( $row = mysql_fetch_array($rs, MYSQL_ASSOC) )
    {
        $data[] = array(
            'label' => $row['roc_code'],
            'value' => $row['roc_code'],
			'id_roc' => $row['id_roc'],
			'id_type_product' => $row['id_type_product'],
			'id_product' => $row['id_product'],
			'product_name' => $row['product_name']
        );
    }
}

echo json_encode($data);
flush();

