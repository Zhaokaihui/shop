<?php
namespace shop\store\controller;
use shop\common\model\ShopCats as M;
/**

 * * 门店分类控制器
 */
class Shopcats extends Base{
    protected $beforeActionList = ['checkAuth'];

    /**
     * 列表查询
     */
    public function listQuery(){
        $m = new M();
        $list = $m->listQuery((int)session('tb_STORE.shopId'),input('post.parentId/d'));
        $rs = array();
        $rs['status'] = 1;
        $rs['list'] = $list;
        return $rs;
    }

}
