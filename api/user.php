<?php
class user{
    function __construct(){
        $con = mysql_connect('localhost','christopher','wudbadmin')or die(mysql_error());
        mysql_select_db('ours');
        mysql_query("SET NAMES 'UTF8'");
    }

    public function blind($name, $num, $pwd){
        $sql = "insert into `gdpuer_user` values('', '$name', '$num', '$pwd')";
        mysql_query($sql);
    }

    public function reblind($name, $num, $pwd){
        $name = '"'.$name.'"';
        mysql_query("UPDATE `gdpuer_user` SET num = $num, pw = $pw WHERE name = '$name' ");
    }

    public function get_num($name){
        $name = '"'.$name.'"';
        $sql = "SELECT * FROM `gdpuer_user` WHERE name = $name";
        $result = mysql_query($sql);
        $data = mysql_fetch_array($result);
        $xh = $data['number'];
        return $xh;
    }

    public function get_pw($name){
        $name = '"'.$name.'"';
        $sql = "SELECT * FROM `gdpuer_user` WHERE name = $name";
        $result = mysql_query($sql );
        $row = mysql_fetch_array($result);
        $pw = $row['pw'];
        return $pw;
    }
}
?>
