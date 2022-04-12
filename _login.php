<?php
$origin=isset($_SERVER['HTTP_ORIGIN'])?$_SERVER['HTTP_ORIGIN']:$_SERVER['HTTP_HOST'];
header('Access-Control-Allow-Origin: '.$origin);
header('Access-Control-Allow-Methods: POST, OPTIONS, GET, PUT');
header('Access-Control-Allow-Credentials: true');

include_once './lib/class.login.php';

$Object = new Login();
$EXPECTED = array('auth','u_email','u_password');

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
    $ret = array('response'=>array(),'acl'=>'','role'=>'','ERROR'=>'Authentication is failed');
}else{
    //$u_email = "anh@at1ts.com";$u_password="123";
    $ret = $Object->loginEmailPass($u_email,$u_password);
    //print_r($ret['acl']);
}
$Object->close_conn();
echo json_encode($ret);
//http_response_code($code);




