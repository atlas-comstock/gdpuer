<?php

header("content-Type: text/html; charset=utf-8");

$key  = urldecode($_REQUEST['key']);//用户发送的关键词
$from = urldecode($_REQUEST['from']);//微信提供的用户openid
$name = urldecode($_REQUEST['name']);//用户备注信息(一般为昵称)
$tel  = urldecode($_REQUEST['tel']);//用户绑定的手机号
//以上是通过小九获取的粉丝相关信息，固定代码，不要修改。

       $dbname = 'zQhyksxIwhotXRbcSyeg';//这里填写你BAE数据库的名称
 
		//从环境变量里取出数据库连接需要的参数
       $host = "localhost";
       $port = "80";
       $user = "gdpuer";
       $pwd = "ourstudio";
 
		//接着调用mysql_connect()连接服务器
       $link = @mysql_connect("{$host}:{$port}",$user,$pwd,true);
       if(!$link) {
                   die("Connect Server Failed: " . mysql_error($link));
                  }
		//连接成功后立即调用mysql_select_db()选中需要连接的数据库
       if(!mysql_select_db($dbname,$link)) {
                   die("Select Database Failed: " . mysql_error($link));
                  }
//以上连接数据库

$date=date('Y-m-d');//定义日期格式
$time=date('H:i:s');//定义时间格式

$chongfu="SELECT count(*) FROM `sleep` WHERE time >= '$date' and openid = '$from'";//根据openid计算粉丝当天的签到次数
$chongfujieguo=mysql_query($chongfu,$link) or die(mysql_error());
$chongfushu=mysql_fetch_row($chongfujieguo);
$chongfunum=$chongfushu[0];//取得粉丝当天的签到次数

if ($chongfunum==0 )//&& $time>=5 && $time<9)
{
  $sql1="INSERT INTO sleep (id,user,time,openid)values(NULL,'{$name}',TIMESTAMP(10),'{$from}')";
//将昵称、当前时间、粉丝的排名（记录数+1）、还有openid插入到表中的user、time、paiming、openid字段。
$result=mysql_query($sql1,$link) or die(mysql_error());
}  

      $sql2="select * from sleep where `time` >= '$date' order by time desc limit 10";
      //进行结果的输出，将满足条件的记录输出出来。
      $query2=mysql_query($sql2,$link) or die(mysql_error());
      $myarr = array(); //定义数组
	  $no=0;
      while ($q=mysql_fetch_row($query2))//循环赋值，将结果放入组数中。
      {
          $arrname=$q[1];//结合我前面表的结果，这里$q[1]是user字段
          if(!$arrname)
          {
             $arrname="佚名";
          }
          $arrtime=$q[2];//结合我前面表的结果，这里$q[2]是time字段
          $arrtime1=date('H:i',strtotime($arrtime));//将时间截取小时和分钟，去掉秒 
          if ( isset($myarr[$paiming]))
              $myarr[$no].="[TOP ".($no+1)."] ".$arrname."  ".$arrtime1."\n";
          else
              $myarr[$no]="[TOP ".($no+1)."]  ".$arrname."  ".$arrtime1."\n\n"; 
         $no++;
       } 
       if($myarr)
       {
           foreach( $myarr as $value )
           {
               $wanshui.=$value;
           }
       }
       if($wanshui)
       {
          $bangdang = "晚睡排行榜：\n\n".$wanshui;
       }


$chongfu="SELECT count(*) FROM `sleep` WHERE time >= '$date' and openid = '$from'";//根据openid计算粉丝当天的签到次数
$chongfujieguo=mysql_query($chongfu,$link) or die(mysql_error());
$chongfushu=mysql_fetch_row($chongfujieguo);
$chongfunum=$chongfushu[0];//取得粉丝当天的签到次数

if ($chongfunum!=1)//如果粉丝当天的签到次数不为1
    {
       $sql3="select * from sleep WHERE time >= '$date' and openid = '$from'";//取粉丝当前的起床信息。
       $query3=mysql_query($sql3,$link) or die(mysql_error());
       $last=mysql_fetch_row($query3);
       $lasttime=$last[2];//最后一条记录的时间
       $lasttime1=date('H:i',strtotime($lasttime));//时间去掉秒
       $lastpaiming=$last[3];//最后一条记录的排名
	   $win=$lastpaiming-1;
       $reply="\n入睡成功!您今天".$lasttime1."睡觉!晚安嬷嬷哒~~";
       echo $bangdang.$reply;
    }  

else
{

if ($time>=6 && $time<20)
{
     echo $bangdang."这是什么作息时间吖~";
    }

else if ($time>=20 && $time<21)
   {
     echo $bangdang."才晚上九点多，早着呢！没下半场吗？没事做就和小助手聊一会儿天吧。";
    }

else if ($time>=21 && $time<22)
   {
     echo $bangdang."嗯，看来你是好学生哦，这么早就睡了？";
    }

else if ($time>=22 && $time<23)
   {
     echo $bangdang."洗完白白没？没的话快去洗，都什么时候了？";
    }

else if ($time>=23)
   {
     echo $bangdang."亲，该睡觉了，我已经给你暖好被窝了，赶紧的！";
    }

else if ($time<2)
   {
    echo "你就是传说中的夜猫子吗？凌晨了都，赶紧洗洗睡吧！";
    }

else if ($time>=2 && $time<4)
   {
     echo "半夜三更的起来干嘛，别说梦话了行吗？真的好吓人的！";
    }

else if ($time>=4 && $time<6)
   {
     echo "这才几点啊，再睡一会吧！";
    }
}

?>
