<?php 
include_once('header.php');
include_once('left.php'); 
if($udb->get("user","Level",["User"=>$u]) != 999){
    include_once('footer.php');
    exit;
}
$ICP    = UGet('ICP');
$footer = UGet('footer');
$footer = htmlspecialchars_decode(base64_decode($footer));
$Plug     = UGet('Plug');
$apply  = UGet('apply');
$Privacy = UGet('Privacy');
$iconUP = UGet('iconUP');
if (empty($subscribe['end_time'])) $subscribe['end_time'] = 0;

?>

<style type="text/css">
.layui-layout-admin .layui-body {top: 40px;}
</style>
<div class="layui-tab layui-tab-brief layui-body layui-row content-body" lay-filter="root" style="padding-bottom: 0px;">
<ul class="layui-tab-title">
 <li class="layui-this" lay-id="1">全局配置</li>
 <li lay-id="2">用户管理</li>
 <li lay-id="3">订阅管理</li>
</ul>
<div class="layui-tab-content" style="padding-bottom: 0px;">
<div class="layui-tab-item layui-show layui-form layui-form-pane"><!--全局配置--> 
<div class="layui-row content-body layui-show layui-form layui-form-pane" >
<div class="layui-col-lg12">
 <div class="layui-form-item">
    <div class="layui-inline">
      <label class="layui-form-label">默认用户</label>
      <div class="layui-input-inline">
        <input type="text" name="DUser" id="DUser" lay-verify="required" value = '<?php echo $Duser;?>' placeholder='admin'  autocomplete="off" class="layui-input">
      </div>
      <div class="layui-form-mid layui-word-aux">默认主页的账号,优先级:Get>Cookie/Host>默认用户>admin</div>
    </div>
 </div>
 <div class="layui-form-item">
    <div class="layui-inline">
      <label class="layui-form-label">注册用户</label>
     <div class="layui-input-inline">
      <select id="Reg" name="Reg"  >
        <option value="0" <?php if($reg==0){echo'selected=""';}?>>禁止注册</option>
        <option value="1" <?php if($reg==1){echo'selected=""';}?>>允许注册</option>
        <option value="2" <?php if($reg==2){echo'selected=""';}?>>邀请注册</option>
      </select>
      </div>
      <div class="layui-form-mid layui-word-aux">个人使用可以禁止注册哦! <?php if($reg==2) echo '<a href="./index.php?c=admin&page=YQReg&u='.$u.'" target="_blank">管理邀请</a>';?></div>
    </div>
 </div>
 <div class="layui-form-item">
    <div class="layui-inline">
      <label class="layui-form-label">注册入口</label>
      <div class="layui-input-inline">
        <input type="text" name="Register" lay-verify="required" value='<?php echo $Register;?>' placeholder='Register' autocomplete="off" class="layui-input">
      </div>
      <div class="layui-form-mid layui-word-aux">默认为Register,不想被随意注册时可以修改!</div>
    </div>
 </div>
 <div class="layui-form-item">
    <div class="layui-inline">
      <label class="layui-form-label">登录入口</label>
      <div class="layui-input-inline">
        <input type="text" name="login" lay-verify="required" value='<?php echo $login;?>' placeholder='login' autocomplete="off" class="layui-input">
      </div>
      <div class="layui-form-mid layui-word-aux">默认为login,修改可以防止被爆破,修改请记好入口名,否则无法登录后台!</div>
    </div>
 </div>
 
 
 <div class="layui-form-item">
    <div class="layui-inline">
      <label class="layui-form-label">静态路径</label>
      <div class="layui-input-inline">
    <input type="text" name="libs" id="libs" lay-verify="required" value = '<?php echo $libs; ?>' placeholder='./static'  autocomplete="off" class="layui-input">
      </div>
      <div class="layui-form-mid layui-word-aux">默认为./static 即本地服务器!建议使用CDN来提高加载速度!</div>
    </div>
 </div> 

 <div class="layui-form-item">
    <div class="layui-inline">
      <label class="layui-form-label">ICP备案号</label>
      <div class="layui-input-inline">
    <input type="text" name="ICP"   value = '<?php echo $ICP; ?>' placeholder='工信部ICP备案号'  autocomplete="off" class="layui-input">
      </div>
      <div class="layui-form-mid layui-word-aux">底部显示的备案号</div>
    </div>
 </div> 
