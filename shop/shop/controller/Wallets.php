<?php
namespace shop\shop\controller;
use shop\common\model\Shops as SM;
use shop\common\model\Users as UM;
/**
 * * 余额控制器
 */
class Wallets extends Base{
	/**
	 * 生成支付代码
	 */
	function getWalletsUrl(){
		$needPay = input('needPay');
        $pkey = WSTBase64urlEncode($needPay."@1");
        $data = [];
        $data['status'] = 1;
        $data['url'] = url('shop/wallets/payment','pkey='.$pkey,'html',true);
		return $data;
	}
	
	/**
	 * 跳去支付页面
	 */
	public function payment(){
		if((int)session('tb_USER.userId')==0){
			$this->assign('message',"对不起，您尚未登录，请先登录!");
            return $this->fetch('error_msg');
		}
		$userId = (int)session('tb_USER.userId');
		$shopId = (int)session('tb_USER.shopId');
		$m = new UM();
		$user = $m->getFieldsById($userId,["payPwd"]);
		$this->assign('hasPayPwd',($user['payPwd']!="")?1:0);
		$pkey = input('pkey');
		$this->assign('pkey',$pkey);
        $pkey = WSTBase64urlDecode($pkey);
        $pkey = explode('@',$pkey);
        $needPay = $pkey[0];
        $this->assign('needPay',$needPay);
        //获取商家钱包
        $shop = model('common/shops')->getFieldsById($shopId,'shopMoney');
        $this->assign('shopMoney',$shop['shopMoney']);
        return $this->fetch('renew/pay_wallets');
	}

	/**
	 * 钱包支付
	 */
	public function payByWallet(){
		$m = new SM();
        $pkey = WSTBase64urlDecode(input('pkey'));
        $pkey = explode('@',$pkey);
        $obj = array ();
        $obj["orderNo"] = WSTOrderNo();
        $obj["targetId"] = (int)session('tb_USER.shopId');
        $obj["targetType"] = 1;
        $obj["total_fee"] = $pkey[0];
        $obj["scene"] = 'renew';
        return $m->payByWallet($obj);
	}

}
