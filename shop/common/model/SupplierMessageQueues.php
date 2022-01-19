<?php
namespace shop\common\model;
use think\Db;
/**

 * * 消息队列
 */
class SupplierMessageQueues extends Base{
   /**
	* 新增
 	*/
	public function add($param){
		$supplierId = $param["supplierId"];
		$tplCode = $param["tplCode"];
		$msgcat = Db::name("shop_message_cats")->where(["msgCode"=>$tplCode])->find();
		if(!empty($msgcat)){
			$msgDataId = $msgcat["msgDataId"];
			$msgType = $param['msgType'];
			$dbo = Db::name("supplier_users su")->join("__USERS__ u","su.userId=u.userId");
			if($msgType==4){
				$dbo = $dbo->where("u.wxOpenId!=''");
			}
			$where = "su.dataFlag=1 and FIND_IN_SET(".$msgType.",su.privilegeMsgTypes) and FIND_IN_SET(".$msgDataId.",su.privilegeMsgs)";
			$list = $dbo->where($where)->where(["su.supplierId"=>$supplierId])->field("su.userId,u.userPhone")->select();
			
			if($msgType==1){
				foreach ($list as $key => $user) {
					WSTSendMsg($user['userId'],$param['content'],$param['msgJson'],$msgType);
				}
			}
		}
		
		
	}
	/**
	 * 发送成功修改状态
	 */
	public function edit($id){
		$data = [];
		$data['sendStatus'] = 1;
		$result = $this->where(["id"=>$id])->save($data);
       	return $result;
	}

}
