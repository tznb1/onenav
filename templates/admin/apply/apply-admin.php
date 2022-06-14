<?php 
require_once(dirname(__DIR__).'/header.php');
include_once(dirname(__DIR__).'/left.php'); 
$categorys = [];
    //获取父分类
    $category_parent = $db->select('on_categorys','*',["fid"   =>  0,"ORDER" =>  ["weight" => "DESC"]]);
    //遍历父分类下的二级分类
    foreach ($category_parent as $category) {
        array_push($categorys,$category);
        $category_subs = $db->select('on_categorys','*',["fid" => $category['id'],"ORDER" => ["weight" => "DESC"] ]);
        //合并数组
        $categorys = array_merge ($categorys,$category_subs);
    }
?>

<div class="layui-body" style=" padding-bottom: 15px; ">
  <div class="layui-row content-body" style="margin-top: 0px;margin-left: 0px;margin-right: 0px;">
    <div class="layui-col-lg12">
        <table id="apply_list" lay-filter="apply_list"></table>
        <script type="text/html" id="user_tool">
            <div class="layui-btn-container">
            <button class="layui-btn layui-btn-sm " lay-event="conf" >设置</button>
            <button class="layui-btn layui-btn-sm layui-btn-danger" lay-event="empty" >清空收录申请</button>
        </div>
        </script>
        <!-- 开启表格头部工具栏END -->
    </div>
    <script type="text/html" id="link_operate">
    <a class="layui-btn layui-btn-xs" lay-event="operation">操作 <i class="layui-icon layui-icon-down"></i></a>
    </script>
  </div>
</div>

<!--设置-->
<ul class="conf" style = "margin-top:18px;display:none;padding-right: 10px;" >
    <form class="layui-form" lay-filter="conf">
        <div class="layui-form-item" >
            <input id="apply_switch-input" type="hidden" value="<?php echo getconfig('apply_switch','0');?>">
            <label class="layui-form-label">申请收录</label>
            
            <div class="layui-input-inline">
                <select lay-verify="required"  id="apply" name="apply" lay-search>
                    <option value="0">关闭申请</option>
                    <option value="1">需要审核</option>
                    <option value="2">无需审核</option>
                </select>
            </div>
            <div class="layui-form-mid layui-word-aux">此功能存在安全隐患,请慎用!特别是无需审核!</div>
        </div>
        <div class="layui-form-item layui-form-text">
            <label class="layui-form-label">收录公告</label>
            <div class="layui-input-block">
            <textarea name = "Notice" placeholder="显示在收录页的公告使用HTML代码编写(如有拦截提示,请暂时关闭防XSS脚本和防SQL注入)" rows = "5" class="layui-textarea"><?php echo getconfig('apply_Notice','<b>收录说明：</b><br>1. 禁止提交违规违法站点<br>2. 页面整洁，无多个弹窗广告和恶意跳转<br>3. 非盈利性网站，网站正常访问<br>4. 添加本站友链或网站已ICP备案优先收录<br>');?></textarea>
            </div>
        </div>
        <div class="layui-form-item layui-form-text">
            <label class="layui-form-label">使用说明</label>
            <div class="layui-form-mid ">部分主题没有收录入口,请自行添加到链接或者底部等你认为合适的地方!前往<a style="color:#3c78d8" target="_blank" href="./index.php?c=apply&u=<?php echo $u?>" target="_blank">申请收录</a></div>
        </div>
        <div class="layui-form-item layui-form-text">
            <label class="layui-form-label">安全限制</label>
            <div class="layui-form-mid ">1.禁止含有特殊字符<'&">等 &nbsp;  2.SQL和XSS相关的敏感词  &nbsp; 3.限制超过256个字符 <br /> 4.提交限频:IP/24小时/5次 (删除记录可恢复)</div>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit lay-filter="conf">保存设置</button>
            </div>
        </div>
  </form>
</ul>

<!--详情-->
<ul class="details" style = "margin-top:18px;display:none;padding-right: 10px;" >
    <form class="layui-form" lay-filter="details">
  <div class="layui-form-item">
    <label class="layui-form-label">网站标题</label>
    <div class="layui-input-block">
      <input type="text" name="title" disabled class="layui-input">
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">网站链接</label>
    <div class="layui-input-block">
      <input type="text" name="url" disabled class="layui-input">
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">网站图标</label>
    <div class="layui-input-block">
      <input type="text" name="iconurl" disabled class="layui-input">
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">网站描述</label>
    <div class="layui-input-block">
      <input type="text" name="description" disabled class="layui-input">
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">分类名称</label>
    <div class="layui-input-block">
      <input type="text" name="category" disabled class="layui-input">
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">联系邮箱</label>
    <div class="layui-input-block">
      <input type="text" name="email" disabled class="layui-input">
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">申请者IP</label>
    <div class="layui-input-block">
      <input type="text" name="ip" disabled class="layui-input">
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">申请者UA</label>
    <div class="layui-input-block">
      <input type="text" name="ua" disabled class="layui-input">
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">申请时间</label>
    <div class="layui-input-block">
      <input type="text" name="time" disabled class="layui-input">
    </div>
  </div>
  </form>
