<?php

function checkSignature()
{
    if (empty($_GET['echostr'])) {
        return;
    }
    file_put_contents('wx.log', serialize($_GET), FILE_APPEND);

    $signature = $_GET["signature"];
    $timestamp = $_GET["timestamp"];
    $nonce = $_GET["nonce"];
    
    $token = 'dreambuild2021';
    $tmpArr = array($token, $timestamp, $nonce);
    sort($tmpArr, SORT_STRING);
    $tmpStr = implode( $tmpArr );
    $tmpStr = sha1( $tmpStr );

    if( $tmpStr == $signature ){
        echo $_GET['echostr'];
    }else{
        echo 'failed';
    }
    exit;
}

checkSignature();


//微信post的所有数据在一起
// $postStr = $GLOBALS['HTTP_RAW_POST_DATA']
$postStr = file_get_contents('php://input');

if (empty($postStr)) {
    file_put_contents('wx.log', 'post数据为空'.FILE_APPEND."\n", FILE_APPEND);
    return 'post数据为空'."\n";
}


$xml = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);

function replyMsg($content) {
    global $xml;
    $str = sprintf('<xml><ToUserName><![CDATA[%s]]></ToUserName>
        <FromUserName><![CDATA[%s]]></FromUserName>
    <CreateTime>%d</CreateTime>
    <MsgType><![CDATA[text]]></MsgType>
    <Content><![CDATA[%s]]></Content></xml>',
    $xml->FromUserName,$xml->ToUserName,time(), $content);
    echo $str;
}
function replyArticle($data) {
    global $xml;
    $article = '<ArticleCount>'.count($data).'</ArticleCount>';
    $article .= '<Articles>';
    foreach ($data as $d) {
        $article .= sprintf('<item><Title><![CDATA[%s]]></Title>',$d['title']);
        $article .= sprintf('<Url><![CDATA[%s]]></Url>',$d['url']);
        $article .= sprintf('<PicUrl><![CDATA[%s]]></PicUrl>',$d['picurl']);
        $article .= sprintf('<Description><![CDATA[%s]]></Description></item>',$d['desc']);
    }
    $article .= '</Articles>';

    $str = sprintf('<xml><ToUserName><![CDATA[%s]]></ToUserName>
        <FromUserName><![CDATA[%s]]></FromUserName>
    <CreateTime>%d</CreateTime>
    <MsgType><![CDATA[news]]></MsgType>
    %s</xml>',
    $xml->FromUserName,$xml->ToUserName,time(),$article);
    echo $str;
}

if ($xml->MsgType == 'event') {
    //关注
    if ($xml->Event == 'subscribe') {
        $data = array(
            array('title'=>'photo','url'=>'https://www.baidu.com','picurl'=>'https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1490232911&di=09b78688b82d17afefd7ae2f2a11095e&imgtype=jpg&er=1&src=http%3A%2F%2Fimg.9553.com%2Fuploadfile%2F2016%2F0506%2F20160506024135355.jpg','desc'=>'it is a photo'),
            array('title'=>'欢迎关注'),
        );
        replyArticle($data);
    }
}
else {
    if($xml->Content){
       require './movie.php';
       $data=Movie::GetMovieInfo($xml->Content);
       
       $dataList=[];
       foreach($data as $key=>$value){
           $dataList[]=[
            //    'title'=>$value['title'],
            //    'url'=>'$value->alt',
            //    'picurl'=>$value->images->large,
            //    'desc'=>'我是描述'
            'title'=>$value->title,
            'url'=>$value->alt,
            'picurl'=>$value->images->medium,
           ];
       }
        replyArticle(array_slice($dataList,1,8));
    }
    else {
        $uid = md5($xml->FromUserName);
        session_id($uid);
        session_start();
        //上一次输入
        $old_content = $_SESSION['content'];
        //这一次用户的输入
        $_SESSION['content'] = strval($xml->Content);
        replyMsg('now:'.$xml->Content.',last:'.$old_content);
    }
}




?>




