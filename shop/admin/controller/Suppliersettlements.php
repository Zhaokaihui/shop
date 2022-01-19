<?php
namespace shop\admin\controller;
use shop\admin\model\SupplierSettlements as M;
/**
 * * 结算控制器
 */
class Suppliersettlements extends Base{
    public function index(){
        $this->assign("p",(int)input("p"));
    	return $this->fetch('list');
    }

    /**
     * 获取列表
     */
    public function pageQuery(){
    	$m = new M();
    	return WSTGrid($m->pageQuery());
    }
    
    /**
     *  跳去结算详情
     */
    public function toView(){
    	$m = new M();
    	$object = $m->getById();
        $this->assign("p",(int)input("p"));
    	$this->assign("object",$object);
    	return $this->fetch('view');
    }

    /**
     * 获取订单商品
     */
    public function pageGoodsQuery(){
        $m = new M();
        return WSTGrid($m->pageGoodsQuery());
    }


    /**
     * 进入平台结算野蛮
     */
    public function toSupplierIndex(){
        $this->assign("areaList",model('areas')->listQuery(0));
        $this->assign("p",(int)input("p"));
        return $this->fetch('list_supplier');
    }

    /**
     * 获取待结算的商家列表
     */
    public function pageSupplierQuery(){
        $m = new M();
        return WSTGrid($m->pageSupplierQuery());
    }
    /**
     * 进入订单列表页面
     */
    public function toOrders(){
        $this->assign("id",(int)input('id'));
        $this->assign("p",(int)input("p"));
        return $this->fetch('list_order');
    }
    /**
     * 获取商家的待结算订单列表
     */
    public function pageSupplierOrderQuery(){
        $m = new M();
        return WSTGrid($m->pageSupplierOrderQuery());
    }
    
    /**
     * 导出
     */
    public function toExport(){
        $m = new M();
        $rs = $m->toExport();
        $this->assign('rs',$rs);
    }
}
