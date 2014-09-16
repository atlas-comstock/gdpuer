<?php
	$ch=curl_init();
	$ran = rand(1,6);
	curl_setopt_array(
	$ch,
    array(
      CURLOPT_URL=>'http://douban.fm/j/mine/playlist?channel='.$ran,
      CURLOPT_RETURNTRANSFER=>true,

		 )
	);

   $content=curl_exec($ch);
   
   $content=json_decode($content,true);
	$one=$content['song'][0];
echo 'title|'.$one['title'].'#description|'.$one['artist'].' '. round($one['rating_avg'],1).'M#murl|'.$one['url'].'#hqurl|'.$one['url'];

?>