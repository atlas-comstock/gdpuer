<?php
$per_info_url='ttp://127.0.0.1/helper/api/chengji/personinfo.php?xh='.$xh.'&pw='.$pw;
	echo	$ret_personinfo=file_get_contents($per_info_url);
?>