<?php
$origin=isset($_SERVER['HTTP_ORIGIN'])?$_SERVER['HTTP_ORIGIN']:$_SERVER['HTTP_HOST'];
header('Access-Control-Allow-Origin: '.$origin);
header('Access-Control-Allow-Methods: POST, OPTIONS, GET, PUT');
header('Access-Control-Allow-Credentials: true');

include_once './lib/class.acl.php';

$Object = new ACL();
$EXPECTED = array('auth','u_id');

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
    $ret = array('login'=>array(),'ERROR'=>'Authentication is failed');
}else{
    $result = $Object->get_ACL(3); //$g_name,$role,$u_ids
    //print_r($result); die();
    if(is_numeric($result) && !empty($result)){
        $ret = array('status'=>'SUCCESS','ERROR'=>'','acl'=>$result);

    } else {
        $ret = array('status'=>'FAIL','ERROR'=>$result,'acl'=>'');
    }

}
$Object->close_conn();
echo json_encode($ret);
//http_response_code($code);




