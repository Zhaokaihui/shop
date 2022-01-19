<?php
use think\Db;
/**
 * * * * * * */

/**
* 删除app端
*/
function delAppToken($userId){
	try{
		$prefix = config('database.prefix');
		
		// 删除app端的token
		$appTableName = $prefix."app_session";
		$rs = Db::query("SHOW TABLES like '{$appTableName}'");
		if(!empty($rs))Db::name('app_session')->where(['userId'=>$userId])->delete();

		// 删除小程序端的token
		$weAppTableName = $prefix."weapp_session";
		$rs = Db::query("SHOW TABLES like '{$weAppTableName}'");
		if(!empty($rs))Db::name('weapp_session')->where(['userId'=>$userId])->delete();
	}catch(\Exception $e){

	}
}


/**
 * 微信配置
 */
function WXAdmin(){
	$wechat = new \wechat\WSTWechat(WSTConf('CONF.wxAppId'),WSTConf('CONF.wxAppKey'));
	return $wechat;
}

/**
 * 密码规则检测
 */
function WSTCheckPwdRule($pwd){
	$reg = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*[1-9])(?=.*[\W|_]).{8,}$/";
    $rs = Validate::regex($pwd,$reg);
    return $rs?true:'密码必须是包含大小写字母、数字、符号,且长度为8-20位';
}