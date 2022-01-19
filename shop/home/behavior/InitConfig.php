<?php
namespace shop\home\behavior;
/**
 * * * * * 交流社区:http://bbs.shopsoft.com
 * * 初始化基础数据
 */
class InitConfig 
{
    public function run($params){
        WSTConf('protectedUrl',model('HomeMenus')->getMenusUrl());
        hook('initConfigHook',['getParams'=>input()]);
    }
}