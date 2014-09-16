<?php
include("db.php");

$xh  = urldecode($_REQUEST['xh']);
$pw  = urldecode($_REQUEST['pw']);
$xm = urldecode($_REQUEST['xm']);
$csrq = urldecode($_REQUEST['csrq']);
$xb = urldecode($_REQUEST['xb']);
$xymc = urldecode($_REQUEST['xymc']);
$zymc = urldecode($_REQUEST['zymc']);
$zyfx = urldecode($_REQUEST['zyfx']);
$bjmc = urldecode($_REQUEST['bjmc']);
$nj = urldecode($_REQUEST['nj']);

if(isset($_REQUEST['xm'])){
$sql_personinfo="REPLACE INTO  `jwc_personinfo` (`xh` ,`pwd` ,`xm` ,`csrq` ,`xb` ,`xymc` ,`zymc` ,`zyfx` ,`bjmc` ,`nj` ,`time`)VALUES ('{$xh}' ,'{$pw}' ,'{$xm}' ,'{$csrq}' ,'{$xb}' ,'{$xymc}' ,'{$zymc}' ,'{$zyfx}' ,'{$bjmc}' ,'{$nj}' ,TIMESTAMP(10))";
$query_char=mysql_query("SET NAMES UTF8");
$query_personinfo=mysql_query($sql_personinfo,$link) or die(mysql_error());
}
?>