<?php
$url='http://branch2.gdpu.edu.cn/jyzd/index.php';
$str=file_get_contents($url);
$search = '/<ul class="GsTL1 large-6 columns">(.*?)<\/ul>/is';
preg_match_all($search,$str,$r,PREG_SET_ORDER );
$pat = '/<a(.*?)href="(.*?)"(.*?)>(.*?)<\/a>/i';
preg_match_all($pat, $r[0][0],$match, PREG_SET_ORDER);
$str='';
foreach($match as $key=>$row){
	// if($key<9){
	// 	$str=$str.'@title|'.($key+1).'.'.$row[4].'#url|http://av.jejeso.com/helper/api/jobs/'.$row[2].'#pic';
	// }
	if($key<9){
		$str=$str.'@title|'.($key+1).'.'.$row[4].'#url|http://branch2.gdpu.edu.cn/jyzd/'.$row[2].'#pic';
	}
	
}
echo 'title|就业信息'.$str;
?>