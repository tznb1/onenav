<?php
//name:API核心类
include_once ('./class/Class.php');

class Api {
    protected $db;
    public function __construct($db){
        $this->db = $db;
        //返回json类型
        header('Content-Type:application/json; charset=utf-8');
    }
   
   //主页设置
   public function edit_homepage($token,$title,$logo,$keywords,$urlz,$TEMPLATE,$description,$ICP,$footer,$db,$gotop,$quickAdd,$GoAdmin,$navwidth,$head,$LoadIcon){
        $this->auth($token);
        //更新数据库
        if($TEMPLATE == 'admin'){
            $this->err_msg(-1102,'主题风格设置错误！');
        }else if(!empty($TEMPLATE)  &&  !file_exists('./templates/'.getSubstrRight($TEMPLATE,'|').'/index.php')){
            $this->err_msg(-1103,'主题模板不存在,请核对后再试！');
        }
        //else if(!empty($Token)  && strlen($Token)<32){$this->err_msg(-1103,'因安全问题,Token长度不能小于32位！');} 
        Writeconfig('title',strip_tags($title));
        Writeconfig('description',strip_tags($description));
        Writeconfig('logo',strip_tags($logo));
        Writeconfig('keywords',strip_tags($keywords));
        Writeconfig('urlz',strip_tags($urlz));
        Writeconfig('gotop',strip_tags($gotop));
        Writeconfig('quickAdd',strip_tags($quickAdd));
        Writeconfig('GoAdmin',strip_tags($GoAdmin));
        Writeconfig('LoadIcon',strip_tags($LoadIcon));
        Writeconfig('ICP',strip_tags($ICP));
        Writeconfig('footer',base64_encode($footer));
        Writeconfig('head',base64_encode($head));
        Writeconfig('navwidth',number_format($navwidth));
        
        //if ($Email !="") {Writeconfig('Email',$Email);}
        //if ($user !="") {Writeconfig('user',$user);}
        //if ($password !="") {Writeconfig('password',$password);}
        //if ($Token !="") {Writeconfig('Token',$Token);}
        if ($TEMPLATE !="") {Writeconfig('Style',getSubstrLeft($TEMPLATE,'|'));Writeconfig('Theme',getSubstrRight($TEMPLATE,'|'));  }
        exit(json_encode($data = ['code'  =>  0,'msg'   =>  'successful']));
    }
  
//账号设置
   public function edit_user($token,$Email,$NewToken,$user,$pass,$newpassword){
        global $username,$password,$u;
        $this->auth($token);
        //更新数据库
        if($pass != $password){
            $this->err_msg(-1102,'密码错误,请核对后再试！');
        }else if(!empty($user)  && !check_user($user)){
            $this->err_msg(-1103,'账号只能使用英文和数字!');
        }else if(!empty($newpassword)  &&  strlen($newpassword)<8){
            $this->err_msg(-1103,'密码长度不能小于8个字符！');
        }else if($newpassword==$password){
            $this->err_msg(-1103,'新密码不能和原密码一样!');
        }else if(!empty($NewToken)  && strlen($NewToken)<32){
            $this->err_msg(-1103,'因安全问题,Token长度不能小于32位！');
        }
        $logout=0;
        if ($Email !="") {Writeconfig('Email',$Email);}
        if ($user !="" && $user !=$username) {Writeconfig('user',$user);$logout=1;}
        if ($newpassword !="" && $newpassword !=$password) {Writeconfig('password',$newpassword);$logout=1;}
        if ($NewToken !="") {Writeconfig('Token',$NewToken);}
        exit(json_encode($data = ['code'=>0,'msg'=>'successful','logout'=>$logout,'u'=> $u]));
    }
    //创建分类目录
    public function add_category($token,$name,$property = 0,$weight = 0,$description = '',$Icon){
        $this->auth($token);
        if( empty($name) ){
            $this->err_msg(-1004,'分类名称不能为空！');
        }
        $data = [
            'name'          =>  strip_tags($name),
            'add_time'      =>  time(),
            'weight'        =>  $weight,
            'property'      =>  $property,
            'description'   =>  strip_tags($description),
            'Icon'          =>  $Icon
        ];
        //插入分类目录
        $this->db->insert("on_categorys",$data);
        //返回ID
        $id = $this->db->id();
        //如果id为空（NULL），说明插入失败了，姑且认为是name重复导致
        if( empty($id) ){
            $this->err_msg(-1000,'分类已经存在！');
        }
        else{
            //成功并返回json格式
            $data = [
                'code'      =>  0,
                'id'        =>  intval($id)
            ];
            exit(json_encode($data));
        }
        
    }

