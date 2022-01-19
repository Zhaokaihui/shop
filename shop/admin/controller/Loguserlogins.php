<?php
namespace shop\admin\controller;
use shop\admin\model\LogUserLogins as M;
/**
 * * 用户登录日志控制器
 */
class Loguserlogins extends Base{
	
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

    public function shopIndex(){
        return $this->fetch("logshoplogins/list");
    }
    
    /**
     * 获取分页
     */
    public function shopPageQuery(){
        $m = new M();
        return WSTGrid($m->shopPageQuery());
    }
}
