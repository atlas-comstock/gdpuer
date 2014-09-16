<?php
$keyword=$_GET['keyword'];
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
    $itemurl='http://av.jejeso.com/helper/api/book/item.php?id='.$value;
    $bookname=strip_tags($bn[1][$key]);
	$str='@title|'.($key+1).'.'.$bookname.'#url|'.$itemurl.'#pic';
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
?>