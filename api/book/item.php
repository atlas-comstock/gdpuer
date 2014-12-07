<?php
$id = $_GET[id];
$url = 'http://opac.lib.gdpu.edu.cn/cgi-win/tcgid.exe?'.$id;
$content = file_get_contents($url);
$pattern = '/<center>(.*?)<\/center>/is';
preg_match_all($pattern, $content, $mat);
$qk = $mat[1][1];
$qk = iconv("GB2312","UTF-8" , $qk); 
echo $qk;
?>