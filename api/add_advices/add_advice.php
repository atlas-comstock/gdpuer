<?php
include '../../../includes/init.php';
$url = "http://av.jejeso.com/helper/api/add_advices/thanks.html";
$content = file_get_contents($url);
echo $content;
$name = $_REQUEST['name'];
$dorm = $_REQUEST['dorm'];
$phone = $_REQUEST['phone'];
$advice = $_REQUEST['advice'];
$field_values = array( "name" => $name, "dorm" =>$dorm , "phone" =>$phone, "advice" =>$advice);  
$db->autoExecute("advices", $field_values); 
?>