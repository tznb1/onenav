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
            $this->msg(-1102,'主题风格设置错误！');
        }else if(!empty($TEMPLATE)  &&  !file_exists('./templates/'.getSubstrRight($TEMPLATE,'|').'/index.php')){
            $this->msg(-1103,'主题模板不存在,请核对后再试！');
        }
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
        if ($TEMPLATE !="") {Writeconfig('Style',getSubstrLeft($TEMPLATE,'|'));Writeconfig('Theme',getSubstrRight($TEMPLATE,'|'));  }
        exit(json_encode($data = ['code'  =>  0,'msg'   =>  'successful']));
    }
  
//账号设置
   public function edit_user($token,$Email,$NewToken,$user,$pass,$newpassword){
        global $username,$password,$RegTime,$udb;
        $this->auth($token);
        //更新数据库
        if(md5($pass.$RegTime) != $password){
            $this->msg(-1102,'密码错误,请核对后再试！');
        }else if(!empty($user)  && !check_user($user)){
            $this->msg(-1103,'账号只能使用英文和数字!');
        }else if(!empty($newpassword)  &&  strlen($newpassword)!=32){
            $this->msg(-1103,'密码异常,正常情况是32位的md5！');
        }else if(md5($newpassword.$RegTime)==$password){
            $this->msg(-1103,'新密码不能和原密码一样!');
        }else if(!empty($NewToken)  && strlen($NewToken)<32){
            $this->msg(-1103,'因安全问题,Token长度不能小于32位！');
        }
        $logout=0;
        if ($Email !="") {Writeconfig('Email',$Email);}
        if ($NewToken !="") {Writeconfig('Token',$NewToken);}
        //if ($user !="" && $user !=$username) {Writeconfig('user',$user);$logout=1;}
        if ($newpassword != 'd41d8cd98f00b204e9800998ecf8427e' && $newpassword !="") {
            Writeconfig('password',md5($newpassword.$RegTime));
            $logout=1;
            $Re = $udb->update('user',['Pass'=>md5($newpassword.$RegTime)],['User' => $username]);
            if($Re->rowCount() == 1){
                msgA(['code'=>0,'msg'=>'successful','logout'=>$logout,'u'=> $username]);
            }else{msgA(['code'=>-1111,'msg'=>'修改失败']);}
        }
    msg(0,'successful');
}
//root全局配置修改
   public function edit_root($token,$DUser,$Reg,$Register,$login,$libs,$visit,$IconAPI){
        global $udb,$u;
        $this->auth($token);
        if($udb->get("user","Level",["User"=>$u]) != 999){
            $this->msg(-1102,'您没有权限修改全局配置!');
        }elseif($udb->count("user",["User"=>$DUser])  == 0 || $DUser==''){
            $this->msg(-1102,'默认账号'.$DUser.'不存在,请检查!');
        }elseif($Reg != 0 && $Reg != 1 && $Reg !=''){
            $this->msg(-1103,'注册用户参数错误');
        }elseif($Register == $login){
            $this->msg(-1103,'注册入口名不能和登录入口名相同!');
        }elseif($visit != 0 && $visit != 1 && $visit !=''){
            $this->msg(-1103,'访问控制参数错误!');
        }elseif(!is_numeric($IconAPI)){
            $this->msg(-1103,'图标API接口参数错误!');
        }
        
        if(!empty($DUser)){Writeconfigd($udb,'config','DUser',$DUser);}
        Writeconfigd($udb,'config','Reg',$Reg);
        if(!empty($Register)){Writeconfigd($udb,'config','Register',$Register);}
        if(!empty($login)){Writeconfigd($udb,'config','Login',$login);}
        if(!empty($libs)){Writeconfigd($udb,'config','Libs',$libs);}
        Writeconfigd($udb,'config','Visit',$visit);
        Writeconfigd($udb,'config','IconAPI',$IconAPI);
    msg(0,'successful');
}
    //创建分类目录
    public function add_category($token,$name,$property = 0,$weight = 0,$description = '',$Icon){
        $this->auth($token);
        if( empty($name) ){
            $this->msg(-1004,'分类名称不能为空！');
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
            $this->msg(-1000,'分类已经存在！');
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
            $this->msg(-1003,'分类ID不能为空！');
        }
        //如果分类名为空
        elseif( empty($name) ){
            $this->msg(-1004,'分类名称不能为空！');
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
                $this->msg(-1005,'分类名称已存在！');
            }
        }
    }

