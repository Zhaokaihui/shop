<?php 
/**

 * * * * * */
return [
    'module_init'=> [
        'shop\\home\\behavior\\InitConfig'
    ],
    'action_begin'=> [
        'shop\\home\\behavior\\ListenProtectedUrl'
    ]
]
?>