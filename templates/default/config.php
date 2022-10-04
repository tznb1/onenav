<?php

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    Writeconfig($config.'HeadBackgroundColor',$_POST['HeadBackgroundColor']);
    Writeconfig($config.'SidebarBackgroundColor',$_POST['SidebarBackgroundColor']);
    Writeconfig($config.'CardBackgroundColor',$_POST['CardBackgroundColor']);
    Writeconfig($config.'OtherBackgroundColor',$_POST['OtherBackgroundColor']);
    Writeconfig($config.'HeadFontColor',$_POST['HeadFontColor']);
    Writeconfig($config.'CategoryFontColor',$_POST['CategoryFontColor']);
    Writeconfig($config.'TitleFontColor',$_POST['TitleFontColor']);
    Writeconfig($config.'DescrFontColor',$_POST['DescrFontColor']);
    
    if($_GET['local'] == 'PresetColor'){
        //预设配色修改
        msg(0,"修改成功"); 
    }
    Writeconfig($config.'CardNum',intval($_POST['CardNum']));
    Writeconfig($config.'backgroundURL',$_POST['backgroundURL']);
    Writeconfig($config.'DescrRowNumber',intval($_POST['DescrRowNumber']));
    Writeconfig($config.'night',intval($_POST['night']));
    Writeconfig($config.'WeatherBackground',intval($_POST['WeatherBackground']));
    Writeconfig($config.'WeatherKey',$_POST['WeatherKey']);
    Writeconfig($config.'WeatherFontColor',$_POST['WeatherFontColor']);
    Writeconfig($config.'WeatherPosition',$_POST['WeatherPosition']);
    Writeconfig($config.'ClickLocation',$_POST['ClickLocation']);
    


    msg(0,"修改成功");
}

?>
 
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <title>主题DIY</title>
  <link rel='stylesheet' href='<?php echo $libs?>/Layui/v2.6.8/css/layui.css'>
  <style>    
    .layui-form-item {
        margin-bottom: 10px;
        height: 38px;
    }
  </style>
