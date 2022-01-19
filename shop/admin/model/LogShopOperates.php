<?php
namespace shop\admin\model;
use think\Db;
/**
 * * * * * * * 操作日志业务处理
 */
class LogShopOperates extends Base{
	protected $pk = "operateId";
    /**
	 * 分页
	 */
	public function pageQuery(){
		$startDate = input('startDate');
		$endDate = input('endDate');
        $loginName = input('loginName');
        $shopName = input('shopName');
        $operateUrl = input('operateUrl');
		$where = [];
		if($startDate!='')$where[] = ['l.operateTime','>=',$startDate." 00:00:00"];
		if($endDate!='')$where[] = [' l.operateTime','<=',$endDate." 23:59:59"];
        if($loginName!='')$where[] = [' u.loginName','like',"%".$loginName."%"];
        if($shopName!='')$where[] = [' s.shopName','like',"%".$shopName."%"];
        if($operateUrl!='')$where[] = [' l.operateUrl','like',"%".$operateUrl."%"];
		return $mrs = Db::name('log_shop_operates l')
			->join('shop_users su',' su.userId=l.userId','inner')
			->join('shops s',' s.shopId=su.shopId','inner')
		    ->join('users u',' l.userId=u.userId','inner')
			->where($where)
			->field('l.*,u.loginName,s.shopName')
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
