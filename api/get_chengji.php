<?php

$xh = $_GET[xh];
$pw = $_GET[pw];

$url='http://phpdo9.nat123.net:52182/helper/api/chengji/chengjiapi2.php?xh='.$xh.'&pw='.$pw;

$content = file_get_contents($url);

echo $content;

?>