<?php

$xh = $_GET[xh];
$pw = $_GET[pw];
// $url='http://ours.123nat.com:59832/helper/api/chengji/chengjiapi2.php?xh='.$xh.'&pw='.$pw;
$url='http://branch2.gdpu.edu.cn/gd/api/cj/chengjiapi2.php?xh='.$xh.'&pw='.$pw;
$content = file_get_contents($url);
echo $content;

?>