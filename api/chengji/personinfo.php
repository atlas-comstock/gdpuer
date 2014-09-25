<?php
ini_set("display_errors", 0);
header("Content-type: text/html; charset=utf-8");


$xh = $_GET['xh'];
$pw = $_GET['pw'];

//提交账号和密码，身份模拟登陆
$post_fields 	= '__VIEWSTATE=dDwxOTA0NTQ3NDgwO3Q8O2w8aTwxPjs%2BO2w8dDw7bDxpPDg%2BO2k8MTM%2BO2k8MTU%2BOz47bDx0PHA8O3A8bDxvbmNsaWNrOz47bDx3aW5kb3cuY2xvc2UoKVw7Oz4%2BPjs7Pjt0PHA8bDxWaXNpYmxlOz47bDxvPGY%2BOz4%2BOzs%2BO3Q8cDxsPFZpc2libGU7PjtsPG88Zj47Pj47Oz47Pj47Pj47bDxpbWdETDtpbWdUQzs%2BPjS%2Figxoe%2FD2rIfDraki%2BD1Sdxcq&tbYHM='.$xh.'&tbPSW='.$pw.'&ddlSF=%D1%A7%C9%FA&imgDL.x=31&imgDL.y=8';
//$post_fields 	= '__VIEWSTATE=dDwtNjg3Njk1NzQ3O3Q8O2w8aTwxPjs%2BO2w8dDw7bDxpPDg%2BO2k8MTM%2BO2k8MTU%2BOz47bDx0PHA8O3A8bDxvbmNsaWNrOz47bDx3aW5kb3cuY2xvc2UoKVw7Oz4%2BPjs7Pjt0PHA8bDxWaXNpYmxlOz47bDxvPGY%2BOz4%2BOzs%2BO3Q8cDxsPFZpc2libGU7PjtsPG88Zj47Pj47Oz47Pj47Pj47bDxpbWdETDtpbWdUQzs%2BPvpW9bNHRO98aj%2BzEmn77FHqeOjK&tbYHM='.$xh.'&tbPSW='.$pw.'&ddlSF=%D1%A7%C9%FA&imgDL.x=28&imgDL.y=19';
//$submit_url 	= 'http://10.50.17.2/default3.aspx';//提价页面
$submit_url 	= 'http://10.50.17.1/default3.aspx';//提价页面

$ch = curl_init($submit_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch,CURLOPT_HEADER,1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
$contents = curl_exec($ch);

preg_match('/Set-Cookie: (.*);/i', $contents, $results);
$cookie = $results[1];
curl_close($ch);

/*
$geturl_xsxx = 'http://10.50.17.2/xsxx.aspx?xh='.$xh;//个人信息页面

 查询学生个人信息保存到数据库

 正则表达匹配信息
 preg_match("/<span id=\"xm\">(.*)<\/span>/",$string,$xm);

 $xh  学号
 $pw  密码
 $xm  姓名
 $csrq 出身日期
 $xb 性别
 $xymc 学院名称
 $zymc 专业名称
 $zyfx 专业方向
 $bjmc 班级名称
 $nj 年级
 */

//$geturl_xsxx = 'http://10.50.17.2/xsxx.aspx?xh='.$xh;//个人信息页面
$geturl_xsxx = 'http://10.50.17.1/xsxx.aspx?xh='.$xh;//个人信息页面



$header[]='Cookie:'.$cookie;

$ch = curl_init($geturl_xsxx);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch,CURLOPT_HTTPHEADER,$header);
curl_setopt($ch, CURLOPT_HEADER, 0);

$string = curl_exec($ch);//输出内容
$string=get_utf8_string($string);//转成utf8

//匹配姓名
preg_match("/<span id=\"xm\">(.*)<\/span>/",$string,$xm);
$xm=$xm[1];
$xm=get_utf8_string($xm);

//匹配出生日期
preg_match("/<span id=\"csrq\">(.*)<\/span>/",$string,$csrq);
$csrq=$csrq[1];
$csrq=get_utf8_string($csrq);

//匹配性别
preg_match("/<span id=\"xb\">(.*)<\/span>/",$string,$xb);
$xb=$xb[1];
$xb=get_utf8_string($xb);


//匹配学院
preg_match("/<span id=\"xymc\">(.*)<\/span>/",$string,$xymc);
$xymc=$xymc[1];
$xymc=get_utf8_string($xymc);

//匹配专业名称
preg_match("/<span id=\"zymc\">(.*)<\/span>/",$string,$zymc);
$zymc=$zymc[1];
$zymc=get_utf8_string($zymc);

//匹配专业方向
preg_match("/<span id=\"zyfx\">(.*)<\/span>/",$string,$zyfx);
$zyfx=$zyfx[1];
$zyfx=get_utf8_string($zyfx);

//匹配班级名称
preg_match("/<span id=\"bjmc\">(.*)<\/span>/",$string,$bjmc);
$bjmc=$bjmc[1];
$bjmc=get_utf8_string($bjmc);


//匹配年级
preg_match("/<span id=\"dqszj\">(.*)<\/span>/",$string,$nj);
$nj=$nj[1];
$nj=get_utf8_string($nj);


if($xm){


    $ret=array('xm'=>$xm,'xymc'=>$xymc,'bjmc'=>$bjmc);
    echo json_encode($ret);


    //echo post_db($xh,$pw,$xm,$csrq,$xb,$xymc,$zymc,$zyfx,$bjmc,$nj);


}else{
    echo get_utf8_string("获取失败...");
}
function get_utf8_string($content) 
{    	  
    $encoding = mb_detect_encoding($content, array('ASCII','UTF-8','GB2312','GBK','BIG5'));  
    return  mb_convert_encoding($content, 'utf-8', $encoding);
}
function post_db($xh,$pw,$xm,$csrq,$xb,$xymc,$zymc,$zyfx,$bjmc,$nj) 
{    	  
    $api_url='http://ours.123nat.com:59832/helper/jwc/dbapi.php?xh='.$xh.'&pw='.$pw.'&xm='.$xm.'&csrq='.$csrq.'&xb='.$xb.'&xymc='.$xymc.'&zymc='.$zymc.'&zyfx='.$zyfx.'&bjmc='.$bjmc.'&nj='.$nj;
    $ret_personinfo=file_get_contents($api_url);
    return $ret_personinfo;

}


?>