</ul>
<!--编辑-->
<ul class="edit" style = "margin-top:18px;display:none;padding-right: 10px;" >
    <form class="layui-form" lay-filter="edit">
  <div class="layui-form-item" style = "display:none;">
    <label class="layui-form-label">ID</label>
    <div class="layui-input-block">
      <input type="text" name="id" required  disabled lay-verify="required" class="layui-input">
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">网站标题</label>
    <div class="layui-input-block">
      <input type="text" name="title" required  lay-verify="required" class="layui-input">
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">网站链接</label>
    <div class="layui-input-block">
      <input type="text" name="url" required  lay-verify="required" class="layui-input">
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">网站图标</label>
    <div class="layui-input-block">
      <input type="text" name="iconurl"  class="layui-input">
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">网站描述</label>
    <div class="layui-input-block">
      <input type="text" name="description"  class="layui-input">
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">网站分类</label>
    <div class="layui-input-block">
      <select name="edit_category" required lay-verify="required" lay-search>
        <?php foreach ($categorys as $category) {
        ?>
        <option value="<?php echo $category['id'] ?>"><?php echo ($category['fid'] == 0 ? "":"├ ").$category['name']; ?></option>
        <?php } ?>
      </select>
    </div>
  </div>
    <div class="layui-form-item">
    <div class="layui-input-block">
      <button class="layui-btn" lay-submit lay-filter="edit_serv">保存</button>
    </div>
  </div>
  </form>
</ul>
<script src = '<?php echo $libs?>/jquery/jquery-3.6.0.min.js'></script>
<script src = '<?php echo $libs?>/Layui/v2.6.8/layui.js'></script>
<script>
$('#apply').val(document.getElementById('apply_switch-input').value); 

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

//表头
var cols=[[
      //{type:'checkbox'}, //开启复选框
      {field:'id',title:'ID',width:60,sort:true}
      ,{field:'iconurl',title:'图标',width:60,templet:function(d){
          if (d.iconurl.length !== 0){
              return '<img style="width: 28px" src="' + d.iconurl + '" />'
          }else{
              return '无';
          }
      }}
      ,{field:'title',title:'名称',minWidth:150,sort:true}
      ,{field:'url',title:'链接',minWidth:120,sort:true,templet:function(d){
          return '<a style="color:#3c78d8" target="_blank" href="'+d.url+'">'+d.url+'</a>'
      }}
      ,{field:'description',title:'描述',minWidth:120,sort:true}
      ,{field:'category_name',title:'分类',minWidth:120,sort:true}
      ,{field:'email',title:'Email',minWidth:120,sort:true}
      ,{field:'ip',title:'申请者IP',minWidth:140,sort:true,templet:function(d){
          return '<a style="color:#3c78d8" title="查询归属地" target="_blank" href="//ip.cn/?ip='+d.ip+'">'+d.ip+'</a>'
      }}
      ,{field:'time',title: '申请时间',minWidth:160,sort:true,templet:function(d){
          if(d.time == null){return '';}
          else{return timestampToTime(d.time);}}} 
      ,{field:'state',title:'状态',width:120,templet:function(d){
          if (d.state == 0){
              return '待审核'
          }else if(d.state == 1){
              return '手动通过';
          }else if(d.state == 2){
              return '已拒绝';
          }else if(d.state == 3){
              return '自动通过';
          }else{
              return 'null';
          }
      }} 
      ,{title:'操作',toolbar:'#link_operate',width:130}
    ]]
    // 0.待审核 1.手动通过 2.已拒绝 3.自动通过
//表渲染
table.render({
    elem: '#apply_list'
    ,height: 'full-110' //自适应高度
    ,url: './index.php?c=api&method=apply_list&u=<?php echo $u;?>' //数据接口
    ,page: true //开启分页
    ,limit:limit  //默认每页显示行数
    ,even:true //隔行背景色
    ,loading:true //加载条
    ,toolbar: '#user_tool'
    ,id:'apply_list'
    ,cols: cols
});


