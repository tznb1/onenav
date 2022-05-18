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
    // 禁止修改主页|账号设置|删除用户|上传书签|导入输入|查登录日志
    $pattern = "/(edit_homepage|edit_user|user_list_del|upload|imp_link|loginlog_list|edit_root)/i";
    if ( preg_match($pattern,$method) ) {msg(-1010,'演示站禁止此操作!');}
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
    $property = $OnlyOpen == true ? ' And property = 0 ':''; //访客模式,添加查询语句只查询公开分类
    //$sql = "SELECT *,(SELECT count(*) FROM on_links  WHERE fid = on_categorys.id ) AS count  FROM on_categorys  WHERE (name LIKE '%{$q}%' or description LIKE '%{$q}%' ) {$property} ORDER BY weight DESC,id DESC LIMIT {$limit} OFFSET {$offset}";
    
    $sql = "SELECT *,(SELECT Icon FROM on_categorys WHERE id = a.fid LIMIT 1 ) AS fIcon,(SELECT name FROM on_categorys WHERE id = a.fid LIMIT 1 ) AS fname ,(SELECT count(*) FROM on_links  WHERE fid = a.id ) AS count FROM on_categorys as a WHERE (name LIKE '%{$q}%' or description LIKE '%{$q}%' )  ORDER BY weight DESC,id DESC LIMIT {$limit} OFFSET {$offset}";
    if ($OnlyOpen){
        //游客/访客模式,取公开数量
        $count = $db->count('on_categorys','*',[ "AND"=>["OR"=>['name[~]'=>$q,'description[~]'=>$q],"property"=>'0']] );
    }else{
        $count = $db->count('on_categorys','*',["OR" =>['name[~]'=>$q,'description[~]'=>$q]]); //统计总数
    }
    
    $datas = $db->query($sql)->fetchAll(); //原生查询
    msgA(['code'=>0,'msg'=>'','count'=>$count,'data'=>$datas ,'sql' =>$sql]);
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
    $db->insert("on_categorys",$data); //插入分类目录
    $id = $db->id();//返回ID
    if(empty($id)){
        msg(-1000,'分类已经存在！');
    }else{
        msgA(['code'=>0,'id'=>intval($id)]); //成功返回
    }
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
    if( !isset($fid) ) {
        array_pop($data);
    }
    $re  = $db->update('on_categorys',$data,[ 'id' => $id]);
    $row = $re->rowCount();//获取影响行数
    if($row) {
        msg(0,'successful');
    }else{
        msg(-1005,'分类名称已存在！');
    }
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
        if($count > 0) { 
            msg(-1006,'分类目录下存在数据,不允许删除!');
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
            $name  = geticon($db->get("on_categorys","Icon",["id"=>$_id])).$db->get("on_categorys","name",["id"=>$_id]);
            //分类有下有数据,强制删除时先删除分类ID相符的链接!
            if ($count > 0 && $force =='1'){ 
                $data = $db->delete('on_links',[ 'fid' => $_id]);
                if ($count = $data->rowCount()){
                    $res = $res.'<tr><td>'.$_id.'</td><td>'.$name.'</td><td>强行删除:'.$data->rowCount().'条链接,已删除!</td></tr>';
                    $db->delete('on_categorys',[ 'id' => $_id] );
                }else{
                    $res=$res.'<tr><td>'.$_id.'</td><td>'.$name.'</td><td>分类下存在:'.$count.'条链接,强行删除:'.$data->rowCount().'条链接,删除失败!</td></tr>';
                }
            }elseif($count > 0){ 
                //分类下有数据,非强制删除,提示删除失败
                $res=$res.'<tr><td>'.$_id.'</td><td>'.$name.'</td><td>分类目录下存在'.$count.'条数据,删除失败!</td></tr>';
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
    //判断分类筛选,统计条数
    if ($fid ==0 ){
        $class ='';
        if($OnlyOpen){
            $count = $db->count('on_links','*',[ "AND"=>["OR"=>['title[~]'=>$q,'description[~]'=>$q,'url[~]'=>$q],"property"=>"0"]]);
        }else{
            $count = $db->count('on_links','*',["OR" =>['title[~]'=>$q,'description[~]'=>$q,'url[~]'=>$q]]);
        }
        
    }else{
        $class =' And fid ='.intval($fid); //查询语句
         if($OnlyOpen){
             $count = $db->count('on_links','*',[ "AND"=>["OR"=>['title[~]'=>$q,'description[~]'=>$q,'url[~]'=>$q],"fid"=>$fid,"property"=>"0"]]);
         }else{
             $count = $db->count('on_links','*',[ "AND"=>["OR"=>['title[~]'=>$q,'description[~]'=>$q,'url[~]'=>$q],"fid"=>$fid]]);
         }
        
    }
    $property = $OnlyOpen == true ? ' And property = 0 ':'';
    $sql = "SELECT *,(SELECT name FROM on_categorys WHERE id = on_links.fid) AS category_name FROM on_links WHERE (title LIKE '%{$q}%' or description LIKE '%{$q}%' or url LIKE '%{$q}%') {$class} {$property} ORDER BY weight DESC,id DESC LIMIT {$limit} OFFSET {$offset}";
    $datas = $db->query($sql)->fetchAll();
    msgA(['code'=>0,'msg'=>'','count'=>$count,'data'=>$datas]);
}

//添加链接
function add_link(){
    global $db;
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
    $re = $db->insert('on_links',$data);//插入数据库
    $row = $re->rowCount();//返回影响行数
    if($row){
        $id = $db->id();//返回ID
        msgA(['code'=>0,'id'=>$id]);
    }else{
        msg(-1011,'URL已经存在！');
    }
}

//修改链接
function edit_link(){
    global $db;
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
    $re = $db->update('on_links',$data,[ 'id' => $id]); //更新数据
    $row = $re->rowCount();//返回影响行数
    if($row){
        $id = $db->id();//返回ID
        msgA(['code'=>0,'id'=>$id]);
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
				msg(0,'successful');
			} else {
				msg(-1010,'链接ID不存在！');
			}
		}
	}elseif($batch=='1'){
	    //批量删除
	    $idgroup=explode(",",$id);//分割文本
	    foreach($idgroup as $_id){
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
            msgA( ['code'=>0,'data'=>$link_info] );
        }else{
            msgA( ['code'=>0,'data'=>[] ] );
        }
    }else{
        msgA(['code'=>0,'data'=>$link_info]);
    }
}
//获取单个分类信息
function get_a_category() {
    global $db,$OnlyOpen;
    $id = intval(trim($_GET['id']));
    if(empty($id)){ $id = intval(trim($_POST['id'])); }
    if(empty($id)){ msg(-1010,'id不能为空！'); }
    $category_info = $db->get("on_categorys","*",["id" => $id]);
    //var_dump($category_info);
    //访客模式,如果分类是私有就返回空
    if($OnlyOpen){
        if ( $category_info['property'] == "0" ) {
            msgA( ['code'=>0,'data'=>$category_info] );
        }else{
            msgA( ['code'=>0,'data'=>[] ] );
        }
    }else{
        msgA(['code'=>0,'data'=>$category_info]);
    }
}
//获取链接信息
function get_link_info() {
    $url = @$_POST['url']; //获取URL
    //检查链接是否合法
    if( empty($url) ) {
        msg(-1010,'URL不能为空!');
    }elseif(!preg_match("/^(http:\/\/|https:\/\/).*/",$url)){
        msg(-1010,'只支持识别http/https协议的链接!');
    }elseif( !filter_var($url, FILTER_VALIDATE_URL) ) {
         msg(-1010,'URL无效!');
    }
    //获取网站标题
    $c = curl_init(); 
    curl_setopt($c, CURLOPT_URL, $url); 
    curl_setopt($c, CURLOPT_RETURNTRANSFER, 1); 
    curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($c, CURLOPT_SSL_VERIFYHOST, false);
    //设置超时时间
    curl_setopt($c , CURLOPT_TIMEOUT, 5);
    $data = curl_exec($c); 
    curl_close($c); 
    $pos = strpos($data,'utf-8'); 
    if($pos===false){$data = iconv("gbk","utf-8",$data);} 
    preg_match("/<title>(.*)<\/title>/i",$data, $title); 
    $link['title'] =  $title[1];
    //获取网站描述
    $tags = get_meta_tags($url);
    $link['description'] = $tags['description'];
    msgA(['code'=>0,'data'=>$link]);
}

