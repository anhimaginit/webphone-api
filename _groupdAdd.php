<?php
$origin=isset($_SERVER['HTTP_ORIGIN'])?$_SERVER['HTTP_ORIGIN']:$_SERVER['HTTP_HOST'];
header('Access-Control-Allow-Origin: '.$origin);
header('Access-Control-Allow-Methods: POST, OPTIONS, GET, PUT');
header('Access-Control-Allow-Credentials: true');

include_once './lib/class.acl.php';

$Object = new ACL();
$EXPECTED = array('token','g_name','role','u_ids');

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
    $result = $Object->create_acl("user3","user"); //$g_name,$role,$u_ids
    if(is_numeric($result) && !empty($result)){
        $ret = array('status'=>'SUCCESS','ERROR'=>'','g_id'=>$result);

    } else {
        $ret = array('status'=>'FAIL','ERROR'=>$result,'g_id'=>'');
    }

}
$Object->close_conn();
echo json_encode($ret);
//http_response_code($code);




