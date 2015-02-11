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

$rs = mysql_query('select * from company inner join company_address on company.company_name like "%'. mysql_real_escape_string($term) .'%" and company_address.id_address=company.id_address inner join company_contact on company.id_company=company_contact.id_company group by company.id_company order by company_name asc limit 0,20');

$data = array();
if ( $rs && mysql_num_rows($rs) )
{
    while( $row = mysql_fetch_array($rs, MYSQL_ASSOC) )
    {
        $data[] = array(
            'label' => $row['company_name'],
            'value' => $row['company_name'],
			'id_company' => $row['id_company'],
			'id_address' => $row['id_address'],
			'id_contact' => $row['id_contact'],
			'company_address' => $row['address_no'].' '.$row['building'].' '.$row['soi'].' '.$row['road'].' '.$row['sub_district'].' '.$row['district'].' '.$row['province'].' '.$row['postal_code'],
			'company_contact' => $row['contact_name']. ' / '.$row['tel'],
			'com_contact' => $row['contact_name'],
			'company_tel' => $row['company_tel'],
			'company_fax' => $row['company_fax'],
			'mobile' => $row['mobile'],
			'company_email' => $row['email'],
			'com_cate' => $row['id_com_cat']
        );
    }
}

echo json_encode($data);
flush();

   