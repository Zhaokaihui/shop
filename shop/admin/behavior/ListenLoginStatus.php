<?php
namespace shop\admin\behavior;
/**
 * * * * * * * 检测用户有没有登录
 */
class ListenLoginStatus 
{
    public function run($params){
        $STAFF = session('tb_STAFF');
        $allowUrl = ['admin/index/login',
			         'admin/index/checklogin',
			         'admin/index/logout',
                     'admin/index/getverify',
                     'admin/cronjobs/autocancelnopay',
                     'admin/cronjobs/autoappraise',
                     'admin/cronjobs/autoreceive',
                     'admin/cronjobs/autosendmsg',
        			 'admin/cronjobs/autofilexml',
        			 'admin/cronjobs/autodealorderservice',
                     'admin/cronjobs/autoshopsettlement',
                     'admin/cronjobs/autochangeshopstatus',
                     'admin/cronjobs/autochangesupplierstatus',
                     'admin/cronjobs/clearposter',
                     'admin/orderrefunds/wxrefundnodify',
                     'admin/orderrefunds/wxapprefundnodify',
                     'admin/supplierorderrefunds/wxrefundnodify',
                     'admin/shops/wxenterrefundnotify',
                     'admin/suppliers/wxenterrefundnotify'
			        ];
	    $request = request();
        $visit = strtolower($request->module()."/".$request->controller()."/".$request->action());
        if(empty($STAFF) && !in_array($visit,$allowUrl)){
	        if($request->isAjax()){
	        	echo json_encode(['status'=>-999,'msg'=>'对不起，您还没有登录，请先登录']);
	        }else{
	        	header("Location:".url('admin/index/login'));
	        }
	        exit();
        }
    }
}