//用户行工具栏事件
table.on('tool(apply_list)', function(obj){
    var data = obj.data;
    var that = this;
    console.log(data)
    if(obj.event === 'operation'){
      //更多下拉菜单
      // 0.待审核 1.手动通过 2.已拒绝 3.自动通过 
      if(data.state == 0){
          menu = [{title: '详情',id: 0},{title: '编辑',id: 1},{title: '通过',id: 2}, {title: '拒绝',id: 3}, {title: '删除',id: 4}];
      }else if(data.state == 1 || data.state == 3 ) {
          menu = [{title: '详情',id: 0},{title: '删除',id: 4}];
      }else {
          menu = [{title: '详情',id: 0},{title: '删除',id: 4}];
      }
      dropdown.render({
        elem: that
        ,show: true //外部事件触发即显示
        ,data: menu
        ,click: function(d, othis){
          //根据 id 做出不同操作
          
          if(d.id === 0){
              form.val('details', {
                "title": data.title
                ,"url": data.url
                ,"iconurl": data.iconurl
                ,"description": data.description
                ,"email": data.email
                ,"category": data.category_name + '  ID:'+ data.category_id 
                ,"ip": data.ip
                ,"ua": data.ua
                ,"time":timestampToTime(data.time)
            });
            if(document.body.clientWidth < 768){area = ['100%' , '100%'];}else{area = ['768px' , '570px'];}
              layer.open({
                type: 1,
                shadeClose: true,
                title: '详情',
                area : area,
                content: $('.details')
            });
          }else if(d.id === 1){
              form.val('edit', {
                "title": data.title
                ,"id": data.id
                ,"url": data.url
                ,"iconurl": data.iconurl
                ,"edit_category": data.category_id
                ,"description": data.description
            });
            if(document.body.clientWidth < 768){area = ['100%' , '100%'];}else{area = ['768px' , '420px'];}
              layer.open({
                type: 1,
                shadeClose: true,
                title: '编辑',
                area : area,
                content: $('.edit')
              });
          }else{
              layer.load(2, {shade: [0.1,'#fff']});//加载层
              $.post('./index.php?c=api&method=apply_fn&fn=' + d.id +"&u=<?php echo $u;?>",{"id" : data.id },function(data,status){
                if(data.code == 0){
                    layer.msg(data.msg, {icon: 1});
                    setTimeout(() => {location.reload();}, 700);
                }else{
                    layer.msg(data.msg, {icon: 5});
                }
                layer.closeAll('loading');//关闭加载层
              });
          }
        }
      }); 
    }
});

//表头工具
table.on('toolbar(apply_list)', function(obj){
    var checkStatus = table.checkStatus(obj.config.id),id='';
    var data = checkStatus.data;
    switch(obj.event){
        case 'conf':
            if(document.body.clientWidth < 768){area = ['100%' , '100%'];}else{area = ['768px' , '450px'];}
            layer.open({
                type: 1,
                shadeClose: true,
                title: '收录设置',
                area : area,
                content: $('.conf')
            });
        break;
        
        case 'empty':
            layer.confirm('确定清空数据？',{icon: 3, title:'温馨提示！'}, function(index){
                layer.load(2, {shade: [0.1,'#fff']});//加载层
                $.post('./index.php?c=api&method=apply_fn&fn=' + '40' +"&u=<?php echo $u;?>",{"id" : data.id },function(data,status){
                    if(data.code == 0){
                        layer.msg(data.msg, {icon: 1});
                        setTimeout(() => {location.reload();}, 700);
                    }else{
                        layer.msg(data.msg, {icon: 5});
                    }
                    layer.closeAll('loading');//关闭加载层
                });
            });
        break;
      
    }
});

    //保存配置
    form.on('submit(conf)', function(data){
        console.log(data.field);
        layer.load(2, {shade: [0.1,'#fff']});//加载层
        $.post('./index.php?c=api&method=apply_save' + "&u=<?php echo $u;?>" ,data.field,function(data,status){
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
    
    //保存配置
    form.on('submit(edit_serv)', function(data){
        if( data.field.edit_category.length == 0){
            layer.msg('请选择分类!', {icon: 5});
            return false; 
        } 
        layer.load(2, {shade: [0.1,'#fff']});//加载层
        $.post('./index.php?c=api&method=apply_fn&fn=1' + "&u=<?php echo $u;?>"  ,data.field,function(data,status){
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

//取Cookie
function getCookie(cname){
	var name = cname + "=";
	var ca = document.cookie.split(';');
	for(var i=0; i<ca.length; i++) {
		var c = ca[i].trim();
		if (c.indexOf(name)==0) { return c.substring(name.length,c.length); }
	}
	return "";
}
});
</script>
</body>
</html>