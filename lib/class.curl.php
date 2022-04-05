<?php

class ClassCurl {
    public $header;
    function __construct() {
        $this->header=[
            'X-Apple-Tz: 0',
            'X-Apple-Store-Front: 143444,12',
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            'Accept-Encoding: gzip, deflate',
            'Accept-Language: en-US,en;q=0.5',
            'Cache-Control: no-cache',
            'Content-Type: application/x-www-form-urlencoded; charset=utf-8',
            'Host: www.example.com',
            'Referer: http://www.example.com/index.php', //Your referrer address
            'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:28.0) Gecko/20100101 Firefox/28.0',
            'X-MicrosoftAjax: Delta=true'
        ];
    }

    function curl_func($parameters){
        $event_type=$parameters['event_type'];

        switch($event_type){
            case 'outgoing':
                //call curl
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
                //call curl
                break;

            case 'hangup':
                //call curl
                break;
        }

    }
}