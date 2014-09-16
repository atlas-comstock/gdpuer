<?php
include("wechatext.class.php");
global $_W;
$content = $this->message['content'];
$from_user=$this->message['from'];

	$sql = "SELECT * FROM " . tablename('stubind_reply') . " WHERE `weid`=:weid LIMIT 1";
	$row = pdo_fetch($sql, array(':weid' => $_W['weid']));

		//自动获取用户信息
		$options = array(
		'account'=> $row['account'] ,
		'password'=>$row['password'],
		'datapath'=>$_W['attachurl'].'cookie_',
		); 
		$wechat = new Wechatext($options);
		
		$sql = "SELECT * FROM " . tablename('stu_profile') . " WHERE `from_user`=:from_user LIMIT 1";
		$row_info = pdo_fetch($sql, array(':from_user' => $from_user));
		
		
		
$ret = preg_match('/查图书(.*)/i', $this->message['content'], $matchs);
$keyword = $matchs[1];
 $urlword=rawurlencode(mb_convert_encoding($keyword, 'gb2312', 'utf-8'));

$url='http://opac.lib.gdpu.edu.cn/cgi-win/s3trs.exe';
$post_data='word1='.$urlword.'&word2=&word3=&s=%BC%EC%CB%F7';
$html=curl($url,$post_data);
$html=get_utf8_string($html);

preg_match_all('/<a href=\/cgi-win\/tcgid.exe\?(?P<id>.+?)>(?P<title>.+?)<\/a>/s',$html,$idtitle); 
preg_match_all('/<\/a><\/td><td>(?P<year>.+?)<\/td><td>(?P<sqh>.+?)<\/td>/s',$html,$yns); 

$all='';
foreach($idtitle['id'] as $key=>$value){
  if($key<5){
  	$year=$yns['year'][$key];
	$sqh=$yns['sqh'][$key];
    //$itemurl='http://zlgc.gdpu.edu.cn/gdpuer/book/item.php?id='.$value;
	$bookname=$idtitle['title'][$key];
	$floor=substr( $sqh, 0, 1 );
	$floor=sqh($floor);
	
	
	
	$str=($key+1).'.'.$bookname."\n编号： ".$value."\n出版年".$year."\n索取号".$sqh."\n".$floor."\n";
	//$str='@title|'.($key+1).'.'.$bookname.'、编号： '.$value.'#url|'.$itemurl.'#pic';
	$all=$all.$str."\n";
 }
}
	$err='亲，图书馆网站的搜索功能出现问题了，无法帮你查询，请反馈信息给图书馆信息技术部~';
if($all!=''){
	//echo 'description|信息查询#title|图书信息'.$all;
	

if (!empty($row_info['fakeid'])) {

if ($wechat->checkValid()) {
			$fakeid=$row_info['fakeid'];//fakeid
			$send=$wechat->send($fakeid,$all);
			$se=json_decode($send,true);
			if($se['base_resp']['err_msg']=='ok'){
				return $this->respText('请稍等..');
			}else{
				return $this->respText($err);
			}
		}


}

	
}
else{

	return $this->respText($err);
}




function get_utf8_string($content) 
{    	  
	$encoding = mb_detect_encoding($content, array('ASCII','UTF-8','GB2312','GBK','BIG5'));  
	return  mb_convert_encoding($content, 'utf-8', $encoding);
}

function sqh($sqh){

$sqh_no=strtoupper($sqh);
$sqh_no=ord($sqh_no);
if($sqh_no>='65' && $sqh_no<='72')
{
 $floor="【3楼】中文社科图书阅览一室(A-H)";
}
if($sqh_no>='73' && $sqh_no<='75')
{
 $floor="【4楼】中文社科图书阅览二室(I、J、K)";
}
if($sqh_no=='82')
{
 $floor="【5楼】中文医药类图书阅览室(R)";
}
if(($sqh_no>='78' && $sqh_no<='81') || ($sqh_no>='83' && $sqh_no<='90'))
{
 $floor="【5楼】中文自然科学书阅览一室(N-Z)";
}

return $floor;

}

function bookinfo($id) 
{    	  
header("Content-type: text/html; charset=utf-8");
$url    = 'http://opac.lib.gdpu.edu.cn/cgi-win/tcgid.exe?'.$id;
$string = file_get_contents($url);
$string = get_utf8_string($string);
$str    = str_replace('<tr><td>', "【", $string);
$str    = str_replace('</td></tr>', "】", $str);


$str = preg_match_all('/【(?P<info>.+?)】+/s', $str, $results);
/*$ret = "【大学城图书馆】\n【3楼】 A-H\n中文社科图书阅览一室(A-H)\n【4楼】 I、J、K\n中文社科图书阅览二室(I、J、K)\n【5楼】R\n中文医药类图书阅览室(R)\n【5楼】N-Z\n中文自然科学书阅览一室(N-Z)";*/
preg_match('/库<\/td><td align="center">(?P<sqh>.+?)<td align=\"center\">/s',$results['info']['0'],$sqh);
$sqh=$sqh['sqh'];
$floor=substr($sqh,0,1);
$ret="【大学城图书馆】\n图书在".sqh($floor);
foreach ($results['info'] as $v) {

    $v = "\n\n" . $v;
    $v = str_replace('库</td><td align="center">', "库\n索取号: ", $v);
    $v = str_replace('</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">', "\n状态: ", $v);
    $v = str_replace('</td><td align="center">&nbsp;', "\n借阅类型: ", $v);
    $v = str_replace('<td align="center">', "\n登录号: ", $v);
	
	//$sqh='索取号: TP391.41/S0203';
	//$floor=substr($sqh,11,1);
    $ret .= $v;
}

return $ret;


}

function curl($submit_url,$post_fields){
$ch = curl_init($submit_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch,CURLOPT_HEADER,0);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
$contents = curl_exec($ch);
curl_close($ch);

return $contents;

}

?>