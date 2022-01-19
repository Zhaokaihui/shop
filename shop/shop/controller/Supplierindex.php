<?php
namespace shop\shop\controller;
use shop\shop\model\SupplierGoods as M;
/**
 * * 商品控制器
 */
class Supplierindex extends Base{
    protected $beforeActionList = ['checkAuth'];
   /**
	* 查看上架商品列表
	*/
	public function index(){
    	//获取商品记录
    	$m = new M();
    	$data = [];
        $catId = (int)input('catId');
        $goodsCatIds = [];
        if($catId>0){
            $goodsCatIds = model('GoodsCats')->getParentIs($catId);
        }
        reset($goodsCatIds);
        $data['catId'] = $catId;
    	$data['isStock'] = (int)Input('isStock/d');
    	$data['isNew'] = (int)Input('isNew/d');
        $data['isFreeShipping'] = (int)input('isFreeShipping/d');
    	$data['orderBy'] = (int)Input('orderBy/d');
    	$data['order'] = (int)Input('order/d',1);
    	$data['keyword'] = WSTReplaceFilterWords(input('keyword'),WSTConf("CONF.limitWords"));
    	$data['minPrice'] = (int)Input('minPrice/d');
    	$data['maxPrice'] = (int)Input('maxPrice/d');
        $data['areaId'] = (int)Input('areaId');
        $aModel = model('common/areas');

        // 获取地区
        $data['area1'] = $data['area2'] = $data['area3'] = $aModel->listQuery(); // 省级

        // 如果有筛选地区 获取上级地区信息
        
        if($data['areaId']!==0){
            $areaIds = $aModel->getParentIs($data['areaId']);
            /*
              2 => int 440000
              1 => int 440100
              0 => int 440106
            */
            $selectArea = [];
            $areaName = '';
            foreach($areaIds as $k=>$v){
                $a = $aModel->getById($v);
                $areaName .=$a['areaName'];
                $selectArea[] = $a;
            }
            // 地区完整名称
            $selectArea['areaName'] = $areaName;
            // 当前选择的地区
            $data['areaInfo'] = $selectArea;
            $data['area2'] = $aModel->listQuery($areaIds[2]); // 广东的下级
 
            $data['area3'] = $aModel->listQuery($areaIds[1]); // 广州的下级
        }
        
        $cats = WSTGoodsCats(0);
        $catName = '全部商品分类';
        foreach($cats as $k => $v){
           if($catId==$v['catId'])$catName = $v['catName'];
        }
        $data['catName'] = $catName;
        $data['catList'] = $cats;
    	$data['goodsPage'] = $m->pageQuery($goodsCatIds);
		return $this->fetch('supplier/index',$data);
	}
  
}
