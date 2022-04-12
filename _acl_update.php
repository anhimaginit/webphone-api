<?php
$origin=isset($_SERVER['HTTP_ORIGIN'])?$_SERVER['HTTP_ORIGIN']:$_SERVER['HTTP_HOST'];
header('Access-Control-Allow-Origin: '.$origin);
header('Access-Control-Allow-Methods: POST, OPTIONS, GET, PUT');
header('Access-Control-Allow-Credentials: true');

include_once './lib/class.acl.php';

$Object = new ACL();
$EXPECTED = array('auth','g_id','u_id');

foreach ($EXPECTED AS $key) {
    if (!empty($_POST[$key])){
        ${$key} = $Object->protect($_POST[$key]);
    } else {
        ${$key} = NULL;
    }
}

//--- validate
$isAuth = $Object->basicAuth($auth);

if(!$isAuth){
    $ret = array('Update'=>'','ERROR'=>'Authentication is failed');
}else{
    $acl =$_POST['acl'];

    /*$view =$acl['Permission']['Assigned_Integration']['view'];

    if($view == ""){
        print_r("toi o day");
    }

    if($view === "false"){
        print_r("toi khong toi");
    }
    die();*/
    $ret = $Object->updateACL($g_id,$u_id,$acl);

}
$Object->close_conn();
echo json_encode($ret);
//http_response_code($code);




