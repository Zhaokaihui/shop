<?php 
/**
 * * * * * * */
return [
    'module_init'=> [
        'shop\\admin\\behavior\\InitConfig'
    ],
    'action_begin'=> [
        'shop\\admin\\behavior\\ListenLoginStatus',
        'shop\\admin\\behavior\\ListenPrivilege',
        'shop\\admin\\behavior\\ListenOperate'
    ]
]
?>