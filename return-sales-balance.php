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

$rs = mysql_query('select company.id_company,company.company_name,account.id_account,account.username from company inner join account on company.create_by=account.id_account and company.company_name like "%'. mysql_real_escape_string($term) .'%"   order by company.company_name asc limit 0,20');

$data = array();
if ( $rs && mysql_num_rows($rs) )
{
    while( $row = mysql_fetch_array($rs, MYSQL_ASSOC) )
    {
        $data[] = array(
            'label' => $row['company_name'],
            'value' => $row['company_name'],
			'id_company' => $row['id_company'],
			'id_pm' => $row['id_account'],
			'pm' => $row['username'],
			'company_contact' => $row['contact_name'],
			'id_company2' => $row['id_company'],
			'id_pm2' => $row['id_account'],
			'pm2' => $row['username']
        );
    }
}

echo json_encode($data);
flush();

