<?php
namespace shop\admin\model;
use think\Db;
/**
 * * * * * * * 操作日志业务处理
 */
class SupplierLogOperates extends Base{
	protected $pk = "operateId";
    /**
	 * 分页
	 */
	public function pageQuery(){
		$startDate = input('startDate');
		$endDate = input('endDate');
        $loginName = input('loginName');
        $supplierName = input('supplierName');
        $operateUrl = input('operateUrl');
		$where = [];
		if($startDate!='')$where[] = ['l.operateTime','>=',$startDate." 00:00:00"];
		if($endDate!='')$where[] = [' l.operateTime','<=',$endDate." 23:59:59"];
        if($loginName!='')$where[] = [' u.loginName','like',"%".$loginName."%"];
        if($supplierName!='')$where[] = [' s.supplierName','like',"%".$supplierName."%"];
        if($operateUrl!='')$where[] = [' l.operateUrl','like',"%".$operateUrl."%"];
		return $mrs = Db::name('supplier_log_operates l')
			->join('supplier_users su',' su.userId=l.userId','inner')
			->join('suppliers s',' s.supplierId=su.supplierId','inner')
		    ->join('users u',' l.userId=u.userId','inner')
			->where($where)
			->field('l.*,u.loginName,s.supplierName')
			->order('l.operateId', 'desc')->paginate(input('limit/d'));
			
	}
	
	
	/**
	 *  获取指定的操作记录
	 */
	public function getById($id){
		$rs = $this->get($id);
		if(!empty($rs)){
			return WSTReturn('', 1,$rs);
		}
		return WSTReturn('对不起，没有找到该记录', -1);
	}
}
