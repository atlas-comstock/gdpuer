<?php
$id=$_GET['id'];
$url='http://blog.sina.com.cn/s/'.$id.'.html';
$html=file_get_contents($url);
$match_content='/<p(.*?)<\/P>/';
$match_blogtitle='/<h2(.*?)<\/h2>/';
preg_match_all($match_content,$html,$content);
preg_match_all($match_blogtitle,$html,$blogtitle);
$blogtitle=strip_tags($blogtitle[0][0]);
$blogtitle=str_replace('&nbsp;','',$blogtitle);
$cont="";
foreach($content[0] as $key=>$value){
  	$str=strip_tags($value);
	$str=str_replace('&nbsp;','',$str);
  	$cont=$cont.$str;
}
$title=get_utf8_string($blogtitle);
$content=get_utf8_string($cont);
$gdpuer=get_utf8_string('广药小助手');
$weixin=get_utf8_string('微信号:GDPUer');

$html=<<<eot
<script type="text/javascript" src="../script/js/tablesorter.js"></script>
   <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>{$title}</title>
    

 <meta name="viewport" content="width=device-width,height=device-height,inital-scale=1.0,maximum-scale=1.0,user-scalable=no;">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="format-detection" content="telephone=no">
<meta charset="utf-8">

<link rel="stylesheet" href="../script/inc/jquery.mobile-1.3.1.min.css" />
<script src="../script/js/jquery.min.js" type="text/javascript"></script>
<script src="../script/js/jquery.mobile-1.3.1.min.js" type="text/javascript"></script>
<link href="../script/inc/news.css" rel="stylesheet" type="text/css" />
  <script src="../script/js/audio.min.js" type="text/javascript"></script>
   
    <script>
      audiojs.events.ready(function() {
        audiojs.createAll();
      });
    </script>
</head> 
    <script>
window.onload = function ()
{
var oWin = document.getElementById("win");
var oLay = document.getElementById("overlay");	
var oBtn = document.getElementById("popmenu");
var oClose = document.getElementById("close");
oBtn.onclick = function ()
{
oLay.style.display = "block";
oWin.style.display = "block"	
};
oLay.onclick = function ()
{
oLay.style.display = "none";
oWin.style.display = "none"	
}
};
</script>

<body id="news">
<div style="width:1px;height:1px;overflow:hidden;">

</div>
<div class="Listpage">

   <div class="page-bizinfo">
<div class="header" style="position: relative;">
<h4 id="activity-name">{$title}</h4>
</div>
<div id="overlay"></div><a id="biz-link" class="btn"  onclick="dourl('http://www.xiaojo.com/myadmin/pages/home.php?user=ourstudio')"  href = "http://www.xiaojo.com/myadmin/pages/home.php?user=ourstudio">
<div class="arrow">
<div class="icons arrow-r"></div>
</div>
<div class="logo">
<div class="circle"></div>
<img id="img" src="http://www.xiaojo.com/upload/13/04/20/136643284510750.jpg                       " border=0 width="100" height="100">
</div>
<div id="nickname">
{$gdpuer}</div>
<div id="weixinid">{$weixin}</div>
</a>
<div class="text" id="content">
<div class="page-bizinfo">
<div class="page-bizinfo">


<div class="text" id="content">
<div class="">
<div class="text">
  <p><span style="font-size:15px">{$content}</span></p>
</div>
</div>
</div>
 <script src="http://www.wxapi.cn/index/js/play.js" type="text/javascript"></script>
 <script>

function dourl(url){
location.href= url;
}
</script>
<div class="page-content" >

</div>
</div>	
</div>
</div>
</div>
</div>



<div style="display:none"> </div>
</body>
</html>
eot;

echo $html;

function get_utf8_string($content) 
{    	  
	$encoding = mb_detect_encoding($content, array('ASCII','UTF-8','GB2312','GBK','BIG5'));  
	return  mb_convert_encoding($content, 'utf-8', $encoding);
}
?>