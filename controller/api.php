<?php
if($libs==''){exit('<h3>非法请求</h3>');}//禁止直接访问此接口!
Visit();//访问控制
header("Access-Control-Allow-Origin: *"); //允许跨域访问
$method = htmlspecialchars(trim($_GET['method']),ENT_QUOTES);
//鉴权验证 Cookie验证通过,验证二级密码,Cookie验证失败时尝试验证token
if(!is_login2()){
    $Plug  = $udb->get("config","Value",["Name"=>'Plug']);// 1:兼容模式
    $token = trim($_POST['token']);
    if(strlen($token) >= 32){
        if( $token === $ApiToken ){
            // token鉴权成功(默认模式)
        }elseif( ($Plug === '1' || $Plug === '2') && $token === md5($u.$ApiToken)){
            // token鉴权成功(兼容模式)
        }else{
            msg(-1000,'-1:鉴权失败!'); 
        }
    }elseif(strlen($token) == 0){
        if( $Plug === '2' && ( $method =='link_list' || $method ==='category_list' || $method ==='get_a_link' | $method === 'get_a_category')){
            $OnlyOpen = TRUE;
            // 开启兼容模时:接口白名单,允许访问公开数据
        }else{
            msg(-1000,'-2:鉴权失败!');
        }
    }
}elseif( getconfig('Pass2') =='' || check_Pass2()){
    // Cookie 二级密码验证成功(未设置时也认为成功)
}else{
    msg(-2222,'请先验证二级密码!');
}

//demo(); //演示模式下禁止一些操作!
function demo(){
    global $method;
    // 禁止修改主页|账号设置|删除用户|上传书签|导入输入|查登录日志|全局设置|清空数据|设置主题
    $pattern = "/(edit_homepage|edit_user|user_list_del|upload|imp_link|loginlog_list|edit_root|data_empty|set_theme)/i";
    if ( preg_match($pattern,$method) ) { msg(-1010,'演示站禁止此操作!'.($method=="set_theme" ?"看主题效果请点击>预览":""));}
}

//是否加载防火墙
if($XSS == 1 || $SQL == 1){require ('./class/WAF.php');}
//获取方法并过滤,判断是否存在函数,存在则调用!反之报错!

if ( function_exists($method) ) {
    $method();
}else{
    msg(-1000,'method not found!');;
}

// 写Cookie,检测来路是否正常!
function setcookie_lm_limit($limit){
    $pattern = "/page=(link_list|category_list|root|loginlog)/i";
    if ( preg_match($pattern,$_SERVER['HTTP_REFERER']) ) {
        setcookie("lm_limit", $limit, time()+60*60*24*720);
    }
}

//分类列表
function category_list(){
    global $db,$OnlyOpen;
    $q = inject_check($_POST['query']);//获取关键字(防止SQL注入)
    
    $page = empty(intval($_REQUEST['page'])) ? 1 : intval($_REQUEST['page']);
    $limit = empty(intval($_REQUEST['limit'])) ? 20 : intval($_REQUEST['limit']);
    setcookie_lm_limit($limit);
    $offset = ($page - 1) * $limit; //起始行号
    
    if($OnlyOpen){
        $WHERE = 'a.property = 0 AND (fproperty = 0 OR fproperty is null)';
    }else{
        $WHERE = "(a.name LIKE '%$q%' OR a.description LIKE '%$q%')";
    }

    $query_sql = 
        "SELECT a.*,\n". 
        "(SELECT Icon FROM on_categorys WHERE id = a.fid LIMIT 1 ) AS fIcon,\n".
        "(SELECT name FROM on_categorys WHERE id = a.fid LIMIT 1 ) AS fname,\n".
        "(SELECT property FROM on_categorys WHERE id = a.fid LIMIT 1 ) AS fproperty,\n".
        "(SELECT count(*) FROM on_links  WHERE fid = a.id ) AS count\n".
        "FROM on_categorys AS a\n".
        "WHERE $WHERE\n".
        "ORDER BY a.weight DESC, a.id DESC LIMIT {$limit} OFFSET {$offset}";
    
    $count_sql = 
        "SELECT COUNT(1) AS COUNT,\n". 
        "(SELECT property FROM on_categorys WHERE id = a.fid LIMIT 1 ) AS fproperty\n".
        "FROM on_categorys AS a WHERE $WHERE";

    //统计总数
    $count_re = $db->query($count_sql)->fetchAll();
    $count = intval($count_re[0]['COUNT']);
    //查询
    $datas = $db->query($query_sql)->fetchAll(); //原生查询
    msgA(['code'=>0,'msg'=>'','count'=>$count,'data'=>$datas 
    //,'sql' =>$query_sql
    ]);
}

//添加分类
function add_category(){
    global $db;
    $name = $_POST['name'];//获取分类名称
    $Icon = $_POST['Icon'];//获取分类图标
    $property = empty($_POST['property']) ? 0 : 1;//获取私有属性
    $weight = empty($_POST['weight']) ? 0 : intval($_POST['weight']);//获取权重
    $description = $_POST['description']; //获取描述
    $fid = intval($_POST['fid']);
    if(empty($name)){
        msg(-1004,'分类名称不能为空！');
    }elseif(!empty($Icon) && !preg_match('/^(layui-icon-|fa-)([A-Za-z0-9]|-)+$/',$Icon)){
        msg(-1004,'无效的分类图标！');
    }elseif($fid !== 0 ){
        $count = $db->count("on_categorys", ["fid" => $id]);
        if(!empty($count)) {
            msg(-2000,'添加失败，该分类下已存在子分类！');
        }
        $target_fid = $db->get("on_categorys", "fid", ["id" => $fid]);
        if($target_fid > 0 ) { 
            msg(-2000,'添加失败，父级分类不能是二级分类！');
        }elseif($target_fid === null){
            msg(-2000,'添加失败，父级分类不存在！');
        }
       
    }

    $data = [
        'name'          =>  htmlspecialchars($name,ENT_QUOTES),
        'add_time'      =>  time(),
        'weight'        =>  $weight,
        'property'      =>  $property,
        'description'   =>  htmlspecialchars($description,ENT_QUOTES),
        'Icon'          =>  htmlspecialchars($Icon,ENT_QUOTES),
        'fid'           =>  $fid
        ];
    try{
        $_id = $db->get('on_categorys','id',[ "name" => htmlspecialchars($name,ENT_QUOTES) ] ); 
        if( !empty( $_id ) ){ msg(-1000,'分类已经存在,ID:'.$_id);} 
        $db->insert("on_categorys",$data); //插入分类目录
    }catch(Exception $e){
        msg(-1000,'添加分类失败！');
    }
    
    $id = $db->id();//返回ID
    msgA(['code'=>0,'id'=>intval($id)]); //成功返回
}

//修改分类
function edit_category(){
    global $db;
    $id = intval($_POST['id']);//获取ID
    $fid = intval($_POST['fid']); //获取父分类ID
    $name = $_POST['name'];//获取分类名称
    $Icon = $_POST['Icon'];//获取分类图标
    $property = empty($_POST['property']) ? 0 : 1;//获取私有属性
    $weight = empty($_POST['weight']) ? 0 : intval($_POST['weight']);//获取权重
    $description = $_POST['description']; //获取描述
    if(empty($id)){
        msg(-1003,'分类ID不能为空！');
    }elseif(empty($name)){
        msg(-1004,'分类名称不能为空！');
    }elseif(!empty($Icon) && !preg_match('/^(layui-icon-|fa-)([A-Za-z0-9]|-)+$/',$Icon)){
        msg(-1004,'无效的分类图标！');
    }elseif($id === $fid ){
        msg(-1004,'父级分类不能是自己！');
    }elseif($fid !== 0 ){
        $count = $db->count("on_categorys", ["fid" => $id]);
        if(!empty($count)) {
            msg(-2000,'修改失败，该分类下已存在子分类！');
        }
        $target_fid = $db->get("on_categorys", "fid", ["id" => $fid]);
        if($target_fid > 0 ) { 
            msg(-2000,'修改失败，父级分类不能是二级分类！');
        }elseif($target_fid === null){
            msg(-2000,'修改失败，父级分类不存在！');
        }
       
    }

    $data = [
        'name'          =>  htmlspecialchars($name,ENT_QUOTES),
        'up_time'       =>  time(),
        'weight'        =>  $weight,
        'property'      =>  $property,
        'description'   =>  htmlspecialchars($description,ENT_QUOTES),
        'Icon'          =>  htmlspecialchars($Icon,ENT_QUOTES),
        'fid'           =>  intval($fid)
        ];
    //防止未传递父id时被清空
    if( !isset($_POST['fid']) ) {
        array_pop($data);
    }
    
    try{
        $_id = $db->get('on_categorys','id',["AND" => ["name" => htmlspecialchars($name,ENT_QUOTES) ,"id[!]" => $id]] ); 
        if( !empty( $_id ) ){ msg(-1000,'分类已经存在,ID:'.$_id);} 
        $re  = $db->update('on_categorys',$data,[ 'id' => $id]);
    }catch(Exception $e){
        msg(-1000,'修改分类失败！');
    }
    
    $row = $re->rowCount();//获取影响行数
    msg($row ? 0:-1005,$row ? 'successful':'分类名称已存在！');
}

//删除分类
function del_category(){
    global $db;
    $id = $_POST['id'];//获取ID
    $batch = intval($_POST['batch']);
    $force = intval($_POST['force']);
    if(empty($id)){
        msg(-1003,'分类ID不能为空！');
    }
    //判断是否为批量删除,为空或0则表示单条删除
    if(empty($batch)||$batch=='0'){
        //单条删除
        $count = $db->count("on_links", ["fid" => $id]); //查询数据条数
        $count2 = $db->count("on_categorys", ["fid" => $id]); 
        if($count > 0) { 
            msg(-1006,'分类目录下存在数据,不允许删除!');
        }elseif($count2 > 0){
            msg(-1006,'分类目录下存在二级分类,不允许删除!');
        }else{
            $data = $db->delete('on_categorys',[ 'id' => $id] );
            $row  = $data->rowCount();//返回影响行数
            if($row){
                msg(0,'successful');
            }else{
                msg(-1007,'分类删除失败！');
            }
        }
    }elseif($batch=='1'){
        //批量删除
        $idgroup=explode(",",$id);//分割文本
        $res='<table class="layui-table" lay-even><colgroup><col width="55"><col width="200"><col></colgroup><thead><tr><th>ID</th><th>分类名称</th><th>状态信息</th></tr></thead><tbody>';
        foreach($idgroup as $_id){
            $count = $db->count("on_links", ["fid" => $_id]);
            $count2 = $db->count("on_categorys", ["fid" => $id]); 
            $name  = geticon($db->get("on_categorys","Icon",["id"=>$_id])).$db->get("on_categorys","name",["id"=>$_id]);
            //分类有下有数据,强制删除时先删除分类ID相符的链接!
            if ($count > 0 && $force =='1'){ 
                if($count2 > 0 ){
                    $res = $res.'<tr><td>'.$_id.'</td><td>'.$name.'</td><td>强行删除:失败,因分类下存在二级分类!</td></tr>';
                    continue;
                }
                $data = $db->delete('on_links',[ 'fid' => $_id]);
                if ($count = $data->rowCount()){
                    $res = $res.'<tr><td>'.$_id.'</td><td>'.$name.'</td><td>强行删除:'.$data->rowCount().'条链接,已删除!</td></tr>';
                    $db->delete('on_categorys',[ 'id' => $_id] );
                }else{
                    $res=$res.'<tr><td>'.$_id.'</td><td>'.$name.'</td><td>分类下存在:'.$count.'条链接,强行删除:'.$data->rowCount().'条链接,删除失败!</td></tr>';
                }
            }elseif($count > 0 || $count2 > 0){ 
                //分类下有数据,非强制删除,提示删除失败
                $res=$res.'<tr><td>'.$_id.'</td><td>'.$name.'</td><td>分类目录下存在'.$count.'条链接,'.$count2.'组分类,删除失败!</td></tr>';
            }else{
                //分类下没有数据,直接删除
                $data = $db->delete('on_categorys',[ 'id' => $_id] );
                $row = $data->rowCount();//返回影响行数
                if($row) {
                    $res=$res.'<tr><td>'.$_id.'</td><td>'.$name.'</td><td>已删除!</td></tr>';
                }else{
                    $res=$res.'<tr><td>'.$_id.'</td><td>'.$name.'</td><td>删除失败!</td></tr>';
                }
            }
        }
        msgA(['code'=>0,'msg'=>'successful','res'=> $res.'</tbody></table>']);
    }
}

//链接列表 
function link_list(){
    global $db,$OnlyOpen;
    $q = inject_check($_POST['query']);//获取关键字(防止SQL注入)
    $page = empty(intval($_REQUEST['page'])) ? 1 : intval($_REQUEST['page']);
    $limit = empty(intval($_REQUEST['limit'])) ? 20 : intval($_REQUEST['limit']);
    setcookie_lm_limit($limit);
    $offset = ($page - 1) * $limit; //起始行号
    $fid = intval(@$_POST['fid']); //获取分类ID
    $tagid = $_REQUEST['tagid']; //获取标签ID
    
    //查询条件
    $class = empty($fid) ? "":"And on_links.fid = $fid";  //分类筛选
    if( $tagid == '0' ||  intval($tagid) > '0' ) {
       $class = $class . " And on_links.tagid = " . intval($tagid);
    }

    if($OnlyOpen){ //访客模式(不支持搜索,仅支持分类筛选)
        $WHERE = "on_links.property = 0 AND f.property = 0 AND (fcp = 0 OR fcp is null) $class";
        $fcp = "(SELECT property FROM on_categorys WHERE id = f.fid LIMIT 1 ) AS fcp,"; //查询父分类的私有属性
    } else{  
        $WHERE = "(on_links.title LIKE '%$q%' OR on_links.description LIKE '%$q%' OR on_links.url LIKE '%$q%') $class";
    }
    
    //统计语句
    $count_sql = "SELECT {$fcp}COUNT(1) AS COUNT FROM on_links INNER JOIN on_categorys AS f ON f.id = on_links.fid WHERE $WHERE";
    //查询语句
    $query_sql = "SELECT {$fcp}on_links.*, f.name AS category_name FROM on_links INNER JOIN on_categorys AS f ON f.id = on_links.fid ".
                 "WHERE $WHERE ORDER BY weight DESC,id DESC LIMIT $limit OFFSET $offset";
                 
    //统计总数
    $count_re = $db->query($count_sql)->fetchAll();
    $count = intval($count_re[0]['COUNT']);
    //查询
    $datas = $db->query($query_sql)->fetchAll();  
    msgA(['code'=>0,'msg'=>'','count'=>$count,'data'=>$datas,
    //"sql" => $query_sql 
    ]);
}

//添加链接
function add_link(){
    global $db,$username;
    $fid = intval(@$_POST['fid']); //获取分类ID
    $title = $_POST['title'];
    $url = $_POST['url'];
    $url_standby = $_POST['url_standby'];
    $iconurl = $_POST['iconurl'];
    $description = empty($_POST['description']) ? '' : $_POST['description'];
    $weight = empty($_POST['weight']) ? 0 : intval($_POST['weight']);
    $property = empty($_POST['property']) ? 0 : 1;
    check_link($fid,$title,$url,$url_standby); //检测链接是否合法
    
    $data = [
            'fid'           =>  $fid,
            'title'         =>  htmlspecialchars($title,ENT_QUOTES),
            'url'           =>  $url,
            'url_standby'   =>  $url_standby,
            'description'   =>  htmlspecialchars($description,ENT_QUOTES),
            'add_time'      =>  time(),
            'weight'        =>  $weight,
            'property'      =>  $property,
            'iconurl'       =>  $iconurl
            ];
            
            
    try{
        $_id = $db->get('on_links','id',[ "url" => $url ] ); 
        if( !empty( $_id ) ){ msg(-1000,'URL已经存在,ID:'.$_id);} 
        $re = $db->insert('on_links',$data);//插入数据库
    }catch(Exception $e){
        msg(-1000,'添加链接失败！');
    }
    
    $id = $db->id();//返回ID
    
    //图标处理
    if(!empty($_POST['icon_base64']) && UGet('iconUP') == 1 ){
        $path = "data/user/{$username}/favicon/{$id}";
        unlink($path.'.jpg');unlink($path.'.png');unlink($path.'.ico');unlink($path.'.svg');
        if($_POST['icon_base64'] == 'del'){
            
        }elseif(preg_match('/data:image\/(jpeg|png|x-icon|svg-xml|svg\+xml);base64,(\S+)/', $_POST['icon_base64'], $result)){
            if( GetFileSize($result[2]) > 1024) {msg(-1015,'文件大小不能超过1M');}
            //根据MIME类型写扩展名
            if($result[1] == 'jpeg'){
                $path = $path.'.jpg';
            }elseif($result[1] == 'png'){
                $path = $path.'.png';
            }elseif($result[1] == 'x-icon'){
                $path = $path.'.ico';
            }elseif($result[1] == 'svg-xml' || $result[1] == 'svg+xml'){
                $path = $path.'.svg';
            }
            
            if( Check_Path("data/user/{$username}/favicon") && file_put_contents($path, base64_decode($result[2]))){
                $iconurl = "./{$path}";
                $db->update('on_links',['iconurl'=>  $iconurl],[ 'id' => $id]); //更新图标URL
            }else{
                msg(-1015,'链接已添加,但写入图标失败,请检查权限!');
            }
        }else{
            msg(-1015,'链接已添加,图标因格式不支持未保存!');
        }
    }
    
    msgA(['code'=>0,'id'=>intval($id),'path'=>$iconurl]);
}


