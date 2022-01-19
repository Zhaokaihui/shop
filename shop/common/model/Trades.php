<?php
namespace shop\common\model;
use think\Db;
/**
 * * 行业类
 */
class Trades extends Base{
	protected $pk = 'tradeId';
	
	/**
     * 获取行业信息
     */
    public function getFieldsById($tradeId,$fields){
        return $this->where(['tradeId'=>$tradeId,'dataFlag'=>1])->field($fields)->find();
    }


}