</head>
<body>
<form class="layui-form">
<div class="layui-tab layui-tab-brief" lay-filter="docDemoTabBrief">

  <ul class="layui-tab-title">
    <li class="layui-this">配色</li>
    <li>设置</li>
    <li>天气</li>
    <li>搜索</li>
  </ul>
  
  <div class="layui-tab-content">
      <!--配色Tab-->
      <div class="layui-tab-item layui-show">
        <div class="layui-form-item">
            <label class="layui-form-label">使用须知</label>
            <div class="layui-form-mid layui-word-aux" style="color: #d40909!important;">全部配色和背景图在夜间模式状态下无效！
            <a target="_blank" style="color:#03a9f4!important;font-weight:bold;font-size:16px" href='https://gitee.com/tznb/OneNav/wikis/主题说明/默认主题(加强)'>使用说明</a></div>
        </div>
        
        <div class="layui-form-item">
            <label class="layui-form-label">头部背景色</label>
            <div class="layui-input-inline" >
             <input type="text" value="<?php echo getconfig($config.'HeadBackgroundColor');?>" placeholder="请选择颜色" class="layui-input" id="HeadBackgroundColor-input" name="HeadBackgroundColor">
            </div>
            <div class="layui-inline" style="left: -11px;"><div id="HeadBackgroundColor"></div><label class="layui-word-aux">顶部工具栏</label></div>
        </div>
        
        <div class="layui-form-item">
            <label class="layui-form-label">卡片背景色</label>
            <div class="layui-input-inline" >
             <input type="text" value="<?php echo getconfig($config.'CardBackgroundColor');?>" placeholder="请选择颜色" class="layui-input" id="CardBackgroundColor-input" name="CardBackgroundColor">
            </div>
            <div class="layui-inline" style="left: -11px;"><div id="CardBackgroundColor"></div><label class="layui-word-aux">链接信息卡片</label></div>
        </div>
        
        <div class="layui-form-item">
            <label class="layui-form-label">其他背景色</label>
            <div class="layui-input-inline" >
             <input type="text" value="<?php echo getconfig($config.'OtherBackgroundColor');?>" placeholder="请选择颜色" class="layui-input" id="OtherBackgroundColor-input" name="OtherBackgroundColor">
            </div>
            <div class="layui-inline" style="left: -11px;"><div id="OtherBackgroundColor"></div><label class="layui-word-aux">主要的背景色</label></div>
        </div>
        
        <div class="layui-form-item">
            <label class="layui-form-label">边栏背景色</label>
            <div class="layui-input-inline" >
             <input type="text" value="<?php echo getconfig($config.'SidebarBackgroundColor');?>" placeholder="请选择颜色" class="layui-input" id="SidebarBackgroundColor-input" name="SidebarBackgroundColor">
            </div>
            <div class="layui-inline" style="left: -11px;"><div id="SidebarBackgroundColor"></div><label class="layui-word-aux">留空则继承其他</label></div>
        </div>
        
        <div class="layui-form-item">
            <label class="layui-form-label">头部字体色</label>
            <div class="layui-input-inline" >
             <input type="text" value="<?php echo getconfig($config.'HeadFontColor');?>" placeholder="请选择颜色" class="layui-input" id="HeadFontColor-input" name="HeadFontColor">
            </div>
            <div class="layui-inline" style="left: -11px;"><div id="HeadFontColor"></div><label class="layui-word-aux">顶部工具栏</label></div>
        </div>
    
        <div class="layui-form-item">
            <label class="layui-form-label">分类字体色</label>
            <div class="layui-input-inline" >
             <input type="text" value="<?php echo getconfig($config.'CategoryFontColor');?>" placeholder="请选择颜色" class="layui-input" id="CategoryFontColor-input" name="CategoryFontColor">
            </div>
            <div class="layui-inline" style="left: -11px;"><div id="CategoryFontColor"></div><label class="layui-word-aux">分类名称</label></div>
        </div>
        
        <div class="layui-form-item">
            <label class="layui-form-label">标题字体色</label>
            <div class="layui-input-inline" >
             <input type="text" value="<?php echo getconfig($config.'TitleFontColor');?>" placeholder="请选择颜色" class="layui-input" id="TitleFontColor-input" name="TitleFontColor">
            </div>
            <div class="layui-inline" style="left: -11px;"><div id="TitleFontColor"></div><label class="layui-word-aux">链接标题</label></div>
        </div>
        
        <div class="layui-form-item">
            <label class="layui-form-label">描述字体色</label>
            <div class="layui-input-inline" >
             <input type="text" value="<?php echo getconfig($config.'DescrFontColor');?>" placeholder="请选择颜色" class="layui-input" id="DescrFontColor-input" name="DescrFontColor">
            </div>
            <div class="layui-inline" style="left: -11px;"><div id="DescrFontColor"></div><label class="layui-word-aux">描述和备案号共享</label></div>
        </div>
        
        <div class="layui-form-item">
            <input id="PresetColor-input" type="hidden" value="<?php echo getconfig($config.'PresetColor','0');?>">
            <label class="layui-form-label">预设配色</label>
            <div class="layui-input-inline">
             <select id="PresetColor" name="PresetColor" lay-filter="PresetColor" lay-search>
              <option></option>
              <option value="0">默认配色</option>
              <option value="1">黑底白字</option>
              <option value="2">黑底金字</option>
              <option value="3">淡绿护眼</option>
              <option value="4">深绿护眼</option>
              <option value="5">粉底黑字</option>
             </select>
            </div>
            <div class="layui-form-mid layui-word-aux">征集配色方案啊...</div>
        </div>
      </div>
      <!--设置Tab-->
      <div class="layui-tab-item">
        <div class="layui-form-item">
            <input id="CardNum-input" type="hidden" value="<?php echo getconfig($config.'CardNum','0');?>">
            <label class="layui-form-label">卡片数量</label>
            <div class="layui-input-inline">
            <select lay-verify="required"  id="CardNum" name="CardNum" lay-search>
            <option value="1">最多10张</option>
            <option value="2">最多9张</option>
            <option value="0">最多4张</option>
            </select>
            </div>
            <div class="layui-form-mid layui-word-aux">横向显示的卡片数量</div>
        </div>
        
        <div class="layui-form-item">
            <input id="DescrRowNumber-input" type="hidden" value="<?php echo getconfig($config.'DescrRowNumber','2');?>">
            <label class="layui-form-label">描述行数</label>
            <div class="layui-input-inline">
            <select lay-verify="required"  id="DescrRowNumber" name="DescrRowNumber" lay-search>
            <option value="0">0 (隐藏)</option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            </select>
            </div>
            <div class="layui-form-mid layui-word-aux">链接描述显示的行数</div>
        </div>            
  
        <div class="layui-form-item">
            <input id="night-input" type="hidden" value="<?php echo getconfig($config.'night','0');?>">
            <label class="layui-form-label">夜间模式</label>
            <div class="layui-input-inline">
            <select lay-verify="required"  id="night" name="night" lay-search>
            <option value="0">关闭</option>
            <option value="1">开启</option>
            <option value="2">自动</option>
            </select>
            </div>
            <div class="layui-form-mid layui-word-aux">自动: 19:00-07:00 开启夜间模式</div>
        </div>
        
        <div class="layui-form-item">
            <input id="ClickLocation-input" type="hidden" value="<?php echo getconfig($config.'ClickLocation','0');?>">
            <label class="layui-form-label">点击位置</label>
            <div class="layui-input-inline">
            <select lay-verify="required"  id="ClickLocation" name="ClickLocation" lay-search>
            <option value="0">整个卡片</option>
            <option value="1">仅链接标题</option>
            </select>
            </div>
            <div class="layui-form-mid layui-word-aux">链接卡片可以点击的范围</div>
        </div>
  
        <div class="layui-form-item">
            <label class="layui-form-label">背景图URL</label>
            <div class="layui-input-inline" style="width: 73%;">
            <input type="url" id = "backgroundURL" name="backgroundURL" value = "<?php echo getconfig($config.'backgroundURL','');?>" placeholder="存在时其他背景色无效,请输入图片URL" autocomplete="off" class="layui-input">
            </div>
        </div>
      
      </div>
      <!--天气Tab-->
      <div class="layui-tab-item">
        <div class="layui-form-item">
            <input id="WeatherPosition-input" type="hidden" value="<?php echo getconfig($config.'WeatherPosition','0');?>">
            <label class="layui-form-label">插件位置</label>
            <div class="layui-input-inline">
            <select lay-verify="required"  id="WeatherPosition" name="WeatherPosition" lay-search>
            <option value="0">关闭</option>
            <option value="1">头部工具条</option>
            <option value="2">正文右上角</option>
            </select>
            </div>
            <div class="layui-form-mid layui-word-aux">插件显示的位置</div>
        </div>
        
        <div class="layui-form-item">
            <input id="WeatherBackground-input" type="hidden" value="<?php echo getconfig($config.'WeatherBackground','4');?>">
            <label class="layui-form-label">天气背景</label>
            <div class="layui-input-inline">
            <select lay-verify="required"  id="WeatherBackground" name="WeatherBackground" lay-search>
            <option value="1">随天气变化</option>
            <option value="2">浅色</option>
            <option value="3">深色</option>
            <option value="4">透明</option>
            </select>
            </div>
            <div class="layui-form-mid layui-word-aux">天气插件的背景颜色</div>
        </div>
        
        <div class="layui-form-item">
            <input id="WeatherFontColor-input" type="hidden" value="<?php echo getconfig($config.'WeatherFontColor','2');?>">
            <label class="layui-form-label">天气字体色</label>
            <div class="layui-input-inline">
            <select lay-verify="required"  id="WeatherFontColor" name="WeatherFontColor" lay-search>
            <option value="1">随头部字体色</option>
            <option value="2">随标题字体色</option>
            </select>
            </div>
            <div class="layui-form-mid layui-word-aux">天气插件的字体颜色</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">插件Key</label>
            <div class="layui-input-inline" style="width: 50%;">
            <input type="url" id = "WeatherKey" name="WeatherKey" value = "<?php echo getconfig($config.'WeatherKey','');?>" placeholder="" autocomplete="off" class="layui-input" >
            </div>
            <div class="layui-form-mid layui-word-aux"><a target="_blank" style="color:#03a9f4!important;" href='https://widget.qweather.com/create-simple/'>申请Key</a></div>
        </div>

          
      </div>
      <!--搜索-->
      <div class="layui-tab-item">
        <div class="layui-form-item">
            <label class="layui-form-label">使用须知</label>
            <div class="layui-form-mid layui-word-aux" style="color: #d40909!important;">哦吼,还没开发到这.请关注更新</div>
        </div>
      </div>

  </div>
  <!-- Tab End -->
  <div class="layui-form-item">
    <div class="layui-input-block">
      <button class="layui-btn" lay-submit lay-filter="edit_homepage">保存</button>
    </div>
  </div>
  
