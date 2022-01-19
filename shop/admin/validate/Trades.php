<?php 
namespace shop\admin\validate;
use think\Validate;
/**
 * * * * * * * 行业验证器
 */
class Trades extends Validate{
	protected $rule = [
	    'tradeName' => 'require|max:30',
	    'tradeSort' => 'require|max:16'
    ];
    
    protected $message = [
         'tradeName.require' => '请输入行业名称',
         'tradeName.max' => '行业名称不能超过10个字符',
         'tradeSort.require' => '请输入排序号',
         'tradeSort.max' => '排序号不能超过8个字符'
    ];
    
    protected $scene = [
        'add'   =>  ['tradeName','tradeSort'],
        'edit'  =>  ['tradeName','tradeSort']
    ]; 
}