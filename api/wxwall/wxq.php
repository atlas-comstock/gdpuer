<?php
header("Content-type: text/html; charset=utf-8");

$getdata=urldecode($_REQUEST['key']);
$from = urldecode($_REQUEST['from']);
$bz="墙";//这个按照自己的规则修改吧 
preg_match("/{$bz}(.*)/",$getdata,$t);
$wallkey=$t[1];

if(!isset($_REQUEST['key']))
{
echo '上墙错误..没有内容';
break;
}

{

	   $url="http://ours.123nat.com:59832/api/wxwall/wxqlist.php";//这里填写你的API地址
	   
	   $show=10;//这里填写你第一次要显示多少条数据
       
	   $dbname = 'zQhyksxIwhotXRbcSyeg';//这里填写你BAE数据库的名称
 
       /*从环境变量里取出数据库连接需要的参数*/
       $host = "localhost";
       $port = "80";
       $user = "gdpuer";
       $pwd = "ourstudio";
 
       /*接着调用mysql_connect()连接服务器*/
       $link = @mysql_connect("{$host}:{$port}",$user,$pwd,true);
       if(!$link) {
                   die("Connect Server Failed: " . mysql_error($link));
                  }
       /*连接成功后立即调用mysql_select_db()选中需要连接的数据库*/
       if(!mysql_select_db($dbname,$link)) {
                   die("Select Database Failed: " . mysql_error($link));
                  }
//以上连接数据库
}

$tm=time();
$key='qiang'.$tm;
$time=date('h:i:s',$tm);
$sql1="INSERT INTO `wxwall` (`id`, `key`, `time`, `content`, `openid`) VALUES (NULL,'{$key}',TIMESTAMP(10),'{$wallkey}','{$from}')";
$query1=mysql_query($sql1,$link) or die(mysql_error());

$sql2="SELECT count(*) FROM `wxwall` WHERE `time`";   
$query2=mysql_query($sql2,$link) or die(mysql_error());
$count=mysql_fetch_row($query2);
$snum=$count[0]-1-$show;



echo 'description|#title|你的微信墙%n已经在'.$time.'发送成功#pic|#url|@title|【内容】'.$wallkey.'%n点击查看微信墙#description|#pic|#url|'.$url.'?snum='.$snum;

?>