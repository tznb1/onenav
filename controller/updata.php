<?php  //数据库升级脚本
if($libs==''){exit('<h3>非法请求</h3>');}//禁止直接访问此接口!
if(file_exists('./data/'.$u.'.db3')){
//判断是否需要升级数据库
//$db->query("DROP TABLE main.on_config")->fetchAll();//删除表!
$data = $db->query("select * from sqlite_master where name = 'on_config'")->fetchAll();
if (count($data)==0){ 
$db->query('CREATE TABLE "main"."on_config" ("name" TEXT,"value" TEXT);');
if(!file_exists('./data/'.$u.'.php')){
Writeconfig("title", '我的书签');
Writeconfig("logo", '我的书签');
Writeconfig("keywords", '');
Writeconfig("description",'');
Writeconfig("Email",'');
Writeconfig("db", $u.'.db');
Writeconfig("Theme", 'default');
Writeconfig('Style','0');
Writeconfig("urlz", 'on');
Writeconfig('gotop','on');
Writeconfig('quickAdd','on');
Writeconfig('GoAdmin','on');
Writeconfig('LoadIcon','on');
Writeconfig("user", $u);
Writeconfig("password", 'admin');
Writeconfig("Token", '');
exit("<h3>数据库已升级,请刷新页面!已创建配置表!初始密码为admin,请及时修改密码</h3>");
}else{
require ('./data/'.$u.'.php');
$site_setting['TOKEN']           =   TOKEN;
$site_setting['TEMPLATE']           =   TEMPLATE;
$site_setting['EMAIL']           =   EMAIL;
Writeconfig("title", $site_setting['title']);
Writeconfig("logo", $site_setting['logo']);
Writeconfig("keywords", $site_setting['keywords']);
Writeconfig("description",$site_setting['description']);
Writeconfig("Email",$site_setting['EMAIL']);
Writeconfig("db", $u.'.db');
Writeconfig("Theme", $site_setting['TEMPLATE']);
Writeconfig('Style','0');
Writeconfig("urlz", 'on');
Writeconfig('gotop','on');
Writeconfig('quickAdd','on');
Writeconfig('GoAdmin','on');
Writeconfig('LoadIcon','on');
Writeconfig("user", $site_setting['user']);
Writeconfig("password", $site_setting['password']);
Writeconfig("Token", '');
if ($site_setting['user'] =='' || $site_setting['password'] == ''){
    Writeconfig("user", $u);
    Writeconfig("password", 'admin');
    exit("数据库已升级,未检测到配置文件中的账号或密码!将使用默认密码!");
}
//unlink('./data/'.$u.'.php');
rename('./data/'.$u.'.php','./data/obsolete_'.$u.'.php');
exit("数据库已升级,请刷新页面!已创建配置表!");}}

//判断分类表是否存在Icon字段,如果不存在则添加字段!仅在登陆时有效!
$data = $db->query("select * from sqlite_master where name = 'on_categorys' and sql like '%Icon%'")->fetchAll();
if (count($data)==0){ 
$db->query('ALTER TABLE "on_categorys"  ADD COLUMN "Icon" TEXT(128)');
exit("数据库已升级,请刷新页面!已扩展Icon字段!");}
}
