<?php
header("Content-type: text/html; charset=utf-8");
include 'pdo.func.php';
$xh     = $_GET['xh'];
$pw     = $_GET['pw'];
//10.50.25.9
$day    = date("w");
$all    = 'no';
$kbinfo = "全部课表:\n";

if (isset($_GET['day']) && ($_GET['day'] != '') && (($_GET['day'] >= 1) && ($_GET['day'] <= 5))) {
    $day = $_GET['day'];
}
if (isset($_GET['day']) && ($_GET['day'] == 'all')) {
    $all = 'all';
}

$url  = 'http://av.jejeso.com/helper/api/jwcapi.php?xh=' . $xh . '&pw=' . $pw . '&flag=1';
$info = file_get_contents($url);
$info = json_decode($info, true);
$xm   = $info['xm'];
$bj   = $info['bjmc'];

if ((!$xm) || (!$bj)) {
    echo "【账号】或者【密码】错误";
    return;
}


$date    = date("w");
$dayinfo = "【现已支持所有校区】\n$bj\n [" . $xm . "] 童鞋\n" . "今天是 星 期 ";
echo $dayinfo .= day($date) . "\n\n";

$where = "WHERE  `gdpukb_name` LIKE  '$bj'";
if ($day != 0 && $day != 6 && $all != 'all') {
    $where .= "AND  `gdpukb_date` = $day-1";
    $kbinfo = "课表:\n";
}
echo $kbinfo;


$sql     = "select * from gdpukb ";
$sql .= $where;
$list = pdo_fetchall($sql);
//print_r($list);


$str  = '';
$list = array_chunk($list, 8);

foreach ($list as $a => $b) {
    $morning   = "-----上午-----\n";
    $afternoon = "-----下午-----\n";
    foreach ($b as $k => $v) {
        if ($v['gdpukb_num'] <= 3) {
            $morning .= $v['gdpukb_content'] . "\n";
        } else {
            $afternoon .= $v['gdpukb_content'] . "\n";
        }
    }

    if ($day != 0 & $day != 6 && $all != 'all') {
        $str .= "【 星 期 " . day($day) . " 】\n" . $morning . $afternoon . "\n";
    } else {
        $str .= "【 星 期 " . day(($a + 1)) . " 】\n" . $morning . $afternoon . "\n";
    }
    
}
echo $str;
/*添加选修模块
$xuanxiu=xuanxiu($xh,$pw);
echo $str.$xuanxiu;
*/
function day($date)
{
    if ($date == 0) {
        $day = "天";
    }
    if ($date == 1) {
        $day = "一";
    }
    if ($date == 2) {
        $day = "二";
    }
    if ($date == 3) {
        $day = "三";
    }
    if ($date == 4) {
        $day = "四";
    }
    if ($date == 5) {
        $day = "五";
    }
    if ($date == 6) {
        $day = "六";
    }
    return $day;
}
function xuanxiu($xh,$pw,$nf='学年:2013-2014')
{
$api_url='http://av.jejeso.com//helper/api/jwcapi.php?xh='.$xh.'&pw='.$pw.'&flag=3';
$str=file_get_contents($api_url);

		$str=str_replace("<tr>  		<td>","【",$str);
		$str=str_replace('<tr bgcolor="#EEF3F9">  		<td>',"【",$str);
		$str=str_replace('</td>  	</tr>',"】",$str);
		$str=str_replace('</td><td>',"#",$str);
		$str=explode("【",$str);
		$string='';
		foreach($str as $key=>$a){
			if($key>=1){
			$b=explode("#",$a);
			$string .= "学年:".$b[0]."\n".'学期:'.$b[1]."\n".'课程名称:'.$b[4]."\n".'学分:'.$b[5]."\n".'起止周:'.$b[7]."\n".'上课时间:'.$b[8]."\n".'上课地点:'.$b[9]."\n".'教师姓名:'.$b[10]."\n\n";
			}
		}
		$str2=explode($nf,$string);
		$string='';
		foreach($str2 as $key=>$a){
			if($key>=1){
			$a=$nf.$a;
			$string=$string.$a;
			}
        }

	$string = "----今年选修情况----\n".$string;
	return $string;
}
?>
