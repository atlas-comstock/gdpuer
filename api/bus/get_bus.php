<?php
$city = $_GET [city];
$no = $_GET [no];
$url = "http://openapi.aibang.com/bus/lines?app_key=f41c8afccc586de03a99c86097e98ccb&city=" . $city . "&q=" . $no;
$result = file_get_contents ( $url );
$xml = simplexml_load_string ( $result );
$final = str_replace ( ";", "->", $xml->lines->line->stats );
echo $final;
?>