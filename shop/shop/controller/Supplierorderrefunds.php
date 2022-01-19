<?php
namespace shop\shop\controller;
use shop\common\model\SupplierOrderRefunds as M;
/**

 * * 订单退款控制器
 */
class Supplierorderrefunds extends Base{
	protected $beforeActionList = ['checkAuth'];
    /**
	 * 用户申请退款
	 */
	public function refund(){
		$m = new M();
		$rs = $m->refund();
		return $rs;
	}
}