    //修改分类目录
    public function edit_category($token,$id,$name,$property = 0,$weight = 0,$description = '',$Icon){
        $this->auth($token);
        //如果id为空
        if( empty($id) ){
            $this->err_msg(-1003,'分类ID不能为空！');
        }
        //如果分类名为空
        elseif( empty($name) ){
            $this->err_msg(-1004,'分类名称不能为空！');
        }
        //更新数据库
        else{
            $data = [
                'name'          =>  strip_tags($name),
                'up_time'       =>  time(),
                'weight'        =>  $weight,
                'property'      =>  $property,
                'description'   =>  strip_tags($description),
                'Icon'   =>  $Icon
            ];
            $re = $this->db->update('on_categorys',$data,[ 'id' => $id]);
            //var_dump( $this->db->log() );
            //获取影响行数
            $row = $re->rowCount();
            if($row) {
                $data = [
                    'code'  =>  0,
                    'msg'   =>  'successful',
                    'Icon'  =>  $Icon
                ];
                exit(json_encode($data));
            }
            else{
                $this->err_msg(-1005,'分类名称已存在！');
            }
        }
    }

//删除分类目录
public function del_category($token,$id,$batch,$force) {
        //验证授权
        $this->auth($token);
        //如果id为空
        if( empty($id)){$this->err_msg(-1003,'分类ID不能为空！');}
        
  if (empty($batch)||$batch=='0'){
        //如果分类目录下存在数据
        $count = $this->db->count("on_links", ["fid" => $id]);
        //如果分类目录下存在数据，则不允许删除
        if($count > 0) {
            $this->err_msg(-1006,'分类目录下存在数据,不允许删除!');
        }
        else{
            $data = $this->db->delete('on_categorys',[ 'id' => $id] );
            $row = $data->rowCount();//返回影响行数
            if($row) { $this-> msg(0,'successful');}
            else{$this->err_msg(-1007,'分类删除失败！');}
        }}
  elseif($batch=='1'){
    $idgroup=explode(",",$id);//分割文本
$res='<table class="layui-table" lay-even><colgroup><col width="55"><col width="200"><col></colgroup><thead><tr><th>ID</th><th>分类名称</th><th>状态信息</th></tr></thead><tbody>';//表头,直接写好让前端js执行!
    foreach($idgroup as $_id){
        $count = $this->db->count("on_links", ["fid" => $_id]);
        $name =geticon($this->db->get("on_categorys","Icon",["id"=>$_id])).$this->db->get("on_categorys","name",["id"=>$_id]);
        if ($count > 0 && $force =='1'){ //分类有下有数据,但要求强制删除时先删除分类ID相符的链接!
            $data = $this->db->delete('on_links',[ 'fid' => $_id] );
            if ($count =$data->rowCount()){
                $res=$res.'<tr><td>'.$_id.'</td><td>'.$name.'</td><td>强行删除:'.$data->rowCount().'条链接,已删除!</td></tr>';
                $this->db->delete('on_categorys',[ 'id' => $_id] );
            }else{
                $res=$res.'<tr><td>'.$_id.'</td><td>'.$name.'</td><td>分类下存在:'.$count.'条链接,强行删除:'.$data->rowCount().'条链接,删除失败!</td></tr>';
            }
        }
        elseif($count > 0) { //分类下有数据
           $res=$res.'<tr><td>'.$_id.'</td><td>'.$name.'</td><td>分类目录下存在'.$count.'条数据,删除失败!</td></tr>';
        }
        else{
            $data = $this->db->delete('on_categorys',[ 'id' => $_id] );
            $row = $data->rowCount();//返回影响行数
            if($row) { $res=$res.'<tr><td>'.$_id.'</td><td>'.$name.'</td><td>已删除!</td></tr>';}
            else{$res=$res.'<tr><td>'.$_id.'</td><td>'.$name.'</td><td>删除失败!</td></tr>';}
        }
    }
   exit(json_encode(['code'  =>  0,'msg'   =>  'successful','res'  =>  $res.'</tbody></table>']));
  }
}

//修改单元格
public function edit_danyuan($token,$id,$field,$value,$form){
$this->auth($token);
if(empty($id)){$this->err_msg(-1003,'ID不能为空');}
elseif($field ==='weight' && !is_numeric($value)){$this->err_msg(-1111,'修改失败:权重只能为数字!');}
elseif(($field ==='weight' || $field ==='name' || $field ==='description' || $field ==='title' ) && ($form ==='on_categorys' || $form ==='on_links') ){
     $t =time();
     $re = $this->db->update($form,[$field => $value,'up_time' =>$t],['id' => $id]);
     $row = $re->rowCount();
     if($row){  exit(json_encode(['code'=>0,'msg'=>'successful','t'=>$t]));}
     else{$this->err_msg(-1111,'修改失败!');}
}
$this->err_msg(-1003,'参数错误,请尝试清理浏览器缓存!若不能解决请联系管理员!');
}   
//修改连接分类
public function Mobile_class($token,$lid,$cid){
$this->auth($token);
if($lid =='' || empty($cid)){$this->err_msg(-1003,'ID不能为空');}
$idgroup=explode(",",$lid);//分割文本
//最好在加个检查,重新生成id组防止有错误,正常js提交的肯定是没问题得,但API操作就不好说了
$sql= "UPDATE on_links SET fid = ".$cid." where id in(".$lid.");";
$data =$this->db->query($sql);
$row = $data->rowCount();//返回影响行数
if($row){  
    $this->msg(0,'successful!');
}
else{
    $this->err_msg(-1111,'修改失败!');
}}
 
//提权和置顶
public function edit_tiquan($token,$id,$value,$form){
$this->auth($token);
if($id =='' || $value =='' || $form ==''){$this->err_msg(-1003,'存在空的参数!');}
elseif(($value ==='提权' || $value ==='置顶'  ) && ($form ==='on_categorys' || $form ==='on_links') ){
     $idgroup=explode(",",$id);//分割文本
     $t =time();
     $max = $this->db->max($form, 'weight');//取权重列最大值
     $fail=0;
     foreach($idgroup as $_id){
         $max++;
         $re = $this->db->update($form, ['weight'=> $max],[ 'id' => $_id]);
         $row = $re->rowCount();
         if(!$row){$fail++;}//失败计数
     }
     if(!$fail){exit(json_encode(['code'=>0,'msg'=>'successful','t'=>$t]));}
     else{$this->err_msg(-1111,'有'.$fail.'条链接提权失败!');}
}
$this->err_msg(-1003,'参数错误,请尝试清理浏览器缓存!若不能解决请联系管理员!');
} 


//返回错误（json）
protected function err_msg($code,$err_msg){
    $data = ['code'      =>  $code,'err_msg'   =>  $err_msg];
    //返回json类型
    header('Content-Type:application/json; charset=utf-8');
    exit(json_encode($data));
}
//返回成功（json）
protected function msg($code,$msg){
    $data = ['code'      =>  $code,'msg'   =>  $msg];
    //返回json类型
    header('Content-Type:application/json; charset=utf-8');
    exit(json_encode($data));
}
//计算Key
protected function Getkey($user,$pass,$Expire){
    $key = md5($user.$pass.$Expire.$_SERVER['HTTP_USER_AGENT'].'9VKT9Kwh');
    return $key;
}

