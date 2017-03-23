<?php
require('./Curl.php');
class Movie{
    public static function GetMovieInfo($input){
        $url='https://api.douban.com/v2/movie/search?q='.$input;
        $data=Curl::CurlGet($url);
        $data=json_decode($data);
        $data=$data->subjects;
        return $data;
    }
}
// $result=Movie::GetMovieInfo('钢铁侠');
// print_r($result->subjects[0]->title);
// print_r($result->subjects[0]->images->large);
// print_r($result->subjects[0]->alt);
?>