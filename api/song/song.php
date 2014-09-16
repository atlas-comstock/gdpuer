<?php
$t=$_REQUEST['key'];

$bz="音乐";//一贯特色，关键字标志BZ

$t = "真的爱你";
$t=str_replace($bz,"",$t);//文本处理

$t=rawurlencode(mb_convert_encoding($t, 'gb2312', 'utf-8'));
   $ch=curl_init();
   curl_setopt_array(
   $ch,
    array(
      CURLOPT_URL=>"http://cgi.music.soso.com/fcgi-bin/m.q?w=".$t."&p=1&source=1&t=1",
      CURLOPT_RETURNTRANSFER=>true,

       )
   );

   $content=curl_exec($ch);
   
   preg_match_all('/<TD class=\"data\">(.*)<\/TD>/',$content,$d2);
   $mus=explode("@",$d2[1][0]);
   
   $musurl=str_replace("FI","",$mus[16]);
   $musurl=str_replace(";;|||","",$musurl);//音乐地址

   $mustitle=$mus[2];//音乐标题
   $mussonger=$mus[6];//音乐歌手
   
   echo "title|".$mustitle.'#description|'.$mussonger."#murl|".$musurl."#hqurl|".$musurl;


?>