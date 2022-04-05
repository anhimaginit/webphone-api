<?php
require('../db.php');

$query = "select * from assigned_integration where u_id = 1;";
$sockets = db::getInstance()->get_result($query);
$access_cd = $sockets['ai_accessToken'];
//return print_r($sockets);

//require_once 'curl/curl.php';
//use simple_curl\curl;

$url = 'https://www.zohoapis.com/phonebridge/v3/integrate';

$headers = ["Authorization: Zoho-oauthtoken " . $_GET['accode']];
$params = '';
$handle = curl_init();
curl_setopt_array($handle,
    array(
        CURLOPT_URL => $url,
        // Enable the post response.
        CURLOPT_POST       => true,
        // The data to transfer with the response.
        CURLOPT_POSTFIELDS => $params,
        CURLOPT_RETURNTRANSFER     => true,
    )
);

curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);

$data = curl_exec($handle);

curl_close($handle);


$url = 'https://www.zohoapis.com/phonebridge/v3/callnotify';

$headers = ["Authorization: Zoho-oauthtoken " . $access_cd, "Content: application/x-www-form-urlencoded"];

//return print_r($headers);
$params = 'type=received&state=ringing&id='.$_GET['cid']. '&from=8017839004&to=9071';
//print_r(curl::get_response_assoc());


//$query="select * from " . $_GET['q'];
//$sockets = db::getInstance()->get_result($query);


$handle = curl_init();

//$url = "https://accounts.zoho.com/oauth/v2/token";

// Array with the fields names and values.
// The field names should match the field names in the form.
curl_setopt_array($handle,
    array(
        CURLOPT_URL => $url,
        // Enable the post response.
        CURLOPT_POST       => true,
        // The data to transfer with the response.
        CURLOPT_POSTFIELDS => $params,
        CURLOPT_RETURNTRANSFER     => true,
    )
);

curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);

$data = curl_exec($handle);

curl_close($handle);


//print_r($data);




$event_type='incoming';


switch($event_type){
    case 'outgoing':
        break;


    case 'incoming':
        $query = "select * from user where u_fname LIKE 'Brandon' AND u_lname LIKE 'Hillman';";
        $sockets = db::getInstance()->get_result($query);
        $query = (array('type'=>'received', 'state'=>'ringing', 'id'=>'8017839004','from'=>'8017839004', 'to'=>'9071'));
//              curl::prepare($url, $query, $headers);
//              curl::exec_post();
//              $curl_resp = curl::get_response_assoc();
//      print_r($curl_resp);
        break;

    case 'answered':
        break;

    case 'hangup':
        break;
}



return print_r($data); //echo 'none';











