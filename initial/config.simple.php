<?php

use Medoo\Medoo;
$db = new medoo([
    'database_type' => 'sqlite',
    'database_file' => 'data/'.$u.'.db3'
]);
//注:此配置文件已废弃,正常使用Web直接注册即可!如果遇到无法注册的情况请请按以下办法尝试解决
//1.清理浏览器缓存,或用无痕/小号模式注册!
//2.检查data文件夹是否有写入权限,正常设为755权限
//3.服务器是否禁止了copy函数
//4.如果无法解决可以手动注册,即复制此文件和onenav.simple.db3到data目录,并改名为admin.db3和admin.php 然后访问注意即可!
//5.升级说明:正常情况直接覆盖文件访问主页即可!建议先备份数据在升级!
//用户名
define('USER','admin');
//密码
define('PASSWORD','admin');
//邮箱，用于后台Gravatar头像显示
define('EMAIL','337003006@qq.com');
//token参数，API需要使用
define('TOKEN','');
//主题风格
define('TEMPLATE','default');

//站点信息
$site_setting = [];
//站点标题
$site_setting['title']          =   '我的书签';
//文字Logo
$site_setting['logo']          =   '我的书签';
//站点关键词
$site_setting['keywords']       =   'OneNav,OneNav导航,OneNav书签,开源导航,开源书签,简洁导航,云链接,个人导航,个人书签';
//站点描述
$site_setting['description']    =   'OneNav是一款使用PHP + SQLite3开发的简约导航/书签管理器，免费开源。';

//这两项不要修改
$site_setting['user']           =   USER;
$site_setting['password']       =   PASSWORD;