<?php
/*
已经修复echo空图文问题，2013.5.13
--By Yanson
另外描述什么的分开来写了，便于修改
*/


header("Content-Type:text/html;charset=utf-8"); 
$t=$_REQUEST['key'];//微信发来的树洞信息
$wx_token='gdpuer'.$_REQUEST['wx_token'];
$yz_token='gdpuer'.md5("zzbon");//定义关键词
$token_arr= array("2.00Y4RewC6sCTkD176ec339fe1ok5WE","2.00Y4RewC0kTlko24dc352d530rcIxO","2.00Y4RewC08bfobd2da336ffcceOG1E","2.00Y4RewCzt2KiD9efd4b5f680ICJSD");
$rand=rand(0,count($token_arr));
$wb_token=$token_arr[$rand];//微博发送用户token
//52心理\求收养\微博投票\定时SHOWONE

/*返回信息*/
$url="http://weibo.cn/zzbwxd";//链接的地址可以是微博地址或者其他
$description="此功能由OURStudio Yanson实现";//描述可以打小广告
$picurl="http://tp2.sinaimg.cn/2001138865/180/40022353031/1";//图片地址
$successtitle="微博发送成功";//成功的信息
$errtitle="指令错误或内容不能为空,任何疑问请发送@你的留言，告知帮主";//失败的信息

$err="title|".$errtitle."#url|".$url."#description|".$description."#pic|".$picurl;//错误信息
$success="title|".$successtitle."#url|".$url."#description|".$description."#pic|".$picurl;//成功信息


/*处理文本*/
$t=str_replace($bz,"",$t);
$t='@找找帮 '.$t.'【广药小助手】';//定义后缀
if($wx_token==$yz_token){
	if(isset($_REQUEST['key'])){
	$ch=curl_init();
	curl_setopt($ch, CURLOPT_HTTPHEADER,'Authorization:OAuth2 ' . $wb_token); 
	curl_setopt_array(
	$ch,
    array(
      CURLOPT_URL=>'https://api.weibo.com/2/statuses/update.json',
      CURLOPT_RETURNTRANSFER=>true,
      CURLOPT_POST=>true,
      CURLOPT_POSTFIELDS=>'status='.$t.'&access_token='.$wb_token
		 )
	);
  	 $content=curl_exec($ch);
  	 curl_close($ch);  
 	  echo $success; 
 	}
	else{
 		echo $err;
 	}
}
else{
  echo "验证不通过,请联系帮主！";
}  
?>