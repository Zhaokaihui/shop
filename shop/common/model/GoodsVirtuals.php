<?php
namespace shop\common\model;
use think\Db;
use think\Loader;
/**

 * * 虚拟商品卡券模型
 */
class GoodsVirtuals extends Base{
    /************************************************************* 商家操作商品相关start *************************************************************/
    
    /**
     * 删除
     */
    public function del($sId=0){
		$shopId = ($sId==0)?(int)session('tb_USER.shopId'):$sId;
        $ids = input('ids');
        $id = input('id');
        if($ids=='')return WSTReturn('请选择要删除的卡券号');
        try{
            $this->where([['id','in',$ids],['shopId','=',$shopId],['goodsId','=',$id]])->update(['dataFlag'=>-1]);
            $this->updateGoodsStock($id);
            Db::commit();
        }catch (\Exception $e) {
            Db::rollback();
            return WSTReturn('操作失败');
        }
        return WSTReturn('操作成功',1);
    }

    /**
     * 编辑
     */
    public function edit($sId=0){
		$shopId = ($sId==0)?(int)session('tb_USER.shopId'):$sId;
        $id = (int)input('id');
        //判断卡券是否有效
        $rs = $this->where(['id'=>$id,'shopId'=>$shopId,'dataFlag'=>1,'isUse'=>0])->find();
        if(empty($rs))return WSTReturn('非法的卡券');
        $cardNo = input('cardNo');
        $cardPwd = input('cardPwd');
        if($cardNo=='' || $cardPwd=='')return WSTReturn('请输入完整卡券信息');
        $conts = $this->where([['id','<>',$id],['shopId','=',$shopId],['cardNo','=',$cardNo],['dataFlag','=',1]])->Count();
        if($conts>0)return WSTReturn('该卡券号已存在，请重新输入');
        $rs = $this->update(['cardNo'=>$cardNo,'cardPwd'=>$cardPwd],[['id','=',$id],['shopId','=',$shopId]]);
        if($rs !== false){
            return WSTReturn('操作成功',1);
        }
        return WSTReturn('操作失败');
    }

    /**
     * 获取虚拟商品库存列表
     */
    public function stockByPage($sId=0){
		$shopId = ($sId==0)?(int)session('tb_USER.shopId'):$sId;
        $key = input('cardNo');
        $id = (int)input('id');
        $isUse = (int)input('isUse',-1);
        $where = [['shopId',"=",$shopId],['goodsId',"=",$id],['dataFlag',"=",1]];
        if($key !='')$where[] = ['cardNo','like','%'.$key.'%'];
        if(in_array($isUse,[0,1]))$where[] = ['isUse',"=",$isUse];
        $page = $this->field('orderNo,orderId,cardNo,id,cardPwd,isUse')
        ->where($where)->order('id desc')
        ->paginate(20)->toArray();
        return $page;
    }

    /**
     * 生成卡券号
     */
    public function getCardNo($shopId){
        $cardNo = date('Ymd').sprintf("%08d", rand(0,99999999));
        $conts = $this->where(['shopId'=>$shopId,'dataFlag'=>1,'cardNo'=>$cardNo])->Count();
        if($conts==0){
            return $cardNo;
        }else{
            return $this->getCardNo($shopId);
        }
    }

    /**
     * 生成卡券
     */
    public function add($sId=0){
		$shopId = ($sId==0)?(int)session('tb_USER.shopId'):$sId;
        $goodsId = (int)input('goodsId');
        //判断商品是否有效
        $goods = model('goods')->where(['goodsId'=>$goodsId,'shopId'=>$shopId,'goodsType'=>1])->find();
        if(empty($goods))return WSTReturn('非法的卡券商品');
        $cardNo = input('cardNo');
        $cardPwd = input('cardPwd');
        if($cardNo=='' || $cardPwd=='')return WSTReturn('请输入完整卡券信息');
        $conts = $this->where(['shopId'=>$shopId,'dataFlag'=>1,'cardNo'=>$cardNo])->Count();
        if($conts>0)return WSTReturn('该卡券号已存在，请重新输入');
        $data = [];
        $data['cardNo'] = $cardNo;
        $data['cardPwd'] = $cardPwd;
        $data['dataFlag'] = 1;
        $data['shopId'] = $shopId;
        $data['goodsId'] = $goodsId;
        $data['createTime'] = date('Y-m-d H:i:s');
        Db::startTrans();
        try{
            $this->save($data);
            $this->updateGoodsStock($goodsId,$shopId);
            Db::commit();
        }catch (\Exception $e) {
            Db::rollback();
            return WSTReturn('新增失败');
        }
        return WSTReturn('新增成功',1);
    }


    /**
     * 更新商品数量
     */
    public function updateGoodsStock($id, $sId=0){
        $shopId = ($sId==0)?(int)session('tb_USER.shopId'):$sId;
        $counts = $this->where(['dataFlag'=>1,'goodsId'=>$id,'shopId'=>$shopId,'isUse'=>0])->Count();
        Db::name('goods')->where('goodsId',$id)->setField('goodsStock',$counts);
    }

    /************************************************************* 商家操作商品相关end *************************************************************/
}