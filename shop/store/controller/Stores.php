<?php
namespace shop\store\controller;
use shop\store\model\Stores as M;
/**
 * * 门店控制器
 */

class Stores extends Base{
    protected $beforeActionList = ['checkAuth'];
    /**
    * 店铺公告页
    */
    public function index(){
       
    }
    
}
