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

//是否加载防火墙
if($XSS == 1 || $SQL == 1){require ('./class/WAF.php');}
//获取方法并过滤,判断是否存在函数,存在则调用!反之报错!

if ( function_exists($method) ) {
    $method();
}else{
    msg(-1000,'method not found!');;
}

//分类列表
function category_list(){
    global $db,$OnlyOpen;
    $q = inject_check($_POST['query']);//获取关键字(防止SQL注入)
    if ( !empty ($_GET['page'])){
        $page  = empty(intval($_GET['page']))  ? 1 : intval($_GET['page']);  //页码
    }else{
        $page  = empty(intval($_POST['page']))  ? 1 : intval($_POST['page']);  //页码
    }
    if ( !empty ($_GET['page'])){
        $limit = empty(intval($_GET['limit'])) ? 20: intval($_GET['limit']); //每页条数
    }else{
        $limit = empty(intval($_POST['limit'])) ? 20: intval($_POST['limit']); //每页条数
    }
    
    $offset = ($page - 1) * $limit; //起始行号
    $property = $OnlyOpen == true ? ' And property = 0 ':''; //访客模式,添加查询语句只查询公开分类
    $sql = "SELECT *,(SELECT count(*) FROM on_links  WHERE fid = on_categorys.id ) AS count  FROM on_categorys  WHERE (name LIKE '%{$q}%' or description LIKE '%{$q}%' ) {$property} ORDER BY weight DESC,id DESC LIMIT {$limit} OFFSET {$offset}";
    if ($OnlyOpen){
        //游客/访客模式,取公开数量
        $count = $db->count('on_categorys','*',[ "AND"=>["OR"=>['name[~]'=>$q,'description[~]'=>$q],"property"=>'0']] );
    }else{
        $count = $db->count('on_categorys','*',["OR" =>['name[~]'=>$q,'description[~]'=>$q]]); //统计总数
    }
    
    $datas = $db->query($sql)->fetchAll(); //原生查询
    msgA(['code'=>0,'msg'=>'','count'=>$count,'data'=>$datas]);
}

