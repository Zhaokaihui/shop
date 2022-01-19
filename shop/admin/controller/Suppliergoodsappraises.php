<?php
namespace shop\admin\controller;
use shop\admin\model\SupplierGoodsAppraises as M;
/**
 * * * * * * * 商品评价控制器
 */
class Suppliergoodsappraises extends Base{
	
    public function index(){
        //获取地区
        $area1 = model('areas')->listQuery(0);
        $this->assign("p",(int)input("p"));
        return $this->fetch("list",['area1'=>$area1,]);
    }
    /**
     * 获取分页
     */
    public function pageQuery(){
        $m = new M();
        return WSTGrid($m->pageQuery());
    }
    /**
     * 跳去编辑页面
     */
    public function toEdit(){
        $m = new M();
        $data = $m->getById(input("get.id/d",0));
        if($data['images']!='')
            $data['images'] = explode(',',$data['images']);
        $assign = ['data'=>$data];
        $this->assign("p",(int)input("p"));
        return $this->fetch("edit",$assign);
    }
    /**
    * 修改
    */
    public function edit(){   
        $m = new M();
        return $m->edit();
    }
    /**
     * 删除
     */
    public function del(){
        $m = new M();
        return $m->del();
    }

    /**
    * 根据商品id取评论
    */
    public function getById(){
        $m = model("common/SupplierGoodsAppraises");
        $goodsId = (int)input("goodsId");
        $rs = $m->getById();
        return $rs;
    }
}
