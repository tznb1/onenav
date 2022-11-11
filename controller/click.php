<?php
if($libs==''){exit('<h3>非法请求</h3>');}//禁止直接访问此接口!
Visit();//访问控制
//获取link.id
$id = intval($_GET['id']);

//如果链接为空
if(empty($id)) {
    $msg = '<p>无效ID！</p>';
    require('./templates/admin/403.php');
    exit();
}

//查询链接信息
$link = $db->get('on_links',['id','fid','url','url_standby','property','click','title','description','tagid'],[
    'id'    =>  $id
]);

//如果查询失败
if( !$link ){
    $msg = '<p>无效ID！</p>';
    require('./templates/admin/403.php');
    exit();
}

$Themeo = getconfig('Themeo','defaulto');//读取模板名
$dir = "./templates/$Themeo"; //目录路径
$path = "./templates/{$Themeo}/link.php"; //模板路径
//如果不存在则使用默认模板
if(empty($Themeo) || $Themeo == 'defaulto' ||!file_exists($path) ){
    $path = './templates/admin/click.php';
    if($Themeo != 'defaulto') Writeconfig('Themeo','defaulto');//写默认
}
$is_login = is_login2();

//查询该ID的父及ID信息
$category = $db->get('on_categorys',['id','property','fid'],['id' => $link['fid']]);
if(!empty($category['fid']) && $category['property'] == 0){
    $fcategory = $db->get('on_categorys',['id','property','fid'],['id' => $category['fid']]);
}
//标签组>特定条件下允许访问私有链接
if( getconfig('tag_private')=='on' && !empty($link['tagid'])){
    $tag_info = $db->get('lm_tag','*',['id'=>$link['tagid']]); 
    if ( preg_match('/tag=('.$tag_info['id'].'|'.$tag_info['mark'].')/',$_SERVER['HTTP_REFERER']) ) { 
        $allow = true;
    }
}

$ICP    = $udb->get("config","Value",["Name"=>'ICP']);
$Ofooter = $udb->get("config","Value",["Name"=>'footer']);
$Ofooter = htmlspecialchars_decode(base64_decode($Ofooter));
$urlz = getconfig('urlz');
$visitorST = getconfig('visitorST');
$adminST = getconfig('adminST');

//link.id为公有，且category.id为公有 或标签组允许
if( (( $link['property'] == 0 ) && ($category['property'] == 0 ) && ($fcategory['property'] == 0 ))|| $allow || $is_login){
    //增加link.id的点击次数
    $click = $link['click'] + 1;
    //更新数据库
    $update = $db->update('on_links',[
        'click'     =>  $click
    ],[
        'id'    =>  $id
    ]);
    //如果更新成功
    if($update) {
        // 如果存在备用链接则优先使用过渡页
        if( !empty($link['url_standby']) ) {
            require($path);
            exit;
        }
        if ($urlz == '302'){
            header('location:'.$link['url']);
            exit;
        }elseif($urlz == 'Privacy'){ //隐私保护
            echo '<html lang="zh-ch"><head><title>正在保护您的隐私..</title><meta name="referrer" content="same-origin"></head>';
            header("Refresh:0;url=".$link['url']);
            exit;
        }else{
            require($path);
            exit;
        }
    }
}else{
    $msg = '<p>很抱歉，该页面是私有的，您无权限访问此页面。</p>
    <p>如果您是管理员，请尝试登录OneNav后台并重新访问。</p>';
    require('./templates/admin/403.php');
    exit();
}

