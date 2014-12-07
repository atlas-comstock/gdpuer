<?php
$com = $_GET [com];
$no = $_GET [no];
$com = rawurlencode ( mb_convert_encoding ( $com, 'gbk', 'utf-8' ) );
$url = "http://www.gpsso.com/WebService/kuaidi/kuaidi.asmx/KuaidiQuery?Compay=" . $com . "&OrderNo=" . $no;
$result = file_get_contents ( $url );
$xml = simplexml_load_string ( $result );
$str = $xml->INFO;
if ($str == "未知数据") {
	echo "暂无数据\n请重新输入快递公司或者订单号";
} else {
	echo $xml->KDINFO_1;
}
?>