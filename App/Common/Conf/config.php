<?php
return array(
    //数据库配置信息
    'DB_TYPE' => 'mysqli', // 数据库类型
    'DB_HOST' => '103.29.68.61', // 服务器地址
    'DB_NAME' => 'epgogofr_db1', // 数据库名
    'DB_USER' => 'epgogofr_db1', // 用户名
    'DB_PWD' => 'epgogofr_db1', // 密码
    'DB_PORT' => 3306, // 端口
    'DB_PREFIX' => '', // 数据库表前缀
    /*------------------------------------跳转模板-----------------------------------------------*/
    'TMPL_ACTION_ERROR' => 'Public:dispatch_jump',                                // 默认错误跳转对应的模板文件
    'TMPL_ACTION_SUCCESS' => 'Public:dispatch_jump',                                // 默认成功跳转对应的模板文件

    'URL_CASE_INSENSITIVE' => true,      //URL访问不再区分大小写
    'URL_MODEL' => 2,           //URL模式

    /*------------------------------------配置-----------------------------------------------*/
    'SERVER_HOST' => 'https://' . $_SERVER['SERVER_NAME'], // 服务地址域名，测试改成"http://103.29.68.61"

);
?>
