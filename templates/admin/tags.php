<?php include_once('header.php'); ?>
<?php include_once('left.php'); 
$tagin = getconfig('tagin','id/mark') ?>
<div class="layui-body " style="padding-bottom: 0px;">
<!-- 内容主体区域 -->
<div class="layui-row content-body">
    <div class="layui-col-lg12">
        <table id="list" lay-filter="list"></table>
        <script type="text/html" id="tool">
        <div class="layui-btn-container">
            <button class="layui-btn layui-btn-sm layui-btn-danger" lay-event="del_tags">删除</button>
            <button class="layui-btn layui-btn-sm " lay-event="set_tags">设置</button>
            <button class="layui-btn layui-btn-sm " lay-event="add_tags">添加</button>
            <button class="layui-btn layui-btn-sm " lay-event="load_tags">刷新</button>
            <button class="layui-btn layui-btn-sm " lay-event="help_tags">帮助</button>
        </div>
        </script>
        <!-- 开启表格头部工具栏END -->
    </div>
    <script type="text/html" id="operate">
        <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
    </script>
</div>
<!-- 内容主题区域END -->
</div>

<!--添加标签组-->
<ul class="add_tags" style = "margin-top:18px;display:none;padding-right: 10px;" >
    <form class="layui-form" lay-filter="add_tags">
        <div class="layui-form-item" >
            <label class="layui-form-label">名称</label>
            <div class="layui-input-inline"><input lay-verify="required" name="name" value = "" placeholder="标签名" class="layui-input"></div>
        </div>
        <div class="layui-form-item" >
            <label class="layui-form-label">标识</label>
            <div class="layui-input-inline"><input lay-verify="required" name="mark" value = "" placeholder="标识" class="layui-input"></div>
        </div>
        <div class="layui-form-item" >
            <label class="layui-form-label">密码</label>
            <div class="layui-input-inline"><input  name="pass" value = "" placeholder="访问密码,留空为公开" class="layui-input"></div>
        </div>
        <div class="layui-form-item" >
            <label class="layui-form-label">期限</label>
            <div class="layui-input-inline"><input  name="expire" value = "" placeholder="有效期,留空为永久" class="layui-input" id="expire"></div>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit lay-filter="add_tags">添加</button>
            </div>
        </div>
  </form>
</ul>
<!--编辑标签组-->
<ul class="edit_tags" style = "margin-top:18px;display:none;padding-right: 10px;" >
    <form class="layui-form" lay-filter="edit_tags">
        <div class="layui-form-item" style = "display:none;">
            <label class="layui-form-label">id</label>
            <div class="layui-input-inline"><input lay-verify="required" name="id" value = "" placeholder="" class="layui-input"></div>
        </div>
        <div class="layui-form-item" >
            <label class="layui-form-label">名称</label>
            <div class="layui-input-inline"><input lay-verify="required" name="name" value = "" placeholder="标签名" class="layui-input"></div>
        </div>
        <div class="layui-form-item" >
            <label class="layui-form-label">标识</label>
            <div class="layui-input-inline"><input lay-verify="required" name="mark" value = "" placeholder="标识" class="layui-input"></div>
        </div>
        <div class="layui-form-item" >
            <label class="layui-form-label">密码</label>
            <div class="layui-input-inline"><input  name="pass" value = "" placeholder="访问密码,留空为公开" class="layui-input"></div>
        </div>
        <div class="layui-form-item" >
            <label class="layui-form-label">期限</label>
            <div class="layui-input-inline"><input  name="expire" value = "" placeholder="有效期,留空为永久" class="layui-input" id="expire"></div>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit lay-filter="edit_tags">修改</button>
            </div>
        </div>
  </form>
</ul>
<!--设置-->
<ul class="set_tags" style = "margin-top:18px;display:none;padding-right: 10px;" >
    <form class="layui-form" lay-filter="set_tags">
        <div class="layui-form-item">
            <label class="layui-form-label">入口类型</label>
            <div class="layui-input-inline">
                <select name="tagin">
                    <option value="id" <?php if($tagin=="id"){echo'selected=""';}?> >ID</option>
                    <option value="mark" <?php if($tagin=="mark"){echo'selected=""';}?> >标识</option>
                    <option value="id/mark" <?php if($tagin=="id/mark"){echo'selected=""';}?> >ID/标识</option>
                </select>
            </div>
        </div>
        <div class="layui-form-item" >
            <label class="layui-form-label">隐藏链接</label>
            <input type="checkbox" name="taghome" lay-skin="primary" title="已设标签的链接不在主页显示" <?php echo getconfig('taghome')=='on'?'checked=""':''; ?> >
        </div>
        <div class="layui-form-item" >
            <label class="layui-form-label">私有链接</label>
            <input type="checkbox" name="tag_private" lay-skin="primary" title="游客能看私有链接(慎)" <?php echo getconfig('tag_private')=='on'?'checked=""':''; ?> >
        </div>
        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit lay-filter="set_tags">保存</button>
            </div>
        </div>
  </form>
