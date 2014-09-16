<head>
  <meta charset="utf-8">
  <title>学院查询----学生选修查询系统 By Yanson From OUR Studio</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="OUR Studio, 学生选修查询系统">
  <meta name="author" content="YANSON">

</head>
<form class="form-inline" action="" method="post">
<label>学院名称</label>
<input id="nj_input" name="nj_input" type="text" />
<label>条数</label>
<input id="num" name="num" type="text" />
<button   onkeydown='if(event.keyCode==13){gosubmit();}' type="submit">查询</button>
</form>
<?php
header("content-Type: text/html; charset=utf-8");


	   $dbname = 'fPbhUckfqXdcQiuSglFR';//这里填写你BAE数据库的名称
 
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

$nj_input=$_REQUEST['nj_input'];
$perNumber=$_REQUEST['num'];
if(!$perNumber){$perNumber=30; }//每页显示的记录数


$page=$_GET['page']; //获得当前的页面值
$count=mysql_query("select count(*) from `jwc_personinfo` WHERE `xymc` like '%$nj_input%'"); //获得记录总数
$rs=mysql_fetch_array($count); 
$totalNumber=$rs[0];
echo '共有'.$totalNumber.'条记录<br><br>';
$totalPage=ceil($totalNumber/$perNumber); //计算出总页数
if (!isset($page)) {
 $page=1;
} //如果没有值,则赋值1
$startCount=($page-1)*$perNumber; //分页开始,根据此方法计算出开始的记录

if ($page != 1) { //页数不等于1
?>
<a href="xy_694.php?num=<?php echo $perNumber ;?>&nj_input=<?php echo $nj_input ;?>&page=<?php echo $page - 1;?>">上一页</a> <!--显示上一页-->
<?php
}
for ($i=1;$i<=$totalPage;$i++) {  //循环显示出页面
?>
<a href="xy_694.php?num=<?php echo $perNumber ;?>&nj_input=<?php echo $nj_input ;?>&page=<?php echo $i;?>"><?php echo $i ;?></a>
<?php
}
if ($page<$totalPage) { //如果page小于总页数,显示下一页链接
?>
<a href="xy_694.php?num=<?php echo $perNumber ;?>&nj_input=<?php echo $nj_input ;?>&page=<?php echo $page + 1;?>">下一页</a>
<?php
}
echo '<br><br><br>';

$sql_name = "SELECT * FROM `jwc_personinfo` WHERE `xymc` like '%$nj_input%' limit $startCount,$perNumber";
$query_char=mysql_query("SET NAMES UTF8");
$query_name=mysql_query($sql_name,$link) or die(mysql_error());
while($name_ret=mysql_fetch_row($query_name)){
$xh=$name_ret[0];
$pw=$name_ret[1];
$xm=$name_ret[2];
$csrq=$name_ret[3];
$xb=$name_ret[4];
$xymc=$name_ret[5];
$zymc=$name_ret[6];  
$zyfx=$name_ret[7];
$bjmc=$name_ret[8];
$nj=$name_ret[9];

  //echo $xh.'<br>'.$pw.'<br>'.$name.'<br>'.$nj.'<br><br>';
  
  
$ret="学号----<font color=blue>".$xh."</font><br>密码----<font color=blue>".$pw."</font><br>年级----<font color=blue>".$nj."</font><br>姓名----<font color=blue>".$xm."</font><br>性别----<font color=blue>".$xb."</font><br>学院名称----<font color=blue>".$xymc."</font><br>专业名称----<font color=blue>".$zymc."</font><br>专业方向----<font color=blue>".$zyfx."</font><br>班级名称----<font color=blue>".$bjmc."</font><br>出生日期----<font color=blue>".$csrq."</font><br><br>";
 echo $ret;  
  
  
  
?>

<!--iframe frameborder='0' name='xx' height='50%' width='100%' src="http://yanson.duapp.com/chengji/xuanxiu_chaxun_y_694.php?xh=<?php echo $xh; ?>&pw=<?php echo $pw; ?>">
</iframe-->


<?php 
}
if ($page != 1) { //页数不等于1
?>
<a href="xy_694.php?num=<?php echo $perNumber ;?>&nj_input=<?php echo $nj_input ;?>&page=<?php echo $page - 1;?>">上一页</a> <!--显示上一页-->
<?php
}
for ($i=1;$i<=$totalPage;$i++) {  //循环显示出页面
?>
<a href="xy_694.php?num=<?php echo $perNumber ;?>&nj_input=<?php echo $nj_input ;?>&page=<?php echo $i;?>"><?php echo $i ;?></a>
<?php
}
if ($page<$totalPage) { //如果page小于总页数,显示下一页链接
?>
<a href="xy_694.php?num=<?php echo $perNumber ;?>&nj_input=<?php echo $nj_input ;?>&page=<?php echo $page + 1;?>">下一页</a>
<?php
} 
?>
