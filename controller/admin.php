<?php
if($libs==''){exit('<h3>非法请求</h3>');}//禁止直接访问此接口!
Visit();//访问控制
//后台入口文件
$ip = getIP(); //获取请求IP
check_auth($username,$password);//检查认证
$page = empty($_GET['page']) ? 'index' : $_GET['page'];
//如果页面是修改edit_category
if ($page == 'edit_category' ) {
    //获取id
    $id = intval($_GET['id']);
    //查询单条分类信息
    $category = $db->get('on_categorys','*',[ 'id'  =>  $id ]);
    //checked按钮
    if( $category['property'] == 1 ) {
        $category['checked'] = 'checked';
    }
    else{
        $category['checked'] = '';
    }
}

//如果页面是修改link
if ($page == 'edit_link') {
    //查询所有分类信息，用于分类框选择
    $categorys = $db->select('on_categorys','*',[ 'ORDER'  =>  ['weigth'    =>  'DESC'] ]);
    //获取id
    $id = intval($_GET['id']);
    //查询单条链接信息
    $link = $db->get('on_links','*',[ 'id'  =>  $id ]);
    //查询单个分类信息
    $cat_name = $db->get('on_categorys',['name'],[ 'id' =>  $link['fid'] ]);
    $cat_name = $cat_name['name'];
    
    //checked按钮
    if( $link['property'] == 1 ) {
        $link['checked'] = 'checked';
    }
    else{
        $link['checked'] = '';
    }
}

//如果页面是添加链接页面
if ( ($page == 'add_link') || ($page == 'add_link_tpl') || ($page == 'add_quick_tpl' ) || ($page == 'add_link_tpl_m' )) {
    //查询所有分类信息
    $categorys = $db->select('on_categorys','*',[ 'ORDER'  =>  ['weight'    =>  'DESC'] ]);
    //checked按钮
    if( $category['property'] == 1 ) {
        $category['checked'] = 'checked';
    }
    else{
        $category['checked'] = '';
    }
}

//导入书签页面
if ( $page == 'imp_link' ) {
    //查询所有分类信息
    $categorys = $db->select('on_categorys','*',[ 'ORDER'  =>  ['weight'    =>  'DESC'] ]);
    //checked按钮
    if( $category['property'] == 1 ) {
        $category['checked'] = 'checked';
    }
    else{
        $category['checked'] = '';
    }
}

//如果是退出
//如果页面是添加链接页面
if ($page == 'logout') {
    global $username;
    //清除cookie
    setcookie($username."_key2", '', time()-1,"/");
    //跳转到首页
    header('location:/?u='.$username);
    exit;
}

//检查授权
function check_auth($username,$password){
    global $login;
    if (!is_login2()){
        if ($login ==='login'){
            $msg = "<h3>您未登录，请<a href = 'index.php?c=".$login."&u=".$username."'>重新登录</a>！</h3>";
        }else{
            $msg = "<h3>您未登录<br />登陆入口已被隐藏!<br />请联系管理员</a>！</h3>";
        }
        require('./templates/admin/403.php');
        exit;
    }
}
// 载入前台首页模板
require('./templates/admin/'.$page.'.php');