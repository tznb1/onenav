<?php 
include_once('header.php');
include_once('left.php'); 
if($udb->get("user","Level",["User"=>$u]) != 999){
    include_once('footer.php');
    exit;
}
$ICP    = $udb->get("config","Value",["Name"=>'ICP']);
$footer = $udb->get("config","Value",["Name"=>'footer']);
$footer = htmlspecialchars_decode(base64_decode($footer));
$Plug     = $udb->get("config","Value",["Name"=>'Plug']);
?>

<style type="text/css">
.layui-layout-admin .layui-body {top: 40px;}
</style>
<div class="layui-tab layui-tab-brief layui-body layui-row content-body" lay-filter="root">
<ul class="layui-tab-title">
 <li class="layui-this">全局配置</li>
 <li >用户管理</li>
</ul>
<div class="layui-tab-content" >
<div class="layui-tab-item layui-show layui-form layui-form-pane"><!--全局配置--> 
<div class="layui-row content-body layui-show layui-form layui-form-pane" style="margin-top: 0px;">
<div class="layui-col-lg12">
 <div class="layui-form-item">
    <div class="layui-inline">
      <label class="layui-form-label">默认用户</label>
      <div class="layui-input-inline">
        <input type="text" name="DUser" id="DUser" lay-verify="required" value = '<?php echo $Duser;?>' placeholder='admin'  autocomplete="off" class="layui-input">
      </div>
      <div class="layui-form-mid layui-word-aux">默认主页的账号,优先级:Get>Cookie>默认用户>admin</div>
    </div>
 </div>
 <div class="layui-form-item">
    <div class="layui-inline">
      <label class="layui-form-label">注册用户</label>
     <div class="layui-input-inline">
      <select id="Reg" name="Reg"  >
        <option value="0" <?php if($reg==0){echo'selected=""';}?>>禁止注册</option>
        <option value="1" <?php if($reg==1){echo'selected=""';}?>>允许注册</option>
      </select>
      </div>
      <div class="layui-form-mid layui-word-aux">个人使用可以禁止注册哦!</div>
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
    <input type="text" name="libs"  lay-verify="required" value = '<?php echo $libs; ?>' placeholder='./static'  autocomplete="off" class="layui-input">
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
      <div class="layui-form-mid layui-word-aux">因法律风险问题,取消了普通用户自定义备案号!这里设置的是全局显示的!</div>
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
      <div class="layui-form-mid layui-word-aux">选择兼容模式时,可以使用xiaoz开发的uTools插件 <a href="https://doc.xiaoz.me/books/onenav-extend/page/utools" target="_blank">帮助</a></div>
    </div>
 </div> 
  <div class="layui-form-item">
    <div class="layui-inline">
      <label class="layui-form-label">图标API</label>
      <div class="layui-input-inline">
      <select id="IconAPI" name="IconAPI"  >
        <option value="1" <?php if($IconAPI==1){echo'selected=""';}?>>本地服务(支持缓存)</option>
        <option value="2" <?php if($IconAPI==2){echo'selected=""';}?>>favicon.rss.ink (小图标)</option>
        <option value="3" <?php if($IconAPI==3){echo'selected=""';}?>>ico.hnysnet.com </option>
        <option value="4" <?php if($IconAPI==4){echo'selected=""';}?>>api.15777.cn </option>
        <option value="5" <?php if($IconAPI==5){echo'selected=""';}?>>favicon.cccyun.cc </option>
        <option value="6" <?php if($IconAPI==6){echo'selected=""';}?>>api.iowen.cn </option>
      </select>
      </div>
      <div class="layui-form-mid layui-word-aux">所有API接口均由其他大佬提供!若有异常请尝试更换接口!</div>
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
            <button class="layui-btn layui-btn-sm " lay-event="Reg" >注册账号</button>
            <button class="layui-btn layui-btn-sm " lay-event="help" >帮助</button>
            <button class="layui-btn layui-btn-sm " lay-event="repair" >修复/升级</button>
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
</div>
</div>
</div>
<script src = '<?php echo $libs?>/jquery/jquery-3.6.0.min.js'></script>
<script src = '<?php echo $libs?>/Layui/v2.6.8/layui.js'></script>
<script src = '<?php echo $libs?>/jquery/jquery.md5.js'></script>
<script>
layui.use(['element','table','layer','form','util'], function(){
    var element = layui.element;
    var table = layui.table;
    var util = layui.util;
    var form = layui.form;
    layer = layui.layer;
//表头 https://www.layui.site/demo/table/cellEvent.html
var cols=[[ //表头
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
          return '<a style="color:#3c78d8" title="查询归属地" target="_blank" href="//ip.ws.126.net/ipquery?ip='+d.RegIP+'">'+d.RegIP+'</a>'
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
    ,limit:20  //默认每页显示行数
    ,even:true //隔行背景色
    ,loading:true //加载条
    ,toolbar: '#user_tool'
    ,id:'user_list'
    ,cols: cols
});


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
        if(data.Level === '0'){
            layer.confirm('是否将 '+data.User+' 设为管理员?',{icon: 3, title:'温馨提示'}, function(index){
                $.post('./index.php?c=api&method=func&u=<?php echo $u;?>',{'fn':'rootu','Set':'Level','id':data.ID,'Level':'999'},function(data,status){
                    if(data.code == 0){obj.update({Level: data.Level});}
                    layer.msg(data.msg, {icon: data.icon});
                });
            });
        }else if(data.Level === '999'){
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
    }
});
//回车和按钮事件
$('#user_keyword').keydown(function (e){if(e.keyCode === 13){user_search();}}); 
$('.layui-btn').on('click', function(){
   var type = $(this).data('type');
   active[type] ? active[type].call(this) : '';
});

var active = {
user_search:function(){user_search();},
    tabAdd: function(){
      //新增一个Tab项
      element.tabAdd('root', {
        title: '新选项'+ (Math.random()*1000|0) //用于演示
        ,content: '内容'+ (Math.random()*1000|0)
        ,id: new Date().getTime() //实际使用一般是规定好的id，这里以时间戳模拟下
      })
    }
    ,tabDelete: function(othis){
      //删除指定Tab项
      element.tabDelete('root', 'id');
      othis.addClass('layui-btn-disabled');
    }
    ,tabChange: function(){
      //切换到指定Tab项
      element.tabChange('root', '22'); 
    }
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
        layer.msg('已修改！', {icon: 1});
      }
      else{
        layer.msg(data.msg, {icon: 5});
      }
    });
    return false; 
});  
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
//时间戳格式化
function  timestampToTime(timestamp) {
    var  date =  new  Date(timestamp * 1000); //时间戳为10位需*1000，时间戳为13位的话不需乘1000
    var y = date.getFullYear();
    var m = date.getMonth() + 1;
    m = m < 10 ? ('0' + m) : m;
    var d = date.getDate();
    d = d < 10 ? ('0' + d) : d;
    var h = date.getHours();
    h = h < 10 ? ('0' + h) : h;
    var minute = date.getMinutes();
    var second = date.getSeconds();
    minute = minute < 10 ? ('0' + minute) : minute;
    second = second < 10 ? ('0' + second) : second;
    return y + '-' + m + '-' + d+' '+h+':'+minute+':'+second;
}
//取随机数字
function randomnum(length) {
  var str = '0123456789';
  var result = '';
  for (var i = length; i > 0; --i) 
    result += str[Math.floor(Math.random() * str.length)];
  return result;
}
});
</script>
</body>
</html>