<div class="layui-form-item">
    <div class="layui-inline">
      <label class="layui-form-label">自定义代码</label>
      <div class="layui-input-inline">
      <select id="visit" name="Diy"  >
        <option value="0" <?php if($Diy==0){echo'selected=""';}?>>禁止</option>
        <option value="1" <?php if($Diy==1){echo'selected=""';}?>>允许</option>
      </select>
      </div>
      <div class="layui-form-mid " style="color:#FF0000">是否允许普通用户使用自定义头部和底部代码,存在风险请慎用!管理员和防XSS脚本对此无效!</div>
    </div>
 </div> 
  <div class="layui-form-item">
    <div class="layui-inline">
      <label class="layui-form-label">访问控制</label>
      <div class="layui-input-inline">
      <select id="visit" name="visit"  >
        <option value="0" <?php if($Visit==0){echo'selected=""';}?>>禁止访问</option>
        <option value="1" <?php if($Visit==1){echo'selected=""';}?>>允许访问</option>
      </select>
      </div>
      <div class="layui-form-mid layui-word-aux">禁止访问时首页无法预览,链接无法跳转,普通用户无法登录后台,同时关闭注册!管理员账号不受影响!</div>
    </div>
 </div>
  <div class="layui-form-item">
    <div class="layui-inline">
      <label class="layui-form-label">防XSS脚本</label>
      <div class="layui-input-inline">
      <select id="XSS" name="XSS"  >
        <option value="0" <?php if($XSS==0){echo'selected=""';}?>>关闭</option>
        <option value="1" <?php if($XSS==1){echo'selected=""';}?>>开启</option>
      </select>
      </div>
      <div class="layui-form-mid layui-word-aux">拦截POST表单中的XSS恶意代码,提升网站安全性!(测试)</div>
    </div>
 </div> 
  <div class="layui-form-item">
    <div class="layui-inline">
      <label class="layui-form-label">防SQL注入</label>
      <div class="layui-input-inline">
      <select id="SQL" name="SQL"  >
        <option value="0" <?php if($SQL==0){echo'selected=""';}?>>关闭</option>
        <option value="1" <?php if($SQL==1){echo'selected=""';}?>>开启</option>
      </select>
      </div>
      <div class="layui-form-mid layui-word-aux">拦截POST表单中的SQL注入代码,提升网站安全性!(测试)</div>
    </div>
 </div> 
  <div class="layui-form-item">
    <div class="layui-inline">
      <label class="layui-form-label">插件支持</label>
      <div class="layui-input-inline">
      <select id="Plug" name="Plug"  >
        <option value="0" <?php if($Plug==0){echo'selected=""';}?>>默认模式</option>
        <option value="1" <?php if($Plug==1){echo'selected=""';}?>>兼容模式1</option>
        <option value="2" <?php if($Plug==2){echo'selected=""';}?>>兼容模式2</option>
      </select>
      </div>
      <div class="layui-form-mid layui-word-aux">选择兼容模式时,可以使用xiaoz开发的uTools插件 <a href="https://gitee.com/tznb/OneNav/wikis/%E4%BD%BF%E7%94%A8%E8%AF%B4%E6%98%8E/uTools%E6%8F%92%E4%BB%B6" target="_blank">帮助</a></div>
    </div>
 </div> 
  <div class="layui-form-item">
    <div class="layui-inline">
      <label class="layui-form-label">图标API</label>
      <div class="layui-input-inline">
      <select id="IconAPI" name="IconAPI"  >
        <option value="0" <?php if($IconAPI==0){echo'selected=""';}?>>离线图标</option>
        <option value="1" <?php if($IconAPI==1){echo'selected=""';}?>>本地服务(支持缓存)</option>
        <option value="2" <?php if($IconAPI==2){echo'selected=""';}?>>favicon.rss.ink (小图标)</option>
        <option value="4" <?php if($IconAPI==4){echo'selected=""';}?>>api.15777.cn </option>
        <option value="5" <?php if($IconAPI==5){echo'selected=""';}?>>favicon.cccyun.cc </option>
        <option value="6" <?php if($IconAPI==6){echo'selected=""';}?>>api.iowen.cn </option>
      </select>
      </div>
      <div class="layui-form-mid layui-word-aux">所有API接口均由其他大佬提供!若有异常请尝试更换接口!</div>
    </div>
 </div>
  <div class="layui-form-item">
    <div class="layui-inline">
      <label class="layui-form-label">收录功能</label>
      <div class="layui-input-inline">
      <select id="apply" name="apply"  >
        <option value="0" <?php if($apply==0){echo'selected=""';}?>>关闭</option>
        <option value="1" <?php if($apply==1){echo'selected=""';}?>>开启</option>
      </select>
      </div>
      <div class="layui-form-mid layui-word-aux">此为全局开关,关闭后所有账号无法使用此功能,账号自己还可设置是否开启!</div>
    </div>
 </div>
 
 <div class="layui-form-item">
    <div class="layui-inline">
      <label class="layui-form-label">二级域名</label>
      <div class="layui-input-inline">
      <select id="Pandomain" name="Pandomain"  >
        <option value="0" <?php if($Pandomain==0){echo'selected=""';}?>>关闭</option>
        <option value="1" <?php if($Pandomain==1){echo'selected=""';}?>>开启</option>
      </select>
      </div>
      <div class="layui-form-mid layui-word-aux">以二级域名的形式直接进入用户主页,需配置域名泛解析和服务器泛域名绑定(需订阅)</div>
    </div>
 </div>
 
  <div class="layui-form-item">
    <div class="layui-inline">
      <label class="layui-form-label">离线模式</label>
      <div class="layui-input-inline">
      <select id="offline" name="offline"  >
        <option value="0" <?php if($offline==0){echo'selected=""';}?>>关闭</option>
        <option value="1" <?php if($offline==1){echo'selected=""';}?>>开启</option>
      </select>
      </div>
      <div class="layui-form-mid layui-word-aux">开启将禁止服务器访问互联网,部分功能将被禁用(如:更新提示,公告,在线主题,链接识别,书签克隆等)!</div>
    </div>
 </div>

  <div class="layui-form-item">
    <div class="layui-inline">
      <label class="layui-form-label">强制私有</label>
      <div class="layui-input-inline">
      <select id="Privacy" name="Privacy"  >
        <option value="0" <?php if($Privacy==0){echo'selected=""';}?>>关闭</option>
        <option value="1" <?php if($Privacy==1){echo'selected=""';}?>>全站用户</option>
        <option value="2" <?php if($Privacy==2){echo'selected=""';}?>>普通用户</option>
      </select>
      </div>
      <div class="layui-form-mid layui-word-aux">开启后用户必须登录才可进入主页(过渡页不限制),多用户时防止用户添加非法URL造成封站(需订阅)</div>
    </div>
 </div>
 
  <div class="layui-form-item">
    <div class="layui-inline">
      <label class="layui-form-label">图标上传</label>
      <div class="layui-input-inline">
      <select id="iconUP" name="iconUP"  >
        <option value="0" <?php if($iconUP==0){echo'selected=""';}?>>禁止</option>
        <option value="1" <?php if($iconUP==1){echo'selected=""';}?>>允许</option>
      </select>
      </div>
      <div class="layui-form-mid layui-word-aux">设为允许时用户在后台添加或编辑链接时可以上传图标(需订阅)</div>
    </div>
 </div>
  <div class="layui-form-item layui-form-text">
    <label class="layui-form-label">底部代码</label>
    <div class="layui-input-block"> 
       <textarea name="footer" class="layui-textarea"  placeholder="例如统计代码,又拍云LOGO等,支持HTML,JS,CSS" ><?php echo $footer?></textarea>
    </div>
  </div>
