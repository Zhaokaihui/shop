<?php
namespace shop\store\controller;
use shop\common\model\Users as MUsers;
use shop\common\model\LogSms;
use shop\store\model\HomeMenus as HM;
/**
 * * 默认控制器
 */
class Index extends Base{
    protected $beforeActionList = ['checkAuth'=>['only'=>'index,main']];
    /**
     * 店铺主页
     */
    public function index(){
       $m = new HM();
       $ms = $m->getStoreMenus();
       $this->assign("sysMenus",$ms[2]);
       return $this->fetch('/index');
    }

  /**
   * 去登录
   */
  public function login(){
    $USER = session('tb_STORE');
    //如果已经登录了则直接跳去用户中心
    if(!empty($USER) && $USER['userId']!='' && $USER['userType']==1)$this->index();
    $loginName = cookie("loginName");
    if(!empty($loginName)){
        $this->assign('loginName',cookie("loginName"));
    }else{
        $this->assign('loginName','');
    }
    return $this->fetch('/login');
  }

  /**
     * 获取用户信息
     */
  public function getSysMessages(){
      $rs = model('Systems')->getSysMessages();
      return $rs;
  }

  /**
   * 验证登录
   *
   */
  public function checkLogin(){
    $rs = model('Users')->checkStoreLogin();
    return $rs;
  }

  /**
   * 用户退出
   */
  public function logout(){
    session('tb_STORE',null);
    return WSTReturn("退出成功，正在跳转页面", 1);
  }

  /**
   * 系统预览
   */
  public function main(){
    $s = model('store/shops');
    $data = $s->getShopSummary();
    $this->assign('data',$data);
    return $this->fetch("/main");
  }
  
}
