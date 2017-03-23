<?php
require('./Curl.php');

class Robot{
    public static function Chart($input){
        $arr = array(
            'key'=>'5ea93606aceb4328a4e97285979f6056',
            'info'=>$input,
            'userid'=>'dreambuild2021'
        );
        $data_string =  json_encode($arr);

        $url='http://www.tuling123.com/openapi/api';
        $param=$data_string;
        $data=Curl::CurlPost($url,$param);
        $data = json_decode($data);
        return $data;
    }
}
// $test=Robot::Chart('小狗图片');
// print_r($test);
?>