//上传书签
function upload(){
    global $username;
    delfile('data/upload',5);
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
        //转移上传的文件(不转移的话代码执行完毕文件就会被删除) 待测试在二级目录上传是否正常!!!!!!
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
    $all = intval(@$_POST['all']); //保留属性
    $suffix = strtolower(end(explode('.',$filename)));
    $AutoClass = $_POST['AutoClass'];
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
        $categorys = $tempdb->select('on_categorys','*');//列出分类
        $data = $tempdb->query("select * from sqlite_master where name = 'on_categorys' and sql like '%Icon%'")->fetchAll(); //查找字段
        $icon = count($data)==0 ? false:true ; //判断有没有图标字段!如果没有则尝试从名称取图标写到专用字段
        // $data = $tempdb->query("select * from sqlite_master where name = 'on_categorys' and sql like '%Icon%'")->fetchAll(); //查找字段
        // $icon = count($data)==0 ? false:true ; //判断有没有图标字段!如果没有则尝试从名称取图标写到专用字段
        $fail =0;$success=0;//初始计数
        //遍历分类
        foreach ($categorys as $category) {
            $name = strip_tags($category['name']);//取分类名(存在XSS风险)
            if(empty($name)){continue;}//如果名称为空则跳到循环尾 (名称为空)
            $cat_name = $db->get('on_categorys','*',[ 'name' =>  $name ]); //取当前库同名ID
            $cat_id = $db->get('on_categorys','*',[ 'id' =>  $category['id'] ]); //取当前库同名ID
            $ico ='';
            //如果没有图标字段,则为原版数据库!尝试正则提取是否有图标,有的话就写到写到数据库!
            //注:原版可以写N个图标,但我这设计只能一个,所以只提取第一个图标!
            if (!$icon && preg_match('/<i class="fa (.+)"><\/i>/i',$category['name'],$matches) != 0){
                $ico=$matches[1];
            }elseif($icon){
                $ico=$category['Icon'];
            }
            //如果分类名相同就不创建新分类,而是把全部连接导入到同名分类下!
            if(strip_tags($cat_name['name'])==$name){
                $tempdb->update('on_categorys',['id' => $cat_name['id'] ],[ 'id' => $category['id']]); //修改分类ID,方便后面导入连接!
                $tempdb->update('on_links',['fid' => $cat_name['id'] ],[ 'fid' => $category['id']]); //修改链接分类ID,方便后面导入连接!
            }else{
                $data = [
                        'name'          =>  htmlspecialchars($name,ENT_QUOTES),
                        'add_time'      =>  $all == 1 ? $category['add_time']:time(),
                        'up_time'       =>  $all == 1 ? $category['up_time']:null,
                        'weight'        =>  $all == 1 ? $category['weight']:0,
                        'property'      =>  empty($category['property']) ? 0 : 1,
                        'fid'           =>  empty($category['fid']) ? 0 : intval($category['fid']) ,
                        'description'   =>  htmlspecialchars($category['description'],ENT_QUOTES),
                        'Icon'          =>  htmlspecialchars($ico,ENT_QUOTES)
                        ];
                $db->insert("on_categorys",$data);
                $id = $db->id();
                if(!empty($id)){
                    $tempdb->update('on_categorys',['id' => $id ],[ 'id' => $category['id']]); //修改分类ID,方便后面导入连接!
                    $tempdb->update('on_links',['fid' => $id ],[ 'fid' => $category['id']]); //修改链接分类ID,方便后面导入连接!
                }
            }
        }
        //导入链接
        $links = $tempdb->select('on_links','*');
        $total = count($links);
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
                'click'         =>  $all == 1 ? $link['click']:0
                ];
                $re = $db->insert('on_links',$data);
                $row = $re->rowCount();
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
            $re = $db->insert('on_links',$data);//插入数据库
            $row = $re->rowCount();//返回影响行数
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
        $ADD_DATE =  intval(@$_POST['ADD_DATE']);
        $icon     =  intval(@$_POST['icon']);  
        $iconcount = 0 ;
        $default_category = $db-> get('on_categorys', 'name', ['id' => $fid]);
        if(empty($default_category)){msg(-1016,'获取分类名失败!');}
        
        //如果提取图标的话检测目录是否存在,不存在则创建目录
        $new_file = 'favicon/'.$userdb['User'];
        if($icon == 1 && !file_exists($new_file)){
            mkdir($new_file, 0777);
        }
        
        // 遍历HTML
        foreach( $HTMLs as $HTMLh ){
            if( preg_match("/<DT><H3.+>(.*)<\/H3>/i",$HTMLh,$category) ){
                //匹配到文件夹名时加入数组
                $category[1] = empty($category[1]) ? $default_category : $category[1];
                array_push($categoryt,$category[1]);
                array_push($categorys,$category[1]);
            }elseif( preg_match('/<DT><A HREF="(.*)" ADD_DATE="(\d*)".*>(.*)<\/A>/i',$HTMLh,$urls) ){
                // 1.链接 2.添加时间 3.标题
                $datat['category']  = $categorys[count($categorys) -1];
                $datat['category']  = empty($datat['category']) ? $default_category : $datat['category'] ;
                $datat['ADD_DATE']  = $urls[2];
                $datat['title']     = $urls[3];
                $datat['url']       = $urls[1];
                $datat['html']   = $HTMLh;
                
                
                array_push($data,$datat);
            }elseif( preg_match('/<\/DL><p>/i',$HTMLh) ){
                //匹配到文件夹结束标记时删除一个
                array_pop($categorys);
            }
        }
        //遍历结束,分类名去重!
        $categoryt = array_unique($categoryt);
        //var_dump($categoryt);var_dump($data);exit;
         
        // 检查和创建分类
        $fids = [];
        $currenttime = time();
        foreach( $categoryt as $name ){
            $id = $db-> get('on_categorys', 'id', ['name' => $name]);
            if( empty($id) ){
                //插入分类目录
                $db->insert("on_categorys",['name' => $name,'add_time' => $currenttime,'property' => $property]); 
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
        //var_dump($fids);var_dump($data);exit;

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
            $re = $db->insert('on_links',[
                'fid'           =>  $fids[$link['category']],
                'add_time'      =>  $time,
                'title'         =>  $link['title'] ,
                'url'           =>  $link['url'],
                'property'      =>  $property,
                'iconurl'       =>  $path
            ]);
            $row = $re->rowCount();//返回影响行数
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
    $type = $_POST['type'];
    $name = $_POST['name'];
    if( $type == 'PC/Pad'){
        Writeconfig('Theme' , $name);
        Writeconfig('Theme2', $name);
    }elseif($type == 'PC'){
        Writeconfig('Theme' , $name);
    }elseif($type == 'Pad'){
        Writeconfig('Theme2' , $name);
    }
    
    msg(0,'设置成功');
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
    if($udb->get("user","Level",["User"=>$u]) !== '999'){ //权限判断
        msg(-1102,'您没有权限修改全局配置!');
    }elseif($udb->count("user",["User"=>$DUser]) === 0 ){ //账号检测
        msg(-1102,'默认账号'.$DUser.'不存在,请检查!');
    }elseif($Reg !== '0' && $Reg !== '1'){ //注册开关检测
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
        if($ud["Level"]==='999'){
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
            msgA(['code'=>0,'msg'=>'用户已经是'.($Level==='999'?'管理员':'普通会员!'),'icon'=>1,'Level'=>$Level]);
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

// 一键检测
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
    
    
    if( ( $php_version < 5.6 ) || ( $php_version > 8 ) ) {
        $log = $log . "PHP版本：不满足要求,需要5.6 <= PHP <= 7.4,建议使用7.4 )\n";
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
        $log = $log ."curl：不支持 (请安装libcurl)\n";
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
        $log = $log ."upload目录：异常,请检查权限!\n";
    }
    
     //$log = $log .phpinfo();
     msg(-1000,$log);
}

//获取onenav最新版本号
function get_latest_version() {
    global $udb,$version;
    try {
        $NewVer = $udb->get("config","Value",["Name"=>'NewVer']); //缓存的版本号
        $NewVer = $NewVer =='' ? $version : $NewVer ;  //如果没有记录就使用当前版本!
        $NewVerGetTime = $udb->get("config","Value",["Name"=>'NewVerGetTime']); //上次从Git获取版本号的时间
        //如果距上次获取时间超过30分钟则重新获取!
        if( time() - intval( $NewVerGetTime ) >= 1800 ) {
            $curl = curl_init("https://gitee.com/tznb/OneNav/raw/master/initial/version.txt");
            curl_setopt($curl, CURLOPT_FAILONERROR, true);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl , CURLOPT_TIMEOUT, 3); //超时3s
            $NewVer = curl_exec($curl);
            curl_close($curl);
            //如果获取成功则写入数据库!
            if(preg_match('/^v.+-(\d{8})$/i',$NewVer,$matches)){
                $NewVerGetTime = time();
                Writeconfigd($udb,'config','NewVer',$NewVer);
                Writeconfigd($udb,'config','NewVerGetTime',$NewVerGetTime);
                $gitee = true;
            }else{
                $NewVer = $version;
            }
        }
        
        $data = ["code" => 200,"msg" => ( $gitee ? 'on-line' : 'cache' ),"data" => $NewVer];
        
    } catch (\Throwable $th) {
        $data = [
            "code"      =>  200,
            "msg"       =>  "",
            "data"      =>  ""
        ];
    }
    msgA($data);
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
        $re = $db->insert('on_links',$data);//插入数据库
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
    $count_categorys = $db->Count("on_categorys");
    $count_links = $db->Count("on_links");
    if($count_categorys === 0 && $count_links === 0 ){
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
    header("Content-Type: application/db3"); //zip格式的
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
    if( $userdb['Level'] !== '999'){ msg(-1111,'您没有权限使用此功能');}
}
//其他函数
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



function check_xss($value){
    if(preg_match('/<(iframe|script|body|img|layer|div|meta|style|base|object|input)|">/i',$value)){
        return true;
    }else{
        return false;
    }
}

function get_http_code($url) { 
        $curl = curl_init(); 
        curl_setopt($curl, CURLOPT_URL, $url); //设置URL 
        curl_setopt($curl, CURLOPT_HEADER, 1); //获取Header 
        curl_setopt($curl, CURLOPT_NOBODY, true); //Body就不要了吧，我们只是需要Head 
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); //数据存到成字符串吧，别给我直接输出到屏幕了 
        $data = curl_exec($curl); //开始执行啦～ 
        $return = curl_getinfo($curl, CURLINFO_HTTP_CODE); //我知道HTTPSTAT码哦～ 
           
        curl_close($curl); //用完记得关掉他 
           
        return $return; 
    }