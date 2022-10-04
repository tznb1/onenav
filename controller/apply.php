<?php 
$apply = $udb->get("config","Value",["Name"=>'apply']);

// 如果管理了收录功能则返回404
if ($apply != 1 ){
    header('HTTP/1.1 404 Not Found');
    header("status: 404 Not Found");
    exit;
}

$apply = getconfig('apply_switch','0');
// 用户关闭收录申请
if ( $apply == 0 ){
    if($_SERVER['REQUEST_METHOD'] === 'GET'){
        header('HTTP/1.1 404 Not Found');
        header("status: 404 Not Found");
        exit("管理员关闭了此功能2");
    }else{
        msg(-1111,"管理员关闭了此功能");
    }
}

if($_SERVER['REQUEST_METHOD'] === 'GET'){
    $categorys = get_category();//获取分类
    require('./templates/admin/apply/apply-user.php');
    exit;
}

// 不是Get请求,就当是Post吧!

//强制加载防火墙来过滤相关攻击!
$XSS = 1; $SQL = 1; require ('./class/WAF.php');

// 遍历请求表单,拦截可疑内容!
foreach($_POST as $key =>$value){
    //$key != "url"  && //URL限制不限制
    if( htmlspecialchars($value,ENT_QUOTES) != $value ){
        msg(-1103,$key.' -> 请避免使用<\'&">单引号,双引号等特殊字符!');
    }elseif( strlen($value) >= 256 ){
        msg(-1103,$key.' -> 字符串长度不允许超过256');
    }
}


$title = $_POST['title'];
$url =  $_POST['url'];
$iconurl = $_POST['iconurl'];
$description = $_POST['description'];
$category_id = intval ($_POST['category_id']);
$email = $_POST['email'];

if( !filter_var($url, FILTER_VALIDATE_URL) ) {
    msg(-1010,'URL无效!');
}elseif( !empty($iconurl) && !filter_var($iconurl, FILTER_VALIDATE_URL) ){
    msg(-1010,'网站图标无效!');
}elseif(!preg_match('/^([a-zA-Z]|[0-9])(\w|\-)+@[a-zA-Z0-9]+\.([a-zA-Z]{2,4})$/',$email)){
    msg(-1010,'联系邮箱无效!');
}elseif(!isset($_POST['category_id'])){
    msg(-1010,'分类ID不能为空!');
}elseif(!isset($_POST['title'])){
    msg(-1010,'网站标题不能为空!');
}elseif(!isset($_POST['description'])){
    msg(-1010,'网站描述不能为空!');
}
//获取和检查分类信息
$category  = $db->get("on_categorys","*",["id"=> $category_id ]);
$category_name = $category['name'];
if(!isset($category_name)){
    msg(-1010,'分类ID错误!'); //没有找到分类名称
}elseif($category['property'] == 1){
    msg(-1010,'分类ID错误!'); //禁止往私有分类添加!
}
// 检查是否重复
$url_data  = $db->get("lm_apply","*",["url"=> $url ]);
if(isset($url_data['id'])){
    if ($url_data['state'] == 0){
        msg(-1010,'审核中,请勿重复提交!');
    }elseif ($url_data['state'] == 1 || $url_data['state'] == 3 ){
        msg(-1010,'已通过,请勿重复提交!');
    }elseif ($url_data['state'] == 2){
        msg(-1010,'已拒绝,请勿重复提交!');
    }
}
if($apply != 0){
    // 统计IP 24小时内提交的数量!,超限则拦截!
    $count = $db->count("lm_apply", ["ip" => getIP() ,"time[>]" => time() - 60*60*24]);
    if ($count >= 5){
        msg(-1010,'您提交的申请数量已达到上限!请明天再试!');
    }
}


if($apply == 0){
    msg(-1111,"管理员关闭了此功能");
}elseif($apply == 1){
    // state状态码规定: 0.待审核 1.手动通过 2.已拒绝 3.自动通过
    $data = [
            'iconurl'       =>  $iconurl,
            'title'         =>  $title,
            'url'           =>  $url,
            'email'         =>  $email,
            'ip'            =>  getIP(),
            'ua'            =>  $_SERVER['HTTP_USER_AGENT'],
            'time'          =>  time(),
            'state'         =>  0,
            'category_id'   =>  $category_id,
            'category_name' =>  $category_name,
            'description'      =>  $description
            ];
    $re = $db->insert('lm_apply',$data);//插入数据库
    $row = $re->rowCount();//返回影响行数
    if($row){
        $id = $db->id();//返回ID
        msg(0,'提交成功!');
    }else{
        msg(-1011,'URL已经存在！');
    }
    msg(0,'提交成功,管理员审核中!');
}elseif($apply == 2){
    $data = [
            'iconurl'       =>  $iconurl,
            'title'         =>  $title,
            'url'           =>  $url,
            'email'         =>  $email,
            'ip'            =>  getIP(),
            'ua'            =>  $_SERVER['HTTP_USER_AGENT'],
            'time'          =>  time(),
            'state'         =>  3,
            'category_id'   =>  $category_id,
            'category_name' =>  $category_name,
            'description'      =>  $description
            ];
    $re = $db->insert('lm_apply',$data);//插入数据库
    $row = $re->rowCount();//返回影响行数
    if($row){
        $id = $db->id();//返回ID
        $data = [
            'fid'           =>  $category_id,
            'title'         =>  $title,
            'url'           =>  $url,
            'description'   =>  $description,
            'add_time'      =>  time(),
            'weight'        =>  0,
            'property'      =>  0,
            'iconurl'       =>  $iconurl
            ];
            
        try{
            $_id = $db->get('on_links','id',[ "url" => $url ] ); 
            if( !empty( $_id ) ){ msg(-1000,'URL已经存在!');} 
            $re = $db->insert('on_links',$data);//插入数据库
        }catch(Exception $e){
            msg(-1000,'系统错误:申请收录失败.');
        }
            

        $row = $re->rowCount();//返回影响行数
        if($row){
            msg(0,'收录成功!');
        }else{
            $db->update('lm_apply',["state" => 2 ],[ 'id' => $id]);
            msg(-1011,'URL已经存在！'); //存在于链接列表中!
        }
    }
}
         
            
function get_category() {
    global $db;
    $categorys = [];
    //获取父分类
    $category_parent = $db->select('on_categorys','*',["fid"   =>  0,'property'  =>  0,"ORDER" =>  ["weight" => "DESC"]]);
    //遍历父分类下的二级分类
    foreach ($category_parent as $category) {
        array_push($categorys,$category);
        $category_subs = $db->select('on_categorys','*', ["fid" => $category['id'],"ORDER" => ["weight" => "DESC"] ,'property' =>  0]);
        //合并数组
        $categorys = array_merge ($categorys,$category_subs);
    }
    return $categorys;
}


?>
