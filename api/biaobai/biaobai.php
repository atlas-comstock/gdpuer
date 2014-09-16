<?php
include("db.php");

$getdata=urldecode($_REQUEST['key']);
$from = urldecode($_REQUEST['from']);

$getdata=str_replace("，",",",$getdata);//统一是,

preg_match("/(.*),(.*)/",$getdata,$t);
$bbkey=explode(",",$t[0]);
$bz=$bbkey[0];
$target=$bbkey[1];
$content=$bbkey[2];

$tips="@title|如果要表白发送：@title|表白，对方的名字，表白内容@title|如果要查看有没有人向自己表白，发送@title|看表白,自己的姓名@title|看谁被表白发送@title|看被表白@title|就可以看到哪些人被别人表白了哦";



if($getdata=="看被表白"){

$sql4="select count(*) from biaobai group BY `target`";
$query4=mysql_query($sql4,$link) or die(mysql_error());

$sql5="select * from biaobai group BY `target`";
$query5=mysql_query($sql5,$link) or die(mysql_error());

$renshu=mysql_fetch_row($query4);
if(!$renshu){
echo "暂时还没有人表白\n\n".$tips;
break;
}

echo $bbnum="被表白的名单有:\n\n";
$n=1;
while ($ren=mysql_fetch_row($query5))//循环赋值，将结果放入组数中。
      {
		  echo $n."、".$ren[1]."\n";
		  $n++;
       }
	   break;
}
if($bz!=="看表白"&&(!$target)&&(!$content)){//如果没对象又没内容
echo "title|今天不是愚人节..你又没有表白对象又没有表白内容这是耍我么？#pic|#url|".$tips;
break;
}
if($bz=="看表白"&&(!$target)){//如果没对象有内容
echo "title|连个名字都没有...你到底是谁！快说..你想干嘛..打算偷窥谁的表白内容？#pic|#url|".$tips;
break;
}
if($bz!=="看表白"&&(!$target)&&$content){//如果没对象有内容
echo "title|你还没找到对象哦亲！匿名表白也是要有个目标的额！#pic|#url|".$tips;
break;
}
if($bz!=="看表白"&&$target&&(!$content)){//如果有对象没内容
echo "title|怎么了？我们表白是匿名的哦，这都不敢写内容么？#pic|#url|".$tips;
break;
}


elseif($bz=="表白"){
/*发表表白*/
echo "title|表白成功#pic|#url|@title|1.想办法让你心仪的Ta关注本微信@title|2.并按照提示发送@title|看表白,Ta的名字@title|就可以看到你对Ta的告白啦！";
$sql1="INSERT INTO  `biaobai` (`id` ,`target` ,`content` ,`time` ,`openid`)VALUES (NULL , '{$target}',  '{$content}',TIMESTAMP(10) ,'{$from}')";
$query1=mysql_query($sql1,$link) or die(mysql_error());
}
elseif($bz=="看表白"){
/*看表白*/
$sql2="SELECT count(*) FROM `biaobai` WHERE `target` =  '{$target}'";
$query2=mysql_query($sql2,$link) or die(mysql_error());
$count=mysql_fetch_row($query2);//算出有多少个数据

$sql3="SELECT * FROM  `biaobai` WHERE  `target` =  '{$target}' order by `time` desc LIMIT 0 , 30";//只显示出前30个
$query3=mysql_query($sql3,$link) or die(mysql_error());
echo $bbnum="WOW..亲爱的{$target}\n\n你一共收到{$count[0]}个人向你表白耶\n\n";

$no=1;
while ($look=mysql_fetch_row($query3))//循环赋值，将结果放入组数中。
      {
		  echo $no."、".$values[$no]=$look[2]."\n";
		  $no++;
       }



if($count[0]>=30){
echo "\n\n(由于你倾国倾城太多人表白了所以只能显示前30条了)";
}
}

?>