<?php 
header("Content-Type:text/html;charset=utf-8"); 
$number = $_GET['number']; 
if (isset($_GET['number'])) { 
$url = 'http://webservice.webxml.com.cn/WebServices/MobileCodeWS.asmx/getMobileCodeInfo'; 
$ch = curl_init(); 
curl_setopt($ch, CURLOPT_URL, $url); 
curl_setopt($ch, CURLOPT_POST, true); 
curl_setopt($ch, CURLOPT_POSTFIELDS, "mobileCode={$number}&userId="); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
$data = curl_exec($ch); 
curl_close($ch); 
$data = simplexml_load_string($data); 
if (strpos($data, 'http://')) { 
echo '手机号码格式错误!'; 
} else { 
echo $data; 
} 
} 
?>