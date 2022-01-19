<?php
namespace shop\shop\controller;
use shop\shop\model\SupplierGoodsAppraises as M;
/**

 * * 评价控制器
 */
class Suppliergoodsappraises extends Base{
	protected $beforeActionList = ['checkAuth'];
	
    public function index(){
    	return $this->myAppraise();
    }
	/**
	* 获取评价列表 用户
	*/
	public function myAppraise(){
        $this->assign("p",(int)input("p"));
		return $this->fetch('supplier/orders/appraise_manage');
	}

	// 获取评价列表 用户
	public function userAppraise(){
		$m = new M();
		return $m->userAppraise();
	}
	/**
	* 添加评价
	*/
	public function add(){
		$m = new M();
		$rs = $m->add();
		return $rs;

	}
	/**
	* 根据商品id取评论
	*/
	public function getById(){
		$m = model("common/SupplierGoodsAppraises");
		$goodsId = (int)input("goodsId");
		$rs = $m->getById($goodsId);
		return $rs;
	}


}
