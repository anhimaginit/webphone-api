<?php
$origin=isset($_SERVER['HTTP_ORIGIN'])?$_SERVER['HTTP_ORIGIN']:$_SERVER['HTTP_HOST'];
header('Access-Control-Allow-Origin: '.$origin);
header('Access-Control-Allow-Methods: POST, OPTIONS, GET, PUT');
header('Access-Control-Allow-Credentials: true');

include_once './lib/class.company.php';

$Object = new Company();
$EXPECTED = array('token','b_ids');

foreach ($EXPECTED AS $key) {
    if (!empty($_POST[$key])){
        ${$key} = $Object->protect($_POST[$key]);
    } else {
        ${$key} = NULL;
    }
}
//die();
//--- validate
$isAuth = true;//$Object->basicAuth($token);

if(!$isAuth){
    $ret = array('login'=>array(),'ERROR'=>'Authentication is failed');
}else{
    $result = $Object->branch_bids($b_ids);
    $ret = array('response'=>$result,'ERROR'=>'');

}
$Object->close_conn();
echo json_encode($ret);
//http_response_code($code);




