<?php
namespace shop\admin\controller;
use shop\admin\model\LogShopOperates as M;
/**
 * * 用户登录日志控制器
 */
class Logshopoperates extends Base{
	
    public function index(){
    	return $this->fetch("list");
    }
    
    /**
     * 获取分页
     */
    public function pageQuery(){
    	$m = new M();
    	return WSTGrid($m->pageQuery());
    }

}
