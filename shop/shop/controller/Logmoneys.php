<?php
namespace shop\shop\controller;
/**

 * * 资金流水控制器
 */
class Logmoneys extends Base{
    protected $beforeActionList = ['checkAuth'];

    /**
     * 查看商家资金流水
     */
    public function shopmoneys(){
        $rs = model('Shops')->getFieldsById((int)session('tb_USER.shopId'),['lockMoney','shopMoney','noSettledOrderFee','paymentMoney']);
        $this->assign('object',$rs);
        return $this->fetch('logmoneys/list');
    }
    /**
     * 获取商家数据
     */
    public function pageShopQuery(){
        $shopId = (int)session('tb_USER.shopId');
        $data = model('logMoneys')->pageQuery(1,$shopId);
        return WSTGrid($data);
    }

	/**
	 * 充值[商家]
	 */
    public function toRecharge(){
        if((int)WSTConf('CONF.isOpenRecharge')==0)return;
    	$payments = model('common/payments')->recharePayments('1');
    	$this->assign('payments',$payments);
        $chargeItems = model('common/ChargeItems')->queryList();
        $this->assign('chargeItems',$chargeItems);
    	return $this->fetch('recharge/pay_step1');
    }

    /**
     * 缴纳年费[商家]
     */
    public function toRenew(){
        $payments = model('common/payments')->getOnlinePayments();
        $this->assign('payments',$payments);
        $catShopInfo = model('shops')->getCatShopInfo();
        $needPay = $catShopInfo['needPay'];
        $shopId = (int)session('tb_USER.shopId');
        $data = model('common/shops')->getFieldsById($shopId,'expireDate');
        $this->assign('data',$data);
        $this->assign('catShopInfo',$catShopInfo);
        $this->assign('needPay',$needPay);
        return $this->fetch('renew/pay_step1');
    }
}
