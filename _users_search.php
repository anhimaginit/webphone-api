<?php
$origin=isset($_SERVER['HTTP_ORIGIN'])?$_SERVER['HTTP_ORIGIN']:$_SERVER['HTTP_HOST'];
header('Access-Control-Allow-Origin: '.$origin);
header('Access-Control-Allow-Methods: POST, OPTIONS, GET, PUT');
header('Access-Control-Allow-Credentials: true');

include_once './lib/class.user.php';
$Object = new User();
$EXPECTED = array('token','u_name');

foreach ($EXPECTED AS $key) {
    if (!empty($_POST[$key])){
        ${$key} = $Object->protect($_POST[$key]);
    } else {
        ${$key} = NULL;
    }
}

print_r($u_name);
die();
//--- validate
$isAuth = $Object->basicAuth($token);

if(!$isAuth){
    $ret = array('response'=>array(),'ERROR'=>'Authentication is failed');
}else{
    $result = $Object->search_users($u_name);

    $ret = array('response'=>$result,'ERROR'=>'');

}
$Object->close_conn();
echo json_encode($ret);
//http_response_code($code);