//修改链接
function edit_link(){
    global $db,$username;
    $id  = intval(@$_POST['id']);  //获取链接ID
    $fid = intval(@$_POST['fid']); //获取分类ID
    $title = $_POST['title'];
    $url = $_POST['url'];
    $url_standby = $_POST['url_standby'];
    $iconurl = $_POST['iconurl'];
    $description = empty($_POST['description']) ? '' : $_POST['description'];
    $weight = empty($_POST['weight']) ? 0 : intval($_POST['weight']);
    $property = empty($_POST['property']) ? 0 : 1;
    check_link($fid,$title,$url,$url_standby); //检测链接是否合法
    
    //图标处理
    if(!empty($_POST['icon_base64'])  && UGet('iconUP') == 1 ){
        $path = "data/user/{$username}/favicon/{$id}";
        unlink($path.'.jpg');unlink($path.'.png');unlink($path.'.ico');unlink($path.'.svg');
        if($_POST['icon_base64'] == 'del'){
            
        }elseif(preg_match('/data:image\/(jpeg|png|x-icon|svg-xml|svg\+xml);base64,(\S+)/', $_POST['icon_base64'], $result)){
            if( GetFileSize($result[2]) > 1024) {msg(-1015,'文件大小不能超过1M');}
            //根据MIME类型写扩展名
            if($result[1] == 'jpeg'){
                $path = $path.'.jpg';
            }elseif($result[1] == 'png'){
                $path = $path.'.png';
            }elseif($result[1] == 'x-icon'){
                $path = $path.'.ico';
            }elseif($result[1] == 'svg-xml' || $result[1] == 'svg+xml'){
                $path = $path.'.svg';
            }
            
            if( Check_Path("data/user/{$username}/favicon") && file_put_contents($path, base64_decode($result[2]))){
                $iconurl = "./{$path}";
            }else{
                msg(-1015,'写入图标失败,请检查权限!');
            }
        }else{
            msg(-1015,'不支持的图标格式.');
        }
    }
    
    $data = [
            'fid'           =>  $fid,
            'title'         =>  htmlspecialchars($title,ENT_QUOTES),
            'url'           =>  $url,
            'url_standby'   =>  $url_standby,
            'description'   =>  htmlspecialchars($description,ENT_QUOTES),
            'up_time'       =>  time(),
            'weight'        =>  $weight,
            'property'      =>  $property,
            'iconurl'       =>  $iconurl
            ];
    if( !isset($iconurl) ) {
        array_pop($data);
    }
    
    
    try{
        $_id = $db->get('on_links','id',["AND" => ["url" => $url,"id[!]" => $id]] ); 
        if( !empty( $_id ) ){ msg(-1000,'URL已经存在,ID:'.$_id);} 
        $re = $db->update('on_links',$data,[ 'id' => $id]); //更新数据
    }catch(Exception $e){
        msg(-1000,'修改链接失败！');
    }
    
    $row = $re->rowCount();//返回影响行数
    if($row){
        msgA(['code'=>0,'id'=>$id,'path' => $iconurl]);
    }else{
        msg(-1011,'URL已经存在！');
    }
}
//批量设置链接属性
function set_link_attribute() {
    global $db;
    $ids = $_POST['ids'];
    $property = intval($_POST['property']);
    if( $property != 0 && $property !=1 ){msg(-2000,'链接属性错误,只能是0或1!');}
    $re = $db->update('on_links',["property" => $property],[ 'id' => explode(",",$ids)]);
    //拼接SQL文件
    // $sql = "UPDATE on_links SET property = $property WHERE id IN ($ids)";
    // $re = $db->query($sql);
    //返回影响行数
    $row = $re->rowCount();
    if ( $row > 0 ){
        msg(200,"success");
    }else{
        msg(-2000,"failed"); 
    }
}
//删除链接
function del_link(){
    global $db;
    $id = $_POST['id'];
    $batch = intval($_POST['batch']);
    if( empty($id)){
		msg(-1003,'链接ID不能为空！');
	}
	//判断是否为批量删除
	if(empty($batch)||$batch=='0'){
	    //单条删除
	    $id = intval($id);
		$count = $db->count('on_links',[ 'id' => $id]);//查询ID是否存在
		if((empty($id)) || ($count == false)) {
			msg(-1010,'.链接ID不存在！');
		}else{
			$re = $db->delete('on_links',[ 'id' =>  $id] );
			if($re) {
			    del_icon($id);//删除图标
				msg(0,'successful');
			} else {
				msg(-1010,'链接ID不存在！');
			}
		}
	}elseif($batch=='1'){
	    //批量删除
	    $idgroup=explode(",",$id);//分割文本
	    foreach($idgroup as $_id){
	        del_icon($_id);//删除图标
	        $db->delete('on_links',['id'=>intval($_id)]);
	     }
	    msg(0,'successful');
	}
}