    //验证方法
    protected function auth($token){
        global $username,$password,$ApiToken,$u;
        //计算正确的token：用户名 + TOKEN
        $token_yes = $ApiToken;
        //如果token为空，则验证cookie
        if(empty($token)) {
            if($_COOKIE[$u.'_key'] =='' or $u ==''){
                $this->err_msg(-1002,'授权失败!');
                return false;
            }
            else if( !$this->is_login() ) {
                $this->err_msg(-1002,'Key授权失败!');
                return false;
            }else{return true;}
        }
        else if($token != $token_yes){
            $this->err_msg(-1002,'Token授权失败!');
            return false;
        }
        else{
            return true;
        }
    }

    //添加链接
    public function add_link($token,$fid,$title,$url,$description = '',$weight = 0,$property = 0){
        $this->auth($token);
        $fid = intval($fid);
        //检测链接是否合法
        $this->check_link($fid,$title,$url);
        //合并数据
        $data = [
            'fid'           =>  $fid,
            'title'         =>  $title,
            'url'           =>  $url,
            'description'   =>  $description,
            'add_time'      =>  time(),
            'weight'        =>  $weight,
            'property'      =>  $property
        ];
        //插入数据库
        $re = $this->db->insert('on_links',$data);
        //返回影响行数
        $row = $re->rowCount();
        //如果为真
        if( $row ){
            $id = $this->db->id();
            $data = [
                'code'      =>  0,
                'id'        =>  $id
            ];
            exit(json_encode($data));
        }
        //如果插入失败
        else{
            $this->err_msg(-1011,'URL已经存在！');
        }
    }

