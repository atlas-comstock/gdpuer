<?php 
include '../../common/phpheader.php';
header ( "Content-type: text/html; charset=utf-8" );
ini_set ( "display_errors", 0 );
include ("dom.php");

$xh = $_REQUEST ['xh'];
$pw = $_REQUEST ['pw'];
$post_fields = '__VIEWSTATE=dDwxOTA0NTQ3NDgwO3Q8O2w8aTwxPjs%2BO2w8dDw7bDxpPDg%2BO2k8MTM%2BO2k8MTU%2BOz47bDx0PHA8O3A8bDxvbmNsaWNrOz47bDx3aW5kb3cuY2xvc2UoKVw7Oz4%2BPjs7Pjt0PHA8bDxWaXNpYmxlOz47bDxvPGY%2BOz4%2BOzs%2BO3Q8cDxsPFZpc2libGU7PjtsPG88Zj47Pj47Oz47Pj47Pj47bDxpbWdETDtpbWdUQzs%2BPjS%2Figxoe%2FD2rIfDraki%2BD1Sdxcq&tbYHM=' . $xh . '&tbPSW=' . $pw . '&ddlSF=%D1%A7%C9%FA&imgDL.x=31&imgDL.y=8';
$submit_url = 'http://10.50.17.1/default3.aspx'; // 提交页面
$ch = curl_init ( $submit_url );
curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
curl_setopt ( $ch, CURLOPT_HEADER, 1 );
curl_setopt ( $ch, CURLOPT_POST, 1 );
curl_setopt ( $ch, CURLOPT_POSTFIELDS, $post_fields );
$contents = curl_exec ( $ch );
preg_match ( '/Set-Cookie: (.*);/i', $contents, $results );
$cookie = $results [1];
curl_close ( $ch ); // 提取成功之后的ASP.NetSessionId的cookies
                 
// 学生成绩页面
$geturl_xscj = 'http://10.50.17.1/xscj.aspx?xh=' . $xh;

$header [] = 'Cookie:' . $cookie;
$ch = curl_init ( $geturl_xscj );
curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
curl_setopt ( $ch, CURLOPT_HTTPHEADER, $header );
curl_setopt ( $ch, CURLOPT_HEADER, 0 );
$string = curl_exec ( $ch ); // 输出内容
$string = get_utf8_string ( $string ); // 转成utf8

$html = new simple_html_dom ();
$html->load ( $string );
$table = $html->find ( 'table[id=DataGrid1]', 0 );
$text = $table->outertext;
echo $text;

$html2 = new simple_html_dom ();
$html2->load ( $string );
$table2 = $html2->find ( 'table[id=Table1]', 0 );
$text2 = $table2->outertext;
echo $text2;

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
