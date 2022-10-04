<!DOCTYPE html>
<head>
    <meta charset="utf-8"/>
    <title>站长应急脚本</title>
</head>
<?php
exit('<h3>您无权访问</h3>'); //要使用此脚本时是去掉前面的// 不用时请加上//注释掉!

//此文件特定情况使用,例如丢失管理权限时,将指定用户设为管理员的脚本!
//全局配置错误导致无法使用时,忘记入口等等!可以通过此脚本来恢复默认值!

//如果要使用请将上面的exit注释掉(exit前面加//)

//用完请恢复注释(去掉exit前面的//),否则存在非常严重的安全隐患!
//用完请恢复注释(去掉exit前面的//),否则存在非常严重的安全隐患!
//用完请恢复注释(去掉exit前面的//),否则存在非常严重的安全隐患!

//修改好相关数据后访问 http://域名/initial/SetAdmin.php



//载入数据库框架(不要动)
require ('../class/Medoo.php');
//载入数据库(不要动)
$db = new Medoo\Medoo(['database_type'=>'sqlite','database_file'=>'../data/lm.user.db3']);

//(按需) 修改全局配置(恢复默认值),请按需修改!不需要的话就注释掉!
$db->update('config',['Value' => 'login'],['Name' => 'Login']);  //登陆入口 (入口忘记了?) http://域名/index.php?c=login
$db->update('config',['Value' => './static'],['Name' => 'Libs']); //静态路径(改错路径或者无法访问造成异常时需要恢复默认)
$db->update('config',['Value' => 'Register'],['Name' => 'Register']); //注册入口 http://域名/index.php?c=Register
$db->update('config',['Value' => '1'],['Name' => 'Reg']); //允许注册 (开放注册)
$db->update('config',['Value' => '1'],['Name' => 'Visit']);  //允许访问 (如果需要注册则必须开启 1=开启)
echo '<h3>全局配置修改,请检查是否成功!</h3>';


//(按需)  下面是设置管理员的
//return; // 不设置管理员,只修改全局配置的话把这里注释掉

$user = 'admin'; //需要设为管理员的账号
//查找用户 (不要动)
$ud = $db->get("user","*",["User"=>$user]);
if($ud["User"]==''){exit('<h3>没有找到用户,请先注册!</h3>');}

//设置权限 (不要动)
$Re = $db->update('user',['Level'=> '999' ],['ID' => $ud["ID"]]);
if($Re->rowCount() == 1){
    echo '<h3>已将'.$user.'设为管理员! 为了您的网站安全,请立即恢复注释或删除此文件,t:'.time().'</h3>';
}else{
    echo '<h3>设置管理员账号失败,t:'.time().'</h3>';
}


?>
