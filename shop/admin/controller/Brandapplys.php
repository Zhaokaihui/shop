<?php
namespace shop\admin\controller;
use shop\admin\model\BrandApplys as M;
/**
 * * * * * * * 品牌申请控制器
 */
class Brandapplys extends Base{
    public function index(){
    	$this->assign("p",(int)input("p"));
    	return $this->fetch("list");
    }
    
    /**
     * 获取分页
     */
    public function pageQuery(){
    	$m = new M();
    	$rs = $m->pageQuery();
    	return WSTGrid($rs);
    }
    
    /**
     * 获取品牌
     */
    public function get(){
    	$m = new M();
    	$rs = $m->get((int)Input("post.id"));
    	return $rs;
    }
    
    /**
     * 跳去新增/编辑页面
     */
    public function toEdit(){
    	$id = Input("get.id/d",0);
    	$m = new M();
    	if($id>0){
    		$object = $m->getById($id);
    	}else{
    		$object = $m->getEModel('brand_applys');
    	}
    	$this->assign("p",(int)input("p"));
        $this->assign('object',$object);
        $this->assign('gcatList',model('GoodsCats')->listQuery(0));
        return $this->fetch("edit");
    }

    /**
     * 去处理品牌申请
     */
    public function handleApply(){
        $m = new M();
        return $m->handleApply();
    }

    /**
     * 删除
     */
    public function del(){
        $m = new M();
        $rs = $m->del();
        return $rs;
    }

    /*
     * 删除品牌下的商家
     */
    public function delShop(){
        $m = new M();
        $rs = $m->delShop();
        return $rs;
    }

    /**
     * 根据品牌名称查找品牌信息
     */
    public function getBrandByKey(){
        $m = new M();
        $rs = $m->getBrandByKey();
        return $rs;
    }
}
