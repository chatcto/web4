<?php
//北京天气API
//http://php.weather.sina.com.cn/xml.php?city=%B1%B1%BE%A9&password=DJOYnieT8234jlsK&day=0

require'./Curl.php';
$url = "http://php.weather.sina.com.cn/xml.php?city=%B1%B1%BE%A9&password=DJOYnieT8234jlsK&day=0";
$content=Curl::CurlGet($url);
print_r($content);