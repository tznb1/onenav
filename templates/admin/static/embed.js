if (_GET("page") == 'edit_homepage'){ 
$('#TEMPLATE').val(document.getElementById('theme').value);
$('#TEMPLATE2').val(document.getElementById('theme2').value);
layui.use(['form'],function() {
var form=layui.form;
form.render();
}); 
}
 
layui.use(['element','table','layer','form','upload','util'], function(){
    var element = layui.element,
    table = layui.table,
    util = layui.util,
    form = layui.form,
    upload = layui.upload,
    layer = layui.layer,
    $ = layui.$;

//分类列表
table.render({
    elem: '#category_list'
    ,height: 'full-150' //自适应高度
    ,url: 'index.php?c=api&method=category_list&u='+u //数据接口
    ,page: true //开启分页
    ,limit:20  //默认每页显示行数
    ,even:true //隔行背景色
    ,id:'category_list'
    ,loading:true //加载条
    ,cellMinWidth: 150 //最小宽度
    ,cols: [[ //表头
    {type: 'checkbox'},
      {field: 'id', title: 'ID', width:80, sort: true}
      ,{field: 'name', title: '分类名称', width:160, edit: 'text',templet: function(d){ 
          if ( d.Icon == null){ return d.name; }{
          if (d.Icon.substr(0,3) =='lay'){
            return '<i class="layui-icon '+d.Icon+'"></i> '+d.name;
          } else {
            return  '<i class="fa '+d.Icon+'"></i> '+d.name;
          } return d.name;}
      }}
      ,{field: 'add_time', title: '添加时间', width:160, sort: true,templet:function(d){
        var add_time = timestampToTime(d.add_time);
        return add_time;
      }}
      ,{field: 'up_time', title: '修改时间', width:160, sort: true,templet:function(d){
          if(d.up_time != '' && d.up_time != null){
            var up_time = timestampToTime(d.up_time);
            return up_time;}else{return '';}
      }}
      ,{field: 'weight', title: '权重', width: 80, sort: true, edit: 'text' ,align:'center'}
      ,{field: 'count', title: '链接', width: 80, sort: true ,align:'center'}
      ,{field: 'property', title: '私有', width: 100, sort: true,templet: function(d){
        if(d.property == 1) {
         return "<input type='checkbox' value='" + d.id + "' lay-filter='stat' id='property' checked='checked' name='category' lay-skin='switch' lay-text='私有|公开' >";
         }else{
         return "<input type='checkbox' value='" + d.id + "' lay-filter='stat' id='property' name='category'  lay-skin='switch' lay-text='私有|公开' >";}
      }}
      ,{field: 'description', title: '描述', edit: 'text'}
      ,{ title:'操作', toolbar: '#nav_operate', width:150}
    ]]
});
//监听单元格编辑(分类)
table.on('edit(category_list)', function(obj){
var value = obj.value //得到修改后的值
    ,data = obj.data //得到所在行所有键值
    ,field = obj.field; //得到字段
    $.post('./index.php?c=api&method=edit_danyuan&u='+u,{'id':data.id,'field':field,'value':value,'form':'on_categorys'},function(data,status){
    if(data.code == 0){
        layer.msg('修改成功')
        obj.update({up_time:data.t});//修改单元格的更新时间
    } else{layer.msg(data.msg);}});
});
//监听单元格编辑(连接)
table.on('edit(mylink)', function(obj){
var value = obj.value //得到修改后的值
    ,data = obj.data //得到所在行所有键值
    ,field = obj.field; //得到字段
    $.post('./index.php?c=api&method=edit_danyuan&u='+u,{'id':data.id,'field':field,'value':value,'form':'on_links'},function(data,status){
    if(data.code == 0){
        layer.msg('修改成功')
        obj.update({up_time:data.t});
    } else{layer.msg(data.msg);}});
});

//回车和按钮事件
$('#C_keyword').keydown(function (e){if(e.keyCode === 13){category_q();}}); 
$('#link_keyword').keydown(function (e){if(e.keyCode === 13){link_q();}}); 
$('.layui-btn').on('click', function(){
   var type = $(this).data('type');
   active[type] ? active[type].call(this) : '';
});

//事件执行
var active = {
link_reload:function(){link_q();},
C_reload: function(){category_q();},
C_Delete: function(){category_del(0);},
C_ForceDel:function(){category_del(1);},
addcategory:function(){window.open('./index.php?c=admin&page=add_category&u='+u,"_self");},
};
//链接搜索
function link_q(){
var fid = document.getElementById("fid").value;
var keyword = document.getElementById("link_keyword").value;//获取输入内容
console.log(fid,keyword);
table.reload('link_list', {
  url: 'index.php?c=api&method=link_list&u='+u
  ,method: 'post'
  ,request: {
   pageName: 'page' //页码的参数名称，默认：page
   ,limitName: 'limit' //每页数据量的参数名，默认：limit
  }
  ,where: {
   query : keyword,
   fid : fid
  }
  ,page: {
   curr: 1
  }
});
}

//分类删除
function category_del(force){
var checkStatus = table.checkStatus('category_list')
var data = checkStatus.data;
var res = '',id = ''; 
if( data.length == 0 ) {layer.msg('未选中任何数据');return} //没有选中数据,结束运行!
for (let i = 0; i < data.length; i++) {if (i < data.length-1){id +=data[i].id+','}else{id +=data[i].id;$("div.layui-table-body table tbody ").find("tr:eq(" + data[i].id + ")").remove(); }} //生成id表
num=randomnum(4);
layer.prompt({formType: 0,value: '',title: '输入'+num+'确定删除:'},function(value, index, elem){
if(value == num){
    $.post('./index.php?c=api&method=del_category&u='+u,{'id':id,'batch':'1','force':force},function(data,status){
    if(data.code == 0){
    layer.closeAll();//关闭所有层
    category_q(); //刷新数据
    open_msg('600px', '500px','处理结果',data.res);
    } else{layer.msg(data.msg);}});
}else{
    layer.msg('输入内容有误,无需删除请点击取消!', {icon: 5});}
}); 
}
//分类搜索
function category_q(){
var inputVal = $('.layui-input').val();//获取输入内容
table.reload('category_list', {
  url: 'index.php?c=api&method=category_list&u='+u
  ,method: 'post'
  ,request: {
   pageName: 'page' //页码的参数名称，默认：page
   ,limitName: 'limit' //每页数据量的参数名，默认：limit
  }
  ,where: {
   query : inputVal
  }
  ,page: {
   curr: 1
  }
});
}
//删除结果弹出
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
//分类列表工具栏事件
table.on('tool(category_list)', function(obj){
    var data = obj.data;
    //console.log(obj);
    if(obj.event === 'del'){
      layer.confirm('确认删除？',{icon: 3, title:'温馨提示！'}, function(index){
        $.post('./index.php?c=api&method=del_category&u='+u,{'id':obj.data.id},function(data,status){
            if(data.code == 0){
                obj.del();
            }
            else{
                layer.msg(data.msg);
            }
        });
        layer.close(index);
      });
    } else if(obj.event === 'edit'){ 
      window.location.href ='./index.php?c=admin&page=edit_category&id=' + obj.data.id +'&u='+u;
    }
});

//链接列表
var link_list_cols=[[ //表头
      {type:'checkbox'} //开启复选框
      ,{field: 'id', title: 'ID', width:60, sort: true}
      // ,{field: 'fid', title: '分类ID',sort:true, width:90}
      ,{field: 'category_name', title: '所属分类',sort:true,width:140}
      ,{field: 'url', title: 'URL',templet:function(d){
        var url = '<a target = "_blank" href = "' + d.url + '" title = "' + d.url + '">' + d.url + '</a>';
        return url;
      }}
      ,{field: 'title', title: '链接标题', width:200, edit: 'text'}
      ,{field: 'add_time', title: '添加时间', width:160, sort: true,templet:function(d){
        var add_time = timestampToTime(d.add_time);
        return add_time;
      }}
      ,{field: 'up_time', title: '修改时间', width:160,sort:true,templet:function(d){
          if(d.up_time == null){return '';}
          else{var up_time = timestampToTime(d.up_time); return up_time;}
      }} 
      ,{field: 'weight', title: '权重', width: 75,sort:true, edit: 'text'}
      ,{field: 'property', title: '私有', width: 100, sort: true,templet: function(d){
        if(d.property == 1) {
         return "<input type='checkbox' value='" + d.id + "' lay-filter='stat' id='list' checked='checked' name='status'  lay-skin='switch' lay-text='私有|公开' >";}
         else {
         return "<input type='checkbox' value='" + d.id + "' lay-filter='stat' id='list'  name='status'  lay-skin='switch' lay-text='私有|公开' >";}
      }}
      ,{field: 'click', title: '点击数',width:90,sort:true}
      ,{ title:'操作', toolbar: '#link_operate',width:128}
    ]]
intCols();
function intCols()
       {
           for (var i=0;i<link_list_cols[0].length;i++)
           {
               if(link_list_cols[0][i].field!=undefined)
               {
                   let localfield='link_list_'+link_list_cols[0][i].field;
                   let hidevalue =window.localStorage.getItem(localfield);
                   if(hidevalue==='false')
                   {
                       link_list_cols[0][i].hide=false;
                   }else if(hidevalue==='true')
                   {
                       link_list_cols[0][i].hide=true;
                   }
               }
           }
       }

table.render({
    elem: '#link_list'
    ,height: 'full-150' //自适应高度
    ,url: 'index.php?c=api&method=link_list&u='+u //数据接口
    ,page: true //开启分页
    ,limit:20  //默认每页显示行数
    ,even:true //隔行背景色
    ,loading:true //加载条
    ,cellMinWidth: 150 //最小宽度
    ,toolbar: '#linktool'
    ,id:'link_list'
    ,cols: link_list_cols
});

//如果页面是链接列表
if( _GET("page")==="link_list"){
// 选择需要观察变动的节点
const targetNode1 =document.getElementsByClassName('layui-table-tool-self')[0];//document.getElementById('some-id');
// 观察器的配置（需要观察什么变动）
const config = { attributes: true, childList: true, subtree: true };
// 当观察到变动时执行的回调函数
const callback = function(mutationsList, observer) {
	console.log(mutationsList);
	for (let mutation of mutationsList) {
		if (mutation.type === 'childList') {
			// console.log('A child node has been added or removed');
		} else if (mutation.type === 'attributes') {
			console.log(mutation.target.innerText);
			//先根据innertext 列名称 对link_list_cols 进行field 查询,查到field 可以找到本地缓存的字段,约定,本地缓存的命名规则为表名字母缩写_加field名组成,防止冲突
			var field = "";
			for (var i = 0; i < link_list_cols[0].length; i++) {
				if (link_list_cols[0][i].title === mutation.target.innerText) //标题相同 则取field
				{
					field = link_list_cols[0][i].field;
					break;
				}
			}
			if (field !== "") {
				// 组装缓存key
				let localkey = 'link_list_' + field;
				//判断value值
				if (mutation.target.classList[2] != undefined) //说明2: "layui-form-checked"  复选框是已选择的,说明此列是在表中显示的
				{
					window.localStorage.setItem(localkey, false);
				} else //没被选择,说明此列不在table中显示
				{
					window.localStorage.setItem(localkey, true);
				}
			}
		}
	}
};
// 创建一个观察器实例并传入回调函数
const observer = new MutationObserver(callback);
// 以上述配置开始观察目标节点
observer.observe(targetNode1, config);
}//链接列表End
//链接列表工具栏事件
table.on('toolbar(mylink)', function(obj){
    var checkStatus = table.checkStatus(obj.config.id),id='';
    switch(obj.event){
      case 'getCheckData':
        var data = checkStatus.data;
        if( data.length == 0 ) {
          layer.msg('未选中任何数据！');
        }
        //提交批量删除
        else{
            for (let i = 0; i < data.length; i++) {if (i < data.length-1){id +=data[i].id+','}else{id +=data[i].id}} //生成id表
            console.log(id)
            layer.confirm('确认删除选中数据？',{icon: 3, title:'温馨提示！'}, function(index){
                $.post('./index.php?c=api&method=del_link&u='+u,{'id':id,'batch':'1'},function(data,status){
                    if(data.code == 0){
                         layer.open({title:'温馨提醒',content:'选中数据已删除！',yes:function(index,layero){window.location.reload();layer.close(index)}});
                    }else{
                        console.log('删除失败')
                    }
                });
            });
        }
          break;
      case 'MC':
        var data = checkStatus.data;
        if( data.length == 0 ) {
          layer.msg('未选中任何数据！');
        }
        //提交批量修改分类
        else{
            for (let i = 0; i < data.length; i++) {if (i < data.length-1){id +=data[i].id+','}else{id +=data[i].id}} //生成id表
            var fid=document.getElementById("fid");
            var index=fid.selectedIndex ;
            text=fid.options[index].text;
            fid=fid.options[index].value;
            console.log(fid,text,id);
            if (fid ==0){ layer.msg('分类不能为全部!', {icon: 2});return}
            layer.confirm('确定要将所选链接转移到:'+text+'?', {
               title: "批量修改分类:",
               btn: ['确定','取消'] //按钮 Mobile_class
               }, function(){
                $.post('./index.php?c=api&method=Mobile_class&u='+u,{'lid':id ,'cid':fid },function(data,status){
                if(data.code == 0){
                link_q();
                layer.msg('转移成功!', {icon: 1});
                }else{
                layer.msg(data.msg, {icon: 2});
                }
             });
             });
        };
          break;
      case 'addlink':
          window.open('./index.php?c=admin&page=add_link&u='+u,"_self");
          break;
      case 'zhiding':
        var data = checkStatus.data;
        if( data.length == 0 ) {
          layer.msg('未选中任何数据！');
        }
        //置顶
        else{
            for(var i = data.length-1;i!=-1;i--){ if (i != 0){id +=data[i].id+','}else{id +=data[i].id}}console.log(id); //生成id表(逆向)
            $.post('./index.php?c=api&method=edit_tiquan&u='+u,{'id':id ,'value':'置顶','form':'on_links' },function(data,status){
             if(data.code == 0){
             link_q();
             layer.msg('操作成功!', {icon: 1});
             }else{
             layer.msg(data.msg, {icon: 2});
             }
             });
            };
          break;              
    }
});
//链接表头部工具栏事件
  table.on('tool(mylink)', function(obj){
    var data = obj.data;
    console.log(obj.event)
    if(obj.event === 'del'){
      layer.confirm('确认删除？',{icon: 3, title:'温馨提示！'}, function(index){
        $.post('./index.php?c=api&method=del_link&u='+u,{'id':obj.data.id},function(data,status){
            if(data.code == 0){
                obj.del();
            }
            else{
                layer.msg(data.msg);
            }
        });
        layer.close(index);
      });
    } else if(obj.event === 'edit'){
      window.location.href = './index.php?c=admin&page=edit_link&id=' + obj.data.id+'&u='+u;
    }
  });
//分类和连接开关事件
form.on('switch(stat)',
function(obj) {
	var sta;
	var contexts;
	var x = obj.elem.checked; //判断开关状态
	console.log(x) 
	obj.elem.checked = x;
	if (x == true) {
		sta = 1;
		contexts = "私有";
	} else {
		sta = 0;
		contexts = "公开";
	}
	if (obj.elem.id === 'list') {
		$.post('./index.php?c=api&method=edit_property&u=' + u, {
			'id': obj.value,
			'property': sta,
			'form': 'on_links'
		},
		function(data, status) {
			if (data.code == 0) {
			    //obj.elem.checked = x;
			    //form.render();
				layer.msg('ID:' + obj.value + ',已转为' + contexts + '!');
			} else {
				layer.msg('ID:' + obj.value + ',转为' + contexts + '失败!');
				obj.elem.checked = !x;
				form.render();
				layer.close(index);
			}
		});
	} else if (obj.elem.id === 'property') {
		$.post('./index.php?c=api&method=edit_property&u=' + u, {
			'id': obj.value,
			'property': sta,
			'form': 'on_categorys'
		},
		function(data, status) {
			if (data.code == 0) {
				layer.msg('ID:' + obj.value + ',已转为' + contexts + '!');
			} else {
				layer.msg('ID:' + obj.value + ',转为' + contexts + '失败!');
				obj.elem.checked = !x;
				form.render();
				layer.close(index);
			}
		});
	}
});

//添加分类目录
form.on('submit(add_category)', function(data){
    $.post('./index.php?c=api&method=add_category&u='+u,data.field,function(data,status){
      //如果添加成功
      if(data.code == 0) {
        layer.msg('已添加！', {icon: 1});
      }
      else{
        layer.msg(data.msg, {icon: 5});
      }
    });
    console.log(data.field) 
    return false; 
});

//首页设置
form.on('submit(edit_homepage)', function(data){
    $.post('./index.php?c=api&method=edit_homepage&u='+u,data.field,function(data,status){
      //如果添加成功
      if(data.code == 0) {
        layer.msg('已修改！', {icon: 1});
      }
      else{
        layer.msg(data.msg, {icon: 5});
      }
    });
    console.log(data.field) 
    return false; 
});
//账号设置
form.on('submit(edit_user)', function(data){
    if(data.field.password ==''){layer.msg('为了您账号安全,请输入密码在提交!', {icon: 5});return;}
    data.field.password = $.md5(data.field.password);
    if(data.field.newpassword !=''){
        data.field.newpassword = $.md5(data.field.newpassword);
    }
    console.log(data.field) 
    $.post('./index.php?c=api&method=edit_user&u='+u,data.field,function(data,status){
      if(data.code == 0) {
          if(data.logout == 1){alert("修改成功,请重新登陆!点击确定返回首页!"); window.location.href = './index.php?u='+data.u;  return false;}//修改了账号密码,跳到主页!
          layer.msg(data.msg, {icon: 1});
      }
      else{
          layer.msg(data.msg, {icon: 5});
      }
    });
    return false; 
});  
//生成令牌
 form.on('submit(Gtoken)', function(data){
    var Token=randomString(32);
    document.getElementById("NewToken").value = Token;
    open_msg('320px', '250px','API Token(令牌) 使用说明','<div style="padding: 15px;">'+Token+'<br>↑这是您的令牌,请妥善保管↑<br>点击保存配置即刻生效!<br></div>');
    return false; 
  }); 

//修改分类目录
form.on('submit(edit_category)', function(data){
    $.post('./index.php?c=api&method=edit_category&u='+u,data.field,function(data,status){
      //如果添加成功
      if(data.code == 0) {
        layer.msg('已修改！', {icon: 1});
      }
      else{
        layer.msg(data.msg, {icon: 5});
      }
    });
    console.log(data.field) 
    return false; 
});
//全局配置
form.on('submit(edit_root)', function(data){
    console.log(data.field) 
    $.post('./index.php?c=api&method=edit_root&u='+u,data.field,function(data,status){
      if(data.code == 0) {
        layer.msg('已修改！', {icon: 1});
      }
      else{
        layer.msg(data.msg, {icon: 5});
      }
    });
    return false; 
});  
//添加链接
form.on('submit(add_link)', function(data){
    $.post('./index.php?c=api&method=add_link&u='+u,data.field,function(data,status){
      //如果添加成功
      if(data.code == 0) {
        layer.msg('已添加！', {icon: 1});
      }
      else{
        layer.msg(data.msg, {icon: 5});
      }
    });
    console.log(data.field) 
    return false; 
});
//识别链接信息
form.on('submit(get_link_info)', function(data){
    $.post('./index.php?c=api&method=get_link_info&u='+u,data.field.url,function(data,status){
      if(data.code == 0) {
        console.log(data);
      }
      else{
        layer.msg(data.msg, {icon: 5});
      }
    });
    console.log(data.field) 
    return false; 
});
//更新链接
form.on('submit(edit_link)', function(data){
    $.post('./index.php?c=api&method=edit_link&u='+u,data.field,function(data,status){
      if(data.code == 0) {
        layer.msg('已更新！', {icon: 1});
      }
      else{
        layer.msg(data.msg, {icon: 5});
      }
    });
    console.log(data.field) 
    return false;
});
//识别链接信息
form.on('submit(get_link_info)', function(data){
    //是用ajax异步加载
    $.post('./index.php?c=api&method=get_link_info&u='+u,data.field,function(data,status){
      if(data.code == 0) {
        console.log(data);
      }
      else{
        layer.msg(data.msg, {icon: 5});
      }
    });
    console.log(data.field) 
    return false; 
});
//导入书签
form.on('submit(imp_link)', function(data){
    layer.load(1, {shade:[0.1,'#fff']});//加载层
    //用ajax异步加载
    $.post('./index.php?c=api&method=imp_link&u='+u,data.field,function(data,status){
        layer.closeAll();//关闭所有层
      //如果添加成功
      if(data.code == 0) {
          if (data.fail > 0)
          {open_msg('800px', '600px',data.msg,data.res);}
          else{layer.open({title:'导入完成',content:data.msg});}
      }
      else{
        layer.msg(data.msg, {icon: 5});
        
      }
    });
    
    console.log(data.field) 
    return false; 
});
//书签上传
//执行实例
upload.render({
    elem: '#up_html' //绑定元素
    ,url: './index.php?c=api&method=upload&u='+u //上传接口
    ,exts: 'html|HTML|db3'
    ,accept: 'file'
    ,done: function(res){
      //console.log(res);
      //上传完毕回调
      if( res.code == 0 ) {
        $("#filename").val(res.file_name);
      }
      else if( res.code < 0) {
        layer.msg(res.msg, {icon: 5});
        layer.close();
      }
    }
    ,error: function(){
      //请求异常回调
    }
  });
  
form.on('select(session)', function (data) {
    //获取当前选中下拉项的索引
    var indexGID = data.elem.selectedIndex;
    //获取当前选中下拉项的 value值
    var goodsID = data.value;
    //判断是否选的0
    if(goodsID == '0'){
        open_msg('320px', '250px','注意','<div style="padding: 15px;">超过24小时未关闭浏览器也会失效<br></div>');
    }
    });
//结束
});



