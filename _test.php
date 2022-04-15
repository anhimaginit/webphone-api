<?php
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

//$ret = $Object->getFieldsTable();
$g_id="";$g_name="super_admin_default"; $g_role="super_admin";$u_id=1;$u_name="Brandon";
//$g_id="";$g_name="user_default"; $g_role="user";$u_id="";
$Object->create_group_default($g_id,$g_name,$g_role,$u_id);










