<?php

class news{
    public static function getAnser(){
        $host = "http://jisunews.market.alicloudapi.com";
        $path = "/news/get";
        $method = "GET";
        $appcode = "7b1c52bfb17a444199ebfefa9dc5068d";
        $headers = array();
        array_push($headers, "Authorization:APPCODE " . $appcode);
        $querys = "channel=%E5%A4%B4%E6%9D%A1&num=10&start=0";
        $bodys = "";
        $url = $host . $path . "?" . $querys;

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_FAILONERROR, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        if (1 == strpos("$".$host, "https://"))
        {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        }
        $news=curl_exec($curl);
        $news = json_decode($news);
        $news = news::ObjectArray($news);
        $content = $news['result']['list'];

        return $content;
    }
   public static function ObjectArray($array)
    {
        if (is_object($array)) {
            $array = (array)($array);
        }if(is_array($array)){
            foreach ($array as $key => $value) {
                $array[$key] = self::ObjectArray($value);
            }
        }
        return $array;
    }
}
echo "<pre>";
// print_r(news::getAnser());
    
?>