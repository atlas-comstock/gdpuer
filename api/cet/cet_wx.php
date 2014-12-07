<?php
header ( "content-Type: text/html; charset=utf-8" );

$zkzh = $_REQUEST ['zkzh'];
$xm = $_REQUEST ['xm'];

echo "\t【广药小助手】\n\t【cet成绩查询】\n▶我是最帅的分隔符";

$ret = "\n\n" . "\n姓名: " . $xm . "\n准考证号:\n" . $zkzh . "\n";
echo $ret;
$cet = get_ours_cet ( $zkzh, $xm );
$ret = get_utf8_string ( $cet );
echo strip_tags ( $ret );
function get_utf8_string($content) {
  // 将一些字符转化成utf8格式
  $encoding = mb_detect_encoding ( $content, array (
      'ASCII',
      'UTF-8',
      'GB2312',
      'GBK',
      'BIG5' 
  ) );
  return mb_convert_encoding ( $content, 'utf-8', $encoding );
}

// 查四六级
function get_ours_cet($zkzh, $xm) {
  $url = 'http://av.jejeso.com/helper/api/ours/cet.php?zkzh=' . $zkzh . '&xm=' . $xm;
  $result = file_get_contents ( $url );
  return $result;
}

?>