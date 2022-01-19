<?php
namespace shop\common\model;
use think\Db;
/**

 * * 结算类
 */
class SupplierSettlements extends Base{
	protected $pk = 'settlementId';
	/**
     * 即时计算
     */
    public function speedySettlement($orderId){
       
        $order = model('supplier/SupplierOrders')->get(['orderId'=>$orderId]);
        $suppliers = model('common/suppliers')->get(['supplierId'=>$order->supplierId]);
        if(empty($suppliers))return WSTReturn('结算失败，商家不存在');
        $backMoney = 0;

        $realTotalMoney = $order["realTotalMoney"];
        $commissionFee = $order["commissionFee"];
        $payType = $order["payType"];
        $refundedPayMoney = $order["refundedPayMoney"];
        
        $settlementMoney = 0;
        
        if($payType==1){//线上支付
            if($realTotalMoney<=0 ){//线上支付，纯积分支付
                
            }else{
                $settlementMoney = $realTotalMoney - $refundedPayMoney;
                //在线支付的返还金额=实付金额+积分抵扣金额-已退款支付金额-已退款积分抵扣金额 - 失效获得积分换算的金额 -佣金
                $backMoney = $realTotalMoney - $refundedPayMoney - $commissionFee;
            }

        }else{//货到付款
            $settlementMoney = 0;
            //货到付款的返还金额=积分抵扣金额-佣金
            $backMoney = 0 - $commissionFee;
        }
        $tmpBackMoney = WSTBCMoney($backMoney, $commissionFee);
        $settlementMoney = WSTBCMoney($settlementMoney, 0);
        $backMoney = WSTBCMoney($backMoney, 0);
        $data = [];
        $data['settlementType'] = 1;
        $data['supplierId'] = $order->supplierId;
        $data['settlementMoney'] = $settlementMoney;
        $data['commissionFee'] = $order->commissionFee;
        $data['backMoney'] = $backMoney;
        $data['settlementStatus'] = 1;
        $data['settlementTime'] = date('Y-m-d H:i:s');
        $data['createTime'] = date('Y-m-d H:i:s');
        $data['settlementNo'] = '';
        $settlementId = Db::name('supplier_settlements')->insertGetId($data);
        if($settlementId>0){
            $settlementNo =$settlementId.(fmod($settlementId,7));
            Db::name('supplier_settlements')->where('settlementId',$settlementId)->update(['settlementNo'=>$settlementNo]);
            $order->settlementId = $settlementId;
            $order->isClosed = 1;
            $order->save();
            //修改商家钱包
            $suppliers->noSettledOrderFee = $suppliers['noSettledOrderFee']+$order->commissionFee;//减少未结算佣金
            $suppliers->noSettledOrderNum = $suppliers['noSettledOrderNum']-1;//减少未结算订单数
            $suppliers->supplierMoney = $suppliers['supplierMoney']+$backMoney;
            $suppliers->save();
            //返还金额
            $lmarr = [];
            //货到付款处理
            if($order->payType==0){
                
            }else{
                //在线支付的话，记录商家应得的钱的流水
                if($tmpBackMoney>0){
                    $lm = [];
                    $lm['targetType'] = 3;
                    $lm['targetId'] = $order->supplierId;
                    $lm['dataId'] = $settlementId;
                    $lm['dataSrc'] = 2;
                    $lm['remark'] = '结算订单申请【'.$settlementNo.'】返还金额¥'.$tmpBackMoney;
                    $lm['moneyType'] = 1;
                    $lm['money'] =$tmpBackMoney;
                    $lm['payType'] = 0;
                    $lm['createTime'] = date('Y-m-d H:i:s');
                    $lmarr[] = $lm;
                } 
            }
            //收取佣金
            if($order->commissionFee>0){
                $lm = [];
                $lm['targetType'] = 3;
                $lm['targetId'] = $order->supplierId;
                $lm['dataId'] = $settlementId;
                $lm['dataSrc'] = 2;
                $lm['remark'] = '结算订单申请【'.$settlementNo.'】收取订单佣金¥'.$order->commissionFee;
                $lm['moneyType'] = 0;
                $lm['money'] = $order->commissionFee;
                $lm['payType'] = 0;
                $lm['createTime'] = date('Y-m-d H:i:s');
                $lmarr[] = $lm;
            }
            model('common/LogMoneys')->saveAll($lmarr);
        }
        return WSTReturn('结算失败');
    }
}
