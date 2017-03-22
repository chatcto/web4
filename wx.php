<?php

/*
订阅号 认证
多发消息,用户收不到消息提醒


服务号 认证
消息限制比较多,但是不会折叠,和普通好友一样



编辑模式
不需要任何开发,公众号后台去设置

开发模式
通过接口,开发程序
用户消息 => 微信服务器 => (xml) => php接口 => (xml) => 微信服务器 => 用户

*/


//define(TOKEN, 'tiantiandaoqin');

// phpinfo();
// die;
require('./daily.php');
$DailTitle =  Daily::GetDailTitle();
print_r($DailTitle);
function checkSignature()
{
	if (empty($_GET['echostr'])) {
		return;
	}
		file_put_contents('wx.log', serialize($_GET), FILE_APPEND);

    $signature = $_GET["signature"];
    $timestamp = $_GET["timestamp"];
    $nonce = $_GET["nonce"];
    
	$token = 'starkweixin';
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

// $postStr = '<xml><ToUserName><![CDATA[gh_638aabd86eac]]></ToUserName>
// <FromUserName><![CDATA[opdZEt_uOSZnzn9ZKAnkH1N491dA]]></FromUserName>
// <CreateTime>1468555014</CreateTime>
// <MsgType><![CDATA[text]]></MsgType>
// <Content><![CDATA[php]]></Content>
// <Event>...</Event>
// <MsgId>6307395757914917125</MsgId>
// </xml>';

// file_put_contents('wx.log', $postStr."\n\n", FILE_APPEND);
// echo $postStr."\n";
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
			array('title'=>'photo','url'=>'http://www.ucai.cn','picurl'=>'https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1490232911&di=09b78688b82d17afefd7ae2f2a11095e&imgtype=jpg&er=1&src=http%3A%2F%2Fimg.9553.com%2Fuploadfile%2F2016%2F0506%2F20160506024135355.jpg','desc'=>'it is a photo'),
			array('title'=>'欢迎关注'),
		);
		replyArticle($data);
	}
}
else {
	if ($xml->Content == 'p') {
		$data = array(
                        array('title'=>'photo','url'=>'http://www.ucai.cn','picurl'=>'https://ss0.baidu.com/73t1bjeh1BF3odCf/it/u=3891964583,2645622568&fm=96&s=B10F97589A838F031B6B7459030050FC','desc'=>'it is a photo'),
                        array('title'=>'欢迎,照片'),
                );
                replyArticle($data);
	}

	elseif ($xml->Content == 'photo') {
		replyMsg('see photo');
	}elseif ($xml->Content == '知乎日报') {
		$DailTitle =  Daily::GetDailTitle();
		// $DailTitle =  "哈哈哈哈";

		replyMsg($DailTitle);
	}elseif ($xml->Content == 'x') {
		$content = "钢铁侠";
		$arrdata = [];
		$data = [
                	['title'=>'photo','url'=>'http://www.ucai.cn','picurl'=>'https://ss0.baidu.com/73t1bjeh1BF3odCf/it/u=3891964583,2645622568&fm=96&s=B10F97589A838F031B6B7459030050FC','desc'=>'it is a photo'],
                ['title'=>'欢迎,照片'],
        );		
		$data1 = [
                	['title'=>'photo','url'=>'http://www.ucai.cn','picurl'=>'https://ss0.baidu.com/73t1bjeh1BF3odCf/it/u=3891964583,2645622568&fm=96&s=B10F97589A838F031B6B7459030050FC','desc'=>'it is a photo'],
                ['title'=>'欢迎,照片'],
        );
		$arrdata[] =$data;
		$arrdata[] =$data1;
		
        replyArticle($arrdata);
		// $result = SearchMovie::search($content);
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




