<?php
include("wechatext.class.php");
global $_W;
$rid = 15;
$content = $this->message['content'];
$from_user=$this->message['from'];

		$sql = "SELECT * FROM " . tablename('stubind_reply') . " WHERE `weid`=:weid LIMIT 1";
		$row = pdo_fetch($sql, array(':weid' => $_W['weid']));
	
	//自动获取用户信息
		$options = array(
		'account'=> $row['account'] ,
		'password'=>$row['password'],
		'datapath'=>$_W['attachurl'].'cookie_',
		); 
		$wechat = new Wechatext($options);
		
	$sql = "SELECT * FROM " . tablename('stu_profile') . " WHERE `from_user`=:from_user LIMIT 1";
	$row_info = pdo_fetch($sql, array(':from_user' => $from_user));
	
		if (!empty($row_info['fakeid'])) {
	
		
			if ($wechat->checkValid()) {
		
						$fakeid=$row_info['fakeid'];//fakeid
				
						$send=$wechat->send($fakeid,'测试');
						$se=json_decode($send,true);
						if($se['base_resp']['err_msg']=='ok'){
						return $this->respText('OK');
						}else{
						return $this->respText('NO');
						}
			}

		
		}
	
