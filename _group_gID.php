<?php
$origin=isset($_SERVER['HTTP_ORIGIN'])?$_SERVER['HTTP_ORIGIN']:$_SERVER['HTTP_HOST'];
header('Access-Control-Allow-Origin: '.$origin);
header('Access-Control-Allow-Methods: POST, OPTIONS, GET, PUT');
header('Access-Control-Allow-Credentials: true');

include_once './lib/class.acl.php';

$Object = new ACL();
$EXPECTED = array('auth','g_id');

foreach ($EXPECTED AS $key) {
    if (!empty($_POST[$key])){
        ${$key} = $Object->protect($_POST[$key]);
    } else {
        ${$key} = NULL;
    }
}
//die();
//--- validate
$isAuth = $Object->basicAuth($auth);

if(!$isAuth){
    $ret = array('response'=>array(),'ERROR'=>'Authentication is failed');
}else{
    $result = $Object->getGrp_gID($g_id);

    $ret = array('response'=>$result,'ERROR'=>'');

}
$Object->close_conn();
echo json_encode($ret);
//http_response_code($code);




