<?php 
$matchs = array();
$ret = preg_match('/^(?P<express>申通|ems|EMS|顺丰|圆通|中通|韵达|天天|汇通|全峰|德邦|宅急送|包裹平邮|邦送物流|百世汇通|DHL快递|大田物流|德邦物流|EMS国内|EMS国际|E邮宝|凡客配送|国通快递|挂号信|共速达|国际小包|汇通快递|华宇物流|汇强快递|佳吉快运|佳怡物流|加拿大邮政|快捷速递|龙邦速递|联邦快递|联昊通|能达速递|如风达|瑞典邮政|全一快递|全峰快递|全日通|申通快递|顺丰快递|速尔快递|TNT快递|天天快递|天地华宇|UPS快递|新邦物流|新蛋物流|香港邮政|圆通快递|韵达快递|邮政包裹|优速快递|中通快递|中铁快运|中邮物流|快递)(.*)/i', $this->message['content'], $matchs);
$express = $matchs['express'];
$kuaid_num = $matchs[2];
$mappings = Array
(
    '申通' => 'shentong',
    'EMS' => 'ems',
    '顺丰' => 'shunfeng',
    '圆通' => 'yuantong',
    '中通' => 'zhongtong',
    '韵达' => 'yunda',
    '天天' => 'tiantian',
    '汇通' => 'huitongkuaidi',
    '全峰' => 'quanfengkuaidi',
    '德邦' => 'debangwuliu',
    '宅急送' => 'zhaijisong',
    '包裹平邮' => 'youzhengguonei',
    '邦送物流' => 'bangsongwuliu',
    '百世汇通' => 'huitongkuaidi',
    'DHL快递' => 'dhl',
    '大田物流' => 'datianwuliu',
    '德邦物流' => 'debangwuliu',
    'EMS国内' => 'ems',
    'EMS国际' => 'emsguoji',
    'E邮宝' => 'ems',
    '凡客配送' => 'rufengda',
    '国通快递' => 'guotongkuaidi',
    '挂号信' => 'youzhengguonei',
    '共速达' => 'gongsuda',
    '国际小包' => 'youzhengguoji',
    '汇通快递' => 'huitongkuaidi',
    '华宇物流' => 'tiandihuayu',
    '汇强快递' => 'huiqiangkuaidi',
    '佳吉快运' => 'jiajiwuliu',
    '佳怡物流' => 'jiayiwuliu',
    '加拿大邮政' => 'canpost',
    '快捷速递' => 'kuaijiesudi',
    '龙邦速递' => 'longbanwuliu',
    '联邦快递' => 'lianbangkuaidi',
    '联昊通' => 'lianhaowuliu',
    '能达速递' => 'ganzhongnengda',
    '如风达' => 'rufengda',
    '瑞典邮政' => 'ruidianyouzheng',
    '全一快递' => 'quanyikuaidi',
    '全峰快递' => 'quanfengkuaidi',
    '全日通' => 'quanritongkuaidi',
    '申通快递' => 'shentong',
    '顺丰快递' => 'shunfeng',
    '速尔快递' => 'suer',
    'TNT快递' => 'tnt',
    '天天快递' => 'tiantian',
    '天地华宇' => 'tiandihuayu',
    'UPS快递' => 'ups',
    '新邦物流' => 'xinbangwuliu',
    '新蛋物流' => 'neweggozzo',
    '香港邮政' => 'hkpost',
    '圆通快递' => 'yuantong',
    '韵达快递' => 'yunda',
    '邮政包裹' => 'youzhengguonei',
    '优速快递' => 'youshuwuliu',
    '中通快递' => 'zhongtong',
    '中铁快运' => 'zhongtiewuliu',
    '中邮物流' => 'zhongyouwuliu'
);

$support="申通|EMS|顺丰|圆通|中通|韵达|天天|汇通|全峰|德邦|宅急送|包裹平邮|邦送物流|百世汇通|DHL快递|大田物流|德邦物流|EMS国内|EMS国际|E邮宝|凡客配送|国通快递|挂号信|共速达|国际小包|汇通快递|华宇物流|汇强快递|佳吉快运|佳怡物流|加拿大邮政|快捷速递|龙邦速递|联邦快递|联昊通|能达速递|如风达|瑞典邮政|全一快递|全峰快递|全日通|申通快递|顺丰快递|速尔快递|TNT快递|天天快递|天地华宇|UPS快递|新邦物流|新蛋物流|香港邮政|圆通快递|韵达快递|邮政包裹|优速快递|中通快递|中铁快运|中邮物流";
$MOREN="1、[智能识别]\n\n只需输入, 快递+单号, 例如: \n\n快递768142203911\n\n2、[手动识别]\n目前支持\n\n$support\n\n格式输入，公司+单号, 例如: \n\n申通1200041125";
if(!$kuaid_num) {
	return $this->respText($MOREN);
}




if($express=='快递'){
$kuaidi_auto = json_decode(file_get_contents('http://www.kuaidi100.com/autonumber/auto?num=' .$kuaid_num),1);
$type = $kuaidi_auto['0']['comCode'];
}else{
$type= $mappings[$express];
}
$info='快递公司：' .getkey($mappings,$type)."\n单号：" .$kuaid_num."\n";
$url='http://baidu.kuaidi100.com/query?type=' .$type. '&postid=' .$kuaid_num;
$dat = ihttp_get($url);
if(!empty($dat) && !empty($dat['content'])) {
	$traces = json_decode($dat['content'], true);
	if(is_array($traces)) {
		if($traces['message']) {
			$msg = $traces['message'];
		}
		$traces = $traces['data'];
		if(is_array($traces)) {
			$traces = array_reverse($traces);
			$reply = '';
			foreach($traces as $trace) {
				$reply .= "{$trace['time']}\n{$trace['context']}\n\n";
			}
			if(!empty($reply)) {
				$replys = array();
				$replys[] = array(
					'title' => '已经为你查到相关快递记录',
					'picurl' => $GLOBALS['_W']['siteroot'] . '/source/modules/userapi/images/'.$type.'.png',
					'description' => $reply,
				);
				//return $this->respNews($replys);
				$reply = $info."\n已经为你查到相关快递记录: \n\n" . $reply;
				return $this->respText($reply);
			}
		}
	}
}
if($msg) {
	$msg = ", 错误信息为: \n【". $msg.'】';
}
return $this->respText("[如果发现智能识别快递公司错误]请输入快递公司名+单号，如中通768142203911\n\n".$reply."\n没有查找到相关的数据" . $msg . "\n\n支持的快递有\n".$support);

function getkey($array,$value){
while ($v = current($array)) {
    if ($v == $value) {
        return key($array);
    }
    next($array);
}
}
