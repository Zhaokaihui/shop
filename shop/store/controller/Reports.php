<?php
namespace shop\store\controller;
use shop\store\model\Reports as M;
/**
 * * 报表控制器
 */
class Reports extends Base{
    protected $beforeActionList = ['checkAuth'];
	
    public function getTopSaleGoods(){
    	$n = new M();
        return WSTGrid($n->getTopSaleGoods());
    }
   
    public function getStatSales(){
    	$m = new M();
        return $m->getStatSales();
    }

}
