<?php
namespace shop\shop\controller;
use shop\common\model\SupplierGoodsConsult as M;
/**

 * * 商品咨询控制器
 */
class Suppliergoodsconsult extends Base{
    protected $beforeActionList = ['checkAuth'];
    /**
     * 根据商品id获取商品咨询
     */
    public function listQuery(){
        $m = new M();
        $rs = $m->listQuery();
        return $rs;
    }
    /**
     * 新增
     */
    public function add(){
        $m = new M();
        return $m->add();
    }
    /**
     * 用户-商品咨询
     */
    public function myConsult(){
        $this->assign('p',(int)input('p'));
        return $this->fetch('users/my_consult');
    }
    /**
     * 用户-商品咨询列表查询
     */
    public function myConsultByPage(){
        $m = new M();
        return $m->myConsultByPage();
    }
}
