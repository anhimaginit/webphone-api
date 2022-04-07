<?php
$origin=isset($_SERVER['HTTP_ORIGIN'])?$_SERVER['HTTP_ORIGIN']:$_SERVER['HTTP_HOST'];
header('Access-Control-Allow-Origin: '.$origin);
header('Access-Control-Allow-Methods: POST, OPTIONS, GET, PUT');
header('Access-Control-Allow-Credentials: true');

include_once './lib/class.company.php';

$Object = new Company();
$EXPECTED = array('auth','u_type','u_id','limit','offset','text_search');

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
    if($u_type=="branch-manager"){

    }else{
        $result = $Object->companies($u_type,$u_id,$limit,$offset,$text_search);
    }

    $ret = array('response'=>$result,'ERROR'=>'');

}
$Object->close_conn();
echo json_encode($ret);
//http_response_code($code);