</div>  
  <div class="layui-form-item">
    <div class="layui-input-block">
      <button class="layui-btn" lay-submit lay-filter="edit_root">保存配置</button>
    </div>
  </div>

  </div><!--表单End-->
    
</div><!--全局配置End-->
<div class="layui-tab-item" ><!--用户管理-->
<div class="layui-row content-body" style="margin-top: 0px;margin-left: 0px;margin-right: 0px;">
    <div class="layui-col-lg12">

        <div class="layui-inline" >
        <input class="layui-input" name="keyword" id="user_keyword" placeholder='请输入账号,邮箱,注册IP' value=''autocomplete="off" >
        </div>
        <div class="layui-btn-group ">
        <button class="layui-btn layui-btn " data-type="user_search">搜索</button>
        </div>
        <table id="user_list" lay-filter="user_list"></table>
        <script type="text/html" id="user_tool">
            <div class="layui-btn-container">
            <button class="layui-btn layui-btn-sm layui-btn-danger" lay-event="Del">删除选中</button>
            <button class="layui-btn layui-btn-sm " lay-event="Reg" <?php  echo $reg === '0'? 'style = "display:none;"':'' ?> >注册账号</button>
            <button class="layui-btn layui-btn-sm " lay-event="help" >帮助</button>
            <button class="layui-btn layui-btn-sm " lay-event="repair" >修复/升级</button>
            <button class="layui-btn layui-btn-sm " lay-event="loginlog" >登录日志</button>
            <button class="layui-btn layui-btn-sm layui-btn-normal" lay-event="up_to_twonav" >导出数据</button>
        </div>
        </script>
        <!-- 开启表格头部工具栏END -->
    </div>
    <script type="text/html" id="link_operate">
    <a class="layui-btn layui-btn-xs" lay-event="admin">后台</a>
    <a class="layui-btn layui-btn-xs" lay-event="edit">改密</a>
    </script>
