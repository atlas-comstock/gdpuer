<?php
header("content-Type: text/html; charset=utf-8");
$t=str_replace('视频','',$_REQUEST['key']);
$client_id='86c24802543c4711';//这里填写自己的appkey
$keyjson='https://openapi.youku.com/v2/searches/video/by_keyword.json?client_id='.$client_id.'&keyword='.$t;
$json=json_decode(file_get_contents($keyjson));
$v=$json->videos;

$objurl='http://m.youku.com/smartphone/detail?vid=';//优酷的3g页面 考虑到目前手机多数要安装Flash插件才能看 所以转到3G页面是HTML5的


$i=1;//定义多图文首个开始
foreach($v as $k)
{
$title='【标题】 '.$k->title;//标题
$id= $k->id;//ID
$thumbnail=$k->thumbnail;//图片描述
$category='【分类】 '.$k->category;//分类
$tags='【标签】 '.$k->tags;//标签
$time='【时长】 '.floor(($k->duration)/60).":".floor(($k->duration)%60);//时长
if($i==1)
{
  echo "title|".$title."\n".$time."#description|"."#pic|".$thumbnail."#url|".$objurl.$id;
}else{
  echo "@title|".$title."\n".$category."\n".$tags."\n".$time."#description|"."#pic|".$thumbnail."#url|".$objurl.$id;
}
$i+=1;
  if($i>9)
  {
  break;
  }
}



/*处理成微信图文数据,数组形式
$format = array("title","#description","#pic","#url","@title|");
$format=implode("|",$format);
preg_match_all('/\D\w+[|]/', $format, $formats);
Array
(
    [0] => Array
        (
            [0] => title|
            [1] => #description|
            [2] => #pic|
            [3] => #url|
            [4] => @title|
        )

)
*/
?>