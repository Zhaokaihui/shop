<?php
namespace shop\shop\controller;
use think\Loader;
use Env;
use shop\common\model\Payments as M;
use shop\shop\model\SupplierOrders as OM;
use shop\common\model\LogMoneys as LM;
use shop\common\model\ChargeItems as CM;
/**
 * * * * * 交流社区:http://bbs.shopsoft.com
 * * * * * * 阿里支付控制器
 */
class Supplieralipays extends Base{

    /**
     * 生成支付代码
     */
    function getAlipaysUrl(){
        $payObj = input("payObj/s");
        
        $obj = array();
        $data = array();
        $orderAmount = 0;
        $out_trade_no = "";
        $passback_params = "";
        $subject = "";
        $body = "";
        $m = new M();
        $payment = $m->getPayment("alipays");
        require Env::get('root_path') . 'extend/alipay/aop/AopClient.php' ;
        require Env::get('root_path') . 'extend/alipay/aop/request/AlipayTradePagePayRequest.php' ;
        $m = new OM();
        $returnUrl = url("shop/supplieralipays/payorders","",true,true);
        
        $pkey = input("pkey");
        $pkey = WSTBase64urlDecode($pkey);
        $pkey = explode('@',$pkey);
        $userId = (int)session('tb_USER.userId');
        $shopId = (int)session('tb_USER.shopId');
        $obj["userId"] = $userId;
        $obj["shopId"] = $shopId;
        $obj["orderNo"] = $pkey[0];
        $obj["isBatch"] = (int)$pkey[1];
        $data = $m->checkOrderPay2($obj);
        if($data["status"]==1){
            $order = $m->getPayOrders($obj);
            $orderAmount = $order["needPay"];
            $payRand = $order["payRand"];
            $out_trade_no = $obj["orderNo"]."a".$payRand;
            $passback_params = $payObj."@".$userId."@".$obj["isBatch"]."@".$shopId;
            $subject = '支付购买商品费用'.$orderAmount.'元';
            $body = '支付订单费用';
        }
        $returnUrl = url("shop/supplieralipays/payorders","",true,true);
        
        
        if($data["status"]==1){
            
            $aop = new \AopClient ();  
            $aop->gatewayUrl = 'https://openapi.alipay.com/gateway.do';  
            $aop->appId = $payment["appId"];  
            $aop->rsaPrivateKey = $payment["rsaPrivateKey"]; 
            $aop->apiVersion = '1.0';  
            $aop->signType = 'RSA2';  
            $aop->postCharset= "UTF-8";;  
            $aop->format='json';  
            $request = new \AlipayTradePagePayRequest ();  
            $request->setReturnUrl($returnUrl);  
            $request->setNotifyUrl(url("shop/supplieralipays/aliNotify","",true,true));  
            $passback_params = urlencode($passback_params);
            $bizcontent = "{\"body\":\"$body\","
                        . "\"subject\": \"$subject\","
                        . "\"out_trade_no\": \"$out_trade_no\","
                        . "\"total_amount\": \"$orderAmount\","
                        . "\"passback_params\": \"$passback_params\","
                        . "\"product_code\":\"FAST_INSTANT_TRADE_PAY\""
                        . "}";
            $request->setBizContent($bizcontent);

            //请求  
            $result = $aop->pageExecute ($request);
            $data["result"]= $result;
            return $data;
        }else{
            return $data;
        }
        
    }

    /**
     * 验证签名
     */
    function aliCheck($params){
        require Env::get('root_path') . 'extend/alipay/aop/AopClient.php' ;
        $aop = new \AopClient;
        $m = new M();
        $payment = $m->getPayment("alipays");
        $aop->alipayrsaPublicKey = $payment["alipayrsaPublicKey"];
        $flag = $aop->rsaCheckV1($params, NULL, "RSA2");
        return $flag;
    }
    
    function payorders(){
        if($this->aliCheck($_GET)){
            $this->redirect(url("shop/supplieralipays/paysuccess"));
        }else{
            $this->error('支付失败');
        }
    }

    /**
     * 服务器异步通知方法
     */
    function aliNotify() {
        if($this->aliCheck($_POST)){
            if ($_POST['trade_status'] == 'TRADE_SUCCESS' || $_POST['trade_status'] == 'TRADE_FINISHED'){
                $extras = explode("@",urldecode($_POST['passback_params']));
                $rs = array();
                
                //商户订单号
                $obj = array();
                $tradeNo = explode("a",$_POST['out_trade_no']);
                $obj["trade_no"] = $_POST['trade_no'];
                $obj["out_trade_no"] = $tradeNo[0];
                $obj["total_fee"] = $_POST['total_amount'];
                
                $obj["userId"] = (int)$extras[1];
                $obj["isBatch"] = (int)$extras[2];
                $obj["shopId"] = (int)$extras[3];
                $obj["payFrom"] = 'alipays';

                //支付成功业务逻辑
                $m = new OM();
                $rs = $m->complatePay($obj);
                
                if($rs["status"]==1){
                    echo 'success';
                }else{
                    echo 'fail';
                }
            }
        } else {
            echo "fail";
        }
    }
    
    /**
     * 检查支付结果
     */
    public function paySuccess() {
        return $this->fetch('supplier/order_pay_step3');
    }

}
