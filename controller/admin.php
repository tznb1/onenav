<?php
if($libs==''){exit('<h3>非法请求</h3>');}//禁止直接访问此接口!
Visit();//访问控制
//后台入口文件

$ip = getIP(); //获取请求IP
$Pass2 = getconfig('Pass2');
check_auth($username,$password);//检查认证
$page = empty($_GET['page']) ? 'index' : $_GET['page'];
if ( $page == 'Pass2' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if($Pass2 === $_POST['Pass2']){
        $time = time();
        $Expire = $time + 43200 ;
        $key = Getkey2($username,$Pass2,$Expire,2,$time);
        setcookie($username.'_P2', $key.'.'.$Expire.'.'.$time, 0,"/",'',false,1);
        msg(0,'successful');
    }else{
        msg(-1000,'二级密码错误!');
    }
}

if( check_Pass2() || $Pass2 ==''){
    //验证通过
}else{
    //验证不同通过,载入输入页面
    require('./templates/admin/check_Pass2.php');
    exit;
}



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

//载入主题配置
if($page == 'config'){
    $theme=$_GET['Theme'];
    $Theme='./templates/'.$theme.'/config.php';
    if (file_exists($Theme)){
        $config = 'Theme-'.$theme.'-';
        include_once($Theme);
    }else{
        exit('<h3>未找到主题配置</h3>');
    }
    exit;
}


//主题设置页面
if( $page == 'Theme' ) {
    //主题目录
    $tpl_dir = dirname(__DIR__).'/templates/';
    $tpls = [];
    //遍历目录
    foreach ( scandir($tpl_dir) as $value) {
        //完整的路径
        $path = $tpl_dir.$value;
        //如果是目录，则push到目录列表
        if( is_dir($path) ) {
            switch ($value) {
                case '.':
                case '..':
                case 'admin':
                    continue;
                    break;
                default:
                    array_push($tpls,$value);
                    break;
            }
        }
        else{
            continue;
        }
    }
    
    //读取主题里面的信息
    //设置一个空数组
    $themes = [];
    foreach ($tpls as $value) {
        //如果文件存在
        if( is_file($tpl_dir.$value.'/info.json') ) {
            $themes[$value]['info'] = json_decode(@file_get_contents( $tpl_dir.$value.'/info.json' ));
        }else{ //文件不存在时
            $themes[$value]['info']->name = $value;
            $themes[$value]['info']->description="未找到主题信息文件";
            $themes[$value]['info']->homepage="https://gitee.com/tznb/OneNav";
            $themes[$value]['info']->version="0.0.0";
            $themes[$value]['info']->update="1970/01/01";
            $themes[$value]['info']->author="未知";
            $themes[$value]['info']->screenshot="";
        }
        $themes[$value]['info']->config = is_file($tpl_dir.$value.'/config.php') ? '1':'0';
        // 截图优先顺序
        //$first = 'local';
        if( $first == 'local' && is_file($tpl_dir.$value.'/screenshot.png') ){
            $themes[$value]['info']->screenshot = "./templates/".$value."/screenshot.png";
        }elseif($first == 'local' && is_file($tpl_dir.$value.'/screenshot.jpg') ){
            $themes[$value]['info']->screenshot = "./templates/".$value."/screenshot.jpg";
        }elseif(empty($themes[$value]['info']->screenshot)){
            $themes[$value]['info']->screenshot = "./templates/admin/static/42ed3ef2c4a50f6d.png";
        }
        
        
    }

    //获取当前主题
    $current_themes1 = getconfig('Theme');  //PC 
    $current_themes2 = getconfig('Theme2'); //Pad
}

//如果是退出
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