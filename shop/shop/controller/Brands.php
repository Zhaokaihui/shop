<?php
namespace shop\shop\controller;
use shop\common\model\Brands as M;
/**

 * * 品牌控制器
 */
class Brands extends Base{
    protected $beforeActionList = ['checkAuth'];
    /**
     * 获取品牌列表
     */
    public function listQuery(){
        $m = new M();
        $shopId = (int)session('tb_USER.shopId');
        return ['status'=>1,'list'=>$m->shopBrandListQuery($shopId,input('post.catId/d'))];
    }
}