</div>

</div><!--用户管理End-->

<div class="layui-tab-item" ><!--订阅-->
<div class="layui-row content-body place-holder" style="padding-bottom: 3em;">
    <!-- 说明提示框 -->
    <div class="layui-col-lg12">
      <div class="setting-msg">
        <ol>
            <li>您可以在下方点击购买订阅，购买后可以：</li>
            <li>1. 可使用标签功能</li>
            <li>2. 可使用二级域名绑定账号功能</li>
            <li>3. 可使用链接检测功能</li>
            <li>4. 可使用全局强制私有模式</li>
            <li>5. 可无限次数下载主题和系统更新</li>
            <li>6. 可以使用使用图标上传功能,让您的书签图标更加好看</li>
            <li>7. 更多专属功能开发中 (未来新增的功能均为订阅可用)</li>
            <li>8. 可帮助OneNav Extend持续发展，让它变得更加美好</li>
            <li>#. 技术支持:QQ 271152681  </li>
        </ol>
      </div>
    </div>
    <!-- 说明提示框END -->
    <!-- 订阅表格 -->
    <div class="layui-col-lg6">
    <h2 style = "margin-bottom:1em;">我的订阅：</h2>
    <div class="layui-form layui-form-pane layui-form-item" >
        <label class="layui-form-label">您的域名</label>
        <div class="layui-input-block">
            <input type="text" name="domain" id = "domain" value = "<?php echo $_SERVER['HTTP_HOST']; ?>  (订阅时填写)" autocomplete="off" disabled="disabled" class="layui-input">
        </div>
    </div>
    <form class="layui-form layui-form-pane" action="">

        <div class="layui-form-item">
            <label class="layui-form-label">订单号</label>
            <div class="layui-input-block">
                <input type="text" id = "order_id" name="order_id" value = "<?php echo $subscribe['order_id']; ?>" required  lay-verify="" autocomplete="off" placeholder="请输入订单号" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">订阅邮箱</label>
            <div class="layui-input-block">
                <input type="email" name="email" id = "email" value = "<?php echo $subscribe['email']; ?>" required lay-verify="" autocomplete="off" placeholder="订阅邮箱" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item" style = "display:none;">
            <label class="layui-form-label">域名</label>
            <div class="layui-input-block">
                <input type="text" name="domain" id = "domain" value = "<?php echo $_SERVER['HTTP_HOST']; ?>" autocomplete="off" placeholder="网站域名" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">到期时间</label>
            <div class="layui-input-block">
            <input type="text" name="end_time" id = "end_time" readonly="readonly" value = "<?php echo date("Y-m-d H:i:s",$subscribe['end_time']); ?>" autocomplete="off" placeholder="订阅到期时间" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <button class="layui-btn" lay-submit="" lay-filter="set_subscribe">保存设置</button>
            <button class="layui-btn" lay-submit="" lay-filter="reset_subscribe">删除订阅</button>
            <a class="layui-btn layui-btn-danger" rel = "nofollow" target = "_blank" title = "点此购买订阅" href="https://gitee.com/tznb/OneNav/wikis/%E8%AE%A2%E9%98%85%E6%9C%8D%E5%8A%A1%E6%8C%87%E5%BC%95"><i class="fa fa-shopping-cart"></i> 购买订阅</a>
            <button class="layui-btn" lay-submit="" lay-filter="get_subscribe">查询订阅</button>
        </div>

    </form>
    </div>
    <!-- 订阅表格END -->

