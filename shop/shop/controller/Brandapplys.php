<?php
namespace shop\shop\controller;
use shop\shop\model\BrandApplys as M;
/**

 * * 品牌申请控制器
 */
class Brandapplys extends Base{
    protected $beforeActionList = ['checkAuth'];
    public function index(){
        $this->assign("p",(int)input("p"));
        return $this->fetch("brandapplys/list");
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
        return $this->fetch("brandapplys/edit");
    }

    /*
     * 查看品牌下的商家
     */
    public function toView(){
        $this->assign("brandId",(int)input("id"));
        $this->assign("p",(int)input("p"));
        return $this->fetch("brandapplys/view");
    }

    /**
     * 获取品牌下的商家分页
     */
    public function shopPageQuery(){
        $m = new M();
        $rs = $m->shopPageQuery();
        return WSTGrid($rs);
    }

    /**
     * 新增
     */
    public function add(){
        $m = new M();
        $rs = $m->add();
        return $rs;
    }


    /**
     * 编辑
     */
    public function edit(){
        $m = new M();
        $rs = $m->edit();
        return $rs;
    }

    /**
     * 删除
     */
    public function del(){
        $m = new M();
        $rs = $m->del();
        return $rs;
    }

    /**
     * 根据品牌名称模糊查找品牌信息
     */
    public function getBrandByKey(){
        $m = new M();
        $rs = $m->getBrandByKey();
        return $rs;
    }

    /**
     * 获取品牌信息
     */
    public function getBrandInfo(){
        $m = new M();
        $rs = $m->getBrandInfo();
        return $rs;
    }
}