</ul>


<script src = "<?php echo $libs?>/jquery/jquery-3.6.0.min.js"></script>
<script src = '<?php echo $libs?>/jquery/jquery.md5.js'></script>
<script src = "<?php echo $libs?>/Layui/v2.6.8/layui.js"></script>
<script src = "./templates/admin/static/public.js?t=<?php echo $version; ?>"></script>
<script>
layui.use(['element','table','layer','form','util','dropdown','laydate'], function(){
    var element = layui.element;
    var table = layui.table;
    var util = layui.util;
    var form = layui.form;
    var dropdown = layui.dropdown;
    var laydate = layui.laydate;
    var layer = layui.layer;
    var u = '<?php echo $u?>';
    
//每页数量检测,超出阈值是恢复20
var limit = String(getCookie('lm_limit'));
if (limit < 10 || limit > 90){
    limit = 20 ;
}

//日期时间选择器
laydate.render({
    elem: '#expire'
    ,type: 'datetime'
    ,min: new Date().toLocaleString()
});

var cols=[[ //表头
      {type:'checkbox'} //开启复选框
      ,{field: 'id', title: 'ID', width:80, sort: true,event: 'id', style:'cursor: pointer;'}
      ,{field: 'name', title: '标签名称',sort:true,event: 'copy', style:'cursor: pointer;'}
      ,{field: 'mark', title: '标识',sort:true,event: 'mark', style:'cursor: pointer;'}
      ,{field: 'count', title: '链接数',sort:true,event: 'link_tab', style:'cursor: pointer;'}
      ,{field: 'pass', title: '访问密码',sort:true}
      ,{field: 'views', title: '浏览次数',sort:true}
      ,{field: 'expire', title: '到期时间',sort:true,templet:function(d){
          if(d.expire == 0 ){
              return "永久";
          }
        var expire = timestampToTime(d.expire);
        return expire;
      }}
      ,{ title:'操作', toolbar: '#operate',width:128}
    ]]

table.render({
    elem: '#list'
    ,height: 'full-100' //自适应高度
    ,url: './index.php?c=api&method=tags_list&u='+u //数据接口
    ,page: true //开启分页
    ,limit:limit  //默认每页显示行数
    ,even:true //隔行背景色
    ,loading:true //加载条
    ,toolbar: '#tool'
    ,id:'list'
    ,cols: cols
});
//头工具
table.on('toolbar(list)', function(obj){
    var checkStatus = table.checkStatus(obj.config.id),id='';
    var data = checkStatus.data;
    
    switch(obj.event){
        case 'add_tags':
            if(document.body.clientWidth < 768){area = ['100%' , '100%'];}else{area = ['368px' , '350px'];}
            //表单赋值,清空数据和填写8位随机标识
            form.val('add_tags', {"name": '',"mark": randomString(8),"pass": '',"expire":''});
            layer.open({
                type: 1,
                shadeClose: true,
                title: '添加标签组',
                area : area,
                content: $('.add_tags')
            });
        break;
        case 'del_tags':
            if( data.length == 0 ) {layer.msg('未选中任何数据');return} //没有选中数据,结束运行!
            for (let i = 0; i < data.length; i++) {if(data[i].count > 0 ){ layer.msg(data[i].name + '>>存在链接,不能直接删除!', {icon: 5});return;}; if (i < data.length-1){id +=data[i].id+','}else{id +=data[i].id}} //生成id表
            console.log(id)
            layer.confirm('确认删除选中数据？',{icon: 3, title:'温馨提示！'}, function(index){
                $.post('./index.php?c=api&method=del_tags&u='+u,{'id':id,'md5':$.md5(id)},function(data,status){
                    if(data.code == 0){
                         layer.open({title:'处理结果',content:data.msg,yes:function(index,layero){window.location.reload();layer.close(index)}});
                    }else{
                        layer.msg(data.msg, {icon: 5});
                    }
                });
            });
        break;
        case 'set_tags': 
            if(document.body.clientWidth < 768){area = ['100%' , '100%'];}else{area = ['368px' , '300px'];}
            layer.open({
                type: 1,
                shadeClose: true,
                title: '设置',
                area : area,
                content: $('.set_tags')
            });
        break;
        case 'load_tags':
            window.location.reload();
        break;
        case 'help_tags':
            layer.open({ //弹出结果
                type: 1
                ,title: '帮助说明'
                ,area: ['300px', '300px']
                ,maxmin: true
                ,shadeClose: true
                ,content: '<div style="padding: 15px;">1.点击ID和标识都能打开页面<br>2.点击名称可以复制链接<br>3.点击链接数可以跳转到链接列表<br>4.已登录时不受密码和到期限制<br>5.订阅失效时只能删除数据,不能添加修改<br>6.设置里面有3个选项,可按需修改!<br>7.咨询/反馈 QQ:271152681<br>8.此功能付费订阅可用,体验请点> <a href="https://demo.lm21.top/" target="_blank" style="color:#3c78d8">演示站</a></div>'
                ,btn: ['我知道了'] 
            });
        break;
    }
});
//行工具
table.on('tool(list)', function(obj){
    var data = obj.data;
    console.log(data)
    if(document.body.clientWidth < 768){area = ['100%' , '100%'];}else{area = ['368px' , '350px'];}
    if(obj.event === 'edit'){
        expire = data.expire == 0 ? '':timestampToTime(data.expire);
        form.val('edit_tags', {
            "id": data.id,
            "name": data.name,
            "mark": data.mark,
            "pass": data.pass,
            "expire":expire
        });
        layer.open({
                type: 1,
                shadeClose: true,
                title: '编辑标签组',
                area : area,
                content: $('.edit_tags')
        });
    }else if(obj.event === 'id'){
        window.open('./index.php?tag='+data.id +'&u=' + u);
    }else if(obj.event === 'mark'){
        window.open('./index.php?tag='+data.mark +'&u=' + u);
    }else if(obj.event === 'copy'){
        URL = window.location.href.match(/(.+)\/index.php?/)[1]; //获取地址
        var set_tags = form.val('set_tags'); //获取入口类型
        if (set_tags.tagin == 'id'){
            string = URL+'/index.php?tag='+data.id;
        }else if(set_tags.tagin == 'mark'){
            string = URL+'/index.php?tag='+data.mark;
        }else{
            string = URL+'/index.php?tag='+data.id;
        }
        if( copyString(string)){ //复制
            layer.msg("复制成功", {icon: 1});
        }else{
            layer.msg("复制失败", {icon: 5});
        }
    }else if(obj.event === 'link_tab'){ //跳到链接列表显示相关链接
        window.open('./index.php?c=admin&page=link_list&tagid='+data.id+'&u=' + u);
    }
});

//复制到粘贴板
function copyString(string){
  if(!string) return false
  let dom = document.createElement('input');
  dom.value = string;
  document.body.appendChild(dom);
  dom.select(); // 选择对象
  document.execCommand("Copy"); // 执行浏览器复制命令
  document.body.removeChild(dom)
  return true
}

//添加标签组 
form.on('submit(add_tags)', function(data){
    console.log(data.field);
    layer.load(2, {shade: [0.1,'#fff']});//加载层
    $.post('./index.php?c=api&method=add_tags' + "&u=<?php echo $u;?>" ,data.field,function(data,status){
        console.log(data,status);
        if(data.code == 0){
            layer.msg(data.msg, {icon: 1});
            setTimeout(() => {location.reload();}, 700);
        }else{
            layer.msg(data.msg, {icon: 5});
        }
        layer.closeAll('loading');//关闭加载层
    });
    return false; 
});

//编辑标签组
form.on('submit(edit_tags)', function(data){
    console.log(data.field);
    layer.load(2, {shade: [0.1,'#fff']});//加载层
    $.post('./index.php?c=api&method=edit_tags' + "&u=<?php echo $u;?>" ,data.field,function(data,status){
        console.log(data,status);
        if(data.code == 0){
            layer.msg(data.msg, {icon: 1});
            setTimeout(() => {location.reload();}, 700);
        }else{
            layer.msg(data.msg, {icon: 5});
        }
        layer.closeAll('loading');//关闭加载层
    });
    return false; 
});

//设置
form.on('submit(set_tags)', function(data){
    console.log(data.field);
    layer.load(2, {shade: [0.1,'#fff']});//加载层
    $.post('./index.php?c=api&method=set_tags' + "&u=<?php echo $u;?>" ,data.field,function(data,status){
        console.log(data,status);
        if(data.code == 0){
            layer.msg(data.msg, {icon: 1});
            setTimeout(() => {location.reload();}, 700);
        }else{
            layer.msg(data.msg, {icon: 5});
        }
        layer.closeAll('loading');//关闭加载层
    });
    return false; 
});


});
</script>
</div>

</body>
</html>