</div><!--订阅End-->


</div>
</div>
</div>
</div>
</div>
<script src = '<?php echo $libs?>/jquery/jquery-3.6.0.min.js'></script>
<script src = '<?php echo $libs?>/Layui/v2.6.8/layui.js'></script>
<script src = '<?php echo $libs?>/jquery/jquery.md5.js'></script>
<script src = "./templates/admin/static/public.js?t=<?php echo $version; ?>"></script>
<script>
layui.use(['element','table','layer','form','util','dropdown'], function(){
    var element = layui.element;
    var table = layui.table;
    var util = layui.util;
    var form = layui.form;
    var dropdown = layui.dropdown;
    layer = layui.layer;

var limit = String(getCookie('lm_limit'));
if (limit < 10 || limit > 90){
    limit = 20 ;
}

    //Hash地址的定位
    var layid = location.hash.replace(/^#root=/, '');
    element.tabChange('root', layid);
    console.log(layid);
    //切换事件
    element.on('tab(root)', function(elem){
        layid = $(this).attr('lay-id');
        location.hash = 'root='+ $(this).attr('lay-id');
    });
    
    
var user_cols=[[ //表头
      {type:'checkbox'} //开启复选框
      ,{field:'ID',title:'ID',width:60,sort:true}
      ,{field:'User',title:'账号',minWidth:120,sort:true,templet:function(d){
          return '<a style="color:#3c78d8" title="打开用户主页" target="_blank" href="./?u='+d.User+'">'+d.User+'</a>'
      }}
      ,{field:'Level',title:'用户组',minWidth:90,sort:true ,event: 'group', style:'cursor: pointer;',templet:function(d){
          if(d.Level ==999){return '管理员'}
          else{return '普通会员'}
      }}
      ,{field:'SQLite3',title:'数据库',minWidth:150,event: 'SetName', style:'cursor: pointer;'}
      ,{field:'Email',title:'Email',minWidth:170,sort:true}
      ,{field:'RegIP',title:'注册IP',minWidth:140,sort:true,templet:function(d){
          return '<a style="color:#3c78d8" title="查询归属地" target="_blank" href="//ip.cn/?ip='+d.RegIP+'">'+d.RegIP+'</a>'
      }}
 
      ,{field:'RegTime',title: '注册时间',minWidth:160,sort:true,templet:function(d){
          if(d.RegTime == null){return '';}
          else{return timestampToTime(d.RegTime);}}} 
      ,{title:'操作',toolbar:'#link_operate',width:130}
    ]]
//用户表渲染
table.render({
    elem: '#user_list'
    ,height: 'full-200' //自适应高度
    ,url: './index.php?c=api&method=user_list&u=<?php echo $u;?>' //数据接口
    ,page: true //开启分页
    ,limit:limit  //默认每页显示行数
    ,even:true //隔行背景色
    ,loading:true //加载条
    ,toolbar: '#user_tool'
    ,id:'user_list'
    ,cols: user_cols
});

dropdown.render({elem: '#libs',data:[{title: '本地服务',url: './static'},{title: '小zCDN',url: '//libs.xiaoz.top/lm21/onenav'}] ,click: function(obj){this.elem.val(obj.url);},style: 'width: 190px;'});
//用户行工具栏事件
table.on('tool(user_list)', function(obj){
    var data = obj.data;
    console.log(data)
    if('<?php echo $u;?>' === data.User){layer.msg('您不能对自己操作!', {icon: 5});return false;}
    if(obj.event === 'edit'){
        layer.prompt({formType: 0,value: '',title: '请输入新密码:'},function(value, index, elem){
            if(value.length<8){
                layer.msg('密码长度不能小于8个字符!', {icon: 5});
                return false;
            }
            $.post('./index.php?c=api&method=func&u=<?php echo $u;?>',{'fn':'rootu','Set':'Pass','id':data.ID,'Pass':$.md5(value)},function(data,status){
                if(data.code == 0){
                    layer.closeAll();//关闭所有层
                }
                layer.msg(data.msg, {icon: data.icon});
            });
        });
    } else if(obj.event === 'admin'){
        $.post('./index.php?c=api&method=user_list_login&u=<?php echo $u;?>',{'id':obj.data.ID},function(data,status){
            if(data.code == 0){
                if(data.msg !='Cookie有效'){
                    layer.msg(data.msg, {time: 20000,btn: ['知道了']}, function(index){
                        window.open('./index.php?c=admin&u=' + obj.data.User);
                    });
                }else{
                window.open('./index.php?c=admin&u=' + obj.data.User);
                }
            } else{
                layer.msg(data.msg, {icon: 5});
            }
        });
    } else if(obj.event === 'group'){
        if(data.Level == '0'){
            layer.confirm('是否将 '+data.User+' 设为管理员?',{icon: 3, title:'温馨提示'}, function(index){
                $.post('./index.php?c=api&method=func&u=<?php echo $u;?>',{'fn':'rootu','Set':'Level','id':data.ID,'Level':'999'},function(data,status){
                    if(data.code == 0){obj.update({Level: data.Level});}
                    layer.msg(data.msg, {icon: data.icon});
                });
            });
        }else if(data.Level == '999'){
            layer.confirm('是否将 '+data.User+' 设为普通用户?',{icon: 3, title:'温馨提示'}, function(index){
                $.post('./index.php?c=api&method=func&u=<?php echo $u;?>',{'fn':'rootu','Set':'Level','id':data.ID,'Level':'0'},function(data,status){
                    if(data.code == 0){obj.update({Level: data.Level});}
                    layer.msg(data.msg, {icon: data.icon});
                });
            });
        }
    } else if(obj.event === 'SetName'){
        layer.confirm('该行为存在风险,建议备份data目录在操作!',{icon: 3,anim: 2, title:'温馨提示'}, function(index){ 
            layer.closeAll();
            layer.prompt({formType: 0,anim: 1,value: '',title: '请输入'+data.User+'的新账号:'},function(value, index, elem){
                if(!/^[A-Za-z0-9]{4,13}$/.test(value)){
                    layer.closeAll();
                    layer.msg('账号只能是4到13位的数字和字母!', {icon: 5});
                    return false;
                } 
                $.post('./index.php?c=api&method=func&u=<?php echo $u;?>',{'fn':'rootu','Set':'SetName','id':data.ID,'NewName':value},function(data,status){
                    if(data.code == 0){
                        layer.closeAll();//关闭所有层
                        obj.update({User: value});//回写账号
                        obj.update({SQLite3: value+'.db3'});//回写数据库
                        if( data.du ==1){document.getElementById("DUser").value = value;}//默认用户同步回写
                    }
                    layer.msg(data.msg, {icon: data.icon});
                });
            });
        });
    }
});
//表头工具
table.on('toolbar(user_list)', function(obj){
    var checkStatus = table.checkStatus(obj.config.id),id='';
    var data = checkStatus.data;
    switch(obj.event){
      case 'Del':
      if( data.length == 0 ) {layer.msg('未选中任何数据!'); return;}else{for (let i = 0; i < data.length; i++) {if (i < data.length-1){id +=data[i].ID+','}else{id +=data[i].ID}}}
      console.log(id) 
      num=randomnum(4);
      layer.prompt({formType: 0,value: '',title: '输入'+num+'确定删除:'},function(value, index, elem){
          if(value != num){
              layer.msg('输入内容有误,无需删除请点击取消!', {icon: 5});
              return;
          }else{
              $.post('./index.php?c=api&method=user_list_del&u=<?php echo $u;?>',{'id':id},function(data,status){
                  if(data.code == 0){
                      layer.closeAll();
                      user_search();//刷新表格
                      open_msg('600px', '500px','处理结果',data.res);
                  } else{layer.msg(data.msg);}});
          }
      }); 
    
      break;
      case 'Reg':
      window.open('./index.php?c=<?php echo $Register;?>');
      break;
      case 'help':
      open_msg('300px', '300px','帮助说明','<div style="padding: 15px;">1.点击账号进入用户主页<br>2.点击注册IP查询IP归属地<br>3.点击后台进入用户后台(免密)<br>4.点击用户组可以切换用户组<br>5.升级后建议点击两次修复<br>6.管理员账号都是权限一样的!<br>7.点击数据库可以修改账号</div>');
      break;
      case 'repair':
      $.post('./index.php?c=api&method=func&u=<?php echo $u;?>',{'fn':'repair'},function(data,status){
          if(data.code == 0){
              layer.msg(data.msg, {icon: 1});
          } else{
              open_msg('88%', '88%','修复详情','<div style="padding: 15px;">'+data.msg+'</div>');
          }
      });
      break;
      case 'loginlog':
      window.location.href="./index.php?c=admin&page=loginlog&u=<?php echo $u;?>";
      break;  
      case 'up_to_twonav':
          export_to_twonav();
      break; 
      
    }
});
//回车和按钮事件
$('#user_keyword').keydown(function (e){if(e.keyCode === 13){user_search();}}); 
$('.layui-btn').on('click', function(){
   var type = $(this).data('type');
   active[type] ? active[type].call(this) : '';
});

var active = {
user_search:function(){user_search();}
};
//用户搜索
function user_search(){
var keyword = document.getElementById("user_keyword").value;//获取输入内容
table.reload('user_list', {
  url: './index.php?c=api&method=user_list&u=<?php echo $u;?>'
  ,method: 'post'
  ,request: {
   pageName: 'page'
   ,limitName: 'limit'
  }
  ,where: {
   query : keyword
  }
  ,page: {
   curr: 1
  }
});
}
//全局配置
form.on('submit(edit_root)', function(data){
    console.log(data.field) 
    $.post('./index.php?c=api&method=edit_root&u=<?php echo $u;?>',data.field,function(data,status){
      if(data.code == 0) {
        layer.msg('保存成功,刷新中..', {icon: 1});
        setTimeout(() => {
            location.reload();
        }, 700);
      }
      else{
        layer.msg(data.msg, {icon: 5});
      }
    });
    return false; 
});  


  //保存订阅信息
  form.on('submit(set_subscribe)', function(data){
    var order_id = data.field.order_id;
    var index = layer.load(1);
    $.get('https://api.lm21.top/api.php?fn=check_subscribe',data.field,function(data,status){
      if(data.code == 200) {
        email = data.data.email;
        end_time = data.data.end_time;
        domain = data.data.domain;
        $("#end_time").val(timestampToTime(end_time));
        //存储到数据库中
        $.post("./index.php?c=api&method=set_subscribe&u=<?php echo $u;?>",{order_id:order_id,email:email,end_time:end_time,domain:domain},function(data,status){
          if(data.code == 0) {
            layer.closeAll('loading');
            layer.msg(data.msg, {icon: 1});
          }
          else{
            layer.closeAll('loading');
            layer.msg(data.msg, {icon: 5});
          }
        });
      }
      else{
        layer.closeAll('loading');
        layer.msg(data.msg, {icon: 5});
      }

    });
    console.log(data.field) 
    return false;
  });
  //清空订阅信息
  form.on('submit(reset_subscribe)', function(data){
    //存储到数据库中
    $.post("./index.php?c=api&method=set_subscribe&u=<?php echo $u;?>",{order_id:'',email:'',end_time:null},function(data,status){
      if(data.code == 0) {
        //清空表单
      $("#order_id").val('');
      $("#email").val('');
      //$("#domain").val('');
      $("#end_time").val('');
        layer.msg(data.msg, {icon: 1});
      }
      else{
        layer.closeAll('loading');
        layer.msg(data.msg, {icon: 5});
      }
    });
    return false; 
  });

  //获取订阅
  form.on('submit(get_subscribe)', function(data){
      $.get('https://api.lm21.top/api.php?fn=get_subscribe',data.field,function(data,status){
       if(data.code == 200) {
        $("#order_id").val(data.data.order_id);
        $("#end_time").val(timestampToTime(data.data.end_time));
        layer.closeAll('loading');
        layer.msg(data.msg, {icon: 1});
       }else{
        layer.closeAll('loading');
        layer.msg(data.msg, {icon: 5,time: 10000});
       }
      });
      
    return false; 
  });
function export_to_twonav(){
    let tip = layer.open({
        title:"导出数据"
        ,content: "导出数据用于升级到TwoNav"
        ,btn: ['开始导出', '升级教程', '取消']
        ,yes: function(index, layero){
            let fail = false;
            let up_info = {'code':0};
            let i=0;
            layer.close(tip);
            layer.load(1, {shade:[0.3,'#fff']});//加载层
            let msg_id = layer.msg('正在准备数据,请勿操作.', {icon: 16,time: 1000*300});
            //设置同步模式
            $.ajaxSetup({ async : false }); 
                
            //获取更新信息
            $.post("./index.php?c=api&method=export_to_twonav&type=user_list&u=<?php echo $u;?>", function(data, status) {
                   up_info = data;
            });
            console.log(up_info);
            //如果失败
            if(up_info.code != 1){
                layer.closeAll();
                layer.alert(up_info.msg ?? "错误代码：404",{icon:2,title:'导出失败',anim: 2,shadeClose: false,closeBtn: 0,btn: ['知道了']});
                return;
            }
            //设为异步模式
            $.ajaxSetup({ async : true }); 
            //开始请求更新
            request_update(); let msg = '';
            function request_update(){
                if( i >= up_info.info.length){
                    pack_data();
                    return;
                }else{
                    i++;
                }
                let user = up_info.info[i-1];
                console.log(up_info.info[i-1]);
                $("#layui-layer"+ msg_id+" .layui-layer-padding").html('<i class="layui-layer-ico layui-layer-ico16"></i>[ ' + i + ' / ' + up_info.info.length + ' ] 正在处理 ' + user);
                    
                $.post("./index.php?c=api&method=export_to_twonav&type=export&u=<?php echo $u;?>",{"user":user}, function(data, status) {
                    if (data.code == 1) { 
                        request_update();
                    }else{
                        layer.closeAll();
                        layer.alert(data.msg ?? "export.未知错误,请联系开发者!",{icon:5,title:up_info.info[i-1],anim: 2,shadeClose: false,closeBtn: 0,btn: ['知道了']});
                    } 
                });
            }
            //打包数据
            function pack_data(){
                $("#layui-layer"+ msg_id+" .layui-layer-padding").html('<i class="layui-layer-ico layui-layer-ico16"></i>正在打包数据' );
                $.post("./index.php?c=api&method=export_to_twonav&type=pack_data&u=<?php echo $u;?>",function(data, status) {
                    if (data.code == 1) { 
                        layer.closeAll();
                        layer.alert('导出完毕,请参照教程继续操作!<a href="./data/'+data.msg+'" target="_blank" style="color: #01AAED;">下载数据</a>',{icon:1,title:'导出数据',anim: 2,shadeClose: false,closeBtn: 0,btn: ['知道了']});
                    }else{
                        layer.closeAll();
                        layer.alert(data.msg ?? "pack_data.未知错误,请联系开发者!",{icon:5,title:up_info.info[i-1],anim: 2,shadeClose: false,closeBtn: 0,btn: ['知道了']});
                    } 
                });
                
                
            }
            },btn2: function(index, layero){
                window.open("https://gitee.com/tznb/OneNav/wikis/pages?sort_id=7955135&doc_id=2439895");
                return false;
            },btn3: function(index, layero){
                return true;
            },cancel: function(){ 
                return true;
            }
        });
    
    
    return false; 
    layer.load(2, {shade: [0.1,'#fff']});//加载层
    $.post("./index.php?c=api&method=export_to_twonav&u=<?php echo $u;?>",function(data,status){
        layer.closeAll('loading');
        if(data.code == 0) {
            layer.msg(data.msg, {icon: 1});
        }else{
            layer.msg(data.msg, {icon: 5});
      }
    });
}

//结果弹出
function open_msg(x,y,t,c){
    layer.open({ //弹出结果
    type: 1
    ,title: t
    ,area: [x, y]
    ,maxmin: true
    ,shadeClose: true
    ,content: c
    ,btn: ['我知道了'] 
    });
}
});
</script>
</body>
</html>