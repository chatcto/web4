<?php
require './Curl.php';
class Daily {
	public static function GetDailTitle()
	{
		$url = "http://news-at.zhihu.com/api/4/news/latest";
		$data = Curl::CurlGet($url);
		$data = json_decode($data);
		$data = self::ObjectArray($data);
		//$strdata = '';
		// foreach ($data['stories'] as $key => $value) {
		// 	$strdata .= $value['title']."\n\n";
		// }
		return $data['stories'];
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
$DailTitle = Daily::GetDailTitle();
print_r($DailTitle);

?>
