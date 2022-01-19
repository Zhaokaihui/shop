<?php
namespace shop\shop\controller;
/**

 * * 订单投诉控制器
 */
class Supplierordercomplains extends Base{
    protected $beforeActionList = ['checkAuth'];
    /******************************** 用户 ******************************************/
    /**
    * 查看投诉列表
    */
	public function index(){
	    $this->assign("p",(int)input("p"));
		return $this->fetch('supplier/orders/list_complain');
	}
    /**
    * 获取用户投诉列表
    */    
    public function queryUserComplainByPage(){
        $m = model('SupplierOrderComplains');
        return $m->queryUserComplainByPage();
        
    }
    /**
     * 订单投诉页面
     */
    public function complain(){
        $data = model('SupplierOrderComplains')->getOrderInfo();
        $this->assign("data",$data);
        $this->assign("src",input('src'));
        $this->assign("p",(int)input('p',1));
        return $this->fetch("supplier/orders/complain");
    }
    /**
     * 保存订单投诉信息
     */
    public function saveComplain(){
        return model('SupplierOrderComplains')->saveComplain();
    }
    /**
     * 用户查投诉详情
     */
    public function getUserComplainDetail(){
        $data = model('SupplierOrderComplains')->getComplainDetail();
        $this->assign("data",$data);
        $this->assign("p",(int)input("p"));
        return $this->fetch("supplier/orders/complain_detail");
    }
}
