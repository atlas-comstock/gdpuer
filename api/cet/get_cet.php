<?php
require ('cet_html_dom.php');
echo "\t【广药小助手】\n\t【cet成绩查询】\n▶我是最帅的分隔符";
if (isset ( $_GET ['zkzh'] )) {
  $src = 'http://www.chsi.com.cn/cet/query';
  $id = $_GET ['zkzh'];
  $name = $_GET ['xm'];
  $ch = curl_init ();
  curl_setopt_array ( $ch, array (
      CURLOPT_URL => $src . '?zkzh=' . $id . '&xm=' . $name,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_POST => false,
      CURLOPT_REFERER => 'http://www.chsi.com.cn/cet/' 
  ) );
  $content = curl_exec ( $ch );
  if (curl_errno ( $ch ) == 0) {
    $html = new simple_html_dom ();
    $html->load ( $content );
    $table = $html->find ( 'table[class=cetTable]', 0 );
    if (! $table) {
      $str = "请确认姓名或准考证号是否正确!";
    } else {
      $text = $table->outertext;
      $html->clear ();
      $table->clear ();
      unset ( $html );
      $str = $text;
      $str = str_replace ( '<table border="0" align="center" cellpadding="0" cellspacing="6" class="cetTable">    <tr>      <th>', "", $str );
      $str = str_replace ( '</th>     <td>', "", $str );
      $str = str_replace ( '</td>   </tr>   <tr>      <th>', "\n", $str );
      $str = str_replace ( '<strong><span style="color: #F00;">', "", $str );
      $str = str_replace ( '</span>', "", $str );
      $str = str_replace ( '&nbsp;&nbsp;', "", $str );
      $str = str_replace ( '<span class="color01">', "\n", $str );
      $str = str_replace ( '</strong></td>    </tr>  </table>', "\n\n查询数据来源于学信网\nOURStudio提供技术支持.", $str );
    }
    $str = strip_tags ( $str );
    echo "\n\n" . "\n姓名: " . $name . "\n准考证号:\n" . $id . "\n" . $str;
  } else {
    echo curl_error ( $ch );
  }
}

// 将一些字符转化成utf8格式
function get_utf8_string($content) {
  $encoding = mb_detect_encoding ( $content, array (
      'ASCII',
      'UTF-8',
      'GB2312',
      'GBK',
      'BIG5' 
  ) );
  return mb_convert_encoding ( $content, 'utf-8', $encoding );
}

?>