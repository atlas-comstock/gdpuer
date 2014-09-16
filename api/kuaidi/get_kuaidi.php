<?php
$com = $_GET[com];
$no = $_GET[no];
$com=rawurlencode(mb_convert_encoding($com, 'gbk', 'utf-8'));
$url = "http://www.gpsso.com/WebService/kuaidi/kuaidi.asmx/KuaidiQuery?Compay=".$com."&OrderNo=".$no;
$result = file_get_contents($url);
$xml = simplexml_load_string($result);
echo $xml->KDINFO_1;
?>