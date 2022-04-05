<?php
$origin=isset($_SERVER['HTTP_ORIGIN'])?$_SERVER['HTTP_ORIGIN']:$_SERVER['HTTP_HOST'];
header('Access-Control-Allow-Origin: '.$origin);
header('Access-Control-Allow-Methods: POST, OPTIONS, GET, PUT');
header('Access-Control-Allow-Credentials: true');

include_once 'class.curl.php';

$Object = new ClassCurl();
$EXPECTED = array('token','Appt_UniqueID','Completeby_UniqueID','Facility_UniqueID','Notes',
    'Orderby_UniqueID','Patient_UniqueID','Results','Signature_URL','Status','CompletedDate','UniqueID');
/*
$EXPECTED = array('token','Appoinment','CompletedBy','Facility','Notes',
    'OrderedBy','Patient','Results','Signature_URL','Status');
*/

foreach ($EXPECTED AS $key) {
    if (!empty($_POST[$key])){
        ${$key} = $Object->protect($_POST[$key]);
    } else {
        ${$key} = NULL;
    }
}

echo json_encode("aa");
//http_response_code($code);