//异步识别链接信息
function get_link_info() {
    var url = $("#url").val();
    var index = layer.load(1);
    $.post('./index.php?c=api&method=get_link_info&u='+u,{url:url},function(data,status){
      //如果添加成功
      if(data.code == 0) {
        if(data.data.title != null) {
          $("#title").val(data.data.title);
        }
        if(data.data.description != null) {
          $("#description").val(data.data.description);
        }
        
        layer.close(index);
      }
      else{
        layer.msg(data.msg, {icon: 5});
        layer.close(index);
      }
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
//取随机字符串
function randomString(length) {
  var str = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  var result = '';
  for (var i = length; i > 0; --i) 
    result += str[Math.floor(Math.random() * str.length)];
  return result;
}
//取随机数字
function randomnum(length) {
  var str = '0123456789';
  var result = '';
  for (var i = length; i > 0; --i) 
    result += str[Math.floor(Math.random() * str.length)];
  return result;
}
//取Get参数
function _GET(variable){
   var query = window.location.search.substring(1);
   var vars = query.split("&");
       for (var i=0;i<vars.length;i++) {
               var pair = vars[i].split("=");
               if(pair[0] == variable){return pair[1];}
       }
       return(false);
}

function check_weak_password(){
  
        layer.open({
          title:'风险提示！',
          content: '系统检测到您使用的默认密码，请参考<a href = "https://dwz.ovh/ze1ts" target = "_blank" style = "color:#01AAED;">帮助文档</a>尽快修改！' //这里content是一个普通的String
        });
     
}