//删除分类目录
public function del_category($token,$id,$batch,$force) {
        //验证授权
        $this->auth($token);
        //如果id为空
        if( empty($id)){$this->msg(-1003,'分类ID不能为空！');}
        
  if (empty($batch)||$batch=='0'){
        //如果分类目录下存在数据
        $count = $this->db->count("on_links", ["fid" => $id]);
        //如果分类目录下存在数据，则不允许删除
        if($count > 0) {
            $this->msg(-1006,'分类目录下存在数据,不允许删除!');
        }
        else{
            $data = $this->db->delete('on_categorys',[ 'id' => $id] );
            $row = $data->rowCount();//返回影响行数
            if($row) { $this-> msg(0,'successful');}
            else{$this->msg(-1007,'分类删除失败！');}
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
if(empty($id)){$this->msg(-1003,'ID不能为空');}
elseif($field ==='weight' && !is_numeric($value)){$this->msg(-1111,'修改失败:权重只能为数字!');}
elseif(($field ==='weight' || $field ==='name' || $field ==='description' || $field ==='title' ) && ($form ==='on_categorys' || $form ==='on_links') ){
     $t =time();
     $re = $this->db->update($form,[$field => $value,'up_time' =>$t],['id' => $id]);
     $row = $re->rowCount();
     if($row){  exit(json_encode(['code'=>0,'msg'=>'successful','t'=>$t]));}
     else{$this->msg(-1111,'修改失败!');}
}
$this->msg(-1003,'参数错误,请尝试清理浏览器缓存!若不能解决请联系管理员!');
}   
//修改连接分类
public function Mobile_class($token,$lid,$cid){
$this->auth($token);
if($lid =='' || empty($cid)){$this->msg(-1003,'ID不能为空');}
$idgroup=explode(",",$lid);//分割文本
//最好在加个检查,重新生成id组防止有错误,正常js提交的肯定是没问题得,但API操作就不好说了
$sql= "UPDATE on_links SET fid = ".$cid." where id in(".$lid.");";
$data =$this->db->query($sql);
$row = $data->rowCount();//返回影响行数
if($row){  
    $this->msg(0,'successful!');
}
else{
    $this->msg(-1111,'修改失败!');
}}
 
//提权和置顶
public function edit_tiquan($token,$id,$value,$form){
$this->auth($token);
if($id =='' || $value =='' || $form ==''){$this->msg(-1003,'存在空的参数!');}
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
     else{$this->msg(-1111,'有'.$fail.'条链接提权失败!');}
}
$this->msg(-1003,'参数错误,请尝试清理浏览器缓存!若不能解决请联系管理员!');
} 

//返回（json）
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
        $token_yes = $ApiToken;
        //如果token为空，则验证cookie
        if(empty($token)) {
            if($_COOKIE[$u.'_key'] =='' or $u ==''){
                $this->msg(-1002,'授权失败!');
                return false;
            }
            else if( !$this->is_login() ) {
                $this->msg(-1002,'Key授权失败!');
                return false;
            }else{return true;}
        }
        else if($token != $token_yes){
            $this->msg(-1002,'Token授权失败!');
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
            $this->msg(-1011,'URL已经存在！');
        }
    }

    //批量导入链接
    public function imp_link($token,$filename,$fid,$property = 0,$all =0){
        $this->auth($token);
        //检查文件是否存在
        if ( !file_exists($filename) ) {
            $this->msg(-1016,'文件不存在!');
        }elseif(substr($filename,0, 12)!='data/upload/'){
            //检测路径前缀,不限制的话可以可以导入data下其他用户的数据库!存在安全隐患!
            $this->msg(-1016,'路径非法!');
        }
        $res='<table class="layui-table" lay-even><colgroup><col width="200"><col width="250"><col></colgroup><thead><tr><th>标题</th><th>URL</th><th>失败原因</th></tr></thead><tbody>';
        //落幕导入段-检测是否为数据库,是则导入! 
        if(strtolower(end(explode('.',$filename)))=='db3'){
            $tempdb = new Medoo\Medoo(['database_type' => 'sqlite', 'database_file' => $filename]);
            $sql = "SELECT * FROM on_categorys";
            $categorys = $tempdb->select('on_categorys','*');
            $data = $tempdb->query("select * from sqlite_master where name = 'on_categorys' and sql like '%Icon%'")->fetchAll();
            $icon = count($data)==0 ? false:true ; //判断有没有图标字段!如果没有则尝试从名称取图标写到专用字段
            
            $fail =0;$success=0;
            foreach ($categorys as $category) {
                $name = strip_tags($category['name']);//取分类名
                if(($name=='')){continue;}//如果名称为空则跳到循环尾 (名称为空)
                $cat_name = $this -> db->get('on_categorys','*',[ 'name' =>  $name ]); //取当前库同名ID
                $cat_id = $this -> db->get('on_categorys','*',[ 'id' =>  $category['id'] ]); //取当前库同名ID
                //如果没有图标字段,则说明是原版数据库!尝试正则提取是否有图标,有的话就写到写到数据库!
                //注:原版可以写N个图标,但我这设计只能一个,所以只提取第一个图标!
                if (!$icon && preg_match('/<i class="fa (.+)"><\/i>/i',$category['name'],$matches) != 0){
                    $ico=$matches[1];
                }else{
                    $ico=$category['Icon'];
                }
                
                //如果分类名相同就不创建新分类,而是把全部连接导入到同名分类下!
                if((strip_tags($cat_name['name'])==$name)){
                    $tempdb->update('on_categorys',['id' => $cat_name['id'] ],[ 'id' => $category['id']]); //修改分类ID,方便后面导入连接!
                    $tempdb->update('on_links',['fid' => $cat_name['id'] ],[ 'fid' => $category['id']]); //修改链接分类ID,方便后面导入连接!
                }
                else{
                    $data = [
                        'name'          =>  strip_tags($name),
                        'add_time'      =>  $all == 1 ? $category['add_time']:time(),
                        'up_time'       =>  $all == 1 ? $category['up_time']:null,
                        'weight'        =>  $all == 1 ? $category['weight']:0,
                        'property'      =>  $category['property'],
                        'description'   =>  $category['description'],
                        'Icon'          =>  $ico
                    ];
                    $this->db->insert("on_categorys",$data);
                    $id = $this->db->id();
                    if(!empty($id)){
                    $tempdb->update('on_categorys',['id' => $id ],[ 'id' => $category['id']]); //修改分类ID,方便后面导入连接!
                    $tempdb->update('on_links',['fid' => $id ],[ 'fid' => $category['id']]); //修改链接分类ID,方便后面导入连接!
                    }
                }

            }
            //导入链接
            $links = $tempdb->select('on_links','*');
            $total = count($links);
            foreach ($links as $link) {
                if( ($link['title'] == '') || ($link['url'] == '') ) {
                $fail++;
                $res=$res.'<tr><td>'.$link['title'].'</td><td>'.$link['url'].'</td><td>标题或URL为空</td></tr>';
                continue;
                }
                $data = [
                'fid'           =>  $link['fid'],
                'title'         =>  $link['title'],
                'url'           =>  $link['url'],
                'description'   =>  $link['description'],
                'add_time'      =>  $all == 1 ? $link['add_time']:time(),
                'up_time'       =>  $all == 1 ? $link['up_time']:null,
                'weight'        =>  $all == 1 ? $link['weight']:0,
                'property'      =>  $link['property'],
                'click'         =>  $all == 1 ? $link['click']:0
                ];
                $re = $this->db->insert('on_links',$data);
                $row = $re->rowCount();
                if( $row ){$success++;}else{$fail++;$res=$res.'<tr><td>'.$link['title'].'</td><td>'.$link['url'].'</td><td>URL重复</td></tr>';}
            }
          //删除书签
        unlink($filename);
        $data=['code'=>0,'msg'=>'总数：'.$total.' 成功：'.$success.' 失败：'.$fail,'res'  =>  $res.'</tbody></table>','fail'=>$fail];
        exit(json_encode($data));  
        }
        //落幕导入End
        //解析HTML数据
        if (empty($fid)) {
            $this->msg(-1016,'上传html格式时所属分类不能为空!');
        }
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
                $res=$res.'<tr><td>'.$title.'</td><td>'.$url.'</td><td>标题或URL为空</td></tr>';
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
                $res=$res.'<tr><td>'.$title.'</td><td>'.$url.'</td><td>URL重复</td></tr>';
            }
        }
        //删除书签
        unlink($filename);
        $data = [
            'code'      =>  0,
            'msg'       =>  '总数：'.$total.' 成功：'.$success.' 失败：'.$fail,
            'res'  =>  $res.'</tbody></table>',
            'fail'=>$fail
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
            $this->msg(-1015,'文件上传失败!');
        }
        else
        {
            $filename = $_FILES["file"]["name"];
            //获取文件后缀
            $suffix = explode('.',$filename);
            $suffix = strtolower(end($suffix));
            
            //临时文件位置
            $temp = $_FILES["file"]["tmp_name"];
            if( $suffix != 'html'  && $suffix != 'db3') {
                //删除临时文件
                unlink($filename);
                $this->msg(-1014,'不支持的文件后缀名！');
            }
            if(!is_dir('data/upload')){mkdir('data/upload',0755,true);}
            if(copy($temp,'data/upload/'.$filename) ) {
                $data = [
                    'code'      =>  0,
                    'file_name' =>  'data/upload/'.$filename
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
            $this->msg(-1012,'链接ID不存在！');
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
            $this->msg(-1011,'URL已经存在！');
        }
    }
    
    //删除链接
public function del_link($token,$id,$batch) {
	//验证token是否合法
	$this->auth($token);
	//如果id为空
	if( empty($id)) {
		$this->msg(-1003,'链接ID不能为空！');
	}
	if (empty($batch)||$batch=='0') {
		//查询ID是否存在
		$count = $this->db->count('on_links',[ 'id' => $id]);
		//如果id不存在
		if( (empty($id)) || ($count == false) ) {
			$this->msg(-1010,'链接ID不存在！');
		} else {
			$re = $this->db->delete('on_links',[ 'id' =>  $id] );
			if($re) {
				exit(json_encode(['code'=>0,'msg'=>'successful']));
			} else {
				$this->msg(-1010,'链接ID不存在！');
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
            $this->msg(-1010,'.链接ID不存在！');
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
                $this->msg(-1010,'链接ID不存在.！');
            }
        }
    }

    //验证链接合法性
    protected function check_link($fid,$title,$url){
        //如果父及（分类）ID不存在
        if( empty($fid )) {
            $this->msg(-1007,'类别id(fid)不存在！');
        }
        //如果父及ID不存在数据库中
        //验证分类目录是否存在
        $count = $this->db->count("on_categorys", [
            "id" => $fid
        ]);
        if ( empty($count) ){
            $this->msg(-1007,'类别不存在！');
        }
        //如果链接标题为空
        if( empty($title) ){
            $this->msg(-1008,'标题不能为空！');
        }
        //链接不能为空
        if( empty($url) ){
            $this->msg(-1009,'URL不能为空！');
        }
        //链接不合法
        if( !filter_var($url, FILTER_VALIDATE_URL) ) {
            $this->msg(-1010,'URL无效！');
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

    //查询用户
    public function user_list($page,$limit,$token = '',$query ='',$fid = 0){
       global $u;
        $this->auth($token);
        $offset = ($page - 1) * $limit;
        $q =$query;
        //如果成功登录，自动识别Cookie或token
        if( $this->auth($token)){
        
        //原生查询
        $udb = new Medoo\Medoo(['database_type'=>'sqlite','database_file'=>'./data/lm.user.db3']);
        if($udb->get("user","Level",["User"=>$u]) != 999){msg(-1111,'您没有权限使用此功能');}
        $sql ="SELECT * FROM user WHERE User LIKE '%".$q."%' or Email LIKE '%".$q."%' or RegIP LIKE '%".$q."%' ORDER BY ID ASC LIMIT {$limit} OFFSET {$offset}";
        $count =  $udb->count('user','*',["OR" =>['User[~]'=>$q,'Email[~]'=>$q,'RegIP[~]'=>$q]]);
        
        $datas = $udb ->query($sql)->fetchAll();
        $datas = [
            'code'      =>  0,
            'msg'       =>  '',
            'count'     =>  $count,
            'data'      =>  $datas,
            
        ];
        exit(json_encode($datas));
    }}
   //删除用户
    public function user_list_del($token = '',$id = ''){
         global $u;
         $this->auth($token);
         $udb = new Medoo\Medoo(['database_type'=>'sqlite','database_file'=>'./data/lm.user.db3']);
         if($udb->get("user","Level",["User"=>$u]) != 999){msg(-1111,'您没有权限使用此功能');}
         $idgroup=explode(",",$id);//分割文本
         $res='<table class="layui-table" lay-even><colgroup><col width="55"><col width="150"><col></colgroup><thead><tr><th>ID</th><th>账号</th><th>状态信息</th></tr></thead><tbody>';//表头,直接写好让前端js执行!
	     foreach($idgroup as $_id){
	         $ud = $udb->get("user","*",["ID"=>$_id]);
	         $Path= './data/'.$ud['SQLite3'];
	         $k='';
	         if($ud["Level"]==999){
	         $res=$res.'<tr><td>'.$_id.'</td><td>'.$ud["User"].'</td><td>不允许删除管理员账号,请先降级!</td></tr>';
	         continue;}
	         if($ud["User"]==''){$k='账号不存在';$ud["User"]='账号不存在';}
	         elseif(file_exists($Path)){if(unlink($Path)){$k='库已删除!';}else{$k='库删除失败!';};}
	         else{$k='库不存在!';}
	         
	         $data =$udb->delete('user',['ID'=>$_id]);
	         if($data->rowCount() !=0){
	             $res=$res.'<tr><td>'.$_id.'</td><td>'.$ud["User"].'</td><td>表已删除,'.$k.'</td></tr>';
	         }else{
	             $res=$res.'<tr><td>'.$_id.'</td><td>'.$ud["User"].'</td><td>表删除失败,'.$k.'</td></tr>';
	         }
 
	     }
    msgA(['code'  =>  0,'msg'   =>  'successful','res'  =>  $res.'</tbody></table>']);
    }

   //管理员免密登录用户后台
    public function user_list_login($token = '',$id = ''){
        global $u;
        $this->auth($token);
        $udb = new Medoo\Medoo(['database_type'=>'sqlite','database_file'=>'./data/lm.user.db3']);
        $ud = $udb->get("user","*",["ID"=>$id]);
        if($ud["User"]==''){msg(-1111,'没有找到用户');}
        if($udb->get("user","Level",["User"=>$u]) != 999){msg(-1111,'您没有权限使用此功能');}
        $Expire= time()+1 * 60 * 60;;
        $key =Getkey($ud["User"],$ud["Pass"],$Expire);
        if(Getkey($ud["User"],$ud["Pass"],$_COOKIE[$ud["User"].'_Expire'])==$_COOKIE[$ud["User"].'_key'] && $_COOKIE[$ud["User"].'_Expire']>time()){
            msg(0,'Cookie有效');
        }else{
        setcookie($ud["User"].'_key', $key, $Expire,"/");
        setcookie($ud["User"].'_Expire', $Expire, $Expire,"/");
        msg(0,'successful');}
    }

    //验证是否登录
    protected function is_login(){
    global $username,$password;
    $key = Getkey($username,$password,$_COOKIE[$username.'_Expire']);
    //获取session
    $session = $_COOKIE[$username.'_key'];
    //如果已经成功登录
    if($session == $key  && $_COOKIE[$username.'_Expire'] >time()) {
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
            $this->msg(-1010,'URL is not valid!');
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