    //批量导入链接
    public function imp_link($token,$filename,$fid,$property = 0){
        $this->auth($token);
        //检查文件是否存在
        if ( !file_exists($filename) ) {
            $this->err_msg(-1016,'File does not exist!');
        }
        //解析HTML数据
        $content = file_get_contents($filename);

        $pattern = "/<A.*<\/A>/i";

        preg_match_all($pattern,$content,$arr);
        //失败次数
        $fail = 0;
        //成功次数
        $success = 0;
        //总数
        $total = count($arr[0]);
        foreach( $arr[0] as $link )
        {
            $pattern = "/http.*\"? ADD_DATE/i";
            preg_match($pattern,$link,$urls);
            $url = str_replace('" ADD_DATE','',$urls[0]);
            $pattern = "/>.*<\/a>$/i";
            preg_match($pattern,$link,$titles);
            
            $title = str_replace('>','',$titles[0]);
            $title = str_replace('</A','',$title);
            
            //如果标题或者链接为空，则不导入
            if( ($title == '') || ($url == '') ) {
                $fail++;
                continue;
            }
            $data = [
                'fid'           =>  $fid,
                'description'   =>  '',
                'add_time'      =>  time(),
                'weight'        =>  0,
                'property'      =>  $property
            ];
            $data['title'] = $title;
            $data['url']    = $url;
            
            //插入数据库
            $re = $this->db->insert('on_links',$data);
            //返回影响行数
            $row = $re->rowCount();
            //如果为真
            if( $row ){
                $id = $this->db->id();
                $data = [
                    'code'      =>  0,
                    'id'        =>  $id
                ];
                $success++;
                
            }
            //如果插入失败
            else{
                $fail++;
            }
        }
        //删除书签
        unlink($filename);
        $data = [
            'code'      =>  0,
            'msg'       =>  '总数：'.$total.' 成功：'.$success.' 失败：'.$fail
            ,'f' => $f
        ];
        exit(json_encode($data));
    }
    /**
     * 书签上传
     * type:上传类型，默认为上传书签，后续类型保留使用
     */
    public function upload($token,$type){
        $this->auth($token);
        if ($_FILES["file"]["error"] > 0)
        {
            $this->err_msg(-1015,'File upload failed!');
        }
        else
        {
            $filename = $_FILES["file"]["name"];
            //获取文件后缀
            $suffix = explode('.',$filename);
            $suffix = strtolower(end($suffix));
            
            //临时文件位置
            $temp = $_FILES["file"]["tmp_name"];
            if( $suffix != 'html' ) {
                //删除临时文件
                unlink($filename);
                $this->err_msg(-1014,'不支持的文件后缀名！');
            }
            
            if( copy($temp,'data/'.$filename) ) {
                $data = [
                    'code'      =>  0,
                    'file_name' =>  'data/'.$filename
                ];
                exit(json_encode($data));
            }
        }
    }

