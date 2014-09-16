<?php
header("Content-type: text/html; charset=utf-8");
?>
<!DOCTYPE html>
<html lang="en">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
<script type="text/javascript" src="http://lib.sinaapp.com/js/jquery/1.7.2/jquery.js"></script>
  <link href="./css/bootstrap.css" rel="stylesheet">
  <link href="./css/docs.css" rel="stylesheet">
  <link href="./css/pygments-manni.css" rel="stylesheet">
  <body style="background:#FFFFCC;">
  <div >
    <div id='wxqlist' style='width:100%;margin:0 auto;'>
        <div style='width:100%;height:45px;top:0px;position:fixed;' class="btn btn-success"><h4>微信墙</h4></div>
      <br />
<?php
{
       $dbname = 'zQhyksxIwhotXRbcSyeg';//这里填写你BAE数据库的名称
 
       /*从环境变量里取出数据库连接需要的参数*/
       $host = "localhost";
       $port = "80";
       $user = "gdpuer";
       $pwd = "getenv('HTTP_BAE_ENV_SK')";
 
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


if(!isset($_GET['snum']))
{
echo '参数错误！';
break;
}

$sql1="select * from wxwall where `time`  order by time asc";
$query1=mysql_query($sql1,$link) or die(mysql_error());
$no=0;
$keys= array();
$values= array();
 while ($q=mysql_fetch_row($query1))//循环赋值，将结果放入组数中。
      {
		  $keys[$no]=$q[1];//结合我前面表的结果，这里$q[1]是key字段
		  $values[$no]=$q[3];//结合我前面表的结果，这里$q[1]是content字段
		  $no++;
       } 
$sql2="SELECT count(*) FROM `wxwall` WHERE `time`";   
$query2=mysql_query($sql2,$link) or die(mysql_error());
$count=mysql_fetch_row($query2);
$ki=$count[0];

if($ki>80)
{
foreach($keys as $ku)
{
$sql3="delete  FROM `wxwall` WHERE `time`";
$query3=mysql_query($sql3,$link) or die(mysql_error());
}
}
for($i=$ki-1;$i>$_GET['snum'];$i--)
{
  if($i%2==0){
    echo '<div class="btn btn-primary" style="width:45%;height:300px;"><textarea cols="30" readonly="readonly" style="height:250px;border-style : none;background-color : transparent;border-width : 0px;color:#FFFFFF;">'.($ki-$i).'、'.$values[$i].'</textarea></div><br />';
  }
    if($i%2!==0){
      echo '<div class="btn btn-primary" style="float:right;width:45%;height:300px;margin-top:-25px;"><textarea cols="30" readonly="readonly" style="height:250px;border-style : none;background-color : transparent;border-width : 0px;color:#FFFFFF;">'.($ki-$i).'、'.$values[$i].'</textarea></div><br />';
  }
  if($i==$_GET['snum']+1)
{
  echo '<br /><br /><br /><br /><div onclick="c('.($_GET['snum']-1).')" class="btn btn-danger" style="height:60px;width:100%;position:fixed;bottom:0;"><h4>点击继续</h4></div>';
}
if($i==0)
{
break;
}
}
?>
<div id='dw'>
  </div>
</div>
</div>
<script>
function c(d)
  {
$('#wxqlist').load('wxqlist.php?snum='+d);
  }
</script>
</body>
</html>