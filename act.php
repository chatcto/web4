<?php
require('conf.php');

$dbms='mysql';     //数据库类型
$host='127.0.0.1'; //数据库主机名
$dbName='web';    //使用的数据库
$user='web';      //数据库连接用户名
$pass='web';          //对应的密码
$dbport = '3307';  //端口
$dsn="$dbms:host=$host;port={$dbport};dbname=$dbName";

session_start();
header('Content-Type: text/html;charset=UTF-8');

if ($_SESSION['user']) {
	echo 'logined<br>';
    echo "<pre>";
    $wechat_user = $_SESSION['user'];
	var_dump($wechat_user);
    // print_r($wechat_user['openid']);
try {
    $dbh = new PDO($dsn, $user, $pass, array(PDO::MYSQL_ATTR_INIT_COMMAND => "set names utf8")); //初始化一个PDO对象
    print_r($dbh);
    echo "连接成功<br/>";
    // // 你还可以进行一次搜索操作
    // foreach ($dbh->query('SELECT * from user') as $row) {
    //     print_r($row); //你可以用 echo($GLOBAL); 来看到这些值
    // }

    $strSql = "insert into user (
            openid,
            name,
            sex,
            language,
            province,
            country,
            headimgurl,
            unionid
            ) 
            values (
            '{$wechat_user['openid']}',
            '{$wechat_user['nickname']}',
            {$wechat_user['sex']},
            '{$wechat_user['language']}',
            '{$wechat_user['province']}',
            '{$wechat_user['country']}',
            '{$wechat_user['headimgurl']}',
            '{$wechat_user['unionid']}'
            )";
    print_r($strSql);
    $reslut = $dbh->exec($strSql);//返回影响了多少行数据
    print_r($reslut);

    // $strSql = "insert into user (name,age) values ('stark','12')";
    // $reslut = $dbh->exec($strSql);//返回影响了多少行数据
    // print_r($reslut);

        $dbh = null;
    } catch (PDOException $e) {
        die ("Error!: " . $e->getMessage() . "<br/>");
    }
//默认这个不是长连接，如果需要数据库长连接，需要最后加一个参数：array(PDO::ATTR_PERSISTENT => true) 变成这样：
// $db = new PDO($dsn, $user, $pass, array(PDO::ATTR_PERSISTENT => true));


	exit;
}

function get_curl($url) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, false);
    curl_setopt($curl, CURLOPT_USERAGENT, 'User-Agent: Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 2.0.50727; .NET CLR 3.0.04506.648; .NET CLR 3.5.21022)');
    curl_setopt($curl, CURLOPT_TIMEOUT, 10);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($curl);
    curl_close($curl);
    return $result;
}

$code = $_GET['code'];
if (empty($code)) {
	echo 'user not permissioned';
	exit;
} else {
	echo $code;
}

$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$appid}&secret={$appsecret}&code={$code}&grant_type=authorization_code";
$cont = get_curl($url);
$cont = (array)json_decode($cont);
var_dump($cont);

$url = "https://api.weixin.qq.com/sns/userinfo?access_token={$cont['access_token']}&openid={$cont['openid']}&lang=zh_CN";
$cont = get_curl($url);
$user = (array)json_decode($cont);
var_dump($user);

// array(10) {
//   ["openid"]=&gt;
//   string(28) "oPs5ouLW3qg7P6CLj-jS7M1XVtSw"
//   ["nickname"]=&gt;
//   string(10) "stark.wang"
//   ["sex"]=&gt;
//   int(1)
//   ["language"]=&gt;
//   string(5) "zh_CN"
//   ["city"]=&gt;
//   string(0) ""
//   ["province"]=&gt;
//   string(6) "北京"
//   ["country"]=&gt;
//   string(6) "中国"
//   ["headimgurl"]=&gt;
//   string(127) "http://wx.qlogo.cn/mmopen/uCr0XQkia65fRdRkjAArpwbYoDiad9LrMMVq1SiabjsC3EGspryE59ogR2XatPvQrVxcUTjEF2xwN1XMxNY1Qlx6Hqsue5lhQhM/0"
//   ["privilege"]=&gt;
//   array(0) {
//   }
//   ["unionid"]=&gt;
//   string(28) "o28P7ww-ZMphcik-5ZSbkCr_QTQw"
// }

// require('db.php');

if ($user['openid']) {
	$_SESSION['user'] = $user;
}

