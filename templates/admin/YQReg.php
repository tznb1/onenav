<?php 
include_once('header.php');
include_once('left.php'); 
if($udb->get("user","Level",["User"=>$u]) != 999){
    include_once('footer.php');
    exit;
}

?>

<style type="text/css">
.layui-layout-admin .layui-body {top: 40px;}
</style>
<div class="layui-tab layui-tab-brief layui-body layui-row content-body" lay-filter="root" style="padding-bottom: 0px;">
<ul class="layui-tab-title">
 <li class="layui-this">邀请注册</li>
</ul>
<div class="layui-tab-content" >

<div class="layui-tab-item layui-show layui-form layui-form-pane" ><!--用户管理-->
<div class="layui-row content-body" style="margin-top: 0px;margin-left: 0px;margin-right: 0px;">
    <div class="layui-col-lg12">
        <table id="list" lay-filter="list"></table>
        <script type="text/html" id="toolbar">
        <div class="layui-btn-container">
            <button type="button" class="layui-btn layui-btn-sm" lay-event="Add">生成</button>
            <button type="button" class="layui-btn layui-btn-sm" lay-event="Refresh">刷新</button>
            <button type="button" class="layui-btn layui-btn-sm" lay-event="Set">设置</button>
        </div>
        </script>
        <!-- 开启表格头部工具栏END -->
    </div>
</div>

</div><!--用户管理End-->
</div>
</div>
</div>

<!--设置-->
<ul class="Set" style = "margin-top:18px;display:none;padding-right: 10px;" >
    <form class="layui-form" lay-filter="Set">
        <div class="layui-form-item" >
            <label class="layui-form-label">获取邀请</label>
            <div class="layui-input-block">
                <textarea name = "url" placeholder="获取邀请的地址,留空则不显示!非http开头时将作为提示信息弹出!" rows = "7" class="layui-textarea"><?php echo UGet('Get_Invitation'); ?></textarea>
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit lay-filter="Set">保存</button>
            </div>
        </div>
  </form>
</ul>

<script src = '<?php echo $libs?>/jquery/jquery-3.6.0.min.js'></script>
<script src = '<?php echo $libs?>/Layui/v2.6.8/layui.js'></script>
<script src = '<?php echo $libs?>/jquery/jquery.md5.js'></script>
<script>
var host = '<?php echo getindexurl().'?c='.$Register."&u={$u}&key=";?>';

layui.use(['element','table','layer','form','util','dropdown'], function(){
    var element = layui.element;
    var table = layui.table;
    var util = layui.util;
    var form = layui.form;
    var dropdown = layui.dropdown;
    layer = layui.layer;
    

var cols=[[ //表头
      {field:'order',title:'序号',width:80,sort:true}
      ,{field:'key',title:'注册码',width:120,sort:true}
      ,{field:'url',title:'注册链接',width:600,sort:true,templet:function(d){
          return host + d.key;
      }}
      ,{field:'add_time',title:'生成时间',width:160,sort:true,templet:function(d){
          if(d.add_time == null){
              return '';
          }else{
              return timestampToTime(d.add_time);
          }
      }} 
      ,{field:'desc',title:'状态',sort:true,templet:function(d){
          if(d.use_time == null){
              return d.desc;
          }else{
              return timestampToTime(d.use_time) + ',' + d.desc ;
          }
      }} 
    ]]
    
    
//用户表渲染
table.render({
    elem: '#list'
    ,height: 'full-160' //自适应高度
    ,url: './index.php?c=api&method=Reg&sn=list&u=<?php echo $u;?>' //数据接口
    ,page: false 
    ,even:true //隔行背景色
    ,loading:true //加载条
    ,toolbar: '#toolbar'
    ,id:'list'
    ,cols: cols
});

//头工具栏事件
table.on('toolbar(list)', function(obj){
    var checkStatus = table.checkStatus(obj.config.id);
    switch(obj.event){
        case 'Add':
        $.post('./index.php?c=api&method=Reg&u=<?php echo $u;?>',{'sn':'add'},function(data,status){
            if(data.code == 0){
                layer.msg('添加成功',{ icon: 1 })
                table.reload('list');
            }else{
                layer.msg(data.msg,{ icon: 5 } );
            }
        });
        break;
      
      
      //刷新表格
      case 'Refresh':
        table.reload('list');
      break;
      
      //设置
      case 'Set':
        if(document.body.clientWidth < 768){area = ['100%' , '100%'];}else{area = ['800px' , '300px'];}
            layer.open({
                type: 1,
                shadeClose: true,
                title: '设置',
                area : area,
                content: $('.Set')
            });
      break;
      
    };
});

//保存设置
form.on('submit(Set)', function(data){
    $.post('./index.php?c=api&method=Reg&sn=Set' + "&u=<?php echo $u;?>" ,data.field,function(data,status){
        if(data.code == 0){
            layer.msg(data.msg, {icon: 1});
            setTimeout(() => {location.reload();}, 700);
        }else{
            layer.msg(data.msg, {icon: 5});
        }
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

});
</script>
</body>
</html>