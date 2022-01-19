<?php
namespace shop\admin\controller;
use shop\admin\model\Styles as M;
/**
 * * 风格配置控制器
 */
class Styles extends Base{
	
    public function index(){
        $m = new M();
        $rs = $m->getCats();
        $m->initStyles();
        $this->assign('cats',$rs);
    	return $this->fetch();
    }
    /**
     * 获取风格列表
     */
    public function listQueryBySys(){
        $m = new M();
        $rs = $m->listQuery();
        return WSTReturn('',1,$rs);
    }
    
    /**
     * 保存
     */
    public function changeStyle(){
    	$m = new M();
    	return $m->changeStyle();
    }
}
