<?php include '../common/phpheader.php';?>
<?php
$keyword=$_GET['key'];
$urlword=rawurlencode(mb_convert_encoding($keyword, 'gb2312', 'utf-8'));
$url='http://www.lib.gdpu.edu.cn/was40/outline?page=1&channelid=50301&searchword='.$urlword.'&ispage=yes';
$html=file_get_contents($url);
$html=get_utf8_string($html);
$list = '/<ul>(.*?)<\/ul>/is';
preg_match_all($list,$html,$ul,PREG_SET_ORDER ); 
$ulstr=str_replace('&nbsp','',$ul[0][1]);
$href = '/http:\/\/opac.lib.gdpu.edu.cn\/cgi-win\/tcgid.exe?(.*?)"/is';
preg_match_all($href,$ulstr,$id);
$title = '/title="(.*?)"/is';
preg_match_all($title,$ulstr,$bn);
$all='';
foreach($id[1] as $key=>$value){
  if($key<9){
    $value=str_replace('?','',$value);
	//$info=bookinfo($value);
    $itemurl='http://zlgc.gdpu.edu.cn/gdpuer/book/item.php?id='.$value;
    $bookname=strip_tags($bn[1][$key]);
	$str='@title|'.($key+1).'.'.$bookname.'、编号： '.$value.'#url|'.$itemurl.'#pic';
	$all=$all.$str;
  }
}
if($all!=''){
	echo 'description|信息查询#title|图书信息'.$all;
}
else{
	echo '亲，图书馆网站的搜索功能出现问题了，无法帮你查询，请反馈信息给图书馆信息技术部~';
}

function get_utf8_string($content) 
{    	  
	$encoding = mb_detect_encoding($content, array('ASCII','UTF-8','GB2312','GBK','BIG5'));  
	return  mb_convert_encoding($content, 'utf-8', $encoding);
}

function bookinfo($id) 
{    	  
header("Content-type: text/html; charset=utf-8");
$url    = 'http://opac.lib.gdpu.edu.cn/cgi-win/tcgid.exe?.$id';
$string = file_get_contents($url);
$string = get_utf8_string($string);
$str    = str_replace('<tr><td>', "【", $string);
$str    = str_replace('</td></tr>', "】", $str);


$str = preg_match_all('/【(?P<info>.+?)】+/s', $str, $results);
$ret = "【大学城图书馆】\n【3楼】 A-H\n中文社科图书阅览一室(A-H)\n【4楼】 I、J、K\n中文社科图书阅览二室(I、J、K)\n【5楼】R\n中文医药类图书阅览室(R)\n【5楼】N-Z\n中文自然科学书阅览一室(N-Z)";
foreach ($results['info'] as $v) {
    $v = "\n\n" . $v;
    $v = str_replace('库</td><td align="center">', "库\n索取号: ", $v);
    $v = str_replace('</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">', "\n状态: ", $v);
    $v = str_replace('</td><td align="center">&nbsp;', "\n借阅类型: ", $v);
    $v = str_replace('<td align="center">', "\n登录号: ", $v);
    $ret .= $v;
}

return $ret;


}

?>