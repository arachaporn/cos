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

$rs = mysql_query('select * from pre_quotation_detail where title_pre_quotation like "%'. mysql_real_escape_string($term) .'%" order by title_pre_quotation asc limit 0,20');

$data = array();
if ( $rs && mysql_num_rows($rs) )
{
    while( $row = mysql_fetch_array($rs, MYSQL_ASSOC) )
    {
        $data[] = array(
            'label' => $row['title_pre_quotation'],
            'value' => $row['title_pre_quotation'],
			'id_pre_quotation' => $row['id_pre_quotation'],
			'id_product_appearance' => $row['id_product_appearance'],
			'id_type_product' => $row['id_type_product'],
			'num_pre_quotation' => $row['num_pre_quotation'],
			'unit_pre_quotation' => $row['unit_pre_quotation'],
			'pre_quotation_price' => $row['pre_quotation_price'],
			'sum_quotation_price' => $row['num_pre_quotation']*$row['pre_quotation_price'],
			'id_pre_quotation2' => $row['id_pre_quotation'],
			'id_type_product2' => $row['id_type_product'],
			'num_pre_quotation2' => $row['num_pre_quotation'],
			'unit_pre_quotation2' => $row['unit_pre_quotation'],
			'pre_quotation_price2' => $row['pre_quotation_price'],
			'sum_quotation_price2' => $row['num_pre_quotation']*$row['pre_quotation_price']
        );
    }
}

echo json_encode($data);
flush();