</div></form>
<!-- 内容主题区域END -->
<script src = '<?php echo $libs?>/jquery/jquery-3.6.0.min.js'></script>
<script src = '<?php echo $libs?>/Layui/v2.6.8/layui.js'></script>
<script>
var u = '<?php echo $u?>';
var t = '<?php echo $theme;?>';
var s = '<?php echo $_GET['source'];?>';
$('#CardNum').val(document.getElementById('CardNum-input').value); //初始卡片数量
$('#night').val(document.getElementById('night-input').value); //初始卡片数量
$('#DescrRowNumber').val(document.getElementById('DescrRowNumber-input').value); //描述行数
$('#WeatherBackground').val(document.getElementById('WeatherBackground-input').value); 
$('#WeatherFontColor').val(document.getElementById('WeatherFontColor-input').value); 
$('#WeatherPosition').val(document.getElementById('WeatherPosition-input').value); 
$('#ClickLocation').val(document.getElementById('ClickLocation-input').value); 
layui.use(['form','colorpicker','element','dropdown'], function(){
    var form = layui.form;
    var colorpicker = layui.colorpicker;
    var dropdown = layui.dropdown;

form.on('select(PresetColor)', function(data){
    console.log(data.value);
    var  post='';
    if(data.value == '0'){
        // 默认配色
        post = {'HeadBackgroundColor':'#3f51b5','SidebarBackgroundColor':'','CardBackgroundColor':'#ffffff','OtherBackgroundColor':'#ffffff','HeadFontColor':'#ffffff','CategoryFontColor':'#212121','TitleFontColor':'#212121','DescrFontColor':'#9e9e9e'};
    }else if(data.value == '1'){
        // 黑底白字
        post = {'HeadBackgroundColor':'#000000','SidebarBackgroundColor':'','CardBackgroundColor':'#414141','OtherBackgroundColor':'#232323','HeadFontColor':'#f8f8f8','CategoryFontColor':'#f8f8f8','TitleFontColor':'#f8f8f8','DescrFontColor':'#d5d5d5'};
    }else if(data.value == '2'){
        // 黑底金色
        post = {'HeadBackgroundColor':'#000000','SidebarBackgroundColor':'','CardBackgroundColor':'#414141','OtherBackgroundColor':'#232323','HeadFontColor':'#f5eb8c','CategoryFontColor':'#f5eb8c','TitleFontColor':'#f5eb8c','DescrFontColor':'#f4eca9'};
    }else if(data.value == '3'){
        // 浅绿护眼
        post = {'HeadBackgroundColor':'#bbe6bf','SidebarBackgroundColor':'','CardBackgroundColor':'#E3EDCD','OtherBackgroundColor':'#C7EDCC','HeadFontColor':'#6f680c','CategoryFontColor':'#6f680c','TitleFontColor':'#6f680c','DescrFontColor':'#747038'};
    }else if(data.value == '4'){
        // 深绿护眼
        post = {'HeadBackgroundColor':'#6ba628','SidebarBackgroundColor':'','CardBackgroundColor':'#abc87a','OtherBackgroundColor':'#76a650','HeadFontColor':'#373535','CategoryFontColor':'#000000','TitleFontColor':'#000000','DescrFontColor':'#3b3b36'};
    }else if(data.value == '5'){
        // 粉色
    post = {'HeadBackgroundColor':'#efa9dd','SidebarBackgroundColor':'','CardBackgroundColor':'#e89bd4','OtherBackgroundColor':'#e4a6d3','HeadFontColor':'#6a6868','CategoryFontColor':'#6a6868','TitleFontColor':'#6a6868','DescrFontColor':'#5e5e56'};
    }

if (post !== ''){
    $.post('./index.php?c=admin&page=config&local=PresetColor&u='+u+'&Theme='+t,post,function(data,status){
      if(data.code == 0) {
          window.location.reload()
      }else{
          layer.msg(data.msg, {icon: 5});
      }
    });
}else{
    console.log("post = {"+
        "'HeadBackgroundColor':'"+
        document.getElementById('HeadBackgroundColor-input').value +
        "','CardBackgroundColor':'"+
        document.getElementById('CardBackgroundColor-input').value +
        "','OtherBackgroundColor':'"+
        document.getElementById('OtherBackgroundColor-input').value +
        "','SidebarBackgroundColor':'"+
        document.getElementById('SidebarBackgroundColor-input').value +
        "','HeadFontColor':'"+
        document.getElementById('HeadFontColor-input').value +
        "','CategoryFontColor':'"+
        document.getElementById('CategoryFontColor-input').value +
        "','TitleFontColor':'"+
        document.getElementById('TitleFontColor-input').value +
        "','DescrFontColor':'"+
        document.getElementById('DescrFontColor-input').value +"'};");
}
});

var BackgroundColor = [{title: '全透明',code: 'transparent'},{title: '白色透明',code: 'rgba(255,255,255,0.2)'},{title: '黑色透明',code: 'rgba(0,0,0,0.2)'}]
//背景色透明选项
dropdown.render({elem: '#HeadBackgroundColor-input',data:BackgroundColor ,click: function(obj){this.elem.val(obj.code);},style: 'width: 225px;'});
dropdown.render({elem: '#CardBackgroundColor-input',data:BackgroundColor ,click: function(obj){this.elem.val(obj.code);},style: 'width: 225px;'});
dropdown.render({elem: '#OtherBackgroundColor-input',data:BackgroundColor ,click: function(obj){this.elem.val(obj.code);},style: 'width: 225px;'});
dropdown.render({elem: '#SidebarBackgroundColor-input',data:BackgroundColor ,click: function(obj){this.elem.val(obj.code);},style: 'width: 225px;'});
//背景图下拉菜单 
  dropdown.render({
    elem: '#backgroundURL'
    ,data: [{
      title: '博天(自适应/动漫)'
      ,url: 'https://api.btstu.cn/sjbz/api.php?lx=dongman&method=zsy'
      ,author:'https://api.btstu.cn/doc/sjbz.php'
    },{
      title: '博天(自适应/妹子)'
      ,url: 'https://api.btstu.cn/sjbz/api.php?lx=meizi&method=zsy'
      ,author:'https://api.btstu.cn/doc/sjbz.php'
    },{
      title: '博天(自适应/风景)'
      ,url: 'https://api.btstu.cn/sjbz/api.php?lx=fengjing&method=zsy'
      ,author:'https://api.btstu.cn/doc/sjbz.php'
    },{
      title: '博天(自适应/随机)'
      ,url: 'https://api.btstu.cn/sjbz/api.php?lx=suiji&method=zsy'
      ,author:'https://api.btstu.cn/doc/sjbz.php'
    },{ 
      title: '姬长信(PC/每日必应)'
      ,url: 'https://api.isoyu.com/bing_images.php'
      ,author:'https://api.isoyu.com'
      ,n:'姬长信'
    },{
      title: '樱花(PC/动漫)'
      ,url: 'https://www.dmoe.cc/random.php'
      ,author:'https://www.dmoe.cc'
    },{
      title: '梁炯灿(PC/动漫)'
      ,url: 'https://tuapi.eees.cc/api.php?category=dongman&type=302'
      ,author:'https://tuapi.eees.cc'
    },{
      title: '梁炯灿(PC/风景)'
      ,url: 'https://tuapi.eees.cc/api.php?category=fengjing&type=302'
      ,author:'https://tuapi.eees.cc'
    },{
      title: '梁炯灿(PC/必应)'
      ,url: 'https://tuapi.eees.cc/api.php?category=biying&type=302'
      ,author:'https://tuapi.eees.cc'
    },{
      title: '梁炯灿(PC/美女)'
      ,url: 'https://tuapi.eees.cc/api.php?category=meinv&type=302'
      ,author:'https://tuapi.eees.cc'
    },{
      title: '苏晓晴(PC/动漫)'
      ,url: 'https://acg.toubiec.cn/random.php'
      ,author:'https://acg.toubiec.cn'
    },{
      title: '墨天逸(PC/动漫)'
      ,url: 'https://api.mtyqx.cn/api/random.php'
      ,author:'https://api.mtyqx.cn/'
    },{
      title: '小歪(PC/动漫)'
      ,url: 'https://api.ixiaowai.cn/api/api.php'
      ,author:'https://api.ixiaowai.cn'
    },{
      title: '小歪(PC/MC酱)'
      ,url: 'https://api.ixiaowai.cn/mcapi/mcapi.php'
      ,author:'https://api.ixiaowai.cn'
    },{
      title: '小歪(PC/风景)'
      ,url: 'https://api.ixiaowai.cn/gqapi/gqapi.php'
      ,author:'https://api.ixiaowai.cn' 
    },{
      title: '保罗(PC/动漫)'
      ,url: 'https://api.paugram.com/wallpaper/?source=sina'
      ,author:'https://api.paugram.com/help/wallpaper'
      ,n:'保罗'
    },{
      title: '樱道(PC/动漫)'
      ,url: 'https://api.r10086.com/img-api.php?type=动漫综合1'
      ,author:'https://img.r10086.com/'
      ,n:'樱道'
    }]
    ,click: function(obj){
        if (obj.n == '樱道'){
            layeropen('官方还有很多分类哦<br />感兴趣的自己去看<br />访问速度比较慢<br />友链有个镜像接口比较快','https://img.r10086.com/');
        }else if (obj.n == '保罗'){
            layeropen('官方还有其他接口<br />感兴趣的自己去看<br />有缓存','https://api.paugram.com/help/wallpaper');
        }else if (obj.n == '姬长信'){
            layeropen('官方还有其他接口<br />感兴趣的自己去看<br />慢且不稳','https://api.isoyu.com/#/壁纸模块');
        }
      this.elem.val(obj.url);
    }
    ,style: 'width: 235px;'
  });
  

function layeropen(content,url){
    layer.open({
        type: 1
        ,title: false //不显示标题栏
        ,closeBtn: false
        ,area: '300px;'
        ,shade: 0.8
        ,btn: ['查看详情', '取消']
        ,btnAlign: 'c'
        ,moveType: 1 //拖拽模式，0或者1
        ,content: '<div style="padding: 50px; line-height: 22px;  font-weight: 300;">'+content+'</div>'
        ,success: function(layero){
          var btn = layero.find('.layui-layer-btn');
          btn.find('.layui-layer-btn0').attr({
            href: url
            ,target: '_blank'
          });
        }
      });
}
// 预设颜色
colors = ['#ffc107','#ffeb3b','#cddc39','#ff9800','#3f51b5','#2196f3','#03a9f4','#ff5722','#f44336','#607d8b','#9e9e9e','#795548','#00bcd4','#673ab7','#9c27b0','#4caf50','#8bc34a','#e91e63','#009688','#000000','#ea81ce','#ffffff']

//头部背景色
  colorpicker.render({
    elem: '#HeadBackgroundColor'
    ,predefine: true
    ,color: '<?php echo getconfig($config.'HeadBackgroundColor');?>'
    ,colors: colors //自定义预定义颜色项
    ,format: 'rgb' //reg格式
    ,alpha: true  //开启透明滑块
    ,change: function(color){
      $('#HeadBackgroundColor-input').val(color);
    }
  });
//侧边栏背景色
  colorpicker.render({
    elem: '#SidebarBackgroundColor'
    ,predefine: true
    ,color: '<?php echo getconfig($config.'SidebarBackgroundColor');?>'
    ,colors: colors
    ,format: 'rgb'
    ,alpha: true 
    ,change: function(color){
      $('#SidebarBackgroundColor-input').val(color);
    }
  });
//卡片背景色
  colorpicker.render({
    elem: '#CardBackgroundColor'
    ,predefine: true
    ,colors: colors
    ,format: 'rgb'
    ,alpha: true 
    ,color: '<?php echo getconfig($config.'CardBackgroundColor');?>'
    ,change: function(color){
      $('#CardBackgroundColor-input').val(color);
    }
  });
//其他背景色
  colorpicker.render({
    elem: '#OtherBackgroundColor'
    ,predefine: true
    ,colors: colors
    ,format: 'rgb'
    ,alpha: true 
    ,color: '<?php echo getconfig($config.'OtherBackgroundColor');?>'
    ,change: function(color){
      $('#OtherBackgroundColor-input').val(color);
    }
  }); 
//头部字体色
  colorpicker.render({
    elem: '#HeadFontColor'
    ,predefine: true
    ,colors: colors
    ,color: '<?php echo getconfig($config.'HeadFontColor');?>'
    ,change: function(color){
      $('#HeadFontColor-input').val(color);
    }
  });   
//分类字体色
  colorpicker.render({
    elem: '#CategoryFontColor'
    ,predefine: true
    ,colors: colors
    ,color: '<?php echo getconfig($config.'CategoryFontColor');?>'
    ,change: function(color){
      $('#CategoryFontColor-input').val(color);
    }
  }); 
//标题字体色
  colorpicker.render({
    elem: '#TitleFontColor'
    ,predefine: true
    ,colors: colors
    ,color: '<?php echo getconfig($config.'TitleFontColor');?>'
    ,change: function(color){
      $('#TitleFontColor-input').val(color);
    }
  }); 
//描述字体色
  colorpicker.render({
    elem: '#DescrFontColor'
    ,predefine: true
    ,colors: colors
    ,color: '<?php echo getconfig($config.'DescrFontColor');?>'
    ,change: function(color){
      $('#DescrFontColor-input').val(color);
    }
  });   
//保存设置
form.on('submit(edit_homepage)', function(data){
    $.post('./index.php?c=admin&page=config&u='+u+'&Theme='+t,data.field,function(data,status){
      //如果添加成功
      if(data.code == 0) {
        if (s == 'admin'){
            layer.msg(data.msg, {icon: 1});
            return false;
        }else{
            parent.location.reload(); //刷新页面
        }
      }
      else{
        layer.msg(data.msg, {icon: 5});
      }
    });
    console.log(data.field);
    return false; 
});
});
</script>
</body>
</html>