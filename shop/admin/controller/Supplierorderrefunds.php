<?php
namespace shop\admin\controller;
use shop\admin\model\SupplierOrderRefunds as M;
/**
 * * * * * * * 退款订单控制器
 */
class Supplierorderrefunds extends Base{
    /**
     * 退款列表
     */
    public function refund(){
    	$areaList = model('areas')->listQuery(0);
    	$this->assign("p",(int)input("p"));
    	$this->assign("areaList",$areaList);
    	return $this->fetch("list");
    }
    public function refundPageQuery(){
        $m = new M();
        return WSTGrid($m->refundPageQuery());
    }
    /**
     * 跳去退款界面
     */
    public function toRefund(){
    	$m = new M();
    	$object = $m->getInfoByRefund();
    	$this->assign("object",$object);
    	return $this->fetch("box_refund");
    }
    /**
     * 退款
     */
    public function orderRefund(){
    	$m = new M();
        return $m->orderRefund();
    }
    /**
     * 导出订单
     */
    public function toExport(){
    	$m = new M();
    	$rs = $m->toExport();
    	$this->assign('rs',$rs);
    }

    public function wxRefundNodify(){
        $m = model("admin/SupplierWeixinpays");
        $m->notify();
    }


}
