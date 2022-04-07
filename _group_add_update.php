<?php
$origin=isset($_SERVER['HTTP_ORIGIN'])?$_SERVER['HTTP_ORIGIN']:$_SERVER['HTTP_HOST'];
header('Access-Control-Allow-Origin: '.$origin);
header('Access-Control-Allow-Methods: POST, OPTIONS, GET, PUT');
header('Access-Control-Allow-Credentials: true');

include_once './lib/class.acl.php';

$Object = new ACL();
$EXPECTED = array('auth','g_id','g_name','g_role','u_name','u_id');

foreach ($EXPECTED AS $key) {
    if (!empty($_POST[$key])){
        ${$key} = $Object->protect($_POST[$key]);
    } else {
        ${$key} = NULL;
    }
}
//die();
//--- validate
$isAuth = true;//$Object->basicAuth($auth);

if(!$isAuth){
    $ret = array('ERROR'=>'Authentication is failed');
}else{
    $ret = $Object->create_group_default($g_id,$g_name,$g_role,$u_id,$u_name);
}
$Object->close_conn();
echo json_encode($ret);
//http_response_code($code);




