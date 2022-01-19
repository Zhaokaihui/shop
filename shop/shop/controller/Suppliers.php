<?php
namespace shop\shop\controller;
use think\Db;
use shop\shop\model\SupplierGoods;
use shop\common\model\GoodsCats;
use think\Loader;
/**
 * * 门店控制器
 */

class Suppliers extends Base{
    protected $beforeActionList = ['checkAuth'];
    /**
     * 店铺详情
     */
    public function index(){
    	$supplierId = (int)input("supplierId");
    	$s = model('suppliers');
    	$data['supplier'] = $s->getSupplierInfo($supplierId);
    	if(empty($data['supplier']))return $this->fetch('supplier/error_lost');
    	$g = model('SupplierGoods');
    	$data['list'] = $g->supplierGoods($supplierId);
        $supplierCats= $g->listSupplierCats(0,8,$supplierId);
        $this->assign('supplierCats',$supplierCats);
    	$this->assign('data',$data);
        $this->assign('goodsName',input("goodsName"));//筛选条件
        $this->assign('msort',(int)input("param.msort",0));//筛选条件
        $this->assign('mdesc',(int)input("param.mdesc",1));//升降序
        $this->assign('sprice',input("param.sprice"));//价格范围
        $this->assign('eprice',input("param.eprice"));
        $this->assign('ct1',(int)input("param.ct1",0));//一级分类
        $this->assign('ct2',(int)input("param.ct2",0));//二级分类
        $this->assign('supplierId',$supplierId);//店铺id
    	return $this->fetch('supplier/supplier_home');
    }
    
    /**
     * 店铺分类
     */
    public function goods(){
    	$s = model('suppliers');
    	$supplierId = (int)input("param.supplierId/d");
    	$data['supplier'] = $s->getShopInfo($supplierId);
    	$ct1 = input("param.ct1/d",0);
    	$ct2 = input("param.ct2/d",0);
    	$goodsName = input("param.goodsName");
    	if(empty($data['supplier']))return $this->fetch('error_lost');
    	$g = model('goods');
    	$data['list'] = $g->supplierGoods($supplierId);
    	$this->assign('msort',input("param.msort/d",0));//筛选条件
    	$this->assign('mdesc',input("param.mdesc/d",1));//升降序
    	$this->assign('sprice',input("param.sprice"));//价格范围
    	$this->assign('eprice',input("param.eprice"));
    	$this->assign('ct1',$ct1);//一级分类
    	$this->assign('ct2',$ct2);//二级分类
    	$this->assign('goodsName',urldecode($goodsName));//搜索
    	$this->assign('data',$data);
    	return $this->fetch('supplier/supplier_goods_list');
    }

}