//添加分类
function add_category(){
    global $db;
    $name = $_POST['name'];//获取分类名称
    $Icon = $_POST['Icon'];//获取分类图标
    $property = empty($_POST['property']) ? 0 : 1;//获取私有属性
    $weight = empty($_POST['weight']) ? 0 : intval($_POST['weight']);//获取权重
    $description = $_POST['description']; //获取描述
    if(empty($name)){
        msg(-1004,'分类名称不能为空！');
    }elseif(!empty($Icon) && !preg_match('/^(layui-icon-|fa-)([A-Za-z0-9]|-)+$/',$Icon)){
        msg(-1004,'无效的分类图标！');
    }

    $data = [
        'name'          =>  htmlspecialchars($name,ENT_QUOTES),
        'add_time'      =>  time(),
        'weight'        =>  $weight,
        'property'      =>  $property,
        'description'   =>  htmlspecialchars($description,ENT_QUOTES),
        'Icon'          =>  htmlspecialchars($Icon,ENT_QUOTES)
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
    }

    $data = [
        'name'          =>  htmlspecialchars($name,ENT_QUOTES),
        'up_time'       =>  time(),
        'weight'        =>  $weight,
        'property'      =>  $property,
        'description'   =>  htmlspecialchars($description,ENT_QUOTES),
        'Icon'          =>  htmlspecialchars($Icon,ENT_QUOTES)
        ];
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
    if ( !empty ($_GET['page'])){
        $page  = empty(intval($_GET['page']))  ? 1 : intval($_GET['page']);  //页码
    }else{
        $page  = empty(intval($_POST['page']))  ? 1 : intval($_POST['page']);  //页码
    }
    if ( !empty ($_GET['limit'])){
        $limit = empty(intval($_GET['limit'])) ? 20: intval($_GET['limit']); //每页条数
    }else{
        $limit = empty(intval($_POST['limit'])) ? 20: intval($_POST['limit']); //每页条数
    }
    
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
            'property'      =>  $property
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
            'property'      =>  $property
            ];
    $re = $db->update('on_links',$data,[ 'id' => $id]); //更新数据
    $row = $re->rowCount();//返回影响行数
    if($row){
        $id = $db->id();//返回ID
        msgA(['code'=>0,'id'=>$id]);
    }else{
        msg(-1011,'URL已经存在！');
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
    if(!filter_var($url, FILTER_VALIDATE_URL)){msg(-1010,'URL无效!');}
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
        //检查目录
        if(!is_dir('data/upload')){
            mkdir('data/upload',0755,true);
        }
        //转移上传的文件(不转移的话代码执行完毕文件就会被删除) 待测试在二级目录上传是否正常!!!!!!
        if(copy($temp,'data/upload/'.$filename)){
            msgA(['code'=>0,'file_name' =>'data/upload/'.$filename]);
        }
    }
}
//书签导入
function imp_link() {
    global $db;
    $filename = trim($_POST['filename']);//书签路径
    //过滤$filename
    $filename = str_replace('../','',$filename);
    $filename = str_replace('./','',$filename);
    $fid = intval($_POST['fid']); //所属分类
    $property = intval(@$_POST['property']); //私有属性
    $all =intval(@$_POST['all']); //保留属性
    $suffix = strtolower(end(explode('.',$filename)));
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
    if($suffix==='html'){
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
    msg(-1016,'不支持的文件类型!');
}

//主页设置
function edit_homepage(){
    $TEMPLATE = htmlspecialchars($_POST['TEMPLATE']);//主题风格
    $TEMPLATE2 = htmlspecialchars($_POST['TEMPLATE2']);//主题风格2
    $navwidth = intval($_POST['navwidth']); //导航宽度
    if($TEMPLATE == 'admin' || empty($TEMPLATE) || $TEMPLATE2 == 'admin' || empty($TEMPLATE2)){
        msg(-1102,'主题风格设置错误！');
    }elseif(!empty($TEMPLATE)  &&  !file_exists('./templates/'.getSubstrRight($TEMPLATE,'|').'/index.php')){
        msg(-1103,'主题模板不存在1！');
    }elseif(!empty($TEMPLATE2)  &&  !file_exists('./templates/'.getSubstrRight($TEMPLATE2,'|').'/index.php')){
        msg(-1103,'主题模板不存在2！');
    }elseif(htmlspecialchars($_POST['title'],ENT_QUOTES) !=$_POST['title']){
        msg(-1103,'站点标题存在非法字符！');
    }elseif(htmlspecialchars($_POST['description'],ENT_QUOTES) !=$_POST['description']){
        msg(-1103,'站点描述存在非法字符！');
    }elseif(htmlspecialchars($_POST['logo'],ENT_QUOTES) !=$_POST['logo']){
        msg(-1103,'文字logo存在非法字符！');
    }elseif(htmlspecialchars($_POST['keywords'],ENT_QUOTES) !=$_POST['keywords']){
        msg(-1103,'站点关键词存在非法字符！');
    }elseif(htmlspecialchars($_POST['ICP'],ENT_QUOTES) !=$_POST['ICP']){
        msg(-1103,'ICP备案号存在非法字符！');
    }elseif($_POST['navwidth'] != '' && ($navwidth <  1 or $navwidth > 500) ){
        msg(-1103,'导航宽度设置错误,范围值1-500！');
    }
    Writeconfig('title',        htmlspecialchars($_POST['title'],       ENT_QUOTES));//站点标题
    Writeconfig('description',  htmlspecialchars($_POST['description'], ENT_QUOTES));//站点描述
    Writeconfig('logo',         htmlspecialchars($_POST['logo'],        ENT_QUOTES));//文字logo
    Writeconfig('keywords',     htmlspecialchars($_POST['keywords'],    ENT_QUOTES));//站点关键词
    Writeconfig('ICP',          htmlspecialchars($_POST['ICP'],         ENT_QUOTES));//ICP备案号
    Writeconfig('urlz',         strip_tags($_POST['urlz']       ));//URL直连
    Writeconfig('gotop',        strip_tags($_POST['gotop']      ));//返回顶部
    Writeconfig('quickAdd',     strip_tags($_POST['quickAdd']   ));//快速添加
    Writeconfig('GoAdmin',      strip_tags($_POST['GoAdmin']    ));//后台入口
    Writeconfig('LoadIcon',     strip_tags($_POST['LoadIcon']   ));//加载图标
    Writeconfig('navwidth',     empty($_POST['navwidth']) ? '' : intval($_POST['navwidth']));//导航宽度
    Writeconfig('footer',       base64_encode($_POST['footer']  ));//底部代码
    Writeconfig('head',         base64_encode($_POST['head']    ));//头部代码
    Writeconfig('Style',        getSubstrLeft ($TEMPLATE,'|'    ));//主题样式
    Writeconfig('Theme',        getSubstrRight($TEMPLATE,'|'    ));//主题模板
    Writeconfig('Style2',        getSubstrLeft ($TEMPLATE2,'|'    ));//主题样式2
    Writeconfig('Theme2',        getSubstrRight($TEMPLATE2,'|'    ));//主题模板  2  
    global $u;//如果选择了默认首页
    if($_POST['DefaultDB'] ==='on' &&  $_COOKIE['DefaultDB'] !== $u){setcookie('DefaultDB',$u, 32472115200,"/");}
    msg(0,'successful');
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
    }
    // $Re = $udb->query("select * from sqlite_master where name = 'user' and sql like '%".'"'."VisitorKey".'"'."%'")->fetchAll();
    // $num = intval($Re[0]['num']);
    // if($num ==0){
    //     $udb->query('ALTER TABLE "user"  ADD COLUMN "VisitorKey" TEXT(32)');
    // }
    
    // $Re = $udb->query("select * from sqlite_master where name = 'user' and sql like '%".'"'."AuthoKey".'"'."%'")->fetchAll();
    // $num = intval($Re[0]['num']);
    // if($num ==0){
    //     $udb->query('ALTER TABLE "user"  ADD COLUMN "AuthoKey" TEXT(32)');
    // }
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
    $limit = empty(intval($_POST['limit'])) ? 20 : intval($_POST['limit']);//每页条数
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
    global $db,$SQLite3;
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
                //写入更新记录
                $insert_re = $db->insert("on_db_logs",["sql_name" => $name, "update_time" => time(), "status" => "TRUE" ]);
                if( $insert_re ) {
                    msg(0,$name." 更新完成！");
                }else {
                    msgA(["code" => -2000,"data" => " 更新失败,请人工检查！(写入on_db_logs失败.)",'error' => $db2->lastErrorMsg()]);
                }
            }else{
                //如果执行失败
                msgA(["code" => -2000,"data" => " 更新失败,请人工检查！",'error' => $db2->lastErrorMsg()]);
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
    if(empty($fid)) {msg(-1007,'分类id(fid)不能为空！');}
    $count = $db->count("on_categorys", ["id" => $fid]);
    if (empty($count)){msg(-1007,'分类不存在！');}
    if (empty($title)){msg(-1008,'标题不能为空！');}
    if (empty($url)){msg(-1009,'URL不能为空！');}
    if (preg_match('/<(iframe|script|body|img|layer|div|meta|style|base|object|input)|">/i',$url)){msg(-1010,'URL存在非法字符！');}
    if (!filter_var($url, FILTER_VALIDATE_URL)){msg(-1010,'URL无效！');}
    if ( ( !empty($url_standby) ) && ( !filter_var($url_standby, FILTER_VALIDATE_URL) ) ) {msg(-1010,'备选URL无效！');}
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