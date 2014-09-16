<?php 
include '../../../includes/init.php';
$name = $_REQUEST['name'];
$dorm = $_REQUEST['dorm'];
$phone = $_REQUEST['phone'];
$advice = $_REQUEST['advice'];
$field_values = array( "name" => $name, "dorm" =>$dorm , "phone" =>$phone, "advice" =>$advice);  
$db->autoExecute("advices", $field_values); 
?>