//获取单个链接信息
function get_a_link(){
    global $db,$OnlyOpen;
    $id = intval(trim($_GET['id']));
    if(empty($id)){ $id = intval(trim($_POST['id'])); }
    if(empty($id)){ msg(-1010,'id不能为空！'); }
    $link_info = $db->get("on_links","*",["id" => intval($id)]);
    
    //访客模式,如果链接是私有就返回空
    if($OnlyOpen){
        if ( $link_info['property'] == "0" ) {
            $category_property = $db->get("on_categorys","*",["id" => $link_info['fid']]);
            if( $category_property["fid"] > 0 ){ //如果分类是二级则追查父的属性
                $fcategory_property = $db->get("on_categorys","property",["id" => $category_property["fid"]]);
            }
            if($category_property["property"] == 0 && $fcategory_property == 0){ //判断父分类是否公有
                msgA( ['code'=>0,'data'=>$link_info] );
            }else{
                msgA( ['code'=>0,'data'=>[] ] );
            }
        }else{ //链接私有返回空
            msgA( ['code'=>0,'data'=>[] ] );
        }
    }else{ //登录状态,直接返回
        msgA(['code'=>0,'data'=>$link_info]);
    }
}
//获取单个分类信息
function get_a_category() {
    global $db,$OnlyOpen;
    $id = intval(trim($_GET['id']));
    if( empty($id) ){  //如果Get取ID为空则尝试从POST取
        $id = intval(trim($_POST['id'])); 
    }
    if( empty($id) ){  //如果还是为空则报错
        msg(-1010,'id不能为空！'); 
    }
    
    $query_sql = "SELECT *, (SELECT property FROM on_categorys WHERE id = a.fid LIMIT 1 ) AS fproperty FROM on_categorys AS a WHERE a.id = " . $id . ($OnlyOpen ? " AND a.property = 0":"") . " LIMIT 1";
    
    $category_info = $db->query($query_sql)->fetchAll()[0];  
    if($category_info == null ){
        msgA( ['code'=>0,'data'=>[] ] );
    }elseif ( $OnlyOpen && $category_info['fproperty'] == "1" ){
        msgA( ['code'=>0,'data'=>[] ,'msg' => "父分类为私有"] );
    }else{
        msgA( ['code'=>0,'data'=>$category_info] );
    }
}
//获取链接信息
function get_link_info() {
    global $offline;
    $url = @$_POST['url']; //获取URL
    //检查链接是否合法
    if( empty($url) ) {
        msg(-1010,'URL不能为空!');
    }elseif(!preg_match("/^(http:\/\/|https:\/\/).*/",$url)){
        msg(-1010,'只支持识别http/https协议的链接!');
    }elseif( !filter_var($url, FILTER_VALIDATE_URL) ) {
         msg(-1010,'URL无效!');
    }
    if ( $offline ){ msgA(['code'=>0,'data'=>[]]); } //离线模式
    //获取网站标题 (HTML/JS跳转无法识别)
    $c = curl_init(); 
    curl_setopt($c, CURLOPT_URL, $url); 
    curl_setopt($c, CURLOPT_RETURNTRANSFER, 1); 
    curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($c, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($c, CURLOPT_FOLLOWLOCATION, 1); //允许重定向,解决http跳转到https无法识别
    curl_setopt($c , CURLOPT_TIMEOUT, 5); //设置超时时间
    $data = curl_exec($c); 
    curl_close($c); 

    require ('./class/get_page_info.php');
    $info = get_page_info($data);
    //var_dump($info);
    $link['title'] =  $info['site_title'];
    $link['description'] = $info['site_description'];
    msgA(['code'=>0,'data'=>$link]);
}


//删除图标
function del_icon($id){
    global $username;
    $path = "data/user/{$username}/favicon/{$id}";
    unlink($path.'.jpg');unlink($path.'.png');unlink($path.'.ico');unlink($path.'.svg');
}

//上传书签
function upload(){
    global $username;
    delfile('data/upload',5); //清理5分钟前的数据!
    $type = $_GET['type'];//获取上传类型
    if ($_FILES["file"]["error"] > 0){
        msg(-1015,'文件上传失败!');
    }else{
        //获取文件名
        $filename = $_FILES["file"]["name"];
        //获取文件后缀
        $suffix = explode('.',$filename);
        $suffix = strtolower(end($suffix));
        //临时文件位置
        $temp = $_FILES["file"]["tmp_name"];
        //检查文件名后缀
        if($suffix != 'html'  && $suffix != 'db3'){
            unlink($filename);//不支持的文件,删除临时文件!
            msg(-1014,'不支持的文件后缀名！');
        }
        $filename = time().'-'.$username.'.'.$suffix;
        //检查目录
        if(!is_dir('data/upload')){
            mkdir('data/upload',0755,true);
        }
        //转移上传的文件(不转移的话代码执行完毕文件就会被删除) 
        if(copy($temp,'data/upload/'.$filename)){
            msgA(['code'=>0,'file_name' =>'data/upload/'.$filename ,"suffix" => $suffix] );
        }
    }
}
//书签导入
function imp_link() {
    global $db,$userdb;
    $filename = trim($_POST['filename']);//书签路径
    //过滤$filename
    $filename = str_replace('../','',$filename);
    $filename = str_replace('./','',$filename);
    $fid = intval($_POST['fid']); //所属分类
    $property = intval(@$_POST['property']); //私有属性
    $all = intval(@$_POST['all']) == 1?true:false; //保留属性(db3)
    $suffix = strtolower(end(explode('.',$filename)));
    $AutoClass = $_POST['AutoClass']; //自动分类(HTML)
    //路径过滤
    if(substr($filename,0, 12)!=='data/upload/'){
        msg(-1016,'路径非法!');
    }elseif(!file_exists($filename)){
        msg(-1016,'文件不存在!');
    }
    //表头
    $res='<table class="layui-table" lay-even><colgroup><col width="200"><col width="250"><col></colgroup><thead><tr><th>标题</th><th>URL</th><th>失败原因</th></tr></thead><tbody>';
    //导入数据为db3
    if($suffix==='db3'){

        $tempdb = new Medoo\Medoo(['database_type' => 'sqlite', 'database_file' => $filename]);
        $category_parent_New = []; 
        try{
            $fid = count($tempdb->query("select * from sqlite_master where name = 'on_categorys' and sql like '%fid%'")->fetchAll()) == 0 ? false:true; //是否有有二级分类
        }catch(Exception $e){
            unlink($filename);//删除书签
            msgA(['code'=>-1111,'msg'=>'读取数据库异常!']);
        }
            
        //根据条件来觉得是取父分类还是全部
        if($fid){
            $category_parent = $tempdb->select('on_categorys','*',["fid" => 0]); //获取父分类
        }else{
            $category_parent = $tempdb->select('on_categorys','*'); //获取分类 (不支持二级分类前的版本)
        }
        
        //处理父分类
        foreach ($category_parent as $category) {      
            $id = insert_categorys($category['name'],$category,$all);
            if( $id != $category['id'] ){ 
                $tempdb->update('on_links',['fid' => $id ],[ 'fid' => $category['id']]); //更新链接所属的分类ID
                if($fid){
                    $tempdb->update('on_categorys',['fid' => $id ],[ 'fid' => $category['id']]);//更新二级分类id
                }
                $category['id'] = $id; //修改新ID
            }
            if ($fid) array_push($category_parent_New,$category); 
        }
        
        //处理二级分类
        foreach ($category_parent_New as $category) {
            $category_subs = $tempdb->select('on_categorys','*',["fid" => $category['id'],"ORDER" => ["weight" => "DESC"] ]);
            foreach ($category_subs as $category2) {
                $id = insert_categorys($category2['name'],$category2,$all);
                if($id != $category2['id']){ 
                    $tempdb->update('on_links',['fid' => $id ],[ 'fid' => $id]); //更新链接所属的分类ID
                }
            }
        }
        //分类处理End
        
        //标签组导入
        try{ //是否存在标签组表
            $tagv = count($tempdb->query("select * from sqlite_master where name = 'lm_tag' and sql like '%id%'")->fetchAll()) == 0 ? false:true;
        }catch(Exception $e){
            $tagv = false;
        }
        //存在则处理
        if($tagv){
            $tags = $tempdb->select('lm_tag','*');
            $taga = [];
            foreach ($tags as $tag) {
                $old_id = 0;
                if(!empty($db->get('lm_tag','id',[ 'id' =>  $tag['id'] ]))){
                    //已经有这个ID了,看看名称和标识有无冲突
                    if(empty($db->get('lm_tag','id',["OR" => ["name" => $tag['name'] ,"mark" => $tag['mark']] ]  ))){
                        //不冲突,插入标签组信息(用新ID)
                        $old_id = $tag['id']; 
                        unset($tag['id']); 
                    }
                }

                try{
                    $db->insert('lm_tag',$tag);//插入数据库
                    $id = $db->id();
                    if(!empty($old_id)) { 
                        $tempdb->update('on_links',['tagid' => $id ],[ 'tagid' => $old_id ] );
                    }
                    $taga[$id] = $tag['name'];
                }catch(Exception $e){
                    //失败暂不处理
                }
            }
        }
        //标签导入End
        
        //导入链接
        $links = $tempdb->select('on_links','*');
        $total = count($links); $fail = 0; $success = 0;
        //遍历链接
        foreach($links as $link){
            //如果标题或URL为空,则跳过
            if( ($link['title'] == '') || ($link['url'] == '') ) {
                $fail++;
                $res=$res.'<tr><td>'.mb_substr($link['title'], 0, 30).'</td><td>'.mb_substr($link['url'], 0, 50).'</td><td>标题或URL为空</td></tr>';
                continue;
            }
            $link['title'] = htmlspecialchars($link['title'],ENT_QUOTES);
            if(check_xss($link['url'])){
                $fail++;
                $res=$res.'<tr><td>'.mb_substr($link['title'], 0, 30).'</td><td>'.'***'.'</td><td>URL存在非法字符</td></tr>';
                continue;
            }
                $data = [
                'fid'           =>  intval($link['fid']),
                'title'         =>  $link['title'],
                'url'           =>  $link['url'],
                'description'   =>  htmlspecialchars($link['description'],ENT_QUOTES),
                'add_time'      =>  $all == 1 ? $link['add_time']:time(),
                'up_time'       =>  $all == 1 ? $link['up_time']:null,
                'weight'        =>  $all == 1 ? $link['weight']:0,
                'property'      =>  empty($link['property']) ? 0 : 1,
                'click'         =>  $all == 1 ? $link['click']:0,
                'url_standby'   =>  $link['url_standby'],
                'iconurl'       =>  $link['iconurl'],
                'tagid'         =>  empty($taga[$link['tagid']]) ? 0 : $link['tagid']
                ];
                
                try{
                    $re = $db->insert('on_links',$data);//插入数据库
                    $row = $re->rowCount();//返回影响行数
                }catch(Exception $e){
                    $row = 0; 
                }
 
                if( $row ){
                    $success++;//成功计数+1 (成功不写到表)
                }else{
                    $fail++; //失败计数+1,写失败原因!
                    $res=$res.'<tr><td>'.mb_substr($link['title'], 0, 30).'</td><td>'.mb_substr($link['url'], 0, 50).'</td><td>URL重复</td></tr>';
                }
            }
        unlink($filename);//删除书签
        msgA(['code'=>0,'msg'=>'总数：'.$total.' 成功：'.$success.' 失败：'.$fail,'res'  =>  $res.'</tbody></table>','fail'=>$fail]);
    }
    //导入数据为HTML
    if($suffix==='html' && $AutoClass != '1'){
        //判断所属分类
        if(empty($fid)){msg(-1016,'上传html格式时所属分类不能为空!');}
        $content = file_get_contents($filename);//读入文件
        preg_match_all("/<A.*<\/A>/i",$content,$arr);//正则查找链接信息
        $fail = 0;//失败次数
        $success = 0;//成功次数
        $total = count($arr[0]);//取链接条数
        //遍历链接
        foreach( $arr[0] as $link ){
            preg_match("/http.*\"? ADD_DATE/i",$link,$urls);//正则匹配URL
            $url = str_replace('" ADD_DATE','',$urls[0]);//提取URL
            preg_match("/>.*<\/a>$/i",$link,$titles);//正则匹配标题
            $title = str_replace('>','',$titles[0]);//提取标题
            $title = str_replace('</A','',$title);//提取标题
            $title = htmlspecialchars($title,ENT_QUOTES);
            //如果标题或链接为空，则不导入
            if( ($title == '') || ($url == '') ) {
                $fail++;//失败计数+1,写失败原因!
                $res=$res.'<tr><td>'.mb_substr($title, 0, 30).'</td><td>'.mb_substr($url, 0, 30).'</td><td>标题或URL为空</td></tr>';
                continue;//跳过
            }
            if(check_xss($url)){
                $fail++;
                $res=$res.'<tr><td>'.mb_substr($title, 0, 30).'</td><td>'.'***'.'</td><td>存在非法字符</td></tr>';
                continue;
            }
            $data = [
                'fid'           =>  $fid,
                'description'   =>  '',
                'add_time'      =>  time(),
                'weight'        =>  0,
                'property'      =>  $property,
                'title'         =>  $title ,
                'url'           =>  $url
            ];
            try{
                $re = $db->insert('on_links',$data);//插入数据库
                $row = $re->rowCount();//返回影响行数
            }catch(Exception $e){
                $row = 0; 
            }
            
            if( $row ){
                $id = $db->id();
                $data = ['code'=>0,'id'=>$id];
                $success++;
            }else{
                $fail++;
                $res=$res.'<tr><td>'.mb_substr($title, 0, 30).'</td><td>'.mb_substr($url, 0, 40).'</td><td>URL重复</td></tr>';
            }
        }
        unlink($filename);//删除临时文件
        $data = [
            'code'      =>  0,
            'msg'       =>  '总数：'.$total.' 成功：'.$success.' 失败：'.$fail,
            'res'       =>  $res.'</tbody></table>',
            'fail'      =>  $fail
            ];
        msgA($data);
    }
    
    //导入数据为HTML 新版分类!
    if($suffix==='html' && $AutoClass === '1'){
        if(empty($fid)){msg(-1016,'上传html格式时所属分类不能为空!');}
        $content = file_get_contents($filename);//读入文件
        $HTMLs = explode("\n",$content);//分割文本
        $data = []; //链接组
        $categorys = []; //分类信息组
        $categoryt = []; //分类信息表
        $fcategorys = []; //上级分类
        $ADD_DATE =  intval(@$_POST['ADD_DATE']);
        $icon     =  intval(@$_POST['icon']);  
        $iconcount = 0 ;
        $default_category = $db-> get('on_categorys', 'name', ['id' => $fid]);
        if(empty($default_category)){msg(-1016,'获取分类名失败!');}
        
        //如果提取图标的话检测目录是否存在,不存在则创建目录
        $new_file = 'data/user/'.$userdb['User'].'/favicon';
        if($icon == 1 && !file_exists($new_file)){
            mkdir($new_file, 0777);
        }
        
        // 遍历HTML
        $Hierarchy = 0;
        foreach( $HTMLs as $HTMLh ){
            if( preg_match("/<DT><H3.+>(.*)<\/H3>/i",$HTMLh,$category) ){
                //匹配到文件夹名时加入数组
                $Hierarchy ++;
                $category[1] = empty($category[1]) ? $default_category : $category[1];
                array_push($categoryt,$category[1]);
                array_push($categorys,$category[1]);
                if($Hierarchy == 3){
                    $fcategorys[$category[1]] = $categorys[$Hierarchy - 2];
                }
            }elseif( preg_match('/<DT><A HREF="(.*)" ADD_DATE="(\d*)".*>(.*)<\/A>/i',$HTMLh,$urls) ){
                // 1.链接 2.添加时间 3.标题
                $datat['category']  = $categorys[count($categorys) -1];
                $datat['category']  = empty($datat['category']) ? $default_category : $datat['category'] ;
                $datat['ADD_DATE']  = $urls[2];
                $datat['title']     = $urls[3];
                $datat['url']       = $urls[1];
                $datat['html']   = $HTMLh;
                //$datat['Hierarchy'] = $Hierarchy;
                //$datat['fcategory'] = $categorys[$Hierarchy - 2];
                array_push($data,$datat);
            }elseif( preg_match('/<\/DL><p>/i',$HTMLh) ){
                //匹配到文件夹结束标记时删除一个
                $Hierarchy --;
                array_pop($categorys);
            }
        }
        //遍历结束,分类名去重!
        $categoryt = array_unique($categoryt);
        //var_dump($categoryt);var_dump($fcategorys);//exit;
         
        // 检查和创建分类
        $fids = [];
        $currenttime = time();
        foreach( $categoryt as $name ){
            $id = $db-> get('on_categorys', 'id', ['name' => $name]);
            if( empty($id) ){
                //插入分类目录
                $db->insert("on_categorys",['name' => $name,'add_time' => $currenttime,'property' => $property ,'Icon' => 'fa-folder']); 
                $id = $db->id();//返回ID
                if(empty($id)){
                    msg(-1000,'意外结束:分类已存在!');
                }else{
                    $fids[$name] = $id;
                }
            }else{
                $fids[$name] = $id;
            }
        }
        $fids[$default_category] = $fid; //加入默认分类
        //var_dump($fids);var_dump($data);exit;
        
        //二级分类处理
        if($_POST['2Class'] == 1){
            foreach( $fcategorys as $name3 => $name2 ){
                $re = $db->update('on_categorys',['fid' => $fids[$name2],'Icon'=>'fa-folder-o' ],[ 'id' => $fids[$name3]]); //更新数据
            }
        }


        // 遍历导入链接
        $fail = 0; $success = 0;
        $data = array_reverse($data); //数组倒序(这样导入后链接的顺序和浏览器一样)
        
        if( $ADD_DATE != 1 ) { $time = $currenttime; } //如果不保留时间则使用当前时间!
        foreach( $data as $link ){
            //如果标题或链接为空，则不导入
            if( empty($link['url']) || empty($link['title']) ) {
                $fail++;//失败计数+1,写失败原因!
                $res=$res.'<tr><td>'.mb_substr($link['title'], 0, 30).'</td><td>'.mb_substr($link['url'], 0, 30).'</td><td>标题或URL为空</td></tr>';
                continue;//跳过
            }
            //检查链接xss
            if(check_xss($link['url'])){
                $fail++;
                $res=$res.'<tr><td>'.mb_substr(htmlspecialchars($link['title'],ENT_QUOTES), 0, 30).'</td><td>'.mb_substr(htmlspecialchars($link['url'],ENT_QUOTES), 0, 30).'</td><td>URL存在非法字符</td></tr>';
                continue;
            }
            //检查标题xss
            if(check_xss($link['title'])){
                $fail++;
                $res=$res.'<tr><td>'.mb_substr(htmlspecialchars($link['title'],ENT_QUOTES), 0, 30).'</td><td>'.mb_substr(htmlspecialchars($link['url'],ENT_QUOTES), 0, 30).'</td><td>标题存在非法字符</td></tr>';
                continue;
            }
            // 检测链接是否合法
            if( !filter_var($link['url'], FILTER_VALIDATE_URL) ) {
                $fail++;
                $res=$res.'<tr><td>'.mb_substr(htmlspecialchars($link['title'],ENT_QUOTES), 0, 30).'</td><td>'.mb_substr(htmlspecialchars($link['url'],ENT_QUOTES), 0, 30).'</td><td>链接无效,只支持识别http/https协议的链接!</td></tr>';
                continue;
            }
            // 如果书签时间不合理则使用当前时间!
            if( $ADD_DATE == 1 ){
                $time = intval($link['ADD_DATE']);
                if( $time > $currenttime || $currenttime < 788889600){
                    $time = $currenttime;
                }
            }
            
            //匹配图片 data:image/png;base64,iVBORw0KGgoAAAANSUhEU
            if ($icon == 1 && preg_match('/ICON="data:image\/png;base64,(iVBORw0KGgoAAAANSUhEU\S+)"/', $link['html'], $result)){
                $path = $new_file.'/'.( $urlid + 1 ) .'.png';
                if (strlen($result[1]) <= 2731 && file_put_contents($path, base64_decode($result[1]) )  ){
                    $iconcount++;
                    $path = "./".$path;
                }else{
                    $path = '';
                }
            }else{
                $path = '';
            }
                
            //插入数据库
            try{
                $re = $db->insert('on_links',[
                    'fid'           =>  $fids[$link['category']],
                    'add_time'      =>  $time,
                    'title'         =>  $link['title'] ,
                    'url'           =>  $link['url'],
                    'property'      =>  $property,
                    'iconurl'       =>  $path
                ]);
                $row = $re->rowCount();//返回影响行数
            }catch(Exception $e){
                $row = 0;
            }
            
            if( $row ){
                $urlid = $db->id();
                $success++;
            }else{
                $res=$res.'<tr><td>'.mb_substr($link['title'], 0, 30).'</td><td>'.mb_substr($link['url'], 0, 40).'</td><td>URL重复'.'</td></tr>';
                $fail++;
                unlink($path);
            }
        }
        unlink($filename);//删除临时文件
        $data = [
            'code'      =>  0,
            'msg'       =>  '总数：'.count($data).' 成功：'.$success.' 失败：'.$fail.( $icon == 1 ? ' 图标：'.$iconcount:''),
            'res'       =>  $res.'</tbody></table>',
            'fail'      =>  $fail
            ];
        msgA($data);
        msg(0,'总数：'.count($data).' 成功：'.$success.' 失败：'.$fail);
    }
    
    msg(-1016,'不支持的文件类型!');
}

//主页设置
function edit_homepage(){
    $layid = $_POST['layid'];
    if( $layid == '1'){
        if(htmlspecialchars($_POST['title'],ENT_QUOTES) !=$_POST['title']){
            msg(-1103,'主标题存在非法字符！');
        }elseif(htmlspecialchars($_POST['subtitle'],ENT_QUOTES) !=$_POST['subtitle']){
            msg(-1103,'副标题存在非法字符！');
        }elseif(htmlspecialchars($_POST['description'],ENT_QUOTES) !=$_POST['description']){
            msg(-1103,'站点描述存在非法字符！');
        }elseif(htmlspecialchars($_POST['logo'],ENT_QUOTES) !=$_POST['logo']){
            msg(-1103,'文字logo存在非法字符！');
        }elseif(htmlspecialchars($_POST['keywords'],ENT_QUOTES) !=$_POST['keywords']){
            msg(-1103,'站点关键词存在非法字符！');
        }
        Writeconfig('title',        htmlspecialchars($_POST['title'],       ENT_QUOTES));//站点标题
        Writeconfig('subtitle',     htmlspecialchars($_POST['subtitle'],    ENT_QUOTES));//站点副标题
        Writeconfig('description',  htmlspecialchars($_POST['description'], ENT_QUOTES));//站点描述
        Writeconfig('logo',         htmlspecialchars($_POST['logo'],        ENT_QUOTES));//文字logo
        Writeconfig('keywords',     htmlspecialchars($_POST['keywords'],    ENT_QUOTES));//站点关键词
        Writeconfig('footer',       base64_encode($_POST['footer']  ));//底部代码
        Writeconfig('head',         base64_encode($_POST['head']    ));//头部代码
        msg(0,$_POST['subtitle']);
    }elseif($layid == '2'){
        Writeconfig('urlz',         strip_tags($_POST['urlz']       ));//跳转方式
        Writeconfig('gotop',        strip_tags($_POST['gotop']      ));//返回顶部
        Writeconfig('quickAdd',     strip_tags($_POST['quickAdd']   ));//快速添加
        Writeconfig('GoAdmin',      strip_tags($_POST['GoAdmin']    ));//后台入口
        Writeconfig('LoadIcon',     strip_tags($_POST['LoadIcon']   ));//加载图标
        Writeconfig('visitorST',    strip_tags($_POST['visitorST']  ));//访客停留
        Writeconfig('adminST',      strip_tags($_POST['adminST']    ));//管理员停留
        
    }
    
    msg(0,'successful');
}

//主题切换
function set_theme(){
    $type = $_REQUEST['type'];
    $name = $_REQUEST['name'];
    if( (!file_exists('./templates/'.$name) || empty($name)) && ($type != 'o' && !empty($type) )){
        msg(-1000,'主题不存在');
    }elseif ( !preg_match("/^[a-zA-Z0-9_-]{1,64}$/",$name) ) { 
        msg(-2000,"主题名称不合法！");
    }
    if( $type == 'PC/Pad'){
        Writeconfig('Theme' , $name);
        Writeconfig('Theme2', $name);
    }elseif($type == 'PC'){
        Writeconfig('Theme' , $name);
    }elseif($type == 'Pad'){
        Writeconfig('Theme2' , $name);
    }elseif($type == 'o'){
        Writeconfig('Themeo' , $name);
    }else{
        msg(-1000,'参数错误');
    }
    msg(0,'设置成功');
}
//删除主题
function del_theme(){
    is_admin();
    $name = $_POST['dir'];
    if ( !preg_match("/^[a-zA-Z0-9_-]{1,64}$/",$name) ) { 
        msg(-2000,"主题名称不合法！");
    }elseif( ($name === 'default') || ($name === 'admin') ) { 
        msg(-2000,"默认主题不允许删除！");
    }
    deldir("templates/".$name);
    if( is_dir("./templates/".$name) ) {
        msg(-2000,"删除失败，可能是权限不足！");
    }else{
        msg(200,"主题已删除！");
    }
}
function deldir($dir) {
        //先删除目录下的文件：
        $dh=opendir($dir);
        while ($file=readdir($dh)) {
          if($file!="." && $file!="..") {
            $fullpath=$dir."/".$file;
            if(!is_dir($fullpath)) {
                unlink($fullpath);
            } else {
                deldir($fullpath);
            }
          }
        }
       
        closedir($dh);
        //删除当前文件夹：
        if(rmdir($dir)) {
          return true;
        } else {
          return false;
        }
    }
//主题下载
function download_theme(){
    global $offline,$udb,$version;
    if ( $offline ){ msg(-5555,"离线模式禁止下载主题!"); } //离线模式
    if(!is_subscribe(true)){
        $download_theme_count = $udb->get('config', 'Value', ["Name"=>'download_theme_count']);
        $download_theme_count = empty($download_theme_count)?0:intval($download_theme_count);
        if($download_theme_count > 3 ){ msg(-5555,"免费下载次数已用完,订阅后可无限下载!");}
    }
    $dir = $_POST['dir'];
    $name = $_POST['name'];
    $i = intval($_POST['i']);
    //读取数据库 解析json 查找 取url1 下载  解压 返回
    if(preg_match('/^v.+-(\d{8})$/i',$version,$matches)){
        $sysver = intval( $matches[1] );
    }else{
        msg(-1110,"获取程序版本异常");
    }
    //从数据库查找主题信息
    $template = $udb->get("config","Value",["Name"=>'templatejson']);
    if(empty($template)){
        msg(-1111,'-1,未找到数据');
    }else{
        $data = json_decode($template, true); //转为数组
        foreach($data["data"] as $key){
            if( $key['dir'] === $dir && $sysver >= intval($key["low"])  && $sysver <= intval($key["high"])){
                $file = $key['dir'].".tar.gz";
                $filePath = "./data/upload/{$file}";
                break; //找到跳出
            }
        }
        if(empty($file)){
            msg(-1112,'-2,未找到数据');
        }
    }
    
    require ('./class/downFile.php');//载入下载函数
    
    for($i=1; $i<=2; $i++){
        if(!empty($key['url'.$i])){ //URL不为空
            if(downFile( $key['url'.$i] , $file , './data/upload/')){
                $file_md5 = md5_file($filePath);
                if($file_md5 === $key['md5']){
                    $downok = true;
                    break;//下载成功,跳出循环!
                }else{
                    unlink($filePath);
                }
            }
        }
    }
    
    if(!$downok || !file_exists($filePath)){
        msg(-1111,'-1,下载失败');
    }elseif($file_md5 != $key['md5']){
        msgA(['code'=>-1112,'msg'=> '效验压缩包异常','key_md5'=> $key['md5'],'file_md5'=>$file_md5]);
    }

    
    try {
        $phar = new PharData($filePath);
        $phar->extractTo('./templates', null, true); //路径 要解压的文件 是否覆盖
        unlink($filePath);//删除文件
    } catch (Exception $e) {
        msg(-1114,'-2,安装失败');
    } finally{
        if(file_exists("./templates/".$key['dir']."/info.json")){
            if(!is_subscribe(true)){
                Writeconfigd($udb,'config','download_theme_count',$download_theme_count + 1);
            }
            msgA(['code'=>0,'msg'=> '下载成功','url'=> $i,'file_md5'=>$file_md5]);
        }else{
            msgA(['code'=>-1233,'msg'=> '-3,安装失败','url'=> $i,'file_md5'=>$file_md5]);
        }
    }
}

//账号设置
function edit_user(){
    global $username,$password,$RegTime,$udb,$Skey;
    $pass = $_POST['password'];
    $newpassword = $_POST['newpassword'];
    $NewToken = $_POST['NewToken'];
    $Email = $_POST['Email'];
    $NewSkey = intval($_POST['Skey']);
    $HttpOnly = $_POST['HttpOnly'];
    $session  = intval($_POST['session']);
    $Pass2 = $_POST['Pass2'];
    if(md5($pass.$RegTime) !== $password){
        msg(-1102,'密码错误,请核对后再试！');
    }elseif(!empty($newpassword)  &&  strlen($newpassword)!=32){
        msg(-1103,'密码异常,正常情况是32位的md5！');
    }elseif(md5($newpassword.$RegTime) === $password){
        msg(-1103,'新密码不能和原密码一样!');
    }elseif(!empty($NewToken)  && strlen($NewToken)<32){
        msg(-1103,'因安全问题,Token长度不能小于32位！');
    }elseif(htmlspecialchars($Email,ENT_QUOTES) != $Email){
        msg(-1103,'Email存在非法字符！');
    }elseif(!empty($Email) && !checkEmail($Email)){
        msg(-1103,'Email格式错误！');
    }elseif($_POST['Elogin'] ==='1' && !empty($newpassword)){
        msg(-1103,'禁止修改密码时重设登陆入口!');
    }elseif($NewSkey !== $Skey && $NewSkey > 2){
        msg(-1103,'Key安全设置错误!');
    }elseif($HttpOnly !=='0' && $HttpOnly !=='1'){
        msg(-1103,'HttpOnly设置错误!');
    }elseif($udb->count("user",["Email"=>$Email]) != 0 ){
        msg(-1103,'邮箱已存在!');
    }
    //对比Cookie相关配置,如有变动则重新生成Key
    if($NewSkey != $Skey || $session !== intval(getconfig('session')) || $HttpOnly != getconfig('HttpOnly')){
        $Expire = $session == 0 ? time()+86400 : GetExpire2($session);
        $time = time();
        $key = Getkey2($username,$password,$Expire,$NewSkey,$time);
        setcookie($username.'_key2', $key.'.'.$Expire.'.'.$time, $session == 0 ? 0 : $Expire,"/",'',false,$HttpOnly==1);
    }
    if($Pass2 != getconfig('Pass2')){
        $time = time();
        $Expire = $time + 43200 ;
        $key = Getkey2($username,$Pass2,$Expire,2,$time);
        setcookie($username.'_P2', $key.'.'.$Expire.'.'.$time, 0,"/",'',false,1);
    }
    //判断是否修改邮箱(仅判断不为空就写入)
    if (!empty($Email)   ) {
        Writeconfig('Email',$Email);
        $udb->update('user',['Email'=>$Email],['User' => $username]);
    }
    //判断是否修改令牌(仅判断不为空就写入)
    if (!empty($NewToken)) {
        Writeconfig('Token',$NewToken);
        $udb->update('user',['Token'=>$NewToken],['User' => $username]);
    }
    Writeconfig('Skey',$NewSkey);  //Skey(Key安全)
    Writeconfig('HttpOnly',$HttpOnly);  //HttpOnly
    Writeconfig('session',$session);  //session 保持登陆
    Writeconfig('Pass2',$Pass2);  //二级密码
    //判断是否重设专属登陆入口
    if ($_POST['Elogin'] ==='1') {
        $Elogin = getloginC($username);
        $Re = $udb->update('user',['Login'=>$Elogin],['User' => $username]);
        if($Re->rowCount() == 1){
            Writeconfig('Login',$Elogin);
        }
    }

    //判断新密码是否为空,如果MD5=d41d8cd98f00b204e9800998ecf8427e说明是空密码!不为空就更新
    if ($newpassword !== 'd41d8cd98f00b204e9800998ecf8427e' && !empty($newpassword)){
        //写入新密码(主表)
        $Re = $udb->update('user',['Pass'=>md5($newpassword.$RegTime)],['User' => $username]);
        //判断密码是否已写入数据库..
        if($Re->rowCount() == 1){
            Writeconfig('password',md5($newpassword.$RegTime));//写入新密码(用户库)
            msgA(['code'=>0,'msg'=>'successful','logout'=>1,'u'=> $username]);
        }else{
            msg(-1111,'密码修改失败..');
        }
    }
    msg(0,'修改成功!'.(!empty($Elogin)? '入口已更新,请及时保存!':'')   );
}
//root全局配置修改
function edit_root(){
    global $udb,$u;
    $DUser      = $_POST['DUser'];   //默认账号
    $Reg        = $_POST['Reg'];     //注册用户开关
    $Register   = $_POST['Register'];//注册入口
    $login      = $_POST['login'];   //登陆入口
    $libs       = $_POST['libs'];    //静态库地址
    $visit      = $_POST['visit'];   //访问控制
    $IconAPI    = $_POST['IconAPI']; //图标API
    $ICP        = $_POST['ICP'];     //ICP备案号
    $Diy        = $_POST['Diy'];     //自定义开关
    $footer     = $_POST['footer'];  //自定义开关
    $XSS        = $_POST['XSS'];  //防XSS脚本
    $SQL        = $_POST['SQL'];  //防SQL注入
    $Plug       = $_POST['Plug'];  //插件支持
    $apply      = $_POST['apply'];  //收录功能
    $offline    = $_POST['offline'];  //离线模式
    $Pandomain  = $_POST['Pandomain']; //泛域名
    $Privacy    = $_POST['Privacy']; //强制私有
    $iconUP     = $_POST['iconUP']; //图标上传
    
    
    if($udb->get("user","Level",["User"=>$u]) != '999'){ //权限判断
        msg(-1102,'您没有权限修改全局配置!');
    }elseif($udb->count("user",["User"=>$DUser]) === 0 ){ //账号检测
        msg(-1102,'默认账号'.$DUser.'不存在,请检查!');
    }elseif($Reg !== '0' && $Reg !== '1' && $Reg !== '2'){ //注册开关检测
        msg(-1103,'注册用户参数错误');
    }elseif($Register == $login){ //入口名检测
        msg(-1103,'注册入口名不能和登录入口名相同!');
    }elseif($visit !== '0' && $visit !== '1'){ //访问控制检测
        msg(-1103,'访问控制参数错误!');
    }elseif(!is_numeric($IconAPI)){ //图标API检测
        msg(-1103,'图标API接口参数错误!');
    }elseif(!preg_match("/^[a-zA-Z0-9]+$/",$Register)){ 
        msg(-1103,'注册入口错误,仅允许使用字母和数字!');
    }elseif(!preg_match("/^[a-zA-Z0-9]+$/",$login)){ 
        msg(-1103,'登陆入口错误,仅允许使用字母和数字!');
    }elseif(empty($libs)){ 
        msg(-1103,'静态路径错误,不能为空并请确保地址可以被访问!');
    }elseif($Diy !== '0' && $Diy !== '1'){
        msg(-1103,'自定义代码参数错误!');
    }elseif($XSS !== '0' && $XSS !== '1'){
        msg(-1103,'防XSS脚本参数错误!');
    }elseif($SQL !== '0' && $SQL !== '1'){
        msg(-1103,'防SQL注入参数错误!');
    }elseif($apply !== '0' && $apply !== '1'){
        msg(-1103,'收录功能参数错误!');
    }elseif($offline !== '0' && $offline !== '1'){
        msg(-1103,'离线模式参数错误!');
    }elseif($Pandomain !== '0' && $Pandomain !== '1'){
        msg(-1103,'泛域名参数错误!');
    }elseif($Pandomain == '1' && !is_subscribe(true)){
        msg(-1103,'未检测到有效订阅,无法开启泛域名功能!');
    }elseif($Privacy != '0' && !is_subscribe(true)){
        msg(-1103,'未检测到有效订阅,无法开启强制私有!');
    }elseif($iconUP != '0' && !is_subscribe(true)){
        msg(-1103,'未检测到有效订阅,无法开启图标上传!');
    }elseif($Reg == '2' && !is_subscribe(true)){
        msg(-1103,'未检测到有效订阅,无法使用邀请注册!');
    }

    Writeconfigd($udb,'config','DUser',$DUser);
    Writeconfigd($udb,'config','Register',$Register);
    Writeconfigd($udb,'config','Login',$login);
    Writeconfigd($udb,'config','Libs',$libs);
    Writeconfigd($udb,'config','Reg',$Reg);
    Writeconfigd($udb,'config','Visit',$visit);
    Writeconfigd($udb,'config','IconAPI',$IconAPI);
    Writeconfigd($udb,'config','ICP',$ICP);
    Writeconfigd($udb,'config','Diy',$Diy);
    Writeconfigd($udb,'config','XSS',$XSS);
    Writeconfigd($udb,'config','SQL',$SQL);
    Writeconfigd($udb,'config','Plug',$Plug);
    Writeconfigd($udb,'config','apply',$apply);
    Writeconfigd($udb,'config','footer',base64_encode($footer));
    Writeconfigd($udb,'config','offline',$offline);
    Writeconfigd($udb,'config','Pandomain',$Pandomain);
    Writeconfigd($udb,'config','Privacy',$Privacy);
    Writeconfigd($udb,'config','iconUP',$iconUP);
    msg(0,'successful');
}

//编辑单元格(分类和链接共用)
function edit_danyuan(){
    global $db;
    $id = intval($_POST['id']);
    $field = $_POST['field'];
    $value = $_POST['value'];
    $form  = $_POST['form'];
    if(empty($id)){
        msg(-1003,'ID不能为空');
    }elseif($field ==='weight' && !is_numeric($value)){
        msg(-1111,'修改失败:权重只能为数字!');
    }elseif(($field ==='weight' || $field ==='name' || $field ==='description' || $field ==='title' ) && ($form ==='on_categorys' || $form ==='on_links')){
        $t =time();
        $re = $db->update($form,[$field => $value,'up_time' =>$t],['id' => $id]);
        $row = $re->rowCount();
        if($row){
            msgA(['code'=>0,'msg'=>'successful','t'=>$t]);
        }else{
            msg(-1111,'修改失败!');
        }
    }
    msg(-1003,'参数错误,请尝试清理浏览器缓存!若不能解决请联系管理员!');
}

//修改连接分类
function Mobile_class(){
    global $db;
    $lid = $_POST['lid'];
    $cid = $_POST['cid'];
    if($lid =='' || empty($cid)){msg(-1003,'ID不能为空');}
    $idgroup=explode(",",$lid);//分割文本
    //最好在加个检查,重新生成id组防止有错误,正常js提交的肯定是没问题得,但API操作就不好说了
    $sql= "UPDATE on_links SET fid = ".$cid." where id in(".$lid.");";
    $data =$db->query($sql);
    $row = $data->rowCount();//返回影响行数
    if($row){  
        msg(0,'successful!');
    }else{
        msg(-1111,'修改失败!');
    }
}

//提权和置顶
function edit_tiquan(){
    global $db;
    $id = $_POST['id'];
    $value = $_POST['value'];
    $form = $_POST['form'];
    if($id =='' || $value =='' || $form ==''){
        msg(-1003,'存在空的参数!');
    }elseif(($value ==='提权' || $value ==='置顶'  ) && ($form ==='on_categorys' || $form ==='on_links') ){
        $idgroup=explode(",",$id);//分割文本
        $t =time();
        $max = $db->max($form, 'weight');//取权重列最大值
        $fail=0;
        foreach($idgroup as $_id){
            $max++;
            $re = $db->update($form, ['weight'=> $max],[ 'id' => $_id]);
            $row = $re->rowCount();
            if(!$row){$fail++;}//失败计数
        }
        if(!$fail){
            msgA(['code'=>0,'msg'=>'successful','t'=>$t]);
        }else{
            msg(-1111,'有'.$fail.'条链接提权失败!');
        }
    }
    msg(-1003,'参数错误,请尝试清理浏览器缓存!若不能解决请联系管理员!');
}

//修改连接私有属性
function edit_property(){
    global $db;
    $id = intval(@$_POST['id']);
    $property = intval(@$_POST['property']);
    $form = @$_POST['form'];
    if($form !='on_categorys' && $form !='on_links'){
        msg(-1003,'表名无效!'.$form);
    }
    $count = $db->count($form,[ 'id' => $id]);//查询ID是否存在
    if( (empty($id)) || ($count == false) ) {
        msg(-1010,'.链接ID不存在！');
    }else{
        $re = $db->update($form,['property'=>$property],[ 'id' => $id]);//更新数据
        $row = $re->rowCount();//获取影响行数
        if($row) {
            msgA(['code'=>0,'msg'=>'successful','source'=>$form]);
        }else{
            msg(-1010,'链接ID不存在！');
        }
    }
}

//检测链接是否有效
function testing_link(){
    global $db,$offline;
    if ( $offline ){ msg(-5555,"离线模式无法使用此功能"); }
    $id = intval(@$_POST['id']);
    $link = $db->get('on_links',['id','url','title'],['id'=>$id]);
    $code = get_http_code($link['url']);
    msgA(['code' => 0 ,'StatusCode' => $code , 'link' => $link ]);
}

//查询用户列表
function user_list(){
    global $u,$udb,$userdb;
    is_admin();
    $q = inject_check($_POST['query']);//获取关键字(防止SQL注入)
    $page  = empty(intval($_POST['page']))  ? 1  : intval($_POST['page' ]);//页码
    $limit = empty(intval($_REQUEST['limit'])) ? 20 : intval($_REQUEST['limit']);//每页条数
    setcookie_lm_limit($limit);
    $offset = ($page - 1) * $limit;//起始行号
    $sql ="SELECT * FROM user WHERE User LIKE '%".$q."%' or Email LIKE '%".$q."%' or RegIP LIKE '%".$q."%' ORDER BY ID ASC LIMIT {$limit} OFFSET {$offset}";
    $count = $udb->count('user','*',["OR" =>['User[~]'=>$q,'Email[~]'=>$q,'RegIP[~]'=>$q]]);//统计条数
    $datas = $udb->query($sql)->fetchAll();//执行搜索
    $datas = ['code'=>0,'msg'=>'successful','count'=>$count,'data'=>$datas];//返回数组(最好在处理下,剔除不需要的数据)
    msgA($datas);
}

//删除用户
function user_list_del(){
    global $u,$udb,$userdb,$Duser;
    is_admin();
    $id = $_POST['id'];
    $idgroup=explode(",",$id);//分割文本
    $res='<table class="layui-table" lay-even><colgroup><col width="55"><col width="150"><col></colgroup><thead><tr><th>ID</th><th>账号</th><th>状态信息</th></tr></thead><tbody>';//表头
    foreach($idgroup as $tempid){
        $ud = $udb->get("user","*",["ID"=>$tempid]);//查找对应ID的数据
        $Path= './data/'.$ud['SQLite3'];//用户数据库路径
        $temp='';
        //如果为管理员账号则不允许直接删除!
        if($ud["Level"]=='999'){
            $res = $res.'<tr><td>'.$tempid.'</td><td>'.$ud["User"].'</td><td>不允许删除管理员账号,请先降级!</td></tr>';
            continue;//跳过
        }elseif($ud["User"]==$Duser){
            $res = $res.'<tr><td>'.$tempid.'</td><td>'.$ud["User"].'</td><td>不允许删除默认用户,请修改(网站管理>全局配置>默认用户)!</td></tr>';
            continue;//跳过
        }elseif($ud["User"]==''){
            $temp='账号不存在';
            $ud["User"]='账号不存在';
        }elseif(file_exists($Path)){
            if(unlink($Path)){
                $temp='库已删除!';
            }else{
                $temp='库删除失败!';
            }
        }else{
            $temp='库不存在!';
        }
        $data =$udb->delete('user',['ID'=>$tempid]);//从表删除用户!
        if($data->rowCount() !=0){
            $res=$res.'<tr><td>'.$tempid.'</td><td>'.$ud["User"].'</td><td>表已删除,'.$temp.'</td></tr>';
        }else{
            $res=$res.'<tr><td>'.$tempid.'</td><td>'.$ud["User"].'</td><td>删表失败,'.$temp.'</td></tr>';
        }
    }//遍历End
    msgA(['code'=>0,'msg'=>'successful','res'=>$res.'</tbody></table>']);
}

//管理员免密登陆用户后台
function user_list_login(){
    global $u,$udb,$userdb;
    is_admin();
    $id = $_POST['id'];
    $ud = $udb->get("user","*",["ID"=>$id]);
    if($ud["User"]==''){msg(-1111,'没有找到用户');}
    $re = is_login_o($ud["User"]);
    if($re['is']){
        msg(0,'Cookie有效');
    }else{
        $key = $re['key'];
        if($key ==''){
            msg(-1000,'错误:'.$re['msg']);
        }
        setcookie($ud["User"].'_key2', $key, 0,"/",'',false,true);
        msg(0,'--- 请勿在公共设备使用 ---<br>--- 登录有效时间1小时 ---<br>--- 关闭浏览器登陆失效 ---');
    }
}

//其他请求
function func(){
switch ($_POST['fn']) {
    case 'repair':          repair();           break;//修复
    case 'rootu':           rootu();            break;//用户管理
    case 'test':   msg(0,'测试成功.'.time());   break;//测试
    default:       msg(-1000,'方法错误!');      break;//错误的方法
}}
// 链接克隆
function link_clone() {
    global $db,$offline;
    $url = $_POST['url'];
    $token = $_POST['token'];
    $user = $_POST['user'];
    if (empty($url)){
        msg(-111,"URL不能为空!");
    }
    if ( $offline ){ msg(-5555,"获取失败 -5555"); } //离线模式
    // 获取分类列表
    $url = $url."/index.php?c=api&method=category_list".(!empty($user)? "&u=".$user :"")."&limit=9999";
    $curl  =  curl_init ( $url ) ; //初始化
    curl_setopt ( $curl , CURLOPT_POST ,  1 ) ;
    curl_setopt ( $curl , CURLOPT_TIMEOUT, 10 ); //超时
    curl_setopt($curl, CURLOPT_FAILONERROR, true);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt ( $curl , CURLOPT_POSTFIELDS ,  array ("token"  =>  $token ,'page' => 1 ,'limit' => 9999 ) ) ; //设置POST内容
    $Res = curl_exec   ( $curl ) ;//执行
    curl_close  ( $curl ) ;//关闭URL请求


    $data = json_decode($Res, true);
    if($data["data"]){ 
        $count = count($data["data"]);
    }else{
        $count = 0;
    }

    if( $count === 0 ) { 
        if($data["code"] == -1000){
            msg(-1111,"他站是Extend版,需要提供正确的Token或者对方允许游客访问API(即兼容模式2)"); 
        }else if($data["code"] == -1002){
            msg(-1112,"他站返回鉴权失败,请提供正确的Token或者留空读取共有数据"); 
        }else if($data["code"] == -2000){
            msg(-1113,"他站似乎没有设置Token,请核对后再试"); 
        }else{
            msg(-1114,"获取分类列表失败");
        }
    }
    $cid =  ($db -> count('on_categorys') ) > 0 ? false : true ; //后续用于如果空表则克隆id
    for($i=0; $i<$count; $i++){
        $categorys_name = strip_tags(htmlspecialchars_decode(trim($data["data"][$i]["name"]),ENT_QUOTES));
        $categorys_id = $db-> get('on_categorys', 'id', ['name' => $categorys_name]);
        
        // 父id处理
        if(intval($data["data"][$i]["fid"]) != 0){ //父id不是0,说明是二级分类!尝试查找它的父!
            $categorys_fid =  $db-> get('on_categorys', 'id', ['name' => $data["data"][$i]["fname"]]);
        }else{
            $categorys_fid = 0 ;
        }
        
        //图标处理
        if( !empty($data["data"][$i]["font_icon"]) ){
            $Icon = trim( str_replace("fa ","",$data["data"][$i]["font_icon"]) );
        }else if(!empty($data["data"][$i]["Icon"])){
            $Icon = $data["data"][$i]["Icon"];
        }else if(preg_match('/<i class="fa (.+)"><\/i>/i',htmlspecialchars_decode(trim($data["data"][$i]["name"])),$matches) != 0) {
            $Icon = trim( $matches[1] );
        }else{
            $Icon = '';
        }
        
        //不存在时创建
        if( empty($categorys_id) ){ 
             $categorys = [
                'name'          =>  $categorys_name,
                'add_time'      =>  intval($data["data"][$i]["add_time"]),
                'up_time'       =>  intval($data["data"][$i]["up_time"]) == 0 ? null : intval($data["data"][$i]["up_time"]),
                'weight'        =>  intval($data["data"][$i]["weight"]),
                'property'      =>  intval($data["data"][$i]["property"]),
                'description'   =>  htmlspecialchars(trim($data["data"][$i]["description"]),ENT_QUOTES),
                'fid'           =>  intval($categorys_fid),
                'Icon'          =>  $Icon,
                'id'            =>  intval($data["data"][$i]["id"]) 
            ];
            if( !$cid ) { array_pop($categorys);}
            $db->insert("on_categorys",$categorys);
            $categorys_id = $db->id(); //返回ID
            if( empty($categorys_id) ){
                msg(-1000,'创建分类失败,意外结束..');
            }
        }
        $categoryt[$categorys_name] = $categorys_id;
    }
    
    // 获取链接
    $url = $url."/index.php?c=api&method=link_list".(!empty($user)? "&u=".$user :"")."&limit=9999";
    $curl  =  curl_init ( $url ) ; //初始化
    curl_setopt ( $curl , CURLOPT_POST ,  1 ) ;
    curl_setopt ( $curl , CURLOPT_TIMEOUT, 8 ); //超时
    curl_setopt($curl, CURLOPT_FAILONERROR, true);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt ( $curl , CURLOPT_POSTFIELDS ,  array ("token"  =>  $token ,'page' => 1 ,'limit' => 9999 ) ) ; //设置POST内容
    $Res = curl_exec   ( $curl ) ;//执行
    curl_close  ( $curl ) ;//关闭URL请求
    $data = json_decode($Res, true);
    if($data["data"]){ 
        $count = count($data["data"]);
    }else{
        $count = 0;
    }

    if( $count === 0 ) { msg(-1111,"获取链接列表失败.."); }
    $cid =  ($db -> count('on_links') ) > 0 ? false : true ; //后续用于如果空表则克隆id
    $pattern = "/^(http:\/\/|https:\/\/|ftp:\/\/|ftps:\/\/|magnet:?|ed2k:\/\/|tcp:\/\/|udp:\/\/|thunder:\/\/|rtsp:\/\/|rtmp:\/\/|sftp:\/\/).+/";
    for($i=0; $i<$count; $i++){
        //检查代码,标题不能为空,url地址通过正则判断是否合规!
        if( empty($data["data"][$i]['title'])  || !preg_match($pattern,$data["data"][$i]['url'])){
            $info["fail"]++;
            continue; 
        }
        //$categorys_name = htmlspecialchars(trim($data["data"][$i]["category_name"]),ENT_QUOTES);
        $categorys_name = strip_tags(htmlspecialchars_decode(trim($data["data"][$i]["category_name"]),ENT_QUOTES));
        
        if( intval($categoryt[$categorys_name]) == 0 ){ //分类名空时跳过!
            $info["fail"]++;
            continue; 
        }
        
        $link_data = [
                    'fid'           =>  intval($categoryt[$categorys_name]),
                    'title'         =>  htmlspecialchars($data["data"][$i]['title']),
                    'description'   =>  htmlspecialchars($data["data"][$i]['description']),
                    'url'           =>  htmlspecialchars($data["data"][$i]['url']),
                    'url_standby'   =>  htmlspecialchars($data["data"][$i]['url_standby']),
                    'iconurl'       =>  $data["data"][$i]['iconurl'],
                    'add_time'      =>  intval($data["data"][$i]['add_time']),
                    'up_time'       =>  intval($data["data"][$i]["up_time"]) == 0 ? null : intval($data["data"][$i]["up_time"]),
                    'click'         =>  intval($data["data"][$i]['click']),
                    'weight'        =>  intval($data["data"][$i]['weight']),
                    'property'      =>  intval($data["data"][$i]['property']),
                    'id'            =>  intval($data["data"][$i]["id"]) 
                    
        ];
        if( !$cid ) { array_pop($link_data);}
        //插入数据库
        try{
            $re = $db->insert('on_links',$link_data);
            $id = $db->id();
        }catch(Exception $e){
            $id = 0;
        }
        if( empty($id) ){ 
            $info["fail"]++; //失败
        }else{
            $info["success"]++; //成功
        }
    }
    $info["fail"] = intval($info["fail"]);
    $info["success"] = intval($info["success"]);
    
    msg(0,"处理完毕,总数:{$count},成功:{$info['success']},失败:{$info['fail']}");
}


function get_sql_update_list() {
    global $db;
        //待更新的数据库文件目录
        $sql_dir = 'initial/sql/';
        //待更新的sql文件列表，默认为空
        $sql_files_all = [];
        //打开一个目录，读取里面的文件列表
        if (is_dir($sql_dir)){
            if ($dh = opendir($sql_dir)){
                while (($file = readdir($dh)) !== false){
                //排除.和..
                if ( ($file != ".") && ($file != "..") ) {
                    array_push($sql_files_all,$file);

                }
            }
                //关闭句柄
                closedir($dh);
            }
        }
        //判断数据库日志表是否存在
        $sql = "SELECT count(*) AS num FROM sqlite_master WHERE type='table' AND name='on_db_logs'";
        //查询结果
        $q_result = $db->query($sql)->fetchAll();
        //如果数量为0，则说明on_db_logs这个表不存在，需要提前导入
        $num = intval($q_result[0]['num']);
        if ( $num === 0 ) {
            $data = [
                "code"      =>  0,
                "data"      =>  ['on_db_logs.sql']
            ];
            msgA($data);
        }else{
            //如果不为0，则需要查询数据库更新表里面的数据进行差集比对
            $get_on_db_logs = $db->select("on_db_logs",[
                "sql_name"
            ],[
                "status"    =>  "TRUE"
            ]);
            //声明一个空数组，存储已更新的数据库列表
            $already_dbs = [];
            foreach ($get_on_db_logs as $value) {
                array_push($already_dbs,$value['sql_name']);
            }
            
            //array_diff() 函数返回两个数组的差集数组
            $diff_result = array_diff($sql_files_all,$already_dbs);
            //去掉键
            $diff_result = array_values($diff_result);
            sort($diff_result);
            
            $data = [
                "code"      =>  0,
                "data"      =>  $diff_result,
                "num"       =>  $num
            ];
            msgA($data);
        }

    }
function exe_sql() {
    global $SQLite3;
        //数据库sql目录
        $sql_dir = 'initial/sql/';
        $name = $_GET['name'];
        $sql_name = $sql_dir.$name;
        //如果文件不存在，直接返回错误
        if ( !file_exists($sql_name) ) {
            msg(-2000,$name.'不存在!');
        }
        //读取需要更新的SQL内容
        try {
            $sql_content = file_get_contents($sql_name);
            $time = time();
            $sql_content = $sql_content . "\nINSERT INTO \"main\".\"on_db_logs\"(\"sql_name\", \"update_time\") VALUES ('${name}', ${time})";
            class MyDB extends SQLite3 {
                function __construct() {
                    global $SQLite3;
                    $this->open($SQLite3);
                }
            }
            $db2 = new MyDB();
            if(!$db2){
                msgA(["code" => -2000,"data" => "打开数据库失败！",'error' => $db2->lastErrorMsg()]);
            }elseif($sql_content ==''){
                msg(-2000,$name." 文件内容为空,更新失败！");
            }
            //执行更新
            $result = $db2->exec($sql_content);
            //如果SQL执行成功，则返回
            if( $result ) {
                msg(0,$name." 更新完成！");
            }else{
                //如果执行失败
                msgA(["code" => -2000 , "data" => " 更新失败,error:\n".$db2->lastErrorMsg()]);
            }
            $db2->close();
        } catch(Exception $e){
            msg(-2000,$e->getMessage());
        }
    }
function repair(){require ('./class/Repair.php');}//修复
function rootu(){
    global $u,$udb,$userdb;
    is_admin();
    $id = $_POST['id'];
    $Set = $_POST['Set'];
    $ud = $udb->get("user","*",["ID"=>$id]);
    if($ud["User"]==''){msg(-1111,'没有找到用户');}
    
    switch ($Set) {
    //设置权限
    case 'Level': 
        $Level = $_POST['Level'];
        if($Level !='0' && $Level !='999' ){
            msg(-1111,'权限设置错误,请核查后再试!');
        }elseif($ud["ID"] == $userdb['ID']){
            msgA(['code'=>-1111,'msg'=>'您不能对自己操作!','icon'=>5,'Level'=>$Level]);
        }elseif($ud["Level"] === $Level){
            msgA(['code'=>0,'msg'=>'用户已经是'.($Level=='999'?'管理员':'普通会员!'),'icon'=>1,'Level'=>$Level]);
        }
        $Re = $udb->update('user',['Level'=> $Level ],['ID' => $ud["ID"]]);
        if($Re->rowCount() == 1){
            msgA(['code'=>0,'msg'=>'修改成功','icon'=>1,'Level'=>$Level]);
        }else{
            msgA(['code'=>-1111,'msg'=>'修改失败','icon'=>5,'Level'=>$Level]);
        }
    break;//设置权限
    
    //修改密码
    case 'Pass': 
        $Pass = $_POST['Pass'];
        if(strlen($Pass)!=32){
            msg(-1103,'密码异常,正常情况是32位的md5！');
        }elseif($ud["ID"] == $userdb['ID']){
            msgA(['code'=>-1111,'msg'=>'您不能对自己操作!','icon'=>5]);
        }
        $PassMD5 = md5($Pass.$ud["RegTime"]);
        $Re = $udb->update('user',['Pass'=>$PassMD5],['ID' => $ud["ID"]]);
        if($Re->rowCount() == 1){
            $SQLite3 = dirname(dirname(__FILE__)).'/data/'.$ud["SQLite3"];
            $db = new Medoo\Medoo(['database_type' => 'sqlite','database_file' => $SQLite3]);
            Writeconfigd($db,'on_config','Pass',$PassMD5);
            msgA(['code'=>0,'msg'=>'修改成功','icon'=>1]);
        }else{
            msgA(['code'=>-1111,'msg'=>'修改失败','icon'=>5]);
        }
    break;//修改密码
    
    //修改用户名
    case 'SetName':
        //检查账号和否满足条件
        $user = $_POST['NewName'];
        $dir = dirname(dirname(__FILE__));
        $dbPath = $dir.'/data/'.$user.'.db3';//新数据库路径
        if(!preg_match('/^[A-Za-z0-9]{4,13}$/', $user)){
            msgA(['code'=>-1111,'msg'=>'账号只能是4到13位的数字和字母!','icon'=>5]);
        }elseif($udb->count("user",["User"=>$user]) != 0 ){
            msgA(['code'=>-1111,'msg'=>'账号已存在!','icon'=>5]);
        }elseif(file_exists($dbPath)){
            msgA(['code'=>-1111,'msg'=>'数据库:'.$user.'.db3 已存在!','icon'=>5]);
        }elseif($ud["ID"] == $userdb['ID']){
            msgA(['code'=>-1111,'msg'=>'您不能对自己操作!','icon'=>5]);
        }
        $SQLite3 = $dir.'/data/'.$ud["SQLite3"];//老数据库路径
        if(!rename($SQLite3,$dbPath)){
            msgA(['code'=>-1111,'msg'=>'重命名数据库文件失败!','icon'=>5]);
        }
        
        $Re = $udb->update('user',['User'=>$user],['ID' => $id]);
        if($Re->rowCount() == 1){
            $DUser = $udb->get("config","Value",["Name"=>'DUser']);
            //如果修改的账号是默认用户的话就一起修改!
            if($DUser == $ud["User"]){
                Writeconfigd($udb,'config','DUser',$user);
                $du = '1';
            }
            $db = new Medoo\Medoo(['database_type' => 'sqlite','database_file' => $dbPath]);
            Writeconfigd($db,'on_config','User',$user);
            Writeconfigd($db,'on_config','SQLite3',$user.'.db3');
            $udb->update('user',['SQLite3'=>$user.'.db3'],['ID' => $id]);
            msgA(['code'=>0,'msg'=>'修改成功!','icon'=>1,'du'=>$du]);
        }else{
            msgA(['code'=>-1111,'msg'=>'修改失败','icon'=>5]);
        }
    break;//修改用户名
    default:       msg(-1000,'Set参数错误!');      break;//错误的方法 Writeconfigd($udb,'config','DUser',$DUser);
}
    
    
}
//日志列表
function loginlog_list(){
    $db = new Medoo\Medoo(['database_type'=>'sqlite','database_file'=>'./data/login.log.db3']);
    $q = inject_check($_POST['query']);//获取关键字(防止SQL注入)
    $page = empty(intval($_REQUEST['page'])) ? 1 : intval($_REQUEST['page']);
    $limit = empty(intval($_REQUEST['limit'])) ? 20 : intval($_REQUEST['limit']);
    setcookie_lm_limit($limit);
    $offset = ($page - 1) * $limit; //起始行号
    $count = $db->count('loginlog','*',["OR"=>['name[~]'=>$q,'pass[~]'=>$q,'ip[~]'=>$q,'value[~]'=>$q]]);
    $sql = "SELECT * FROM loginlog WHERE (name LIKE '%{$q}%' or pass LIKE '%{$q}%' or ip LIKE '%{$q}%' or value LIKE '%{$q}%') LIMIT {$limit} OFFSET {$offset}";
    $datas = $db->query($sql)->fetchAll();
    msgA(['code'=>0,'msg'=>'','count'=>$count,'data'=>$datas]);
}

// 一键检测/一键诊断
function Onecheck(){
    global $SQLite3,$version; //,$db,$udb
    //获取组件信息
    $log = $log ."服务器时间：" . date("Y-m-d H:i:s") ."\n"; 
    //$log = $log ."系统内核:" . php_uname() ."\n";
    $log = $log ."系统信息：" . php_uname('s').','.php_uname('r') ."\n";
    $log = $log . "当前版本：{$version}\n";
    $ext = get_loaded_extensions();
    //检查PHP版本，需要大于5.6小于8.0
    $php_version = floatval(PHP_VERSION);
    $log = $log . "PHP版本：{$php_version}\n";
    $log = $log . "Web版本：{$_SERVER['SERVER_SOFTWARE']}\n";
    
    if( ( $php_version < 5.6 ) || ( $php_version > 8.1 ) ) {
        $log = $log . "PHP版本：不满足要求,需要5.6 <= PHP <= 8.1,建议使用7.4 )\n";
    }
    
    //检查是否支持pdo_sqlite
    if ( array_search('pdo_sqlite',$ext) ) {
        $log = $log ."PDO_Sqlite：支持\n";
    }else{
        $log = $log ."PDO_Sqlite：不支持 (请安装PDO_Sqlite)\n";
    }
    
    //检查是否支持curl
    if ( function_exists('curl_init') ) {
        $log = $log ."curl：支持\n";
    }else{
        $log = $log ."curl：不支持 (在线功能将受到影响)\n";
    }
    // //检查是否支持iconv
    // if ( function_exists('iconv') ) {
    //     $log = $log ."iconv：支持\n";
    // }else{
    //     $log = $log ."iconv：不支持 (链接识别将受到影响)\n";
    // }
    //检查是否已安装mbstring扩展
    if ( extension_loaded('mbstring') ) {
        $log = $log ."mbstring：支持\n";
    }else{
        $log = $log ."mbstring：不支持 (链接识别将受到影响)\n";
    }
    // 检查主表
    if(is_writable('./data/lm.user.db3')){
        $log = $log ."数据库>主表：正常\n";
    }else{
        $log = $log ."数据库>主表：只读,请将./data/lm.user.db3的权限设为755 \n";
    }
    // 检查用户数据库
    if(is_writable($SQLite3)){
        $log = $log ."数据库>用户：正常\n";
    }else{
        $log = $log ."数据库>用户：只读,请将".$SQLite3."的权限设为755 \n";
    }
    // 检查登录日志数据库
    if(!file_exists('./data/login.log.db3')){
        $log = $log ."数据库>日志：不存在 (退出登录,重新登录可自动生成)\n";
    }elseif(is_writable('./data/login.log.db3')){
        $log = $log ."数据库>日志：正常\n";
    }else{
        $log = $log ."数据库>日志：只读,请将./data/login.log.db3的权限设为755 \n";
    }
    
    $a = './data/test_'.time().'.txt';
    if(file_put_contents($a, '测试文本,可以删除!由一键诊断生成!')){
        if(unlink($a)){
            $log = $log ."data目录：正常\n";
        }else{
            $log = $log ."data目录：创建文件成功,删除文件失败\n";
        }
    }else{
        $log = $log ."data目录：异常,请检查权限!\n";
    }
    
    $a = './data/upload/test_'.time().'.txt';
    if(file_put_contents($a, '测试文本,可以删除!由一键诊断生成!')){
        if(unlink($a)){
            $log = $log ."upload目录：正常\n";
        }else{
            $log = $log ."upload目录：创建文件成功,删除文件失败\n";
        }
    }else{
        $log = $log ."upload目录：异常,请检查权限!(可尝试在网站管理>用户管理>点击修复)\n";
    }
    
     msg(-1000,$log);
}

//获取onenav最新版本号
function get_latest_version() {
    global $udb,$version,$offline;
    if ( $offline ){ msgA(["code"=> 200,"msg"=>  "offline","data"=>  $version]); } //离线模式
    
    $NewVerGetTime = $udb->get("config","Value",["Name"=>'NewVerGetTime']); //上次从Git获取版本号的时间
    //如果距上次获取时间超过30分钟则重新获取!
    if($_GET['cache'] === 'no' || time() - intval( $NewVerGetTime ) > 1800 ) {
                        
        if(preg_match('/^v.+-(\d{8})$/i',$version,$matches)){
            $sysver = intval( $matches[1] );
        }else{
            msgA(["code" => 200,"msg" => "获取程序版本异常","data" => "null"]);
        }
        
        //加载远程数据
        $urls = [ "https://update.lm21.top/OneNav/updata.json","https://gitee.com/tznb/OneNav/raw/data/updata.json"];
        foreach($urls as $url){ 
            $Res = ccurl($url,3);
            $data = json_decode($Res["content"], true);
            if($data["code"] == 200 ){ //如果获取成功
                break; //跳出循环.
            } 
        }

        if($data["code"] != '200'){
            msgA(["code" => 200,"msg" => "状态码错误","data" => "null"]);
        }else{
            //遍历查找合适的版本
            foreach($data["data"] as $key){
                if( $sysver >= $key["low"]  && $sysver <= $key["high"] &&  $key["update"] > $sysver){
                    $NewVer = "v{$key['version']}-{$key['update']}";
                    break;
                }
            }
        }
        //如果获取成功则写入数据库!
        if(preg_match('/^v.+-(\d{8})$/i',$NewVer,$matches)){
            $NewVerGetTime = time();
            Writeconfigd($udb,'config','NewVer',$NewVer);
            Writeconfigd($udb,'config','NewVerGetTime',$NewVerGetTime);
            $on_line = true;
        }else{
            $NewVer = $version;
        }
    }else{
        $NewVer = $NewVer = $udb->get("config","Value",["Name"=>'NewVer']);
    }

    msgA(["code" => 200,"msg" => ( $on_line ? 'on-line' : 'cache' ),"data" => $NewVer]);
}
//一键更新/系统升级
function System_Upgrade() {
    global $udb,$version,$offline;
    is_admin();
    if ( $offline ){ msg(-5555,"离线模式禁止下载更新!"); } //离线模式
    
    if(preg_match('/^v.+-(\d{8})$/i',$version,$matches)){
        $sysver = intval( $matches[1] );
    }else{
        msg(-1110,"获取程序版本异常");
    }
    
    //检查指定文件夹是否可写
    $paths = ["./"
              ,"./class","./controller","./data","./data/upload","./favicon"
              ,"./initial","./initial/sql","./static","./templates","./templates/admin"
              ,"./templates/admin/static","./templates/default"
             ];
    foreach($paths as $path){
        if(!is_writable($path)){
            msg(-1112,"文件夹不可写 >> $path");
        }
    }
    //设置执行时长,防止数据较多时超时!
    set_time_limit(5*60);//设置执行最长时间，0为无限制。单位秒!
    ignore_user_abort(true);//关闭浏览器，服务器执行不中断。
    //加载远程数据
    $urls = [ "https://update.lm21.top/OneNav/updata.json","https://gitee.com/tznb/OneNav/raw/data/updata.json"];
    foreach($urls as $url){ 
        $Res = ccurl($url,3);
        $data = json_decode($Res["content"], true);
        if($data["code"] == 200 ){ //如果获取成功
            break; //跳出循环.
        } 
    }
    
    if($data["code"] != '200'){
        msg(-1111,'获取更新信息失败,请稍后再试..');
    }else{
        foreach($data["data"] as $key){
            if( $sysver >= $key["low"]  && $sysver <= $key["high"] &&  $key["update"] > $sysver){
                $file = "System_Upgrade.tar.gz";
                $filePath = "./data/upload/{$file}";
                break; //找到跳出
            }
        }
        if(empty($file)){
            msg(-1112,'暂无可用更新,请稍后再试..');
        }
    }
    

    
    require ('./class/downFile.php');//载入下载函数
    unlink($filePath);
    for($i=1; $i<=2; $i++){
        if(!empty($key['url'.$i])){ //URL不为空
            if(downFile( $key['url'.$i] , $file , './data/upload/')){
                $file_md5 = md5_file($filePath);
                if($file_md5 === $key['md5']){
                    break;//下载成功,跳出循环!
                }else{
                    unlink($filePath);
                }
            }
        }
    }

    if(empty($file_md5) ){
        msg(-1111,'下载更新包失败,请重试');
    }elseif($file_md5 != $key['md5']){
        msgA(['code'=>-1112,'msg'=> '效验失败,请重试','key_md5'=> $key['md5'],'file_md5'=>$file_md5]);
    }

    try {
        $phar = new PharData($filePath);
        $phar->extractTo('./', null, true); //路径 要解压的文件 是否覆盖
        unlink($filePath);//删除文件
        if( file_exists("opcache_reset") ) { opcache_reset(); } //清理PHP缓存
    } catch (Exception $e) {
        msg(-1114,'更新失败,请检查写入权限');//解压出问题了
    } finally{
        msgA(['code'=>0,'msg'=> '更新成功!','url'=> $i,'file_md5'=>$file_md5]);
    }
}
//申请收录相关
//列表
function apply_list(){
    global $u,$db,$userdb,$udb;
    if($udb->get("config","Value",["Name"=>'apply']) != 1){msg(-1,'管理员禁止了此功能!');}
    $page  = empty(intval($_POST['page']))  ? 1  : intval($_POST['page' ]);//页码
    $limit = empty(intval($_REQUEST['limit'])) ? 20 : intval($_REQUEST['limit']);//每页条数
    setcookie_lm_limit($limit);
    $offset = ($page - 1) * $limit;//起始行号
    $sql ="SELECT * FROM \"lm_apply\" LIMIT {$limit} OFFSET {$offset}";
    $count = $db->count('lm_apply');//统计条数
    $datas = $db->query($sql)->fetchAll();//执行搜索
    $datas = ['code'=>0,'msg'=>'successful','count'=>$count,'data'=>$datas];//返回数组(最好在处理下,剔除不需要的数据)
    msgA($datas);
}
//保存配置
function apply_save(){
    global $u,$db,$userdb,$udb;
    if($udb->get("config","Value",["Name"=>'apply']) != 1){msg(-1,'管理员禁止了此功能!');}
    
    $apply   = intval($_POST['apply']);   // 功能选项0.关闭 1.需要审核  2.无需审核
    $Notice  = $_POST['Notice'];  // 公告
    if($apply < 0 || $apply > 2 ){ 
        msg(-1102,'参数错误!');
    }elseif(strlen($Notice) > 512){
        msg(-1102,'公告长度超限!');
    }

    Writeconfig('apply_switch',$apply);
    Writeconfig('apply_Notice',$Notice);
    msg(0,'保存成功');
}
function apply_fn(){
    global $u,$db,$userdb,$udb;
    if($udb->get("config","Value",["Name"=>'apply']) != 1){msg(-1,'管理员禁止了此功能!');}
    $fn   = intval($_GET['fn']);
    $id = intval($_POST['id']);
    if($fn === 1){  //编辑
        $category_id = intval($_POST['edit_category']); 
        $title = $_POST['title'];
        $url = $_POST['url'];
        $iconurl = $_POST['iconurl'];
        $description = $_POST['description'];
        $category_name = $db->get("on_categorys","name",["id"=> $category_id ]);
        $data = [
            'category_id'   =>  $category_id,
            'category_name' =>  $category_name,
            'title'         =>  htmlspecialchars($title,ENT_QUOTES),
            'url'           =>  $url,
            'description'   =>  htmlspecialchars($description,ENT_QUOTES),
            'iconurl'       =>  $iconurl
            ];
        $re = $db->update('lm_apply',$data,[ 'id' => $id]); //更新数据
        $row = $re->rowCount();//返回影响行数
        if($row){
            msg(0,'修改成功');
        }else{
            msg(-1011,'URL已经存在！');
        }
    }elseif($fn === 2){ //通过
        $id = intval($_POST['id']); 
        $link =  $db->get("lm_apply","*",["id"=> $id ]);
        if(empty($id)){
            msg(-1111,'id错误');
        }elseif(empty($link['category_id'])){
            msg(-1111,'分类id错误');
        }elseif(empty($link['title'])){
            msg(-1111,'标题不能为空');
        }elseif(empty($link['url'])){
            msg(-1111,'链接不能为空');
        }elseif($link['state'] != 0){
            msg(-1111,'此申请信息不是待审核状态!');
        }
        check_link($link['category_id'],$link['title'],$link['url'],''); //检测链接是否合法
        $data = [
            'fid'           =>  $link['category_id'],
            'title'         =>  htmlspecialchars($link['title'],ENT_QUOTES),
            'url'           =>  $link['url'],
            'description'   =>  htmlspecialchars($description,ENT_QUOTES),
            'add_time'      =>  time(),
            'iconurl'       =>  $link['iconurl']
            ];
        try{
            $_id = $db->get('on_links','id',[ "url" => $link['url'] ] ); 
            if( !empty( $_id ) ){ msg(-1000,'URL已经存在,ID:'.$_id);} 
            $re = $db->insert('on_links',$data);//插入数据库
        }catch(Exception $e){
            msg(-1000,'添加链接失败！');
        }
    
        $row = $re->rowCount();//返回影响行数
        if($row){
            $db->update('lm_apply',["state" => 1 ],[ 'id' => $id]);
            msg(0,'已通过申请');
        }else{
            msg(-1011,'URL已经存在！');
        }
    }elseif($fn === 3){ //拒绝
        $id = intval($_POST['id']); 
        if(empty($id)){
            msg(-1111,'id错误');
        }
        $re = $db->update('lm_apply',["state" => 2 ],[ 'id' => $id]);
        $row = $re->rowCount();//返回影响行数
        if($row){
            msg(0,'已拒绝');
        }else{
            msg(-1011,'操作失败');
        }
    }elseif($fn === 4){ //删除
        $id = intval($_POST['id']); 
        if(empty($id)){
            msg(-1111,'id错误');
        }
        $re = $db->delete('lm_apply',[ 'id' => $id]);
        $row = $re->rowCount();//返回影响行数
        if($row){
            msg(0,'已删除');
        }else{
            msg(-1011,'删除失败');
        }
    }elseif($fn === 40){ //清空
        $db->query("delete from lm_apply")->fetchAll();
        $db->query("UPDATE sqlite_sequence SET seq = 0 WHERE name='lm_apply';")->fetchAll();
        msg(0,'删除中,请稍后..');
    }
    msg(0,"fn:{$fn} 码不支持!");
}
// 收录结束


//数据清空
function data_empty(){
    global $SQLite3,$userdb,$db,$RegTime,$password;
    $pass = $_GET['pass'];
    if(md5(md5($pass).$RegTime) !== $password && md5($pass.$RegTime) !== $password ){
        msg(-1111,"密码错误,请核对后再试!");
    }
    
    $db->query("delete from on_links")->fetchAll();
    $db->query("UPDATE sqlite_sequence SET seq = 0 WHERE name='on_links';")->fetchAll();
    $db->query("delete from on_categorys")->fetchAll();
    $db->query("UPDATE sqlite_sequence SET seq = 0 WHERE name='on_categorys';")->fetchAll();
    $db->query("delete from lm_tag")->fetchAll();
    $db->query("UPDATE sqlite_sequence SET seq = 0 WHERE name='lm_tag';")->fetchAll();
    $count_categorys = $db->Count("on_categorys");
    $count_links = $db->Count("on_links");
    $count_tag = $db->Count("lm_tag");
    if($count_categorys === 0 && $count_links === 0 && $count_tag === 0){
        delFileUnderDir("data/user/{$userdb['User']}/favicon"); //清空图标目录下的文件
        msg(0,"清空成功");
    }else{
        msg(-1111,"清空失败");
    }
    
}
//导出请求
function export_db3(){
    global $SQLite3,$userdb,$db,$RegTime,$password;
    $pass = $_GET['pass'];
    if(md5(md5($pass).$RegTime) !== $password && md5($pass.$RegTime) !== $password ){
        exit('密码错误,请核对后再试！');
    }
    header("Cache-Control: public");
    header("Content-Description: File Transfer");
    header('Content-disposition: attachment; filename='.basename($userdb["SQLite3"])); //文件名
    header("Content-Type: application/db3"); 
    header("Content-Transfer-Encoding: binary"); //告诉浏览器，这是二进制文件
    header('Content-Length: '. filesize($SQLite3)); //告诉浏览器，文件大小
    readfile($SQLite3);
}
//导出请求
function export_html(){
    global $SQLite3,$userdb,$db,$RegTime,$password;
    $pass = $_GET['pass'];
    if(md5(md5($pass).$RegTime) !== $password && md5($pass.$RegTime) !== $password ){
        exit('密码错误,请核对后再试！');
    }
    header("Cache-Control: public");
    header("Content-Description: File Transfer");
    header('Content-disposition: attachment; filename=OneNavExtend_bookmarks_'.date("Ymd_His").'.html'); //文件名
    header("Content-Type: application/text"); 
    header("Content-Transfer-Encoding: binary"); 
    echo( base64_decode("PCFET0NUWVBFIE5FVFNDQVBFLUJvb2ttYXJrLWZpbGUtMT4NCjwhLS0gVGhpcyBpcyBhbiBhdXRvbWF0aWNhbGx5IGdlbmVyYXRlZCBmaWxlLg0KICAgICBJdCB3aWxsIGJlIHJlYWQgYW5kIG92ZXJ3cml0dGVuLg0KICAgICBETyBOT1QgRURJVCEgLS0+DQo8TUVUQSBIVFRQLUVRVUlWPSJDb250ZW50LVR5cGUiIENPTlRFTlQ9InRleHQvaHRtbDsgY2hhcnNldD1VVEYtOCI+DQo8VElUTEU+T25lTmF2IEV4dGVuZCBCb29rbWFya3M8L1RJVExFPg0KPEgxPk9uZU5hdiBFeHRlbmQgQm9va21hcmtzPC9IMT4NCjxETD48cD4NCg=="));
    echo ('    <DT><H3 ADD_DATE="1643738522" LAST_MODIFIED="1643738522" PERSONAL_TOOLBAR_FOLDER="true">书签栏</H3>'."\n");
    echo ("    <DL><p>\n");
    $categorys = [];
    //获取父分类
    $category_parent = $db->select('on_categorys','*',["fid"   =>  0,"ORDER" =>  ["weight" => "DESC"]]);
    foreach ($category_parent as $category) {
        echo '            <DT><H3 ADD_DATE="'.$category['add_time'].'" LAST_MODIFIED="'.$category['add_time'].'">'.$category['name']."</H3>\n";
        echo "            <DL><p>\n";
        //二级分类
        $category_subs = $db->select('on_categorys','*',["fid" => $category['id'],"ORDER" => ["weight" => "DESC"] ]);
        foreach ($category_subs as $category_sub) {
            echo '              <DT><H3 ADD_DATE="'.$category['add_time'].'" LAST_MODIFIED="'.$category['add_time'].'">'.$category_sub['name']."</H3>\n";
            echo "              <DL><p>\n";
            $links = $db->select('on_links','*',["fid"   =>  $category_sub['id'],"ORDER" =>  ["weight" => "DESC"]]);
            foreach ($links as $link) {
                echo '                  <DT><A HREF="'.$link["url"].'" ADD_DATE="'.$link["add_time"].'">'.$link["title"].'</A>'."\n";
            }
            echo "              </DL><p>\n";
        }//二级分类End
        
        $links = $db->select('on_links','*',["fid"   =>  $category['id'],"ORDER" =>  ["weight" => "DESC"]]);
        foreach ($links as $link) {
            echo '                <DT><A HREF="'.$link["url"].'" ADD_DATE="'.$link["add_time"].'">'.$link["title"].'</A>'."\n";
        }
        echo "            </DL><p>\n";
    }
    echo "    </DL><p>";
    echo base64_decode("DQo8L0RMPjxwPg0K");
    exit;
}
//是否为管理员
function is_admin(){
    global $userdb;
    if( $userdb['Level'] != '999'){ msg(-1111,'您没有权限使用此功能');}
}
//其他函数

//用户状态
function check_login(){
    $status = is_login2() ? "true" : "false";
    msgA(["code" => 200 ,"data" => $status ,"msg" => ""]);
}

//验证其他账户是否登陆
function is_login_o($username){
    global $udb;
    $time = time();
    $newExpire = $time+1 * 60 * 60;
    $userdb   = $udb->get("user","*",["User"=>$username]);
    $password = $userdb['Pass'];//密码
    $SQLite3  = './data/'.$userdb['SQLite3'];//数据库路径
    if(!isset($userdb['ID'])){
        return ['is'=>false,'msg'=>'未找到账号数据'];//未找到账号数据
    }elseif(!file_exists($SQLite3)){
        return ['is'=>false,'msg'=>'SQLite3数据库文件不存在'];//文件不存在
    }
    $db = new Medoo\Medoo(['database_type'=>'sqlite','database_file'=>$SQLite3]);
    $Skey =  $db->get("on_config","value",["name"=>'Skey']);//Key算法
    $Ckey= $_COOKIE[$username.'_key2'];//读取Cookie
    if($Skey ==''){
        Writeconfigd($db,'on_config','Skey','1');
        return ['is'=>false,'msg'=>'写入Skey配置中,请重新尝试!'.$Skey];//Skey未配置!
    }elseif($Ckey ==''){
        return ['is'=>false,'key'=>Getkey2($username,$password,$newExpire,$Skey,$time).'.'.$newExpire.'.'.$time,'msg'=>'没找到Cookie'];//没找到Cookie
    }
    preg_match('/(.{32})\.(\d+)\.(\d+)/i',$Ckey,$matches);//匹配Key.
    $Ckey = $matches[1]; //Key
    $Expire = $matches[2]; //到期时间 
    $Atime = $matches[3]; //创建时间 
    $keyOK = Getkey2($username,$password,$Expire,$Skey,$Atime);

    //如果已经成功登录
    if($keyOK === $Ckey ) {
        //Key验证成功,验证到期时间,如果为0说明会话级,直接返回真
        if($Expire !='0'){
            if($Expire>time()){
                return ['is'=>true];
            }else{
                return ['is'=>false,'key'=>Getkey2($username,$password,$newExpire,$Skey,$time).'.'.$newExpire.'.'.$time];
            }
        }
        return ['is'=>true];
    }else{
        return ['is'=>false,'key'=>Getkey2($username,$password,$newExpire,$Skey,$time).'.'.$newExpire.'.'.$time];
    }
}

//检查链接
function check_link($fid,$title,$url,$url_standby){
    global $db;
    $pattern = "/^(http:\/\/|https:\/\/|ftp:\/\/|ftps:\/\/|sftp:\/\/|magnet:?|ed2k:\/\/|thunder:\/\/|tcp:\/\/|udp:\/\/|rtsp:\/\/).+/";
    
    if(empty($fid)) {msg(-1007,'分类id(fid)不能为空！');}
    $count = $db->count("on_categorys", ["id" => $fid]);
    if (empty($count)){msg(-1007,'分类不存在！');}
    if (empty($title)){msg(-1008,'标题不能为空！');}
    if (empty($url)){msg(-1009,'URL不能为空！');}
    if (check_xss($url)){msg(-1010,'URL存在非法字符！');}
    if (check_xss($url_standby)){msg(-1010,'备用URL存在非法字符！');}
    if (!preg_match($pattern,$url)){msg(-1010,'URL无效！');}
    if ( ( !empty($url_standby) ) && ( !preg_match($pattern,$url_standby) ) ) {msg(-1010,'备选URL无效！');}
    return true;
}



// 书签同步
function sync() {
    $data = $_POST['data'];
    $data = json_decode($data, true); // JSON字符串转数组
    $data = $data[0]["children"]; //取根节点
    if( !count($data) ) msg (-2000,'解析失败..'); 
   
    // 遍历根节点(id:1 = 书签栏 id:2 = 其他书签)
    for($i=0; $i<count($data); $i++){
        $info = get_sync_link($data[$i]["children"],$data[$i]['title']);
    }
    msgA(['code'=>  0,'msg'=>  '总数：'.intval($info["total"]).' 成功：'.intval($info["success"]).' 失败：'.intval($info["fail"])  ]);
}
    
//书签同步处理
function get_sync_link($data,$folder) {
    global $db;
    static $info; //声明一个静态变量.用于计数!
    static $pattern = "/^(http:\/\/|https:\/\/|ftp:\/\/|ftps:\/\/|magnet:?|ed2k:\/\/|tcp:\/\/|udp:\/\/|thunder:\/\/|rtsp:\/\/|rtmp:\/\/|sftp:\/\/).+/";
    // 如果文件夹名为空,则使用默认分类!
    if(empty($folder) ){$folder = "默认分类";}
        
    //根据文件夹名称查找分类id 
    $categorys_name = htmlspecialchars(trim($folder),ENT_QUOTES);
    $categorys_id = $db-> get('on_categorys', 'id', ['name' => $categorys_name]);
    $info["categorys"]++; //分类计数
    if( empty($categorys_id) && !empty($data) ){  //如果没找到就创建一个! (如果是空文件夹则不创建)
        $categorys = [
                'name'          =>  $categorys_name,
                'add_time'      =>  time(),
                'weight'        =>  0,
                'property'      =>  1,
                'description'   =>  "书签同步时自动创建",
                'fid'           =>  0
            ];
        $db->insert("on_categorys",$categorys);
        $categorys_id = $db->id();//返回ID
        if( empty($categorys_id) ){
            msg(-1000,'创建分类失败,意外结束..');
        }
    }
    
    foreach ($data as $key => $value) {
        if( empty($value['url']) ) { //如果URL为空则为文件夹!
            get_sync_link($value['children'],$value['title'],$value['id']); //调用自身,继续遍历!
        }else{
            $info["total"]++; //总数
            //检查代码,标题不能为空,url地址通过正则判断是否合规!
            if( empty($value['title'])  || !preg_match($pattern,$value['url'])){
                $info["fail"]++;
                continue; 
            }

            $link_data = [
                'fid'           =>  intval($categorys_id),
                'title'         =>  htmlspecialchars($value['title']),
                'url'           =>  htmlspecialchars($value['url'],ENT_QUOTES),
                'add_time'      =>  time(),
                'weight'        =>  0,
                'property'      =>  0
            ];
            //插入数据库
            try{
             $re = $db->insert('on_links',$link_data);
             $id = $db->id();
            }catch(Exception $e){
             $id = 0;
            }

            if( empty($id) ){ 
                $info["fail"]++; //失败
            }else{
                $info["success"]++; //成功
            }
        }
    }
    return $info;
}

//标签组>添加
function add_tags(){
    global $db;
    $name = $_POST['name'];
    $mark = $_POST['mark'];
    $pass = $_POST['pass'];
    $expire = $_POST['expire'];
    if( empty($name) ) {
        msg(-1111,'名称不能为空');
    }elseif( !preg_match('/^[A-Za-z0-9]{1,13}$/', $mark) ) {
        msg(-1111,'标识只能是13位内的数字和字母');
    }elseif( !empty($expire) &&  !preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/i',$expire)){
        msg(-1111,'时间错误');
    }elseif( strlen($name) > 128 ){
        msg(-1111,'名称长度过长>128');
    }elseif( strlen($pass) > 128 ){
        msg(-1111,'密码长度过长>128');
    }elseif(!is_subscribe(true)){
        msg(-1111,'您未订阅,请先购买订阅!');
    }
    $data = [
            'name'           =>  htmlspecialchars($name),
            'mark'         =>  htmlspecialchars($mark),
            'pass'           =>  $pass,
            'expire'      =>  strtotime($expire),
            'add_time'      => time(),
            ];
    try{       
        $re = $db->insert('lm_tag',$data);
        $id = $db->id();
    }catch(Exception $e){
        $id=0;
    }
    if( empty($id) ){ 
        msg(-1111,'添加失败,检查名称和标识是否重复!');
    }else{
        msg(0,'添加成功');
    }
}
//标签组>删除
function del_tags(){
    global $db;
    $id = $_POST['id'];
    if( empty($id)){
		msg(-1003,'链接ID不能为空！');
	}elseif(md5($id) != $_POST['md5']){
	    msg(-1003,'签名错误');
	}
    $idgroup = explode(",",$id);//分割文本
    $fail = 0 ;
    foreach($idgroup as $id){
        $count = $db->count("on_links", ["tagid" => $id]); 
        if($count > 0) { 
            $fail++;
        }else{
            $re = $db->delete('lm_tag',['id'=>intval($id)]);
            if( !( $re->rowCount() ) ){
                $fail++;
            }
        }
    }
    msg(0,'处理完毕'.($fail>0?",{$fail}个失败!":'!'));
}
//标签组>编辑 
function edit_tags(){
    global $db;
    $id = $_POST['id'];
    $name = $_POST['name'];
    $mark = $_POST['mark'];
    $pass = $_POST['pass'];
    $expire = $_POST['expire'];

    if( empty($name) ) {
        msg(-1111,'名称不能为空');
    }elseif( !preg_match('/^[A-Za-z0-9]{1,13}$/', $mark) ) {
        msg(-1111,'标识只能是13位内的数字和字母');
    }elseif( !empty($expire) &&  !preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/i',$expire)){
        msg(-1111,'时间错误');
    }elseif( strlen($name) > 128 ){
        msg(-1111,'名称长度过长>128');
    }elseif( strlen($pass) > 128 ){
        msg(-1111,'密码长度过长>128');
    }elseif(!is_subscribe()){
        msg(-1111,'您未订阅,请先购买订阅!');
    }
    

    $data = [
            'name'           =>  htmlspecialchars($name),
            'mark'         =>  htmlspecialchars($mark),
            'pass'           =>  $pass,
            'expire'      =>  strtotime($expire),
            'up_time'      => time(),
            ];

    try{       
        $re = $db->update('lm_tag',$data,[ 'id' => $id]);
        $row = $re->rowCount();
    }catch(Exception $e){
        $row=0;
    }
    
    if(!$row) {
        msg(-1111,'修改失败,检查名称和标识是否重复!');
    }else{
        msg(0,'修改成功');
    }
}

//标签组>设置
function set_tags(){
    global $db;
    $taghome = $_POST['taghome']=='on'?'on':'off' ;
    $tag_private = $_POST['tag_private']=='on'?'on':'off' ;
    $tagin = $_POST['tagin'];

    if( $tagin != 'id' && $tagin != 'mark' && $tagin != 'id/mark') {
        msg(-1111,'参数错误 -2');
    }
    
    Writeconfig('taghome',$taghome);
    Writeconfig('tagin',$tagin);
    Writeconfig('tag_private',$tag_private);
    msg(0,'修改成功');

}

//标签组>链接表设标签
function link_set_tag(){
    if(!is_subscribe()){
        msg(-1111,'您未订阅,请先购买订阅!');
    }
    global $db;
    $lid = $_POST['lid'];
    $tagid = $_POST['tagid'];
    if($lid =='' || $tagid == ''){msg(-1003,'ID不能为空');}
    $sql= "UPDATE on_links SET tagid = ".$tagid." where id in(".$lid.");";
    $data =$db->query($sql);
    $row = $data->rowCount();//返回影响行数
    if($row){  
        msg(0,'successful!');
    }else{
        msg(-1111,'修改失败!');
    }
}

//标签组>列表 
function tags_list(){
    global $db;
    $page = empty(intval($_REQUEST['page'])) ? 1 : intval($_REQUEST['page']);
    $limit = empty(intval($_REQUEST['limit'])) ? 20 : intval($_REQUEST['limit']);
    setcookie_lm_limit($limit);
    $offset = ($page - 1) * $limit; //起始行号

    //统计语句
    $count_sql = "SELECT COUNT(1) AS COUNT FROM lm_tag";
    //查询语句
    $query_sql = "SELECT *,(SELECT count(*) FROM on_links  WHERE tagid = tag.id ) AS count FROM lm_tag AS tag LIMIT $limit OFFSET $offset";
    //统计总数
    $count_re = $db->query($count_sql)->fetchAll();
    $count = intval($count_re[0]['COUNT']);
    //查询
    $datas = $db->query($query_sql)->fetchAll();  
    msgA(['code'=>0,'msg'=>'','count'=>$count,'data'=>$datas,
    "sql" => $query_sql 
    ]);
}

//保存订阅设置
function set_subscribe(){
    global $udb;
    is_admin();
    $data['order_id'] = htmlspecialchars( trim($_REQUEST['order_id']) ); //获取订单ID
    $data['email'] = htmlspecialchars( trim($_REQUEST['email']) ); //获取邮箱
    $data['end_time'] = htmlspecialchars( trim($_REQUEST['end_time']) );//到期时间
    $data['domain'] = htmlspecialchars( trim($_REQUEST['domain']) );//支持域名
    $data['host'] = $_SERVER['HTTP_HOST']; //当前域名
    if(empty($data['order_id'])&&empty($data['email'])&&empty($data['end_time'])){
        $value = serialize($data); //序列化存储
        Writeconfigd($udb,'config','s_subscribe',$value); //序列化存储到数据库
        msg(0,'清除成功');
    }
    if (preg_match('/(.+)\.(.+\..+)/i',$_SERVER["HTTP_HOST"],$HOST) ){$data['host'] = $HOST[2];} //取根域名
    if(!strstr($data['domain'],$data['host'])){
        msg(-1111,"您的订阅不支持当前域名 >> ".$_SERVER['HTTP_HOST']);
    }elseif($data['end_time'] < time()){
        msg(-1111,"您的订阅已过期!");
    }
    $value = serialize($data); //序列化存储
    Writeconfigd($udb,'config','s_subscribe',$value); //序列化存储到数据库
    msg(0,'保存成功');
}

//数据备份>创建备份
function backup_db(){
    global $u,$version,$db;
    if(!is_subscribe()) { msg(-1111,'您未订阅,请先购买订阅!'); }
    $backup_dir = "data/user/{$u}/backup/"; //备份目录
    //判断目录是否存在，不存在则创建
    if( !is_dir($backup_dir) ) {
        try {
            mkdir($backup_dir,0755,true);
        } catch (\Throwable $th) {
            msg(-2000,'备份目录创建失败，请检查目录权限！');
        }
    }
    //尝试拷贝数据库进行备份
    try {
        $strs="QWERTYUIOPASDFGHJKLZXCVBNM1234567890qwertyuiopasdfghjklzxcvbnm";
        $random=substr(str_shuffle($strs),mt_rand(0,strlen($strs)-11),5);
        $file_name = "{$version}_".date("ymdHis",time())."_{$random}";
        $backup_db_path = $backup_dir.$file_name.".db3" ; //数据库路径
        $backup_info_path = $backup_dir.$file_name.".info" ; //信息文件路径
        copy("data/{$u}.db3",$backup_db_path);
        try{ //复制成功时写信息文件
            $link_cont = $db->count('on_links', '*'); //取链接数
            $on_categorys = $db->count('on_categorys', '*'); //取分类数
            $md5 = md5_file($backup_db_path); //取文件MD5(用于回滚时核验)
            $desc = $_GET['desc'];
            $info = json_encode(["link_count"=> $link_cont , "category_count"=> $on_categorys , "md5" => $md5 , "version" => $version ,"desc" => "$desc" ]); //生成信息
            file_put_contents($backup_info_path, $info); //写到文件
        }catch (\Throwable $th) {}
        

        msg(200,'success');
    } catch (\Throwable $th) {
        msg(-2000,'创建备份文件失败，请检查目录权限！');
    }
}

//数据备份>删除
function del_backup_db() {
    global $u;
    if(!is_subscribe()) { msg(-1111,'您未订阅,请先购买订阅!'); }
    $name = $_GET['name'];
    //文件名检测
    if( !preg_match_all('/^v\d+\.\d+\.\d+-\d{8}_\d{12}_[A-Za-z0-9]{5}.db3$/',$name) ) {
        msg(-200,'数据库名称不合法！');
    }
    $backup_dir = "data/user/{$u}/backup/";
    //删除数据库
    try {
        unlink($backup_dir.$name);
        unlink($backup_dir.substr($name, 0,-4).'.info'); //删除信息文件
        msg(200,'备份数据库已被删除！');
    } catch (\Throwable $th) {
        msg(-200,"删除失败，请检查目录权限！");
    }
}

//回滚数据库
function restore_db() {
    global $u,$db,$udb;
    if(!is_subscribe()) { msg(-1111,'您未订阅,请先购买订阅!'); }
    $backup_dir = "data/user/{$u}/backup/"; //备份目录
    $name = $_GET['name'];
    $info_name = substr($name, 0,-4).'.info';
    //文件名检测
    if( !preg_match_all('/^v\d+\.\d+\.\d+-\d{8}_\d{12}_[A-Za-z0-9]{5}.db3$/',$name) ) {
        msg(-200,'数据库名称不合法！');
    }elseif(!file_exists($backup_dir.$info_name)){
        msg(-200,'info文件缺失！');
    }else{
        $info_file = @file_get_contents($backup_dir.$info_name);
        $info = json_decode($info_file,true);
    }
    if(!empty($info['md5']) && $info['md5'] != md5_file($backup_dir.$name)){
        msg(-200,"回滚失败，文件MD5不一致！可能是文件损坏或被修改过!"); 
    }

    //恢复数据库
    try {
        copy($backup_dir.$name,"data/{$u}.db3");
        try{ //账号关键数据同步写入
            $user = $udb->get("user","*",["User"=>$u]);
            Writeconfigd($db,'on_config',"User",$user['User']);
            Writeconfigd($db,'on_config',"Pass",$user['Pass']);
            Writeconfigd($db,'on_config',"RegTime",$user['RegTime']);
            Writeconfigd($db,'on_config',"RegIP",$user['RegIP']);
            Writeconfigd($db,'on_config',"SQLite3",$user['SQLite3']);
            Writeconfigd($db,'on_config',"Email",$user['Email']);
            Writeconfigd($db,'on_config',"Token",$user['Token']);
            Writeconfigd($db,'on_config',"Login",$user['Login']);
        }catch(\Throwable $th){
            msg(200,"回滚成功,同步数据失败了,建议联系站长修复");
        }
        msg(200,'数据库已回滚为'.$name.'链接数:'.$db->count('on_links', '*'));
    } catch (\Throwable $th) {
        msg(-200,"回滚失败，请检查目录权限！");
    }
}
    
//数据备份>列表
function backup_db_list() {
    global $u;
    if(!is_subscribe()) { msg(-1111,'您未订阅,请先购买订阅!'); }
    $backup_dir = "data/user/{$u}/backup/"; //备份目录
    $dbs = scandir($backup_dir); //遍历备份列表
    $newdbs = $dbs;
    if(empty($dbs)){msgA(['code' => 0,'msg' => '没有找到备份数据!','count' =>  0]);}

    //列表过滤
    for ($i=0; $i < count($dbs); $i++) { 
        if( ($dbs[$i] == '.') || ($dbs[$i] == '..') || ( substr($newdbs[$i], -4) != '.db3') ) {
            unset($newdbs[$i]);
        }
    }

    $dbs = $newdbs; //赋值过滤后的数据
    $num = count($dbs); //取列表数
    rsort($dbs,2); //按时间从大到小重排序

    //备份文件数大于10个时删除旧数据
    if( $num > 10 ) {
        for ($i=$num; $i > 10; $i--) { 
            unlink($backup_dir.$dbs[$i-1]); //删除备份文件
            unlink($backup_dir.substr($dbs[$i-1], 0,-4).'.info'); //删除信息文件
            array_pop($dbs); //删除数组最后一个元素
        }
        $count = 10;
    }else{
        $count = $num;
    }
    //声明一个空数组
    $data = [];
    //遍历数据库，获取时间，大小
    foreach ($dbs as $key => $value) {
        $arr['id'] = $key + 1;
        $arr['name'] =   $value;
        $arr['mtime'] = date("Y-m-d H:i:s",filemtime($backup_dir.$value));
        $arr['size'] = (filesize($backup_dir.$value) / 1024).'KB';
        try{ //读取信息文件
            $info_file = @file_get_contents($backup_dir.substr($value, 0,-4).'.info');
            $info = json_decode($info_file,true);
            $arr['category_cont'] = $info['category_count'];
            $arr['link_cont'] = $info['link_count'];
            $arr['md5'] = $info['md5'];
            $arr['desc'] = $info['desc'];
        }catch (\Throwable $th) {
            $arr['link_cont'] = null;
            $arr['category_cont'] = null;
            $arr['md5'] = null;
        }

        $data[$key] = $arr;
    }

    msgA( ['code' => 0,'msg' => '','count' =>  $count,'data' =>  $data] );
}
//下载备份数据库
function download_backup_db(){
    global $u,$db,$udb,$RegTime,$password;
    $pass = $_GET['pass'];
    if(md5(md5($pass).$RegTime) !== $password && md5($pass.$RegTime) !== $password ){
        exit('密码错误,请核对后再试！');
    }
    if(!is_subscribe()) { msg(-1111,'您未订阅,请先购买订阅!'); }
    $backup_dir = "data/user/{$u}/backup/"; //备份目录
    $name = $_GET['name'];
    //文件名检测
    if( !preg_match_all('/^v\d+\.\d+\.\d+-\d{8}_\d{12}_[A-Za-z0-9]{5}.db3$/',$name) ) {
        exit('数据库名称不合法！');
    }
    $SQLite3 = $backup_dir.'/'.$name;
    if(!file_exists($SQLite3)){
        exit('数据库不存在！');
    }
    header("Cache-Control: public");
    header("Content-Description: File Transfer");
    header('Content-disposition: attachment; filename='.basename($name)); //文件名
    header("Content-Type: application/db3");
    header("Content-Transfer-Encoding: binary"); //告诉浏览器，这是二进制文件
    header('Content-Length: '. filesize($SQLite3)); //告诉浏览器，文件大小
    readfile($SQLite3);
}
//邀请注册相关
function Reg() {
    global $reg,$udb;
    if($reg != '2' || !is_subscribe(true)){msg(-200,"当前无法使用该功能");}
    $sn = $_REQUEST['sn'];
    if($sn === 'add'){
        $list = unserialize(getconfig('invitation_list',''));
        $code = hash("crc32b",uniqid().'lm21'.time()); //生成注册码
        
        $list[$code]['code'] = '1'; //1.未使用 2.已使用 其他表示不存在
        $list[$code]['desc'] = '未使用';
        $list[$code]['add_time'] = time(); //添加时间
        $list[$code]['use_time'] = null; //使用时间
        Writeconfig('invitation_list',serialize($list));
        msgA( ['code' => 0,'msg' => '添加成功','key' =>  $code] );
    }elseif($sn === 'list'){
        
        
        $list = unserialize(getconfig('invitation_list',''));
        $datas = [];
        foreach ($list as $key => $info ){
            $i++;
            array_push($datas,array( 'order'=>$i,'key'=>"$key",'code'=>$info['code'],'desc'=>$info['desc'],'add_time'=>$info['add_time'],'use_time'=>$info['use_time'] ));
        }
    
        $data = [
            'code'  => 0,
            'msg'   => '获取成功',
            'count'=> count($datas),
            'data' => $datas,
        ];
        msgA($data);
    }elseif($sn === 'empty'){ //清空数据,前端没做
        Writeconfig('invitation_list','');
        msgA( ['code' => 0,'msg' => '已清空数据'] );
    }elseif($sn === 'Set'){ 
        Writeconfigd($udb,'config','Get_Invitation',$_POST['url']);
        msgA( ['code' => 0,'msg' => '已保存设置'] );
    }
    
    
    msg(-200,"无效参数");
}
//导出数据到TwoNav
function export_to_twonav(){
    global $udb;is_admin();
    
    if($_GET['type'] == 'user_list'){
        
        if(!is_dir('./data/updata')){
            mkdir('./data/updata', 0777);
        }

        try {
            if(is_file('./data/updata/lm.TwoNav.db3')){unlink('./data/updata/lm.TwoNav.db3');}
            $MyDB = new Medoo\Medoo(['database_type'=>'sqlite','database_file'=>'./data/updata/lm.TwoNav.db3']);
            $MyDB->query('CREATE TABLE IF NOT EXISTS "backup" ("id" INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,"name" TEXT,"data" TEXT,CONSTRAINT "id" UNIQUE ("id" ASC));')->fetchAll();
            $MyDB->insert('backup',['name'=>'ver','data'=>get_version()]); //记系统版本
            $MyDB->insert('backup',['name'=>'backup_time','data'=>time()]); //记备份时间
            
            $configs = [];
            foreach($udb->select("config","*") as $config){
                $configs[$config['Name']] = $config['Value'];
            }
            $MyDB->insert('backup',['name'=>'config','data'=>$configs]); //全局配置
            
            $count = $udb->count('user'); //总条数
            $limit = 100; //每页数量
            $pages= ceil($count/$limit); //总页数
            //分页逐条处理
            for ($page=1; $page<=$pages; $page++) {
                $users = $udb->select('user','*',['ORDER' => ['ID'=>'ASC'],'LIMIT'=>[($page - 1) * $limit,$limit]]);
                foreach($users as $user){
                    $MyDB->insert('backup',['name'=>'user','data'=>$user]); 
                } 
            }
            
        }catch (Exception $e) {
            msg(-1,'创建备份数据库失败'); 
        }
        
        $user_list = $udb->select('user','User',['ORDER' => ['ID'=>'ASC']]);
        msgA(['code' => 1,'info' => $user_list]);
    }
    
    if($_GET['type'] == 'export'){
        try {
            $User = $udb->get('user','*',['User' => $_POST['user']]);
            if(empty($User) || !is_file("./data/{$User['SQLite3']}")){
                msg(-1,"用户数据库不存在");
            }
            $MyDB = new Medoo\Medoo(['database_type'=>'sqlite','database_file'=>'./data/updata/lm.TwoNav.db3']);
            $UserDB = new Medoo\Medoo(['database_type'=>'sqlite','database_file'=>"./data/{$User['SQLite3']}"]);
            foreach(['on_categorys','on_links','on_config','lm_apply','lm_tag'] as $table_name){
                $count = $UserDB->count($table_name); //总条数
                $limit = 100; //每页数量
                $pages= ceil($count/$limit); //总页数
                //分页处理
                for ($page=1; $page<=$pages; $page++) {
                    $datas = $UserDB->select($table_name,'*',['ORDER' => ['id'=>'ASC'],'LIMIT'=>[($page - 1) * $limit,$limit]]);
                    foreach($datas as $data){
                        $MyDB->insert('backup',['name'=>"{$User['User']}_{$table_name}",'data'=>$data]);
                    }
                }
            }
        }catch (Exception $e) {
            msg(-1,'写入备份数据失败'); 
        }
        
        //备份数据目录
        try {
            $file_list = glob("./data/user/{$User['User']}/MessageBoard/*.json");
            $destDir = "./data/updata/{$User['User']}/MessageBoard";
            if (!empty($file_list)  && !is_dir($destDir)) mkdir($destDir,0777,true) or msg(-1,'创建目录失败,请检查权限!');
            foreach ($file_list as $filePath){
                if(is_file($filePath)){
                    copy($filePath, $destDir .'/'. basename($filePath));
                }
            }
            $file_list = glob("./data/user/{$User['User']}/favicon/*.{jpeg,jpg,png,ico,svg}",GLOB_BRACE);
            $destDir = "./data/updata/{$User['User']}/favicon";
            if (!empty($file_list)  && !is_dir($destDir)) mkdir($destDir,0777,true) or msg(-1,'创建目录失败,请检查权限!');
            foreach ($file_list as $filePath){
                if(is_file($filePath)){
                    copy($filePath, $destDir .'/'. basename($filePath));
                }
            }
        }catch (Exception $e) {
            msg(-1,'复制用户数据失败'); 
        }
        
        msg(1,"success");
    }
    
    if($_GET['type'] == 'pack_data'){
        try {
            $Path = "./data/twonav_updata_".uniqid().".tar";
            if(is_file($Path)){
                unlink($Path);
            }
            $phar = new PharData($Path);
            $phar->buildFromDirectory("./data/updata");
            if(!is_file($Path)){
                msg(-1,'打包数据失败');
            }
            //计算文件哈希值并重新命名
            $new_Path = "./data/twonav_updata_".hash_file('crc32b',$Path).".tar";
            if(is_file($new_Path)){
                unlink($new_Path);
            }
            rename($Path,$new_Path);
        } catch (Exception $e) {
            msg(-1,'压缩数据异常');
        }
        deldir("./data/updata");
        msg(1,basename($new_Path));
    }
    msg(-1,'参数错误');
}

function check_xss($value){
    if(preg_match('/<(iframe|script|body|img|layer|div|meta|style|base|object|input)|">/i',$value)){
        return true;
    }else{
        return false;
    }
}