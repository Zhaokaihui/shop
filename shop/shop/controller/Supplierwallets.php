<?php
namespace shop\shop\controller;
use shop\shop\model\SupplierOrders as OM;
use shop\common\model\Users as UM;
/**
 * * 余额控制器
 */
class SupplierWallets extends Base{
	/**
	 * 生成支付代码
	 */
	function getWalletsUrl(){
		$pkey = input('pkey');
        $data = [];
        $data['status'] = 1;
        $data['url'] = url('shop/supplierwallets/payment','pkey='.$pkey,'html',true);
		return $data;
	}
	
	/**
	 * 跳去支付页面
	 */
	public function payment(){
		if((int)session('tb_USER.userId')==0){
			$this->assign('message',"对不起，您尚未登录，请先登录!");
            return $this->fetch('supplier/error_msg');
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
        $data = [];
        $data['orderNo'] = $pkey[0];
        $data['isBatch'] = (int)$pkey[1];
        $data['userId'] = $userId;
        $data['shopId'] = $shopId;
		$m = new OM();
		$rs = $m->getOrderPayInfo($data);
		if(empty($rs)){
			$this->assign('message',"您的订单已支付，请勿重复支付~");
            return $this->fetch('supplier/error_msg');
		}else{
			$this->assign('needPay',$rs['needPay']);
			//获取用户钱包
			$shop = model('shops')->getFieldsById($shopId,'shopMoney');
			$this->assign('shopMoney',$shop['shopMoney']);
	        return $this->fetch('supplier/order_pay_wallets');
	    }
	}

	/**
	 * 钱包支付
	 */
	public function payByWallet(){
		$m = new OM();
        return $m->payByWallet();
	}

}
