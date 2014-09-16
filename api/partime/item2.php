<?php
$id=$_GET['id'];
$url='http://blog.sina.com.cn/s/'.$id.'.html';
$html=file_get_contents($url);
echo $html;
?>