    //修改链接
    public function edit_link($token,$id,$fid,$title,$url,$description = '',$weight = 0,$property = 0){
        $this->auth($token);
        $fid = intval($fid);
        //检测链接是否合法
        $this->check_link($fid,$title,$url);
        //查询ID是否存在
        $count = $this->db->count('on_links',[ 'id' => $id]);
        //如果id不存在
        if( (empty($id)) || ($count == false) ) {
            $this->err_msg(-1012,'链接ID不存在！');
        }
        //合并数据
        $data = [
            'fid'           =>  $fid,
            'title'         =>  $title,
            'url'           =>  $url,
            'description'   =>  $description,
            'up_time'       =>  time(),
            'weight'        =>  $weight,
            'property'      =>  $property
        ];
        //插入数据库
        $re = $this->db->update('on_links',$data,[ 'id' => $id]);
        //返回影响行数
        $row = $re->rowCount();
        //如果为真
        if( $row ){
            $id = $this->db->id();
            $data = [
                'code'      =>  0,
                'msg'        =>  'successful'
            ];
            exit(json_encode($data));
        }
        //如果插入失败
        else{
            $this->err_msg(-1011,'URL已经存在！');
        }
    }
    
    //删除链接
public function del_link($token,$id,$batch) {
	//验证token是否合法
	$this->auth($token);
	//如果id为空
	if( empty($id)) {
		$this->err_msg(-1003,'链接ID不能为空！');
	}
	if (empty($batch)||$batch=='0') {
		//查询ID是否存在
		$count = $this->db->count('on_links',[ 'id' => $id]);
		//如果id不存在
		if( (empty($id)) || ($count == false) ) {
			$this->err_msg(-1010,'链接ID不存在！');
		} else {
			$re = $this->db->delete('on_links',[ 'id' =>  $id] );
			if($re) {
				exit(json_encode(['code'=>0,'msg'=>'successful']));
			} else {
				$this->err_msg(-1010,'链接ID不存在！');
			}
		}
	}elseif($batch=='1'){
	     $idgroup=explode(",",$id);//分割文本
	     foreach($idgroup as $_id){
	         $this->db->delete('on_links',[ 'id' =>  $_id] );
	     }

	    exit(json_encode(['code'=>0,'msg'=>'successful']));
	}
}

    //修改连接私有属性
    public function edit_property($token,$id,$property,$source){
        //验证token是否合法
        $this->auth($token);
        //查询ID是否存在
        $count = $this->db->count($source,[ 'id' => $id]);
        //如果id不存在
        if( (empty($id)) || ($count == false) ) {
            $this->err_msg(-1010,'.链接ID不存在！');
        }
        else{
            $data = [
            'property'      =>  $property
        ];
        //插入数据库on_categorys
        $re = $this->db->update($source,$data,[ 'id' => $id]);
            //$re = $this->db->delete('on_links',[ 'id' =>  $id] );
            if($re) {
                $data = [
                    'code'  =>  0,
                    'msg'   =>  $property,
                    'source' => $source
                ];
                exit(json_encode($data));
            }
            else{
                $this->err_msg(-1010,'链接ID不存在.！');
            }
        }
    }

    //验证链接合法性
    protected function check_link($fid,$title,$url){
        //如果父及（分类）ID不存在
        if( empty($fid )) {
            $this->err_msg(-1007,'类别id(fid)不存在！');
        }
        //如果父及ID不存在数据库中
        //验证分类目录是否存在
        $count = $this->db->count("on_categorys", [
            "id" => $fid
        ]);
        if ( empty($count) ){
            $this->err_msg(-1007,'类别不存在！');
        }
        //如果链接标题为空
        if( empty($title) ){
            $this->err_msg(-1008,'标题不能为空！');
        }
        //链接不能为空
        if( empty($url) ){
            $this->err_msg(-1009,'URL不能为空！');
        }
        //链接不合法
        if( !filter_var($url, FILTER_VALIDATE_URL) ) {
            $this->err_msg(-1010,'URL无效！');
        }
        return true;
    }
    
