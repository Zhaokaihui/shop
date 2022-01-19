<?php
namespace shop\shop\behavior;
/**

 * * 记录用户的访问日志
 */
class ListenOperate 
{
    public function run($params){
        if(session("tb_USER.shopId")>0){
            $urls = session("tb_USER.shopMenuMaps");
            $request = request();
            $visit = strtolower($request->module()."/".$request->controller()."/".$request->action());
            $expurls = ['shop/logshopoperates/index','shop/logshopoperates/pagequery'];
            if(array_key_exists($visit,$urls) && !in_array($visit,$expurls)){

                $privilege = $urls[$visit];
                $data = [];
                $data['menuId'] = $privilege['menuId'];
                $data['operateUrl'] = $_SERVER['REQUEST_URI'];
                $data['operateDesc'] = $privilege['menuName'];
                $data['content'] = !empty($_REQUEST)?json_encode($_REQUEST):'';
                $data['operateIP'] = $request->ip();
                $data['operateSrc'] = 1;
                model('shop/LogShopOperates')->add($data);
            }
        }
    }
}