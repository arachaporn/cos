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

$rs = mysql_query('select * from company_contact inner join company where company_contact.contact_name like "%'.mysql_real_escape_string($term).'%" group by company_contact.contact_name order by company_contact.contact_name asc limit 0,20');

$data = array();
if ( $rs && mysql_num_rows($rs) )
{
    while( $row = mysql_fetch_array($rs, MYSQL_ASSOC) )
    {
        $data[] = array(
            'label' => $row['contact_name'],
            'value' => $row['contact_name'],
			'id_contact' => $row['id_contact'],
			'department' => $row['department'],
			'telephone' => $row['tel'],
			'email' => $row['email']
        );
    }
}
echo json_encode($data);
flush();