    //查询分类目录
    public function category_list($page,$limit,$token = '' ,$query =''){
        $offset = ($page - 1) * $limit;
        $q =$query;
        //如果成功登录，则查询所有,否则就查询公开分类
        
        
        if(  $this->auth($token)){
            $sql = "SELECT *,(SELECT count(*) FROM on_links  WHERE fid = on_categorys.id ) AS count  FROM on_categorys  WHERE name LIKE '%".$q."%' or description LIKE '%".$q."%' ORDER BY weight DESC,id DESC LIMIT {$limit} OFFSET {$offset}";
            $count = $this->db->count('on_categorys','*',["OR" =>['name[~]'=>$q,'description[~]'=>$q]]);//统计总数
        }

        //原生查询
        $datas = $this->db->query($sql)->fetchAll();
        $datas = [
            'code'      =>  0,
            'msg'       =>  '',
            'count'     =>  $count,
            'data'      =>  $datas,
            'query' =>$q
        ];
        exit(json_encode($datas));
    }

    //查询链接
    public function link_list($page,$limit,$token = '',$query ='',$fid = 0){
        $offset = ($page - 1) * $limit;
        $q =$query;
        //如果成功登录，自动识别Cookie或token
        if( $this->auth($token)){
            //统计总数
            if ($fid ==0 ){
                $cn ='';
                $count = $this->db->count('on_links','*',["OR" =>['title[~]'=>$q,'description[~]'=>$q,'url[~]'=>$q]]);
            }else{ //'fid' >= $fid 
                $cn =' And fid ='.$fid;
                $count = $this->db->count('on_links','*',[ "AND"=>["OR"=>['title[~]'=>$q,'description[~]'=>$q,'url[~]'=>$q],"fid"=>$fid]]);
            }
            
            $sql = "SELECT *,(SELECT name FROM on_categorys WHERE id = on_links.fid) AS category_name FROM on_links WHERE (title LIKE '%".$q."%' or description LIKE '%".$q."%' or url LIKE '%".$q."%')".$cn." ORDER BY weight DESC,id DESC LIMIT {$limit} OFFSET {$offset}";
        }
        
       
        //原生查询
        $datas = $this->db->query($sql)->fetchAll();
        $datas = [
            'code'      =>  0,
            'msg'       =>  '',
            'count'     =>  $count,
            'data'      =>  $datas,
            'sql' =>$sql
        ];
        exit(json_encode($datas));
    }

    //验证是否登录
    protected function is_login(){
    global $u,$username,$password;
    $key = Getkey($username,$password,$_COOKIE[$u.'_Expire']);
    //获取session
    $session = $_COOKIE[$u.'_key'];
    //如果已经成功登录
    if($session == $key) {
        return true;
    }
    else{
        return false;
    }
}

    //获取链接信息
    public function get_link_info($token,$url){
        $this->auth($token);
        //检查链接是否合法
        //链接不合法
        if( !filter_var($url, FILTER_VALIDATE_URL) ) {
            $this->err_msg(-1010,'URL is not valid!');
        }
        //获取网站标题
        $c = curl_init(); 
        curl_setopt($c, CURLOPT_URL, $url); 
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($c, CURLOPT_SSL_VERIFYHOST, false);
        //设置超时时间
        curl_setopt($c , CURLOPT_TIMEOUT, 10);
        $data = curl_exec($c); 
        curl_close($c); 
        $pos = strpos($data,'utf-8'); 
        if($pos===false){$data = iconv("gbk","utf-8",$data);} 
        preg_match("/<title>(.*)<\/title>/i",$data, $title); 
        
        $link['title'] =  $title[1]; 

        //获取网站描述
        $tags = get_meta_tags($url);
        $link['description'] = $tags['description'];
        
        $data = [
            'code'      =>  0,
            'data'      =>  $link
        ];
        exit(json_encode($data));
    }
//API库End.
}

