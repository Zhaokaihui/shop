<?php 
namespace shop\common\validate;
use think\Validate;
/**

 * * 售后服务验证器
 */
class SupplierOrderServices extends Validate{
	protected $rule = [
		'goodsServiceType'  => 'in:0,1,2',
		'serviceRemark'   => 'require|length:3,600',

		'isSupplierAgree' => 'in:0,1',
		'supplierAddress'=>'requireIf:isSupplierAgree,1',
		'supplierName'=>'requireIf:isSupplierAgree,1',
		'supplierPhone'=>'requireIf:isSupplierAgree,1',
		'disagreeRemark'=>'requireIf:isSupplierAgree,0',

		'expressType'=>'require|in:0,1',
		'expressId'=>'requireIf:expressType,1',
		'expressNo'=>'requireIf:expressType,1',

		'isSupplierAccept'=>'require|in:-1,1',
		'supplierRejectType'=>'require',
		'supplierRejectOther'=>'requireIf:supplierRejectType,10000',

		'supplierExpressType'=>'require|in:0,1',
		'supplierExpressId'=>'requireIf:supplierExpressType,1',
		'supplierExpressNo'=>'requireIf:supplierExpressType,1',

		'isUserAccept'=>'require|in:-1,1',
		'userRejectType'=>'require',
		'userRejectOther'=>'requireIf:userRejectType,10000',
	];
	
	protected $message  =   [
		'goodsServiceType.in'   => '无效的售后类型！',
		'serviceRemark.require' => '问题描述不能为空',
		'serviceRemark.length' => '问题描述应为3-200个字',
		
		'isSupplierAgree.in'   => '无效的受理值！',
		'supplierAddress.requireIf' => '商家收货地址不能为空',
		'supplierName.requireIf' => '收货人不能为空',
		'supplierPhone.requireIf' => '商家联系人不能为空',
		'disagreeRemark.requireIf' => '请输入不受理原因',

		'expressType.in'   => '无效的物流类型！',
		'expressId.requireIf'   => '请选择物流公司',
		'expressNo.requireIf'   => '物流单号不能为空',
		
		'isSupplierAccept.in'   => '无效的确认值！',
		'supplierRejectType.require'   => '请选择拒收类型',
		'supplierRejectOther.requireIf'   => '请输入拒收原因',

		
		'supplierExpressType.in'=>'无效的物流类型！',
		'supplierExpressId.requireIf'   => '请选择物流公司',
		'supplierExpressNo.requireIf'   => '物流单号不能为空',
		
		'isUserAccept.in'   => '无效的确认值！',
		'userRejectType.require'   => '请选择拒收类型',
		'userRejectOther.requireIf'   => '请输入拒收原因',
	];
    protected $scene = [
		// 用户提交
		'commit'   =>  ['goodsServiceType','serviceRemark'],
		// 商家受理
		'deal'   =>  ['isSupplierAgree', 'supplierAddress', 'supplierName', 'supplierPhone', 'disagreeRemark' ],
		// 退款
		'refund' => ['isSupplierAgree'],
		// 用户发货
		'userExpress' => ['expressType','expressId','expressNo'],
		// 商家是否确认收货
		'supplierComfirm' => ['isSupplierAccept','supplierRejectType','supplierRejectOther'],
		// 商家发货
		'supplierSend' => ['supplierExpressType','supplierExpressId','supplierExpressNo'],
		// 用户确认收货
		'userConfirm' => ['isUserAccept','userRejectType','userRejectOther'],
	]; 




}